<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'variant_id',
        'product_name',  
        'variant_name', 
        'price',     
        'qty',
        'subtotal'
    ];

    protected $casts = [
        'price' => 'integer',
        'subtotal' => 'integer'
    ];

    // relations
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // 1 order item punya 1 variant
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    // booted untuk auto hitung subtotal sebelum saving
    protected static function booted()
    {
        static::saving(function ($item) {
            $item->subtotal = $item->price * $item->qty;
        });
    }
}