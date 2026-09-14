<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminRememberToken extends Model
{
    protected $table = 'admin_remember_tokens';

    protected $fillable = [
        'user_id',
        'selector',
        'validator_hash',
        'user_agent',
        'ip_address',
        'last_used_at',
        'expires_at',
    ];

    protected $casts = [
        'last_used_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
