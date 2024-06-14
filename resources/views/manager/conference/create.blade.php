@extends('manager.layouts.app')
@section('page_title')
    {{(!empty($page_title) && isset($page_title)) ? $page_title : ''}}
@endsection
@push('head-scripts')
    <link href="{{ asset('manager/select2/dist/css/select2.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('manager/select2/dist/css/select2-bootstrap5.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('manager/cropper/cropper.min.css') }}" rel="stylesheet"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <style>
        .custom-file-button input[type=file] {
            margin-left: -2px !important;
        }

        /*.custom-file-button input[type=file]::-webkit-file-upload-button {*/
        /*    display: none;*/
        /*}*/

        /*.custom-file-button input[type=file]::file-selector-button {*/
        /*    display: none;*/
        /*}*/

        /*.custom-file-button:hover label {*/
        /*    background-color: #dde0e3;*/
        /*    cursor: pointer;*/
        /*}*/

        img {
            max-width: 100%;
        }
        .cropper-point.point-se {
            height: 5px !important;
            width: 5px !important;
        }
    </style>
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
                    @can('manager_event_conference-list')
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
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label" for="name">Name *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           name="name"
                                           id="name" placeholder="Name" value="{{old('name')}}">
                                    @error('name')
                                    <strong class="text-danger">{{ $message }}</strong>
                                    @enderror
                                </div>
                            </div>


                            <div class="col-6" id="image_input">
                                <div class="image-edit custom-file-button mb-3">
                                    <label class="form-label" for="image_url">Image *</label>
                                    <input type="file" id="image-cropper" accept=".png, .jpg, .jpeg" name="image_url"
                                           class="form-control wizard-required image-cropper @error('image_url') is-invalid @enderror">
                                    @error('image_url')
                                    <strong class="text-danger">{{ $message }}</strong>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label" for="venue">Venue *</label>
                                    <input type="text" class="form-control @error('venue') is-invalid @enderror"
                                           name="venue"
                                           id="venue" placeholder="Venue." value="{{old('venue')}}">
                                    @error('venue')
                                    <strong class="text-danger">{{ $message }}</strong>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label" for="department_id">Department *</label>
                                    <select class="select2-options-department-id form-control @error('department_id') is-invalid @enderror" name="department_id"></select>
                                    @error('department_id')
                                    <strong class="text-danger">{{ $message }}</strong>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label" for="conference_date">Conference Date *</label>
                                    <input type="date" class="form-control @error('conference_date') is-invalid @enderror" name="conference_date"
                                           id="conference_date" placeholder="Year" value="{{old('conference_date')}}">
                                    @error('conference_date')
                                    <strong class="text-danger">{{ $message }}</strong>
                                    @enderror
                                </div></div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label" for="conference_workshop">Conference Workshop </label>
                                    <input type="text" class="form-control @error('conference_workshop') is-invalid @enderror"
                                           name="conference_workshop"
                                           id="conference_workshop" placeholder="Conference Workshop" value="{{old('conference_workshop')}}">
                                    @error('conference_workshop')
                                    <strong class="text-danger">{{ $message }}</strong>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label" for="description">Description</label>
                                    <textarea type="text" id="description" value="{{ old('description') }}" name="description" class="form-control  rounded-0"></textarea>
                                </div></div>



                        </div>
                    </div>
                    <button type="submit" class="btn btn-sm btn-success">Submit</button>
                </form>
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
                                        <img class="cropper-image" id="previewImage" src="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer p-2 m-0">
                            <button type="button" class="btn  btn-sm crop" id="cropImage" style="background-color: #a51313; color: white">Crop
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
                <script src="{{ asset('department/select2/dist/js/select2.js') }}"></script>
                <script src="{{ asset('department/cropper/cropper.js') }}"></script>
                <!-- ckeditor -->
                <script src="{{asset('department/ckeditor/ckeditor.js')}}"></script>
                <script src="{{asset('department/ckeditor/ckfinder/ckfinder.js')}}"></script>
                <script src="{{asset('department/ckeditor/samples/js/sample.js')}}"></script>
                <script>


                    $(document).ready(function() {
                        var editor = CKEDITOR.replace( 'description', {
                            filebrowserUploadUrl: "{{route('manager.conference-upload-ckeditor', ['_token' => csrf_token() ])}}",
                            filebrowserUploadMethod: 'form'
                        });
                    });

                </script>
                <script type="text/javascript">
                    $(document).ready(function() {
                        //Select Category
                        $('.select2-options-department-id').select2({
                            theme: "bootstrap5",
                            placeholder: 'Select Department',
                            allowClear: true,
                            ajax: {
                                url: '{{route('manager.get.conference-department-select')}}',
                                dataType: 'json',
                                delay: 250,
                                type: 'GET',
                                data: function (params){
                                    var query = {
                                        q: params.term,
                                        type: 'public',
                                        _token: '{{csrf_token()}}'
                                    }
                                    return query;
                                },
                                processResults: function (data) {
                                    return {
                                        results:  $.map(data, function (item) {
                                            return {
                                                id: item.department_id,
                                                text: item.department_name
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
                        // Thumbnail Cropper
                        $('#reset-image').on('click', function() {
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
                                aspectRatio: 806 / 570,
                                viewMode: 1,
                                autoCropArea: 0.75,
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
                                width: 806,
                                height: 570,
                            });
                            canvas.toBlob(function (blob) {
                                // Crop & Convert it to image
                                const imageInput = document.getElementById("image_input")
                                var x = document.createElement("INPUT");
                                x.setAttribute("type", "file");
                                x.setAttribute("name", "cropper_image");
                                x.classList.add("d-none");
                                imageInput.appendChild(x);
                                const file = new File([blob], 'image.jpg', {type: 'image/jpeg'})
                                const dataTransfer = new DataTransfer()
                                dataTransfer.items.add(file)
                                x.files = dataTransfer.files
                                $modal.modal('hide');
                            });
                        });
                    });
                </script>



    @endpush
