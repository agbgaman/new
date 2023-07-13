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
            <h4 class="page-title mb-0">{{ __('Mass Notifications') }}</h4>
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}"><i
                            class="fa-solid fa-message-exclamation mr-2 fs-12"></i>{{ __('Admin') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a
                        href="{{route('admin.notifications')}}"> {{ __('Mass Notifications') }}</a></li>
            </ol>
        </div>
        <div class="page-rightheader">
            <a href="{{ route('admin.notifications.create') }}"
               class="btn btn-primary mt-1">{{ __('New Notification') }}</a>
        </div>
    </div>
    <!-- END PAGE HEADER -->
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12 col-xm-12">
            <div class="card overflow-hidden border-0">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card-header d-flex align-items-center">
                            <h3 class="card-title">{{ __('All User Notifications') }}</h3>
                            <a class="refresh-button" href="#" data-tippy-content="Refresh Table">
                                <i class="fa fa-refresh table-action-buttons view-action-button"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <x-date-range-inputs/>
                    </div>
                </div>
                <div class="card-body pt-2">
                    <!-- SET DATATABLE -->
                    <table id='allNotificationsTable' class='table' width='100%'>
                        <thead>
                        <tr>
                            <th width="10%">{{ __('Created Date') }}</th>
                            <th width="10%">{{ __('Type') }}</th>
                            <th width="10%">{{ __('User Action') }}</th>
                            <th width="20%">{{ __('Notification By') }}</th>
                            <th width="20%">{{ __('Subject') }}</th>
                            <th width="20%">{{ __('Users') }}</th>
                            <th width="5%">{{ __('Actions') }}</th>
                        </tr>
                        </thead>
                    </table> <!-- END SET DATATABLE -->
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <!-- Data Tables JS -->
    <script src="{{URL::asset('plugins/datatable/datatables.min.js')}}"></script>
    <script src="{{URL::asset('plugins/sweetalert/sweetalert2.all.min.js')}}"></script>
    <script type="text/javascript">
        $(function () {

            "use strict";

            var table = $('#allNotificationsTable').DataTable({
                "lengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
                responsive: true,
                colReorder: true,
                "order": [],
                language: {
                    "emptyTable": "<div><img id='no-results-img' src='{{ URL::asset('img/files/no-notification.png') }}'><br>{{ __('You have not created any mass notifications yet') }}</div>",
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
                    url: "{{ route('admin.notifications') }}",
                    data: function (d) {
                        d.created_on_from = $('#created_on_from').val();
                        d.created_on_to = $('#created_on_to').val();
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
                        data: 'notification-type',
                        name: 'notification-type',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'subject',
                        name: 'subject',
                        render: $.fn.dataTable.render.text(),
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'user_id',
                        name: 'user_id',
                        render: $.fn.dataTable.render.text(),
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'user-action',
                        name: 'user-action',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'users',
                        name: 'users',
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
            $('#created_on_from, #created_on_to').on('change', function () {
                table.draw();
            });

            // DELETE CONFIRMATION
            $(document).on('click', '.deleteNotificationButton', function (e) {

                e.preventDefault();

                Swal.fire({
                    title: '{{ __('Confirm Notification Deletion') }}',
                    text: '{{ __('It will permanently delete this notification') }}',
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
                            url: 'notifications/delete',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function (data) {
                                if (data == 'success') {
                                    Swal.fire('{{ __('Notification Deleted') }}', '{{ __('Notification has been successfully deleted') }}', 'success');
                                    $("#allNotificationsTable").DataTable().ajax.reload();
                                } else {
                                    Swal.fire('{{ __('Delete Failed') }}', '{{ __('There was an error while deleting this notification') }}', 'error');
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
