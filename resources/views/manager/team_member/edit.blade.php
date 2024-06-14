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
                    @can('manager_team_member-list')
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
                    @method('PUT')
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="name">Name *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           name="name"
                                           id="name" placeholder="Name"
                                           value="{{(!empty($data->name) && isset($data->name)) ? $data->name : old('name')}}">
                                    @error('name')
                                    <strong class="text-danger">{{ $message }}</strong>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="designation">Designation</label>
                                    <input type="text" class="form-control @error('designation') is-invalid @enderror"
                                           name="designation"
                                           id="designation" placeholder="Designation"
                                           value="{{(!empty($data->designation) && isset($data->designation)) ? $data->designation : old('designation')}}">
                                    @error('designation')
                                    <strong class="text-danger">{{ $message }}</strong>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="address">Address</label>
                                    <input type="text" class="form-control @error('address') is-invalid @enderror"
                                           name="address"
                                           id="address" placeholder="Address"
                                           value="{{(!empty($data->address) && isset($data->address)) ? $data->address : old('address')}}">
                                    @error('address')
                                    <strong class="text-danger">{{ $message }}</strong>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="phone_no">Phone No</label>
                                    <input type="number" class="form-control @error('phone_no') is-invalid @enderror"
                                           name="phone_no"
                                           id="phone_no" placeholder="Phone No."
                                           value="{{(!empty($data->phone_no) && isset($data->phone_no)) ? $data->phone_no : old('phone_no')}}">
                                    @error('phone_no')
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
                                <div class="image-edit custom-file-button  mb-3">
                                    <label class="form-label" for="image_url">Profile Image *</label>
                                    <input type="file" id="image-cropper" accept=".png, .jpg, .jpeg" name="image_url"
                                           class="form-control wizard-required image-cropper @error('image_url') is-invalid @enderror">
                                    {{--                                    <input type="hidden" name="base64image" id="base64image">--}}
                                    <p><small>The previous uploaded image is already selected in case of changing image
                                            click on Choose File.</small></p>
                                    @error('image_url')
                                    <strong class="text-danger">{{ $message }}</strong>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12 col-md-12 col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label" for="description">Description</label>
                                    <textarea name="description" id="description" cols="30" rows="1"
                                              class="form-control">{!! $data->description !!}</textarea>
                                </div>
                            </div>

                            <div class="col-12 col-md-12 col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label" for="education">Education</label>
                                    <textarea name="education" id="education" cols="30" rows="2"
                                              class="form-control">{!! $data->education !!}</textarea>
                                </div>
                            </div>

                            <div class="col-12 col-md-12 col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label" for="employment">Employment</label>
                                    <textarea name="employment" id="employment" cols="30" rows="2"
                                              class="form-control">{!! $data->employment !!}</textarea>
                                </div>
                            </div>

                            <div class="col-12 col-md-12 col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label" for="membership">Memberships</label>
                                    <textarea name="membership" id="membership" cols="30" rows="2"
                                              class="form-control">{!! $data->membership !!}</textarea>
                                </div>
                            </div>

                            <div class="col-12 col-md-12 col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label" for="sitting_time">Sitting Time</label>
                                    <textarea name="sitting_time" id="sitting_time" cols="30" rows="2"
                                              class="form-control">{!! $data->sitting_time !!}</textarea>
                                </div>
                            </div>

                            <div class="col-12 col-md-12 col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label" for="speciality">Speciality</label>
                                    <textarea name="speciality" id="speciality" cols="30" rows="3"
                                              class="form-control">{!! $data->speciality !!}</textarea>
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
    </div>
    {{-- End: Form --}}
    {{-- Modal --}}
    <div class="modal fade bd-example-modal-lg imageCrop" id="model" tabindex="-1" role="dialog"
         aria-labelledby="cropperModalLabel " aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header py-1 m-0 px-3" style="background-color: #a51313">
                    <h5 class="modal-title fw-bold " id="cropperModal" style="color: white">Profile Image</h5>
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
                filebrowserUploadUrl: "{{route('manager.team-member-upload-ckeditor', ['_token' => csrf_token() ])}}",
                filebrowserUploadMethod: 'form'
            });
        });
        $(document).ready(function () {
            var editor_education = CKEDITOR.replace('education', {
                filebrowserUploadUrl: "{{route('manager.team-member-upload-ckeditor', ['_token' => csrf_token() ])}}",
                filebrowserUploadMethod: 'form'
            });
        });
        $(document).ready(function () {
            var editor_employment = CKEDITOR.replace('employment', {
                filebrowserUploadUrl: "{{route('manager.team-member-upload-ckeditor', ['_token' => csrf_token() ])}}",
                filebrowserUploadMethod: 'form'
            });
        });
        $(document).ready(function () {
            var editor_membership = CKEDITOR.replace('membership', {
                filebrowserUploadUrl: "{{route('manager.team-member-upload-ckeditor', ['_token' => csrf_token() ])}}",
                filebrowserUploadMethod: 'form'
            });
        });
        $(document).ready(function () {
            var editor_membership = CKEDITOR.replace('sitting_time', {
                filebrowserUploadUrl: "{{route('manager.team-member-upload-ckeditor', ['_token' => csrf_token() ])}}",
                filebrowserUploadMethod: 'form'
            });
        });
        $(document).ready(function () {
            var editor_membership = CKEDITOR.replace('speciality', {
                filebrowserUploadUrl: "{{route('manager.team-member-upload-ckeditor', ['_token' => csrf_token() ])}}",
                filebrowserUploadMethod: 'form'
            });
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
            //Select Department
            $('.select2-options-department-id').select2({
                theme: "bootstrap5",
                placeholder: 'Select Department',
                allowClear: true,
                ajax: {
                    url: '{{route('manager.get.member-department-select')}}',
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
                    aspectRatio: 306 / 388,
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
                    height: 388,
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
