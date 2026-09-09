<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EMPackage extends Model
{
    protected $table = 'em_packages';

    protected $fillable = [
        'user_id',
        'uuid',
        'slug',
        'package_name',
        'package_price',
        'guest_price',
        'package_description',
        'available_classes',
        'is_active',
        'active',
        'status',
    ];

    protected $casts = [
        'package_price' => 'decimal:2',
        'guest_price' => 'decimal:2',
        'is_active' => 'boolean',
        'active' => 'boolean',
    ];

    /**
     * Training uses two price tiers:
     * - admin-created Training customers => package_price
     * - self-registered/anonymous Training customers => guest_price
     */
    public function priceForCustomer(?EMCustomer $customer): float
    {
        $regularPrice = (float) ($this->getRawOriginal('package_price') ?? 0);
        $guestRaw = $this->getRawOriginal('guest_price');
        $guestPrice = $guestRaw === null || $guestRaw === ''
            ? $regularPrice
            : (float) $guestRaw;

        return !$customer || $customer->isGuestAccount()
            ? $guestPrice
            : $regularPrice;
    }

    /**
     * Preserve legacy controller/view references to package_price on Training
     * routes while keeping admin screens on the real user/member price.
     */
    public function getPackagePriceAttribute($value)
    {
        if (!app()->bound('request') || app()->runningInConsole()) {
            return $value;
        }

        $route = request()->route();
        if (!$route || !request()->routeIs('em.customer.*')) {
            return $value;
        }

        $customer = null;
        $customerId = session('em_customer_id');

        if ($customerId) {
            $accountType = session('em_customer_account_type');

            if (!$accountType) {
                $accountType = EMCustomer::query()
                    ->whereKey($customerId)
                    ->where('active', 1)
                    ->value('account_type');

                if ($accountType) {
                    session(['em_customer_account_type' => $accountType]);
                }
            }

            if ($accountType) {
                $customer = new EMCustomer();
                $customer->account_type = $accountType;
            }
        }

        return $this->priceForCustomer($customer);
    }

    public function credits()
    {
        return $this->hasMany(EMCustomerCredit::class, 'package_id');
    }

    public function bookings()
    {
        return $this->hasMany(EMSessionBooking::class, 'package_id');
    }

    public function orderItems()
    {
        return $this->hasMany(EMPackageOrderItem::class, 'package_id');
    }

    public function carts()
    {
        return $this->hasMany(EMCustomerPackageCart::class, 'package_id');
    }
}
