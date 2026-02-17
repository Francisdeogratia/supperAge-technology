<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MarketplaceProduct extends Model
{
    protected $fillable = [
        'store_id',
        'name',
        'slug',
        'description',
        'type',
        'price',
        'currency',
        'stock',
        'images',
        'category',
        'status',
        'views',
        'orders'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'images' => 'array'
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name) . '-' . Str::random(6);
            }
        });
    }

    public function store()
    {
        return $this->belongsTo(MarketplaceStore::class, 'store_id');
    }

    public function orders()
    {
        return $this->hasMany(MarketplaceOrder::class, 'product_id');
    }

    public function views()
    {
        return $this->hasMany(MarketplaceStoreView::class, 'product_id');
    }
}