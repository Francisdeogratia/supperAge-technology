<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskCenter extends Model
{
    protected $table = 'task_center';

    protected $fillable = [
    'specialcode',
    'creator_id',
    'task_type',
    'task_content',
    'post_id', // ✅ Must be here
    'price',
    'currency',
    'target',
    'duration',
    'total_budget',
    'max_participants',
    'is_active',
];



    protected $casts = [
    'price' => 'float',
    'target' => 'string',
    'duration' => 'integer',
];

}
