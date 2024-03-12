<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FriendRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_request_id',
        'user_is_requested_id',
        'status'
    ];

    protected $casts = [
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp'
    ];
    protected $hidden = [
        'user_request_id',
        'user_is_requested_id'
    ];

    protected $dateFormat = 'U';
}
