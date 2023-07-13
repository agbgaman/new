@extends('layouts.app')

@section('css')
    <!-- Data Table CSS -->
    <link href="{{URL::asset('plugins/datatable/datatables.min.css')}}" rel="stylesheet"/>
    <!-- Sweet Alert CSS -->
    <link href="{{URL::asset('plugins/sweetalert/sweetalert2.min.css')}}" rel="stylesheet"/>
@endsection

@section('page-header')
    <!-- PAGE HEADER -->
    <div class="page-header mt-5-7">
        <div class="page-leftheader">
            <h4 class="page-title mb-0">{{ $project->name }} Tasks</h4>
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{route('user.transcribe.file')}}"><i
                            class="fa-solid fa-microphone-lines mr-2 fs-12"></i>{{ __('User') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="{{url('#')}}"> {{ $project->name }}</a>
                </li>
            </ol>
        </div>
    </div>
    <!-- END PAGE HEADER -->
@endsection

@section('content')
    <style>
        @media screen and (max-width: 767px) {
            .dataTables_length {
                text-align: right !important;
            }
        }

    </style>
    <!-- USERS LIST DATA TABEL -->
    <div class="row">
        <div class="col-lg-12 col-md-12 col-xm-12">
            <div class="card border-0">
                <div class="card-header flex justify-content-between">
                    <h3 class="card-title">{{ $project->name }} Tasks</h3>
                </div>
                <div class="card-body pt-2">
                    <!-- BOX CONTENT -->
                    <div class="box-content">

                        <!-- DATATABLE -->
                        <table id='listFoldersTable' class="table table-bordered custom-table-style" width='100%'>
                            <thead>
                            <tr>
                                <th width="15%">{{ __('Folder Name') }}</th>
                                <th width="7%">{{ __('Total Text') }}</th>
                                <th width="7%">{{ __('Accepted Text') }}</th>
                                <th width="7%">{{ __('Rejected Text') }}</th>
                                <th width="7%">{{ __('Pending Text') }}</th>
                                <th width="8%">{{ __('Remaining Text ') }}</th>
                                <th width="8%">{{ __('Assigned On') }}</th>
                                <th width="7%">{{ __('Status') }}</th>
                            </tr>
                            </thead>
                        </table>
                        <!-- END DATATABLE -->

                    </div> <!-- END BOX CONTENT -->
                </div>
            </div>
        </div>
    </div>
    <!-- END USERS LIST DATA TABEL -->
@endsection

@section('js')
    <!-- Data Tables JS -->
    <script src="{{URL::asset('plugins/datatable/datatables.min.js')}}"></script>
    <script src="{{URL::asset('plugins/sweetalert/sweetalert2.all.min.js')}}"></script>
    <script type="text/javascript">
        $(function () {

            "use strict";

            var table = $('#listFoldersTable').DataTable({
                "lengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
                responsive: true,
                colReorder: true,
                "order": [[0, "desc"]],
                language: {
                    search: "<i class='fa fa-search search-icon'></i>",
                    lengthMenu: '_MENU_ ',
                    paginate: {
                        first: '<i class="fa fa-angle-double-left"></i>',
                        last: '<i class="fa fa-angle-double-right"></i>',
                        previous: '<i class="fa fa-angle-left"></i>',
                        next: '<i class="fa fa-angle-right"></i>'
                    }
                },
                pagingType: 'full_numbers',
                processing: true,
                serverSide: true,

                ajax: {
                    url: "{{ route('user.transcribe.assign-text-to-text',$id) }}",
                    data: {
                        project_id: {{$id}}
                    }
                },
                columns: [
                    {
                        data: 'name',
                        name: 'name',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'text_count',
                        name: 'text_count',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'accepted_text',
                        name: 'accepted_text',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'rejected_text',
                        name: 'rejected_text',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'pending_text',
                        name: 'pending_text',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'remaining-count',
                        name: 'remaining-count',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'updated-on',
                        name: 'updated-on',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

            // DELETE CONFIRMATION
            $(document).on('click', '.deleteUserButton', function (e) {

                e.preventDefault();

                Swal.fire({
                    title: '{{ __('Confirm Folder Deletion') }}',
                    text: '{{ __('Warning! This action will delete folder permanently') }}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: '{{ __('Delete') }}',
                    reverseButtons: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        var formData = new FormData();
                        formData.append("id", $(this).attr('id'));
                        $.ajax({
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            method: 'post',
                            url: 'image-delete',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function (data) {
                                if (data == 'success') {
                                    Swal.fire('{{ __('Image Deleted') }}', '{{ __('Image has been successfully deleted') }}', 'success');
                                    $("#listFoldersTable").DataTable().ajax.reload();
                                } else {
                                    Swal.fire('{{ __('Delete Failed') }}', '{{ __('There was an error while deleting this user') }}', 'error');
                                }
                            },
                            error: function (data) {
                                Swal.fire({type: 'error', title: 'Oops...', text: '{{ __("Something went wrong") }}!'})
                            }
                        })
                    }
                })
            });
        });
    </script>
@endsection
