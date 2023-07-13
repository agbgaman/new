@extends('layouts.app')

@section('css')
    <!-- Data Table CSS -->
    <link href="{{URL::asset('plugins/awselect/awselect.min.css')}}" rel="stylesheet" />
    <!-- Telephone Input CSS -->
    <link href="{{URL::asset('plugins/telephoneinput/telephoneinput.css')}}" rel="stylesheet" >
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet"/>
@endsection

@section('page-header')
    <!-- EDIT PAGE HEADER -->
    <div class="page-header mt-5-7">
        <div class="page-leftheader">
            <h4 class="page-title mb-0">{{ __('Upload CSV') }}</h4>
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-user-shield mr-2 fs-12"></i>{{ __('Admin') }}</a></li>
                {{--                <li class="breadcrumb-item" aria-current="page"><a href="{{ route('admin.user.dashboard') }}"> {{ __('User Management') }}</a></li>--}}
                <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.csv.index') }}"> {{ __('Upload CSV') }}</a></li>

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
                    <h3 class="card-title">{{ __('Upload CSV') }}</h3>
                </div>
                <div class="card-body pb-0">
                    <form method="POST" action="{{ route('admin.csv.store') }}" id="my-form" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-sm-6 col-md-6">
                                <div class="input-box">
                                    <div class="form-group">
                                        <label class="form-label fs-12">{{ __('CSV File') }} <span class="text-muted">({{ __('Required') }})</span></label>
                                        <input type="file" accept=".csv" onclick="fillInput(event)" data-value="CSV" class="form-control @error('image') is-danger @enderror" name="csv" multiple value="{{ old('image') }}" required>
                                        @error('image')
                                        <p class="text-danger">{{ $errors->first('image') }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <div class="input-box">
                                    <div class="form-group">
                                        <label class="form-label fs-12">{{ __('Name') }} <span class="text-muted">({{ __('Required') }})</span></label>
                                        <input type="text" id="CSVName" class="form-control @error('name') is-danger @enderror" name="name" value="{{ old('name') }}" required>
                                        @error('name')
                                        <p class="text-danger">{{ $errors->first('name') }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>


                            <div class="col-sm-6 col-md-6">
                                <div class="input-box">
                                    <label class="form-label fs-12">{{ __('Folder') }} <span class="text-muted">({{ __('Required') }})</span></label>
                                    <select class="form-control" id="folder" name="folder_id"
                                            data-placeholder="{{ __('Select Folder') }} "
                                            style="background: #f5f9fc !important;  color: #1e1e2d; border-radius: 0.5rem; padding: 10px 20px !important;">
                                        @foreach($folders as $folders)
                                            <option value={{$folders->id}}>{{$folders->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <div class="input-box">
                                    <label class="form-label fs-12">{{ __('Status') }} <span class="text-muted">({{ __('Required') }})</span></label>
                                    <select id="user-country" name="status" data-placeholder="{{ __('Select status') }} ">
                                        <option value=active>{{ __('Active') }}</option>
                                        <option value=inactive>{{ __('In Active') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="card-footer border-0 text-right mb-2 pr-0">
                                <a href="{{ route('admin.images.folder') }}" class="btn btn-cancel mr-2">{{ __('Return') }}</a>
                                <button type="submit" id="submit-button" class="btn btn-primary">{{ __('Create') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- EDIT USER PROFILE PAGE -->
@endsection

@section('js')
    <!-- Awselect JS -->
    <script src="{{URL::asset('plugins/awselect/awselect.min.js')}}"></script>
    <script src="{{URL::asset('js/awselect.js')}}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/compressorjs/1.1.1/compressor.min.js"></script>
    <!-- Telephone Input JS -->
    <script src="{{URL::asset('plugins/telephoneinput/telephoneinput.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>


    <script>
        $(document).ready(function () {
            $('#folder').select2();
        });

        function fillInput(event) {
            // Get the value of the selected image
            let value = event.target.getAttribute("data-value");
            // Set the value of the input field
            document.getElementById("CSVName").value = value;
        }

    </script>
@endsection
