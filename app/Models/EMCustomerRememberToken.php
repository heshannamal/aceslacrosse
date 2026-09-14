<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EMCustomerRememberToken extends Model
{
    protected $table = 'em_customer_remember_tokens';

    protected $fillable = [
        'customer_id',
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

    public function customer()
    {
        return $this->belongsTo(EMCustomer::class, 'customer_id');
    }
}
