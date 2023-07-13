@extends('layouts.app')

@section('css')
    <!-- Data Table CSS -->
    <link href="{{URL::asset('plugins/awselect/awselect.min.css')}}" rel="stylesheet"/>
    <!-- Telephone Input CSS -->
    <link href="{{URL::asset('plugins/telephoneinput/telephoneinput.css')}}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet"/>
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet"/>
    <!-- CKEditor Stylesheets -->
    <link rel="stylesheet" href="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.css">
    <!-- Sweet Alert CSS -->
    <link href="{{ URL::asset('plugins/sweetalert/sweetalert2.min.css') }}" rel="stylesheet" />
    <!-- Toastr CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />

@endsection

@section('page-header')
    <!-- EDIT PAGE HEADER -->
    <div class="page-header mt-5-7">
        <div class="page-leftheader">
            <h4 class="page-title mb-0">{{ __('Create New User') }}</h4>
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i
                            class="fa-solid fa-user-shield mr-2 fs-12"></i>{{ __('Admin') }}</a></li>
                <li class="breadcrumb-item" aria-current="page"><a
                        href="#"> {{ __('Mail System') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a
                        href="{{ route('admin.mailing.system.campaign.index') }}">{{ __('Campaign') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a
                        href="{{ route('admin.mailing.system.campaign.index') }}">{{ __('Create User List') }}</a></li>

            </ol>
        </div>
    </div>
    <!-- END PAGE HEADER -->
@endsection

@section('content')
    <!-- EDIT USER PROFILE PAGE -->
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-sm-12">
            <div class="card border-0">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Create New Campaign') }}</h3>
                </div>
                <div class="card-body pb-20">
                    <div class="row">
                        <div class="col-sm-12 col-md-12">
                            <div class="col-sm-6 col-md-6">
                                <div class="input-box">
                                    <div class="form-group">
                                        <label class="form-label fs-12">{{ __('Name') }}</label>
                                        <input type="email" class="form-control" name="name"
                                               id="name">
                                    </div>
                                </div>
                            </div>
                                <div class="col-sm-12 col-md-12">
                                    <div class="row">
                                        <div class="col-sm-6 col-md-6">
                                            <div class="input-box">
                                                <div class="form-group">
                                                    <label class="form-label fs-12">{{ __('User List Name') }} <span
                                                            class="text-muted">({{ __('Required') }})</span></label>
                                                    <select class="form-control awselect" name="list" id="userList"
                                                            required>
                                                        @foreach($userLists as $list)
                                                            <option value="{{ $list->id }}">{{ $list->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-4">
                                            <div class="input-box">
                                                <div class="form-group">
                                                    <label class="form-label fs-12">{{ __('Preview Email') }}</label>
                                                    <input type="email" class="form-control" name="preview_email"
                                                           id="preview_email">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-2">
                                            <button type="button" class="btn btn-primary mt-4"
                                                    id="previewEmailBtn">{{ __('Preview Email') }}</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-12">
                                    <div class="input-box">
                                        <div class="form-group">
                                            <label class="form-label fs-12">{{ __('Mail Body') }} <span
                                                    class="text-muted">({{ __('Required') }})</span></label>
                                            <textarea class="form-control ckeditor" id="description"
                                                      name="description"></textarea>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-12">
                                    <div class="input-box">
                                        <div class="form-group">
                                            <label class="form-label fs-12">{{ __('Footer') }} <span
                                                    class="text-muted">({{ __('Required') }})</span></label>
                                            <textarea class="form-control ckeditor" id="footer"
                                                      name="footer"></textarea>

                                        </div>
                                    </div>
                                </div>
                                <button id="submitCampaign" class="btn btn-primary">{{ __('Submit') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <!-- Awselect JS -->
    <script src="{{URL::asset('plugins/awselect/awselect.min.js')}}"></script>
    <script src="{{URL::asset('js/awselect.js')}}"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <!-- CKEditor Scripts -->
    <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
    <!-- Sweet Alert JS -->
    <script src="{{ URL::asset('plugins/sweetalert/sweetalert2.all.min.js') }}"></script>
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


    <script>
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        $(document).ready(function () {
            $('#userSelect').select2({
                width: 'resolve', // add this line
            });
            CKEDITOR.replace('description');
            CKEDITOR.replace('footer');

            $('#previewEmailBtn').click(function () {
                // Get the CKEditor value
                var description = CKEDITOR.instances.description.getData();
                var preview_email = $('#preview_email').val()
                var footer      = CKEDITOR.instances.footer.getData();

                // Send the value to the controller via AJAX
                $.ajax({
                    url: "{{ route('admin.mailing.system.campaign.preview.email') }}",
                    type: "POST",
                    headers: {
                        "X-CSRF-TOKEN": csrfToken
                    },
                    data: {
                        description: description,
                        preview_email: preview_email,
                        footer: footer,
                        // Other data if needed
                    },
                    success: function (response) {
                        // Handle the response
                        // This function will be executed when the AJAX request is successful
                    },
                    error: function (xhr, status, error) {

                    }
                });
            });
            $('#submitCampaign').click(function() {
                // Get the CKEditor value
                var description = CKEDITOR.instances.description.getData();
                var footer      = CKEDITOR.instances.footer.getData();
                var userList = $('#userList').val();
                var name = $('#name').val();

                // Show the confirmation dialog
                Swal.fire({
                    title: '{{ __('Confirm Campaign Submission') }}',
                    text: '{{ __('Are you sure you want to submit this campaign?') }}',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: '{{ __('Submit') }}',
                    reverseButtons: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Confirmation dialog confirmed, proceed with AJAX request
                        $.ajax({
                            url: "{{ route('admin.mailing.system.campaign.store') }}",
                            type: "POST",
                            headers: {
                                "X-CSRF-TOKEN": csrfToken
                            },
                            data: {
                                description: description,
                                name: name,
                                userList: userList,
                                footer: footer,
                                // Other data if needed
                            },
                            success: function(response) {
                                // Handle the response
                                // This function will be executed when the AJAX request is successful
                                toastr.success('Campaign mail send Successfully !');
                                window.location.href = '/admin/mailing-system-campaign';


                            },
                            error: function(xhr, status, error) {
                                Swal.fire({
                                    type: 'error',
                                    title: 'Oops...',
                                    text: '{{ __('Something went wrong') }}!'
                                })
                            }
                        });
                    }
                });
            });

        });


    </script>
@endsection
