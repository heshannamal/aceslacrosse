@extends('layouts.app')

@section('title', 'Hotels - ACES Lacrosse')

@section('content')
<!-- Fancybox CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css" />

<!-- Fancybox JS -->
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.umd.js"></script>
<style>
            body {
           font-family: 'Roboto', sans-serif;
            overflow-x: hidden;
        }
h1, h2, h3, .section-title {
    font-family: 'Roboto', sans-serif;
    font-weight: 900;
}
  h1 {
    font-size: 3.8rem !important;
    text-shadow: 0 2px 8px rgba(0, 0, 0, 0.6);
 }




    /* MOBILE RESPONSIVE */
    @media (max-width: 768px) {
        .hero-section {
            height: auto;
        }
        .hero-overlay h1 {
            color: #fff;
            font-size: 27px !important;
        }

    }
    .championship-gallery .gallery-card {
    position: relative;
    overflow: hidden;

}

.championship-gallery img {
    width: 100%;
    height: 260px;
    object-fit: cover;

}

.gallery-title {
    position: absolute;
    bottom: 12px;
    left: 50%;
    transform: translateX(-50%);

    background: rgba(0, 0, 0, 0.28);
    color: #fff;
    font-size: 12px;
    font-weight: 700;
    padding: 6px 12px;
    border-radius: 5px;
    white-space: nowrap;
    text-align: center;
}

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
        background: transparent !important;
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

    }

    @media screen and (min-width: 2560px) {
        .container, .container-lg, .container-md, .container-sm, .container-xl, .container-xxl {
            max-width: 230rem;
        }
        .championship-gallery img {
            height: auto;
        }
        .hero-overlay h1 {
            font-size: 10rem !important;
        }
    }


</style>


{{-- <section class="hero-section" style="background-image: url({{ asset('public/assets/images/championshipsbackground.webp') }})">
    <h1 class="hero-title">ACES CHAMPIONSHIPS</h1>
</section> --}}
<section class="hero-section" >
    <img src="{{ asset('public/assets/images/championshipsbackground.webp') }}" alt="Aces Team">
    <div class="hero-overlay">
        <h1>ACES CHAMPIONSHIPS</h1>
    </div>
</section>
<section class="championship-gallery container py-5">
    <div class="row g-4">

        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="gallery-card">
                <a href="{{ asset('public/assets/images/CHAMPIONSHIPS/2026/ACES U12 Rogue Valley Rising.jpg') }}" data-fancybox="championship-gallery">
                    <img src="{{ asset('public/assets/images/CHAMPIONSHIPS/2026/ACES U12 Rogue Valley Rising.jpg') }}" class="img-fluid" alt="">
                </a>
                <div class="gallery-title">ACES U12 - Rogue Valley Rising 2026</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="gallery-card">
                <a href="{{ asset('public/assets/images/CHAMPIONSHIPS/2026/ACES HS Academy Sactown Sixes.jpg') }}" data-fancybox="championship-gallery">
                    <img src="{{ asset('public/assets/images/CHAMPIONSHIPS/2026/ACES HS Academy Sactown Sixes.jpg') }}" class="img-fluid" alt="">
                </a>
                <div class="gallery-title">ACES HS Academy - Sactown Sixes 2026</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="gallery-card">
                <a href="{{ asset('public/assets/images/CHAMPIONSHIPS/2026/ACES U14 Sactown Sixes.jpg') }}" data-fancybox="championship-gallery">
                    <img src="{{ asset('public/assets/images/CHAMPIONSHIPS/2026/ACES U14 Sactown Sixes.jpg') }}" class="img-fluid" alt="">
                </a>
                <div class="gallery-title">ACES U14 - Sactown Sixes 2026</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="gallery-card">
                <a href="{{ asset('public/assets/images/CHAMPIONSHIPS/2025/ACES2028_Sactown_Sixes.jpg') }}" data-fancybox="championship-gallery">
                    <img src="{{ asset('public/assets/images/CHAMPIONSHIPS/2025/ACES2028_Sactown_Sixes.jpg') }}" class="img-fluid" alt="">
                </a>
                <div class="gallery-title">ACES 2028 - Sactown Sixes 2025</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="gallery-card">
                <a href="{{ asset('public/assets/images/CHAMPIONSHIPS/2025/ACESJV-Golden Gate Games.jpg') }}" data-fancybox="championship-gallery">
                    <img src="{{ asset('public/assets/images/CHAMPIONSHIPS/2025/ACESJV-Golden Gate Games.jpg') }}" class="img-fluid" alt="">
                </a>
                <div class="gallery-title">ACES JV - Golden Gate Games 2025</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="gallery-card">
                <a href="{{ asset('public/assets/images/CHAMPIONSHIPS/2025/ACES 2028 Grapevine Classic .jpg') }}" data-fancybox="championship-gallery">
                    <img src="{{ asset('public/assets/images/CHAMPIONSHIPS/2025/ACES 2028 Grapevine Classic .jpg') }}" class="img-fluid" alt="">
                </a>
                <div class="gallery-title">ACES 2028 - Grapevine Classic 2025</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="gallery-card">
                <a href="{{ asset('public/assets/images/CHAMPIONSHIPS/2025/ACES12U-SactownSixes.jpg') }}" data-fancybox="championship-gallery">
                    <img src="{{ asset('public/assets/images/CHAMPIONSHIPS/2025/ACES12U-SactownSixes.jpg') }}" class="img-fluid" alt="">
                </a>
                <div class="gallery-title">ACES U12 - Sactown Sixes 2025</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="gallery-card">
                <a href="{{ asset('public/assets/images/CHAMPIONSHIPS/2025/ACES14U Sacramento Lacrosse Cup.jpg') }}" data-fancybox="championship-gallery">
                    <img src="{{ asset('public/assets/images/CHAMPIONSHIPS/2025/ACES14U Sacramento Lacrosse Cup.jpg') }}" class="img-fluid" alt="">
                </a>
                <div class="gallery-title">ACES U14 - Sacramento Lacrosse 2025</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="gallery-card">
                <a href="{{ asset('public/assets/images/CHAMPIONSHIPS/2025/ACES14U_Sactown_Sixes.jpg') }}" data-fancybox="championship-gallery">
                    <img src="{{ asset('public/assets/images/CHAMPIONSHIPS/2025/ACES14U_Sactown_Sixes.jpg') }}" class="img-fluid" alt="">
                </a>
                <div class="gallery-title">ACES U14 - Sactown Sixes 2025</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="gallery-card">
                <a href="{{ asset('public/assets/images/CHAMPIONSHIPS/2025/ACES2027_Rouge_Valley_Rising.jpg') }}" data-fancybox="championship-gallery">
                    <img src="{{ asset('public/assets/images/CHAMPIONSHIPS/2025/ACES2027_Rouge_Valley_Rising.jpg') }}" class="img-fluid" alt="">
                </a>
                <div class="gallery-title">ACES 2027 - ROGUE Valley Rising 2025</div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="gallery-card">
                <a href="{{ asset('public/assets/images/ACES 2029 - hot shots.jpg') }}" data-fancybox="championship-gallery">
                    <img src="{{ asset('public/assets/images/ACES 2029 - hot shots.jpg') }}" class="img-fluid" alt="">
                </a>
                <div class="gallery-title">ACES 2029 - Hot shots 2023</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="gallery-card">
                <a href="{{ asset('public/assets/images/ACES 2024 - Hot shots .jpg') }}" data-fancybox="championship-gallery">
                    <img src="{{ asset('public/assets/images/ACES 2024 - Hot shots .jpg') }}" class="img-fluid" alt="">
                </a>
                <div class="gallery-title">ACES 2024 - Hot shots 2023</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="gallery-card">
                <a href="{{ asset('public/assets/images/ACES U14 - Grapevine 2023.jpg') }}" data-fancybox="championship-gallery">
                    <img src="{{ asset('public/assets/images/ACES U14 - Grapevine 2023.jpg') }}" class="img-fluid" alt="">
                </a>
                <div class="gallery-title">ACES U14 - Grapevine 2023</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="gallery-card">
                <a href="{{ asset('public/assets/images/Sac ACES U10 - 2022 Turkeyshoot.jpg') }}" data-fancybox="championship-gallery">
                    <img src="{{ asset('public/assets/images/Sac ACES U10 - 2022 Turkeyshoot.jpg') }}" class="img-fluid" alt="">
                </a>
                <div class="gallery-title">Sac ACES U10 - 2022 Turkeyshoot</div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="gallery-card">
                <a href="{{ asset('public/assets/images/Sac ACES JV - Gold Rush 2022.jpg') }}" data-fancybox="championship-gallery">
                    <img src="{{ asset('public/assets/images/Sac ACES JV - Gold Rush 2022.jpg') }}" class="img-fluid">
                </a>
                <div class="gallery-title">Sac ACES JV - Gold Rush 2022</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="gallery-card">
                <a href="{{ asset('public/assets/images/Sac ACES JV - SB Fall Brawl 2022.jpg') }}" data-fancybox="championship-gallery">
                    <img src="{{ asset('public/assets/images/Sac ACES JV - SB Fall Brawl 2022.jpg') }}" class="img-fluid">
                </a>
                <div class="gallery-title">Sac ACES JV - SB Fall Brawl 2022</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="gallery-card">
                <a href="{{ asset('public/assets/images/Sac ACES JV Elite - Vegas Showcase 2022.jpg') }}" data-fancybox="championship-gallery">
                    <img src="{{ asset('public/assets/images/Sac ACES JV Elite - Vegas Showcase 2022.jpg') }}" class="img-fluid">
                </a>
                <div class="gallery-title">Sac ACES JV Elite - Vegas Showcase 2022</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="gallery-card">
                <a href="{{ asset('public/assets/images/Sac ACES 2029 - Turkeyshoot 2022.jpg') }}" data-fancybox="championship-gallery">
                    <img src="{{ asset('public/assets/images/Sac ACES 2029 - Turkeyshoot 2022.jpg') }}" class="img-fluid">
                </a>
                <div class="gallery-title">Sac ACES 2029 - Turkeyshoot 2022</div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="gallery-card">
                <a href="{{ asset('public/assets/images/Sac ACES 2029 - Gold Rush 2022 .jpg') }}" data-fancybox="championship-gallery">
                    <img src="{{ asset('public/assets/images/Sac ACES 2029 - Gold Rush 2022 .jpg') }}" class="img-fluid">
                </a>
                <div class="gallery-title">Sac ACES 2029 - Gold Rush 2022</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="gallery-card">
                <a href="{{ asset('public/assets/images/ACES Wild 2025 - SoCal Showdown 2022.jpg') }}" data-fancybox="championship-gallery">
                    <img src="{{ asset('public/assets/images/ACES Wild 2025 - SoCal Showdown 2022.jpg') }}" class="img-fluid">
                </a>
                <div class="gallery-title">ACES Wild 2025 - SoCal Showdown 2022</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="gallery-card">
                <a href="{{ asset('public/assets/images/Pinnacle 2027 - SoCal Showdown 2022.jpg') }}" data-fancybox="championship-gallery">
                    <img src="{{ asset('public/assets/images/Pinnacle 2027 - SoCal Showdown 2022.jpg') }}" class="img-fluid">
                </a>
                <div class="gallery-title">Pinnacle 2027 - SoCal Showdown 2022</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="gallery-card">
                <a href="{{ asset('public/assets/images/Pinnacle - SoCal Showdown 2022.jpg') }}" data-fancybox="championship-gallery">
                    <img src="{{ asset('public/assets/images/Pinnacle - SoCal Showdown 2022.jpg') }}" class="img-fluid">
                </a>
                <div class="gallery-title">Pinnacle - SoCal Showdown 2022</div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="gallery-card">
                <a href="{{ asset('public/assets/images/Sac ACES 2026 - 2022 Battle of the Bay .jpg') }}" data-fancybox="championship-gallery">
                    <img src="{{ asset('public/assets/images/Sac ACES 2026 - 2022 Battle of the Bay .jpg') }}" class="img-fluid">
                </a>
                <div class="gallery-title">2026's - 2022 Battle of the Bay</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="gallery-card">
                <a href="{{ asset('public/assets/images/Sac ACES 2026 - 2022 Grapevine Classic .jpg') }}" data-fancybox="championship-gallery">
                    <img src="{{ asset('public/assets/images/Sac ACES 2026 - 2022 Grapevine Classic .jpg') }}" class="img-fluid">
                </a>
                <div class="gallery-title">2026's - 2022 Grapevine Classic</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="gallery-card">
                <a href="{{ asset('public/assets/images/Sac ACES U10 - 2022 Robin Hood Skirmish _wecompress.com_.jpg') }}" data-fancybox="championship-gallery">
                    <img src="{{ asset('public/assets/images/Sac ACES U10 - 2022 Robin Hood Skirmish _wecompress.com_.jpg') }}" class="img-fluid">
                </a>
                <div class="gallery-title">U10s - 2022 Robin Hood Skirmish</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="gallery-card">
                <a href="{{ asset('public/assets/images/Sac ACES U10 - 2021 Turkeyshoot.jpg') }}" data-fancybox="championship-gallery">
                    <img src="{{ asset('public/assets/images/Sac ACES U10 - 2021 Turkeyshoot.jpg') }}" class="img-fluid">
                </a>
                <div class="gallery-title">U10s - 2021 Turkey Shootout</div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="gallery-card">
                <a href="{{ asset('public/assets/images/2027_s - 2021 Grapevine Classic.jpg') }}" data-fancybox="championship-gallery">
                    <img src="{{ asset('public/assets/images/2027_s - 2021 Grapevine Classic.jpg') }}" class="img-fluid">
                </a>
                <div class="gallery-title">2027's - 2021 Grapevine Classic</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="gallery-card">
                <a href="{{ asset('public/assets/images/2024_s 2021 Battle of the Bay.jpg') }}" data-fancybox="championship-gallery">
                    <img src="{{ asset('public/assets/images/2024_s 2021 Battle of the Bay.jpg') }}" class="img-fluid">
                </a>
                <div class="gallery-title">2024's 2021 Battle of the Bay</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="gallery-card">
                <a href="{{ asset('public/assets/images/2023_s - 2021 Battle of the Bay.jpg') }}" data-fancybox="championship-gallery">
                    <img src="{{ asset('public/assets/images/2023_s - 2021 Battle of the Bay.jpg') }}" class="img-fluid">
                </a>
                <div class="gallery-title">2023's - 2021 Battle of the Bay</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="gallery-card">
                <a href="{{ asset('public/assets/images/download (12).avif') }}" data-fancybox="championship-gallery">
                    <img src="{{ asset('public/assets/images/download (12).avif') }}" class="img-fluid">
                </a>
                <div class="gallery-title">HS 2020- 2019 West Coast Shootout</div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="gallery-card">
                <a href="{{ asset('public/assets/images/download (13).avif') }}" data-fancybox="championship-gallery">
                    <img src="{{ asset('public/assets/images/download (13).avif') }}" class="img-fluid">
                </a>
                <div class="gallery-title">HS Elite- 2019 Halloween Shootout</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="gallery-card">
                <a href="{{ asset('public/assets/images/download (1).webp') }}" data-fancybox="championship-gallery">
                    <img src="{{ asset('public/assets/images/download (1).webp') }}" class="img-fluid">
                </a>
                <div class="gallery-title">U14- 2019 King's Showcase</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="gallery-card">
                <a href="{{ asset('public/assets/images/download (14).avif') }}" data-fancybox="championship-gallery">
                    <img src="{{ asset('public/assets/images/download (14).avif') }}" class="img-fluid">
                </a>
                <div class="gallery-title">HS 2021- 2019 West Coast Shootout</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="gallery-card">
                <a href="{{ asset('public/assets/images/download (2).webp') }}" data-fancybox="championship-gallery">
                    <img src="{{ asset('public/assets/images/download (2).webp') }}" class="img-fluid">
                </a>
                <div class="gallery-title">HS Elite - 2019 Santa Cruz Classic</div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="gallery-card">
                <a href="{{ asset('public/assets/images/download (3).webp') }}" data-fancybox="championship-gallery">
                    <img src="{{ asset('public/assets/images/download (3).webp') }}" class="img-fluid">
                </a>
                <div class="gallery-title">U14- 2019 Santa Cruz Classic</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="gallery-card">
                <a href="{{ asset('public/assets/images/download (4).avif') }}" data-fancybox="championship-gallery">
                    <img src="{{ asset('public/assets/images/download (4).avif') }}" class="img-fluid">
                </a>
                <div class="gallery-title">HS Elite- 2018 Halloween Shootout</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="gallery-card">
                <a href="{{ asset('public/assets/images/download (16).avif') }}" data-fancybox="championship-gallery">
                    <img src="{{ asset('public/assets/images/download (16).avif') }}" class="img-fluid">
                </a>
                <div class="gallery-title">JV - 2018 Santa Cruz Classic</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="gallery-card">
                <a href="{{ asset('public/assets/images/download (17).avif') }}" data-fancybox="championship-gallery">
                    <img src="{{ asset('public/assets/images/download (17).avif') }}" class="img-fluid">
                </a>
                <div class="gallery-title">U14 - 2018 Santa Cruz Classic</div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="gallery-card">
                <a href="{{ asset('public/assets/images/download (18).avif') }}" data-fancybox="championship-gallery">
                    <img src="{{ asset('public/assets/images/download (18).avif') }}" class="img-fluid">
                </a>
                <div class="gallery-title">HS Elite- 2018 Lake Tahoe Lacrosse</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="gallery-card">
                <a href="{{ asset('public/assets/images/download (19).avif') }}" data-fancybox="championship-gallery">
                    <img src="{{ asset('public/assets/images/download (19).avif') }}" class="img-fluid">
                </a>
                <div class="gallery-title">HS Open- 2017 War On The Shore</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="gallery-card">
                <a href="{{ asset('public/assets/images/download (20).avif') }}" data-fancybox="championship-gallery">
                    <img src="{{ asset('public/assets/images/download (20).avif') }}" class="img-fluid">
                </a>
                <div class="gallery-title">2020 - 2017 Battle of the Bay</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="gallery-card">
                <a href="{{ asset('public/assets/images/download (21).avif') }}" data-fancybox="championship-gallery">
                    <img src="{{ asset('public/assets/images/download (21).avif') }}" class="img-fluid">
                </a>
                <div class="gallery-title">HS Elite - 2017 Battle of the Bay</div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="gallery-card">
                <a href="{{ asset('public/assets/images/download (22).avif') }}" data-fancybox="championship-gallery">
                    <img src="{{ asset('public/assets/images/download (22).avif') }}" class="img-fluid">
                </a>
                <div class="gallery-title">U12 - 2017 Battle of the Bay</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="gallery-card">
                <a href="{{ asset('public/assets/images/download (23).avif') }}" data-fancybox="championship-gallery">
                    <img src="{{ asset('public/assets/images/download (23).avif') }}" class="img-fluid">
                </a>
                <div class="gallery-title">HS Elite - 2017 Lake Tahoe Tournament</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="gallery-card">
                <a href="{{ asset('public/assets/images/download (24).avif') }}" data-fancybox="championship-gallery">
                    <img src="{{ asset('public/assets/images/download (24).avif') }}" class="img-fluid">
                </a>
                <div class="gallery-title">2019s - 2017 Tinseltown Throwdown</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="gallery-card">
                <a href="{{ asset('public/assets/images/download (25).avif') }}" data-fancybox="championship-gallery">
                    <img src="{{ asset('public/assets/images/download (25).avif') }}" class="img-fluid">
                </a>
                <div class="gallery-title">2020s = 2017 Tinseltown Throwdown</div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="gallery-card">
                <a href="{{ asset('public/assets/images/download (26).avif') }}" data-fancybox="championship-gallery">
                    <img src="{{ asset('public/assets/images/download (26).avif') }}" class="img-fluid">
                </a>
                <div class="gallery-title">2020s - 2017 West Coast Showcase</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="gallery-card">
                <a href="{{ asset('public/assets/images/download (27).avif') }}" data-fancybox="championship-gallery">
                    <img src="{{ asset('public/assets/images/download (27).avif') }}" class="img-fluid">
                </a>
                <div class="gallery-title">U14 - 2017 West Coast Showcase</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="gallery-card">
                <a href="{{ asset('public/assets/images/download (4).webp') }}" data-fancybox="championship-gallery">
                    <img src="{{ asset('public/assets/images/download (4).webp') }}" class="img-fluid">
                </a>
                <div class="gallery-title">HS Elite - 2017 Gold Rush</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="gallery-card">
                <a href="{{ asset('public/assets/images/download (5).webp') }}" data-fancybox="championship-gallery">
                    <img src="{{ asset('public/assets/images/download (5).webp') }}" class="img-fluid">
                </a>
                <div class="gallery-title">U16 - 2017 Gold Rush</div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="gallery-card">
                <a href="{{ asset('public/assets/images/download (6).webp') }}" data-fancybox="championship-gallery">
                    <img src="{{ asset('public/assets/images/download (6).webp') }}" class="img-fluid">
                </a>
                <div class="gallery-title">HS - 2016 Halloween Shootout</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="gallery-card">
                <a href="{{ asset('public/assets/images/download (7).webp') }}" data-fancybox="championship-gallery">
                    <img src="{{ asset('public/assets/images/download (7).webp') }}" class="img-fluid">
                </a>
                <div class="gallery-title">HS - 2016 Las Vegas Showcase</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="gallery-card">
                <a href="{{ asset('public/assets/images/download (28).avif') }}" data-fancybox="championship-gallery">
                    <img src="{{ asset('public/assets/images/download (28).avif') }}" class="img-fluid">
                </a>
                <div class="gallery-title">U13 - 2015 Las Vegas Showcase</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="gallery-card">
                <a href="{{ asset('public/assets/images/download (29).avif') }}" data-fancybox="championship-gallery">
                    <img src="{{ asset('public/assets/images/download (29).avif') }}" class="img-fluid">
                </a>
                <div class="gallery-title">U15 - 2015 Halloween Shootout</div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="gallery-card">
                <a href="{{ asset('public/assets/images/download (30).avif') }}" data-fancybox="championship-gallery">
                    <img src="{{ asset('public/assets/images/download (30).avif') }}" class="img-fluid">
                </a>
                <div class="gallery-title">U15 - 2015 Turkey Shootout</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="gallery-card">
                <a href="{{ asset('public/assets/images/download (31).avif') }}" data-fancybox="championship-gallery">
                    <img src="{{ asset('public/assets/images/download (31).avif') }}" class="img-fluid">
                </a>
                <div class="gallery-title">U17 - 2015 Las Vegas Showcase</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="gallery-card">
                <a href="{{ asset('public/assets/images/download (32).avif') }}" data-fancybox="championship-gallery">
                    <img src="{{ asset('public/assets/images/download (32).avif') }}" class="img-fluid">
                </a>
                <div class="gallery-title">U19 - 2015 Halloween Shootout</div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="gallery-card">
                <a href="{{ asset('public/assets/images/download (33).avif') }}" data-fancybox="championship-gallery">
                    <img src="{{ asset('public/assets/images/download (33).avif') }}" class="img-fluid">
                </a>
                <div class="gallery-title">HS Ignite - 2015 Classic for a Cause</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="gallery-card">
                <a href="{{ asset('public/assets/images/download (34).avif') }}" data-fancybox="championship-gallery">
                    <img src="{{ asset('public/assets/images/download (34).avif') }}" class="img-fluid">
                </a>
                <div class="gallery-title">U17 - 2014 Battle of the Bay</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="gallery-card">
                <a href="{{ asset('public/assets/images/download (35).avif') }}" data-fancybox="championship-gallery">
                    <img src="{{ asset('public/assets/images/download (35).avif') }}" class="img-fluid">
                </a>
                <div class="gallery-title">U13 - 2014 Lake Tahoe Classic</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="gallery-card">
                <a href="{{ asset('public/assets/images/download (36).avif') }}" data-fancybox="championship-gallery">
                    <img src="{{ asset('public/assets/images/download (36).avif') }}" class="img-fluid">
                </a>
                <div class="gallery-title">U19 - 2014 West Coast Showcase</div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="gallery-card">
                <a href="{{ asset('public/assets/images/download (37).avif') }}" data-fancybox="championship-gallery">
                    <img src="{{ asset('public/assets/images/download (37).avif') }}" class="img-fluid">
                </a>
                <div class="gallery-title">U19 - 2014 Pacific Surf</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="gallery-card">
                <a href="{{ asset('public/assets/images/download (38).avif') }}" data-fancybox="championship-gallery">
                    <img src="{{ asset('public/assets/images/download (38).avif') }}" class="img-fluid">
                </a>
                <div class="gallery-title">HS Elite - 2014 West Coast Showcase</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="gallery-card">
                <a href="{{ asset('public/assets/images/download (39).avif') }}" data-fancybox="championship-gallery">
                    <img src="{{ asset('public/assets/images/download (39).avif') }}" class="img-fluid">
                </a>
                <div class="gallery-title">HS - 2014 Yolo</div>
            </div>
        </div>

    </div>
</section>



@endsection
