@extends('admin.layouts.app')

@section('title', 'Edit Profile')
@section('page-title', 'Edit Profile')

@section('content')
<div class="page-heading">
    <div>
        <h2>Edit Profile</h2>
        <p>Update your own admin account details and password.</p>
    </div>
    <a href="{{ route('admin.profile.show') }}" class="btn btn-light border">
        <i class="fa-solid fa-arrow-left me-2"></i>Back to Profile
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-xxl-10">
        <form method="POST" action="{{ route('admin.profile.update') }}">
            @csrf
            @method('PUT')

            <div class="admin-card mb-4">
                <div class="admin-card-header">
                    <h3><i class="fa-regular fa-id-card me-2 text-primary"></i>Profile Information</h3>
                </div>
                <div class="admin-card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" for="profile_name">Name <span class="text-danger">*</span></label>
                            <input id="profile_name" class="form-control" name="name" value="{{ old('name', $user->name) }}" maxlength="190" required autocomplete="name">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" for="profile_email">Email Address <span class="text-danger">*</span></label>
                            <input id="profile_email" class="form-control" type="email" name="email" value="{{ old('email', $user->email) }}" maxlength="190" required autocomplete="email">
                        </div>
                    </div>
                </div>
            </div>

            <div class="admin-card">
                <div class="admin-card-header">
                    <div>
                        <h3><i class="fa-solid fa-lock me-2 text-primary"></i>Change Password</h3>
                        <small class="text-muted">Leave these fields empty if you do not want to change your password.</small>
                    </div>
                </div>
                <div class="admin-card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold" for="current_password">Current Password</label>
                            <div class="admin-password-wrap">
                                <input id="current_password" class="form-control" type="password" name="current_password" autocomplete="current-password">
                                <button type="button" class="admin-password-toggle" data-password-target="current_password" aria-label="Show current password">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            </div>
                            <div class="form-text">Required only when setting a new password.</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold" for="profile_password">New Password</label>
                            <div class="admin-password-wrap">
                                <input id="profile_password" class="form-control" type="password" name="password" minlength="6" autocomplete="new-password">
                                <button type="button" class="admin-password-toggle" data-password-target="profile_password" aria-label="Show new password">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            </div>
                            <div class="form-text">Minimum 6 characters.</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold" for="profile_password_confirmation">Confirm New Password</label>
                            <div class="admin-password-wrap">
                                <input id="profile_password_confirmation" class="form-control" type="password" name="password_confirmation" minlength="6" autocomplete="new-password">
                                <button type="button" class="admin-password-toggle" data-password-target="profile_password_confirmation" aria-label="Show password confirmation">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="admin-profile-edit-note mt-4">
                        <i class="fa-solid fa-shield-halved"></i>
                        <span>User groups and admin permissions are managed separately from the Users settings area.</span>
                    </div>

                    <div class="d-flex flex-column flex-sm-row justify-content-end gap-2 mt-4">
                        <a href="{{ route('admin.profile.show') }}" class="btn btn-light border px-4">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fa-solid fa-floppy-disk me-2"></i>Save Profile
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
    .admin-password-wrap{position:relative}.admin-password-wrap .form-control{padding-right:46px}.admin-password-toggle{position:absolute;right:7px;top:50%;transform:translateY(-50%);width:34px;height:34px;border:0;border-radius:8px;background:transparent;color:#7a8495;display:flex;align-items:center;justify-content:center}.admin-password-toggle:hover{background:var(--primary-soft);color:var(--primary)}
    .admin-profile-edit-note{display:flex;align-items:flex-start;gap:10px;padding:13px 15px;border-radius:10px;background:#f8fafc;border:1px solid var(--border);color:#667085;font-size:12px;font-weight:600}.admin-profile-edit-note i{color:var(--primary);margin-top:2px}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-password-target]').forEach(function (button) {
        button.addEventListener('click', function () {
            const input = document.getElementById(button.dataset.passwordTarget);
            if (!input) return;

            const show = input.type === 'password';
            input.type = show ? 'text' : 'password';

            const icon = button.querySelector('i');
            if (icon) {
                icon.classList.toggle('fa-eye', !show);
                icon.classList.toggle('fa-eye-slash', show);
            }
        });
    });
});
</script>
@endpush
