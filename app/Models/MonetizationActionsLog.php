<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonetizationActionsLog extends Model
{
    protected $table = 'monetization_actions_log';
    
    protected $fillable = [
        'admin_id',
        'user_id',
        'action',
        'message'
    ];
    
    public function admin()
    {
        return $this->belongsTo(UserRecord::class, 'admin_id');
    }
    
    public function user()
    {
        return $this->belongsTo(UserRecord::class, 'user_id');
    }
}