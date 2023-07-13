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
        <div class="page-header mt-5-7">
            <div class="page-leftheader">
                <h4 class="page-title mb-0">{{ __('Report ') }}</h4>
                <ol class="breadcrumb mb-2">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-sack-dollar mr-2 fs-12"></i>{{ __('Admin') }}</a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="{{ route('admin.finance.dashboard') }}"> {{ __('Finance Management') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="#"> {{ __('Invoice Settings') }}</a></li>
                </ol>
            </div>
        </div>
        <div class="page-rightheader">
            <a href="{{route('admin.invoices.create')}}" class="btn btn-primary mt-1" type="button"  data-tippy-content="{{ __('Upload New Report CSV') }}" >Upload Report CSV</a>
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
                    <h3 class="card-title">{{ __('Report') }}</h3>
                </div>
                <div class="card-body pt-2">
                    <!-- BOX CONTENT -->
                    <div class="box-content">

                        <!-- DATATABLE -->
                        <table id='listFoldersTable' class='table listFoldersTable' width='100%'>
                            <thead>
                            <tr>
                                <td width="7%">{{ __('Created On') }}</td>
                                <th width="5%">{{ __('Project Name') }}</th>
                                <td width="7%">{{ __('User') }}</td>
                                <th width="7%">{{ __('Accepted Data') }}</th>
                                <th width="7%">{{ __('Rejected Data') }}</th>
                                <th width="7%">{{ __('Referral Email') }}</th>
                                <th width="8%">{{ __('Earning') }}</th>
                                <th width="8%">{{ __('Commission') }}</th>
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

            var table = $('#listFoldersTable').DataTable({
                "lengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
                responsive: true,
                colReorder: true,
                "order": [[ 0, "desc" ]],
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
                ajax: "{{ route('admin.user.invoices') }}",
                columns: [
                    {
                        data: 'created-on',
                        name: 'created-on',
                        orderable:  true,
                        searchable: true
                    },
                    {
                        data: 'project-name',
                        name: 'project-name',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'userName',
                        name: 'userName',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'accepted_data',
                        name: 'accepted_data',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'rejected_data',
                        name: 'rejected_data',
                        orderable: true,
                        searchable: true
                    },

                    {
                        data: 'referral_email',
                        name: 'referral_email',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'earning',
                        name: 'earning',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'commission',
                        name: 'commission',
                        orderable: true,
                        searchable: true
                    },
                    // {
                    //     data: 'actions',
                    //     name: 'actions',
                    //     orderable: false,
                    //     searchable: false
                    // },
                ]
            });

            // DELETE CONFIRMATION
            $(document).on('click', '.deleteUserButton', function(e) {

                e.preventDefault();

                Swal.fire({
                    title: '{{ __('Confirm Text Deletion') }}',
                    text: '{{ __('Warning! This action will delete text permanently') }}',
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
                            url: 'csv-delete',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function (data) {
                                if (data == 'success') {
                                    Swal.fire('{{ __('Text Deleted') }}', '{{ __('Text has been successfully deleted') }}', 'success');
                                    $("#listFoldersTable").DataTable().ajax.reload();
                                } else {
                                    Swal.fire('{{ __('Delete Failed') }}', '{{ __('There was an error while deleting this text') }}', 'error');
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
    </script>
@endsection
