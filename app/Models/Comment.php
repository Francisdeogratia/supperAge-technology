<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $fillable = ['tale_id', 'username', 'comment'];

    public function tale()
    {
        return $this->belongsTo(TalesExten::class, 'tale_id');
    }
}
