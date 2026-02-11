<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $dateRange = $request->get('date_range', '30'); // Default 30 days
        $dateFrom = now()->subDays($dateRange)->startOfDay();
        $dateTo = now()->endOfDay();
        $stats = [
            'total_users' => User::count(),
            'total_customers' => User::customers()->count(),
            'total_products' => Product::count(),
            'total_categories' => Category::count(),
            'total_orders' => Order::count(),
        ];
        $stats['period_revenue'] = Order::paid()
            ->where('created_at', '>=', $dateFrom)
            ->where('created_at', '<=', $dateTo)
            ->selectRaw('DATE(created_at) as date, SUM(total) as revenue, COUNT(*) as orders')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        $stats['period_orders'] = Order::where('created_at', '>=', $dateFrom)
            ->count();
        $stats['period_new_customers'] = User::customers()
            ->where('created_at', '>=', $dateFrom)
            ->count();
        $stats['category_stats'] = Order::join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->where('orders.created_at', '>=', $dateFrom)
            ->where('orders.created_at', '<=', $dateTo)
            ->selectRaw('categories.name, SUM(order_items.total_price) as revenue, COUNT(DISTINCT orders.id) as orders')
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('revenue', 'desc')
            ->limit(5)
            ->get();
        $recentOrders = Order::with(['user', 'items.product'])
            ->where('created_at', '>=', now()->subDays(30))
            ->latest()
            ->limit(10)
            ->get();
        return view('admin.dashboard', compact('stats', 'dateRange', 'recentOrders'));
    }
}