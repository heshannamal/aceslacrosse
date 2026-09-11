@include('emails.training._header')
@php
    $oldAddress = collect([$oldSession?->street_address, $oldSession?->city])->map(fn ($v) => trim((string) $v))->filter()->unique();
    $oldLocation = $oldAddress->isNotEmpty() ? $oldAddress->implode(', ') : trim((string) ($oldSession?->location ?? ''));
    $newAddress = collect([$newSession?->street_address, $newSession?->city])->map(fn ($v) => trim((string) $v))->filter()->unique();
    $newLocation = $newAddress->isNotEmpty() ? $newAddress->implode(', ') : trim((string) ($newSession?->location ?? ''));
@endphp
<div style="font-size:11px;letter-spacing:1.7px;text-transform:uppercase;color:#611eb2;font-weight:900;">Booking Updated</div>
<h1 style="margin:8px 0 12px;font-size:30px;line-height:36px;color:#171021;font-weight:900;">Your training session changed</h1>
<p style="margin:0;font-size:15px;line-height:23px;color:#5f6673;">Hi {{ $booking->customer->first_name ?: $booking->customer->display_name }}, an ACES Lacrosse admin updated your booked session.</p>
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin:24px 0 8px;"><tr>
<td width="50%" valign="top" style="padding-right:7px;"><table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="border:1px solid #e2e2e7;border-radius:17px;background:#fff;"><tr><td style="padding:18px;"><div style="font-size:10px;text-transform:uppercase;letter-spacing:1.3px;color:#8b8491;font-weight:900;">Previous Session</div><div style="margin-top:7px;font-size:18px;font-weight:900;color:#171021;">{{ $oldSession?->training_type ?: $oldSession?->name ?: 'Previous session' }}</div>@if($oldSession)<div style="margin-top:9px;font-size:12px;line-height:19px;color:#667085;">{{ $oldSession->event_date?->format('M j, Y') }}<br>{{ \Carbon\Carbon::parse($oldSession->start_time)->format('g:i A') }}<br>{{ $oldLocation ?: '-' }}</div>@endif</td></tr></table></td>
<td width="50%" valign="top" style="padding-left:7px;"><table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="border:1px solid #d7baf1;border-radius:17px;background:#faf7ff;"><tr><td style="padding:18px;"><div style="font-size:10px;text-transform:uppercase;letter-spacing:1.3px;color:#611eb2;font-weight:900;">New Session</div><div style="margin-top:7px;font-size:18px;font-weight:900;color:#171021;">{{ $newSession->training_type ?: $newSession->name }}</div><div style="margin-top:9px;font-size:12px;line-height:19px;color:#5f6673;">{{ $newSession->event_date?->format('M j, Y') }}<br>{{ \Carbon\Carbon::parse($newSession->start_time)->format('g:i A') }}<br>{{ $newLocation ?: '-' }}</div></td></tr></table></td>
</tr></table>
<p style="margin:24px 0 8px;"><a href="{{ route('em.customer.dashboard') }}" style="display:inline-block;background:#611eb2;color:#fff;text-decoration:none;font-weight:900;padding:13px 24px;border-radius:999px;">View Updated Booking</a></p>
@include('emails.training._footer')
