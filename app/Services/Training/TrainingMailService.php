<?php

namespace App\Services\Training;

use App\Models\EMCustomer;
use App\Models\EMPackageOrder;
use App\Models\EMSessionBooking;
use App\Models\EMSessionEvent;
use Illuminate\Support\Collection;
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
                if ($bcc !== '' && strcasecmp($bcc, $to) !== 0) {
                    $message->bcc($bcc);
                }
            });
        } catch (\Throwable $e) {
            report($e);
        }
    }

    private function sendFamily(EMCustomer $customer, string $subject, string $view, array $data = [], array $extraEmails = []): void
    {
        $recipients = app(TrainingFamilyService::class)->recipients($customer, $extraEmails);
        if ($recipients === []) {
            return;
        }

        $primary = array_shift($recipients);
        $recipientEmails = collect(array_merge([$primary], $recipients))
            ->pluck('email')
            ->map(fn ($email) => strtolower(trim((string) $email)))
            ->filter()
            ->all();

        try {
            Mail::send($view, $data, function ($message) use ($primary, $recipients, $recipientEmails, $subject) {
                $message->to($primary['email'], $primary['name'] ?: null)->subject($subject);

                foreach ($recipients as $recipient) {
                    $message->cc($recipient['email'], $recipient['name'] ?: null);
                }

                $bcc = trim((string) config('services.training.mail_bcc'));
                if ($bcc !== '' && !in_array(strtolower($bcc), $recipientEmails, true)) {
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
        $billingEmail = optional($order->paymentLogs->first())->billing_email;

        $this->sendFamily(
            $customer,
            'ACES Lacrosse Training receipt ' . $order->order_no,
            'emails.training.receipt',
            compact('customer', 'order'),
            $billingEmail ? [$billingEmail] : []
        );
    }

    public function bookingCreated(EMSessionBooking $booking): void
    {
        // Booking confirmation emails are intentionally disabled.
        // Payment completion is confirmed by the payment receipt email only.
        return;
    }

    public function bookingUpdated(EMSessionBooking $booking, ?EMSessionEvent $oldSession, EMSessionEvent $newSession): void
    {
        $booking->loadMissing(['customer', 'child']);
        if (!$booking->customer) {
            return;
        }

        $this->sendFamily(
            $booking->customer,
            'Your ACES Lacrosse session was updated',
            'emails.training.booking-updated',
            compact('booking', 'oldSession', 'newSession')
        );
    }

    public function bookingCancelled(EMSessionBooking $booking, ?EMSessionEvent $session, bool $creditReturned): void
    {
        $booking->loadMissing(['customer', 'child']);
        if (!$booking->customer) {
            return;
        }

        $this->sendFamily(
            $booking->customer,
            'Your ACES Lacrosse booking was cancelled',
            'emails.training.booking-cancelled',
            compact('booking', 'session', 'creditReturned')
        );
    }

    public function reminder(EMSessionBooking $booking): void
    {
        $booking->loadMissing(['customer', 'child', 'sessionEvent']);
        if (!$booking->customer || !$booking->sessionEvent) {
            return;
        }

        $this->sendFamily(
            $booking->customer,
            'ACES Training reminder - ' . ($booking->sessionEvent->training_type ?: $booking->sessionEvent->name),
            'emails.training.session-reminder',
            compact('booking')
        );
    }

    public function familyReminder(EMCustomer $customer, Collection $sessionItems, string $todayDisplay): void
    {
        if ($sessionItems->isEmpty()) {
            return;
        }

        $this->sendFamily(
            $customer,
            'Reminder: Your ACES Lacrosse Training Session' . ($sessionItems->count() === 1 ? ' Is' : 's Are') . ' Today',
            'emails.training.family-session-reminder',
            compact('customer', 'sessionItems', 'todayDisplay')
        );
    }
}
