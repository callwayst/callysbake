<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil user pertama yang role user
        $user = User::where('role', 'user')->first();
        if (!$user) {
            $this->command->info('Tidak ada user dengan role user. Seeder dihentikan.');
            return;
        }

        // Ambil cart aktif beserta relasi items -> variant -> product
        $cart = Cart::with('items.variant.product')
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->first();

        // Pastikan ada cart dan items
        if ($cart && $cart->items->isNotEmpty()) {

            // Ambil alamat user, kalau belum ada buat dummy
            $address = $user->addresses()->first();
            if (!$address) {
                $address = $user->addresses()->create([
                    'receiver_name' => $user->name,
                    'phone' => '081234567890',
                    'address' => 'Jl. Contoh No.1',
                    'city' => 'Jakarta',
                    'postal_code' => '12345',
                    'is_default' => true,
                ]);
            }

            $total = 0;

            // Buat order
            $order = Order::create([
                'user_id' => $user->id,
                'address_id' => $address->id,
                'voucher_id' => null,
                'total_price' => 0,
                'discount' => 0,
                'final_price' => 0,
                'status' => 'paid',
            ]);

            // Buat order items dari cart
            foreach ($cart->items as $item) {
                $subtotal = $item->qty * $item->variant->price;
                $total += $subtotal;

                OrderItem::create([
                    'order_id' => $order->id,
                    'variant_id' => $item->variant_id,
                    'product_name' => $item->variant->product->name,
                    'variant_name' => $item->variant->name,
                    'price' => $item->variant->price,
                    'qty' => $item->qty,
                    'subtotal' => $subtotal,
                ]);
            }

            // Update total dan final price di order
            $order->update([
                'total_price' => $total,
                'final_price' => $total,
            ]);

            // Tandai cart sudah checked_out
            $cart->update(['status' => 'checked_out']);

            $this->command->info("Order berhasil dibuat untuk user: {$user->name}");
        } else {
            $this->command->info('Tidak ada cart aktif atau cart kosong.');
        }
    }
}