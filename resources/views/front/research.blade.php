@extends('front.layouts.app')
@push('head-scripts')
    <style>
        .form-control{
            height: 20px;
        }
    </style>
@endpush

@section('content')

    <!-- Start Page Banner Area -->
    <div class="page-banner-area bg-1 pt-100">
        <div class="container">
            <div class="page-banner-content">
                <h2>Researches</h2>
                <ul>
                    <li>
                        <a href="{{ route('home') }}">
                            <i class="ri-home-8-line"></i>
                            Home
                        </a>
                    </li>
                    <li>
                        <span>Researches</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- End Page Banner Area -->
<div class="container my-5">
    <div class="row">
        <div class="col-12">
            <div class="table-responsive research-front">
                <table id="indextable" class="table table-bordered table-striped table-hover table-responsive w-100 pt-1">
                    <thead class="table" style="background-color: #da0808;color: white">
                    <th>#</th>
                    <th>Title</th>
                    <th>Department</th>
                    <th>Date</th>
                    <th>Actions</th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
@push('footer-scripts')
    <script type="text/javascript" src="{{ asset('manager/datatable/datatables.min.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            //Datatable
            $('#indextable').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                order: [[ 0, "desc" ]],
                ajax: {
                    "type":"GET",
                    "url":"{{route('get.research')}}",
                    // "data":function (d){
                    //     d.category_id = document.getElementById('category-id').value;
                    // }
                },
                columns: [
                    {data: 'id', orderable: false},
                    {data: 'title'},
                    {data: 'department_id'},
                    {data: 'year'},
                    {data: null},
                ],
                columnDefs: [
                    {
                        targets: 0,
                        orderable: false,
                        searchable: false,
                        width: '100px',
                        render: function ( data, type, row, meta ) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        targets: -1,
                        searchable: false,
                        orderable: false,
                        render: function (data, type, row, meta) {

                            let detail = "{{ route('research.detail', ':id') }}";
                            detail = detail.replace(':id', row.id);



                            return '<div class="d-flex">' +

                                    '<a class="me-1" href="' + detail + '"><span class="badge text-light" style="background-color: #da0808;"> Read More</span></a>' +

                                '</div>'

                        }
                    }


                ]
            });
        });
        function selectRange(){
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
