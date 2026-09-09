@extends('layouts.app')

@section('title', 'Tryouts & New Players - ACES Lacrosse')

@section('content')
    <style>
        /* ---- Tournaments Section (exact look) ---- */
        .tournaments-section {
            max-width: 1250px;
            margin: 100px auto;
            padding: 0 20px;

        }

.flyer-section {
    width: 100%;
    max-width: 1250px;
    margin: 0 auto;
    padding: 0 20px;
}

.flyer-section a {
    display: block;
}

.flyer-section .banner-image {
    display: block;
    width: 100%;
    height: auto;
    object-fit: contain;
}

        .tournaments-section h2 {
            text-align: center;
            font-size: 42px;
            font-weight: 900;
            color: #611eb2;
            text-transform: uppercase;
            margin-bottom: 60px;
        }

        /* Card layout */
        .tournament-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
            gap: 40px;
        }

        /* Individual card */
        .tournament-card {
            position: relative;
            background: #fff;
            border-radius: 20px;
            padding: 28px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        /* ===== GRADIENT BORDER (TOP ONLY) ===== */
        .tournament-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            height: 6px;
            width: 100%;
            background: linear-gradient(to right, #4c1d95, #7c3aed);
            border-top-left-radius: 20px;
            border-top-right-radius: 20px;
        }

        .tournament-card::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 6px;
            background: var(--border-gradient);

        }

        /* ===== LEFT BORDER (color changes by class) ===== */
        .tournament-card::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 6px;
            height: 100%;
            background: var(--left-color);
            border-bottom-left-radius: 20px;
            border-top-left-radius: 20px;

        }


        /* ===== GLOW DOT ===== */
        .corner-dot {
            position: absolute;
            top: 12px;
            right: 12px;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background: var(--dot-color);
            box-shadow: 0 0 10px var(--dot-color);
        }

        /* Hover effect */
        .tournament-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 14px 35px rgba(0, 0, 0, 0.15);
        }

        /* Card Title */
        .tournament-card h3 {
            font-size: 17px;
            font-weight: 800;
            color: #3e007b;
            margin-bottom: 20px;
        }

        /* Date & location fields */
        .tournament-info {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 15px;
            color: #444;
            margin-bottom: 10px;
        }

        /* Rounded date badge */
        .date-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #f3f1ff;
            color: #611eb2;
            padding: 6px 14px;
            border-radius: 20px;
            border: 1px solid #c0c0c0ff;
            font-weight: 600;
            font-size: 14px;
        }

        /* Gradient button */
        .tournament-btn {
            display: block;
            text-align: center;
            width: 100%;
            background: linear-gradient(to right, #4c1d95, #7c3aed);
            color: #fff;
            border-radius: 30px;
            padding: 10px 0;
            margin-top: 18px;
            font-weight: 700;
            font-size: 14px;
            text-transform: uppercase;
            text-decoration: none;
            box-shadow: 0 3px 10px rgba(155, 52, 239, 0.4);
        }

        /* ===== COLOR THEMES ===== */
        .tournament-card.pink {
            --left-color: #ff3e9d;
            --dot-color: #ff3e9d;
        }

        .tournament-card.blue {
            --left-color: #1dbcd4ff;
            --dot-color: #1dbcd4ff;
        }

        .tournament-card.green {
            --left-color: #4ade80;
            --dot-color: #4ade80;
        }

        .tournament-card.orange {
            --left-color: #f59e0b;
            --dot-color: #f59e0b;
        }

        .tournament-card.violet {
            --left-color: #9333ea;
            --dot-color: #9333ea;
        }

        /* --- On hover: fade in + pulse --- */
        .tournament-card:hover .corner-dot {
            opacity: 1;
            transform: scale(1);
            animation: pulse 1s ease-in-out infinite;
        }

        /* --- Pulse animation --- */
        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 2px rgba(0, 0, 0, 0.25);
            }

            50% {
                box-shadow: 0 0 0 5px rgba(0, 0, 0, 0.25);
            }

            100% {
                box-shadow: 0 0 0 8px rgba(177, 176, 176, 0.51);
            }
        }
@media screen and (min-width: 2560px) {
    .container, .container-lg, .container-md, .container-sm, .container-xl, .container-xxl {
        max-width: 230rem;
    }
    .evaluation-content {
        max-width: 230rem;
    }
    .evaluation-section h1 {
        font-size: 10rem !important
    }
    .evaluation-section p {
        font-size: 5rem !important;
    }
    .assessment-box h2 {
        font-size: 5rem;
    }
    .tournaments-section {
        max-width: 230rem;
    }
    .tournaments-section h2 {
        font-size: 10rem;
    }
    .tournament-card h3 {
        font-size: 6rem;
    }

    .corner-dot {
        width: 4rem;
        height: 5rem;
    }

    .date-badge {
        font-size: 4rem;
    }

    .tournament-btn {
        font-size: 4rem;
    }
    .tournament-info {
        font-size: 4rem;
    }

}
@media (min-width: 768px) {
    .flyer-section {
        max-width: 720px;
    }
}

@media (min-width: 992px) {
    .flyer-section {
        max-width: 960px;
    }
}

@media (min-width: 1200px) {
    .flyer-section {
        max-width: 1140px;
    }
}

@media (min-width: 1400px) {
    .flyer-section {
        max-width: 1250px;
    }
}
.btn-register-flyer {
    display: inline-block !important;
    width: auto !important;
    padding: 8px 22px;
    border-radius: 20px;
    background: linear-gradient(90deg, #4c1d95, #7c3aed);
    color: #fff !important;
    font-size: 12px;
    font-weight: 700;
    line-height: 1.2;
    text-transform: uppercase;
    text-decoration: none;
    box-shadow: 0 4px 10px rgba(97, 30, 178, 0.3);
}


    </style>
    <!-- Evaluation Section -->
    <section class="evaluation-section">
        <div class="evaluation-overlay">
            <img src="{{ asset('public/assets/images/aces-evaluation-bg.jpg') }}" alt="ACES EVALUATION">
        </div>
        <div class="evaluation-content">
            <h1>ACES EVALUATION<br>2026</h1>
            <p>Are you interested in joining the ACES for training 2026 but can't make out tryout?</p>

            <div class="assessment-box mt-4">
                <h2>ACES ASSESSMENT</h2>
                <p>Please Register for a Group Assessment and<br>we'll coordinate a time for evaluation</p>

            </div>
            <!-- Register Button below the assessment box -->
            <div class="mt-3">
                <a href=" https://registration.teamsnap.com/form/72735" class="btn btn-register" target="_blank"
                    rel="noopener">Register Now</a>
            </div>
        </div>
    </section>
    {{-- <section class="flyer-section">
        <a href="https://registration.teamsnap.com/form/68570"
        target="_blank"
        rel="noopener">
            <img src="{{ asset('public/assets/images/Aces-Winter-Tryouts-web.png') }}"
                class="banner-image"
                alt="ACES Pinnacle Winter Tryouts">
        </a>
        <div class="text-center mt-3 mb-4">
            <a href="https://registration.teamsnap.com/form/68570"
                class="btn btn-register-flyer"
                target="_blank"
                rel="noopener">
                Register Now
            </a>
        </div>
    </section> --}}
    {{-- <section class="tournaments-section">
        <h2>TOURNAMENTS</h2>

        <div class="tournament-grid">
            <!-- 1 -->
            <div class="tournament-card pink">
                <div class="corner-dot"></div>
                <h3>SANTA BARBARA FALL BRAWL</h3>
                <div class="tournament-info date-badge">📅 October 25–26</div>
                <div class="tournament-info">📍 Santa Barbara Polo Club</div>
                <a href="#" class="tournament-btn">VARSITY, JV ELITE</a>
            </div>

            <!-- 2 -->
            <div class="tournament-card blue">
                <div class="corner-dot"></div>
                <h3>HALLOWEEN HACKFEST</h3>
                <div class="tournament-info date-badge">📅 November 1–2</div>
                <div class="tournament-info">📍 Kirigan Cellars</div>
                <a href="#" class="tournament-btn">14U, 12U, 10U</a>
            </div>

            <!-- 3 -->
            <div class="tournament-card green">
                <div class="corner-dot"></div>
                <h3>SACTOWN SIXES</h3>
                <div class="tournament-info date-badge">📅 December 6–7</div>
                <div class="tournament-info">📍 Bartholomew Sports Park</div>
                <a href="#" class="tournament-btn">VARSITY, JV ELITE, U14, U12, U10</a>
            </div>

            <!-- 4 -->
            <div class="tournament-card orange">
                <div class="corner-dot"></div>
                <h3>GOLD RUSH</h3>
                <div class="tournament-info date-badge">📅 December 13–14</div>
                <div class="tournament-info">📍 Petaluma Community Sports Fields</div>
                <a href="#" class="tournament-btn">JV ELITE, 14U, 10U</a>
            </div>

            <!-- 5 -->
            <div class="tournament-card violet">
                <div class="corner-dot"></div>
                <h3>KING'S SHOWCASE</h3>
                <div class="tournament-info date-badge">📅 January 24–25</div>
                <div class="tournament-info">📍 Beach Chalet Fields, San Francisco</div>
                <a href="#" class="tournament-btn">VARSITY, JV ELITE, U14, U12, U10</a>
            </div>
        </div>
    </section> --}}
    {{-- <section class="tournaments-section">
    <h2>SUMMER TOURNAMENTS</h2>

    <div class="tournament-grid">
        <!-- 1 -->
        <div class="tournament-card pink">
        <div class="corner-dot"></div>
        <h3>BATTLE OF THE BAY</h3>
        <div class="tournament-info date-badge">📅 June 06–07</div>
        <div class="tournament-info">📍 Golden Gate Park Polo Field</div>
        <a href="#" class="tournament-btn">VARSITY, JV ELITE, U14, U12, U10</a>
        </div>

        <!-- 2 -->
        <div class="tournament-card blue">
        <div class="corner-dot"></div>
        <h3>ROGUE VALLEY RISING</h3>
        <div class="tournament-info date-badge">📅 June 27–28</div>
        <div class="tournament-info">📍 Medford, Oregon</div>
        <a href="#" class="tournament-btn">VARSITY, JV ELITE, U14, U12, U10</a>
        </div>

        <!-- 3 -->
        <div class="tournament-card green">
        <div class="corner-dot"></div>
        <h3>GRAPEVINE CLASSIC</h3>
        <div class="tournament-info date-badge">📅 July 11–12</div>
        <div class="tournament-info">📍 A Place To Play</div>
        <a href="#" class="tournament-btn">VARSITY, JV ELITE, U14, U12, U10</a>
        </div>
    </div>
    </section> --}}
@endsection
