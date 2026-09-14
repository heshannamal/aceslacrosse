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
use App\Services\Training\TrainingFamilyService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use RuntimeException;

class FamilyTrainingPortalController extends TrainingPortalController
{
    private const FAMILY_ACTIVE_BOOKING_STATUSES = ['pending_payment', 'booked', 'paid', 'completed'];
    private const TIMEZONE = 'America/Los_Angeles';
    private const PROCESSING_FEE_RATE = 0.03;

    public function index()
    {
        $response = parent::index();
        $customer = $this->familyCustomer();

        if (!$customer || !($response instanceof \Illuminate\View\View)) {
            return $response;
        }

        $familyIds = app(TrainingFamilyService::class)->memberIds($customer);
        $summary = $this->familySummary($familyIds);
        $cartCount = $this->familyCartCount($familyIds);
        $familyBookings = EMSessionBooking::query()
            ->whereIn('customer_id', $familyIds)
            ->whereIn('status', ['booked', 'paid', 'completed'])
            ->get(['id', 'session_event_id', 'child_id', 'customer_child_id']);

        return $response->with([
            'children' => app(TrainingFamilyService::class)->children($customer),
            'summary' => $summary,
            'cartCount' => $cartCount,
            'trainingCartCount' => $cartCount,
            'remainingCredits' => $summary['remaining_credits'],
            'usedCredits' => $summary['used_credits'],
            'bookingsCount' => $summary['booked_sessions'],
            'pendingBookingsCount' => EMSessionBooking::query()
                ->whereIn('customer_id', $familyIds)
                ->where('status', 'pending_payment')
                ->count(),
            'familyBookedSessionIds' => $familyBookings->pluck('session_event_id')->filter()->unique()->values(),
        ]);
    }

    public function calendarEvents()
    {
        $response = parent::calendarEvents();
        $events = collect($response->getData(true));
        $customer = $this->familyCustomer();

        if (!$customer) {
            return response()->json($events->values());
        }

        $familyIds = app(TrainingFamilyService::class)->memberIds($customer);
        $bookingRows = EMSessionBooking::query()
            ->whereIn('customer_id', $familyIds)
            ->whereIn('status', self::FAMILY_ACTIVE_BOOKING_STATUSES)
            ->get(['session_event_id', 'status'])
            ->groupBy('session_event_id');

        return response()->json($events->map(function ($event) use ($bookingRows) {
            $rows = $bookingRows->get((int) ($event['id'] ?? 0), collect());
            $event['family_booked'] = $rows->whereIn('status', ['booked', 'paid', 'completed'])->isNotEmpty();
            $event['family_pending'] = $rows->where('status', 'pending_payment')->isNotEmpty();
            return $event;
        })->values());
    }

    public function packages()
    {
        $response = parent::packages();
        $customer = $this->familyCustomer();
        if ($customer && $response instanceof \Illuminate\View\View) {
            $response->with('trainingCartCount', $this->familyCartCount(app(TrainingFamilyService::class)->memberIds($customer)));
        }
        return $response;
    }

    public function packageDetails($id)
    {
        $response = parent::packageDetails($id);
        $customer = $this->familyCustomer();
        if ($customer && $response instanceof \Illuminate\View\View) {
            $response->with('trainingCartCount', $this->familyCartCount(app(TrainingFamilyService::class)->memberIds($customer)));
        }
        return $response;
    }

    public function cart()
    {
        $customer = $this->familyCustomer();
        if (!$customer) {
            return parent::cart();
        }

        $familyIds = app(TrainingFamilyService::class)->memberIds($customer);
        $this->repriceFamilyCart($customer, $familyIds);

        $cartItems = EMCustomerPackageCart::with(['package', 'booking.child', 'booking.sessionEvent'])
            ->whereIn('customer_id', $familyIds)
            ->orderBy('id')
            ->get();

        return view('pages.customer_sessions.cart', [
            'customer' => $customer,
            'cartItems' => $cartItems,
            'totals' => $this->familyCartTotals($cartItems),
            'summary' => $this->familySummary($familyIds),
            'trainingCartCount' => (int) $cartItems->sum('quantity'),
        ]);
    }

    public function updateCart(Request $request, $id)
    {
        $data = $request->validate(['quantity' => ['required', 'integer', 'min:1', 'max:10']]);
        $customer = $this->familyCustomer();
        if (!$customer) {
            return parent::updateCart($request, $id);
        }

        $familyIds = app(TrainingFamilyService::class)->memberIds($customer);
        $cart = EMCustomerPackageCart::with('package')
            ->whereIn('customer_id', $familyIds)
            ->findOrFail($id);

        $cart->quantity = (int) $data['quantity'];
        $cart->unit_price = $cart->package->priceForCustomer($customer);
        $cart->total_price = round((float) $cart->unit_price * $cart->quantity, 2);
        $cart->save();

        return back()->with('success', 'Shared family Training cart updated.');
    }

    public function removeCart($id)
    {
        $customer = $this->familyCustomer();
        if (!$customer) {
            return parent::removeCart($id);
        }

        $familyIds = app(TrainingFamilyService::class)->memberIds($customer);

        DB::transaction(function () use ($familyIds, $id) {
            $cart = EMCustomerPackageCart::query()
                ->whereIn('customer_id', $familyIds)
                ->lockForUpdate()
                ->findOrFail($id);

            if ($cart->booking_id) {
                EMSessionBooking::query()
                    ->whereKey($cart->booking_id)
                    ->whereIn('customer_id', $familyIds)
                    ->where('status', 'pending_payment')
                    ->delete();
            }

            $cart->delete();
        });

        return back()->with('success', 'Item removed from the shared family Training cart.');
    }

    public function checkout()
    {
        $customer = $this->familyCustomer();
        if (!$customer) {
            return parent::checkout();
        }

        $familyIds = app(TrainingFamilyService::class)->memberIds($customer);
        $this->repriceFamilyCart($customer, $familyIds);

        $cartItems = EMCustomerPackageCart::with(['package', 'booking.child', 'booking.sessionEvent'])
            ->whereIn('customer_id', $familyIds)
            ->orderBy('id')
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('em.customer.index')->with('error', 'Your shared family Training cart is empty.');
        }

        return view('pages.customer_sessions.checkout', [
            'customer' => $customer,
            'cartItems' => $cartItems,
            'totals' => $this->familyCartTotals($cartItems),
            'summary' => $this->familySummary($familyIds),
            'trainingCartCount' => (int) $cartItems->sum('quantity'),
        ]);
    }

    public function bookSession(Request $request, $id, \App\Services\Training\TrainingMailService $mail)
    {
        $customer = $this->familyCustomer();
        if (!$customer) {
            return parent::bookSession($request, $id, $mail);
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

        $family = app(TrainingFamilyService::class);
        $familyIds = $family->memberIds($customer);

        try {
            $result = DB::transaction(function () use ($customer, $familyIds, $id, $data) {
                $session = EMSessionEvent::query()->whereKey($id)->where('is_active', 1)->lockForUpdate()->first();
                if (!$session) {
                    throw new RuntimeException('Session not found.');
                }

                $sessionEnd = $this->familySessionEnd($session);
                if (!$sessionEnd || $sessionEnd->lt(now(self::TIMEZONE))) {
                    throw new RuntimeException('This session has already ended.');
                }

                $child = $this->resolveFamilyBookingChild($customer, $familyIds, $data);

                $familyPending = EMSessionBooking::query()
                    ->whereIn('customer_id', $familyIds)
                    ->where('session_event_id', $session->id)
                    ->where('status', 'pending_payment')
                    ->where(function ($query) use ($child) {
                        $query->where('child_id', $child->id)->orWhere('customer_child_id', $child->id);
                    })
                    ->exists();

                if ($familyPending) {
                    throw new RuntimeException('This player/session is already in your shared family cart. Either parent can open the cart, remove it, or complete checkout.');
                }

                $alreadyBooked = EMSessionBooking::query()
                    ->where('session_event_id', $session->id)
                    ->whereIn('status', ['booked', 'paid', 'completed'])
                    ->where(function ($query) use ($child) {
                        $query->where('child_id', $child->id)->orWhere('customer_child_id', $child->id);
                    })
                    ->exists();

                if ($alreadyBooked) {
                    throw new RuntimeException('This player is already booked for this session.');
                }

                $bookedCount = EMSessionBooking::query()
                    ->where('session_event_id', $session->id)
                    ->whereIn('status', self::FAMILY_ACTIVE_BOOKING_STATUSES)
                    ->lockForUpdate()
                    ->count();

                if ((int) $session->capacity > 0 && $bookedCount >= (int) $session->capacity) {
                    throw new RuntimeException('This session is fully booked.');
                }

                if ($data['booking_payment_method'] === 'credit') {
                    $credit = EMCustomerCredit::query()
                        ->whereIn('customer_id', $familyIds)
                        ->where('status', 'active')
                        ->where('remaining_classes', '>', 0)
                        ->orderByRaw('CASE WHEN customer_id = ? THEN 0 ELSE 1 END', [$customer->id])
                        ->orderBy('id')
                        ->lockForUpdate()
                        ->first();

                    if (!$credit) {
                        throw new RuntimeException('Your family does not have active credits. Select a package to continue.');
                    }

                    $booking = $this->createFamilyBooking($customer, $child, $session, [
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
                        'customer_id' => $credit->customer_id,
                        'credit_id' => $credit->id,
                        'booking_id' => $booking->id,
                        'type' => 'used',
                        'classes' => 1,
                        'note' => 'Shared family credit used by customer #' . $customer->id . ' for ' . ($session->training_type ?: $session->name),
                        'description' => 'One ACES Training family credit used.',
                    ]);

                    return [
                        'message' => 'Session booked successfully using 1 shared family credit.',
                        'redirect' => route('em.customer.bookings'),
                    ];
                }

                if (empty($data['selected_package_id'])) {
                    throw new RuntimeException('Please select a package to continue.');
                }

                $package = $this->familyActivePackageQuery()->whereKey($data['selected_package_id'])->lockForUpdate()->first();
                if (!$package) {
                    throw new RuntimeException('Selected package is no longer available.');
                }

                $booking = $this->createFamilyBooking($customer, $child, $session, [
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
                    'message' => 'Booking reserved in your shared family cart. Either parent can complete checkout.',
                    'redirect' => route('em.customer.cart'),
                ];
            });
        } catch (\Throwable $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['status' => false, 'message' => $e->getMessage() ?: 'Booking failed.'], 422);
            }
            return back()->with('error', $e->getMessage());
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['status' => true, 'message' => $result['message'], 'redirect' => $result['redirect']]);
        }

        return redirect()->to($result['redirect'])->with('success', $result['message']);
    }

    private function familyCustomer(): ?EMCustomer
    {
        $id = (int) session('em_customer_id');
        return $id > 0 ? EMCustomer::query()->whereKey($id)->where('active', 1)->first() : null;
    }

    private function resolveFamilyBookingChild(EMCustomer $customer, array $familyIds, array $data): EMCustomerChild
    {
        if (!empty($data['child_id'])) {
            $childId = (int) $data['child_id'];
            $linked = EMCustomerChildParent::query()
                ->whereIn('customer_id', $familyIds)
                ->where('child_id', $childId)
                ->exists();

            if (!$linked) {
                throw new RuntimeException('Selected player is not connected to your family Training account.');
            }

            $child = EMCustomerChild::query()->whereKey($childId)->where('is_active', 1)->first();
            if (!$child) {
                throw new RuntimeException('Selected player is unavailable.');
            }

            $this->ensureCurrentParentChildLink($customer, $child, $familyIds);
            return $child;
        }

        $firstName = trim((string) ($data['player_first'] ?? ''));
        if ($firstName === '') {
            throw new RuntimeException('Please choose an existing player or enter a new player first name.');
        }

        $positions = collect($data['positions'] ?? [])->map(fn ($position) => trim((string) $position))->filter()->unique()->implode(', ');
        $child = EMCustomerChild::create([
            'first_name' => $firstName,
            'last_name' => trim((string) ($data['player_last'] ?? '')) ?: null,
            'class_year' => !empty($data['grad_year']) ? (string) $data['grad_year'] : null,
            'position' => $positions ?: null,
            'is_active' => 1,
        ]);

        EMCustomerChildParent::create([
            'customer_id' => $customer->id,
            'child_id' => $child->id,
            'relationship' => (int) $customer->parent_type === 2 ? 'Parent 2' : 'Parent 1',
            'is_primary' => (int) $customer->parent_type === 1 ? 1 : 0,
            'can_book' => 1,
            'can_pay' => 1,
            'can_pickup' => 0,
            'notes' => null,
        ]);

        return $child;
    }

    private function ensureCurrentParentChildLink(EMCustomer $customer, EMCustomerChild $child, array $familyIds): void
    {
        if (EMCustomerChildParent::query()->where('customer_id', $customer->id)->where('child_id', $child->id)->exists()) {
            return;
        }

        $source = EMCustomerChildParent::query()->whereIn('customer_id', $familyIds)->where('child_id', $child->id)->orderByDesc('is_primary')->first();
        if (!$source) {
            return;
        }

        EMCustomerChildParent::updateOrCreate([
            'customer_id' => $customer->id,
            'child_id' => $child->id,
        ], [
            'relationship' => (int) $customer->parent_type === 2 ? 'Parent 2' : 'Parent 1',
            'is_primary' => (int) $customer->parent_type === 1 ? 1 : 0,
            'can_book' => (bool) $source->can_book,
            'can_pay' => (bool) $source->can_pay,
            'can_pickup' => (bool) $source->can_pickup,
            'notes' => $source->notes,
        ]);
    }

    private function createFamilyBooking(EMCustomer $customer, EMCustomerChild $child, EMSessionEvent $session, array $extra = []): EMSessionBooking
    {
        do {
            $bookingNo = 'BKG-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));
        } while (EMSessionBooking::query()->where('booking_no', $bookingNo)->exists());

        return EMSessionBooking::create(array_merge([
            'booking_no' => $bookingNo,
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

    private function familySummary(array $familyIds): array
    {
        $credits = EMCustomerCredit::query()->whereIn('customer_id', $familyIds)->get();

        return [
            'total_credits' => (int) $credits->sum('total_classes'),
            'used_credits' => (int) $credits->sum('used_classes'),
            'remaining_credits' => (int) $credits->sum('remaining_classes'),
            'booked_sessions' => EMSessionBooking::query()
                ->whereIn('customer_id', $familyIds)
                ->whereIn('status', ['booked', 'paid', 'completed'])
                ->count(),
        ];
    }

    private function familyCartCount(array $familyIds): int
    {
        return (int) EMCustomerPackageCart::query()->whereIn('customer_id', $familyIds)->sum('quantity');
    }

    private function repriceFamilyCart(EMCustomer $customer, array $familyIds): void
    {
        $items = EMCustomerPackageCart::with('package')->whereIn('customer_id', $familyIds)->get();
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

    private function familyCartTotals($items): array
    {
        $subtotal = round((float) $items->sum(fn ($item) => (float) $item->total_price), 2);
        $processingFee = round($subtotal * self::PROCESSING_FEE_RATE, 2);
        return [
            'subtotal' => $subtotal,
            'processing_fee' => $processingFee,
            'tax' => 0.00,
            'total' => round($subtotal + $processingFee, 2),
        ];
    }

    private function familySessionEnd(EMSessionEvent $session): ?Carbon
    {
        try {
            $date = Carbon::parse($session->event_date)->format('Y-m-d');
            $time = Carbon::parse($session->end_time ?: ($session->start_time ?: '23:59:59'))->format('H:i:s');
            return Carbon::parse($date . ' ' . $time, self::TIMEZONE);
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function familyActivePackageQuery()
    {
        return EMPackage::query()
            ->where(fn ($query) => $query->whereNull('is_active')->orWhere('is_active', 1))
            ->where(fn ($query) => $query->whereNull('active')->orWhere('active', 1))
            ->where(fn ($query) => $query->whereNull('status')->orWhere('status', 'active'));
    }
}
