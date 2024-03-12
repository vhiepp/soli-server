<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'content',
        'user_author_id',
        'status'
    ];

    protected $hidden = [
        'user_author_id'
    ];

    protected $casts = [
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp'
    ];

    protected $attributes = [
        'status' => 'showing'
    ];

    protected $dateFormat = 'U';

    public static function boot(): void
    {
        parent::boot();

        static::retrieved(function ($user) {
            $user->author;
        });
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_author_id');
    }
}
