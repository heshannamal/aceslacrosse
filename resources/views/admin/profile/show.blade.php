@extends('admin.layouts.app')

@section('title', 'Profile')
@section('page-title', 'My Profile')

@section('content')
@php
    $initials = collect(explode(' ', $user->name))
        ->filter()
        ->take(2)
        ->map(fn ($part) => strtoupper(substr($part, 0, 1)))
        ->implode('') ?: 'AD';
    $roleLabel = (int) $user->id === 1 ? 'Super Admin' : 'Admin';
@endphp

<div class="page-heading">
    <div>
        <h2>Admin Profile</h2>
        <p>View your account details, access level, and assigned groups.</p>
    </div>
    <a href="{{ route('admin.profile.edit') }}" class="btn btn-primary px-4">
        <i class="fa-solid fa-pen-to-square me-2"></i>Edit Profile
    </a>
</div>

<div class="row g-4">
    <div class="col-xl-4">
        <div class="admin-card admin-profile-summary h-100">
            <div class="admin-card-body text-center p-4 p-lg-5">
                <div class="admin-profile-avatar mx-auto">{{ $initials }}</div>
                <h3 class="admin-profile-name">{{ $user->name }}</h3>
                <p class="admin-profile-email">{{ $user->email }}</p>
                <span class="status-badge badge-soft-primary mt-2">{{ $roleLabel }}</span>

                <div class="admin-profile-meta mt-4">
                    <div>
                        <span>Admin ID</span>
                        <strong>#{{ $user->id }}</strong>
                    </div>
                    <div>
                        <span>Member Since</span>
                        <strong>{{ optional($user->created_at)->format('M d, Y') ?: '—' }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-8">
        <div class="admin-card mb-4">
            <div class="admin-card-header">
                <h3><i class="fa-regular fa-user me-2 text-primary"></i>Account Details</h3>
            </div>
            <div class="admin-card-body">
                <div class="row g-3 admin-profile-detail-grid">
                    <div class="col-md-6">
                        <div class="admin-profile-detail-item">
                            <span>Full Name</span>
                            <strong>{{ $user->name }}</strong>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="admin-profile-detail-item">
                            <span>Email Address</span>
                            <strong>{{ $user->email }}</strong>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="admin-profile-detail-item">
                            <span>Account Type</span>
                            <strong>{{ $roleLabel }}</strong>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="admin-profile-detail-item">
                            <span>Last Updated</span>
                            <strong>{{ optional($user->updated_at)->format('M d, Y h:i A') ?: '—' }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="admin-card">
            <div class="admin-card-header">
                <h3><i class="fa-solid fa-user-shield me-2 text-primary"></i>Access & User Groups</h3>
            </div>
            <div class="admin-card-body">
                @if((int) $user->id === 1)
                    <div class="admin-profile-access-note">
                        <i class="fa-solid fa-crown"></i>
                        <div>
                            <strong>Super Admin Access</strong>
                            <p class="mb-0">This account has access to all admin modules and permissions.</p>
                        </div>
                    </div>
                @elseif($user->userGroups->isEmpty())
                    <div class="empty-state py-4">
                        <i class="fa-solid fa-users-slash"></i>
                        <p class="mb-0">No user groups are assigned to this account.</p>
                    </div>
                @else
                    <div class="row g-3">
                        @foreach($user->userGroups as $group)
                            <div class="col-md-6">
                                <div class="admin-profile-group-card">
                                    <div class="admin-profile-group-icon"><i class="fa-solid fa-users-gear"></i></div>
                                    <div class="min-w-0">
                                        <strong>{{ $group->name }}</strong>
                                        <span>{{ $group->slug }}</span>
                                        @if($group->permissions->isNotEmpty())
                                            <small>{{ $group->permissions->where('active', 1)->count() }} active permissions</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .admin-profile-avatar{width:104px;height:104px;border-radius:50%;display:flex;align-items:center;justify-content:center;background:#111827;color:#fff;border:5px solid #fff;box-shadow:0 0 0 4px var(--primary),0 16px 32px rgba(15,23,42,.14);font-size:30px;font-weight:900;letter-spacing:.04em}
    .admin-profile-name{margin:24px 0 4px;font-size:24px;font-weight:800}.admin-profile-email{margin:0;color:var(--muted);font-weight:600;word-break:break-word}
    .admin-profile-meta{display:grid;grid-template-columns:1fr 1fr;border-top:1px solid var(--border);padding-top:22px;gap:14px}.admin-profile-meta>div{display:flex;flex-direction:column;gap:5px}.admin-profile-meta span,.admin-profile-detail-item span{color:var(--muted);font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.45px}.admin-profile-meta strong{font-size:13px}
    .admin-profile-detail-item{height:100%;padding:16px 18px;border:1px solid var(--border);border-radius:10px;background:#fbfbfc;display:flex;flex-direction:column;gap:7px}.admin-profile-detail-item strong{font-size:14px;word-break:break-word}
    .admin-profile-access-note{display:flex;align-items:flex-start;gap:14px;padding:18px;border-radius:12px;background:var(--primary-soft);border:1px solid rgba(243,40,44,.14)}.admin-profile-access-note>i{width:42px;height:42px;border-radius:50%;display:flex;align-items:center;justify-content:center;background:var(--primary);color:#fff;flex:0 0 42px}.admin-profile-access-note strong{display:block;margin-bottom:4px}.admin-profile-access-note p{color:#687386;font-size:13px}
    .admin-profile-group-card{height:100%;display:flex;align-items:center;gap:13px;border:1px solid var(--border);border-radius:10px;padding:14px;background:#fff}.admin-profile-group-icon{width:42px;height:42px;border-radius:9px;background:var(--primary-soft);color:var(--primary);display:flex;align-items:center;justify-content:center;flex:0 0 42px}.admin-profile-group-card strong,.admin-profile-group-card span,.admin-profile-group-card small{display:block}.admin-profile-group-card span{font-size:11px;color:var(--muted)}.admin-profile-group-card small{font-size:10px;color:var(--primary);font-weight:700;margin-top:3px}
    @media(max-width:575.98px){.admin-profile-meta{grid-template-columns:1fr}.admin-profile-avatar{width:88px;height:88px;font-size:26px}}
</style>
@endpush
