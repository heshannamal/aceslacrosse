@extends('layouts.app')

@section('title', 'Home - ACES Lacrosse')

@section('content')
<style>


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

.aces-club-item p {
    text-align: justify;
    margin-bottom: 15px;
    font-size: 17px !important;
    line-height: 1.6em !important;
    letter-spacing: .63px !important;
    color: #121212bf !important;
    text-transform: none !important;
    font-weight: 400 !important;
    font-family: Bitter !important;
    padding-left: 0 !important;
}

h2.fw-bold.mb-5.text-uppercase.title {
    font-size: 48px;
}

h4.fw-bold.text-uppercase.sub-title {
  font-size: 30px;
}


/* larage screen  */
@media screen and (min-width: 2560px) {
  .container, .container-lg, .container-md, .container-sm, .container-xl, .container-xxl {
    max-width: 230rem;
  }

  h2.fw-bold.mb-5.text-uppercase.title {
    font-size: 10rem;
  }
  h4.fw-bold.text-uppercase.sub-title {
      font-size: 7rem;
  }

  img.Aces.Academy {
      width: 100%;
      height: fit-content;
  }

  img.Elite.Travel.Teams {
      width: 100%;
      height: fit-content;
  }

  img.Pinnacle.Recruiting {
      width: 100%;
      height: fit-content;
  }

  p.text-muted {
    text-align: justify;
    margin-bottom: 15px;
    font-size: 80px !important;
    line-height: 1.6em !important;
    letter-spacing: .63px !important;
    color: #121212bf !important;
    text-transform: none !important;
    font-weight: 400 !important;
    font-family: Bitter !important;
    padding-left: 0 !important;
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


<!-- Hero Section -->
<section class="hero-section">
    <img src="{{ asset('public/assets/images/hero-bg.jpg') }}" alt="Aces Team">
    </div>
</section>

<!-- Evaluation Section -->
{{-- <section class="evaluation-section" style="background-image: {{ asset("public/assets/images/aces-evaluation-bg.jpg") }}">
  <div class="evaluation-overlay"></div>
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
<section class="aces-club-section py-5">
  <div class="container-fluid text-center">
    <h2 class="fw-bold mb-5 text-uppercase title" style="color:#611eb2;">ACES LACROSSE CLUB</h2>

    <div class="cards-container">
      <!-- Card 1 -->
      <div class="aces-club-item">
        <h4 class="fw-bold text-uppercase sub-title" style="color:#611eb2;">Aces Academy</h4>
        <img src="{{ asset('public/assets/images/sac 1.jpg') }}" class="Aces Academy" alt="Aces Academy">
        <p class="text-muted">
          ACES Academy is the Sacramento Valley’s premier lacrosse training organization. ACES Academy consists of bi-weekly training sessions designed to enhance skill, athletic ability, and lacrosse acumen. No detail is too small when it comes to developing a player’s game. Training and player development are the cornerstones of ACES Lacrosse....
          <br><a href="https://aceslacrosse.com/pages/academy" class="text-decoration-none fw-semibold" style="color:#611eb2;">Read More</a>
        </p>
      </div>

      <!-- Card 2 -->
      <div class="aces-club-item">
        <h4 class="fw-bold text-uppercase sub-title" style="color:#611eb2;">Elite Travel Teams</h4>
        <img src="{{ asset('public/assets/images/sac 3.jpg') }}" class="Elite Travel Teams" alt="Elite Travel Teams">
        <p class="text-muted">
          Sacramento ACES travel team roster are selected on a bi-annual basis with tryouts held in February and August.  Teams travel to regional events both locally and across the west coast. Players training in the ACES Academy also have the opportunity to move up and be selected for a team roster. All Elite Travel Club members train in the ACES Academy....
          <br><a href="https://aceslacrosse.com/pages/travel-teams" class="text-decoration-none fw-semibold" style="color:#611eb2;">Learn More</a>
        </p>
      </div>

      <!-- Card 3 -->
      <div class="aces-club-item">
        <h4 class="fw-bold text-uppercase sub-title" style="color:#611eb2;">Pinnacle Recruiting</h4>
        <img src="{{ asset('public/assets/images/sac 2.jpg') }}" class="Pinnacle Recruiting" alt="Pinnacle Recruiting">
        <p class="text-muted">
          Pinnacle Teams are formed for top players looking to play lacrosse in college. Teams are comprised of players across the Nor Cal region, as well as from other states. Pinnacle teams also compete in select fall/winter events with a focus on recruiting....
          <br><a href="https://pinnaclelax.com" class="text-decoration-none fw-semibold" style="color:#611eb2;">Learn More</a>
        </p>
      </div>
    </div>
  </div>
</section>

@include('layouts.instargram')





@endsection
