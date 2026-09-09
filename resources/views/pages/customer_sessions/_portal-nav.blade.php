@php
    $trainingCustomer = $customer ?? null;
    $isTrainingLoggedIn = !empty($trainingCustomer) || session()->has('em_customer_id');
    $trainingName = $trainingCustomer ? $trainingCustomer->display_name : session('em_customer_name', 'Training Account');
    $trainingInitial = strtoupper(substr(trim((string) $trainingName), 0, 1));
    $trainingInitial = $trainingInitial !== '' ? $trainingInitial : 'T';

    if (isset($trainingCartCount)) {
        $trainingCount = (int) $trainingCartCount;
    } elseif (isset($cartCount)) {
        $trainingCount = (int) $cartCount;
    } elseif ($trainingCustomer) {
        $trainingCount = (int) \App\Models\EMCustomerPackageCart::query()
            ->where('customer_id', $trainingCustomer->id)
            ->sum('quantity');
    } else {
        $trainingCount = 0;
    }
@endphp

<div class="ao-toolbar">
    <div class="ao-toolbar-links">
        <a class="ao-chip-link {{ request()->routeIs('em.customer.index') ? 'active' : '' }}" href="{{ route('em.customer.index') }}">
            <i class="fa-solid fa-calendar-days"></i> Training
        </a>
        <a class="ao-chip-link {{ request()->routeIs('em.customer.packages', 'em.customer.package.details') ? 'active' : '' }}" href="{{ route('em.customer.packages') }}">
            <i class="fa-solid fa-box-open"></i> Packages
        </a>
        @if($isTrainingLoggedIn)
            <a class="ao-chip-link {{ request()->routeIs('em.customer.dashboard', 'em.customer.bookings') ? 'active' : '' }}" href="{{ route('em.customer.dashboard') }}">
                <i class="fa-solid fa-table-cells-large"></i> My Bookings
            </a>
        @endif
    </div>

    <div class="ao-account">
        <a class="ao-icon-btn {{ request()->routeIs('em.customer.cart', 'em.customer.checkout') ? 'active' : '' }}" href="{{ route('em.customer.cart') }}">
            <i class="fa-solid fa-cart-shopping"></i> Cart
            @if($trainingCount > 0)
                <span class="ao-cart-badge">{{ $trainingCount > 99 ? '99+' : $trainingCount }}</span>
            @endif
        </a>

        @if($isTrainingLoggedIn)
            <a class="ao-avatar" href="{{ route('em.customer.profile') }}" title="{{ $trainingName }}">{{ $trainingInitial }}</a>
        @else
            <a class="ao-btn ao-btn-red ao-btn-sm" href="{{ route('em.customer.login') }}">
                <i class="fa-solid fa-right-to-bracket"></i> Sign In
            </a>
        @endif
    </div>
</div>
