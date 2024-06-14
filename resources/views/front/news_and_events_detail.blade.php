@extends('front.layouts.app')
@section('content')

    <!-- Start Page Banner Area -->
    @foreach($eventDetails as $event)
        <div class="page-banner-area bg-20 pt-100">
            <div class="container">
                <div class="page-banner-content">
                    <h2>Event Details</h2>
                    <ul>
                        <li>
                            <a href="{{ route('home') }}">
                                <i class="ri-home-8-line"></i>
                                Home
                            </a>
                        </li>
                        <li>
                            <span>News & Events</span>
                        </li>
                        <li>
                            <span>{{$event->name}}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- End Page Banner Area -->

        <!-- Stat Blog Details Area -->
        <div class="blog-details-area ptb-100">
            <div class="container">
                <div class="row">

                    <div class="col-lg-8">
                        <div class="blog-details-content">
                            {{--                        <img src="{{asset('/front/coreui/assets/img/blog-1.jpg')}}" class="blog-post-img" alt="Image">--}}
                            <div class="blog-post-img">
                                <img
                                    src="{{route('event-thumbnail.getImageThumbnail',[$event->id,$event->thumbnail_url_name]) }}"
                                    alt="" class="main">
                            </div>
                            <span class="tag">{{$event->event_name}}</span>
                            <ul>
                                <li>
                                    <i class="ri-building-4-line"></i>
                                    <a href="{{route('news-and-events-details',$event->slug)}}"> {{$event->department_name}}</a>
                                </li>
                                <li>
                                    <i class="ri-calendar-line"></i>
                                    {{$event->e_date}}
                                </li>

                            </ul>

                            <h2>{{$event->name}}</h2>
                            <p>{!! $event->long_description !!}</p>


                            {{--                        <div class="tag-social d-flex justify-content-between align-items-center">--}}


                            {{--                            <ul class="socila-link">--}}
                            {{--                                <li>--}}
                            {{--                                    <span>Share:</span>--}}
                            {{--                                </li>--}}
                            {{--                                <li>--}}
                            {{--                                    <a href="https://www.facebook.com/" target="_blank">--}}
                            {{--                                        <img src="{{asset('/front/coreui/assets/img/facebook.svg')}}" alt="Image">--}}
                            {{--                                    </a>--}}
                            {{--                                </li>--}}
                            {{--                                <li>--}}
                            {{--                                    <a href="https://www.twitter.com/" target="_blank">--}}
                            {{--                                        <img src="{{asset('/front/coreui/assets/img/twitter.svg')}}" alt="Image">--}}
                            {{--                                    </a>--}}
                            {{--                                </li>--}}
                            {{--                                <li>--}}
                            {{--                                    <a href="https://www.linkedin.com/" target="_blank">--}}
                            {{--                                        <img src="{{asset('/front/coreui/assets/img/linkedin.svg')}}" alt="Image">--}}
                            {{--                                    </a>--}}
                            {{--                                </li>--}}
                            {{--                                <li>--}}
                            {{--                                    <a href="https://www.instagram.com/" target="_blank">--}}
                            {{--                                        <img src="{{asset('/front/coreui/assets/img/instagram.svg')}}" alt="Image">--}}
                            {{--                                    </a>--}}
                            {{--                                </li>--}}
                            {{--                            </ul>--}}
                            {{--                        </div>--}}

                            {{--                        <div class="prev-next d-flex justify-content-between align-items-center">--}}
                            {{--                            @if ($previousEvent)--}}
                            {{--                                <a href="{{ route('news-and-events-details', $previousEvent->slug) }}" class="d-flex align-items-center">--}}
                            {{--                                    <i class="ri-arrow-left-s-line"></i>--}}
                            {{--                                    <span class="ms-3">Prev post</span>--}}
                            {{--                                </a>--}}
                            {{--                            @endif--}}

                            {{--                            @if ($nextEvent)--}}
                            {{--                                <a href="{{ route('news-and-events-details', $nextEvent->slug) }}" class="d-flex align-items-center">--}}
                            {{--                                    <span class="me-3">Next post</span>--}}
                            {{--                                    <i class="ri-arrow-right-s-line"></i>--}}
                            {{--                                </a>--}}
                            {{--                            @endif--}}
                            {{--                        </div>--}}


                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="sidebar-wrap ml-15">
                            {{--                        <div class="sidebar-widget">--}}
                            {{--                            <form class="src-form">--}}
                            {{--                                <input type="text" class="form-control" placeholder="Search...">--}}
                            {{--                                <button class="src-btn">--}}
                            {{--                                    <i class="ri-search-line"></i>--}}
                            {{--                                </button>--}}
                            {{--                            </form>--}}
                            {{--                        </div>--}}

                            <div class="sidebar-widget departments">
                                <h3>Categories</h3>

                                <ul>
                                    @foreach($events as $e)
                                        {{--                                    {{dd($e)}}--}}
                                        <li>
                                            <a href="{{route('news-and-events-category',$e->name)}}">
                                                {{$e->name}}
                                                <i class="ri-arrow-right-s-line"></i>
                                            </a>
                                        </li>

                                    @endforeach

                                </ul>
                            </div>

                            <div class="sidebar-widget recent-post">
                                <h3>Recent Posts</h3>

                                <div class="recent-post-wrap">
                                    @foreach($recentEvents as $recent)
                                        <article class="item">
                                            <a href="{{route('news-and-events-details',$recent->slug)}}" class="thumb">
                                                {{--                                        <span class="fullimage cover bg-1" role="img"></span>--}}
                                                <span class="fullimage cover bg-1" role="img"> <img
                                                        src="{{route('event-thumbnail.getImageThumbnail',[$recent->id,$recent->thumbnail_url_name]) }}"
                                                        alt="" title=""></span>
                                            </a>
                                            <div class="info">
                                                <h4 class="title usmall">
                                                    <a href="{{route('news-and-events-details',$recent->slug)}}">{{$recent->name}}</a>
                                                </h4>
                                                <span class="date">
												<i class="ri-calendar-2-fill"></i>
												{{$recent->e_date}}
											</span>
                                            </div>
                                        </article>
                                    @endforeach

                                </div>
                            </div>

                        </div>
                    </div>


                </div>
            </div>
        </div>
    @endforeach

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
