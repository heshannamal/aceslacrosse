<?php

namespace App\Http\Middleware;

use App\Models\EMCustomer;
use App\Models\EMCustomerPackageCart;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTrainingCustomerPricing
{
    /**
     * Keep every signed-in Training cart aligned with the customer's account
     * type before booking/cart/checkout/payment logic runs.
     *
     * Admin-created customers are Users/Members and pay package_price.
     * Self-registered customers are Guests and pay guest_price.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $customerId = (int) $request->session()->get('em_customer_id', 0);

        if ($customerId > 0) {
            $customer = EMCustomer::query()
                ->whereKey($customerId)
                ->where('active', 1)
                ->first();

            if ($customer) {
                $accountType = $customer->isGuestAccount()
                    ? EMCustomer::ACCOUNT_TYPE_GUEST
                    : EMCustomer::ACCOUNT_TYPE_USER;

                $request->session()->put('em_customer_account_type', $accountType);

                $items = EMCustomerPackageCart::with('package')
                    ->where('customer_id', $customer->id)
                    ->get();

                foreach ($items as $item) {
                    if (!$item->package) {
                        continue;
                    }

                    $quantity = max(1, (int) $item->quantity);
                    $unitPrice = round($item->package->priceForCustomer($customer), 2);
                    $totalPrice = round($unitPrice * $quantity, 2);

                    if (
                        round((float) $item->unit_price, 2) !== $unitPrice ||
                        round((float) $item->total_price, 2) !== $totalPrice
                    ) {
                        $item->unit_price = $unitPrice;
                        $item->total_price = $totalPrice;
                        $item->save();
                    }
                }
            }
        }

        return $next($request);
    }
}
