<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EMCustomer;
use App\Models\EMCustomerChild;
use App\Models\EMPackageOrder;
use App\Models\EMPackageOrderItem;
use App\Models\EMPackagePaymentLog;
use App\Models\EMSessionBooking;
use App\Models\EMSessionEvent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Throwable;

class TrainingEmailTestController extends Controller
{
    private const RECIPIENTS = [
        'kasunencoreit@gmail.com',
        'prasad@encorebrand.com',
    ];

    public function index()
    {
        return view('admin.email-tests.index', [
            'emails' => $this->emailDefinitions(),
            'recipients' => self::RECIPIENTS,
            'mailer' => config('mail.default'),
            'fromAddress' => config('mail.from.address'),
        ]);
    }

    public function send(Request $request, string $type)
    {
        $definitions = $this->emailDefinitions();
        abort_unless(array_key_exists($type, $definitions), 404);

        $definition = $definitions[$type];

        try {
            $data = $this->sampleData($type);

            foreach (self::RECIPIENTS as $recipient) {
                Mail::send($definition['view'], $data, function ($message) use ($recipient, $definition) {
                    $message
                        ->to($recipient)
                        ->subject('[TEST] ' . $definition['subject']);
                });
            }
        } catch (Throwable $e) {
            report($e);

            return back()->with(
                'error',
                'Test email failed: ' . $e->getMessage()
            );
        }

        return back()->with(
            'success',
            $definition['name'] . ' test email sent to ' . implode(' and ', self::RECIPIENTS) . '.'
        );
    }

    private function emailDefinitions(): array
    {
        return [
            'password-reset' => [
                'name' => 'Password Reset',
                'subject' => 'Reset your ACES Lacrosse Training password',
                'view' => 'emails.training.password-reset',
                'trigger' => 'Customer requests a Training password reset.',
                'icon' => 'fa-key',
            ],
            'receipt' => [
                'name' => 'Payment Receipt',
                'subject' => 'ACES Lacrosse Training payment receipt TEST01',
                'view' => 'emails.training.receipt',
                'trigger' => 'A Training package/order payment completes successfully.',
                'icon' => 'fa-receipt',
            ],
            'booking-updated' => [
                'name' => 'Booking Updated',
                'subject' => 'Your ACES Lacrosse session was updated',
                'view' => 'emails.training.booking-updated',
                'trigger' => 'An admin moves an existing Training booking to another session.',
                'icon' => 'fa-calendar-days',
            ],
            'booking-cancelled' => [
                'name' => 'Booking Cancelled',
                'subject' => 'Your ACES Lacrosse booking was cancelled',
                'view' => 'emails.training.booking-cancelled',
                'trigger' => 'A Training booking is cancelled.',
                'icon' => 'fa-calendar-xmark',
            ],
            'session-reminder' => [
                'name' => 'Session Reminder',
                'subject' => 'ACES Training reminder - Stickwork',
                'view' => 'emails.training.session-reminder',
                'trigger' => 'A reminder is sent before an upcoming Training session.',
                'icon' => 'fa-bell',
            ],
        ];
    }

    private function sampleData(string $type): array
    {
        $customer = new EMCustomer([
            'first_name' => 'Test',
            'last_name' => 'Parent',
            'email' => 'customer@aceslacrosse.com',
            'phone' => '(415) 555-0198',
            'active' => 1,
        ]);

        $child = new EMCustomerChild([
            'first_name' => 'Test',
            'last_name' => 'Player',
            'team' => 'ACES',
            'class_year' => '2030',
            'position' => 'Midfield',
            'is_active' => 1,
        ]);

        $sessionDate = Carbon::now('America/Los_Angeles')->addDays(7)->startOfDay();

        $session = new EMSessionEvent([
            'name' => 'Stickwork',
            'training_type' => 'Stickwork',
            'description' => 'ACES Lacrosse stickwork training builds confident catching, throwing, ball control and game-ready mechanics through focused repetition.',
            'street_address' => '395 Doherty Dr',
            'city' => 'San Rafael',
            'location' => 'San Rafael, CA',
            'instructor' => 'Prasad',
            'event_date' => $sessionDate,
            'start_time' => '17:00:00',
            'end_time' => '18:15:00',
            'what_to_bring' => 'Sneakers, Lacrosse Stick, Goggles, Water',
            'capacity' => 10,
            'is_active' => 1,
        ]);

        $booking = new EMSessionBooking([
            'booking_no' => 'BKG-' . $sessionDate->format('Ymd') . '-TEST01',
            'player_first' => $child->first_name,
            'player_last' => $child->last_name,
            'grad_year' => 2030,
            'positions' => 'Midfield',
            'status' => 'booked',
            'booked_at' => Carbon::now('America/Los_Angeles'),
        ]);
        $booking->setRelation('customer', $customer);
        $booking->setRelation('child', $child);
        $booking->setRelation('sessionEvent', $session);

        if ($type === 'password-reset') {
            return [
                'customer' => $customer,
                'url' => route('em.customer.password.reset', [
                    'token' => 'TEST-RESET-TOKEN-NOT-VALID',
                    'email' => $customer->email,
                ]),
            ];
        }

        if ($type === 'receipt') {
            $order = new EMPackageOrder([
                'order_no' => 'ACES-TR-' . Carbon::now()->format('Ymd') . '-TEST01',
                'subtotal' => 75.00,
                'tax' => 0.00,
                'processing_fee' => 2.25,
                'total' => 77.25,
                'payment_status' => 'paid',
                'payment_method' => 'card',
                'card_last_four' => '4242',
                'paid_at' => Carbon::now('America/Los_Angeles'),
            ]);

            $item = new EMPackageOrderItem([
                'package_name' => '1 Training Session',
                'quantity' => 1,
                'classes_per_package' => 1,
                'unit_price' => 75.00,
                'total_price' => 75.00,
            ]);

            $paymentLog = new EMPackagePaymentLog([
                'gateway' => 'authorize_net',
                'environment' => 'test',
                'payment_method' => 'card',
                'status' => 'paid',
                'subtotal' => 75.00,
                'processing_fee' => 2.25,
                'tax' => 0.00,
                'amount' => 77.25,
                'transaction_id' => 'TEST-120089880771',
                'auth_id' => 'TEST01',
                'ref_id' => 'ACES-TEST-REF',
                'name_on_card' => 'Test Parent',
                'card_last_four' => '4242',
                'billing_first_name' => 'Test',
                'billing_last_name' => 'Parent',
                'billing_email' => 'customer@aceslacrosse.com',
                'billing_phone' => '(415) 555-0198',
                'billing_street' => '395 Doherty Dr',
                'billing_city' => 'San Rafael',
                'billing_state' => 'CA',
                'billing_zip' => '94903',
                'billing_country' => 'USA',
                'quantity' => 1,
                'paid_at' => Carbon::now('America/Los_Angeles'),
            ]);

            $order->setRelation('customer', $customer);
            $order->setRelation('items', collect([$item]));
            $order->setRelation('paymentLogs', collect([$paymentLog]));

            return compact('customer', 'order');
        }

        if ($type === 'booking-updated') {
            $oldSession = new EMSessionEvent([
                'name' => 'Stickwork',
                'training_type' => 'Stickwork',
                'street_address' => '395 Doherty Dr',
                'city' => 'San Rafael',
                'location' => 'San Rafael, CA',
                'event_date' => $sessionDate->copy()->subDays(2),
                'start_time' => '17:00:00',
                'end_time' => '18:15:00',
            ]);

            $newSession = new EMSessionEvent([
                'name' => 'Fieldwork',
                'training_type' => 'Fieldwork',
                'street_address' => '50 Nova Albion Way',
                'city' => 'San Rafael',
                'location' => 'San Rafael, CA',
                'event_date' => $sessionDate,
                'start_time' => '18:30:00',
                'end_time' => '19:45:00',
                'what_to_bring' => 'Stick, Goggles, Cleats, Training Shoes, Water',
            ]);

            $booking->setRelation('sessionEvent', $newSession);

            return compact('booking', 'oldSession', 'newSession');
        }

        if ($type === 'booking-cancelled') {
            return [
                'booking' => $booking,
                'session' => $session,
                'creditReturned' => true,
            ];
        }

        return compact('booking');
    }
}
