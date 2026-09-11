<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin') | ACES Lacrosse</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="{{ asset('public/admin_assets/css/admin.css') }}" rel="stylesheet">
    <link href="{{ asset('public/admin_assets/css/admin-profile.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
<div class="admin-layout">
    @include('admin.layouts.sidebar')
    <div class="main-wrapper">
        @include('admin.layouts.topbar')
        <main class="page-content">
            @if(session('success'))<div class="alert alert-success admin-alert"><i class="fa-solid fa-circle-check"></i><span>{{ session('success') }}</span></div>@endif
            @if(session('error'))<div class="alert alert-danger admin-alert"><i class="fa-solid fa-circle-exclamation"></i><span>{{ session('error') }}</span></div>@endif
            @if($errors->any())<div class="alert alert-danger admin-alert align-items-start"><i class="fa-solid fa-circle-exclamation mt-1"></i><div><strong>Please fix these errors:</strong><ul class="mb-0 mt-1">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div></div>@endif
            @yield('content')

            @if(request()->routeIs('admin.bookings.session-wise'))
                @include('admin.bookings._detail_edit_overrides')
                @include('admin.bookings._interaction_fix')
                @include('admin.bookings._manual_booking_override')
            @endif

            @if(request()->routeIs('admin.payments.index'))
                @include('admin.payments._display_overrides')
            @endif

            @include('admin._training_ui_overrides')
        </main>
    </div>
</div>
<div class="sidebar-backdrop" id="sidebarBackdrop"></div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('public/admin_assets/js/admin.js') }}"></script>
@stack('scripts')
</body>
</html>
