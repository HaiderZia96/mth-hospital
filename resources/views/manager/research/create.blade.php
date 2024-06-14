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

            <div class="row profile-menu">
                <div class="col-lg-3 col-md-12 col-12">
                    @include('manager/inc.sideHeader')
                </div>
                <div class="col-lg-9 col-md-12 col-12">
                    <form method="{{$method}}" action="{{$action}}" enctype="{{$enctype}}">
                        @csrf
                        <div class="mb-3">
                            <div class="row">
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
                                    <div class="mb-3">
                                        <label class="form-label" for="author">Author *</label>
                                        <input type="text" class="form-control @error('author') is-invalid @enderror"
                                               name="author"
                                               id="author" placeholder="Author." value="{{old('author')}}">
                                        @error('author')
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
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="journal">Journal</label>
                                        <input type="text" class="form-control @error('journal') is-invalid @enderror"
                                               name="journal"
                                               id="journal" placeholder="Journal" value="{{old('journal')}}">
                                        @error('journal')
                                        <strong class="text-danger">{{ $message }}</strong>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="publish">Publish</label>
                                        <input type="text" class="form-control @error('publish') is-invalid @enderror"
                                               name="publish"
                                               id="publish" placeholder="Publish" value="{{old('publish')}}">
                                        @error('publish')
                                        <strong class="text-danger">{{ $message }}</strong>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="impact_factor">Impact Factor</label>
                                        <input type="text"
                                               class="form-control @error('impact_factor') is-invalid @enderror"
                                               name="impact_factor"
                                               id="impact_factor" placeholder="Impact Factor"
                                               value="{{old('impact_factor')}}">
                                        @error('impact_factor')
                                        <strong class="text-danger">{{ $message }}</strong>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="year">Year *</label>
                                        <input type="date" class="form-control @error('year') is-invalid @enderror"
                                               name="year"
                                               id="year" placeholder="Year" value="{{old('year')}}">
                                        @error('year')
                                        <strong class="text-danger">{{ $message }}</strong>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12 col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label" for="detail">Detail</label>
                                        <textarea name="detail" id="detail" cols="30" rows="3"
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

            </div>
            {{-- End: Form --}}
        </div>
    </div>
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


    {{-- Modal Banner--}}
    <div class="modal fade bd-example-modal-lg bannerCrop" id="model" tabindex="-1" role="dialog"
         aria-labelledby="cropperModalLabel" aria-hidden="true" data-coreui-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header py-1 m-0 px-3 bg-dark-green" style="background-color: #a51313">
                    <h5 class="modal-title text-white fw-bold " id="cropperModal" style="color: white">Banner</h5>
                    <button type="button" class="close btn-close" id="reset-image-banner" data-coreui-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body p-0 m-0">
                    <div class="img-container">
                        <div class="row pe-4">
                            <div class="col-md-12">
                                <img class="cropper-image" id="previewBanner" src="" alt="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn  btn-sm bg-dark-green text-white crop" id="cropBanner"
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

            $(document).ready(function () {
                var editor = CKEDITOR.replace('detail', {
                    filebrowserUploadUrl: "{{route('manager.research-upload-ckeditor', ['_token' => csrf_token() ])}}",
                    filebrowserUploadMethod: 'form'
                });
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
                    url: '{{route('manager.get.research-department-select')}}',
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
    {{-- Toastr : Script : Start --}}
    @if(Session::has('messages'))
        <script>
            noti({!! json_encode((Session::get('messages'))) !!});
        </script>
    @endif
    {{-- Toastr : Script : End --}}
@endpush
