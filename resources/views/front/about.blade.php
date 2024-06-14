@extends('front.layouts.app')
@section('content')

    <!-- Start Page Banner Area -->
    <div class="page-banner-area bg-1 pt-100">
        <div class="container">
            <div class="page-banner-content">
                <h2>About Us</h2>
                <ul>
                    <li>
                        <a href="{{ route('home') }}">
                            <i class="ri-home-8-line"></i>
                            Home
                        </a>
                    </li>
                    <li>
                        <span>About Us</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Start Choose Us Area -->
    <div class="choose-us-area ptb-100">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="choose-us-content">
                        <span class="top-title">WHY CHOOSE US</span>
                        <h2>Our hospital has professional doctors who provide low cost 24 hour service</h2>
                        <p>Embark on a journey to better health with Medina Teaching Hospital. Our comprehensive services, compassionate team, and patient-centric approach ensure you receive the care you deserve. Trust us with your well-being.</p>

                        <ul>
                            <li>
                                <span>1</span>
                                <h3>Modern Technology</h3>
                                Discover compassionate care, cutting-edge technology, and a commitment to your well-being at Medina Teaching Hospital. We're here to serve you with expertise and empathy.
                            </li>
                            <li>
                                <span>2</span>
                                <h3>Professional Doctors</h3>
                                Our world-class medical professionals and advanced treatments are dedicated to your health and happiness. Join us on the path to wellness at Medina Teaching Hospital, where every patient is a priority.
                            </li>
                            <li>
                                <span>3</span>
                                <h3>Affordable Price</h3>
                                The hospital is committed to provide free of cost medical and health services to the under privileged cross section of the society including consultancy, investigations, diagnoses, treatment, medicines, surgery, (major and minor operations) and hospitalization.
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="choose-us-img ml-86">
                        <img src="{{asset('/front/coreui/assets/img/choose-us-img.jpg')}}"  alt="Image">

                        <div class="ambulance-services d-flex">
                            <img src="{{asset('/front/coreui/assets/img/icon-2.svg')}}" alt="Image">
                            <div class="ambulance-info">
                                <span>24/7 Hours Service</span>
                                <a href="tel:1-885-665-2022">1-885-665-2022</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Choose Us Area -->
    <!-- Start Our Mison Area -->
    <div class="our-mison-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 pe-0">
                    <div class="single-mison bg-color">
                        <h3>Our Vision</h3>
                        <p>Creating a healthier and happier community, we believe in the power of healing, education, and advanced medical practices. As a teaching hospital, we actively contribute to the training and development of the next generation of healthcare professionals, ensuring a legacy of excellence in medical care.</p>
                    </div>
                </div>
                <div class="col-lg-6 ps-0">
                    <div class="single-mison">
                        <h3>Our Mission</h3>
                        <p>Our mission is to provide comprehensive, patient-centered care that exceeds expectations and sets new standards in the healthcare industry.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Our Mison Area -->

    <!-- Start Client Area -->
    <div class="client-area bg-3 ptb-100">
        <div class="container">
            <div class="section-title">
                <span class="top-title">MESSAGES</span>
{{--                <h2>Our happy clients say about us</h2>--}}
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="single-client">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="client-info d-flex align-items-center">
                                <img src="{{asset('/front/coreui/assets/img/mian-hanif.png')}}" alt="Image">
                                <div class="ms-3">
                                    <h3>Mian Muhammad Hanif</h3>
                                    <span>Patron in Chief</span>
                                </div>
                            </div>
                            <img src="{{asset('/front/coreui/assets/img/quat.svg')}}" class="quat" alt="Image">
                        </div>

                        <p>Dear Patients and Community Members,</p>
                        <p>As the Patron in Chief of <strong>Madinah Teaching Hospital (MTH)</strong>, I extend a warm welcome. Our commitment to excellence is reflected in our cutting-edge facilities, skilled professionals, and patient-centered approach.</p>
                        <p>At <strong>Madinah Teaching Hospital (MTH)</strong>, we prioritize your well-being, combining compassion with innovative medical practices. Our focus on continuous learning ensures we remain at the forefront of healthcare standards.</p>
                        <p>I express gratitude to our dedicated staff and community for contributing to our success. Together, we are building a legacy of healthcare excellence. Thank you for choosing <strong>Madinah Teaching Hospital (MTH)</strong> your trusted healthcare partner.</p>
                        <br><br>
                        <p class="text-end mb-1">Best Regards,</p>
                        <p class="text-end mb-1"><strong>Mian Muhammad Hanif</strong></p>
                        <p class="text-end mb-1">Patron in Chief</p>
                        <p class="text-end mb-1"><strong>Madinah Teaching Hospital (MTH)</strong></p>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="single-client">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="client-info d-flex align-items-center">
                                <img src="{{asset('/front/coreui/assets/img/mian-haider.png')}}" alt="Image">
                                <div class="ms-3">
                                    <h3>Muhammad Haider Amin</h3>
                                    <span>Chairman</span>
                                </div>
                            </div>
                            <img src="{{asset('/front/coreui/assets/img/quat.svg')}}" class="quat" alt="Image">
                        </div>

                        <p>Dear Patients and Supporters,</p>
                        <p>As Chairman of <strong>Madinah Teaching Hospital (MTH)</strong>, I'm privileged to lead a team dedicated to your health and well-being. Our commitment to excellence, compassion, and innovation ensures you receive top-notch care. Thank you for choosing <strong>Madinah Teaching Hospital (MTH)</strong>, where your health is our priority.</p>
                        <br><br>
                        <p class="text-end mb-1">Best regards,</p>
                        <p class="text-end mb-1"><strong>Muhammad Haider Amin</strong></p>
                        <p class="text-end mb-1">Chairman</p>
                        <p class="text-end mb-1"><strong>Madinah Teaching Hospital (MTH)</strong></p>

                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- End Client Area -->

@endsection
@push('footer-scripts')
    <script>
        $(function() {
            $('.is_active_about').addClass('active');
        });
    </script>
@endpush
