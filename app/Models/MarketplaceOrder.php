<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketplaceOrder extends Model
{
    protected $fillable = [
        'order_number',
        'store_id',
        'product_id',
        'buyer_id',
        'buyer_name',
        'buyer_email',
        'buyer_phone',
        'buyer_address',
        'buyer_city',
        'buyer_state',
        'buyer_country',
        'quantity',
        'unit_price',
        'total_amount',
        'currency',
        'notes',
        'status',
        'confirmed_at',
        'shipped_at',
        'delivered_at'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'confirmed_at' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime'
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = 'ORD-' . strtoupper(uniqid());
            }
        });
    }

    public function store()
    {
        return $this->belongsTo(MarketplaceStore::class, 'store_id');
    }

    public function product()
    {
        return $this->belongsTo(MarketplaceProduct::class, 'product_id');
    }

    public function buyer()
    {
        return $this->belongsTo(UserRecord::class, 'buyer_id');
    }
}