<?php

namespace App\Http\Controllers;

use App\Models\EMCustomer;
use App\Models\EMCustomerCredit;
use App\Models\EMCustomerCreditLog;
use App\Models\EMCustomerPackageCart;
use App\Models\EMSessionBooking;
use App\Services\Training\AuthorizeNetService;
use App\Services\Training\TrainingFamilyService;
use App\Services\Training\TrainingMailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FamilyEMCustomerController extends EMCustomerController
{
    public function pay(Request $request, AuthorizeNetService $gateway, TrainingMailService $mail)
    {
        $customer = $this->currentFamilyCustomer();
        if (!$customer) {
            return parent::pay($request, $gateway, $mail);
        }

        $familyIds = app(TrainingFamilyService::class)->memberIds($customer);
        $beforeCreditId = (int) EMCustomerCredit::query()
            ->whereIn('customer_id', $familyIds)
            ->max('id');

        /*
         * The legacy payment routine expects one customer_id. Before charging,
         * atomically hand the shared family cart and its pending reservations to
         * the parent who is actually paying. Order, credits and payment audit
         * rows therefore continue through the existing proven checkout path.
         */
        DB::transaction(function () use ($customer, $familyIds) {
            $carts = EMCustomerPackageCart::query()
                ->whereIn('customer_id', $familyIds)
                ->lockForUpdate()
                ->get();

            $bookingIds = $carts->pluck('booking_id')->filter()->unique()->values();

            if ($bookingIds->isNotEmpty()) {
                EMSessionBooking::query()
                    ->whereIn('id', $bookingIds)
                    ->whereIn('customer_id', $familyIds)
                    ->where('status', 'pending_payment')
                    ->update(['customer_id' => $customer->id]);
            }

            EMCustomerPackageCart::query()
                ->whereIn('id', $carts->pluck('id'))
                ->update(['customer_id' => $customer->id]);
        });

        $response = parent::pay($request, $gateway, $mail);

        // ACES credits have no start/end validity window. Any credits created by
        // this successful family checkout remain available until fully used.
        EMCustomerCredit::query()
            ->where('customer_id', $customer->id)
            ->where('id', '>', $beforeCreditId)
            ->update([
                'valid_from' => null,
                'valid_until' => null,
            ]);

        return $response;
    }

    public function cancelBooking($id, TrainingMailService $mail)
    {
        $customer = $this->currentFamilyCustomer();
        if (!$customer) {
            return parent::cancelBooking($id, $mail);
        }

        $familyIds = app(TrainingFamilyService::class)->memberIds($customer);
        $creditReturned = false;

        $booking = DB::transaction(function () use ($familyIds, $id, &$creditReturned) {
            $booking = EMSessionBooking::with(['customer', 'child', 'sessionEvent'])
                ->whereIn('customer_id', $familyIds)
                ->lockForUpdate()
                ->findOrFail($id);

            if (in_array($booking->status, ['cancelled', 'refunded'], true)) {
                return $booking;
            }

            if ($booking->credit_id) {
                $credit = EMCustomerCredit::query()->whereKey($booking->credit_id)->lockForUpdate()->first();
                if ($credit) {
                    $credit->used_classes = max(0, (int) $credit->used_classes - 1);
                    $credit->remaining_classes = (int) $credit->remaining_classes + 1;
                    $credit->status = 'active';
                    $credit->save();
                    $creditReturned = true;

                    EMCustomerCreditLog::create([
                        'customer_id' => $credit->customer_id,
                        'credit_id' => $credit->id,
                        'booking_id' => $booking->id,
                        'type' => 'refund',
                        'classes' => 1,
                        'note' => 'Family booking cancelled and 1 shared credit was returned.',
                        'description' => 'ACES Training family cancellation credit return.',
                    ]);
                }
            }

            if ($booking->cart_id) {
                EMCustomerPackageCart::query()->whereKey($booking->cart_id)->delete();
            }

            $booking->status = 'cancelled';
            $booking->cancelled_at = now();
            $booking->save();

            return $booking;
        });

        $booking->loadMissing(['customer', 'child', 'sessionEvent']);
        $mail->bookingCancelled($booking, $booking->sessionEvent, $creditReturned);

        return back()->with(
            'success',
            'Booking cancelled' . ($creditReturned ? ' and one shared family credit was returned.' : '.')
        );
    }

    private function currentFamilyCustomer(): ?EMCustomer
    {
        $id = (int) session('em_customer_id');
        return $id > 0 ? EMCustomer::query()->whereKey($id)->where('active', 1)->first() : null;
    }
}
