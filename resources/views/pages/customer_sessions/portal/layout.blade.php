<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Training Dashboard | ACES Lacrosse')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root{--ao-red:#611eb2;--ao-dark:#171021;--ao-soft:#f4edfc;--ao-border:rgba(0,0,0,.08);--ao-muted:#667085}
        *{box-sizing:border-box}body{margin:0;background:#f7f6f9;color:#080808;font-family:'Roboto',Arial,sans-serif}.ao-portal{min-height:100vh;display:flex}.ao-side{position:fixed;left:0;top:0;bottom:0;width:178px;z-index:100;background:radial-gradient(circle at top left,rgba(97,30,178,.35),transparent 36%),linear-gradient(180deg,#171021,#24142f 55%,#0f0b16);color:#fff;border-right:1px solid rgba(255,255,255,.08);box-shadow:16px 0 45px rgba(41,20,58,.16)}.ao-side-logo{display:flex;height:74px;align-items:center;justify-content:center;border-bottom:1px solid rgba(255,255,255,.08);padding:9px}.ao-side-logo img{max-width:128px;max-height:58px;object-fit:contain}.ao-side-nav{padding:16px 10px}.ao-side-link{display:flex;align-items:center;gap:10px;min-height:43px;margin-bottom:8px;padding:0 13px;border-radius:14px;color:rgba(255,255,255,.74);text-decoration:none;font-size:12px;font-weight:900;transition:.18s}.ao-side-link i{width:19px;text-align:center;color:#c897ff}.ao-side-link:hover{background:rgba(255,255,255,.08);color:#fff;transform:translateX(2px)}.ao-side-link.active{background:linear-gradient(135deg,var(--ao-red),#8c4ed2);color:#fff;box-shadow:0 12px 26px rgba(97,30,178,.28)}.ao-side-link.active i{color:#fff}.ao-main{width:calc(100% - 178px);margin-left:178px;min-height:100vh}.ao-top{position:sticky;top:0;z-index:90;height:64px;background:rgba(255,255,255,.95);backdrop-filter:blur(16px);border-bottom:1px solid var(--ao-border);box-shadow:0 8px 28px rgba(0,0,0,.04);display:flex;align-items:center;justify-content:flex-end;padding:0 18px;gap:12px}.ao-mobile-toggle{display:none;border:0;background:var(--ao-dark);color:#fff;width:42px;height:42px;border-radius:13px}.ao-top-cart{position:relative;display:inline-flex;align-items:center;justify-content:center;width:42px;height:42px;border-radius:14px;background:var(--ao-dark);color:#fff;text-decoration:none}.ao-top-cart:hover{background:var(--ao-red);color:#fff}.ao-top-count{position:absolute;right:-5px;top:-6px;min-width:20px;height:20px;padding:0 5px;border-radius:999px;background:var(--ao-red);border:2px solid #fff;font-size:10px;font-weight:900;display:flex;align-items:center;justify-content:center}.ao-user-trigger{display:flex;align-items:center;gap:9px;border:1px solid var(--ao-border);background:#fff;border-radius:999px;padding:5px 10px 5px 5px;text-decoration:none;color:#111;box-shadow:0 8px 22px rgba(0,0,0,.05)}.ao-avatar{width:36px;height:36px;border-radius:999px;object-fit:cover;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,var(--ao-red),var(--ao-dark));color:#fff;font-weight:900;border:2px solid #fff}.ao-user-name{font-size:12px;font-weight:900;max-width:150px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}.ao-dropdown{border:0!important;border-radius:18px!important;padding:8px!important;box-shadow:0 20px 50px rgba(0,0,0,.14)!important}.ao-dropdown .dropdown-item{border-radius:12px;padding:10px 12px;font-size:12px;font-weight:800}.ao-dropdown .dropdown-item:hover{background:var(--ao-soft);color:var(--ao-red)}.ao-content{padding:18px 18px 48px;max-width:1500px;margin:0 auto}.ao-alert{border-radius:14px;padding:12px 15px;margin-bottom:14px;font-weight:700}.ao-alert.success{background:#ecfdf3;color:#166534}.ao-alert.error{background:#fff1f2;color:#991b1b}.ao-tabs{display:inline-flex;background:#fff;border:1px solid var(--ao-border);border-radius:999px;padding:5px;gap:4px;margin-bottom:18px}.ao-tab{display:inline-flex;align-items:center;gap:7px;padding:9px 15px;border-radius:999px;text-decoration:none;color:#667085;font-size:12px;font-weight:900}.ao-tab.active{background:var(--ao-red);color:#fff;box-shadow:0 8px 22px rgba(97,30,178,.24)}
        @media(max-width:991px){.ao-side{transform:translateX(-105%);transition:.25s}.ao-side.open{transform:translateX(0)}.ao-main{width:100%;margin-left:0}.ao-top{justify-content:space-between}.ao-mobile-toggle{display:inline-flex;align-items:center;justify-content:center}.ao-content{padding:15px}.ao-user-name{display:none}}
    </style>
    @stack('styles')
</head>
<body>
@php
    $portalCustomer = $customer ?? \App\Models\EMCustomer::query()->whereKey(session('em_customer_id'))->first();
    $portalName = $portalCustomer ? $portalCustomer->display_name : 'Customer';
    $portalPhoto = $portalCustomer?->profile_photo;
    $portalPhotoUrl = $portalPhoto ? (\Illuminate\Support\Str::startsWith($portalPhoto,['http://','https://']) ? $portalPhoto : asset('storage/'.$portalPhoto)) : null;
    $portalCartCount = isset($trainingCartCount) ? (int)$trainingCartCount : (int)\App\Models\EMCustomerPackageCart::query()->where('customer_id',session('em_customer_id'))->sum('quantity');
@endphp
<div class="ao-portal">
    <aside class="ao-side" id="aoPortalSidebar">
        <a class="ao-side-logo" href="{{ route('em.customer.index') }}"><img src="{{ asset('public/assets/images/ACES-logo.webp') }}" alt="ACES Lacrosse"></a>
        <nav class="ao-side-nav">
            <a class="ao-side-link {{ request()->routeIs('em.customer.dashboard') ? 'active' : '' }}" href="{{ route('em.customer.dashboard') }}"><i class="fa-solid fa-house"></i><span>Dashboard</span></a>
            <a class="ao-side-link {{ request()->routeIs('em.customer.bookings') ? 'active' : '' }}" href="{{ route('em.customer.bookings') }}"><i class="fa-regular fa-calendar-check"></i><span>My Bookings</span></a>
            <a class="ao-side-link {{ request()->routeIs('em.customer.profile*') ? 'active' : '' }}" href="{{ route('em.customer.profile') }}"><i class="fa-regular fa-user"></i><span>My Profile</span></a>
        </nav>
    </aside>
    <main class="ao-main">
        <header class="ao-top">
            <button class="ao-mobile-toggle" id="aoPortalToggle" type="button"><i class="fa-solid fa-bars"></i></button>
            <div class="d-flex align-items-center gap-2 ms-auto">
                <a class="ao-top-cart" href="{{ route('em.customer.cart') }}"><i class="fa-solid fa-cart-shopping"></i>@if($portalCartCount>0)<span class="ao-top-count">{{ $portalCartCount>99?'99+':$portalCartCount }}</span>@endif</a>
                <div class="dropdown">
                    <a class="ao-user-trigger" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                        @if($portalPhotoUrl)<img class="ao-avatar" src="{{ $portalPhotoUrl }}" alt="{{ $portalName }}">@else<span class="ao-avatar">{{ strtoupper(substr($portalName,0,1)) }}</span>@endif
                        <span class="ao-user-name">{{ $portalName }}</span><i class="fa-solid fa-chevron-down small text-muted"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end ao-dropdown">
                        <a class="dropdown-item" href="{{ route('em.customer.dashboard') }}"><i class="fa-solid fa-house me-2" style="color:#611eb2"></i>Dashboard</a>
                        <a class="dropdown-item" href="{{ route('em.customer.profile') }}"><i class="fa-regular fa-user me-2" style="color:#611eb2"></i>Profile</a>
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ route('em.customer.logout') }}">@csrf<button class="dropdown-item text-danger" type="submit"><i class="fa-solid fa-arrow-right-from-bracket me-2"></i>Logout</button></form>
                    </div>
                </div>
            </div>
        </header>
        <div class="ao-content">
            @if(session('success'))<div class="ao-alert success">{{ session('success') }}</div>@endif
            @if(session('error'))<div class="ao-alert error">{{ session('error') }}</div>@endif
            @if($errors->any())<div class="ao-alert error">{{ $errors->first() }}</div>@endif
            @yield('content')
        </div>
    </main>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>document.addEventListener('DOMContentLoaded',function(){var btn=document.getElementById('aoPortalToggle'),side=document.getElementById('aoPortalSidebar');if(btn&&side){btn.addEventListener('click',function(){side.classList.toggle('open')});document.addEventListener('click',function(e){if(window.innerWidth>991)return;if(!side.contains(e.target)&&!btn.contains(e.target))side.classList.remove('open')})}});</script>
@stack('scripts')
</body>
</html>
