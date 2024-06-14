@extends('department.layouts.app')
@section('page_title')
    {{(!empty($page_title) && isset($page_title)) ? $page_title : ''}}
@endsection
@push('head-scripts')
    <link rel="stylesheet" href="{{ asset('department/select2/dist/css/select2.min.css') }}" rel="stylesheet"/>
    <link rel="stylesheet" href="{{ asset('department/select2/dist/css/select2-bootstrap5.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('department/cropper/cropper.min.css') }}" rel="stylesheet"/>
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
                    @can('department_frontend_hero-banner-list')
                        <a href="{{(!empty($url) && isset($url)) ? $url : ''}}"
                           class="btn btn-sm btn-primary">{{(!empty($url_text) && isset($url_text)) ? $url_text : ''}}</a>
                    @endcan
                </div>
            </div>
            <hr>
            {{-- Start: Form --}}
            <form method="{{$method}}" action="{{$action}}" enctype="{{$enctype}}">
                @csrf
                @method('PUT')


                <div class="row">
                    <div class="col-6">
                        <div class="mb-3">
                            <label class="form-label" for="title">Title</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror"
                                   name="title"
                                   id="title" placeholder="Title" value="{{(!empty($data->title) && isset($data->title)) ? $data->title : old('title')}}">
                            @error('title')
                            <strong class="text-danger">{{ $message }}</strong>
                            @enderror
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="image-edit mb-3">
                            <label class="form-label" for="image">Department Banner</label>
                            <input type="file" id="image-cropper" accept=".png, .jpg, .jpeg" name="image" class="form-control wizard-required image-cropper @error('password') is-invalid @enderror">
                            <input type="hidden" name="base64image" id="base64image">
                            <p ><small>The previous uploaded image is already selected in case of changing image click on Choose File.</small></p>
                            @error('image')
                            <strong class="text-danger">{{ $message }}</strong>
                            @enderror
                            <div class="image-preview container-image-preview">
                                <div id="image-preview-background"></div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="description">Description</label>
                        <div class="col-12">
                            <textarea id="banner-description" class="form-control mb-0 @error('description') is-invalid @enderror" rows="6" cols="60"  name="description">{!! (isset($data->description))?$data->description:old('description') !!}</textarea>
                            @if ($errors->has('description'))
                                <span class="text-danger">{{ $message }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label class="form-label" for="name">Status</label>
                            <select
                                class="form-control form-select @error('status.*') is-invalid @enderror"
                                name="status">
                                <option value="1" {{(isset($data->status)) && $data->status == '1'  ? 'selected' : ''}}>Active</option>
                                <option value="0" {{(isset($data->status)) && $data->status == '0'  ? 'selected' : ''}}>In Active</option>
                            </select>
                            @error('status')
                            <strong class="text-danger">{{ $message }}</strong>
                            @enderror
                        </div>
                    </div>



                </div>
                <button type="submit" class="btn btn-sm btn-success">Submit</button>
            </form>
            {{-- End: Form --}}
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
            {{-- Modal --}}
            <div class="modal fade bd-example-modal-lg imageCrop" id="model" tabindex="-1" role="dialog" aria-labelledby="cropperModalLabel" aria-hidden="true" data-bs-backdrop="static">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="cropperModal">Crop Image</h5>
                            <button type="button" class="close btn-close" id="reset-image" data-coreui-dismiss="modal" aria-label="Close"></button>
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
                            <button type="button" class="btn btn-secondary" id="reset-image-close" data-coreui-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary crop" id="cropImage">Crop</button>
                        </div>
                    </div>
                </div>
            </div>
            {{-- End: Modal --}}
            {{-- End: Page Content --}}
        </div>
    </div>
@endsection
@push('footer-scripts')
    <script src="{{ asset('department/select2/dist/js/select2.js') }}"></script>
    <script src="{{ asset('department/cropper/cropper.js') }}"></script>
    <!-- ckeditor -->
    <script src="{{asset('department/ckeditor/ckeditor.js')}}"></script>
    <script src="{{asset('department/ckeditor/ckfinder/ckfinder.js')}}"></script>
    <script src="{{asset('department/ckeditor/samples/js/sample.js')}}"></script>
    <script>
        $(document).ready(function() {

            var editor = CKEDITOR.replace( 'banner-description');
        });
    </script>
    <script>
        //Image Cropper
        $(document).ready(function() {
            var $modal = $('.imageCrop');
            var image = document.getElementById('previewImage');
            var cropper;
            $("body").on("change", ".image-cropper", function(e){
                e.preventDefault();
                var files = e.target.files;
                var done = function(url) {
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
                        reader.onload = function(e) {
                            done(reader.result);
                        };
                        reader.readAsDataURL(file);
                    }
                }
            });
            $modal.on('shown.coreui.modal', function() {
                cropper = new Cropper(image, {
                    dragMode: 'move',
                    aspectRatio: 1920 / 990,
                    autoCropArea: 1.04,
                    restore: false,
                    guides: false,
                    center: false,
                    highlight: false,
                    cropBoxMovable: false,
                    cropBoxResizable: false,
                    toggleDragModeOnDblclick: false,
                });
            }).on('hidden.coreui.modal', function() {
                cropper.destroy();
                cropper = null;
            });
            $("body").on("click", "#cropImage", function() {
                canvas = cropper.getCroppedCanvas({
                    width: 1920,
                    height: 990,
                });
                canvas.toBlob(function(blob) {
                    url = URL.createObjectURL(blob);
                    var reader = new FileReader();
                    reader.readAsDataURL(blob);
                    reader.onloadend = function() {
                        var base64data = reader.result;
                        $('#base64image').val(base64data);
                        document.getElementById('image-preview-background').style.backgroundImage = "url("+base64data+")";
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
