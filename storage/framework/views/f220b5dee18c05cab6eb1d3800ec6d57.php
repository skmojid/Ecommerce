<?php $__env->startSection('title', 'Products'); ?>
<?php $__env->startSection('page-title', 'Products Management'); ?>

<?php $__env->startSection('content'); ?>
<!-- Header Actions -->
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Products</h2>
        <p class="text-sm text-gray-600 mt-1">Manage your store inventory</p>
    </div>
    <div class="flex space-x-3">
        <a href="<?php echo e(route('admin.products.create')); ?>" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors">
            <i class="fas fa-plus mr-2"></i>
            Add Product
        </a>
        <div class="relative">
            <button onclick="document.getElementById('bulkActionsDropdown').classList.toggle('hidden')" 
                    class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                <i class="fas fa-cogs mr-2"></i>
                Bulk Actions
                <i class="fas fa-chevron-down ml-2"></i>
            </button>
            <!-- Dropdown Menu -->
            <div id="bulkActionsDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-10">
                <a href="#" onclick="bulkActivate()" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-check-circle mr-2 text-green-600"></i>
                    Activate Selected
                </a>
                <a href="#" onclick="bulkDeactivate()" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-times-circle mr-2 text-red-600"></i>
                    Deactivate Selected
                </a>
                <a href="#" onclick="bulkFeature()" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-star mr-2 text-yellow-600"></i>
                    Feature Selected
                </a>
                <div class="border-t border-gray-100"></div>
                <a href="#" onclick="bulkDelete()" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                    <i class="fas fa-trash mr-2"></i>
                    Delete Selected
                </a>
            </div>
        </div>
        <button onclick="window.location.href='<?php echo e(route('admin.products.export')); ?>'" 
                class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
            <i class="fas fa-download mr-2"></i>
            Export CSV
        </button>
    </div>
</div>

<!-- Search and Filters -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
    <form method="GET" action="<?php echo e(route('admin.products.index')); ?>" class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Search -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search Products</label>
                <div class="relative">
                    <input type="text" name="search" value="<?php echo e(request('search')); ?>" 
                           placeholder="Search by name, SKU, or description..." 
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 pl-10">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                </div>
            </div>
            
            <!-- Category -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                <select name="category" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
                    <option value="">All Categories</option>
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($category->id); ?>" <?php echo e(request('category') == $category->id ? 'selected' : ''); ?>>
                            <?php echo e($category->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            
            <!-- Status -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
                    <option value="">All Status</option>
                    <option value="active" <?php echo e(request('status') == 'active' ? 'selected' : ''); ?>>
                        <i class="fas fa-check-circle mr-2 text-green-600"></i>
                        Active
                    </option>
                    <option value="inactive" <?php echo e(request('status') == 'inactive' ? 'selected' : ''); ?>>
                        <i class="fas fa-times-circle mr-2 text-red-600"></i>
                        Inactive
                    </option>
                    <option value="featured" <?php echo e(request('status') == 'featured' ? 'selected' : ''); ?>>
                        <i class="fas fa-star mr-2 text-yellow-600"></i>
                        Featured
                    </option>
                </select>
            </div>
            
            <!-- Price Range -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Price Range</label>
                <div class="flex items-center space-x-2">
                    <input type="number" name="min_price" value="<?php echo e(request('min_price')); ?>" 
                           placeholder="Min" min="0" step="0.01"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
                    <span class="text-gray-400">—</span>
                    <input type="number" name="max_price" value="<?php echo e(request('max_price')); ?>" 
                           placeholder="Max" min="0" step="0.01"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>
        </div>
        
        <!-- Filter Actions -->
        <div class="flex items-center justify-between">
            <div class="flex space-x-3">
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2.5 rounded-lg hover:bg-indigo-700 transition-colors">
                    <i class="fas fa-search mr-2"></i>
                    Apply Filters
                </button>
                <a href="<?php echo e(route('admin.products.index')); ?>" class="text-gray-600 hover:text-gray-900 px-4 py-2 rounded-lg hover:bg-gray-100 transition-colors">
                    <i class="fas fa-times mr-2"></i>
                    Clear All
                </a>
            </div>
            <!-- Active Filters Display -->
            <?php if(request()->hasAny(['search', 'category', 'status', 'min_price', 'max_price'])): ?>
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-600">Active:</span>
                    <?php if(request('search')): ?>
                        <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                            Search: "<?php echo e(request('search')); ?>"
                        </span>
                    <?php endif; ?>
                    <?php if(request('category')): ?>
                        <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">
                            <?php echo e($categories->firstWhere('id', request('category'))->name ?? 'Category'); ?>

                        </span>
                    <?php endif; ?>
                    <?php if(request('status')): ?>
                        <span class="px-2 py-1 bg-purple-100 text-purple-800 text-xs rounded-full">
                            <?php echo e(request('status')); ?>

                        </span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- Products Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left">
                        <input type="checkbox" id="selectAll" class="rounded border-gray-300">
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Featured</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <input type="checkbox" name="products[]" value="<?php echo e($product->id); ?>" class="rounded border-gray-300">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php if($product->primaryImage): ?>
                                <img src="<?php echo e(asset($product->primaryImage->first()->image_path)); ?>" alt="<?php echo e($product->name); ?>" 
                                     class="h-12 w-12 rounded object-cover">
                            <?php else: ?>
                                <div class="h-12 w-12 bg-gray-200 rounded flex items-center justify-center">
                                    <i class="fas fa-image text-gray-400"></i>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900"><?php echo e($product->name); ?></div>
                            <?php if($product->short_description): ?>
                                <div class="text-xs text-gray-500 mt-1"><?php echo e(Str::limit($product->short_description, 50)); ?></div>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900"><?php echo e($product->sku); ?></td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            <?php echo e($product->category->name ?? 'N/A'); ?>

                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">$<?php echo e(number_format($product->price, 2)); ?></td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs font-medium rounded-full 
                                <?php if($product->track_quantity && $product->quantity <= ($product->stock_alert_threshold ?? 5)): ?>
                                    bg-red-100 text-red-800
                                <?php elseif($product->track_quantity && $product->quantity <= 10): ?>
                                    bg-yellow-100 text-yellow-800
                                <?php else: ?>
                                    bg-green-100 text-green-800
                                <?php endif; ?>">
                                <?php echo e($product->quantity); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs font-medium rounded-full 
                                <?php echo e($product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                                <?php echo e($product->is_active ? 'Active' : 'Inactive'); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-2 py-1 text-xs font-medium rounded-full 
                                <?php echo e($product->is_featured ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800'); ?>">
                                <?php echo e($product->is_featured ? 'Featured' : 'Regular'); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-end space-x-2">
                                <a href="<?php echo e(route('admin.products.show', $product)); ?>" 
                                   class="text-indigo-600 hover:text-indigo-900 p-1 rounded hover:bg-indigo-50"
                                   title="View Product">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="<?php echo e(route('admin.products.edit', $product)); ?>" 
                                   class="text-blue-600 hover:text-blue-900 p-1 rounded hover:bg-blue-50"
                                   title="Edit Product">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="<?php echo e(route('admin.products.destroy', $product)); ?>" method="POST" class="inline"
                                      onsubmit="return confirm('Are you sure you want to delete this product?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-900 p-1 rounded hover:bg-red-50"
                                            title="Delete Product">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="10" class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-box-open text-4xl mb-2"></i>
                            <p class="text-lg">No products found.</p>
                            <p class="text-sm text-gray-400 mt-1">Try adjusting your search or filters.</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200">
        <div class="text-sm text-gray-600">
            Showing <?php echo e($products->firstItem()); ?> to <?php echo e($products->lastItem()); ?> of <?php echo e($products->total()); ?> results
        </div>
        <?php echo e($products->links()); ?>

    </div>
</div>

<!-- Bulk Actions Form (Hidden) -->
<form id="bulkActionsForm" method="POST" action="<?php echo e(route('admin.products.bulkUpdate')); ?>" class="hidden">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="products" id="selectedProducts">
    <input type="hidden" name="action" id="bulkAction">
</form>

<?php $__env->startSection('scripts'); ?>
<script>
    // Select all functionality
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('input[name="products[]"]');
        checkboxes.forEach(checkbox => checkbox.checked = this.checked);
        updateSelectedProducts();
    });

    // Update selected products
    document.querySelectorAll('input[name="products[]"]').forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedProducts);
    });

    function updateSelectedProducts() {
        const selected = Array.from(document.querySelectorAll('input[name="products[]"]:checked'))
            .map(cb => cb.value);
        document.getElementById('selectedProducts').value = JSON.stringify(selected);
    }

    // Bulk action handlers
    function bulkActivate() {
        document.getElementById('bulkAction').value = 'activate';
        document.getElementById('bulkActionsForm').submit();
    }

    function bulkDeactivate() {
        document.getElementById('bulkAction').value = 'deactivate';
        document.getElementById('bulkActionsForm').submit();
    }

    function bulkFeature() {
        document.getElementById('bulkAction').value = 'feature';
        document.getElementById('bulkActionsForm').submit();
    }

    function bulkDelete() {
        if (confirm('Are you sure you want to delete selected products?')) {
            document.getElementById('bulkAction').value = 'delete';
            document.getElementById('bulkActionsForm').submit();
        }
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('bulkActionsDropdown');
        const button = event.target.closest('button');
        if (!dropdown.contains(event.target) && !button) {
            dropdown.classList.add('hidden');
        }
    });
</script>
<?php $__env->stopSection(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\habib\Downloads\laravel-ecom\laravel-ecom\resources\views/admin/products/index.blade.php ENDPATH**/ ?>