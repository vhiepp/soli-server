<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Notification extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'done_by_user_id',
        'type',
        'message',
        'status',
        'detail_data',
        'updated_at'
    ];

    protected $hidden = [
        'user_id',
        'done_by_user_id'
    ];

    protected $casts = [
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp'
    ];

    protected $dateFormat = 'U';

    public static function boot(): void
    {
        parent::boot();

        static::retrieved(function ($notification) {
            $notification->detail_data = json_decode($notification->detail_data);
            $notification->doneByUser;
        });
    }

    public function doneByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'done_by_user_id', 'id');
    }

    public function forUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
