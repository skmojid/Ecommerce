@extends('layouts.app')

@section('title', 'Cart Test')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Cart Functionality Test</h1>
    
    <!-- Product Info -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Available Product</h2>
        @if($product = \App\Models\Product::first())
            <div class="border rounded-lg p-4">
                <h3 class="text-lg font-medium">{{ $product->name }}</h3>
                <p class="text-gray-600">{{ $product->description }}</p>
                <p class="text-2xl font-bold text-green-600">${{ number_format($product->price, 2) }}</p>
                <p class="text-sm text-gray-500">Stock: {{ $product->quantity }} available</p>
                
                <!-- Add to Cart Form -->
                <form action="{{ route('cart.add') }}" method="POST" class="mt-4">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    
                    <div class="flex items-center space-x-4">
                        <div>
                            <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Quantity:</label>
                            <input type="number" 
                                   id="quantity" 
                                   name="quantity" 
                                   min="1" 
                                   max="99" 
                                   value="1"
                                   class="w-24 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        
                        <button type="submit" 
                                class="mt-6 bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            Add to Cart
                        </button>
                    </div>
                </form>
            </div>
        @else
            <p class="text-gray-500">No products available for testing.</p>
        @endif
    </div>

    <!-- Cart Status -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Current Cart Status</h2>
        @auth
            <?php 
            $cart = \App\Models\Cart::where('user_id', Auth::id())->with(['items.product', 'items.product.primaryImage'])->first();
            if (!$cart) {
                $cart = new \stdClass();
                $cart->id = null;
                $cart->items = collect();
                $cart->subtotal = 0;
                $cart->total = 0;
            }
            ?>
            <div class="space-y-2">
                <p><strong>User:</strong> {{ Auth::user()->email }}</p>
                <p><strong>Cart ID:</strong> {{ $cart->id }}</p>
                <p><strong>Items:</strong> {{ $cart->items->count() }}</p>
                <p><strong>Subtotal:</strong> ${{ number_format($cart->subtotal ?? 0, 2) }}</p>
                <p><strong>Total:</strong> ${{ number_format($cart->total ?? 0, 2) }}</p>
            </div>
            
            @if($cart->items->count() > 0)
                <div class="mt-4">
                    <h4 class="font-medium mb-2">Cart Items:</h4>
                    @foreach($cart->items as $item)
                        <div class="border rounded p-3 mb-2">
                            <p><strong>{{ $item->product->name }}</strong></p>
                            <p>Quantity: {{ $item->quantity }} × ${{ number_format($item->unit_price, 2) }} = ${{ number_format($item->total_price, 2) }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        @else
            <p class="text-gray-500">Please login to view cart details.</p>
        @endauth
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold mb-4">Quick Actions</h2>
        <div class="space-y-3">
            @auth
                <a href="{{ route('cart.index') }}" class="block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-center">
                    View Full Cart
                </a>
                <form action="{{ route('cart.clear') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                        Clear Cart
                    </button>
                </form>
            @else
                <a href="{{ route('admin.login') }}" class="block bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 text-center">
                    Login to Test Cart
                </a>
            @endauth
        </div>
    </div>
</div>
@endsection