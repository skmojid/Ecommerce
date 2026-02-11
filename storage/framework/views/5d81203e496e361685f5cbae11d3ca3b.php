<?php $__env->startSection('title', 'Orders Management'); ?>
<?php $__env->startSection('page-title', 'Orders Management'); ?>

<?php $__env->startSection('content'); ?>
<!-- Header Actions -->
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Orders</h2>
        <p class="text-sm text-gray-600 mt-1">Manage customer orders and fulfillment</p>
    </div>
    <div class="flex space-x-3">
        <button onclick="window.location.href='<?php echo e(route('admin.orders.export')); ?>'" 
                class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
            <i class="fas fa-download mr-2"></i>
            Export CSV
        </button>
    </div>
</div>

<!-- Search and Filters -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
    <form method="GET" action="<?php echo e(route('admin.orders.index')); ?>" class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Search -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search Orders</label>
                <div class="relative">
                    <input type="text" 
                           name="search" 
                           value="<?php echo e(request('search')); ?>" 
                           placeholder="Search by order number, customer, or email..." 
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 pl-10">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                </div>
            </div>
            
            <!-- Order Status -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Order Status</label>
                <select name="status" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
                    <option value="">All Status</option>
                    <option value="pending" <?php echo e(request('status') == 'pending' ? 'selected' : ''); ?>>Pending</option>
                    <option value="processing" <?php echo e(request('status') == 'processing' ? 'selected' : ''); ?>>Processing</option>
                    <option value="shipped" <?php echo e(request('status') == 'shipped' ? 'selected' : ''); ?>>Shipped</option>
                    <option value="delivered" <?php echo e(request('status') == 'delivered' ? 'selected' : ''); ?>>Delivered</option>
                    <option value="cancelled" <?php echo e(request('status') == 'cancelled' ? 'selected' : ''); ?>>Cancelled</option>
                    <option value="refunded" <?php echo e(request('status') == 'refunded' ? 'selected' : ''); ?>>Refunded</option>
                </select>
            </div>
            
            <!-- Payment Status -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Payment Status</label>
                <select name="payment_status" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
                    <option value="">All Payment</option>
                    <option value="pending" <?php echo e(request('payment_status') == 'pending' ? 'selected' : ''); ?>>Pending Payment</option>
                    <option value="paid" <?php echo e(request('payment_status') == 'paid' ? 'selected' : ''); ?>>Paid</option>
                    <option value="failed" <?php echo e(request('payment_status') == 'failed' ? 'selected' : ''); ?>>Failed</option>
                    <option value="refunded" <?php echo e(request('payment_status') == 'refunded' ? 'selected' : ''); ?>>Refunded</option>
                </select>
            </div>
        </div>
        
        <!-- Filter Actions -->
        <div class="flex items-center justify-between">
            <div class="flex space-x-3">
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2.5 rounded-lg hover:bg-indigo-700 transition-colors">
                    <i class="fas fa-search mr-2"></i>
                    Apply Filters
                </button>
                <a href="<?php echo e(route('admin.orders.index')); ?>" class="text-gray-600 hover:text-gray-900 px-4 py-2.5 rounded-lg hover:bg-gray-100 transition-colors">
                    <i class="fas fa-times mr-2"></i>
                    Clear All
                </a>
            </div>
        </div>
    </form>
</div>

<!-- Orders Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <a href="<?php echo e(route('admin.orders.show', $order)); ?>" class="text-indigo-600 hover:text-indigo-900 font-medium">
                                #<?php echo e($order->order_number ?? $order->id); ?>

                            </a>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm">
                                <div class="font-medium text-gray-900"><?php echo e($order->user->name ?? 'Guest'); ?></div>
                                <div class="text-gray-600 text-xs"><?php echo e($order->user->email ?? 'N/A'); ?></div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900"><?php echo e($order->created_at->format('M d, Y')); ?></td>
                        <td class="px-6 py-4 text-right">
                            <div class="text-lg font-bold text-gray-900">$<?php echo e(number_format($order->total ?? 0, 2)); ?></div>
                            <div class="text-xs text-gray-500 mt-1">
                                <?php if($order->items->count() > 1): ?>
                                    (<?php echo e($order->items->count()); ?> items)
                                <?php endif; ?>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 text-xs font-medium rounded-full <?php echo e($order->payment_status_badge); ?>">
                                <?php echo e($order->payment_status); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 text-xs font-medium rounded-full <?php echo e($order->status_badge); ?>">
                                <?php echo e($order->status); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-center space-x-2">
                                <a href="<?php echo e(route('admin.orders.show', $order)); ?>" 
                                        class="text-indigo-600 hover:text-indigo-900 p-1 rounded hover:bg-indigo-50"
                                        title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                <button onclick="updateOrderStatus(<?php echo e($order->id); ?>, 'shipped')" 
                                        class="text-purple-600 hover:text-purple-900 p-1 rounded hover:bg-purple-50"
                                        title="Mark Shipped"
                                        <?php if($order->status == 'shipped'): ?>
                                        disabled
                                        <?php endif; ?>>
                                    <i class="fas fa-truck"></i>
                                </button>
                                <button onclick="updateOrderStatus(<?php echo e($order->id); ?>, 'delivered')" 
                                        class="text-green-600 hover:text-green-900 p-1 rounded hover:bg-green-50"
                                        title="Mark Delivered"
                                        <?php if($order->status == 'delivered'): ?>
                                        disabled
                                        <?php endif; ?>>
                                    <i class="fas fa-check"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="space-y-4">
                                <i class="fas fa-shopping-cart text-6xl text-gray-300 mb-4"></i>
                                <h3 class="text-xl font-semibold text-gray-700 mb-2">No orders found</h3>
                                <p class="text-gray-500">Try adjusting your search or filters to see results.</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <?php if($orders->hasPages()): ?>
        <div class="bg-white px-6 py-3 flex items-center justify-between border-t border-gray-200">
            <div class="text-sm text-gray-600">
                Showing <?php echo e($orders->firstItem()); ?> to <?php echo e($orders->lastItem()); ?> of <?php echo e($orders->total()); ?> orders
            </div>
            <?php echo e($orders->links()); ?>

        </div>
    <?php endif; ?>
</div>

<?php $__env->startSection('scripts'); ?>
<script>
    function updateOrderStatus(orderId, status) {
        if (confirm(`Are you sure you want to update order status to ${status}?`)) {
            fetch(`<?php echo e(route('admin.orders.updateStatus', ':id')); ?>`.replace(':id', orderId), {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    status: status
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert('Error updating order: ' + data.message);
                }
            })
            .catch(error => {
                alert('Error updating order: ' + error.message);
            });
        }
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\habib\Downloads\Ecommerce-main\Ecommerce-main\resources\views/admin/orders/index.blade.php ENDPATH**/ ?>