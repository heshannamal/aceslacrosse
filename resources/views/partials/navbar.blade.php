@php
    $trainingLoggedIn = session()->has('em_customer_id');
    $trainingCustomer = null;
    $trainingCartCount = 0;

    if ($trainingLoggedIn) {
        try {
            $trainingCustomer = \App\Models\EMCustomer::query()
                ->whereKey((int) session('em_customer_id'))
                ->where('active', 1)
                ->first();

            if ($trainingCustomer) {
                $trainingCartCount = (int) \App\Models\EMCustomerPackageCart::query()
                    ->where('customer_id', $trainingCustomer->id)
                    ->sum('quantity');
            } else {
                $trainingLoggedIn = false;
            }
        } catch (\Throwable $e) {
            $trainingLoggedIn = false;
            $trainingCustomer = null;
            $trainingCartCount = 0;
        }
    }

    $trainingLoginUrl = route('em.customer.login', ['redirect' => route('em.customer.index')]);
    $trainingCartUrl = $trainingLoggedIn
        ? route('em.customer.cart')
        : route('em.customer.login', ['redirect' => route('em.customer.cart')]);
    $trainingProfileUrl = $trainingLoggedIn
        ? route('em.customer.profile')
        : route('em.customer.login', ['redirect' => route('em.customer.profile')]);

    $trainingDisplayName = $trainingCustomer?->display_name ?: session('em_customer_name', 'Training Account');
    $trainingEmail = $trainingCustomer?->email ?: '';
    $trainingInitials = collect(preg_split('/\s+/', trim((string) $trainingDisplayName)))
        ->filter()
        ->take(2)
        ->map(fn ($part) => strtoupper(substr($part, 0, 1)))
        ->implode('');
    $trainingInitials = $trainingInitials ?: 'A';

    $trainingProfilePhoto = null;
    if ($trainingCustomer && $trainingCustomer->profile_photo) {
        $trainingProfilePhoto = \Illuminate\Support\Str::startsWith($trainingCustomer->profile_photo, ['http://', 'https://'])
            ? $trainingCustomer->profile_photo
            : asset('public/storage/' . ltrim($trainingCustomer->profile_photo, '/'));
    }
@endphp

{{-- Training AJAX uses this token. Keeping it in the shared navbar makes it available on every public page. --}}
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
    .top-navbar {
        font-family: 'Roboto', sans-serif;
        background-color: #fff;
        padding: 16px 0 14px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        position: sticky;
        top: 0;
        width: 100%;
        z-index: 1000;
    }

    .desktop-navigation {
        position: relative;
    }

    .desktop-brand-row {
        min-height: 64px;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 13px;
    }

    .navbar-brand {
        margin: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .navbar-brand img {
        height: 60px;
        width: auto;
    }

    .header-actions {
        position: absolute;
        right: 0;
        top: 50%;
        transform: translateY(-50%);
        display: flex;
        align-items: center;
        gap: 9px;
        z-index: 20;
    }

    .header-action-btn {
        position: relative;
        width: 40px;
        height: 40px;
        border: 1px solid #e1e3e8;
        border-radius: 12px;
        background: #fff;
        color: #1b1b1f !important;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none !important;
        cursor: pointer;
        transition: .2s ease;
        box-shadow: 0 3px 9px rgba(23, 16, 33, .04);
    }

    .header-action-btn:hover,
    .header-action-btn:focus {
        color: #611eb2 !important;
        border-color: rgba(97, 30, 178, .45);
        background: #faf7ff;
    }

    .header-action-btn i {
        font-size: 17px;
    }

    .header-cart-badge {
        position: absolute;
        top: -6px;
        right: -6px;
        min-width: 19px;
        height: 19px;
        padding: 0 5px;
        border-radius: 99px;
        background: #e32329;
        color: #fff;
        border: 2px solid #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 9px;
        line-height: 1;
        font-weight: 900;
    }

    .training-account-wrap {
        position: relative;
    }

    .training-profile-trigger {
        padding: 2px;
        border-color: #611eb2;
        border-radius: 50%;
        overflow: hidden;
    }

    .training-profile-photo,
    .training-profile-initials {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        object-fit: cover;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #611eb2, #9b5de5);
        color: #fff;
        font-size: 11px;
        font-weight: 900;
        letter-spacing: .02em;
    }

    .training-account-menu {
        display: none;
        position: absolute;
        top: calc(100% + 10px);
        right: 0;
        width: 226px;
        padding: 12px;
        border-radius: 18px;
        background: #fff;
        border: 1px solid #ececf0;
        box-shadow: 0 18px 50px rgba(17, 17, 24, .2);
        z-index: 10050;
    }

    .training-account-menu.show {
        display: block;
    }

    .training-account-summary {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px;
        margin-bottom: 8px;
        border-radius: 14px;
        background: #f7f0fd;
    }

    .training-account-summary .training-profile-photo,
    .training-account-summary .training-profile-initials {
        width: 42px;
        height: 42px;
        flex: 0 0 42px;
    }

    .training-account-copy {
        min-width: 0;
    }

    .training-account-copy strong {
        display: block;
        color: #171021;
        font-size: 12px;
        font-weight: 900;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .training-account-copy small {
        display: block;
        margin-top: 2px;
        color: #74707c;
        font-size: 9px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .training-account-label {
        display: flex;
        align-items: center;
        gap: 5px;
        margin-top: 6px;
        color: #611eb2;
        font-size: 8px;
        font-weight: 900;
        letter-spacing: .12em;
        text-transform: uppercase;
    }

    .training-account-link,
    .training-account-logout {
        width: 100%;
        min-height: 39px;
        padding: 9px 10px;
        border: 0;
        border-radius: 10px;
        background: transparent;
        color: #17171b !important;
        display: flex;
        align-items: center;
        gap: 9px;
        text-decoration: none !important;
        font-size: 11px;
        font-weight: 900;
        text-align: left;
    }

    .training-account-link:hover {
        background: #f5f3f7;
        color: #611eb2 !important;
    }

    .training-account-logout {
        margin-top: 5px;
        justify-content: center;
        background: #171717;
        color: #fff !important;
        cursor: pointer;
    }

    .training-account-logout:hover {
        background: #611eb2;
    }

    .search-overlay {
        display: none;
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        background: #fff;
        z-index: 10000;
        padding: 20px 0;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .search-overlay.active {
        display: block;
    }

    .search-container {
        display: flex;
        align-items: center;
        gap: 20px;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 15px;
    }

    .search-box {
        flex: 1;
        position: relative;
    }

    .search-input {
        width: 100%;
        padding: 15px 50px 15px 20px;
        font-size: 16px;
        border: 2px solid #333;
        outline: none;
        background: #fff;
    }

    .search-input:focus {
        border-color: #611eb2;
    }

    .search-submit {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        cursor: pointer;
        font-size: 20px;
        color: #333;
    }

    .search-submit:hover,
    .search-close:hover {
        color: #611eb2;
    }

    .search-close {
        font-size: 28px;
        cursor: pointer;
        color: #333;
        line-height: 1;
    }

    .nav-menu {
        display: flex;
        list-style: none;
        gap: 30px;
        margin: 0;
        padding: 0;
        align-items: center;
        justify-content: center;
    }

    .nav-menu li {
        position: relative;
    }

    .nav-menu a {
        text-decoration: none;
        color: #333;
        font-weight: 500;
        font-size: 15px;
        transition: color 0.3s;
        display: flex;
        align-items: center;
        gap: 5px;
        white-space: nowrap;
    }

    .nav-menu a:hover,
    .nav-menu .active,
    a.mobile.active,
    a.mobile-dropdown-toggle.active {
        color: #611eb2;
    }

    a.dropdown.active {
        color: #611eb2;
        text-decoration: underline;
        text-underline-offset: 0.3rem;
    }

    .dropdown-menu-custom {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        background: #fff;
        min-width: 220px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        border-radius: 8px;
        padding: 15px 0;
        margin-top: 10px;
        z-index: 1000;
    }

    .dropdown-menu-custom.show {
        display: block;
    }

    .dropdown-menu-custom a {
        display: block;
        padding: 12px 20px;
        color: #333;
        text-decoration: none;
        transition: background 0.3s;
    }

    .dropdown-menu-custom a:hover {
        background: #f5f5f5;
        color: #611eb2;
    }

    .mobile-navigation {
        display: none;
    }

    .mobile-top-bar {
        display: grid;
        grid-template-columns: 44px 1fr auto;
        align-items: center;
        gap: 10px;
        padding: 0 4px;
    }

    .hamburger {
        font-size: 24px;
        cursor: pointer;
        color: #333;
    }

    .mobile-brand {
        justify-self: center;
    }

    .mobile-brand img {
        height: 48px;
        width: auto;
    }

    .mobile-header-actions {
        position: static;
        transform: none;
        gap: 6px;
    }

    .mobile-header-actions .header-action-btn {
        width: 36px;
        height: 36px;
        border-radius: 11px;
    }

    .mobile-header-actions .training-profile-trigger {
        border-radius: 50%;
    }

    .mobile-header-actions .training-profile-photo,
    .mobile-header-actions .training-profile-initials {
        width: 30px;
        height: 30px;
    }

    .mobile-menu {
        position: fixed;
        top: 0;
        left: -100%;
        width: 100%;
        max-width: 380px;
        height: 100vh;
        background: #fff;
        box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        transition: left 0.3s ease;
        z-index: 9999;
        overflow-y: auto;
    }

    .mobile-menu.active {
        left: 0;
    }

    .mobile-menu-header {
        padding: 20px 25px;
        background: #fff;
    }

    .mobile-menu-close {
        font-size: 28px;
        cursor: pointer;
        color: #333;
        line-height: 1;
        display: inline-block;
    }

    .mobile-menu-items {
        list-style: none;
        padding: 0;
        margin: 0;
        background: #fff;
    }

    .mobile-menu-items > li {
        border-bottom: 1px solid #eee;
        background: #fff;
    }

    .mobile-menu-items > li > a {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 25px;
        color: #333;
        text-decoration: none;
        font-weight: 500;
        font-size: 18px;
        background: #fff;
    }

    .mobile-menu-items .arrow-icon {
        font-size: 16px;
        transition: transform 0.3s;
        color: #333;
    }

    .mobile-submenu {
        display: none;
        background: #f9f9f9;
        padding: 0;
    }

    .mobile-submenu.show {
        display: block;
    }

    .mobile-submenu a {
        display: block;
        padding: 14px 20px 14px 40px;
        color: #555;
        text-decoration: none;
        font-size: 15px;
        font-weight: 400;
        border-bottom: 1px solid #eee;
    }

    .overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 9998;
    }

    .overlay.active {
        display: block;
    }

    @media (max-width: 1200px) {
        .nav-menu {
            gap: 18px;
        }

        .nav-menu a {
            font-size: 14px;
        }
    }

    @media (max-width: 991px) {
        .top-navbar {
            padding: 10px 0;
        }

        .desktop-navigation {
            display: none;
        }

        .mobile-navigation {
            display: block;
        }

        .search-container {
            padding: 0 15px;
        }

        .search-input {
            padding: 12px 45px 12px 15px;
            font-size: 15px;
        }

        .search-close {
            font-size: 24px;
        }

        .training-account-menu {
            position: fixed;
            top: 72px;
            right: 12px;
            width: min(240px, calc(100vw - 24px));
        }
    }

    @media screen and (min-width: 2560px) {
        .navbar-brand img {
            height: auto !important;
            width: auto;
        }

        a.dropdown-toggle,
        a.dropdown,
        a.nav-item {
            font-size: 4rem !important;
        }
    }
</style>

<nav class="top-navbar">
    <div class="search-overlay" id="searchOverlay">
        <div class="search-container">
            <div class="search-box">
                <input type="text" class="search-input" placeholder="Search" id="searchInput">
                <button class="search-submit" type="button" aria-label="Submit search">
                    <i class="fas fa-search"></i>
                </button>
            </div>
            <i class="fas fa-times search-close" id="closeSearch" aria-label="Close search"></i>
        </div>
    </div>

    <div class="container">
        <div class="desktop-navigation">
            <div class="desktop-brand-row">
                <a href="{{ URL('/') }}" class="navbar-brand">
                    <img src="{{ asset('public/assets/images/ACES-logo.webp') }}" alt="ACES Logo">
                </a>

                <div class="header-actions">
                    <a href="{{ $trainingCartUrl }}" class="header-action-btn" aria-label="Training cart">
                        <i class="fa-solid fa-cart-shopping"></i>
                        @if($trainingLoggedIn && $trainingCartCount > 0)
                            <span class="header-cart-badge">{{ $trainingCartCount > 99 ? '99+' : $trainingCartCount }}</span>
                        @endif
                    </a>

                    @if($trainingLoggedIn)
                        <div class="training-account-wrap">
                            <button type="button" class="header-action-btn training-profile-trigger" data-training-account-trigger aria-label="Training account menu" aria-expanded="false">
                                @if($trainingProfilePhoto)
                                    <img src="{{ $trainingProfilePhoto }}" alt="{{ $trainingDisplayName }}" class="training-profile-photo">
                                @else
                                    <span class="training-profile-initials">{{ $trainingInitials }}</span>
                                @endif
                            </button>

                            <div class="training-account-menu" data-training-account-menu>
                                <div class="training-account-summary">
                                    @if($trainingProfilePhoto)
                                        <img src="{{ $trainingProfilePhoto }}" alt="{{ $trainingDisplayName }}" class="training-profile-photo">
                                    @else
                                        <span class="training-profile-initials">{{ $trainingInitials }}</span>
                                    @endif
                                    <div class="training-account-copy">
                                        <strong>{{ $trainingDisplayName }}</strong>
                                        @if($trainingEmail)<small>{{ $trainingEmail }}</small>@endif
                                        <span class="training-account-label"><i class="fa-solid fa-bolt"></i> Training Account</span>
                                    </div>
                                </div>

                                <a class="training-account-link" href="{{ route('em.customer.dashboard') }}">
                                    <i class="fa-solid fa-table-cells-large"></i> Dashboard
                                </a>
                                <a class="training-account-link" href="{{ route('em.customer.profile') }}">
                                    <i class="fa-regular fa-user"></i> My Profile
                                </a>
                                <form method="POST" action="{{ route('em.customer.logout') }}">
                                    @csrf
                                    <button type="submit" class="training-account-logout">
                                        <i class="fa-solid fa-right-from-bracket"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ $trainingProfileUrl }}" class="header-action-btn training-profile-trigger" aria-label="Training login">
                            <span class="training-profile-initials"><i class="fa-regular fa-user"></i></span>
                        </a>
                    @endif
                </div>
            </div>

            <ul class="nav-menu">
                <li><a href="{{ URL('/') }}" class="nav-item {{ request()->is('/') ? 'active' : '' }}">Home</a></li>
                <li class="dropdown-item-custom">
                    <a href="#" class="nav-item dropdown-toggle {{ request()->is('pages/mission') || request()->is('pages/coaching-staff') || request()->is('pages/aces-in-college') || request()->is('pages/testimonials') || request()->is('pages/championships') ? 'active' : '' }}">About</a>
                    <div class="dropdown-menu-custom">
                        <a href="{{ URL('/pages/mission') }}" class="nav-item dropdown {{ request()->is('pages/mission') ? 'active' : '' }}">Mission & History</a>
                        <a href="{{ URL('/pages/coaching-staff') }}" class="nav-item dropdown {{ request()->is('pages/coaching-staff') ? 'active' : '' }}">Coaching Staff</a>
                        <a href="{{ URL('/pages/aces-in-college') }}" class="nav-item dropdown {{ request()->is('pages/aces-in-college') ? 'active' : '' }}">ACES Playing In College</a>
                        <a href="{{ URL('/pages/testimonials') }}" class="nav-item dropdown {{ request()->is('pages/testimonials') ? 'active' : '' }}">Testimonials</a>
                        <a href="{{ URL('/pages/championships') }}" class="nav-item dropdown {{ request()->is('pages/championships') ? 'active' : '' }}">Championships</a>
                    </div>
                </li>
                <li><a href="{{ URL('/pages/academy') }}" class="nav-item {{ request()->is('pages/academy') ? 'active' : '' }}">Academy</a></li>
                <li><a href="{{ URL('/pages/travel-teams') }}" class="nav-item {{ request()->is('pages/travel-teams') ? 'active' : '' }}">Travel Teams</a></li>
                <li><a href="https://pinnaclelax.com/" class="nav-item" target="__blank">Pinnacle</a></li>
                <li><a href="{{ URL('/pages/tryouts') }}" class="nav-item {{ request()->is('pages/tryouts') ? 'active' : '' }}">Tryouts & New Players</a></li>
                <li class="dropdown-item-custom">
                    <a href="#" class="dropdown-toggle {{ request()->is('pages/sacramento-fall-league') || request()->is('pages/grow-your-game-el-dorado-hills') || request()->is('pages/aces-grow-your-game-davis') ? 'active' : '' }}">Camps, Clinics and Leagues</a>
                    <div class="dropdown-menu-custom">
                        <a href="{{ URL('/pages/sacramento-fall-league') }}" class="nav-item dropdown {{ request()->is('pages/sacramento-fall-league') ? 'active' : '' }}">Sacramento Fall League</a>
                        <a href="{{ URL('/pages/grow-your-game-el-dorado-hills') }}" class="nav-item dropdown {{ request()->is('pages/grow-your-game-el-dorado-hills') ? 'active' : '' }}">Grow Your Game - El Dorado Hills</a>
                        <a href="{{ URL('/pages/aces-grow-your-game-davis') }}" class="nav-item dropdown {{ request()->is('pages/aces-grow-your-game-davis') ? 'active' : '' }}">Grow Your Game - Davis</a>
                    </div>
                </li>
                <li><a href="{{ route('em.customer.index') }}" class="nav-item {{ request()->is('training*') ? 'active' : '' }}">Training</a></li>
                <li><a href="{{ URL('/pages/contact-us') }}" class="nav-item {{ request()->is('pages/contact-us') ? 'active' : '' }}">Contact Us</a></li>
            </ul>
        </div>

        <div class="mobile-navigation">
            <div class="mobile-top-bar">
                <i class="fas fa-bars hamburger" id="hamburger"></i>

                <a href="{{ URL('/') }}" class="mobile-brand">
                    <img src="{{ asset('public/assets/images/ACES-logo.webp') }}" alt="ACES Logo">
                </a>

                <div class="header-actions mobile-header-actions">
                    <a href="{{ $trainingCartUrl }}" class="header-action-btn" aria-label="Training cart">
                        <i class="fa-solid fa-cart-shopping"></i>
                        @if($trainingLoggedIn && $trainingCartCount > 0)
                            <span class="header-cart-badge">{{ $trainingCartCount > 99 ? '99+' : $trainingCartCount }}</span>
                        @endif
                    </a>

                    @if($trainingLoggedIn)
                        <div class="training-account-wrap">
                            <button type="button" class="header-action-btn training-profile-trigger" data-training-account-trigger aria-label="Training account menu" aria-expanded="false">
                                @if($trainingProfilePhoto)
                                    <img src="{{ $trainingProfilePhoto }}" alt="{{ $trainingDisplayName }}" class="training-profile-photo">
                                @else
                                    <span class="training-profile-initials">{{ $trainingInitials }}</span>
                                @endif
                            </button>

                            <div class="training-account-menu" data-training-account-menu>
                                <div class="training-account-summary">
                                    @if($trainingProfilePhoto)
                                        <img src="{{ $trainingProfilePhoto }}" alt="{{ $trainingDisplayName }}" class="training-profile-photo">
                                    @else
                                        <span class="training-profile-initials">{{ $trainingInitials }}</span>
                                    @endif
                                    <div class="training-account-copy">
                                        <strong>{{ $trainingDisplayName }}</strong>
                                        @if($trainingEmail)<small>{{ $trainingEmail }}</small>@endif
                                        <span class="training-account-label"><i class="fa-solid fa-bolt"></i> Training Account</span>
                                    </div>
                                </div>
                                <a class="training-account-link" href="{{ route('em.customer.dashboard') }}"><i class="fa-solid fa-table-cells-large"></i> Dashboard</a>
                                <a class="training-account-link" href="{{ route('em.customer.profile') }}"><i class="fa-regular fa-user"></i> My Profile</a>
                                <form method="POST" action="{{ route('em.customer.logout') }}">
                                    @csrf
                                    <button type="submit" class="training-account-logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ $trainingProfileUrl }}" class="header-action-btn training-profile-trigger" aria-label="Training login">
                            <span class="training-profile-initials"><i class="fa-regular fa-user"></i></span>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</nav>

<div class="mobile-menu" id="mobileMenu">
    <div class="mobile-menu-header">
        <i class="fas fa-times mobile-menu-close" id="closeMenu"></i>
    </div>

    <ul class="mobile-menu-items">
        <li><a href="{{ URL('/') }}" class="mobile menu {{ request()->is('/') ? 'active' : '' }}">Home</a></li>
        <li>
            <a href="#" class="mobile-dropdown-toggle {{ request()->is('pages/mission') || request()->is('pages/coaching-staff') || request()->is('pages/aces-in-college') || request()->is('pages/testimonials') || request()->is('pages/championships') ? 'active' : '' }}">About <i class="fas fa-arrow-right arrow-icon"></i></a>
            <div class="mobile-submenu">
                <a href="{{ URL('/pages/mission') }}" class="mobile {{ request()->is('pages/mission') ? 'active' : '' }}">Mission & History</a>
                <a href="{{ URL('/pages/coaching-staff') }}" class="mobile {{ request()->is('pages/coaching-staff') ? 'active' : '' }}">Coaching Staff</a>
                <a href="{{ URL('/pages/aces-in-college') }}" class="mobile {{ request()->is('pages/aces-in-college') ? 'active' : '' }}">ACES Playing In College</a>
                <a href="{{ URL('/pages/testimonials') }}" class="mobile {{ request()->is('pages/testimonials') ? 'active' : '' }}">Testimonials</a>
                <a href="{{ URL('/pages/championships') }}" class="mobile {{ request()->is('pages/championships') ? 'active' : '' }}">Championships</a>
            </div>
        </li>
        <li><a href="{{ URL('/pages/academy') }}" class="mobile menu {{ request()->is('pages/academy') ? 'active' : '' }}">Academy</a></li>
        <li><a href="{{ URL('/pages/travel-teams') }}" class="mobile menu {{ request()->is('pages/travel-teams') ? 'active' : '' }}">Travel Teams</a></li>
        <li><a href="https://pinnaclelax.com/" target="__blank">Pinnacle</a></li>
        <li><a href="{{ URL('/pages/tryouts') }}" class="mobile menu {{ request()->is('pages/tryouts') ? 'active' : '' }}">Tryouts & New Players</a></li>
        <li>
            <a href="#" class="mobile-dropdown-toggle {{ request()->is('pages/sacramento-fall-league') || request()->is('pages/grow-your-game-el-dorado-hills') || request()->is('pages/aces-grow-your-game-davis') ? 'active' : '' }}">Camps, Clinics and Leagues <i class="fas fa-arrow-right arrow-icon"></i></a>
            <div class="mobile-submenu">
                <a href="{{ URL('/pages/sacramento-fall-league') }}" class="mobile {{ request()->is('pages/sacramento-fall-league') ? 'active' : '' }}">Sacramento Fall League</a>
                <a href="{{ URL('/pages/grow-your-game-el-dorado-hills') }}" class="mobile {{ request()->is('pages/grow-your-game-el-dorado-hills') ? 'active' : '' }}">Grow Your Game - El Dorado Hills</a>
                <a href="{{ URL('/pages/aces-grow-your-game-davis') }}" class="mobile {{ request()->is('pages/aces-grow-your-game-davis') ? 'active' : '' }}">Grow Your Game - Davis</a>
            </div>
        </li>
        <li><a href="{{ route('em.customer.index') }}" class="mobile {{ request()->is('training*') ? 'active' : '' }}">Training</a></li>
        <li><a href="{{ URL('/pages/contact-us') }}" class="mobile {{ request()->is('pages/contact-us') ? 'active' : '' }}">Contact Us</a></li>
    </ul>
</div>

<div class="overlay" id="overlay"></div>

<script>
    $(document).ready(function() {
        $('.dropdown-toggle').on('click', function(e) {
            e.preventDefault();
            const dropdown = $(this).siblings('.dropdown-menu-custom');
            $('.dropdown-menu-custom').not(dropdown).removeClass('show');
            dropdown.toggleClass('show');
        });

        $(document).on('click', function(e) {
            if (!$(e.target).closest('.dropdown-item-custom').length) {
                $('.dropdown-menu-custom').removeClass('show');
            }
        });

        $('[data-header-search]').on('click', function() {
            $('#searchOverlay').addClass('active');
            $('#searchInput').trigger('focus');
        });

        $('#closeSearch').on('click', function() {
            $('#searchOverlay').removeClass('active');
            $('#searchInput').val('');
        });

        $(document).on('keydown', function(e) {
            if (e.key === 'Escape') {
                $('#searchOverlay').removeClass('active');
                $('#searchInput').val('');
                $('[data-training-account-menu]').removeClass('show');
                $('[data-training-account-trigger]').attr('aria-expanded', 'false');
            }
        });

        $('.search-submit').on('click', function(e) {
            e.preventDefault();
            const searchValue = $('#searchInput').val();
            if (searchValue.trim()) {
                console.log('Searching for:', searchValue);
            }
        });

        $('#hamburger').on('click', function() {
            $('#mobileMenu').addClass('active');
            $('#overlay').addClass('active');
            $('body').css('overflow', 'hidden');
        });

        $('#closeMenu, #overlay').on('click', function() {
            $('#mobileMenu').removeClass('active');
            $('#overlay').removeClass('active');
            $('body').css('overflow', 'auto');
        });

        $('.mobile-dropdown-toggle').on('click', function(e) {
            e.preventDefault();
            const $this = $(this);
            const submenu = $this.siblings('.mobile-submenu');
            const icon = $this.find('.arrow-icon');

            $('.mobile-submenu').not(submenu).removeClass('show');
            $('.arrow-icon').not(icon).removeClass('fa-arrow-down').addClass('fa-arrow-right');
            submenu.toggleClass('show');

            if (submenu.hasClass('show')) {
                icon.removeClass('fa-arrow-right').addClass('fa-arrow-down');
            } else {
                icon.removeClass('fa-arrow-down').addClass('fa-arrow-right');
            }
        });

        $('[data-training-account-trigger]').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const $trigger = $(this);
            const $menu = $trigger.siblings('[data-training-account-menu]');

            $('[data-training-account-menu]').not($menu).removeClass('show');
            $('[data-training-account-trigger]').not($trigger).attr('aria-expanded', 'false');

            $menu.toggleClass('show');
            $trigger.attr('aria-expanded', $menu.hasClass('show') ? 'true' : 'false');
        });

        $(document).on('click', function(e) {
            if (!$(e.target).closest('.training-account-wrap').length) {
                $('[data-training-account-menu]').removeClass('show');
                $('[data-training-account-trigger]').attr('aria-expanded', 'false');
            }
        });
    });
</script>
