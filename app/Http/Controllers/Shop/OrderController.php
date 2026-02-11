<?php
namespace App\Http\Controllers\Shop;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Auth::user()
            ->orders()
            ->with(['items.product'])
            ->when($request->status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($request->search, function ($query, $search) {
                return $query->where('order_number', 'like', "%{$search}%");
            })
            ->when($request->date_from, function ($query, $dateFrom) {
                return $query->whereDate('created_at', '>=', $dateFrom);
            })
            ->when($request->date_to, function ($query, $dateTo) {
                return $query->whereDate('created_at', '<=', $dateTo);
            })
            ->latest()
            ->paginate(10);
        return view('shop.orders.index', compact('orders'));
    }
    public function show($orderNumber)
    {
        $order = Auth::user()
            ->orders()
            ->where('order_number', $orderNumber)
            ->with(['items.product', 'items.product.category', 'items.product.primaryImage'])
            ->firstOrFail();
        return view('shop.orders.show', compact('order'));
    }
}