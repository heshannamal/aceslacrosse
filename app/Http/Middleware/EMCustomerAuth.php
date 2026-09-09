<?php

namespace App\Http\Middleware;

use App\Models\EMCustomer;
use Closure;
use Illuminate\Http\Request;

class EMCustomerAuth
{
    public function handle(Request $request, Closure $next)
    {
        $customerId = session('em_customer_id');
        $customer = $customerId
            ? EMCustomer::query()->whereKey($customerId)->where('active', 1)->first()
            : null;

        if (!$customer) {
            session()->forget([
                'em_customer_id',
                'em_customer_name',
                'em_customer_account_type',
            ]);
            session()->put('em_url_intended', $request->fullUrl());

            return redirect()
                ->route('em.customer.login')
                ->with('error', 'Please sign in to continue with Training.');
        }

        session([
            'em_customer_name' => $customer->display_name,
            'em_customer_account_type' => $customer->account_type ?: EMCustomer::ACCOUNT_TYPE_USER,
        ]);

        return $next($request);
    }
}
