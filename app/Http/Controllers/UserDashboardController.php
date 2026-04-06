<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use App\Models\Product;

class UserDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $cartCount       = $user->cart?->items()->count() ?? 0;
        $totalOrders     = $user->orders()->count();
        $paidOrders      = $user->orders()->where('status', 'paid')->count();
        $shippedOrders   = $user->orders()->where('status', 'shipped')->count();
        $completedOrders = $user->orders()->where('status', 'done')->count();
        $cancelledOrders = $user->orders()->where('status', 'cancelled')->count();

        $availableVouchers = Voucher::where('user_id', $user->id)
                                    ->where('is_active', true)->count();
        $recentOrders      = $user->orders()->with('items')->latest()->take(5)->get();
        $topProducts       = Product::topSelling(6)->get();

        return view('user.dashboard', compact(
            'cartCount', 'totalOrders', 'paidOrders',
            'shippedOrders', 'completedOrders', 'cancelledOrders',
            'availableVouchers', 'recentOrders', 'topProducts'
        ));
    }
}