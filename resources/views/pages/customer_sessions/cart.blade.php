@extends('layouts.app')

@section('title', 'Training Cart | ACES Lacrosse')

@section('content')
@php
    $items = collect($cartItems ?? []);
    $subtotal = (float) ($totals['subtotal'] ?? $items->sum('total_price'));
    $processingFee = (float) ($totals['processing_fee'] ?? round($subtotal * 0.03, 2));
    $total = (float) ($totals['total'] ?? round($subtotal + $processingFee, 2));
    $totalCredits = (int) $items->sum(function ($item) {
        return (int) ($item->package?->available_classes ?? 0) * max(1, (int) ($item->quantity ?? 1));
    });
    $bookedSessions = (int) $items->filter(function ($item) {
        return !empty($item->booking_id) || ($item->source_type ?? null) === 'booking' || !empty($item->booking);
    })->count();
    $remainingCredits = max(0, $totalCredits - $bookedSessions);
@endphp

<style>
:root{--ao-red:#611eb2;--ao-soft:#f4edfc;--ao-black:#171021;--ao-bg:#f7f6f9;--ao-border:#e7e4ec;--ao-muted:#687083}
.training-cart-page{min-height:calc(100vh - 104px);background:var(--ao-bg);padding:30px 0 60px;font-family:'Roboto',Arial,sans-serif;color:#111}.training-cart-shell{width:min(1120px,calc(100% - 32px));margin:0 auto}.training-cart-grid{display:grid;grid-template-columns:minmax(0,1fr) 320px;gap:24px;align-items:start}.training-cart-list{display:flex;flex-direction:column;gap:18px}.training-cart-card{background:#fff;border:1px solid #e4e0e9;border-radius:28px;box-shadow:0 14px 30px rgba(48,27,65,.065);padding:22px}.training-cart-card-head{display:flex;justify-content:space-between;align-items:flex-start;gap:20px}.training-cart-badge{display:inline-flex;align-items:center;border-radius:999px;background:var(--ao-soft);color:var(--ao-red);font-size:11px;font-weight:900;padding:7px 11px}.training-cart-title{margin:10px 0 0;font-size:25px;font-weight:900;letter-spacing:-.04em}.training-cart-line-label{color:#677084;font-size:10px;font-weight:900;text-transform:uppercase;letter-spacing:.13em;text-align:right}.training-cart-line-total{margin-top:4px;color:var(--ao-red);font-size:29px;line-height:1;font-weight:800}.training-session-box{margin-top:18px;border:1px solid rgba(97,30,178,.24);background:linear-gradient(135deg,#faf7ff,#fff);border-radius:20px;padding:15px}.training-session-kicker{color:#6e7688;font-size:10px;font-weight:900;text-transform:uppercase;letter-spacing:.18em}.training-session-title{margin:4px 0 10px;font-size:19px;font-weight:800}.training-session-meta{display:flex;flex-wrap:wrap;gap:7px}.training-session-pill{display:inline-flex;align-items:center;gap:6px;max-width:100%;border:1px solid #eceef2;border-radius:999px;background:#fff;padding:7px 10px;color:#252a34;font-size:10px;font-weight:800;text-decoration:none}.training-session-pill i{color:var(--ao-red)}.training-what{margin-top:11px;border:1px solid #eceef2;border-radius:13px;background:#fff;padding:11px}.training-what span{display:block;color:#687083;font-size:9px;font-weight:900;letter-spacing:.16em;text-transform:uppercase}.training-what strong{display:block;margin-top:6px;font-size:11px}.training-cart-card-footer{display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;border-top:1px solid #eceef2;margin-top:16px;padding-top:15px}.training-cancel{display:inline-flex;align-items:center;gap:7px;border:0;background:none;color:#b42318;font-weight:900;font-size:12px}.training-qty{display:flex;align-items:center;gap:8px}.training-qty input{width:72px;height:38px;border:1px solid #d9dde4;border-radius:10px;padding:0 10px}.training-qty button{height:38px;border:1px solid #171021;border-radius:999px;background:#fff;padding:0 13px;font-weight:900;font-size:11px}.training-summary{position:sticky;top:126px;background:#fff;border:1px solid #e4e0e9;border-radius:28px;box-shadow:0 18px 34px rgba(48,27,65,.10);padding:23px}.training-summary h2{margin:0 0 18px;font-size:25px;font-weight:900}.training-summary-row{display:flex;justify-content:space-between;gap:18px;padding:8px 0;color:#5d6472;font-size:12px;font-weight:800}.training-summary-row strong{color:#4b515c}.training-summary hr{border:0;border-top:1px solid #dfe2e7;margin:10px 0}.training-summary-total{display:flex;justify-content:space-between;align-items:flex-end;gap:18px;border-top:1px solid #dfe2e7;margin-top:8px;padding-top:20px}.training-summary-total span{font-size:16px;font-weight:900}.training-summary-total strong{color:var(--ao-red);font-size:30px;font-weight:900}.training-primary,.training-secondary{width:100%;min-height:44px;border-radius:999px;display:flex;align-items:center;justify-content:center;gap:8px;text-decoration:none;font-size:12px;font-weight:900}.training-primary{margin-top:19px;background:var(--ao-red);color:#fff;box-shadow:0 12px 24px rgba(97,30,178,.24)}.training-primary:hover{color:#fff;background:#4d168f}.training-secondary{margin-top:9px;border:1.5px solid #171021;background:#fff;color:#171021}.training-secondary:hover{background:#171021;color:#fff}.training-cart-empty{background:#fff;border:1px dashed #cfd3da;border-radius:28px;padding:50px 20px;text-align:center}.training-cart-empty i{font-size:38px;color:var(--ao-red)}.training-cart-empty h2{margin-top:14px;font-size:25px;font-weight:900}.training-cart-alert{margin-bottom:18px;border-radius:16px;padding:13px 16px;font-weight:800;font-size:12px}.training-cart-alert.success{background:#ecfdf3;color:#166534}.training-cart-alert.error{background:#fff1f2;color:#b42318}@media(max-width:900px){.training-cart-grid{grid-template-columns:1fr}.training-summary{position:static}}@media(max-width:600px){.training-cart-page{padding-top:18px}.training-cart-shell{width:min(100% - 20px,1120px)}.training-cart-card,.training-summary{border-radius:20px;padding:17px}.training-cart-card-head{flex-direction:column}.training-cart-line-label,.training-cart-line-total{text-align:left}.training-cart-title{font-size:22px}}
</style>

<section class="training-cart-page">
    <div class="training-cart-shell">
        @if(session('success'))<div class="training-cart-alert success">{{ session('success') }}</div>@endif
        @if(session('error'))<div class="training-cart-alert error">{{ session('error') }}</div>@endif
        @if($errors->any())<div class="training-cart-alert error">{{ $errors->first() }}</div>@endif

        <div class="training-cart-grid">
            <div class="training-cart-list">
                @forelse($items as $item)
                    @php
                        $package = $item->package;
                        $booking = $item->booking;
                        $session = $booking?->sessionEvent;
                        $child = $booking?->child;
                        $isBookingItem = !empty($item->booking_id) || ($item->source_type ?? null) === 'booking' || !empty($booking);
                        $sessionDate = $session?->event_date ? \Carbon\Carbon::parse($session->event_date) : null;
                        $startTime = $session?->start_time ? \Carbon\Carbon::parse($session->start_time)->format('g:i A') : null;
                        $endTime = $session?->end_time ? \Carbon\Carbon::parse($session->end_time)->format('g:i A') : null;
                        $location = $session ? ($session->location ?: trim(($session->street_address ?? '').' '.($session->city ?? ''))) : null;
                    @endphp
                    <article class="training-cart-card">
                        <div class="training-cart-card-head">
                            <div>
                                <span class="training-cart-badge">{{ $isBookingItem ? 'Pending Payment' : 'Training Package' }}</span>
                                <h2 class="training-cart-title">{{ $package?->package_name ?: 'Training Package' }}</h2>
                            </div>
                            <div>
                                <div class="training-cart-line-label">Line Total</div>
                                <div class="training-cart-line-total">${{ number_format((float)$item->total_price,2) }}</div>
                            </div>
                        </div>

                        @if($isBookingItem && $session)
                            <div class="training-session-box">
                                <div class="training-session-kicker">Selected Training Session</div>
                                <h3 class="training-session-title">{{ $session->training_type ?: $session->name }}</h3>
                                <div class="training-session-meta">
                                    <span class="training-session-pill"><i class="fa-regular fa-user"></i>{{ trim(($child?->first_name ?: $booking->player_first).' '.($child?->last_name ?: $booking->player_last)) }}</span>
                                    @if($sessionDate)<span class="training-session-pill"><i class="fa-regular fa-calendar"></i>{{ $sessionDate->format('D, M d, Y') }}</span>@endif
                                    @if($startTime)<span class="training-session-pill"><i class="fa-regular fa-clock"></i>{{ $startTime }}{{ $endTime ? ' - '.$endTime : '' }}</span>@endif
                                    @if($location)<a class="training-session-pill" target="_blank" rel="noopener" href="https://www.google.com/maps/search/?api=1&query={{ urlencode($location) }}"><i class="fa-solid fa-location-dot"></i>{{ $location }}</a>@endif
                                </div>
                                @if(trim((string)($session->what_to_bring ?? '')) !== '')
                                    <div class="training-what"><span>What to Bring</span><strong><i class="fa-solid fa-gift me-1" style="color:#611eb2"></i>{{ $session->what_to_bring }}</strong></div>
                                @endif
                            </div>
                        @endif

                        <div class="training-cart-card-footer">
                            @if(($item->source_type ?? null) === 'direct')
                                <form class="training-qty" method="POST" action="{{ route('em.customer.cart.update',$item->id) }}">
                                    @csrf
                                    <strong>Qty</strong><input type="number" min="1" max="10" name="quantity" value="{{ $item->quantity }}"><button type="submit">Update</button>
                                </form>
                            @else
                                <span></span>
                            @endif
                            <form method="POST" action="{{ route('em.customer.cart.remove',$item->id) }}" onsubmit="return confirm('{{ $isBookingItem ? 'Remove this package and pending booking?' : 'Remove this package from cart?' }}')">
                                @csrf @method('DELETE')
                                <button class="training-cancel" type="submit"><i class="fa-regular fa-trash-can"></i> Cancel</button>
                            </form>
                        </div>
                    </article>
                @empty
                    <div class="training-cart-empty"><i class="fa-solid fa-cart-shopping"></i><h2>Your Training Cart Is Empty</h2><p>Book a session or choose a Training package to continue.</p><a class="training-primary" href="{{ route('em.customer.index') }}">Browse Training</a></div>
                @endforelse
            </div>

            <aside class="training-summary">
                <h2>Summary</h2>
                <div class="training-summary-row"><span>Total Credits</span><strong>{{ $totalCredits }}</strong></div>
                <div class="training-summary-row"><span>Booked Sessions</span><strong>{{ $bookedSessions }}</strong></div>
                <div class="training-summary-row"><span>Remaining Credits</span><strong>{{ $remainingCredits }}</strong></div>
                <hr>
                <div class="training-summary-row"><span>Subtotal</span><strong>${{ number_format($subtotal,2) }}</strong></div>
                <div class="training-summary-row"><span>CC Processing Fee 3%</span><strong>${{ number_format($processingFee,2) }}</strong></div>
                <div class="training-summary-total"><span>Total</span><strong>${{ number_format($total,2) }}</strong></div>
                @if($items->isNotEmpty())<a class="training-primary" href="{{ route('em.customer.checkout') }}">Proceed to Checkout <i class="fa-solid fa-arrow-right"></i></a>@endif
                <a class="training-secondary" href="{{ route('em.customer.index') }}">Continue Booking <i class="fa-regular fa-calendar"></i></a>
            </aside>
        </div>
    </div>
</section>
@endsection