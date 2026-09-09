@extends('layouts.app')
@section('title', 'ACES Playing In College')
@section('content')
<style>
    .section-heading {
    margin: 35px 0 35px 0;
}

.section-heading h5 {
    margin: 0 0 6px 0;
    font-size: 1.5rem;
    font-weight: 550;
    color: #7c7c7cff;
}

.section-heading .line {
    width: 100%;
    height: 2px;
    background: #ccc;
}
.college-section {
    padding: 50px 0;
}

.college-section h2 {
    text-align: center;
    font-weight: 700;
    margin-bottom: 40px;
}

.college-class-title {
    font-weight: 700;
    margin: 40px 0 20px 0;
    font-size: 1rem;
    text-transform: uppercase;
}

.college-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    row-gap: 25px;
    column-gap:0px;
}

.college-card img {
    width: 90%;
    display: block;
}
/* ---------- MOBILE VIEW (max-width: 768px) ---------- */
@media (max-width: 768px) {

    /* Center the section heading */
    .section-heading {
        text-align: center;
        font-size: 1.5rem;
    }

    .section-heading .line {
        margin: 0 auto;  /* center the line */
        width: 60%;      /* optional, looks cleaner on mobile */
    }

    /* Grid becomes 1 column */
    .college-grid {
        grid-template-columns: 1fr !important;
        justify-items: center;   /* center cards */
        row-gap: 5px !important;
    }

    /* Make image smaller + centered */
    .college-card img {
        width: 100%;        /* smaller image for mobile */
        margin: 0 auto;    /* center the image */
        display: block;
    }
    .college-section h2 {
        font-size: 2rem;
}
}

@media screen and (min-width: 2560px) {
  .container, .container-lg, .container-md, .container-sm, .container-xl, .container-xxl {
    max-width: 230rem;
  }

  h2 {
    font-size: 7rem;
}

h5 {
    font-size: 4rem !important;
}


}


</style>

<section class="college-section container">

    <h2>ACES PLAYING IN COLLEGE</h2>

    <!-- CLASS OF 2026 -->
    <div class="section-heading">
    <h5>CLASS OF 2026</h5>
    <div class="line"></div>
</div>


    <div class="college-grid">

        <div class="college-card">
            <img src="{{ asset('public/assets/images/class2026/ACES 2026_ACES 2026 1.svg') }}">
        </div>

        <div class="college-card">
            <img src="{{ asset('public/assets/images/class2026/ACES 2026_ACES 2026 1 copy.svg') }}">
        </div>

        <div class="college-card">
            <img src="{{ asset('public/assets/images/class2026/ACES 2026_ACES 2026 1 copy 2.svg') }}">
        </div>

        <div class="college-card">
            <img src="{{ asset('public/assets/images/class2026/ACES 2026_ACES 2026 1 copy 3.svg') }}">
        </div>

        <div class="college-card">
            <img src="{{ asset('public/assets/images/class2026/ACES 2026_ACES 2026 1 copy 4.svg') }}">
        </div>

        <div class="college-card">
            <img src="{{ asset('public/assets/images/class2026/ACES 2026_ACES 2026 1 copy 5.svg') }}">
        </div>

        <div class="college-card">
            <img src="{{ asset('public/assets/images/class2026/ACES 2026_ACES 2026 1 copy 6.svg') }}">
        </div>

        <div class="college-card">
            <img src="{{ asset('public/assets/images/class2026/ACES 2026_ACES 2026 1 copy 7.svg') }}">
        </div>

        <div class="college-card">
            <img src="{{ asset('public/assets/images/class2026/ACES 2026_ACES 2026 1 copy 8.svg') }}">
        </div>

        <div class="college-card">
            <img src="{{ asset('public/assets/images/1_ACES 2026 1.svg') }}">
        </div>

        <div class="college-card">
            <img src="{{ asset('public/assets/images/1_ACES 2026 2.svg') }}">
        </div>

        <div class="college-card">
            <img src="{{ asset('public/assets/images/1_ACES 2026 3.svg') }}">
        </div>

        <div class="college-card">
            <img src="{{ asset('public/assets/images/1_ACES 2026 4.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/1_ACES 2026 5.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/1_ACES 2026 6.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/1_ACES 2026 7.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/1_ACES 2026 8.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/1_ACES 2026 9.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/1_ACES 2026 10.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/1_ACES 2026 11.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/1_ACES 2026 12.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/1_ACES 2026 13.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/1_ACES 2026 14.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/1_ACES 2026 15.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/1_ACES 2026 16.svg') }}">
        </div>
    </div>


    <!-- CLASS OF 2026 -->
    <div class="section-heading">
    <h5>CLASS OF 2025</h5>
    <div class="line"></div>
</div>


    <div class="college-grid">

        <div class="college-card">
            <img src="{{ asset('public/assets/images/20251.png') }}">
        </div>

        <div class="college-card">
            <img src="{{ asset('public/assets/images/20252.png') }}">
        </div>

        <div class="college-card">
            <img src="{{ asset('public/assets/images/20253.png') }}">
        </div>

        <div class="college-card">
            <img src="{{ asset('public/assets/images/20254.png') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/20255.png') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/20256.png') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/20257.png') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/20258.png') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/20259.png') }}">
        </div>

    </div>
    <!-- CLASS OF 2026 -->
    <div class="section-heading">
    <h5>CLASS OF 2024</h5>
    <div class="line"></div>
</div>


    <div class="college-grid">

        <div class="college-card">
            <img src="{{ asset('public/assets/images/20241.svg') }}">
        </div>

        <div class="college-card">
            <img src="{{ asset('public/assets/images/20242.svg') }}">
        </div>

        <div class="college-card">
            <img src="{{ asset('public/assets/images/20243.svg') }}">
        </div>

        <div class="college-card">
            <img src="{{ asset('public/assets/images/20244.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/20245.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/20246.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/20247.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/20248.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/20249.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/202410.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/202411.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/202412.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/202413.svg') }}">
        </div>
                <div class="college-card">
            <img src="{{ asset('public/assets/images/202414.svg') }}">
        </div>
                <div class="college-card">
            <img src="{{ asset('public/assets/images/202415.svg') }}">
        </div>
                <div class="college-card">
            <img src="{{ asset('public/assets/images/202416.svg') }}">
        </div>
                <div class="college-card">
            <img src="{{ asset('public/assets/images/202417.svg') }}">
        </div>
                <div class="college-card">
            <img src="{{ asset('public/assets/images/202418.svg') }}">
        </div>
    </div>
        <!-- CLASS OF 2026 -->
    <div class="section-heading">
    <h5>CLASS OF 2023</h5>
    <div class="line"></div>
</div>


    <div class="college-grid">

        <div class="college-card">
            <img src="{{ asset('public/assets/images/20231.svg') }}">
        </div>

        <div class="college-card">
            <img src="{{ asset('public/assets/images/20232.svg') }}">
        </div>

        <div class="college-card">
            <img src="{{ asset('public/assets/images/20233.svg') }}">
        </div>

        <div class="college-card">
            <img src="{{ asset('public/assets/images/20234.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/20235.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/20236.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/20237.svg') }}">
        </div>

    </div>
        <div class="section-heading">
    <h5>CLASS OF 2022</h5>
    <div class="line"></div>
</div>


    <div class="college-grid">

        <div class="college-card">
            <img src="{{ asset('public/assets/images/20221.svg') }}">
        </div>

        <div class="college-card">
            <img src="{{ asset('public/assets/images/20222.svg') }}">
        </div>

        <div class="college-card">
            <img src="{{ asset('public/assets/images/20223.svg') }}">
        </div>

        <div class="college-card">
            <img src="{{ asset('public/assets/images/20224.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/20225.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/20226.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/20227.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/20228.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/20229.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/202210.svg') }}">
        </div>

    </div>
        <div class="section-heading">
    <h5>CLASS OF 2021</h5>
    <div class="line"></div>
</div>


    <div class="college-grid">

        <div class="college-card">
            <img src="{{ asset('public/assets/images/20211.svg') }}">
        </div>

        <div class="college-card">
            <img src="{{ asset('public/assets/images/20212.svg') }}">
        </div>

        <div class="college-card">
            <img src="{{ asset('public/assets/images/20213.svg') }}">
        </div>

        <div class="college-card">
            <img src="{{ asset('public/assets/images/20214.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/20215.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/20216.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/20217.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/20218.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/20219.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/202110.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/20211.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/202112.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/202113.svg') }}">
        </div>
                <div class="college-card">
            <img src="{{ asset('public/assets/images/202114.svg') }}">
        </div>
                        <div class="college-card">
            <img src="{{ asset('public/assets/images/202115.svg') }}">
        </div>
                        <div class="college-card">
            <img src="{{ asset('public/assets/images/202116.svg') }}">
        </div>
                        <div class="college-card">
            <img src="{{ asset('public/assets/images/202117.svg') }}">
        </div>
                        <div class="college-card">
            <img src="{{ asset('public/assets/images/202118.svg') }}">
        </div>
    </div>
        <div class="section-heading">
    <h5>CLASS OF 2020</h5>
    <div class="line"></div>
</div>


    <div class="college-grid">

        <div class="college-card">
            <img src="{{ asset('public/assets/images/20201.svg') }}">
        </div>

        <div class="college-card">
            <img src="{{ asset('public/assets/images/20202.svg') }}">
        </div>

        <div class="college-card">
            <img src="{{ asset('public/assets/images/20203.svg') }}">
        </div>

        <div class="college-card">
            <img src="{{ asset('public/assets/images/20204.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/20205.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/20206.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/20207.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/20208.svg') }}">
        </div>

    </div>
        <div class="section-heading">
    <h5>CLASS OF 2019</h5>
    <div class="line"></div>
</div>


    <div class="college-grid">

        <div class="college-card">
            <img src="{{ asset('public/assets/images/20191.svg') }}">
        </div>

        <div class="college-card">
            <img src="{{ asset('public/assets/images/20192.svg') }}">
        </div>

        <div class="college-card">
            <img src="{{ asset('public/assets/images/20193.svg') }}">
        </div>

        <div class="college-card">
            <img src="{{ asset('public/assets/images/20195.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/20194.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/20196.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/20197.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/20198.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/20199.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/201910.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/201911.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/201912.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/201913.svg') }}">
        </div>
                <div class="college-card">
            <img src="{{ asset('public/assets/images/201914.svg') }}">
        </div>
                        <div class="college-card">
            <img src="{{ asset('public/assets/images/201915.svg') }}">
        </div>


    </div>
        <div class="section-heading">
    <h5>CLASS OF 2018</h5>
    <div class="line"></div>
</div>


    <div class="college-grid">

        <div class="college-card">
            <img src="{{ asset('public/assets/images/20181.svg') }}">
        </div>

        <div class="college-card">
            <img src="{{ asset('public/assets/images/20182.svg') }}">
        </div>

        <div class="college-card">
            <img src="{{ asset('public/assets/images/20183.svg') }}">
        </div>

        <div class="college-card">
            <img src="{{ asset('public/assets/images/20184.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/20185.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/20186.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/20187.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/20188.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/20189.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/201810.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/201811.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/201812.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/201813.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/201814.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/201815.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/201816.svg') }}">
        </div>

    </div>
        <div class="section-heading">
    <h5>CLASS OF 2017</h5>
    <div class="line"></div>
</div>


    <div class="college-grid">

        <div class="college-card">
            <img src="{{ asset('public/assets/images/20171.svg') }}">
        </div>

        <div class="college-card">
            <img src="{{ asset('public/assets/images/20172.svg') }}">
        </div>

        <div class="college-card">
            <img src="{{ asset('public/assets/images/20173.svg') }}">
        </div>

        <div class="college-card">
            <img src="{{ asset('public/assets/images/20174.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/20175.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/20176.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/20177.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/20178.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/20179.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/201710.svg') }}">
        </div>

    </div>
        <div class="section-heading">
    <h5>CLASS OF 2016</h5>
    <div class="line"></div>
</div>


    <div class="college-grid">

        <div class="college-card">
            <img src="{{ asset('public/assets/images/20161.svg') }}">
        </div>

        <div class="college-card">
            <img src="{{ asset('public/assets/images/20162.svg') }}">
        </div>

        <div class="college-card">
            <img src="{{ asset('public/assets/images/20163.svg') }}">
        </div>

        <div class="college-card">
            <img src="{{ asset('public/assets/images/20164.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/20165.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/20166.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/20167.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/20168.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/20169.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/201610.svg') }}">
        </div>

    </div>
        <div class="section-heading">
    <h5>CLASS OF 2015</h5>
    <div class="line"></div>
</div>


    <div class="college-grid">

        <div class="college-card">
            <img src="{{ asset('public/assets/images/20151.svg') }}">
        </div>

        <div class="college-card">
            <img src="{{ asset('public/assets/images/20152.svg') }}">
        </div>

        <div class="college-card">
            <img src="{{ asset('public/assets/images/20153.svg') }}">
        </div>

        <div class="college-card">
            <img src="{{ asset('public/assets/images/20154.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/20155.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/20156.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/20157.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/20158.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/20159.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/201510.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/201511.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/201512.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/201513.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/201514.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/201515.svg') }}">
        </div>
    </div>
        <div class="section-heading">
    <h5>CLASS OF 2014</h5>
    <div class="line"></div>
</div>


    <div class="college-grid">

        <div class="college-card">
            <img src="{{ asset('public/assets/images/20141.svg') }}">
        </div>

        <div class="college-card">
            <img src="{{ asset('public/assets/images/20142.svg') }}">
        </div>

        <div class="college-card">
            <img src="{{ asset('public/assets/images/20143.svg') }}">
        </div>

        <div class="college-card">
            <img src="{{ asset('public/assets/images/20144.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/20145.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/20146.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/20147.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/20148.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/20149.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/201410.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/201411.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/201412.svg') }}">
        </div>

    </div>
        <div class="section-heading">
    <h5>CLASS OF 2013</h5>
    <div class="line"></div>
</div>


    <div class="college-grid">

        <div class="college-card">
            <img src="{{ asset('public/assets/images/20131.svg') }}">
        </div>
        <div class="college-card">
            <img src="{{ asset('public/assets/images/20132.svg') }}">
        </div>
    </div>
</section>
@endsection

