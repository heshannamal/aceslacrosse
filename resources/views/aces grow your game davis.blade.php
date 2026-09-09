@extends('layouts.app')

{{-- @section('title', 'Hotels - ACES Lacrosse')

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
    height: 100%;
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



/* General Section Styling */
.director-section {
  font-family: 'Poppins', sans-serif;
  background: #fff;
  padding: 60px 20px;
  text-align: center;
}

/* Header */
.director-header h2 {
  font-size: 28px;
  color: #6a1b9a;
  font-weight: 800;
  letter-spacing: 1px;
  margin-bottom: 5px;
  text-transform: uppercase;
}

.director-header p {
  color: #555;
  font-size: 16px;
  margin-bottom: 40px;
}

/* Card Container */
.director-card {
  max-width: 800px;
  margin: 0 auto;
  background: #fff;
  box-shadow: 0 4px 25px rgba(0,0,0,0.1);
  border-radius: 20px;
  padding: 40px 30px;
}

/* Name + Role */
.director-info .director-name {
  font-size: 24px;
  font-weight: 700;
  color: #222;
  margin: 0;
  text-transform: uppercase;
}

.director-info .director-role {
  color: #777;
  font-size: 14px;
  margin-top: 4px;
}

/* Divider */
.director-card hr {
  margin: 30px 0;
  border: 0;
  border-top: 1px solid #eee;
}

/* Details Grid */
.director-details {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 20px;
}

/* Detail Boxes with Borders */
.detail-box {
  background: #f8f8f8;
  border-radius: 12px;
  padding: 15px 20px;
  display: flex;
  align-items: center;
  justify-content: flex-start;
  text-align: left;
  transition: 0.3s;
  border: 1px solid #e0d4f0; /* light purple border */
}

.detail-box.gradient {
  background: linear-gradient(135deg, #f3e5f5, #ede7f6);
  border: 1px solid #d6c2eb; /* slightly darker purple for gradient boxes */
}

/* Hover effect */
.detail-box:hover {
  transform: translateY(-3px);

}


.detail-box p {
  color: #333;
  font-size: 15px;
  margin: 0;
}

.tag {
  background: linear-gradient(135deg, #7b1fa2, #4a148c);
  color: #fff;
  font-size: 12px;
  font-weight: 700;
  padding: 4px 8px;
  border-radius: 6px;
  margin-right: 10px;

}
.director-card .divider {
    width: 100%;               /* length of line */
    height: 2px;              /* thickness */
    background-color: #3c1053;
    border: none;
    margin: 10px auto 0;      /* spacing and center */
    border-radius: 2px;       /* optional rounded ends */
}
@media (max-width: 600px) {
  .detail-box {
    flex-direction: column;      /* Stack tag above text */
    align-items: center;         /* Center align content */
    text-align: center;          /* Center paragraph text */
    gap: 6px;
  }

  .tag {
    margin: 0 0 6px 0;           /* Space below tag */
    display: inline-block;
  }

  .detail-box p {
    font-size: 14px;
  }
}

.clinic{
  margin: 0 auto; /* centers section horizontally */
  text-align: center; /* centers text inside */
}
.clinic-section {
  max-width: 900px;
  width: 100%;
  margin: 0 auto; /* centers section horizontally */
  text-align: center; /* centers text inside */
  background-color: #f4ebff;
   border-radius: 16px;

}


/* Title & location */
.clinic-title {
  font-size: 1.8rem;
  font-weight: 700;
  color: #5a189a;
  margin-bottom: 6px;
}

.clinic-location {
  font-size: 1rem;
  color: #5f5f5f;
  margin-bottom: 25px;
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
  height: 250px;
}

/* Main training card */
.training-card {
  background: #fff;
  border-radius: 20px;
  box-shadow: 0 6px 25px rgba(0, 0, 0, 0.08);
  padding: 35px;
  text-align: left;
  max-width: 800px;
  margin: 0 auto; /* centers section horizontally */
}

/* Header row */
.header-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: 1px solid #eee;
  padding-bottom: 15px;
  margin-bottom: 25px;
  flex-wrap: wrap;
}

.header-row h2 {
  font-size: 1.3rem;
  color: #4a0072;
  font-weight: 600;
  margin: 0;
}

.session-length {
  background: #f1e6ff;
  color: #4a0072;;
  padding: 6px 14px;
  border-radius: 20px;
  font-size: 0.9rem;
}

/* Dates grid */
.dates-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 20px;
  margin-bottom: 30px;
}

.date-box {
  background: #faf7ff;
  border: 1px solid #eaeaea;
  border-radius: 12px;
  padding: 20px;
  text-align: center;
  transition: all 0.25s ease;
}

.date-box:hover {
  background: #f4ebff;
  box-shadow: 0 3px 10px rgba(0, 0, 0, 0.06);
  transform: translateY(-3px);
}

.date-box p {
  font-weight: 600;
  margin-bottom: 10px;
}

.date-box span {
  background: #e8d8ff;
  color: #4a0072;
  padding: 6px 12px;
  border-radius: 16px;
  font-size: 0.9rem;
}

/* Button */
.register-btn {
  display: block;
  width: 100%;
  background: linear-gradient(90deg, #5a189a, #3c1053);
  color: #fff;
  border: none;
  border-radius: 10px;
  padding: 15px;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 4px 10px rgba(90, 24, 154, 0.3);
}

.register-btn:hover {
  background: linear-gradient(90deg, #080808ff, #090909ff);
  box-shadow: 0 6px 15px rgba(90, 24, 154, 0.4);
  transform: translateY(-2px);
}

/* Responsive */
@media (max-width: 600px) {
  .header-row {
    flex-direction: column;
    align-items: flex-start;
    gap: 10px;
  }
  .training-card {
    padding: 25px;
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
    text-align: center;
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
    gap: 0; /* remove gap between stacked images */
    margin: 0; /* remove side margin for flush look */
  }

  .image-box.small,
  .image-box.large {
    flex: 1 1 100%;
    height: 240px;
    box-shadow: none; /* optional: remove shadow for cleaner stacking */
  }

  .image-box img {
    border-radius: 0; /* make edges align perfectly */
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
  .tournament-intro .container {
    max-width: fit-content;
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
    font-size: 4rem;
  }
  .development-card h3 {
    font-size: 4rem;
  }
  .development-card p {
    font-size: 3rem;
  }
  .development-card .icon {
    font-size: 5rem;
  }
  .director-header h2 {
    font-size: 8rem;
  }
  .director-header p {
    font-size: 4rem;
  }
  .director-card {
    max-width: 250rem;
  }

  .director-info .director-name {
    font-size: 6rem;
  }
  .director-info .director-role {
    font-size: 4rem;
  }
  .tag {
    font-size: 4rem;
  }
  .detail-box p {
    font-size: 4rem;
  }
  .clinic-title {
    font-size: 8rem;
  }
  .clinic-location {
    font-size: 5rem;
  }

  .clinic-section {
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
  .training-card {
    max-width: 230rem;
  }
  .header-row h2 {
    font-size: 5rem;
  }
  .session-length {
    font-size: 4rem;
  }
  .date-box p {
    font-size: 5rem;
  }
  .date-box span {
    font-size: 4rem;
  }
  .register-btn {
    font-size: 4rem;
  }
  .registration-banner-button-small {
    width: 110rem !important;
  }
  p {
    font-size: 6rem;
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




}


</style>
<!-- Hero Section -->
<section class="hero-section">
    <img src="{{ asset('public/assets/images/Aces_GYG_DAVIS.jpg') }}" alt="Our Mission">
    <div class="overlay"></div>
</section>

<section class="tournament-intro">
    <div class="container">
        <h1 class="main-heading">Sacramento ACES</h1>
        <h2 class="sub-heading">ACES Grow Your Game - Davis 2025</h2>
        <a href="#" class="age-button">U10 • U12 • U14 Boys & Girls</a>
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
<section class="director-section">
  <div class="director-header">
    <h2>DIRECTOR</h2>
    <p>Leadership. Vision. Excellence.</p>
  </div>

  <div class="director-card">
    <div class="director-info">
      <h3 class="director-name">Nate Ceppos</h3>
      <p class="director-role">Program Director</p>
      <hr class="divider">
    </div>

    <hr>

    <div class="director-details">
      <div class="detail-box">
        <span class="tag">Graduate</span>
        <p>Davis High School</p>
      </div>
      <div class="detail-box">
        <span class="tag">2020</span>
        <p>Pinnacle Starting Midfielder</p>
      </div>
      <div class="detail-box gradient">
        <span class="tag">Current</span>
        <p>Davis JV Head Coach</p>
      </div>
      <div class="detail-box gradient">
        <span class="tag">Current</span>
        <p>ACES Elite 10U Coach</p>
      </div>
    </div>


  </div>
</section>
<section class="clinic">
    <h1 class="clinic-title">GROW YOUR GAME CLINIC</h1>
    <p class="clinic-location">Community Park - Davis</p>
    </section>
  <section class="clinic-section">
    <div class="map-wrapper">
        <br>
<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d6239.78642891204!2d-121.75762295722963!3d38.55927405961512!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x808529bc78240115%3A0x8d1821895250ba51!2sCommunity%20Park!5e0!3m2!1sen!2slk!4v1762761691636!5m2!1sen!2slk" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>

    <div class="training-card">
      <div class="header-row">
        <h2>🎾 6-Week Training Program</h2>
        <span class="session-length">1.5 hours per session</span>
      </div>

      <div class="dates-grid">
        <div class="date-box">
          <p>September 5, 2025</p>
          <span>5:00 - 6:30 PM</span>
        </div>
        <div class="date-box">
          <p>September 12, 2025</p>
          <span>5:00 - 6:30 PM</span>
        </div>
        <div class="date-box">
          <p>September 19, 2025</p>
          <span>5:00 - 6:30 PM</span>
        </div>
        <div class="date-box">
          <p>September 26, 2025</p>
          <span>5:00 - 6:30 PM</span>
        </div>
        <div class="date-box">
          <p>October 3, 2025</p>
          <span>5:00 - 6:30 PM</span>
        </div>
        <div class="date-box">
          <p>October 10, 2025</p>
          <span>5:00 - 6:30 PM</span>
        </div>
      </div>

                              <a href="https://registration.teamsnap.com/form/37600"
   class="register-btn"
   target="_blank"
   rel="noopener">
   Register Now →
</a>
    </div><br>
  </section>
<br>
<section class="registration-section">
    <div class="registration-banner-button-small">
        <p>REGISTRATION FEE: $95 per player</p>
    </div>

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
        <p>
            REGISTRATION FOR ALL SESSIONS WILL REMAIN OPEN THROUGHOUT THE CLINICS. <br>
            IT’S NEVER TOO LATE TO REGISTER!
        </p>
    </div>
</section>

@endsection --}}
@section('content')
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

@endsection

