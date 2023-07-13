@extends('layouts.app')

@section('css')
    <!-- Data Table CSS -->
    <link href="{{ URL::asset('plugins/datatable/datatables.min.css') }}" rel="stylesheet"/>
    <!-- Awselect CSS -->
    <link href="{{ URL::asset('plugins/awselect/awselect.min.css') }}" rel="stylesheet"/>
    <!-- Sweet Alert CSS -->
    <link href="{{ URL::asset('plugins/sweetalert/sweetalert2.min.css') }}" rel="stylesheet"/>
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
            <h4 class="page-title mb-0">{{ __('Permission Request') }}</h4>
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i
                            class="fa-solid fa-user-shield mr-2 fs-12"></i>{{ __('Admin') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a
                        href="{{ route('admin.user.permission.request') }}">
                        {{ __('Permission Request') }}</a></li>
            </ol>
        </div>
    </div>
    <!-- END PAGE HEADER -->
@endsection

@section('content')
    <!-- PROJECT INSTRACTION LIST DATA TABEL -->
    <div class="row">
        <div class="col-lg-12 col-md-12 col-xm-12">
            <div class="card border-0">
                <div class="row">
                    <div class="col-md-5">
                        <div class="card-header d-flex align-items-center">
                            <h3 class="card-title">{{ __('Projects Permission') }}</h3>
                            <a class="refresh-button" href="#" data-tippy-content="Refresh Table">
                                <i class="fa fa-refresh table-action-buttons view-action-button"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <label for="startDateTime" class="small font-weight-bold">{{ __('status:') }}</label>

                        <select name="status" id="status" class="form-control">
                            <option value=""> Select any Status</option>
                            <option value="Applied"> Applied</option>
                            <option value="Failed"> Failed</option>
                            <option value="Approved"> Approved</option>
                            <option value="pending"> Pending</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="startDateTime" class="small font-weight-bold">{{ __('Project:') }}</label>

                        <select name="projects" id="project_id" class="form-control">
                            <option value=""> Select any Project</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}"> {{ $project->name }} </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <x-date-range-inputs/>
                    </div>
                </div>

                <div class="card-body pt-2">
                    <!-- BOX CONTENT -->

                    <div class="box-content">
                        <!-- DATATABLE -->
                        <table id='listProjectInstructionTable' class='table' width='100%'>
                            <thead>
                            <tr>
                                <th width="15%">{{ __('Created At') }}</th>
                                <th width="8%">{{ __('User Name') }}</th>
                                <th width="8%">{{ __('Project Name') }}</th>
                                <th width="8%">{{ __('Status') }}</th>
                                <th width="8%">{{ __('Approved On') }}</th>
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
        $(function () {

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
                ajax: {
                    url: "{{  route('admin.user.permission.request') }}",
                    data: function (d) {
                        d.created_on_from   = $('#created_on_from').val();
                        d.created_on_to     = $('#created_on_to').val();
                        d.project_id        = $('#project_id').val();
                        d.status            = $('#status').val();
                        // add any other filters you want to pass to the backend here
                    }
                },
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
                        data: 'project',
                        name: 'project',
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
                        data: 'approved-on',
                        name: 'approved-on',
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
            // add a change event listener to the date filter inputs
            $('#created_on_from, #created_on_to,#project_id,#status').on('change', function () {
                table.draw();
            });
            // ACTIVATE Transcription
            $(document).on('click', '.agreeTranscriptionButton', function (e) {

                e.preventDefault();

                var formData = new FormData();
                formData.append("id", $(this).attr('id'));

                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    method: 'post',
                    url: '/admin/user-projects-permission-request-approved',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        if (data == 'success') {
                            Swal.fire('{{ __('Permission Completed') }}', '{{ __('Permission of selected user has been passed successfully') }}', 'success');
                            $("#listProjectInstructionTable").DataTable().ajax.reload();
                        } else {
                            Swal.fire('{{ __('Permission Already Completed') }}', '{{ __('Permission of selected user is already activated') }}', 'error');
                        }
                    },
                    error: function (data) {
                        Swal.fire({type: 'error', title: 'Oops...', text: 'Something went wrong!'})
                    }
                })

            });


            // DEACTIVATE Transcription
            $(document).on('click', '.disagreeTranscriptionButton', function (e) {

                e.preventDefault();

                var formData = new FormData();
                formData.append("id", $(this).attr('id'));

                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    method: 'post',
                    url: '/admin/user-projects-permission-request-disagree',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        if (data == 'success') {
                            Swal.fire('{{ __('Permission Failed') }}', '{{ __('Permission of selected user has been failed successfully') }}', 'success');
                            $("#listProjectInstructionTable").DataTable().ajax.reload();
                        } else {
                            Swal.fire('{{ __('Permission Already Failed') }}', '{{ __('Permission of selected user is already failed') }}', 'error');
                        }
                    },
                    error: function (data) {
                        Swal.fire({type: 'error', title: 'Oops...', text: 'Something went wrong!'})
                    }
                })

            });

        });
    </script>
@endsection
