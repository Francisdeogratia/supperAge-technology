<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class AdClick extends Model
{
    protected $fillable = [
        'ad_id',
        'user_id',
        'ip_address',
        'user_agent',
        'country',
        'converted',
        'clicked_at'
    ];

    protected $casts = [
        'converted' => 'boolean',
        'clicked_at' => 'datetime'
    ];

    public $timestamps = false;

    public function advertisement()
    {
        return $this->belongsTo(Advertisement::class, 'ad_id');
    }
}