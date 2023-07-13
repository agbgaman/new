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
            <h4 class="page-title mb-0">{{ __('Edit Folder') }}</h4>
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-user-shield mr-2 fs-12"></i>{{ __('Admin') }}</a></li>
                {{--                <li class="breadcrumb-item" aria-current="page"><a href="{{ route('admin.user.dashboard') }}"> {{ __('User Management') }}</a></li>--}}
                <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.images.folder') }}"> {{ __('User Images Folder') }}</a></li>

            </ol>
        </div>
    </div>
    <!-- END PAGE HEADER -->
@endsection

@section('content')
    <style>
        .select2-selection__rendered {
            background-color: #F5F9FC;
        }
    </style>
    <!-- EDIT USER PROFILE PAGE -->
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-sm-12">
            <div class="card border-0">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Edit Folder') }}</h3>
                </div>
                <div class="card-body pb-0">
                    <form method="POST" action="{{ route('admin.images.folder.update',['id'=>$folder->id]) }}" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-sm-6 col-md-6">
                                <div class="input-box">
                                    <div class="form-group">
                                        <label class="form-label fs-12">{{ __('Name') }} <span class="text-muted">({{ __('Required') }})</span></label>
                                        <input type="text" class="form-control @error('name') is-danger @enderror" name="name" value="{{$folder->name}}" required>
                                        @error('name')
                                        <p class="text-danger">{{ $errors->first('name') }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <div class="input-box">
                                    <label class="form-label fs-12">{{ __('Assign User') }} <span class="text-muted">({{ __('Required') }})</span></label>
                                    <select id="usser" name="assign_user_id" data-placeholder="{{ __('Select user') }} " class="form-control"
                                            style="background: #f5f9fc !important;  color: #1e1e2d; border-radius: 0.5rem; padding: 10px 20px !important;">
                                        <option value="">{{ __('Select user') }}</option>
                                        @foreach($users as $user)
                                        <option value={{$user->id}} @if($user->id == $folder->assign_user_id) selected @endif>{{$user->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <div class="input-box">
                                    <label class="form-label fs-12">{{ __('Select language') }} <span class="text-muted">({{ __('Required') }})</span></label>
                                    <select id="languages" name="language" data-placeholder="{{ __('Select your language') }}">
                                        @foreach ($languages as $language)
                                            <option value="{{ $language->id }}" data-code="{{ $language->language_code }}" data-img="{{ \Illuminate\Support\Facades\URL::asset($language->language_flag) }}" @if (config('stt.vendor_logos') == 'show') data-vendor="{{ \Illuminate\Support\Facades\URL::asset($language->vendor_img) }}" @endif @if ($folder->language_id == $language->id) selected @endif> {{ $language->language }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <div class="input-box">
                                    <label class="form-label fs-12">{{ __('Status') }} <span class="text-muted">({{ __('Required') }})</span></label>
                                    <select id="featured" name="status" data-placeholder="{{ __('Select status') }} ">
                                        <option value=active>{{ __('Active') }}</option>
                                        <option value=inactive>{{ __('In Active') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <div class="input-box">
                                    <label class="form-label fs-12">{{ __('Assign User for QC') }} <span class="text-muted">({{ __('Required') }})</span></label>
                                    <select id="quality_assurance" name="quality_assurance_id" data-placeholder="{{ __('Select user for QC') }} " class="form-control"
                                            style="background: #f5f9fc !important;  color: #1e1e2d; border-radius: 0.5rem; padding: 10px 20px !important;">
                                        @foreach($quality_assurance_users as $user)
                                            <option value={{$user->id}} @if($user->id == $folder->quality_assurance_id) selected @endif>{{$user->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <div class="input-box">
                                    <label class="form-label fs-12">{{ __('Select Project') }} <span class="text-muted">({{ __('Required') }})</span></label>
                                    <select id="project_id" name="project_id" data-placeholder="{{ __('Select Project') }} " class="form-control"
                                            style="background: #f5f9fc !important;  color: #1e1e2d; border-radius: 0.5rem; padding: 10px 20px !important;">
                                        @foreach($projects as $project)
                                            <option value={{$project->id}} @if($project->id == $folder->project_id) selected @endif>{{$project->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="card-footer border-0 text-right mb-2 pr-0">
                                <a href="{{ route('admin.images.folder') }}" class="btn btn-cancel mr-2">{{ __('Return') }}</a>
                                <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
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

    <!-- Telephone Input JS -->
    <script src="{{URL::asset('plugins/telephoneinput/telephoneinput.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#usser').select2();
            $('#quality_assurance').select2();
            $('#project_id').select2();
        });

        $(function() {
            "use strict";

            $("#phone-number").intlTelInput();
        });
    </script>
@endsection
