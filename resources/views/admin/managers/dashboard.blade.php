@extends('admin.layouts.app')

@section('title', 'Manager Dashboard')
@section('page-title', 'Manager Dashboard')

@section('content')
<!-- Quick Stats -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Customers Managed -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-lg transition-shadow">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                <i class="fas fa-users text-blue-600 text-xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-1">{{ $stats['total_customers'] ?? 0 }}</h3>
            <p class="text-sm text-gray-600">Customers</p>
        </div>
        <div class="mt-2">
            <span class="text-xs text-green-600 bg-green-100 px-2 py-1 rounded-full">
                +{{ $stats['new_customers_this_month'] ?? 0 }}
            </span>
            <span class="text-xs text-gray-500 ml-2">this month</span>
        </div>
    </div>

    <!-- Orders Managed -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-lg transition-shadow">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                <i class="fas fa-shopping-cart text-purple-600 text-xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-1">{{ $stats['orders_managed'] ?? 0 }}</h3>
            <p class="text-sm text-gray-600">Orders Managed</p>
        </div>
        <div class="mt-2">
            <span class="text-xs text-green-600 bg-green-100 px-2 py-1 rounded-full">
                +{{ $stats['orders_today'] ?? 0 }}
            </span>
            <span class="text-xs text-gray-500 ml-2">today</span>
        </div>
    </div>

    <!-- Support Tickets -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-lg transition-shadow">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                <i class="fas fa-headset text-orange-600 text-xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-1">{{ $stats['open_tickets'] ?? 0 }}</h3>
            <p class="text-sm text-gray-600">Support Tickets</p>
        </div>
        <div class="mt-2">
            <span class="text-xs text-red-600 bg-red-100 px-2 py-1 rounded-full">
                {{ $stats['urgent_tickets'] ?? 0 }}
            </span>
            <span class="text-xs text-gray-500 ml-2">urgent</span>
        </div>
    </div>

    <!-- Performance -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-lg transition-shadow">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                <i class="fas fa-chart-line text-green-600 text-xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-1">{{ $stats['avg_response_time'] ?? 0 }}h</h3>
            <p class="text-sm text-gray-600">Avg Response Time</p>
        </div>
        <div class="mt-2">
            <span class="text-xs text-green-600 bg-green-100 px-2 py-1 rounded-full">
                Excellent
            </span>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Recent Orders -->
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            Recent Orders
            <a href="{{ route('admin.orders.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 ml-auto">
                View All
            </a>
        </h3>
        <div class="space-y-4">
            @foreach($recentOrders as $order)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100">
                    <div>
                        <span class="text-sm font-medium text-gray-900">#{{ $order->order_number ?? $order->id }}</span>
                        <span class="text-xs text-gray-500 ml-2">{{ $order->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="text-right">
                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $order->status_class ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $order->status ?? 'Pending' }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Team Performance -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Your Team Performance</h3>
        <div class="space-y-4">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm text-gray-600">Conversion Rate</span>
                <span class="text-lg font-bold text-gray-900">{{ $stats['team_conversion_rate'] ?? 0 }}%</span>
            </div>
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm text-gray-600">Customer Satisfaction</span>
                <div class="flex items-center">
                    <div class="flex-1 bg-gray-200 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full" style="width: {{ $stats['team_satisfaction'] ?? 0 }}%"></div>
                    </div>
                    <span class="text-lg font-bold text-gray-900 ml-2">{{ $stats['team_satisfaction'] ?? 0 }}%</span>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-600">Team Efficiency</span>
                <div class="text-lg font-bold text-gray-900">{{ $stats['team_efficiency'] ?? 0 }}%</span>
            </div>
        </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <a href="{{ route('admin.users.create') }}" 
           class="bg-blue-600 text-white rounded-lg p-6 hover:bg-blue-700 transition-colors group">
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mb-3 group-hover:bg-blue-200 transition-colors">
                <i class="fas fa-user-plus text-blue-600 text-xl group-hover:scale-110 transition-transform"></i>
            </div>
            <h4 class="text-lg font-semibold text-white">Add User</h4>
            <p class="text-sm text-blue-100">Create new team member</p>
        </a>
        
        <a href="{{ route('admin.orders.create') }}" 
           class="bg-green-600 text-white rounded-lg p-6 hover:bg-green-700 transition-colors group">
            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mb-3 group-hover:bg-green-200 transition-colors">
                <i class="fas fa-plus text-green-600 text-xl group-hover:scale-110 transition-transform"></i>
            </div>
            <h4 class="text-lg font-semibold text-white">Create Order</h4>
            <p class="text-sm text-green-100">Manual order entry</p>
        </a>
        
        <a href="#" 
           class="bg-orange-600 text-white rounded-lg p-6 hover:bg-orange-700 transition-colors group">
            <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center mb-3 group-hover:bg-orange-200 transition-colors">
                <i class="fas fa-chart-bar text-orange-600 text-xl group-hover:scale-110 transition-transform"></i>
            </div>
            <h4 class="text-lg font-semibold text-white">View Reports</h4>
            <p class="text-sm text-orange-100">Analytics & insights</p>
        </a>
        
        <a href="#" 
           class="bg-purple-600 text-white rounded-lg p-6 hover:bg-purple-700 transition-colors group">
            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mb-3 group-hover:bg-purple-200 transition-colors">
                <i class="fas fa-cog text-purple-600 text-xl group-hover:scale-110 transition-transform"></i>
            </div>
            <h4 class="text-lg font-semibold text-white">Settings</h4>
            <p class="text-sm text-purple-100">Configure preferences</p>
        </a>
    </div>
</div>

@section('scripts')
<script>
    // Simulated stats data - in real application, this would come from your database
    window.stats = {
        total_customers: {{ $stats['total_customers'] ?? 125 }},
        new_customers_this_month: {{ $stats['new_customers_this_month'] ?? 8 }},
        orders_managed: {{ $stats['orders_managed'] ?? 342 }},
        orders_today: {{ $stats['orders_today'] ?? 28 }},
        open_tickets: {{ $stats['open_tickets'] ?? 5 }},
        urgent_tickets: {{ $stats['urgent_tickets'] ?? 2 }},
        avg_response_time: {{ $stats['avg_response_time'] ?? 1.2 }},
        team_conversion_rate: {{ $stats['team_conversion_rate'] ?? 85 }},
        team_satisfaction: {{ $stats['team_satisfaction'] ?? 92 }},
        team_efficiency: {{ $stats['team_efficiency'] ?? 78 }}
    };
</script>
@endsection