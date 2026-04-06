<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        'user_id',
        'receiver_name',
        'phone',
        'address',
        'city',
        'postal_code',
        'is_default'
    ];

    protected $casts = [
        'is_default' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Sesuaikan dengan kolom di migration
    public function getFullAddressAttribute()
    {
        return "{$this->address}, {$this->city} {$this->postal_code}";
    }

    public function setAsDefault()
    {
        self::where('user_id', $this->user_id)->update(['is_default' => false]);
        $this->update(['is_default' => true]);
    }
}