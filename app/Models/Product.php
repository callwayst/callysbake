<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Product extends Model
{
    protected $fillable = [
        'name', 'description', 'image',
        'category_id', 'price', 'stock', 'is_active', 'type'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class)
            ->where('status', 'active')
            ->with('user');
    }

    // ✅ langsung ke order_items (bukan nested)
    public function orderItems()
    {
        return $this->hasManyThrough(
            OrderItem::class,
            ProductVariant::class,
            'product_id',
            'variant_id',
            'id',
            'id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    public function getMinPriceAttribute()
    {
        return $this->variants()->min('price') ?? $this->price;
    }

    public function getAverageRatingAttribute()
    {
        return round($this->reviews()->avg('rating'), 1);
    }

    public function getSoldCountAttribute()
    {
        return $this->orderItems()->sum('qty');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeActive(Builder $q)
    {
        return $q->where('is_active', true);
    }

    public function scopeTopSelling($query, $limit = 6)
    {
        return $query
            ->withSum('orderItems as sold_count', 'qty')
            ->orderByDesc('sold_count')
            ->limit($limit);
    }
}