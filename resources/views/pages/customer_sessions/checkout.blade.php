@extends('layouts.app')

@section('title', 'Training Checkout | Alcatraz Outlaws')

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
    $fullName = trim(($customer->first_name ?? '').' '.($customer->last_name ?? ''));
@endphp

<style>
:root{--ao-red:#f3282c;--ao-soft:#fff1f1;--ao-bg:#f5f6f8;--ao-border:#e0e3e8;--ao-muted:#666d79}.training-checkout-page{min-height:calc(100vh - 104px);background:var(--ao-bg);padding:24px 0 55px;font-family:'Raleway',sans-serif;color:#111}.training-checkout-shell{width:min(1230px,calc(100% - 24px));margin:0 auto}.training-checkout-grid{display:grid;grid-template-columns:minmax(0,1fr) 310px;gap:20px;align-items:stretch}.training-checkout-card{background:#fff;border:1px solid var(--ao-border);border-radius:26px;padding:20px;box-shadow:0 14px 30px rgba(0,0,0,.045)}.training-checkout-title{margin:0 0 16px;font-family:'Archivo Black',sans-serif;font-size:26px;letter-spacing:-.04em}.training-checkout-columns{display:grid;grid-template-columns:1fr 1fr;gap:20px}.training-checkout-section{min-width:0}.training-checkout-section:first-child{padding-right:18px;border-right:1px solid #cfd3d8}.training-checkout-section-head{display:flex;align-items:center;justify-content:space-between;gap:14px;border-bottom:1px solid #dfe2e7;padding-bottom:12px;margin-bottom:13px}.training-checkout-section-head h3{margin:0;font-size:19px;font-weight:500}.training-checkout-section-head i{color:var(--ao-red)}.training-fields-2{display:grid;grid-template-columns:1fr 1fr;gap:12px}.training-fields-email{display:grid;grid-template-columns:1.4fr .7fr;gap:12px}.training-fields-address{display:grid;grid-template-columns:1.25fr .65fr .55fr;gap:12px}.training-fields-card{display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px}.training-field{margin-bottom:12px}.training-field label{display:block;margin-bottom:5px;font-size:10px;font-weight:900}.training-field label span{color:var(--ao-red)}.training-input,.training-select{width:100%;height:42px;border:1px solid #d7dbe1;border-radius:8px;background:#fff;padding:0 11px;font-size:12px;outline:none;transition:.18s}.training-input:focus,.training-select:focus{border-color:var(--ao-red);box-shadow:0 0 0 3px rgba(243,40,44,.08)}.training-error{display:block;margin-top:4px;color:#b42318;font-size:10px;font-weight:800}.training-checkout-summary{background:#fff;border:1px solid var(--ao-border);border-radius:26px;padding:20px;box-shadow:0 14px 30px rgba(0,0,0,.06);display:flex;flex-direction:column}.training-checkout-summary-head{display:flex;align-items:center;justify-content:space-between;gap:15px}.training-checkout-summary-head h2{margin:0;font-size:25px;font-weight:500}.training-checkout-summary-head i{color:var(--ao-red);font-size:19px}.training-summary-row{display:flex;justify-content:space-between;gap:14px;padding:8px 0;color:#5e6571;font-size:12px;font-weight:800}.training-summary-spacer{flex:1;min-height:90px}.training-summary-total-area{border-top:1px solid #dfe2e7;padding-top:17px}.training-summary-total{display:flex;justify-content:space-between;align-items:flex-end;gap:14px}.training-summary-total span{font-size:16px;font-weight:900}.training-summary-total strong{font-size:29px;color:var(--ao-red);font-weight:500}.training-pay-btn{width:100%;min-height:46px;border:0;border-radius:999px;margin-top:18px;background:var(--ao-red);color:#fff;font-size:12px;font-weight:900;box-shadow:0 12px 24px rgba(243,40,44,.24)}.training-pay-btn:hover{background:#d91f24}.training-checkout-alert{margin-bottom:15px;border-radius:14px;padding:12px 14px;font-size:12px;font-weight:800}.training-checkout-alert.error{background:#fff1f2;color:#b42318}.training-security{margin-top:12px;color:#69707b;font-size:10px;line-height:1.5}.training-security i{color:var(--ao-red)}@media(max-width:950px){.training-checkout-grid{grid-template-columns:1fr}.training-summary-spacer{min-height:0}.training-checkout-summary{min-height:auto}}@media(max-width:720px){.training-checkout-columns{grid-template-columns:1fr}.training-checkout-section:first-child{padding-right:0;border-right:0;border-bottom:1px solid #dfe2e7;padding-bottom:8px}.training-fields-2,.training-fields-email,.training-fields-address,.training-fields-card{grid-template-columns:1fr}.training-checkout-shell{width:min(100% - 16px,1230px)}.training-checkout-card,.training-checkout-summary{border-radius:20px;padding:16px}}
</style>

<section class="training-checkout-page">
    <div class="training-checkout-shell">
        @if(session('error'))<div class="training-checkout-alert error">{{ session('error') }}</div>@endif
        @if($errors->any())<div class="training-checkout-alert error">Please check the highlighted fields and try again.</div>@endif

        <form method="POST" action="{{ route('em.customer.pay') }}" autocomplete="off">
            @csrf
            <div class="training-checkout-grid">
                <div class="training-checkout-card">
                    <h1 class="training-checkout-title">Billing Details</h1>
                    <div class="training-checkout-columns">
                        <section class="training-checkout-section">
                            <div class="training-checkout-section-head"><h3>Billing</h3><i class="fa-regular fa-user"></i></div>
                            <div class="training-fields-2">
                                <div class="training-field"><label>First Name <span>*</span></label><input class="training-input" name="first_name" value="{{ old('first_name',$customer->first_name) }}" required>@error('first_name')<small class="training-error">{{ $message }}</small>@enderror</div>
                                <div class="training-field"><label>Last Name</label><input class="training-input" name="last_name" value="{{ old('last_name',$customer->last_name) }}">@error('last_name')<small class="training-error">{{ $message }}</small>@enderror</div>
                            </div>
                            <div class="training-fields-email">
                                <div class="training-field"><label>Email <span>*</span></label><input class="training-input" type="email" name="email" value="{{ old('email',$customer->email) }}" required>@error('email')<small class="training-error">{{ $message }}</small>@enderror</div>
                                <div class="training-field"><label>Phone</label><input class="training-input" name="phone" value="{{ old('phone',$customer->phone) }}">@error('phone')<small class="training-error">{{ $message }}</small>@enderror</div>
                            </div>
                            <div class="training-field"><label>Street Address <span>*</span></label><input class="training-input" name="street" value="{{ old('street') }}" required>@error('street')<small class="training-error">{{ $message }}</small>@enderror</div>
                            <div class="training-fields-address">
                                <div class="training-field"><label>City <span>*</span></label><input class="training-input" name="city" value="{{ old('city') }}" required>@error('city')<small class="training-error">{{ $message }}</small>@enderror</div>
                                <div class="training-field"><label>State <span>*</span></label><input class="training-input" name="state" value="{{ old('state') }}" required>@error('state')<small class="training-error">{{ $message }}</small>@enderror</div>
                                <div class="training-field"><label>Zip <span>*</span></label><input class="training-input" name="zip" value="{{ old('zip') }}" required>@error('zip')<small class="training-error">{{ $message }}</small>@enderror</div>
                            </div>
                            <div class="training-field"><label>Country <span>*</span></label><input class="training-input" name="country" value="{{ old('country','USA') }}" required>@error('country')<small class="training-error">{{ $message }}</small>@enderror</div>
                        </section>

                        <section class="training-checkout-section">
                            <div class="training-checkout-section-head"><h3>Card Details</h3><i class="fa-regular fa-credit-card"></i></div>
                            <div class="training-field"><label>Name on Card <span>*</span></label><input class="training-input" name="name_on_card" value="{{ old('name_on_card',$fullName) }}" required>@error('name_on_card')<small class="training-error">{{ $message }}</small>@enderror</div>
                            <div class="training-field"><label>Card Number <span>*</span></label><input class="training-input" name="card_number" inputmode="numeric" autocomplete="cc-number" placeholder="1234 1234 1234 1234" required>@error('card_number')<small class="training-error">{{ $message }}</small>@enderror</div>
                            <div class="training-fields-card">
                                <div class="training-field"><label>Month <span>*</span></label><select class="training-select" name="exp_month" required><option value="">MM</option>@for($m=1;$m<=12;$m++)<option value="{{ $m }}" @selected(old('exp_month')==$m)>{{ str_pad($m,2,'0',STR_PAD_LEFT) }}</option>@endfor</select>@error('exp_month')<small class="training-error">{{ $message }}</small>@enderror</div>
                                <div class="training-field"><label>Year <span>*</span></label><select class="training-select" name="exp_year" required><option value="">YYYY</option>@for($y=now()->year;$y<=now()->year+20;$y++)<option value="{{ $y }}" @selected(old('exp_year')==$y)>{{ $y }}</option>@endfor</select>@error('exp_year')<small class="training-error">{{ $message }}</small>@enderror</div>
                                <div class="training-field"><label>CVV <span>*</span></label><input class="training-input" name="cvv" inputmode="numeric" maxlength="4" autocomplete="cc-csc" required>@error('cvv')<small class="training-error">{{ $message }}</small>@enderror</div>
                            </div>
                            <p class="training-security"><i class="fa-solid fa-lock me-1"></i>Card number and CVV are sent to the payment gateway for this transaction and are not stored by Alcatraz Outlaws.</p>
                        </section>
                    </div>
                </div>

                <aside class="training-checkout-summary">
                    <div class="training-checkout-summary-head"><h2>Order Summary</h2><i class="fa-solid fa-cart-shopping"></i></div>
                    <div style="margin-top:17px">
                        <div class="training-summary-row"><span>Total Credits</span><strong>{{ $totalCredits }}</strong></div>
                        <div class="training-summary-row"><span>Booked Sessions</span><strong>{{ $bookedSessions }}</strong></div>
                        <div class="training-summary-row"><span>Remaining Credits</span><strong>{{ $remainingCredits }}</strong></div>
                    </div>
                    <div class="training-summary-spacer"></div>
                    <div class="training-summary-total-area">
                        <div class="training-summary-row"><span>Subtotal</span><strong>${{ number_format($subtotal,2) }}</strong></div>
                        <div class="training-summary-row"><span>CC Processing Fee 3%</span><strong>${{ number_format($processingFee,2) }}</strong></div>
                        <div class="training-summary-total"><span>Total</span><strong>${{ number_format($total,2) }}</strong></div>
                        <button class="training-pay-btn" type="submit">Pay ${{ number_format($total,2) }}</button>
                    </div>
                </aside>
            </div>
        </form>
    </div>
</section>
@endsection
