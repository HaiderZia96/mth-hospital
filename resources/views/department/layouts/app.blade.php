<!DOCTYPE html>
<html lang="en">
<head>
    <title>@yield('page_title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <!-- Vendors styles-->
    <link rel="stylesheet" href="{{asset('department/coreui/vendors/simplebar/css/simplebar.css')}}">
    <link rel="stylesheet" href="{{asset('department/coreui/css/vendors/simplebar.css')}}">
    <!-- Main styles for this application-->
    <link href="{{asset('department/coreui/icons/css/all.css')}}" rel="stylesheet">
    <link href="{{asset('department/coreui/css/style.css')}}" rel="stylesheet">
    <!-- We use those styles to show code examples, you should remove them in your application.-->
    <link href="{{asset('department/coreui/css/examples.css')}}" rel="stylesheet">
    <link href="{{asset('department/toastr/toastr.min.css')}}" rel="stylesheet">
    @stack('head-scripts')
</head>
<body>
@include('department.layouts.leftSidebar')
<div class="wrapper d-flex flex-column min-vh-100 bg-light">
    @include('department.layouts.header')
    <div class="body flex-grow-1 px-3">
        <div class="container-lg">
            @yield('content')
        </div>
    </div>
    @include('department.layouts.footer')
</div>
<!-- CoreUI and necessary plugins-->
<script src="{{asset('department/js/jquery-3.7.0.min.js')}}"></script>
<script src="{{asset('department/coreui/vendors/@coreui/coreui/js/coreui.bundle.min.js')}}"></script>
<script src="{{asset('department/coreui/vendors/simplebar/js/simplebar.min.js')}}"></script>
<!-- Plugins and scripts required by this view-->
<script src="{{asset('department/coreui/vendors/@coreui/utils/js/coreui-utils.js')}}"></script>
<script src="{{asset('department/toastr/toastr.min.js')}}"></script>
<script src="{{asset('department/js/scripts.js')}}"></script>
<script src="{{asset('department/coreui/js/canvas-to-blob.min')}}"></script>
@stack('footer-scripts')
<script>
    $(document).ready(function (){
        $('#switch').click(function (){
            $.ajax({
                type:'GET',
                url:"{{route('get.switch-module')}}",
                dataType:'json',
                success:function(data){
                    if(data.length > 0){
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
                        $.each(data, function(index,item){
                            let URL = item.route;
                            table += "<tr>";
                            table += "<td>"+item.id+"</td>";
                            table += "<td>"+item.name+"</td>";
                            table += "<td>";
                            table += "<a href='"+URL+"' class='badge bg-primary text-white'>View</a>";
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
</body>
</html>
