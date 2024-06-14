@extends('front.layouts.app')
@section('content')

    <!-- Start Page Banner Area -->
    <div class="page-banner-area bg-2 pt-100">
        <div class="container">
            <div class="page-banner-content">
                <h2>Departments</h2>
                <ul>
                    <li>
                        <a href="{{ route('home') }}">
                            <i class="ri-home-8-line"></i>
                            Home
                        </a>
                    </li>

                    <li>
                        <span>Departments</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- End Page Banner Area -->

    <!-- Start Our Department Area -->
    <div class="our-department-area pt-100 pb-70">
        <div class="container">
            <div class="section-title">
                <span class="top-title">OUR DEPARTMENT</span>
                <h2>Our hospital has all kinds of departments, so you can get all kinds of treatment</h2>
            </div>

            <div class="row justify-content-center">
                @foreach($departments as $dept)

                    <div class="col-xl-3 col-md-6">


                        <div class="single-our-department">

                            <a href="{{route('departments-details',$dept->slug)}}"
                               style="display: block;width: 100%;height: 100%">
                                <img src="{{ route('departments.show-images', [$dept->id,'thumbnail_url']) }}"
                                     class="img-fluid w-100 h-100">
                            </a>

                            <div class="department-content one">

                                <h3>
                                    <a href="{{route('departments-details',$dept->slug)}}">
                                        {{$dept->title}}
                                    </a>
                                </h3>

                                {{--                                    <p>{!! Str::words($dept->description, 8, ' ...') !!}</p>--}}
                                <p>&nbsp;</p>
                            </div>
                            <div class="department-content hover">
                                <a href="{{ route('departments-details', $dept->slug) }}">
                                    <div class="icon d-none d-lg-block">
                                        <img src="{{route('departments.show-images',[$dept->id,'icon_url']) }}"
                                             alt="{{ $dept->slug }}" title="{{ $dept->slug }}">
                                    </div>
                                </a>

                                <h3>
                                    <a href="{{route('departments-details',$dept->slug)}}">
                                        {{$dept->title}}
                                    </a>
                                </h3>
                                <a href="{{route('departments-details',$dept->slug)}}" class="read-more">
                                    Read More
                                    <i class="ri-arrow-right-line"></i>
                                </a>
                            </div>

                        </div>


                    </div>
                @endforeach
                <div class="col-lg-12">
                    <div class="pagination-area d-flex justify-content-center">
                        {!! $departments->links() !!}
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- End Our Department Area -->

    <!-- Start Knock us Area -->
    <div class="knock-us-area bg-color-0057b8 ptb-100">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <div class="knock-us-content text-center">
                        <h3>Knock us out today to get medical services</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Knock us Area -->

@endsection
@push('footer-scripts')
    <script>
        $(function () {
            $('.is_active_department').addClass('active');
        });
    </script>
@endpush
