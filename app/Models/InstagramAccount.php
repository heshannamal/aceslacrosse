<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstagramAccount extends Model
{
    protected $fillable = [
        'instagram_user_id',
        'username',
        'access_token',
        'token_expires_at',
        'last_refreshed_at',
        'last_refresh_attempt_at',
        'last_refresh_error',
        'active',
    ];

    protected $casts = [
        'access_token' => 'encrypted',
        'token_expires_at' => 'datetime',
        'last_refreshed_at' => 'datetime',
        'last_refresh_attempt_at' => 'datetime',
        'active' => 'boolean',
    ];

    protected $hidden = [
        'access_token',
    ];
}
