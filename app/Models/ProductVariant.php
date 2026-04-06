<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\OrderItem;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'name',
        'price',
        'stock'
    ];

    protected $casts = [
        'price' => 'integer',
        'stock' => 'integer'
    ];

    // relations
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // 1 variant bisa ada di banyak cart item
    public function cartItems()
    {
        return $this->hasMany(CartItem::class, 'variant_id');
    }

    public function vouchers()
    {
        return $this->hasMany(Voucher::class, 'product_id', 'product_id'); 
    }

    // 1 variant bisa ada di banyak order item
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'variant_id');
    }

    // helpers
    public function inStock($qty = 1)
    {
        return $this->stock >= $qty;
    }

    // helper untuk ngurangin stock setelah order
    public function decreaseStock($qty)
    {
        $this->decrement('stock', $qty);
    }
}