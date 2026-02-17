<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['title','description','category','reward_points','status'];

   public function users()
{
    return $this->belongsToMany(UserRecord::class, 'task_user', 'task_id', 'user_id')
                ->withPivot('is_completed', 'completed_at')
                ->withTimestamps();
}

}

