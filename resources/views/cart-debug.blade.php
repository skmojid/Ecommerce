@extends('layouts.app')

@section('title', 'Cart Debug')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Cart Debug Information</h1>
    
    <!-- Authentication Status -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Authentication Status</h2>
        @guest
            <p class="text-red-600">✗ Not authenticated</p>
            <a href="{{ route('admin.login') }}" class="text-blue-600 hover:underline">Login here</a>
        @else
            <div class="space-y-2 text-green-600">
                <p>✓ Authenticated as: {{ Illuminate\Support\Facades\Auth::user()->email }}</p>
                <p>✓ User ID: {{ Illuminate\Support\Facades\Auth::id() }}</p>
                <p>✓ Role: {{ Illuminate\Support\Facades\Auth::user()->role }}</p>
                <p>✓ Session ID: {{ session()->getId() }}</p>
            </div>
        @endguest
    </div>

    <!-- Cart Test Form -->
    @guest
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-6">
            <p class="text-yellow-800">Please login to test cart functionality.</p>
        </div>
    @else
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">Add Product to Cart</h2>
            
            @if($product = \App\Models\Product::first())
                <form action="{{ route('cart.add') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    
                    <div class="border rounded-lg p-4 bg-gray-50">
                        <h3 class="font-medium">{{ $product->name }}</h3>
                        <p class="text-gray-600 text-sm">{{ $product->description }}</p>
                        <p class="text-xl font-bold text-green-600">${{ number_format($product->price, 2) }}</p>
                        <p class="text-sm text-gray-500">Stock: {{ $product->quantity }} available</p>
                    </div>
                    
                    <div>
                        <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity:</label>
                        <input type="number" 
                               id="quantity" 
                               name="quantity" 
                               min="1" 
                               max="99" 
                               value="1"
                               required
                               class="mt-1 w-32 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    
                    <button type="submit" 
                            class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Add to Cart
                    </button>
                </form>
            @else
                <p class="text-gray-500">No products available for testing.</p>
            @endif
        </div>

        <!-- Current Cart Status -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">Current Cart Status</h2>
            
<?php 
            $userId = Illuminate\Support\Facades\Auth::id();
            $cart = \App\Models\Cart::where('user_id', $userId)->with(['items.product', 'items.product.images'])->first();
            ?>
            
            @if($cart)
                <div class="space-y-2">
                    <p><strong>Cart ID:</strong> {{ $cart->id }}</p>
                    <p><strong>Items Count:</strong> {{ $cart->items->count() }}</p>
                    <p><strong>Subtotal:</strong> ${{ number_format($cart->subtotal, 2) }}</p>
                    <p><strong>Tax:</strong> ${{ number_format($cart->tax_amount, 2) }}</p>
                    <p><strong>Shipping:</strong> ${{ number_format($cart->shipping_amount, 2) }}</p>
                    <p><strong>Total:</strong> ${{ number_format($cart->total, 2) }}</p>
                </div>
                
                @if($cart->items->count() > 0)
                    <div class="mt-4">
                        <h4 class="font-medium mb-2">Cart Items:</h4>
                        @foreach($cart->items as $item)
                            <div class="border rounded p-3 mb-2 bg-gray-50">
                                <p><strong>{{ $item->product->name }}</strong></p>
                                <p>Quantity: {{ $item->quantity }} × ${{ number_format($item->unit_price, 2) }} = ${{ number_format($item->total_price, 2) }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 mt-4">Cart is empty</p>
                @endif
            @else
                <p class="text-gray-500">No cart found for this user.</p>
            @endif
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Quick Actions</h2>
            <div class="space-y-3">
                <a href="{{ route('cart.index') }}" class="block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-center">
                    View Full Cart
                </a>
                <form action="{{ route('cart.clear') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700"
                            onclick="return confirm('Are you sure you want to clear the cart?')">
                        Clear Cart
                    </button>
                </form>
            </div>
        </div>
    @endguest
</div>
@endsection