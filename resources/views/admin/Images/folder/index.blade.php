@extends('layouts.app')

@section('css')
    <!-- Data Table CSS -->
    <link href="{{URL::asset('plugins/datatable/datatables.min.css')}}" rel="stylesheet" />
    <!-- Sweet Alert CSS -->
    <link href="{{URL::asset('plugins/sweetalert/sweetalert2.min.css')}}" rel="stylesheet" />
@endsection

@section('page-header')
    <!-- PAGE HEADER -->
    <div class="page-header mt-5-7">
        <div class="page-leftheader">
            <h4 class="page-title mb-0">{{ __('All Images Folder') }}</h4>
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-user-shield mr-2 fs-12"></i>{{ __('Admin') }}</a></li>
                 <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.images.folder') }}"> {{ __('User Images Folder') }}</a></li>
            </ol>
        </div>
        <div class="page-rightheader">
            <button class="btn btn-special create-project mr-2" type="button" id="add-folder" data-tippy-content="{{ __('Create New Folder') }}" ><i class="fa-solid fa-rectangle-history-circle-plus"></i></button>
        </div>
    </div>
    <!-- END PAGE HEADER -->
@endsection

@section('content')
    <!-- USERS LIST DATA TABEL -->
    <div class="row">
        <div class="col-lg-12 col-md-12 col-xm-12">
            <div class="card border-0">
                <div class="card-header flex justify-content-between">
                    <h3 class="card-title">{{ __('Folder Management') }}</h3>
                </div>
                <div class="card-body pt-2">
                    <!-- BOX CONTENT -->
                    <div class="box-content">

                        <!-- DATATABLE -->
                        <table id='listFoldersTable' class='table listFoldersTable' width='100%'>
                            <thead>
                            <tr>
                                <th width="8%">{{ __('Created At') }}</th>
                                <th width="10%">{{ __('Name') }}</th>
                                <th width="8%">{{ __('Created By') }}</th>
                                <th width="8%">{{ __('Assign User') }}</th>
                                <th width="8%">{{ __('Project') }}</th>
                                <th width="6%">{{ __('Total Images') }}</th>
                                <th width="6%">{{ __('Total Text') }}</th>
                                <th width="7%">{{ __('Status') }}</th>
                                <th width="8%">{{ __('Actions') }}</th>
                            </tr>
                            </thead>
                        </table>
                        <!-- END DATATABLE -->
                    </div>
                    <!-- END BOX CONTENT -->
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
                "order": [],
                language: {
                    search: "<i class='fa fa-search search-icon'></i>",
                    lengthMenu: '_MENU_ ',
                    paginate : {
                        first    : '<i class="fa fa-angle-double-left"></i>',
                        last     : '<i class="fa fa-angle-double-right"></i>',
                        previous : '<i class="fa fa-angle-left"></i>',
                        next     : '<i class="fa fa-angle-right"></i>'
                    }
                },
                pagingType : 'full_numbers',
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.images.folder.list') }}",
                columns: [
                    {
                        data: 'created-on',
                        name: 'created-on',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'name',
                        name: 'name',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'created-by',
                        name: 'created-by',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'assignUser',
                        name: 'assignUser',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'project-id',
                        name: 'project-id',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'images_count',
                        name: 'Total Images',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'text_count',
                        name: 'Total Text',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    },
                ],
                initComplete: function () {
                    $(".dataTables_filter input").attr("placeholder", "Enter search term");
                },
            });

            // DELETE CONFIRMATION
            $(document).on('click', '.deleteUserButton', function(e) {

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
                            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                            method: 'post',
                            url: 'folder-delete',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function (data) {
                                if (data == 'success') {
                                    Swal.fire('{{ __('User Deleted') }}', '{{ __('User has been successfully deleted') }}', 'success');
                                    $("#listFoldersTable").DataTable().ajax.reload();
                                } else {
                                    Swal.fire('{{ __('Delete Failed') }}', '{{ __('There was an error while deleting this user') }}', 'error');
                                }
                            },
                            error: function(data) {
                                Swal.fire({ type: 'error', title: 'Oops...', text: '{{ __("Something went wrong") }}!' })
                            }
                        })
                    }
                })
            });

        });
        // CREATE NEW PROJECT
        $(document).on('click', '#add-folder', function(e) {

            e.preventDefault();

            Swal.fire({
                title: '{{ __('Create New Folder') }}',
                showCancelButton: true,
                confirmButtonText: 'Create',
                reverseButtons: true,
                closeOnCancel: true,
                input: 'text',
            }).then((result) => {
                if (result.value) {
                    var formData = new FormData();
                    formData.append("name", result.value);
                    $.ajax({
                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        method: 'post',
                        url: 'folder-store',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (data) {
                            if (data['status'] == 'success') {
                                Swal.fire('{{ __('Folder Created') }}', '{{ __('New project has been successfully created') }}', 'success');
                                location.reload();
                            } else {
                                Swal.fire('{{ __('Folder Creation Error') }}', data['message'], 'error');
                            }
                        },
                        error: function(data) {
                            Swal.fire({ type: 'error', title: 'Oops...', text: '{{ __('Something went wrong') }}!' })
                        }
                    })
                } else if (result.dismiss !== Swal.DismissReason.cancel) {
                    Swal.fire('{{ __('No Project Name Entered') }}', '{{ __('Make sure to provide a new project name before creating') }}', 'error')
                }
            })
        });

    </script>
@endsection
