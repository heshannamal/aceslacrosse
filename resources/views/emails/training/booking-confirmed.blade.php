@include('emails.training._header')
@php
    $s = $booking->sessionEvent;
    $playerName = trim(($booking->child?->first_name ?: $booking->player_first) . ' ' . ($booking->child?->last_name ?: $booking->player_last));
    $addressParts = collect([$s?->street_address, $s?->city])->map(fn ($v) => trim((string) $v))->filter()->unique();
    $displayLocation = $addressParts->isNotEmpty() ? $addressParts->implode(', ') : trim((string) ($s?->location ?? ''));
    $shortBookingNo = !empty($booking->booking_no) ? \Illuminate\Support\Str::afterLast($booking->booking_no, '-') : '-';
@endphp
<div style="font-size:11px;line-height:15px;letter-spacing:1.7px;text-transform:uppercase;color:#611eb2;font-weight:900;">Booking Confirmed</div>
<h1 style="margin:8px 0 12px;font-size:30px;line-height:36px;color:#171021;font-weight:900;">Your training session is booked</h1>
<p style="margin:0;font-size:15px;line-height:23px;color:#5f6673;">Hi {{ $booking->customer->first_name ?: $booking->customer->display_name }}, your ACES Lacrosse Training booking is confirmed.</p>
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin:24px 0 8px;background:#faf7ff;border:1px solid #d9c3ef;border-radius:18px;">
<tr><td style="padding:20px;">
    <div style="font-size:10px;line-height:14px;letter-spacing:1.4px;text-transform:uppercase;color:#887699;font-weight:900;">Training Session</div>
    <div style="margin-top:6px;font-size:22px;line-height:28px;color:#171021;font-weight:900;">{{ $s?->training_type ?: $s?->name ?: 'Training Session' }}</div>
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin-top:15px;">
        <tr><td style="padding:5px 0;color:#7b7284;font-size:12px;font-weight:800;width:110px;">Player</td><td style="padding:5px 0;color:#171021;font-size:13px;font-weight:800;">{{ $playerName ?: '-' }}</td></tr>
        <tr><td style="padding:5px 0;color:#7b7284;font-size:12px;font-weight:800;">Date</td><td style="padding:5px 0;color:#171021;font-size:13px;font-weight:800;">{{ $s?->event_date?->format('l, M j, Y') ?: '-' }}</td></tr>
        <tr><td style="padding:5px 0;color:#7b7284;font-size:12px;font-weight:800;">Time</td><td style="padding:5px 0;color:#171021;font-size:13px;font-weight:800;">{{ $s ? \Carbon\Carbon::parse($s->start_time)->format('g:i A') : '-' }} - {{ $s ? \Carbon\Carbon::parse($s->end_time)->format('g:i A') : '-' }}</td></tr>
        <tr><td style="padding:5px 0;color:#7b7284;font-size:12px;font-weight:800;">Location</td><td style="padding:5px 0;color:#171021;font-size:13px;font-weight:800;">{{ $displayLocation ?: '-' }}</td></tr>
        <tr><td style="padding:5px 0;color:#7b7284;font-size:12px;font-weight:800;">Booking No</td><td style="padding:5px 0;color:#611eb2;font-size:13px;font-weight:900;">{{ $shortBookingNo }}</td></tr>
    </table>
</td></tr></table>
@if($s?->what_to_bring)
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin-top:14px;background:#fff;border:1px solid #e2e2e7;border-radius:16px;"><tr><td style="padding:16px 18px;"><div style="font-size:10px;letter-spacing:1.3px;text-transform:uppercase;color:#611eb2;font-weight:900;">What to Bring</div><div style="margin-top:6px;font-size:13px;line-height:20px;color:#4b5563;font-weight:700;">{{ $s->what_to_bring }}</div></td></tr></table>
@endif
<p style="margin:24px 0 8px;"><a href="{{ route('em.customer.dashboard') }}" style="display:inline-block;background:#611eb2;color:#fff;text-decoration:none;font-weight:900;padding:13px 24px;border-radius:999px;">Manage Booking</a></p>
@include('emails.training._footer')
