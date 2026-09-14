<?php

namespace App\Console\Commands;

use App\Models\EMSessionBooking;
use App\Services\Training\TrainingFamilyService;
use App\Services\Training\TrainingMailService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class SendTodaySessionReminderEmails extends Command
{
    protected $signature = 'aces:send-today-session-reminders {--date= : Test date YYYY-MM-DD} {--force : Send even if already sent}';
    protected $description = 'Send one combined reminder per Training family for sessions held today.';

    private const TIMEZONE = 'America/Los_Angeles';

    public function handle(TrainingFamilyService $families, TrainingMailService $mail): int
    {
        try {
            $today = $this->option('date')
                ? Carbon::createFromFormat('Y-m-d', $this->option('date'), self::TIMEZONE)->toDateString()
                : Carbon::now(self::TIMEZONE)->toDateString();
        } catch (\Throwable $e) {
            $this->error('Invalid --date value. Use YYYY-MM-DD.');
            return self::FAILURE;
        }

        $query = EMSessionBooking::with(['customer', 'child', 'sessionEvent'])
            ->whereIn('status', ['booked', 'paid'])
            ->whereHas('sessionEvent', fn ($q) => $q->whereDate('event_date', $today));

        $hasSentColumn = Schema::hasColumn('em_session_bookings', 'reminder_email_sent_at');
        if (!$this->option('force') && $hasSentColumn) {
            $query->whereNull('reminder_email_sent_at');
        }

        $bookings = $query->orderBy('customer_id')->orderBy('id')->get();
        if ($bookings->isEmpty()) {
            $this->info('No unsent Training reminders for ' . $today . '.');
            return self::SUCCESS;
        }

        $groups = $bookings->groupBy(function ($booking) use ($families) {
            $key = $families->familyKey($booking->customer);
            return $key > 0 ? 'family-' . $key : 'customer-' . $booking->customer_id;
        });

        $sent = 0;
        $failed = 0;

        foreach ($groups as $familyKey => $familyBookings) {
            try {
                $customer = optional($familyBookings->first())->customer;
                if (!$customer) {
                    $failed++;
                    continue;
                }

                $sessionItems = $familyBookings->map(function ($booking) {
                    $session = $booking->sessionEvent;
                    if (!$session) {
                        return null;
                    }

                    $address = collect([$session->street_address, $session->city])
                        ->map(fn ($value) => trim((string) $value))
                        ->filter()
                        ->unique()
                        ->values();

                    return [
                        'booking' => $booking,
                        'session' => $session,
                        'child_name' => trim(($booking->child?->first_name ?? $booking->player_first ?? '') . ' ' . ($booking->child?->last_name ?? $booking->player_last ?? '')) ?: 'Player',
                        'session_name' => $session->training_type ?: ($session->name ?: 'Training Session'),
                        'date' => optional($session->event_date)->format('M d, Y') ?: $today,
                        'time' => $this->timeRange($session),
                        'location' => $address->isNotEmpty() ? $address->implode(', ') : (trim((string) $session->location) ?: '-'),
                        'instructor' => trim((string) $session->instructor) ?: '-',
                        'what_to_bring' => $session->what_to_bring,
                    ];
                })->filter()->values();

                if ($sessionItems->isEmpty()) {
                    $failed++;
                    continue;
                }

                $mail->familyReminder(
                    $customer,
                    $sessionItems,
                    Carbon::createFromFormat('Y-m-d', $today, self::TIMEZONE)->format('M d, Y')
                );

                if ($hasSentColumn) {
                    EMSessionBooking::query()
                        ->whereIn('id', $familyBookings->pluck('id'))
                        ->update(['reminder_email_sent_at' => now()]);
                }

                $sent++;
            } catch (\Throwable $e) {
                report($e);
                Log::error('ACES family Training reminder failed.', [
                    'family' => $familyKey,
                    'booking_ids' => $familyBookings->pluck('id')->all(),
                    'error' => $e->getMessage(),
                ]);
                $failed++;
            }
        }

        $this->info('Family reminders sent: ' . $sent . ', failed: ' . $failed . '.');
        return $failed > 0 ? self::FAILURE : self::SUCCESS;
    }

    private function timeRange($session): string
    {
        try {
            $start = $session->start_time ? Carbon::parse($session->start_time)->format('g:i A') : '';
            $end = $session->end_time ? Carbon::parse($session->end_time)->format('g:i A') : '';
            return trim($start . ($end ? ' - ' . $end : '')) ?: '-';
        } catch (\Throwable $e) {
            return '-';
        }
    }
}
