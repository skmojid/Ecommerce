@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<!-- Cart Header -->
<section class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Shopping Cart</h1>
            <p class="text-xl text-indigo-100" data-items-count>
                {{ $cart->items->count() }} {{ Str::plural('item', $cart->items->count()) }} in your cart
            </p>
        </div>
    </div>
</section>

<!-- Cart Content -->
<section class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($cart->items->count() > 0)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Cart Items -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="p-6 border-b border-gray-200">
                            <h2 class="text-xl font-semibold text-gray-900">Cart Items</h2>
                        </div>
                        
                        <div class="divide-y divide-gray-200">
                            @foreach($cart->items as $item)
                                @if(!$item->product)
                                    <!-- Skip items with missing products -->
                                    @continue
                                @endif
                                <div class="p-6" data-item-id="{{ $item->id }}">
                                    <div class="flex items-center space-x-4">
                                        <!-- Product Image -->
                                        <div class="flex-shrink-0">
                                            @if($item->product && $item->product->primary_image)
                                                <img src="{{ $item->product->primary_image->url }}" 
                                                     alt="{{ $item->product->name }}" 
                                                     class="w-20 h-20 object-cover rounded-lg">
                                            @else
                                                <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center">
                                                    <i class="fas fa-image text-gray-400"></i>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <!-- Product Details -->
                                        <div class="flex-1">
                                            <h3 class="text-lg font-medium text-gray-900">
                                                @if($item->product)
                                                    <a href="{{ route('shop.show', $item->product) }}" class="hover:text-indigo-600">
                                                        {{ $item->product->name }}
                                                    </a>
                                                @else
                                                    <span>Product Not Found</span>
                                                @endif
                                            </h3>
                                            <p class="text-sm text-gray-500">
                                                {{ $item->product->category->name ?? ($item->product ? 'Uncategorized' : 'Product Not Available') }}
                                            </p>
                                            @if($item->variation_id)
                                                <p class="text-sm text-indigo-600">Variant: {{ $item->variation->name ?? 'N/A' }}</p>
                                            @endif
                                            <p class="text-lg font-semibold text-gray-900 mt-1">
                                                ${{ number_format($item->unit_price, 2) }}
                                            </p>
                                        </div>
                                        
                                        <!-- Quantity Controls -->
                                        <div class="flex items-center space-x-2">
                                            <div class="flex items-center space-x-2">
                                                <input type="hidden" name="product_id_{{ $item->id }}" value="{{ $item->product_id }}">
                                                
                                                <button type="button" onclick="updateCartItem({{ $item->id }}, {{ $item->quantity - 1 }})"
                                                        class="w-8 h-8 rounded-full bg-gray-200 hover:bg-gray-300 text-gray-600 flex items-center justify-center"
                                                        @if($item->quantity <= 1) disabled @endif>
                                                    <i class="fas fa-minus text-xs"></i>
                                                </button>
                                                
                                                <input type="number" id="quantity-{{ $item->id }}" 
                                                       value="{{ $item->quantity }}" 
                                                       min="1" max="99" 
                                                       onchange="updateCartItem({{ $item->id }}, this.value)"
                                                       class="w-16 text-center border border-gray-300 rounded-md px-2 py-1">
                                                
                                                <button type="button" onclick="updateCartItem({{ $item->id }}, {{ $item->quantity + 1 }})"
                                                        class="w-8 h-8 rounded-full bg-gray-200 hover:bg-gray-300 text-gray-600 flex items-center justify-center">
                                                    <i class="fas fa-plus text-xs"></i>
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <!-- Item Total & Remove -->
                                        <div class="text-right">
                                            <p class="text-lg font-semibold text-gray-900" data-item-price="{{ $item->id }}">
                                                ${{ number_format($item->total_price, 2) }}
                                            </p>
                                            <button type="button" onclick="removeCartItem({{ $item->id }})" class="text-red-600 hover:text-red-800 text-sm">
                                                    <i class="fas fa-trash mr-1"></i>
                                                    Remove
                                                </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <!-- Cart Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-md overflow-hidden sticky top-4">
                        <div class="p-6 border-b border-gray-200">
                            <h2 class="text-xl font-semibold text-gray-900">Order Summary</h2>
                        </div>
                        
                        <div class="p-6 space-y-4">
                            <div class="flex justify-between text-gray-600">
                                <span>Subtotal (<span data-items-count>{{ $cart->items->count() }}</span> items)</span>
                                <span data-subtotal>${{ number_format($cart->subtotal ?? 0, 2) }}</span>
                            </div>
                            
                            <div class="flex justify-between text-gray-600">
                                <span>Shipping</span>
                                <span data-shipping>${{ number_format($cart->shipping_amount ?? 0, 2) }}</span>
                            </div>
                            
                            <div class="flex justify-between text-gray-600">
                                <span>Tax</span>
                                <span data-tax>${{ number_format($cart->tax_amount ?? 0, 2) }}</span>
                            </div>
                            
                            @if($cart->discount_amount > 0)
                                <div class="flex justify-between text-green-600">
                                    <span>Discount</span>
                                    <span>-${{ number_format($cart->discount_amount, 2) }}</span>
                                </div>
                            @endif
                            
                            <div class="border-t border-gray-200 pt-4">
                                <div class="flex justify-between text-xl font-semibold text-gray-900">
                                    <span>Total</span>
                                    <span data-total>${{ number_format($cart->total ?? 0, 2) }}</span>
                                </div>
                            </div>
                            
                            <!-- Coupon Code -->
                            <!-- Coupon functionality coming soon -->
                            <div class="mt-4 p-4 bg-gray-50 border border-gray-200 rounded-md">
                                <div class="flex items-center text-gray-600">
                                    <i class="fas fa-tag mr-2"></i>
                                    <span class="text-sm">Coupon codes coming soon!</span>
                                </div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="space-y-3 mt-6">
                                <a href="{{ route('shop.index') }}" 
                                   class="w-full block text-center px-6 py-3 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                                    Continue Shopping
                                </a>
                                
                                @if(Auth::check())
                                    <a href="{{ route('customer.checkout.index') }}" 
                                       class="w-full block text-center px-6 py-3 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                        Proceed to Checkout
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" 
                                       class="w-full block text-center px-6 py-3 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                        Login to Checkout
                                    </a>
                                @endif
                            </div>
                            
                            <!-- Clear Cart -->
                            @if($cart->items->count() > 0)
                                <form action="{{ route('cart.clear') }}" method="POST" class="mt-4">
                                    @csrf
                                    <button type="submit" class="w-full px-4 py-2 text-red-600 border border-red-300 rounded-md hover:bg-red-50"
                                            onclick="return confirm('Are you sure you want to clear your cart?')">
                                        <i class="fas fa-trash mr-2"></i>
                                        Clear Cart
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Empty Cart -->
            <div class="text-center py-16">
                <div class="mb-8">
                    <i class="fas fa-shopping-cart text-6xl text-gray-300"></i>
                </div>
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">Your cart is empty</h2>
                <p class="text-gray-600 mb-8">Looks like you haven't added any products to your cart yet.</p>
                <a href="{{ route('shop.index') }}" 
                   class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Continue Shopping
                </a>
            </div>
        @endif
    </div>
</section>

<!-- Cart Update Feedback -->
@if(session('success'))
    <div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
        <i class="fas fa-check-circle mr-2"></i>
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
        <i class="fas fa-exclamation-circle mr-2"></i>
        {{ session('error') }}
    </div>
@endif
@endsection

@push('scripts')
<script>
// Update cart item quantity
function updateCartItem(itemId, newQuantity) {
    if (newQuantity < 1 || newQuantity > 99) return;
    
    // Show loading state
    const quantityInput = document.getElementById(`quantity-${itemId}`);
    if (quantityInput) {
        quantityInput.disabled = true;
    }
    
    fetch('{{ route("cart.update") }}', {
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            item_id: itemId,
            quantity: newQuantity
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update item display
            if (quantityInput) {
                quantityInput.value = newQuantity;
                quantityInput.disabled = false;
            }
            
            // Update item total price
            const itemRow = quantityInput.closest('tr') || quantityInput.closest('div[class*="p-6"]');
            if (itemRow) {
                const priceElement = itemRow.querySelector('[data-item-price]');
                if (priceElement) {
                    // Fetch current item price from server
                    fetchItemDetails(itemId, newQuantity);
                }
            }
            
            // Update cart totals
            updateCartTotals();
            
            // Show success message
            showNotification('Cart updated successfully!', 'success');
        } else {
            alert('Error: ' + data.message);
            if (quantityInput) quantityInput.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error updating cart');
        if (quantityInput) quantityInput.disabled = false;
    });
}

// Remove cart item
function removeCartItem(itemId) {
    if (!confirm('Are you sure you want to remove this item?')) return;
    
    fetch('{{ route("cart.remove") }}', {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            item_id: itemId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remove item from DOM
            const itemRow = document.querySelector(`[data-item-id="${itemId}"]`);
            if (itemRow) {
                itemRow.remove();
            }
            
            // Update cart totals
            updateCartTotals();
            
            // Update items count
            const itemsCountElements = document.querySelectorAll('[data-items-count]');
            itemsCountElements.forEach(el => {
                const currentCount = parseInt(el.textContent) || 0;
                el.textContent = Math.max(0, currentCount - 1);
            });
            
            // Show success message
            showNotification('Item removed from cart!', 'success');
            
            // If cart is empty, reload page to show empty state
            setTimeout(() => {
                if (document.querySelectorAll('[data-item-id]').length === 0) {
                    location.reload();
                }
            }, 1500);
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error removing item');
    });
}

// Update cart totals
function updateCartTotals() {
    fetch('{{ route("cart.totals") }}')
        .then(response => response.json())
        .then(data => {
            // Update all total displays
            document.querySelectorAll('[data-subtotal]').forEach(el => {
                el.textContent = '$' + parseFloat(data.subtotal).toFixed(2);
            });
            document.querySelectorAll('[data-tax]').forEach(el => {
                el.textContent = '$' + parseFloat(data.tax_amount).toFixed(2);
            });
            document.querySelectorAll('[data-shipping]').forEach(el => {
                el.textContent = '$' + parseFloat(data.shipping_amount).toFixed(2);
            });
            document.querySelectorAll('[data-total]').forEach(el => {
                el.textContent = '$' + parseFloat(data.total).toFixed(2);
            });
            
            // Update items count in header
            document.querySelectorAll('[data-items-count]').forEach(el => {
                el.textContent = data.items_count;
            });
        })
        .catch(error => {
            console.error('Error updating totals:', error);
        });
}

// Fetch item details for price updates
function fetchItemDetails(itemId, quantity) {
    fetch('{{ route("cart.totals") }}')
        .then(response => response.json())
        .then(data => {
            const itemRow = document.querySelector(`[data-item-id="${itemId}"]`);
            if (itemRow && data.items) {
                const priceElement = itemRow.querySelector('[data-item-price]');
                if (priceElement && data.items[itemId]) {
                    const itemData = data.items[itemId];
                    priceElement.textContent = '$' + parseFloat(itemData.total_price).toFixed(2);
                }
            }
        });
}

// Show notification
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `fixed bottom-4 right-4 ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} text-white px-6 py-3 rounded-lg shadow-lg z-50`;
    notification.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} mr-2"></i>
        ${message}
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Add data attributes to cart items for easier manipulation
    document.querySelectorAll('[data-item-id]').forEach(item => {
        // Initialize any needed event listeners
    });
});
</script>
@endpush