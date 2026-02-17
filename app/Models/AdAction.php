<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdAction extends Model
{
    protected $fillable = [
        'ad_id',
        'user_id',
        'action_type',
        'ip_address',
        'user_agent',
        'country',
        'device_type',
        'value',
        'meta_data',
        'action_at',
    ];

    protected $casts = [
        'action_at' => 'datetime',
        'value' => 'decimal:2',
        'meta_data' => 'array',
    ];

    /**
     * Ad relationship
     */
    public function ad()
    {
        return $this->belongsTo(Advertisement::class, 'ad_id');
    }

    /**
     * User relationship
     */
    public function user()
    {
        return $this->belongsTo(UserRecord::class, 'user_id');
    }
}