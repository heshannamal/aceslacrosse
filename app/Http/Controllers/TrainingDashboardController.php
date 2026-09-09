<?php

namespace App\Http\Controllers;

use App\Models\EMCustomer;
use App\Models\EMCustomerCredit;
use App\Models\EMCustomerPackageCart;
use App\Models\EMSessionBooking;
use Carbon\Carbon;

class TrainingDashboardController extends Controller
{
    private const TIMEZONE = 'America/Los_Angeles';

    /**
     * Only confirmed/real bookings belong in the customer booking history.
     * pending_payment records are temporary checkout/cart reservations.
     */
    private const CUSTOMER_BOOKING_STATUSES = ['booked', 'paid', 'completed'];

    public function index()
    {
        return view('pages.customer_sessions.portal.dashboard', $this->dashboardData());
    }

    public function bookings()
    {
        return view('pages.customer_sessions.portal.bookings', $this->dashboardData());
    }

    private function dashboardData(): array
    {
        $customer = EMCustomer::query()
            ->whereKey(session('em_customer_id'))
            ->where('active', 1)
            ->firstOrFail();

        $credits = EMCustomerCredit::query()
            ->where('customer_id', $customer->id)
            ->orderByDesc('id')
            ->get();

        $remainingCredits = (int) $credits->sum(fn ($credit) => max(0, (int) ($credit->remaining_classes ?? 0)));
        $usedCredits = (int) $credits->sum(fn ($credit) => max(0, (int) ($credit->used_classes ?? 0)));

        $children = $customer->activeChildren()
            ->orderBy('em_customer_children.first_name')
            ->orderBy('em_customer_children.last_name')
            ->get();

        // pending_payment is intentionally excluded here. A pending-payment
        // booking is only a temporary reservation linked to the Training cart
        // until checkout succeeds.
        $bookings = EMSessionBooking::with(['child', 'sessionEvent', 'package'])
            ->where('customer_id', $customer->id)
            ->whereIn('status', self::CUSTOMER_BOOKING_STATUSES)
            ->orderByDesc('booked_at')
            ->orderByDesc('id')
            ->get();

        $now = now(self::TIMEZONE);

        $upcomingBookings = $bookings->filter(function (EMSessionBooking $booking) use ($now) {
            $end = $this->bookingSessionEnd($booking);
            return $end && $end->gte($now);
        })->sortBy(function (EMSessionBooking $booking) {
            $end = $this->bookingSessionEnd($booking);
            return $end ? $end->timestamp : PHP_INT_MAX;
        })->values();

        $pastBookings = $bookings->filter(function (EMSessionBooking $booking) use ($now) {
            $end = $this->bookingSessionEnd($booking);
            return $end && $end->lt($now);
        })->sortByDesc(function (EMSessionBooking $booking) {
            $end = $this->bookingSessionEnd($booking);
            return $end ? $end->timestamp : 0;
        })->values();

        $bookingsCount = $bookings->count();

        $trainingCartCount = (int) EMCustomerPackageCart::query()
            ->where('customer_id', $customer->id)
            ->sum('quantity');

        return compact(
            'customer',
            'credits',
            'children',
            'bookings',
            'upcomingBookings',
            'pastBookings',
            'remainingCredits',
            'usedCredits',
            'bookingsCount',
            'trainingCartCount'
        );
    }

    private function bookingSessionEnd(EMSessionBooking $booking): ?Carbon
    {
        $session = $booking->sessionEvent;
        if (!$session || empty($session->event_date)) {
            return null;
        }

        try {
            $date = $session->event_date instanceof Carbon
                ? $session->event_date->format('Y-m-d')
                : Carbon::parse($session->event_date)->format('Y-m-d');
            $time = !empty($session->end_time)
                ? Carbon::parse($session->end_time)->format('H:i:s')
                : (!empty($session->start_time) ? Carbon::parse($session->start_time)->format('H:i:s') : '23:59:59');

            return Carbon::parse($date.' '.$time, self::TIMEZONE);
        } catch (\Throwable $e) {
            return null;
        }
    }
}
