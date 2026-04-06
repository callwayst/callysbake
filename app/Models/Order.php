<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'address_id',
        'voucher_id',
        'subtotal',
        'total_price', // tambah ini
        'discount',
        'final_price',
        'status',
    ];

    protected $casts = [
        'subtotal' => 'integer',
        'discount' => 'integer',
        'final_price' => 'integer',
    ];

    // relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // helper untuk hitung subtotal dari order items
    public function calculateSubtotal()
    {
        return $this->items->sum('subtotal');
    }

    // helper untuk recalc total harga setelah ada perubahan di order items
    public function recalcTotal()
    {
        $this->subtotal = $this->calculateSubtotal();

        $this->final_price = $this->subtotal - ($this->discount ?? 0);

        $this->save();
    }
}