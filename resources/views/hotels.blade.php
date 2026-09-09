@extends('layouts.app')

@section('title', 'Hotels - ACES Lacrosse')

@section('content')
<style>
    /* --- HERO CAROUSEL --- */
    #hotelCarousel {
        position: relative;
    }

    .carousel-item img {
        width: 100%;
        height: auto;
        object-fit: cover;
    }

    /* Hide controls by default */
    .carousel-control-prev,
    .carousel-control-next {
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    /* Show on hover */
    #hotelCarousel:hover .carousel-control-prev,
    #hotelCarousel:hover .carousel-control-next {
        opacity: 1;
    }

    /* White box background for arrows */
    .carousel-control-prev-icon,
    .carousel-control-next-icon {
        background-color: rgba(255, 255, 255, 0.85);
        padding: 20px;
        background-size: 50%;
        color: #000; 
        box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        
    }

    /* Position arrows nicely on corners */
    .carousel-control-prev {
        left: 70px;
    }
    .carousel-control-next {
        right: 70px;
    }

    /* --- HOTEL INFO SECTION --- */
    .hotel-section {
        text-align: center;
        padding: 60px 20px;
    }

    .hotel-title {
        font-size: 32px;
        font-weight: 800;
        color: #6a0dad;
        text-transform: uppercase;
        margin-bottom: 24px;
    }

    .hotel-contact {
        display: inline-block;
        background: #6a0dad;
        color: #fff;
        font-size: 18px;
        padding: 14px 30px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        width: 75%;
        transition: background 0.3s ease;
    }

    .hotel-contact:hover {
        background: #4b0082;
    }
/* --- INDICATORS (dots) --- */
.carousel-indicators {
    display: none !important; /* hide by default on desktop */
}

.carousel-indicators [data-bs-target] {
    background-color: #ffffffff;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    opacity: 0.7;
}

.carousel-indicators .active {
    opacity: 1;
    transform: scale(1.3);
    background-color: #888888ff;
}

.carousel-control-prev {
    left: 0px;
}

.carousel-control-next {
    right: 0px;
}

/* --- MOBILE VIEW --- */
@media (max-width: 768px) {
    .carousel-item img {
        height: fit-content;
    }

    .carousel-control-prev,
    .carousel-control-next {
        display: none !important; /* hide arrows on mobile */
    }

    .hotel-title {
        font-size: 22px;
    }

    .hotel-contact {
        font-size: 16px;
        padding: 12px 24px;
    }

    /* ✅ Force show dots on mobile */
    .carousel-indicators {
        display: flex !important;
        justify-content: center;
        gap: 8px;
        bottom: 2px;
    }
}

 @media screen and (min-width: 2560px) {
    .container, .container-lg, .container-md, .container-sm, .container-xl, .container-xxl {
      max-width: 230rem;
    }
    .hotel-title {
        font-size: 10rem;
    }
    .hotel-contact {
        font-size: 5rem;
    }

 }


</style>

<!-- ✅ Hero Section with Carousel -->
<section id="hotelCarousel" class="carousel slide" data-bs-ride="carousel">
  <!-- Indicators (Dots) -->
  <div class="carousel-indicators">
    <button type="button" data-bs-target="#hotelCarousel" data-bs-slide-to="0" class="active" aria-current="true"></button>
    <button type="button" data-bs-target="#hotelCarousel" data-bs-slide-to="1"></button>
    <button type="button" data-bs-target="#hotelCarousel" data-bs-slide-to="2"></button>
  </div>

  <!-- Carousel Images -->
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="{{ asset('public/assets/images/IMAGE-1.jpg') }}" class="d-block w-100" alt="Hotel 1">
    </div>
    <div class="carousel-item">
      <img src="{{ asset('public/assets/images/image.jpg') }}" class="d-block w-100" alt="Hotel 2">
    </div>
    <div class="carousel-item">
      <img src="{{ asset('public/assets/images/IMAGE-2.jpg') }}" class="d-block w-100" alt="Hotel 3">
    </div>
  </div>

  <!-- Navigation Arrows -->
  <button class="carousel-control-prev" type="button" data-bs-target="#hotelCarousel" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#hotelCarousel" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</section>


<!-- ✅ Info Section -->
<section class="hotel-section">
    <h2 class="hotel-title">HOTEL LIST COMING SOON</h2>
    <a href="tel:+14805601938" class="hotel-contact">
        Contact Scott Adams - (480) 560-1938
    </a>
</section>
@endsection
