@php
    $logoUrl = asset('public/assets/images/ACES-logo.webp');
    $paymentLog = $order->paymentLogs->first();
    $items = $order->items;

    $customerName = trim(($customer->first_name ?? '') . ' ' . ($customer->last_name ?? ''));
    if ($customerName === '') {
        $customerName = $customer->display_name ?? 'Customer';
    }

    $billingName = trim(($paymentLog->billing_first_name ?? '') . ' ' . ($paymentLog->billing_last_name ?? ''));
    if ($billingName === '') {
        $billingName = $customerName;
    }

    $billingEmail = trim((string) ($paymentLog->billing_email ?? $customer->email ?? ''));
    $billingPhone = trim((string) ($paymentLog->billing_phone ?? $customer->phone ?? ''));
    $billingStreet = trim((string) ($paymentLog->billing_street ?? ''));
    $billingCity = trim((string) ($paymentLog->billing_city ?? ''));
    $billingState = trim((string) ($paymentLog->billing_state ?? ''));
    $billingZip = trim((string) ($paymentLog->billing_zip ?? ''));
    $billingCountry = trim((string) ($paymentLog->billing_country ?? ''));

    $billingCityLine = trim(
        $billingCity .
        ($billingCity && ($billingState || $billingZip) ? ', ' : '') .
        $billingState .
        ($billingState && $billingZip ? ' ' : '') .
        $billingZip
    );

    $subtotal = (float) ($order->subtotal ?? 0);
    $processingFee = (float) ($order->processing_fee ?? $paymentLog->processing_fee ?? 0);
    $tax = (float) ($order->tax ?? 0);
    $total = (float) ($order->total ?? 0);
    $itemCount = $items->count();
    $totalCredits = $items->sum(function ($item) {
        return (int) ($item->classes_per_package ?? 0) * max(1, (int) ($item->quantity ?? 1));
    });

    $paidAtRaw = $paymentLog->paid_at ?? $order->paid_at ?? now();
    try {
        $paidAt = \Carbon\Carbon::parse($paidAtRaw)->format('M d, Y \a\t h:i A');
    } catch (\Throwable $e) {
        $paidAt = (string) $paidAtRaw;
    }

    $transactionId = $paymentLog->transaction_id ?? null;
    $shortOrderNo = !empty($order->order_no)
        ? \Illuminate\Support\Str::afterLast($order->order_no, '-')
        : '-';
@endphp
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>ACES Training Receipt</title>
</head>
<body style="margin:0;padding:0;background:#f4f3f7;color:#111827;font-family:Arial,Helvetica,sans-serif;">
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="width:100%;background:#f4f3f7;padding:24px 0;">
<tr><td align="center">
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="width:100%;max-width:760px;padding:0 14px;">
<tr><td>
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="width:100%;background:#fff;border-radius:26px;overflow:hidden;box-shadow:0 18px 50px rgba(23,16,33,.08);">
    <tr>
        <td style="padding:28px 34px 30px;background:#171021;">
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                <tr>
                    <td align="left" valign="top"><img src="{{ $logoUrl }}" alt="ACES Lacrosse" style="display:block;width:auto;height:58px;max-width:180px;"></td>
                    <td align="right" valign="top">
                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="background:#24162f;border:1px solid #51316d;border-radius:17px;">
                            <tr><td style="padding:11px 17px 12px;text-align:right;">
                                <div style="font-size:10px;line-height:14px;letter-spacing:1.8px;text-transform:uppercase;color:#cda9f1;font-weight:800;">Payment Receipt</div>
                                <div style="margin-top:5px;font-size:17px;line-height:22px;color:#fff;font-weight:800;">{{ $shortOrderNo }}</div>
                            </td></tr>
                        </table>
                    </td>
                </tr>
            </table>
            <div style="margin-top:28px;font-size:11px;line-height:15px;letter-spacing:1.9px;text-transform:uppercase;color:#cda9f1;font-weight:900;">Payment Successful</div>
            <div style="margin-top:9px;font-size:34px;line-height:39px;color:#fff;font-weight:900;">You’re All Set</div>
            <div style="margin-top:10px;max-width:590px;font-size:14px;line-height:22px;color:#e9e2ef;font-weight:500;">Hi {{ $customerName }}, thanks for your purchase. Your ACES Lacrosse Training payment was received successfully and your package credits are ready to use.</div>
        </td>
    </tr>

    <tr><td style="padding:30px 34px 8px;">
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0"><tr>
            <td width="33.33%" style="padding-right:7px;"><table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background:#faf7ff;border:1px solid #d7baf1;border-radius:18px;"><tr><td style="padding:17px 16px;"><div style="font-size:10px;letter-spacing:1.2px;text-transform:uppercase;color:#888894;font-weight:900;">Total Paid</div><div style="margin-top:7px;font-size:27px;line-height:31px;color:#611eb2;font-weight:900;">${{ number_format($total,2) }}</div></td></tr></table></td>
            <td width="33.33%" style="padding:0 4px;"><table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background:#fff;border:1px solid #e4e4e8;border-radius:18px;"><tr><td style="padding:17px 16px;"><div style="font-size:10px;letter-spacing:1.2px;text-transform:uppercase;color:#888894;font-weight:900;">Packages</div><div style="margin-top:7px;font-size:27px;line-height:31px;color:#111827;font-weight:900;">{{ $itemCount }}</div></td></tr></table></td>
            <td width="33.33%" style="padding-left:7px;"><table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background:#fff;border:1px solid #e4e4e8;border-radius:18px;"><tr><td style="padding:17px 16px;"><div style="font-size:10px;letter-spacing:1.2px;text-transform:uppercase;color:#888894;font-weight:900;">Credits Added</div><div style="margin-top:7px;font-size:27px;line-height:31px;color:#111827;font-weight:900;">{{ $totalCredits }}</div></td></tr></table></td>
        </tr></table>
    </td></tr>

    <tr><td style="padding:26px 34px 10px;">
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0"><tr>
            <td width="50%" valign="top" style="padding-right:9px;"><table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background:#fff;border:1px solid #dedfe4;border-radius:20px;"><tr><td style="padding:18px 20px;">
                <div style="font-size:11px;letter-spacing:1.6px;text-transform:uppercase;color:#611eb2;font-weight:900;">Payment Details</div>
                <div style="padding:14px 0 11px;border-bottom:1px solid #e5e6ea;"><div style="font-size:10px;text-transform:uppercase;color:#8f9099;font-weight:900;">Transaction ID</div><div style="margin-top:4px;font-size:13px;line-height:19px;font-weight:800;word-break:break-all;">{{ $transactionId ?: '-' }}</div></div>
                <div style="padding:12px 0 11px;border-bottom:1px solid #e5e6ea;"><div style="font-size:10px;text-transform:uppercase;color:#8f9099;font-weight:900;">Paid At</div><div style="margin-top:4px;font-size:13px;line-height:19px;font-weight:700;">{{ $paidAt }}</div></div>
                <div style="padding:12px 0 11px;border-bottom:1px solid #e5e6ea;"><div style="font-size:10px;text-transform:uppercase;color:#8f9099;font-weight:900;">Payment Status</div><div style="margin-top:6px;"><span style="display:inline-block;padding:5px 13px;background:#eafff5;border:1px solid #4ee0ad;border-radius:999px;color:#078b61;font-size:11px;font-weight:800;">{{ ucfirst($order->payment_status ?? 'Paid') }}</span></div></div>
                <div style="padding:12px 0 0;"><div style="font-size:10px;text-transform:uppercase;color:#8f9099;font-weight:900;">Payment Method</div><div style="margin-top:4px;font-size:13px;font-weight:700;">Credit / Debit Card</div></div>
            </td></tr></table></td>
            <td width="50%" valign="top" style="padding-left:9px;"><table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background:#fff;border:1px solid #dedfe4;border-radius:20px;"><tr><td style="padding:18px 20px;">
                <div style="font-size:11px;letter-spacing:1.6px;text-transform:uppercase;color:#611eb2;font-weight:900;">Billing Details</div>
                <div style="padding:14px 0 11px;border-bottom:1px solid #e5e6ea;"><div style="font-size:10px;text-transform:uppercase;color:#8f9099;font-weight:900;">Billing Name</div><div style="margin-top:4px;font-size:13px;font-weight:700;">{{ $billingName }}</div></div>
                <div style="padding:11px 0;border-bottom:1px solid #e5e6ea;"><div style="font-size:10px;text-transform:uppercase;color:#8f9099;font-weight:900;">Email</div><div style="margin-top:4px;font-size:12px;line-height:18px;word-break:break-word;">{{ $billingEmail ?: '-' }}</div></div>
                <div style="padding:11px 0;border-bottom:1px solid #e5e6ea;"><div style="font-size:10px;text-transform:uppercase;color:#8f9099;font-weight:900;">Phone</div><div style="margin-top:4px;font-size:12px;font-weight:700;">{{ $billingPhone ?: '-' }}</div></div>
                <div style="padding:11px 0 0;"><div style="font-size:10px;text-transform:uppercase;color:#8f9099;font-weight:900;">Billing Address</div><div style="margin-top:5px;font-size:12px;line-height:18px;font-weight:700;">@if($billingStreet || $billingCityLine || $billingCountry){{ $billingStreet }}@if($billingStreet && ($billingCityLine || $billingCountry))<br>@endif{{ $billingCityLine }}@if($billingCityLine && $billingCountry)<br>@endif{{ $billingCountry }}@else-@endif</div></div>
            </td></tr></table></td>
        </tr></table>
    </td></tr>

    <tr><td style="padding:24px 34px 5px;"><div style="font-size:24px;line-height:29px;color:#111827;font-weight:900;">Package Summary</div><div style="margin-top:4px;font-size:13px;line-height:20px;color:#74747d;">Purchased package details and added credits.</div></td></tr>
    <tr><td style="padding:7px 34px 8px;">
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="border-collapse:separate;border-spacing:0 10px;">
            @foreach($items as $item)
                @php
                    $quantity = max(1, (int) ($item->quantity ?? 1));
                    $unitPrice = (float) ($item->unit_price ?? 0);
                    $itemTotal = (float) ($item->total_price ?? 0);
                @endphp
                <tr><td><table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background:#fff;border:1px solid #e2e2e6;border-radius:18px;"><tr><td style="padding:16px 18px;"><table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td><div style="font-size:17px;line-height:22px;font-weight:900;">{{ $item->package_name ?? 'Training Package' }}</div><div style="margin-top:4px;font-size:12px;color:#637199;">Quantity {{ $quantity }} × ${{ number_format($unitPrice,2) }} · {{ (int) ($item->classes_per_package ?? 0) }} credits each</div></td><td width="145" align="right"><div style="font-size:10px;text-transform:uppercase;color:#9a9aa5;font-weight:900;">Item Total</div><div style="margin-top:4px;font-size:25px;color:#611eb2;font-weight:900;">${{ number_format($itemTotal,2) }}</div></td></tr></table></td></tr></table></td></tr>
            @endforeach
        </table>
    </td></tr>

    <tr><td style="padding:10px 34px 27px;">
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td width="51%">&nbsp;</td><td width="49%"><table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background:#fff;border:1px solid #e1e2e6;border-radius:20px;"><tr><td style="padding:17px 19px 19px;"><div style="margin-bottom:11px;font-size:11px;letter-spacing:1.6px;text-transform:uppercase;color:#611eb2;font-weight:900;">Order Details</div><table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
            <tr><td style="padding:3px 0;font-size:13px;color:#4f4f57;">Subtotal</td><td align="right" style="padding:3px 0;font-size:13px;font-weight:800;">${{ number_format($subtotal,2) }}</td></tr>
            <tr><td style="padding:3px 0;font-size:13px;color:#4f4f57;">CC Processing Fee</td><td align="right" style="padding:3px 0;font-size:13px;font-weight:800;">${{ number_format($processingFee,2) }}</td></tr>
            @if($tax > 0)<tr><td style="padding:3px 0;font-size:13px;color:#4f4f57;">Tax</td><td align="right" style="padding:3px 0;font-size:13px;font-weight:800;">${{ number_format($tax,2) }}</td></tr>@endif
            <tr><td colspan="2" style="padding-top:10px;"><div style="height:1px;background:#e5e5e9;"></div></td></tr>
            <tr><td style="padding-top:12px;font-size:17px;font-weight:900;">Grand Total</td><td align="right" style="padding-top:12px;font-size:25px;color:#611eb2;font-weight:900;">${{ number_format($total,2) }}</td></tr>
        </table></td></tr></table></td></tr></table>
    </td></tr>

    <tr><td style="padding:0 34px 32px;"><table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background:#faf7ff;border:1px solid #d7baf1;border-radius:20px;"><tr><td style="padding:18px 22px;text-align:center;font-size:13px;line-height:21px;color:#4b3b58;">This is your official payment receipt from <strong style="color:#171021;">ACES Lacrosse</strong>. Please keep this email for your records.</td></tr></table></td></tr>
</table>
<div style="padding:17px 10px 0;text-align:center;font-size:11px;line-height:18px;color:#8a8490;">© {{ date('Y') }} ACES Lacrosse. All rights reserved.</div>
</td></tr></table>
</td></tr></table>
</body></html>
