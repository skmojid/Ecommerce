<?php $__env->startSection('title', 'Order Details'); ?>
<?php $__env->startSection('page-title', 'Order Details'); ?>

<?php $__env->startSection('content'); ?>
<!-- Order Header -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Order #<?php echo e($order->order_number ?? $order->id); ?></h1>
            <p class="text-sm text-gray-600">Placed on <?php echo e($order->created_at->format('M d, Y \a\t h:i A')); ?></p>
        </div>
        <div class="flex space-x-3">
            <span class="px-3 py-1 text-xs font-medium rounded-full <?php echo e($order->status_badge ?? 'bg-gray-100 text-gray-800'); ?>">
                <?php echo e($order->status ?? 'Pending'); ?>

            </span>
            <span class="px-3 py-1 text-xs font-medium rounded-full <?php echo e($order->payment_status_badge ?? 'bg-gray-100 text-gray-800'); ?>">
                <?php echo e($order->payment_status ?? 'Pending'); ?>

            </span>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Customer Information -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Customer Information</h2>
            <div class="space-y-3">
                <div>
                    <p class="text-sm font-medium text-gray-900"><?php echo e($order->user->name ?? 'Guest Customer'); ?></p>
                    <p class="text-sm text-gray-600"><?php echo e($order->user->email ?? 'N/A'); ?></p>
                    <?php if($order->user->phone): ?>
                        <p class="text-sm text-gray-600"><?php echo e($order->user->phone); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Items -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Order Items</h2>
            <div class="space-y-4">
                <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-center justify-between py-4 border-b border-gray-100">
                        <div class="flex items-center space-x-4">
                            <?php if($item->product && $item->product->primary_image): ?>
                                <img src="<?php echo e(asset('storage/' . $item->product->primary_image->image_path)); ?>" 
                                     alt="<?php echo e($item->product->name); ?>" 
                                     class="w-16 h-16 object-cover rounded-lg">
                            <?php else: ?>
                                <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-image text-gray-400"></i>
                                </div>
                            <?php endif; ?>
                            <div>
                                <h3 class="text-sm font-medium text-gray-900"><?php echo e($item->product->name ?? $item->product_name ?? 'Product Removed'); ?></h3>
                                <p class="text-sm text-gray-600">Qty: <?php echo e($item->quantity); ?> × $<?php echo e(number_format($item->unit_price, 2)); ?></p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-bold text-gray-900">$<?php echo e(number_format($item->total_price, 2)); ?></p>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            
            <!-- Order Summary -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                <div class="space-y-2">
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>Subtotal:</span>
                        <span>$<?php echo e(number_format($order->subtotal ?? 0, 2)); ?></span>
                    </div>
                    <?php if($order->tax_amount > 0): ?>
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Tax:</span>
                            <span>$<?php echo e(number_format($order->tax_amount, 2)); ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if($order->shipping_amount > 0): ?>
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Shipping:</span>
                            <span>$<?php echo e(number_format($order->shipping_amount, 2)); ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if($order->discount_amount > 0): ?>
                        <div class="flex justify-between text-sm text-green-600">
                            <span>Discount:</span>
                            <span>-$<?php echo e(number_format($order->discount_amount, 2)); ?></span>
                        </div>
                    <?php endif; ?>
                    <div class="flex justify-between text-lg font-bold text-gray-900 pt-2 border-t border-gray-200">
                        <span>Total:</span>
                        <span>$<?php echo e(number_format($order->total ?? 0, 2)); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Shipping & Billing Information -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
    <?php if($order->shipping_address): ?>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Shipping Address</h2>
            <div class="space-y-1 text-sm">
                <p class="text-gray-900"><?php echo e($order->shipping_address['name'] ?? ''); ?></p>
                <p class="text-gray-600"><?php echo e($order->shipping_address['address'] ?? ''); ?></p>
                <p class="text-gray-600">
                    <?php echo e($order->shipping_address['city'] ?? ''); ?>, <?php echo e($order->shipping_address['state'] ?? ''); ?> <?php echo e($order->shipping_address['zip'] ?? ''); ?>

                </p>
                <p class="text-gray-600"><?php echo e($order->shipping_address['country'] ?? ''); ?></p>
            </div>
        </div>
    <?php endif; ?>

    <?php if($order->billing_address): ?>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Billing Address</h2>
            <div class="space-y-1 text-sm">
                <p class="text-gray-900"><?php echo e($order->billing_address['name'] ?? ''); ?></p>
                <p class="text-gray-600"><?php echo e($order->billing_address['address'] ?? ''); ?></p>
                <p class="text-gray-600">
                    <?php echo e($order->billing_address['city'] ?? ''); ?>, <?php echo e($order->billing_address['state'] ?? ''); ?> <?php echo e($order->billing_address['zip'] ?? ''); ?>

                </p>
                <p class="text-gray-600"><?php echo e($order->billing_address['country'] ?? ''); ?></p>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Order Actions -->
<div class="mt-6 flex justify-between items-center">
    <a href="<?php echo e(route('admin.orders.index')); ?>" class="text-gray-600 hover:text-gray-900">
        <i class="fas fa-arrow-left mr-2"></i>
        Back to Orders
    </a>
    
    <div class="flex space-x-3">
        <?php if($order->canBeShipped()): ?>
            <form method="POST" action="<?php echo e(route('admin.orders.updateStatus', $order)); ?>">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <input type="hidden" name="status" value="shipped">
                <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
                    <i class="fas fa-truck mr-2"></i>
                    Mark as Shipped
                </button>
            </form>
        <?php endif; ?>

        <?php if($order->status === 'shipped'): ?>
            <form method="POST" action="<?php echo e(route('admin.orders.updateStatus', $order)); ?>">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <input type="hidden" name="status" value="delivered">
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                    <i class="fas fa-check mr-2"></i>
                    Mark as Delivered
                </button>
            </form>
        <?php endif; ?>

        <?php if($order->canBeCancelled()): ?>
            <form method="POST" action="<?php echo e(route('admin.orders.updateStatus', $order)); ?>" onsubmit="return confirm('Are you sure you want to cancel this order?')">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <input type="hidden" name="status" value="cancelled">
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
                    <i class="fas fa-times mr-2"></i>
                    Cancel Order
                </button>
            </form>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\habib\Downloads\laravel-ecom\laravel-ecom\resources\views/admin/orders/show.blade.php ENDPATH**/ ?>