<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Product;
use App\Models\Review;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role','user')->get();
        $products = Product::all();

        $comments = [
            "Cepat dan aman",
            "Produk sesuai ekspektasi",
            "Suka banget, recommended!",
            "Pengiriman lambat",
            "Top, kualitas oke",
            "Kurang sesuai deskripsi",
            "Harga sebanding kualitas",
            "Packaging kurang rapi",
            "Sangat puas",
            "Cukup baik untuk harga ini"
        ];

        for ($i=0; $i<10; $i++) {
            $user = $users->random();
            $product = $products->random();

            Review::firstOrCreate(
                ['user_id' => $user->id, 'product_id' => $product->id],
                [
                    'rating' => rand(1,5),
                    'comment' => $comments[$i],
                    'approved' => rand(0,1),
                ]
            );
        }
    }
}