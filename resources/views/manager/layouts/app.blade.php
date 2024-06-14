<!DOCTYPE html>
<html lang="en">
<head>
    <title>@yield('page_title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <!-- Vendors styles-->
    <link rel="stylesheet" href="{{asset('manager/coreui/vendors/simplebar/css/simplebar.css')}}">
    <link rel="stylesheet" href="{{asset('manager/coreui/css/vendors/simplebar.css')}}">

    <!-- Main styles for this application-->
    <link href="{{asset('manager/coreui/icons/css/all.css')}}" rel="stylesheet">
    <link href="{{asset('manager/coreui/css/style.css')}}" rel="stylesheet">

    {{--    Select 2--}}
    <link href="{{ asset('manager/select2/dist/css/select2.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('manager/select2/dist/css/select2-bootstrap5.min.css') }}" rel="stylesheet"/>
    {{--- Hold On ---}}
    <link rel="stylesheet" href="{{ asset('manager/holdOn/src/css/HoldOn.css') }}"/>

    {{--   Cropper--}}
    <link href="{{ asset('manager/cropper/cropper.min.css') }}" rel="stylesheet"/>
    <!-- We use those styles to show code examples, you should remove them in your application.-->
    <link href="{{asset('manager/coreui/css/examples.css')}}" rel="stylesheet">
    <link href="{{asset('manager/toastr/toastr.min.css')}}" rel="stylesheet">

    <link href="{{asset('manager/coreui/css/custom.css')}}" rel="stylesheet">
    @stack('head-scripts')
    <style>
        .disabledTab {
            pointer-events: none;
            opacity: 0.75;
        }
    </style>
</head>
<body>
@include('manager.layouts.leftSidebar')
<div class="wrapper d-flex flex-column min-vh-100 bg-light">
    @include('manager.layouts.header')
    <div class="body flex-grow-1 px-3">
        <div class="container-lg">
            @yield('content')
        </div>
    </div>
    @include('manager.layouts.footer')
</div>
<!-- CoreUI and necessary plugins-->
<script src="{{asset('manager/js/jquery-3.7.0.min.js')}}"></script>
<script src="{{asset('manager/coreui/vendors/@coreui/coreui/js/coreui.bundle.min.js')}}"></script>
<script src="{{asset('manager/coreui/vendors/simplebar/js/simplebar.min.js')}}"></script>

{{--  Select 2--}}
<script src="{{ asset('manager/select2/dist/js/select2.js') }}"></script>

{{--Cropper js--}}
<script src="{{ asset('manager/cropper/cropper.js') }}"></script>
<script src="{{ asset('manager/js/canvas-to-blob.min.js') }}"></script>

<!-- ckeditor -->
<script src="{{asset('manager/ckeditor/ckeditor.js')}}"></script>
<script src="{{asset('manager/ckeditor/ckfinder/ckfinder.js')}}"></script>
<script src="{{asset('manager/ckeditor/samples/js/sample.js')}}"></script>

<!-- Plugins and scripts required by this view-->
<script src="{{asset('manager/coreui/vendors/@coreui/utils/js/coreui-utils.js')}}"></script>
<script src="{{asset('manager/toastr/toastr.min.js')}}"></script>
<script type="text/javascript" src="{{asset('manager/holdOn/src/js/HoldOn.js') }}"></script>
<script src="{{asset('manager/js/scripts.js')}}"></script>
@stack('footer-scripts')
<script>
    $(document).ready(function () {
        $('#switch').click(function () {
            $.ajax({
                type: 'GET',
                url: "{{route('get.switch-module')}}",
                dataType: 'json',
                success: function (data) {
                    if (data.length > 0) {
                        $("#switch-table").empty();
                        var table = "<table class='table table-bordered'>";
                        table += "<thead>";
                        table += "<tr>";
                        table += "<td>#</td>";
                        table += "<td>Name</td>";
                        table += "<td>Action</td>";
                        table += "</tr>";
                        table += "</thead>";
                        table += "<tbody>";
                        $.each(data, function (index, item) {
                            let URL = item.route;
                            table += "<tr>";
                            table += "<td>" + item.id + "</td>";
                            table += "<td>" + item.name + "</td>";
                            table += "<td>";
                            table += "<a href='" + URL + "' class='badge bg-primary text-white'>View</a>";
                            table += "</td>";
                            table += "</tr>";
                        });
                        table += "</tbody>";
                        table += "</table>";
                        // then finally
                        $("#switch-table").append(table);
                    }
                }
            });
        })
    });
</script>
<script>
    //Prevent Click
    function holdOn(themeName) {
        HoldOn.open({
            theme: themeName,
            message: "<h4>Please wait...</h4>"
        });
    }
</script>
</body>
</html>
