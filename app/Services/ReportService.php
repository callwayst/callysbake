<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use App\Models\Review;
use App\Models\ProductVariant;

class ReportService
{
    public function summary()
    {
        return [

            // ===== Sales =====
            'totalSales' => Order::where('status','done')->sum('final_price'),
            'totalOrders' => Order::count(),

            // ===== Users =====
            'activeUsers' => User::where('active',1)->count(),
            'inactiveUsers' => User::where('active',0)->count(),

            // ===== Reviews =====
            'avgRating' => round(Review::avg('rating'),1),

            // ===== Stock =====
            'lowStock' => ProductVariant::where('stock','<=',5)->count(),
        ];
    }


    public function orders($from = null, $to = null)
    {
        return Order::with('user')
            ->when($from, fn($q) => $q->whereDate('created_at','>=',$from))
            ->when($to, fn($q) => $q->whereDate('created_at','<=',$to))
            ->latest()
            ->get();
    }
}