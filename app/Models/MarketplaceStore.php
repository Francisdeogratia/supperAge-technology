<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MarketplaceStore extends Model
{
    protected $fillable = [
    'owner_id',
    'store_name',
    'store_slug',
    'description',
    'logo',
    'banner',
    'phone',
    'email',
    'address',
    'country',
    'state',
    'city',
    'status',
    'subscription_started_at',
    'subscription_expires_at',
    'subscription_status',
    'total_products',
    'total_orders',
    'total_views',
    'total_revenue'
];

protected $casts = [
    'total_revenue' => 'decimal:2',
    'subscription_started_at' => 'datetime',
    'subscription_expires_at' => 'datetime'
];

// Check if subscription is expired
public function isSubscriptionExpired()
{
    if (!$this->subscription_expires_at) {
        return false;
    }
    return now()->greaterThan($this->subscription_expires_at);
}

// Get days until expiry
public function daysUntilExpiry()
{
    if (!$this->subscription_expires_at) {
        return null;
    }
    return now()->diffInDays($this->subscription_expires_at, false);
}
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($store) {
            if (empty($store->store_slug)) {
                $store->store_slug = Str::slug($store->store_name) . '-' . Str::random(6);
            }
        });
    }

    public function owner()
    {
        return $this->belongsTo(UserRecord::class, 'owner_id');
    }

    public function products()
    {
        return $this->hasMany(MarketplaceProduct::class, 'store_id');
    }

    public function orders()
    {
        return $this->hasMany(MarketplaceOrder::class, 'store_id');
    }

    public function views()
    {
        return $this->hasMany(MarketplaceStoreView::class, 'store_id');
    }
}