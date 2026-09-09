@extends('layouts.app')

@section('title', 'Training Packages | ACES Lacrosse')

@section('content')
@include('pages.customer_sessions._styles')

@php
    $isTrainingLoggedIn = !empty($customer);
    $priceLabel = (!$customer || $customer->isGuestAccount()) ? 'Guest Price' : 'Member Price';
@endphp

<div class="ao-training"><div class="ao-wrap">
    @include('pages.customer_sessions._portal-nav')
    @if(session('success'))<div class="ao-alert ao-alert-success">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="ao-alert ao-alert-error">{{ session('error') }}</div>@endif

    <div class="d-flex align-items-end justify-content-between gap-3 flex-wrap mb-4">
        <div>
            <div class="ao-eyebrow">ACES TRAINING CREDITS</div>
            <h1 class="ao-section-title">Choose a Package</h1>
            <p class="ao-muted mb-0">Browse and add packages without signing in. Authentication is required when you continue to checkout.</p>
        </div>
        @if(!$isTrainingLoggedIn)
            <a class="ao-btn ao-btn-outline" href="{{ route('em.customer.login') }}"><i class="fa-solid fa-right-to-bracket"></i> Training Sign In</a>
        @endif
    </div>

    <div class="ao-grid-3">
        @forelse($packages as $package)
            @php $displayPrice = $package->priceForCustomer($customer); @endphp
            <article class="ao-panel p-4 d-flex flex-column">
                <div class="d-flex justify-content-between align-items-start gap-2">
                    <span class="ao-tag" style="width:max-content">{{ $package->available_classes }} {{ \Illuminate\Support\Str::plural('Class', $package->available_classes) }}</span>
                    <span class="ao-price-tier">{{ $priceLabel }}</span>
                </div>
                <h2 class="mt-3 mb-1" style="font-weight:900">{{ $package->package_name }}</h2>
                <p class="ao-muted flex-grow-1">{{ $package->package_description ?: 'ACES Lacrosse training class credits.' }}</p>
                <div class="d-flex align-items-end justify-content-between gap-3 mb-3">
                    <div><small class="ao-muted">{{ $priceLabel }}</small><div style="font-size:36px;font-weight:900;color:#611eb2">${{ number_format($displayPrice, 2) }}</div></div>
                    @if($package->available_classes > 0)<small class="ao-muted">${{ number_format($displayPrice / $package->available_classes, 2) }}/class</small>@endif
                </div>
                <div class="d-grid gap-2">
                    <a class="ao-btn ao-btn-outline" href="{{ route('em.customer.package.details', $package->id) }}">Details</a>
                    <form method="POST" action="{{ route('em.customer.cart.add', $package->id) }}">@csrf<button class="ao-btn ao-btn-red w-100">Add to Training Cart <i class="fa-solid fa-cart-plus"></i></button></form>
                </div>
            </article>
        @empty
            <div class="ao-panel p-5 text-center" style="grid-column:1/-1"><h3>No packages available</h3><p class="ao-muted mb-0">Please check again later.</p></div>
        @endforelse
    </div>
</div></div>
<style>.ao-price-tier{font-size:10px;font-weight:900;letter-spacing:.08em;text-transform:uppercase;color:#667085;background:#f2f4f7;border-radius:999px;padding:6px 9px}</style>
@endsection
