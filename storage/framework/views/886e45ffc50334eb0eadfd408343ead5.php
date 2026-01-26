<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Admin Panel'); ?> - <?php echo e(config('app.name')); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .admin-sidebar {
            background: linear-gradient(180deg, #1e293b 0%, #334155 100%);
        }
        .admin-link {
            transition: all 0.3s ease;
            position: relative;
        }
        .admin-link:hover {
            transform: translateX(4px);
        }
        .admin-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 24px;
            background: #6366f1;
            border-radius: 0 4px 4px 0;
        }
        .stat-card {
            transition: all 0.3s ease;
            border: 1px solid transparent;
        }
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            border-color: #e5e7eb;
        }
        .sidebar-collapsed {
            width: 80px;
        }
        .sidebar-collapsed .sidebar-text {
            display: none;
        }
        .sidebar-collapsed .sidebar-header h1,
        .sidebar-collapsed .sidebar-header p,
        .sidebar-collapsed .nav-section h3 {
            display: none;
        }
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-slide-in {
            animation: slideIn 0.5s ease-out;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="admin-sidebar w-64 min-h-screen shadow-xl">
            <div class="flex flex-col h-full">
                <!-- Logo Section -->
                <div class="p-6 border-b border-gray-700 sidebar-header">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-indigo-600 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-chart-line text-white text-xl"></i>
                        </div>
                        <div class="sidebar-text">
                            <h1 class="text-xl font-bold text-white">Admin</h1>
                            <p class="text-xs text-gray-400">Management Panel</p>
                        </div>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
                    <!-- Dashboard -->
                    <a href="<?php echo e(route('admin.dashboard')); ?>" 
                       class="admin-link flex items-center p-3 rounded-lg hover:bg-gray-700 <?php echo e(request()->routeIs('admin.dashboard*') ? 'bg-gray-700 text-indigo-400 active' : 'text-gray-300'); ?>">
                        <i class="fas fa-tachometer-alt mr-3 w-5"></i>
                        <span class="sidebar-text">Dashboard</span>
                    </a>

                    <!-- Catalog Section -->
                    <div class="pt-4 nav-section">
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Catalog</h3>
                        <div class="space-y-1">
                            <a href="<?php echo e(route('admin.products.index')); ?>" 
                               class="admin-link flex items-center p-3 rounded-lg hover:bg-gray-700 <?php echo e(request()->routeIs('admin.products*') ? 'bg-gray-700 text-indigo-400 active' : 'text-gray-300'); ?>">
                                <i class="fas fa-box mr-3 w-5"></i>
                                <span class="sidebar-text">Products</span>
                            </a>
                            <a href="<?php echo e(route('admin.categories.index')); ?>" 
                               class="admin-link flex items-center p-3 rounded-lg hover:bg-gray-700 <?php echo e(request()->routeIs('admin.categories*') ? 'bg-gray-700 text-indigo-400 active' : 'text-gray-300'); ?>">
                                <i class="fas fa-tags mr-3 w-5"></i>
                                <span class="sidebar-text">Categories</span>
                            </a>
                        </div>
                    </div>

                    <!-- Orders Section -->
                    <div class="pt-4 nav-section">
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Orders</h3>
                        <div class="space-y-1">
                            <a href="<?php echo e(route('admin.orders.index')); ?>" 
                               class="admin-link flex items-center p-3 rounded-lg hover:bg-gray-700 <?php echo e(request()->routeIs('admin.orders*') ? 'bg-gray-700 text-indigo-400 active' : 'text-gray-300'); ?>">
                                <i class="fas fa-shopping-cart mr-3 w-5"></i>
                                <span class="sidebar-text">Orders</span>
                            </a>
                            <a href="<?php echo e(route('admin.orders.export')); ?>" 
                               class="admin-link flex items-center p-3 rounded-lg hover:bg-gray-700 text-gray-300">
                                <i class="fas fa-download mr-3 w-5"></i>
                                <span class="sidebar-text">Export</span>
                            </a>
                        </div>
                    </div>

                    <!-- Users Section -->
                    <div class="pt-4 nav-section">
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Users</h3>
                        <div class="space-y-1">
                            <a href="<?php echo e(route('admin.users.index')); ?>" 
                               class="admin-link flex items-center p-3 rounded-lg hover:bg-gray-700 <?php echo e(request()->routeIs('admin.users*') ? 'bg-gray-700 text-indigo-400 active' : 'text-gray-300'); ?>">
                                <i class="fas fa-users mr-3 w-5"></i>
                                <span class="sidebar-text">Users</span>
                            </a>
                            <a href="<?php echo e(route('admin.users.create')); ?>" 
                               class="admin-link flex items-center p-3 rounded-lg hover:bg-gray-700 text-gray-300">
                                <i class="fas fa-user-plus mr-3 w-5"></i>
                                <span class="sidebar-text">Add User</span>
                            </a>
                        </div>
                    </div>

                    <!-- Reports Section -->
                    <div class="pt-4 nav-section">
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Reports</h3>
                        <div class="space-y-1">
                            <a href="<?php echo e(route('admin.orders.statistics')); ?>" class="admin-link flex items-center p-3 rounded-lg hover:bg-gray-700 text-gray-300">
                                <i class="fas fa-chart-bar mr-3 w-5"></i>
                                <span class="sidebar-text">Analytics</span>
                            </a>
                            <a href="<?php echo e(route('admin.orders.export')); ?>" class="admin-link flex items-center p-3 rounded-lg hover:bg-gray-700 text-gray-300">
                                <i class="fas fa-file-alt mr-3 w-5"></i>
                                <span class="sidebar-text">Sales Reports</span>
                            </a>
                        </div>
                    </div>

                    <!-- Settings Section -->
                    <div class="pt-4 nav-section">
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">System</h3>
                        <div class="space-y-1">
                            <a href="#" class="admin-link flex items-center p-3 rounded-lg hover:bg-gray-700 text-gray-300">
                                <i class="fas fa-cog mr-3 w-5"></i>
                                <span class="sidebar-text">Settings</span>
                            </a>
                            <a href="#" class="admin-link flex items-center p-3 rounded-lg hover:bg-gray-700 text-gray-300">
                                <i class="fas fa-server mr-3 w-5"></i>
                                <span class="sidebar-text">System Status</span>
                            </a>
                        </div>
                    </div>
                </nav>

                <!-- User Profile Section -->
                <div class="p-4 border-t border-gray-700">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-indigo-600 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-white"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-white"><?php echo e(Auth::user()->name); ?></p>
                            <p class="text-xs text-gray-400">Administrator</p>
                        </div>
                        <form action="<?php echo e(route('logout')); ?>" method="POST" class="inline">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="text-gray-400 hover:text-white transition-colors">
                                <i class="fas fa-sign-out-alt"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Header -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">
                                <?php echo $__env->yieldContent('page-title', 'Dashboard'); ?>
                            </h1>
                            <p class="text-sm text-gray-500 mt-1">Welcome back, <?php echo e(Auth::user()->name); ?></p>
                        </div>

                        <!-- Right Actions -->
                        <div class="flex items-center space-x-4">
                            <!-- Search -->
                            <div class="hidden md:block">
                                <div class="relative">
                                    <input type="text" placeholder="Search..." class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                </div>
                            </div>

                            <!-- Notifications -->
                            <button class="relative p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors">
                                <i class="fas fa-bell text-xl"></i>
                                <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                            </button>

                            <!-- Settings -->
                            <button class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors">
                                <i class="fas fa-cog text-xl"></i>
                            </button>
                            
                            <!-- Dark Mode Toggle -->
                            <button onclick="toggleDarkMode()" class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors">
                                <i class="fas fa-moon text-xl"></i>
                            </button>

                            <!-- User Menu -->
                            <div class="relative">
                                <button onclick="toggleUserMenu()" class="flex items-center space-x-2 p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors">
                                    <div class="w-8 h-8 bg-indigo-600 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-white text-sm"></i>
                                    </div>
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </button>

                                <!-- Dropdown Menu -->
                                <div id="userDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                                    <div class="p-4 border-b border-gray-100">
                                        <p class="text-sm font-medium text-gray-900"><?php echo e(Auth::user()->name); ?></p>
                                        <p class="text-xs text-gray-500"><?php echo e(Auth::user()->email); ?></p>
                                    </div>
                                    <div class="py-2">
                                        <a href="<?php echo e(route('admin.users.profile', Auth::user())); ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-user mr-2"></i>
                                            Profile
                                        </a>
                                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-cog mr-2"></i>
                                            Settings
                                        </a>
                                        <form action="<?php echo e(route('logout')); ?>" method="POST">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                                <i class="fas fa-sign-out-alt mr-2"></i>
                                                Logout
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto bg-gray-50 p-6">
                <!-- Flash Messages -->
                <?php if(session()->has('success')): ?>
                    <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            <span class="font-medium"><?php echo e(session('success')); ?></span>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if(session()->has('error')): ?>
                    <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <span class="font-medium"><?php echo e(session('error')); ?></span>
                        </div>
                    </div>
                <?php endif; ?>

                <?php echo $__env->yieldContent('content'); ?>
            </main>
        </div>
    </div>

    <?php $__env->startSection('scripts'); ?>
    <script>
        function toggleUserMenu() {
            const dropdown = document.getElementById('userDropdown');
            dropdown.classList.toggle('hidden');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('userDropdown');
            const button = event.target.closest('button[onclick="toggleUserMenu()"]');
            
            if (!dropdown.contains(event.target) && !button) {
                dropdown.classList.add('hidden');
            }
        });

        // Dark mode toggle
        function toggleDarkMode() {
            document.body.classList.toggle('dark');
            localStorage.setItem('darkMode', document.body.classList.contains('dark'));
        }

        // Load dark mode preference
        if (localStorage.getItem('darkMode') === 'true') {
            document.body.classList.add('dark');
        }

        // Mobile sidebar toggle
        function toggleSidebar() {
            const sidebar = document.querySelector('aside');
            sidebar.classList.toggle('sidebar-collapsed');
        }

        // Auto-hide flash messages
        setTimeout(() => {
            const flashMessages = document.querySelectorAll('[class*="bg-green-50"], [class*="bg-red-50"], [class*="bg-blue-50"]');
            flashMessages.forEach(msg => {
                msg.style.transition = 'opacity 0.5s';
                msg.style.opacity = '0';
                setTimeout(() => msg.remove(), 500);
            });
        }, 5000);
    </script>
    <?php $__env->stopSection(); ?>
</body>
</html><?php /**PATH C:\Users\habib\Downloads\laravel-ecom\laravel-ecom\resources\views/layouts/admin.blade.php ENDPATH**/ ?>