@extends('layouts.app')

@section('title', 'Order #' . $order->order_number)

@section('content')
<!-- Order Header -->
<section class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Order Details</h1>
            <p class="text-xl text-indigo-100">Order #{{ $order->order_number }}</p>
        </div>
    </div>
</section>

<!-- Order Content -->
<section class="py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Order Information -->
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-6">Order Information</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Order Details -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Order Number</label>
                                <p class="text-gray-900 font-semibold">{{ $order->order_number }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Date Placed</label>
                                <p class="text-gray-900">{{ $order->created_at->format('F d, Y \a\t g:i A') }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Status</label>
                                <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                    @switch($order->status)
                                        @case('pending')
                                            bg-yellow-100 text-yellow-800
                                        @break
                                        @case('processing')
                                            bg-blue-100 text-blue-800
                                        @break
                                        @case('shipped')
                                            bg-indigo-100 text-indigo-800
                                        @break
                                        @case('delivered')
                                            bg-green-100 text-green-800
                                        @break
                                        @case('cancelled')
                                            bg-red-100 text-red-800
                                        @break
                                        @case('refunded')
                                            bg-gray-100 text-gray-800
                                        @break
                                        @default
                                            bg-gray-100 text-gray-800
                                    @endswitch
                                ">
                                    {{ ucfirst($order->status) }}
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Payment Status</label>
                                <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                    @switch($order->payment_status)
                                        @case('pending')
                                            bg-yellow-100 text-yellow-800
                                        @break
                                        @case('paid')
                                            bg-green-100 text-green-800
                                        @break
                                        @case('failed')
                                            bg-red-100 text-red-800
                                        @break
                                        @default
                                            bg-gray-100 text-gray-800
                                    @endswitch
                                ">
                                    {{ ucfirst($order->payment_status) }}
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Total Amount</label>
                                <p class="text-2xl font-bold text-gray-900">${{ number_format($order->total, 2) }}</p>
                            </div>
                        </div>
                        
                        <!-- Shipping Address -->
                        <div class="space-y-4">
                            <h3 class="text-xl font-semibold text-gray-900 mb-4">Shipping Address</h3>
                            
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-gray-900">
                                    {{ $order->shipping_address['first_name'] }} {{ $order->shipping_address['last_name'] }}<br>
                                    {{ $order->shipping_address['address'] }}<br>
                                    {{ $order->shipping_address['city'] }}, {{ $order->shipping_address['state'] }} {{ $order->shipping_address['postal_code'] }}<br>
                                    {{ $order->shipping_address['country'] }}<br>
                                    {{ $order->shipping_address['phone'] }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Order Items -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-6">Order Items ({{ $order->items->count() }})</h2>
                        
                        <div class="space-y-4">
                            @foreach($order->items as $item)
                                <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
                                    <div class="flex-shrink-0">
                                        @if($item->product && $item->product->primary_image)
                                            <img src="{{ $item->product->primary_image->url }}" 
                                                 alt="{{ $item->product->name }}" 
                                                 class="w-20 h-20 object-cover rounded">
                                        @else
                                            <div class="w-20 h-20 bg-gray-200 rounded flex items-center justify-center">
                                                <i class="fas fa-image text-gray-400 text-2xl"></i>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-gray-900">{{ $item->product_name }}</h4>
                                        <p class="text-sm text-gray-600 mb-2">
                                            SKU: {{ $item->product_sku ?? 'N/A' }}
                                        </p>
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-900">
                                                Quantity: {{ $item->quantity }} × 
                                            </span>
                                            <span class="text-gray-900">
                                                ${{ number_format($item->unit_price, 2) }} = ${{ number_format($item->total_price, 2) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Order Totals -->
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <div class="space-y-2">
                                <div class="flex justify-between text-gray-600">
                                    <span>Subtotal:</span>
                                    <span class="font-semibold text-gray-900">${{ number_format($order->subtotal, 2) }}</span>
                                </div>
                                <div class="flex justify-between text-gray-600">
                                    <span>Shipping:</span>
                                    <span class="font-semibold text-gray-900">${{ number_format($order->shipping_amount, 2) }}</span>
                                </div>
                                <div class="flex justify-between text-gray-600">
                                    <span>Tax:</span>
                                    <span class="font-semibold text-gray-900">${{ number_format($order->tax_amount, 2) }}</span>
                                </div>
                                @if($order->discount_amount > 0)
                                    <div class="flex justify-between text-green-600">
                                        <span>Discount:</span>
                                        <span class="font-semibold text-green-600">-${{ number_format($order->discount_amount, 2) }}</span>
                                    </div>
                                @endif
                                <div class="flex justify-between text-lg font-bold text-gray-900 pt-2 border-t border-gray-300">
                                    <span>Total:</span>
                                    <span class="text-indigo-600">${{ number_format($order->total, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</section>

<!-- Back to Orders -->
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <a href="{{ route('customer.orders.index') }}" 
           class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to Orders
        </a>
    </div>
</div>
@endsection