@extends('front.layouts.app')
@section('content')
    <!-- Start Page Banner Area -->
    <div class="page-banner-area bg-23 pt-100">
        <div class="container">
            <div class="page-banner-content">
                <h2>Contact Us</h2>
                <ul>
                    <li>
                        <a href="{{ route('home') }}">
                            <i class="ri-home-8-line"></i>
                            Home
                        </a>
                    </li>
                    <li>
                        <span>Contact Us</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- End Page Banner Area -->

    <!-- Start Map Area -->
    <div class="map-area pt-100">
        <div class="container">
            <div class="map-content">
                <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d13607.773475364696!2d73.0768741!3d31.4982404!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x392241fab14bf1fd%3A0x497d9b02ce190384!2sMadinah%20Teaching%20Hospital!5e0!3m2!1sen!2s!4v1704522928360!5m2!1sen!2s" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </div>
    <!-- End Map Area -->

    <!-- Start Contact Informetion Area -->
    <div class="contact-informetion-area ptb-100">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <div class="left-informetion">
                        <h2>Contact Information</h2>
                        <ul>
                            <li>
                                <span>ADDRESS:</span>
                                Sargodha Rd, University Town, Faisalabad, Punjab
                            </li>
                            <li>
                                <span>EMAIL US:</span>
                                <a href="#">info@mth.org.pk</a>
                            </li>
                            <li>
                                <span>PHONE:</span>
                                <a href="tel: 041-8869861, 041-8869862">041-8869861 , 041-8869862</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6">
                    <div class="right-informetion">
                        <h2>Opening Hours</h2>

                        <ul>
                            <li>
                                Saturday– Thursday
                                <span>24 Hours</span>
                            </li>
                            <li>
                                Saturday– Thursday
                                <span>24 Hours</span>
                            </li>
                            <li>
                                Sunday
                                <span>24 Hours</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Contact Informetion Area -->

    <!-- Start Contact Area -->
    <div class="contact-area pb-100">
        <div class="container">
            <div class="contact-form">
                <h3>Send message</h3>

                <form method="{{$method}}" action="{{$action}}" enctype="{{$enctype}}">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <div class="form-group">
                                <label>NAME</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"    name="name"
                                       id="name" placeholder="Name" value="{{old('name')}}">
                                @error('name')
                                <strong class="text-danger">{{ $message }}</strong>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6">
                            <div class="form-group">
                                <label>EMAIL</label>
                                <input type="text" class="form-control @error('email') is-invalid @enderror"    name="email"
                                       id="email" placeholder="Email" value="{{old('email')}}">
                                @error('email')
                                <strong class="text-danger">{{ $message }}</strong>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6">
                            <div class="form-group">
                                <label>PHONE</label>
                                <input type="text" class="form-control @error('phone_no') is-invalid @enderror"    name="phone_no"
                                       id="phone_no" placeholder="Phone" value="{{old('phone_no')}}">
{{--                                @error('phone_no')--}}
{{--                                <strong class="text-danger">{{ $message }}</strong>--}}
{{--                                @enderror--}}
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6">
                            <div class="form-group">
                                <label>SUBJECT</label>
                                <input type="text" class="form-control @error('subject') is-invalid @enderror"    name="subject"
                                       id="subject" placeholder="Subject" value="{{old('subject')}}">
                                @error('subject')
                                <strong class="text-danger">{{ $message }}</strong>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-12">

                            <div class="form-group">
                                <label>YOUR MESSAGE</label>
                                <textarea name="message" class="form-control @error('message') is-invalid @enderror" id="message" cols="30" rows="6"  placeholder="write message here...."></textarea>
                                @error('message')
                                <strong class="text-danger">{{ $message }}</strong>
                                @enderror
                            </div>
                        </div>



                        <div class="col-lg-12 col-md-12 text-center">
{{--                            <button type="submit" class="default-btn active">--}}
{{--                                Send message--}}
{{--                            </button>--}}
                            <button type="submit" class="default-btn active">Send message</button>
{{--                            <div id="msgSubmit" class="h3 text-center hidden"></div>--}}
{{--                            <div class="clearfix"></div>--}}
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Contact Area -->

@endsection
@push('footer-scripts')
    <script>
        $(function() {
            $('.is_active_contact').addClass('active');
        });
    </script>
    {{-- Toastr : Script : Start --}}
    @if(Session::has('messages'))
        <script>
            noti({!! json_encode((Session::get('messages'))) !!});
        </script>
    @endif
    {{-- Toastr : Script : End --}}
@endpush
