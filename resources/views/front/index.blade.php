@extends('front.layouts.app')
@section('content')
    <!-- Start Hero Area -->
    <div class="hero-area">
        <div class="swiper hero-slide">
            <div class="swiper-wrapper">
                <div class="swiper-slide bg-1">
                    <div class="container">
                        <div class="hero-content">
                            <h1>Our strength is your well-being</h1>
                            <h3>Welcome to Medina Teaching Hospital: Your Trusted Partner in Healthcare Excellence!</h3>
                            <p>Discover compassionate care, cutting-edge technology, and a commitment to your well-being
                                at Medina Teaching Hospital. We're here to serve you with expertise and empathy.</p>
                            {{--                            <div class="hero-btn">--}}
                            {{--                                <a href="{{ route('home') }}" class="default-btn">Learn more</a>--}}
                            {{--                                <a href="{{ route('home') }}" class="default-btn active">Contact us</a>--}}
                            {{--                            </div>--}}
                        </div>
                    </div>
                </div>

                <div class="swiper-slide bg-2">
                    <div class="container">
                        <div class="hero-content">
                            <h1>We want to heal the patient with services</h1>
                            <h3>Your Journey to Wellness Starts Here: Welcome to Medina Teaching Hospital!</h3>
                            <p>Embark on a journey to better health with Medina Teaching Hospital. Our comprehensive
                                services, compassionate team, and patient-centric approach ensure you receive the care
                                you deserve. Trust us with your well-being.</p>
                            {{--                            <div class="hero-btn">--}}
                            {{--                                <a href="{{ route('home') }}" class="default-btn">Learn more</a>--}}
                            {{--                                <a href="{{ route('home') }}" class="default-btn active">Contact us</a>--}}
                            {{--                            </div>--}}
                        </div>
                    </div>
                </div>

                <div class="swiper-slide bg-3">
                    <div class="container">
                        <div class="hero-content">
                            <h1>Need your expertise we are ready</h1>
                            <h3>Medina Teaching Hospital: Advancing Healthcare, Enriching Lives!</h3>
                            <p>Choose Medina Teaching Hospital for a healthcare experience that goes beyond the
                                ordinary. With a focus on advancing medical practices and enriching lives, we're here to
                                support your health and wellness journey.</p>
                            {{--                            <div class="hero-btn">--}}
                            {{--                                <a href="{{ route('home') }}" class="default-btn">Learn more</a>--}}
                            {{--                                <a href="{{ route('home') }}" class="default-btn active">Contact us</a>--}}
                            {{--                            </div>--}}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="pagination-btn">
            <div class="swiper-pagination"></div>
        </div>

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
        </ul>
    </div>
    <!-- End Hero Area -->


    <!-- Start Who We Are Area -->
    <div class="who-we-are-area pt-100 pb-70">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="mr-44">
                        <div class="row align-items-end">
                            <div class="col-lg-7 col-md-6">
                                <div class="who-we-are-img img-1">
                                    <img src="{{asset('/front/coreui/assets/img/service-mth-01.jpg')}}" alt="image">
                                </div>
                            </div>
                            <div class="col-lg-5 col-md-6">
                                <div class="who-we-are-img-2">
                                    <h3>Connect With <span>MTH</span> Health Care</h3>
                                    <img src="{{asset('/front/coreui/assets/img/service-mth-02.jpg')}}" alt="image">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="who-we-are-img-3">
                                    <img src="{{asset('/front/coreui/assets/img/service-mth-03.jpg')}}" alt="image">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="ml-44">
                        <div class="who-we-are-content">
                            <span class="top-title">WHO WE ARE</span>
                            <h2>We have been providing services to patients for over 20 years</h2>
                            <p>Madinah Teaching Hospital(MTH) is to alleviate the sufferings of ailing humanity,
                                particularly patients from the under privileged cross section of the society, by
                                providing high quality specialist.</p>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-sm-6">
                                <div class="single-who-we-are">
                                    <i class="flaticon-hands"></i>
                                    <h3>1K+ Healing Hands</h3>
                                    <p>Welcome to Medina Teaching Hospital, a beacon of excellence in healthcare
                                        committed to transforming lives through innovation, compassion, and unwavering
                                        dedication to patient well-being.</p>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6">
                                <div class="single-who-we-are">
                                    <i class="flaticon-doctor"></i>
                                    <h3>Experience Doctors</h3>
                                    <p>We pride ourselves on being a leading healthcare institution, equipped with
                                        state-of-the-art facilities and a team of highly skilled and compassionate
                                        medical professionals.</p>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6">
                                <div class="single-who-we-are">
                                    <i class="flaticon-handshake"></i>
                                    <h3>Advanced Healthcare</h3>
                                    <p>Our commitment to innovation is reflected in the cutting-edge technologies and
                                        treatments we employ to address a wide range of health concerns. From preventive
                                        care to complex medical interventions, Medina Teaching Hospital is your trusted
                                        partner on the journey to optimal health.</p>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6">
                                <div class="single-who-we-are">
                                    <i class="flaticon-pharmacy"></i>
                                    <h3>50+ Pharmacies</h3>
                                    <p>Medina Teaching Hospital is more than a healthcare facility; we are a community
                                        dedicated to improving lives. Our comprehensive range of services, community
                                        outreach programs, and commitment to research and development reflect our
                                        dedication to serving as a catalyst for positive change in healthcare.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Who We Are Area -->

    <!-- Start Our Department Area -->

    @if(count($departments) >= 1)
        <div class="our-department-area bg-color-f8f9fa pt-100 pb-70">
            <div class="container">
                <div class="section-title">
                    <span class="top-title">OUR DEPARTMENT</span>
                    <h2>Our hospital has all kinds of departments, so you can get all kinds of treatment</h2>
                </div>

                <div class="row justify-content-center">
                    @foreach($departments as $department)
                        <div class="col-xl-3 col-md-6">
                            <div class="single-our-department">
                                <a href="{{route('departments-details',$department->slug)}}"
                                   style="display: block;width: 100%;height: 100%">
                                    <img src="{{ route('departments.show-images', [$department->id,'thumbnail_url']) }}"
                                         class="img-fluid w-100 h-100" alt="{{ $department->thumbnail_name }}">
                                </a>

                                <div class="department-content one">

                                    <h3>
                                        <a href="{{route('departments-details',$department->slug)}}">
                                            {{$department->title}}
                                        </a>
                                    </h3>

                                    {{--                                <p>{!! Str::words($department->description, 8, ' ...') !!}</p>--}}
                                    <p>&nbsp;</p>

                                </div>


                                <div class="department-content hover">
                                    <a href="{{ route('departments-details', $department->slug) }}">
                                        <div class="icon d-none d-lg-block">
                                            {{--                                        <i class="flaticon-fracture"></i>--}}
                                            <img
                                                src="{{route('departments.show-images',[$department->id,'icon_url']) }}"
                                                alt="{{ $department->slug }}"
                                                title="{{ $department->slug }}">

                                        </div>
                                    </a>

                                    <h3>
                                        <a href="{{route('departments-details',$department->slug)}}">
                                            {{$department->title}}
                                        </a>
                                    </h3>
                                    {{--                                    <p>{!! Str::words($department->description, 8, ' ...') !!}</p>--}}
                                    <a href="{{route('departments-details',$department->slug)}}" class="read-more">
                                        Read More
                                        <i class="ri-arrow-right-line"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- End Our Department Area -->

    <!-- Start Our Team Area -->
    @if(count($teams) >= 1)
        <div class="our-team-area bg-color-f1f5f8 pt-100 pb-70">
            <div class="container">

                <div class="section-title team-title">
                    <span class="top-title">OUR SPECIALISTS</span>
                    <h2>We have all the professional specialists in our hospital</h2>
                </div>

                <div class="row justify-content-center">
                    @foreach($teams as $team)
                        <div class="col-lg-3 col-sm-6">
                            <div class="single-team">
                                <img src="{{route('our-team.getImage',[$team->id,$team->image_url_name]) }}" alt=""
                                     title="">
                                <h3>
                                    <a href="{{ route('our-team-details',$team->slug) }}">{{$team->name}}</a>
                                </h3>
                                <span>{{$team->designation}}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
    <!-- End Our Team Area -->


    <!-- Start News Area -->
    @if(count($newsEvents) >= 1)
        <div class="blog-area pt-100 pb-70">
            <div class="container">

                <div class="section-title">
                    {{--                <span class="top-title">BLOG POST</span>--}}
                    <h2>News & Events</h2>
                </div>

                <div class="row text-center text-md-start">
                    @foreach($newsEvents AS $news)
                        <div class="col-lg-4 col-md-6">
                            <div class="single-blog">
                                <a href="{{route('news-and-events-details',$news->slug)}}">
                                    <img
                                        src="{{route('event-thumbnail.getImageThumbnail',[$news->id,$news->thumbnail_url_name]) }}"
                                        alt="" title="">
                                </a>

                                <div class="blog-content">
                                    <a href="{{route('news-and-events-details',$news->slug)}}"
                                       class="tag"> {{$news->event_name}}</a>

                                    <ul>
                                        <li>
                                            <a href="{{route('news-and-events-details',$news->slug)}}">
                                                <i class="ri-building-4-line"></i>
                                                {{$news->department_name}}
                                            </a>
                                        </li>
                                        <li>
                                            <i class="ri-calendar-line"></i>
                                            {{$news->e_date}}
                                        </li>
                                        {{--                                    <li>--}}
                                        {{--                                        <a href="#">--}}
                                        {{--                                            <i class="ri-chat-3-line"></i>--}}
                                        {{--                                            No comment--}}
                                        {{--                                        </a>--}}
                                        {{--                                    </li>--}}
                                    </ul>

                                    <h3>
                                        <a href="{{route('news-and-events-details',$news->slug)}}">{{$news->name}}</a>
                                    </h3>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    @endif
    <!-- End News Area -->

    <!-- Start Knock us Area -->
    <div class="knock-us-area bg-color-0057b8 ptb-100">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <div class="knock-us-content text-center">
                        <h3>Knock us out today to get medical services</h3>
                    </div>
                </div>

                {{--                <div class="col-lg-4">--}}
                {{--                    <div class="knock-us-btn">--}}
                {{--                        <a href="#" class="default-btn active">Book an appointment</a>--}}
                {{--                    </div>--}}
                {{--                </div>--}}
            </div>
        </div>
    </div>
    <!-- End Knock us Area -->


    <!-- Start Go Top Area -->
    <div class="go-top">
        <i class="ri-arrow-up-s-fill"></i>
        <i class="ri-arrow-up-s-fill"></i>
    </div>
    <!-- End Go Top Area -->
@endsection
@push('footer-scripts')
    <script>
        $(function () {
            $('.is_active_home').addClass('active');
        });
    </script>
@endpush
