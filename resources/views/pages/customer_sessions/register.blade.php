@extends('layouts.app')

@section('title', 'Create Training Account | ACES Lacrosse')

@section('content')
<div class="training-register-page">
    <div class="training-register-shell">
        @if(session('success'))
            <div class="training-register-alert success"><i class="fa-solid fa-circle-check"></i>{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="training-register-alert error"><i class="fa-solid fa-circle-exclamation"></i>{{ session('error') }}</div>
        @endif

        @if($errors->any())
            <div class="training-register-alert error">
                <i class="fa-solid fa-circle-exclamation"></i>
                <div><strong>Please check the form.</strong><br><span>{{ $errors->first() }}</span></div>
            </div>
        @endif

        <div class="training-register-card">
            <section class="training-register-visual">
                <div class="training-register-overlay"></div>
                <div class="training-register-visual-content">
                    <img src="{{ asset('public/assets/images/ACES-logo.webp') }}" alt="ACES Lacrosse" class="training-register-brand">

                    <div class="training-register-visual-copy">
                        <span class="training-register-pill">ACES TRAINING</span>
                        <h2>Train Hard.<br>Play Harder.</h2>
                        <p>Create your parent account, add your player, and start booking ACES Lacrosse training sessions.</p>
                        <a href="{{ route('em.customer.index') }}#trainingSessions" class="training-register-browse-btn">
                            Browse Sessions <i class="fa-regular fa-calendar"></i>
                        </a>
                    </div>
                </div>
            </section>

            <section class="training-register-panel">
                <div class="training-register-heading">
                    <img src="{{ asset('public/assets/images/ACES-logo.webp') }}" alt="ACES Lacrosse" class="training-register-form-logo">
                    <div>
                        <span class="training-register-kicker">New to ACES Training?</span>
                        <h1>Create Your Account</h1>
                        <p>Add your parent details and your first player's information. You can add more players later from your Training profile.</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('em.customer.register.submit') }}" id="trainingRegisterForm">
                    @csrf

                    <div class="training-register-columns">
                        <div class="training-register-column">
                            <div class="training-form-section-title"><i class="fa-regular fa-user"></i> Parent Details</div>

                            <div class="training-field-grid">
                                <div class="training-field">
                                    <label for="first_name">First Name <span>*</span></label>
                                    <div class="training-input-wrap">
                                        <i class="fa-regular fa-user"></i>
                                        <input id="first_name" name="first_name" value="{{ old('first_name') }}" autocomplete="given-name" required>
                                    </div>
                                </div>

                                <div class="training-field">
                                    <label for="last_name">Last Name</label>
                                    <div class="training-input-wrap">
                                        <i class="fa-regular fa-user"></i>
                                        <input id="last_name" name="last_name" value="{{ old('last_name') }}" autocomplete="family-name">
                                    </div>
                                </div>
                            </div>

                            <div class="training-field">
                                <label for="email">Email Address <span>*</span></label>
                                <div class="training-input-wrap">
                                    <i class="fa-regular fa-envelope"></i>
                                    <input id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="email" required>
                                </div>
                            </div>

                            <div class="training-field">
                                <label for="phone">Phone Number</label>
                                <div class="training-input-wrap">
                                    <i class="fa-solid fa-phone"></i>
                                    <input id="phone" name="phone" value="{{ old('phone') }}" autocomplete="tel">
                                </div>
                            </div>

                            <div class="training-field-grid">
                                <div class="training-field mb-0">
                                    <label for="password">Password <span>*</span></label>
                                    <div class="training-input-wrap password-wrap">
                                        <i class="fa-solid fa-lock"></i>
                                        <input id="password" type="password" name="password" autocomplete="new-password" placeholder="At least 8 characters" required>
                                        <button type="button" class="training-password-toggle" data-target="password" aria-label="Show password"><i class="fa-regular fa-eye"></i></button>
                                    </div>
                                </div>

                                <div class="training-field mb-0">
                                    <label for="password_confirmation">Confirm Password <span>*</span></label>
                                    <div class="training-input-wrap password-wrap">
                                        <i class="fa-solid fa-lock"></i>
                                        <input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password" placeholder="Repeat password" required>
                                        <button type="button" class="training-password-toggle" data-target="password_confirmation" aria-label="Show password"><i class="fa-regular fa-eye"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @include('pages.customer_sessions.register-child-fields')
                    </div>

                    <div class="training-register-actions">
                        <a href="{{ route('em.customer.index') }}" class="training-register-back-btn"><i class="fa-solid fa-arrow-left"></i> Back to Training</a>
                        <button type="submit" class="training-register-submit-btn">Create Account <i class="fa-solid fa-user-plus"></i></button>
                    </div>
                </form>

                <p class="training-register-switch">Already have a Training account? <a href="{{ route('em.customer.login') }}">Sign in</a></p>
            </section>
        </div>
    </div>
</div>

<style>
.training-register-page{--aces-purple:#611eb2;--aces-purple-dark:#4d168f;--aces-purple-soft:#f4edfc;--aces-dark:#171021;--aces-muted:#667085;min-height:100vh;padding:42px 22px 68px;overflow-x:hidden;background:radial-gradient(circle at 8% 7%,rgba(97,30,178,.09),transparent 28%),linear-gradient(180deg,#fff 0%,#f8f7fa 100%);font-family:'Roboto',Arial,sans-serif}.training-register-page,.training-register-page *{box-sizing:border-box}.training-register-shell{width:min(1500px,100%);margin:0 auto}.training-register-alert{margin:0 0 16px;padding:13px 18px;border-radius:12px;font-weight:700;display:flex;gap:10px;align-items:flex-start}.training-register-alert.success{color:#166534;background:#ecfdf3;border:1px solid #bbf7d0}.training-register-alert.error{color:#991b1b;background:#fff1f2;border:1px solid #fecdd3}.training-register-card{display:grid;grid-template-columns:minmax(320px,35%) minmax(0,1fr);overflow:hidden;background:#fff;border:1px solid #e8e4ed;border-radius:28px;box-shadow:0 28px 72px rgba(38,20,54,.14)}.training-register-visual{position:relative;min-height:760px;background:url('{{ asset('public/assets/images/hero-bg.jpg') }}') center/cover no-repeat}.training-register-overlay{position:absolute;inset:0;background:linear-gradient(180deg,rgba(23,16,33,.26),rgba(23,16,33,.6) 52%,rgba(10,6,15,.96))}.training-register-visual-content{position:relative;z-index:2;min-height:inherit;padding:42px;display:flex;flex-direction:column;justify-content:space-between}.training-register-brand{width:210px;max-height:100px;object-fit:contain;object-position:left center;filter:drop-shadow(0 4px 12px rgba(0,0,0,.28))}.training-register-pill{display:inline-flex;padding:9px 16px;border:1px solid rgba(200,151,255,.9);border-radius:999px;background:rgba(23,16,33,.5);color:#fff;font-size:12px;font-weight:900;letter-spacing:.18em}.training-register-visual h2{margin:20px 0 16px;color:#fff;font-size:clamp(46px,4.5vw,72px);font-weight:900;line-height:.92;letter-spacing:-.055em}.training-register-visual p{max-width:480px;margin:0;color:rgba(255,255,255,.82);font-size:15px;font-weight:600;line-height:1.6}.training-register-browse-btn{display:inline-flex;align-items:center;gap:10px;margin-top:22px;padding:14px 22px;border-radius:999px;background:var(--aces-purple);color:#fff!important;font-weight:900;text-decoration:none;box-shadow:0 14px 28px rgba(97,30,178,.3);transition:.2s}.training-register-browse-btn:hover{background:var(--aces-purple-dark);transform:translateY(-2px)}.training-register-panel{min-width:0;padding:36px 42px 30px}.training-register-heading{display:flex;gap:24px;align-items:center;margin-bottom:30px;padding-bottom:24px;border-bottom:1px solid #ece8f0}.training-register-form-logo{width:150px;max-height:76px;object-fit:contain}.training-register-kicker{display:block;margin-bottom:6px;color:var(--aces-purple);font-size:13px;font-weight:900;letter-spacing:.08em;text-transform:uppercase}.training-register-panel h1{margin:0;color:var(--aces-dark);font-size:clamp(34px,3vw,48px);font-weight:900;line-height:1;letter-spacing:-.04em}.training-register-heading p{max-width:720px;margin:10px 0 0;color:var(--aces-muted);font-size:14px;font-weight:600;line-height:1.55}.training-register-columns{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:30px}.training-register-column{min-width:0}.training-register-column.child-column{padding-left:30px;border-left:1px solid #ece8f0}.training-form-section-title{display:flex;align-items:center;gap:8px;margin-bottom:18px;color:var(--aces-purple);font-size:12px;font-weight:900;letter-spacing:.12em;text-transform:uppercase}.training-field{min-width:0;margin-bottom:17px}.training-field label{display:block;margin-bottom:7px;color:#475467;font-size:13px;font-weight:800}.training-field label span{color:var(--aces-purple)}.training-field-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:14px}.training-input-wrap{width:100%;min-width:0;min-height:49px;padding:0 14px;border:1px solid #d9dee7;border-radius:13px;background:#fff;display:flex;align-items:center;gap:11px;transition:.2s}.training-input-wrap:focus-within{border-color:var(--aces-purple);box-shadow:0 0 0 3px rgba(97,30,178,.1)}.training-input-wrap>i{flex:0 0 17px;color:#8b95a5;text-align:center}.training-input-wrap input{flex:1;width:100%;min-width:0;height:47px;border:0;outline:0;background:transparent;color:#111827;font-size:14px;font-weight:700}.training-input-wrap input::placeholder{color:#98a2b3;font-weight:500}.training-password-toggle{flex:0 0 auto;border:0;background:transparent;color:#8b95a5;padding:5px}.training-register-actions{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-top:28px;padding-top:22px;border-top:1px solid #ece8f0}.training-register-back-btn,.training-register-submit-btn{min-height:50px;padding:12px 20px;border-radius:999px;display:inline-flex;align-items:center;justify-content:center;gap:10px;text-decoration:none;font-size:15px;font-weight:900;transition:.2s}.training-register-back-btn{border:1.5px solid var(--aces-dark);background:#fff;color:var(--aces-dark)!important}.training-register-back-btn:hover{background:var(--aces-dark);color:#fff!important}.training-register-submit-btn{border:0;background:var(--aces-purple);color:#fff;box-shadow:0 12px 24px rgba(97,30,178,.22)}.training-register-submit-btn:hover{background:var(--aces-purple-dark);transform:translateY(-1px)}.training-register-switch{margin:20px 0 0;text-align:center;color:var(--aces-muted);font-size:13px;font-weight:700}.training-register-switch a{color:var(--aces-purple);font-weight:900;text-decoration:none}@media(max-width:1180px){.training-register-card{grid-template-columns:minmax(280px,32%) minmax(0,1fr)}.training-register-panel{padding:32px 28px}.training-register-columns{gap:22px}.training-register-column.child-column{padding-left:22px}.training-field-grid{grid-template-columns:1fr}}@media(max-width:900px){.training-register-page{padding:26px 14px 52px}.training-register-card{grid-template-columns:1fr}.training-register-visual{min-height:420px}.training-register-visual-content{padding:30px}.training-register-panel{padding:30px 24px}.training-register-heading{align-items:flex-start;flex-direction:column;gap:12px}.training-register-form-logo{width:180px}.training-register-columns{grid-template-columns:1fr}.training-register-column.child-column{padding:24px 0 0;border-left:0;border-top:1px solid #ece8f0}.training-field-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}@media(max-width:620px){.training-register-card{border-radius:20px}.training-register-visual{min-height:350px}.training-register-visual-content{padding:24px}.training-register-visual h2{font-size:46px}.training-register-panel{padding:26px 18px}.training-field-grid,.training-register-actions{grid-template-columns:1fr}.training-register-heading{margin-bottom:24px}.training-register-form-logo{width:160px}}
</style>

<script>
document.addEventListener('DOMContentLoaded',function(){document.querySelectorAll('.training-password-toggle').forEach(function(button){button.addEventListener('click',function(){const input=document.getElementById(button.dataset.target);if(!input)return;const show=input.type==='password';input.type=show?'text':'password';const icon=button.querySelector('i');if(icon){icon.classList.toggle('fa-eye',!show);icon.classList.toggle('fa-eye-slash',show)}button.setAttribute('aria-label',show?'Hide password':'Show password')})})});
</script>
@endsection
