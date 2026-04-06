<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use App\Models\Voucher;
use Illuminate\Support\Facades\DB;
use Exception;

class CheckoutService
{
    public function checkout($user, $cart, $voucherId, $addressId)
    {
        return DB::transaction(function () use ($user, $cart, $voucherId, $addressId) {

            if ($cart->items->isEmpty()) {
                throw new Exception('Cart kosong');
            }

            $total = 0;

            // ========================
            // HITUNG TOTAL
            // ========================
            foreach ($cart->items as $item) {

                $variant = ProductVariant::findOrFail($item->variant_id);

                if ($variant->stock < $item->qty) {
                    throw new Exception("Stok {$variant->name} tidak cukup");
                }

                $total += $variant->price * $item->qty;
            }

            // ========================
            // VOUCHER (pakai ID, bukan code)
            // ========================
            $discount = 0;
            $voucher = null;

            if ($voucherId) {
                $voucher = Voucher::find($voucherId);

                if ($voucher && now()->lte($voucher->expired_at)) {

                    if ($voucher->type === 'percent') {
                        $discount = $total * $voucher->value / 100;
                    } else {
                        $discount = $voucher->value;
                    }
                }
            }

            $final = $total - $discount;

            // ========================
            // CREATE ORDER
            // ========================
            $order = Order::create([
                'user_id'     => $user->id,
                'address_id'  => $addressId,
                'voucher_id'  => $voucher?->id,
                'total_price' => $total,
                'discount'    => $discount,
                'final_price' => $final,
                'status'      => 'pending'
            ]);

            // ========================
            // CREATE ORDER ITEMS
            // ========================
            foreach ($cart->items as $item) {

                $variant = ProductVariant::findOrFail($item->variant_id);

                OrderItem::create([
                    'order_id'     => $order->id,
                    'variant_id'   => $variant->id,
                    'product_name' => $variant->product->name,
                    'variant_name' => $variant->name,
                    'price'        => $variant->price,
                    'qty'          => $item->qty
                ]);

                $variant->decrement('stock', $item->qty);
            }

            // ========================
            // CLEAR CART
            // ========================
            $cart->items()->delete();

            return $order;
        });
    }
}