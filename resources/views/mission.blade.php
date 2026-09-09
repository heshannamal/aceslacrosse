@extends('layouts.app')

@section('title', 'Mission - ACES Lacrosse')

@section('content')
<style>
/* ---- Hero Section ---- */
.hero-section {
    position: relative;
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

/* ---- Mission Content ---- */
.mission-section {
    max-width: 80rem;
    margin: 50px auto;
    padding: 0 20px;
}

.mission-section h2 {
    text-align: center;
    font-size: 24px;
    color: #333;
    margin-top: 25px;
    margin-bottom: 15px;
    text-transform: uppercase;
    font-weight: bold;
    text-decoration: underline;
}

.mission-section p {
    font-size: 16px;
    line-height: 1.7;
    color: #555;
    font-weight: bold;
    text-align: justify;
    margin-bottom: 15px;
}
.mission-section iframe {
    width: 100%;            /* match mission-section width */
    max-width: 100%;        /* prevent overflow */
    height: auto;           /* auto height based on width */
    aspect-ratio: 16/9;     /* maintain proper video ratio */
    display: block;
    margin: 20px auto 0 auto; /* center iframe and add top spacing */
}
/* Responsive */

@media screen and (min-width: 2560px) {
  .container, .container-lg, .container-md, .container-sm, .container-xl, .container-xxl {
    max-width: 230rem;
  }

  h2.mission-title {
    font-size: 10rem;
}
.mission-section {
    /* font-size: 5rem; */
    max-width: 230rem;
    margin: 50px auto;
    padding: 0 20px;
}
p.paragraph {
    font-size: 5rem;
}



  

}


</style>


<section class="hero-section">
    <img src="{{ asset('public/assets/images/aces 3.jpg') }}" alt="Our Mission">
    <div class="overlay"></div>
    <div class="hero-text">
    </div>
</section>


<section class="mission-section">

    <h2 class="mission-title">Philosophy</h2>
    <p class="paragraph">
        As we enter our 13th season, the Sacramento ACES are here to provide the best coaching and knowledge of the game available, and to have all the found in a staff consisting of coaches that not only live, but have a passion for being a part of the Sacramento region. After a highly successful 2025 season of growth of all our players skills and lacrosse IQ, the ACES are poised to take further steps to turn Sacramento into a hotbed of lacrosse. We know each player is different, and it is our mission to provide a fun, competitive and hardworking atmosphere that will connect and pull the best out of our players.
    </p>

    <h2 class="mission-title">Scholarship</h2>
    <p class="paragraph">
        Encore Lacrosse is extremely proud to announce the Frank Resetarits Scholarship Fund. This fund has been created in Frank’s name, with the lead of his own coaching staff in Sacramento, to support ACES Lacrosse players who exemplify the club’s standards of excellence on and off the field, as well as have a financial need to participate in club training and tournaments. If you are interested in contributing to the fund, or applying for the merit and need-based scholarship, please send an email to <a href="mailto:info@encorelacrosse.com">info@encorelacrosse.com</a>.
    </p>

    <!-- Optional Video -->
    <div class="text-center mt-4">
        <iframe width="910" height="513" src="https://www.youtube.com/embed/hE47xhjqNuE" title="Sacramento Aces - Frank Resetarits Scholarship Fund" frameborder="0" allowfullscreen></iframe>
    </div>
</section>
@endsection
