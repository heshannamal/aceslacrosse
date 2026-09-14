<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EMAdminCreditAdditionLog extends Model
{
    protected $table = 'em_admin_credit_addition_logs';

    protected $fillable = [
        'admin_user_id',
        'child_id',
        'customer_id',
        'credit_id',
        'credit_amount',
        'credit_option',
        'balance_before',
        'balance_after',
        'note',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'credit_amount' => 'integer',
        'balance_before' => 'integer',
        'balance_after' => 'integer',
    ];

    public function adminUser()
    {
        return $this->belongsTo(User::class, 'admin_user_id');
    }

    public function child()
    {
        return $this->belongsTo(EMCustomerChild::class, 'child_id');
    }

    public function customer()
    {
        return $this->belongsTo(EMCustomer::class, 'customer_id');
    }

    public function credit()
    {
        return $this->belongsTo(EMCustomerCredit::class, 'credit_id');
    }
}
