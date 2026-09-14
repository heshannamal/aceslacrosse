<?php

namespace App\Http\Controllers\Admin;

use App\Models\EMSessionBooking;
use App\Services\Training\TrainingFamilyService;
use App\Services\Training\TrainingMailService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AcesBookingController extends BookingController
{
    public function validateManual(Request $request, TrainingFamilyService $families)
    {
        return $this->cleanResponse(parent::validateManual($request, $families));
    }

    public function store(Request $request, TrainingMailService $mail, TrainingFamilyService $families)
    {
        return $this->cleanResponse(parent::store($request, $mail, $families));
    }

    public function updateSession(Request $request, EMSessionBooking $booking, TrainingMailService $mail)
    {
        return $this->cleanResponse(parent::updateSession($request, $booking, $mail));
    }

    public function destroy(EMSessionBooking $booking, TrainingMailService $mail)
    {
        return $this->cleanResponse(parent::destroy($booking, $mail));
    }

    public function move(Request $request, EMSessionBooking $booking, TrainingMailService $mail)
    {
        return $this->updateSession($request, $booking, $mail);
    }

    private function cleanResponse($response)
    {
        if ($response instanceof JsonResponse) {
            $payload = $response->getData(true);
            if (isset($payload['message'])) {
                $payload['message'] = $this->cleanText((string) $payload['message']);
            }

            return response()->json($payload, $response->getStatusCode());
        }

        foreach (['success', 'error'] as $key) {
            if (session()->has($key)) {
                session()->flash($key, $this->cleanText((string) session($key)));
            }
        }

        return $response;
    }

    private function cleanText(string $message): string
    {
        return str_ireplace([
            'shared family Training credit',
            'shared family credit',
            'family credit',
            'This family has no active Training credits.',
            'linked to this family',
            'both linked parents',
        ], [
            'Training credit',
            'Training credit',
            'Training credit',
            'This Training account has no active credits.',
            'linked to this Training account',
            'both parents',
        ], $message);
    }
}
