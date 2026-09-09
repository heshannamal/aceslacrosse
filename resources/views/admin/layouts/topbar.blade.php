@php
    $admin = auth()->user();
    $adminName = $admin->name ?? 'Admin User';
    $adminEmail = $admin->email ?? '';
    $adminRole = auth()->id() === 1 ? 'Super Admin' : 'Admin';
    $initials = collect(explode(' ', $adminName))
        ->filter()
        ->take(2)
        ->map(fn ($part) => strtoupper(substr($part, 0, 1)))
        ->implode('') ?: 'AD';
@endphp

<header class="topbar">
    <div class="topbar-left">
        <button class="menu-btn" id="menuBtn" type="button" aria-label="Open admin menu">
            <i class="fa-solid fa-bars"></i>
        </button>
        <h1>@yield('page-title', 'Dashboard')</h1>
    </div>

    <div class="topbar-search">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input id="globalAdminSearch" type="search" placeholder="Search..." value="{{ request('search') }}">
    </div>

    <div class="profile-box">

        <div class="dropdown admin-profile-dropdown">
            <button
                class="profile-trigger"
                type="button"
                data-bs-toggle="dropdown"
                data-bs-auto-close="outside"
                aria-expanded="false"
                aria-label="Open admin profile menu"
            >
                <span class="avatar">{{ $initials }}</span>
                <span class="profile-text">
                    <strong>{{ $adminName }}</strong>
                    <span>{{ $adminRole }}</span>
                </span>
                <i class="fa-solid fa-chevron-down profile-chevron"></i>
            </button>

            <div class="dropdown-menu dropdown-menu-end admin-profile-menu">
                <div class="admin-profile-menu-head">
                    <span class="admin-profile-menu-avatar">{{ $initials }}</span>
                    <span class="admin-profile-menu-user">
                        <strong>{{ $adminName }}</strong>
                        <small>{{ $adminEmail }}</small>
                        <em>{{ $adminRole }}</em>
                    </span>
                </div>

                <div class="admin-profile-menu-divider"></div>

                <a class="admin-profile-menu-item" href="{{ route('admin.profile.show') }}">
                    <span class="admin-profile-menu-icon"><i class="fa-regular fa-user"></i></span>
                    <span><strong>View Profile</strong><small>Account and access details</small></span>
                </a>

                <a class="admin-profile-menu-item" href="{{ route('admin.profile.edit') }}">
                    <span class="admin-profile-menu-icon"><i class="fa-solid fa-pen-to-square"></i></span>
                    <span><strong>Edit Profile</strong><small>Name, email and password</small></span>
                </a>

                <div class="admin-profile-menu-divider"></div>

                <form method="POST" action="{{ route('admin.logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="admin-profile-menu-item admin-profile-logout">
                        <span class="admin-profile-menu-icon"><i class="fa-solid fa-arrow-right-from-bracket"></i></span>
                        <span><strong>Logout</strong><small>Sign out of admin</small></span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
