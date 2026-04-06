<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'user_id'
    ];

    // cart punya 1 user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    // accessor untuk total harga di cart
    public function getTotalAttribute()
    {
        return $this->items->sum('subtotal');
    }

    public function isEmpty()
    {
        return $this->items()->count() === 0;
    }
}