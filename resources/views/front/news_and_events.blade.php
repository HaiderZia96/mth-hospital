@extends('front.layouts.app')
@section('content')
    <!-- Start Page Banner Area -->
    <div class="page-banner-area bg-5 pt-100">
        <div class="container">
            <div class="page-banner-content">
                <h2>Events</h2>
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
                </ul>
            </div>
        </div>
    </div>
    <!-- End Page Banner Area -->

    <!-- Start Blog Area -->
    <div class="blog-area ptb-100">
        <div class="container">
            <div class="section-title">
                <span class="top-title">News & Events POST</span>
                <h2>See our latest news post</h2>
            </div>

            <div class="row text-center text-md-start">
                @foreach($record as $news)
                    <div class="col-lg-4 col-md-6">
                        <div class="single-blog">
                            <a href="{{route('news-and-events-details',$news->slug)}}">
                                {{--                            <img src="{{asset('/front/coreui/assets/img/blog-1.jpg')}}" alt="Image">--}}
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
                                    {{--                                <li>--}}
                                    {{--                                    <a href="{{route('news-and-events-details',$news->slug)}}">--}}
                                    {{--                                        <i class="ri-chat-3-line"></i>--}}
                                    {{--                                        No comment--}}
                                    {{--                                    </a>--}}
                                    {{--                                </li>--}}
                                </ul>

                                <h3>
                                    <a href="{{route('news-and-events-details',$news->slug)}}">{{$news->name}}</a>
                                </h3>
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="col-lg-12">
                    <div class="col-lg-12">
                        <div class="pagination-area d-flex justify-content-center">
                            {!! $record->links() !!}
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
@push('footer-scripts')
    <script>
        $(function() {
            $('.is_active_event').addClass('active');
        });
    </script>
@endpush
