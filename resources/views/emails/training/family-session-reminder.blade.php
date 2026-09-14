@include('emails.training._header')
<div style="font-size:11px;letter-spacing:1.7px;text-transform:uppercase;color:#611eb2;font-weight:900;">Training Reminder</div>
<h1 style="margin:8px 0 12px;font-size:30px;line-height:36px;color:#171021;font-weight:900;">Your Training {{ $sessionItems->count() === 1 ? 'session is' : 'sessions are' }} today</h1>
<p style="margin:0 0 20px;font-size:15px;line-height:23px;color:#5f6673;">Hello, here {{ $sessionItems->count() === 1 ? 'is the session' : 'are the sessions' }} scheduled for {{ $todayDisplay }}.</p>

@foreach($sessionItems as $item)
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin:0 0 16px;background:#faf7ff;border:1px solid #d9c3ef;border-radius:18px;">
<tr><td style="padding:20px;">
    <div style="font-size:10px;line-height:14px;letter-spacing:1.4px;text-transform:uppercase;color:#887699;font-weight:900;">{{ $item['session_name'] }}</div>
    <div style="margin-top:6px;font-size:21px;line-height:28px;color:#171021;font-weight:900;">{{ $item['child_name'] }}</div>
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin-top:13px;">
        <tr><td style="padding:4px 0;width:105px;color:#7b7284;font-size:12px;font-weight:800;">Date</td><td style="padding:4px 0;color:#171021;font-size:13px;font-weight:800;">{{ $item['date'] }}</td></tr>
        <tr><td style="padding:4px 0;color:#7b7284;font-size:12px;font-weight:800;">Time</td><td style="padding:4px 0;color:#171021;font-size:13px;font-weight:800;">{{ $item['time'] }}</td></tr>
        <tr><td style="padding:4px 0;color:#7b7284;font-size:12px;font-weight:800;">Location</td><td style="padding:4px 0;color:#171021;font-size:13px;font-weight:800;">{{ $item['location'] }}</td></tr>
        <tr><td style="padding:4px 0;color:#7b7284;font-size:12px;font-weight:800;">Instructor</td><td style="padding:4px 0;color:#171021;font-size:13px;font-weight:800;">{{ $item['instructor'] }}</td></tr>
    </table>
    @if(!empty($item['what_to_bring']))
        <div style="margin-top:13px;padding:12px 14px;background:#fff;border:1px solid #e3dbea;border-radius:13px;">
            <div style="font-size:10px;text-transform:uppercase;letter-spacing:1.2px;color:#611eb2;font-weight:900;">What to Bring</div>
            <div style="margin-top:5px;color:#4b5563;font-size:13px;line-height:20px;font-weight:700;">{{ $item['what_to_bring'] }}</div>
        </div>
    @endif
</td></tr>
</table>
@endforeach

<p style="margin:24px 0 8px;"><a href="{{ route('em.customer.bookings') }}" style="display:inline-block;background:#611eb2;color:#fff;text-decoration:none;font-weight:900;padding:13px 24px;border-radius:999px;">View My Bookings</a></p>
@include('emails.training._footer')
