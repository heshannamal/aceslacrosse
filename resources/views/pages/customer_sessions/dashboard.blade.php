@extends('layouts.app')

@section('title', 'Training Dashboard | Alcatraz Outlaws')

@section('content')
@include('pages.customer_sessions._styles')

@php
    $customerName = trim((string) ($customer->display_name ?? ''));

    if ($customerName === '') {
        $customerName = trim(
            (string) ($customer->first_name ?? '') . ' ' .
            (string) ($customer->last_name ?? '')
        );
    }

    if ($customerName === '') {
        $customerName = 'Parent';
    }

    $customerInitial = strtoupper(substr($customerName, 0, 1));
    $customerInitial = $customerInitial !== '' ? $customerInitial : 'A';
@endphp

<div class="training-dashboard-nav-spacer" id="trainingDashboardNavSpacer"></div>

<nav class="training-dashboard-nav" id="trainingDashboardNav" aria-label="Training account navigation">
    <div class="training-dashboard-nav-inner">
        <a href="{{ route('em.customer.dashboard') }}" class="training-dashboard-brand">
            <span class="training-dashboard-brand-mark">
                <img src="{{ asset('images/logo.png') }}" alt="Alcatraz Outlaws">
            </span>
            <span>
                <strong>TRAINING</strong>
                <small>ACCOUNT</small>
            </span>
        </a>

        <div class="training-dashboard-links">
            <a href="{{ route('em.customer.index') }}" class="training-dashboard-link">
                <i class="fa-regular fa-calendar"></i>
                <span>Training</span>
            </a>

            <a href="{{ route('em.customer.dashboard') }}" class="training-dashboard-link active">
                <i class="fa-solid fa-table-cells-large"></i>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('em.customer.bookings') }}" class="training-dashboard-link">
                <i class="fa-regular fa-calendar-check"></i>
                <span>Bookings</span>
            </a>

            <a href="{{ route('em.customer.packages') }}" class="training-dashboard-link">
                <i class="fa-solid fa-box-open"></i>
                <span>Packages</span>
            </a>

            <a href="{{ route('em.customer.profile') }}" class="training-dashboard-link">
                <i class="fa-regular fa-user"></i>
                <span>Profile</span>
            </a>
        </div>

        <div class="training-dashboard-actions">
            <a href="{{ route('em.customer.cart') }}" class="training-dashboard-cart" title="Training Cart">
                <i class="fa-solid fa-cart-shopping"></i>
                <span>Cart</span>
                @if((int) $trainingCartCount > 0)
                    <b>{{ (int) $trainingCartCount > 99 ? '99+' : (int) $trainingCartCount }}</b>
                @endif
            </a>

            <div class="training-dashboard-user">
                <span class="training-dashboard-avatar">{{ $customerInitial }}</span>
                <span class="training-dashboard-user-copy">
                    <strong>{{ $customerName }}</strong>
                    <small>{{ $customer->email }}</small>
                </span>
            </div>

            <form method="POST" action="{{ route('em.customer.logout') }}" class="training-dashboard-logout-form">
                @csrf
                <button type="submit" class="training-dashboard-logout">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </div>
</nav>

<div class="ao-training outlaws-training-dashboard">
    <div class="ao-wrap">
        @if(session('success'))
            <div class="ao-alert ao-alert-success mb-3">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="ao-alert ao-alert-error mb-3">{{ session('error') }}</div>
        @endif

        @if($errors->any())
            <div class="ao-alert ao-alert-error mb-3">{{ $errors->first() }}</div>
        @endif

        <section class="otd-hero">
            <div class="row g-4 align-items-center">
                <div class="col-lg-7">
                    <span class="otd-eyebrow">
                        <i class="fa-solid fa-bolt"></i>
                        Parent Dashboard
                    </span>

                    <h1>Welcome Back,<br>{{ $customerName }}</h1>
                    <p>Manage your Alcatraz Outlaws Training credits, players, bookings and upcoming sessions.</p>

                    <div class="d-flex gap-2 flex-wrap mt-3">
                        <a href="{{ route('em.customer.index') }}#trainingSessions" class="otd-primary-btn">
                            Book Training <i class="fa-regular fa-calendar"></i>
                        </a>

                        <a href="{{ route('em.customer.profile') }}" class="otd-hero-outline-btn">
                            Manage Players <i class="fa-solid fa-users"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="row g-3">
                        <div class="col-4">
                            <div class="otd-stat">
                                <span>Available Credits</span>
                                <strong class="red">{{ $remainingCredits }}</strong>
                            </div>
                        </div>

                        <div class="col-4">
                            <div class="otd-stat">
                                <span>Used Credits</span>
                                <strong>{{ $usedCredits }}</strong>
                            </div>
                        </div>

                        <div class="col-4">
                            <div class="otd-stat">
                                <span>Bookings</span>
                                <strong>{{ $bookingsCount }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="otd-section">
            <div class="otd-section-head">
                <div>
                    <span class="otd-kicker">TRAINING SESSIONS</span>
                    <h2>My Bookings</h2>
                    <p>Upcoming and past Alcatraz Outlaws Training sessions.</p>
                </div>

                <a href="{{ route('em.customer.index') }}#trainingSessions" class="otd-outline-btn">
                    Browse Sessions <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>

            @if($bookingsCount > 0)
                @foreach($bookingGroups as $group)
                    <div class="otd-booking-group">
                        <button
                            type="button"
                            class="otd-group-toggle"
                            data-bs-toggle="collapse"
                            data-bs-target="#{{ $group['collapse_id'] }}"
                            aria-expanded="{{ $loop->first ? 'true' : 'false' }}"
                            aria-controls="{{ $group['collapse_id'] }}"
                        >
                            <div class="otd-group-left">
                                <span class="otd-group-icon">
                                    <i class="{{ $group['icon'] }}"></i>
                                </span>
                                <div>
                                    <h3>{{ $group['title'] }}</h3>
                                    <p>{{ $group['subtitle'] }}</p>
                                </div>
                            </div>

                            <div class="otd-group-right">
                                <span>{{ $group['items']->count() }} bookings</span>
                                <i class="fa-solid fa-chevron-down"></i>
                            </div>
                        </button>

                        <div id="{{ $group['collapse_id'] }}" class="collapse {{ $loop->first ? 'show' : '' }}">
                            <div class="otd-group-body">
                                @if($group['items']->count() > 0)
                                    <div class="otd-card-grid">
                                        @foreach($group['items'] as $booking)
                                            @php
                                                $session = $booking->sessionEvent;
                                                $child = $booking->child;

                                                $sessionTitle = 'Training Session';
                                                if ($session && !empty($session->training_type)) {
                                                    $sessionTitle = $session->training_type;
                                                } elseif ($session && !empty($session->name)) {
                                                    $sessionTitle = $session->name;
                                                }

                                                $dateText = '-';
                                                if ($session && !empty($session->event_date)) {
                                                    try {
                                                        $dateText = \Carbon\Carbon::parse($session->event_date)->format('D, M d, Y');
                                                    } catch (\Throwable $e) {
                                                        $dateText = (string) $session->event_date;
                                                    }
                                                }

                                                $startText = '';
                                                if ($session && !empty($session->start_time)) {
                                                    try {
                                                        $startText = \Carbon\Carbon::parse($session->start_time)->format('g:i A');
                                                    } catch (\Throwable $e) {
                                                        $startText = (string) $session->start_time;
                                                    }
                                                }

                                                $endText = '';
                                                if ($session && !empty($session->end_time)) {
                                                    try {
                                                        $endText = \Carbon\Carbon::parse($session->end_time)->format('g:i A');
                                                    } catch (\Throwable $e) {
                                                        $endText = (string) $session->end_time;
                                                    }
                                                }

                                                $timeText = trim($startText . ($endText !== '' ? ' - ' . $endText : ''));
                                                if ($timeText === '') {
                                                    $timeText = '-';
                                                }

                                                $playerFirst = $child ? $child->first_name : $booking->player_first;
                                                $playerLast = $child ? $child->last_name : $booking->player_last;
                                                $playerName = trim((string) $playerFirst . ' ' . (string) $playerLast);
                                                if ($playerName === '') {
                                                    $playerName = 'Player';
                                                }

                                                $whatToBring = '-';
                                                if ($session && !empty($session->what_to_bring)) {
                                                    $whatToBring = $session->what_to_bring;
                                                }

                                                $locationText = '-';
                                                if ($session && !empty($session->location)) {
                                                    $locationText = $session->location;
                                                }

                                                $statusText = strtoupper(str_replace('_', ' ', (string) $booking->status));
                                                $canCancel = !$group['past'] && in_array(
                                                    (string) $booking->status,
                                                    ['pending_payment', 'booked', 'paid'],
                                                    true
                                                );
                                            @endphp

                                            <article class="otd-booking-card {{ $group['past'] ? 'is-past' : '' }}">
                                                <div class="otd-card-title-row">
                                                    <div>
                                                        <span class="otd-status {{ strtolower((string) $booking->status) }}">
                                                            {{ $statusText }}
                                                        </span>
                                                        <h3>{{ $sessionTitle }}</h3>
                                                    </div>
                                                    <span class="otd-booking-no">{{ $booking->booking_no }}</span>
                                                </div>

                                                <div class="otd-detail-list">
                                                    <div class="otd-detail">
                                                        <span><i class="fa-regular fa-calendar"></i></span>
                                                        <div><small>Date</small><strong>{{ $dateText }}</strong></div>
                                                    </div>

                                                    <div class="otd-detail">
                                                        <span><i class="fa-regular fa-clock"></i></span>
                                                        <div><small>Time</small><strong>{{ $timeText }}</strong></div>
                                                    </div>

                                                    <div class="otd-detail">
                                                        <span><i class="fa-solid fa-user"></i></span>
                                                        <div><small>Player</small><strong>{{ $playerName }}</strong></div>
                                                    </div>

                                                    <div class="otd-detail">
                                                        <span><i class="fa-solid fa-location-dot"></i></span>
                                                        <div><small>Location</small><strong>{{ $locationText }}</strong></div>
                                                    </div>

                                                    <div class="otd-detail full-width">
                                                        <span><i class="fa-solid fa-list-check"></i></span>
                                                        <div><small>What To Bring</small><strong>{{ $whatToBring }}</strong></div>
                                                    </div>
                                                </div>

                                                @if($canCancel)
                                                    <form
                                                        method="POST"
                                                        action="{{ route('em.customer.booking.cancel', $booking->id) }}"
                                                        onsubmit="return confirm('Cancel this Training booking?')"
                                                        class="otd-card-actions"
                                                    >
                                                        @csrf
                                                        <button type="submit" class="otd-cancel-btn">
                                                            <i class="fa-regular fa-trash-can"></i>
                                                            Cancel Booking
                                                        </button>
                                                    </form>
                                                @endif
                                            </article>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="otd-empty">No bookings in this section.</div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="otd-empty large">
                    <i class="fa-regular fa-calendar-xmark"></i>
                    <h3>No bookings yet</h3>
                    <p>Choose an upcoming Alcatraz Outlaws Training session to get started.</p>
                    <a href="{{ route('em.customer.index') }}#trainingSessions" class="otd-primary-btn">
                        Book Now
                    </a>
                </div>
            @endif
        </section>
    </div>
</div>

<style>
    .training-dashboard-nav-spacer {
        height: 74px;
    }

    .training-dashboard-nav {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 990;
        background: rgba(255, 255, 255, .97);
        border-top: 1px solid rgba(0, 0, 0, .04);
        border-bottom: 1px solid rgba(0, 0, 0, .08);
        box-shadow: 0 10px 28px rgba(15, 23, 42, .08);
        backdrop-filter: blur(15px);
        transition: top .08s linear;
    }

    .training-dashboard-nav-inner {
        width: min(1240px, calc(100% - 32px));
        min-height: 74px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .training-dashboard-brand {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        min-width: 145px;
        color: #050505;
        text-decoration: none;
    }

    .training-dashboard-brand:hover {
        color: #050505;
    }

    .training-dashboard-brand-mark {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #050505;
        overflow: hidden;
    }

    .training-dashboard-brand-mark img {
        width: 36px;
        height: 36px;
        object-fit: contain;
    }

    .training-dashboard-brand strong,
    .training-dashboard-brand small {
        display: block;
        line-height: 1;
    }

    .training-dashboard-brand strong {
        font-family: 'Archivo Black', sans-serif;
        font-size: 14px;
        letter-spacing: .02em;
    }

    .training-dashboard-brand small {
        margin-top: 4px;
        color: #f3282c;
        font-size: 9px;
        font-weight: 900;
        letter-spacing: .16em;
    }

    .training-dashboard-links {
        flex: 1;
        display: flex;
        justify-content: center;
        gap: 4px;
    }

    .training-dashboard-link,
    .training-dashboard-cart,
    .training-dashboard-logout {
        min-height: 40px;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        padding: 8px 12px;
        color: #344054;
        font-size: 12px;
        font-weight: 900;
        text-decoration: none;
        border: 0;
        background: transparent;
        transition: .18s ease;
    }

    .training-dashboard-link:hover,
    .training-dashboard-link.active {
        background: #fff1f1;
        color: #f3282c;
    }

    .training-dashboard-actions {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .training-dashboard-cart {
        position: relative;
        border: 1px solid rgba(0, 0, 0, .08);
        background: #fff;
        color: #050505;
    }

    .training-dashboard-cart:hover {
        border-color: #f3282c;
        color: #f3282c;
    }

    .training-dashboard-cart b {
        min-width: 19px;
        height: 19px;
        padding: 0 5px;
        border-radius: 999px;
        background: #f3282c;
        color: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 9px;
    }

    .training-dashboard-user {
        display: flex;
        align-items: center;
        gap: 8px;
        padding-left: 4px;
    }

    .training-dashboard-avatar {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: #050505;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 900;
        border: 2px solid #f3282c;
    }

    .training-dashboard-user-copy strong,
    .training-dashboard-user-copy small {
        display: block;
        max-width: 145px;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }

    .training-dashboard-user-copy strong {
        color: #101828;
        font-size: 11px;
        font-weight: 900;
    }

    .training-dashboard-user-copy small {
        color: #98a2b3;
        font-size: 9px;
    }

    .training-dashboard-logout-form {
        margin: 0;
    }

    .training-dashboard-logout {
        background: #050505;
        color: #fff;
        cursor: pointer;
    }

    .training-dashboard-logout:hover {
        background: #f3282c;
        color: #fff;
    }

    .outlaws-training-dashboard {
        --red: #f3282c;
        --dark: #050505;
        --soft: #fff1f1;
        --gray: #f5f6f8;
        --muted: #667085;
        min-height: 70vh;
        padding: 1px 0 70px;
        background: #fff;
        font-family: 'Raleway', 'Karla', Arial, sans-serif;
    }

    .otd-hero {
        position: relative;
        overflow: hidden;
        margin: 24px 0 34px;
        padding: 34px;
        border-radius: 30px;
        color: #fff;
        background:
            radial-gradient(circle at top right, rgba(243, 40, 44, .42), transparent 30%),
            linear-gradient(135deg, #050505 0%, #111827 58%, #2b0d0f 100%);
        box-shadow: 0 24px 60px rgba(0, 0, 0, .16);
    }

    .otd-hero::after {
        content: '';
        position: absolute;
        right: -100px;
        top: -120px;
        width: 300px;
        height: 300px;
        border-radius: 50%;
        background: rgba(255, 255, 255, .06);
    }

    .otd-hero > * {
        position: relative;
        z-index: 1;
    }

    .otd-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 14px;
        border-radius: 999px;
        background: rgba(255, 255, 255, .1);
        border: 1px solid rgba(255, 255, 255, .14);
        color: var(--red);
        font-size: 11px;
        font-weight: 900;
        letter-spacing: .18em;
        text-transform: uppercase;
    }

    .otd-hero h1 {
        margin: 16px 0 10px;
        font-family: 'Archivo Black', sans-serif;
        font-size: clamp(38px, 4.4vw, 64px);
        line-height: .94;
        letter-spacing: -.055em;
    }

    .otd-hero p {
        max-width: 650px;
        margin-bottom: 0;
        color: rgba(255, 255, 255, .72);
        font-weight: 700;
    }

    .otd-stat {
        height: 100%;
        min-height: 130px;
        padding: 18px;
        border-radius: 22px;
        background: rgba(255, 255, 255, .1);
        border: 1px solid rgba(255, 255, 255, .14);
    }

    .otd-stat span {
        display: block;
        color: rgba(255, 255, 255, .55);
        font-size: 10px;
        font-weight: 900;
        letter-spacing: .12em;
        text-transform: uppercase;
    }

    .otd-stat strong {
        display: block;
        margin-top: 10px;
        font-family: 'Archivo Black', sans-serif;
        font-size: 40px;
        line-height: 1;
    }

    .otd-stat strong.red {
        color: var(--red);
    }

    .otd-primary-btn,
    .otd-outline-btn,
    .otd-hero-outline-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        border-radius: 999px;
        font-weight: 900;
        text-decoration: none;
        transition: .18s ease;
    }

    .otd-primary-btn {
        padding: 12px 18px;
        background: var(--red);
        color: #fff !important;
        box-shadow: 0 12px 26px rgba(243, 40, 44, .25);
    }

    .otd-primary-btn:hover {
        transform: translateY(-2px);
        background: #d91f23;
    }

    .otd-hero-outline-btn {
        padding: 11px 18px;
        border: 1px solid rgba(255, 255, 255, .6);
        color: #fff !important;
    }

    .otd-hero-outline-btn:hover {
        background: #fff;
        color: #050505 !important;
    }

    .otd-outline-btn {
        padding: 11px 16px;
        border: 2px solid #050505;
        background: #fff;
        color: #050505 !important;
    }

    .otd-outline-btn:hover {
        background: #050505;
        color: #fff !important;
    }

    .otd-section-head {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 20px;
    }

    .otd-kicker {
        color: var(--red);
        font-size: 11px;
        font-weight: 900;
        letter-spacing: .2em;
    }

    .otd-section-head h2 {
        margin: 6px 0 0;
        font-family: 'Archivo Black', sans-serif;
        font-size: 36px;
        letter-spacing: -.045em;
    }

    .otd-section-head p {
        margin: 6px 0 0;
        color: var(--muted);
        font-weight: 700;
    }

    .otd-booking-group {
        margin-bottom: 16px;
        border: 1px solid rgba(0, 0, 0, .08);
        border-radius: 24px;
        background: #fff;
        box-shadow: 0 12px 30px rgba(0, 0, 0, .045);
        overflow: hidden;
    }

    .otd-group-toggle {
        width: 100%;
        padding: 19px 20px;
        border: 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        background:
            radial-gradient(circle at top right, rgba(243, 40, 44, .08), transparent 32%),
            #fff;
        text-align: left;
    }

    .otd-group-left,
    .otd-group-right {
        display: flex;
        align-items: center;
    }

    .otd-group-left {
        gap: 14px;
    }

    .otd-group-right {
        gap: 12px;
    }

    .otd-group-icon {
        width: 46px;
        height: 46px;
        border-radius: 50%;
        background: var(--soft);
        color: var(--red);
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 auto;
    }

    .otd-group-left h3 {
        margin: 0;
        font-family: 'Archivo Black', sans-serif;
        font-size: 24px;
        letter-spacing: -.04em;
    }

    .otd-group-left p {
        margin: 5px 0 0;
        color: var(--muted);
        font-size: 12px;
        font-weight: 700;
    }

    .otd-group-right span {
        padding: 8px 12px;
        border-radius: 999px;
        background: #f3f4f6;
        font-size: 11px;
        font-weight: 900;
    }

    .otd-group-right > i {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #050505;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: .2s ease;
    }

    .otd-group-toggle[aria-expanded='true'] .otd-group-right > i {
        transform: rotate(180deg);
        background: var(--red);
    }

    .otd-group-body {
        padding: 0 20px 20px;
    }

    .otd-card-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px;
    }

    .otd-booking-card {
        padding: 18px;
        border-radius: 22px;
        border: 1px solid rgba(0, 0, 0, .08);
        background:
            radial-gradient(circle at top right, rgba(243, 40, 44, .08), transparent 36%),
            #fff;
        box-shadow: 0 10px 26px rgba(0, 0, 0, .04);
    }

    .otd-booking-card.is-past {
        background: #fafafa;
    }

    .otd-card-title-row {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 10px;
    }

    .otd-card-title-row h3 {
        margin: 9px 0 0;
        font-family: 'Archivo Black', sans-serif;
        font-size: 25px;
        line-height: 1;
        letter-spacing: -.04em;
    }

    .otd-booking-no {
        color: #98a2b3;
        font-size: 9px;
        font-weight: 900;
        text-align: right;
        word-break: break-all;
    }

    .otd-status {
        display: inline-flex;
        padding: 5px 9px;
        border-radius: 999px;
        background: #eef2f6;
        color: #475467;
        font-size: 9px;
        font-weight: 900;
        letter-spacing: .08em;
    }

    .otd-status.booked,
    .otd-status.paid,
    .otd-status.completed {
        background: #ecfdf3;
        color: #166534;
    }

    .otd-status.pending_payment {
        background: #fff7ed;
        color: #c2410c;
    }

    .otd-status.cancelled,
    .otd-status.refunded {
        background: #fff1f2;
        color: #be123c;
    }

    .otd-detail-list {
        margin-top: 16px;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
    }

    .otd-detail {
        min-width: 0;
        display: flex;
        align-items: flex-start;
        gap: 9px;
        padding: 11px;
        border-radius: 15px;
        background: #f6f7f8;
    }

    .otd-detail.full-width {
        grid-column: 1 / -1;
    }

    .otd-detail > span {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: var(--soft);
        color: var(--red);
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 auto;
    }

    .otd-detail small,
    .otd-detail strong {
        display: block;
    }

    .otd-detail small {
        color: #98a2b3;
        font-size: 8px;
        font-weight: 900;
        letter-spacing: .11em;
        text-transform: uppercase;
    }

    .otd-detail strong {
        margin-top: 4px;
        color: #101828;
        font-size: 11px;
        line-height: 1.35;
        word-break: break-word;
    }

    .otd-card-actions {
        margin-top: 12px;
        padding-top: 12px;
        border-top: 1px solid rgba(0, 0, 0, .06);
    }

    .otd-cancel-btn {
        border: 0;
        background: transparent;
        color: #d92d20;
        font-size: 11px;
        font-weight: 900;
    }

    .otd-empty {
        padding: 28px;
        border: 2px dashed rgba(0, 0, 0, .1);
        border-radius: 20px;
        color: #667085;
        text-align: center;
        font-weight: 800;
    }

    .otd-empty.large {
        padding: 48px 24px;
    }

    .otd-empty.large > i {
        margin-bottom: 12px;
        color: var(--red);
        font-size: 34px;
    }

    @media (max-width: 1199px) {
        .training-dashboard-user-copy {
            display: none;
        }

        .training-dashboard-link span,
        .training-dashboard-cart > span,
        .training-dashboard-logout span {
            display: none;
        }

        .training-dashboard-link,
        .training-dashboard-cart,
        .training-dashboard-logout {
            width: 40px;
            padding: 0;
        }

        .otd-card-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 767px) {
        .training-dashboard-nav-spacer {
            height: 66px;
        }

        .training-dashboard-nav-inner {
            width: calc(100% - 20px);
            min-height: 66px;
            gap: 8px;
        }

        .training-dashboard-brand {
            min-width: 48px;
        }

        .training-dashboard-brand > span:last-child,
        .training-dashboard-user {
            display: none;
        }

        .training-dashboard-brand-mark {
            width: 40px;
            height: 40px;
        }

        .training-dashboard-links {
            justify-content: flex-start;
            overflow-x: auto;
            scrollbar-width: none;
        }

        .training-dashboard-links::-webkit-scrollbar {
            display: none;
        }

        .training-dashboard-link {
            flex: 0 0 38px;
            width: 38px;
            min-height: 38px;
        }

        .training-dashboard-actions {
            gap: 4px;
        }

        .training-dashboard-cart,
        .training-dashboard-logout {
            width: 38px;
            min-height: 38px;
        }

        .otd-hero {
            padding: 24px;
            border-radius: 24px;
        }

        .otd-stat {
            min-height: 105px;
            padding: 12px;
        }

        .otd-stat strong {
            font-size: 30px;
        }

        .otd-section-head,
        .otd-group-toggle {
            align-items: flex-start;
            flex-direction: column;
        }

        .otd-group-right {
            width: 100%;
            justify-content: space-between;
        }

        .otd-card-grid,
        .otd-detail-list {
            grid-template-columns: 1fr;
        }

        .otd-detail.full-width {
            grid-column: auto;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const projectHeader = document.getElementById('outlawsHeader');
    const trainingHeader = document.getElementById('trainingDashboardNav');
    const spacer = document.getElementById('trainingDashboardNavSpacer');

    if (!trainingHeader || !spacer) {
        return;
    }

    function syncTrainingHeader() {
        let top = 0;

        if (projectHeader) {
            const rect = projectHeader.getBoundingClientRect();
            top = Math.max(0, rect.bottom);
        }

        trainingHeader.style.top = top + 'px';
        spacer.style.height = trainingHeader.offsetHeight + 'px';
    }

    syncTrainingHeader();
    window.addEventListener('resize', syncTrainingHeader);
    window.addEventListener('scroll', syncTrainingHeader, { passive: true });
});
</script>
@endsection
