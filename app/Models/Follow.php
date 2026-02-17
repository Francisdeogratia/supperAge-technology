<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Follow extends Model
{
    protected $table = 'follow_tbl';
    protected $primaryKey = 'follow_id';
    public $timestamps = true;

    protected $fillable = [
        'sender_id',
        'receiver_id',
    ];

    // Who sent the follow request
    public function sender()
    {
        
        return $this->belongsTo(UserRecord::class, 'sender_id');
    }

    // Who received the follow request
    public function receiver()
    {
        return $this->belongsTo(UserRecord::class, 'receiver_id');
    }
// ✅ Automatically adjust follower counts when follows are deleted
    protected static function booted()
    {
        static::deleted(function ($follow) {
            // Decrement the receiver's follower count if it’s above 0
            $receiver = UserRecord::find($follow->receiver_id);
            if ($receiver && $receiver->number_followers > 0) {
                $receiver->decrement('number_followers');
            }
        });
    }
    

}
