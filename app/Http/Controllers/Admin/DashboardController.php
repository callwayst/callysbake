<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Review;
use App\Models\Voucher;

class DashboardController extends Controller
{
    public function index()
    {
        // ==================== STAT CARDS ====================
        $totalSales    = Order::whereIn('status', ['paid', 'shipped', 'done'])->sum('final_price');
        $totalOrders   = Order::count();
        $totalUsers    = User::where('role', 'user')->count();
        $totalProducts = Product::count();
        $lowStock      = ProductVariant::where('stock', '<=', 5)->count();

        // ==================== ORDERS SUMMARY ====================
        $statuses     = ['pending', 'paid', 'shipped', 'done', 'cancelled'];
        $orderSummary = [];
        foreach ($statuses as $s) {
            $orderSummary[$s] = Order::where('status', $s)->count();
        }

        // ==================== RECENT ORDERS ====================
        $recentOrders = Order::with('user')->latest()->take(5)->get();

        // ==================== TOP PRODUCTS ====================
        $topProducts = Product::with('variants')
            ->withCount(['orderItems as sold' => function ($q) {
                $q->select(DB::raw('SUM(qty)'));
            }])
            ->join('product_variants', 'products.id', '=', 'product_variants.product_id')
            ->join('order_items', 'product_variants.id', '=', 'order_items.variant_id')
            ->groupBy('products.id')
            ->orderByDesc('sold')
            ->take(5)
            ->get();

        // ==================== SALES CHART ====================
        // ✅ FIX: Ikutkan status 'paid' dan 'shipped' dan 'done' supaya grafik tidak lurus
        // karena order baru masuk sebagai 'pending' (cash) atau 'paid' (non-cash),
        // bukan langsung 'done'
        $salesChartLabels = [];
        $salesChartData   = [];

        for ($i = 6; $i >= 0; $i--) {
            $date               = Carbon::today()->subDays($i);
            $salesChartLabels[] = $date->isoFormat('ddd'); // Sen, Sel, Rab, ...
            $salesChartData[]   = Order::whereDate('created_at', $date)
                ->whereIn('status', ['paid', 'shipped', 'done'])
                ->sum('final_price');
        }

        // ==================== REVIEWS ====================
        $recentReviews = Review::with('user', 'product')
            ->latest()
            ->take(5)
            ->get();

        // ==================== VOUCHERS ====================
        // ✅ FIX: Ambil dari DB, bukan hardcoded
        $vouchers        = Voucher::latest()->take(5)->get()->map(function ($v) {
            $v->is_active = $v->expired_at >= now();
            return $v;
        });
        $activeVouchers  = Voucher::where('expired_at', '>=', now())->count();
        $expiredVouchers = Voucher::where('expired_at', '<', now())->count();

        // ==================== RETURN VIEW ====================
        return view('admin.dashboard', compact(
            'totalSales',
            'totalOrders',
            'totalUsers',
            'totalProducts',
            'lowStock',
            'orderSummary',
            'recentOrders',
            'topProducts',
            'salesChartLabels',
            'salesChartData',
            'recentReviews',
            'vouchers',
            'activeVouchers',
            'expiredVouchers'
        ));
    }
}