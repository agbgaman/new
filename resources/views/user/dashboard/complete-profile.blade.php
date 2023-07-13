@extends('layouts.app')

@section('css')
    <!-- Awselect CSS -->
    <link href="{{URL::asset('plugins/awselect/awselect.min.css')}}" rel="stylesheet"/>
    <!-- Telephone Input CSS -->
    <link href="{{URL::asset('plugins/telephoneinput/telephoneinput.css')}}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet"/>
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
        .nav-pills .nav-link {
            width: 100%;
            padding: 20px 36px 20px 20px;
            position: relative;
            background-color: rgb(255, 255, 255);
            border-left: 1px solid rgb(208, 211, 212);
            border-bottom: 1px solid rgb(208, 211, 212);
            box-sizing: border-box;
            cursor: auto;
            pointer-events: all !important;
        }

        .nav-pills .nav-link.active {
            background-color: white;
            color: black;
        }

        .nav-pills .nav-link:not(.active) {
            background-color: rgb(255, 255, 255);
            color: gray;
        }

        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    </style>

    <div class="row">
        <div class="col-md-2">
            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                <a class="nav-link active" id="tab1" data-bs-toggle="pill" href="#content1">
                    <span class="cursor-pointer">01 <br> Basic Information</span>
                </a>
                <a class="nav-link" id="tab2" data-bs-toggle="pill" href="#content2">
                    <span class="cursor-pointer">02 <br> Languages</span>
                </a>
                <a class="nav-link" id="tab3" data-bs-toggle="pill" href="#content3">
                    <span class="cursor-pointer">03 <br> Location</span>
                </a>
                <a class="nav-link" id="tab4" data-bs-toggle="pill" href="#content4">
                    <span class="cursor-pointer">04 <br> Education</span>
                </a>
                <a class="nav-link" id="tab5" data-bs-toggle="pill" href="#content5">
                    <span class="cursor-pointer">05 <br> Work Experience</span>
                </a>
                <a class="nav-link" id="tab6" data-bs-toggle="pill" href="#content6">
                    <span class="cursor-pointer">06 <br> Phone Number</span>
                </a>
                {{-- <a class="nav-link" id="tab7" data-bs-toggle="pill" href="#content7">
                    <span class="cursor-pointer">07 <br> Preview</span>
                </a> --}}
            </div>
        </div>
        <div class="col-md-10">
            <form action="{{route('user.dashboard.complete-profile.store')}}" method="post"
                  enctype="multipart/form-data">
                @csrf
                <div class="tab-content mt-3">
                    <div class="tab-pane fade show active" id="content1">
                        <div class="card">
                            <div class="card-body">
                                <div class="container">
                                    <h2>STEP 01: Basic Information</h2>
                                    <p>Choose your contract type to proceed</p>

                                    <div class="mt-4 mb-3">
                                        <label class="form-label">Contributor Type</label>
                                        <br>
                                        <div class="btn-group" role="group" aria-label="Contributor Type">
                                            <input type="radio" class="btn-check" name="btnradio" id="btnradio1"
                                                   autocomplete="off" checked>
                                            <label class="btn btn-outline-primary" for="btnradio1">Independent
                                                Contractor</label>

                                            <input type="radio" class="btn-check" name="btnradio" id="btnradio2"
                                                   autocomplete="off">
                                            <label class="btn btn-outline-primary" for="btnradio2">Business
                                                Contractor</label>
                                        </div>
                                    </div>

                                    <div class="mt-9 mb-5">
                                        <h3>Profile</h3>
                                        <div class="row">
                                            <div class="col-sm-6 col-md-3">
                                                <label>Full Name</label>
                                                <p id="userName">{{auth()->user()->name}}</p>
                                            </div>
                                            <div class="col-sm-6 col-md-4">
                                                <label>Email</label>
                                                <p id="userEmail">{{auth()->user()->email}}</p>
                                            </div>
                                            <div class="col-sm-6 col-md-5">
                                                <label>Gender</label>
                                                <select class="form-control" name="gender">
                                                    <option
                                                        value="male" {{ $information->gender == 'male' ? 'selected' : '' }}>
                                                        Male
                                                    </option>
                                                    <option
                                                        value="female" {{ $information->gender == 'female' ? 'selected' : '' }}>
                                                        Female
                                                    </option>
                                                </select>

                                            </div>
                                            <div class="col-sm-6 col-md-3">
                                                <label>Which city you were born?</label>
                                                <input class="form-control" name="born_city"
                                                       value="{{$information->born_city}}" type="text">
                                            </div>
                                            <div class="col-sm-6 col-md-4">
                                                <label>In which state/province were you born?</label>
                                                <input class="form-control" name="state_province"
                                                       value="{{$information->state_province}}" type="text">
                                            </div>
                                            <div class="col-sm-6 col-md-5">
                                                <label>In which country were you born?</label>
                                                <select id="user_country" name="country"
                                                        data-placeholder="Select Your Country:">
                                                    @foreach(config('countries') as $value)
                                                        <option value="{{ $value }}"
                                                                @if(auth()->user()->country == $value) selected @endif>{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-sm-6 col-md-3">
                                                <label>Do You have Household Pet</label>
                                                <div class="row ml-4">
                                                    <div class="col-auto form-check">
                                                        <input class="form-check-input" type="radio" name="hasPet"
                                                               id="hasPetYes"
                                                               value="yes" {{ $information->hasPet == 'yes' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="hasPetYes">Yes</label>
                                                    </div>
                                                    <div class="col-auto form-check ml-3">
                                                        <input class="form-check-input" type="radio" name="hasPet"
                                                               id="hasPetNo"
                                                               value="no" {{ $information->hasPet == 'no' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="hasPetNo">No</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-4">
                                                <label>How well you can read in English</label>
                                                <select class="form-control" name="english_skills">
                                                    <option
                                                        value="beginner" {{ $information->english_skills == 'beginner' ? 'selected' : '' }}>
                                                        Beginner
                                                    </option>
                                                    <option
                                                        value="intermediate" {{ $information->english_skills == 'intermediate' ? 'selected' : '' }}>
                                                        Intermediate
                                                    </option>
                                                    <option
                                                        value="advanced" {{ $information->english_skills == 'advanced' ? 'selected' : '' }}>
                                                        Advanced
                                                    </option>
                                                    <option
                                                        value="fluent" {{ $information->english_skills == 'fluent' ? 'selected' : '' }}>
                                                        Fluent
                                                    </option>
                                                    <option
                                                        value="near_native" {{ $information->english_skills == 'near_native' ? 'selected' : '' }}>
                                                        Near Native
                                                    </option>
                                                    <option
                                                        value="native" {{ $information->english_skills == 'native' ? 'selected' : '' }}>
                                                        Native or Bilingual
                                                    </option>
                                                </select>
                                            </div>

                                            <div class="col-sm-6 col-md-5">
                                                <label>Birthday</label>
                                                <input type="date" class="form-control" name="date"
                                                       value="{{ $information->date }}">
                                            </div>
                                            <div class="col-sm-6 col-md-3">
                                                <label>Translation Experience</label>
                                                <div class="row ml-4">
                                                    <div class="col-auto form-check">
                                                        <input class="form-check-input" type="radio"
                                                               name="hasTranslationExperience"
                                                               id="hasTranslationExperienceYes"
                                                               value="yes" {{ $information->hasTranslationExperience == 'yes' ? 'checked' : '' }}>
                                                        <label class="form-check-label"
                                                               for="hasTranslationExperienceYes">Yes</label>
                                                    </div>
                                                    <div class="col-auto form-check ml-3">
                                                        <input class="form-check-input" type="radio"
                                                               name="hasTranslationExperience"
                                                               id="hasTranslationExperienceNo"
                                                               value="no" {{ $information->hasTranslationExperience == 'no' ? 'checked' : '' }}>
                                                        <label class="form-check-label"
                                                               for="hasTranslationExperienceNo">No</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-4">
                                                <label>Company currently working as SEE</label>
                                                <input class="form-control" name="working_company" type="number"
                                                       value="{{ $information->working_company }}">
                                            </div>
                                            <div class="col-sm-6 col-md-5">
                                                <label>In Which country did you spent most time in from 0-15? </label>
                                                <select id="spent_time_country" class="form-control"
                                                        name="spent_time_country">
                                                    @foreach(config('countries') as $value)
                                                        <option value="{{ $value }}"
                                                                @if($information->spent_time_country == $value) selected @endif>{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <br>
                                            <div class="col-sm-6 col-md-3">
                                                <label>Number of year you have lived in your current Country</label>
                                                <input class="form-control" name="country_you_lived"
                                                       value="{{$information->country_you_lived}}" type="number">
                                            </div>
                                            <div class="col-sm-6 col-md-9">
                                                <label>How many family member that are aged< 18 or +65 who would be
                                                    willing to
                                                    participate in a Dash Collection Data Task. Enter 0 if you do not
                                                    have any</label>
                                                <input class="form-control" name="familyParticipation"
                                                       value="{{$information->familyParticipation}}" type="number">
                                            </div>

                                            <div class="col-sm-6 col-md-3">
                                                <label>Do have Android device will built-in stylus
                                                    functionality?</label>
                                                <select class="form-control" name="android_functionality">
                                                    <option
                                                        value="yes" {{ $information->android_functionality == 'yes' ? 'selected' : '' }}>
                                                        Yes
                                                    </option>
                                                    <option
                                                        value="no" {{ $information->android_functionality == 'no' ? 'selected' : '' }}>
                                                        No
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="col-sm-6 col-md-4">
                                                <label>Approximately what age did you first learn to speak
                                                    English</label>
                                                <input class="form-control" name="englishLearningAge"
                                                       value="{{$information->englishLearningAge}}" type="number">
                                            </div>
                                            <div class="col-sm-6 col-md-5">
                                                <div class="col-sm-10 col-md-10">

                                                    <label>Which of the following is best describes your race and
                                                        ethnicity</label>
                                                </div>
                                                <div class="col-sm-12 col-md-12">

                                                    <input class="form-control" name="race_and_ethnicity"
                                                           value="{{$information->race_and_ethnicity}}" type="number">
                                                </div>
                                            </div>

                                            <div class="row mt-5">
                                                <div class="col-sm-6 col-md-5">
                                                </div>
                                                <div class="col-sm-6 col-md-5">
                                                    <button type="submit" class="btn btn-primary">Submit</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="content2">
                        <div class="card">
                            <div class="card-body">
                                <h2>STEP 02: Languages</h2>
                                <p>Select your native language and add other languages that you speak</p>
                                @php
                                    if(auth()->user()->language == 'en-US') {
                                        $selected_languages = null;
                                    } else {
                                        $selected_languages = json_decode(auth()->user()->language, true);
                                    }
                                @endphp
                                <div class="col-sm-6 col-md-12">
                                    <div class="input-box">
                                        <label class="form-label fs-12">{{ __('Primary languages you speak at home') }}
                                            <span
                                                class="text-muted">({{ __('Required') }})</span></label>
                                        <select id="primary_language" name="primary_language" class="select2"
                                                data-placeholder="{{ __('Select your languages') }}"
                                        >
                                            @foreach ($languages as $language)
                                                <option value="{{ $language->id }}"
                                                        data-code="{{ $language->language_code }}"
                                                        data-img="{{ \Illuminate\Support\Facades\URL::asset($language->language_flag) }}"
                                                        @if (config('stt.vendor_logos') == 'show') data-vendor="{{ \Illuminate\Support\Facades\URL::asset($language->vendor_img) }}"
                                                        @endif
                                                        @if($information->primary_language == $language->id)  selected @endif
                                                > {{ $language->language }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-12">
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
                                <div class="row mt-5">
                                    <div class="col-sm-6 col-md-5">
                                    </div>
                                    <div class="col-sm-6 col-md-5">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="content3">
                        <div class="card">
                            <div class="card-body">
                                <h4>STEP 03: Location</h4>
                                <p>Please enter your full address information .</p>
                                <div class="row">
                                    <div class="col-sm-12 col-md-12">
                                        <div class="form-group">
                                            <label for="address">Street Address</label>
                                            <textarea class="form-control" id="address" rows="3" name="address"
                                                      placeholder="Street name and number">{{ $information->address }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label for="city">City</label>
                                            <input type="text" class="form-control" name="city"
                                                   value="{{ auth()->user()->city }}"
                                                   placeholder="Enter city name">
                                        </div>
                                    </div>

                                    {{--                                    <div class="col-sm-6 col-md-4">--}}
                                    {{--                                        <div class="form-group">--}}
                                    {{--                                            <label for="spent_time_country">Country</label>--}}
                                    {{--                                            <select id="country" class="form-control" name="country">--}}
                                    {{--                                                @foreach(config('countries') as $value)--}}
                                    {{--                                                    <option value="{{ $value }}"--}}
                                    {{--                                                            @if(auth()->user()->spent_time_country == $value) selected @endif>{{ $value }}</option>--}}
                                    {{--                                                @endforeach--}}
                                    {{--                                            </select>--}}
                                    {{--                                        </div>--}}
                                    {{--                                    </div>--}}
                                    {{--                                    <div class="col-sm-6 col-md-4">--}}

                                    {{--                                        <div class="form-group">--}}
                                    {{--                                            <label for="state">STATE OR PROVINCE</label>--}}
                                    {{--                                            <input type="text" class="form-control" id="state" name="state_province"--}}
                                    {{--                                                   value="{{ $information->state_province }}"--}}
                                    {{--                                                   placeholder="Enter state or province name">--}}
                                    {{--                                        </div>--}}
                                    {{--                                    </div>--}}
                                    <div class="col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label for="zip">ZIP CODE</label>
                                            <input type="text" class="form-control" id="zip"
                                                   value="{{ auth()->user()->postal_code }}" name="zip"
                                                   placeholder="00000">
                                        </div>
                                    </div>
                                    <div class="col-sm-8 col-md-8">
                                        <div class="form-group">
                                            <label for="residency-years">Residency History</label>
                                            <select class="form-control" id="residency-years" name="residency_years">
                                                <option selected>Select Years</option>
                                                <option value="1"
                                                        @if($information->residency_years == 1) selected @endif>1 Years
                                                </option>
                                                <option value="2"
                                                        @if($information->residency_years == 2) selected @endif>2 Years
                                                </option>
                                                <option value="3"
                                                        @if($information->residency_years == 3) selected @endif>3
                                                    Years
                                                </option>
                                                <option value="4"
                                                        @if($information->residency_years == 4) selected @endif>4
                                                    Years
                                                </option>
                                                <option value="5"
                                                        @if($information->residency_years == 5) selected @endif>5+
                                                    Years
                                                </option>
                                            </select>
                                        </div>
                                        <span>Select how many years you have lived in your current country of residency</span>

                                    </div>
                                    <br>
                                    <div class="row mt-5">
                                        <div class="col-sm-6 col-md-5">
                                        </div>
                                        <div class="col-sm-6 col-md-5">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="content4">
                        <div class="card">
                            <div class="card-body">
                                <h4>STEP 04: Education</h4>
                                <p>Enter information about your education level .</p>
                                <div>
                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label for="residency-years">HIGHEST LEVEL OF EDUCATION</label>
                                            <select class="form-control" id="education" name="education">
                                                <option disabled selected>Select Years</option>
                                                <option value="1" @if($information->education == 1) selected @endif>
                                                    Online Courses
                                                </option>
                                                <option value="2" @if($information->education == 2) selected @endif>
                                                    Student
                                                </option>
                                                <option value="3" @if($information->education == 3) selected @endif>
                                                    High School Diploma
                                                </option>
                                                <option value="4" @if($information->education == 4) selected @endif>
                                                    Diploma Degree
                                                </option>
                                                <option value="5" @if($information->education == 5) selected @endif>
                                                    Associate's Degree
                                                </option>
                                                <option value="6" @if($information->education == 6) selected @endif>
                                                    Bachelor's Degree
                                                </option>
                                                <option value="7" @if($information->education == 7) selected @endif>
                                                    Master's Degree
                                                </option>
                                                <option value="8" @if($information->education == 8) selected @endif>
                                                    Doctorate (Ph.D.)
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label for="residency-years">LINGUISTICS QUALIFICATION</label>
                                            <select class="form-control" id="linguistics" name="linguistics">
                                                <option disabled>Select</option>
                                                <option value="1" @if($information->linguistics == 1) selected @endif>
                                                    Diploma - Studying
                                                </option>
                                                <option value="2" @if($information->linguistics == 2) selected @endif>
                                                    Diploma - Completed
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-5">
                                    <div class="col-sm-6 col-md-5">
                                    </div>
                                    <div class="col-sm-6 col-md-5">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="content5">
                        <div class="card">
                            <div class="card-body">
                                <h4>STEP 05: Work Experience</h4>
                                <p>Get targeted oportunities by letting us know about your work experience.</p>
                                <br>
                                <br>
                                <div class="col-sm-6 col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1"
                                               name="experienceTranscription"
                                               id="experienceTranscription"
                                               @if($information->experienceTranscription) checked @endif>
                                        <label class="form-check-label" for="experienceTranscription">
                                            I have work experience in transcribing audio or annotating data.
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1"
                                               name="experienceProofreading"
                                               id="experienceProofreading"
                                               @if($information->experienceProofreading) checked @endif>
                                        <label class="form-check-label" for="experienceProofreading">
                                            I have work experience in proofreading.
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1"
                                               name="experienceSearchEngineEvaluator"
                                               id="experienceSearchEngineEvaluator"
                                               @if($information->experienceSearchEngineEvaluator) checked @endif>
                                        <label class="form-check-label" for="experienceSearchEngineEvaluator">
                                            I am currently working as a search engine evaluator.
                                        </label>
                                    </div>
                                </div>
                                <div class="row mt-5">
                                    <div class="col-sm-6 col-md-5">
                                    </div>
                                    <div class="col-sm-6 col-md-5">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="content6">
                        <div class="card">
                            <div class="card-body">
                                <h4>STEP 06: Phone Number</h4>
                                <p>Add at least one phone number for personal contact.</p>
                                <br>
                                <br>

                                <div class="col-sm-6 col-md-6">
                                    <div class="input-box">
                                        <div class="form-group">
                                            <label class="form-label fs-12">{{ __('Phone Number') }}</label>
                                            <div class="input-group">
                                                <input type="tel"
                                                       class="fs-12 form-control"
                                                       id="phone-number" name="phone_number"
                                                       value="{{ auth()->user()->phone_number }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-5">
                                    <div class="col-sm-6 col-md-5">
                                    </div>
                                    <div class="col-sm-6 col-md-5">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    {{--                <div class="tab-pane fade" id="content7">--}}
                    {{--                    <div class="card">--}}
                    {{--                        <div class="card-body">--}}
                    {{--                            <h4>Content for Tab 7</h4>--}}
                    {{--                            <p>This is the content for Tab 7.</p>--}}
                    {{--                        </div>--}}
                    {{--                    </div>--}}
                    {{--                </div>--}}
                </div>
            </form>
        </div>
    </div>

@endsection

@section('js')

    <!-- Awselect JS -->
    <script src="{{URL::asset('plugins/awselect/awselect-custom.js')}}"></script>
    <script src="{{URL::asset('js/awselect.js')}}"></script>
    <script src="{{URL::asset('plugins/telephoneinput/telephoneinput.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <!-- File Uploader -->
    <script src="{{URL::asset('js/avatar.js')}}"></script>
    <script>
        $(function () {
            "use strict";

            $("#phone-number").intlTelInput();
        });
        $(document).ready(function () {

            $('#user_country').select2();
            $('#spent_time_country').select2();
            $('#primary_language').select2();
            $('#languages1').select2({
                tags: true,
                tokenSeparators: [',', ' '], // Allow tags to be separated by comma or space
                placeholder: "Select Languages",
            });

            // Tab click event handler
            $('.nav-link').click(function (e) {
                e.preventDefault(); // Prevent the default link behavior
                console.log('clicked');
                // Get the target div ID from the href attribute
                var targetDivId = $(this).attr('href');

                // Scroll to the target div
                $('html, body').animate({
                    scrollTop: $(targetDivId).offset().top
                }, 500);
            });

        });
        let userName = document.getElementById("userName");
        let nameInput = document.createElement("input");
        nameInput.className = "form-control";
        nameInput.value = userName.innerText;

        document.getElementById("btnradio1").addEventListener("change", function () {
            if (userName.nodeName === "INPUT") {
                let newP = document.createElement("p");
                newP.innerText = userName.value;
                newP.id = "userName";
                userName.replaceWith(newP);
                userName = document.getElementById("userName");
            }
        });

        document.getElementById("btnradio2").addEventListener("change", function () {
            if (userName.nodeName === "P") {
                userName.replaceWith(nameInput);
                userName = document.getElementById("userName");
            }
        });
    </script>

@endsection
