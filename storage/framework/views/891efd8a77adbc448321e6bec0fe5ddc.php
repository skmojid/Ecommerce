<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Admin Panel'); ?> - eCommerce Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar-container {
            width: 260px;
            height: 100vh;
            background: white;
            border-right: 1px solid #e5e7eb;
            box-shadow: 2px 0 8px rgba(0, 0, 0, 0.04);
        }
        
        .sidebar-link { 
            @apply flex items-center gap-4 px-6 py-3 text-gray-700 hover:bg-gray-50 transition-colors duration-200 relative;
        }
        
        .sidebar-link.active { 
            @apply bg-indigo-50 text-indigo-600 border-l-4 border-indigo-600;
        }
        
        .sidebar-icon {
            @apply w-5 h-5 text-center flex-shrink-0;
        }
        
        .sidebar-section { 
            @apply mt-8;
        }
        
        .sidebar-section-title { 
            @apply px-6 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3;
        }
        
        .sidebar-badge {
            @apply ml-auto bg-red-500 text-white text-xs rounded-full px-2 py-1 min-w-[20px] text-center font-medium;
        }
        
        .sidebar-link:hover .sidebar-badge {
            @apply bg-red-600;
        }
        
        .sidebar-logo {
            @apply flex items-center gap-4 px-6 py-6 border-b border-gray-200;
        }
        
        .sidebar-logo-icon {
            @apply w-10 h-10 bg-indigo-600 rounded-lg flex items-center justify-center text-white flex-shrink-0;
        }
        
        .sidebar-bottom {
            @apply mt-auto border-t border-gray-200;
        }
        
        .sidebar-link:hover .sidebar-icon {
            @apply text-indigo-600;
        }
        
        .sidebar-link.active .sidebar-icon {
            @apply text-indigo-600;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="sidebar-container flex flex-col">
            <!-- Logo Section -->
            <div class="sidebar-logo">
                <div class="sidebar-logo-icon">
                    <i class="fas fa-store"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-gray-900">ShopHub</h1>
                    <p class="text-sm text-gray-500">Admin Panel</p>
                </div>
            </div>
            
            <!-- Navigation -->
            <nav class="flex-1 overflow-y-auto">
                <!-- Dashboard -->
                <a href="<?php echo e(route('admin.dashboard')); ?>" class="sidebar-link <?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>">
                    <i class="fas fa-tachometer-alt sidebar-icon"></i>
                    <span>Dashboard</span>
                </a>
                
                <!-- Products Section -->
                <div class="sidebar-section">
                    <h3 class="sidebar-section-title">Products</h3>
                    <a href="<?php echo e(route('admin.products.index')); ?>" class="sidebar-link <?php echo e(request()->routeIs('admin.products.index') ? 'active' : ''); ?>">
                        <i class="fas fa-box sidebar-icon"></i>
                        <span>All Products</span>
                    </a>
                </div>
                
                <!-- Orders Section -->
                <div class="sidebar-section">
                    <h3 class="sidebar-section-title">Orders</h3>
                    <a href="<?php echo e(route('admin.orders.index')); ?>" class="sidebar-link <?php echo e(request()->routeIs('admin.orders.*') ? 'active' : ''); ?>">
                        <i class="fas fa-shopping-cart sidebar-icon"></i>
                        <span>All Orders</span>
                        <span class="sidebar-badge">3</span>
                    </a>
                </div>
                
                <!-- Users Section -->
                <div class="sidebar-section">
                    <h3 class="sidebar-section-title">Users</h3>
                    <a href="<?php echo e(route('admin.users.index')); ?>" class="sidebar-link <?php echo e(request()->routeIs('admin.users.*') ? 'active' : ''); ?>">
                        <i class="fas fa-users sidebar-icon"></i>
                        <span>All Users</span>
                    </a>
                </div>
            </nav>
            
            <!-- Bottom Actions -->
            <div class="sidebar-bottom">
                <a href="<?php echo e(route('shop.index')); ?>" class="sidebar-link text-gray-600 hover:text-green-600">
                    <i class="fas fa-external-link-alt sidebar-icon"></i>
                    <span>Visit Store</span>
                </a>
                <form action="<?php echo e(route('logout')); ?>" method="POST" class="sidebar-link text-gray-600 hover:text-red-600 cursor-pointer">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="flex items-center w-full text-left">
                        <i class="fas fa-sign-out-alt sidebar-icon"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Bar -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <h1 class="text-2xl font-semibold text-gray-800"><?php echo $__env->yieldContent('page-title', 'Admin Panel'); ?></h1>
                            <?php if($breadcrumbs ?? false): ?>
                                <nav class="ml-4 text-sm text-gray-500">
                                    <ol class="flex items-center space-x-2">
                                        <li><a href="<?php echo e(route('admin.dashboard')); ?>" class="hover:text-gray-700">Dashboard</a></li>
                                        <?php echo $__env->yieldContent('breadcrumbs'); ?>
                                    </ol>
                                </nav>
                            <?php endif; ?>
                        </div>
                        
                        <!-- User Menu -->
                        <?php if(Auth::user()): ?>
                            <div class="flex items-center space-x-4">
                                <!-- Global Search -->
                                <div class="relative">
                                    <input type="text" 
                                           placeholder="Search..." 
                                           class="w-64 px-4 py-2 pr-10 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                    <button class="absolute right-2 top-2.5 text-gray-400 hover:text-indigo-600 transition-colors">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                                
                                <!-- Notifications -->
                                <button class="relative p-2 text-gray-600 hover:text-gray-900 transition-colors group">
                                    <i class="fas fa-bell group-hover:animate-pulse"></i>
                                    <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                                </button>
                                
                                <!-- Messages -->
                                <button class="relative p-2 text-gray-600 hover:text-gray-900 transition-colors group">
                                    <i class="fas fa-envelope group-hover:animate-pulse"></i>
                                    <span class="absolute top-1 right-1 bg-indigo-600 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center font-bold">2</span>
                                </button>
                                
                                <!-- User Dropdown -->
                                <div class="relative group">
                                    <button class="flex items-center space-x-3 pl-4 border-l border-gray-200 hover:border-indigo-300 transition-colors">
                                        <div class="text-right">
                                            <p class="text-sm font-medium text-gray-900 group-hover:text-indigo-600 transition-colors"><?php echo e(Auth::user()->name); ?></p>
                                            <p class="text-xs text-gray-500 group-hover:text-indigo-500 transition-colors">
                                                <?php echo e(Auth::user()->isAdmin() ? 'Administrator' : (Auth::user()->isManager() ? 'Manager' : 'User')); ?>

                                            </p>
                                        </div>
                                        <div class="w-10 h-10 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-full flex items-center justify-center shadow-lg group-hover:shadow-xl transition-shadow">
                                            <span class="text-white font-bold text-lg">
                                                <?php echo e(strtoupper(substr(Auth::user()->name, 0, 1))); ?>

                                            </span>
                                        </div>
                                    </button>
                                    
                                    <!-- Dropdown Menu (on hover) -->
                                    <div class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                                        <div class="py-2">
                                            <a href="<?php echo e(route('profile')); ?>" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                                                <i class="fas fa-user-circle mr-3 w-4"></i>Profile
                                            </a>
                                            <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                                                <i class="fas fa-cog mr-3 w-4"></i>Settings
                                            </a>
                                            <div class="border-t border-gray-100 my-2"></div>
                                            <form action="<?php echo e(route('logout')); ?>" method="POST" class="block">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-700 transition-colors w-full text-left">
                                                    <i class="fas fa-sign-out-alt mr-3 w-4"></i>Logout
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="flex-1 overflow-y-auto bg-gray-50">
                <div class="p-6">
                    <!-- Flash Messages -->
                    <?php if(session('success')): ?>
                        <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            <?php echo e(session('success')); ?>

                        </div>
                    <?php endif; ?>

                    <?php if(session('error')): ?>
                        <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg flex items-center">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            <?php echo e(session('error')); ?>

                        </div>
                    <?php endif; ?>

                    <?php if($errors->any()): ?>
                        <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                <span class="font-medium">Please fix the following errors:</span>
                            </div>
                            <ul class="list-disc list-inside ml-6 space-y-1">
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <!-- Main Content -->
                    <?php echo $__env->yieldContent('content'); ?>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.bg-green-50, .bg-red-50');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.style.display = 'none', 500);
            });
        }, 5000);

        // Add subtle hover animations to sidebar links
        document.querySelectorAll('.sidebar-link').forEach(link => {
            link.addEventListener('mouseenter', function() {
                if (!this.classList.contains('active')) {
                    this.style.transform = 'translateX(4px)';
                }
            });
            
            link.addEventListener('mouseleave', function() {
                if (!this.classList.contains('active')) {
                    this.style.transform = 'translateX(0)';
                }
            });
        });
    </script>
</body>
</html><?php /**PATH C:\Users\habib\Downloads\Ecommerce-main\Ecommerce-main\resources\views/admin/layouts/app.blade.php ENDPATH**/ ?>