@extends('layouts.app')

@section('title', 'Training Cart')

@section('content')
@include('pages.customer_sessions._styles')

<div class="ao-training"><div class="ao-wrap">
    @include('pages.customer_sessions._portal-nav')
    @if(session('success'))<div class="ao-alert ao-alert-success">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="ao-alert ao-alert-error">{{ session('error') }}</div>@endif
    @if($errors->any())<div class="ao-alert ao-alert-error">{{ $errors->first() }}</div>@endif

    <div class="mb-4">
        <div class="ao-eyebrow">TRAINING CART</div>
        <h1 class="ao-section-title">Review Training Packages</h1>
        <p class="ao-muted mb-0">Guest pricing is shown while browsing. You will be asked to sign in or create a Training account when you checkout.</p>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            @forelse($cartItems as $item)
                <article class="ao-cart-item">
                    <div class="ao-cart-item-head">
                        <div>
                            <span class="ao-tag">Guest Price</span>
                            <h2 class="mt-2 mb-1" style="font-weight:900">{{ $item->package->package_name }}</h2>
                            <div class="ao-muted">{{ $item->package->available_classes }} {{ \Illuminate\Support\Str::plural('class', $item->package->available_classes) }} per package</div>
                        </div>
                        <div class="text-end"><small class="ao-muted">LINE TOTAL</small><div style="color:#f3282c;font-size:28px;font-weight:900">${{ number_format($item->total_price, 2) }}</div><small class="ao-muted">${{ number_format($item->unit_price, 2) }} each</small></div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap mt-3">
                        <form class="d-flex gap-2 align-items-center" method="POST" action="{{ route('em.customer.cart.update', $item->id) }}">
                            @csrf
                            <label style="font-weight:800">Qty</label>
                            <input class="ao-input" style="width:82px" type="number" min="1" max="10" name="quantity" value="{{ $item->quantity }}">
                            <button class="ao-btn ao-btn-outline ao-btn-sm">Update</button>
                        </form>
                        <form method="POST" action="{{ route('em.customer.cart.remove', $item->id) }}">
                            @csrf @method('DELETE')
                            <button class="btn btn-link text-danger fw-bold text-decoration-none"><i class="fa-regular fa-trash-can"></i> Remove</button>
                        </form>
                    </div>
                </article>
            @empty
                <div class="ao-panel p-5 text-center">
                    <i class="fa-solid fa-cart-shopping fa-3x mb-3" style="color:#f3282c"></i>
                    <h2>Your Training cart is empty</h2>
                    <p class="ao-muted">Choose a Training package to continue.</p>
                    <a class="ao-btn ao-btn-red" href="{{ route('em.customer.packages') }}">Browse Packages</a>
                </div>
            @endforelse
        </div>

        <div class="col-lg-4">
            <aside class="ao-panel ao-summary">
                <div class="ao-eyebrow">GUEST CHECKOUT</div>
                <h2 style="font-weight:900">Summary</h2>
                <div class="ao-summary-row"><span>Subtotal</span><strong>${{ number_format($totals['subtotal'], 2) }}</strong></div>
                <div class="ao-summary-row"><span>CC Processing Fee 3%</span><strong>${{ number_format($totals['processing_fee'], 2) }}</strong></div>
                <div class="ao-summary-total"><span>Total</span><strong>${{ number_format($totals['total'], 2) }}</strong></div>

                @if($cartItems->isNotEmpty())
                    <a class="ao-btn ao-btn-red w-100 mt-3" href="{{ route('em.customer.checkout') }}">Proceed to Checkout <i class="fa-solid fa-arrow-right"></i></a>
                    <div class="ao-cart-login-note"><i class="fa-solid fa-user-lock"></i><span>At checkout, sign in to an existing Training account or create a new Parent 1 + player account.</span></div>
                @endif
                <a class="ao-btn ao-btn-outline w-100 mt-2" href="{{ route('em.customer.packages') }}">Continue Shopping</a>
            </aside>
        </div>
    </div>
</div></div>

<style>
.ao-cart-login-note{display:flex;gap:10px;margin-top:14px;padding:13px;border-radius:13px;background:#fff5f5;color:#667085;font-size:12px;font-weight:700;line-height:1.45}.ao-cart-login-note i{color:#f3282c;margin-top:2px}
</style>
@endsection
