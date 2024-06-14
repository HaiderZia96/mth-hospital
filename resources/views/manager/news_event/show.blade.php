@extends('manager.layouts.app')
@section('page_title')
    {{(!empty($page_title) && isset($page_title)) ? $page_title : ''}}
@endsection
@push('head-scripts')
    <link href="{{ asset('manager/select2/dist/css/select2.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('manager/select2/dist/css/select2-bootstrap5.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('manager/cropper/cropper.min.css') }}" rel="stylesheet"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
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
                    @can('manager_event_news-list')
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

                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="name">Name *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           name="name"
                                           id="name" placeholder="Name"
                                           value="{{(!empty($data->name) && isset($data->name)) ? $data->name : old('name')}}"
                                           disabled>

                                    @error('name')
                                    <strong class="text-danger">{{ $message }}</strong>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="e_cate">Category *</label>
                                    <select
                                        class="select2-options-category-id form-control @error('e_cate') is-invalid @enderror"
                                        name="e_cate" disabled></select>
                                    @error('e_cate')
                                    <strong class="text-danger">{{ $message }}</strong>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="dpt_id">Department *</label>
                                    <select
                                        class="select2-options-department-id form-control @error('dpt_id') is-invalid @enderror"
                                        name="dpt_id" disabled></select>
                                    @error('dpt_id')
                                    <strong class="text-danger">{{ $message }}</strong>
                                    @enderror

                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Event Date *</label>
                                    <input type="date" class="form-control rounded-0" name="e_date"
                                           value="{{(!empty($data->e_date) && isset($data->e_date)) ? $data->e_date : old('e_date')}}"
                                           disabled>
                                    @error('e_date')
                                    <strong class="text-danger">{{ $message }}</strong>
                                    @enderror

                                </div>
                            </div>


                            <div class="col-12 col-md-6">
                                <div class="image-edit mb-3">
                                    <label class="form-label" for="thumbnail_url">Thumbnail *</label>
                                    <div class="input-group mb-3">
                                        <a class="btn btn-dark" type="button" id="button-addon1"
                                           data-bs-toggle="tooltip" data-bs-placement="top" title="Download Thumbnail"
                                           href="{{ route('manager.news-event.get-news-attachment',[$data->id,'thumbnail_url']) }}">
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
                                        <label class="form-label" for="banner_url">Banner *</label>
                                        <div class="input-group mb-3">
                                            <a class="btn btn-dark" type="button" id="button-addon1"
                                               data-bs-toggle="tooltip" data-bs-placement="top" title="Download Banner"
                                               href="{{ route('manager.news-event.get-news-attachment',[$data->id,'banner_url']) }}">
                                                Download
                                            </a>
                                            <input disabled type="text" class="form-control" id="banner_url"
                                                   name="banner_url"
                                                   placeholder="{{(!empty($data->banner_name) && isset($data->banner_name)) ? $data->banner_name : old('banner_name')}}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="short_description">Short Detail *</label>
                                    <textarea name="short_description" id="short_description" cols="30" rows="3"
                                              class="form-control" disabled>{!! $data->short_description !!}</textarea>
                                </div>
                            </div>

                            <div class="col-12 col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="long_description">Detail *</label>
                                    <textarea name="long_description" id="long_description" cols="30" rows="3"
                                              class="form-control" disabled>{!! $data->long_description !!}</textarea>
                                </div>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
        </div>
        {{--        Card Body ends--}}
    </div>
    {{--    Main Card ends here--}}
    {{-- End: Form --}}
    {{-- Modal --}}
    <div class="modal fade bd-example-modal-lg imageCrop" id="model" tabindex="-1" role="dialog"
         aria-labelledby="cropperModalLabel" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cropperModal">Crop Image</h5>
                    <button type="button" class="close btn-close" id="reset-image" data-coreui-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="img-container">
                        <div class="row">
                            <div class="col-md-11">
                                <img id="previewImage" src="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="reset-image-close"
                            data-coreui-dismiss="modal">Close
                    </button>
                    <button type="button" class="btn btn-primary crop" id="cropImage">Crop</button>
                </div>
            </div>
        </div>
    </div>
    {{-- End: Modal --}}

    {{-- Modal  Banner Image--}}
    <div class="modal fade bd-example-modal-lg bannerCrop" id="model" tabindex="-1" role="dialog"
         aria-labelledby="cropperModalLabel" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cropperModal">Crop Image</h5>
                    <button type="button" class="close btn-close" id="reset-image" data-coreui-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="img-container">
                        <div class="row">
                            <div class="col-md-11">
                                <img id="previewBanner" src="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="reset-image-close"
                            data-coreui-dismiss="modal">Close
                    </button>
                    <button type="button" class="btn btn-primary crop" id="cropBanner">Crop</button>
                </div>
            </div>
        </div>
    </div>

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

            var editor = CKEDITOR.replace('short_description');
            var editor_hod = CKEDITOR.replace('long_description');

        });
    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            let department = [{
                id: "{{ $data['DptID']['id'] }}",
                text: "{{ $data['DptID']['title'] }}",
            }];
            $(".select2-options-department-id").select2({
                data: department,
                theme: "bootstrap5",
                placeholder: 'Select Department',
            });
            //Select Category
            $('.select2-options-department-id').select2({
                theme: "bootstrap5",
                placeholder: 'Select Department',
                allowClear: true,
                ajax: {
                    url: '{{route('manager.get.event-department-select')}}',
                    dataType: 'json',
                    delay: 250,
                    type: 'GET',
                    data: function (params) {
                        var query = {
                            q: (params.term != undefined ? params.term : ' '),
                            type: 'public',
                            _token: '{{csrf_token()}}'
                        }
                        return query;
                    },
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    id: item.id,
                                    text: item.name
                                }
                            })
                        };
                    },
                    cache: true
                }
            }).trigger('change.select2')

            //Select Category
            let event_category = [{
                id: "{{ $data['eCateID']['id'] }}",
                text: "{{ $data['eCateID']['name'] }}",
            }];

            $(".select2-options-category-id").select2({
                data: event_category,
                theme: "bootstrap5",
                placeholder: 'Select Event Category',
            });

            $('.select2-options-category-id').select2({
                theme: "bootstrap5",
                placeholder: 'Select Event Category',
                allowClear: true,
                ajax: {
                    url: '{{route('manager.get.event-category-select')}}',
                    dataType: 'json',
                    delay: 250,
                    type: 'GET',
                    data: function (params) {
                        var query = {
                            q: (params.term != undefined ? params.term : ' '),
                            type: 'public',
                            _token: '{{csrf_token()}}'
                        }
                        return query;
                    },
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    id: item.id,
                                    text: item.name
                                }
                            })
                        };
                    },
                    cache: true
                }
            }).trigger('change.select2')
            $(document).on('select2:open', () => {
                document.querySelector('.select2-search__field').focus();
            });
        })
    </script>
    <script>
        //Image Cropper
        $(document).ready(function () {
            var $modal = $('.imageCrop');
            var image = document.getElementById('previewImage');
            var cropper;
            $("body").on("change", ".image-cropper", function (e) {
                e.preventDefault();
                var files = e.target.files;
                var done = function (url) {
                    image.src = url;
                    $modal.modal('show');
                };
                var reader;
                var file;
                var URL;
                if (files && files.length > 0) {
                    file = files[0];
                    if (URL) {
                        done(URL.createObjectURL(file));
                    } else if (FileReader) {
                        reader = new FileReader();
                        reader.onload = function (e) {
                            done(reader.result);
                        };
                        reader.readAsDataURL(file);
                    }
                }
            });
            $modal.on('shown.coreui.modal', function () {
                cropper = new Cropper(image, {
                    dragMode: 'move',
                    aspectRatio: 153 / 196,
                    autoCropArea: 1,
                    restore: false,
                    guides: false,
                    center: false,
                    highlight: false,
                    cropBoxMovable: false,
                    cropBoxResizable: false,
                    toggleDragModeOnDblclick: false,
                });
            }).on('hidden.coreui.modal', function () {
                cropper.destroy();
                cropper = null;
            });
            $("body").on("click", "#cropImage", function () {
                canvas = cropper.getCroppedCanvas({
                    width: 937,
                    height: 1201,
                });
                canvas.toBlob(function (blob) {
                    url = URL.createObjectURL(blob);
                    var reader = new FileReader();
                    reader.readAsDataURL(blob);
                    reader.onloadend = function () {
                        var base64data = reader.result;
                        $('#base64image').val(base64data);
                        document.getElementById('image-preview-background').style.backgroundImage = "url(" + base64data + ")";
                        $modal.modal('hide');
                    }
                });
            });
        });
    </script>

    {{--    Department Banner Script--}}
    <script>
        //Department Banner Cropper
        $(document).ready(function () {
            var $modal = $('.bannerCrop');
            var hod = document.getElementById('previewBanner');
            var cropper;
            $("body").on("change", ".banner-cropper", function (e) {
                e.preventDefault();
                var files = e.target.files;
                var done = function (url) {
                    hod.src = url;
                    $modal.modal('show');
                };
                var reader;
                var file;
                var URL;
                if (files && files.length > 0) {
                    file = files[0];
                    if (URL) {
                        done(URL.createObjectURL(file));
                    } else if (FileReader) {
                        reader = new FileReader();
                        reader.onload = function (e) {
                            done(reader.result);
                        };
                        reader.readAsDataURL(file);
                    }
                }
            });
            $modal.on('shown.coreui.modal', function () {
                cropper = new Cropper(hod, {
                    dragMode: 'move',
                    aspectRatio: 24 / 5,
                    autoCropArea: 1,
                    restore: false,
                    guides: false,
                    center: false,
                    highlight: false,
                    cropBoxMovable: false,
                    cropBoxResizable: false,
                    toggleDragModeOnDblclick: false,
                });
            }).on('hidden.coreui.modal', function () {
                cropper.destroy();
                cropper = null;
            });
            $("body").on("click", "#cropBanner", function () {
                canvas = cropper.getCroppedCanvas({
                    width: 1920,
                    height: 400,
                });
                canvas.toBlob(function (blob) {
                    url = URL.createObjectURL(blob);
                    var reader = new FileReader();
                    reader.readAsDataURL(blob);
                    reader.onloadend = function () {
                        var base64data = reader.result;
                        $('#base64banner').val(base64data);
                        document.getElementById('banner-preview-background').style.backgroundImage = "url(" + base64data + ")";
                        $modal.modal('hide');
                    }
                });
            });
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
