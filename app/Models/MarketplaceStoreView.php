<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketplaceStoreView extends Model
{
    protected $fillable = [
        'store_id',
        'product_id',
        'viewer_id',
        'ip_address'
    ];

    public function store()
    {
        return $this->belongsTo(MarketplaceStore::class, 'store_id');
    }

    public function product()
    {
        return $this->belongsTo(MarketplaceProduct::class, 'product_id');
    }

    public function viewer()
    {
        return $this->belongsTo(UserRecord::class, 'viewer_id');
    }
}