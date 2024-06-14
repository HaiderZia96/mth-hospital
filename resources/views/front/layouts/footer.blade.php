<!-- Start Footer Area -->
<div class="footer-area bg-color-f1f5f8 pt-100 pb-70">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-sm-6">
                <div class="single-footer-widget">
                    <a href="{{ route('home') }}" class="logo">
                        <img src="{{asset('/front/coreui/assets/logo/logo_new.png')}}"  class="main-logo" alt="logo" width="120px">
                        <img src="{{asset('/front/coreui/assets/logo/logo_new.png')}}"  class="white-logo" alt="logo" width="120px">
                    </a>
                    <p>Praesent sapien massa, convallis a pellentesque nec, egestas non nisi. Donec sollicitudin molestie.</p>
                    <h4>Sargodha Rd, University Town, Faisalabad, Punjab</h4>
                    <ul class="info">
                        <li>
                            <span>EMAIL US:</span>
                            <a href="#"><span>info@mth.org.pk</span></a>
                        </li>
                        <li>
                            <span>CALL US:</span>
                            <a href="tel: 041-8869861, 041-8869862">041-8869861 , 041-8869862</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-3 col-sm-6">
                <div class="single-footer-widget">
                    <h3>Departments</h3>

                    <ul class="import-link">
                        @foreach($footerDepartments as $department)
                        <li>
                            <a href="{{route('departments-details',$department->slug)}}">
                                <i class="ri-arrow-right-s-line"></i>{{$department->title}}
                            </a>
{{--                            <a href="#">--}}
{{--                                <i class="ri-arrow-right-s-line"></i>--}}
{{--                                Emergency Departments--}}
{{--                            </a>--}}
                        </li>
                        @endforeach
{{--                        <li>--}}
{{--                            <a href="#">--}}
{{--                                <i class="ri-arrow-right-s-line"></i>--}}
{{--                                Orthopedics--}}
{{--                            </a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="#">--}}
{{--                                <i class="ri-arrow-right-s-line"></i>--}}
{{--                                Neurosciences--}}
{{--                            </a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="#">--}}
{{--                                <i class="ri-arrow-right-s-line"></i>--}}
{{--                                Gastroenterology--}}
{{--                            </a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="#">--}}
{{--                                <i class="ri-arrow-right-s-line"></i>--}}
{{--                                Bariatric Surgery--}}
{{--                            </a>--}}
{{--                        </li>--}}
                    </ul>
                </div>
            </div>

            <div class="col-lg-3 col-sm-6">
                <div class="single-footer-widget">
                    <h3>Helpful Links</h3>

                    <ul class="import-link">
                        <li>
                            <a href="{{ route('about-us') }}">
                                <i class="ri-arrow-right-s-line"></i>
                                About Us
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('research') }}">
                                <i class="ri-arrow-right-s-line"></i>
                                Researches
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('our-team') }}">
                                <i class="ri-arrow-right-s-line"></i>
                               Our Team
                            </a>
                        </li>
{{--                        <li>--}}
{{--                            <a href="{{ route('conference') }}">--}}
{{--                                <i class="ri-arrow-right-s-line"></i>--}}
{{--                                Conferences--}}
{{--                            </a>--}}
{{--                        </li>--}}
                        <li>
                            <a href="{{ route('achievements') }}">
                                <i class="ri-arrow-right-s-line"></i>
                               Achievements
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-3 col-sm-6">
                <div class="single-footer-widget">
                    <h3>Subscribe Our Newsletter</h3>

                    <form class="newsletter-form" data-toggle="validator">
                        <input type="email" class="form-control" placeholder="Email address" name="EMAIL" required autocomplete="off">

                        <button class="default-btn" type="submit">
                            Submit now
                        </button>

                        <div id="validator-newsletter" class="form-result"></div>
                    </form>

                    <ul class="socila-link">
                        <li>
                            <a href="https://www.facebook.com/" target="_blank">
                                <img src="{{asset('/front/coreui/assets/img/facebook.svg')}}" alt="Image">
                            </a>
                        </li>
                        <li>
                            <a href="https://www.facebook.com/" target="_blank">
                                <img src="{{asset('/front/coreui/assets/img/twitter.svg')}}" alt="Image">
                            </a>
                        </li>
                        <li>
                            <a href="https://www.facebook.com/" target="_blank">
                                <img src="{{asset('/front/coreui/assets/img/linkedin.svg')}}" alt="Image">
                            </a>
                        </li>
                        <li>
                            <a href="https://www.instagram.com/" target="_blank">
                                <img src="{{asset('/front/coreui/assets/img/instagram.svg')}}" alt="Image">
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Footer Area -->

<!-- Start Copyright Area -->
<div class="copy-right-area">
    <div class="container">
{{--        <p>© <a href="https://mth.org.pk/" target="_blank">MTH</a></p>--}}
        <p>Copyright <span class="las la-copyright"></span> <?= date('Y')?>. All Rights Reserved By <a href="https://new.mth.org.pk/">MTH</a>

    </div>
</div>
<!-- End Copyright Area -->
