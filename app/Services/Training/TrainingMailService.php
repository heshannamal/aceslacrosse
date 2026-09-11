<?php

namespace App\Services\Training;

use App\Models\EMCustomer;
use App\Models\EMPackageOrder;
use App\Models\EMSessionBooking;
use App\Models\EMSessionEvent;
use Illuminate\Support\Facades\Mail;

class TrainingMailService
{
    private function send(string $to, string $subject, string $view, array $data = []): void
    {
        if (trim($to) === '') {
            return;
        }

        try {
            Mail::send($view, $data, function ($message) use ($to, $subject) {
                $message->to($to)->subject($subject);
                $bcc = trim((string) config('services.training.mail_bcc'));
                if ($bcc !== '') {
                    $message->bcc($bcc);
                }
            });
        } catch (\Throwable $e) {
            report($e);
        }
    }

    public function passwordReset(EMCustomer $customer, string $url): void
    {
        $this->send($customer->email, 'Reset your ACES Lacrosse Training password', 'emails.training.password-reset', compact('customer', 'url'));
    }

    public function receipt(EMCustomer $customer, EMPackageOrder $order): void
    {
        $order->loadMissing(['items.package', 'paymentLogs']);
        $this->send($customer->email, 'ACES Lacrosse Training receipt ' . $order->order_no, 'emails.training.receipt', compact('customer', 'order'));
    }

    public function bookingCreated(EMSessionBooking $booking): void
    {
        /*
         * A booking created/confirmed by a paid checkout already belongs to an
         * order and is covered by the single payment receipt email. Skipping
         * that booking email prevents customers receiving two messages for the
         * same checkout. Credit-only and admin/manual bookings still receive
         * their normal confirmation email.
         */
        if (!empty($booking->order_id)) {
            return;
        }

        $booking->loadMissing(['customer', 'child', 'sessionEvent']);
        if (!$booking->customer) {
            return;
        }
        $this->send($booking->customer->email, 'Training booking confirmed - ACES Lacrosse', 'emails.training.booking-confirmed', compact('booking'));
    }

    public function bookingUpdated(EMSessionBooking $booking, ?EMSessionEvent $oldSession, EMSessionEvent $newSession): void
    {
        $booking->loadMissing(['customer', 'child']);
        if (!$booking->customer) {
            return;
        }
        $this->send($booking->customer->email, 'Your ACES Lacrosse session was updated', 'emails.training.booking-updated', compact('booking', 'oldSession', 'newSession'));
    }

    public function bookingCancelled(EMSessionBooking $booking, ?EMSessionEvent $session, bool $creditReturned): void
    {
        $booking->loadMissing(['customer', 'child']);
        if (!$booking->customer) {
            return;
        }
        $this->send($booking->customer->email, 'Your ACES Lacrosse booking was cancelled', 'emails.training.booking-cancelled', compact('booking', 'session', 'creditReturned'));
    }

    public function reminder(EMSessionBooking $booking): void
    {
        $booking->loadMissing(['customer', 'child', 'sessionEvent']);
        if (!$booking->customer || !$booking->sessionEvent) {
            return;
        }
        $this->send($booking->customer->email, 'ACES Training reminder - ' . ($booking->sessionEvent->training_type ?: $booking->sessionEvent->name), 'emails.training.session-reminder', compact('booking'));
    }
}
