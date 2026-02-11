<?php $__env->startSection('title', 'Order Successful'); ?>

<?php $__env->startSection('content'); ?>
<!-- Success Header -->
<section class="bg-gradient-to-r from-green-600 to-teal-600 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center">
            <div class="mb-6">
                <i class="fas fa-check-circle text-6xl"></i>
            </div>
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Order Successful!</h1>
            <p class="text-xl text-green-100">Thank you for your order</p>
        </div>
    </div>
</section>

<!-- Order Details -->
<section class="py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Order Header -->
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">Order #<?php echo e($order->order_number); ?></h2>
                        <p class="text-sm text-gray-600">Placed on <?php echo e($order->created_at->format('F d, Y \a\t h:i A')); ?></p>
                    </div>
                    <div class="text-right">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                            <?php switch($order->status):
                                case ('pending'): ?>
                                    bg-yellow-100 text-yellow-800
                                <?php break; ?>
                                <?php case ('processing'): ?>
                                    bg-blue-100 text-blue-800
                                <?php break; ?>
                                <?php case ('shipped'): ?>
                                    bg-indigo-100 text-indigo-800
                                <?php break; ?>
                                <?php case ('delivered'): ?>
                                    bg-green-100 text-green-800
                                <?php break; ?>
                                <?php case ('cancelled'): ?>
                                    bg-red-100 text-red-800
                                <?php break; ?>
                                <?php default: ?>
                                    bg-gray-100 text-gray-800
                            <?php endswitch; ?>
                        ">
                            <?php echo e(ucfirst($order->status)); ?>

                        </span>
                        <p class="text-sm text-gray-600 mt-1">Payment: <?php echo e(ucfirst($order->payment_status)); ?></p>
                    </div>
                </div>
            </div>

            <!-- Order Content -->
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Order Items -->
                    <div class="lg:col-span-2">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Items</h3>
                        
                        <div class="space-y-4">
                            <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
                                    <div class="flex-shrink-0">
                                        <?php if($item->product && $item->product->primary_image): ?>
                                            <img src="<?php echo e($item->product->primary_image->url); ?>" 
                                                 alt="<?php echo e($item->product_name); ?>" 
                                                 class="w-16 h-16 object-cover rounded">
                                        <?php else: ?>
                                            <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center">
                                                <i class="fas fa-image text-gray-400"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="flex-1">
                                        <h4 class="font-medium text-gray-900"><?php echo e($item->product_name); ?></h4>
                                        <p class="text-sm text-gray-500">SKU: <?php echo e($item->product_sku); ?></p>
                                        <p class="text-sm text-gray-500">Quantity: <?php echo e($item->quantity); ?></p>
                                    </div>
                                    
                                    <div class="text-right">
                                        <p class="text-sm text-gray-600">$<?php echo e(number_format($item->unit_price, 2)); ?> each</p>
                                        <p class="text-lg font-semibold text-gray-900">
                                            $<?php echo e(number_format($item->total_price, 2)); ?>

                                        </p>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        <!-- Shipping Address -->
                        <div class="mt-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Shipping Address</h3>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-gray-900">
                                    <?php echo e($order->shipping_address['first_name']); ?> <?php echo e($order->shipping_address['last_name']); ?>

                                </p>
                                <p class="text-gray-600"><?php echo e($order->shipping_address['address']); ?></p>
                                <p class="text-gray-600">
                                    <?php echo e($order->shipping_address['city']); ?>, <?php echo e($order->shipping_address['state']); ?> <?php echo e($order->shipping_address['postal_code']); ?>

                                </p>
                                <p class="text-gray-600"><?php echo e($order->shipping_address['country']); ?></p>
                                <p class="text-gray-600"><?php echo e($order->shipping_address['phone']); ?></p>
                            </div>
                        </div>

                        <!-- Order Notes -->
                        <?php if($order->notes): ?>
                            <div class="mt-8">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Notes</h3>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-gray-600"><?php echo e($order->notes); ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Order Summary -->
                    <div class="lg:col-span-1">
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Summary</h3>
                            
                            <div class="space-y-3">
                                <div class="flex justify-between text-gray-600">
                                    <span>Subtotal</span>
                                    <span>$<?php echo e(number_format($order->subtotal, 2)); ?></span>
                                </div>
                                <div class="flex justify-between text-gray-600">
                                    <span>Shipping</span>
                                    <span>$<?php echo e(number_format($order->shipping_amount, 2)); ?></span>
                                </div>
                                <div class="flex justify-between text-gray-600">
                                    <span>Tax</span>
                                    <span>$<?php echo e(number_format($order->tax_amount, 2)); ?></span>
                                </div>
                                
                                <?php if($order->discount_amount > 0): ?>
                                    <div class="flex justify-between text-green-600">
                                        <span>Discount</span>
                                        <span>-$<?php echo e(number_format($order->discount_amount, 2)); ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="flex justify-between text-lg font-semibold text-gray-900 pt-3 border-t border-gray-300">
                                    <span>Total</span>
                                    <span>$<?php echo e(number_format($order->total, 2)); ?></span>
                                </div>
                            </div>

                            <!-- Payment Method -->
                            <div class="mt-6 pt-6 border-t border-gray-300">
                                <p class="text-sm text-gray-600 mb-2">Payment Method</p>
                                <div class="flex items-center space-x-2">
                                    <?php switch($order->payment_method):
                                        case ('stripe'): ?>
                                            <i class="fab fa-stripe text-blue-600 text-xl"></i>
                                            <span class="font-medium">Credit Card</span>
                                        <?php break; ?>
                                        <?php case ('paypal'): ?>
                                            <i class="fab fa-paypal text-blue-500 text-xl"></i>
                                            <span class="font-medium">PayPal</span>
                                        <?php break; ?>
                                        <?php case ('cod'): ?>
                                            <i class="fas fa-money-bill-wave text-green-600 text-xl"></i>
                                            <span class="font-medium">Cash on Delivery</span>
                                        <?php break; ?>
                                        <?php default: ?>
                                            <i class="fas fa-credit-card text-gray-600 text-xl"></i>
                                            <span class="font-medium"><?php echo e(ucfirst($order->payment_method)); ?></span>
                                    <?php endswitch; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-6 space-y-3">
                            <a href="<?php echo e(route('shop.index')); ?>" 
                               class="w-full block text-center px-6 py-3 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                <i class="fas fa-shopping-bag mr-2"></i>
                                Continue Shopping
                            </a>
                            
                            <a href="<?php echo e(route('customer.orders.show', $order)); ?>" 
                               class="w-full block text-center px-6 py-3 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">
                                <i class="fas fa-receipt mr-2"></i>
                                View Order Details
                            </a>
                        </div>

                        <!-- Customer Support -->
                        <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <h4 class="font-medium text-blue-900 mb-2">Need Help?</h4>
                            <p class="text-sm text-blue-700 mb-3">
                                Our customer support team is here to help with any questions about your order.
                            </p>
                            <div class="space-y-2 text-sm">
                                <p class="text-blue-700">
                                    <i class="fas fa-envelope mr-2"></i>
                                    support@example.com
                                </p>
                                <p class="text-blue-700">
                                    <i class="fas fa-phone mr-2"></i>
                                    1-800-123-4567
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Actions -->
        <div class="mt-8 text-center">
            <p class="text-gray-600 mb-4">
                A confirmation email has been sent to your registered email address.
            </p>
            <p class="text-sm text-gray-500">
                Order #<?php echo e($order->order_number); ?> • <?php echo e($order->created_at->format('M d, Y h:i A')); ?>

            </p>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\habib\Downloads\Ecommerce-main\Ecommerce-main\resources\views/shop/checkout/success.blade.php ENDPATH**/ ?>