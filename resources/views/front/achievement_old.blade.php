@extends('front.layouts.app')
@section('content')

    <!-- Start Page Banner Area -->
    <div class="page-banner-area bg-1 pt-100">
        <div class="container">
            <div class="page-banner-content">
                <h2>Achievements & Awards</h2>
                <ul>
                    <li>
                        <a href="{{ route('home') }}">
                            <i class="ri-home-8-line"></i>
                            Home
                        </a>
                    </li>
                    <li>
                        <span>Achievements & Awards</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- End Page Banner Area -->

    <!-- Start Blog Area -->
    <div class="achievement-area ptb-100">
        <div class="container">
            <div class="section-title">
                <span class="top-title">Achievements & Awards</span>
                <h2>See our latest achievements</h2>
            </div>
            <div class="achievement-gallery">
                <div class="row justify-content-center">
                    @foreach($achievements as $achievement)
                        <div class="col-lg-3 col-md-4 col-xs-6 thumb">
                            <a href="{{route('achievements.getImage',[$achievement->id,$achievement->image_url_name]) }}"
                               title="{{$achievement->name}}" class="fancybox" rel="ligthbox">
                                <img
                                    src="{{route('achievements.getImage',[$achievement->id,$achievement->image_url_name]) }}"
                                    alt="{{$achievement->name}}" title="{{$achievement->name}}" class="zoom img-fluid">
                            </a>
                        </div>
                    @endforeach
{{--                @foreach($record as $news)--}}
{{--                    <div class="col-lg-4 col-md-6">--}}
{{--                        <div class="single-blog">--}}
{{--                            <a href="{{route('news-and-events-details',$news->slug)}}">--}}
{{--                                --}}{{--                            <img src="{{asset('/front/coreui/assets/img/blog-1.jpg')}}" alt="Image">--}}
{{--                                <img src="{{route('event-thumbnail.getImageThumbnail',$news->id) }}" alt="" title="">--}}
{{--                            </a>--}}

{{--                            <div class="blog-content">--}}
{{--                                <a href="{{route('news-and-events-details',$news->slug)}}" class="tag"> {{$news->event_name}}</a>--}}

{{--                                <ul>--}}
{{--                                    <li>--}}
{{--                                        <a href="{{route('news-and-events-details',$news->slug)}}">--}}
{{--                                            <i class="ri-building-4-line"></i>--}}
{{--                                            {{$news->department_name}}--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
{{--                                    <li>--}}
{{--                                        <i class="ri-calendar-line"></i>--}}
{{--                                        {{$news->e_date}}--}}
{{--                                    </li>--}}
{{--                                    <li>--}}
{{--                                        <a href="{{route('news-and-events-details',$news->slug)}}">--}}
{{--                                            <i class="ri-chat-3-line"></i>--}}
{{--                                            No comment--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
{{--                                </ul>--}}

{{--                                <h3>--}}
{{--                                    <a href="{{route('news-and-events-details',$news->slug)}}">{{$news->name}}</a>--}}
{{--                                </h3>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                @endforeach--}}

                {{--                <div class="col-lg-4 col-md-6">--}}
                {{--                    <div class="single-blog">--}}
                {{--                        <a href="{{ route('news-and-events-detail') }}">--}}
                {{--                            <img src="{{asset('/front/coreui/assets/img/blog-2.jpg')}}" alt="Image">--}}
                {{--                        </a>--}}

                {{--                        <div class="blog-content">--}}
                {{--                            <a href="{{ route('news-and-events-detail') }}" class="tag">Health</a>--}}

                {{--                            <ul>--}}
                {{--                                <li>--}}
                {{--                                    <a href="{{ route('news-and-events-detail') }}">--}}
                {{--                                        <i class="ri-user-3-line"></i>--}}
                {{--                                        Stevens--}}
                {{--                                    </a>--}}
                {{--                                </li>--}}
                {{--                                <li>--}}
                {{--                                    <i class="ri-calendar-line"></i>--}}
                {{--                                    16 May, 2022--}}
                {{--                                </li>--}}
                {{--                                <li>--}}
                {{--                                    <a href="{{ route('news-and-events-detail') }}">--}}
                {{--                                        <i class="ri-chat-3-line"></i>--}}
                {{--                                        No comment--}}
                {{--                                    </a>--}}
                {{--                                </li>--}}
                {{--                            </ul>--}}

                {{--                            <h3>--}}
                {{--                                <a href="{{ route('news-and-events-detail') }}">Why would I give up all bad habits to stay good</a>--}}
                {{--                            </h3>--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
                {{--                </div>--}}

                {{--                <div class="col-lg-4 col-md-6">--}}
                {{--                    <div class="single-blog">--}}
                {{--                        <a href="{{ route('news-and-events-detail') }}">--}}
                {{--                            <img src="{{asset('/front/coreui/assets/img/blog-3.jpg')}}" alt="Image">--}}
                {{--                        </a>--}}

                {{--                        <div class="blog-content">--}}
                {{--                            <a href="{{ route('news-and-events-detail') }}" class="tag">First Aid</a>--}}

                {{--                            <ul>--}}
                {{--                                <li>--}}
                {{--                                    <a href="{{ route('news-and-events-detail') }}">--}}
                {{--                                        <i class="ri-user-3-line"></i>--}}
                {{--                                        Leonard--}}
                {{--                                    </a>--}}
                {{--                                </li>--}}
                {{--                                <li>--}}
                {{--                                    <i class="ri-calendar-line"></i>--}}
                {{--                                    17 May, 2022--}}
                {{--                                </li>--}}
                {{--                                <li>--}}
                {{--                                    <a href="{{ route('news-and-events-detail') }}">--}}
                {{--                                        <i class="ri-chat-3-line"></i>--}}
                {{--                                        No comment--}}
                {{--                                    </a>--}}
                {{--                                </li>--}}
                {{--                            </ul>--}}

                {{--                            <h3>--}}
                {{--                                <a href="{{ route('news-and-events-detail') }}">Everyone's home must have a first aid kit</a>--}}
                {{--                            </h3>--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
                {{--                </div>--}}

                {{--                <div class="col-lg-4 col-md-6">--}}
                {{--                    <div class="single-blog">--}}
                {{--                        <a href="{{ route('news-and-events-detail') }}">--}}
                {{--                            <img src="{{asset('/front/coreui/assets/img/blog-4.jpg')}}"  alt="Image">--}}
                {{--                        </a>--}}

                {{--                        <div class="blog-content">--}}
                {{--                            <a href="{{ route('news-and-events-detail') }}" class="tag">Treatment</a>--}}

                {{--                            <ul>--}}
                {{--                                <li>--}}
                {{--                                    <a href="{{ route('news-and-events-detail') }}">--}}
                {{--                                        <i class="ri-user-3-line"></i>--}}
                {{--                                        Kevin--}}
                {{--                                    </a>--}}
                {{--                                </li>--}}
                {{--                                <li>--}}
                {{--                                    <i class="ri-calendar-line"></i>--}}
                {{--                                    18 May, 2022--}}
                {{--                                </li>--}}
                {{--                                <li>--}}
                {{--                                    <a href="{{ route('news-and-events-detail') }}">--}}
                {{--                                        <i class="ri-chat-3-line"></i>--}}
                {{--                                        No comment--}}
                {{--                                    </a>--}}
                {{--                                </li>--}}
                {{--                            </ul>--}}

                {{--                            <h3>--}}
                {{--                                <a href="{{ route('news-and-events-detail') }}">The importance of personal treatment</a>--}}
                {{--                            </h3>--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
                {{--                </div>--}}

                {{--                <div class="col-lg-4 col-md-6">--}}
                {{--                    <div class="single-blog">--}}
                {{--                        <a href="{{ route('news-and-events-detail') }}">--}}
                {{--                            <img src="{{asset('/front/coreui/assets/img/blog-5.jpg')}}"  alt="Image">--}}
                {{--                        </a>--}}

                {{--                        <div class="blog-content">--}}
                {{--                            <a href="{{ route('news-and-events-detail') }}" class="tag">Health</a>--}}

                {{--                            <ul>--}}
                {{--                                <li>--}}
                {{--                                    <a href="{{ route('news-and-events-detail') }}">--}}
                {{--                                        <i class="ri-user-3-line"></i>--}}
                {{--                                        Katherine--}}
                {{--                                    </a>--}}
                {{--                                </li>--}}
                {{--                                <li>--}}
                {{--                                    <i class="ri-calendar-line"></i>--}}
                {{--                                    19 May, 2022--}}
                {{--                                </li>--}}
                {{--                                <li>--}}
                {{--                                    <a href="{{ route('news-and-events-detail') }}">--}}
                {{--                                        <i class="ri-chat-3-line"></i>--}}
                {{--                                        No comment--}}
                {{--                                    </a>--}}
                {{--                                </li>--}}
                {{--                            </ul>--}}

                {{--                            <h3>--}}
                {{--                                <a href="{{ route('news-and-events-detail') }}">The words of the doctor increase the morale of the patient</a>--}}
                {{--                            </h3>--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
                {{--                </div>--}}

                {{--                <div class="col-lg-4 col-md-6">--}}
                {{--                    <div class="single-blog">--}}
                {{--                        <a href="{{ route('news-and-events-detail') }}">--}}
                {{--                            <img src="{{asset('/front/coreui/assets/img/blog-6.jpg')}}"  alt="Image">--}}
                {{--                        </a>--}}

                {{--                        <div class="blog-content">--}}
                {{--                            <a href="{{ route('news-and-events-detail') }}" class="tag">First Aid</a>--}}

                {{--                            <ul>--}}
                {{--                                <li>--}}
                {{--                                    <a href="{{ route('news-and-events-detail') }}">--}}
                {{--                                        <i class="ri-user-3-line"></i>--}}
                {{--                                        Charles--}}
                {{--                                    </a>--}}
                {{--                                </li>--}}
                {{--                                <li>--}}
                {{--                                    <i class="ri-calendar-line"></i>--}}
                {{--                                    20 May, 2022--}}
                {{--                                </li>--}}
                {{--                                <li>--}}
                {{--                                    <a href="{{ route('news-and-events-detail') }}">--}}
                {{--                                        <i class="ri-chat-3-line"></i>--}}
                {{--                                        No comment--}}
                {{--                                    </a>--}}
                {{--                                </li>--}}
                {{--                            </ul>--}}

                {{--                            <h3>--}}
                {{--                                <a href="{{ route('news-and-events-detail') }}">The success of the treatment of stroke patients</a>--}}
                {{--                            </h3>--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
                {{--                </div>--}}

                {{--                <div class="col-lg-12">--}}
                {{--                    <div class="pagination-area">--}}
                {{--                        <a href="{{ route('news-and-events') }}" class="next page-numbers">--}}
                {{--                            <i class="ri-arrow-left-line"></i>--}}
                {{--                        </a>--}}
                {{--                        <span class="page-numbers current" aria-current="page">1</span>--}}
                {{--                        <a href="{{ route('news-and-events') }}" class="page-numbers">2</a>--}}
                {{--                        <a href="{{ route('news-and-events') }}" class="page-numbers">3</a>--}}

                {{--                        <a href="{{ route('news-and-events') }}" class="next page-numbers">--}}
                {{--                            <i class="ri-arrow-right-line"></i>--}}
                {{--                        </a>--}}
                {{--                    </div>--}}
                {{--                </div>--}}
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


