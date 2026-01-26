<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CheckoutController extends Controller
{
    /**
     * Display the checkout page
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Get current cart
        $cart = $this->getUserCart();

        if ($cart->items->count() === 0) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty. Add some products before checkout.');
        }

        // Calculate totals
        $cart->calculateTotals();

        // Get user's saved addresses if any
        $addresses = $user->addresses ?? collect();

        return view('shop.checkout.index', compact('cart', 'user', 'addresses'));
    }

    /**
     * Process checkout
     */
    public function process(Request $request)
    {
        $user = Auth::user();

        // Get current cart
        $cart = $this->getUserCart();

        if ($cart->items->count() === 0) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty. Add some products before checkout.');
        }

        // Validate checkout data
        $validator = Validator::make($request->all(), [
            'shipping_address' => 'required|array',
            'shipping_address.first_name' => 'required|string|max:255',
            'shipping_address.last_name' => 'required|string|max:255',
            'shipping_address.address' => 'required|string|max:500',
            'shipping_address.city' => 'required|string|max:255',
            'shipping_address.state' => 'required|string|max:255',
            'shipping_address.postal_code' => 'required|string|max:20',
            'shipping_address.country' => 'required|string|max:255',
            'shipping_address.phone' => 'required|string|max:20',
            'billing_address' => 'nullable|array',
            'payment_method' => 'required|in:stripe,paypal,cod',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $validated = $validator->validated();

        // Use billing address if provided, otherwise use shipping address
        $billingAddress = $validated['billing_address'] ?? $validated['shipping_address'];

        try {
            DB::beginTransaction();

            // Create order
            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => $this->generateOrderNumber(),
                'status' => 'pending',
                'payment_status' => 'pending',
                'payment_method' => $validated['payment_method'],
                'currency' => 'USD',
                'subtotal' => $cart->subtotal,
                'tax_amount' => $cart->tax_amount,
                'shipping_amount' => $cart->shipping_amount,
                'discount_amount' => $cart->discount_amount,
                'total' => $cart->total,
                'notes' => $validated['notes'] ?? null,
                'shipping_address' => $validated['shipping_address'],
                'billing_address' => $billingAddress,
            ]);

            // Create order items
            foreach ($cart->items as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'product_name' => $cartItem->product->name,
                    'product_sku' => $cartItem->product->sku,
                    'quantity' => $cartItem->quantity,
                    'unit_price' => $cartItem->unit_price,
                    'total_price' => $cartItem->total_price,
                ]);

                // Update product stock
                if ($cartItem->product->track_quantity) {
                    $cartItem->product->decrement('quantity', $cartItem->quantity);
                }
            }

            // Clear cart
            $cart->items()->delete();
            $cart->update([
                'subtotal' => 0,
                'tax_amount' => 0,
                'shipping_amount' => 0,
                'discount_amount' => 0,
                'total' => 0,
                'coupon_code' => null,
            ]);

            DB::commit();

            // Redirect to success page with order ID
            return redirect()->route('customer.checkout.success', ['order' => $order->id])
                ->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'There was an error processing your order. Please try again.')
                ->withInput();
        }
    }

    /**
     * Display order success page
     */
    public function success(Request $request)
    {
        $orderId = $request->get('order') ?? session('order_id');

        if (! $orderId) {
            return redirect()->route('shop.index')
                ->with('error', 'Order information not found.');
        }

        $order = Order::with(['items.product', 'user'])
            ->where('id', $orderId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Store order ID in session in case of refresh
        session(['order_id' => $orderId]);

        return view('shop.checkout.success', compact('order'));
    }

    /**
     * Get user's current cart
     */
    private function getUserCart(): Cart
    {
        $user = Auth::user();

        $cart = $user->cart()->first() ?? $user->cart()->create();

        return $cart->load([
            'items.product',
            'items.product.category',
            'items.product.primaryImage',
        ]);
    }

    /**
     * Generate unique order number
     */
    private function generateOrderNumber(): string
    {
        $prefix = 'ORD';
        $timestamp = now()->format('Ymd');
        $random = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);

        return $prefix.$timestamp.$random;
    }
}
