<?php

namespace App\Http\Controllers;

use App\Models\EMCustomer;
use App\Models\EMCustomerChild;
use App\Models\EMCustomerChildParent;
use App\Models\EMCustomerCredit;
use App\Models\EMCustomerCreditLog;
use App\Models\EMCustomerPackageCart;
use App\Models\EMPackage;
use App\Models\EMSessionBooking;
use App\Models\EMSessionEvent;
use App\Services\Training\TrainingMailService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use RuntimeException;

class TrainingPortalController extends Controller
{
    private const ACTIVE_BOOKING_STATUSES = ['pending_payment', 'booked', 'paid', 'completed'];
    private const TIMEZONE = 'America/Los_Angeles';
    private const PROCESSING_FEE_RATE = 0.03;
    private const GUEST_CART_SESSION_KEY = 'training_guest_cart';

    public function login(Request $request)
    {
        if ($this->currentCustomer()) {
            return redirect()->route('em.customer.dashboard');
        }

        if ($request->filled('redirect')) {
            session()->put('em_url_intended', $request->string('redirect')->toString());
        }

        return view('pages.customer_sessions.auth', ['mode' => 'login']);
    }

    public function loginSubmit(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'max:190'],
            'password' => ['required', 'string'],
        ]);

        $customer = EMCustomer::query()
            ->whereRaw('LOWER(email) = ?', [strtolower(trim($data['email']))])
            ->where('active', 1)
            ->first();

        if (!$customer || !$customer->password || !Hash::check($data['password'], $customer->password)) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'The email address or password is incorrect.']);
        }

        $this->startCustomerSession($request, $customer);
        $this->mergeGuestCart($customer);
        $this->repriceCustomerCart($customer);

        return redirect()->to(
            session()->pull('em_url_intended', route('em.customer.index'))
        );
    }

    public function register(Request $request)
    {
        if ($this->currentCustomer()) {
            return redirect()->route('em.customer.dashboard');
        }

        if ($request->filled('redirect')) {
            session()->put('em_url_intended', $request->string('redirect')->toString());
        }

        return view('pages.customer_sessions.auth', ['mode' => 'register']);
    }

    public function registerSubmit(Request $request)
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
            'child_class_year' => ['nullable', 'string', 'max:20'],
            'child_birthdate' => ['nullable', 'date'],
            'child_position' => ['nullable', 'string', 'max:255'],
            'child_school' => ['nullable', 'string', 'max:150'],
            'child_gender' => ['nullable', 'string', 'max:30'],
            'child_medical_notes' => ['nullable', 'string'],
            'child_allergies' => ['nullable', 'string'],
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
                'class_year' => $this->nullableTrim($data['child_class_year'] ?? null),
                'birthdate' => $data['child_birthdate'] ?? null,
                'position' => $this->nullableTrim($data['child_position'] ?? null),
                'school' => $this->nullableTrim($data['child_school'] ?? null),
                'gender' => $this->nullableTrim($data['child_gender'] ?? null),
                'medical_notes' => $this->nullableTrim($data['child_medical_notes'] ?? null),
                'allergies' => $this->nullableTrim($data['child_allergies'] ?? null),
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
        $this->repriceCustomerCart($customer);

        return redirect()
            ->to(session()->pull('em_url_intended', route('em.customer.index')))
            ->with('success', 'Your Training account and player profile were created successfully.');
    }

    public function logout(Request $request)
    {
        $request->session()->forget([
            'em_customer_id',
            'em_customer_name',
            'em_customer_account_type',
            'em_url_intended',
        ]);

        // Training and Shop keep separate sessions. Never destroy the Shop login here.
        $request->session()->regenerateToken();

        return redirect()
            ->route('em.customer.index')
            ->with('success', 'You have been signed out of Training.');
    }

    public function index()
    {
        $customer = $this->currentCustomer();
        $calendarSessions = $this->upcomingSessions();
        $sessions = $calendarSessions->take(12)->values();
        $packages = $this->activePackages();
        $children = $customer
            ? $customer->activeChildren()->orderBy('first_name')->orderBy('last_name')->get()
            : collect();
        $summary = $customer ? $this->customerSummary($customer) : $this->emptySummary();
        $trainingCartCount = $this->trainingCartCount($customer);
        $pendingBookingsCount = $customer
            ? EMSessionBooking::query()->where('customer_id', $customer->id)->where('status', 'pending_payment')->count()
            : 0;

        return view('pages.customer_sessions.landing.index', [
            'customer' => $customer,
            'sessions' => $sessions,
            'calendarSessions' => $calendarSessions,
            'packages' => $packages,
            'children' => $children,
            'summary' => $summary,
            'cartCount' => $trainingCartCount,
            'trainingCartCount' => $trainingCartCount,
            'remainingCredits' => $summary['remaining_credits'],
            'usedCredits' => $summary['used_credits'],
            'bookingsCount' => $summary['booked_sessions'],
            'pendingBookingsCount' => $pendingBookingsCount,
        ]);
    }

    public function calendarEvents()
    {
        return response()->json(
            $this->upcomingSessions()->map(function (EMSessionEvent $session) {
                return [
                    'id' => $session->id,
                    'title' => $session->training_type ?: $session->name,
                    'date' => optional($session->event_date)->format('Y-m-d'),
                    'start' => $session->start_time,
                    'end' => $session->end_time,
                    'location' => $session->location,
                    'instructor' => $session->instructor,
                    'capacity' => (int) $session->capacity,
                    'booked' => (int) $session->bookings_count,
                    'spots_left' => $session->spots_left,
                    'full' => (bool) $session->is_full,
                ];
            })->values()
        );
    }

    public function packages()
    {
        $customer = $this->currentCustomer();

        return view('pages.customer_sessions.packages', [
            'customer' => $customer,
            'packages' => $this->activePackages(),
            'trainingCartCount' => $this->trainingCartCount($customer),
        ]);
    }

    public function packageDetails($id)
    {
        $customer = $this->currentCustomer();
        $package = $this->activePackageQuery()->findOrFail($id);

        return view('pages.customer_sessions.package-details', [
            'customer' => $customer,
            'package' => $package,
            'trainingCartCount' => $this->trainingCartCount($customer),
        ]);
    }

    public function addToCart(Request $request, $id)
    {
        $package = $this->activePackageQuery()->findOrFail($id);
        $quantity = max(1, min(10, (int) $request->input('quantity', 1)));
        $customer = $this->currentCustomer();

        if ($customer) {
            $cart = EMCustomerPackageCart::firstOrNew([
                'customer_id' => $customer->id,
                'package_id' => $package->id,
                'source_type' => 'direct',
                'booking_id' => null,
            ]);

            $cart->quantity = min(10, ($cart->exists ? (int) $cart->quantity : 0) + $quantity);
            $cart->unit_price = $package->priceForCustomer($customer);
            $cart->total_price = round((float) $cart->unit_price * $cart->quantity, 2);
            $cart->save();
        } else {
            $guestCart = $this->guestCart();
            $guestCart[$package->id] = min(10, ((int) ($guestCart[$package->id] ?? 0)) + $quantity);
            session([self::GUEST_CART_SESSION_KEY => $guestCart]);
        }

        return redirect()
            ->route('em.customer.cart')
            ->with('success', 'Training package added to your cart.');
    }

    public function cart()
    {
        $customer = $this->currentCustomer();

        if ($customer) {
            $this->mergeGuestCart($customer);
            $this->repriceCustomerCart($customer);

            $cartItems = EMCustomerPackageCart::with([
                'package',
                'booking.child',
                'booking.sessionEvent',
            ])->where('customer_id', $customer->id)->orderBy('id')->get();

            return view('pages.customer_sessions.cart', [
                'customer' => $customer,
                'cartItems' => $cartItems,
                'totals' => $this->cartTotals($cartItems),
                'summary' => $this->customerSummary($customer),
                'trainingCartCount' => (int) $cartItems->sum('quantity'),
            ]);
        }

        $guestCart = $this->guestCart();
        $packages = $this->activePackageQuery()
            ->whereIn('id', array_keys($guestCart))
            ->get()
            ->keyBy('id');

        $cartItems = collect();
        foreach ($guestCart as $packageId => $quantity) {
            $package = $packages->get((int) $packageId);
            if (!$package) {
                continue;
            }

            $quantity = max(1, min(10, (int) $quantity));
            $price = $package->priceForCustomer(null);

            $cartItems->push((object) [
                'id' => $package->id,
                'package' => $package,
                'quantity' => $quantity,
                'unit_price' => $price,
                'total_price' => round($price * $quantity, 2),
            ]);
        }

        session([
            self::GUEST_CART_SESSION_KEY => $cartItems
                ->mapWithKeys(function ($item) {
                    return [$item->package->id => $item->quantity];
                })
                ->all(),
        ]);

        return view('pages.customer_sessions.public-cart', [
            'customer' => null,
            'cartItems' => $cartItems,
            'totals' => $this->cartTotals($cartItems),
            'trainingCartCount' => (int) $cartItems->sum('quantity'),
        ]);
    }

    public function updateCart(Request $request, $id)
    {
        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:10'],
        ]);
        $customer = $this->currentCustomer();

        if ($customer) {
            $cart = EMCustomerPackageCart::with('package')
                ->where('customer_id', $customer->id)
                ->findOrFail($id);

            $cart->quantity = (int) $data['quantity'];
            $cart->unit_price = $cart->package->priceForCustomer($customer);
            $cart->total_price = round((float) $cart->unit_price * $cart->quantity, 2);
            $cart->save();
        } else {
            $guestCart = $this->guestCart();
            if (!array_key_exists((int) $id, $guestCart) && !array_key_exists((string) $id, $guestCart)) {
                abort(404);
            }

            $guestCart[(int) $id] = (int) $data['quantity'];
            session([self::GUEST_CART_SESSION_KEY => $guestCart]);
        }

        return back()->with('success', 'Training cart updated.');
    }

    public function removeCart($id)
    {
        $customer = $this->currentCustomer();

        if ($customer) {
            DB::transaction(function () use ($customer, $id) {
                $cart = EMCustomerPackageCart::query()
                    ->where('customer_id', $customer->id)
                    ->lockForUpdate()
                    ->findOrFail($id);

                if ($cart->booking_id) {
                    EMSessionBooking::query()
                        ->where('id', $cart->booking_id)
                        ->where('customer_id', $customer->id)
                        ->where('status', 'pending_payment')
                        ->delete();
                }

                $cart->delete();
            });
        } else {
            $guestCart = $this->guestCart();
            unset($guestCart[(int) $id], $guestCart[(string) $id]);
            session([self::GUEST_CART_SESSION_KEY => $guestCart]);
        }

        return back()->with('success', 'Item removed from your Training cart.');
    }

    public function checkout()
    {
        $customer = $this->currentCustomer();

        if (!$customer) {
            if ($this->trainingCartCount(null) < 1) {
                return redirect()
                    ->route('em.customer.packages')
                    ->with('error', 'Your Training cart is empty.');
            }

            session()->put('em_url_intended', route('em.customer.checkout'));

            return redirect()
                ->route('em.customer.login')
                ->with('error', 'Sign in or create a Training account to continue checkout.');
        }

        $this->mergeGuestCart($customer);
        $this->repriceCustomerCart($customer);

        $cartItems = EMCustomerPackageCart::with([
            'package',
            'booking.child',
            'booking.sessionEvent',
        ])->where('customer_id', $customer->id)->orderBy('id')->get();

        if ($cartItems->isEmpty()) {
            return redirect()
                ->route('em.customer.index')
                ->with('error', 'Your Training cart is empty.');
        }

        return view('pages.customer_sessions.checkout', [
            'customer' => $customer,
            'cartItems' => $cartItems,
            'totals' => $this->cartTotals($cartItems),
            'summary' => $this->customerSummary($customer),
            'trainingCartCount' => (int) $cartItems->sum('quantity'),
        ]);
    }

    /**
     * Baddies-style two-step booking endpoint used by the Training landing modal.
     */
    public function bookSession(Request $request, $id, TrainingMailService $mail)
    {
        $customer = $this->currentCustomer();
        if (!$customer) {
            return $this->bookingFailure($request, 'Please login to continue.', 401);
        }

        $data = $request->validate([
            'child_id' => ['nullable', 'integer'],
            'booking_payment_method' => ['required', Rule::in(['credit', 'package'])],
            'selected_package_id' => ['nullable', 'integer'],
            'player_first' => ['nullable', 'string', 'max:190'],
            'player_last' => ['nullable', 'string', 'max:190'],
            'grad_year' => ['nullable', 'integer', 'min:2000', 'max:2100'],
            'positions' => ['nullable', 'array'],
            'positions.*' => ['nullable', 'string', 'max:100'],
        ]);

        try {
            $result = DB::transaction(function () use ($customer, $id, $data) {
                $session = EMSessionEvent::query()
                    ->whereKey($id)
                    ->where('is_active', 1)
                    ->lockForUpdate()
                    ->first();

                if (!$session) {
                    throw new RuntimeException('Session not found.');
                }

                $sessionEnd = $this->sessionEnd($session);
                if (!$sessionEnd || $sessionEnd->lt(now(self::TIMEZONE))) {
                    throw new RuntimeException('This session has already ended.');
                }

                $bookedCount = EMSessionBooking::query()
                    ->where('session_event_id', $session->id)
                    ->whereIn('status', self::ACTIVE_BOOKING_STATUSES)
                    ->lockForUpdate()
                    ->count();

                if ((int) $session->capacity > 0 && $bookedCount >= (int) $session->capacity) {
                    throw new RuntimeException('This session is fully booked.');
                }

                $child = $this->resolveBookingChild($customer, $data);

                $duplicate = EMSessionBooking::query()
                    ->where('session_event_id', $session->id)
                    ->whereIn('status', self::ACTIVE_BOOKING_STATUSES)
                    ->where(function ($query) use ($child) {
                        $query->where('child_id', $child->id)
                            ->orWhere('customer_child_id', $child->id);
                    })
                    ->exists();

                if ($duplicate) {
                    throw new RuntimeException('This player is already booked for this session.');
                }

                if ($data['booking_payment_method'] === 'credit') {
                    $credit = EMCustomerCredit::query()
                        ->where('customer_id', $customer->id)
                        ->where('status', 'active')
                        ->where('remaining_classes', '>', 0)
                        ->where(function ($query) {
                            $query->whereNull('valid_until')
                                ->orWhereDate('valid_until', '>=', now(self::TIMEZONE)->toDateString());
                        })
                        ->orderByRaw('valid_until IS NULL')
                        ->orderBy('valid_until')
                        ->orderBy('id')
                        ->lockForUpdate()
                        ->first();

                    if (!$credit) {
                        throw new RuntimeException('You do not have active credits. Select a package to continue.');
                    }

                    $booking = $this->createBooking($customer, $child, $session, [
                        'package_id' => $credit->package_id,
                        'credit_id' => $credit->id,
                        'status' => 'booked',
                        'booked_at' => now(),
                    ]);

                    $credit->remaining_classes = max(0, (int) $credit->remaining_classes - 1);
                    $credit->used_classes = (int) $credit->used_classes + 1;
                    if ((int) $credit->remaining_classes <= 0) {
                        $credit->status = 'used';
                    }
                    $credit->save();

                    EMCustomerCreditLog::create([
                        'customer_id' => $customer->id,
                        'credit_id' => $credit->id,
                        'booking_id' => $booking->id,
                        'type' => 'used',
                        'classes' => 1,
                        'note' => 'Credit used for session booking: ' . ($session->training_type ?: $session->name),
                        'description' => 'One Alcatraz Outlaws Training credit used.',
                    ]);

                    return [
                        'booking' => $booking,
                        'message' => 'Session booked successfully using 1 credit.',
                        'redirect' => route('em.customer.bookings'),
                        'email' => true,
                    ];
                }

                if (empty($data['selected_package_id'])) {
                    throw new RuntimeException('Please select a package to continue.');
                }

                $package = $this->activePackageQuery()
                    ->whereKey($data['selected_package_id'])
                    ->lockForUpdate()
                    ->first();

                if (!$package) {
                    throw new RuntimeException('Selected package is no longer available.');
                }

                $booking = $this->createBooking($customer, $child, $session, [
                    'package_id' => $package->id,
                    'status' => 'pending_payment',
                    'booked_at' => now(),
                ]);

                $price = $package->priceForCustomer($customer);
                $cart = EMCustomerPackageCart::create([
                    'customer_id' => $customer->id,
                    'booking_id' => $booking->id,
                    'package_id' => $package->id,
                    'source_type' => 'booking',
                    'quantity' => 1,
                    'unit_price' => $price,
                    'total_price' => $price,
                ]);

                $booking->cart_id = $cart->id;
                $booking->save();

                return [
                    'booking' => $booking,
                    'message' => 'Booking reserved. Complete checkout to confirm the session.',
                    'redirect' => route('em.customer.cart'),
                    'email' => false,
                ];
            });
        } catch (\Throwable $e) {
            return $this->bookingFailure($request, $e->getMessage() ?: 'Booking failed. Please try again.', 422);
        }

        if (!empty($result['email'])) {
            $mail->bookingCreated($result['booking']);
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'status' => true,
                'message' => $result['message'],
                'redirect' => $result['redirect'],
            ]);
        }

        return redirect()->to($result['redirect'])->with('success', $result['message']);
    }

    private function bookingFailure(Request $request, string $message, int $status = 422)
    {
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'status' => false,
                'message' => $message,
            ], $status);
        }

        return back()->with('error', $message);
    }

    private function resolveBookingChild(EMCustomer $customer, array $data): EMCustomerChild
    {
        if (!empty($data['child_id'])) {
            $child = $customer->activeChildren()
                ->where('em_customer_children.id', (int) $data['child_id'])
                ->first();

            if (!$child) {
                throw new RuntimeException('Selected player is invalid or is not connected to your Training account.');
            }

            return $child;
        }

        $firstName = trim((string) ($data['player_first'] ?? ''));
        if ($firstName === '') {
            throw new RuntimeException('Please choose an existing player or enter a new player first name.');
        }

        $positions = collect($data['positions'] ?? [])
            ->map(fn ($position) => trim((string) $position))
            ->filter()
            ->unique()
            ->values()
            ->implode(', ');

        $child = EMCustomerChild::create([
            'first_name' => $firstName,
            'last_name' => $this->nullableTrim($data['player_last'] ?? null),
            'class_year' => !empty($data['grad_year']) ? (string) $data['grad_year'] : null,
            'position' => $positions ?: null,
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

        return $child;
    }

    private function createBooking(
        EMCustomer $customer,
        EMCustomerChild $child,
        EMSessionEvent $session,
        array $extra = []
    ): EMSessionBooking {
        return EMSessionBooking::create(array_merge([
            'booking_no' => $this->generateBookingNumber(),
            'customer_id' => $customer->id,
            'child_id' => $child->id,
            'customer_child_id' => $child->id,
            'session_event_id' => $session->id,
            'player_first' => $child->first_name,
            'player_last' => $child->last_name,
            'grad_year' => is_numeric($child->class_year) ? (int) $child->class_year : null,
            'positions' => $child->position,
        ], $extra));
    }

    private function generateBookingNumber(): string
    {
        do {
            $number = 'BKG-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));
        } while (EMSessionBooking::query()->where('booking_no', $number)->exists());

        return $number;
    }

    private function currentCustomer(): ?EMCustomer
    {
        $id = session('em_customer_id');
        if (!$id) {
            return null;
        }

        $customer = EMCustomer::query()->whereKey($id)->where('active', 1)->first();

        if (!$customer) {
            session()->forget([
                'em_customer_id',
                'em_customer_name',
                'em_customer_account_type',
            ]);
            return null;
        }

        session([
            'em_customer_name' => $customer->display_name,
            'em_customer_account_type' => $customer->account_type ?: EMCustomer::ACCOUNT_TYPE_USER,
        ]);

        return $customer;
    }

    private function startCustomerSession(Request $request, EMCustomer $customer): void
    {
        $request->session()->regenerate();
        $request->session()->put('em_customer_id', $customer->id);
        $request->session()->put('em_customer_name', $customer->display_name);
        $request->session()->put(
            'em_customer_account_type',
            $customer->account_type ?: EMCustomer::ACCOUNT_TYPE_USER
        );
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

    private function activePackages()
    {
        return $this->activePackageQuery()
            ->orderBy('available_classes')
            ->orderBy('package_price')
            ->get();
    }

    private function upcomingSessions()
    {
        $sessions = EMSessionEvent::query()
            ->where('is_active', 1)
            ->orderBy('event_date')
            ->orderBy('start_time')
            ->get();

        $counts = collect();
        $ids = $sessions->pluck('id')->filter()->values();

        if ($ids->isNotEmpty()) {
            $counts = EMSessionBooking::query()
                ->whereIn('session_event_id', $ids)
                ->whereIn('status', self::ACTIVE_BOOKING_STATUSES)
                ->selectRaw('session_event_id, COUNT(*) total')
                ->groupBy('session_event_id')
                ->pluck('total', 'session_event_id');
        }

        $now = now(self::TIMEZONE);

        return $sessions
            ->map(function (EMSessionEvent $session) use ($counts) {
                $session->bookings_count = (int) ($counts[$session->id] ?? 0);
                $session->spots_left = (int) $session->capacity > 0
                    ? max(0, (int) $session->capacity - $session->bookings_count)
                    : null;
                $session->is_full = (int) $session->capacity > 0
                    && $session->bookings_count >= (int) $session->capacity;
                $session->ends_at = $this->sessionEnd($session);
                return $session;
            })
            ->filter(function (EMSessionEvent $session) use ($now) {
                return $session->ends_at && $session->ends_at->gte($now);
            })
            ->values();
    }

    private function sessionEnd(EMSessionEvent $session): ?Carbon
    {
        try {
            $date = $session->event_date instanceof Carbon
                ? $session->event_date->format('Y-m-d')
                : Carbon::parse($session->event_date)->format('Y-m-d');
            $time = Carbon::parse($session->end_time ?: ($session->start_time ?: '23:59:59'))
                ->format('H:i:s');

            return Carbon::parse($date . ' ' . $time, self::TIMEZONE);
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function customerSummary(EMCustomer $customer): array
    {
        $credits = EMCustomerCredit::query()->where('customer_id', $customer->id)->get();

        return [
            'total_credits' => (int) $credits->sum('total_classes'),
            'used_credits' => (int) $credits->sum('used_classes'),
            'remaining_credits' => (int) $credits->sum('remaining_classes'),
            'booked_sessions' => EMSessionBooking::query()
                ->where('customer_id', $customer->id)
                ->whereIn('status', ['booked', 'paid', 'completed'])
                ->count(),
        ];
    }

    private function emptySummary(): array
    {
        return [
            'total_credits' => 0,
            'used_credits' => 0,
            'remaining_credits' => 0,
            'booked_sessions' => 0,
        ];
    }

    private function trainingCartCount(?EMCustomer $customer): int
    {
        if ($customer) {
            return (int) EMCustomerPackageCart::query()
                ->where('customer_id', $customer->id)
                ->sum('quantity');
        }

        return (int) collect($this->guestCart())->sum();
    }

    private function guestCart(): array
    {
        $cart = session(self::GUEST_CART_SESSION_KEY, []);
        return is_array($cart) ? $cart : [];
    }

    private function mergeGuestCart(EMCustomer $customer): void
    {
        $guestCart = $this->guestCart();
        if ($guestCart === []) {
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
            $total = round($price * max(1, (int) $item->quantity), 2);

            if ((float) $item->unit_price !== (float) $price || (float) $item->total_price !== (float) $total) {
                $item->unit_price = $price;
                $item->total_price = $total;
                $item->save();
            }
        }
    }

    private function cartTotals($items): array
    {
        $subtotal = round((float) $items->sum(function ($item) {
            return (float) $item->total_price;
        }), 2);
        $processingFee = round($subtotal * self::PROCESSING_FEE_RATE, 2);

        return [
            'subtotal' => $subtotal,
            'processing_fee' => $processingFee,
            'tax' => 0.00,
            'total' => round($subtotal + $processingFee, 2),
        ];
    }

    private function nullableTrim($value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim((string) $value);
        return $value === '' ? null : $value;
    }
}
