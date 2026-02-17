<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserReport extends Model
{
    protected $fillable = [
        'reporter_id',
        'reported_user_id',
        'reason',
        'status',
        'action_taken',
        'admin_note',
        'reviewed_by',
        'reviewed_at'
    ];

    public function reporter()
    {
        return $this->belongsTo(UserRecord::class, 'reporter_id');
    }

    public function reportedUser()
    {
        return $this->belongsTo(UserRecord::class, 'reported_user_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(UserRecord::class, 'reviewed_by');
    }
}