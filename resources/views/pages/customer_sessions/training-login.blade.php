@extends('layouts.app')

@section('title', ($mode ?? 'login') === 'set_password' ? 'Create Training Password | Alcatraz Outlaws' : 'Training Login | Alcatraz Outlaws')

@section('content')
@php
    $isSetPassword = ($mode ?? 'login') === 'set_password';
    $emailValue = $email ?? old('email');
@endphp

<div class="training-auth-page">
    <div class="training-auth-shell">
        @if(session('success'))
            <div class="training-auth-alert success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="training-auth-alert error">{{ session('error') }}</div>
        @endif

        @if(!empty($info_message))
            <div class="training-auth-alert info">{{ $info_message }}</div>
        @endif

        @if($errors->any())
            <div class="training-auth-alert error">
                <strong>Please check the form.</strong>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <div class="training-auth-card">
            <section class="training-auth-visual">
                <div class="training-auth-visual-overlay"></div>

                <div class="training-auth-visual-content">
                    <img src="{{ asset('images/logo.png') }}" alt="Alcatraz Outlaws" class="training-auth-brand">

                    <div class="training-auth-visual-copy">
                        <span class="training-auth-pill">
                            {{ $isSetPassword ? 'CREATE PASSWORD' : 'PARENT LOGIN' }}
                        </span>

                        <h2>Train Hard.<br>Play Harder.</h2>

                        <a href="{{ route('em.customer.index') }}#sessions" class="training-auth-browse-btn">
                            Browse Sessions
                            <i class="fa-regular fa-calendar"></i>
                        </a>
                    </div>
                </div>
            </section>

            <section class="training-auth-form-panel">
                <img src="{{ asset('images/logo.png') }}" alt="Alcatraz Outlaws" class="training-auth-form-logo">

                @if($isSetPassword)
                    <div class="training-kicker">First time here?</div>
                    <h1>Create Password</h1>
                    <p class="training-auth-intro">
                        Your parent account already exists in Alcatraz Outlaws Training. Create your password to continue booking sessions.
                    </p>

                    <form method="POST" action="{{ route('em.customer.password.store') }}" class="training-login-form">
                        @csrf

                        <div class="training-field">
                            <label>Email Address</label>
                            <div class="training-readonly-email">
                                <i class="fa-regular fa-envelope"></i>
                                <span>{{ $emailValue }}</span>
                            </div>
                        </div>

                        <div class="training-field">
                            <label for="setup_password">New Password <span>*</span></label>
                            <div class="training-input-wrap password-wrap">
                                <i class="fa-solid fa-lock"></i>
                                <input id="setup_password" type="password" name="password" autocomplete="new-password" placeholder="Create password" required autofocus>
                                <button type="button" class="training-password-toggle" data-target="setup_password" aria-label="Show password">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="training-field">
                            <label for="setup_password_confirmation">Re-type Password <span>*</span></label>
                            <div class="training-input-wrap password-wrap">
                                <i class="fa-solid fa-lock"></i>
                                <input id="setup_password_confirmation" type="password" name="password_confirmation" autocomplete="new-password" placeholder="Re-type password" required>
                                <button type="button" class="training-password-toggle" data-target="setup_password_confirmation" aria-label="Show password">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="training-login-actions">
                            <a href="{{ route('em.customer.login') }}" class="training-auth-back-btn">
                                <i class="fa-solid fa-arrow-left"></i> Back
                            </a>
                            <button type="submit" class="training-auth-submit-btn">
                                Create Password <i class="fa-solid fa-key"></i>
                            </button>
                        </div>
                    </form>
                @else
                    <div class="training-kicker">Welcome back</div>
                    <h1>Login to Book</h1>
                    <p class="training-auth-intro">
                        Sign in to manage players, credits, bookings and checkout.
                    </p>

                    <form method="POST" action="{{ route('em.customer.login.submit') }}" class="training-login-form" novalidate>
                        @csrf
                        <input type="hidden" name="redirect" value="{{ old('redirect', $redirect ?? request('redirect')) }}">

                        <div class="training-field">
                            <label for="login_email">Email Address</label>
                            <div class="training-input-wrap">
                                <i class="fa-regular fa-envelope"></i>
                                <input id="login_email" type="email" name="email" value="{{ $emailValue }}" autocomplete="email" placeholder="you@example.com" required autofocus>
                            </div>
                        </div>

                        <div class="training-field">
                            <label for="login_password">Password</label>
                            <div class="training-input-wrap password-wrap">
                                <i class="fa-solid fa-lock"></i>
                                <input id="login_password" type="password" name="password" autocomplete="current-password" placeholder="••••••••">
                                <button type="button" class="training-password-toggle" data-target="login_password" aria-label="Show password">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="training-login-options">
                            <label class="training-remember">
                                <input type="checkbox" name="remember" value="1">
                                <span>Remember Me</span>
                            </label>
                            <a href="{{ route('em.customer.password.request') }}">Forgot Password?</a>
                        </div>

                        <div class="training-login-actions">
                            <a href="{{ route('em.customer.index') }}" class="training-auth-back-btn">
                                <i class="fa-solid fa-arrow-left"></i> Back
                            </a>
                            <button type="submit" class="training-auth-submit-btn">
                                Login <i class="fa-solid fa-arrow-right-to-bracket"></i>
                            </button>
                        </div>
                    </form>

                    <p class="training-auth-switch">
                        New to Training?
                        <a href="{{ route('em.customer.register') }}">Create Account</a>
                    </p>
                @endif
            </section>
        </div>
    </div>
</div>

<style>
.training-auth-page{--outlaws-red:#f3282c;--outlaws-dark:#07101f;--outlaws-muted:#667085;min-height:100vh;padding:54px 24px 74px;background:radial-gradient(circle at 7% 8%,rgba(243,40,44,.08),transparent 28%),linear-gradient(180deg,#fff 0%,#fbfbfc 100%);font-family:'Karla',Arial,sans-serif}.training-auth-shell{max-width:1160px;margin:0 auto}.training-auth-alert{margin-bottom:16px;padding:13px 18px;border-radius:12px;font-weight:700;display:flex;gap:8px;align-items:center}.training-auth-alert.success{color:#166534;background:#ecfdf3;border:1px solid #bbf7d0}.training-auth-alert.error{color:#991b1b;background:#fff1f2;border:1px solid #fecdd3}.training-auth-alert.info{color:#1d4ed8;background:#eff6ff;border:1px solid #bfdbfe}.training-auth-card{display:grid;grid-template-columns:1fr 1fr;overflow:hidden;background:#fff;border-radius:28px;box-shadow:0 26px 70px rgba(15,23,42,.14);border:1px solid #eaecf0}.training-auth-visual{position:relative;min-height:650px;background:url('{{ asset('bay-area-lacrosse-academy-images/1.jpg') }}') center/cover no-repeat}.training-auth-visual-overlay{position:absolute;inset:0;background:linear-gradient(180deg,rgba(2,8,18,.28),rgba(2,8,18,.58) 48%,rgba(0,0,0,.92))}.training-auth-visual-content{position:relative;z-index:2;height:100%;min-height:inherit;padding:46px;display:flex;flex-direction:column;justify-content:space-between}.training-auth-brand{width:205px;max-height:96px;object-fit:contain;object-position:left center;filter:drop-shadow(0 4px 10px rgba(0,0,0,.25))}.training-auth-pill{display:inline-flex;align-items:center;width:max-content;padding:9px 16px;border:1px solid rgba(243,40,44,.9);border-radius:999px;background:rgba(9,15,27,.42);color:#fff;font-size:12px;line-height:1;font-weight:900;letter-spacing:.18em}.training-auth-visual h2{margin:20px 0 26px;color:#fff;font-family:'Archivo Black','Karla',Arial,sans-serif;font-size:clamp(48px,5.2vw,74px);line-height:.92;letter-spacing:-.06em}.training-auth-browse-btn{display:inline-flex;align-items:center;gap:11px;padding:14px 22px;border-radius:999px;background:var(--outlaws-red);color:#fff!important;font-weight:900;text-decoration:none;box-shadow:0 14px 26px rgba(243,40,44,.27);transition:.2s ease}.training-auth-browse-btn:hover{transform:translateY(-2px);background:#d91f23}.training-auth-form-panel{padding:48px 52px 38px;background:#fff;display:flex;flex-direction:column;justify-content:center}.training-auth-form-logo{width:205px;max-height:86px;object-fit:contain;object-position:left center;margin-bottom:24px}.training-kicker{font-family:'Caveat',cursive;color:var(--outlaws-red);font-size:42px;font-weight:700;line-height:.95;margin-bottom:4px}.training-auth-form-panel h1{margin:0;color:var(--outlaws-dark);font-family:'Archivo Black','Karla',Arial,sans-serif;font-size:clamp(40px,4vw,58px);line-height:.98;letter-spacing:-.055em}.training-auth-intro{margin:14px 0 26px;color:var(--outlaws-muted);font-size:15px;font-weight:600;line-height:1.55}.training-field{margin-bottom:18px}.training-field label{display:block;margin-bottom:7px;color:#475467;font-size:13px;font-weight:800}.training-field label span{color:var(--outlaws-red)}.training-input-wrap{min-height:50px;padding:0 14px;border:1px solid #d9dee7;border-radius:13px;background:#fff;display:flex;align-items:center;gap:11px;transition:border-color .2s ease,box-shadow .2s ease}.training-input-wrap:focus-within{border-color:var(--outlaws-red);box-shadow:0 0 0 3px rgba(243,40,44,.1)}.training-input-wrap>i{width:17px;color:#8b95a5;text-align:center}.training-input-wrap input{flex:1;min-width:0;height:48px;border:0;outline:0;background:transparent;color:#111827;font-size:14px;font-weight:700}.training-input-wrap input::placeholder{color:#98a2b3;font-weight:500}.training-password-toggle{border:0;background:transparent;color:#8b95a5;padding:5px}.training-readonly-email{min-height:50px;padding:0 14px;border:1px solid #e4e7ec;border-radius:13px;background:#f8fafc;display:flex;align-items:center;gap:11px;color:#111827;font-weight:800;word-break:break-word}.training-readonly-email i{color:#8b95a5}.training-login-options{display:flex;justify-content:space-between;align-items:center;gap:16px;margin:4px 0 28px;font-size:13px;font-weight:800}.training-login-options a,.training-auth-switch a{color:var(--outlaws-red);text-decoration:none;font-weight:900}.training-remember{display:inline-flex;align-items:center;gap:7px;color:#667085;cursor:pointer}.training-remember input{accent-color:var(--outlaws-red)}.training-login-actions{display:grid;grid-template-columns:1fr 1fr;gap:12px;padding-top:8px}.training-auth-back-btn,.training-auth-submit-btn{min-height:50px;border-radius:999px;display:inline-flex;align-items:center;justify-content:center;gap:10px;text-decoration:none;font-size:15px;font-weight:900;transition:.2s ease}.training-auth-back-btn{border:1.5px solid #111827;background:#fff;color:#111827!important}.training-auth-submit-btn{border:0;background:var(--outlaws-red);color:#fff;box-shadow:0 12px 24px rgba(243,40,44,.22)}.training-auth-back-btn:hover{background:#111827;color:#fff!important}.training-auth-submit-btn:hover{background:#d91f23;transform:translateY(-1px)}.training-auth-switch{margin:22px 0 0;text-align:center;color:#667085;font-size:13px;font-weight:700}@media(max-width:991px){.training-auth-page{padding:28px 16px 54px}.training-auth-card{grid-template-columns:1fr}.training-auth-visual{min-height:410px}.training-auth-visual-content{padding:30px}.training-auth-visual h2{font-size:54px}.training-auth-form-panel{padding:34px 26px 30px}}@media(max-width:720px){.training-login-actions{grid-template-columns:1fr}.training-login-options{align-items:flex-start;flex-direction:column}.training-auth-form-logo{width:180px}.training-auth-form-panel h1{font-size:40px}}@media(max-width:480px){.training-auth-page{padding-inline:10px}.training-auth-card{border-radius:20px}.training-auth-visual-content{padding:24px}.training-auth-visual h2{font-size:44px}.training-auth-form-panel{padding:28px 20px}}
</style>

<script>
document.addEventListener('DOMContentLoaded',function(){document.querySelectorAll('.training-password-toggle').forEach(function(button){button.addEventListener('click',function(){const input=document.getElementById(button.dataset.target);if(!input)return;const hidden=input.type==='password';input.type=hidden?'text':'password';const icon=button.querySelector('i');if(icon){icon.classList.toggle('fa-eye',!hidden);icon.classList.toggle('fa-eye-slash',hidden)}button.setAttribute('aria-label',hidden?'Hide password':'Show password')})})});
</script>
@endsection
