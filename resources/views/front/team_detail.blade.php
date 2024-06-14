@extends('front.layouts.app')
@section('content')
    <!-- Start Page Banner Area -->
    @foreach($memberDetails as $member)
        <div class="page-banner-area bg-6 pt-100">
            <div class="container">
                <div class="page-banner-content">
                    <h2>Doctor Details</h2>
                    <ul>
                        <li>
                            <a href="{{ route('home') }}">
                                <i class="ri-home-8-line"></i>
                                Home
                            </a>
                        </li>
                        <li>
                            <span>Patient Care</span>
                        </li>
                        <li>
                            <span>Doctor details</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- End Page Banner Area -->

        <!-- Start Doctor Details Area -->
        <div class="doctor-details-area ptb-100">
            <div class="container">
                <div class="doctor-details-content">
                    <div class="doctor-details-wrap">
                        <div class="row align-items-center">
                            <div class="col-lg-6">
                                <div class="doctor-img">
                                    <img src="{{route('our-team.getImage',[$member->id,$member->image_url_name]) }}"
                                         alt="" title="">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="doctor-content">
                                    <h3>{{$member->name}}</h3>
                                    <span class="family">{{$member->designation}}</span>
                                    <ul>
                                        @if($member->address != '' || null)
                                            <li>
                                                <span>Address:</span>
                                                {{$member->address}}
                                            </li>
                                        @endif
                                        @if($member->phone_no != '' || null)
                                            <li>
                                                <span>Phone:</span>
                                                <a href="tel:{{$member->phone_no}}">{{$member->phone_no}}</a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <ul class="socila-link">
                        <li>
                            <a href="https://www.facebook.com/" target="_blank">
                                <img src="{{asset('/front/coreui/assets/img/facebook.svg')}}" alt="Image">
                            </a>
                        </li>
                        <li>
                            <a href="https://www.twitter.com/" target="_blank">
                                <img src="{{asset('/front/coreui/assets/img/twitter.svg')}}" alt="Image">
                            </a>
                        </li>
                        <li>
                            <a href="https://www.linkedin.com/" target="_blank">
                                <img src="{{asset('/front/coreui/assets/img/linkedin.svg')}}" alt="Image">
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="pt-100">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="doctor-informetion mr-15">
                                @if($member->description != '' || null)
                                    <h2>Biography</h2>
                                    <p>{!! $member->description !!}
                                    </p>
                                @endif
                                <div class="gap-mb-50"></div>
                                @if($member->education != '' || null)
                                    <h2>Education</h2>
                                    <p>
                                        {!! $member->education !!}

                                    </p>
                                @endif

                                <div class="gap-mb-50"></div>
                                @if($member->employment != '' || null)
                                    <h2>Employment</h2>
                                    <p>
                                        {!! $member->employment !!}
                                    </p>
                                @endif


                                <div class="gap-mb-50"></div>
                                @if($member->employment != '' || null)
                                    <h2>Memberships</h2>
                                    <p> {!! $member->membership !!}</p>
                                @endif


                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="sidebar-wrap ml-15">
                                <div class="sidebar-widget appointment-time">
                                    <h3>Sitting Time</h3>
                                    <ul>
                                        <li>
                                            {!! $member->sitting_time !!}
                                        </li>
                                    </ul>
                                </div>

                                <div class="sidebar-widget review">
                                    <h3>Speciality</h3>
                                    <ul>
                                        <li>
                                            {!! $member->speciality !!}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    @endforeach
    <!-- End Doctor Details Area -->

    <!-- Start Knock us Area -->
    <div class="knock-us-area bg-color-0057b8 ptb-100">
        <div class="container">
            <div class="row text-center">
                <div class="col-lg-12">
                    <div class="knock-us-content">
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
