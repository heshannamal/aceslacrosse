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
        $this->send($customer->email, 'Reset your Alcatraz Outlaws Training password', 'emails.training.password-reset', compact('customer', 'url'));
    }

    public function receipt(EMCustomer $customer, EMPackageOrder $order): void
    {
        $order->loadMissing(['items.package', 'paymentLogs']);
        $this->send($customer->email, 'Alcatraz Outlaws Training receipt ' . $order->order_no, 'emails.training.receipt', compact('customer', 'order'));
    }

    public function bookingCreated(EMSessionBooking $booking): void
    {
        $booking->loadMissing(['customer', 'child', 'sessionEvent']);
        if (!$booking->customer) {
            return;
        }
        $this->send($booking->customer->email, 'Training booking confirmed - Alcatraz Outlaws', 'emails.training.booking-confirmed', compact('booking'));
    }

    public function bookingUpdated(EMSessionBooking $booking, ?EMSessionEvent $oldSession, EMSessionEvent $newSession): void
    {
        $booking->loadMissing(['customer', 'child']);
        if (!$booking->customer) {
            return;
        }
        $this->send($booking->customer->email, 'Your Alcatraz Outlaws session was updated', 'emails.training.booking-updated', compact('booking', 'oldSession', 'newSession'));
    }

    public function bookingCancelled(EMSessionBooking $booking, ?EMSessionEvent $session, bool $creditReturned): void
    {
        $booking->loadMissing(['customer', 'child']);
        if (!$booking->customer) {
            return;
        }
        $this->send($booking->customer->email, 'Your Alcatraz Outlaws booking was cancelled', 'emails.training.booking-cancelled', compact('booking', 'session', 'creditReturned'));
    }

    public function reminder(EMSessionBooking $booking): void
    {
        $booking->loadMissing(['customer', 'child', 'sessionEvent']);
        if (!$booking->customer || !$booking->sessionEvent) {
            return;
        }
        $this->send($booking->customer->email, 'Training reminder - ' . ($booking->sessionEvent->training_type ?: $booking->sessionEvent->name), 'emails.training.session-reminder', compact('booking'));
    }
}
