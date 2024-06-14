@extends('manager.layouts.app')
@section('page_title')
    {{(!empty($page_title) && isset($page_title)) ? $page_title : ''}}
@endsection
@push('head-scripts')

@endpush
@section('content')
    <div class="card mt-3">
        <div class="card-body">
            {{-- Start: Page Content --}}
            <div class="d-flex justify-content-between">
                <div>
                    <h4 class="card-title mb-0">{{(!empty($p_title) && isset($p_title)) ? $p_title : ''}}</h4>
                    <div
                        class="small text-medium-emphasis">{{(!empty($p_summary) && isset($p_summary)) ? $p_summary : ''}}</div>
                </div>
                <div class="btn-toolbar d-none d-md-block" role="toolbar" aria-label="Toolbar with buttons">
                    @can('manager_department_department-list')
                        <a href="{{(!empty($url) && isset($url)) ? $url : ''}}"
                           class="btn btn-sm btn-primary">{{(!empty($url_text) && isset($url_text)) ? $url_text : ''}}</a>
                    @endcan
                </div>
            </div>
            <hr>
            {{-- Start: Form --}}
            <div>
                <form method="{{$method}}" action="{{$action}}" enctype="{{$enctype}}">
                    @csrf
                    <div class="mb-3">
                        <div class="row">
                            <div class="header"
                                 style="background-color:rgba(0, 0, 21, 0.03);padding:0.5rem;margin-bottom:0.5rem;font-weight:700">
                                Department Info
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="title">Title *</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                           name="title"
                                           id="title" placeholder="Title"
                                           value="{{(!empty($data->title) && isset($data->title)) ? $data->title : old('title')}}"
                                           disabled>
                                    @error('title')
                                    <strong class="text-danger">{{ $message }}</strong>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="image-edit mb-3">
                                    <label class="form-label" for="icon_url">Department Icon *</label>
                                    <div class="input-group mb-3">
                                        <a class="btn btn-dark" type="button" id="button-addon1"
                                           data-bs-toggle="tooltip" data-bs-placement="top" title="Download Icon"
                                           href="{{ route('manager.get.department-attachment',[$data->id,'icon_url']) }}">
                                            Download
                                        </a>
                                        <input disabled type="text" class="form-control" id="icon_url" name="icon_url"
                                               placeholder="{{(!empty($data->icon_name) && isset($data->icon_name)) ? $data->icon_name : old('icon_url_name')}}">
                                    </div>

                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="image-edit mb-3">
                                    <label class="form-label" for="thumbnail_url">Thumbnail*</label>
                                    <div class="input-group mb-3">
                                        <a class="btn btn-dark" type="button" id="button-addon1"
                                           data-bs-toggle="tooltip" data-bs-placement="top" title="Download Thumbnail"
                                           href="{{ route('manager.get.department-attachment',[$data->id,'thumbnail_url']) }}">
                                            Download
                                        </a>
                                        <input disabled type="text" class="form-control" id="thumbnail_url"
                                               name="thumbnail_url"
                                               placeholder="{{(!empty($data->thumbnail_name) && isset($data->thumbnail_name)) ? $data->thumbnail_name : old('thumbnail_name')}}">
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <div class="image-edit">
                                        <label class="form-label" for="department_banner">Department Banner*</label>
                                        <div class="input-group mb-3">
                                            <a class="btn btn-dark" type="button" id="button-addon1"
                                               data-bs-toggle="tooltip" data-bs-placement="top" title="Download Banner"
                                               href="{{ route('manager.get.department-attachment',[$data->id,'department_banner_url']) }}">
                                                Download
                                            </a>
                                            <input disabled type="text" class="form-control" id="department_banner"
                                                   name="department_banner_url"
                                                   placeholder="{{(!empty($data->department_banner_name) && isset($data->department_banner_name)) ? $data->department_banner_name : old('department_banner_name')}}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <div class="image-edit custom-file-button">
                                        <label class="form-label" for="department_cover">Group Photo</label>
                                        <div class="input-group mb-3">
                                            <a class="btn btn-dark" type="button" id="button-addon1"
                                               data-bs-toggle="tooltip" data-bs-placement="top"
                                               title="Download Cover Image"
                                               href="{{ route('manager.get.department-attachment',[$data->id,'cover_image_url']) }}">
                                                Download
                                            </a>
                                            <input disabled type="text" class="form-control" id="department_cover"
                                                   name="cover_image_url"
                                                   placeholder="{{(!empty($data->cover_image_name) && isset($data->cover_image_name)) ? $data->cover_image_name : old('cover_image_name')}}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="description">Description</label>
                                    <textarea type="text" id="department-description"
                                              name="description" class="form-control  rounded-0"
                                              disabled>{!! $data->description !!}</textarea>
                                </div>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- End: Form --}}

    {{-- End: Modal --}}
    {{-- Page Description : Start --}}
    @if(!empty($p_description) && isset($p_description))
        <div class="card-footer">
            <div class="row">
                <div class="col-12 mb-sm-2 mb-0">
                    <p>{{(!empty($p_description) && isset($p_description)) ? $p_description : ''}}</p>
                </div>
            </div>
        </div>
    @endif
    {{-- Page Description : End --}}
@endsection
@push('footer-scripts')
    <script>
        $(document).ready(function () {
            var editor = CKEDITOR.replace('department-description');
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
