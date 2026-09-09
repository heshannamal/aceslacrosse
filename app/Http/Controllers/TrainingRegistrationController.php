<?php

namespace App\Http\Controllers;

use App\Models\EMCustomer;
use App\Models\EMCustomerChild;
use App\Models\EMCustomerChildParent;
use App\Models\EMCustomerPackageCart;
use App\Models\EMPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class TrainingRegistrationController extends Controller
{
    private const GUEST_CART_SESSION_KEY = 'training_guest_cart';

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['nullable', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:80'],
            'email' => ['required', 'email', 'max:190', Rule::unique('em_customers', 'email')],
            'password' => ['required', 'string', 'min:8', 'confirmed'],

            'child_first_name' => ['required', 'string', 'max:100'],
            'child_last_name' => ['nullable', 'string', 'max:100'],
            'child_team' => ['nullable', 'string', 'max:150'],
            'child_spring_team' => ['nullable', 'string', 'max:150'],
            'child_grade' => ['nullable', 'string', 'max:50'],
            'child_birthdate' => ['nullable', 'date'],
            'child_position' => ['nullable', 'string', 'max:255'],
        ]);

        $customer = DB::transaction(function () use ($data) {
            $customer = EMCustomer::create([
                'first_name' => trim($data['first_name']),
                'last_name' => $this->nullableTrim($data['last_name'] ?? null),
                'email' => strtolower(trim($data['email'])),
                'phone' => $this->nullableTrim($data['phone'] ?? null),
                'password' => Hash::make($data['password']),
                'parent_type' => 1,
                'account_type' => EMCustomer::ACCOUNT_TYPE_GUEST,
                'active' => 1,
            ]);

            $child = EMCustomerChild::create([
                'first_name' => trim($data['child_first_name']),
                'last_name' => $this->nullableTrim($data['child_last_name'] ?? null),
                'team' => $this->nullableTrim($data['child_team'] ?? null),
                'spring_team' => $this->nullableTrim($data['child_spring_team'] ?? null),
                'grade' => $this->nullableTrim($data['child_grade'] ?? null),
                'birthdate' => $data['child_birthdate'] ?? null,
                'position' => $this->nullableTrim($data['child_position'] ?? null),
                'is_active' => 1,
            ]);

            EMCustomerChildParent::create([
                'customer_id' => $customer->id,
                'child_id' => $child->id,
                'relationship' => 'Parent 1',
                'is_primary' => 1,
                'can_book' => 1,
                'can_pay' => 1,
                'can_pickup' => 0,
                'notes' => null,
            ]);

            return $customer;
        });

        $this->startCustomerSession($request, $customer);
        $this->mergeGuestCart($customer);

        return redirect()
            ->to(session()->pull('em_url_intended', route('em.customer.index')))
            ->with('success', 'Your Training account and player profile were created successfully.');
    }

    private function startCustomerSession(Request $request, EMCustomer $customer): void
    {
        $request->session()->regenerate();
        $request->session()->put('em_customer_id', $customer->id);
        $request->session()->put('em_customer_name', $customer->display_name);
        $request->session()->put(
            'em_customer_account_type',
            $customer->account_type ?: EMCustomer::ACCOUNT_TYPE_GUEST
        );
    }

    private function mergeGuestCart(EMCustomer $customer): void
    {
        $guestCart = session(self::GUEST_CART_SESSION_KEY, []);

        if (!is_array($guestCart) || $guestCart === []) {
            return;
        }

        DB::transaction(function () use ($customer, $guestCart) {
            $packages = EMPackage::query()
                ->where(function ($query) {
                    $query->whereNull('is_active')->orWhere('is_active', 1);
                })
                ->where(function ($query) {
                    $query->whereNull('active')->orWhere('active', 1);
                })
                ->where(function ($query) {
                    $query->whereNull('status')->orWhere('status', 'active');
                })
                ->whereIn('id', array_keys($guestCart))
                ->get()
                ->keyBy('id');

            foreach ($guestCart as $packageId => $quantity) {
                $package = $packages->get((int) $packageId);

                if (!$package) {
                    continue;
                }

                $quantity = max(1, min(10, (int) $quantity));

                $cart = EMCustomerPackageCart::firstOrNew([
                    'customer_id' => $customer->id,
                    'package_id' => $package->id,
                    'source_type' => 'direct',
                    'booking_id' => null,
                ]);

                $cart->quantity = min(
                    10,
                    ($cart->exists ? (int) $cart->quantity : 0) + $quantity
                );
                $cart->unit_price = $package->priceForCustomer($customer);
                $cart->total_price = round((float) $cart->unit_price * $cart->quantity, 2);
                $cart->save();
            }
        });

        session()->forget(self::GUEST_CART_SESSION_KEY);
    }

    private function nullableTrim($value): ?string
    {
        $value = trim((string) ($value ?? ''));

        return $value === '' ? null : $value;
    }
}
