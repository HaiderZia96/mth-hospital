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
            <div class="attachments">
                {{-- Start: Form --}}
                <div class="row profile-menu">
                    <div class="col-md-12 col-lg-3 col-12">
                        @include('manager.inc.sideHeaderActive')
                    </div>
                    <div class="col-md-12 col-lg-9 col-12">
                        <form method="{{$method}}" action="{{$action}}" enctype="{{$enctype}}">
                            @csrf
                            <div class="mb-3">

                                <fieldset class="reset-this redo-fieldset mb-3">
                                    <legend class="reset-this redo-legend">Attachment</legend>
                                    <div class="col-lg-12">
                                        <div class="attachment-file">

                                            <div class="row">
                                                {{-- <div class="image-previews"></div> --}}

                                                <div class="col-sm-12 mb-3 input-group" id="research-attachment">
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

                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table id="indextable"
                                           class="table table-bordered table-striped table-hover table-responsive w-100 pt-1">
                                        <thead class="table-dark">
                                        <th>#</th>
                                        <th>File Name</th>
                                        <th>Action</th>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Attachment Div Ends Here--}}
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
    <script type="text/javascript" src="{{ asset('manager/datatable/datatables.min.js')}}"></script>

    <script>
        $(document).ready(function () {
            let URL = "{{ route('manager.get.attachment', $rid) }}";

            //Datatable
            $('#indextable').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                order: [[0, "desc"]],
                // dom: 'lBfrtip',
                // buttons: [
                //     {
                //         extend: 'copyHtml5',
                //         exportOptions: {
                //             columns: [0, 1, 2, 3]
                //         }
                //     },
                //     {
                //         extend: 'csvHtml5',
                //         exportOptions: {
                //             columns: [0, 1, 2, 3]
                //         }
                //     },
                //     {
                //         extend: 'excelHtml5',
                //         exportOptions: {
                //             columns: [0, 1, 2, 3]
                //         }
                //     },
                //     {
                //         extend: 'pdfHtml5',
                //         exportOptions: {
                //             columns: [0, 1, 2, 3]
                //         }
                //     },
                //     {
                //         extend: 'print',
                //         exportOptions: {
                //             columns: [0, 1, 2, 3]
                //         }
                //     }
                // ],
                // lengthMenu: [[10, 20, 100, 500], [10, 20, 100, 500]],
                // order: [[0, "desc"]],
                ajax: {
                    "type": "GET",
                    "url": URL
                },
                columns: [
                    {data: 'id'},
                    {data: 'attachment_name'},
                    {data: null},
                ],
                columnDefs: [
                    {
                        targets: 0,
                        orderable: false,
                        searchable: false,
                        width: '20px',
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        targets: 1,
                        width: '15%'
                    },
                    {

                        targets: -1,
                        searchable: false,
                        orderable: false,
                        render: function (data, type, row, meta) {

                            let URL = "{{ route('manager.attachment.show',[':rid',':id'] ) }}";
                            URL = URL.replace(':rid', row.rid);
                            URL = URL.replace(':id', row.id);

                            return '<div class="d-flex">' +
                                @can('manager_research_attachment-show')
                                    '<a class="me-1" href="' + URL + '"><span class="badge bg-success text-dark">Show</span>' +
                                @endcan
                                    @can('manager_research_research-delete')
                                    '<a class="me-1" href="javascript:void(0)"><span type="button" class="badge bg-danger" data-url="' + URL + '" data-coreui-toggle="modal" data-coreui-target="#del-model">Delete</span></a>' +
                                @endcan
                                    '</div>';
                        }
                    },
                ]
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