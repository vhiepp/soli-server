<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class PostComment extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'post_id',
        'comment_id'
    ];

    protected $casts = [
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp'
    ];

    protected $hidden = [
        'posts_id',
        'comment_id'
    ];

    protected $dateFormat = 'U';
}
