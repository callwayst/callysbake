<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'order_id',
        'rating',
        'comment',
        'approved',
        'status' => 'active'
    ];

    protected $casts = [
        'approved' => 'boolean'
    ];

    // review dibuat oleh user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // review untuk product tertentu
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // optional: review berasal dari order tertentu
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // scope biar bersih
    public function scopeApproved($query)
    {
        return $query->where('approved', true);
    }
}