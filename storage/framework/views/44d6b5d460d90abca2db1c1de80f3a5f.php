<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Admin Panel'); ?> - ShopHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Enhanced Sidebar Styling */
        .admin-sidebar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .admin-link {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            border-radius: 12px;
            overflow: hidden;
        }
        
        .admin-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: left 0.5s;
        }
        
        .admin-link:hover::before {
            left: 100%;
        }
        
        .admin-link:hover {
            transform: translateX(8px);
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }
        
        .admin-link.active {
            background: rgba(255, 255, 255, 0.15);
            color: #fbbf24;
            box-shadow: 0 4px 15px rgba(251, 191, 36, 0.3);
        }
        
        .admin-link.active::after {
            content: '';
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 24px;
            background: #fbbf24;
            border-radius: 4px 0 0 4px;
            box-shadow: 0 0 10px rgba(251, 191, 36, 0.5);
        }
        
        /* Enhanced Cards */
        .stat-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid transparent;
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            position: relative;
            overflow: hidden;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(99, 102, 241, 0.05), transparent);
            transition: left 0.6s;
        }
        
        .stat-card:hover::before {
            left: 100%;
        }
        
        .stat-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 25px 50px -12px rgba(99, 102, 241, 0.25);
            border-color: #6366f1;
        }
        
        /* Mobile Sidebar */
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
        
        /* Enhanced Animations */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.7;
            }
        }
        
        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-10px);
            }
        }
        
        .animate-slide-in {
            animation: slideIn 0.6s ease-out;
        }
        
        .animate-pulse-slow {
            animation: pulse 3s infinite;
        }
        
        .animate-float {
            animation: float 4s ease-in-out infinite;
        }
        
        /* Premium Buttons */
        .premium-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .premium-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }
        
        .premium-btn:hover::before {
            left: 100%;
        }
        
        .premium-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%);
        }
        
        /* Glassmorphism effect */
        .glass {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="admin-sidebar w-64 min-h-screen shadow-2xl">
            <div class="flex flex-col h-full">
                <!-- Logo Section -->
                <div class="p-6 border-b border-white/20 sidebar-header">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg animate-float">
                            <i class="fas fa-crown text-2xl text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-indigo-600"></i>
                        </div>
                        <div class="sidebar-text">
                            <h1 class="text-2xl font-bold text-white">ShopHub</h1>
                            <p class="text-xs text-white/80">Premium Admin</p>
                        </div>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
                    <!-- Dashboard -->
                    <a href="<?php echo e(route('admin.dashboard')); ?>" 
                       class="admin-link flex items-center p-3 rounded-lg text-white/90 hover:text-white <?php echo e(request()->routeIs('admin.dashboard*') ? 'active' : ''); ?>">
                        <i class="fas fa-tachometer-alt mr-3 w-5"></i>
                        <span class="sidebar-text">Dashboard</span>
                    </a>

                    <!-- Catalog Section -->
                    <div class="pt-4 nav-section">
                        <h3 class="text-xs font-semibold text-white/60 uppercase tracking-wider mb-2">Catalog</h3>
                        <div class="space-y-1">
                            <a href="<?php echo e(route('admin.products.index')); ?>" 
                               class="admin-link flex items-center p-3 rounded-lg text-white/90 hover:text-white <?php echo e(request()->routeIs('admin.products*') ? 'active' : ''); ?>">
                                <i class="fas fa-box mr-3 w-5"></i>
                                <span class="sidebar-text">Products</span>
                            </a>
                            <a href="<?php echo e(route('admin.categories.index')); ?>" 
                               class="admin-link flex items-center p-3 rounded-lg text-white/90 hover:text-white <?php echo e(request()->routeIs('admin.categories*') ? 'active' : ''); ?>">
                                <i class="fas fa-tags mr-3 w-5"></i>
                                <span class="sidebar-text">Categories</span>
                            </a>
                        </div>
                    </div>

                    <!-- Orders Section -->
                    <div class="pt-4 nav-section">
                        <h3 class="text-xs font-semibold text-white/60 uppercase tracking-wider mb-2">Orders</h3>
                        <div class="space-y-1">
                            <a href="<?php echo e(route('admin.orders.index')); ?>" 
                               class="admin-link flex items-center p-3 rounded-lg text-white/90 hover:text-white <?php echo e(request()->routeIs('admin.orders*') ? 'active' : ''); ?>">
                                <i class="fas fa-shopping-cart mr-3 w-5"></i>
                                <span class="sidebar-text">Orders</span>
                            </a>
                            <a href="<?php echo e(route('admin.orders.export')); ?>" 
                               class="admin-link flex items-center p-3 rounded-lg text-white/90 hover:text-white">
                                <i class="fas fa-download mr-3 w-5"></i>
                                <span class="sidebar-text">Export</span>
                            </a>
                        </div>
                    </div>

                    <!-- Users Section -->
                    <div class="pt-4 nav-section">
                        <h3 class="text-xs font-semibold text-white/60 uppercase tracking-wider mb-2">Users</h3>
                        <div class="space-y-1">
                            <a href="<?php echo e(route('admin.users.index')); ?>" 
                               class="admin-link flex items-center p-3 rounded-lg text-white/90 hover:text-white <?php echo e(request()->routeIs('admin.users*') ? 'active' : ''); ?>">
                                <i class="fas fa-users mr-3 w-5"></i>
                                <span class="sidebar-text">Users</span>
                            </a>
                            <a href="<?php echo e(route('admin.users.create')); ?>" 
                               class="admin-link flex items-center p-3 rounded-lg text-white/90 hover:text-white">
                                <i class="fas fa-user-plus mr-3 w-5"></i>
                                <span class="sidebar-text">Add User</span>
                            </a>
                        </div>
                    </div>

                    <!-- Reports Section -->
                    <div class="pt-4 nav-section">
                        <h3 class="text-xs font-semibold text-white/60 uppercase tracking-wider mb-2">Reports</h3>
                        <div class="space-y-1">
                            <a href="<?php echo e(route('admin.orders.statistics')); ?>" class="admin-link flex items-center p-3 rounded-lg text-white/90 hover:text-white">
                                <i class="fas fa-chart-bar mr-3 w-5"></i>
                                <span class="sidebar-text">Analytics</span>
                            </a>
                            <a href="<?php echo e(route('admin.orders.export')); ?>" class="admin-link flex items-center p-3 rounded-lg text-white/90 hover:text-white">
                                <i class="fas fa-file-alt mr-3 w-5"></i>
                                <span class="sidebar-text">Sales Reports</span>
                            </a>
                        </div>
                    </div>

                    <!-- Settings Section -->
                    <div class="pt-4 nav-section">
                        <h3 class="text-xs font-semibold text-white/60 uppercase tracking-wider mb-2">System</h3>
                        <div class="space-y-1">
                            <a href="#" class="admin-link flex items-center p-3 rounded-lg text-white/90 hover:text-white">
                                <i class="fas fa-cog mr-3 w-5"></i>
                                <span class="sidebar-text">Settings</span>
                            </a>
                            <a href="#" class="admin-link flex items-center p-3 rounded-lg text-white/90 hover:text-white">
                                <i class="fas fa-server mr-3 w-5"></i>
                                <span class="sidebar-text">System Status</span>
                            </a>
                        </div>
                    </div>
                </nav>

                <!-- User Profile Section -->
                <div class="p-4 border-t border-white/20 glass">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-full flex items-center justify-center shadow-lg">
                            <i class="fas fa-user text-white text-lg"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-white"><?php echo e(Auth::user()->name); ?></p>
                            <p class="text-xs text-white/80">Administrator</p>
                        </div>
                        <form action="<?php echo e(route('logout')); ?>" method="POST" class="inline">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="text-white/80 hover:text-white transition-all duration-300 hover:scale-110">
                                <i class="fas fa-sign-out-alt"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden main-content transition-all duration-300 lg:ml-20">
            <!-- Top Header -->
            <header class="bg-white/90 backdrop-blur-md shadow-lg border-b border-white/20 sticky top-0 z-40">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-3xl font-bold bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent">
                                <?php echo $__env->yieldContent('page-title', 'Dashboard'); ?>
                            </h1>
                            <p class="text-sm text-gray-600 mt-1">Welcome back, <span class="font-medium text-gray-800"><?php echo e(Auth::user()->name); ?></span> 👋</p>
                        </div>

                        <!-- Right Actions -->
                        <div class="flex items-center space-x-4">
                            <!-- Search -->
                            <div class="hidden md:block">
                                <div class="relative">
                                    <input type="text" placeholder="Search anything..." class="w-64 pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent bg-gray-50/50 backdrop-blur-sm transition-all duration-300">
                                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                </div>
                            </div>

                            <!-- Notifications -->
                            <button class="relative p-2.5 text-gray-600 hover:text-purple-600 hover:bg-purple-50 rounded-xl transition-all duration-300 group">
                                <i class="fas fa-bell text-xl group-hover:scale-110 transition-transform"></i>
                                <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full animate-pulse-slow"></span>
                            </button>

                            <!-- Settings -->
                            <button class="p-2.5 text-gray-600 hover:text-purple-600 hover:bg-purple-50 rounded-xl transition-all duration-300 group">
                                <i class="fas fa-cog text-xl group-hover:rotate-90 transition-transform duration-300"></i>
                            </button>
                            
                            <!-- Dark Mode Toggle -->
                <!-- Mobile Menu Toggle -->
                <button onclick="toggleSidebar()" class="lg:hidden p-2.5 text-gray-600 hover:text-purple-600 hover:bg-purple-50 rounded-xl transition-all duration-300">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                
                <button onclick="toggleDarkMode()" class="p-2.5 text-gray-600 hover:text-purple-600 hover:bg-purple-50 rounded-xl transition-all duration-300 group">
                    <i class="fas fa-moon text-xl group-hover:scale-110 transition-transform"></i>
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
            <main class="flex-1 overflow-y-auto bg-gradient-to-br from-gray-50/50 to-white/50 backdrop-blur-sm p-6">
                <!-- Flash Messages -->
                <?php if(session()->has('success')): ?>
                    <div class="mb-6 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 text-green-700 px-6 py-4 rounded-xl shadow-lg animate-slide-in">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-check text-white text-sm"></i>
                            </div>
                            <span class="font-medium"><?php echo e(session('success')); ?></span>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if(session()->has('error')): ?>
                    <div class="mb-6 bg-gradient-to-r from-red-50 to-rose-50 border border-red-200 text-red-700 px-6 py-4 rounded-xl shadow-lg animate-slide-in">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-exclamation text-white text-sm"></i>
                            </div>
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
            const mainContent = document.querySelector('.main-content');
            
            if (window.innerWidth < 1024) {
                sidebar.classList.toggle('hidden');
                sidebar.classList.toggle('fixed');
                sidebar.classList.toggle('inset-0');
                sidebar.classList.toggle('z-50');
            } else {
                sidebar.classList.toggle('sidebar-collapsed');
                mainContent.classList.toggle('ml-64');
                mainContent.classList.toggle('ml-20');
            }
        }
        
        // Mobile responsive handling
        function handleResponsive() {
            const sidebar = document.querySelector('aside');
            const mainContent = document.querySelector('.main-content');
            
            if (window.innerWidth < 1024) {
                sidebar.classList.add('hidden');
                sidebar.classList.remove('sidebar-collapsed');
                mainContent.classList.remove('ml-64', 'ml-20');
            } else {
                sidebar.classList.remove('hidden', 'fixed', 'inset-0', 'z-50');
                sidebar.classList.add('sidebar-collapsed');
                mainContent.classList.add('ml-20');
            }
        }
        
        // Initialize responsive handling
        document.addEventListener('DOMContentLoaded', handleResponsive);
        window.addEventListener('resize', handleResponsive);

        // Auto-hide flash messages with enhanced animation
        setTimeout(() => {
            const flashMessages = document.querySelectorAll('[class*="bg-green-50"], [class*="bg-red-50"], [class*="bg-blue-50"]');
            flashMessages.forEach(msg => {
                msg.style.transition = 'all 0.5s ease-out';
                msg.style.transform = 'translateX(100px)';
                msg.style.opacity = '0';
                setTimeout(() => msg.remove(), 500);
            });
        }, 5000);
    </script>
    <?php $__env->stopSection(); ?>
</body>
</html><?php /**PATH C:\Users\habib\Downloads\Ecommerce-main\Ecommerce-main\resources\views/layouts/admin.blade.php ENDPATH**/ ?>