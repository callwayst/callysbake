<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'avatar',
        'phone',    
        'address', 
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // helper untuk cek role
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    public function scopeAdmin($q)
    {
        return $q->where('role', 'admin');
    }

    // relations
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    // 1 user = 1 cart aktif
    public function cart()
    {
        return $this->hasOne(Cart::class);
    }
    
    public function cartItems()
    {
        return $this->hasMany(CartItem::class, 'user_id'); // kolom di cart_items = user_id
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function vouchers()
    {
        return $this->hasMany(\App\Models\Voucher::class);
    }

}