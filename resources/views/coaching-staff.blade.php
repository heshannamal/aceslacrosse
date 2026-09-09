@extends('layouts.app')

@section('title', 'Coaching Staff - ACES Lacrosse')

@section('content')
<style>

.leadership-section .coach-img {
    width: 95%;   /* TAKE FULL COLUMN WIDTH */
    height: auto;
    display: block;
}


/* Reduce gap between rows */
.leadership-section .row {
    margin-top: 25px !important; /* reduce from mt-5 (80px) */
}

.leadership-section .g-4 {
    --bs-gutter-y: 1rem; /* reduce vertical spacing */
    --bs-gutter-x: 1rem;
}
.section-title {
    color: #5620b3ff;
    position: relative;
    display: inline; /* allow multi-line underline */
    padding-bottom: -2px; /* space for underline */
    border-bottom: 3px solid #000; /* full-width underline */
}

.section-title::after {
    content: none; /* remove old underline */
}

.purple {
    color: #5620b3ff !important;
}
@media (max-width: 767px) {
    /* Make each column full width */
    .leadership-section .col-6 {
        flex: 0 0 100% !important;
        max-width: 100% !important;
    }

    /* Remove gaps between images */
    .leadership-section .row.g-4 {
        --bs-gutter-x: 0 !important;
        --bs-gutter-y: 0 !important;
    }

    /* Make images full width */
    .leadership-section .coach-img {
        width: 100%;
        margin: 0; /* remove any extra margin */
    }
}
/* Section Title */
.director-section {
    margin-left: 90px;
}
.director-section .section-title {
    font-weight: 800;
    color: #5a1faf;
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* Image */
.director-photo {
    width: 100%;
    object-fit: cover;
    max-height: 480px; /* prevents image being too tall */
}

/* Right-side spacing */
.director-content {
    padding-left: 15px; /* bring text closer to image */
}

.director-info {
    font-weight: 600; /* similar to .director-name */
    color: black;   /* or any color you want */
    text-transform: none; /* remove uppercase if needed */
    letter-spacing: 0.5px;
    margin: 0 0 0 -390px;
}


.director-email a {
    color: #0f1831ff;
    text-decoration: underline;
    font-size: 1rem;
}

/* Bio Text */
.director-bio {
    text-align: justify;
    max-width: 570px;
    line-height: 1.65;
    font-size: 17px;
    margin-top: 0px;
    padding-right: 20px; /* makes paragraph height match image better */
}

/* MOBILE */
@media (max-width: 768px) {
    .director-content {
        padding-left: 0;
        margin-top: 20px;
    }
}
.career-wrapper {
    max-width: 1200px;
    margin: 0 auto;
    padding: 40px 20px;

}

.career-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 40px 0px; /* vertical 40px, horizontal 50px */
    margin-left: -50px;


}
.career-grid > div:nth-child(2) {
  margin-left: -60px; /* tweak this value until it lines up */
}
.career-block {
    text-align: left;
    margin-bottom: 50px; /* space between sections */

}

.career-block h6 {
    font-size: 1.2rem;
    font-weight: 700;
    margin-bottom: 12px;
}

.career-block ul {
    padding-left: 20px;
    margin: 0;
    margin-left: 50px;

}

.career-block ul li {
    margin-bottom: 8px;
    line-height: 1.55;
    word-break: normal;  /* text wraps naturally */
    line-height: 1.55;
    width: 420px;        /* all <li> same width */
    display: list-item;
    font-size: 1.2rem;
    margin-left: 40px;

}

/* Mobile view */
@media (max-width: 991px) {
    .career-grid {
        grid-template-columns: 1fr;  /* stack columns */
        gap: 40px;
        margin-left: -10px;
    }

    .career-block ul li {
        width: auto;         /* override desktop width */
        max-width: 100%;     /* take full container width */
        word-break: break-word; /* wrap long words if needed */
    }

    .career-block ul {
        margin-left: 20px;   /* proper bullet indent on mobile */
    }
    .career-grid > div:nth-child(2) {
     margin-left: -10px; /* tweak this value until it lines up */
}
}


.parents-section .accordion-button {
  font-size: 16px;
  font-weight: 600;
  color: #000000ff;
  background: #fff;
  border: none;
  border-radius: 0;
  box-shadow: none;
  border-bottom: 1px solid #ccc; /*  only bottom border */
  padding: 18px 0;
  padding-left: 25px;
}

.parents-section .accordion-button::after {
  filter: brightness(0); /* black arrow */
  color: #000;
}

.parents-section .accordion-button:not(.collapsed) {
  color: #000;
  background: #fff; /* keep white background when open */
  box-shadow: none;
}

.parents-section .accordion-item {
  border: none; /* remove Bootstrap default border */

}

.parents-section .accordion-body {
  font-size: 15px;
  color: #000000ff;
  background: #fff;
  border-top: none;
  padding-top: 0;
  align-items: justify;


}
.parents-section .col-md-6 {
    max-width: 550px;  /* limit column width */


}
.parents-section {
    margin-left:80px;  /* adjust the px as needed */
}
@media (max-width: 768px) {
    /* Director section overall spacing */
    .director-section {
        margin-left: 0 !important;
        padding: 0 15px; /* clean side padding */
        text-align: center;
    }

    /* Director title */
    .director-section .section-title {
        text-align: center !important;
        margin-left: 0;
        font-size: 22px;
    }

    /* Director image */
    .director-photo {
        width: 100%;
        max-width: 330px;
        margin: 0 auto 20px;
        display: block;
        margin: 0;
        padding: 0;
    }

    /* Director name styling */
    .director-info a {
        display: block;       /* forces email to new line */

    }
    .director-info  {
        margin-left: -110px;
        margin-top: -40px;
    }
    .director-bio  {
        margin-left: 10px;

    }
    .director-name {
        display: inline-block;
        margin-left: -50px;
    }


    /* Fix columns: image on top, text below */
    .director-section .row {
        flex-direction: column;
        align-items: center;
    }

    /* Remove desktop spacing */
    .director-section .col-lg-5,
    .director-section .col-md-6 {
        margin-left: 0 !important;
        max-width: 100%;
    }
}


@media (max-width: 768px) {
    .parents-section {
        margin-left: 0 !important;
    }

    .parents-section .row {
        margin-left: 0 !important;
    }

    .parents-section .col-md-6 {
        max-width: 100% !important;
    }

    .accordion-button {
        text-align: left;
        padding-left: 15px;
    }
}

.accordion-body {
    text-align: left;
}

li.coashingstaff-text {
    font-size: 16px;
}

p.coashingstaff-text {
    font-size: 16px;
}

@media screen and (min-width: 2560px) {
  .container, .container-lg, .container-md, .container-sm, .container-xl, .container-xxl {
    max-width: 230rem;
  }

  h2.section-title.mb-4 {
    font-size: 10rem;
  }

  p.lead-text.mb-3 {
      font-size: 4rem;
  }
  p.lead-text.mb-4 {
    font-size: 4rem;
  }

  h6.fw-bold.mt-5 {
    font-size: 8rem;
  }

  .stats.mt-3 {
    font-size: 4rem;
  }

  .director-bio {
    text-align: justify;
    max-width: fit-content;
    line-height: 1.65;
    font-size: 75px;
    margin-top: 0px;
    padding-right: 20px;
}

  .director-photo {
      width: 100%;
      object-fit: cover;
      max-height: fit-content;
  }

  .career-wrapper {
      max-width: 230rem;
  }

  .career-block h6 {
    font-size: 5.2rem;
    font-weight: 700;
    margin-bottom: 12px;
  }

  .career-block ul li {
    font-size: 4.2rem;
    width: fit-content;

  }

  p.coashingstaff-text {
    font-size: 4rem;
  }

  .parents-section .col-md-6 {
    font-size: 6rem;
    max-width: none;
  }


  button.accordion-button.collapsed {
    font-size: 4rem;
  }

  button.accordion-button {
    font-size: 4rem !important;
  }

  li.coashingstaff-text {
    font-size: 4rem;
  }



}

</style>

<!-- Hero Section -->
<section class="hero-section">
  <img src="{{ asset('public/assets/images/Coachingstaff.jpg') }}" alt="Aces Team">

</section>
<!-- Leadership Section -->
<section class="leadership-section py-5">
    <div class="container text-center">

        <h2 class="section-title mb-4">SACRAMENTO ACES LEADERSHIP</h2>
        <br><br>
        <p class="lead-text mb-3">
            Over the past twelfth years the Sacramento ACES have assembled the premier coaching staff in the Sacramento Valley.
            Harkening back to their own prior<br> experiences as players, ACES coaches focus on creating a fun, competitive environment
            that promotes development over "x's and o's". We feel players learn more<br> through reps and activity than standing and watching.
        </p>

        <p class="lead-text mb-4">
            Creating a consistent culture and approach in leadership and providing opportunities that impact lives beyond the
            lacrosse field are top priorities for the ACES<br> program. The Sacramento ACES culture starts at the top with the
            connection the coaching staff have with one another.
        </p>

        <h6 class="fw-bold mt-5">2026 ACES Coaches by the Numbers</h6>

        <div class="stats mt-3">
            <p><strong class="purple">500</strong> - Sacramento ACES coaches have over 500 years of combined playing and coaching experience</p>
            <p><strong class="purple">191</strong> - Sacramento ACES players who have gone on to play collegiate lacrosse</p>
            <p><strong class="purple">10</strong> - Former collegiate All Americans that make up the Sacramento ACES coaching staff</p>
            <p><strong class="purple">96</strong> - Seasons of collegiate coaching experience on the Sacramento ACES staff</p>
            <p><strong class="purple">13</strong> - Different states Sacramento ACES coaches originate from</p>
            <p><strong class="purple">49</strong> - Sacramento ACES alumni who have returned to coach and train (all years)</p>
            <p><strong class="purple">75</strong> - Championships Sacramento ACES teams have won</p>
            <p><strong class="purple">14</strong> - Years as a Club</p>
        </div>

        <!-- Image Row -->
<div class="row mt-5 g-4">

    <div class="col-md-3 col-6">
        <img src="{{ asset('public/assets/images/download2.avif') }}" class="coach-img">
    </div>

    <div class="col-md-3 col-6">
        <img src="{{ asset('public/assets/images/download (3).avif') }}" class="coach-img">
    </div>

    <div class="col-md-3 col-6">
        <img src="{{ asset('public/assets/images/download (4).avif') }}" class="coach-img">
    </div>

    <div class="col-md-3 col-6">
        <img src="{{ asset('public/assets/images/download (5).avif') }}" class="coach-img">
    </div>

</div>

<div class="row mt-5 g-4">

    <div class="col-md-3 col-6">
        <img src="{{ asset('public/assets/images/download (6).avif') }}" class="coach-img">
    </div>

    <div class="col-md-3 col-6">
        <img src="{{ asset('public/assets/images/download (7).avif') }}" class="coach-img">
    </div>

    <div class="col-md-3 col-6">
        <img src="{{ asset('public/assets/images/download (8).avif') }}" class="coach-img">
    </div>

    <div class="col-md-3 col-6">
        <img src="{{ asset('public/assets/images/download (9).avif') }}" class="coach-img">
    </div>

</div>
<br><br>
<h2 class="section-title mb-4 text-center">SACRAMENTO ACES DIRECTOR</h2>
<section class="director-section container py-5">



    <div class="row align-items-start gx-4">
      <!-- LEFT IMAGE -->
      <div class="col-lg-5 col-md-6 mb-4 mb-md-0">
          <img src="{{ asset('public/assets/images/download (10).avif') }}" class="img-fluid director-photo" alt="Director Photo">
      </div>

      <!-- RIGHT CONTENT -->
      <div class="col-lg-7 col-md-6 director-content">

        <p class="director-info">
          <span class="director-name">Franks Resetarits |</span>
          <a href="mailto:Frank@EncoreBrand.com">Frank@EncoreBrand.com</a>
        </p>

        <p class="director-bio">
            <strong>Biography |</strong> Frank grew up in Hamburg NY just outside of Buffalo. He went to
            Hamburg High School, and grew up playing both box and field lacrosse where he was a two time
            High School All American as well as an Canadian Junior "A" All Star. After his high school
            career, he went on to the University at Albany. Albany won three America East Conference
            Championships during Frank's tenure. Albany finished the 2007 season ranked #4 in Division 1.
            The team’s success led to Frank being chosen as America East Conference Player of the year,
            1st team All American and a Tewaaraton Trophy finalist, awarded to the nation's top player.
            Coach Resetarits went on to be drafted by the Long Island Lizards of Major League Lacrosse
            and the San Jose Stealth of the National Lacrosse League. He would eventually be traded to
            his hometown team (Buffalo Bandits). As a coach, Frank has mentored dozens of All Americans
            in High School and College on both coasts. Frank has been the Head Coach of the UC Davis Men's
            team since 2016. During his tenure the Aggies made their first appearance in the MCLA National
            Playoff in 2018. In 2019 they followed that up with the first WCLL Conference Championship in
            program history as well as another MCLA National Tournament appearance. Due to the success of
            the Aggies, Frank was named WCLL Coach of the Year in 2016, 2018 and 2020. In 2018 Frank also
            joined the staff of the NCAA Division 1 Woman's team at UC Davis where he works closely with
            the Aggie attack. Frank lives in Sacramento with his wife Heather and he eats, sleeps and
            breaths the Buffalo Bills.
        </p>

      </div>
    </div>

<section class="career-wrapper">

    <div class="career-grid">

        <!-- LEFT COLUMN -->
        <div>

            <div class="career-block">
                <h6>Professional Career:</h6>
                <ul>
                    <li>2007–2009 Long Island Lizards – Major League Lacrosse</li>
                    <li>2008–2010 San Jose Stealth – National Lacrosse League</li>
                    <li>2010–2012 Buffalo Bandits – National Lacrosse League</li>
                    <li>2011 Brampton Excelsiors – Canadian Lacrosse Association</li>
                    <li>2011 Mann Cup Champion</li>
                </ul>
            </div>

            <div class="career-block">
                <h6>Collegiate Career:</h6>
                <ul>
                    <li>4-year starter at University at Albany</li>
                    <li>4× All America East Conference (3× 1st team)</li>
                    <li>2× team captain</li>
                    <li>2007 1st team Division 1 All American</li>
                    <li>2007 Tewaraton Trophy Finalist</li>
                    <li>2007 America East Player of the Year</li>
                    <li>2007 UAlbany Athlete of the Year</li>
                    <li>2006 NCAA Scoring Champion</li>
                    <li>Finished top 20 in all-time NCAA goal scorers (159)</li>
                    <li>2nd all-time in UAlbany points (242)</li>
                    <li>UAlbany Athletic Hall of Fame Inductee – 2023</li>
                </ul>
            </div>

        </div>

        <!-- RIGHT COLUMN -->
        <div>

            <div class="career-block">
                <h6>High School / Junior Box Lacrosse Career:</h6>
                <ul>
                    <li>2× High School All American</li>
                    <li>Finished 3rd in NY State history in career goals: 292; 9th in points (419)</li>
                    <li>2001–2003 Welland Warlords Junior B Box Lacrosse</li>
                    <li>2003 Junior B All Star Game MVP</li>
                    <li>2004–06 St. Catharines Athletics Junior A Box Lacrosse</li>
                    <li>2006 OLA Junior A All Star</li>
                    <li>2006 St. Catharines Team MVP</li>
                </ul>
            </div>

            <div class="career-block">
                <h6>Coaching Career:</h6>
                <ul>
                    <li>2010–2012 Eden (NY) High School – Assistant Head Coach</li>
                    <li>2010–2012 Champion Lacrosse Co-Director</li>
                    <li>2012– Notre Dame de Namur – Asst. Coach (NCAA D2)</li>
                    <li>2013– American River College – Head Coach (CJCLA)</li>
                    <li>2013–2015 University of Pacific Head Coach (MCLA)</li>
                    <li>2016–Present UC Davis Men’s Head Coach (MCLA)</li>
                    <li>2018–Present UC Davis Women’s Asst. Coach (NCAA D1)</li>
                    <li>WCLL Coach of the Year: 2016, 2018, 2020, 2023, 2025</li>
                </ul>
            </div>

        </div>

    </div>

</section>
</section>

    <h2 class="section-title mb-4 text-center">COACHING STAFF</h2>
<section class="parents-section container my-5">


  <div class="row">
    <!-- LEFT COLUMN -->
    <div class="col-md-6">
      <div class="accordion" id="accordionLeft">
        <!-- LEFT NAMES -->
        <div class="accordion-item">
          <h2 class="accordion-header" id="headingOne">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
              RYAN FRISCH
            </button>
          </h2>
          <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionLeft">
            <div class="accordion-body">
              <p class="coashingstaff-text">
                4 year starter at Rensselear Polytechnic Institute (RPI)
              </p>
              <p class="coashingstaff-text">
                2005 NCAA Division 3 All American
              </p>
              <p class="coashingstaff-text">
                3 time All Liberty League Selection (2 time 1st team)
              <p>
              <p class="coashingstaff-text">
                Team CaptainMidfielder for Boston Crabs-American Lacrosse League2001
              <p>
              <p class="coashingstaff-text">
                High School All American
              <p>
              <p class="coashingstaff-text">
                2001 NEPSAC All Star
              <p>
              <p class="coashingstaff-text">
                2001 Kimball Award Winner
              <p>
              <p class="coashingstaff-text">
                ACES coach since 2014
              </p>
            </div>
          </div>
        </div>

        <div class="accordion-item">
          <h2 class="accordion-header" id="headingTwo">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">
              LORNE SILVERSTEIN
            </button>
          </h2>
          <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionLeft">
            <div class="accordion-body">
              <p class="coashingstaff-text">
                1984-1986 Syracuse University Midfield/Defenseman
              </p>
              <p class="coashingstaff-text">
                1980-1983 McDonogh High School (MD)- Team Captain
              </p>
              <p class="coashingstaff-text">
                Men’s Head Coach Syracuse Club -1987
              </p>
              <p class="coashingstaff-text">
                Men’s Head Coach UC Davis 2005-2006
              </p>
              <p class="coashingstaff-text">
                Private Lacrosse Trainer, 2007-Present
              </p>
              <p class="coashingstaff-text">
                Varsity Head Coach Davis High School 2008-2009
              </p>
              <p class="coashingstaff-text">
                Chico State Defensive Coordinator 2008-2009
              </p>
              <p class="coashingstaff-text">
                Davis Lacrosse Association, U15 Head Coach 2011-2013, Summer Camp and Youth Instruction Coordinator 2010-2014
              </p>
              <p class="coashingstaff-text">
                Davis High School, Junior Varsity Head Coach 2015
              </p>
              <p class="coashingstaff-text">
                Board Member, U.S. Wheelchair Lacrosse Association, 2017-2019
              </p>
              <p class="coashingstaff-text">
                Jesuit High School Varsity Head Coach 2018-present
              </p>
              <p class="coashingstaff-text">
                Pirates Lacrosse Club, Head Coach/Program Director. 2018-Present
              </p>

              <p class="coashingstaff-text">
                ACES coach since 2013
              </p>
            </div>
          </div>
        </div>

        <div class="accordion-item">
          <h2 class="accordion-header" id="headingThree">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree">
              CLIFF HU
            </button>
          </h2>
          <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionLeft">
            <div class="accordion-body">
              <p class="coashingstaff-text">
               4 Year Player at Lynchburg College
              </p>
              <p class="coashingstaff-text">
               2 year starter at Robinson Secondary School (VA)
              </p>
              <p class="coashingstaff-text">
               2007 Midfield MVPAssistant Coach Davis High School
              </p>
              <p class="coashingstaff-text">
               ACES coach since 2014
              </p>
              <p class="coashingstaff-text">
                UC Davis Assistant Head Coach - 2021 - present
              </p>
            </div>
          </div>
        </div>

        <div class="accordion-item">
          <h2 class="accordion-header" id="headingFour">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour">
              JOSH RODEN
            </button>
          </h2>
          <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionLeft">
            <div class="accordion-body">
             <ul>
              <li class="coashingstaff-text">Playing:</li>
              <li class="coashingstaff-text">4 year varsity @ Miramonte HS in Orinda</li>
              <li class="coashingstaff-text">High School All American 2006</li>
              <li class="coashingstaff-text">3 year captain @ Chico State</li>
              <li class="coashingstaff-text">3 year all league WCLL</li>
              <li class="coashingstaff-text">Timperley lacrosse team 2012-2014</li>
              <li class="coashingstaff-text">English National team 2013-2017</li>
              <li class="coashingstaff-text">5th @ Denver World Championships ’14</li>
              <li class="coashingstaff-text">Gold medal @ European Championships in Hungary ’16</li>
             </ul>
             <ul>
               <li class="coashingstaff-text">Playing:</li>
               <li class="coashingstaff-text">England Academy Coach 2013/14</li>
               <li class="coashingstaff-text">Manchester University Head Coach 2013</li>
               <li class="coashingstaff-text">Chico State Head coach 2015-2020</li>
               <li class="coashingstaff-text">Chico Rebels Director 2018-2020</li>
               <li class="coashingstaff-text">UC Davis Offensive Coordinator - 2022 - present</li>
             </ul>
            </div>
          </div>
        </div>

        <div class="accordion-item">
          <h2 class="accordion-header" id="headingFive">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive">
              JARED STOWERS
            </button>
          </h2>
          <div id="collapseFive" class="accordion-collapse collapse" data-bs-parent="#accordionLeft">
            <div class="accordion-body">
              <ul>
                <li class="coashingstaff-text">University of Nevada Reno Starting Defenseman</li>
                <li class="coashingstaff-text">American River College Starting Defenseman</li>
                <li class="coashingstaff-text">2018- present - Head Coach Casa Roble</li>
              </ul>
            </div>
          </div>
        </div>

        <div class="accordion-item">
          <h2 class="accordion-header" id="headingSix">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix">
              CAMERON RIDDELL
            </button>
          </h2>
          <div id="collapseSix" class="accordion-collapse collapse" data-bs-parent="#accordionLeft">
            <div class="accordion-body">
             <ul>
               <li class="coashingstaff-text">Starter/team Captain - Quince Orchard High School (MD)</li>
                <li class="coashingstaff-text">2x All Conference</li>
                <li class="coashingstaff-text">2nd team All Montgomery County</li>
                <li class="coashingstaff-text">UC Davis Starting Defender</li>
             </ul>
            </div>
          </div>
        </div>

        <div class="accordion-item">
          <h2 class="accordion-header" id="headingSeven">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSeven">
              JOJO OLIVAS
            </button>
          </h2>
          <div id="collapseSeven" class="accordion-collapse collapse" data-bs-parent="#accordionLeft">
            <div class="accordion-body">
              <ul>
                <li class="coashingstaff-text">2017 San Joaquin Conference Player of the Year - St. Mary's</li>
                <li class="coashingstaff-text">2018-19 - University of Utah - MCLA/NCAA D1</li>
                <li class="coashingstaff-text">Assistant Coach Jesuit - 2022-presen</li>
              </ul>
            </div>
          </div>
        </div>

        <div class="accordion-item">
          <h2 class="accordion-header" id="headingEight">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEight">
              JORDAN FOX-FICK
            </button>
          </h2>
          <div id="collapseEight" class="accordion-collapse collapse" data-bs-parent="#accordionLeft">
            <div class="accordion-body">
              <ul>
                <li class="coashingstaff-text">4 year starter - Elk Grove</li>
                <li class="coashingstaff-text">2014 NCJLA All Star</li>
                <li class="coashingstaff-text">2015 - Belmont Abbey College - NCAA D2</li>
                <li class="coashingstaff-text">2016-19 - Arizona State Universityh</li>
                <li class="coashingstaff-text">2020 - present - UC Davis Assistant Coach</li>
                <li class="coashingstaff-text">Jesuit Head Coach 2022 -present</li>
              </ul>
            </div>
          </div>
        </div>

        <div class="accordion-item">
          <h2 class="accordion-header" id="headingNine">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseNine">
              BLAISE CURTIS
            </button>
          </h2>
          <div id="collapseNine" class="accordion-collapse collapse" data-bs-parent="#accordionLeft">
            <div class="accordion-body">
              <ul>
                <li class="coashingstaff-text">Varsity Team Captain Aberdeen High School, MD (2013)</li>
                <li class="coashingstaff-text">Offensive MVP & All County Aberdeen High School, MD (2012, 2013)</li>
                <li class="coashingstaff-text">4 year starting attack/midfield for United States Coast Guard Academy (2013-2017)</li>
                <li class="coashingstaff-text">Captain at the United States Coast Guard Academy (2017)</li>
                <li class="coashingstaff-text">Assistant Coach at The Citadel in Charleston, SC (2017-2018)</li>
              </ul>
            </div>
          </div>
        </div>

        <div class="accordion-item">
          <h2 class="accordion-header" id="headingTen">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTen">
              JASON GRACE
            </button>
          </h2>
          <div id="collapseTen" class="accordion-collapse collapse" data-bs-parent="#accordionLeft">
            <div class="accordion-body">
              <ul>
                <li class="coashingstaff-text">2001- 2004 - Starting defender York College - NCAA D3</li>
                <li class="coashingstaff-text">2012- 2014 - Assistant Coach- Hanover College - NCAA D3</li>
                <li class="coashingstaff-text">2014- 2016 - Head Coach - Anne Arundel - NCJAA</li>
                <li class="coashingstaff-text">2016 - 2019 - Head Coach - Earlham College - NCAA D3</li>
                <li class="coashingstaff-text">2019 - 2021 - Head Coach- Potomac State - NCJAA</li>
              </ul>
            </div>
          </div>
        </div>

        <div class="accordion-item">
          <h2 class="accordion-header" id="headingEleven">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEleven">
              BRANDON KELLER
            </button>
          </h2>
          <div id="collapseEleven" class="accordion-collapse collapse" data-bs-parent="#accordionLeft">
            <div class="accordion-body">
              <ul>
                <li class="coashingstaff-text">All Conference Goalie at Jesuit</li>
                <li class="coashingstaff-text">NCAA Division 2 - Adams State</li>
                <li class="coashingstaff-text">Director of Sac NetMinders</li>
                <li class="coashingstaff-text">Head JV Coach - Jesuit - 2023 - present</li>
              </ul>
            </div>
          </div>
        </div>

        <div class="accordion-item">
          <h2 class="accordion-header" id="headingTwelve">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwelve">
              CAMDEN COOK
            </button>
          </h2>
          <div id="collapseTwelve" class="accordion-collapse collapse" data-bs-parent="#accordionLeft">
            <div class="accordion-body">
              <ul>
                <li class="coashingstaff-text">2007-09 3 year starter, 2 time captain - California High School</li>
                <li class="coashingstaff-text">2009 Team MVP, 1st team All Conference</li>
                <li class="coashingstaff-text">2009 US Lacrosse All American</li>
                <li class="coashingstaff-text">2010-13 - Starting Defender -Sonoma State</li>
                <li class="coashingstaff-text">2013-16 - Defensive Coordinator - California High</li>
              </ul>
            </div>
          </div>
        </div>

        <div class="accordion-item">
          <h2 class="accordion-header" id="headingThirteen">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThirteen">
              JACK MEOTTI
            </button>
          </h2>
          <div id="collapseThirteen" class="accordion-collapse collapse" data-bs-parent="#accordionLeft">
            <div class="accordion-body">
              <ul>
                <li class="coashingstaff-text">Eastern Connecticut University - NCAA D3</li>
                <li class="coashingstaff-text">All Connecticut Conference Midfielder</li>
                <li class="coashingstaff-text">Connecticut Whalers - 2008-11</li>
                <li class="coashingstaff-text">Jesuit JV Coach</li>
              </ul>
            </div>
          </div>
        </div>


      </div>
    </div>

    <!-- RIGHT COLUMN -->
    <div class="col-md-6">
      <div class="accordion" id="accordionRight">
        <div class="accordion-item">
          <h2 class="accordion-header" id="headingFourteen">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFourteen">
              STEVE MCLAUGHLIN
            </button>
          </h2>
          <div id="collapseFourteen" class="accordion-collapse collapse" data-bs-parent="#accordionLeft">
            <div class="accordion-body">
              <ul>
                <li class="coashingstaff-text">4 year Defenseman at Virginia Tech</li>
                <li class="coashingstaff-text">1999 USLIA All American Defenseman</li>
                <li class="coashingstaff-text">2 time SELC All Conference</li>
                <li class="coashingstaff-text">2009-2011 Head Coach Jesuit High School Varsity Lacrosse</li>
                <li class="coashingstaff-text">2009-2012 Head Coach Ryquin Boys High School Lacrosse</li>
              </ul>
            </div>
          </div>
        </div>
        <!-- RIGHT NAMES -->
        <div class="accordion-item">
          <h2 class="accordion-header" id="headingFifteen">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFifteen">
              JAMES GUZIK
            </button>
          </h2>
          <div id="collapseFifteen" class="accordion-collapse collapse" data-bs-parent="#accordionRight">
            <div class="accordion-body">
              <ul>
                <li class="coashingstaff-text">2005 High School Academic All American</li>
                <li class="coashingstaff-text">4 year starter – UC Santa Barbara</li>
                <li class="coashingstaff-text">2009 MCLA All American</li>
                <li class="coashingstaff-text">Assistant Coach Granite Bay High School</li>
              </ul>
            </div>
          </div>
        </div>

        <div class="accordion-item">
          <h2 class="accordion-header" id="headingSixteen">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSixteen">
              CHASE HILDEBURN
            </button>
          </h2>
          <div id="collapseSixteen" class="accordion-collapse collapse" data-bs-parent="#accordionRight">
            <div class="accordion-body">
              <ul>
                <li class="coashingstaff-text">2 time all conference -Miramonte High School</li>
                <li class="coashingstaff-text">4 year starter at UC Davis</li>
                <li class="coashingstaff-text">2014 Academic All American</li>
                <li class="coashingstaff-text">2016 WCLL Player of the Year </li>
                <li class="coashingstaff-text">3 time WCLL All Conference</li>
                <li class="coashingstaff-text">All time leading at UC Davis</li>
              </ul>
            </div>
          </div>
        </div>

        <div class="accordion-item">
          <h2 class="accordion-header" id="headingSeventeen">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSeventeen">
              SAVANNAH HADLEY
            </button>
          </h2>
          <div id="collapseSeventeen" class="accordion-collapse collapse" data-bs-parent="#accordionRight">
            <div class="accordion-body">
              <ul>
                <li class="coashingstaff-text">4 time NCJLA Offensive MVP - Pleasant Grove</li>
                <li class="coashingstaff-text">2013-16 - UC Davis - NCAA D1</li>
                <li class="coashingstaff-text">2016 - UC Davis Team Captain</li>
                <li class="coashingstaff-text">2017 - Granite Bay High Woman's Head Coach</li>
              </ul>
            </div>
          </div>
        </div>

        <div class="accordion-item">
          <h2 class="accordion-header" id="headingEighteen">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEighteen">
              MIKE GILLESPIE
            </button>
          </h2>
          <div id="collapseEighteen" class="accordion-collapse collapse" data-bs-parent="#accordionRight">
            <div class="accordion-body">
              <ul>
                <li class="coashingstaff-text">LSM Fairfield University </li>
                <li class="coashingstaff-text">Head Coach University of the Pacific</li>
                <li class="coashingstaff-text">2005-2013Vice President – Western Collegiate Lacrosse League</li>
                <li class="coashingstaff-text">President WCLL 2019 - present</li>
              </ul>
            </div>
          </div>
        </div>

        <div class="accordion-item">
          <h2 class="accordion-header" id="headingNineteen">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseNineteen">
              WILL STABBERT
            </button>
          </h2>
          <div id="collapseNineteen" class="accordion-collapse collapse" data-bs-parent="#accordionRight">
            <div class="accordion-body">
              <ul>
                <li class="coashingstaff-text">2 time US Lacrosse High School All American - Granite Bay</li>
                <li class="coashingstaff-text">4 year starter at Drexel - NCAA Division 1</li>
                <li class="coashingstaff-text">2 time Team Captain</li>
                <li class="coashingstaff-text">2017 - Colonial Athletic Association All Rookie Team</li>
                <li class="coashingstaff-text">9th all time in takeaways at Drexel</li>
              </ul>
            </div>
          </div>
        </div>

        <div class="accordion-item">
          <h2 class="accordion-header" id="headingTwenty">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwenty">
              GEORGE VALENZUELA
            </button>
          </h2>
          <div id="collapseTwenty" class="accordion-collapse collapse" data-bs-parent="#accordionRight">
            <div class="accordion-body">
              <ul>
                <li class="coashingstaff-text">2012-15 UC Davis Starting Goalie</li>
                <li class="coashingstaff-text">UC Davis All Time Saves leader </li>
                <li class="coashingstaff-text">2018- present - Assistant Coach UC Davis</li>
              </ul>
            </div>
          </div>
        </div>

        <div class="accordion-item">
          <h2 class="accordion-header" id="headingTwentyOne">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwentyOne">
              MAGGIE SCHNEIDERETH
            </button>
          </h2>
          <div id="collapseTwentyOne" class="accordion-collapse collapse" data-bs-parent="#accordionRight">
            <div class="accordion-body">
              <ul>
                <li class="coashingstaff-text">2015 - High School All American</li>
                <li class="coashingstaff-text">2017- 2021 - starting attack Johns Hopkins - NCAA D1</li>
                <li class="coashingstaff-text">2017 - Set the Johns Hopkins record for assists by a freshman and 2nd all time points by a freshman</li>
                <li class="coashingstaff-text">2019 - Led Big 10 conference in assists</li>
                <li class="coashingstaff-text">2020 - Tewaraton Watch List</li>
                <li class="coashingstaff-text">2019 & 2021 - All Big 10 Conference</li>
                <li class="coashingstaff-text">4x NCAA tournament appearances</li>
                <li class="coashingstaff-text">Johns Hopkins Team Captain</li>
                <li class="coashingstaff-text">2022-2024 - Offensive Coordinator UC Davis - NCAA D1</li>
              </ul>
            </div>
          </div>
        </div>

        <div class="accordion-item">
          <h2 class="accordion-header" id="headingTwentyTwo">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwentyTwo">
              BILL HUSS
            </button>
          </h2>
          <div id="collapseTwentyTwo" class="accordion-collapse collapse" data-bs-parent="#accordionRight">
            <div class="accordion-body">
              <ul>
                <li class="coashingstaff-text">Four Year Club Highschool Varsity Starter Elk Grove Gladiators</li>
                <li class="coashingstaff-text">NCJLA All Star 2013, 2014</li>
                <li class="coashingstaff-text">Member of one of the original ACES teams </li>
                <li class="coashingstaff-text">Four Year Starter Belmont Abbey College NCAA D2</li>
                <li class="coashingstaff-text">Second Team All Conference 2017</li>
                <li class="coashingstaff-text">All American Honorable Mention 2017</li>
                <li class="coashingstaff-text">Conference Carolinas Champion 2018 </li>
                <li class="coashingstaff-text">Current Head Coach with Elk Grove Gladiators</li>
              </ul>
            </div>
          </div>
        </div>

        <div class="accordion-item">
          <h2 class="accordion-header" id="headingTwentyThree">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwentyThree">
              MATT DUDDERAR
            </button>
          </h2>
          <div id="collapseTwentyThree" class="accordion-collapse collapse" data-bs-parent="#accordionRight">
            <div class="accordion-body">
              <ul>
                <li class="coashingstaff-text">1996-98 Starting Midfielder - Calvert Hall</li>
                <li class="coashingstaff-text">1999-02 - Air Force - NCAA D1</li>
                <li class="coashingstaff-text">2x Team Captain</li>
                <li class="coashingstaff-text">2002 Great Western Lacrosse League All Conference</li>
                <li class="coashingstaff-text">2003 - Assistant Coach - Air Force - NCAA D1</li>
                <li class="coashingstaff-text">2009-10 - Head Coach - St. Thomas Moore High School</li>
              </ul>
            </div>
          </div>
        </div>

        <div class="accordion-item">
          <h2 class="accordion-header" id="headingTwentyFour">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwentyFour">
              MARK WOOD
            </button>
          </h2>
          <div id="collapseTwentyFour" class="accordion-collapse collapse" data-bs-parent="#accordionRight">
            <div class="accordion-body">
              <ul>
                <li class="coashingstaff-text">US Lacrosse All American - Newbury Park</li>
                <li class="coashingstaff-text">Starting attack - Bridgton Academy</li>
                <li class="coashingstaff-text">Whittier University - NCAA D3</li>
                <li class="coashingstaff-text">Director - Renegade Lacrosse</li>
                <li class="coashingstaff-text">UC Davis - 2 time All Conference Attack</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


@endsection
