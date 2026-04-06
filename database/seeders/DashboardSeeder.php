<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Review;

class DashboardSeeder extends Seeder
{
    public function run()
    {
        // ==================== CATEGORIES ====================
        $bahan = Category::firstOrCreate(['slug' => 'bahan-kue'], ['name' => 'Bahan Kue', 'is_active' => true]);
        $alat = Category::firstOrCreate(['slug' => 'alat-baking'], ['name' => 'Alat Baking', 'is_active' => true]);
        $topping = Category::firstOrCreate(['slug' => 'topping-hiasan'], ['name' => 'Topping/Hiasan', 'is_active' => true]);

        // ==================== USERS ====================
        if (User::count() < 10) {
            User::factory()->count(10 - User::count())->create();
        }

        foreach(User::all() as $user) {
            $user->addresses()->firstOrCreate(
                ['receiver_name' => $user->name, 'phone' => '08123456789', 'address' => 'Jl. Contoh No. '.$user->id],
                ['city' => 'Jakarta', 'postal_code' => '12345', 'is_default' => true]
            );
        }

        // ==================== ORDERS + ORDER ITEMS ====================
        $statuses = ['pending','paid','shipped','done','cancelled'];

        foreach ($statuses as $status) {
            $order = Order::firstOrCreate(
                ['status' => $status, 'user_id' => rand(1, User::count())],
                [
                    'address_id' => \App\Models\Address::inRandomOrder()->first()->id,
                    'total_price' => rand(100000,500000),
                    'discount' => rand(0,50000),
                    'final_price' => rand(50000,450000),
                    'created_at' => now()->subDays(rand(0,6)),
                ]
            );

            // buat 1 OrderItem per order
            $variant = ProductVariant::inRandomOrder()->first();
            OrderItem::firstOrCreate(
                ['order_id' => $order->id, 'variant_id' => $variant->id],
                [
                    'qty' => rand(1,5),
                    'price' => $variant->price,
                    'subtotal' => rand(50000,200000),
                    'product_name' => $variant->product->name,
                    'variant_name' => $variant->name,
                ]
            );
        }

        // ==================== REVIEWS (AMAN & UNIQUE) ====================
        $reviewComments = [
            "Cepat Pengirimannya, recommended!",
            "Suka banget, packaging rapi",
            "Kualitas oke tapi stok sering habis",
            "Cukup bagus barangnya untuk harga segini",
            "Biasa aja, kurang spesial",
            "Top, pengiriman cepat",
            "Kurang sesuai ekspektasi",
            "Andalan untuk beli bahan kue berkualitas!"
        ];

        $userIds = User::pluck('id')->toArray();
        $productIds = Product::pluck('id')->toArray();
        $usedPairs = Review::pluck(\DB::raw("CONCAT(user_id,'-',product_id)"))->toArray();

        while (count($usedPairs) < 10) {
            $userId = $userIds[array_rand($userIds)];
            $productId = $productIds[array_rand($productIds)];
            $key = $userId.'-'.$productId;

            if (in_array($key, $usedPairs)) continue;
            $usedPairs[] = $key;

            Review::firstOrCreate(
                ['user_id' => $userId, 'product_id' => $productId],
                [
                    'rating' => rand(1,5),
                    'comment' => $reviewComments[array_rand($reviewComments)],
                    'created_at' => now()->subDays(rand(0,6)),
                ]
            );
        }
    }
}