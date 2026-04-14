<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items', 'address'])
            ->latest();

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->payment_method) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->search) {
            $query->whereHas('user', fn($q) =>
                $q->where('name', 'like', '%'.$request->search.'%')
            );
        }

        $orders      = $query->get();
        $perPage     = 5;
        $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
        $pagedItems  = $orders->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $mobileOrders = new \Illuminate\Pagination\LengthAwarePaginator(
            $pagedItems,
            $orders->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $counts = [
            'all'       => Order::count(),
            'pending'   => Order::where('status', 'pending')->count(),
            'paid'      => Order::where('status', 'paid')->count(),
            'shipped'   => Order::where('status', 'shipped')->count(),
            'done'      => Order::where('status', 'done')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'mobileOrders', 'counts'));
    }

    public function show($id)
    {
        $order = Order::with(['user', 'items.variant.product', 'address', 'voucher'])
            ->findOrFail($id);

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,paid,shipped,done,cancelled',
        ]);

        $order = Order::with('items.variant')->findOrFail($id);
        
        if ($request->status === 'cancelled' && $order->status !== 'cancelled') {
            foreach ($order->items as $item) {
                if ($item->variant) {
                    $item->variant->increment('stock', $item->qty);
                }
            }
        }

        $order->update(['status' => $request->status]);

        if ($request->ajax()) {
            return response()->json([
                'success'    => true,
                'order_id'   => $id,
                'new_status' => $request->status,
            ]);
        }

        return back()->with('success', 'Status pesanan berhasil diupdate.');
    }
}