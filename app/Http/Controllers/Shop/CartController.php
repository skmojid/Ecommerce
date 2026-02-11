<?php
namespace App\Http\Controllers\Shop;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
class CartController extends Controller
{
    public function getCart()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $existingCart = $user->cart()->first();
            $cart = $existingCart ?: $user->cart()->create();
            $this->cleanupOrphanedItems($cart);
            return $cart->load([
                'items.product',
                'items.product.category',
                'items.product.images',
            ]);
        } else {
            $sessionId = session()->getId();
            $cart = Cart::firstOrCreate(['session_id' => $sessionId]);
            $this->cleanupOrphanedItems($cart);
            return $cart;
        }
    }
    public function index(Request $request)
    {
        $cart = $this->getCart();
        $cart->load([
            'items.product',
            'items.product.category',
            'items.product.images',
        ]);
        $cart->updateTotals();
        return view('shop.cart.index', compact('cart'));
    }
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1|max:99',
            'variation_id' => 'nullable|integer',
        ], [
            'quantity.required' => 'Please enter a quantity.',
            'quantity.min' => 'Quantity must be at least 1.',
            'quantity.max' => 'Quantity cannot exceed 99 items.',
        ]);
        try {
            DB::beginTransaction();
            $cart = $this->getCart();
            $product = $this->getCachedProduct($request->product_id);
            if (! $this->validateStockAvailability($product, $request->quantity)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient stock',
                    'available_quantity' => $product->quantity,
                    'requested_quantity' => $request->quantity,
                    'cart_total' => $this->getCartTotal(),
                ], 422);
            }
            $existingItem = $cart->items()
                ->where('product_id', $product->id)
                ->first();
            if ($existingItem) {
                $newQuantity = $existingItem->quantity + $request->quantity;
                if (! $this->validateStockAvailability($product, $newQuantity)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Insufficient stock for requested quantity',
                        'available_quantity' => $product->quantity,
                        'requested_quantity' => $newQuantity,
                        'cart_total' => $this->getCartTotal(),
                    ], 422);
                }
                $existingItem->quantity = $newQuantity;
                $existingItem->unit_price = $product->price;
                $existingItem->total_price = $existingItem->unit_price * $newQuantity;
                $existingItem->save();
                $message = 'Cart item updated successfully!';
                $cart->updateTotals();
            } else {
                $cartItem = CartItem::firstOrCreate([
                    'cart_id' => $cart->id,
                    'product_id' => $product->id,
                ], [
                    'quantity' => $request->quantity,
                    'unit_price' => $product->price,
                    'total_price' => $product->price * $request->quantity,
                ]);
                if ($cartItem->wasRecentlyCreated) {
                    $message = 'Product added to cart successfully!';
                } else {
                    $cartItem->quantity += $request->quantity;
                    $cartItem->unit_price = $product->price;
                    $cartItem->total_price = $cartItem->unit_price * $cartItem->quantity;
                    $cartItem->save();
                    $message = 'Cart item updated successfully!';
                }
            }
            $cart->updateTotals();
            DB::commit();
            Cache::forget("product.{$request->product_id}");
            if ($request->expectsJson()) {
                $latestItem = $cart->items()->latest()->first();
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'cart_total' => $cart->total,
                    'cart_items_count' => $cart->items->count(),
                    'item' => [
                        'id' => $latestItem->id,
                        'product_name' => $product->name,
                        'quantity' => $request->quantity,
                        'subtotal' => $latestItem->total_price,
                        'image' => $latestItem->product->images()->where('is_primary', true)->first()?->image_path ?? $latestItem->product->images()->first()?->image_path,
                    ],
                ]);
            }
            return back()->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error adding product to cart: '.$e->getMessage(),
                ], 500);
            }
            return back()->with('error', 'Error adding product to cart. Please try again.');
        }
    }
    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:0|max:99',
            'variation_id' => 'nullable|exists:product_variations,id',
            'item_id' => 'nullable|exists:cart_items,id',
        ], [
            'quantity.required' => 'Please enter a quantity.',
            'quantity.min' => 'Quantity must be at least 0.',
            'quantity.max' => 'Quantity cannot exceed 99 items.',
        ]);
        try {
            DB::beginTransaction();
            $cart = $this->getCart();
            $cartItem = null;
            if ($request->item_id) {
                $cartItem = $cart->items()->where('id', $request->item_id)->first();
            } elseif ($request->product_id) {
                $cartItem = $cart->items()->where('product_id', $request->product_id)->first();
            }
            if (!$cartItem) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cart item not found.',
                ], 404);
            }
            if ($request->quantity == 0) {
                $cartItem->delete();
                $message = 'Item removed from cart!';
            } else {
                $product = $this->getCachedProduct($cartItem->product_id);
                if (! $this->validateStockAvailability($product, $request->quantity)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Insufficient stock',
                        'available_quantity' => $product->quantity,
                        'requested_quantity' => $request->quantity,
                    ], 422);
                }
                $cartItem->quantity = $request->quantity;
                $cartItem->unit_price = $product->price;
                $cartItem->total_price = $cartItem->unit_price * $request->quantity;
                $cartItem->save();
                $message = 'Cart item updated successfully!';
            }
            $cart->updateTotals();
            DB::commit();
            Cache::forget("product.{$cartItem->product_id}");
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'cart_total' => $cart->total,
                    'cart_items_count' => $cart->items->count(),
                ]);
            }
            return back()->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error updating cart item: '.$e->getMessage(),
                ], 500);
            }
            return back()->with('error', 'Error updating cart item. Please try again.');
        }
    }
    public function remove(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:cart_items,id',
        ]);
        try {
            DB::beginTransaction();
            $cart = $this->getCart();
            $cartItem = CartItem::findOrFail($request->item_id);
            if ($cartItem->cart_id !== $cart->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid cart item.',
                ], 403);
            }
            $productName = $cartItem->product->name;
            $cartItem->delete();
            $cart->updateTotals();
            DB::commit();
            $message = "{$productName} removed from cart!";
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'cart_total' => $cart->total,
                    'cart_items_count' => $cart->items->count(),
                ]);
            }
            return back()->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error removing item from cart: '.$e->getMessage(),
                ], 500);
            }
            return back()->with('error', 'Error removing item from cart. Please try again.');
        }
    }
    public function clear(Request $request)
    {
        try {
            DB::beginTransaction();
            $cart = $this->getCart();
            $cart->items()->delete();
            $cart->update([
                'subtotal' => 0,
                'tax_amount' => 0,
                'shipping_amount' => 0,
                'discount_amount' => 0,
                'total' => 0,
            ]);
            DB::commit();
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cart cleared successfully',
                    'cart_total' => 0,
                    'cart_items_count' => 0,
                ]);
            }
            return back()->with('success', 'Cart cleared successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error clearing cart. Please try again.');
        }
    }
    public function totals()
    {
        $cart = $this->getCart();
        $cart->calculateTotals();
        $cart->load('items');
        $items = [];
        foreach ($cart->items as $item) {
            $items[$item->id] = [
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'total_price' => $item->total_price,
            ];
        }
        return response()->json([
            'subtotal' => $cart->subtotal,
            'tax_amount' => $cart->tax_amount,
            'shipping_amount' => $cart->shipping_amount,
            'discount_amount' => $cart->discount_amount,
            'total' => $cart->total,
            'items_count' => $cart->items->count(),
            'items' => $items,
        ]);
    }
    private function getCachedProduct($productId)
    {
        return Cache::remember(
            "product.{$productId}",
            300,
            function () use ($productId) {
                return Product::with(['category', 'primaryImage'])
                    ->findOrFail($productId);
            }
        );
    }
    private function validateStockAvailability(Product $product, $requestedQuantity): bool
    {
        if (! $product->track_quantity) {
            return true; // Product doesn't track inventory
        }
        return $product->quantity >= $requestedQuantity;
    }
    private function getCartTotal()
    {
        $cart = $this->getCart();
        return $cart->subtotal ?? 0;
    }
    private function cleanupOrphanedItems(Cart $cart): void
    {
        $orphanedItems = $cart->items()->whereDoesntHave('product')->get();
        foreach ($orphanedItems as $item) {
            $item->delete();
        }
    }
}