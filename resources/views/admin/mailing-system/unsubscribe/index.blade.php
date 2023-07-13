@extends('layouts.app')

@section('css')
    <!-- Data Table CSS -->
    <link href="{{URL::asset('plugins/datatable/datatables.min.css')}}" rel="stylesheet" />
    <!-- Sweet Alert CSS -->
    <link href="{{URL::asset('plugins/sweetalert/sweetalert2.min.css')}}" rel="stylesheet" />
@endsection

@section('page-header')
    <!-- PAGE HEADER -->
    <!-- PAGE HEADER -->
    <div class="page-header mt-5-7">
        <div class="page-leftheader">
            <h4 class="page-title mb-0">{{ __('User Information') }}</h4>
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i
                            class="fa-solid fa-user-shield mr-2 fs-12"></i>{{ __('Admin') }}</a></li>
                <li class="breadcrumb-item" aria-current="page"><a
                        href="#"> {{ __('Mail System') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a
                        href="{{ route('admin.mailing.system.campaign.index') }}">{{ __('Unsubscribe User') }}</a></li>
            </ol>
        </div>
{{--        <div class="page-rightheader">--}}
{{--            <a href="{{ route('admin.mailing.system.campaign.create') }}" class="btn btn-primary mt-1">{{ __('Create User Campaign') }}</a>--}}
{{--        </div>--}}
    </div>
    <!-- END PAGE HEADER -->
@endsection

@section('content')
    <!-- USERS LIST DATA TABEL -->
    <div class="row">
        <div class="col-lg-12 col-md-12 col-xm-12">
            <div class="card border-0">
                <div class="card-header flex justify-content-between">
                    <h3 class="card-title">{{ __('Unsubscribe User') }}</h3>
                </div>
                <div class="card-body pt-2">
                    <!-- BOX CONTENT -->
                    <div class="box-content">
                        <!-- DATATABLE -->
                        <table id='listFoldersTable' class='table listFoldersTable' width='100%'>
                            <thead>
                            <tr>
                                <th width="8%">{{ __('Created At') }}</th>
                                <th width="8%">{{ __('Name') }}</th>
                                <th width="6%">{{ __('Campaign') }}</th>
                                <th width="6%">{{ __('Actions') }}</th>
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
                ajax: "{{ route('admin.mailing.system.unsubscribe.list') }}",
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
                        data: 'campaign',
                        name: 'campaign',
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

        });
    </script>
@endsection
