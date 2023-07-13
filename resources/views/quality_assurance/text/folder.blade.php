@extends('layouts.app')

@section('css')
    <!-- Data Table CSS -->
    <link href="{{URL::asset('plugins/datatable/datatables.min.css')}}" rel="stylesheet"/>
    <!-- Sweet Alert CSS -->
    <link href="{{URL::asset('plugins/sweetalert/sweetalert2.min.css')}}" rel="stylesheet"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet"/>

@endsection

@section('page-header')
    <!-- PAGE HEADER -->
    <div class="page-header mt-5-7">
        <div class="page-leftheader">
            <h4 class="page-title mb-0">{{ __('All Folder') }}</h4>
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}"><i
                            class="fa-solid fa-user-shield mr-2 fs-12"></i>{{ __('User') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a
                        href="#"> {{ __('User Images Folder') }}</a></li>
            </ol>
        </div>
{{--        <div class="page-rightheader">--}}
{{--            <button id="download-folders-button" class="btn btn-primary" style="display: none;">Download</button>--}}
{{--            <button id="export-folders-button" class="btn btn-primary" style="display: none;">Export</button>--}}
{{--        </div>--}}
    </div>
    <!-- END PAGE HEADER -->
@endsection

@section('content')
    <!-- USERS LIST DATA TABEL -->
    <div class="row">
        <form id="download-folders-form" action="{{ route('admin.coco.folder.download-multiple') }}" method="POST"
              style="display: none;">
            @csrf
            <input type="text" name="folder_ids" id="download-folders-input">
        </form>
        <div class="col-lg-12 col-md-12 col-xm-12">
            <div class="card border-0">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">{{ __('Folder Management') }}</h3>

                </div>
                <div class="card-body pt-2">
                    <!-- BOX CONTENT -->
                    <div class="box-content">

                        <!-- DATATABLE -->
                        <table id='listFoldersTable' class='table listFoldersTable' width='100%'>
                            <thead>
                            <tr>
{{--                                <th width="2%"></th>--}}
                                <th width="15%">{{ __('Assigned At' ) }}</th>
                                <th width="15%">{{ __('Name') }}</th>
                                <th width="8%">{{ __('Total Text') }}</th>
                                <th width="8%">{{ __('Accepted Image') }}</th>
                                <th width="8%">{{ __('RejectedImage') }}</th>
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
    <form id="export-folders-form" action="{{ route('admin.coco.folder.export-multiple') }}" method="POST"
          style="display: none;">
        @csrf
        <input type="text" name="folder_ids" id="export-folders-input">
    </form>

    <!-- END USERS LIST DATA TABEL -->
@endsection

@section('js')
    <!-- Data Tables JS -->
    <script src="{{URL::asset('plugins/datatable/datatables.min.js')}}"></script>
    <script src="{{URL::asset('plugins/sweetalert/sweetalert2.all.min.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

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
                    url: "{{ route('qa.text-folder') }}",
                },
                columns: [
                    // {
                    //     data: 'checkbox',
                    //     name: 'checkbox',
                    //     orderable: false,
                    //     searchable: false
                    // },
                    {
                        data: 'updated-on',
                        name: 'updated-on',
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
                        data: 'text_count',
                        name: 'Total Text',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'accepted_image',
                        name: 'accepted_image',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'rejected_image',
                        name: 'rejected_image',
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
                columnDefs: [
                    {

                    }
                ],
                initComplete: function () {
                    $(".dataTables_filter input").attr("placeholder", "Enter search term");
                },
            });

            $('#listFoldersTable').on('click', '.folder-checkbox', function () {

                // Check if at least one checkbox is selected
                const hasChecked = $('.folder-checkbox:checked').length > 0;

                // Show or hide the delete button based on whether at least one checkbox is selected
                if (hasChecked) {
                    $('#download-folders-button').show();
                    $('#export-folders-button').show();
                } else {
                    $('#download-folders-button').hide();
                    $('#export-folders-button').hide();
                }

                let selectedFolderIds = [];
                $('.folder-checkbox:checked').each(function () {
                    selectedFolderIds.push($(this).data('folder-id'));
                });
                // Set the selected folder IDs as the value of the hidden input in the form
                $('#assign-folders-input').val(JSON.stringify(selectedFolderIds));

            });
            $('#export-folders-button').on('click', function () {
                // Get the IDs of the selected folders
                let selectedFolderIds = [];
                $('.folder-checkbox:checked').each(function () {
                    selectedFolderIds.push($(this).data('folder-id'));
                });

                // If no folders are selected, show an alert and return
                if (selectedFolderIds.length === 0) {
                    alert('Please select at least one folder to export.');
                    return;
                }

                // Set the selected folder IDs as the value of the hidden input in the form
                $('#export-folders-input').val(JSON.stringify(selectedFolderIds));

                // Submit the form to download the CSV file
                $('#export-folders-form').submit();
            });
            $('#download-folders-button').on('click', function () {
                // Get the IDs of the selected folders
                let selectedFolderIds = [];
                $('.folder-checkbox:checked').each(function () {
                    selectedFolderIds.push($(this).data('folder-id'));
                });

                // If no folders are selected, show an alert and return
                if (selectedFolderIds.length === 0) {
                    alert('Please select at least one folder to export.');
                    return;
                }

                // Set the selected folder IDs as the value of the hidden input in the form
                $('#download-folders-input').val(JSON.stringify(selectedFolderIds));

                // Submit the form to download the CSV file
                $('#download-folders-form').submit();
            });

            $(document).ready(function () {
                $('#folder').select2();
            });

            // Activate Payment
            $(document).on('click', '.agreeStatus', function (e) {

                e.preventDefault();

                var formData = new FormData();
                formData.append("id", $(this).attr('id'));
                // Append the status to the formData object
                formData.append('type', 'coco');
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    method: 'post',
                    url: '/quality-assurance/approve-status',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        if (data == 'success') {
                            Swal.fire('{{ __('Folder Completed') }}', '{{ __('Folder of selected user has been completed successfully') }}', 'success');
                            $("#userResultTable").DataTable().ajax.reload();
                            setTimeout(function () {
                                location.reload();
                            }, 2000);

                        } else {
                            Swal.fire('{{ __('Folder Already Completed') }}', '{{ __('Folder of selected user is already activated') }}', 'error');
                        }
                    },
                    error: function (data) {
                        if (data.responseJSON.error === 'price_not_set') {
                            Swal.fire('{{ __('Folder Not Set') }}', '{{ __('Please update your price in Folder') }}', 'error');
                        } else {
                            Swal.fire({type: 'error', title: 'Oops...', text: 'Something went wrong!'});
                        }
                    }
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
                                error: function (data) {
                                    Swal.fire({
                                        type: 'error',
                                        title: 'Oops...',
                                        text: '{{ __("Something went wrong") }}!'
                                    })
                                }
                            })
                        }
                    })
                });
            });
        });
    </script>
@endsection
