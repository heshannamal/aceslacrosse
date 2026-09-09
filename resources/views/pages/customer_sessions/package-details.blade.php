@extends('layouts.app')

@section('title', $package->package_name . ' | ACES Training')

@section('content')
@include('pages.customer_sessions._styles')

@php
    $displayPrice = $package->priceForCustomer($customer ?? null);
    $priceLabel = (!$customer || $customer->isGuestAccount()) ? 'Guest Price' : 'Member Price';
@endphp

<div class="ao-training"><div class="ao-wrap">
    @include('pages.customer_sessions._portal-nav')
    @if(session('success'))<div class="ao-alert ao-alert-success">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="ao-alert ao-alert-error">{{ session('error') }}</div>@endif

    <div class="ao-panel p-4 p-lg-5">
        <a href="{{ route('em.customer.packages') }}" class="ao-chip-link mb-4"><i class="fa-solid fa-arrow-left"></i> Packages</a>
        <div class="ao-grid-2 align-items-center">
            <div>
                <div class="ao-eyebrow">ACES TRAINING PACKAGE</div>
                <h1 class="ao-title" style="font-size:52px">{{ $package->package_name }}</h1>
                <p class="ao-muted" style="font-size:17px">{{ $package->package_description ?: 'Use these class credits to book available ACES Lacrosse training sessions.' }}</p>
                <div class="d-flex gap-2 flex-wrap mt-4"><span class="ao-tag"><i class="fa-solid fa-ticket"></i> {{ $package->available_classes }} {{ \Illuminate\Support\Str::plural('Class', $package->available_classes) }}</span><span class="ao-tag"><i class="fa-solid fa-repeat"></i> Credits stay on your account until used</span></div>
            </div>
            <div class="ao-panel p-4" style="background:#faf7ff;border-color:#dcc8f2">
                <small class="ao-muted">{{ strtoupper($priceLabel) }}</small>
                <div style="font-size:48px;font-weight:900;color:#611eb2">${{ number_format($displayPrice, 2) }}</div>
                @if($package->available_classes > 0)<div class="ao-muted mb-4">${{ number_format($displayPrice / $package->available_classes, 2) }} per class</div>@endif
                <form method="POST" action="{{ route('em.customer.cart.add', $package->id) }}">
                    @csrf
                    <div class="ao-field mb-3"><label>Quantity</label><input class="ao-input" type="number" min="1" max="10" name="quantity" value="1"></div>
                    <button class="ao-btn ao-btn-red w-100">Add Package to Training Cart <i class="fa-solid fa-cart-plus"></i></button>
                </form>
                @if(!$customer)<div class="ao-muted mt-3" style="font-size:12px"><i class="fa-solid fa-lock me-1"></i>You can add this now. Sign in or create an account at checkout.</div>@endif
            </div>
        </div>
    </div>
</div></div>
@endsection
