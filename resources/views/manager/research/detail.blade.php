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
                    @can('manager_research_research-list')
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

                            <div class="col-6" id="attachment_input">
                                <div class="image-edit custom-file-button mb-3">
                                    <label class="form-label" for="attachment_url">Attachment </label>
                                    <input type="file" id="image" accept=".png, .jpg, .jpeg, .pdf"
                                           name="attachment_url[]"
                                           class="form-control wizard-required  @error('image_url') is-invalid @enderror"
                                           multiple="multiple">
                                    @error('attachment_url')
                                    <strong class="text-danger">{{ $message }}</strong>
                                    @enderror
                                </div>

                                <div id="list_file" style="background-color: #cdd0d5;color: black">
                                </div>
                                @foreach($attachs as $attach)
                                    <ul class="ps-0 my-2" style="list-style-type: none">

                                        <li style="background-color: #cdd0d5" class="p-1"><a class="me-2"
                                                                                             style="text-decoration: none; color: black"
                                                                                             href="javascript:void(0)">{{$attach->attachment_name}}
                                                <span type="button" class="badge bg-danger float-end"
                                                      data-url="{{ route('manager.attachment.destroy',  $attach->id) }}"
                                                      data-coreui-toggle="modal"
                                                      data-coreui-target="#del-model">Remove</span></a></li>
                                    </ul>


                                @endforeach
                            </div>

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
                            <button type="button" class="btn  btn-sm crop" id="cropImage"
                                    style="background-color: #a51313; color: white">Crop
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            {{-- End: Modal --}}
            {{-- Delete Confirmation Model : Start --}}
            <div class="del-model-wrapper">
                <div class="modal fade" id="del-model" tabindex="-1" aria-labelledby="del-model" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="btn-close shadow-none" data-coreui-dismiss="modal"
                                        aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p class="font-weight-bold mb-2"> Are you sure you wanna delete this ?</p>
                                <p class="text-muted "> This item will be deleted immediately. You can't undo this
                                    action.</p>
                            </div>
                            <div class="modal-footer">
                                <form method="POST" id="del-form">
                                    @csrf
                                    {{method_field('DELETE')}}
                                    <button type="button" class="btn btn-light" data-coreui-dismiss="modal">Cancel
                                    </button>
                                    <button type="submit" class="btn btn-danger">
                                        {{ __('Delete') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


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

            <script>
                var fileInput = document.getElementById('image');
                var listFile = document.getElementById('list_file');
                var fileAttach = document.getElementById('file_attach');
                fileInput.onchange = function () {
                    var files = Array.from(this.files);
                    files = files.map(file => file.name);
                    listFile.innerHTML = files.join('<br/>');
                }
                $(document).ready(function () {
                    $("#file_attach").click(function () {
                        $("list_file").remove();
                    });
                });
            </script>

            {{-- Delete Confirmation Model : Script : Start --}}
            <script>
                $("#del-model").on('show.coreui.modal', function (event) {
                    var triggerLink = $(event.relatedTarget);
                    var url = triggerLink.data("url");
                    $("#del-form").attr('action', url);
                })
            </script>
    @endpush
