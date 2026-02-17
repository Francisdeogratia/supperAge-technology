<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class AdImpression extends Model
{
    protected $fillable = [
        'ad_id',
        'user_id',
        'ip_address',
        'user_agent',
        'country',
        'device_type',
        'viewed_at'
    ];

    protected $casts = [
        'viewed_at' => 'datetime'
    ];

    public $timestamps = false;

    public function advertisement()
    {
        return $this->belongsTo(Advertisement::class, 'ad_id');
    }
}