<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SamplePost extends Model
{
    use SoftDeletes;

    protected $table = 'sample_posts';

     protected $fillable = [
        'post_content',
        'file_path',
        'specialcode',
        'username',
        'user_id',
        'text_color',
        'bgnd_color',
        'created_at',
        'updated_at',
        'scheduled_at',
        'status',
        'views',
        'likes',
        'shares',
        'link_preview',
    ];

    protected $casts = [
        'link_preview' => 'array',
        'scheduled_at' => 'datetime',
    ];

    // ✅ Relationship to UserRecord
    // ✅ Relationship to UserRecord
    public function user()
    {
        return $this->belongsTo(UserRecord::class, 'user_id');
    }
   
    // ✅ Likes Relationship
    public function likesRelation()
    {
        return $this->hasMany(PostLike::class, 'post_id');
    }

    // ✅ Comments Relationship
    public function comments()
    {
        return $this->hasMany(PostComment::class, 'post_id')->whereNull('parent_id');
    }

    // ✅ Reposts Relationship
    public function repostsRelation()
    {
        return $this->hasMany(PostRepost::class, 'post_id');
    }

    // ✅ Shares Relationship
    public function sharesRelation()
    {
        return $this->hasMany(PostShare::class, 'post_id');
    }

    // ✅ Views Relationship
    public function viewsRelation()
    {
        return $this->hasMany(PostView::class, 'post_id');
    }

    // ✅ Performance Tracking Relationship
    public function performance()
    {
        return $this->hasOne(PostPerformanceTracking::class, 'post_id');
    }

   





// Optional: if you're using accessors
public function getLikesRelationCountAttribute()
{
    return $this->likesRelation()->count();
}

public function getRepostsRelationCountAttribute()
{
    return $this->repostsRelation()->count();
}

public function getSharesRelationCountAttribute()
{
    return $this->sharesRelation()->count();
}



}
