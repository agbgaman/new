@extends('layouts.app')

@section('css')
    <!-- Data Table CSS -->
    <link href="{{URL::asset('plugins/datatable/datatables.min.css')}}" rel="stylesheet"/>
    <!-- Sweet Alert CSS -->
    <link href="{{URL::asset('plugins/sweetalert/sweetalert2.min.css')}}" rel="stylesheet"/>
    <link href="{{URL::asset('plugins/awselect/awselect.min.css')}}" rel="stylesheet"/>

    <script src="//cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>

@endsection

@section('page-header')
    <!-- PAGE HEADER -->
    <div class="page-header mt-5-7">
        <div class="page-leftheader">
            <h4 class="page-title mb-0">{{ __('All Registered Users') }}</h4>
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i
                            class="fa-solid fa-user-shield mr-2 fs-12"></i>{{ __('Admin') }}</a></li>
                <li class="breadcrumb-item" aria-current="page"><a
                        href="{{ route('admin.user.dashboard') }}"> {{ __('User Management') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a
                        href="{{ route('admin.user.list') }}"> {{ __('User List') }}</a></li>
            </ol>
        </div>
        <div class="page-rightheader">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal">
                {{ __('Create New User List') }}
            </button>


            <a href="{{ route('admin.user.create') }}" class="btn btn-primary mt-1">{{ __('Create New User') }}</a>
        </div>
    </div>
    <!-- END PAGE HEADER -->
@endsection

@section('content')
    <!-- USERS LIST DATA TABEL -->
    <div class="row">
        <div class="col-lg-12 col-md-12 col-xm-12">
            <div class="card border-0">
                <div class="row">
                    <div class="card-header justify-content-between">

                        <h3 class="card-title">{{ __('User Management') }}</h3>

                        <button class="btn btn-primary" type="button" data-bs-toggle="collapse"
                                data-bs-target="#advancedFilters" aria-expanded="false"
                                aria-controls="advancedFilters">
                            Advanced Filters
                        </button>
                    </div>
                </div>

                <div class="collapse" id="advancedFilters">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="form-group col-md-2">
                                        <label for="startDateTime">{{ __('Lower Age Range:') }}</label>
                                        <input type="number" id="ageL" class="form-control">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="startDateTime">{{ __('High Age Range:') }}</label>
                                        <input type="number" id="ageG" class="form-control">
                                    </div>
                                    <div class="form-group col-md-1">
                                        <label for="startDateTime">{{ __('Family M:') }}</label>
                                        <input type="number" id="family" class="form-control">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="database-columns">{{ __('Select Country:') }}</label>
                                        <select class="form-control" name="" id="country">
                                            <option value="">Select Country</option>
                                            @foreach(config('countries') as $value)
                                                <option value="{{ $value }}">{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="database-columns">{{ __('Select Columns:') }}</label>
                                        <select class="form-control" name="database-columns" id="database_columns">
                                            <option value="created_at">Register On</option>
                                            <option value="last_seen">Last Seen</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-2">
                                        <label for="startDateTime">{{ __('Start:') }}</label>
                                        <input type="datetime-local" id="created_on_from" class="form-control">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="endDateTime">{{ __('End:') }}</label>
                                        <input type="datetime-local" id="created_on_to" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="database-columns">{{ __('Select Language:') }}</label>
                                <select id="languages" name="primary_language" class="form-control"
                                        data-placeholder="{{ __('Select your languages') }}"
                                >
                                    <option value="">{{ __('Select your languages') }}</option>
                                    @foreach ($languages as $language)
                                        <option value="{{ $language->id }}"
                                                data-code="{{ $language->language_code }}"
                                                data-img="{{ \Illuminate\Support\Facades\URL::asset($language->language_flag) }}"
                                                @if (config('stt.vendor_logos') == 'show') data-vendor="{{ \Illuminate\Support\Facades\URL::asset($language->vendor_img) }}"
                                            @endif

                                        > {{ $language->language }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="database-columns">{{ __('Select Group:') }}</label>
                                <select class="form-control" name="group" id="group">
                                    <option value="">Select Group</option>
                                    <option value="admin">Admin</option>
                                    <option value="user">User</option>
                                    <option value="accounts">Accounts</option>
                                    <option value="quality_assurance">Quality Assurance</option>
                                </select>
                            </div>
                            {{--                            <div class="form-group col-md-2">--}}
                            {{--                                <label for="startDateTime">{{ __('City:') }}</label>--}}
                            {{--                                <input type="text" id="city_name" class="form-control">--}}
                            {{--                            </div>--}}
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
                                <th width="2%"><input type="checkbox" id="check-all"></th>
                                <th width="13%">{{ __('User') }}</th>
                                <th width="9%">{{ __('Group') }}</th>
                                <th width="7%">{{ __('Country') }}</th>
                                <th width="5%">{{ __('Status') }}</th>
                                <th width="5%">{{ __('Registered On') }}</th>
                                <th width="9%">{{ __('Last Seen') }}</th>
                                <th width="8%">{{ __('Actions') }}</th>
                            </tr>
                            </thead>
                        </table>
                        <!-- END DATATABLE -->

                    </div> <!-- END BOX CONTENT -->
                </div>
            </div>
        </div>
        <div class="modal" tabindex="-1" id="myModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">User List</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="modal-form">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Name">
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea class="form-control" id="description" name="description"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="save" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END USERS LIST DATA TABEL -->
@endsection

@section('js')
    <!-- Awselect JS -->
    <script src="{{URL::asset('plugins/awselect/awselect-custom.js')}}"></script>
    <script src="{{URL::asset('js/awselect.js')}}"></script>
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
                    url: "{{ route('admin.user.list') }}",
                    data: function (d) {
                        d.created_on_from = $('#created_on_from').val();
                        d.created_on_to = $('#created_on_to').val();
                        d.ageG = $('#ageG').val();
                        d.family = $('#family').val();
                        d.country = $('#country').val();
                        d.database_columns = $('#database_columns').val();
                        d.group = $('#group').val();
                        d.languages = $('#languages').val();
                        d.ageL = $('#ageL').val();
                        // d.city = $('#city_name').val();
                        // add any other filters you want to pass to the backend here
                    }
                },
                columns: [
                    {
                        data: 'checkbox',
                        name: 'checkbox',
                        orderable: false,
                        searchable: false,
                        render: function (data, type, row) {
                            return '<input type="checkbox" class="select-checkbox" value="' + row.id + '">';
                        }
                    },
                    {
                        data: 'user',
                        name: 'user',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'custom-group',
                        name: 'custom-group',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'custom-country',
                        name: 'custom-country',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'custom-status',
                        name: 'custom-status',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'created-on',
                        name: 'created-on',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'last-seen-on',
                        name: 'last-seen-on',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

            // add a change event listener to the date filter inputs
            $('#created_on_from, #created_on_to, #database_columns, #group, #family, #country, #ageG,#ageL,#languages').on('change', function () {
                table.draw();
            });
            var selected = {};  // This should be declared in the global scope

// Handle click on checkbox to set state of "Select all" control
            $('#listUsersTable tbody').on('change', 'input[type="checkbox"]', function () {
                // If checkbox is not checked
                if (!this.checked) {
                    var el = $('#check-all').get(0);
                    // If "Select all" control is checked and has 'indeterminate' property
                    if (el && el.checked && ('indeterminate' in el)) {
                        // Set visual state of "Select all" control as 'indeterminate'
                        el.indeterminate = true;
                    }
                }

                // Keep track of the selected checkboxes
                selected[$(this).val()] = $(this).prop('checked');
            });

// Handle click on "Select all" control
            $('#check-all').on('click', function(){
                var isChecked = $(this).is(':checked');

                // Get all user IDs
                table.rows({ 'search': 'applied' }).every(function(rowIdx, tableLoop, rowLoop) {
                    var data = this.data();
                    selected[data.id] = isChecked;
                });

                // Update checkbox status in current page
                updateCheckboxStatus();
            });
            function updateCheckboxStatus() {
                table.rows({ 'search': 'applied' }).every(function(rowIdx, tableLoop, rowLoop) {
                    var data = this.data();
                    var checkbox = this.node().querySelector('input[type="checkbox"]');
                    checkbox.checked = !!selected[data.id];
                });
            }


// Handle checkbox click event
            $('#listUsersTable').on('click', '.select-checkbox', function() {
                var id = $(this).val();

                if ($(this).is(':checked')) {
                    selected[id] = true;
                } else {
                    // if unchecked, remove from the selected array
                    delete selected[id];
                }
            });

// when save button is clicked
            $('#save').on('click', function() {
                var name        = $('#name').val();
                var description = $('#description').val();

                // selected users
                var users = [];
                $.each(selected, function(key, value) {
                    if (value === true) {
                        users.push(key);
                    }
                });

                // AJAX call to your server
                $.ajax({
                    url: "{{ route('admin.mailing.system.user.store') }}",
                    method: "POST",
                    data: {
                        name: name,
                        description: description,
                        users: users,
                        _token: "{{ csrf_token() }}" // include CSRF token if your app uses it
                    },
                    success: function(response) {
                        // Handle successful response here
                        console.log(response);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        // Handle error response here
                        console.error(textStatus, errorThrown);
                    }
                });

                // clear form and selection
                $('#modal-form')[0].reset();
                selected = {};
                $('#myModal').modal('hide');
            });


            table.on('draw', function () {
                updateCheckboxStatus();
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
