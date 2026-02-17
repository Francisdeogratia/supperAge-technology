<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserMonetization extends Model
{
    protected $table = 'user_monetization';
    
    protected $fillable = [
        'user_id',
        'status',
        'approved_at',
        'approved_by',
        'notes',
        'total_earnings'
    ];
    
    protected $casts = [
        'approved_at' => 'datetime',
        'total_earnings' => 'decimal:2'
    ];
    
    public function user()
    {
        return $this->belongsTo(UserRecord::class, 'user_id');
    }
    
    public function approvedBy()
    {
        return $this->belongsTo(UserRecord::class, 'approved_by');
    }
}

