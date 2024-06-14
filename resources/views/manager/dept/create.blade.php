@extends('manager.layouts.app')
@section('page_title')
    {{(!empty($page_title) && isset($page_title)) ? $page_title : ''}}
@endsection
@push('head-scripts')
    <link href="{{ asset('manager/select2/dist/css/select2.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('manager/select2/dist/css/select2-bootstrap5.min.css') }}" rel="stylesheet"/>
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
                            <div class="header py-3 mb-2 fw-bold">
                                Department Info
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="title">Title *</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                           name="title"
                                           id="title" placeholder="Title" value="{{old('title')}}">
                                    @error('title')
                                    <strong class="text-danger">{{ $message }}</strong>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="image-edit mb-3">
                                    <label class="form-label" for="icon_url">Department Icon *</label>
                                    <input type="file" accept=".png, .svg .jpg .jpeg .gif" name="icon_url"
                                           class="form-control wizard-required  @error('icon_url') is-invalid @enderror">
                                    @error('icon_url')
                                    <strong class="text-danger">{{ $message }}</strong>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12 col-md-6" id="thumbnail_input">
                                <div class="image-edit custom-file-button mb-3">
                                    <label class="form-label" for="thumbnail_url">Thumbnail *</label>
                                    <input type="file" id="image-cropper" accept=".png, .jpg, .jpeg"
                                           name="thumbnail_url"
                                           class="form-control wizard-required image-cropper @error('thumbnail_url') is-invalid @enderror">
                                    @error('thumbnail_url')
                                    <strong class="text-danger">{{ $message }}</strong>
                                    @enderror
                                </div>
                            </div>


                            <div class="col-12 col-md-6" id="banner_input">
                                <div class="mb-3">
                                    <div class="image-edit custom-file-button">
                                        <label class="form-label" for="department_banner_url">Department Banner
                                            *</label>
                                        <input type="file" id="department-banner-cropper" accept=".png, .jpg, .jpeg"
                                               name="department_banner_url"
                                               class="form-control wizard-required department-banner-cropper @error('department_banner_url') is-invalid @enderror">
                                        @error('department_banner_url')
                                        <strong class="text-danger">{{ $message }}</strong>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{--    Dept Cover Image--}}
                            <div class="col-12 col-md-6" id="dept-cover-image">
                                <div class="mb-3">
                                    <div class="image-edit custom-file-button">
                                        <label class="form-label" for="department_cover">Group Photo</label>
                                        <input type="file" id="department_cover" accept=".png, .jpg, .jpeg"
                                               name="cover_image_url"
                                               class="form-control wizard-required @error('cover_image_url') is-invalid @enderror">
                                        @error('cover_image_url')
                                        <strong class="text-danger">{{ $message }}</strong>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="department-description">Description</label>
                                    <textarea type="text" id="department-description"
                                              name="description"
                                              class="form-control  rounded-0">{{ old('description') }}</textarea>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-sm btn-success" onclick="holdOn('sk-rect');">Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
        {{--        Card Body ends here--}}
    </div>

    {{-- End: Form --}}
    {{-- Modal --}}
    <div class="modal fade bd-example-modal-lg imageCrop" id="model" tabindex="-1" role="dialog"
         aria-labelledby="cropperModalLabel " aria-hidden="true" data-coreui-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header py-1 m-0 px-3" style="background-color: #a51313">
                    <h5 class="modal-title fw-bold " id="cropperModal" style="color: white">Thumbnail</h5>
                    <button type="button" class="close btn-close" id="reset-image" data-coreui-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body p-0 m-0">
                    <div class="img-container">
                        <div class="row pe-4">
                            <div class="col-md-12">
                                <img class="cropper-image img-fluid" id="previewImage" src="" alt="Thumbnail">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer p-2 m-0">
                    <button type="button" class="btn  btn-sm crop" id="cropImage"
                            style="background-color: #a51313; color: white">Crop
                    </button>
                </div>
            </div>
        </div>
    </div>
    {{-- End: Modal --}}


    {{-- Modal Banner--}}
    <div class="modal fade bd-example-modal-lg departmentBannerCrop" id="model" tabindex="-1" role="dialog"
         aria-labelledby="cropperModalLabel" aria-hidden="true" data-coreui-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header py-1 m-0 px-3 bg-dark-green" style="background-color: #a51313">
                    <h5 class="modal-title text-white fw-bold " id="cropperModal" style="color: white">
                        Department Banner</h5>
                    <button type="button" class="close btn-close" id="reset-image-banner"
                            data-coreui-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body p-0 m-0">
                    <div class="img-container">
                        <div class="row pe-4">
                            <div class="col-md-12">
                                <img class="cropper-image img-fluid" id="previewDepartmentBanner" src=""
                                     alt="Department Banner">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn  btn-sm bg-dark-green text-white crop"
                            id="cropDepartmentBanner" style="background-color: #a51313; color: white">Crop
                    </button>
                </div>
            </div>
        </div>
    </div>


    {{-- End: Modal --}}

    {{-- Cover Image Modal --}}
    <div class="modal fade bd-example-modal-lg coverImageModal" id="model" tabindex="-1" role="dialog"
         aria-labelledby="cropperModalLabel " aria-hidden="true" data-coreui-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header py-1 m-0 px-3" style="background-color: #a51313">
                    <h5 class="modal-title fw-bold " id="cropperModal" style="color: white">Group Photo</h5>
                    <button type="button" class="close btn-close" id="reset-image-icon"
                            data-coreui-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body p-0 m-0">
                    <div class="img-container">
                        <div class="row pe-4">
                            <div class="col-md-12">
                                <img class="cropper-image img-fluid" id="previewImageCover" src="" alt="Icon">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer p-2 m-0">
                    <button type="button" class="btn  btn-sm crop" id="coverImageButton"
                            style="background-color: #a51313; color: white">Crop
                    </button>
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
            var editor = CKEDITOR.replace('department-description', {
                filebrowserUploadUrl: "{{route('manager.dept-upload-ckeditor', ['_token' => csrf_token() ])}}",
                filebrowserUploadMethod: 'form'
            });

        });
    </script>

    <script>
        //Image Cropper
        $(document).ready(function () {
            // Thumbnail Cropper
            $('#reset-image').on('click', function () {
                $('.image-cropper').val('');
            });
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
                    aspectRatio: 306 / 392,
                    viewMode: 1,
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
                    width: 306,
                    height: 392,
                });

                canvas.toBlob(function (blob) {
                    // Crop & Convert it to image
                    const thumbnailInput = document.getElementById("thumbnail_input")
                    var x = document.createElement("INPUT");
                    x.setAttribute("type", "file");
                    x.setAttribute("name", "cropper_thumbnail");
                    x.classList.add("d-none");
                    thumbnailInput.appendChild(x);
                    const file = new File([blob], 'thumbnail.jpg', {type: 'image/jpeg'})
                    const dataTransfer = new DataTransfer()
                    dataTransfer.items.add(file)
                    x.files = dataTransfer.files
                    $modal.modal('hide');
                });
            });
        });
    </script>

    {{--    Department Banner Script--}}
    <script>
        //Department Banner Cropper
        $(document).ready(function () {
            $('#reset-image-banner').on('click', function () {
                $('.department-banner-cropper').val('');
            });
            var $modal = $('.departmentBannerCrop');
            var banner = document.getElementById('previewDepartmentBanner');
            var cropper;
            $("body").on("change", ".department-banner-cropper", function (e) {
                e.preventDefault();
                var files = e.target.files;
                var done = function (url) {
                    banner.src = url;
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
                cropper = new Cropper(banner, {
                    dragMode: 'move',
                    aspectRatio: 1920 / 400,
                    viewMode: 1,
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
            $("body").on("click", "#cropDepartmentBanner", function () {
                canvas = cropper.getCroppedCanvas({
                    width: 1920,
                    height: 400,
                });

                canvas.toBlob(function (blob) {
                    // Crop & Convert it to image
                    const bannerInput = document.getElementById("banner_input")
                    var x = document.createElement("INPUT");
                    x.setAttribute("type", "file");
                    x.setAttribute("name", "cropper_banner");
                    x.classList.add("d-none");
                    bannerInput.appendChild(x);
                    const file = new File([blob], 'banner.jpg', {type: 'image/jpeg'})
                    const dataTransfer = new DataTransfer()
                    dataTransfer.items.add(file)
                    x.files = dataTransfer.files
                    $modal.modal('hide');
                });
            });
        });
    </script>

    {{--    Cover Image Script --}}
    <script>
        $(document).ready(function () {
            $('#reset-image-icon').on('click', function () {
                $('#department_cover').val('');
            });
            var $modal = $('.coverImageModal');
            var icon = document.getElementById('previewImageCover');
            var cropper;
            $("body").on("change", "#department_cover", function (e) {
                e.preventDefault();
                var files = e.target.files;
                var done = function (url) {
                    icon.src = url;
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
                cropper = new Cropper(icon, {
                    dragMode: 'move',
                    aspectRatio: 696 / 383,
                    viewMode: 1,
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
            $("body").on("click", "#coverImageButton", function () {
                canvas = cropper.getCroppedCanvas({
                    width: 696,
                    height: 383,
                });

                canvas.toBlob(function (blob) {
                    // Crop & Convert it to image
                    const hodInput = document.getElementById("dept-cover-image")
                    var x = document.createElement("INPUT");
                    x.setAttribute("type", "file");
                    x.setAttribute("name", "cover_image_url");
                    x.classList.add("d-none");
                    hodInput.appendChild(x);
                    const file = new File([blob], 'cover_image.jpg', {type: 'image/jpeg'})
                    const dataTransfer = new DataTransfer()
                    dataTransfer.items.add(file)
                    x.files = dataTransfer.files
                    $modal.modal('hide');
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
