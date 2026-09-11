@include('emails.training._header')
<div style="font-size:11px;letter-spacing:1.7px;text-transform:uppercase;color:#611eb2;font-weight:900;">Account Security</div>
<h1 style="margin:8px 0 12px;font-size:30px;line-height:36px;color:#171021;font-weight:900;">Reset your Training password</h1>
<p style="margin:0;font-size:15px;line-height:23px;color:#5f6673;">Hi {{ $customer->first_name ?: $customer->display_name }}, we received a request to reset the password for your ACES Lacrosse Training account.</p>
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin:22px 0;background:#faf7ff;border:1px solid #d9c3ef;border-radius:17px;"><tr><td style="padding:17px 19px;color:#4b3b58;font-size:13px;line-height:21px;">For your security, this password reset link expires in <strong style="color:#171021;">60 minutes</strong>. If you did not request a reset, you can safely ignore this email.</td></tr></table>
<p style="margin:24px 0 8px;"><a href="{{ $url }}" style="display:inline-block;background:#611eb2;color:#fff;text-decoration:none;font-weight:900;padding:13px 24px;border-radius:999px;">Reset Password</a></p>
<p style="margin:18px 0 0;font-size:11px;line-height:18px;color:#8a8490;word-break:break-all;">If the button does not work, copy and paste this link into your browser:<br>{{ $url }}</p>
@include('emails.training._footer')
