@extends('layouts.app')

@section('title', 'Academy - ACES Lacrosse')

@section('content')
<style>
/* ---- Hero Section ---- */
.hero-section {
    position: relative;
    height: auto;
    overflow: hidden;
}

.hero-section img {
    width: 100%;
    height: auto;
    object-fit: cover;
    display: block;
}

.hero-section .overlay {
    position: absolute;
    inset: 0;

}
/* ---- AcademyContent ---- */
.academy-section {
    max-width: 80rem;
    margin: 50px auto;
    padding: 0 20px;
}

.academy-section h2 {
    text-align: center;
    font-size: 35px;
    color: #611eb2;
    margin-top: 25px;
    margin-bottom: 15px;
    text-transform: uppercase;
    font-weight: 900;
}

.academy-section p {
    font-size: 16px;
    line-height: 1.7;
    color: #0e0e0eff;
    text-align: justify;
    margin-bottom: 15px;
    font-weight: 400;
}
/* ---- Tournaments Section (exact look) ---- */
.tournaments-section {
    max-width: 1250px;
    margin: 100px auto;
    padding: 0 20px;

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
  0% { box-shadow: 0 0 0 2px rgba(0,0,0,0.25); }
  50% { box-shadow: 0 0 0 5px rgba(0,0,0,0.25); }
  100% { box-shadow: 0 0 0 8px rgba(177, 176, 176, 0.51); }
}
/* ===== TEAM SECTION ===== */
.team-section {
  max-width: 1250px;
  margin: 120px auto;
  padding: 0 20px;
}

.team-container {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 60px;
  flex-wrap: wrap;
}

/* Left content */
.team-content {
  flex: 1 1 500px;
}

.team-content h2 {
  font-size: 42px;
  font-weight: 900;
  color: #611eb2;
  text-transform: uppercase;
  margin-bottom: 35px;
}

/* List styling */
.team-content ul {
  list-style: none;
  padding: 0;
  margin: 0;
}

.team-content li {
  display: flex;
  align-items: flex-start;
  gap: 14px;
  font-size: 18px;
  color: #555555ff;
  margin-bottom: 20px;
  line-height: 1.5;
  font-weight: 700;
}

/* Circle check icon */
.check-icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  background: #4d4d4eff;
  color: #fff;
  font-size: 14px;
  font-weight: 700;
  width: 20px;
  height: 20px;
  border-radius: 50%;
  flex-shrink: 0;
  box-shadow: 0 2px 6px rgba(97, 30, 178, 0.3);
}

/* Right image */
.team-image {
  flex: 1 1 540px;
}

.team-image img {
  width: 100%;
  border-radius: 12px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
}

/* Responsive */
@media (max-width: 900px) {
  .team-container {
    flex-direction: column;
  }
  .team-content h2 {
    text-align: center;
  }
}

/* ===== OFFENSE SECTION ===== */
.offense-section {
  max-width: 1250px;
  margin: 120px auto;
  padding: 0 20px;
}

.offense-container {
  display: flex;
  flex-direction: column;
  gap: 50px;
}

/* Left text column */
.offense-content {
  flex: 1;
}

.offense-content h2 {
  font-size: 42px;
  font-weight: 900;
  color: #611eb2;
  text-transform: uppercase;
  margin-bottom: 35px;
}

.offense-content ul {
  list-style: none;
  padding: 0;
  margin: 0;
}

.offense-content li {
  display: flex;
  align-items: flex-start;
  gap: 14px;
  font-size: 18px;
  color: #333;
  margin-bottom: 18px;
  line-height: 1.6;
}


/* Right image column */
.offense-images {
  display: flex;
  flex-direction: column;
  gap: 40px;
}

.offense-img img {
  width: 100%;
  border-radius: 12px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
}

/* Responsive layout */
@media (min-width: 900px) {
  .offense-container {
    flex-direction: row;
    align-items: flex-start;
  }
  .offense-content {
    flex: 1 1 45%;
  }
  .offense-images {
    flex: 1 1 55%;
  }

}
/* ===== GOALIES & FACE OFF SECTIONS ===== */
.goalies-section,
.faceoff-section {
  max-width: 1250px;
  margin: 120px auto;
  padding: 0 20px;
}

.goalies-container h2,
.faceoff-container h2 {
  font-size: 38px;
  font-weight: 900;
  color: #611eb2;
  text-transform: uppercase;
  margin-bottom: 25px;
}

.goalies-container p,
.faceoff-container p {
  display: flex;
  align-items: flex-start;
  gap: 12px;
  font-size: 18px;
  line-height: 1.6;
  color: #333;
  margin: 0;
}
/* Responsive: Center headers on smaller screens */
@media (max-width: 768px) {
  .offense-content h2,
  .goalies-container h2,
  .faceoff-container h2 {
    text-align: center;
  }

  /* Also center the text block and icon list for a better look */
  .offense-content ul,
  .goalies-container p,
  .faceoff-container p {
    text-align: left;
    margin: 0 auto;
    max-width: 90%;
  }

  /* Center images under the text */
  .offense-images {
    justify-content: center;
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .offense-img img {
    width: 100%;
    max-width: 400px;
  }
}

.evaluation-section {
    max-width: 80rem;
    margin: 50px auto;
}

 @media screen and (min-width: 2560px) {
    .container, .container-lg, .container-md, .container-sm, .container-xl, .container-xxl {
      max-width: 230rem;
    }
    .hero-section {
      position: relative;
      height: auto;
      overflow: hidden;
    }
    .academy-section h2 {
      font-size: 10rem;
    }
    .academy-section {
      max-width: 230rem;
    }
    .academy-section p {
      font-size: 5rem;
    }

    .evaluation-section {
      max-width: 230rem;
    }
    .evaluation-section h1 {
      font-size: 10rem !important;
    }
    .evaluation-section p {
      font-size: 5rem !important;
    }
    .assessment-box h2 {
      font-size: 6rem;
    }

    .evaluation-content {
      max-width: none;
    }

    .tournaments-section {
      max-width: 230rem;

    }

  .tournaments-section h2 {
    font-size: 8rem;
  }

  .tournaments-section {
    max-width: 230rem;
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

</style>

<!-- ✅ Hero Section -->
<section class="hero-section">
    <img src="{{ asset('public/assets/images/aces one.jpg') }}" alt="Our Mission">
    <div class="overlay"></div>
    <div class="hero-text">
    </div>
</section>
<!-- ✅ Academy Content -->
<section class="academy-section">

    <h2>2026 ACES ELITE TRAVEL TEAMS</h2>
    <p>
    Sacramento ACES Elite travel teams are selected on a bi-annual basis with tryouts held in February and August. Elite teams consist of the top players from ACES Academy. Elite teams participate in both local Northern California tournaments as well as some of the best out of state, west coast events.
<br><br>Over the past twelve years, ACES Elite travel teams have won over 60 tournaments and ACES have sent over 160 players to play at the collegiate  level.  ACES Elite travel teams are coached by the most experienced and knowledgeable staff in the Sacramento Valley.
    </p>
</section>

<!-- Evaluation Section -->
{{-- <section class="evaluation-section">
  <div class="evaluation-overlay">
    <img src="{{ asset('public/assets/images/aces-evaluation-bg.jpg') }}" alt="ACES EVALUATION">
  </div>
  <div class="evaluation-content">
    <h1>ACES EVALUATION<br>2025/2026 TRYOUTS</h1>
    <p>Are you interested in joining the ACES for training 2025 but can't make out tryout?</p>

    <div class="assessment-box mt-4">
      <h2>ACES ASSESSMENT</h2>
      <p>Please Register for a Group Assessment and<br>we'll coordinate a time for evaluation</p>

    </div>
        <!-- Register Button below the assessment box -->
    <div class="mt-3">
      <a href="https://registration.teamsnap.com/form/38469" class="btn btn-register" target="_blank" rel="noopener">Register Now</a>
    </div>
  </div>
</section> --}}
{{-- <section class="evaluation-section">
    <a href="https://registration.teamsnap.com/form/46426" target="__blank">
    <img src="{{ asset('public/assets/images/AcesTryouts_5.jpg') }}" class="banner-image w-100 h-auto" alt="">
    </a>
</section> --}}
<section class="tournaments-section">
    <h2>Fall | Winter Tournament Schedule</h2>

    <div class="tournament-grid">

        <!-- 1 -->
        <div class="tournament-card pink">
            <div class="corner-dot"></div>

            <h3>
                <a href="https://www.buku.events/santa-barbara-fall-brawl-buku-events"
                   target="_blank"
                   rel="noopener noreferrer">
                    SANTA BARBARA FALL BRAWL
                </a>
            </h3>

            <div class="tournament-info date-badge">
                📅 October 24–25
            </div>

            <div class="tournament-info">
                📍
                <a href="https://maps.app.goo.gl/ktmLHCZrxAZgr6CM8"
                   target="_blank"
                   rel="noopener noreferrer">
                    Santa Barbara Polo Club
                </a>
            </div>

            <span class="tournament-btn">HS</span>
        </div>


        <!-- 2 -->
        <div class="tournament-card blue">
            <div class="corner-dot"></div>

            <h3>
                <a href="https://teamnorcal.com/programs/fall-ball-overview/"
                   target="_blank"
                   rel="noopener noreferrer">
                    SILICON VALLEY SHOOTOUT
                </a>
            </h3>

            <div class="tournament-info date-badge">
                📅 November 22
            </div>

            <div class="tournament-info">
                📍 Fremont
            </div>

            <span class="tournament-btn">U12</span>
        </div>


        <!-- 3 -->
        <div class="tournament-card green">
            <div class="corner-dot"></div>

            <h3>
                <a href="https://www.invictuslacrosse.com/leagues/sacramento-sixes/18025"
                   target="_blank"
                   rel="noopener noreferrer">
                    SACTOWN SIXES
                </a>
            </h3>

            <div class="tournament-info date-badge">
                📅 December 5
            </div>

            <div class="tournament-info">
                📍
                <a href="https://maps.app.goo.gl/ECkYoCeQjkMxA9Ux7"
                   target="_blank"
                   rel="noopener noreferrer">
                    Brock Park Sports Complex
                </a>
            </div>

            <span class="tournament-btn">HS, U14</span>
        </div>


        <!-- 4 -->
        <div class="tournament-card orange">
            <div class="corner-dot"></div>

            <h3>
                <a href="https://101lax.com/programs/tournaments/gold-rush/"
                   target="_blank"
                   rel="noopener noreferrer">
                    GOLD RUSH
                </a>
            </h3>

            <div class="tournament-info date-badge">
                📅 December 12–13
            </div>

            <div class="tournament-info">
                📍
                <a href="https://maps.app.goo.gl/jSEQeEayyQqnPXK38"
                   target="_blank"
                   rel="noopener noreferrer">
                    Petaluma Community Sports Fields
                </a>
            </div>

            <span class="tournament-btn">HS, U14, U12, U10</span>
        </div>


        <!-- 5 -->
        <div class="tournament-card violet">
            <div class="corner-dot"></div>

            <h3>
                <a href="https://www.buku.events/golden-gate-games"
                   target="_blank"
                   rel="noopener noreferrer">
                    GOLDEN GATE GAMES
                </a>
            </h3>

            <div class="tournament-info date-badge">
                📅 January 23–24
            </div>

            <div class="tournament-info">
                📍
                <a href="https://maps.app.goo.gl/vxjDDVjFPYBYPYL96"
                   target="_blank"
                   rel="noopener noreferrer">
                    Golden Gate Park
                </a>
            </div>

            <span class="tournament-btn">HS, U14, U12, U10</span>
        </div>

    </div>
</section>
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
