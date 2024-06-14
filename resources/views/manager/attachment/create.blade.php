@extends('manager.layouts.app')
@section('page_title')
    {{(!empty($page_title) && isset($page_title)) ? $page_title : ''}}
@endsection
@push('head-scripts')
    <link rel="stylesheet" href="{{ asset('manager/datatable/datatables.min.css') }}"/>
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
            <div class="attachments">
                {{-- Start: Form --}}
                <div class="row profile-menu">
                    <div class="col-md-3">
                        @include('manager.inc.sideHeaderActive')
                    </div>
                    <div class="col-md-9">
                        {{-- Delete Confirmation Model : Start --}}
                        <div class="del-model-wrapper">
                            <div class="modal fade" id="del-model" tabindex="-1" aria-labelledby="del-model"
                                 aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="btn-close shadow-none"
                                                    data-coreui-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p class="font-weight-bold mb-2"> Are you sure you wanna delete this
                                                ?</p>
                                            <p class="text-muted "> This item will be deleted immediately. You
                                                can't undo this action.</p>
                                        </div>
                                        <div class="modal-footer">
                                            <form method="POST" id="del-form">
                                                @csrf
                                                {{method_field('DELETE')}}
                                                <button type="button" class="btn btn-light"
                                                        data-coreui-dismiss="modal">Cancel
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
                        <form method="{{$method}}" action="{{$action}}" enctype="{{$enctype}}">
                            @csrf
                            <div class="mb-3">
                                <fieldset class="reset-this redo-fieldset mb-3">
                                    <legend class="reset-this redo-legend">Attachment*</legend>
                                    <div class="col-lg-12">
                                        <div class="attachment-file">
                                            <div class="row">
                                                <div class="col-sm-12 mb-3 input-group">
                                                    <input type="file"
                                                           class="form-control @error('attachment_url') is-invalid @enderror"
                                                           name="attachment_url" id="attachment_url">
                                                </div>
                                                <!-- Container for multiple image previews -->
                                                @error('attachment_url')
                                                <strong class="text-danger">{{ $message }}</strong>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-dark float-end">Upload</button>
                                </fieldset>
                            </div>
                        </form>


                    </div>
                </div>
            </div>


        </div>
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

@endpush
