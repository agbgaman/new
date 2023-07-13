@extends('layouts.app')

@section('css')
    <!-- Data Table CSS -->
    <link href="{{URL::asset('plugins/awselect/awselect.min.css')}}" rel="stylesheet"/>
    <!-- Telephone Input CSS -->
    <link href="{{URL::asset('plugins/telephoneinput/telephoneinput.css')}}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet"/>
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet"/>

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
                        href="{{ route('admin.mailing.system.index') }}">{{ __('User List') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a
                        href="{{ route('admin.user.create') }}">{{ __('Create User List') }}</a></li>

            </ol>
        </div>
    </div>
    <!-- END PAGE HEADER -->
@endsection

@section('content')
    <style>
        .select2 .select2-selection {
            padding: 10px 20px !important;
            background-color: #F5F9FC !important;
        }

        .select2 .select2-selection__choice {
            background-color: #b3d4fc !important;
            border: none;
            border-radius: 4px;
            color: #1967d2 !important;
            font-size: 12px;
            padding: 2px 4px;
            margin-right: 4px;
        }

        .select2-container .select2-selection--multiple {
            min-height: 100px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            height: auto;
            padding-top: 2px;
            padding-bottom: 2px;
        }

    </style>

    <!-- EDIT USER PROFILE PAGE -->
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-sm-12">
            <div class="card border-0">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Create New User List') }}</h3>
                </div>
                <div class="card-body pb-20">
                    <form action="{{route('admin.mailing.system.user.store')}}" method="POST">
                        @csrf
                        <div class="col-sm-12 col-md-12">
                            <div class="col-sm-6 col-md-6">
                                <div class="input-box">
                                    <div class="form-group">
                                        <label class="form-label fs-12">{{ __('List Name') }} <span class="text-muted">({{ __('Required') }})</span></label>
                                        <input type="text" class="form-control" name="name" value="{{ old('name') }}"
                                               required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="card-header justify-content-between">

                                    <h3 class="card-title"></h3>

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
                                                <div class="form-group col-md-1">
                                                    <label for="startDateTime">{{ __('Age:') }}</label>
                                                    <input type="number" id="age" class="form-control">
                                                </div>
                                                <div class="form-group col-md-1">
                                                    <label for="startDateTime">{{ __('Family M:') }}</label>
                                                    <input type="number" id="family" class="form-control">
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label for="database-columns">{{ __('Select Country:') }}</label>
                                                    <select class="form-control" name="country" id="country">
                                                        <option value="">Select Country</option>
                                                        @foreach(config('countries') as $value)
                                                            <option value="{{ $value }}">{{ $value }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label for="database-columns">{{ __('Select Columns:') }}</label>
                                                    <select class="form-control" name="database-columns"
                                                            id="database_columns">
                                                        <option value="created_at">Register On</option>
                                                        <option value="last_seen">Last Seen</option>
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
                                                <div class="form-group col-md-2">
                                                    <label for="startDateTime">{{ __('Start:') }}</label>
                                                    <input type="datetime-local" id="created_on_from"
                                                           class="form-control">
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label for="endDateTime">{{ __('End:') }}</label>
                                                    <input type="datetime-local" id="created_on_to"
                                                           class="form-control">
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
                                                        >{{ $language->language }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-12 col-md-6">
                                <label for="userSelect">{{ __('Select User:') }}</label>
                                <select class="form-control" id="userSelect" name="users[]" multiple="multiple">
                                    <!-- Option elements go here -->
                                </select>
                            </div>
                        </div>
                        <div class="card-footer border-0 text-right mb-2 pr-0">
                            <a href="{{ route('admin.mailing.system.index') }}"
                               class="btn btn-cancel mr-2">{{ __('Return') }}</a>
                            <button type="submit" id="update-button" class="btn btn-primary">{{ __('Save') }}</button>
                        </div>
                    </form>
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
    <script>
        $(document).ready(function () {
            $('#userSelect').select2({
                width: 'resolve', // add this line

                ajax: {
                    url: '/admin/mailing-system-fetch-users',
                    delay: 250, // wait 250 milliseconds before triggering the request
                    dataType: 'json',
                    data: function (params) {
                        var query = {
                            q: params.term, // Select2 uses 'term' by default
                            start_date: $('#created_on_from').val(),
                            end_date: $('#created_on_to').val(),
                            columns: $('#database_columns').val(),
                            age: $('#age').val(),
                            family: $('#family').val(),
                            country: $('#country').val(),
                            group: $('#group').val(),
                            languages: $('#languages').val()
                            // Add more filters here if needed
                        }

                        return query;
                    },
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (user) {
                                return {
                                    text: user.name + " (" + user.email + ")", // Adding email here
                                    id: user.id
                                }
                            })
                        };
                    }
                }
            });
        });


    </script>
@endsection
