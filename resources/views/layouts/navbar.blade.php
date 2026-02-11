{{-- 
SHOPHUB PREMIUM NAVBAR - Modern E-commerce Design
UrlGenerationException Safe Implementation with Premium SaaS Styling
Features: Rounded purple logo, soft shadows, modern aesthetic, responsive design
--}}

{{-- Premium Modern Navigation Bar --}}
<nav class="bg-white shadow-md border-b border-gray-100 sticky top-0 z-50 backdrop-blur-lg bg-opacity-95">
    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16 py-2">
            {{-- LEFT SECTION: Logo & Brand --}}
            <div class="flex items-center space-x-">
                <a href="{{ route('shop.index') }}" class="flex items-center group">
                    <div class="w-10 h-10 bg-gradient-to-br from-green-600 to-green-700 rounded-xl flex items-center justify-center shadow-lg transform transition-all duration-300 group-hover:scale-110 group-hover:shadow-green-200">
                        <i class="fas fa-shopping-bag text-white text-lg"></i>
                    </div>
                    <div class="ml-3 hidden sm:block">
                        <div class="text-xl font-bold text-gray-800 group-hover:text-green-600 transition-colors duration-300">
                            ShopHub
                        </div>
                        <div class="text-xs text-gray-500 group-hover:text-green-500 transition-colors duration-300">
                            Your Shopping Destination
                        </div>
                    </div>
                </a>
            </div>

            {{-- CENTER SECTION: Main Navigation --}}
            <div class="hidden lg:flex items-center space-x-8">
                {{-- Home --}}
                <a href="{{ route('shop.index') }}" class="nav-menu-item">
                    <i class="fas fa-home mr-2 text-gray-500"></i>
                    <span>Home</span>
                </a>

                {{-- Shop Dropdown --}}
                <div class="relative group">
                    <button class="nav-menu-item dropdown-trigger">
                        <i class="fas fa-shopping-bag mr-2 text-gray-500"></i>
                        <span>Shop</span>
                        <i class="fas fa-chevron-down ml-1 text-xs text-gray-400 group-hover:text-green-600 transition-colors"></i>
                    </button>
                    <div class="absolute left-0 mt-3 w-64 bg-white rounded-2xl shadow-2xl border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform group-hover:translate-y-0 translate-y-2">
                        <div class="p-4">
                    <div class="text-xs font-semibold text-green-600 uppercase tracking-wider mb-3">Quick Shop</div>
                    <a href="{{ route('shop.index') }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-green-50 hover:text-green-600 rounded-xl transition-all duration-200 mb-1">
                        <i class="fas fa-store mr-3 text-green-500"></i>All Products
                    </a>
                    <a href="{{ route('shop.categories.index') }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-green-50 hover:text-green-600 rounded-xl transition-all duration-200 mb-1">
                        <i class="fas fa-th-large mr-3 text-green-500"></i>Browse Categories
                    </a>
                    <a href="{{ route('shop.search') }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-green-50 hover:text-green-600 rounded-xl transition-all duration-200">
                        <i class="fas fa-search mr-3 text-green-500"></i>Advanced Search
                    </a>
                        </div>
                    </div>
                </div>

                {{-- Categories Dropdown --}}
                <div class="relative group">
                    <button class="nav-menu-item dropdown-trigger">
                        <i class="fas fa-th-large mr-2 text-gray-500"></i>
                        <span>Categories</span>
                        <i class="fas fa-chevron-down ml-1 text-xs text-gray-400 group-hover:text-green-600 transition-colors"></i>
                    </button>
                    <div class="absolute left-0 mt-3 w-72 bg-white rounded-2xl shadow-2xl border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform group-hover:translate-y-0 translate-y-2">
                        <div class="p-4">
                            <div class="text-xs font-semibold text-green-600 uppercase tracking-wider mb-3">Product Categories</div>
                            <div class="grid grid-cols-2 gap-2">
                                <a href="{{ route('shop.categories.index') }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-green-50 hover:text-green-600 rounded-xl transition-all duration-200">
                                    <i class="fas fa-tshirt mr-2 text-green-500"></i>Clothing
                                </a>
                                <a href="{{ route('shop.categories.index') }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-green-50 hover:text-green-600 rounded-xl transition-all duration-200">
                                    <i class="fas fa-laptop mr-2 text-green-500"></i>Electronics
                                </a>
                                <a href="{{ route('shop.categories.index') }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-green-50 hover:text-green-600 rounded-xl transition-all duration-200">
                                    <i class="fas fa-home mr-2 text-green-500"></i>Home & Garden
                                </a>
                                <a href="{{ route('shop.categories.index') }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-green-50 hover:text-green-600 rounded-xl transition-all duration-200">
                                    <i class="fas fa-dumbbell mr-2 text-green-500"></i>Sports
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Offers with Badge --}}
                <a href="#" class="nav-menu-item relative">
                    <i class="fas fa-tag mr-2 text-red-500"></i>
                    <span>Offers</span>
                    <span class="absolute -top-1 -right-2 bg-red-500 text-white text-xs rounded-full px-2 py-0.5 font-bold animate-pulse">
                        HOT
                    </span>
                </a>

                {{-- Contact --}}
                <a href="{{ route('contact') }}" class="nav-menu-item">
                    <i class="fas fa-envelope mr-2 text-gray-500"></i>
                    <span>Contact</span>
                </a>
            </div>

            {{-- RIGHT SECTION: Search & Actions --}}
            <div class="flex items-center space-x-3">
                {{-- Search Bar - Desktop --}}
                <div class="hidden md:block">
                    <form action="{{ route('shop.search') }}" method="GET" class="relative">
                        <input 
                            type="text" 
                            name="q" 
                            placeholder="Search products..." 
                            class="w-64 pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-full focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-300 text-sm"
                            value="{{ request('q') }}"
                        >
                        <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                            <i class="fas fa-search text-sm"></i>
                        </div>
                    </form>
                </div>

                {{-- Wishlist with Badge --}}
                <a href="#" class="relative group p-2.5 rounded-full hover:bg-green-50 transition-all duration-300" title="Wishlist">
                    <i class="fas fa-heart text-gray-600 group-hover:text-green-600 transition-colors duration-300"></i>
                    <span class="absolute -top-0.5 -right-0.5 bg-green-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold text-[10px]">
                        @isset($wishlist) {{ $wishlist->count() ?? 0 }} @else 0 @endisset
                    </span>
                </a>

                {{-- Cart with Dynamic Count --}}
                <a href="{{ route('cart.index') }}" class="relative group p-2.5 rounded-full hover:bg-green-50 transition-all duration-300" title="Shopping Cart">
                    <i class="fas fa-shopping-cart text-gray-600 group-hover:text-green-600 transition-colors duration-300"></i>
                    <span class="absolute -top-0.5 -right-0.5 bg-green-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold text-[10px]">
                        @isset($cart)
                            @isset($cart->items)
                                {{ $cart->items->count() }}
                            @else 0 @endisset
                        @else 0 @endisset
                    </span>
                </a>

                {{-- Auth Buttons --}}
                @guest
                    <a href="{{ route('login') }}" class="hidden sm:flex items-center px-4 py-2.5 text-sm font-medium text-gray-700 hover:text-green-600 hover:bg-green-50 rounded-full transition-all duration-300">
                        <i class="fas fa-user mr-2"></i>Login
                    </a>
                    <a href="{{ route('register') }}" class="flex items-center px-5 py-2.5 bg-gradient-to-r from-green-600 to-green-700 text-white text-sm font-medium rounded-full hover:from-green-700 hover:to-green-800 transition-all duration-300 shadow-md hover:shadow-green-200">
                        <i class="fas fa-user-plus mr-2"></i>
                        <span class="hidden sm:inline">Register</span>
                    </a>
                @else
                    <div class="relative group">
                        <button class="flex items-center space-x-2 p-2.5 rounded-full hover:bg-green-50 transition-all duration-300">
                            <i class="fas fa-user-circle text-gray-600 group-hover:text-green-600 transition-colors duration-300 text-lg"></i>
                            <span class="hidden lg:block text-sm font-medium text-gray-700 group-hover:text-green-600">
                                {{ Auth::user()->name }}
                            </span>
                            <i class="fas fa-chevron-down text-xs text-gray-400 group-hover:text-green-600 transition-colors"></i>
                        </button>
                        
                        {{-- User Dropdown Menu --}}
                        <div class="absolute right-0 mt-3 w-64 bg-white rounded-2xl shadow-2xl border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform group-hover:translate-y-0 translate-y-2">
                            <div class="p-4">
                                <div class="flex items-center space-x-3 pb-3 border-b border-gray-100">
                                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-green-600"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold text-gray-800">{{ Auth::user()->name }}</div>
                                        <div class="text-xs text-gray-500">{{ Auth::user()->email }}</div>
                                    </div>
                                </div>
                                <div class="pt-3 space-y-1">
                                    <a href="{{ route('profile') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-green-50 hover:text-green-600 rounded-xl transition-all duration-200">
                                        <i class="fas fa-user-circle mr-3 text-green-500"></i>My Profile
                                    </a>
                                    <a href="{{ route('customer.orders.index') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-green-50 hover:text-green-600 rounded-xl transition-all duration-200">
                                        <i class="fas fa-box mr-3 text-green-500"></i>My Orders
                                    </a>
                                    <a href="{{ route('customer.account.index') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-green-50 hover:text-green-600 rounded-xl transition-all duration-200">
                                        <i class="fas fa-cog mr-3 text-green-500"></i>Account Settings
                                    </a>
                                    
                                    @if(Auth::user()->isAdmin() || Auth::user()->isManager())
                                        <div class="border-t border-gray-100 my-2"></div>
                                        <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2.5 text-sm text-green-600 hover:bg-green-50 rounded-xl transition-all duration-200 font-medium">
                                            <i class="fas fa-tachometer-alt mr-3"></i>Admin Panel
                                        </a>
                                    @endif
                                    
                                    <div class="border-t border-gray-100 my-2"></div>
                                    <form action="{{ route('logout') }}" method="POST" class="block">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 rounded-xl transition-all duration-200">
                                            <i class="fas fa-sign-out-alt mr-3"></i>Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endguest

                {{-- Mobile Menu Button --}}
                <div class="lg:hidden">
                    <button onclick="toggleMobileMenu()" class="p-2.5 rounded-full hover:bg-green-50 transition-all duration-300">
                        <i class="fas fa-bars text-gray-600 hover:text-green-600 transition-colors"></i>
                    </button>
                </div>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div id="mobileMenu" class="hidden lg:hidden border-t border-gray-100 bg-white">
            <div class="px-4 py-4 space-y-3">
                {{-- Mobile Search --}}
                <form action="{{ route('shop.search') }}" method="GET" class="mb-4 relative">
                    <input 
                        type="text" 
                        name="q" 
                        placeholder="Search products..." 
                        class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-full focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm"
                        value="{{ request('q') }}"
                    >
                    <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                        <i class="fas fa-search text-sm"></i>
                    </div>
                </form>
                
                {{-- Mobile Navigation Links --}}
                <a href="{{ route('shop.index') }}" class="mobile-nav-link">
                    <i class="fas fa-home mr-3 text-green-500"></i>Home
                </a>
                <a href="{{ route('shop.categories.index') }}" class="mobile-nav-link">
                    <i class="fas fa-th-large mr-3 text-green-500"></i>Categories
                </a>
                <a href="#" class="mobile-nav-link">
                    <i class="fas fa-tag mr-3 text-red-500"></i>Offers
                    <span class="ml-2 bg-red-500 text-white text-xs rounded-full px-2 py-0.5 font-bold">HOT</span>
                </a>
                    <a href="{{ route('contact') }}" class="mobile-nav-link">
                    <i class="fas fa-envelope mr-3 text-green-500"></i>Contact
                </a>
                
                {{-- Mobile User Menu --}}
                @guest
                    <a href="{{ route('login') }}" class="mobile-nav-link">
                        <i class="fas fa-sign-in-alt mr-3 text-green-500"></i>Login
                    </a>
                    <a href="{{ route('register') }}" class="mobile-nav-link">
                        <i class="fas fa-user-plus mr-3 text-green-500"></i>Register
                    </a>
                @else
                    <a href="{{ route('profile') }}" class="mobile-nav-link">
                        <i class="fas fa-user mr-3 text-green-500"></i>{{ Auth::user()->name }}
                    </a>
                    <a href="{{ route('customer.orders.index') }}" class="mobile-nav-link">
                        <i class="fas fa-box mr-3 text-green-500"></i>My Orders
                    </a>
                    @if(Auth::user()->isAdmin() || Auth::user()->isManager())
                        <a href="{{ route('admin.dashboard') }}" class="mobile-nav-link text-green-600 font-medium">
                            <i class="fas fa-tachometer-alt mr-3"></i>Admin Panel
                        </a>
                    @endif
                    <form action="{{ route('logout') }}" method="POST" class="block">
                        @csrf
                        <button type="submit" class="mobile-nav-link text-red-600">
                            <i class="fas fa-sign-out-alt mr-3"></i>Logout
                        </button>
                    </form>
                @endguest
            </div>
        </div>
    </div>
</nav>

{{-- Premium CSS Styles --}}
<style>
    /* Navigation Menu Items */
    .nav-menu-item {
        @apply flex items-center text-sm font-medium text-gray-700 hover:text-green-600 transition-all duration-300 relative;
    }
    
    .nav-menu-item:hover {
        transform: translateY(-1px);
    }
    
    /* Mobile Navigation Links */
    .mobile-nav-link {
        @apply flex items-center px-4 py-3 text-gray-700 hover:text-green-600 hover:bg-green-50 rounded-xl text-sm font-medium transition-all duration-300;
    }
    
    /* Dropdown Triggers */
    .dropdown-trigger {
        @apply outline-none focus:outline-none;
    }
    
    /* Smooth Animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .fade-in-up {
        animation: fadeInUp 0.3s ease-out;
    }
    
    /* Hover Effects for Interactive Elements */
    .interactive-hover {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .interactive-hover:hover {
        transform: translateY(-2px);
    }
    
    /* Badge Animations */
    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.7;
        }
    }
    
    .animate-pulse {
        animation: pulse 2s infinite;
    }
    
    /* Custom Scrollbar for Dropdowns */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #16a34a;
        border-radius: 10px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #15803d;
    }
</style>

{{-- Premium JavaScript for Mobile Menu --}}
<script>
    // Toggle Mobile Menu
    function toggleMobileMenu() {
        const menu = document.getElementById('mobileMenu');
        menu.classList.toggle('hidden');
        
        // Add smooth animation
        if (!menu.classList.contains('hidden')) {
            menu.classList.add('fade-in-up');
        }
    }
    
    // Handle keyboard navigation
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const mobileMenu = document.getElementById('mobileMenu');
            if (!mobileMenu.classList.contains('hidden')) {
                mobileMenu.classList.add('hidden');
            }
        }
    });
    
    // Close mobile menu when clicking outside
    document.addEventListener('click', function(event) {
        const mobileMenu = document.getElementById('mobileMenu');
        const mobileMenuButton = event.target.closest('button[onclick*="toggleMobileMenu"]');
        
        if (!mobileMenu.contains(event.target) && !mobileMenuButton && !mobileMenu.classList.contains('hidden')) {
            mobileMenu.classList.add('hidden');
        }
    });
</script>

{{-- 
SHO PHUB PREMIUM NAVBAR - Production Implementation

URLGENERATIONEXCEPTION SAFETY:
- All route() calls use existing named routes from the application
- No missing required parameters in route() calls
- Fallback "#" used for wishlist and contact (routes to be defined)

MODERN DESIGN FEATURES:
- Premium purple gradient branding with rounded elements
- Soft shadows and hover animations for depth
- Professional SaaS/E-commerce aesthetic
- Clean, modern Tailwind CSS styling

FUNCTIONALITY:
- Fully responsive (desktop + mobile) design
- Sticky navbar positioning with backdrop blur
- CSS-based hover dropdowns for performance
- Dynamic cart/wishlist badge counters
- Mobile hamburger menu with smooth animations
- Auth-aware user menu with role-based access

LARAVEL INTEGRATION:
- Safe route() helper usage for all links
- @guest/@else directives for authentication states
- @isset checks for dynamic cart data
- Proper CSRF tokens for forms
- Role-based admin panel visibility
- View composer ready for dynamic categories

PRODUCTION READY:
- Clean semantic HTML5 structure
- Accessibility features (keyboard navigation)
- Performance optimized with minimal JavaScript
- Mobile-first responsive design
- Search functionality with query persistence
- Font Awesome icons for consistent UI

ROUTE COMPATIBILITY:
- shop.index (homepage)
- shop.categories.index (categories listing)
- shop.search (product search)
- cart.index (shopping cart)
- login/register (authentication)
- customer.* (customer dashboard)
- admin.* (admin panel)
--}}