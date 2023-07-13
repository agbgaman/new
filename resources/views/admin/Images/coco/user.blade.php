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
            <h4 class="page-title mb-0">{{ __('Users') }}</h4>
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i
                            class="fa-solid fa-user-shield mr-2 fs-12"></i>{{ __('Admin') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#"> {{ __('COCO') }}</a></li>
            </ol>
        </div>
    </div>
    <!-- END PAGE HEADER -->
@endsection

@section('content')
    <style>
        .folder-icon-list {
            margin-right: 5px;
            transition: transform 0.3s ease;
            font-size: 18px;
            color: #ffc107;

        }

        .folder-icon {
            font-size: 48px;
            color: #ffc107;
        }

        .badge-success {
            background-color: #28a745;
            color: #fff;
            font-size: 0.9rem;
            font-weight: 500;
            padding: 0.35rem 0.5rem;
            line-height: 1;
            vertical-align: middle;
            margin-left: 10px;
        }

        .font-weight-bold-success {
            color: #28a745;
            font-weight: bold;
        }
    </style>
    <!-- USERS LIST DATA TABEL -->
    <div class="row">
        <div class="col-lg-12 col-md-12 col-xm-12">
            <div class="card border-0">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card-header d-flex align-items-center">
                            <h3 class="card-title mb-0 mr-3">{{ __('COCO Users') }}</h3>
                            <a class="refresh-button" href="#" data-tippy-content="Refresh Table">
                                <i class="fa fa-refresh table-action-buttons view-action-button"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="database-columns">{{ __('Select Columns:') }}</label>
                                <select class="form-control" name="database-columns" id="database_columns">
                                    <option value="created_at">Register On</option>
                                    <option value="last_seen">Last Seen</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="startDateTime">{{ __('Start:') }}</label>
                                <input type="datetime-local" id="created_on_from" class="form-control">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="endDateTime">{{ __('End:') }}</label>
                                <input type="datetime-local" id="created_on_to" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-2">
                    <!-- BOX CONTENT -->
                    <div class="box-content">

                        <!-- DATATABLE -->
                        <table id='listUsersTable' class='table listUsersTable' width='100%'>
                            <thead>
                            <tr>
                                <th width="10%">{{ __('Last Seen') }}</th>
                                <th width="15%">{{ __('User') }}</th>
                                <th width="7%">{{ __('Folders') }}</th>
                                <th width="7%">{{ __('Assigned Folders') }}</th>
                                <th width="7%">{{ __('QA Complete') }}</th>
                                {{--                                <th width="7%">{{ __('Group') }}</th>--}}
                                <th width="7%">{{ __('Country') }}</th>
                                {{--                                <th width="7%">{{ __('Currency') }}</th>--}}
                                {{--                                <th width="5%">{{ __('Status') }}</th>--}}
                                {{--                                <th width="8%">{{ __('Actions') }}</th>--}}
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

            var table = $('#listUsersTable').DataTable({
                "lengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
                responsive: true,
                colReorder: true,
                "order": [],
                // "order": [[0, "desc"]],
                language: {
                    search: "<i class='fa fa-sear?ch search-icon'></i>",
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
                    url: "{{ route('admin.coco.user',$project->id)}}",
                    data: function (d) {
                        d.project_id = {{ $project->id }};
                        d.created_on_from = $('#created_on_from').val();
                        d.created_on_to = $('#created_on_to').val();
                        d.database_columns = $('#database_columns').val();

                    }
                },
                columns: [
                    {
                        data: 'last-seen-on',
                        name: 'last-seen-on',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'user',
                        name: 'user',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'folder_count',
                        name: 'folder_count',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'assigned_folders',
                        name: 'assigned_folders',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'complete',
                        name: 'complete',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'custom-country',
                        name: 'custom-country',
                        orderable: true,
                        searchable: true
                    },
                ],
                initComplete: function () {
                    $(".dataTables_filter input").attr("placeholder", "Enter search term");
                },
            });
            // add a change event listener to the date filter inputs
            $('#created_on_from, #created_on_to, #database_columns').on('change', function () {
                table.draw();
            });

            // DELETE CONFIRMATION
            $(document).on('click', '.deleteUserButton', function (e) {

                e.preventDefault();

                Swal.fire({
                    title: '{{ __('Confirm User Deletion') }}',
                    text: '{{ __('Warning! This action will delete user permanently') }}',
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
                            url: 'delete',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function (data) {
                                if (data == 'success') {
                                    Swal.fire('{{ __('User Deleted') }}', '{{ __('User has been successfully deleted') }}', 'success');
                                    $("#listUsersTable").DataTable().ajax.reload();
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
