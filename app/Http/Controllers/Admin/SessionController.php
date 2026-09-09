<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EMSessionBooking;
use App\Models\EMSessionEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SessionController extends Controller
{
    private const CONFIRMED_BOOKING_STATUSES = ['booked', 'paid', 'completed'];

    public function index(Request $request)
    {
        $search = trim((string) $request->get('search'));
        $type = trim((string) $request->get('training_type'));
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        $status = $request->get('status', 'upcoming');

        if (!in_array($status, ['all', 'upcoming', 'past'], true)) {
            $status = 'upcoming';
        }

        $trainingTypes = EMSessionEvent::query()
            ->where('is_active', 1)
            ->whereNotNull('training_type')
            ->where('training_type', '!=', '')
            ->distinct()
            ->orderBy('training_type')
            ->pluck('training_type');

        $query = EMSessionEvent::query()->where('is_active', 1);

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('training_type', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhere('instructor', 'like', '%' . $search . '%')
                    ->orWhere('location', 'like', '%' . $search . '%')
                    ->orWhere('street_address', 'like', '%' . $search . '%')
                    ->orWhere('city', 'like', '%' . $search . '%');
            });
        }

        if ($type !== '') {
            $query->where('training_type', $type);
        }

        if ($dateFrom) {
            $query->whereDate('event_date', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('event_date', '<=', $dateTo);
        }

        $sessions = $query
            ->orderBy('event_date')
            ->orderBy('start_time')
            ->get();

        $sessionIds = $sessions->pluck('id')->filter()->unique()->values()->all();
        $bookingCounts = collect();
        $pendingReservationCounts = collect();

        if (!empty($sessionIds)) {
            $bookingCounts = EMSessionBooking::query()
                ->whereIn('session_event_id', $sessionIds)
                ->whereIn('status', self::CONFIRMED_BOOKING_STATUSES)
                ->selectRaw('session_event_id, COUNT(*) as bookings_count')
                ->groupBy('session_event_id')
                ->pluck('bookings_count', 'session_event_id');

            // Keep pending checkout items visible for reference only. They are
            // cart items and do not consume session capacity.
            $pendingReservationCounts = EMSessionBooking::query()
                ->whereIn('session_event_id', $sessionIds)
                ->where('status', 'pending_payment')
                ->selectRaw('session_event_id, COUNT(*) as reservations_count')
                ->groupBy('session_event_id')
                ->pluck('reservations_count', 'session_event_id');
        }

        $pacificNow = now('America/Los_Angeles');

        $sessions = $sessions->map(function ($session) use ($bookingCounts, $pendingReservationCounts, $pacificNow) {
            $capacity = (int) ($session->capacity ?: 0);
            $bookedCount = (int) ($bookingCounts[$session->id] ?? 0);
            $pendingReservations = (int) ($pendingReservationCounts[$session->id] ?? 0);

            // Availability is based only on confirmed bookings.
            $session->bookings_count = $bookedCount;
            $session->pending_reservations_count = $pendingReservations;
            $session->capacity_used_count = $bookedCount;
            $session->is_full = $capacity > 0 && $bookedCount >= $capacity;

            try {
                $sessionDate = \Carbon\Carbon::parse(
                    $session->event_date,
                    'America/Los_Angeles'
                )->format('Y-m-d');

                if (!empty($session->end_time)) {
                    $sessionTime = \Carbon\Carbon::parse($session->end_time)->format('H:i:s');
                } elseif (!empty($session->start_time)) {
                    $sessionTime = \Carbon\Carbon::parse($session->start_time)->format('H:i:s');
                } else {
                    $sessionTime = '23:59:59';
                }

                $sessionEndAt = \Carbon\Carbon::parse(
                    $sessionDate . ' ' . $sessionTime,
                    'America/Los_Angeles'
                );

                $session->is_past = $sessionEndAt->lt($pacificNow);
                $session->session_sort_timestamp = $sessionEndAt->timestamp;
            } catch (\Throwable $e) {
                $session->is_past = true;
                $session->session_sort_timestamp = 0;
            }

            return $session;
        });

        if ($status === 'upcoming') {
            $sessions = $sessions->filter(function ($session) {
                return empty($session->is_past);
            })->values();
        } elseif ($status === 'past') {
            $sessions = $sessions->filter(function ($session) {
                return !empty($session->is_past);
            })->values();
        }

        if ($status === 'past') {
            $sessions = $sessions->sortByDesc('session_sort_timestamp')->values();
        } else {
            $sessions = $sessions->sortBy('session_sort_timestamp')->values();
        }

        return view('admin.schedules.sessions-index', compact(
            'sessions',
            'trainingTypes',
            'search',
            'type',
            'dateFrom',
            'dateTo',
            'status'
        ));
    }

    public function create()
    {
        return view('admin.schedules.session-form', ['session' => null]);
    }

    public function store(Request $request)
    {
        $data = $this->prepareSessionData($request, $this->validateSession($request));
        $data['user_id'] = auth()->id();
        $data['is_active'] = 1;

        EMSessionEvent::create($data);

        return redirect()
            ->route('admin.em.sessions.index')
            ->with('success', 'Session created successfully.');
    }

    public function edit(EMSessionEvent $session)
    {
        return view('admin.schedules.session-form', compact('session'));
    }

    public function update(Request $request, EMSessionEvent $session)
    {
        $data = $this->prepareSessionData(
            $request,
            $this->validateSession($request),
            $session
        );

        $session->update($data);

        return redirect()
            ->route('admin.em.sessions.index')
            ->with('success', 'Session updated successfully.');
    }

    public function destroy(EMSessionEvent $session)
    {
        $session->is_active = 0;
        $session->save();

        return redirect()
            ->route('admin.em.sessions.index')
            ->with('success', 'Session deleted successfully.');
    }

    private function validateSession(Request $request): array
    {
        return $request->validate([
            'name' => 'nullable|string|max:255',
            'training_type' => 'required|string|max:50',
            'description' => 'nullable|string',
            'documentation' => 'nullable|file|mimes:pdf|max:20480',
            'street_address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'location' => 'required|string|max:500',
            'location_lat' => 'nullable|numeric|between:-90,90',
            'location_lng' => 'nullable|numeric|between:-180,180',
            'instructor' => 'nullable|string|max:255',
            'instructors' => 'required|array|min:1',
            'instructors.*' => 'required|string|max:255',
            'event_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'what_to_bring' => 'nullable|string',
            'capacity' => 'required|integer|min:1',
        ]);
    }

    private function prepareSessionData(
        Request $request,
        array $data,
        ?EMSessionEvent $session = null
    ): array {
        $instructors = collect($request->input('instructors', []))
            ->map(function ($name) {
                return trim((string) $name);
            })
            ->filter()
            ->values()
            ->all();

        if ($instructors !== []) {
            $data['instructor'] = implode(', ', $instructors);
        } else {
            $data['instructor'] = trim((string) ($data['instructor'] ?? ''));
        }

        unset($data['instructors']);

        if (empty($data['name'])) {
            $data['name'] = $data['training_type'];
        }

        if ($request->hasFile('documentation')) {
            if ($session && !empty($session->documentation)) {
                Storage::disk('public')->delete($session->documentation);
            }

            $data['documentation'] = $request
                ->file('documentation')
                ->store('em-session-documents', 'public');
        } elseif ($session && !empty($session->documentation)) {
            unset($data['documentation']);
        }

        return $data;
    }
}
