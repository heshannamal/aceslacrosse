<?php

namespace App\Http\Controllers;

use App\Models\EMCustomer;
use App\Models\EMCustomerPackageCart;
use App\Models\EMPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TrainingAuthController extends Controller
{
    private const GUEST_CART_SESSION_KEY = 'training_guest_cart';
    private const PASSWORD_SETUP_CUSTOMER_KEY = 'em_password_setup_customer_id';
    private const PASSWORD_SETUP_EMAIL_KEY = 'em_password_setup_email';

    public function login(Request $request)
    {
        if ($this->currentCustomer()) {
            return redirect()->route('em.customer.dashboard');
        }

        if ($request->filled('redirect')) {
            $request->session()->put('em_url_intended', $request->string('redirect')->toString());
        }

        return view('pages.customer_sessions.training-login', [
            'mode' => 'login',
            'email' => old('email'),
            'redirect' => $request->input('redirect'),
        ]);
    }

    public function loginSubmit(Request $request)
    {
        $emailData = $request->validate([
            'email' => ['required', 'email', 'max:190'],
        ]);

        $email = strtolower(trim($emailData['email']));

        if ($request->filled('redirect')) {
            $request->session()->put('em_url_intended', $request->string('redirect')->toString());
        }

        $customer = EMCustomer::query()
            ->whereRaw('LOWER(email) = ?', [$email])
            ->first();

        if (!$customer) {
            return back()
                ->withInput($request->only('email', 'redirect'))
                ->withErrors(['email' => 'Invalid email address.']);
        }

        if (!$customer->active) {
            return back()
                ->withInput($request->only('email', 'redirect'))
                ->withErrors(['email' => 'Your Training account is inactive.']);
        }

        /*
         * Match the Baddies first-time login behavior. Customers imported or
         * created by Admin may already exist without a password. Send those
         * customers to a dedicated password creation screen before login.
         */
        if (empty($customer->password)) {
            $request->session()->put(self::PASSWORD_SETUP_CUSTOMER_KEY, $customer->id);
            $request->session()->put(self::PASSWORD_SETUP_EMAIL_KEY, $customer->email);

            return redirect()
                ->route('em.customer.password.create')
                ->with('info_message', 'Your account is ready. Please create a password to continue.');
        }

        $passwordData = $request->validate([
            'password' => ['required', 'string'],
        ]);

        if (!Hash::check($passwordData['password'], $customer->password)) {
            return back()
                ->withInput($request->only('email', 'redirect'))
                ->withErrors(['password' => 'Invalid email or password.']);
        }

        $this->completeLogin($request, $customer);

        return $this->redirectAfterLogin($request)
            ->with('success', 'Welcome back to Alcatraz Outlaws Training.');
    }

    public function createPassword(Request $request)
    {
        if ($this->currentCustomer()) {
            return redirect()->route('em.customer.dashboard');
        }

        $customer = $this->passwordSetupCustomer($request);

        if (!$customer) {
            $this->clearPasswordSetup($request);

            return redirect()
                ->route('em.customer.login')
                ->with('error', 'Please enter your Training account email to create your password.');
        }

        if (!empty($customer->password)) {
            $this->clearPasswordSetup($request);

            return redirect()
                ->route('em.customer.login')
                ->with('success', 'Your password is already set. Please sign in.');
        }

        return view('pages.customer_sessions.training-login', [
            'mode' => 'set_password',
            'email' => $customer->email,
            'customer' => $customer,
            'redirect' => $request->session()->get('em_url_intended'),
            'info_message' => session('info_message') ?: 'Your parent account already exists. Create a password to continue booking sessions.',
        ]);
    }

    public function storePassword(Request $request)
    {
        $customer = $this->passwordSetupCustomer($request);

        if (!$customer) {
            $this->clearPasswordSetup($request);

            return redirect()
                ->route('em.customer.login')
                ->with('error', 'Your password setup session expired. Please enter your email again.');
        }

        if (!empty($customer->password)) {
            $this->clearPasswordSetup($request);

            return redirect()
                ->route('em.customer.login')
                ->with('success', 'Your password is already set. Please sign in.');
        }

        $data = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'password.confirmed' => 'The password confirmation does not match.',
            'password.min' => 'Your password must be at least 8 characters.',
        ]);

        $customer->password = Hash::make($data['password']);
        $customer->remember_token = null;
        $customer->save();

        $this->clearPasswordSetup($request);
        $this->completeLogin($request, $customer);

        return $this->redirectAfterLogin($request)
            ->with('success', 'Your Training password was created successfully.');
    }

    private function passwordSetupCustomer(Request $request): ?EMCustomer
    {
        $customerId = (int) $request->session()->get(self::PASSWORD_SETUP_CUSTOMER_KEY, 0);
        $email = strtolower(trim((string) $request->session()->get(self::PASSWORD_SETUP_EMAIL_KEY, '')));

        if ($customerId < 1 || $email === '') {
            return null;
        }

        return EMCustomer::query()
            ->whereKey($customerId)
            ->whereRaw('LOWER(email) = ?', [$email])
            ->where('active', 1)
            ->first();
    }

    private function clearPasswordSetup(Request $request): void
    {
        $request->session()->forget([
            self::PASSWORD_SETUP_CUSTOMER_KEY,
            self::PASSWORD_SETUP_EMAIL_KEY,
        ]);
    }

    private function currentCustomer(): ?EMCustomer
    {
        $customerId = (int) session('em_customer_id');

        if ($customerId < 1) {
            return null;
        }

        $customer = EMCustomer::query()
            ->whereKey($customerId)
            ->where('active', 1)
            ->first();

        if (!$customer) {
            session()->forget([
                'em_customer_id',
                'em_customer_name',
                'em_customer_account_type',
            ]);
        }

        return $customer;
    }

    private function completeLogin(Request $request, EMCustomer $customer): void
    {
        $request->session()->regenerate();
        $request->session()->put('em_customer_id', $customer->id);
        $request->session()->put('em_customer_name', $customer->display_name);
        $request->session()->put(
            'em_customer_account_type',
            $customer->account_type ?: EMCustomer::ACCOUNT_TYPE_USER
        );

        $this->mergeGuestCart($customer);
        $this->repriceCustomerCart($customer);
    }

    private function redirectAfterLogin(Request $request)
    {
        $target = $request->session()->pull('em_url_intended');

        if (!$target) {
            return redirect()->route('em.customer.index');
        }

        /* Only allow local application redirects. */
        $appUrl = rtrim((string) config('app.url'), '/');
        $isRelative = str_starts_with($target, '/') && !str_starts_with($target, '//');
        $isLocalAbsolute = $appUrl !== '' && str_starts_with($target, $appUrl . '/');

        if (!$isRelative && !$isLocalAbsolute) {
            return redirect()->route('em.customer.index');
        }

        return redirect()->to($target);
    }

    private function mergeGuestCart(EMCustomer $customer): void
    {
        $guestCart = session(self::GUEST_CART_SESSION_KEY, []);

        if (!is_array($guestCart) || $guestCart === []) {
            return;
        }

        DB::transaction(function () use ($customer, $guestCart) {
            $packages = $this->activePackageQuery()
                ->whereIn('id', array_keys($guestCart))
                ->get()
                ->keyBy('id');

            foreach ($guestCart as $packageId => $quantity) {
                $package = $packages->get((int) $packageId);
                if (!$package) {
                    continue;
                }

                $cart = EMCustomerPackageCart::firstOrNew([
                    'customer_id' => $customer->id,
                    'package_id' => $package->id,
                    'source_type' => 'direct',
                    'booking_id' => null,
                ]);

                $cart->quantity = min(
                    10,
                    ($cart->exists ? (int) $cart->quantity : 0) + max(1, (int) $quantity)
                );
                $cart->unit_price = $package->priceForCustomer($customer);
                $cart->total_price = round((float) $cart->unit_price * $cart->quantity, 2);
                $cart->save();
            }
        });

        session()->forget(self::GUEST_CART_SESSION_KEY);
    }

    private function repriceCustomerCart(EMCustomer $customer): void
    {
        $items = EMCustomerPackageCart::with('package')
            ->where('customer_id', $customer->id)
            ->get();

        foreach ($items as $item) {
            if (!$item->package) {
                continue;
            }

            $price = $item->package->priceForCustomer($customer);
            $quantity = max(1, (int) $item->quantity);
            $total = round($price * $quantity, 2);

            if ((float) $item->unit_price !== $price || (float) $item->total_price !== $total) {
                $item->unit_price = $price;
                $item->total_price = $total;
                $item->save();
            }
        }
    }

    private function activePackageQuery()
    {
        return EMPackage::query()
            ->where(function ($query) {
                $query->whereNull('is_active')->orWhere('is_active', 1);
            })
            ->where(function ($query) {
                $query->whereNull('active')->orWhere('active', 1);
            })
            ->where(function ($query) {
                $query->whereNull('status')->orWhere('status', 'active');
            });
    }
}
