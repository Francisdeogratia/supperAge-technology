<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $tales_id
 * @property string $specialcode
 * @property string $tales_content
 * @property \Carbon\Carbon $tales_datetime
 * @property string $tales_types
 * @property string $username
 * @property string|null $files_talesexten
 * @property string $text_color
 * @property string $bgnd_color
 * @property string $type
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property int $views
 * @property int $likes
 * @property int $shares
 */

class TalesExten extends Model
{
    use SoftDeletes;

    protected $table = 'tales_extens';
    protected $primaryKey = 'tales_id';

    protected $fillable = [
        'specialcode',
        'tales_content',
        'tales_datetime',
        'tales_types',
        'username',
        'files_talesexten',
        'text_color',
        'bgnd_color',
        'type',
        'views',
        'likes',
        'shares',
        'link_preview',
    ];


    protected $casts = [
    'link_preview' => 'array',
];
    public function comments()
{
    return $this->hasMany(Comment::class, 'tale_id');
}

}
