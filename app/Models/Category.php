<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    // category punya banyak product
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // scope untuk active category
    public function scopeActive(Builder $q)
    {
        return $q->where('is_active', true);
    }

    protected static function booted()
    {
        static::creating(function ($cat) {
            $cat->slug = \Illuminate\Support\Str::slug($cat->name);
        });
    }
}