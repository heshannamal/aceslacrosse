@extends('layouts.app')

@section('title', 'Contact Us - ACES Lacrosse')

@section('content')

<style>
/* =========================================================
   HERO SECTION
========================================================= */
.hero-section {
    position: relative;
    width: 100%;
    min-height: 400px;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #111;
}

.hero-section img {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.hero-overlay {
    position: absolute;
    inset: 0;
    z-index: 1;
    background: rgba(0, 0, 0, 0.45);
    display: flex;
    align-items: center;
    justify-content: center;
}

.hero-overlay h1 {
    margin: 0;
    color: #ffffff;
    font-size: 56px;
    font-weight: 800;
    line-height: 1.1;
    text-align: center;
    text-transform: uppercase;
    letter-spacing: 2px;
    text-shadow: 0 3px 10px rgba(0, 0, 0, 0.45);
}


/* =========================================================
   CONTACT SECTION
========================================================= */
.contact-section {
    padding: 80px 20px 100px;
    background: #f8f7fb;
}

.contact-section .contact-container {
    max-width: 950px;
    margin: 0 auto;
}

.contact-header {
    max-width: 650px;
    margin: 0 auto 40px;
    text-align: center;
}

.contact-header .small-title {
    display: inline-block;
    margin-bottom: 10px;
    color: #6a0dad;
    font-size: 28px;
    font-weight: 700;
    letter-spacing: 2px;
    text-transform: uppercase;
}

.contact-header h2 {
    margin: 0 0 12px;
    color: #17121e;
    font-size: 42px;
    font-weight: 800;
    line-height: 1.2;
}

.contact-header p {
    margin: 0;
    color: #747078;
    font-size: 15px;
    line-height: 1.7;
}


/* =========================================================
   CONTACT CARD
========================================================= */
.contact-card {
    position: relative;
    padding: 45px;
    background: #ffffff;
    border: 1px solid #ebe7ef;
    border-radius: 16px;
    box-shadow: 0 18px 50px rgba(55, 25, 75, 0.08);
}

.contact-card::before {
    content: "";
    position: absolute;
    top: 0;
    left: 45px;
    right: 45px;
    height: 4px;
    background: #6a0dad;
    border-radius: 0 0 5px 5px;
}


/* =========================================================
   FORM
========================================================= */
.contact-form {
    width: 100%;
}

.form-row {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 22px;
    margin-bottom: 22px;
}

.form-group {
    width: 100%;
}

.form-group.full-width {
    grid-column: 1 / -1;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    color: #291a35;
    font-size: 13px;
    font-weight: 700;
}

.form-group label span {
    margin-left: 2px;
    color: #d63344;
}

.form-control-contact {
    width: 100%;
    height: 52px;
    padding: 0 15px;
    border: 1px solid #d7d1dd;
    border-radius: 7px;
    background: #ffffff;
    color: #2c2530;
    font-size: 15px;
    font-family: inherit;
    outline: none;
    box-sizing: border-box;
    transition:
        border-color 0.2s ease,
        box-shadow 0.2s ease;
}

textarea.form-control-contact {
    min-height: 150px;
    height: auto;
    padding: 14px 15px;
    resize: vertical;
    line-height: 1.6;
}

.form-control-contact::placeholder {
    color: #aaa2af;
}

.form-control-contact:hover {
    border-color: #aa90ba;
}

.form-control-contact:focus {
    border-color: #6a0dad;
    box-shadow: 0 0 0 4px rgba(106, 13, 173, 0.08);
}

.form-control-contact.is-invalid {
    border-color: #dc3545;
}

.field-error {
    display: block;
    margin-top: 6px;
    color: #dc3545;
    font-size: 12px;
}


/* =========================================================
   ALERT
========================================================= */
.contact-alert {
    margin-bottom: 25px;
    padding: 14px 18px;
    border-radius: 7px;
    font-size: 14px;
}

.contact-alert-success {
    color: #176b38;
    background: #edf9f1;
    border: 1px solid #c0e8cc;
}

.contact-alert-error {
    color: #9b2934;
    background: #fff1f2;
    border: 1px solid #efc5c9;
}


/* =========================================================
   BUTTON
========================================================= */
.submit-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    min-width: 180px;
    height: 52px;
    margin-top: 8px;
    padding: 0 30px;
    background: #6a0dad;
    color: #ffffff;
    border: 1px solid #6a0dad;
    border-radius: 7px;
    font-size: 15px;
    font-weight: 700;
    cursor: pointer;
    transition:
        background-color 0.2s ease,
        transform 0.2s ease,
        box-shadow 0.2s ease;
}

.submit-btn:hover {
    background: #520a88;
    border-color: #520a88;
    color: #ffffff;
    transform: translateY(-2px);
    box-shadow: 0 8px 18px rgba(106, 13, 173, 0.22);
}

.submit-btn svg {
    width: 17px;
    height: 17px;
}

.form-note {
    margin-top: 12px;
    color: #98919d;
    font-size: 12px;
}


/* =========================================================
   RESPONSIVE
========================================================= */
@media (max-width: 768px) {
    .hero-section {
        min-height: 260px;
    }

    .hero-overlay h1 {
        font-size: 36px;
    }

    .contact-section {
        padding: 55px 15px 70px;
    }

    .contact-header h2 {
        font-size: 34px;
    }

    .contact-card {
        padding: 30px 22px;
    }

    .contact-card::before {
        left: 22px;
        right: 22px;
    }

    .form-row {
        grid-template-columns: 1fr;
        gap: 20px;
        margin-bottom: 20px;
    }

    .form-group.full-width {
        grid-column: auto;
    }

    .form-control-contact {
        font-size: 16px;
    }

    .submit-btn {
        width: 100%;
    }
}

@media (max-width: 480px) {
    .hero-section {
        min-height: 220px;
    }

    .hero-overlay h1 {
        font-size: 30px;
    }

    .contact-header h2 {
        font-size: 30px;
    }

    .contact-card {
        padding: 28px 18px;
    }

    .contact-card::before {
        left: 18px;
        right: 18px;
    }
}


/* =========================================================
   LARGE SCREEN
========================================================= */
@media screen and (min-width: 2560px) {
    .contact-section .contact-container {
        max-width: 1200px;
    }

    .hero-section {
        min-height: 650px;
    }

    .hero-overlay h1 {
        font-size: 80px;
    }

    .contact-header h2 {
        font-size: 54px;
    }

    .contact-header p {
        font-size: 19px;
    }

    .form-group label {
        font-size: 16px;
    }

    .form-control-contact {
        height: 60px;
        font-size: 17px;
    }

    textarea.form-control-contact {
        min-height: 180px;
    }

    .submit-btn {
        height: 60px;
        font-size: 17px;
    }
}
</style>


{{-- HERO --}}
<section class="hero-section">

    <img
        src="{{ asset('public/assets/images/aces contact us.jpg') }}"
        alt="ACES Lacrosse Contact Us"
    >

    <div class="hero-overlay">
        <h1>Contact Us</h1>
    </div>

</section>


{{-- CONTACT --}}
<section class="contact-section">

    <div class="contact-container">

        <div class="contact-header">

            <span class="small-title">
                Get In Touch
            </span>
{{--
            <h2>
                Contact ACES Lacrosse
            </h2> --}}

            <p>
                Have questions about ACES Lacrosse, teams, practices,
                tryouts or upcoming programs? Send us a message below.
            </p>

        </div>


        <div class="contact-card">

            {{-- SUCCESS --}}
            @if(session('success'))
                <div class="contact-alert contact-alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="contact-alert contact-alert-error">
                    {{ session('error') }}
                </div>
            @endif

            {{-- GENERAL VALIDATION ERROR --}}
            @if($errors->any())
                <div class="contact-alert contact-alert-error">
                    Please check the highlighted fields below.
                </div>
            @endif


            <form
                class="contact-form"
                method="POST"
                action="{{ route('contact.submit') }}"
            >

                @csrf


                {{-- ROW 1 --}}
                <div class="form-row">

                    {{-- Player --}}
                    <div class="form-group">

                        <label for="player_name">
                            Player Full Name
                            <span>*</span>
                        </label>

                        <input
                            type="text"
                            id="player_name"
                            name="player_name"
                            value="{{ old('player_name') }}"
                            class="form-control-contact @error('player_name') is-invalid @enderror"
                            placeholder="Player full name"
                            required
                        >

                        @error('player_name')
                            <span class="field-error">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>


                    {{-- Grad Year --}}
                    <div class="form-group">

                        <label for="grad_year">
                            Grad Year
                            <span>*</span>
                        </label>

                        <input
                            type="number"
                            id="grad_year"
                            name="grad_year"
                            value="{{ old('grad_year') }}"
                            class="form-control-contact @error('grad_year') is-invalid @enderror"
                            placeholder="e.g. 2030"
                            min="2026"
                            max="2050"
                            inputmode="numeric"
                            required
                        >

                        @error('grad_year')
                            <span class="field-error">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>

                </div>


                {{-- ROW 2 --}}
                <div class="form-row">

                    {{-- Parent --}}
                    <div class="form-group">

                        <label for="parent_name">
                            Parent Name
                            <span>*</span>
                        </label>

                        <input
                            type="text"
                            id="parent_name"
                            name="parent_name"
                            value="{{ old('parent_name') }}"
                            class="form-control-contact @error('parent_name') is-invalid @enderror"
                            placeholder="Parent / guardian name"
                            required
                        >

                        @error('parent_name')
                            <span class="field-error">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>


                    {{-- Email --}}
                    <div class="form-group">

                        <label for="email">
                            Email
                            <span>*</span>
                        </label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            class="form-control-contact @error('email') is-invalid @enderror"
                            placeholder="Email address"
                            required
                        >

                        @error('email')
                            <span class="field-error">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>

                </div>


                {{-- ROW 3 --}}
                <div class="form-row">

                    {{-- Phone --}}
                    <div class="form-group">

                        <label for="phone">
                            Phone Number
                            <span>*</span>
                        </label>

                        <input
                            type="tel"
                            id="phone"
                            name="phone"
                            value="{{ old('phone') }}"
                            class="form-control-contact @error('phone') is-invalid @enderror"
                            placeholder="Phone number"
                            required
                        >

                        @error('phone')
                            <span class="field-error">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>


                    {{-- Subject --}}
                    <div class="form-group">

                        <label for="subject">
                            Subject
                            <span>*</span>
                        </label>

                        <input
                            type="text"
                            id="subject"
                            name="subject"
                            value="{{ old('subject') }}"
                            class="form-control-contact @error('subject') is-invalid @enderror"
                            placeholder="Subject"
                            required
                        >

                        @error('subject')
                            <span class="field-error">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>

                </div>


                {{-- MESSAGE --}}
                <div class="form-row">

                    <div class="form-group full-width">

                        <label for="message">
                            Message
                            <span>*</span>
                        </label>

                        <textarea
                            id="user_message"
                            name="user_message"
                            rows="6"
                            class="form-control-contact @error('user_message') is-invalid @enderror"
                            placeholder="How can we help?"
                            required
                        >{{ old('user_message') }}</textarea>

                        @error('user_message')
                            <span class="field-error">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>

                </div>


                <button
                    type="submit"
                    class="submit-btn"
                >

                    Send Message

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            d="M5 12H19M19 12L13 6M19 12L13 18"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />
                    </svg>

                </button>


                <div class="form-note">
                    Fields marked with * are required.
                </div>

            </form>

        </div>

    </div>

</section>

@endsection
