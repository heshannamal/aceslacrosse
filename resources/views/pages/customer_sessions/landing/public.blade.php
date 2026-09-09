@extends('layouts.app')

@section('title', 'Training | Alcatraz Outlaws')

@section('content')
@include('pages.customer_sessions._styles')

@php
    $loggedIn = !empty($customer);
    $guestTier = !$customer || $customer->isGuestAccount();
@endphp

<div class="ao-training">
    <div class="ao-wrap">
        @include('pages.customer_sessions._portal-nav')

        @if(session('success'))<div class="ao-alert ao-alert-success">{{ session('success') }}</div>@endif
        @if(session('error'))<div class="ao-alert ao-alert-error">{{ session('error') }}</div>@endif
        @if($errors->any())<div class="ao-alert ao-alert-error">{{ $errors->first() }}</div>@endif

        <section class="ao-public-hero mb-5">
            <div class="ao-public-hero-overlay"></div>
            <div class="ao-public-hero-copy">
                <span class="ao-public-kicker">ALCATRAZ OUTLAWS TRAINING</span>
                <h1>Train Hard.<br>Play Harder.</h1>
                <p>Browse upcoming Training sessions and packages without signing in. Login is required only when you reserve a player or continue through checkout.</p>
                <div class="d-flex flex-wrap gap-2">
                    <a class="ao-btn ao-btn-red" href="#trainingSessions"><i class="fa-regular fa-calendar"></i> View Sessions</a>
                    @if($loggedIn)
                        <a class="ao-btn ao-public-outline" href="{{ route('em.customer.dashboard') }}"><i class="fa-solid fa-table-cells-large"></i> Training Dashboard</a>
                    @else
                        <a class="ao-btn ao-public-outline" href="{{ route('em.customer.login') }}"><i class="fa-solid fa-right-to-bracket"></i> Sign In</a>
                        <a class="ao-btn ao-public-ghost" href="{{ route('em.customer.register') }}">Create Account</a>
                    @endif
                </div>
            </div>
        </section>

        @if($loggedIn)
            <section class="ao-panel p-4 mb-5">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-3">
                    <div>
                        <div class="ao-eyebrow">YOUR TRAINING ACCOUNT</div>
                        <h2 class="ao-section-title mb-0">Hi {{ $customer->first_name ?: $customer->display_name }}</h2>
                        <div class="ao-muted mt-1">{{ $guestTier ? 'Guest pricing applies to this account.' : 'Member pricing applies to this account.' }}</div>
                    </div>
                    <a class="ao-btn ao-btn-outline ao-btn-sm" href="{{ route('em.customer.profile') }}">Profile & Players</a>
                </div>
                <div class="ao-grid-3">
                    <div class="ao-stat"><span>Total Credits</span><strong>{{ $summary['total_credits'] }}</strong></div>
                    <div class="ao-stat"><span>Remaining Credits</span><strong>{{ $summary['remaining_credits'] }}</strong></div>
                    <div class="ao-stat"><span>Booked Sessions</span><strong>{{ $summary['booked_sessions'] }}</strong></div>
                </div>
            </section>
        @endif

        <section id="trainingSessions" class="mb-5">
            <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-3">
                <div>
                    <div class="ao-eyebrow">UPCOMING</div>
                    <h2 class="ao-section-title">Training Sessions</h2>
                    <p class="ao-muted mb-0">Everyone can view session details. Sign in only when you want to book.</p>
                </div>
                <a class="ao-btn ao-btn-outline ao-btn-sm" href="{{ route('em.customer.packages') }}">Training Packages</a>
            </div>

            <div class="ao-grid-3">
                @forelse($sessions as $session)
                    @php
                        $location = collect([$session->location, $session->street_address, $session->city])->filter()->unique()->implode(', ');
                    @endphp
                    <article class="ao-session-card">
                        <div class="d-flex justify-content-between align-items-start gap-3">
                            <div><div class="ao-eyebrow">TRAINING TYPE</div><h3>{{ $session->training_type ?: $session->name }}</h3></div>
                            <span class="ao-tag">{{ optional($session->event_date)->format('M d') }}</span>
                        </div>
                        <div class="ao-session-meta">
                            <div class="ao-meta-row"><i class="fa-regular fa-calendar"></i><span>{{ optional($session->event_date)->format('D, M j, Y') }}</span></div>
                            <div class="ao-meta-row"><i class="fa-regular fa-clock"></i><span>{{ \Carbon\Carbon::parse($session->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($session->end_time)->format('g:i A') }}</span></div>
                            <div class="ao-meta-row"><i class="fa-solid fa-location-dot"></i><span>{{ $location }}</span></div>
                            @if($session->instructor)<div class="ao-meta-row"><i class="fa-solid fa-user-group"></i><span>{{ $session->instructor }}</span></div>@endif
                            <div class="ao-meta-row"><i class="fa-solid fa-ticket"></i><span>{{ $session->is_full ? 'Session Full' : (($session->spots_left ?? 0) . ' spots left') }}</span></div>
                        </div>
                        <div class="ao-session-actions">
                            <button type="button" class="ao-btn ao-btn-outline ao-btn-sm" data-bs-toggle="modal" data-bs-target="#sessionDetails{{ $session->id }}">Details</button>
                            @if(!$loggedIn)
                                <a class="ao-btn ao-btn-red ao-btn-sm {{ $session->is_full ? 'disabled' : '' }}" href="{{ $session->is_full ? '#' : route('em.customer.login') }}">{{ $session->is_full ? 'Full' : 'Sign In to Book' }}</a>
                            @elseif($children->isEmpty())
                                <a class="ao-btn ao-btn-red ao-btn-sm {{ $session->is_full ? 'disabled' : '' }}" href="{{ $session->is_full ? '#' : route('em.customer.profile') }}">{{ $session->is_full ? 'Full' : 'Add Player to Book' }}</a>
                            @else
                                <button type="button" class="ao-btn ao-btn-red ao-btn-sm" data-bs-toggle="modal" data-bs-target="#sessionBook{{ $session->id }}" {{ $session->is_full ? 'disabled' : '' }}>{{ $session->is_full ? 'Full' : 'Book' }}</button>
                            @endif
                        </div>
                    </article>

                    <div class="modal fade" id="sessionDetails{{ $session->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered"><div class="modal-content ao-modal-card">
                            <div class="ao-modal-head"><div><small>TRAINING SESSION</small><div style="font-size:20px;font-weight:900">{{ $session->training_type ?: $session->name }}</div></div><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
                            <div class="modal-body p-4">
                                <p>{{ $session->description ?: 'Focused Alcatraz Outlaws training session.' }}</p>
                                <div class="ao-session-meta">
                                    <div class="ao-meta-row"><i class="fa-regular fa-calendar"></i><span>{{ optional($session->event_date)->format('l, M j, Y') }}</span></div>
                                    <div class="ao-meta-row"><i class="fa-regular fa-clock"></i><span>{{ \Carbon\Carbon::parse($session->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($session->end_time)->format('g:i A') }}</span></div>
                                    <div class="ao-meta-row"><i class="fa-solid fa-location-dot"></i><span>{{ $location }}</span></div>
                                    @if($session->instructor)<div class="ao-meta-row"><i class="fa-solid fa-user-group"></i><span>{{ $session->instructor }}</span></div>@endif
                                </div>
                                @if($session->what_to_bring)<div class="ao-cart-session mt-3"><strong>What to Bring</strong><div class="ao-muted mt-1">{{ $session->what_to_bring }}</div></div>@endif
                            </div>
                        </div></div>
                    </div>

                    @if($loggedIn && $children->isNotEmpty())
                        <div class="modal fade" id="sessionBook{{ $session->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered"><div class="modal-content ao-modal-card">
                                <div class="ao-modal-head"><div><small>ADD BOOKING</small><div style="font-size:20px;font-weight:900">{{ $session->training_type ?: $session->name }}</div></div><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
                                <div class="modal-body p-4">
                                    <form method="POST" action="{{ route('em.customer.session.book', $session->id) }}">
                                        @csrf
                                        <div class="ao-eyebrow mb-2">SELECT PLAYER</div>
                                        @foreach($children as $child)
                                            <label class="ao-player-option"><input type="radio" name="child_id" value="{{ $child->id }}" {{ $loop->first ? 'checked' : '' }}> <strong class="ms-2">{{ trim($child->first_name . ' ' . $child->last_name) }}</strong>@if($child->team)<span class="ao-muted ms-2">{{ $child->team }}</span>@endif</label>
                                        @endforeach
                                        @if($summary['remaining_credits'] < 1 && $featuredPackages->isNotEmpty())
                                            <div class="ao-field mt-3"><label>Package for this booking</label><select class="ao-select" name="package_id">@foreach($featuredPackages as $package)<option value="{{ $package->id }}">{{ $package->package_name }} — ${{ number_format($package->priceForCustomer($customer), 2) }}</option>@endforeach</select><small class="ao-muted">No available class credits. Checkout will be required.</small></div>
                                        @endif
                                        <div class="d-flex justify-content-end gap-2 mt-4"><button type="button" class="ao-btn ao-btn-outline" data-bs-dismiss="modal">Cancel</button><button class="ao-btn ao-btn-red">{{ $summary['remaining_credits'] > 0 ? 'Confirm Booking' : 'Continue to Cart' }} <i class="fa-solid fa-arrow-right"></i></button></div>
                                    </form>
                                </div>
                            </div></div>
                        </div>
                    @endif
                @empty
                    <div class="ao-panel p-5 text-center" style="grid-column:1/-1"><h3>No upcoming sessions yet</h3><p class="ao-muted mb-0">Check back soon for new Alcatraz Outlaws Training dates.</p></div>
                @endforelse
            </div>
        </section>

        <section class="mb-5">
            <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-3">
                <div><div class="ao-eyebrow">TRAINING CREDITS</div><h2 class="ao-section-title">Popular Packages</h2><p class="ao-muted mb-0">{{ $guestTier ? 'Guest pricing is shown.' : 'Member pricing is shown.' }}</p></div>
                <a class="ao-btn ao-btn-outline ao-btn-sm" href="{{ route('em.customer.packages') }}">All Packages</a>
            </div>
            <div class="ao-grid-3">
                @forelse($featuredPackages as $package)
                    @php $displayPrice = $package->priceForCustomer($customer); @endphp
                    <article class="ao-panel p-4 d-flex flex-column">
                        <span class="ao-tag" style="width:max-content">{{ $package->available_classes }} {{ \Illuminate\Support\Str::plural('Class', $package->available_classes) }}</span>
                        <h3 class="mt-3 mb-1" style="font-weight:900">{{ $package->package_name }}</h3>
                        <p class="ao-muted flex-grow-1">{{ $package->package_description ?: 'Alcatraz Outlaws training class credits.' }}</p>
                        <div class="mb-3"><small class="ao-muted">{{ $guestTier ? 'Guest Price' : 'Member Price' }}</small><div style="font-size:34px;font-weight:900;color:#f3282c">${{ number_format($displayPrice, 2) }}</div></div>
                        <form method="POST" action="{{ route('em.customer.cart.add', $package->id) }}">@csrf<button class="ao-btn ao-btn-red w-100">Add to Training Cart</button></form>
                    </article>
                @empty
                    <div class="ao-panel p-5 text-center" style="grid-column:1/-1">No packages available.</div>
                @endforelse
            </div>
        </section>
    </div>
</div>

<style>
.ao-public-hero{position:relative;overflow:hidden;min-height:520px;border-radius:24px;background:url('{{ asset('bay-area-lacrosse-academy-images/1.jpg') }}') center/cover no-repeat;box-shadow:0 24px 55px rgba(5,13,25,.16);display:flex;align-items:center;padding:64px}.ao-public-hero-overlay{position:absolute;inset:0;background:linear-gradient(90deg,rgba(5,8,13,.95),rgba(5,8,13,.72) 52%,rgba(5,8,13,.18))}.ao-public-hero-copy{position:relative;z-index:2;max-width:650px;color:#fff}.ao-public-kicker{font-size:12px;font-weight:900;letter-spacing:.18em;color:#ff5a5e}.ao-public-hero h1{font-size:clamp(50px,6vw,80px);line-height:.93;font-weight:900;letter-spacing:-.05em;margin:15px 0 20px;color:#fff}.ao-public-hero p{max-width:580px;color:#dce1e7;font-size:18px;line-height:1.6;margin-bottom:28px}.ao-public-outline,.ao-public-ghost{border:1px solid rgba(255,255,255,.7);background:rgba(255,255,255,.06);color:#fff}.ao-public-outline:hover,.ao-public-ghost:hover{background:rgba(255,255,255,.16);color:#fff}.ao-player-option{display:flex;align-items:center;width:100%;padding:13px 14px;margin-bottom:8px;border:1px solid #e3e8ef;border-radius:12px;cursor:pointer}.ao-player-option:hover{border-color:#f3282c;background:#fff8f8}@media(max-width:767px){.ao-public-hero{min-height:500px;padding:36px 26px}.ao-public-hero p{font-size:16px}.ao-public-hero h1{font-size:49px}}
</style>
@endsection
