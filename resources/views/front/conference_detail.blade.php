@extends('front.layouts.app')
@section('content')

    <!-- Start Page Banner Area -->
    @foreach($conference_details as $conD)
        <div class="page-banner-area bg-20 pt-100">
            <div class="container">
                <div class="page-banner-content">
                    <h2>Conferences</h2>
                    <ul>
                        <li>
                            <a href="{{ route('home') }}">
                                <i class="ri-home-8-line"></i>
                                Home
                            </a>
                        </li>
                        <li>
                            <span>Conference Detail</span>
                        </li>
                        <li>
                            <span>{{$conD->name}}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- End Page Banner Area -->

        <!-- Stat Blog Details Area -->
        <div class="conference-details-area ptb-100">
            <div class="container">
                <div class="row justify-content-center">

                    <div class="col-lg-8">
                        <div class="conference-details-content mb-5">
                            {{--                        <img src="{{asset('/front/coreui/assets/img/blog-1.jpg')}}" class="blog-post-img" alt="Image">--}}
                            <div class="conference-post-img">
                                <img src="{{route('conference.getImage',$conD->image_url_name) }}"  alt=""class="main">
                            </div>
{{--                            <span class="tag">{{$conD->department_name}}</span>--}}
                            <ul>
                                <li>
                                    <i class="ri-building-4-line"></i>
                                    <a href="{{route('conference-detail',$conD->slug)}}"> {{$conD->department_name}}</a>
                                </li>
                                <li>
                                    <i class="ri-calendar-line"></i>
                                    {{$conD->conference_date}}
                                </li>

                            </ul>

                            <h2>{{$conD->name}}</h2>
                            <p>{!! $conD->description !!}</p>




{{--                            <div class="tag-social d-flex justify-content-between align-items-center">--}}


{{--                                <ul class="socila-link">--}}
{{--                                    <li>--}}
{{--                                        <span>Share:</span>--}}
{{--                                    </li>--}}
{{--                                    <li>--}}
{{--                                        <a href="https://www.facebook.com/" target="_blank">--}}
{{--                                            <img src="{{asset('/front/coreui/assets/img/facebook.svg')}}" alt="Image">--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
{{--                                    <li>--}}
{{--                                        <a href="https://www.twitter.com/" target="_blank">--}}
{{--                                            <img src="{{asset('/front/coreui/assets/img/twitter.svg')}}" alt="Image">--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
{{--                                    <li>--}}
{{--                                        <a href="https://www.linkedin.com/" target="_blank">--}}
{{--                                            <img src="{{asset('/front/coreui/assets/img/linkedin.svg')}}" alt="Image">--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
{{--                                    <li>--}}
{{--                                        <a href="https://www.instagram.com/" target="_blank">--}}
{{--                                            <img src="{{asset('/front/coreui/assets/img/instagram.svg')}}" alt="Image">--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
{{--                                </ul>--}}
{{--                            </div>--}}

{{--                            <div class="prev-next d-flex justify-content-between align-items-center mb-5">--}}
{{--                                @if ($previousCon)--}}
{{--                                    <a href="{{ route('conference-detail', $previousCon->slug) }}" class="d-flex align-items-center">--}}
{{--                                        <i class="ri-arrow-left-s-line"></i>--}}
{{--                                        <span class="ms-3">Prev post</span>--}}
{{--                                    </a>--}}
{{--                                @endif--}}

{{--                                @if ($nextCon)--}}
{{--                                    <a href="{{ route('conference-detail', $nextCon->slug) }}" class="d-flex align-items-center">--}}
{{--                                        <span class="me-3">Next post</span>--}}
{{--                                        <i class="ri-arrow-right-s-line"></i>--}}
{{--                                    </a>--}}
{{--                                @endif--}}
{{--                            </div>--}}


                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="sidebar-wrap ml-15 mb-4">
{{--                            <div class="sidebar-widget">--}}
{{--                                <form class="src-form">--}}
{{--                                    <input type="text" class="form-control" placeholder="Search...">--}}
{{--                                    <button class="src-btn">--}}
{{--                                        <i class="ri-search-line"></i>--}}
{{--                                    </button>--}}
{{--                                </form>--}}
{{--                            </div>--}}

                            <div class="sidebar-widget departments">
                                <h3>Conferences</h3>

                                <ul>
                                    @foreach($conferences as $c)
                                        {{--                                    {{dd($e)}}--}}
                                        <li>
                                            <a href="{{route('conference-category',$c->slug)}}">
                                                {{$c->name}}
                                                <i class="ri-arrow-right-s-line"></i>
                                            </a>
                                        </li>


                                    @endforeach

                                </ul>
                                @if(count($conferences) >= 8)
                                    <div class="text-center pb-4">
                                    <a  href="{{route('conference')}}">See More</a>

                                    </div>
                                @endif
                            </div>

{{--                            <div class="sidebar-widget recent-post">--}}
{{--                                <h3>Recent Posts</h3>--}}

{{--                                <div class="recent-post-wrap">--}}
{{--                                    @foreach($recentEvents as $recent)--}}
{{--                                        <article class="item">--}}
{{--                                            <a href="{{route('news-and-events-details',$recent->slug)}}" class="thumb">--}}
{{--                                                --}}{{--                                        <span class="fullimage cover bg-1" role="img"></span>--}}
{{--                                                <span class="fullimage cover bg-1" role="img"> <img src="{{route('event-thumbnail.getImageThumbnail',$recent->id) }}" alt="" title=""></span>--}}
{{--                                            </a>--}}
{{--                                            <div class="info">--}}
{{--                                                <h4 class="title usmall">--}}
{{--                                                    <a href="{{route('news-and-events-details',$recent->slug)}}">{{$recent->name}}</a>--}}
{{--                                                </h4>--}}
{{--                                                <span class="date">--}}
{{--												<i class="ri-calendar-2-fill"></i>--}}
{{--												{{$recent->e_date}}--}}
{{--											</span>--}}
{{--                                            </div>--}}
{{--                                        </article>--}}
{{--                                    @endforeach--}}

{{--                                </div>--}}
{{--                            </div>--}}

{{--                        </div>--}}
                    </div>


                </div>
            </div>
        </div>  @endforeach

    <!-- End Blog Details Area -->

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
                {{--                    <div class="knock-us-btn text-center">--}}
                {{--                        <a href="#" class="default-btn active">Book An Appointment</a>--}}
                {{--                    </div>--}}
                {{--                </div>--}}
            </div>
        </div>
    </div>
    <!-- End Knock us Area -->



@endsection
