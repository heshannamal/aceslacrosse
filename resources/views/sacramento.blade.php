@extends('layouts.app')

@section('title', 'Hotels - ACES Lacrosse')

@section('content')
<style>
/* ---- Hero Section ---- */
.hero-section {
    position: relative;
    height:100%;
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
.hero-section,
.schedule-header {
    margin: 0;
    padding: 0;
}

/* ---- Schedule Section ---- */
.schedule-header {
    background-color: #3a0b52;
    text-align: center;
    padding: 50px 20px;
    margin: 0;
}

/* ---- Fade In Text ---- */
.schedule-header h2 {
    color: #fff;
    font-weight: 700;
    font-size: 20px;
    letter-spacing: 1px;
    opacity: 0; /* start invisible */
    transform: translateY(20px);
    animation: fadeInUp 1.5s ease forwards;
    animation-delay: 0.3s;
}

/* ---- Keyframes ---- */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
/* ===== Mobile Responsive ===== */
@media (max-width: 768px) {
    .hero-section {
        height: 170px; /* smaller height for mobile */
    }

    .hero-section img {
        height: 100%; /* keeps image covering container */
        height: auto;
        object-fit: contain;
    }
}
.schedule-table table td,
.schedule-table table th {
    border: 1px solid #ddd;
    padding: 12px;
}

.schedule-table table tr:nth-child(even) {
    background-color: #ffffffff;
}

.schedule-table table tr:hover {
    background-color: #ffffffff;
}

.schedule-table table th {
    padding-top: 12px;
    padding-bottom: 12px;
    text-align: center;
}
.schedule-section {
    padding: 80px 20px; /* increased padding */
    text-align: center;
    font-family: Arial, sans-serif;
}

.info-columns {
    display: flex;
    justify-content: center;
    gap: 20px; /* more space between columns */
    flex-wrap: wrap;
    margin-bottom: 40px;
}

.column h3 {
    background-color: #a39e80;
    color: #fff;
    width: 500px;
    padding: 10px 20px; /* bigger heading */
    border-radius: 5px;
    margin-bottom: 20px;
    font-size: 24px; /* larger font */
    letter-spacing: 1.5px;
    text-align: left;
}

.column ul {
    list-style: none;
    padding: 0;
    font-size: 20px; /* bigger list text */
    line-height: 1.6;
    text-align: left;
    gap: 10px;
}

.column ul li {
    margin: 10px 15px;
    position: relative;
    padding-left: 20px;
}

/* Add bullet as colored circle */
.column ul li::before {
    content: '';
    width: 14px; /* bigger bullet */
    height: 14px;
    background-color: #8b1e1e;
    border-radius: 50%;
    position: absolute;
    left: 0;
    top: 8px;
}

.button-wrapper {
    text-align: center;
}

.register {
    display: inline-block;
    background-color: #d50000;
    color: #fff;
    width: 600px;
    padding: 10px ; /* bigger button */
    font-size: 22px; /* larger text */
    font-weight: bold;
    text-decoration: none;
    border-radius: 50px;
    transition: background 0.3s ease;
}

.register:hover {
    background-color: #a30000;
}
/* ===== Mobile Responsive ===== */
@media (max-width: 768px) {
  .info-columns {
    display: flex;
    flex-direction: column; /* stack columns */
    gap: 20px;
    align-items: flex-start; /* align columns to left */
    padding: 0 15px;
  }

  .column {
    width: 100%;
    max-width: 500px;
    padding: 16px;
    box-sizing: border-box;
  }

  .column ul li::before {
      left: -25px;

  }

  .column h3 {
    font-size: 20px;
    width: 100%;
    padding: 12px 5px;
    text-align: left; /* ensure heading is left-aligned */
    margin-bottom: 8px;
  }

  .column ul {
    font-size: 16px;
    text-align: left; /* align list to left */
    padding-left: 50px; /* optional: indent list */
    margin: 0;
  }

  .column ul li {
    margin: 8px 0;
    padding-left: 0;
  }

  .register {
    font-size: 18px;
    padding: 12px 30px;
    width: 90%;
    max-width: 400px;
    margin-top: 20px;
    align-self: flex-start; /* align button to left */
  }
}
/* --- Info Section --- */
.schedule-info {
  text-align: center;
  padding: 40px 20px;
  color: #3c1053;
  font-family: "Poppins", sans-serif;
}

.schedule-info .info-top {
  font-weight: 600;
  margin-bottom: 40px;
  font-size: 1.1rem;
}

.schedule-info .info-cards {
  display: flex;
  justify-content: center;
  flex-wrap: wrap;
  gap: 30px;
}

.schedule-info .info-card {
  position: relative; /* make card a positioning context */
  background: #fff;
  border: 1px solid #b5b08e;
  border-radius: 20px;
  width: 300px;
  padding: 40px 20px 30px; /* extra top padding for content */
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
}

.schedule-info .info-card .icon {
  position: absolute;      /* position relative to card */
  top: -27px;              /* half of icon height to center on border */
  left: 50%;               /* horizontally center */
  transform: translateX(-50%); /* perfectly center horizontally */
  background-color: #3c1053;
  color: #fff;
  font-size: 28px;
  border-radius: 50%;
  width: 55px;
  height: 55px;
  line-height: 55px;
  z-index: 1;
  box-shadow: 0 2px 8px rgba(0,0,0,0.15); /* optional: add subtle shadow */
}


.schedule-info .info-card .label {
  color: #aaa;
  font-size: 1.1rem;
  margin-bottom: 8px;
  position: relative;
}

.schedule-info .info-card .label::after {
  content: "";
  display: block;
  width: 60%;
  height: 1px;
  background: #ccc;
  margin: 6px auto;
}

.schedule-info .info-card .value {
  color: #3c1053;
  font-size: 1.2rem;
  font-weight: 500;
  line-height: 1.6;
}

.schedule-info .info-footer {
  font-weight: 600;
  margin-top: 30px;
  font-size: 1.1rem;
}

/* --- Responsive --- */
@media (max-width: 768px) {
  .schedule-info .info-cards {
    flex-direction: column;
    align-items: center;
  }

  .schedule-info .info-card {
    width: 90%;
  }
}
.schedule-header .divider {
    width: 60%;               /* length of line */
    height: 3px;              /* thickness */
    background-color: #3c1053;
    border: none;
    margin: 10px auto 0;      /* spacing and center */
    border-radius: 2px;       /* optional rounded ends */
}
.header-team{

    text-align: center;
    color: #3a0b52;
}
.scroll-image-section {
    height: 500px; /* adjust as needed */
    width: 100%;
    background-image: url('{{ asset("public/assets/images/Encore_Aravind_Edited-min.jpg") }}');
    background-size: cover;       /* image covers the entire section */
    background-position: center;  /* center the image */
    background-attachment: fixed; /* fixed/faux parallax effect */
}
@media screen and (min-width: 2560px) {
  .container, .container-lg, .container-md, .container-sm, .container-xl, .container-xxl {
    max-width: 230rem;
  }
  .schedule-header h2 {
    font-size: 10rem;
  }
  table {
    max-width: 230rem !important;
  }

  tr {
    font-size: 5rem;
  }
  .column h3 {
    width: none;
    font-size: 5rem;
  }
  .column ul li {
    font-size: 4rem;
  }
  .register {
    width: auto;
    font-size: 5rem;
  }
  .schedule-info .info-top {
    font-size: 5rem !important;
  }

  .schedule-info .info-card {
    width: fit-content;
  }
  .schedule-info .info-card .label {
    font-size: 8rem;
  }

  .schedule-info .info-card .icon {
    font-size: 60px;
    width: fit-content;
    height: fit-content;
  }

  .schedule-info .info-card .value {
    font-size: 5rem;
  }

  .h4, h4 {
    font-size: 4rem;
  }
  .schedule-info .info-footer {
    font-size: 4rem !important;
  }


}

</style>

<!-- Hero Section -->
<section class="hero-section">
    <img src="{{ asset('public/assets/images/Aces_fall league 2026 (1).png') }}" alt="Our Mission">
    <div class="overlay"></div>
</section>

{{-- <!-- Schedule Header Section -->
<section class="schedule-header">
    <h2>2025 SACRAMENTO FALL LEAGUE SCHEDULE</h2>
</section>

<!-- Schedule Table Section -->
<section class="schedule-table" style="padding: 50px 20px; text-align: center;">
    <table style="width: 100%; border-collapse: collapse; max-width: 1000px; margin: 0 auto;">
        <thead>
            <tr style="background-color: #e9e9e9ff; color: #383838ff;">
                <th style="padding: 12px; border: 1px solid #ddd;">Date</th>
                <th style="padding: 12px; border: 1px solid #ddd;">Time</th>
                <th style="padding: 12px; border: 1px solid #ddd;">North Field</th>
                <th style="padding: 12px; border: 1px solid #ddd;">South Field</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>October 5th</td>
                <td>11:00 AM</td>
                <td>Blue Devils vs TNT</td>
                <td>Bears vs Arcade</td>
            </tr>
            <tr>
                <td>October 5th</td>
                <td>12:00 PM</td>
                <td>Bears vs TNT</td>
                <td>Arcade vs Douglas Lacrosse</td>
            </tr>
            <tr>
                <td>October 5th</td>
                <td>1:00 PM</td>
                <td>Blue Devils vs Invictus</td>
                <td>Chubby Unicorns vs Douglas Lacrosse</td>
            </tr>
            <tr>
                <td>October 12th</td>
                <td>11:00 AM</td>
                <td>Invictus vs Arcade</td>
                <td>TNT vs Chubby Unicorns</td>
            </tr>
            <tr>
                <td>October 12th</td>
                <td>12:00 PM</td>
                <td>Arcade vs TNT</td>
                <td>Chubby Unicorns vs Blue Devils</td>
            </tr>
            <tr>
                <td>October 19th</td>
                <td>11:00 AM</td>
                <td>Invictus vs Douglas</td>
                <td></td>
            </tr>
            <tr>
                <td>October 19th</td>
                <td>12:00 PM</td>
                <td>Bears vs Douglas</td>
                <td></td>
            </tr>
            <tr>
                <td>October 26th</td>
                <td>11:00 AM</td>
                <td>Bears vs Blue Devils</td>
                <td></td>
            </tr>
            <tr>
                <td>October 26th</td>
                <td>12:00 PM</td>
                <td>Blue Devils vs Arcade</td>
                <td></td>
            </tr>
            <tr>
                <td>October 26th</td>
                <td>1:00 PM</td>
                <td>Arcade vs Chubby Unicorns</td>
                <td></td>
            </tr>
            <tr>
                <td>November 2nd</td>
                <td>11:00 AM</td>
                <td>Douglas vs Blue Devils</td>
                <td>Chubby Unicorns vs Bears</td>
            </tr>
            <tr>
                <td>November 2nd</td>
                <td>12:00 PM</td>
                <td>Chubby Unicorns vs Arcade</td>
                <td>Douglas vs Bears</td>
            </tr>
        </tbody>
    </table>
</section> --}}
<section class="schedule-section">
  <div class="container">
    <div class="info-columns">
      <!-- Divisions Column -->
      <div class="column">
        <h3>DIVISIONS :</h3>
        <ul>
          <li>High School JV</li>
          <li>High School Varsity</li>
        </ul>
      </div>

      <!-- League Games Column -->
      <div class="column">
        <h3>LEAGUE GAMES :</h3>
        <ul>
          <li>
            Sundays: <br>
            September 27th to November 8th <br>12pm - 4pm <br
            <strong>Each team will play 6 league games</strong>
          </li>
        </ul>
      </div>
    </div>

    <!-- Registration Button -->
    <div class="button-wrapper">
      <a href=" https://registration.teamsnap.com/form/73360" class="register" target="_blank" rel="noopener">TEAM REGISTRATION</a>
    </div>
  </div>
</section>
<section class="schedule-header">
    <h2> Double headers will be scheduled on 3 of the season weekends for a total of 6 games plus the season ending tournament on November 8th.</h2>
</section>
<section class="schedule-info">
  {{-- <div class="info-top">
    Weekly league games | End of season tournament | Certified officials | Medical trainer
  </div> --}}

  <div class="info-cards">
    <!-- Card 1 -->
    <div class="info-card">
      <div class="icon"><i class="bi bi-currency-dollar"></i></div>
      <h5 class="label">cost</h5>
      <p class="value">Team Fees<br><strong>$2350.00 per team</strong></p>
    </div>

    <!-- Card 2 -->
    <div class="info-card">
      <div class="icon"><i class="bi bi-geo-alt-fill"></i></div>
      <h5 class="label">location</h5>
      <p class="value">
        Mather Fields<br>
        3755 Schriever Ave<br>
        Rancho Cordova,<br>
        CA 95655
      </p>
    </div>

    <!-- Card 3 -->
    <div class="info-card">
      <div class="icon"><i class="bi bi-calendar-event"></i></div>
      <h5 class="label">dates</h5>
      <p class="value">
        September 27th<br>
        to<br>
        November 8th<br>
        Sundays
      </p>
    </div>
  </div>

  {{-- <p class="info-footer">Sunday, November 8th</p> --}}
</section>
<!-- Schedule Header Section -->
<section class="schedule-header">
    <h2>END OF SEASON TOURNAMENT</h2>
</section>
<hr class="divider">

<section class="header-team">
    <h4>Each team will play 3 games in a championship style format.</h4><br>
</section>

    <!-- Registration Button -->
    <div class="button-wrapper">
      <a href=" https://registration.teamsnap.com/form/73360" class="register" target="_blank" rel="noopener">TEAM REGISTRATION</a>
    </div>
  </div><br>
<section class="scroll-image-section">
    <div class="image-wrapper">

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
