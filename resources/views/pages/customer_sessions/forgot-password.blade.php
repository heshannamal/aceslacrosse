@extends('layouts.app')
@section('content')
@include('pages.customer_sessions._styles')
<div class="ao-training"><div class="ao-wrap" style="max-width:650px">
    <div class="ao-panel" style="padding:36px">
        <div class="ao-eyebrow">ACCOUNT RECOVERY</div><h1 class="ao-section-title mt-2">Reset your Training password</h1>
        <p class="ao-muted mt-2 mb-4">Enter the email used for your Alcatraz Outlaws Training account. If it matches an active account, we’ll email you a secure reset link.</p>
        @if(session('success'))<div class="ao-alert ao-alert-success">{{ session('success') }}</div>@endif
        @if(session('error'))<div class="ao-alert ao-alert-error">{{ session('error') }}</div>@endif
        @if($errors->any())<div class="ao-alert ao-alert-error">{{ $errors->first() }}</div>@endif
        <form method="POST" action="{{ route('em.customer.password.email') }}">@csrf
            <div class="ao-field mb-4"><label>Email Address *</label><input class="ao-input" type="email" name="email" value="{{ old('email') }}" required autofocus></div>
            <div class="d-flex gap-2 flex-wrap"><a class="ao-btn ao-btn-outline" href="{{ route('em.customer.login') }}"><i class="fa-solid fa-arrow-left"></i> Back to Login</a><button class="ao-btn ao-btn-red">Send Reset Link</button></div>
        </form>
    </div>
</div></div>
@endsection
