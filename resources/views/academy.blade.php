@extends('layouts.app')

@section('title', 'Academy - ACES Lacrosse')

@section('content')
<style>
    .academy-page {
        --aces-purple: #611eb2;
        --aces-purple-dark: #431178;
        --aces-purple-light: #f6f1fc;
        --aces-text: #171717;
        --aces-muted: #64646d;
        --aces-border: #e8e0f1;
        --aces-white: #ffffff;
        background: #fff;
    }

    .academy-page .hero-section {
        position: relative;
        width: 100%;
        overflow: hidden;
    }

    .academy-page .hero-section img {
        display: block;
        width: 100%;
        height: auto;
        object-fit: cover;
    }

    .academy-page .academy-intro {
        width: min(calc(100% - 40px), 1280px);
        margin: 0 auto;
        padding: 48px 0 24px;
    }

    .academy-page .academy-title {
        margin: 0 0 28px;
        color: var(--aces-purple);
        font-size: clamp(32px, 4vw, 48px);
        font-weight: 900;
        line-height: 1.1;
        text-align: center;
        text-transform: uppercase;
        letter-spacing: -0.02em;
    }

    .academy-page .academy-copy {
        max-width: 1120px;
        margin: 0 auto;
    }

    .academy-page .academy-copy p {
        margin: 0 0 18px;
        color: var(--aces-text);
        font-size: 16px;
        font-weight: 400;
        line-height: 1.8;
        text-align: left;
    }

    .academy-page .academy-copy p:last-child {
        margin-bottom: 0;
    }

    .academy-page .registration-section {
        width: min(calc(100% - 40px), 1280px);
        margin: 34px auto 90px;
    }

    .academy-page .registration-card {
        position: relative;
        overflow: hidden;
        border: 1px solid var(--aces-border);
        border-radius: 28px;
        background: linear-gradient(145deg, #ffffff 0%, #fbf9fe 100%);
        box-shadow: 0 18px 50px rgba(67, 17, 120, 0.10);
    }

    .academy-page .registration-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 7px;
        background: linear-gradient(90deg, #431178 0%, #611eb2 50%, #8c52d8 100%);
    }

    .academy-page .registration-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 28px;
        padding: 38px 40px 28px;
        border-bottom: 1px solid var(--aces-border);
    }

    .academy-page .registration-heading-wrap {
        max-width: 720px;
    }

    .academy-page .registration-kicker {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 10px;
        color: var(--aces-purple);
        font-size: 13px;
        font-weight: 800;
        letter-spacing: 0.12em;
        text-transform: uppercase;
    }

    .academy-page .registration-heading {
        margin: 0 0 8px;
        color: var(--aces-purple-dark);
        font-size: clamp(27px, 3vw, 38px);
        font-weight: 900;
        line-height: 1.15;
    }

    .academy-page .registration-subtitle {
        margin: 0;
        color: var(--aces-muted);
        font-size: 16px;
        line-height: 1.6;
    }

    .academy-page .register-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        min-width: 210px;
        padding: 15px 25px;
        border: 2px solid var(--aces-purple);
        border-radius: 999px;
        background: linear-gradient(135deg, #4a148c 0%, #701fc2 100%);
        color: #fff;
        font-size: 15px;
        font-weight: 800;
        text-decoration: none;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        box-shadow: 0 10px 24px rgba(97, 30, 178, 0.24);
        transition: transform .2s ease, box-shadow .2s ease, background .2s ease;
    }

    .academy-page .register-button:hover,
    .academy-page .register-button:focus {
        transform: translateY(-2px);
        background: linear-gradient(135deg, #3e0e79 0%, #611eb2 100%);
        color: #fff;
        text-decoration: none;
        box-shadow: 0 14px 30px rgba(97, 30, 178, 0.32);
    }

    .academy-page .registration-body {
        display: grid;
        grid-template-columns: minmax(0, 1.4fr) minmax(320px, .8fr);
        gap: 34px;
        padding: 34px 40px 40px;
    }

    .academy-page .info-block-title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0 0 18px;
        color: var(--aces-text);
        font-size: 18px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .academy-page .info-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        border-radius: 10px;
        background: var(--aces-purple-light);
        color: var(--aces-purple);
        font-size: 17px;
    }

    .academy-page .date-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 12px;
    }

    .academy-page .date-card {
        display: flex;
        align-items: center;
        gap: 12px;
        min-height: 86px;
        padding: 15px 16px;
        border: 1px solid var(--aces-border);
        border-radius: 16px;
        background: #fff;
        transition: transform .2s ease, border-color .2s ease, box-shadow .2s ease;
    }

    .academy-page .date-card:hover {
        transform: translateY(-2px);
        border-color: rgba(97, 30, 178, .35);
        box-shadow: 0 10px 20px rgba(67, 17, 120, .08);
    }

    .academy-page .date-month {
        display: flex;
        flex: 0 0 48px;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 48px;
        height: 54px;
        border-radius: 12px;
        background: var(--aces-purple-light);
        color: var(--aces-purple-dark);
        line-height: 1;
    }

    .academy-page .date-month small {
        margin-bottom: 4px;
        font-size: 10px;
        font-weight: 900;
        letter-spacing: .06em;
        text-transform: uppercase;
    }

    .academy-page .date-month strong {
        font-size: 20px;
        font-weight: 900;
    }

    .academy-page .date-meta strong {
        display: block;
        margin-bottom: 3px;
        color: var(--aces-text);
        font-size: 15px;
        font-weight: 800;
    }

    .academy-page .date-meta span {
        color: var(--aces-muted);
        font-size: 13px;
    }

    .academy-page .details-panel {
        height: 100%;
        padding: 24px;
        border-radius: 20px;
        background: linear-gradient(145deg, #4b1188 0%, #611eb2 100%);
        box-shadow: 0 14px 30px rgba(67, 17, 120, .16);
    }

    .academy-page .details-panel .info-block-title {
        color: #fff;
    }

    .academy-page .details-panel .info-icon {
        background: rgba(255,255,255,.14);
        color: #fff;
    }

    .academy-page .team-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
        margin-bottom: 18px;
    }

    .academy-page .team-card {
        display: flex;
        min-height: 66px;
        align-items: center;
        justify-content: center;
        padding: 14px;
        border: 1px solid rgba(255,255,255,.24);
        border-radius: 15px;
        background: rgba(255,255,255,.12);
        color: #fff;
        font-size: 18px;
        font-weight: 900;
        text-align: center;
        backdrop-filter: blur(4px);
    }

    .academy-page .session-details {
        display: grid;
        gap: 10px;
    }

    .academy-page .detail-card {
        display: flex;
        align-items: center;
        gap: 13px;
        min-height: 72px;
        padding: 14px 15px;
        border: 1px solid rgba(255,255,255,.18);
        border-radius: 15px;
        background: rgba(255,255,255,.09);
        color: #fff;
    }

    .academy-page .detail-icon {
        display: inline-flex;
        flex: 0 0 38px;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        border-radius: 11px;
        background: rgba(255,255,255,.14);
        font-size: 17px;
    }

    .academy-page .detail-content {
        min-width: 0;
    }

    .academy-page .detail-label {
        display: block;
        margin-bottom: 2px;
        color: rgba(255,255,255,.68);
        font-size: 11px;
        font-weight: 800;
        letter-spacing: .08em;
        text-transform: uppercase;
    }

    .academy-page .detail-value,
    .academy-page .detail-link {
        color: #fff;
        font-size: 15px;
        font-weight: 800;
        line-height: 1.4;
    }

    .academy-page .detail-link {
        text-decoration: underline;
        text-decoration-thickness: 1px;
        text-underline-offset: 3px;
    }

    .academy-page .detail-link:hover,
    .academy-page .detail-link:focus {
        color: #fff;
        opacity: .88;
    }

    @media (max-width: 900px) {
        .academy-page .registration-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .academy-page .register-button {
            width: 100%;
        }

        .academy-page .registration-body {
            grid-template-columns: 1fr;
        }

        .academy-page .date-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 575px) {
        .academy-page .academy-intro,
        .academy-page .registration-section {
            width: min(calc(100% - 28px), 1280px);
        }

        .academy-page .academy-intro {
            padding-top: 34px;
        }

        .academy-page .academy-title {
            margin-bottom: 22px;
            font-size: 30px;
        }

        .academy-page .academy-copy p {
            font-size: 15px;
            line-height: 1.72;
        }

        .academy-page .registration-section {
            margin-top: 24px;
            margin-bottom: 60px;
        }

        .academy-page .registration-card {
            border-radius: 20px;
        }

        .academy-page .registration-header,
        .academy-page .registration-body {
            padding-left: 20px;
            padding-right: 20px;
        }

        .academy-page .registration-header {
            padding-top: 30px;
        }

        .academy-page .registration-body {
            padding-top: 26px;
            padding-bottom: 28px;
        }

        .academy-page .date-grid {
            grid-template-columns: 1fr;
        }

        .academy-page .team-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media screen and (min-width: 2560px) {
        .academy-page .academy-intro,
        .academy-page .registration-section {
            max-width: 230rem;
        }

        .academy-page .academy-title {
            font-size: 8rem;
        }

        .academy-page .academy-copy {
            max-width: 190rem;
        }

        .academy-page .academy-copy p,
        .academy-page .registration-subtitle {
            font-size: 3rem;
        }

        .academy-page .registration-heading {
            font-size: 6rem;
        }

        .academy-page .registration-kicker,
        .academy-page .register-button,
        .academy-page .info-block-title,
        .academy-page .team-card {
            font-size: 2.5rem;
        }

        .academy-page .date-meta strong,
        .academy-page .detail-value,
        .academy-page .detail-link {
            font-size: 2.2rem;
        }

        .academy-page .date-meta span,
        .academy-page .detail-label {
            font-size: 1.8rem;
        }
    }
</style>

<div class="academy-page">
    <section class="hero-section">
        <img src="{{ asset('public/assets/images/aces two.jpg') }}" alt="ACES Training Academy">
    </section>

    <section class="academy-intro">
        <h1 class="academy-title">ACES TRAINING ACADEMY</h1>

        <div class="academy-copy">
            <p>
                ACES Academy is Sacramento’s premier local training foundation focusing on fundamentals, high-volume reps, skill development, and fun! Each session is designed to enhance athletic ability, technical skill, and overall lacrosse IQ—because no detail is too small when building a player’s game.
            </p>

            <p>
                ACES staff includes current and former professional players, NCAA alumni, and top area coaches, combining over 500 years of playing and coaching expertise. Utilizing a proven curriculum developed by the program directors, our ACES Academy coaches ensure every athlete receives top-tier instruction tailored to their growth.
            </p>

            <p>
                While our primary focus is on foundational development, ACES Academy players gain opportunities to compete in local games and earn call-ups to ACES Elite tournament rosters. We firmly believe in promoting from within. Dozens of ACES Academy alumni have earned spots on ACES Elite and Pinnacle teams and gone on to play college lacrosse, proving that dedication to the basics opens doors to the highest level.
            </p>
        </div>
    </section>

    <section class="registration-section" aria-labelledby="academy-registration-title">
        <div class="registration-card">
            <div class="registration-header">
                <div class="registration-heading-wrap">
                    <div class="registration-kicker">2026 Academy Sessions</div>
                    <h2 class="registration-heading" id="academy-registration-title">Train With ACES This Fall &amp; Winter</h2>
                    <p class="registration-subtitle">
                        Six Sunday training dates for U10 and U12 players. Reserve your player’s spot through TeamSnap.
                    </p>
                </div>

                <a href="https://registration.teamsnap.com/form/77536"
                   class="register-button"
                   target="_blank"
                   rel="noopener noreferrer">
                    Register Now <span aria-hidden="true">→</span>
                </a>
            </div>

            <div class="registration-body">
                <div>
                    <h3 class="info-block-title">
                        <span class="info-icon" aria-hidden="true">▣</span>
                        Sunday Schedule
                    </h3>

                    <div class="date-grid">
                        <div class="date-card">
                            <div class="date-month"><small>Nov</small><strong>8</strong></div>
                            <div class="date-meta"><strong>November 8</strong><span>Sunday</span></div>
                        </div>
                        <div class="date-card">
                            <div class="date-month"><small>Nov</small><strong>15</strong></div>
                            <div class="date-meta"><strong>November 15</strong><span>Sunday</span></div>
                        </div>
                        <div class="date-card">
                            <div class="date-month"><small>Nov</small><strong>22</strong></div>
                            <div class="date-meta"><strong>November 22</strong><span>Sunday</span></div>
                        </div>
                        <div class="date-card">
                            <div class="date-month"><small>Dec</small><strong>6</strong></div>
                            <div class="date-meta"><strong>December 6</strong><span>Sunday</span></div>
                        </div>
                        <div class="date-card">
                            <div class="date-month"><small>Dec</small><strong>13</strong></div>
                            <div class="date-meta"><strong>December 13</strong><span>Sunday</span></div>
                        </div>
                        <div class="date-card">
                            <div class="date-month"><small>Dec</small><strong>20</strong></div>
                            <div class="date-meta"><strong>December 20</strong><span>Sunday</span></div>
                        </div>
                    </div>
                </div>

                <aside class="details-panel">
                    <h3 class="info-block-title">
                        <span class="info-icon" aria-hidden="true">◆</span>
                        Session Details
                    </h3>

                    <div class="team-grid">
                        <div class="team-card">U10</div>
                        <div class="team-card">U12</div>
                    </div>

                    <div class="session-details">
                        <div class="detail-card">
                            <span class="detail-icon" aria-hidden="true">⌖</span>
                            <div class="detail-content">
                                <span class="detail-label">Location</span>
                                <a href="https://maps.app.goo.gl/Zt7KfP5T5oK8pXhU6"
                                   class="detail-link"
                                   target="_blank"
                                   rel="noopener noreferrer">
                                    Mather Sports Complex
                                </a>
                            </div>
                        </div>

                        <div class="detail-card">
                            <span class="detail-icon" aria-hidden="true">◷</span>
                            <div class="detail-content">
                                <span class="detail-label">Time</span>
                                <span class="detail-value">10:00 AM – 11:30 AM</span>
                            </div>
                        </div>

                        <div class="detail-card">
                            <span class="detail-icon" aria-hidden="true">$</span>
                            <div class="detail-content">
                                <span class="detail-label">Cost</span>
                                <span class="detail-value">$395</span>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>
</div>
@endsection
