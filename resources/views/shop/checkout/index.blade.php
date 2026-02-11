@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<!-- Checkout Header -->
<section class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Checkout</h1>
            <p class="text-xl text-indigo-100">Complete your order</p>
        </div>
    </div>
</section>

<!-- Checkout Content -->
<section class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <form action="{{ route('customer.checkout.process') }}" method="POST" id="checkoutForm">
            @csrf
            
            <!-- Order Summary & Progress -->
            <div class="mb-8">
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
                    <!-- Progress Steps -->
                    <div class="lg:col-span-3">
                        <div class="flex items-center justify-center space-x-4 sm:space-x-8">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-indigo-600 text-white rounded-full flex items-center justify-center font-semibold">
                                    1
                                </div>
                                <span class="ml-2 text-sm font-medium">Information</span>
                            </div>
                            <div class="flex-1 h-1 bg-gray-300 max-w-20"></div>
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center font-semibold">
                                    2
                                </div>
                                <span class="ml-2 text-sm text-gray-500">Shipping</span>
                            </div>
                            <div class="flex-1 h-1 bg-gray-300 max-w-20"></div>
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center font-semibold">
                                    3
                                </div>
                                <span class="ml-2 text-sm text-gray-500">Payment</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Checkout Form -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Contact Information -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6">Contact Information</h2>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                    Email Address
                                </label>
                                <input type="email" id="email" name="email" value="{{ $user->email }}" readonly
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-600">
                            </div>
                            <div>
                                <label for="shipping_address_phone" class="block text-sm font-medium text-gray-700 mb-2">
                                    Phone Number *
                                </label>
                                <input type="tel" id="shipping_address_phone" name="shipping_address[phone]" required
                                       value="{{ old('shipping_address.phone') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                                @error('shipping_address.phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Address -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6">Shipping Address</h2>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label for="shipping_address_first_name" class="block text-sm font-medium text-gray-700 mb-2">
                                    First Name *
                                </label>
                                <input type="text" id="shipping_address_first_name" name="shipping_address[first_name]" required
                                       value="{{ old('shipping_address.first_name', $user->name) }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                                @error('shipping_address.first_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="shipping_address_last_name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Last Name *
                                </label>
                                <input type="text" id="shipping_address_last_name" name="shipping_address[last_name]" required
                                       value="{{ old('shipping_address.last_name') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                                @error('shipping_address.last_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mt-6">
                            <label for="shipping_address_address" class="block text-sm font-medium text-gray-700 mb-2">
                                Street Address *
                            </label>
                            <input type="text" id="shipping_address_address" name="shipping_address[address]" required
                                   value="{{ old('shipping_address.address', $user->address) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                            @error('shipping_address.address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mt-6">
                            <div>
                                <label for="shipping_address_city" class="block text-sm font-medium text-gray-700 mb-2">
                                    City *
                                </label>
                                <input type="text" id="shipping_address_city" name="shipping_address[city]" required
                                       value="{{ old('shipping_address.city') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                                @error('shipping_address.city')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="shipping_address_state" class="block text-sm font-medium text-gray-700 mb-2">
                                    State *
                                </label>
                                <input type="text" id="shipping_address_state" name="shipping_address[state]" required
                                       value="{{ old('shipping_address.state') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                                @error('shipping_address.state')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="shipping_address_postal_code" class="block text-sm font-medium text-gray-700 mb-2">
                                    Pin Code *
                                </label>
                                <input type="text" id="shipping_address_postal_code" name="shipping_address[postal_code]" required
                                       value="{{ old('shipping_address.postal_code') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                                @error('shipping_address.postal_code')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mt-6">
                            <label for="shipping_address_country" class="block text-sm font-medium text-gray-700 mb-2">
                                Country *
                            </label>
                            <select id="shipping_address_country" name="shipping_address[country]" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Select Country</option>
                                 <option value="India" {{ old('shipping_address.country') == 'India' ? 'selected' : '' }}>
                                    India
                                </option>
                                <option value="United States" {{ old('shipping_address.country') == 'United States' ? 'selected' : '' }}>
                                    United States
                                </option>
                                <option value="Canada" {{ old('shipping_address.country') == 'Canada' ? 'selected' : '' }}>
                                    Canada
                                </option>
                                <option value="United Kingdom" {{ old('shipping_address.country') == 'United Kingdom' ? 'selected' : '' }}>
                                    United Kingdom
                                </option>
                            </select>
                            @error('shipping_address.country')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6">Payment Method</h2>
                        
                        <div class="space-y-4">
                            <label class="flex items-center p-4 border border-gray-300 rounded-md cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="payment_method" value="stripe" required
                                       {{ old('payment_method', 'stripe') == 'stripe' ? 'checked' : '' }}
                                       class="mr-3">
                                <i class="fab fa-stripe text-blue-600 text-2xl mr-3"></i>
                                <div>
                                    <p class="font-medium">Credit Card (Stripe)</p>
                                    <p class="text-sm text-gray-500">Pay securely with your credit card</p>
                                </div>
                            </label>
                            
                            <label class="flex items-center p-4 border border-gray-300 rounded-md cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="payment_method" value="paypal" required
                                       {{ old('payment_method') == 'paypal' ? 'checked' : '' }}
                                       class="mr-3">
                                <i class="fab fa-paypal text-blue-500 text-2xl mr-3"></i>
                                <div>
                                    <p class="font-medium">PayPal</p>
                                    <p class="text-sm text-gray-500">Fast and secure payment</p>
                                </div>
                            </label>
                            
                            <label class="flex items-center p-4 border border-gray-300 rounded-md cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="payment_method" value="cod" required
                                       {{ old('payment_method') == 'cod' ? 'checked' : '' }}
                                       class="mr-3">
                                <i class="fas fa-money-bill-wave text-green-600 text-2xl mr-3"></i>
                                <div>
                                    <p class="font-medium">Cash on Delivery</p>
                                    <p class="text-sm text-gray-500">Pay when you receive your order</p>
                                </div>
                            </label>
                        </div>
                        
                        @error('payment_method')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Order Notes -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6">Order Notes (Optional)</h2>
                        
                        <textarea name="notes" rows="4" placeholder="Any special instructions for your order..."
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-md sticky top-4">
                        <div class="p-6 border-b border-gray-200">
                            <h2 class="text-xl font-semibold text-gray-900">Order Summary</h2>
                        </div>
                        
                        <div class="p-6">
                            <!-- Cart Items -->
                            <div class="space-y-4 mb-6">
                                @foreach($cart->items as $item)
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0">
                                            @if($item->product->primary_image)
                                                <img src="{{ $item->product->primary_image->url }}" 
                                                     alt="{{ $item->product->name }}" 
                                                     class="w-12 h-12 object-cover rounded">
                                            @else
                                                <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center">
                                                    <i class="fas fa-image text-gray-400 text-sm"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-gray-900 line-clamp-1">
                                                {{ $item->product->name }}
                                            </p>
                                            <p class="text-sm text-gray-500">Qty: {{ $item->quantity }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm font-medium text-gray-900">
                                                ${{ number_format($item->total_price, 2) }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <!-- Order Totals -->
                            <div class="border-t border-gray-200 pt-4 space-y-2">
                                <div class="flex justify-between text-sm text-gray-600">
                                    <span>Subtotal</span>
                                    <span>${{ number_format($cart->subtotal, 2) }}</span>
                                </div>
                                <div class="flex justify-between text-sm text-gray-600">
                                    <span>Shipping</span>
                                    <span>${{ number_format($cart->shipping_amount, 2) }}</span>
                                </div>
                                <div class="flex justify-between text-sm text-gray-600">
                                    <span>Tax</span>
                                    <span>${{ number_format($cart->tax_amount, 2) }}</span>
                                </div>
                                
                                @if($cart->discount_amount > 0)
                                    <div class="flex justify-between text-sm text-green-600">
                                        <span>Discount</span>
                                        <span>-${{ number_format($cart->discount_amount, 2) }}</span>
                                    </div>
                                @endif
                                
                                <div class="flex justify-between text-lg font-semibold text-gray-900 pt-2 border-t border-gray-200">
                                    <span>Total</span>
                                    <span>${{ number_format($cart->total, 2) }}</span>
                                </div>
                            </div>
                            
                            <!-- Place Order Button -->
                            <button type="submit" 
                                    class="w-full mt-6 flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <i class="fas fa-lock mr-2"></i>
                                Place Order
                            </button>
                            
                            <p class="text-xs text-gray-500 text-center mt-4">
                                <i class="fas fa-shield-alt mr-1"></i>
                                Your payment information is secure and encrypted
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

@push('scripts')
<script>
// Form validation
document.getElementById('checkoutForm').addEventListener('submit', function(e) {
    const requiredFields = this.querySelectorAll('[required]');
    let isValid = true;
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            field.classList.add('border-red-500');
            isValid = false;
        } else {
            field.classList.remove('border-red-500');
        }
    });
    
    if (!isValid) {
        e.preventDefault();
        alert('Please fill in all required fields.');
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
});
</script>
@endpush