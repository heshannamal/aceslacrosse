@extends('layouts.app')

@section('title', 'Hotels - ACES Lacrosse')

@section('content')
    <style>
        /* --- HERO SECTION --- */
        .hero-section {
            position: relative;
            width: 100%;
            height: auto;

            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hero-overlay {
            background-color: rgba(0, 0, 0, 0.4);
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hero-overlay h1 {
            color: #fff;
            font-size: 56px;
            font-weight: 800;
            text-transform: uppercase;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.6);
        }

        /* --- RESPONSIVE --- */
        @media (max-width: 768px) {
            .hero-section {
                height: auto;
            }

            .hero-overlay h1 {
                font-size: 32px;
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
            border-bottom: 1px solid #ccc;
            /*  only bottom border */
            padding: 18px 0;
        }

        .parents-section .accordion-button::after {
            filter: brightness(0);
            /* black arrow */
            color: #000;
        }

        .parents-section .accordion-button:not(.collapsed) {
            color: #000;
            background: #fff;
            /* keep white background when open */
            box-shadow: none;
        }

        .parents-section .accordion-item {
            border: none;
            /* remove Bootstrap default border */

        }

        .parents-section .accordion-body {
            font-size: 15px;
            color: #000000ff;
            background: #fff;
            border-top: none;
            padding-top: 0;
            align-items: justify;
        }

        .parents-section .accordion-button {
            font-size: 16px;
            font-weight: 600;
            color: #111;
            background: #fff;
            border: none;
            border-bottom: 1px solid #ddd;
            padding: 18px 0;

            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;

            white-space: normal;
            /* ✅ allow wrapping */
        }

        .parents-section .accordion-title {
            display: inline;
            line-height: 1.4;
        }

        .parents-section .accordion-title strong {
            font-weight: 700;
        }

        .parents-section .accordion-meta {
            font-weight: 500;
            color: #444;
        }

        .parents-section .accordion-button::after {
            filter: brightness(0);
            margin-left: auto;
        }

    @media screen and (min-width: 2560px) {
        .container, .container-lg, .container-md, .container-sm, .container-xl, .container-xxl {
            max-width: 230rem;
        }

        .hero-section {
            position: relative;
            width: 100%;
            height: auto;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        h1 {
            font-size: 10rem !important;
        }

        button.accordion-button.collapsed {
            font-size: 4rem;
        }

        button.accordion-button {
            font-size: 4rem !important;
        }

        p {
            font-size: 4rem;
        }

        .parents-section .accordion-title {
            display: inline;
            line-height: 2.4;
        }

    }
    </style>
    <!-- ✅ HERO SECTION -->
    <section class="hero-section" >
        <img src="{{ asset('public/assets/images/TESTIMONIALS.jpg') }}" alt="Aces Team">
        <div class="hero-overlay">
            <h1>TESTIMONIALS</h1>
        </div>
    </section>
    <section class="parents-section container my-5">
        <div class="row">
            <!-- LEFT COLUMN -->
            <div class="col-md-6">
                <div class="accordion" id="accordionLeft">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseOne">
                                <span class="accordion-title">
                                    <strong>DAVE WHITE</strong>  <span class="accordion-meta">(PARENT)</span>
                                </span>
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionLeft">
                            <div class="accordion-body">
                                <p> Being a part of the Sacramento Aces program for the past several years has been a
                                    wonderful experience for both of ours boys. While being a part of the Sacramento Aces,
                                    our boys have learned combinations of skills, team building, competitiveness, along with
                                    many fundamentals to enhance their lacrosse IQ. Coach Frank and his staff have an
                                    exceptional knowledge of the game, which is truly like no other.As a parent it is
                                    wonderful to see how much Coach Frank and his staff relate to the boys and strive to
                                    make practice challenging, yet fun every week. It’s fun to see that translate onto the
                                    field during tournament play. The attention to player development is top notch. We are
                                    proud to be part of the Sacramento Aces family.</p>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseTwo">
                                <span class="accordion-title">
                                    <strong>CHIP MAHLA</strong>  <span class="accordion-meta">(PARENT)</span>
                                </span>
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionLeft">
                            <div class="accordion-body">
                                <p>
                                    My son, Trey, was one of the first members of the Aces when Encore introduced the team
                                    in Sacramento. We were excited that we could avoid the arduous ride to and from the Bay
                                    area for practice with one of two other travel team organizations Trey had played with
                                    prior to the Aces. The thought of practicing close to home was exhilarating—if the
                                    coaching staff was as good as those of the other organizations Trey played with, then
                                    we’d hit the jackpot.
                                </p>
                                <p>To Trey’s and my delight, the Aces organization, from top to bottom, proved itself
                                    superior to any organization we experienced before. Every coach was not only
                                    knowledgeable about lacrosse, but demonstrated a passion for teaching the game to any
                                    player interested in learning, regardless of skill level. That Trey was passionate about
                                    the game only meant that his coaches were even more passionate about teaching him.
                                    Trey’s skills and, more importantly, his lax IQ, soared. Trey had found his travel team
                                    home. </p>
                                <p>Fast forward to 2016 and Trey was asked to play on the newly-formed Pinnacle Aces team
                                    that was going to travel to the East Coast to take on the best the East had to offer.
                                    Frank Resetarits had been telling Trey and his teammates that they belonged in the upper
                                    echelon of high school lacrosse; that the Aces could compete against anyone. Frank’s
                                    knowledge and skill as a lacrosse coach had always amazed Trey. Listening to Frank talk
                                    about how the Aces team was going east on a mission to bring back a championship infused
                                    Trey and his teammates with a confidence I had never seen before. By the time the team
                                    arrived in Boston, there was no question in any player’s mind that the Aces were going
                                    to stomp their east coast opponents. And stomp they did, closing out their 6-0 run to
                                    the championship trophy with a 10-2 thrashing of a talented but seriously overmatched
                                    New Hampshire team. The joy on the players’ faced was expected. What impressed me was
                                    the sheer joy on the faces of the Aces coaching staff—they were even more moved than the
                                    players! They take their jobs as mentors and life coaches very seriously, and the
                                    results are easy to see. </p>
                                <p>Trey leans on his lessons from the Aces coaching staff frequently, both on and off the
                                    field. The only thing Trey regrets about his time with the Aces is that that time will
                                    soon be over as a player. My regrets about that are tenfold more intense. I’ve watched
                                    my son play at the height of his abilities with some of the best lacrosse players in
                                    California thanks to the Aces and their wonderful coaches. I can’t thank Encore enough
                                    for the help it has given my son to grow as a player and as a young man. </p>
                                <p>Many thanks to all of the coaches who have touched Trey’s life: Ryan, Lorne, Steve,
                                    Jerem, John, Stephen, Greg, and most especially, Frank. You have all made a tremendous
                                    impact on Trey and me. We thank you from the bottom of our hearts.</p>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseThree">
                                <span class="accordion-title">
                                    <strong>ROB BROWN</strong> <span class="accordion-meta">(CLUB PRESIDENT, VARSITY HEAD
                                        COACH, PLEASANT GROVE LACROSSE CLUB)</span>
                                </span>
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionLeft">
                            <div class="accordion-body">
                                <p>I am currently the President and head varsity coach for a Sacramento area lacrosse club.
                                    I have been involved in the lacrosse community for 8 years. In 2014, when the Sacramento
                                    Aces was a relatively new club, my son came to me and asked if he could play for them
                                    over the summer. I was skeptical. It seemed like a lot of money and I wasn’t convinced
                                    it was worth it. Over the past few years, we have grown with the club. The experiences
                                    my son has had playing for the Aces have been exceptional. As we have gotten to know
                                    Frank, Ryan, Lorne and the other coaches, it is clear that they truly care about what is
                                    best for each individual player. They strive to develop not only the physical skills and
                                    lacrosse IQ of a team, but more importantly, individual character and sportsmanship. It
                                    is a rare thing to have a competitive club consistently balance the desire for success
                                    and winning with individual player development and experience. </p>
                                <p>I have had several conversations with Director, Frank Resetarits. He is always accessible
                                    to players and parents for questions about the program and the collegiate recruitment
                                    process. His expertise, knowledge, and candor make him a treasured resource to the
                                    players and families in the program. I strongly encourage all players from my club that
                                    desire to play beyond the spring to try out for the Sacramento Aces club. </p>
                                <p>As the sport of lacrosse continues to grow in NortherN California and around the country,
                                    the Sacramento Aces will continue to be the premier competitive lacrosse club in the
                                    region and my family is proud to be a part of it.</p>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingFour">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseFour">
                                <span class="accordion-title">
                                    <strong>MIKE PEIRSOL</strong>  <span class="accordion-meta">(PARENT)</span>
                                </span>
                            </button>
                        </h2>
                        <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionLeft">
                            <div class="accordion-body">
                                <p>My son started playing with the Aces Lacrosse Program four years ago. He had just
                                    completed his first spring season of rec lacrosse, and I was looking to get him into a
                                    program that would provide him with additional coaching. He tried out with Aces and was
                                    accepted into the Academy program. I did not know what to expect, but we have never been
                                    disappointed.</p>
                                <p>The coaching and mentoring that I have observed here is second to none. The coaches
                                    obviously care about the kids and take the time to work with them at every level of
                                    their development. The Academy program is one of the best parts of Aces. It gives kids
                                    who are new to sport, or who may just need some extra time to develop, a place to grow
                                    and thrive.

                                    At every level of the program, players are taught to work as a team, take responsibility
                                    for themselves, and show good sportsmanship.

                                    My son has progressed over the last four years and is now a Pinnacle 2027 member. His
                                    rise from the Academy to the Elite team, and now to the Pinnacle team, would never have
                                    happened if he hadn’t been given the opportunity to find a home with the Aces program.
                                    The coaches here have helped mold him and have brought out the best of his lacrosse
                                    abilities.</p>
                                <p>Joining this program is one of the main reasons my son has progressed as far as he has in
                                    lacrosse.</p>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingFive">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#L_collapseFive">
                                <span class="accordion-title text-uppercase">
                                    <strong>Josh Vinciguerra</strong>  <span class="accordion-meta text-uppercase">(PARENT, President - El Dorado Hills Youth Lacrosse, JV Head Coach - Oak Ridge High Schoo)</span>
                                </span>
                            </button>
                        </h2>
                        <div id="L_collapseFive" class="accordion-collapse collapse" data-bs-parent="#accordionLeft">
                            <div class="accordion-body">
                                <p> My son and his friends have been with Aces since they were in 10u,
                                    playing year-round. Aces and their great coaches play a big role in their love of the game. Their coaches
                                    are professional, team-first, and bring an incredible amount of lacrosse experience and knowledge.
                                    As players move on from our youth program, a large percentage go on to play at Oak Ridge High School, where I am the Head Coach
                                    of our JV team. I know whenever I have an incoming Freshman who grew up playing Aces, they will play exceptional lacrosse.
                                    They not only have excellent skills and lacrosse IQ, but also play as a team.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN -->
            <div class="col-md-6">
                <div class="accordion" id="accordionRight">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingFive">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseFive">
                                <span class="accordion-title">
                                    <strong>RUSS HICKS</strong>  <span class="accordion-meta">(PARENT)</span>
                                </span>
                            </button>
                        </h2>
                        <div id="collapseFive" class="accordion-collapse collapse" data-bs-parent="#accordionRight">
                            <div class="accordion-body">
                                <p>As a parent, you are always looking to help your children pursue their dreams and help
                                    them achieve success in life. Seldom do you run across an organization that has that
                                    same passion and is willing to partner with you to achieve those goals. After working
                                    with the Aces program and their Director, Frank Resetarits, for a little more than 3
                                    years, I can tell you that this operation is one of those companies. </p>
                                <p>My son has played sports since he was 5 years old but decided during his Freshman year in
                                    High School, to pick up a Lacrosse stick and try to learn that sport. His High School
                                    coach recommended that he work with the Aces program over the summer to learn
                                    fundamentals and gain Lacrosse IQ. As soon as Ryan started working with Frank and his
                                    team of very experienced and passionate Coaches, Ryan’s skill level increased
                                    dramatically. He continued to work with the program in the Fall (in between football and
                                    school work) and by the Spring of his Sophomore year, Ryan was promoted to the Varsity
                                    High School team. </p>
                                <p>Frank also moved Ryan up to the Elite Club team and allowed him to travel with the
                                    National Aces Pinnacle Team when they traveled back East and won the Boston Legacy
                                    Tournament. All of this work, plus some excellent private Coaching by both Frank and
                                    Lorne Silverstein, improved Ryan’s Lacrosse acumen as well as his confidence with the
                                    sport. He was also selected for some very strategic Showcases and Elite Prospect Days
                                    which led to follow up invitations for specific College visit invitations. As Schools
                                    began to indicate their interest in Ryan, Frank would follow up with those Coaches and
                                    give them a realistic evaluation of Ryan from the perspective of someone who has
                                    competed at the highest level of the sport and is respected Nationally. All of this work
                                    and interaction resulted in more than a dozen Colleges recruiting my Son and more than
                                    half a dozen extending significant scholarship offers to come play for them when he
                                    graduates this year. Talk about making a dream come true! </p>
                                <p>If your son, loves Lacrosse, and dreams of playing the sport at the next level, or you
                                    simply want to get him the training and education to be successful in the sport at his
                                    current level, you could do no better than to entrust the team at Aces with your player.
                                    They will offer him the guidance and the coaching necessary to achieve his maximum
                                    potential, all the while establishing a broad network of friendships within the Northern
                                    California Community. They are a Parent’s Dream Come True!</p>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingSix">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseSix">
                                <span class="accordion-title">
                                    <strong>SCOTT MATERN</strong>  <span class="accordion-meta">(PARENT)</span>
                                </span>
                            </button>
                        </h2>
                        <div id="collapseSix" class="accordion-collapse collapse" data-bs-parent="#accordionRight">
                            <div class="accordion-body">
                                <p>We have been thrilled with our Sac ACES experience. The coaches are outstanding and we
                                    have been particularly impressed with Frank Resetarits, who not only has an incredible
                                    player resume, but is also extremely good with the kids of all ages – two skillsets that
                                    are often hard to find in one person! We’ve also found the ACES players and families to
                                    be a fun, supportive, and “low-drama” group and I think that comes from the tone that is
                                    set from the top down. My son has enjoyed playing with the best players from all around
                                    his high school league and the relationships he’s made with them have made many high
                                    school games a mini-reunion of his ACES friends. The opportunity to travel out of state
                                    to play (and win!) tournaments against top competition is just icing on the cake.</p>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingSeven">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseSeven">
                                <span class="accordion-title">
                                    <strong>QUINN CHIHOCKI</strong> <span class="accordion-meta">(ACES ALUMNI CLASS OF 2017, SACRED HEART UNIVERSITY
                                    CLASS OF
                                    2021)</span>
                                </span>
                            </button>
                        </h2>
                        <div id="collapseSeven" class="accordion-collapse collapse" data-bs-parent="#accordionRight">
                            <div class="accordion-body">
                                <p>
                                    The ACES program made playing in college for me a reality. I had aspirations to play
                                    collegiately and the Aces coaching staff was able to fine tune my skills and prepare me
                                    for the next level both on the physical and ‘lax IQ’ aspects of the game. The time and
                                    effort each coach is willing to put into every player on each team is unmatched
                                    throughout Northern California. I am very excited to see the culture of the game we have
                                    created here grow more and more each year
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingEight">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseEight">
                                <span class="accordion-title">
                                    <strong>JESSICA</strong> <span class="accordion-meta">(PARENT)</span>
                                </span>
                            </button>
                        </h2>
                        <div id="collapseEight" class="accordion-collapse collapse" data-bs-parent="#accordionRight">
                            <div class="accordion-body">
                                <p>Our boys have thrived with Aces Lacrosse for the past 5 years. The coaching staff is
                                    superior - practices are organized, challenging, competitive, fun & focused on teamwork.
                                    The Director and coaches care about the players individually and are genuinely invested
                                    in their growth and success.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
