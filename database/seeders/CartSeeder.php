<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CartItem;
use App\Models\ProductVariant;
use App\Models\User;

class CartSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        $variants = ProductVariant::inRandomOrder()->take(5)->get();

        foreach ($variants as $variant) {
            CartItem::create([
                'user_id'    => $user->id,
                'variant_id' => $variant->id,
                'quantity'   => rand(1, 3)
            ]);
        }
    }
}