@extends('front.layouts.app')
@section('content')

    <!-- Start Page Banner Area -->
    <div class="page-banner-area bg-1 pt-100">
        <div class="container">
            <div class="page-banner-content">
                <h2>{{$data->title}}</h2>
                <ul>
                    <li>
                        <a href="{{ route('home') }}">
                            <i class="ri-home-8-line"></i>
                            Home
                        </a>
                    </li>
                    <li>
                        <span>Research Details</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- End Page Banner Area -->
    <div class="container my-5">
        <div class="row">
            @if($data->detail != '' || null)
                <h2 class="mt-4">{{$data->title}}</h2>
                <p>{!! $data->detail !!}</p>
            @endif
            @if(count($attachRecord) >= 1)
                <h2 class="mt-4">Attachment</h2>
                @foreach($attachRecord as $attach)
                    <ul style="list-style-type: none">
                        <li>
                            <span> <img src="{{asset('/front/coreui/assets/img/attach.png')}}" class="me-3" alt="Image"
                                        style="width: 20px; height: 20px"> </span>
                            <a href="{{route('research-attachment-url.attach',[$attach->research_id,$attach->attachment_url_name])}}"
                               target="_blank">
                                {{$attach->attachment_name}}
                            </a>
                        </li>
                    </ul>
                @endforeach
            @endif
        </div>
    </div>

@endsection
@push('footer-scripts')

    {{-- Delete Confirmation Model : Script : Start --}}
    <script>
        $("#del-model").on('show.coreui.modal', function (event) {
            var triggerLink = $(event.relatedTarget);
            var url = triggerLink.data("url");
            $("#del-form").attr('action', url);
        })
    </script>
    {{-- Delete Confirmation Model : Script : Start --}}
    {{-- Toastr : Script : Start --}}
    @if(Session::has('messages'))
        <script>
            noti({!! json_encode((Session::get('messages'))) !!});
        </script>
    @endif
    {{-- Toastr : Script : End --}}
@endpush
