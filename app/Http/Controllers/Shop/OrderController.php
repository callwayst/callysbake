<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'all');

        $query = Order::where('user_id', Auth::id())
            ->with(['items.variant.product', 'address'])
            ->latest();

        if ($tab !== 'all') {
            $query->where('status', $tab);
        }

        $orders = $query->get();

        $counts = [
            'all'       => Order::where('user_id', Auth::id())->count(),
            'pending'   => Order::where('user_id', Auth::id())->where('status', 'pending')->count(),
            'paid'      => Order::where('user_id', Auth::id())->where('status', 'paid')->count(),
            'shipped'   => Order::where('user_id', Auth::id())->where('status', 'shipped')->count(),
            'done'      => Order::where('user_id', Auth::id())->where('status', 'done')->count(),
            'cancelled' => Order::where('user_id', Auth::id())->where('status', 'cancelled')->count(),
        ];

        return view('shop.orders.index', compact('orders', 'tab', 'counts'));
    }

    public function show($id)
    {
        $order = Order::where('user_id', Auth::id())
            ->with(['items.variant.product', 'address', 'voucher'])
            ->findOrFail($id);

        // Cek sudah review belum per product
        $reviewedProductIds = Review::where('user_id', Auth::id())
            ->pluck('product_id')
            ->toArray();

        return view('shop.orders.show', compact('order', 'reviewedProductIds'));
    }

    public function cancel($id)
    {
        $order = Order::where('user_id', Auth::id())->findOrFail($id);

        if (!in_array($order->status, ['pending', 'paid'])) {
            return back()->with('error', 'Pesanan tidak bisa dibatalkan.');
        }

        $order->update(['status' => 'cancelled']);
        return back()->with('success', 'Pesanan berhasil dibatalkan.');
    }

    public function confirm($id)
    {
        $order = Order::where('user_id', Auth::id())->findOrFail($id);

        if ($order->status !== 'shipped') {
            return back()->with('error', 'Pesanan belum dikirim.');
        }

        $order->update(['status' => 'done']);
        return back()->with('success', 'Pesanan dikonfirmasi selesai!');
    }
}