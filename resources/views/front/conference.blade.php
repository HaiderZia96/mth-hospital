@extends('front.layouts.app')
@section('content')

    <!-- Start Page Banner Area -->
    <div class="page-banner-area bg-1 pt-100">
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
                        <span>Conferences</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- End Page Banner Area -->

    <!-- Start Conference Area -->
    <div class="conference-area ptb-100">
        <div class="container">
            <div class="section-title">
                <span class="top-title">Conferences</span>
                <h2>See our latest conferences</h2>
            </div>

            <div class="row text-center text-md-start">
                @foreach($conferences as $con)
                    <div class="col-lg-4 col-md-6">
                        <div class="single-blog">
                            <a href="{{route('conference-detail',$con->slug)}}">
                                {{--                            <img src="{{asset('/front/coreui/assets/img/blog-1.jpg')}}" alt="Image">--}}
                                <img src="{{route('conference.getImage',$con->image_url_name) }}" alt="" title="">
                            </a>

                            <div class="blog-content">

                                <ul>
{{--                                    <li>--}}
{{--                                        <a href="{{route('conference-detail',$con->slug)}}">--}}
{{--                                            <i class="ri-building-4-line"></i>--}}
{{--                                            {{$con->venue}}--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
                                    <li>
                                        <a href="{{route('conference-detail',$con->slug)}}" class="tag"> {{$con->department_name}}</a>
                                    </li>
                                    <li class="float-none float-md-end">
                                        <i class="ri-calendar-line"></i>
                                        {{$con->conference_date}}
                                    </li>
                                    {{--                                <li>--}}
                                    {{--                                    <a href="{{route('news-and-events-details',$news->slug)}}">--}}
                                    {{--                                        <i class="ri-chat-3-line"></i>--}}
                                    {{--                                        No comment--}}
                                    {{--                                    </a>--}}
                                    {{--                                </li>--}}
                                </ul>

                                <h3>
                                    <a href="{{route('conference-detail',$con->slug)}}">{{$con->name}}</a>
                                </h3>
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="col-lg-12">
                    <div class="col-lg-12">
                        <div class="pagination-area d-flex justify-content-center">
                            {!! $conferences->links() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Blog Area -->

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
                {{--                        <a href="#" class="default-btn active">Book An Appointment</a>--}}
                {{--                    </div>--}}
                {{--                </div>--}}
            </div>
        </div>
    </div>
    <!-- End Knock us Area -->

@endsection
