<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Voucher;
use App\Models\Product;

class UserDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $cartCount         = $user->cart?->items()->count() ?? 0;
        $totalOrders       = $user->orders()->count();
        $pendingOrders     = $user->orders()->where('status', 'Pending')->count();
        $completedOrders   = $user->orders()->where('status', 'Completed')->count();
        $availableVouchers = Voucher::where('user_id', $user->id)
                                    ->where('is_active', true)->count();
        $recentOrders      = $user->orders()->with('items')->latest()->take(5)->get();

        // Pakai scope topSelling dari model — withSum qty terjual
        $topProducts = Product::topSelling(6)->get();

        return view('user.dashboard', compact(
            'cartCount', 'totalOrders', 'pendingOrders',
            'completedOrders', 'availableVouchers',
            'recentOrders', 'topProducts'
        ));
    }
}