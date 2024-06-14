@extends('front.layouts.app')
@section('content')

    <!-- Start Page Banner Area -->
    <div class="team-banner-area bg-3 pt-100">
        <div class="container">
            <div class="page-banner-content">
                <h2>Find a Doctor</h2>
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
                        <span>Find a Doctor</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- End Page Banner Area -->

    <!-- Start Find Doctor Area -->
    {{--    <div class="find-doctor-area pt-100">--}}
    {{--        <div class="container">--}}
    {{--            <div class="find-doctor-title">--}}
    {{--                <h3>Find a Doctor</h3>--}}
    {{--                <p>Find a healthcare provider at MTH hospital</p>--}}
    {{--            </div>--}}

    {{--            <form class="find-doctors">--}}
    {{--                <div class="row">--}}
    {{--                    <div class="col-lg-12">--}}
    {{--                        <label>SEARCH BY:</label>--}}
    {{--                        <div class="form-group">--}}
    {{--                            <input type="text" class="form-control src" placeholder="Search by Specialty, Condition or Doctor">--}}
    {{--                            <i class="ri-search-line"></i>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}

    {{--                    <div class="col-lg-6">--}}
    {{--                        <label>DEPARTMENT</label>--}}
    {{--                        <div class="form-group">--}}
    {{--                            <select class="form-select form-control" aria-label="Default select example">--}}
    {{--                                <option selected>All Departments</option>--}}
    {{--                                <option value="1">Department</option>--}}
    {{--                            </select>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}

    {{--                    <div class="col-lg-6">--}}
    {{--                        <label>SPECIALTY</label>--}}
    {{--                        <div class="form-group">--}}
    {{--                            <select class="form-select form-control" aria-label="Default select example">--}}
    {{--                                <option selected>Select Speciality</option>--}}
    {{--                                <option value="1">Speciality</option>--}}
    {{--                            </select>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}

    {{--                    <div class="col-lg-12">--}}
    {{--                        <button type="submit" class="default-btn active">--}}
    {{--                            Search--}}
    {{--                        </button>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--            </form>--}}
    {{--        </div>--}}
    {{--    </div>--}}
    <!-- End Find Doctor Area -->

    <!-- Start Our Team Area -->
    <div class="our-team-area ptb-100">
        <div class="container">
            <div class="section-title team-title">
                <span class="top-title">OUR SPECIALISTS</span>
                <h2>We have all the professional specialists in our hospital</h2>
            </div>

            <div class="row justify-content-center">
                @foreach($teams as $team)
                    <div class="col-lg-3 col-sm-6">
                        <a href="{{ route('our-team-details',$team->slug) }}">
                            <div class="single-team">
                                {{--                        <img src="{{asset('/front/coreui/assets/img/team-1.jpg')}}" alt="Image">--}}
                                <img src="{{route('our-team.getImage',[$team->id,$team->image_url_name]) }}" alt=""
                                     title="">
                                <h3>
                                    {{$team->name}}
                                </h3>
                                <span>{{$team->designation}}</span>
                            </div>
                        </a>
                    </div>
                @endforeach

                <div class="col-lg-12">
                    <div class="pagination-area">
                        {!! $teams->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Our Team Area -->

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
            $('.is_active_team').addClass('active');
        });
    </script>
@endpush
