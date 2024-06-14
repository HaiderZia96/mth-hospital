<!DOCTYPE html>
<html lang="en">
<head>
    @include('front.layouts.head')
    @stack('head-scripts')
</head>
<body>
<div class="body-wrap">
{{--    @include('front.layouts.preloader')--}}
    <!-- End Preloader Area -->

    <div id="preloader" class="preloader">
        <div id="pre" class="preloader_container"><div class="preloader_disabler btn btn-default"></div></div>
    </div>

    @include('front.layouts.header')
    @yield('content')
    @include('front.layouts.footer')
    @include('front.layouts.scripts')

</div>
@stack('footer-scripts')
</body>
</html>
