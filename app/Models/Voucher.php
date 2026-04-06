<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Voucher extends Model
{
    protected $fillable = [
        'code',
        'type',          // percent | fixed
        'value',
        'max_discount',
        'min_purchase',
        'usage_limit',
        'used_count',
        'expired_at',
        'is_active'
    ];

    protected $casts = [
        'expired_at' => 'datetime',
        'is_active' => 'boolean'
    ];

    // 1 voucher bisa dipakai banyak order
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // scope untuk active voucher
    public function scopeActive(Builder $q)
    {
        return $q->where('is_active', true)
                 ->where(function ($q) {
                     $q->whereNull('expired_at')
                       ->orWhere('expired_at', '>', now());
                 });
    }

    // helpers (logic bisnis taruh di model, bukan controller)
    public function isValid($subtotal)
    {
        if (!$this->is_active) return false;

        if ($this->expired_at && $this->expired_at->isPast()) return false;

        if ($this->quota && $this->used >= $this->quota) return false;

        if ($subtotal < $this->min_purchase) return false;

        return true;
    }

    // helper untuk hitung diskon berdasarkan subtotal
    public function calculateDiscount($subtotal)
    {
        if ($this->type === 'percent') {
            $discount = ($subtotal * $this->value) / 100;

            if ($this->max_discount) {
                $discount = min($discount, $this->max_discount);
            }

            return $discount;
        }

        return $this->value;
    }

    public function toggle(Voucher $voucher)
    {
        $voucher->is_active = !$voucher->is_active; // ubah status
        $voucher->save();

        $status = $voucher->is_active ? 'activated' : 'deactivated';

        return redirect()
            ->back()
            ->with('success', "Voucher {$voucher->code} has been {$status}.");
    }
}