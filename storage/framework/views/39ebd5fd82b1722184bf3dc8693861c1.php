<?php $__env->startSection('title', 'Categories'); ?>

<?php $__env->startSection('content'); ?>
<!-- Hero Section -->
<section class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Browse Categories</h1>
            <p class="text-xl text-indigo-100 max-w-2xl mx-auto">
                Explore our wide range of product categories
            </p>
        </div>
    </div>
</section>

<!-- Categories Grid -->
<section class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <?php if($categories->count() > 0): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('shop.category', $category)); ?>" 
                       class="group bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                        <div class="relative h-48 bg-gradient-to-br from-indigo-50 to-purple-50 flex items-center justify-center">
                            <?php if($category->image): ?>
                                <img src="<?php echo e(asset($category->image)); ?>" 
                                     alt="<?php echo e($category->name); ?>" 
                                     class="w-full h-full object-cover">
                            <?php else: ?>
                                <div class="text-center">
                                    <i class="fas fa-th-large text-6xl text-indigo-400 mb-3"></i>
                                </div>
                            <?php endif; ?>>
                            
                            <?php if($category->active_products_count > 0): ?>
                                <span class="absolute top-4 right-4 bg-indigo-600 text-white text-xs px-2 py-1 rounded-full">
                                    <?php echo e($category->active_products_count); ?> products
                                </span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-indigo-600 transition-colors">
                                <?php echo e($category->name); ?>

                            </h3>
                            
                            <?php if($category->description): ?>
                                <p class="text-gray-600 text-sm mb-4">
                                    <?php echo e(Str::limit($category->description, 80)); ?>

                                </p>
                            <?php endif; ?>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-indigo-600 font-medium text-sm">
                                    <?php echo e($category->active_products_count); ?> items
                                </span>
                                <span class="text-indigo-600 group-hover:text-indigo-700">
                                    <i class="fas fa-arrow-right"></i>
                                </span>
                            </div>
                        </div>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php else: ?>
            <div class="text-center py-12">
                <i class="fas fa-th-large text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-medium text-gray-900 mb-2">No Categories Available</h3>
                <p class="text-gray-600">Check back later for new product categories.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Call to Action -->
<section class="bg-gray-50 py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold text-gray-900 mb-4">Can't Find What You're Looking For?</h2>
        <p class="text-lg text-gray-600 mb-8">
            Browse our full product catalog or contact us for special requests
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="<?php echo e(route('shop.index')); ?>" class="bg-indigo-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-indigo-700 transition-colors">
                <i class="fas fa-shopping-bag mr-2"></i>
                All Products
            </a>
            <a href="<?php echo e(route('contact')); ?>" class="border-2 border-indigo-600 text-indigo-600 px-8 py-3 rounded-lg font-semibold hover:bg-indigo-600 hover:text-white transition-colors">
                <i class="fas fa-envelope mr-2"></i>
                Contact Us
            </a>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\habib\Downloads\Ecommerce-main\Ecommerce-main\resources\views/shop/categories.blade.php ENDPATH**/ ?>