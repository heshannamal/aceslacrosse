<style>
    .top-navbar {
        font-family: 'Roboto', sans-serif;
    }

    /* Top Navigation Bar */
    .top-navbar {
        background-color: #fff;
        padding: 20px 0;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        position: sticky;
        top: 0;
        width: 100%;
        z-index: 1000;
    }

    /* Desktop Navigation */
    .desktop-navigation {
        /* display: flex; */
        align-items: center;
        justify-content: space-between;
        position: sticky;
    }

    .left-icon {
        flex: 0 0 50px;
    }

    .center-content {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .navbar-brand {
        margin-bottom: 15px;
    }

    .navbar-brand img {
        height: 60px;
        width: auto;
    }

    .right-icon {
        flex: 0 0 50px;
        text-align: right;
    }

    /* Search Overlay */
    .search-overlay {
        display: none;
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        background: #fff;
        z-index: 10000;
        padding: 20px 0;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .search-overlay.active {
        display: block;
    }

    .search-container {
        display: flex;
        align-items: center;
        gap: 20px;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 15px;
    }

    .search-box {
        flex: 1;
        position: relative;
    }

    .search-input {
        width: 100%;
        padding: 15px 50px 15px 20px;
        font-size: 16px;
        border: 2px solid #333;
        outline: none;
        background: #fff;
    }

    .search-input:focus {
        border-color: #6b4ce6;
    }

    .search-submit {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        cursor: pointer;
        font-size: 20px;
        color: #333;
    }

    .search-submit:hover {
        color: #6b4ce6;
    }

    .search-close {
        font-size: 28px;
        cursor: pointer;
        color: #333;
        line-height: 1;
    }

    .search-close:hover {
        color: #6b4ce6;
    }

    .nav-menu {
        display: flex;
        list-style: none;
        gap: 30px;
        margin: 0;
        padding: 0;
        align-items: center;
    }

    .nav-menu li {
        position: relative;
    }

    .nav-menu a {
        text-decoration: none;
        color: #333;
        font-weight: 500;
        font-size: 15px;
        transition: color 0.3s;
        display: flex;
        align-items: center;
        gap: 5px;
        white-space: nowrap;
    }

    .nav-menu a:hover {
        color: #611eb2;
    }

    .nav-menu .active {
        color: #611eb2;
    }

    a.dropdown.active {
        color: #611eb2;
        text-decoration: underline;
        text-underline-offset: 0.3rem;
    }

    a.mobile.active {
        color: #611eb2;
    }

    a.mobile-dropdown-toggle.active {
        color: #611eb2;
    }

    .nav-icon {
        font-size: 22px;
        color: #333;
        cursor: pointer;
        transition: color 0.3s;
    }

    .nav-icon:hover {
        color: #6b4ce6;
    }

    .search-icon {
        font-size: 22px;
        color: #333;
        cursor: pointer;
        transition: color 0.3s;
    }

    .search-icon:hover {
        color: #6b4ce6;
    }

    /* Dropdown Menu */
    .dropdown-menu-custom {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        background: #fff;
        min-width: 220px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        border-radius: 8px;
        padding: 15px 0;
        margin-top: 10px;
        z-index: 1000;
    }

    .dropdown-menu-custom.show {
        display: block;
    }

    .dropdown-menu-custom a {
        display: block;
        padding: 12px 20px;
        color: #333;
        text-decoration: none;
        transition: background 0.3s;
    }

    .dropdown-menu-custom a:hover {
        background: #f5f5f5;
        color: #6b4ce6;
    }

    /* Mobile Navigation */
    .mobile-navigation {
        display: none;
    }

    .mobile-top-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 15px;
    }

    .hamburger {
        font-size: 24px;
        cursor: pointer;
        color: #333;
    }

    .mobile-brand img {
        height: 50px;
    }

    /* Mobile Menu Sidebar */
    .mobile-menu {
        position: fixed;
        top: 0;
        left: -100%;
        width: 100%;
        max-width: 380px;
        height: 100vh;
        background: #fff;
        box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        transition: left 0.3s ease;
        z-index: 9999;
        overflow-y: auto;
    }

    .mobile-menu.active {
        left: 0;
    }

    .mobile-menu-header {
        padding: 20px 25px;
        background: #fff;
    }

    .mobile-menu-close {
        font-size: 28px;
        cursor: pointer;
        color: #333;
        line-height: 1;
        display: inline-block;
    }

    .mobile-menu-items {
        list-style: none;
        padding: 0;
        margin: 0;
        background: #fff;
    }

    .mobile-menu-items>li {
        border-bottom: 1px solid #eee;
        background: #fff;
    }

    .mobile-menu-items>li>a {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 25px;
        color: #333;
        text-decoration: none;
        font-weight: 500;
        font-size: 18px;
        background: #fff;
    }

    .mobile-menu-items .arrow-icon {
        font-size: 16px;
        transition: transform 0.3s;
        color: #333;
    }

    .mobile-submenu {
        display: none;
        background: #f9f9f9;
        padding: 0;
    }

    .mobile-submenu.show {
        display: block;
    }

    .mobile-submenu a {
        display: block;
        padding: 14px 20px 14px 40px;
        color: #555;
        text-decoration: none;
        font-size: 15px;
        font-weight: 400;
        border-bottom: 1px solid #eee;
    }

    .mobile-submenu a:last-child {
        border-bottom: none;
    }

    .overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 9998;
    }

    .overlay.active {
        display: block;
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .nav-menu {
            gap: 20px;
        }

        .nav-menu a {
            font-size: 14px;
        }
    }

    @media (max-width: 991px) {
        .desktop-navigation {
            display: none;
        }

        .mobile-navigation {
            display: block;
        }

        .search-container {
            padding: 0 15px;
        }

        .search-input {
            padding: 12px 45px 12px 15px;
            font-size: 15px;
        }

        .search-close {
            font-size: 24px;
        }
    }
    @media screen and (min-width: 2560px) {
        .navbar-brand img {
            height: auto !important;;
            width: auto;
        }

        a.dropdown-toggle {
            font-size: 4rem !important;
        }
        a.dropdown {
            font-size: 4rem !important;
        }

        a.nav-item {
            font-size: 4rem !important;
        }
    }


</style>


<!-- Navigation Bar -->
<nav class="top-navbar">
    <!-- Search Overlay -->
    <div class="search-overlay" id="searchOverlay">
        <div class="search-container">
            <div class="search-box">
                <input type="text" class="search-input" placeholder="Search" id="searchInput">
                <button class="search-submit" type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </div>
            <i class="fas fa-times search-close" id="closeSearch"></i>
        </div>
    </div>

    <div class="container">
        <!-- Desktop Navigation -->
        <div class="desktop-navigation">
            {{-- <div class="left-icon">
                <i class="fas fa-search search-icon" id="desktopSearchIcon"></i>
            </div> --}}

            <div class="center-content">
                <a href="{{ URL('/') }}" class="navbar-brand">
                    <img src="{{ asset('public/assets/images/ACES-logo.webp') }}" alt="ACES Logo">
                </a>

                <ul class="nav-menu">
                    <li><a href="{{ URL('/') }}" class="nav-item {{ request()->is('/') ? 'active' : '' }}">Home</a></li>
                    <li class="dropdown-item-custom">
                        <a href="#"
                            class="nav-item dropdown-toggle {{ request()->is('pages/mission') || request()->is('pages/coaching-staff') || request()->is('pages/aces-in-college') || request()->is('pages/testimonials') || request()->is('pages/championships') ? 'active' : '' }}">
                            About
                        </a>
                        <div class="dropdown-menu-custom">
                            <a href="{{ URL('/pages/mission') }}"
                                class="nav-item dropdown {{ request()->is('pages/mission') ? 'active' : '' }}">Mission &
                                History</a>
                            <a href="{{ URL('/pages/coaching-staff') }}"
                                class="nav-item dropdown {{ request()->is('pages/coaching-staff') ? 'active' : '' }}">Coaching
                                Staff</a>
                            <a href="{{ URL('/pages/aces-in-college') }}"
                                class="nav-item dropdown {{ request()->is('pages/aces-in-college') ? 'active' : '' }}">ACES
                                Playing In
                                College</a>
                            <a href="{{ URL('/pages/testimonials') }}"
                                class="nav-item dropdown {{ request()->is('pages/testimonials') ? 'active' : '' }}">Testimonials</a>
                            <a href="{{ URL('/pages/championships') }}"
                                class="nav-item dropdown {{ request()->is('pages/championships') ? 'active' : '' }}">Championships</a>
                        </div>
                    </li>
                    <li><a href="{{ URL('/pages/academy') }}"
                            class="nav-item  {{ request()->is('pages/academy') ? 'active' : '' }}">Academy</a></li>
                    <li><a href="{{ URL('/pages/travel-teams') }}"
                            class="nav-item {{ request()->is('pages/travel-teams') ? 'active' : '' }}">Travel Teams</a></li>
                    {{-- <li><a href="{{ URL('/pages/hotels') }}"
                            class="nav-item {{ request()->is('pages/hotels') ? 'active' : '' }}">Hotels</a></li> --}}
                    <li><a href="https://pinnaclelax.com/" class="nav-item" target="__blank">Pinnacle</a></li>
                    <li><a href="{{ URL('/pages/tryouts') }}"
                            class="nav-item {{ request()->is('pages/tryouts') ? 'active' : '' }}">Tryouts & New Players</a></li>
                    <li class="dropdown-item-custom">
                        <a href="#"
                            class="dropdown-toggle {{ request()->is('pages/sacramento-fall-league') || request()->is('pages/grow-your-game-el-dorado-hills') || request()->is('pages/aces-grow-your-game-davis') ? 'active' : '' }}">
                            Camps, Clinics and Leagues
                        </a>
                        <div class="dropdown-menu-custom">
                            <a href="{{ URL('/pages/sacramento-fall-league') }}"
                                class="nav-item dropdown {{ request()->is('pages/sacramento-fall-league') ? 'active' : '' }}">Sacramento
                                Fall League</a>
                            <a href="{{ URL('/pages/grow-your-game-el-dorado-hills') }}"
                                class="nav-item dropdown {{ request()->is('pages/grow-your-game-el-dorado-hills') ? 'active' : '' }}">Grow
                                Your Game - El Dorado Hills</a>
                            <a href="{{ URL('/pages/aces-grow-your-game-davis') }}"
                                class="nav-item dropdown {{ request()->is('pages/aces-grow-your-game-davis') ? 'active' : '' }}">Grow
                                Your Game - Davis</a>
                        </div>
                    </li>
                    {{-- <li><a href="{{ URL('/pages/team-store') }}"
                            class="nav-item {{ request()->is('pages/team-store') ? 'active' : '' }}">Shop</a></li> --}}
                    <li><a href="{{ URL('/pages/contact-us') }}"
                            class="nav-item {{ request()->is('pages/contact-us') ? 'active' : '' }}">Contact Us</a></li>
                </ul>
            </div>

            {{-- <div class="right-icon">
                <i class="fa-regular fa-user nav-icon"></i>
            </div> --}}
        </div>

        <!-- Mobile Navigation -->
        <div class="mobile-navigation">
            <div class="mobile-top-bar">
                <i class="fas fa-bars hamburger" id="hamburger"></i>
                <a href="{{ URL('/') }}" class="mobile-brand">
                    <img src="{{ asset('public/assets/images/ACES-logo.webp') }}" alt="ACES Logo">
                </a>
                <i class="fas fa-search search-icon" id="mobileSearchIcon"></i>
            </div>
        </div>
    </div>
</nav>

<!-- Mobile Menu Sidebar -->
<div class="mobile-menu" id="mobileMenu">
    <div class="mobile-menu-header">
        <i class="fas fa-times mobile-menu-close" id="closeMenu"></i>
    </div>

    <ul class="mobile-menu-items">
        <li><a href="{{ URL('/') }}" class="mobile menu {{ request()->is('/') ? 'active' : '' }}">Home</a></li>
        <li>
            <a href="#"
                class="mobile-dropdown-toggle {{ request()->is('pages/mission') || request()->is('pages/coaching-staff') || request()->is('pages/aces-in-college') || request()->is('pages/testimonials') || request()->is('pages/championships') ? 'active' : '' }}">
                About <i class="fas fa-arrow-right arrow-icon"></i>
            </a>
            <div class="mobile-submenu">
                <a href="{{ URL('/pages/mission') }}"
                    class="mobile {{ request()->is('pages/mission') ? 'active' : '' }}">Mission & History</a>
                <a href="{{ URL('/pages/coaching-staff') }}"
                    class="mobile {{ request()->is('pages/coaching-staff') ? 'active' : '' }}">Coaching Staff</a>
                <a href="{{ URL('/pages/aces-in-college') }}"
                    class="mobile {{ request()->is('pages/aces-in-college') ? 'active' : '' }}">ACES Playing In
                    College</a>
                <a href="{{ URL('/pages/testimonials') }}"
                    class="mobile {{ request()->is('pages/testimonials') ? 'active' : '' }}">Testimonials</a>
                <a href="{{ URL('/pages/championships') }}"
                    class="mobile {{ request()->is('pages/championships') ? 'active' : '' }}">Championships</a>
            </div>
        </li>
        <li><a href="{{ URL('/pages/academy') }}"
                class="mobile menu {{ request()->is('pages/academy') ? 'active' : '' }}">Academy</a></li>
        <li><a href="{{ URL('/pages/travel-teams') }}"
                class="mobile menu {{ request()->is('pages/travel-teams') ? 'active' : '' }}">Travel Teams</a></li>
        {{-- <li><a href="{{ URL('/pages/hotels') }}"
                class="mobile menu {{ request()->is('pages/hotels') ? 'active' : '' }}">Hotels</a></li> --}}
        <li><a href="https://pinnaclelax.com/" target="__blank">Pinnacle</a></li>
        <li><a href="{{ URL('/pages/tryouts') }}"
                class="mobile menu {{ request()->is('pages/tryouts') ? 'active' : '' }}">Tryouts & New Players</a>
        </li>
        <li>
            <a href="#"
                class="mobile-dropdown-toggle {{ request()->is('pages/sacramento-fall-league') || request()->is('pages/grow-your-game-el-dorado-hills') || request()->is('pages/aces-grow-your-game-davis') ? 'active' : '' }}">
                Camps, Clinics and Leagues <i class="fas fa-arrow-right arrow-icon"></i>
            </a>
            <div class="mobile-submenu">
                <a href="{{ URL('/pages/sacramento-fall-league') }}"
                    class="mobile {{ request()->is('pages/sacramento-fall-league') ? 'active' : '' }}">Sacramento
                    Fall League</a>
                <a href="{{ URL('/pages/grow-your-game-el-dorado-hills') }}"
                    class="mobile {{ request()->is('pages/grow-your-game-el-dorado-hills') ? 'active' : '' }}">Grow
                    Your Game - El Dorado Hills</a>
                <a href="{{ URL('/pages/aces-grow-your-game-davis') }}"
                    class="mobile {{ request()->is('pages/aces-grow-your-game-davis') ? 'active' : '' }}">Grow
                    Your Game - Davis</a>
            </div>
        </li>
        {{-- <li><a href="{{ URL('/pages/team-store') }}"
                class="mobile {{ request()->is('pages/team-store') ? 'active' : '' }}">Shop</a></li> --}}
        <li><a href="{{ URL('/pages/contact-us') }}"
                class="mobile {{ request()->is('pages/contact-us') ? 'active' : '' }}">Contact Us</a></li>
    </ul>
</div>

<!-- Overlay -->
<div class="overlay" id="overlay"></div>

<script>
    $(document).ready(function() {
        // Desktop dropdown toggle
        $('.dropdown-toggle').on('click', function(e) {
            e.preventDefault();
            const dropdown = $(this).siblings('.dropdown-menu-custom');
            $('.dropdown-menu-custom').not(dropdown).removeClass('show');
            dropdown.toggleClass('show');
        });

        // Close dropdown when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.dropdown-item-custom').length) {
                $('.dropdown-menu-custom').removeClass('show');
            }
        });

        // Open search overlay - Desktop
        $('#desktopSearchIcon').on('click', function() {
            $('#searchOverlay').addClass('active');
            $('#searchInput').focus();
        });

        // Open search overlay - Mobile
        $('#mobileSearchIcon').on('click', function() {
            $('#searchOverlay').addClass('active');
            $('#searchInput').focus();
        });

        // Close search overlay
        $('#closeSearch').on('click', function() {
            $('#searchOverlay').removeClass('active');
            $('#searchInput').val('');
        });

        // Close search on Escape key
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape' && $('#searchOverlay').hasClass('active')) {
                $('#searchOverlay').removeClass('active');
                $('#searchInput').val('');
            }
        });

        // Search submit
        $('.search-submit').on('click', function(e) {
            e.preventDefault();
            const searchValue = $('#searchInput').val();
            if (searchValue.trim()) {
                console.log('Searching for:', searchValue);
                // Add your search logic here
            }
        });

        // Mobile menu open
        $('#hamburger').on('click', function() {
            $('#mobileMenu').addClass('active');
            $('#overlay').addClass('active');
            $('body').css('overflow', 'hidden');
        });

        // Mobile menu close
        $('#closeMenu, #overlay').on('click', function() {
            $('#mobileMenu').removeClass('active');
            $('#overlay').removeClass('active');
            $('body').css('overflow', 'auto');
        });

        // Mobile dropdown toggle
        $('.mobile-dropdown-toggle').on('click', function(e) {
            e.preventDefault();
            const $this = $(this);
            const submenu = $this.siblings('.mobile-submenu');
            const icon = $this.find('.arrow-icon');

            // Close other submenus
            $('.mobile-submenu').not(submenu).removeClass('show');
            $('.arrow-icon').not(icon).removeClass('fa-arrow-down').addClass('fa-arrow-right');

            // Toggle current submenu
            submenu.toggleClass('show');

            if (submenu.hasClass('show')) {
                icon.removeClass('fa-arrow-right').addClass('fa-arrow-down');
            } else {
                icon.removeClass('fa-arrow-down').addClass('fa-arrow-right');
            }
        });
    });
</script>
