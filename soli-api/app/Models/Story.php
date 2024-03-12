<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Story extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'media_id',
        'status',
        'limit'
    ];

    protected $casts = [
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp'
    ];

    protected $attributes = [
        'description' => ''
    ];

    protected $dateFormat = 'U';

    public static function boot(): void
    {
        parent::boot();

        static::retrieved(function ($post) {
            $post->media;
            $post->author = $post->authors()->first();
        });
    }

    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_stories', 'story_id', 'user_author_id');
    }

    public function media(): HasOne
    {
        return $this->hasOne(Media::class, 'id', 'media_id');
    }
}
