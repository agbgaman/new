@extends('layouts.app')

@section('css')
    <!-- Data Table CSS -->
    <link href="{{ URL::asset('plugins/datatable/datatables.min.css') }}" rel="stylesheet" />
    <!-- Awselect CSS -->
    <link href="{{ URL::asset('plugins/awselect/awselect.min.css') }}" rel="stylesheet" />
    <!-- Sweet Alert CSS -->
    <link href="{{ URL::asset('plugins/sweetalert/sweetalert2.min.css') }}" rel="stylesheet" />
    <style>
        .scrollable-column {
            max-height: 60px;
            overflow-y: auto;
            display: inline-block;
            width: 100%;
        }
    </style>
@endsection

@section('page-header')
    <!-- PAGE HEADER -->
    <div class="page-header mt-5-7">
        <div class="page-leftheader">
            <h4 class="page-title mb-0">{{ __('All project instruction') }}</h4>
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i
                            class="fa-solid fa-user-shield mr-2 fs-12"></i>{{ __('Admin') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.project-instruction') }}">
                        {{ __('Project instruction') }}</a></li>
            </ol>
        </div>
        <div class="page-rightheader">
            <button class="btn btn-special create-project mr-2"
                onclick="window.location.href='{{ route('admin.project-instruction.create-view') }}'" type="button"
                data-tippy-content="{{ __('Create New project instruction') }}"><i
                    class="fa-solid fa-rectangle-history-circle-plus"></i></button>
        </div>
    </div>
    <!-- END PAGE HEADER -->
@endsection

@section('content')
    <style>
        .short-description {
            max-height: 1.2em; /* Adjust this value according to the line height of your table rows */
            /*white-space: nowrap;*/
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
    <!-- PROJECT INSTRUCTION LIST DATA TABLE -->
    <div class="row">
        <div class="col-lg-12 col-md-12 col-xm-12">
            <div class="card border-0">
                <div class="card-header flex justify-content-between">
                    <h3 class="card-title">{{ __('Project Instruction Management') }}</h3>
                </div>
                <div class="card-body pt-2">
                    <!-- BOX CONTENT -->
                    <div class="box-content">
                        <!-- DATATABLE -->
                        <table id='listProjectInstructionTable' class='table' width='100%'>
                            <thead>
                                <tr>
                                    <th width="8%">{{ __('Created At') }}</th>
                                    <th width="8%">{{ __('Name') }}</th>
                                    <th width="8%">{{ __('Earning') }}</th>
                                    <th width="35%">{{ __('Description') }}</th>
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
    <!-- END PROJECT INSTRACTION LIST DATA TABEL -->
@endsection

@section('js')
    <!-- Data Tables JS -->
    <script src="{{ URL::asset('plugins/datatable/datatables.min.js') }}"></script>
    <!-- Awselect JS -->
    <script src="{{ URL::asset('plugins/awselect/awselect.min.js') }}"></script>
    <script src="{{ URL::asset('plugins/sweetalert/sweetalert2.all.min.js') }}"></script>
    <script src="{{ URL::asset('js/awselect.js') }}"></script>

    <script type="text/javascript">
        $(function() {

            "use strict";

            var table = $('#listProjectInstructionTable').DataTable({
                "lengthMenu": [
                    [25, 50, 100, -1],
                    [25, 50, 100, "All"]
                ],
                "scrollY": "300px", // Set the fixed height, e.g., 300px
                "scrollCollapse": true,
                // "paging": false // Disable pagination
                responsive: true,
                colReorder: true,
                "order": [
                    [0, "desc"]
                ],
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
                ajax: "{{ route('admin.project-instruction.list') }}",
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
                        data: 'price',
                        name: 'price',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'description',
                        name: 'description',
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
            $(document).on('click', '.deleteprojectInstructionButton', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: '{{ __('Confirm Project  Deletion') }}',
                    text: '{{ __('Warning! This action will delete Project Instruction permanently') }}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: '{{ __('Delete') }}',
                    reverseButtons: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        var formData = new FormData();
                        formData.append("id", $(this).attr('id'));
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            method: 'post',
                            url: 'project-instruction-delete',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function(data) {
                                if (data == 'success') {
                                    Swal.fire('{{ __('Deleted') }}',
                                        '{{ __('Project has been successfully deleted') }}',
                                        'success');
                                    $("#listProjectInstructionTable").DataTable().ajax
                                        .reload();
                                } else {
                                    Swal.fire('{{ __('Delete Failed') }}',
                                        '{{ __('There was an error while deleting') }}',
                                        'error');
                                }
                            },
                            error: function(data) {
                                Swal.fire({
                                    type: 'error',
                                    title: 'Oops...',
                                    text: '{{ __('Something went wrong') }}!'
                                })
                            }
                        })
                    }
                })
            });
        });
    </script>
@endsection
