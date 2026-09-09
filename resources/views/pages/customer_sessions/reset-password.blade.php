@extends('layouts.app')
@section('title', 'Choose New Training Password | ACES Lacrosse')
@section('content')
@include('pages.customer_sessions._styles')
<div class="ao-training"><div class="ao-wrap" style="max-width:650px"><div class="ao-panel" style="padding:36px">
    <div class="ao-eyebrow">ACES TRAINING ACCOUNT</div><h1 class="ao-section-title mt-2">Choose a new password</h1><p class="ao-muted mt-2 mb-4">Use at least 8 characters. This secure reset link can only be used for the matching ACES Training account.</p>
    @if($errors->any())<div class="ao-alert ao-alert-error">{{ $errors->first() }}</div>@endif
    <form method="POST" action="{{ route('em.customer.password.update') }}">@csrf
        <input type="hidden" name="token" value="{{ $token }}"><input type="hidden" name="email" value="{{ $email }}">
        <div class="ao-field mb-3"><label>Email</label><input class="ao-input" value="{{ $email }}" disabled></div>
        <div class="ao-field mb-3"><label>New Password *</label><input class="ao-input" type="password" name="password" required autofocus></div>
        <div class="ao-field mb-4"><label>Confirm New Password *</label><input class="ao-input" type="password" name="password_confirmation" required></div>
        <button class="ao-btn ao-btn-red">Update Password <i class="fa-solid fa-check"></i></button>
    </form>
</div></div></div>
@endsection
