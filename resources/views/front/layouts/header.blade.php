<!-- Start Header Area -->
<div class="top-header-area bg-color-091c47">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-12">
                <ul class="header-left-content">
                    <li>
                        <a href="{{ route('research') }}">Research</a>
                    </li>
{{--                    <li>--}}
{{--                        <a href="{{ route('conference') }}">Conferences</a>--}}
{{--                    </li>--}}
                    <li>
                        <a href="{{ route('achievements') }}">Achievements & Awards</a>
                    </li>
                    <li>
                        <a href="{{ route('news-and-events') }}">News & Events</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- End Header Area -->

<div class="navbar-area">
    <div class="mobile-responsive-nav">
        <div class="container">
            <div class="mobile-responsive-menu mean-container"><div class="mean-bar"><a href="#nav" class="meanmenu-reveal" ><span><span><span></span></span></span></a><nav class="mean-nav">
                        <ul class="navbar-nav m-auto" style="display: none;">
                        <li class="nav-item">
                            <a href="{{ route('home') }}" class="nav-link border-style is_active_home">Home</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('about-us') }}" class="nav-link border-style is_active_about">About Us</a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('departments') }}" class="nav-link border-style is_active_department">Departments</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('our-team') }}" class="nav-link border-style is_active_team">Our Team</a>
                        </li>
                        </ul>
                    </nav></div>
                    <div class="logo">
                        <a href="{{ route('home') }}">
                            <img src="{{asset('/front/coreui/assets/logo/logo_new.png')}}" class="main-logo" alt="logo"  width="150px">
                            <img src="assets/images/white-logo.png" class="white-logo" alt="logo">
                        </a>
                    </div>
            </div>
        </div>
    </div>

    <div class="desktop-nav">
        <div class="container">
            <nav class="navbar navbar-expand-md navbar-light">
                <a class="navbar-brand" href="{{ route('home') }}">
                    <img src="{{asset('/front/coreui/assets/logo/logo_new.png')}}" class="main-logo" alt="logo"  width="120px">
                    <img src="{{asset('/front/coreui/assets/logo/logo_new.png')}}" class="white-logo" alt="logo" width="120px">
                </a>


                <div class="mean-push"></div><div class="collapse navbar-collapse mean-menu" id="navbarSupportedContent" style="display: none;">
                    <ul class="navbar-nav m-auto">
                        <li class="nav-item">
                            <a href="{{ route('home') }}" class="nav-link border-style is_active_home">Home</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('about-us') }}" class="nav-link border-style is_active_about">About Us</a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('departments') }}" class="nav-link border-style is_active_department">Departments</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('our-team') }}" class="nav-link border-style is_active_team">Our Team</a>
                        </li>
{{--                        <li class="nav-item">--}}
{{--                            <a href="{{ route('news-and-events') }}" class="nav-link border-style is_active_event">News & Events</a>--}}
{{--                        </li>--}}
                        <li class="nav-item">
                            <a href="{{ route('contact-us') }}" class="nav-link border-style is_active_contact">Contact Us</a>
                        </li>
                    </ul>


                </div>
            </nav>
        </div>
    </div>


</div>
