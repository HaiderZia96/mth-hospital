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
                    @can('manager_achievement_award-list')
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
                                           id="name" placeholder="Name" value="{{old('name')}}">
                                    @error('name')
                                    <strong class="text-danger">{{ $message }}</strong>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="department_id">Department *</label>
                                    <select id="department_id"
                                            class="select2-options-department-id form-control @error('department_id') is-invalid @enderror"
                                            name="department_id"></select>
                                    @error('department_id')
                                    <strong class="text-danger">{{ $message }}</strong>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-md-6" id="image_input">
                                <div class="image-edit custom-file-button mb-3">
                                    <label class="form-label" for="image-cropper">Image *</label>
                                    <input type="file" id="image-cropper" accept=".png, .jpg, .jpeg" name="image_url"
                                           class="form-control wizard-required image-cropper @error('image_url') is-invalid @enderror">
                                    @error('image_url')
                                    <strong class="text-danger">{{ $message }}</strong>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12 col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="description">Description</label>
                                    <textarea name="description" id="description" cols="30" rows="3"
                                              class="form-control"></textarea>
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
            {{-- End: Form --}}
        </div>
    </div>
    {{--    Main card ends here--}}
    {{-- Modal --}}
    <div class="modal fade bd-example-modal-lg imageCrop" id="model" tabindex="-1" role="dialog"
         aria-labelledby="cropperModalLabel " aria-hidden="true" data-coreui-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header py-1 m-0 px-3" style="background-color: #a51313">
                    <h5 class="modal-title fw-bold " id="cropperModal" style="color: white">Image</h5>
                    <button type="button" class="close btn-close" id="reset-image" data-coreui-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body p-0 m-0">
                    <div class="img-container">
                        <div class="row pe-4">
                            <div class="col-md-12">
                                <img class="cropper-image" id="previewImage" src="" alt="">
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
            var editor = CKEDITOR.replace('description', {
                filebrowserUploadUrl: "{{route('manager.achievement-upload-ckeditor', ['_token' => csrf_token() ])}}",
                filebrowserUploadMethod: 'form'
            });
        });

    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            //Select Category
            $('.select2-options-department-id').select2({
                theme: "bootstrap5",
                placeholder: 'Select Department',
                allowClear: true,
                ajax: {
                    url: '{{route('manager.get.achievement-department-select')}}',
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
                                    text: item.title
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
            // Profile Image Cropper
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
                    aspectRatio: 806 / 570,
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
                    const file = new File([blob], 'profile.jpg', {type: 'image/jpeg'})
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
