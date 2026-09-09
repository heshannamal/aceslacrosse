@extends('layouts.app')

@section('title', 'Create Training Account | Alcatraz Outlaws')

@section('content')
<div class="training-register-page">
    <div class="training-register-shell">
        @if(session('success'))
            <div class="training-register-alert success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="training-register-alert error">{{ session('error') }}</div>
        @endif

        @if($errors->any())
            <div class="training-register-alert error">
                <strong>Please check the form.</strong>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <div class="training-register-card">
            <section class="training-register-visual">
                <div class="training-register-overlay"></div>
                <div class="training-register-visual-content">
                    <img src="{{ asset('images/logo.png') }}" alt="Alcatraz Outlaws" class="training-register-brand">

                    <div>
                        <span class="training-register-pill">CREATE ACCOUNT</span>
                        <h2>Train Hard.<br>Play Harder.</h2>
                        <p>Create your Parent 1 Training account and add your player.</p>
                        <a href="{{ route('em.customer.index') }}" class="training-register-browse-btn">
                            Browse Sessions <i class="fa-regular fa-calendar"></i>
                        </a>
                    </div>
                </div>
            </section>

            <section class="training-register-panel">
                <img src="{{ asset('images/logo.png') }}" alt="Alcatraz Outlaws" class="training-register-form-logo">

                <h1>Create Your Training Account</h1>
                <p class="training-register-intro">
                    Add your Parent 1 account details and your first player's details.
                </p>

                <form method="POST" action="{{ route('em.customer.register.submit') }}" id="trainingRegisterForm">
                    @csrf

                    <div class="training-register-columns">
                        <div class="training-register-column">
                            <div class="training-form-section-title">Parent Details</div>

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
                                        <input id="password" type="password" name="password" autocomplete="new-password" required>
                                        <button type="button" class="training-password-toggle" data-target="password" aria-label="Show password">
                                            <i class="fa-regular fa-eye"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="training-field mb-0">
                                    <label for="password_confirmation">Confirm Password <span>*</span></label>
                                    <div class="training-input-wrap password-wrap">
                                        <i class="fa-solid fa-lock"></i>
                                        <input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password" required>
                                        <button type="button" class="training-password-toggle" data-target="password_confirmation" aria-label="Show password">
                                            <i class="fa-regular fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @include('pages.customer_sessions.register-child-fields')
                    </div>

                    <div class="training-register-actions">
                        <a href="{{ route('em.customer.index') }}" class="training-register-back-btn">
                            <i class="fa-solid fa-arrow-left"></i> Back
                        </a>
                        <button type="submit" class="training-register-submit-btn">
                            Create Account <i class="fa-solid fa-user-plus"></i>
                        </button>
                    </div>
                </form>

                <p class="training-register-switch">
                    Already have a Training account?
                    <a href="{{ route('em.customer.login') }}">Login</a>
                </p>
            </section>
        </div>
    </div>
</div>

<style>
    .training-register-page {
        --outlaws-red: #f3282c;
        --outlaws-dark: #07101f;
        --outlaws-muted: #667085;
        min-height: calc(100vh - 80px);
        width: 100%;
        padding: clamp(24px, 3vw, 44px) clamp(12px, 2vw, 22px) clamp(48px, 5vw, 70px);
        overflow-x: hidden;
        background: radial-gradient(circle at 7% 8%, rgba(243,40,44,.08), transparent 28%), linear-gradient(180deg,#fff 0%,#fbfbfc 100%);
        font-family: 'Karla', Arial, sans-serif;
    }

    .training-register-page,
    .training-register-page *,
    .training-register-page *::before,
    .training-register-page *::after {
        box-sizing: border-box;
    }

    .training-register-shell {
        width: 100%;
        max-width: 1580px;
        min-width: 0;
        margin: 0 auto;
    }

    .training-register-alert {
        margin-bottom: 16px;
        padding: 13px 18px;
        border-radius: 12px;
        font-weight: 700;
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        align-items: center;
    }

    .training-register-alert.success { color:#166534; background:#ecfdf3; border:1px solid #bbf7d0; }
    .training-register-alert.error { color:#991b1b; background:#fff1f2; border:1px solid #fecdd3; }

    .training-register-card {
        width: 100%;
        min-width: 0;
        display: grid;
        grid-template-columns: minmax(300px, 34%) minmax(0, 1fr);
        overflow: hidden;
        background: #fff;
        border-radius: 28px;
        border: 1px solid #eaecf0;
        box-shadow: 0 26px 70px rgba(15,23,42,.14);
    }

    .training-register-visual {
        position: relative;
        min-width: 0;
        min-height: 760px;
        background: url('{{ asset('bay-area-lacrosse-academy-images/1.jpg') }}') center/cover no-repeat;
    }

    .training-register-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg,rgba(2,8,18,.28),rgba(2,8,18,.58) 48%,rgba(0,0,0,.92));
    }

    .training-register-visual-content {
        position: relative;
        z-index: 2;
        height: 100%;
        min-height: inherit;
        min-width: 0;
        padding: clamp(28px, 3vw, 44px);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .training-register-brand {
        width: min(205px, 72%);
        max-height: 96px;
        object-fit: contain;
        object-position: left center;
        filter: drop-shadow(0 4px 10px rgba(0,0,0,.25));
    }

    .training-register-pill {
        display: inline-flex;
        max-width: 100%;
        padding: 9px 16px;
        border: 1px solid rgba(243,40,44,.9);
        border-radius: 999px;
        background: rgba(9,15,27,.42);
        color: #fff;
        font-size: 12px;
        font-weight: 900;
        letter-spacing: .18em;
    }

    .training-register-visual h2 {
        margin: 20px 0 18px;
        color: #fff;
        font-family: 'Archivo Black','Karla',Arial,sans-serif;
        font-size: clamp(42px, 4vw, 68px);
        line-height: .92;
        letter-spacing: -.06em;
        overflow-wrap: anywhere;
    }

    .training-register-visual p {
        max-width: 480px;
        color: rgba(255,255,255,.78);
        font-weight: 700;
    }

    .training-register-browse-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        max-width: 100%;
        margin-top: 14px;
        padding: 14px 22px;
        border-radius: 999px;
        background: var(--outlaws-red);
        color: #fff!important;
        font-weight: 900;
        text-decoration: none;
        box-shadow: 0 14px 26px rgba(243,40,44,.27);
        white-space: normal;
        text-align: center;
    }

    .training-register-panel {
        min-width: 0;
        padding: clamp(28px, 2.7vw, 44px) clamp(24px, 3vw, 44px) 30px;
        background: #fff;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .training-register-form-logo {
        width: min(190px, 55%);
        max-height: 80px;
        object-fit: contain;
        object-position: left center;
        margin-bottom: 20px;
    }

    .training-register-panel h1 {
        margin: 0;
        color: var(--outlaws-dark);
        font-family: 'Archivo Black','Karla',Arial,sans-serif;
        font-size: clamp(32px, 3vw, 52px);
        line-height: .98;
        letter-spacing: -.055em;
        overflow-wrap: anywhere;
    }

    .training-register-intro {
        margin: 12px 0 24px;
        color: var(--outlaws-muted);
        font-size: 15px;
        font-weight: 600;
    }

    #trainingRegisterForm,
    .training-register-columns,
    .training-register-column,
    .training-field,
    .training-field-grid,
    .training-input-wrap {
        min-width: 0;
    }

    .training-register-columns {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: clamp(20px, 2.4vw, 34px);
    }

    .training-register-column.child-column {
        padding-left: clamp(20px, 2.2vw, 32px);
        border-left: 1px solid #e4e7ec;
    }

    .training-form-section-title {
        margin-bottom: 18px;
        color: #667085;
        font-size: 12px;
        font-weight: 900;
        letter-spacing: .15em;
        text-transform: uppercase;
    }

    .training-field { margin-bottom: 17px; }

    .training-field label {
        display: block;
        margin-bottom: 7px;
        color: #475467;
        font-size: 13px;
        font-weight: 800;
    }

    .training-field label span { color: var(--outlaws-red); }

    .training-field-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
    }

    .training-input-wrap {
        width: 100%;
        min-height: 48px;
        padding: 0 14px;
        border: 1px solid #d9dee7;
        border-radius: 13px;
        background: #fff;
        display: flex;
        align-items: center;
        gap: 11px;
        transition: .2s ease;
    }

    .training-input-wrap:focus-within {
        border-color: var(--outlaws-red);
        box-shadow: 0 0 0 3px rgba(243,40,44,.1);
    }

    .training-input-wrap>i {
        flex: 0 0 17px;
        width: 17px;
        color: #8b95a5;
        text-align: center;
    }

    .training-input-wrap input {
        flex: 1 1 auto;
        width: 100%;
        min-width: 0;
        max-width: 100%;
        height: 46px;
        border: 0;
        outline: 0;
        background: transparent;
        color: #111827;
        font-size: 14px;
        font-weight: 700;
    }

    .training-password-toggle {
        flex: 0 0 auto;
        border: 0;
        background: transparent;
        color: #8b95a5;
        padding: 5px;
    }

    .training-register-actions {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
        margin-top: 24px;
        padding-top: 20px;
        border-top: 1px solid #e4e7ec;
    }

    .training-register-back-btn,
    .training-register-submit-btn {
        width: 100%;
        min-width: 0;
        min-height: 50px;
        padding: 11px 18px;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        text-decoration: none;
        text-align: center;
        font-size: 15px;
        font-weight: 900;
        transition: .2s ease;
    }

    .training-register-back-btn { border:1.5px solid #111827; background:#fff; color:#111827!important; }
    .training-register-submit-btn { border:0; background:var(--outlaws-red); color:#fff; box-shadow:0 12px 24px rgba(243,40,44,.22); }

    .training-register-switch {
        margin: 20px 0 0;
        text-align: center;
        color: #667085;
        font-size: 13px;
        font-weight: 700;
    }

    .training-register-switch a { color:var(--outlaws-red); text-decoration:none; font-weight:900; }

    /* Large and standard desktop widths. Keep the hero and form side-by-side,
       but progressively reduce spacing before the form becomes cramped. */
    @media (max-width: 1440px) {
        .training-register-card { grid-template-columns: minmax(290px, 31%) minmax(0, 1fr); }
        .training-register-panel { padding-inline: 30px; }
        .training-register-columns { gap: 22px; }
        .training-register-column.child-column { padding-left: 22px; }
    }

    /* Smaller desktops / laptops: keep the hero, stack Parent and Child inside
       the form panel so fields never become narrow or overflow. */
    @media (max-width: 1240px) {
        .training-register-card { grid-template-columns: minmax(280px, 34%) minmax(0, 1fr); }
        .training-register-columns { grid-template-columns: 1fr; gap: 24px; }
        .training-register-column.child-column {
            padding: 24px 0 0;
            border-left: 0;
            border-top: 1px solid #e4e7ec;
        }
        .training-register-visual h2 { font-size: clamp(40px, 4.5vw, 58px); }
    }

    /* Compact desktop / tablet landscape: switch the whole card to one column. */
    @media (max-width: 1024px) {
        .training-register-card { grid-template-columns: 1fr; }
        .training-register-visual { min-height: 360px; }
        .training-register-visual-content { min-height: 360px; }
        .training-register-panel { padding: 34px 30px 30px; }
        .training-register-columns { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .training-register-column.child-column {
            padding: 0 0 0 24px;
            border-top: 0;
            border-left: 1px solid #e4e7ec;
        }
    }

    @media (max-width: 820px) {
        .training-register-page { padding: 24px 14px 50px; }
        .training-register-columns { grid-template-columns: 1fr; gap: 24px; }
        .training-register-column.child-column {
            padding: 24px 0 0;
            border-left: 0;
            border-top: 1px solid #e4e7ec;
        }
        .training-register-panel { padding: 30px 24px; }
        .training-register-visual-content { padding: 28px; }
        .training-register-visual h2 { font-size: 48px; }
    }

    @media (max-width: 620px) {
        .training-field-grid { grid-template-columns: 1fr; gap: 0; }
        .training-register-actions { grid-template-columns: 1fr; }
        .training-register-panel h1 { font-size: 36px; }
        .training-register-form-logo { width: 170px; }
        .training-register-visual { min-height: 330px; }
        .training-register-visual-content { min-height: 330px; }
    }

    @media (max-width: 480px) {
        .training-register-page { padding-inline: 8px; }
        .training-register-card { border-radius: 20px; }
        .training-register-panel { padding: 26px 18px; }
        .training-register-visual-content { padding: 22px; }
        .training-register-visual h2 { font-size: 42px; }
        .training-register-browse-btn { width: 100%; }
    }

    /* Short desktop screens: reduce wasted vertical space without changing
       form structure. */
    @media (min-width: 1025px) and (max-height: 820px) {
        .training-register-page { padding-top: 24px; padding-bottom: 34px; }
        .training-register-visual { min-height: 680px; }
        .training-register-panel { padding-top: 28px; padding-bottom: 24px; }
        .training-register-form-logo { margin-bottom: 14px; }
        .training-register-intro { margin-bottom: 18px; }
        .training-field { margin-bottom: 13px; }
        .training-register-actions { margin-top: 18px; padding-top: 16px; }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.training-password-toggle').forEach(function (button) {
        button.addEventListener('click', function () {
            const input = document.getElementById(button.dataset.target);
            if (!input) return;
            const hidden = input.type === 'password';
            input.type = hidden ? 'text' : 'password';
            const icon = button.querySelector('i');
            if (icon) {
                icon.classList.toggle('fa-eye', !hidden);
                icon.classList.toggle('fa-eye-slash', hidden);
            }
        });
    });
});
</script>
@endsection