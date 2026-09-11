@php
    $user = auth()->user();
    $settingsActive = request()->routeIs('admin.permissions.*') || request()->routeIs('admin.user-groups.*') || request()->routeIs('admin.users.*');
    $canAdmin = $user && $user->hasAdminPermission('admin_privilages');
    $canSettings = $user && $user->hasAnyAdminPermission(['manage_permissions','manage_user_groups','manage_users']);
@endphp
<aside class="sidebar" id="sidebar">
    <div class="admin-brand">
        <img src="{{ asset('public/assets/images/ACES-logo.webp') }}" alt="ACES Lacrosse" class="admin-brand-logo">
        <div class="admin-brand-text"><strong>ACES LACROSSE</strong><span>— ADMIN —</span></div>
    </div>
    <nav class="sidebar-nav">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="fa-solid fa-house"></i><span>Dashboard</span></a>
        @if($canAdmin)
            <a href="{{ route('admin.parents.booking.index') }}" class="sidebar-link {{ request()->routeIs('admin.parents.booking.*') ? 'active' : '' }}"><i class="fa-solid fa-user-group"></i><span>Add Members</span></a>
            <a href="{{ route('admin.bookings.session-wise') }}" class="sidebar-link {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}"><i class="fa-solid fa-calendar-check"></i><span>Bookings</span></a>
            <a href="{{ route('admin.em.packages.index') }}" class="sidebar-link {{ request()->routeIs('admin.em.packages.*') ? 'active' : '' }}"><i class="fa-solid fa-box-open"></i><span>Packages</span></a>
            <a href="{{ route('admin.em.sessions.index') }}" class="sidebar-link {{ request()->routeIs('admin.em.sessions.*') ? 'active' : '' }}"><i class="fa-solid fa-calendar-days"></i><span>Sessions</span></a>
            <a href="{{ route('admin.payments.index') }}" class="sidebar-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}"><i class="fa-solid fa-credit-card"></i><span>Payments</span></a>
        @endif
        @if($canSettings)
            <div class="settings-menu {{ $settingsActive ? 'open' : '' }}">
                <button class="sidebar-link settings-toggle" type="button"><span class="d-flex align-items-center gap-3"><i class="fa-solid fa-gear"></i><span>Settings</span></span><i class="fa-solid fa-chevron-down settings-arrow"></i></button>
                <div class="settings-submenu">
                    @if($user->hasAdminPermission('manage_users'))<a href="{{ route('admin.users.index') }}" class="sidebar-link submenu-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"><i class="fa-solid fa-users"></i><span>Users</span></a>@endif
                    @if($user->hasAdminPermission('manage_permissions'))<a href="{{ route('admin.permissions.index') }}" class="sidebar-link submenu-link {{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}"><i class="fa-solid fa-lock"></i><span>Permissions</span></a>@endif
                    @if($user->hasAdminPermission('manage_user_groups'))<a href="{{ route('admin.user-groups.index') }}" class="sidebar-link submenu-link {{ request()->routeIs('admin.user-groups.*') ? 'active' : '' }}"><i class="fa-solid fa-users-gear"></i><span>User Groups</span></a>@endif
                </div>
            </div>
        @endif
    </nav>
    <div class="sidebar-footer"><form method="POST" action="{{ route('admin.logout') }}">@csrf<button type="submit" class="sidebar-link logout-link"><i class="fa-solid fa-arrow-right-from-bracket"></i><span>Logout</span></button></form></div>
</aside>