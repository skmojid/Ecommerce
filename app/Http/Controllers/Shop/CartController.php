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
    /**
     * Get current cart for user or guest
     */
    public function getCart()
    {
        if (Auth::check()) {
            // User cart - database stored with eager loading to prevent N+1
            $user = Auth::user();
            $existingCart = $user->cart()->first();
            $cart = $existingCart ?: $user->cart()->create();

            return $cart->load([
                'items.product',
                'items.product.category',
                'items.product.images',
            ]);
        } else {
            // Guest cart - session based
            $sessionId = session()->getId();

            return Cart::firstOrCreate(['session_id' => $sessionId]);
        }
    }

/**
     * Display shopping cart
     */
    public function index(Request $request)
    {
        $cart = $this->getCart();

        // Load cart with relationships
        $cart->load([
            'items.product',
            'items.product.category',
            'items.product.images',
        ]);

        // Update cart totals to ensure they're current
        $cart->updateTotals();

        return view('shop.cart.index', compact('cart'));
    }

    /**
     * Add product to cart
     */
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

            // Validate stock availability
            if (! $this->validateStockAvailability($product, $request->quantity)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient stock',
                    'available_quantity' => $product->quantity,
                    'requested_quantity' => $request->quantity,
                    'cart_total' => $this->getCartTotal(),
                ], 422);
            }

            // Check if item already exists in cart
            $existingItem = $cart->items()
                ->where('product_id', $product->id)
                ->first();

            if ($existingItem) {
                // Update existing item quantity
                $newQuantity = $existingItem->quantity + $request->quantity;

                // Validate stock for updated quantity
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
                // Create new cart item
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
                    // Update existing item
                    $cartItem->quantity += $request->quantity;
                    $cartItem->unit_price = $product->price;
                    $cartItem->total_price = $cartItem->unit_price * $cartItem->quantity;
                    $cartItem->save();
                    $message = 'Cart item updated successfully!';
                }
            }

            // Update cart totals
            $cart->updateTotals();

            DB::commit();

            // Clear product cache after stock changes
            Cache::forget("product.{$request->product_id}");

            // Return JSON response for AJAX requests
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

    /**
     * Update cart item quantity
     */
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

            // Find cart item by product_id or item_id
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

            // If quantity is 0, remove the item
            if ($request->quantity == 0) {
                $cartItem->delete();
                $message = 'Item removed from cart!';
            } else {
                $product = $this->getCachedProduct($cartItem->product_id);

                // Validate stock availability
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

            // Update cart totals
            $cart->updateTotals();

            DB::commit();

            // Clear product cache after stock changes
            Cache::forget("product.{$cartItem->product_id}");

            // Return JSON response for AJAX requests
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

    /**
     * Remove item from cart
     */
    public function remove(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:cart_items,id',
        ]);

        try {
            DB::beginTransaction();

            $cart = $this->getCart();
            $cartItem = CartItem::findOrFail($request->item_id);

            // Verify item belongs to current cart
            if ($cartItem->cart_id !== $cart->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid cart item.',
                ], 403);
            }

            $productName = $cartItem->product->name;
            $cartItem->delete();

            // Update cart totals
            $cart->updateTotals();

            DB::commit();

            $message = "{$productName} removed from cart!";

            // Return JSON response for AJAX requests
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

    /**
     * Clear cart
     */
    public function clear(Request $request)
    {
        try {
            DB::beginTransaction();

            $cart = $this->getCart();

            // Delete all cart items
            $cart->items()->delete();

            // Reset cart totals
            $cart->update([
                'subtotal' => 0,
                'tax_amount' => 0,
                'shipping_amount' => 0,
                'discount_amount' => 0,
                'total' => 0,
            ]);

            DB::commit();

            // Return JSON response for AJAX requests
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

    /**
     * Get cart totals for AJAX requests
     */
    public function totals()
    {
        $cart = $this->getCart();
        $cart->calculateTotals();
        
        // Load items with their data
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

    /**
     * Get cached product to improve performance
     */
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

    /**
     * Validate stock availability
     */
    private function validateStockAvailability(Product $product, $requestedQuantity): bool
    {
        if (! $product->track_quantity) {
            return true; // Product doesn't track inventory
        }

        return $product->quantity >= $requestedQuantity;
    }

    /**
     * Get cart total for response (temporary method)
     */
    private function getCartTotal()
    {
        $cart = $this->getCart();

        return $cart->subtotal ?? 0;
    }
}
