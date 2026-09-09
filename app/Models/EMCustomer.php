<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class EMCustomer extends Authenticatable
{
    use Notifiable;

    public const ACCOUNT_TYPE_USER = 'user';
    public const ACCOUNT_TYPE_GUEST = 'guest';

    protected $table = 'em_customers';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'password',
        'google_id',
        'profile_photo',
        'email_verified_at',
        'parent_type',
        'account_type',
        'relational_id',
        'active',
        'remember_token',
        'password_reset_token',
        'password_reset_token_encrypted',
        'password_reset_expires_at',
        'password_reset_requested_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'password_reset_token',
        'password_reset_token_encrypted',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password_reset_expires_at' => 'datetime',
        'password_reset_requested_at' => 'datetime',
        'active' => 'boolean',
        'parent_type' => 'integer',
        'relational_id' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (EMCustomer $customer) {
            if (empty($customer->account_type)) {
                $customer->account_type = self::ACCOUNT_TYPE_USER;
            }
        });

        // Any customer explicitly maintained through the Admin area is a
        // Training User/Member, even if the email previously self-registered.
        static::saving(function (EMCustomer $customer) {
            if (app()->bound('request') && request()->routeIs('admin.*')) {
                $customer->account_type = self::ACCOUNT_TYPE_USER;
            }
        });
    }

    public function primaryParent()
    {
        return $this->belongsTo(self::class, 'relational_id');
    }

    public function relatedParents()
    {
        return $this->hasMany(self::class, 'relational_id');
    }

    public function childRelations()
    {
        return $this->hasMany(EMCustomerChildParent::class, 'customer_id');
    }

    public function parentRelations()
    {
        return $this->childRelations();
    }

    public function children()
    {
        return $this->belongsToMany(
            EMCustomerChild::class,
            'em_customer_child_parents',
            'customer_id',
            'child_id'
        )
            ->withPivot([
                'relationship',
                'is_primary',
                'can_book',
                'can_pay',
                'can_pickup',
                'notes',
            ])
            ->withTimestamps();
    }

    public function activeChildren()
    {
        return $this->children()->where('em_customer_children.is_active', 1);
    }

    public function primaryChildren()
    {
        return $this->children()->wherePivot('is_primary', 1);
    }

    public function bookableChildren()
    {
        return $this->activeChildren()->wherePivot('can_book', 1);
    }

    public function payableChildren()
    {
        return $this->activeChildren()->wherePivot('can_pay', 1);
    }

    public function carts()
    {
        return $this->hasMany(EMCustomerPackageCart::class, 'customer_id');
    }

    public function orders()
    {
        return $this->hasMany(EMPackageOrder::class, 'customer_id');
    }

    public function credits()
    {
        return $this->hasMany(EMCustomerCredit::class, 'customer_id');
    }

    public function creditLogs()
    {
        return $this->hasMany(EMCustomerCreditLog::class, 'customer_id');
    }

    public function bookings()
    {
        return $this->hasMany(EMSessionBooking::class, 'customer_id');
    }

    public function paymentLogs()
    {
        return $this->hasMany(EMPackagePaymentLog::class, 'customer_id');
    }

    public function getFullNameAttribute()
    {
        return trim(($this->first_name ?? '') . ' ' . ($this->last_name ?? ''));
    }

    public function getNameAttribute()
    {
        return $this->full_name ?: 'Customer';
    }

    public function getDisplayNameAttribute()
    {
        return $this->full_name ?: 'Customer';
    }

    public function getInitialAttribute()
    {
        return strtoupper(substr($this->display_name, 0, 1));
    }

    public function isGuestAccount(): bool
    {
        return strtolower((string) $this->account_type) === self::ACCOUNT_TYPE_GUEST;
    }

    public function isUserAccount(): bool
    {
        return !$this->isGuestAccount();
    }

    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = $value === null
            ? null
            : strtolower(trim((string) $value));
    }

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    public function scopeParentOne($query)
    {
        return $query->where('parent_type', 1);
    }

    public function scopeParentTwo($query)
    {
        return $query->where('parent_type', 2);
    }

    public function scopeGuests($query)
    {
        return $query->where('account_type', self::ACCOUNT_TYPE_GUEST);
    }

    public function scopeUsers($query)
    {
        return $query->where(function ($builder) {
            $builder->whereNull('account_type')
                ->orWhere('account_type', '!=', self::ACCOUNT_TYPE_GUEST);
        });
    }

    public function scopeSearch($query, $search)
    {
        $search = trim((string) $search);
        if ($search === '') {
            return $query;
        }

        return $query->where(function ($builder) use ($search) {
            $builder->where('first_name', 'like', '%' . $search . '%')
                ->orWhere('last_name', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%')
                ->orWhere('phone', 'like', '%' . $search . '%');
        });
    }
}
