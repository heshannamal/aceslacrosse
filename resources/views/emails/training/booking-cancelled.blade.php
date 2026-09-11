@include('emails.training._header')
@php
    $address = collect([$session?->street_address, $session?->city])->map(fn ($v) => trim((string) $v))->filter()->unique();
    $displayLocation = $address->isNotEmpty() ? $address->implode(', ') : trim((string) ($session?->location ?? ''));
@endphp
<div style="font-size:11px;letter-spacing:1.7px;text-transform:uppercase;color:#611eb2;font-weight:900;">Booking Cancelled</div>
<h1 style="margin:8px 0 12px;font-size:30px;line-height:36px;color:#171021;font-weight:900;">Your training booking was cancelled</h1>
<p style="margin:0;font-size:15px;line-height:23px;color:#5f6673;">Hi {{ $booking->customer->first_name ?: $booking->customer->display_name }}, this ACES Lacrosse Training booking has been cancelled.</p>
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin:24px 0 8px;background:#faf7ff;border:1px solid #d9c3ef;border-radius:18px;"><tr><td style="padding:19px 20px;"><div style="font-size:10px;text-transform:uppercase;letter-spacing:1.3px;color:#887699;font-weight:900;">Cancelled Session</div><div style="margin-top:7px;font-size:21px;font-weight:900;color:#171021;">{{ $session?->training_type ?: $session?->name ?: 'Training Session' }}</div>@if($session)<div style="margin-top:9px;font-size:13px;line-height:20px;color:#5f6673;">{{ $session->event_date?->format('l, M j, Y') }}<br>{{ \Carbon\Carbon::parse($session->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($session->end_time)->format('g:i A') }}<br>{{ $displayLocation ?: '-' }}</div>@endif</td></tr></table>
@if($creditReturned)<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin-top:14px;background:#ecfdf3;border:1px solid #a6e8c4;border-radius:16px;"><tr><td style="padding:15px 18px;color:#067647;font-size:13px;line-height:20px;font-weight:800;">One class credit was returned to your ACES Training account.</td></tr></table>@endif
<p style="margin:24px 0 8px;"><a href="{{ route('em.customer.index') }}" style="display:inline-block;background:#611eb2;color:#fff;text-decoration:none;font-weight:900;padding:13px 24px;border-radius:999px;">Book Another Session</a></p>
@include('emails.training._footer')
