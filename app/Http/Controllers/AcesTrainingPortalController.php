<?php

namespace App\Http\Controllers;

use App\Models\EMSessionBooking;
use Illuminate\Http\Request;

class AcesTrainingPortalController extends FamilyTrainingPortalController
{
    private const CONFIRMED_STATUSES = ['booked', 'paid', 'completed'];

    public function index()
    {
        $response = parent::index();

        if (!($response instanceof \Illuminate\View\View)) {
            return $response;
        }

        $data = $response->getData();
        foreach (['sessions', 'calendarSessions'] as $key) {
            $sessions = collect($data[$key] ?? []);
            if ($sessions->isEmpty()) {
                continue;
            }

            $counts = $this->confirmedCounts($sessions->pluck('id')->filter()->all());
            $sessions->each(function ($session) use ($counts) {
                $booked = (int) ($counts[$session->id] ?? 0);
                $capacity = (int) ($session->capacity ?? 0);
                $session->bookings_count = $booked;
                $session->spots_left = $capacity > 0 ? max(0, $capacity - $booked) : null;
                $session->is_full = $capacity > 0 && $booked >= $capacity;
            });

            $response->with($key, $sessions->values());
        }

        return $response;
    }

    public function calendarEvents()
    {
        $response = parent::calendarEvents();
        $events = collect($response->getData(true));
        $counts = $this->confirmedCounts($events->pluck('id')->filter()->all());

        $events = $events->map(function ($event) use ($counts) {
            $booked = (int) ($counts[(int) ($event['id'] ?? 0)] ?? 0);
            $capacity = (int) ($event['capacity'] ?? 0);
            $event['booked'] = $booked;
            $event['spots_left'] = $capacity > 0 ? max(0, $capacity - $booked) : null;
            $event['full'] = $capacity > 0 && $booked >= $capacity;
            return $event;
        });

        return response()->json($events->values());
    }

    public function updateCart(Request $request, $id)
    {
        $response = parent::updateCart($request, $id);
        $this->cleanTrainingFlashMessages();

        return $response;
    }

    public function removeCart($id)
    {
        $response = parent::removeCart($id);
        $this->cleanTrainingFlashMessages();

        return $response;
    }

    public function checkout()
    {
        $response = parent::checkout();
        $this->cleanTrainingFlashMessages();

        return $response;
    }

    private function confirmedCounts(array $sessionIds)
    {
        if ($sessionIds === []) {
            return collect();
        }

        return EMSessionBooking::query()
            ->whereIn('session_event_id', $sessionIds)
            ->whereIn('status', self::CONFIRMED_STATUSES)
            ->selectRaw('session_event_id, COUNT(*) total')
            ->groupBy('session_event_id')
            ->pluck('total', 'session_event_id');
    }

    private function cleanTrainingFlashMessages(): void
    {
        foreach (['success', 'error'] as $key) {
            if (!session()->has($key)) {
                continue;
            }

            $message = (string) session($key);
            $message = str_ireplace([
                'shared family Training cart',
                'shared family cart',
                'family Training cart',
            ], [
                'Training cart',
                'cart',
                'Training cart',
            ], $message);

            session()->flash($key, $message);
        }
    }
}
