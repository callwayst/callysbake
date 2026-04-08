<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use App\Models\Product;
use App\Models\CartItem;

class UserDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $cartCount = CartItem::where('user_id', $user->id)->count();
        $totalOrders     = $user->orders()->count();
        $pendingOrders   = $user->orders()->where('status', 'pending')->count(); 
        $paidOrders      = $user->orders()->where('status', 'paid')->count();
        $shippedOrders   = $user->orders()->where('status', 'shipped')->count();
        $completedOrders = $user->orders()->where('status', 'done')->count();
        $cancelledOrders = $user->orders()->where('status', 'cancelled')->count();

        $availableVouchers = Voucher::where('user_id', $user->id)
                                    ->where('is_active', true)->count();
        $recentOrders      = $user->orders()->with('items')->latest()->take(5)->get();
        $topProducts       = Product::topSelling(6)->get();

        return view('user.dashboard', compact(
            'cartCount', 'totalOrders', 'pendingOrders', 'paidOrders',
            'shippedOrders', 'completedOrders', 'cancelledOrders',
            'availableVouchers', 'recentOrders', 'topProducts'
        ));
    }
}