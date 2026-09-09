@extends('layouts.app')

{{-- @section('title', 'Hotels - ACES Lacrosse') --}}

@section('content')
<style>
/* ---- Hero Section ---- */
.hero-section {
    position: relative;
    width: 100%;
    height: fit-content; /* or use vh for responsiveness */
    display: flex;
    justify-content: center; /* center horizontally */
    align-items: center;     /* center vertically */
    overflow: hidden;
    padding-top: 40px;       /* ✅ space before the image starts */
}

.hero-section img {
    width: 50%;
    height: fit-content;
    /* object-fit: contain; */
}

.hero-section .overlay {
    position: absolute;
    inset: 0;
}

/* ✅ Responsive adjustments */
@media (max-width: 900px) {
    .hero-section {
        height: auto;
        padding: 40px 30px 0;  /* top space + side padding */
    }

    .hero-section img {
        max-width: 100%;
        height: auto;
    }
}

@media (max-width: 600px) {
    .hero-section {
        padding: 40px 20px 0;  /* top space + smaller side padding */
    }

    .hero-section img {
        width: 100%;
        height: auto;
        object-fit: contain;
    }
}

.tournament-intro {
    text-align: center;
    padding: 60px 20px;
    background-color: #ffffffff; /* optional background */
    color: #3c1053;
    font-family: "Poppins", sans-serif;
}

.tournament-intro .container {
    max-width: 900px;
    margin: 0 auto;
}

.tournament-intro .main-heading {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 20px;
}

.tournament-intro .sub-heading {
    font-size: 1.8rem;
    font-weight: 500;
    margin-bottom: 30px;
}

.tournament-intro .age-button {
    display: inline-block;
    background: linear-gradient(to right, #3c1053, #4c1d95);
    color: #fff;
    padding: 10px 25px;
    font-size: 1.2rem;
    font-weight: 600;
    text-decoration: none;
    border-radius: 50px;
    transition: background 0.3s ease;
}

.tournament-intro .age-button:hover {
    background-color: #a30000;
}

/* ===== Responsive ===== */
@media (max-width: 768px) {
    .tournament-intro .main-heading {
        font-size: 2rem;
    }
    .tournament-intro .sub-heading {
        font-size: 1.2rem;
    }
    .tournament-intro .age-button {
        font-size: 1rem;
        padding: 12px 25px;
    }
}
/* Outer section styling with border */
.development-section {
  display: flex;
  justify-content: center;
  padding: 60px 20px;
  background: #ffffffff; /* light purple background */
}

/* Container box */
.development-container {
  background: #fff;
  border-radius: 20px;
  box-shadow: 0 8px 30px rgba(111, 66, 193, 0.1);
  padding: 50px;
  max-width: 1100px;
  width: 100%;
  text-align: center;
  font-family: 'Poppins', sans-serif;
  border: 1px solid #e5dbf5; /* subtle border around container */
}

/* Individual cards with border */
.development-card {
  display: flex;
  align-items: flex-start;
  gap: 15px;
  background: #faf8ff;
  padding: 25px;
  border-radius: 15px;
  box-shadow: 0 0 0 1px rgba(150, 100, 255, 0.05);
  transition: all 0.3s ease;
  text-align: left;
  border: 1px solid #e5dbf5; /* light purple border around each card */
}

.development-card.highlighted {
  background: #f5edff;
}

/* Hover effect remains */
.development-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 20px rgba(111, 66, 193, 0.2);
}


/* Header text */
.development-header p {
  font-size: 1.2rem;
  color: #333;
  margin-bottom: 10px;
}

.development-header .subtext {
  font-size: 1rem;
  color: #666;
  margin-bottom: 25px;
}

.development-header hr {
  width: 85%;
  border: 0;
  border-top: 1px solid #ddd;
  margin: 0 auto 40px;
}

/* Grid layout */
.development-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(450px, 1fr));
  gap: 25px;
}

/* Individual cards */
.development-card {
  display: flex;
  align-items: flex-start;
  gap: 15px;
  background: #faf8ff;
  padding: 25px;
  border-radius: 15px;
  box-shadow: 0 0 0 1px rgba(150, 100, 255, 0.05);
  transition: all 0.3s ease;
  text-align: left;
}

.development-card.highlighted {
  background: #f5edff;
}

.development-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 20px rgba(111, 66, 193, 0.2);
}

/* Icon style */
.development-card .icon {
  font-size: 1.8rem;
  line-height: 1;
}

/* Text inside cards */
.development-card h3 {
  font-size: 1.1rem;
  color: #5b2fa9;
  margin-bottom: 6px;
}

.development-card p {
  color: #555;
  font-size: 0.95rem;
  margin: 0;
}
/* --- Responsive Design for Development Section --- */

/* Tablet screens */
@media (max-width: 992px) {
  .development-container {
    padding: 40px 25px;
  }

  .development-grid {
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
  }

  .development-card {
    padding: 20px;
  }
}

@media (max-width: 600px) {
  .development-grid {
    grid-template-columns: 1fr; /* single column */
    gap: 15px;
  }

  .development-card {
    flex-direction: column; /* stack items vertically */
    align-items: center;    /* center horizontally */
    text-align: center;     /* center text too */
    padding: 18px;
  }

  .development-card .icon {
    font-size: 1.8rem;
    margin-bottom: 10px;
    text-align: center;
  }

  .development-card h3 {
    font-size: 1rem;
  }

  .development-card p {
    font-size: 0.9rem;
  }
}
.coaches-section {
  text-align: center;
  padding: 4rem 1rem;
  background: #fff;
}

.section-title {
  font-size: 2rem;
  font-weight: 800;
  color: #5a189a;
  letter-spacing: 1px;
  margin-bottom: 0.5rem;
}

.section-subtitle {
  color: #555;
  font-size: 1.05rem;
  margin-bottom: 3rem;
}

.coaches-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
  gap: 2rem;
  max-width: 1100px;
  margin: 0 auto;
}

.coach-card {
  background: #fff;
  border-radius: 20px;
  box-shadow: 0 4px 15px rgba(0,0,0,0.08);
  padding: 2rem;
  text-align: left;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.coach-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 6px 25px rgba(0,0,0,0.12);
}

.coach-header h3 {
  font-size: 1.2rem;
  font-weight: 700;
  color: #222;
}

.coach-header p {
  font-size: 0.9rem;
  color: #7b2cbf;
  margin-bottom: 1.2rem;
}

.coach-timeline {
  list-style: none;
  margin: 0;
  padding: 0;
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

/* Timeline item with border */
.coach-timeline li {
  display: flex;
  align-items: center;
  gap: 1rem;
  background: #f9f9fb;
  border-radius: 15px;
  padding: 1.2rem 1rem;
  font-size: 0.95rem;
  color: #333;
  box-shadow: 0 1px 4px rgba(0,0,0,0.05);

  /* ✅ Add light purple border */
  border: 1px solid #d6c2eb;
  transition: transform 0.3s ease, box-shadow 0.3s ease, background 0.3s ease;
}

/* Hover effect for entire timeline item */
.coach-timeline li:hover {
  transform: translateY(-5px);
  box-shadow: 0 6px 20px rgba(111, 66, 193, 0.2);
  background: #faf5ff; /* subtle light purple background on hover */
}

.badge {
  background: linear-gradient(135deg, #7b2cbf, #5a189a);
  color: #fff;
  border-radius: 8px;
  font-size: 0.75rem;
  font-weight: 600;
  padding: 0.4rem 0.9rem;
  white-space: nowrap;
  min-width: 65px;
  text-align: center;
  box-shadow: 0 2px 6px rgba(91,24,154,0.3);
}


.badge.gold {
  background: linear-gradient(135deg, #fff6d9, #f8c93b);
  color: #5a4500;
  box-shadow: 0 2px 6px rgba(248,201,59,0.3);
}

@media (max-width: 768px) {
  .coaches-grid {
    grid-template-columns: 1fr;
  }
}
/* Yellow timeline item */
.coach-timeline li.yellow-timeline-item {
  background: #fff8e169; /* light yellow */
  border: 1px solid #f9e5a8ff;
}

.coach-timeline li.yellow-timeline-item:hover {
  transform: translateY(-5px);
  background: #fff8e1; /* slightly darker yellow on hover */
}

.clinic{
  margin: 0 auto; /* centers section horizontally */
  text-align: center; /* centers text inside */
}
/* Title & location */
.clinic-title {
  font-size: 1.8rem;
  font-weight: 700;
  color: #5a189a;
  margin-bottom: 6px;
}
.sessions-section {
  background: #ffffffff;
  padding: 4rem 1rem;
  text-align: center;
}

.map-card {
  max-width: 900px;
  margin: 0 auto 3rem auto;
  border-radius: 20px;
  overflow: hidden;
  box-shadow: 0 6px 25px rgba(0,0,0,0.12);
}
/* Map styling */
.map-wrapper {
  background: transparent;
  margin-bottom: 40px;
  max-width: 600px;
  margin: 0 auto; /* centers section horizontally */
}

.map-wrapper iframe {
  border: none;
  border-radius: 12px;
  width: 100%;
  height: 300px;
}
.sessions-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 2rem;
  max-width: 1100px;
  margin: 0 auto;
}

/* Outer card for the entire section */
.sessions-card {
  background: #faf7ff;
  border-radius: 25px;
  padding: 2.5rem;
  box-shadow: 0 10px 35px rgba(0,0,0,0.12);
  max-width: 1200px;
  margin: 0 auto;
}

/* Map card inside the main card */
.sessions-card .map-card {
  border-radius: 20px;
  overflow: hidden;
  box-shadow: 0 6px 25px rgba(0,0,0,0.12);
  margin-bottom: 2rem;
}

/* Sessions grid inside main card remains the same */
.sessions-card .sessions-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 2rem;
}

/* Session cards already have hover animations */
.session-card {
  background: #fff;
  border-radius: 20px;
  border: 2px solid transparent;
  box-shadow: 0 4px 15px rgba(0,0,0,0.08);
  padding: 2rem;
  display: flex;
  flex-direction: column;
  align-items: stretch;
  justify-content: space-between;
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
}

/* Hover: light purple border and lift effect */
.session-card:hover {
  border-color: #e3d3ff;
  box-shadow: 0 8px 25px rgba(90,24,154,0.2);
  transform: translateY(-5px);
}

/* Top bar animation */
.session-card::before {
  content: "";
  position: absolute;
  top: 0;
  left: 50%; /* start from center */
  width: 0%;
  height: 5px;
  background: linear-gradient(90deg, #7b2cbf, #5a189a);
  border-radius: 20px 20px 0 0;
  transform: translateX(-50%);
  transition: width 0.4s ease;
}

.session-card:hover::before {
  width: 100%; /* expand to both sides */
}


.session-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1.5rem;
}

.session-header h3 {
  font-size: 1.25rem;
  font-weight: 700;
  color: #5a189a;
  margin-bottom: 0.1rem;
}
/* Divider */
.session-card hr {
  border: 0;
  border-top: 1px solid #eee;
}
.session-card .divider {
  width: 100%;
  height: 2px;
  background-color: #3b10538b;
  border: none;
  border-radius: 2px;
  margin: 0.2rem 0 0.8rem 0;
}

.duration-badge {
  background: #f3e8ff;
  color: #5a189a;
  font-size: 0.8rem;
  padding: 0.25rem 0.8rem;
  border-radius: 15px;
  font-weight: 600;
}

.session-dates {
  list-style: none;
  padding: 0;
  margin: 0 0 2rem 0;
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.session-dates li {
  background: #faf9ff;
  border: 1px solid #f1ebff;
  border-radius: 10px;
  padding: 0.8rem 1rem;
  font-size: 0.95rem;
  color: #333;
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  gap: 0.3rem;
}


.session-dates span {
  background: #f1ebff;
  color: #5a189a;
  padding: 0.2rem 0.8rem;
  border-radius: 12px;
  font-size: 0.8rem;
  font-weight: 700;
  white-space: nowrap;
}

.register-btn {
  background: linear-gradient(135deg, #7b2cbf, #5a189a);
  color: #fff;
  border: none;
  border-radius: 12px;
  padding: 0.9rem 1.2rem;
  font-size: 0.95rem;
  font-weight: 600;
  box-shadow: 0 6px 15px rgba(91,24,154,0.25);
  cursor: pointer;
  transition: all 0.3s ease;
}

.register-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(91,24,154,0.35);
}

@media (max-width: 768px) {
  .sessions-grid {
    grid-template-columns: 1fr;
  }
}
.registration-section {
  text-align: center;
  padding: 2rem 1rem;
  background-color: #fff;
}

.registration-banner-button,
.registration-banner-button-small {
  display: flex;
  justify-content: center;  /* centers text horizontally */
  align-items: center;      /* centers text vertically */
  background: linear-gradient(90deg, #5a189a, #3c1053);
  color: white;
  border-radius: 30px;
  font-weight: 600;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
  margin: 2rem auto;
  font-size: 0.9rem;
  text-align: center;
  cursor: pointer;
  padding: 5px 0; /* ensures height and spacing */
  border: none;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.registration-banner-button {
  width: 40%;
}

.registration-banner-button-small {
  width: 300px;
}


.registration-banner-button:hover,
.registration-banner-button-small:hover {
  transform: scale(1.03);
  box-shadow: 0 6px 15px rgba(90, 24, 154, 0.4);
}


.images-container {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 1.5rem;
  flex-wrap: wrap;
  max-width: 1100px;
  margin: 2rem auto;
}

.image-box {
  overflow: hidden;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
  transition: transform 0.3s ease;
}

.image-box img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}


/* Size Variations */
.image-box.small {
  flex: 1 1 250px;
  height: 380px;
}

.image-box.large {
  flex: 1 1 400px;
  height: 380px;
}
.register-btn {
    display: inline-block;
    text-decoration: none;
}

/* Tablet view */
@media (max-width: 900px) {
  .image-box.small,
  .image-box.large {
    flex: 1 1 45%;
    height: 280px;
  }
}

/* Mobile view */
@media (max-width: 600px) {
  .images-container {
    flex-direction: column;
    gap: 0;
    margin: 0;
  }
      .register-btn {
        font-size: 14px;
    }


  .image-box.small,
  .image-box.large {
    flex: 1 1 100%;
    height: 240px;
    box-shadow: none;
  }

  .image-box img {
    border-radius: 0;
  }
  .registration-banner-button {
  width: 100%;
  font-size: 0.7rem;
}

}
@media screen and (min-width: 2560px) {
  .container, .container-lg, .container-md, .container-sm, .container-xl, .container-xxl {
      max-width: 230rem;
  }
  .hero-section {
    height: fit-content;
  }
  .tournament-intro .container {
    max-width: 230rem;
  }
  .tournament-intro .main-heading {
    font-size: 10rem;
  }
  .tournament-intro .sub-heading {
    font-size: 4rem;
  }
  .tournament-intro .age-button {
    font-size: 4rem;
  }
  .development-container {
    max-width: fit-content;
  }
  .development-header p {
    font-size: 6rem;
  }
  .development-header .subtext {
    font-size: 3rem;
  }
  .development-card h3 {
    font-size: 4rem;
  }
  .development-card p {
    font-size: 4rem;
  }
  .development-card .icon {
    font-size: 4rem;
  }
  .section-title {
    font-size: 8rem;
  }
  p.section-subtitle {
    font-size: 5rem;
  }
  .coaches-grid {
    max-width: 280rem;
  }
  .coach-header h3 {
    font-size: 4.5rem;
  }
  .coach-header p {
    font-size: 4rem;
  }
  .coach-timeline li {
    font-size: 3.5rem;
  }
  .badge {
    font-size: 3rem;
  }
  .clinic-title {
    font-size: 8rem;
  }
  p {
    font-size: 4rem;
  }
  .sessions-card {
    max-width: 230rem;
  }

  .map-wrapper {
    width: 100%;
    max-width: 120rem;
    margin: 0 auto 40px;
  }

  .map-wrapper iframe {
    width: 100%;
    aspect-ratio: 16 / 9;
    height: auto;
  }
  .sessions-grid {
    max-width: none !important;

  }
  .session-header h3 {
    font-size: 5rem;
  }
  .duration-badge {
    font-size: 3rem;
  }
  .session-dates li {
    font-size: 3.5rem;
  }
  .session-dates span {
    font-size: 3rem
  }
  .register-btn {
    font-size: 3rem;
  }

  .registration-banner-button-small {
    width: 90rem;
  }

  .registration-banner-button-small {
    font-size: 5rem;
  }
  .images-container {
    max-width: 230rem;
  }
  .image-box.small {
    height: 82rem;
  }
  .image-box.large {
    height: 82rem;
  }
  .registration-banner-button, .registration-banner-button-small {
    font-size: 4rem;
  }



}
</style>
<!-- Hero Section -->
<section class="hero-section">
    <img src="{{ asset('public/assets/images/Aces-Grow-Your-Game-Clinic-2026-Web.png') }}" alt="Our Mission">
    <div class="overlay"></div>
</section>

<section class="tournament-intro">
    <div class="container">
        <h1 class="main-heading">Sacramento ACES</h1>
        <h2 class="sub-heading">ACES Grow Your Game - El Dorado Hills 2026</h2>
        <h5 class="sub-heading">Grad Years 2033-2037</h5>
        <a href="https://registration.teamsnap.com/form/69912" target="_blank" class="age-button">Register Session 1</a>
        <a href="https://registration.teamsnap.com/form/69915" target="_blank" class="age-button">Register Session 2</a>
    </div>
</section>
<section class="development-section">
  <div class="development-container">
    <div class="development-header">
      <p>
        Designed for <strong>newer players</strong> or players wanting more
        <strong>developmental skill training.</strong>
      </p>
      <p class="subtext">
        <em>
          The Six session course is designed to teach proper fundamentals,
          accelerate development, and provide personalized growth plans.
        </em>
      </p>
      <hr>
    </div>

    <div class="development-grid">
      <div class="development-card">
        <span class="icon">🥍</span>
        <div>
          <h3>Proper Stick Mechanics</h3>
          <p>Master fundamental lacrosse techniques with professional instruction and hands-on practice.</p>
        </div>
      </div>

      <div class="development-card">
        <span class="icon">🧠</span>
        <div>
          <h3>Lacrosse IQ</h3>
          <p>Hone your game intelligence through strategic training and tactical understanding.</p>
        </div>
      </div>

      <div class="development-card">
        <span class="icon">💪</span>
        <div>
          <h3>Athletic Development</h3>
          <p>Accelerate your physical performance through targeted training and conditioning programs.</p>
        </div>
      </div>

      <div class="development-card">
        <span class="icon">📚</span>
        <div>
          <h3>Lacrosse Homework</h3>
          <p>Receive personalized development plans to accelerate your lacrosse growth at home.</p>
        </div>
      </div>
    </div>
  </div>
</section>
{{-- <section class="coaches-section">
  <h2 class="section-title">COACHES</h2>
  <p class="section-subtitle">
    Elite Experience. Professional Development. Proven Results.
  </p>

  <div class="coaches-grid">
    <!-- Coach 1 -->
    <div class="coach-card">
      <div class="coach-header">
        <h3>JORDAN FOX-FICK</h3>
        <p>4 Year Starter - Elk Grove</p>
      </div>

      <ul class="coach-timeline">
        <li><span class="badge">2014</span> NCJLA All Star</li>
        <li><span class="badge">2015</span> Belmont Abbey College – NCAA D2</li>
        <li><span class="badge">2016–19</span> Arizona State University</li>
        <li><span class="badge">2020–Present</span> UC Davis Assistant Coach</li>
        <li><span class="badge">2022–Present</span> Jesuit Head Coach</li>
      </ul>
    </div>

    <!-- Coach 2 -->
    <div class="coach-card">
      <div class="coach-header">
        <h3>BILL HUSS</h3>
        <p>4 Year Varsity Starter – Elk Grove Gladiators</p>
      </div>

<ul class="coach-timeline">
  <li><span class="badge">2013–14</span> NCJLA All Star</li>
  <li><span class="badge">College</span> 4 Year Starter Belmont Abbey NCAA D2</li>
  <li class="yellow-timeline-item"><span class="badge">Original</span> Member of Original ACES Team</li>
  <li><span class="badge">2017</span> Second Team All Conference</li>
  <li><span class="badge">2017</span> All American Honorable Mention</li>
  <li><span class="badge">2018</span> Conference Carolinas Champion</li>
  <li><span class="badge">Current</span> Head Coach – Elk Grove Gladiators</li>
</ul>


    </div>
  </div>
</section> --}}
<section class="clinic">
    <h1 class="clinic-title">GROW YOUR GAME CLINIC</h1>
    <p class="clinic-location">Lakeview Elementary - El Dorado Hills</p>
    </section>

<section class="sessions-section">
  <div class="sessions-card"> <!-- Outer card wrapper -->

    <!-- Map -->
    <div class="map-wrapper">
        <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d51017347.43897095!2d-121.094519!3d38.698249!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x809ae4a4257926dd%3A0x2be25c5b97a75d5e!2sLakeview%20Elementary%20School!5e0!3m2!1sen!2slk!4v1762789973347!5m2!1sen!2slk" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>

    </div>
<br>
    <!-- Sessions -->
    <div class="sessions-grid">
      <!-- Session 1 -->
      <div class="session-card">
        <div class="session-header">
          <h3>Session 1</h3>

          <span class="duration-badge">1.5 hours</span>

        </div>
        <hr class="divider">
        <br>
        <ul class="session-dates">
            <li>August 12, 2026 <span>5:30 - 7:00 PM</span></li>
            <li>August 19, 2026 <span>5:30 - 7:00 PM</span></li>
            <li>August 26, 2026 <span>5:30 - 7:00 PM</span></li>
            <li>September 2, 2026 <span>5:30 - 7:00 PM</span></li>
            <li>September 9, 2026 <span>5:30 - 7:00 PM</span></li>
            <li>September 16, 2026 <span>5:30 - 7:00 PM</span></li>
        </ul>
        <a href="https://registration.teamsnap.com/form/69912"
   class="register-btn"
   target="_blank"
   rel="noopener">
   Register for Session 1 →
</a>

      </div>

      <!-- Session 2 -->
      <div class="session-card">
        <div class="session-header">
          <h3>Session 2</h3>
          <span class="duration-badge">1.5 hours</span>
        </div>
        <hr class="divider">
        <ul class="session-dates">
            <li>September 23, 2026 <span>5:00 - 6:30 PM</span></li>
            <li>September 30, 2026 <span>5:00 - 6:30 PM</span></li>
            <li>October 7, 2026 <span>5:00 - 6:30 PM</span></li>
            <li>October 14, 2026 <span>5:00 - 6:30 PM</span></li>
            <li>October 21, 2026 <span>5:00 - 6:30 PM</span></li>
            <li>October 28, 2026 <span>5:00 - 6:30 PM</span></li>
        </ul>
        <a href="https://registration.teamsnap.com/form/69915"
            class="register-btn"
            target="_blank"
            rel="noopener">
            Register for Session 2 →
        </a>
      </div>

      <!-- Session 3 -->
      {{-- <div class="session-card">
        <div class="session-header">
          <h3>Session 3</h3>
          <span class="duration-badge">1.25 hours</span>
        </div>
        <hr class="divider">
        <ul class="session-dates">
          <li>October 29, 2025 <span>4:30 PM - Dusk</span></li>
          <li>November 5, 2025 <span>4:30 PM - Dusk</span></li>
          <li>November 12, 2025 <span>4:30 PM - Dusk</span></li>
          <li>November 19, 2025 <span>4:30 PM - Dusk</span></li>
          <li>December 3, 2025 <span>4:30 PM - Dusk</span></li>
          <li>December 10, 2025 <span>4:30 PM - Dusk</span></li>
        </ul>
                        <a href="https://registration.teamsnap.com/form/35279"
   class="register-btn"
   target="_blank"
   rel="noopener">
   Register for Session 2 →
</a>
      </div> --}}

    </div>

  </div>
</section>
<br>
<section class="registration-section">
    {{-- <div class="registration-banner-button-small">
        <span>REGISTRATION FEE: $95 per player</span>
    </div> --}}

    <div class="images-container">
        <div class="image-box small">
            <img src="{{ asset('public/assets/images/download.avif') }}" alt="Lacrosse players action">
        </div>
        <div class="image-box large">
            <img src="{{ asset('public/assets/images/download (1).avif') }}" alt="Lacrosse faceoff">
        </div>
        <div class="image-box small">
            <img src="{{ asset('public/assets/images/download (2).avif') }}" alt="Lacrosse goalie">
        </div>
    </div>

    <div class="registration-banner-button">
        <span>
            REGISTRATION FOR ALL SESSIONS WILL REMAIN OPEN THROUGHOUT THE CLINICS. <br>
            IT’S NEVER TOO LATE TO REGISTER!
        </span>
    </div>
</section>


@endsection
{{-- @section('content')
<style>
.team-store-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 50vh; /* just enough height to center the box nicely */
    padding: 60px 20px 40px; /* balanced spacing, not too tall */
    margin: 0;
    background: #fff;
}

.team-store-box {
    border: 2px solid #5620b3ff;
    border-radius: 8px;
    padding: 50px 80px;
    text-align: center;
    max-width: 1200px;
    width: 90%;
    margin: 0 auto;
}

.team-store-text {
    color: #5620b3ff;
    font-size: 2rem;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
    margin: 0;
}

@media screen and (min-width: 2560px) {
  .container, .container-lg, .container-md, .container-sm, .container-xl, .container-xxl {
      max-width: 230rem;
  }
  .team-store-box {
    max-width: fit-content;
  }
  .team-store-text {
    font-size: 10rem;
  }

}


</style>
<div class="team-store-wrapper">
    <div class="team-store-box">
        <h1 class="team-store-text">COMING SOON</h1>
    </div>
</div>

@endsection --}}
