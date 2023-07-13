@extends('layouts.app')

@section('css')
    <!-- Awselect CSS -->
    <link href="{{URL::asset('plugins/awselect/awselect.min.css')}}" rel="stylesheet"/>
    <!-- Telephone Input CSS -->
    <link href="{{URL::asset('plugins/telephoneinput/telephoneinput.css')}}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet"/>

@endsection

@section('page-header')

    <!-- EDIT PAGE HEADER -->
    <div class="page-header mt-5-7">
        <div class="page-leftheader">
            <h4 class="page-title mb-0">{{ __('Update Personal Information') }}</h4>
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{route('user.dashboard')}}"><i
                            class="fa-solid fa-chart-tree-map mr-2 fs-12"></i>{{ __('User') }}</a></li>
                <li class="breadcrumb-item" aria-current="page"><a href="{{url('#')}}"> {{ __('My Dashboard') }}</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page"><a
                        href="{{url('#')}}"> {{ __('Edit Profile') }}</a></li>
            </ol>
        </div>
    </div>
    <!-- END PAGE HEADER -->
@endsection

@section('content')
    <style>
        #languages1 + .select2 .select2-selection {
            padding: 10px 20px !important;
            background-color: #F5F9FC !important;
        }

        #languages1 + .select2 .select2-selection__choice {
            background-color: #b3d4fc !important;
            border: none;
            border-radius: 4px;
            color: #1967d2 !important;
            font-size: 12px;
            padding: 2px 4px;
            margin-right: 4px;
        }

        #languages1 + .select2 .select2-selection__choice__remove {
            margin-left: 4px;
        }

        #languages1 + .select2 .select2-results__options {
            max-height: 200px;
            overflow-y: scroll;
        }

        #languages1 + .select2 .select2-selection__rendered {
            max-height: 25px;
            overflow-y: auto !important;
        }

        #languages1 + .select2 .select2-results__option {
            font-size: 12px;
            padding: 2px 4px;
        }

    </style>
    <!-- EDIT USER PROFILE PAGE -->
    @if(auth()->user()->country != "China")
        @if(auth()->user()->phone_number_verified_at == null)
            <div class="banner">
                <p>Please verify your phone number<a href="{{route('verify-phone-number')}}" class="btn btn-primary"
                                                     style="margin-left: 10px"> {{ __('Verify') }} </a></p>
            </div>
        @endif
    @endif
    <div class="row">
        <div class="col-xl-3 col-lg-4 col-sm-12">
            <div class="card border-0" id="dashboard-background">
                <div class="widget-user-image overflow-hidden mx-auto mt-5"><img alt="User Avatar"
                                                                                 class="rounded-circle"
                                                                                 src="@if(auth()->user()->profile_photo_path){{ asset(auth()->user()->profile_photo_path) }} @else {{ URL::asset('img/users/avatar.jpg') }} @endif">
                </div>
                <div class="card-body text-center">
                    <div>
                        <h4 class="mb-1 mt-1 font-weight-bold fs-16">{{ auth()->user()->name }}</h4>
                        {{--                        <h6 class="text-muted fs-12">{{ auth()->user()->job_role }}</h6>--}}
                        <a href="{{ route('user.dashboard') }}"
                           class="btn btn-primary mt-3 mb-2">{{ __('View Dashboard') }}</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-9 col-lg-8 col-sm-12">
            <form method="POST" class="w-100" action="{{ route('user.dashboard.update', [auth()->user()->id]) }}"
                  enctype="multipart/form-data">
                @method('PUT')
                @csrf

                <div class="card border-0">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('Edit Profile') }}</h3>
                    </div>
                    <div class="card-body pb-0">
                        <div class="row">
                            <div class="col-sm-6 col-md-6">
                                <div class="input-box">
                                    <div class="form-group">
                                        <label class="form-label fs-12">{{ __('Full Name') }}</label>
                                        <input type="text" class="form-control @error('name') is-danger @enderror"
                                               name="name" value="{{ auth()->user()->name }}">
                                        @error('name')
                                        <p class="text-danger">{{ $errors->first('name') }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            {{--                            <div class="col-sm-6 col-md-6">--}}
                            {{--                                <div class="input-box">--}}
                            {{--                                    <div class="form-group">--}}
                            {{--                                        <label class="form-label fs-12">{{ __('Job Role') }}</label>--}}
                            {{--                                        <input type="text" class="form-control @error('job_role') is-danger @enderror"--}}
                            {{--                                               name="job_role" value="{{ auth()->user()->job_role }}">--}}
                            {{--                                        @error('job_role')--}}
                            {{--                                        <p class="text-danger">{{ $errors->first('job_role') }}</p>--}}
                            {{--                                        @enderror--}}
                            {{--                                    </div>--}}
                            {{--                                </div>--}}
                            {{--                            </div>--}}
                            <div class="col-sm-6 col-md-6">
                                <div class="input-box">
                                    <div class="form-group">
                                        <label class="form-label fs-12">{{ __('Email Address') }}</label>
                                        <input type="email" class="form-control @error('email') is-danger @enderror"
                                               name="email" value="{{ auth()->user()->email }}">
                                        @error('email')
                                        <p class="text-danger">{{ $errors->first('email') }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-6">
                                <div class="input-box">
                                    <div class="form-group">
                                        <label class="form-label fs-12">{{ __('Phone Number') }}</label>
                                        <div class="input-group">
                                            <input type="tel"
                                                   class="fs-12 form-control @error('phone_number') is-danger @enderror"
                                                   id="phone-number" name="phone_number"
                                                   value="{{ auth()->user()->phone_number }}">
                                            @if(auth()->user()->country != "China")
                                                @if(auth()->user()->phone_number_verified_at == null)
                                                    <a href="{{route('verify-phone-number')}}" type="button"
                                                       id="verify-button" class="btn btn-primary">{{ __('Verify') }}</a>
                                                @else
                                                    <a href="#" type="button" disabled id="verify-button"
                                                       class="btn btn-success">{{ __('Verified') }}</a>
                                                @endif
                                            @endif
                                        </div>
                                        @error('phone_number')
                                        <p class="text-danger">{{ $errors->first('phone_number') }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <script>
                                // Get the phone number input field and the verify button
                                var phoneNumberInput = document.getElementById('phone-number');
                                var verifyButton = document.getElementById('verify-button');

                                // Add a click event listener to the verify button
                                verifyButton.addEventListener('click', function () {
                                    // TODO: Add code to trigger phone number verification process
                                });
                            </script>

                            <div class="col-sm-6 col-md-6">
                                <div class="input-box">
                                    <label class="form-label fs-12">{{ __('Profile') }}</label>
                                    <div class="input-group file-browser">
                                        <input type="text" class="form-control border-right-0 browse-file"
                                               placeholder="choose" style="margin-right: 80px;" readonly>
                                        <label class="input-group-btn">
											<span class="btn btn-primary special-btn">
												{{ __('Browse') }} <input type="file" name="profile_photo"
                                                                          style="display: none;">
											</span>
                                        </label>
                                    </div>
                                    @error('profile_photo')
                                    <p class="text-danger">{{ $errors->first('profile_photo') }}</p>
                                    @enderror
                                </div>
                            </div>
                            @php
                                if(auth()->user()->language == 'en-US') {
                                    $selected_languages = null;
                                } else {
                                    $selected_languages = json_decode(auth()->user()->language, true);
                                }
                            @endphp
                            <div class="col-sm-6 col-md-6">
                                <div class="input-box">
                                    <label class="form-label fs-12">{{ __('Known languages') }} <span
                                            class="text-muted">({{ __('Required') }})</span></label>
                                    <select id="languages1" name="language[]" multiple class="select2"
                                            data-placeholder="{{ __('Select your languages') }}"
                                    >
                                        @foreach ($languages as $language)
                                            <option value="{{ $language->id }}"
                                                    data-code="{{ $language->language_code }}"
                                                    data-img="{{ \Illuminate\Support\Facades\URL::asset($language->language_flag) }}"
                                                    @if (config('stt.vendor_logos') == 'show') data-vendor="{{ \Illuminate\Support\Facades\URL::asset($language->vendor_img) }}"
                                                    @endif
                                                    @if($selected_languages) @if (in_array($language->id,  $selected_languages)) selected @endif @endif
                                            > {{ $language->language }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            {{--							<div class="col-sm-6 col-md-6">--}}
                            {{--								<div class="input-box">--}}
                            {{--									<div class="form-group">--}}
                            {{--										<label class="form-label fs-12">{{ __('Company Name') }}</label>--}}
                            {{--										<input type="text" class="form-control @error('company') is-danger @enderror" name="company" value="{{ auth()->user()->company }}">--}}
                            {{--										@error('company')--}}
                            {{--											<p class="text-danger">{{ $errors->first('company') }}</p>--}}
                            {{--										@enderror--}}
                            {{--									</div>--}}
                            {{--								</div>--}}
                            {{--							</div>--}}
                            {{--							<div class="col-sm-6 col-md-6">--}}
                            {{--								<div class="input-box">--}}
                            {{--									<div class="form-group">--}}
                            {{--										<label class="form-label fs-12">{{ __('Company Website') }}</label>--}}
                            {{--										<input type="text" class="form-control @error('website') is-danger @enderror" name="website" value="{{ auth()->user()->website }}">--}}
                            {{--										@error('website')--}}
                            {{--											<p class="text-danger">{{ $errors->first('website') }}</p>--}}
                            {{--										@enderror--}}
                            {{--									</div>--}}
                            {{--								</div>--}}
                            {{--							</div>--}}
                            <div class="col-md-12">
                                <div class="input-box">
                                    <div class="form-group">
                                        <label class="form-label fs-12">{{ __('Address Line') }}</label>
                                        <input type="text" class="form-control @error('address') is-danger @enderror"
                                               name="address" value="{{ auth()->user()->address }}">
                                        @error('address')
                                        <p class="text-danger">{{ $errors->first('address') }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <div class="input-box">
                                    <div class="form-group">
                                        <label class="form-label fs-12">{{ __('City') }}</label>
                                        <input type="text" class="form-control @error('city') is-danger @enderror"
                                               name="city" value="{{ auth()->user()->city }}">
                                        @error('city')
                                        <p class="text-danger">{{ $errors->first('city') }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <div class="input-box">
                                    <div class="form-group">
                                        <label class="form-label fs-12">{{ __('Postal Code') }}</label>
                                        <input type="text"
                                               class="form-control @error('postal_code') is-danger @enderror"
                                                   name="postal_code" value="{{ auth()->user()->postal_code }}">
                                        @error('postal_code')
                                        <p class="text-danger">{{ $errors->first('postal_code') }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label fs-12">{{ __('Country') }}</label>
                                    <select id="user-country" name="country" data-placeholder="Select Your Country:">
                                        @foreach(config('countries') as $value)
                                            <option value="{{ $value }}"
                                                    @if(auth()->user()->country == $value) selected @endif>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('country')
                                    <p class="text-danger">{{ $errors->first('country') }}</p>
                                    @enderror
                                </div>
                            </div>
                            @php
                                $timezones = DateTimeZone::listIdentifiers();
                            @endphp
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label fs-12">{{ __('Time Zone') }}</label>
                                    <select id="user-specific-time" name="timezone"
                                            data-placeholder="Select Your Country:">
                                        @foreach($timezones as $timezone)
                                            <option @if($timezone == auth()->user()->timezone) selected @endif
                                            value="{{$timezone}}">{{$timezone}}</option>
                                        @endforeach
                                    </select>
                                    @error('country')
                                    <p class="text-danger">{{ $errors->first('country') }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                            <div class="card-footer border-0 text-right mb-2 pr-0">
                                <a href="{{ route('user.dashboard') }}" class="btn btn-cancel mr-2">{{ __('Return') }}</a>
                                <button type="submit" id="update-button" class="btn btn-primary">{{ __('Update') }}</button>
                            </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- EDIT USER PROFILE PAGE -->
@endsection

@section('js')
    <!-- Awselect JS -->
    <script src="{{URL::asset('plugins/awselect/awselect-custom.js')}}"></script>
    <script src="{{URL::asset('js/awselect.js')}}"></script>
    <!-- File Uploader -->
    <script src="{{URL::asset('js/avatar.js')}}"></script>
    <!-- Telephone Input JS -->
    <script src="{{URL::asset('plugins/telephoneinput/telephoneinput.js')}}"></script>
    <!-- Add the Select2 JavaScript file -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    {{--    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>--}}
    <script>
        $(function () {
            "use strict";

            $("#phone-number").intlTelInput();
        });
        $(document).ready(function () {

            $('#languages1').select2({
                tags: true,
                tokenSeparators: [',', ' '], // Allow tags to be separated by comma or space
                templateResult: formatOption, // Customize how options are displayed
                placeholder: "Select Languages",
            });
            $('#user-specific-time').select2();


            // Custom function to format option text and icon
            function formatOption(option) {
                if (!option.id) {
                    return option.text;
                }
                var $option = $('<span></span>');
                if (option.data && option.data.img) {
                    $option.append('<img class="select2-option-img" src="' + option.data.img + '">');
                }
                $option.append(document.createTextNode(' ' + option.text));
                return $option;
            }

            // function formatLanguage(language) {
            //     if (!language.id) {
            //         return language.text;
            //     }
            //
            //     var languageCode = $(language.element).data('code');
            //     var languageImg = $(language.element).data('img');
            //
            //     var markup = "<span><img src='" + languageImg + "' alt='" + languageCode + "' class='mr-2' width='15' />";
            //     markup += language.text + "</span>";
            //
            //     return markup;
            // }
            //
            // function formatLanguageSelection(language) {
            //     if (language.id === "") {
            //         return "";
            //     }
            //
            //     var languageImg = $(language.element).data('img');
            //
            //     var markup = "<span><img src='" + languageImg + "' alt='" + language.text + "' class='mr-2' width='15' />";
            //     markup += language.text + "</span>";
            //     return markup;
            // }
        });

        // Get the phone number input field and the verify button
        var phoneNumberInput = document.getElementById('phone-number');
        var verifyButton = document.getElementById('verify-button');

        // Add a click event listener to the verify button
        verifyButton.addEventListener('click', function () {
            // Get the phone number value
            var phoneNumber = phoneNumberInput.value;

            // Send a POST request to the phone number verification route
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '{{ route('verify-phone-number') }}');
            xhr.setRequestHeader('Content-Type', 'application/json');
            xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
            xhr.onload = function () {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    // Redirect to the verification page
                    window.location.href = response.redirect_url;
                } else {
                    // TODO: Handle verification error
                }
            };
            xhr.onerror = function () {
                // TODO: Handle network error
            };
            xhr.send(JSON.stringify({phone_number: phoneNumber}));
        });

    </script>
@endsection
