<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ACES Lacrosse')</title>
     <link rel="icon" href="{{ asset('public/favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('public/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('public/afavicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('public/afavicon-16x16.png') }}">


    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.mageplaza.com/instagram-feed/js/instafeed.min.js"></script>




    <!-- Custom Styles -->
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            overflow-x: hidden;
        }

        h1,
        h2,
        h3,
        .section-title {
            font-family: 'Roboto', sans-serif;
            font-weight: 900;
        }


        /* Hero */
        .hero-section {
            position: relative;
            height: 80vh;
            overflow: hidden;
        }

        .hero-section img {
            width: 100%;
           height: auto;
            object-fit: cover;
        }

        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.3);
        }

        .hero-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            color: white;
            text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.4);
        }

        .hero-content h1 {
            font-size: 3rem;
            font-weight: 700;
        }

        .hero-content p {
            font-size: 1.2rem;
        }

        @media (max-width: 768px) {
            .hero-content h1 {
                font-size: 2rem;
            }
        }

        /* --- Evaluation Section --- */
        .evaluation-section {
            position: relative;
            width: 100%;
            min-height: 90vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        /* background image */
        .evaluation-overlay {
            position: absolute;
            inset: 0;
        }

        .evaluation-overlay img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* optional dark overlay for readability */
        .evaluation-overlay::after {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,0.35);
        }

        /* content */
        .evaluation-content {
            position: relative;
            z-index: 2;
            max-width: 1100px;
            width: 100%;
            text-align: center;
            padding: 0 20px;
        }

        .evaluation-section h1 {
            font-size: clamp(36px, 4vw, 72px);
            font-size: 48px !important;
            text-transform: uppercase;
            color: #ffffff;
            text-shadow:
                0 4px 12px rgba(0, 0, 0, 0.6);
        }

        .evaluation-section p {
            font-size: clamp(14px, 1.2vw, 20px);
            font-weight: 400;
            color: #f1f1f1;
            text-shadow: 0 2px 6px rgba(0,0,0,0.5);
        }

        .assessment-box {
            background: rgba(187, 30, 174, 0.66);
            display: inline-block;
            padding: 25px 200px;
            margin-top: 30px;
        }

        .assessment-box h2 {
            font-weight: 500;
            color: #fff;
            text-transform: uppercase;
        }

        .assessment-box p {
            font-weight: 300;
            color: #fff;
            font-size: 16px;

        }

        .btn-register {
            display: inline-block;
            /* ensures proper block behavior */
            background-color: #76139a !important;
            /* make sure background is applied */
            color: #fff !important;
            font-weight: 500 !important;
            padding: 12px 35px !important;
            /* slightly bigger for emphasis */
            text-transform: uppercase !important;
            margin-top: 15px;
            text-decoration: none;
            transition: background 0.3s ease;
        }

        .btn-register:hover {
            background-color: #76139a !important;
            color: #fff !important;
        }

        .hero-section {
            position: relative;
            width: 100%;
            height: auto;
            /* set desired height, adjust as needed */
            display: block;
            overflow: hidden;
            margin-bottom: 20px;
            /* space after hero */
        }

        .hero-section img {
            width: 100%;
           height: auto;
            /* fill the hero section */
            object-fit: cover;
            /* crop image nicely without distortion */
        }


        .hero-content {
            position: absolute;
            top: 50%;
            left: 50%;
            z-index: 1;
            color: #fff;
            text-align: center;
        }

        .evaluation-section {
            margin-top: 20px;
            /* optional if needed extra gap */
            margin-bottom: 20px;
            /* space after evaluation */
        }

        /* --- Responsive --- */
        @media (max-width: 991px) {
            .evaluation-section {
                min-height: 450px;
                padding: 30px 15px 50px;
            }

            .evaluation-section h1 {
                font-size: 36px;
            }

            .assessment-box {
                padding: 15px 20px;
            }

            .btn-register {
                padding: 10px 25px;
                font-size: 14px;
            }
        }

        @media (max-width: 575px) {
            .evaluation-section {
                min-height: 400px;
                padding: 20px 10px 40px;
            }

            .evaluation-section h1 {
                font-size: 28px;
            }

            .assessment-box {
                padding: 10px 15px;
            }

            .btn-register {
                width: 50%;
                /* full width on mobile */
                padding: 12px 0;
                font-size: 14px;
            }
        }

        /* --- Responsive Styling for Mobile --- */
        @media (max-width: 768px) {
            .evaluation-section {
                height: auto;
                background: url('{{ asset('images/aces-evaluation-bg.jpg') }}') center center / cover no-repeat;
                padding: 60px 15px;
            }

            .evaluation-content {
                max-width: 100%;
                padding: 0 15px;
            }

            .evaluation-section h1 {
                font-size: 26px;
                line-height: 1.3;
            }

            .evaluation-section p {
                font-size: 14px;
                line-height: 1.6;
                margin-top: 8px;
            }

            .assessment-box {
                padding: 20px;
                margin-top: 20px;
                width: 100%;
            }

            .assessment-box h2 {
                font-size: 20px;
            }

            .assessment-box p {
                font-size: 14px;
            }

            .btn-register {
                padding: 10px 10px !important;
                font-size: 14px;
            }
        }

        /* For extra small devices */
        @media (max-width: 480px) {
            .evaluation-section h1 {
                font-size: 22px;
            }

            .assessment-box h2 {
                font-size: 18px;
            }

            .assessment-box p {
                font-size: 13px;
            }
        }

        /* --- ACES Club Section --- */
        .aces-club-section .cards-container {
            display: flex;
            justify-content: center;
            /* center cards horizontally */
            gap: 20px;
            /* gap between cards */
            flex-wrap: wrap;
            /* allow wrapping for responsive */
            margin-top: 40px;
        }

        .aces-club-item {
            width: 28%;
            /* 3 cards per row on large screens */
            text-align: center;
        }

        .aces-club-item img {
            width: 100%;
            height: 400px;
            /* adjust as needed */
            object-fit: cover;
            border-radius: 0;
            display: block;
            margin-bottom: 10px;
        }

        .aces-club-item h4 {
            margin-bottom: 10px;
        }

        .aces-club-item p {
            font-size: 14px;
            line-height: 1.5;
            text-align: justify;
            margin-bottom: 15px;
        }

        /* --- Responsive for tablets --- */
        @media (max-width: 991px) {
            .aces-club-item {
                width: 45%;
                /* 2 cards per row */
            }
        }

        /* --- Responsive for mobile --- */
        @media (max-width: 575px) {
            .aces-club-item {
                width: 100%;
                /* 1 card per row */
                margin-bottom: 30px;
                /* spacing between stacked cards */
            }
        }

        /* ---------- Footer Section ---------- */
        .aces-footer {
            background-color: #6a6a6a;
            position: relative;
        }

        .aces-footer h5 {
            color: #fff;
            font-weight: 700;
        }

        .aces-footer p {
            color: #ddd;
            font-size: 1rem;
        }

        .footer-link {
            color: #ccc;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-link:hover {
            color: #fff;
        }

        .footer-divider {
            border-color: rgba(255, 255, 255, 0.2);
        }

        /* Back to Top Button */
        .back-to-top {
            position: fixed;
            bottom: 25px;
            right: 25px;
            background: #76139a;
            color: #fff;
            font-size: 1.2rem;
            border-radius: none;
            padding: 1px 3px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
        }

        .back-to-top:hover {
            background: #5c0f7a;
        }

        /* Responsive layout */
        @media (max-width: 768px) {
            .aces-footer {
                text-align: center;
            }

            .aces-footer .row {
                flex-direction: column;
            }

            .aces-footer .col-md-8 {
                justify-content: center !important;
                gap: 2rem;
            }
        }

        /* Move About & Support block slightly left — only on larger screens */
        .move-left {
            transform: none !important;
            /* reset on mobile */
        }

        @media (min-width: 992px) {
            .move-left {
                transform: translateX(-300px) !important;
                /* adjust left shift only for desktop */
            }
        }

        @media (max-width: 768px) {
            .aces-footer {
                text-align: left !important;
            }

            .aces-footer .row {
                flex-direction: column;
            }

            .aces-footer .col-md-4,
            .aces-footer .col-md-8 {
                text-align: left !important;
                justify-content: flex-start !important;
                align-items: flex-start !important;
            }

            .aces-footer .col-md-8 {
                gap: 2rem;
            }
        }
        @media screen and (min-width: 2560px) {


            p.mb-0.fw-semibold {
                font-size: 5rem;
            }
            .row.align-items-start {
                font-size: 5rem;
            }

            h5.fw-bold.mb-3 {
                font-size: 5rem;
            }

            .text-center.small {
                font-size: 3rem;
            }


        }
    </style>
</head>

<body>

    @include('partials.navbar')
    <main>
        @yield('content')
    </main>

    @include('partials.footer')
</body>

</html>
