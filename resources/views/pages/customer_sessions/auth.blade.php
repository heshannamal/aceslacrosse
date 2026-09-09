@extends('layouts.app')

@section('title', ($mode ?? 'login') === 'register' ? 'Create Training Account | Alcatraz Outlaws' : 'Training Login | Alcatraz Outlaws')

@section('content')
@php
    $isRegister = ($mode ?? 'login') === 'register';
@endphp

<div class="training-auth-page {{ $isRegister ? 'is-register' : 'is-login' }}">
    <div class="training-auth-shell">
        @if(session('success'))
            <div class="training-auth-alert success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="training-auth-alert error">{{ session('error') }}</div>
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
                            {{ $isRegister ? 'CREATE ACCOUNT' : 'PARENT LOGIN' }}
                        </span>

                        <h2>Train Hard.<br>Play Harder.</h2>

                        <a href="{{ route('em.customer.index') }}" class="training-auth-browse-btn">
                            Browse Sessions
                            <i class="fa-regular fa-calendar"></i>
                        </a>
                    </div>
                </div>
            </section>

            <section class="training-auth-form-panel">
                <img src="{{ asset('images/logo.png') }}" alt="Alcatraz Outlaws" class="training-auth-form-logo">

                @if($isRegister)
                    <h1>Create Your Training Account</h1>
                    <p class="training-auth-intro">
                        Create the Parent 1 account and add the first player. This online account uses guest Training pricing.
                    </p>

                    <form method="POST" action="{{ route('em.customer.register.submit') }}" class="training-register-form">
                        @csrf

                        <div class="training-register-columns">
                            <div class="training-register-column">
                                <div class="training-form-section-title">Parent Details</div>

                                <div class="training-field">
                                    <label for="first_name">Parent First Name <span>*</span></label>
                                    <div class="training-input-wrap">
                                        <i class="fa-regular fa-user"></i>
                                        <input id="first_name" name="first_name" value="{{ old('first_name') }}" autocomplete="given-name" placeholder="Enter first name" required>
                                    </div>
                                </div>

                                <div class="training-field">
                                    <label for="last_name">Parent Last Name</label>
                                    <div class="training-input-wrap">
                                        <i class="fa-regular fa-user"></i>
                                        <input id="last_name" name="last_name" value="{{ old('last_name') }}" autocomplete="family-name" placeholder="Enter last name">
                                    </div>
                                </div>

                                <div class="training-field">
                                    <label for="email">Email Address <span>*</span></label>
                                    <div class="training-input-wrap">
                                        <i class="fa-regular fa-envelope"></i>
                                        <input id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="email" placeholder="you@example.com" required>
                                    </div>
                                </div>

                                <div class="training-field">
                                    <label for="phone">Phone Number</label>
                                    <div class="training-input-wrap">
                                        <i class="fa-solid fa-phone"></i>
                                        <input id="phone" name="phone" value="{{ old('phone') }}" autocomplete="tel" placeholder="(555) 123-4567">
                                    </div>
                                </div>

                                <div class="training-field">
                                    <label for="password">Password <span>*</span></label>
                                    <div class="training-input-wrap password-wrap">
                                        <i class="fa-solid fa-lock"></i>
                                        <input id="password" type="password" name="password" autocomplete="new-password" placeholder="••••••••" required>
                                        <button type="button" class="training-password-toggle" data-target="password" aria-label="Show password">
                                            <i class="fa-regular fa-eye"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="training-field mb-0">
                                    <label for="password_confirmation">Confirm Password <span>*</span></label>
                                    <div class="training-input-wrap password-wrap">
                                        <i class="fa-solid fa-lock"></i>
                                        <input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password" placeholder="••••••••" required>
                                        <button type="button" class="training-password-toggle" data-target="password_confirmation" aria-label="Show password">
                                            <i class="fa-regular fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="training-register-column child-column">
                                <div class="training-form-section-title">Child Details</div>

                                <div class="training-field">
                                    <label for="child_first_name">Child First Name <span>*</span></label>
                                    <div class="training-input-wrap">
                                        <i class="fa-regular fa-user"></i>
                                        <input id="child_first_name" name="child_first_name" value="{{ old('child_first_name') }}" placeholder="Enter first name" required>
                                    </div>
                                </div>

                                <div class="training-field">
                                    <label for="child_last_name">Child Last Name</label>
                                    <div class="training-input-wrap">
                                        <i class="fa-regular fa-user"></i>
                                        <input id="child_last_name" name="child_last_name" value="{{ old('child_last_name') }}" placeholder="Enter last name">
                                    </div>
                                </div>

                                <div class="training-field-grid">
                                    <div class="training-field">
                                        <label for="child_team">Team</label>
                                        <div class="training-input-wrap">
                                            <i class="fa-solid fa-people-group"></i>
                                            <input id="child_team" name="child_team" value="{{ old('child_team') }}" placeholder="Team">
                                        </div>
                                    </div>
                                    <div class="training-field">
                                        <label for="child_spring_team">Spring Team</label>
                                        <div class="training-input-wrap">
                                            <i class="fa-solid fa-people-group"></i>
                                            <input id="child_spring_team" name="child_spring_team" value="{{ old('child_spring_team') }}" placeholder="Spring team">
                                        </div>
                                    </div>
                                </div>

                                <div class="training-field-grid">
                                    <div class="training-field">
                                        <label for="child_grade">Grade</label>
                                        <div class="training-input-wrap">
                                            <i class="fa-solid fa-graduation-cap"></i>
                                            <input id="child_grade" name="child_grade" value="{{ old('child_grade') }}" placeholder="Grade">
                                        </div>
                                    </div>
                                    <div class="training-field">
                                        <label for="child_class_year">Class Year</label>
                                        <div class="training-input-wrap">
                                            <i class="fa-regular fa-calendar"></i>
                                            <input id="child_class_year" name="child_class_year" value="{{ old('child_class_year') }}" placeholder="2032">
                                        </div>
                                    </div>
                                </div>

                                <div class="training-field-grid">
                                    <div class="training-field">
                                        <label for="child_birthdate">Birthdate</label>
                                        <div class="training-input-wrap">
                                            <i class="fa-regular fa-calendar-days"></i>
                                            <input id="child_birthdate" type="date" name="child_birthdate" value="{{ old('child_birthdate') }}">
                                        </div>
                                    </div>
                                    <div class="training-field">
                                        <label for="child_position">Position(s)</label>
                                        <div class="training-input-wrap">
                                            <i class="fa-solid fa-person-running"></i>
                                            <input id="child_position" name="child_position" value="{{ old('child_position') }}" placeholder="Attack, Middie, Defense, Goalie">
                                        </div>
                                    </div>
                                </div>

                                <div class="training-field-grid">
                                    <div class="training-field">
                                        <label for="child_school">School</label>
                                        <div class="training-input-wrap">
                                            <i class="fa-solid fa-school"></i>
                                            <input id="child_school" name="child_school" value="{{ old('child_school') }}" placeholder="School">
                                        </div>
                                    </div>
                                    <div class="training-field">
                                        <label for="child_gender">Gender</label>
                                        <div class="training-input-wrap">
                                            <i class="fa-regular fa-id-badge"></i>
                                            <input id="child_gender" name="child_gender" value="{{ old('child_gender') }}" placeholder="Gender">
                                        </div>
                                    </div>
                                </div>

                                <div class="training-field-grid">
                                    <div class="training-field mb-0">
                                        <label for="child_medical_notes">Medical Notes</label>
                                        <textarea id="child_medical_notes" name="child_medical_notes" placeholder="Optional medical notes">{{ old('child_medical_notes') }}</textarea>
                                    </div>
                                    <div class="training-field mb-0">
                                        <label for="child_allergies">Allergies</label>
                                        <textarea id="child_allergies" name="child_allergies" placeholder="Optional allergies">{{ old('child_allergies') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="training-register-actions">
                            <a href="{{ route('em.customer.index') }}" class="training-auth-back-btn">
                                <i class="fa-solid fa-arrow-left"></i> Back
                            </a>
                            <button type="submit" class="training-auth-submit-btn">
                                Create Account <i class="fa-solid fa-user-plus"></i>
                            </button>
                        </div>
                    </form>

                    <p class="training-auth-switch">
                        Already have a Training account?
                        <a href="{{ route('em.customer.login') }}">Login</a>
                    </p>
                @else
                    <h1>Welcome back<br>Login to Book</h1>
                    <p class="training-auth-intro">Sign in to manage players, credits, bookings and checkout.</p>

                    <form method="POST" action="{{ route('em.customer.login.submit') }}" class="training-login-form">
                        @csrf

                        <div class="training-field">
                            <label for="login_email">Email Address</label>
                            <div class="training-input-wrap">
                                <i class="fa-regular fa-envelope"></i>
                                <input id="login_email" type="email" name="email" value="{{ old('email') }}" autocomplete="email" placeholder="you@example.com" required autofocus>
                            </div>
                        </div>

                        <div class="training-field">
                            <label for="login_password">Password</label>
                            <div class="training-input-wrap password-wrap">
                                <i class="fa-solid fa-lock"></i>
                                <input id="login_password" type="password" name="password" autocomplete="current-password" placeholder="••••••••" required>
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
    .training-auth-page { --outlaws-red:#f3282c; --outlaws-dark:#07101f; --outlaws-muted:#667085; min-height:calc(100vh - 80px); padding:54px 24px 74px; background:radial-gradient(circle at 7% 8%,rgba(243,40,44,.08),transparent 28%),linear-gradient(180deg,#fff 0%,#fbfbfc 100%); font-family:'Karla',Arial,sans-serif; }
    .training-auth-page.is-login .training-auth-shell{max-width:1160px}.training-auth-page.is-register .training-auth-shell{max-width:1540px}.training-auth-shell{margin:0 auto}
    .training-auth-alert{margin-bottom:16px;padding:13px 18px;border-radius:12px;font-weight:700;display:flex;gap:8px;align-items:center}.training-auth-alert.success{color:#166534;background:#ecfdf3;border:1px solid #bbf7d0}.training-auth-alert.error{color:#991b1b;background:#fff1f2;border:1px solid #fecdd3}
    .training-auth-card{display:grid;grid-template-columns:1fr 1fr;overflow:hidden;background:#fff;border-radius:28px;box-shadow:0 26px 70px rgba(15,23,42,.14);border:1px solid #eaecf0}.is-register .training-auth-card{grid-template-columns:.72fr 1.28fr}
    .training-auth-visual{position:relative;min-height:650px;background:url('{{ asset('bay-area-lacrosse-academy-images/1.jpg') }}') center/cover no-repeat}.is-register .training-auth-visual{min-height:850px}.training-auth-visual-overlay{position:absolute;inset:0;background:linear-gradient(180deg,rgba(2,8,18,.28),rgba(2,8,18,.58) 48%,rgba(0,0,0,.92))}.training-auth-visual-content{position:relative;z-index:2;height:100%;min-height:inherit;padding:46px;display:flex;flex-direction:column;justify-content:space-between}
    .training-auth-brand{width:205px;max-height:96px;object-fit:contain;object-position:left center;filter:drop-shadow(0 4px 10px rgba(0,0,0,.25))}.training-auth-pill{display:inline-flex;align-items:center;width:max-content;padding:9px 16px;border:1px solid rgba(243,40,44,.9);border-radius:999px;background:rgba(9,15,27,.42);color:#fff;font-size:12px;line-height:1;font-weight:900;letter-spacing:.18em}.training-auth-visual h2{margin:20px 0 26px;color:#fff;font-family:'Archivo Black','Karla',Arial,sans-serif;font-size:clamp(48px,5.2vw,74px);line-height:.92;letter-spacing:-.06em}.is-register .training-auth-visual h2{font-size:clamp(48px,4.2vw,70px)}
    .training-auth-browse-btn{display:inline-flex;align-items:center;gap:11px;padding:14px 22px;border-radius:999px;background:var(--outlaws-red);color:#fff!important;font-weight:900;text-decoration:none;box-shadow:0 14px 26px rgba(243,40,44,.27);transition:.2s ease}.training-auth-browse-btn:hover{transform:translateY(-2px);background:#d91f23}
    .training-auth-form-panel{padding:48px 52px 38px;background:#fff;display:flex;flex-direction:column;justify-content:center}.is-register .training-auth-form-panel{padding:38px 48px 30px}.training-auth-form-logo{width:205px;max-height:86px;object-fit:contain;object-position:left center;margin-bottom:24px}.training-auth-form-panel h1{margin:0;color:var(--outlaws-dark);font-family:'Archivo Black','Karla',Arial,sans-serif;font-size:clamp(38px,4vw,58px);line-height:.98;letter-spacing:-.055em}.is-register .training-auth-form-panel h1{font-size:clamp(36px,3.1vw,52px)}.training-auth-intro{margin:14px 0 26px;color:var(--outlaws-muted);font-size:15px;font-weight:600;line-height:1.55}
    .training-form-section-title{margin-bottom:18px;color:#667085;font-size:12px;font-weight:900;letter-spacing:.15em;text-transform:uppercase}.training-register-columns{display:grid;grid-template-columns:1fr 1fr;gap:34px}.training-register-column.child-column{padding-left:32px;border-left:1px solid #e4e7ec}.training-field{margin-bottom:17px}.training-field label{display:block;margin-bottom:7px;color:#475467;font-size:13px;font-weight:800}.training-field label span{color:var(--outlaws-red)}
    .training-input-wrap{min-height:48px;padding:0 14px;border:1px solid #d9dee7;border-radius:13px;background:#fff;display:flex;align-items:center;gap:11px;transition:border-color .2s ease,box-shadow .2s ease}.training-input-wrap:focus-within{border-color:var(--outlaws-red);box-shadow:0 0 0 3px rgba(243,40,44,.1)}.training-input-wrap>i{width:17px;color:#8b95a5;text-align:center}.training-input-wrap input{flex:1;min-width:0;height:46px;border:0;outline:0;background:transparent;color:#111827;font-size:14px;font-weight:700}.training-input-wrap input::placeholder{color:#98a2b3;font-weight:500}
    .training-field textarea{width:100%;min-height:76px;resize:vertical;padding:12px 14px;border:1px solid #d9dee7;border-radius:13px;outline:0;color:#111827;font-size:14px;font-weight:600}.training-field textarea:focus{border-color:var(--outlaws-red);box-shadow:0 0 0 3px rgba(243,40,44,.1)}.training-field-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px}.training-password-toggle{border:0;background:transparent;color:#8b95a5;padding:5px}
    .training-login-options{display:flex;justify-content:space-between;align-items:center;gap:16px;margin:4px 0 28px;font-size:13px;font-weight:800}.training-login-options a,.training-auth-switch a{color:var(--outlaws-red);text-decoration:none;font-weight:900}.training-remember{display:inline-flex;align-items:center;gap:7px;color:#667085;cursor:pointer}.training-remember input{accent-color:var(--outlaws-red)}
    .training-login-actions,.training-register-actions{display:grid;grid-template-columns:1fr 1fr;gap:12px;padding-top:8px}.training-register-actions{margin-top:22px;padding-top:20px;border-top:1px solid #e4e7ec}.training-auth-back-btn,.training-auth-submit-btn{min-height:50px;border-radius:999px;display:inline-flex;align-items:center;justify-content:center;gap:10px;text-decoration:none;font-size:15px;font-weight:900;transition:.2s ease}.training-auth-back-btn{border:1.5px solid #111827;background:#fff;color:#111827!important}.training-auth-submit-btn{border:0;background:var(--outlaws-red);color:#fff;box-shadow:0 12px 24px rgba(243,40,44,.22)}.training-auth-back-btn:hover{background:#111827;color:#fff!important}.training-auth-submit-btn:hover{background:#d91f23;transform:translateY(-1px)}.training-auth-switch{margin:22px 0 0;text-align:center;color:#667085;font-size:13px;font-weight:700}
    @media(max-width:1180px){.is-register .training-auth-card{grid-template-columns:.62fr 1.38fr}.is-register .training-auth-form-panel{padding:34px 30px 28px}.training-register-columns{gap:24px}.training-register-column.child-column{padding-left:24px}}
    @media(max-width:991px){.training-auth-page{padding:28px 16px 54px}.training-auth-card,.is-register .training-auth-card{grid-template-columns:1fr}.training-auth-visual,.is-register .training-auth-visual{min-height:410px}.training-auth-visual-content{padding:30px}.training-auth-visual h2,.is-register .training-auth-visual h2{font-size:54px}.training-auth-form-panel,.is-register .training-auth-form-panel{padding:34px 26px 30px}}
    @media(max-width:720px){.training-register-columns{grid-template-columns:1fr;gap:24px}.training-register-column.child-column{padding:22px 0 0;border-left:0;border-top:1px solid #e4e7ec}.training-field-grid{grid-template-columns:1fr;gap:0}.training-login-actions,.training-register-actions{grid-template-columns:1fr}.training-login-options{align-items:flex-start;flex-direction:column}.training-auth-form-logo{width:180px}.training-auth-form-panel h1{font-size:40px}}
    @media(max-width:480px){.training-auth-page{padding-inline:10px}.training-auth-card{border-radius:20px}.training-auth-visual-content{padding:24px}.training-auth-visual h2,.is-register .training-auth-visual h2{font-size:44px}.training-auth-form-panel,.is-register .training-auth-form-panel{padding:28px 20px}}
</style>

<script>
document.addEventListener('DOMContentLoaded',function(){document.querySelectorAll('.training-password-toggle').forEach(function(button){button.addEventListener('click',function(){const input=document.getElementById(button.dataset.target);if(!input)return;const hidden=input.type==='password';input.type=hidden?'text':'password';const icon=button.querySelector('i');if(icon){icon.classList.toggle('fa-eye',!hidden);icon.classList.toggle('fa-eye-slash',hidden)}button.setAttribute('aria-label',hidden?'Hide password':'Show password')})})});
</script>
@endsection
