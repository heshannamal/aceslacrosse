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

class FamilyTrainingBookingController extends Controller
{
    private const CONFIRMED_STATUSES = ['booked', 'paid', 'completed'];
    private const TIMEZONE = 'America/Los_Angeles';

    public function store(Request $request, $id, TrainingFamilyService $families)
    {
        $customer = EMCustomer::query()
            ->whereKey((int) session('em_customer_id'))
            ->where('active', 1)
            ->first();

        if (!$customer) {
            return $this->failure($request, 'Please login to continue.', 401);
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

        $familyIds = $families->memberIds($customer);

        try {
            $result = DB::transaction(function () use ($customer, $familyIds, $id, $data) {
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

                $child = $this->resolveChild($customer, $familyIds, $data);

                $pending = EMSessionBooking::query()
                    ->whereIn('customer_id', $familyIds)
                    ->where('session_event_id', $session->id)
                    ->where('status', 'pending_payment')
                    ->where(function ($query) use ($child) {
                        $query->where('child_id', $child->id)
                            ->orWhere('customer_child_id', $child->id);
                    })
                    ->exists();

                if ($pending) {
                    throw new RuntimeException('This player/session is already in the cart. You can remove it or complete checkout.');
                }

                $duplicate = EMSessionBooking::query()
                    ->where('session_event_id', $session->id)
                    ->whereIn('status', self::CONFIRMED_STATUSES)
                    ->where(function ($query) use ($child) {
                        $query->where('child_id', $child->id)
                            ->orWhere('customer_child_id', $child->id);
                    })
                    ->exists();

                if ($duplicate) {
                    throw new RuntimeException('This player is already booked for this session.');
                }

                // pending_payment belongs to the linked cart and does not
                // reserve capacity until the booking is actually confirmed.
                $confirmedCount = EMSessionBooking::query()
                    ->where('session_event_id', $session->id)
                    ->whereIn('status', self::CONFIRMED_STATUSES)
                    ->lockForUpdate()
                    ->count();

                if ((int) $session->capacity > 0 && $confirmedCount >= (int) $session->capacity) {
                    throw new RuntimeException('This session is fully booked.');
                }

                if ($data['booking_payment_method'] === 'credit') {
                    $credit = EMCustomerCredit::query()
                        ->whereIn('customer_id', $familyIds)
                        ->available()
                        ->orderByRaw('CASE WHEN customer_id = ? THEN 0 ELSE 1 END', [$customer->id])
                        ->orderBy('id')
                        ->lockForUpdate()
                        ->first();

                    if (!$credit) {
                        throw new RuntimeException('You do not have active Training credits. Select a package to continue.');
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
                        'customer_id' => $credit->customer_id,
                        'credit_id' => $credit->id,
                        'booking_id' => $booking->id,
                        'type' => 'used',
                        'classes' => 1,
                        'note' => 'Training credit used by customer #' . $customer->id . ' for ' . ($session->training_type ?: $session->name),
                        'description' => 'One ACES Training credit used.',
                    ]);

                    return [
                        'message' => 'Session booked successfully using 1 Training credit.',
                        'redirect' => route('em.customer.bookings'),
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
                    'message' => 'Booking reserved in your cart. You can complete checkout now.',
                    'redirect' => route('em.customer.cart'),
                ];
            });
        } catch (\Throwable $e) {
            return $this->failure($request, $e->getMessage() ?: 'Booking failed. Please try again.');
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

    private function resolveChild(EMCustomer $customer, array $familyIds, array $data): EMCustomerChild
    {
        if (!empty($data['child_id'])) {
            $childId = (int) $data['child_id'];
            $linked = EMCustomerChildParent::query()
                ->whereIn('customer_id', $familyIds)
                ->where('child_id', $childId)
                ->exists();

            if (!$linked) {
                throw new RuntimeException('Selected player is not connected to your Training account.');
            }

            $child = EMCustomerChild::query()->whereKey($childId)->where('is_active', 1)->first();
            if (!$child) {
                throw new RuntimeException('Selected player is unavailable.');
            }

            $this->ensureCurrentParentLink($customer, $child, $familyIds);
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
            ->implode(', ');

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

    private function ensureCurrentParentLink(EMCustomer $customer, EMCustomerChild $child, array $familyIds): void
    {
        if (EMCustomerChildParent::query()->where('customer_id', $customer->id)->where('child_id', $child->id)->exists()) {
            return;
        }

        $source = EMCustomerChildParent::query()
            ->whereIn('customer_id', $familyIds)
            ->where('child_id', $child->id)
            ->orderByDesc('is_primary')
            ->first();

        if (!$source) return;

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

    private function createBooking(EMCustomer $customer, EMCustomerChild $child, EMSessionEvent $session, array $extra): EMSessionBooking
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

    private function sessionEnd(EMSessionEvent $session): ?Carbon
    {
        try {
            $date = Carbon::parse($session->event_date)->format('Y-m-d');
            $time = Carbon::parse($session->end_time ?: ($session->start_time ?: '23:59:59'))->format('H:i:s');
            return Carbon::parse($date . ' ' . $time, self::TIMEZONE);
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function activePackageQuery()
    {
        return EMPackage::query()
            ->where(fn ($query) => $query->whereNull('is_active')->orWhere('is_active', 1))
            ->where(fn ($query) => $query->whereNull('active')->orWhere('active', 1))
            ->where(fn ($query) => $query->whereNull('status')->orWhere('status', 'active'));
    }

    private function failure(Request $request, string $message, int $status = 422)
    {
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['status' => false, 'message' => $message], $status);
        }

        return back()->with('error', $message);
    }
}
