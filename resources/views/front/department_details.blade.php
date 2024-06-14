@extends('front.layouts.app')
{{--<h3>Hey</h3>--}}
@section('content')

    <!-- Start Page Banner Area -->
    @foreach($departmentDetails as $dept)
        <div class="department-banner-area bg-5 pt-100">

            <img class="dept-img img-fluid"
                 src="{{ route('departments.show-images', [$dept->id,'department_banner_url']) }}"
                 alt="{{ $dept->department_banner_url_name  }}">

            <div class="container">
                <div class="department-banner-content">
                    <h2>Department Details</h2>
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
                        <li>
                            <span>{{$dept->title}}</span>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
        <!-- End Page Banner Area -->

        <!-- Start Department Area -->
        <div class="department-orthopedics-area pt-100">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-12">
                        <div class="department-content mr-15">
                            @if(!empty($dept->cover_image_url))
                                <div class="department-cover-images">
                                    <img class="dept-img img-fluid"
                                         src="{{ route('departments.show-images', [$dept->id,'cover_image_url']) }}"
                                         alt="{{ $dept->cover_image_name  }}">
                                </div>
                            @endif
                            <div class="gap-mb-30 my-2"></div>
                            @if($dept->description != '' || null)
                                <h2>Description</h2>
                                <p>{!! $dept->description !!}</p>
                            @endif
                            <!-- End Our Mison Area -->

                            <div class="gap-mb-30"></div>

                        </div>
                        {{--                        <h2>Our Locations</h2>--}}

                        {{--                        <iframe class="mb-5" src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d13607.773475364696!2d73.0768741!3d31.4982404!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x392241fab14bf1fd%3A0x497d9b02ce190384!2sMadinah%20Teaching%20Hospital!5e0!3m2!1sen!2s!4v1704696668529!5m2!1sen!2s" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" ></iframe>--}}

                    </div>

                    @endforeach
                    <div class="col-lg-4">
                        <div class="sidebar-wrap ml-15 mb-4">
                            <div class="sidebar-widget departments">
                                <h3>Select Departments</h3>

                                <ul>
                                    @foreach($departments as $d)
                                        <li>
                                            <a href="{{route('departments-details',$d->slug)}}">
                                                {{$d->title}}
                                                <i class="ri-arrow-right-s-line"></i>
                                            </a>
                                        </li>

                                    @endforeach
                                </ul>
                                @if(count($departments) >= 10)
                                    <div class="text-center pb-4">
                                        <a href="{{route('departments')}}">See More</a>

                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>

        <!-- End Department Orthopedics Area -->

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
