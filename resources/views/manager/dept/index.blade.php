@extends('manager.layouts.app')
@section('page_title')
    {{(!empty($page_title) && isset($page_title)) ? $page_title : ''}}
@endsection
@push('head-scripts')
    <link rel="stylesheet" href="{{ asset('manager/datatable/datatables.min.css') }}" />
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
                    @can('manager_department_department-create')
                        <a href="{{(!empty($url) && isset($url)) ? $url : ''}}"
                           class="btn btn-sm btn-primary">{{(!empty($url_text) && isset($url_text)) ? $url_text : ''}}</a>
                    @endcan
                    @can('manager_department_department-activity-log-trash')
                        <a href="{{(!empty($trash) && isset($trash)) ? $trash : ''}}"
                           class="btn btn-sm btn-danger">{{(!empty($trash_text) && isset($trash_text)) ? $trash_text : ''}}</a>
                    @endcan
                </div>
            </div>
            <hr>
            {{-- Datatatble : Start --}}
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table id="indextable"
                               class="table table-bordered table-striped table-hover table-responsive w-100 pt-1">
                            <thead class="table-dark">
                            <th>#</th>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Actions</th>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            {{-- Datatatble : End --}}
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
            {{-- Delete Confirmation Model : End --}}
            {{-- End: Page Content --}}
        </div>
    </div>
@endsection
@push('footer-scripts')
    <script type="text/javascript" src="{{ asset('manager/datatable/datatables.min.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            //Datatable
            $('#indextable').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                order: [[0, "desc"]],
                ajax: {
                    "type": "GET",
                    "url": "{{route('manager.get.dept')}}",
                },
                columns: [
                    {data: 'id', orderable: false},
                    {data: 'title', orderable: false},
                    {data: null},
                    {data: null},
                ],
                columnDefs: [
                    {
                        targets: 0,
                        orderable: false,
                        searchable: false,
                        width: '100px',
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    }, {
                        targets: 2,
                        orderable: false,
                        className: 'perm_col',
                        // defaultContent: '<span class="badge bg-info text-dark">group</span>'
                        render: function (data, type, row, meta) {
                            var output = "";
                            if (data.status === 1) {
                                output += '<span>Active</span>';
                            } else {
                                output += '<span>In-Active</span>';
                            }
                            return output;
                        }
                    }, {
                        targets: -1,
                        searchable: false,
                        orderable: false,
                        render: function (data, type, row, meta) {

                            let URL = "{{ route('manager.dept.show', ':id') }}";
                            URL = URL.replace(':id', row.id);

                            let ACTIVITY = "{{ route('manager.get.dept-activity', ':id') }}";
                            ACTIVITY = ACTIVITY.replace(':id', row.id);

                            let update_status = "{{ route('manager.dept.update-department-status', ':id') }}";
                            update_status = update_status.replace(':id', row.id);

                            var up = '{{ route("manager.swap-up.dept", [":id"]) }}';
                            up = up.replace(':id', data.id);

                            var down = '{{ route("manager.swap-down.dept", [":id"]) }}';
                            down = down.replace(':id', data.id);

                            function updateStatus() {
                                let output = '';
                                if (data.status === 0) {
                                    output += '<a class="me-1 ms-2" href="' + update_status + '"><span class="badge bg-secondary text-dark">Activate</span></a>';
                                } else {
                                    output += '<a class="me-1 ms-2" href="' + update_status + '"><span class="badge bg-primary text-light">De-Activate</span></a>';
                                }

                                return output;
                            }

                            return '<div class="d-flex">' +
                                @can('manager_department_department-swap')
                                    '<a href="' + up + '" class="badge bg-success mx-1"><i class="cil-arrow-top"></a></i>' +
                                @endcan
                                    @can('manager_department_department-swap')
                                    '<a href="' + down + '" class="badge bg-warning mx-1"><i class="cil-arrow-bottom"></i></a>' +
                                @endcan
                                    @can('manager_department_department-show')
                                    '<a class="me-1 ms-2" href="' + URL + '"><span class="badge bg-success text-dark">Show</span>' +
                                @endcan
                                    @can('manager_department_department-edit')
                                    '<a class="me-1" href="' + URL + '/edit"><span class="badge bg-info text-dark">Edit</span></a>' +
                                @endcan
                                    @can('manager_department_department-delete')
                                    '<a class="me-1" href="javascript:void(0)"><span type="button" class="badge bg-danger" data-url="' + URL + '" data-coreui-toggle="modal" data-coreui-target="#del-model">Delete</span></a>' +
                                @endcan
                                    @can('manager_department_department-activity-log')
                                    '<a class="me-1" href="' + ACTIVITY + '"><span class="badge bg-warning text-dark">Activity</span></a>' +
                                @endcan
                                    @can('manager_department_update-status')
                                    '|' +
                                updateStatus() +
                                @endcan
                                    '</div>'

                        }
                    }
                ]
            });
        });

        function selectRange() {
            $('.dataTable').DataTable().ajax.reload()
        }
    </script>
    {{-- Delete Confirmation Model : Script : Start --}}
    <script>
        $("#del-model").on('show.coreui.modal', function (event) {
            var triggerLink = $(event.relatedTarget);
            var url = triggerLink.data("url");
            $("#del-form").attr('action', url);
        })
    </script>
    {{-- Delete Confirmation Model : Script : Start --}}
    {{-- Toastr : Script : Start --}}
    @if(Session::has('messages'))
        <script>
            noti({!! json_encode((Session::get('messages'))) !!});
        </script>
    @endif
    {{-- Toastr : Script : End --}}
@endpush