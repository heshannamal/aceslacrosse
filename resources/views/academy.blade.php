@extends('layouts.app')

@section('title', 'Academy - ACES Lacrosse')

@section('content')
<style>
/* ---- Hero Section ---- */

img.banner-image {
    width: 100%;
    height: auto;
}
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

.evaluation-section {
    max-width: 80rem;
    margin: 50px auto;
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

  .hero-section {
      height: auto;
  }

  .team-image {
    flex: 0 0 132px;
  }

  .team-content {
    flex: 1 1 370px;
  }


}

@media screen and (min-width: 2560px) {
  .container, .container-lg, .container-md, .container-sm, .container-xl, .container-xxl {
    max-width: 230rem;
  }
  .academy-section {
    max-width: 230rem;
  }
  .academy-section h2 {
    font-size: 10rem;
  }
  .academy-section p {
    font-size: 5rem;
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
  .team-section {
    max-width: 230rem;
  }
  .team-content h2 {
    font-size: 8rem;
  }

  .team-content li {
    font-size: 4rem;
  }

  .offense-section {
    max-width: 230rem;
  }
  .offense-content h2 {
    font-size: 8rem;
  }

  .offense-content li {
     font-size: 4rem;
  }

  .goalies-section, .faceoff-section {
    max-width: 230rem;
  }

  .goalies-container h2, .faceoff-container h2 {
    font-size: 6rem;
  }

  .goalies-container p, .faceoff-container p {
    font-size: 4rem;
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

}


</style>

<section class="hero-section">
    <img src="{{ asset('public/assets/images/aces two.jpg') }}" alt="Our Mission">
    <div class="overlay"></div>
    <div class="hero-text">
    </div>
</section>

<section class="academy-section">

    <h2>ACES TRAINING ACADEMY</h2>
    <p>
        Sacramento ACES is the Sacramento Valley’s premier lacrosse training organization. ACES Academy consists of training sessions designed to enhance skill, athletic ability, and lacrosse acumen. No detail is too small when it comes to developing a player’s game. ACES Academy features the most experienced coaching staff in the region; <a href="https://aceslacrosse.com/pages/coaching-staff" target="_blank" class="text-decoration-underline " style="color:#76139a;">ACES Academy staff</a>.consists of college coaches, current and former professional and NCAA players, some of the area's top high school coaches, and boasts over 500 years of combined coaching and playing experience.
<br><br>ACES Academy teams participate in Sacramento area games as well as regional Northern California tournaments. In addition to Academy games and tournaments, ACES Academy players are the first to be called up to an ACES Elite team when a roster spot for a tournament becomes available and each season, numerous ACES Academy players have received invitations to join ACES Elite teams. This is because it is our philosophy to promote from within and provide the highest performing Academy members the chance for greater opportunities when they present themselves.
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

{{-- <section>
  <div class="container">
    <a href="https://registration.teamsnap.com/form/46426" target="__blank">
    <img src="{{ asset('public/assets/images/AcesTryouts_5.jpg') }}" class="banner-image" alt="">
    </a>
  </div>
</section> --}}

{{-- Fall/Winter calender --}}

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

{{-- summer calendar --}}

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
<section class="team-section">
  <div class="team-container">
    <!-- LEFT SIDE TEXT -->
    <div class="team-content">
      <h2>TEAM</h2>
      <ul>
        <li>
          <span class="check-icon">✔</span>
          Small sided team concepts to maximize repetitions
        </li>
        <li>
          <span class="check-icon">✔</span>
          6v6 Samurai style games
        </li>
        <li>
          <span class="check-icon">✔</span>
          Settled and Transition team play dynamics
        </li>
        <li>
          <span class="check-icon">✔</span>
          High repetition play to ingrain concepts at game speed
        </li>
      </ul>
    </div>

    <!-- RIGHT SIDE IMAGE -->
    <div class="team-image">
      <img src="{{ asset('public/assets/images/4.jpg') }}" alt="Team Practice">
    </div>
  </div>
</section>

<!-- OFFENSE SECTION -->
<section class="offense-section">
  <div class="offense-container">
    <div class="offense-content">
      <h2>OFFENSE</h2>
      <ul>
        <li><span class="check-icon">✔</span>Teaching the fundamentals of passing with correct form from various release points</li>
        <li><span class="check-icon">✔</span>Catching the ball with appropriate grips in tight, mid range shallow cuts, coming up field below goal line extended and posting up top. Catch the balls with soft hands and deep or catching in front and keeping in front to avoid a trailing defender.</li>
        <li><span class="check-icon">✔</span>Dodging approaches & options coming out of the dodge as well as dodging off the pass with an approaching defender.</li>
        <li><span class="check-icon">✔</span>Swing passes or one more feeds</li>
        <li><span class="check-icon">✔</span>Taking off ball inventory</li>
        <li><span class="check-icon">✔</span>Finding cutting lanes or skip passing lanes.</li>
        <li><span class="check-icon">✔</span>Offensive communication.</li>
        <li><span class="check-icon">✔</span>Shooting techniques from different release points.</li>
        <li><span class="check-icon">✔</span>Odd man situations to identify the 2 on 1</li>
        <li><span class="check-icon">✔</span>Man up offense-Small sided games.</li>
        <li><span class="check-icon">✔</span>Riding affectively.</li>
        <li><span class="check-icon">✔</span>Ground Balls</li>
        <li><span class="check-icon">✔</span>Creating slickness via multiple stick handling progressions</li>
        <li><span class="check-icon">✔</span>
Learning to utilize stick fakes to freeze slides, shift defenses and misdirect sticks in passing lanes</li>

      </ul>
    </div>

    <div class="offense-images">
      <div class="offense-img">
        <img src="{{ asset('public/assets/images/2.jpg') }}" alt="Offense Practice 1">
      </div>
      <div class="offense-img">
        <img src="{{ asset('public/assets/images/3.jpg') }}" alt="Offense Practice 2">
      </div>
    </div>
  </div>
</section>

<!-- DEFENCE SECTION -->
<section class="offense-section">
  <div class="offense-container">
    <div class="offense-content">
      <h2>DEFENSE</h2>
      <ul>
        <li><span class="check-icon">✔</span>Defensive positioning, footwork and approaches.</li>
        <li><span class="check-icon">✔</span>Off ball positioning.</li>
        <li><span class="check-icon">✔</span>Defensive toolbox checks and bumps.</li>
        <li><span class="check-icon">✔</span>Man down defense</li>
        <li><span class="check-icon">✔</span>Getting sticks up in passing lanes.</li>
        <li><span class="check-icon">✔</span>Sliding the correct way and teaching how to make contact the correct way</li>
        <li><span class="check-icon">✔</span>Being a threat to pass, feed and shoot.</li>
        <li><span class="check-icon">✔</span>Creating slickness via multiple stick handling progressions</li>
        <li><span class="check-icon">✔</span>Defensive communication.</li>
        <li><span class="check-icon">✔</span>Ground Balls</li>
        <li><span class="check-icon">✔</span>Small sided games.</li>
        <li><span class="check-icon">✔</span>Odd man situations and reads.</li>

      </ul>
    </div>

    <div class="offense-images">
      <div class="offense-img">
        <img src="{{ asset('public/assets/images/1.jpg') }}" alt="Offense Practice 1">
      </div>

    </div>
  </div>
</section>
<!-- GOALIES SECTION -->
<section class="goalies-section">
  <div class="goalies-container">
    <h2>GOALIES</h2>
    <p>
      <span class="check-icon">✔</span>
      Goalies will work on positioning, baiting, stance, not giving up rebounds, communication,
      outlets, playing small sides games to work on stick skills and footwork.
    </p>
  </div>
</section>

<!-- FACE OFF AND WING PLAY SECTION -->
<section class="faceoff-section">
  <div class="faceoff-container">
    <h2>FACE OFF AND WING PLAY</h2>
    <p>
      <span class="check-icon">✔</span>
      Stance, reads, outs, wingmen adjustments, listening for calls from sideline, shooting, feeding,
      passing, defensive toolbox building to be confident if they get stuck on defense.
      We will also cover the cat and mouse game of substitution.
    </p>
  </div>
</section>


@endsection
