<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;



use Illuminate\Database\Eloquent\Model;

class BadgeVerification extends Model
{
    protected $fillable = [
    'user_id',
    'full_name',
    'gov_id_path',
    'profile_pic_path', // ✅ must be here
    'notes',
    'status',
];


    protected $appends = ['gov_id_url', 'profile_pic_url'];

public function getGovIdUrlAttribute()
{
    return $this->gov_id_path ? asset('storage/'.$this->gov_id_path) : null;
}

public function getProfilePicUrlAttribute()
{
    return $this->profile_image_path ? asset('storage/'.$this->profile_image_path) : null;
}


    public function user()
    {
        return $this->belongsTo(UserRecord::class, 'user_id');
    }
}
