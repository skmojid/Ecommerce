<?php $__env->startSection('title', $category->name ?? 'Category'); ?>

<?php $__env->startSection('content'); ?>
<!-- Breadcrumb -->
<nav class="bg-gray-100 py-3">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <ol class="flex items-center space-x-2 text-sm text-gray-500">
            <li>
                <a href="<?php echo e(route('shop.index')); ?>" class="hover:text-gray-700">
                    <i class="fas fa-home mr-2"></i>Home
                </a>
            </li>
            <li>
                <i class="fas fa-chevron-right"></i>
            </li>
            <li class="text-gray-900 font-medium"><?php echo e($category->name ?? 'Category'); ?></li>
        </ol>
    </div>
</nav>

<!-- Category Header -->
<section class="bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900"><?php echo e($category->name ?? 'Category'); ?></h1>
                <?php if($category->description): ?>
                    <p class="text-gray-600 mt-2"><?php echo e($category->description); ?></p>
                <?php endif; ?>
            </div>
            
            <div class="flex items-center space-x-4">
                <div class="text-sm text-gray-500">
                    <?php echo e($products->total()); ?> products
                </div>
                
                <!-- View Toggle -->
                <div class="hidden md:flex space-x-2">
                    <button onclick="toggleView()" id="gridView" 
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors text-sm">
                        <i class="fas fa-th mr-2"></i>
                        Grid
                    </button>
                    <button onclick="toggleView()" id="listView" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors text-sm">
                        <i class="fas fa-list mr-2"></i>
                        List
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Products Grid -->
<section class="bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Subcategories -->
        <?php if(isset($subcategories) && $subcategories->count() > 0): ?>
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Subcategories</h2>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4">
                    <?php $__currentLoopData = $subcategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subcategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(route('shop.category', $subcategory)); ?>" 
                           class="bg-white p-4 rounded-lg shadow-sm hover:shadow-md transition-shadow border border-gray-200 hover:border-indigo-300 group">
                            <h3 class="font-semibold text-gray-900 group-hover:text-indigo-600">
                                <?php echo e($subcategory->name); ?>

                            </h3>
                            <?php if($subcategory->products_count ?? $subcategory->products->count()): ?>
                                <p class="text-sm text-gray-500 mt-1">
                                    <?php echo e($subcategory->products_count ?? $subcategory->products->count()); ?> products
                                </p>
                            <?php endif; ?>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Products Container -->
        <div id="productsContainer" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 lg:gap-8">
            <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 group transform hover:scale-105">
                    <a href="<?php echo e(route('shop.show', $product)); ?>" class="block">
                        <div class="relative">
                            <?php if($product->is_on_sale): ?>
                                <span class="absolute top-3 right-3 bg-red-500 text-white text-xs px-2 py-1 rounded-full z-10 shadow-lg">
                                    <i class="fas fa-percentage mr-1"></i>
                                    Sale
                                </span>
                            <?php endif; ?>
                            
                            <div class="aspect-w-1 aspect-h-1 bg-gray-100 overflow-hidden">
                                <?php if($product->primary_image): ?>
                                    <img src="<?php echo e($product->primary_image->url); ?>" 
                                         alt="<?php echo e($product->name); ?>" 
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                         loading="lazy">
                                <?php else: ?>
                                    <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                        <i class="fas fa-image text-gray-400 text-4xl"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="p-5">
                            <div class="flex flex-col sm:flex-row sm:items-start justify-between mb-3 gap-3">
                                <div class="flex-1 sm:flex-1-2">
                                    <h3 class="text-lg font-bold text-gray-900 group-hover:text-indigo-600 mb-1"><?php echo e($product->name); ?></h3>
                                    <p class="text-sm text-gray-600"><?php echo e($product->category->name ?? 'Uncategorized'); ?></p>
                                </div>
                                
                                <div class="text-right sm:text-left">
                                    <?php if($product->is_on_sale): ?>
                                        <div class="space-y-1">
                                            <span class="text-xl font-bold text-red-600 block">$<?php echo e(number_format($product->price, 2)); ?></span>
                                            <span class="text-sm text-gray-400 line-through block">$<?php echo e(number_format($product->compare_price, 2)); ?></span>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-xl font-bold text-gray-900 block">$<?php echo e(number_format($product->price, 2)); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <p class="text-gray-700 text-sm mb-4 line-clamp-2 leading-relaxed">
                                <?php echo e($product->short_description ?: Str::limit($product->description, 100)); ?>

                            </p>
                            
                            <!-- Stock Status -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <?php if($product->track_quantity): ?>
                                        <?php if($product->quantity <= 5): ?>
                                            <span class="px-3 py-1.5 bg-red-100 text-red-800 text-xs font-medium rounded-full">
                                                Only <?php echo e($product->quantity); ?> left!
                                            </span>
                                        <?php elseif($product->quantity <= 10): ?>
                                            <span class="px-3 py-1.5 bg-yellow-100 text-yellow-800 text-xs font-medium rounded-full">
                                                Low Stock (<?php echo e($product->quantity); ?>)
                                            </span>
                                        <?php else: ?>
                                            <span class="px-3 py-1.5 bg-green-100 text-green-800 text-xs font-medium rounded-full">
                                                In Stock
                                            </span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="px-3 py-1.5 bg-gray-100 text-gray-800 text-xs font-medium rounded-full">
                                            Available
                                        </span>
                                    <?php endif; ?>
                                </div>
                                
                                <form action="<?php echo e(route('cart.add')); ?>" method="POST" class="inline">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="product_id" value="<?php echo e($product->id); ?>">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" 
                                            class="bg-indigo-600 text-white px-5 py-2.5 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition-all"
                                            <?php if(!$product->isInStock()): ?>
                                            disabled
                                        <?php endif; ?>>
                                        <span class="flex items-center">
                                            <i class="fas fa-shopping-cart mr-2"></i>
                                            <?php if($product->isInStock()): ?>
                                                Add to Cart
                                            <?php else: ?>
                                                Out of Stock
                                            <?php endif; ?>
                                        </span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-span-full text-center py-16">
                    <div class="bg-white rounded-lg shadow-sm p-12">
                        <i class="fas fa-search text-6xl text-gray-300 mb-6"></i>
                        <h3 class="text-2xl font-bold text-gray-700 mb-4">No products found in this category.</h3>
                        <p class="text-gray-600 mb-6">Try browsing other categories or check back later.</p>
                        <a href="<?php echo e(route('shop.categories.index')); ?>" class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                            <i class="fas fa-th-large mr-2"></i>
                            Browse All Categories
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Pagination -->
        <?php if($products->hasPages()): ?>
            <div class="mt-16 flex justify-center">
                <div class="inline-flex items-center space-x-1">
                    <?php echo e($products->links()); ?>

                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    function clearSearch() {
        const url = new URL(window.location.href);
        url.searchParams.delete('search');
        url.searchParams.delete('page');
        window.location.href = url.toString();
    }
    
    function toggleView() {
        const container = document.getElementById('productsContainer');
        const gridBtn = document.getElementById('gridView');
        const listBtn = document.getElementById('listView');
        
        if (container.classList.contains('grid-cols-1')) {
            // Switch to list view
            container.className = 'space-y-6';
            gridBtn.classList.add('bg-indigo-600', 'text-white');
            gridBtn.classList.remove('bg-gray-300', 'text-gray-700');
            listBtn.classList.add('bg-indigo-600', 'text-white');
            listBtn.classList.remove('bg-gray-300', 'text-gray-700');
        } else {
            // Switch to grid view
            container.className = 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6';
            listBtn.classList.add('bg-indigo-600', 'text-white');
            listBtn.classList.remove('bg-gray-300', 'text-gray-700');
            gridBtn.classList.remove('bg-gray-300', 'text-gray-700');
            gridBtn.classList.add('bg-indigo-600', 'text-white');
        }
    }
    
    // Auto-hide alerts after 5 seconds
    setTimeout(() => {
        const alerts = document.querySelectorAll('.bg-green-100, .bg-red-100');
        alerts.forEach(alert => alert.style.display = 'none');
    }, 5000);
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\habib\Downloads\Ecommerce-main\Ecommerce-main\resources\views/shop/category.blade.php ENDPATH**/ ?>