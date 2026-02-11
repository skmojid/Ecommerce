@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 animate-slide-in">
    <!-- Revenue -->
    <div class="stat-card bg-gradient-to-br from-white to-emerald-50 rounded-2xl shadow-lg border border-emerald-100 p-6 relative overflow-hidden group">
        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-emerald-100 to-transparent rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500"></div>
        <div class="flex items-center justify-between relative">
            <div>
                <p class="text-sm font-semibold text-gray-700 flex items-center">
                    <i class="fas fa-dollar-sign mr-2 text-emerald-500"></i>
                    Total Revenue
                </p>
                <p class="text-3xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent mt-2">${{ number_format($stats['total_revenue'] ?? 45231, 2) }}</p>
            </div>
            <div class="w-16 h-16 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-2xl flex items-center justify-center shadow-xl group-hover:scale-110 group-hover:rotate-12 transition-all duration-300">
                <i class="fas fa-chart-line text-white text-xl"></i>
            </div>
        </div>
        <div class="flex items-center mt-4">
            <span class="text-sm text-emerald-700 bg-emerald-100 px-3 py-1 rounded-full font-semibold">
                <i class="fas fa-arrow-up mr-1"></i>
                +12.5%
            </span>
            <span class="text-sm text-gray-500 ml-2">vs last month</span>
        </div>
    </div>

    <!-- Orders -->
    <div class="stat-card bg-gradient-to-br from-white to-blue-50 rounded-2xl shadow-lg border border-blue-100 p-6 relative overflow-hidden group">
        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-100 to-transparent rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500"></div>
        <div class="flex items-center justify-between relative">
            <div>
                <p class="text-sm font-semibold text-gray-700 flex items-center">
                    <i class="fas fa-shopping-bag mr-2 text-blue-500"></i>
                    Total Orders
                </p>
                <p class="text-3xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent mt-2">{{ number_format($stats['total_orders'] ?? 1234) }}</p>
            </div>
            <div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl flex items-center justify-center shadow-xl group-hover:scale-110 group-hover:rotate-12 transition-all duration-300">
                <i class="fas fa-shopping-cart text-white text-xl"></i>
            </div>
        </div>
        <div class="flex items-center mt-4">
            <span class="text-sm text-blue-700 bg-blue-100 px-3 py-1 rounded-full font-semibold">
                <i class="fas fa-arrow-up mr-1"></i>
                +8.2%
            </span>
            <span class="text-sm text-gray-500 ml-2">vs last month</span>
        </div>
    </div>

    <!-- Customers -->
    <div class="stat-card bg-gradient-to-br from-white to-purple-50 rounded-2xl shadow-lg border border-purple-100 p-6 relative overflow-hidden group">
        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-purple-100 to-transparent rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500"></div>
        <div class="flex items-center justify-between relative">
            <div>
                <p class="text-sm font-semibold text-gray-700 flex items-center">
                    <i class="fas fa-users mr-2 text-purple-500"></i>
                    Total Customers
                </p>
                <p class="text-3xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent mt-2">{{ number_format($stats['total_customers'] ?? 0) }}</p>
            </div>
            <div class="w-16 h-16 bg-gradient-to-br from-purple-400 to-purple-600 rounded-2xl flex items-center justify-center shadow-xl group-hover:scale-110 group-hover:rotate-12 transition-all duration-300">
                <i class="fas fa-user-friends text-white text-xl"></i>
            </div>
        </div>
        <div class="flex items-center mt-4">
            <span class="text-sm text-purple-700 bg-purple-100 px-3 py-1 rounded-full font-semibold">
                <i class="fas fa-arrow-up mr-1"></i>
                +15.3%
            </span>
            <span class="text-sm text-gray-500 ml-2">vs last month</span>
        </div>
    </div>

    <!-- Products -->
    <div class="stat-card bg-gradient-to-br from-white to-orange-50 rounded-2xl shadow-lg border border-orange-100 p-6 relative overflow-hidden group">
        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-orange-100 to-transparent rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500"></div>
        <div class="flex items-center justify-between relative">
            <div>
                <p class="text-sm font-semibold text-gray-700 flex items-center">
                    <i class="fas fa-box mr-2 text-orange-500"></i>
                    Total Products
                </p>
                <p class="text-3xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent mt-2">{{ number_format($stats['total_products'] ?? 456) }}</p>
            </div>
            <div class="w-16 h-16 bg-gradient-to-br from-orange-400 to-orange-600 rounded-2xl flex items-center justify-center shadow-xl group-hover:scale-110 group-hover:rotate-12 transition-all duration-300">
                <i class="fas fa-cube text-white text-xl"></i>
            </div>
        </div>
        <div class="flex items-center mt-4">
            <span class="text-sm text-orange-700 bg-orange-100 px-3 py-1 rounded-full font-semibold">
                <i class="fas fa-plus mr-1"></i>
                +23 new
            </span>
            <span class="text-sm text-gray-500 ml-2">this month</span>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Sales Chart -->
    <div class="bg-gradient-to-br from-white to-indigo-50 rounded-2xl shadow-xl border border-indigo-100 p-6 animate-slide-in">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent flex items-center">
                <i class="fas fa-chart-line mr-2 text-indigo-500"></i>
                Sales Overview
            </h3>
            <select class="text-sm border border-indigo-200 rounded-xl px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white/80 backdrop-blur-sm">
                <option>Last 7 days</option>
                <option>Last 30 days</option>
                <option>Last 3 months</option>
            </select>
        </div>
        <div class="h-64 flex items-center justify-center bg-gradient-to-br from-indigo-50/50 to-purple-50/50 rounded-xl relative overflow-hidden">
            <!-- Simple SVG Chart -->
            <svg class="w-full h-full" viewBox="0 0 400 200">
                <!-- Grid lines -->
                <line x1="40" y1="20" x2="40" y2="160" stroke="#e0e7ff" stroke-width="1"/>
                <line x1="40" y1="160" x2="380" y2="160" stroke="#e0e7ff" stroke-width="1"/>
                
                <!-- Chart bars with gradient -->
                <defs>
                    <linearGradient id="barGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                        <stop offset="0%" style="stop-color:#6366f1;stop-opacity:1" />
                        <stop offset="100%" style="stop-color:#8b5cf6;stop-opacity:1" />
                    </linearGradient>
                </defs>
                
                <rect x="60" y="120" width="30" height="40" fill="url(#barGradient)" opacity="0.9" rx="6" class="hover:opacity-100 transition-opacity cursor-pointer"/>
                <rect x="100" y="100" width="30" height="60" fill="url(#barGradient)" opacity="0.9" rx="6" class="hover:opacity-100 transition-opacity cursor-pointer"/>
                <rect x="140" y="80" width="30" height="80" fill="url(#barGradient)" opacity="0.9" rx="6" class="hover:opacity-100 transition-opacity cursor-pointer"/>
                <rect x="180" y="60" width="30" height="100" fill="url(#barGradient)" opacity="0.9" rx="6" class="hover:opacity-100 transition-opacity cursor-pointer"/>
                <rect x="220" y="70" width="30" height="90" fill="url(#barGradient)" opacity="0.9" rx="6" class="hover:opacity-100 transition-opacity cursor-pointer"/>
                <rect x="260" y="50" width="30" height="110" fill="url(#barGradient)" opacity="0.9" rx="6" class="hover:opacity-100 transition-opacity cursor-pointer"/>
                <rect x="300" y="40" width="30" height="120" fill="url(#barGradient)" opacity="0.9" rx="6" class="hover:opacity-100 transition-opacity cursor-pointer"/>
                <rect x="340" y="30" width="30" height="130" fill="url(#barGradient)" opacity="0.9" rx="6" class="hover:opacity-100 transition-opacity cursor-pointer"/>
            </svg>
        </div>
    </div>

    <!-- Revenue Chart -->
    <div class="bg-gradient-to-br from-white to-emerald-50 rounded-2xl shadow-xl border border-emerald-100 p-6 animate-slide-in">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent flex items-center">
                <i class="fas fa-chart-bar mr-2 text-emerald-500"></i>
                Revenue Overview
            </h3>
            <select class="text-sm border border-emerald-200 rounded-xl px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-white/80 backdrop-blur-sm">
                <option>This month</option>
                <option>Last month</option>
                <option>Last quarter</option>
            </select>
        </div>
        <div class="h-64 flex items-center justify-center bg-gradient-to-br from-emerald-50/50 to-teal-50/50 rounded-xl relative overflow-hidden">
            <!-- Enhanced SVG Line Chart -->
            <svg class="w-full h-full" viewBox="0 0 400 200">
                <!-- Grid lines -->
                <line x1="40" y1="20" x2="40" y2="160" stroke="#d1fae5" stroke-width="1"/>
                <line x1="40" y1="160" x2="380" y2="160" stroke="#d1fae5" stroke-width="1"/>
                
                <!-- Area gradient -->
                <defs>
                    <linearGradient id="revenueGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                        <stop offset="0%" style="stop-color:#10b981;stop-opacity:0.4" />
                        <stop offset="100%" style="stop-color:#10b981;stop-opacity:0.05" />
                    </linearGradient>
                    <filter id="glow">
                        <feGaussianBlur stdDeviation="3" result="coloredBlur"/>
                        <feMerge>
                            <feMergeNode in="coloredBlur"/>
                            <feMergeNode in="SourceGraphic"/>
                        </feMerge>
                    </filter>
                </defs>
                
                <!-- Area under the line -->
                <polygon 
                    points="60,120 100,100 140,110 180,80 220,70 260,60 300,40 340,50 340,160 60,160" 
                    fill="url(#revenueGradient)"
                />
                
                <!-- Line chart with glow -->
                <polyline 
                    points="60,120 100,100 140,110 180,80 220,70 260,60 300,40 340,50" 
                    fill="none" 
                    stroke="#10b981" 
                    stroke-width="3"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    filter="url(#glow)"
                />
                
                <!-- Enhanced data points -->
                <circle cx="60" cy="120" r="5" fill="#10b981" stroke="white" stroke-width="2" class="hover:r-6 transition-all cursor-pointer"/>
                <circle cx="100" cy="100" r="5" fill="#10b981" stroke="white" stroke-width="2" class="hover:r-6 transition-all cursor-pointer"/>
                <circle cx="140" cy="110" r="5" fill="#10b981" stroke="white" stroke-width="2" class="hover:r-6 transition-all cursor-pointer"/>
                <circle cx="180" cy="80" r="5" fill="#10b981" stroke="white" stroke-width="2" class="hover:r-6 transition-all cursor-pointer"/>
                <circle cx="220" cy="70" r="5" fill="#10b981" stroke="white" stroke-width="2" class="hover:r-6 transition-all cursor-pointer"/>
                <circle cx="260" cy="60" r="5" fill="#10b981" stroke="white" stroke-width="2" class="hover:r-6 transition-all cursor-pointer"/>
                <circle cx="300" cy="40" r="5" fill="#10b981" stroke="white" stroke-width="2" class="hover:r-6 transition-all cursor-pointer"/>
                <circle cx="340" cy="50" r="5" fill="#10b981" stroke="white" stroke-width="2" class="hover:r-6 transition-all cursor-pointer"/>
            </svg>
        </div>
    </div>
</div>

<!-- Recent Activity & Quick Actions -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
<!-- Recent Orders -->
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-shopping-cart mr-2 text-indigo-500"></i>
                Recent Orders
            </h3>
            <a href="{{ route('admin.orders.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">View all</a>
        </div>
        
        @if($recentOrders->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="text-left py-3 px-4 text-sm font-medium text-gray-700">Order ID</th>
                            <th class="text-left py-3 px-4 text-sm font-medium text-gray-700">Customer</th>
                            <th class="text-left py-3 px-4 text-sm font-medium text-gray-700">Items</th>
                            <th class="text-left py-3 px-4 text-sm font-medium text-gray-700">Amount</th>
                            <th class="text-left py-3 px-4 text-sm font-medium text-gray-700">Status</th>
                            <th class="text-left py-3 px-4 text-sm font-medium text-gray-700">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($recentOrders as $order)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="py-3 px-4 text-sm text-gray-900 font-medium">#{{ $order->id }}</td>
                                <td class="py-3 px-4">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center mr-2">
                                            <i class="fas fa-user text-indigo-600 text-xs"></i>
                                        </div>
                                        <div class="text-gray-900">{{ $order->user->name ?? 'Guest' }}</div>
                                    </td>
                                <td class="py-3 px-4 text-sm text-gray-500">{{ $order->items->count() }} items</td>
                                <td class="py-3 px-4 text-sm text-gray-900">${{ number_format($order->total, 2) }}</td>
                                <td class="py-3 px-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                        @switch($order->status)
                                            @case('pending')
                                                bg-yellow-100 text-yellow-800
                                            @break
                                            @case('processing')
                                                bg-blue-100 text-blue-800
                                            @break
                                            @case('shipped')
                                                bg-indigo-100 text-indigo-800
                                            @break
                                            @case('delivered')
                                                bg-green-100 text-green-800
                                            @break
                                            @case('cancelled')
                                                bg-red-100 text-red-800
                                            @break
                                            @case('refunded')
                                                bg-gray-100 text-gray-800
                                            @break
                                            @default
                                                bg-gray-100 text-gray-800
                                        @endswitch
                                    ">
                                    {{ ucfirst($order->status) }}
                                </span>
                                </td>
                                <td class="py-3 px-4 whitespace-nowrap">{{ $order->created_at->format('M d, Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-8">
                <p class="text-gray-600">No recent orders found.</p>
            </div>
        @endif
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 animate-slide-in">
        <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
            <i class="fas fa-bolt mr-2 text-yellow-500"></i>
            Quick Actions
        </h3>
        <div class="space-y-3">
            <a href="{{ route('admin.products.create') }}" 
               class="flex items-center p-4 bg-gradient-to-r from-indigo-50 to-indigo-100 rounded-lg hover:from-indigo-100 hover:to-indigo-200 transition-all duration-300 group border border-indigo-200">
                <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-indigo-700 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform shadow-lg">
                    <i class="fas fa-plus text-white text-lg"></i>
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-sm font-semibold text-gray-900">Add New Product</p>
                    <p class="text-xs text-gray-600 mt-1">Create a new product listing</p>
                </div>
                <i class="fas fa-arrow-right text-indigo-500 opacity-0 group-hover:opacity-100 transition-opacity"></i>
            </a>

            <a href="{{ route('admin.categories.create') }}" 
               class="flex items-center p-4 bg-gradient-to-r from-purple-50 to-purple-100 rounded-lg hover:from-purple-100 hover:to-purple-200 transition-all duration-300 group border border-purple-200">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-700 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform shadow-lg">
                    <i class="fas fa-tag text-white text-lg"></i>
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-sm font-semibold text-gray-900">Add Category</p>
                    <p class="text-xs text-gray-600 mt-1">Organize products by category</p>
                </div>
                <i class="fas fa-arrow-right text-purple-500 opacity-0 group-hover:opacity-100 transition-opacity"></i>
            </a>

            <a href="{{ route('admin.users.create') }}" 
               class="flex items-center p-4 bg-gradient-to-r from-green-50 to-green-100 rounded-lg hover:from-green-100 hover:to-green-200 transition-all duration-300 group border border-green-200">
                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-700 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform shadow-lg">
                    <i class="fas fa-user-plus text-white text-lg"></i>
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-sm font-semibold text-gray-900">Add User</p>
                    <p class="text-xs text-gray-600 mt-1">Create new user account</p>
                </div>
                <i class="fas fa-arrow-right text-green-500 opacity-0 group-hover:opacity-100 transition-opacity"></i>
            </a>

            <a href="{{ route('admin.orders.export') }}" 
               class="flex items-center p-4 bg-gradient-to-r from-orange-50 to-orange-100 rounded-lg hover:from-orange-100 hover:to-orange-200 transition-all duration-300 group border border-orange-200">
                <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-orange-700 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform shadow-lg">
                    <i class="fas fa-download text-white text-lg"></i>
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-sm font-semibold text-gray-900">Export Report</p>
                    <p class="text-xs text-gray-600 mt-1">Download sales report</p>
                </div>
                <i class="fas fa-arrow-right text-orange-500 opacity-0 group-hover:opacity-100 transition-opacity"></i>
            </a>
        </div>
    </div>
</div>

<!-- Additional Metrics -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
    <!-- Conversion Rate -->
    <div class="bg-gradient-to-br from-white to-gray-50 rounded-xl shadow-sm border border-gray-200 p-6 animate-slide-in">
        <div class="flex items-center justify-between mb-4">
            <h4 class="text-sm font-semibold text-gray-700">Conversion Rate</h4>
            <i class="fas fa-chart-pie text-indigo-500"></i>
        </div>
        <div class="text-3xl font-bold text-gray-900">3.24%</div>
        <div class="flex items-center mt-2">
            <span class="text-xs text-green-600 bg-green-100 px-2 py-1 rounded-full">
                <i class="fas fa-arrow-up mr-1"></i>
                0.8%
            </span>
            <span class="text-xs text-gray-500 ml-2">vs last week</span>
        </div>
    </div>

    <!-- Average Order Value -->
    <div class="bg-gradient-to-br from-white to-gray-50 rounded-xl shadow-sm border border-gray-200 p-6 animate-slide-in">
        <div class="flex items-center justify-between mb-4">
            <h4 class="text-sm font-semibold text-gray-700">Avg. Order Value</h4>
            <i class="fas fa-receipt text-green-500"></i>
        </div>
        <div class="text-3xl font-bold text-gray-900">$86.42</div>
        <div class="flex items-center mt-2">
            <span class="text-xs text-green-600 bg-green-100 px-2 py-1 rounded-full">
                <i class="fas fa-arrow-up mr-1"></i>
                $5.23
            </span>
            <span class="text-xs text-gray-500 ml-2">vs last month</span>
        </div>
    </div>

    <!-- Customer Retention -->
    <div class="bg-gradient-to-br from-white to-gray-50 rounded-xl shadow-sm border border-gray-200 p-6 animate-slide-in">
        <div class="flex items-center justify-between mb-4">
            <h4 class="text-sm font-semibold text-gray-700">Customer Retention</h4>
            <i class="fas fa-users text-purple-500"></i>
        </div>
        <div class="text-3xl font-bold text-gray-900">68.5%</div>
        <div class="flex items-center mt-2">
            <span class="text-xs text-red-600 bg-red-100 px-2 py-1 rounded-full">
                <i class="fas fa-arrow-down mr-1"></i>
                2.1%
            </span>
            <span class="text-xs text-gray-500 ml-2">vs last month</span>
        </div>
    </div>
</div>
@endsection