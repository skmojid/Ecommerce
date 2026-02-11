<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with(['user', 'items'])
            ->when($request->status, function ($query, $status) {
                return $query->byStatus($status);
            })
            ->when($request->payment_status, function ($query, $paymentStatus) {
                return $query->byPaymentStatus($paymentStatus);
            })
            ->when($request->search, function ($query, $search) {
                return $query->where('order_number', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            })
            ->when($request->date_from, function ($query, $dateFrom) {
                return $query->whereDate('created_at', '>=', $dateFrom);
            })
            ->when($request->date_to, function ($query, $dateTo) {
                return $query->whereDate('created_at', '<=', $dateTo);
            })
            ->latest()
            ->paginate(20);
        return view('admin.orders.index', compact('orders'));
    }
    public function show(Order $order)
    {
        $order->load(['user', 'items.product']);
        return view('admin.orders.show', compact('order'));
    }
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => ['required', 'in:pending,shipped,delivered,cancelled,refunded'],
        ]);
        $oldStatus = $order->status;
        $newStatus = $request->status;
        if ($oldStatus === 'delivered' && $newStatus !== 'cancelled') {
            $message = 'Cannot change status of delivered order.';
            if ($request->expectsJson()) {
                return response()->json(['error' => $message], 422);
            }
            return back()->with('error', $message);
        }
        if ($oldStatus === 'cancelled' || $oldStatus === 'refunded') {
            $message = 'Cannot change status of cancelled/refunded order.';
            if ($request->expectsJson()) {
                return response()->json(['error' => $message], 422);
            }
            return back()->with('error', $message);
        }
        if ($newStatus === 'shipped') {
            $order->markAsShipped();
        } elseif ($newStatus === 'delivered') {
            $order->markAsDelivered();
        } elseif ($newStatus === 'cancelled') {
            $order->cancel();
        } elseif ($newStatus === 'refunded') {
            $order->refund();
        } else {
            $order->update(['status' => $newStatus]);
        }
        $message = 'Order status updated successfully.';
        if ($request->expectsJson()) {
            return response()->json(['success' => $message]);
        }
        return back()->with('success', $message);
    }
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'orders' => ['required', 'array'],
            'action' => ['required', 'in:update_status,mark_paid,mark_failed,delete'],
        ]);
        $action = $request->action;
        $orderIds = $request->orders;
        switch ($action) {
            case 'update_status':
                $request->validate([
                    'status' => ['required', 'in:pending,processing,shipped,delivered,cancelled,refunded'],
                ]);
                Order::whereIn('id', $orderIds)->update(['status' => $request->status]);
                $message = 'Order status updated successfully.';
                break;
            case 'mark_paid':
                Order::whereIn('id', $orderIds)->update(['payment_status' => 'paid']);
                $message = 'Orders marked as paid successfully.';
                break;
            case 'mark_failed':
                Order::whereIn('id', $orderIds)->update(['payment_status' => 'failed']);
                $message = 'Orders marked as failed successfully.';
                break;
            case 'delete':
                $cannotDelete = Order::whereIn('id', $orderIds)
                    ->whereIn('status', ['shipped', 'delivered'])
                    ->exists();
                if ($cannotDelete) {
                    return back()->with('error', 'Cannot delete orders that are already shipped or delivered.');
                }
                Order::whereIn('id', $orderIds)->delete();
                $message = 'Orders deleted successfully.';
                break;
            default:
                return back()->with('error', 'Invalid action selected.');
        }
        return back()->with('success', $message);
    }
    public function updatePaymentStatus(Request $request, Order $order)
    {
        $request->validate([
            'payment_status' => ['required', 'in:pending,paid,failed,refunded'],
        ]);
        $order->update(['payment_status' => $request->payment_status]);
        return back()->with('success', 'Payment status updated successfully.');
    }
    public function export(Request $request)
    {
        $orders = Order::with(['user', 'items.product'])
            ->when($request->status, function ($query, $status) {
                return $query->byStatus($status);
            })
            ->when($request->date_from, function ($query, $dateFrom) {
                return $query->whereDate('created_at', '>=', $dateFrom);
            })
            ->when($request->date_to, function ($query, $dateTo) {
                return $query->whereDate('created_at', '<=', $dateTo);
            })
            ->latest()
            ->get();
        $filename = 'orders_'.date('Y-m-d').'.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ];
        $callback = function () use ($orders) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'Order Number',
                'Customer Name',
                'Customer Email',
                'Status',
                'Payment Status',
                'Total',
                'Created At',
            ]);
            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->order_number,
                    $order->user->name,
                    $order->user->email,
                    $order->status,
                    $order->payment_status,
                    $order->total,
                    $order->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }
    public function statistics(Request $request)
    {
        $dateFrom = $request->date_from ?? now()->subDays(30)->format('Y-m-d');
        $dateTo = $request->date_to ?? now()->format('Y-m-d');
        $stats = [
            'total_orders' => Order::whereDate('created_at', '>=', $dateFrom)
                ->whereDate('created_at', '<=', $dateTo)
                ->count(),
            'total_revenue' => Order::paid()
                ->whereDate('created_at', '>=', $dateFrom)
                ->whereDate('created_at', '<=', $dateTo)
                ->sum('total'),
            'orders_by_status' => Order::whereDate('created_at', '>=', $dateFrom)
                ->whereDate('created_at', '<=', $dateTo)
                ->selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray(),
            'orders_by_payment_status' => Order::whereDate('created_at', '>=', $dateFrom)
                ->whereDate('created_at', '<=', $dateTo)
                ->selectRaw('payment_status, COUNT(*) as count')
                ->groupBy('payment_status')
                ->pluck('count', 'payment_status')
                ->toArray(),
            'daily_orders' => Order::whereDate('created_at', '>=', $dateFrom)
                ->whereDate('created_at', '<=', $dateTo)
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->groupBy('date')
                ->orderBy('date')
                ->get(),
        ];
        return response()->json($stats);
    }
}