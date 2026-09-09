<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EMCustomer;
use App\Models\EMCustomerChild;
use App\Models\EMPackagePaymentLog;
use App\Models\EMSessionBooking;
use App\Models\EMSessionEvent;

class DashboardController extends Controller
{
    private const CONFIRMED_BOOKING_STATUSES = ['booked', 'paid', 'completed'];

    public function index()
    {
        $totalParents = EMCustomer::where('active', 1)
            ->where('parent_type', 1)
            ->count();

        $totalPlayers = EMCustomerChild::where('is_active', 1)->count();

        // pending_payment records are Training cart reservations, not bookings.
        $totalBookings = EMSessionBooking::query()
            ->whereIn('status', self::CONFIRMED_BOOKING_STATUSES)
            ->count();

        $pendingPayments = (float) EMPackagePaymentLog::query()
            ->whereIn('status', ['pending', 'processing', 'failed'])
            ->sum('amount');

        $successfulPayments = (float) EMPackagePaymentLog::query()
            ->whereIn('status', ['success', 'paid', 'completed'])
            ->sum('amount');

        $start = now()->subDays(29)->startOfDay();

        $bookingRows = EMSessionBooking::query()
            ->selectRaw('DATE(COALESCE(booked_at, created_at)) as day, COUNT(*) as total')
            ->where('created_at', '>=', $start)
            ->whereIn('status', self::CONFIRMED_BOOKING_STATUSES)
            ->groupBy('day')
            ->pluck('total', 'day');

        $completedRows = EMSessionBooking::query()
            ->selectRaw('DATE(COALESCE(completed_at, updated_at)) as day, COUNT(*) as total')
            ->where('updated_at', '>=', $start)
            ->where('status', 'completed')
            ->groupBy('day')
            ->pluck('total', 'day');

        $chartLabels = [];
        $chartBookings = [];
        $chartCompleted = [];

        for ($i = 0; $i < 30; $i++) {
            $date = $start->copy()->addDays($i);
            $key = $date->toDateString();

            $chartLabels[] = $date->format('M j');
            $chartBookings[] = (int) ($bookingRows[$key] ?? 0);
            $chartCompleted[] = (int) ($completedRows[$key] ?? 0);
        }

        // Keep confirmed bookings and pending checkout holds separate. A pending
        // checkout can still reserve capacity without being shown as a booking.
        $upcomingSessions = EMSessionEvent::query()
            ->withCount([
                'bookings as booked_count' => fn ($query) => $query
                    ->whereIn('status', self::CONFIRMED_BOOKING_STATUSES),
                'bookings as pending_reservation_count' => fn ($query) => $query
                    ->where('status', 'pending_payment'),
            ])
            ->where('is_active', 1)
            ->whereDate('event_date', '>=', today())
            ->orderBy('event_date')
            ->orderBy('start_time')
            ->take(5)
            ->get();

        $recentPayments = EMPackagePaymentLog::with('customer')
            ->whereIn('status', ['success', 'paid', 'completed'])
            ->latest('paid_at')
            ->latest('id')
            ->take(5)
            ->get();

        $recentBookings = EMSessionBooking::with(['customer', 'child', 'sessionEvent'])
            ->whereIn('status', self::CONFIRMED_BOOKING_STATUSES)
            ->latest('booked_at')
            ->latest('id')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalParents',
            'totalPlayers',
            'totalBookings',
            'pendingPayments',
            'successfulPayments',
            'chartLabels',
            'chartBookings',
            'chartCompleted',
            'upcomingSessions',
            'recentPayments',
            'recentBookings'
        ));
    }
}
