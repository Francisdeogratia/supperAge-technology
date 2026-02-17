<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AIChatHistory extends Model
{
    protected $table = 'ai_chat_history';
    
    protected $fillable = [
        'user_id',
        'role',
        'message'
    ];
    
    public function user()
    {
        return $this->belongsTo(UserRecord::class, 'user_id');
    }
}