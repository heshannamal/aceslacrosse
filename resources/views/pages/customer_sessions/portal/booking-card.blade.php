@php
    $bookingKey = $bookingKey ?? 'upcoming';
    $session = $booking->sessionEvent;
    $child = $booking->child;
    $date = $session && $session->event_date ? \Carbon\Carbon::parse($session->event_date) : null;
    $start = $session && $session->start_time ? \Carbon\Carbon::parse($session->start_time)->format('g:i A') : '-';
    $end = $session && $session->end_time ? \Carbon\Carbon::parse($session->end_time)->format('g:i A') : '-';
    $title = $session ? ($session->training_type ?: $session->name) : 'Training';
    $player = trim(($child->first_name ?? $booking->player_first ?? '') . ' ' . ($child->last_name ?? $booking->player_last ?? ''));
    $location = $session ? ($session->location ?: trim(($session->street_address ?? '') . ' ' . ($session->city ?? ''))) : '-';
    $instructor = $session->instructor ?? '-';
    $whatToBring = $session->what_to_bring ?? '-';
    $positions = $booking->positions ?: ($child->position ?? '');
@endphp

<article class="mb-card">
    <div class="mb-head">
        <div>
            <h3>{{ $title }}</h3>
            <span class="mb-no">{{ $booking->booking_no }}</span>
            @if($bookingKey === 'upcoming')
                <span class="mb-session-chip"><i class="fa-solid fa-bolt"></i> Upcoming Session</span>
            @else
                <span class="mb-session-chip is-past"><i class="fa-solid fa-clock-rotate-left"></i> Past Session</span>
            @endif
        </div>
        @if($date)
            <div class="mb-date"><strong>{{ $date->format('d') }}</strong><span>{{ strtoupper($date->format('M')) }}</span></div>
        @endif
    </div>

    <div class="mb-body">
        <div class="mb-details">
            <div class="mb-detail"><span>Type</span><strong>{{ $title }}</strong></div>
            <div class="mb-detail"><span>Status</span><strong class="mb-status-inline">{{ str_replace('_',' ',$booking->status) }}</strong></div>
            <div class="mb-detail"><span>Date</span><strong>{{ $date ? $date->format('l, M d, Y') : '-' }}</strong></div>
            <div class="mb-detail"><span>Time</span><strong>{{ $start }} - {{ $end }}</strong></div>
            <div class="mb-detail"><span>Location</span><strong>{{ $location ?: '-' }}</strong></div>
            <div class="mb-detail"><span>Instructor</span><strong>{{ $instructor ?: '-' }}</strong></div>
            <div class="mb-detail"><span>Player</span><strong>{{ $player ?: 'Player' }}@if($positions)<small>{{ $positions }}</small>@endif</strong></div>
            <div class="mb-detail"><span>Booked At</span><strong>{{ $booking->booked_at ? \Carbon\Carbon::parse($booking->booked_at)->format('M d, Y h:i A') : '-' }}</strong></div>
            <div class="mb-detail full"><span>What to Bring</span><strong>{{ $whatToBring ?: '-' }}</strong></div>
        </div>

        <div class="mb-actions">
            @if($location && $location !== '-')
                <a class="mb-map" target="_blank" rel="noopener" href="https://www.google.com/maps/search/?api=1&query={{ urlencode($location) }}"><i class="fa-solid fa-map-location-dot"></i> Open Map</a>
            @endif
            @if($bookingKey === 'upcoming' && in_array($booking->status,['pending_payment','booked','paid'],true))
                <form method="POST" action="{{ route('em.customer.booking.cancel',$booking->id) }}" onsubmit="return confirm('Cancel this Training booking?')">
                    @csrf
                    <button class="mb-cancel" type="submit"><i class="fa-regular fa-trash-can"></i> Cancel</button>
                </form>
            @endif
        </div>
    </div>
</article>
