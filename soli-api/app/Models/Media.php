<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Media extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'file_url',
        'type'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp'
    ];

    protected $dateFormat = 'U';

    public static function boot(): void
    {
        parent::boot();

        static::retrieved(function ($media) {
            if (!str(str_replace(' ', '', $media->file_url))->isUrl()) {
                if ($media->file_url[0] != '/') $media->file_url = '/' . $media->file_url;
                $media->file_url = env('APP_URL', 'http://localhost:8000') . $media->file_url;
                if (env('SERVER_IMAGE_URL', false) && $media->type == 'image' && env('APP_ENV', '') == 'production') {
                    $media->file_url = env('SERVER_IMAGE_URL') . "?w=600&h=800&image=" . $media->file_url;
                }
            }
        });
    }
}
