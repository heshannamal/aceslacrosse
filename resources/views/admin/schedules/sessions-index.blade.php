@extends('admin.layouts.app')
@section('title','Sessions')
@section('content')
<style>
.sessions-page{--a:#f3282c;--soft:#fff0f0}.sessions-head{display:flex;justify-content:space-between;align-items:flex-start;gap:16px;flex-wrap:wrap;margin-bottom:18px}.sessions-head h2{font-weight:900;margin:0}.sessions-head p{color:#64748b;margin:6px 0 0}.sessions-card{background:#fff;border:1px solid #e2e8f0;border-radius:18px;padding:20px}.sessions-filter{display:grid;grid-template-columns:1.5fr 1fr 1fr 1fr 1fr auto auto;gap:10px;align-items:end;background:linear-gradient(135deg,#fff,#fff7f7);border:1px solid #f4c5c6;border-radius:18px;padding:14px;margin-bottom:20px}.sf-label{display:block;font-size:10px;font-weight:900;text-transform:uppercase;margin-bottom:6px;color:#556070}.sf-input{height:38px;width:100%;border:1px solid #d8dee8;border-radius:999px;padding:0 12px;background:#fff}.session-list{display:flex;flex-direction:column;gap:10px}.session-row{display:flex;justify-content:space-between;align-items:center;gap:15px;border:1px solid #dfe5ed;border-radius:15px;padding:15px}.session-row:hover{border-color:var(--a)}.session-row h4{font-size:17px;font-weight:900;margin:0}.meta-pills{display:flex;gap:7px;flex-wrap:wrap;margin-top:10px}.meta-pill{font-size:10px;font-weight:800;padding:6px 9px;border-radius:999px;background:var(--soft);color:var(--a)}.meta-pill.ok{background:#dcfce7;color:#15803d}.meta-pill.full{background:#fee2e2;color:#b91c1c}.meta-pill.time{background:#e0f2fe;color:#0369a1}.meta-pill.past{background:#eef2f6;color:#64748b}.meta-pill.pending{background:#fff7ed;color:#c2410c}.meta-pill.booked{background:#ecfdf3;color:#027a48}.circle-btn{width:35px;height:35px;border:0;border-radius:50%;background:#f8fafc;display:inline-flex;align-items:center;justify-content:center}@media(max-width:1100px){.sessions-filter{grid-template-columns:1fr 1fr 1fr}}@media(max-width:700px){.sessions-filter{grid-template-columns:1fr}.session-row{flex-direction:column;align-items:flex-start}}
</style>
<div class="sessions-page">
    <div class="sessions-head">
        <div>
            <h2>Sessions</h2>
            <p>Create and manage Alcatraz Outlaws training sessions shown to customers.</p>
        </div>
        <div class="d-flex gap-2 align-items-center">
            <span class="badge rounded-pill text-bg-light text-primary px-3 py-2"><i class="fa-solid fa-calendar-days me-1"></i>{{ $sessions->count() }} Sessions</span>
            <a class="btn btn-primary rounded-pill px-4 fw-bold" href="{{ route('admin.em.sessions.create') }}"><i class="fa-solid fa-plus me-2"></i>Add Session</a>
        </div>
    </div>

    <div class="sessions-card">
        <form method="GET" class="sessions-filter">
            <div><label class="sf-label">Search</label><input class="sf-input" name="search" value="{{ $search }}" placeholder="Search type, instructor, location..."></div>
            <div><label class="sf-label">Type</label><select class="sf-input" name="training_type"><option value="">All Types</option>@foreach($trainingTypes as $trainingType)<option value="{{ $trainingType }}" @selected($type===$trainingType)>{{ $trainingType }}</option>@endforeach</select></div>
            <div><label class="sf-label">From</label><input type="date" class="sf-input" name="date_from" value="{{ $dateFrom }}"></div>
            <div><label class="sf-label">To</label><input type="date" class="sf-input" name="date_to" value="{{ $dateTo }}"></div>
            <div><label class="sf-label">Status</label><select class="sf-input" name="status"><option value="all" @selected($status==='all')>All</option><option value="upcoming" @selected($status==='upcoming')>Upcoming</option><option value="past" @selected($status==='past')>Past</option></select></div>
            <button class="btn btn-primary rounded-pill fw-bold"><i class="fa-solid fa-filter me-1"></i>Filter</button>
            <a class="btn btn-dark rounded-pill fw-bold" href="{{ route('admin.em.sessions.index') }}">Reset</a>
        </form>

        <div class="session-list">
            @forelse($sessions as $session)
                <div class="session-row">
                    <div class="flex-grow-1">
                        <h4>{{ $session->training_type ?: $session->name }}</h4>
                        <div class="text-muted small mt-1">{{ $session->description ?: 'No description' }}</div>
                        <div class="meta-pills">
                            <span class="meta-pill"><i class="fa-regular fa-calendar me-1"></i>{{ $session->event_date?->format('l, M d, Y') }}</span>
                            <span class="meta-pill"><i class="fa-regular fa-clock me-1"></i>{{ $session->start_time ? \Carbon\Carbon::parse($session->start_time)->format('h:i A') : '-' }} - {{ $session->end_time ? \Carbon\Carbon::parse($session->end_time)->format('h:i A') : '-' }}</span>
                            <span class="meta-pill"><i class="fa-solid fa-user me-1"></i>{{ $session->instructor ?: '-' }}</span>
                            <span class="meta-pill"><i class="fa-solid fa-location-dot me-1"></i>{{ $session->location ?: '-' }}</span>
                            <span class="meta-pill {{ $session->is_past ? 'past' : 'time' }}">{{ $session->is_past ? 'Past' : 'Upcoming' }}</span>

                            <span class="meta-pill booked">
                                <i class="fa-solid fa-user-check me-1"></i>
                                {{ (int) $session->bookings_count }} Confirmed {{ \Illuminate\Support\Str::plural('Booking', (int) $session->bookings_count) }}
                                @if((int) $session->capacity > 0)
                                    / {{ (int) $session->capacity }} Capacity
                                @endif
                            </span>

                            @if((int) ($session->pending_reservations_count ?? 0) > 0)
                                <span class="meta-pill pending">
                                    <i class="fa-solid fa-cart-shopping me-1"></i>
                                    {{ (int) $session->pending_reservations_count }} Pending Checkout {{ \Illuminate\Support\Str::plural('Hold', (int) $session->pending_reservations_count) }}
                                </span>
                            @endif

                            <span class="meta-pill {{ $session->is_full ? 'full' : 'ok' }}">
                                <i class="fa-solid fa-users me-1"></i>
                                @if((int) $session->capacity > 0)
                                    {{ $session->is_full ? 'No Spots Left' : max(0, (int) $session->capacity - (int) $session->capacity_used_count) . ' Spots Left' }}
                                @else
                                    Unlimited Capacity
                                @endif
                            </span>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.em.sessions.edit',$session) }}" class="circle-btn text-decoration-none"><i class="fa-solid fa-pen"></i></a>
                        <form method="POST" action="{{ route('admin.em.sessions.destroy',$session) }}" onsubmit="return confirm('Delete this session?');">
                            @csrf
                            @method('DELETE')
                            <button class="circle-btn text-danger"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="text-center py-5 text-muted">No sessions found.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
