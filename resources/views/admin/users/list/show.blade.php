@extends('layouts.app')

@section('css')
    <!-- Data Table CSS -->
    <link href="{{ URL::asset('plugins/datatable/datatables.min.css') }}" rel="stylesheet"/>
    <!-- Awselect CSS -->
    <link href="{{ URL::asset('plugins/awselect/awselect.min.css') }}" rel="stylesheet"/>
    <!-- Sweet Alert CSS -->
    <link href="{{ URL::asset('plugins/sweetalert/sweetalert2.min.css') }}" rel="stylesheet"/>

@endsection
@section('page-header')
    <!-- PAGE HEADER -->
    <div class="page-header mt-5-7">
        <div class="page-leftheader">
            <h4 class="page-title mb-0">{{ __('User Information') }}</h4>
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i
                            class="fa-solid fa-user-shield mr-2 fs-12"></i>{{ __('Admin') }}</a></li>
                <li class="breadcrumb-item" aria-current="page"><a
                        href="{{ route('admin.user.dashboard') }}"> {{ __('User Management') }}</a></li>
                <li class="breadcrumb-item" aria-current="page"><a
                        href="{{ route('admin.user.list') }}">{{ __('User List') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#"> {{ __('User Information') }}</a>
                </li>
            </ol>
        </div>
    </div>
    <!-- END PAGE HEADER -->
@endsection

@section('content')
    <!-- USER PROFILE PAGE -->
    <div class="row">
        <div class="col-xl-3 col-lg-3 col-md-12">
            <div class="card border-0">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Personal Information') }}</h3>
                </div>
                {{--				<div class="overflow-hidden p-0">--}}
                {{--					<div class="row">--}}
                {{--						<div class="col-sm-6 border-right border-bottom text-center">--}}
                {{--							<div class="p-2">--}}
                {{--								<span class="text-muted fs-12">{{ __('Subscription Characters') }}</span>--}}
                {{--								<h5 class="mt-1 mb-1 font-weight-bold text-dark number-font fs-14">{{ number_format($user->available_chars) }}</h5>								--}}
                {{--							</div>--}}
                {{--						</div>--}}
                {{--						<div class="col-sm-6 border-bottom">--}}
                {{--							<div class="text-center p-2">--}}
                {{--								<span class="text-muted fs-12">{{ __('Prepaid Characters') }}</span>--}}
                {{--								<h5 class="mt-1 mb-1 font-weight-bold text-dark number-font fs-14">{{ number_format($user->available_chars_prepaid) }}</h5>								--}}
                {{--							</div>--}}
                {{--						</div>--}}
                {{--					</div>--}}
                {{--					<div class="row">--}}
                {{--						<div class="col-sm-6 border-right border-bottom text-center">--}}
                {{--							<div class="p-2">--}}
                {{--								<span class="text-muted fs-12">{{ __('Subscription Minutes') }}</span>--}}
                {{--								<h5 class="mt-1 mb-1 font-weight-bold text-dark number-font fs-14">{{ number_format($user->available_minutes) }}</h5>								--}}
                {{--							</div>--}}
                {{--						</div>--}}
                {{--						<div class="col-sm-6 border-bottom">--}}
                {{--							<div class="text-center p-2">--}}
                {{--								<span class="text-muted fs-12">{{ __('Prepaid Minutes') }}</span>--}}
                {{--								<h5 class="mt-1 mb-1 font-weight-bold text-dark number-font fs-14">{{ number_format($user->available_minutes_prepaid) }}</h5>								--}}
                {{--							</div>--}}
                {{--						</div>--}}
                {{--					</div>--}}
                {{--				</div>--}}
                <div class="widget-user-image overflow-hidden mx-auto mt-5"><img alt="User Avatar"
                                                                                 class="rounded-circle"
                                                                                 src="@if($user->profile_photo_path) {{ $user->profile_photo_path }} @else {{ URL::asset('img/users/avatar.jpg') }} @endif">
                </div>
                <div class="card-body text-center">
                    <div>
                        <h4 class="mb-1 mt-1 font-weight-bold fs-16">{{ $user->name }}</h4>
                        <h6 class="text-muted fs-12">{{ $user->job_role }}</h6>
                        {{--						@if ($user_subscription != '')--}}
                        {{--							<h6 class="text-muted fs-12">{{ __('Subscription Plan') }}: <span class="text-info">{{ $user_subscription->plan_name }}</span></h6>--}}
                        {{--						@else--}}
                        {{--							<h6 class="text-muted fs-12">{{ __('Subscription Plan') }}: <span class="text-info font-weight-bold">{{ __('No Subscription') }}</span></h6>--}}
                        {{--						@endif--}}
                        <a href="{{ route('admin.user.edit', [$user->id]) }}"
                           class="btn btn-primary mt-3 mb-2 mr-2 pl-5 pr-5"><i
                                class="fa-solid fa-pencil mr-1"></i> {{ __('Edit Profile') }}</a>
                        {{--						<a href="{{ route('admin.user.storage', [$user->id]) }}" class="btn btn-primary mt-3 mb-2"><i class="fa-solid fa-hard-drive mr-1"></i>{{ __('Add Credits') }}</a>--}}
                    </div>
                </div>

                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <tbody>
                            <tr>
                                <td class="py-2 px-0 border-top-0">
                                    <span class="font-weight-semibold w-50">{{ __('Full Name') }} </span>
                                </td>
                                <td class="py-2 px-0 border-top-0">{{ $user->name }}</td>
                            </tr>
                            <tr>
                                <td class="py-2 px-0">
                                    <span class="font-weight-semibold w-50">{{ __('Email') }} </span>
                                </td>
                                <td class="py-2 px-0">{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <td class="py-2 px-0">
                                    <span class="font-weight-semibold w-50">{{ __('User Status') }} </span>
                                </td>
                                <td class="py-2 px-0">{{ ucfirst($user->status) }}</td>
                            </tr>
                            <tr>
                                <td class="py-2 px-0">
                                    <span class="font-weight-semibold w-50">{{ __('User Group') }} </span>
                                </td>
                                <td class="py-2 px-0">{{ ucfirst($user->group) }}</td>
                            </tr>
                            <tr>
                                <td class="py-2 px-0">
                                    <span class="font-weight-semibold w-50">{{ __('Registered On') }} </span>
                                </td>
                                <td class="py-2 px-0">{{ $user->created_at }}</td>
                            </tr>
                            <tr>
                                <td class="py-2 px-0">
                                    <span class="font-weight-semibold w-50">{{ __('Last Updated On') }} </span>
                                </td>
                                <td class="py-2 px-0">{{ $user->updated_at }}</td>
                            </tr>
                            <tr>
                                <td class="py-2 px-0">
                                    <span class="font-weight-semibold w-50">{{ __('Referral ID') }} </span>
                                </td>
                                <td class="py-2 px-0">{{ $user->referral_id }}</td>
                            </tr>
                            {{--                            <tr>--}}
                            {{--                                <td class="py-2 px-0">--}}
                            {{--                                    <span class="font-weight-semibold w-50">{{ __('Job Role') }} </span>--}}
                            {{--                                </td>--}}
                            {{--                                <td class="py-2 px-0">{{ $user->job_role }}</td>--}}
                            {{--                            </tr>--}}
                            <tr>
                                <td class="py-2 px-0">
                                    <span class="font-weight-semibold w-50">{{ __('Company') }}</span>
                                </td>
                                <td class="py-2 px-0">{{ $user->company }}</td>
                            </tr>
                            <tr>
                                <td class="py-2 px-0">
                                    <span class="font-weight-semibold w-50">{{ __('Website') }} </span>
                                </td>
                                <td class="py-2 px-0">{{ $user->website }}</td>
                            </tr>
                            <tr>
                                <td class="py-2 px-0">
                                    <span class="font-weight-semibold w-50">{{ __('Address') }} </span>
                                </td>
                                <td class="py-2 px-0">{{ $user->address }}</td>
                            </tr>
                            <tr>
                                <td class="py-2 px-0">
                                    <span class="font-weight-semibold w-50">{{ __('Postal Code') }} </span>
                                </td>
                                <td class="py-2 px-0">{{ $user->postal_code }}</td>
                            </tr>
                            <tr>
                                <td class="py-2 px-0">
                                    <span class="font-weight-semibold w-50">{{ __('City') }} </span>
                                </td>
                                <td class="py-2 px-0">{{ $user->city }}</td>
                            </tr>
                            <tr>
                                <td class="py-2 px-0">
                                    <span class="font-weight-semibold w-50">{{ __('Country') }} </span>
                                </td>
                                <td class="py-2 px-0">{{ $user->country }}</td>
                            </tr>
                            <tr>
                                <td class="py-2 px-0">
                                    <span class="font-weight-semibold w-50">{{ __('Phone') }} </span>
                                </td>
                                <td class="py-2 px-0">{{ $user->phone_number }}</td>
                            </tr>

                            <tr>
                                <td class="py-2 px-0">
                                    <span class="font-weight-semibold w-50">{{ __('SMS Verification') }} </span>
                                </td>
                                <td class="py-2 px-0">
                                    <button id="toggleButton" class="toggleButton" data-status="1">ON</button>
                                    - @if($user->phone_number_verified_at)
                                        Already Verified
                                    @else
                                        Not Verified
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="py-2 px-0">
                                    <span class="font-weight-semibold w-50">{{ __('Known Language') }} </span>
                                </td>
                                <td class="py-2 px-0"></td>
                            </tr>
                            @php
                                $selected_languages = json_decode($user->language, true);
                            @endphp
                            @if($selected_languages)
                                @foreach ($selected_languages as $selected_language_id)
                                    @foreach ($languages as $language)
                                        @if ($language->id == $selected_language_id)
                                            <tr>
                                                <td class="py-2 px-0">

                                                </td>
                                                <td class="py-2 px-0">
                                                    <img
                                                        src="{{ \Illuminate\Support\Facades\URL::asset($language->language_flag) }}"
                                                        alt="{{ $language->language_code }}" class="mr-2"
                                                        width="20">
                                                    {{ $language->language }}
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @endforeach
                            @else
                                <tr>
                                    <td class="py-2 px-0">

                                    </td>
                                    <td>
                                        No language selected
                                    </td>
                                </tr>
                            @endif

                            </tbody>
                        </table>
                        <div class="border-0 text-right mb-2 mt-2">
                            <a href="{{ route('admin.user.list') }}" class="btn btn-primary">{{ __('Return') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-9 col-lg-9 col-md-12">
            <div class="row">

                {{--				<div class="col-lg-12 col-md-12 col-sm-12">--}}
                {{--					<div class="card mb-5 border-0">--}}
                {{--						<div class="card-header d-inline border-0">--}}
                {{--							<div>--}}
                {{--								<h3 class="card-title fs-16 mt-3 mb-4"><i class="fa-solid fa-box-open mr-4 text-info"></i>{{ __('Subscription') }}</h3>--}}
                {{--							</div>--}}
                {{--							@if ($user_subscription == '')--}}
                {{--								<div>--}}
                {{--									<h3 class="card-title fs-24 font-weight-800">{{ __('Active Forever') }}</h3>--}}
                {{--								</div>--}}
                {{--								<div class="mb-1">--}}
                {{--									<span class="fs-12 text-muted">{{ __('No Subscription') }} / {!! config('payment.default_system_currency_symbol') !!}0.00 {{ __('Per Month') }}</span>--}}
                {{--								</div>--}}
                {{--							@else--}}
                {{--								<div>--}}
                {{--									<h3 class="card-title fs-24 font-weight-800">@if ($user_subscription->pricing_plan == 'monthly') {{ __('Monthly Subscription') }} @else {{ __('Yearly Subscription') }} @endif</h3>--}}
                {{--								</div>--}}
                {{--								<div class="mb-1">--}}
                {{--									<span class="fs-12 text-muted">{{ $user_subscription->plan_name }} {{ __('Plan') }} / {!! config('payment.default_system_currency_symbol') !!}{{ $user_subscription->price }} @if ($user_subscription->pricing_plan == 'monthly') {{ __('Per Month') }} @else {{ __('Per Year') }} @endif</span>--}}
                {{--								</div>--}}
                {{--							@endif--}}
                {{--						</div>--}}
                {{--						<div class="card-body">--}}
                {{--							<div class="mb-3">--}}
                {{--								<span class="fs-12 text-muted">{{ __('Total ') }} {{ $characters }} {{ __('of') }} {{ $total_characters }} <span class="font-weight-bold text-warning">{{ __('Characters') }}</span> {{ __('Available') }}</span>--}}
                {{--							</div>--}}
                {{--							<div class="progress mb-4">--}}
                {{--								<div class="progress-bar progress-bar-striped progress-bar-animated bg-warning subscription-bar" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: {{ $progress['subscription'] }}%"></div>--}}
                {{--							</div>--}}
                {{--							<div class="mb-3">--}}
                {{--								<span class="fs-12 text-muted">{{ __('Total ') }} {{ $characters }} {{ __('of') }} {{ $total_characters }} <span class="font-weight-bold text-primary">{{ __('Minutes') }}</span> {{ __('Available') }}</span>--}}
                {{--							</div>--}}
                {{--							<div class="progress mb-4">--}}
                {{--								<div class="progress-bar progress-bar-striped progress-bar-animated zip-bar" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: {{ $progress['subscription'] }}%"></div>--}}
                {{--							</div>--}}
                {{--						</div>--}}
                {{--					</div>--}}
                {{--				</div>--}}

                {{--				<div class="col-xl-12 col-md-12 col-12">--}}
                {{--					<div class="card mb-5 border-0">--}}
                {{--						<div class="card-header d-inline border-0">--}}
                {{--							<div>--}}
                {{--								<h3 class="card-title fs-16 mt-3 mb-4"><i class="fa-solid fa-sack-dollar mr-4 text-info"></i>{{ __('User Payments') }} <span class="text-muted">({{ __('Current Year') }})</span></h3>--}}
                {{--							</div>--}}
                {{--							<div>--}}
                {{--								<h3 class="card-title fs-24 font-weight-800">{!! config('payment.default_system_currency_symbol') !!}{{ number_format((float)$user_data_year['total_payments'][0]['data'], 2, '.', '') }}</h3>--}}
                {{--							</div>--}}
                {{--							<div class="mb-3">--}}
                {{--								<span class="fs-12 text-muted">{{ __('Total Payments by the User During Current Year ') }}({{ config('payment.default_system_currency') }})</span>--}}
                {{--							</div>--}}
                {{--						</div>--}}
                {{--						<div class="card-body">--}}
                {{--							<div class="chartjs-wrapper-demo">--}}
                {{--								<canvas id="chart-user-payments" class="h-330"></canvas>--}}
                {{--							</div>--}}
                {{--						</div>--}}
                {{--					</div>--}}
                {{--				</div>--}}

                {{--				<div class="col-xl-12 col-md-12 col-12">--}}
                {{--					<div class="card mb-5 border-0">--}}
                {{--						<div class="card-header d-inline border-0">--}}
                {{--							<h3 class="card-title fs-16 mt-3 mb-4"><i class="fa-sharp fa-solid fa-waveform-lines mr-4 text-info"></i>{{ __('Text to Speech Usage') }} <span class="text-muted">({{ __('Current Year') }})</span></h3>--}}
                {{--						</div>--}}
                {{--						<div class="card-body">--}}
                {{--							<div class="row mb-5 mt-2">--}}
                {{--								<div class="col-xl-3 col-12 ">--}}
                {{--									<p class=" mb-1 fs-12">{{ __('Total Standard Characters Used') }}</p>--}}
                {{--									<h3 class="mb-0 fs-20 number-font">{{ number_format($user_data_year['total_standard_chars'][0]['data']) }}</h3>--}}
                {{--								</div>--}}
                {{--								<div class="col-xl-3 col-12 ">--}}
                {{--									<p class=" mb-1 fs-12">{{ __('Total Neural Characters Used') }}</p>--}}
                {{--									<h3 class="mb-0 fs-20 number-font">{{ number_format($user_data_year['total_neural_chars'][0]['data']) }}</h3>--}}
                {{--								</div>--}}
                {{--								<div class="col-xl-3 col-12 ">--}}
                {{--									<p class=" mb-1 fs-12">{{ __('Total Audio Files Created') }}</p>--}}
                {{--									<h3 class="mb-0 fs-20 number-font">{{ number_format($user_data_year['total_audio_files'][0]['data']) }}</h3>--}}
                {{--								</div>--}}
                {{--								<div class="col-xl-3 col-12 ">--}}
                {{--									<p class=" mb-1 fs-12">{{ __('Total Listen Mode Results') }}</p>--}}
                {{--									<h3 class="mb-0 fs-20 number-font">{{ number_format($user_data_year['total_listen_modes'][0]['data']) }}</h3>--}}
                {{--								</div>--}}
                {{--							</div>--}}
                {{--							<div class="chartjs-wrapper-demo">--}}
                {{--								<canvas id="chart-user-chars" class="h-330"></canvas>--}}
                {{--							</div>--}}
                {{--						</div>--}}
                {{--					</div>--}}
                {{--				</div>--}}

                {{--				<div class="col-xl-12 col-md-12 col-12">--}}
                {{--					<div class="card mb-5 border-0">--}}
                {{--						<div class="card-header d-inline border-0">--}}
                {{--							<h3 class="card-title fs-16 mt-3 mb-4"><i class="fa-sharp fa-solid fa-microphone-lines mr-4 text-info"></i>{{ __('Speech To Text Usage') }} <span class="text-muted">({{ __('Current Year') }})</span></h3>--}}
                {{--						</div>--}}
                {{--						<div class="card-body">--}}
                {{--							<div class="row mb-5 mt-2">--}}
                {{--								<div class="col-md col-sm-12 ">--}}
                {{--									<p class=" mb-1 fs-12">{{ __('Total Minutes Used') }}</p>--}}
                {{--									<h3 class="mb-0 fs-20 number-font">{{ number_format((float)$user_data_year['total_minutes'][0]['data'] / 60, 2) }}</h3>--}}
                {{--								</div>--}}
                {{--								<div class="col-md col-sm-12 ">--}}
                {{--									<p class=" mb-1 fs-12">{{ __('Words Generated') }}</p>--}}
                {{--									<h3 class="mb-0 fs-20 number-font">{{ number_format($user_data_year['total_words'][0]['data']) }}</h3>--}}
                {{--								</div>--}}
                {{--								<div class="col-md col-sm-12 ">--}}
                {{--									<p class=" mb-1 fs-12">{{ __('Audio Files Transcribed') }}</p>--}}
                {{--									<h3 class="mb-0 fs-20 number-font">{{ number_format($user_data_year['total_file_transcribe'][0]['data']) }}</h3>--}}
                {{--								</div>--}}
                {{--								<div class="col-md col-sm-12 ">--}}
                {{--									<p class=" mb-1 fs-12">{{ __('Recordings Transcribed') }}</p>--}}
                {{--									<h3 class="mb-0 fs-20 number-font">{{ number_format($user_data_year['total_recording_transcribe'][0]['data']) }}</h3>--}}
                {{--								</div>--}}
                {{--								<div class="col-md col-sm-12 ">--}}
                {{--									<p class=" mb-1 fs-12">{{ __('Live Transcribe Results') }}</p>--}}
                {{--									<h3 class="mb-0 fs-20 number-font">{{ number_format($user_data_year['total_live_transcribe'][0]['data']) }}</h3>--}}
                {{--								</div>--}}
                {{--							</div>--}}
                {{--							<div class="chartjs-wrapper-demo">--}}
                {{--								<canvas id="chart-user-minutes" class="h-330"></canvas>--}}
                {{--							</div>--}}
                {{--						</div>--}}
                {{--					</div>--}}
                {{--				</div>--}}

                <div class="col-xl-12 col-md-12 col-12">
                    <div class="card mb-5 border-0">
                        <div class="card-header border-0">
                            <h3 class="card-title fs-16 mt-3 mb-4">
                                <i class="fa-solid fa-money-check-pen mr-4 text-info"></i>
                                {{ __('User Information') }}
                            </h3>
                        </div>
                        <div class="card-body">
                            @if($information)
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Gender:</strong> {{ $information->gender }} <br>
                                    <strong>Has Pet:</strong> {{ $information->hasPet }} <br>
                                    <strong>Date:</strong> {{ $information->date }} <br>
                                    <strong>Has Translation Experience:</strong> {{ $information->hasTranslationExperience }} <br>
                                    <strong>English Learning Age:</strong> {{ $information->englishLearningAgeenglishLearningAge ?? 'N/A' }} <br>
                                    <strong>English Skills:</strong> {{ $information->english_skills ?? 'N/A' }} <br>
                                    <strong>Company currently working as SEE:</strong> {{ $information->working_company }}<br>
                                    <strong>Spent Time in Country:</strong> {{ $information->spent_time_country }}<br>
                                    <strong>Number of year you have lived in your current Country:</strong> {{ $information->country_you_lived }}<br>
                                    <strong>Residency years:</strong> {{ $information->residency_years }}<br>
                                </div>
                                <div class="col-md-6">
                                    <strong>Family Participation:</strong> {{ $information->familyParticipation }} <br>
                                    <strong>Android Device:</strong> {{ $information->android_functionality }} <br>
                                    <strong>Race and Ethnicity:</strong> {{ $information->race_and_ethnicity }} <br>
                                    <strong>Experience as Search Engine Evaluator:</strong> @if($information->experienceSearchEngineEvaluator == 1) Yes @else No @endif <br>
                                    <strong>Experience in Proofreading:</strong> @if($information->experienceProofreading == 1) Yes @else No @endif <br>
                                    <strong>Experience in Transcription:</strong> @if($information->experienceTranscription == 1) Yes @else No @endif <br>
                                    <strong>LINGUISTICS QUALIFICATION:</strong> {{ $information->linguistics }}<br>
                                    @php
                                        $languages = \Illuminate\Support\Facades\DB::table('transcribe_languages')
                                                ->select('transcribe_languages.id', 'transcribe_languages.language', 'transcribe_languages.language_code', 'transcribe_languages.language_flag', 'transcribe_languages.vendor_img')
                                                ->where('id',$information->primary_language)
                                                ->first();
                                    @endphp
                                    <strong>Primary Language:</strong> @if($languages){{ $languages->language }} @else N/A @endif<br>
                                    <strong>HIGHEST LEVEL OF EDUCATION:</strong> {{ $information->primary_language }}
                                </div>
                            </div>
                            <!-- Add more data as needed -->
                            @else
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>User didn't complete his profile</strong>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card mb-5 border-0">
                        <div class="card-header d-inline border-0">
                            <div class="card-header d-inline border-0">
                                <div>
                                    <h3 class="card-title fs-16 mt-3 mb-4"><i
                                            class="fa-solid fa-money-check-pen mr-4 text-info"></i>{{ __('Applied Projects') }}
                                    </h3>
                                </div>
                            </div>
                            <table class="table" id="database-backup">
                                <thead>
                                <tr role="row">
                                    <th class="fs-12 font-weight-700 border-top-0">{{ __('Sr no') }}</th>
                                    <th class="fs-12 font-weight-700 border-top-0">{{ __('Projects') }}</th>
                                    <th class="fs-12 font-weight-700 border-top-0">{{ __('Type') }}</th>
                                    <th class="fs-12 font-weight-700 border-top-0">{{ __('Status') }}</th>
                                    <th class="fs-12 font-weight-700 border-top-0">{{ __('Created At') }}</th>
                                    <th class="fs-12 font-weight-700 border-top-0">{{ __('Approved At') }}</th>
                                    <th class="fs-12 font-weight-700 border-top-0">{{ __('Action') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($applied_projects as $applied_project)
                                    <tr>
                                        <td>
                                           <span
                                               class="">{{ $loop->iteration }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($applied_project->projects)
                                                {{ ucfirst($applied_project->projects->name) }}
                                            @endif
                                        </td>
                                        <td>
                                            @if($applied_project->projects)
                                                {{ ucfirst(str_replace('_', ' ', $applied_project->projects->type)) }}
                                            @endif
                                        </td>
                                        <td>
                                            <span
                                                class="cell-box transcribe-{{ strtolower($applied_project->status) }}">{{ ucfirst($applied_project->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span
                                                class="">{{ date_format($applied_project->created_at, 'd M Y') }}
                                            </span>
                                        </td>
                                        <td>
                                            <span
                                                class="">{{ $applied_project->read_at }}
                                            </span>
                                        </td>
                                        <td>
                                            <div>
                                                <a class="agreeTranscriptionButton" id="{{$applied_project->id}}"
                                                   href="#"><i
                                                        class="fa fa-check table-action-buttons request-action-button"
                                                        title="Activate Permission"></i></a>
                                                <a class="disagreeTranscriptionButton" id="{{$applied_project->id}}"
                                                   href="#"><i
                                                        class="fa fa-close table-action-buttons delete-action-button"
                                                        title="Deactivate Permission"></i></a>
                                                @if($applied_project->contract_form)
                                                    <a class="downloadButton"
                                                       href="{{ route('admin.user.permission.request.contract-form.pdf', $applied_project->id) }} "><i
                                                            class="fa fa-download table-action-buttons download-action-button"
                                                            title="Download Contract Form PDF"></i></a>
                                                @endif
                                                @if($applied_project->appliedForm)

                                                    <a class="downloadButton"
                                                       href="{{route('admin.user.permission.request.pdf', $applied_project->id) }}"><i
                                                            class="fa fa-download table-action-buttons download-action-button"
                                                            title="Download Consent Form PDF"></i></a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
                <div class="col-xl-12 col-md-12 col-12">

                    <div class="card mb-5 border-0">
                        <div class="card-header d-inline border-0">
                            <div class="card-header d-inline border-0">
                                <div>
                                    <h3 class="card-title fs-16 mt-3 mb-4"><i
                                            class="fa-solid fa-money-check-pen mr-4 text-info"></i>{{ __('Invoices Projects') }}
                                    </h3>
                                </div>
                            </div>
                            <table class="table" id="database-backup">
                                <thead>
                                <tr role="row">
                                    <th class="fs-12 font-weight-700 border-top-0">{{ __('Sr no') }}</th>
                                    <th class="fs-12 font-weight-700 border-top-0">{{ __('Created On') }}</th>
                                    <th class="fs-12 font-weight-700 border-top-0">{{ __('Project Name') }}</th>
                                    <th class="fs-12 font-weight-700 border-top-0">{{ __('Accepted Data') }}</th>
                                    <th class="fs-12 font-weight-700 border-top-0">{{ __('Rejected Data') }}</th>
                                    <th class="fs-12 font-weight-700 border-top-0">{{ __('Earning') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($invoices as $invoice)
                                    <tr>
                                        <td>
                                           <span
                                               class="">{{ $loop->iteration }}
                                            </span>
                                        </td>
                                        <td>
                                            {{ $invoice->created_at }}
                                        </td>
                                        <td>
                                            {{ $invoice->project_name }}
                                        </td>
                                        <td>
                                            {{ $invoice->accepted_data }}
                                        </td>
                                        <td>
                                            {{ $invoice->rejected_data }}
                                        </td>
                                        <td>
                                            {{ $invoice->earning }} {{ $user->currency }}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
                <div class="col-xl-12 col-md-12 col-12">

                    <div class="card mb-5 border-0">
                        <div class="card-header d-inline border-0">
                            <div class="card-header d-inline border-0">
                                <div>
                                    <h3 class="card-title fs-16 mt-3 mb-4"><i
                                            class="fa-solid fa-money-check-pen mr-4 text-info"></i>{{ __('Referrals') }}
                                    </h3>
                                </div>
                            </div>
                            <table class="table" id="database-backup">
                                <thead>
                                <tr role="row">
                                    <th class="fs-12 font-weight-700 border-top-0">{{ __('Sr no') }}</th>
                                    <th class="fs-12 font-weight-700 border-top-0">{{ __('Name') }}</th>
                                    <th class="fs-12 font-weight-700 border-top-0">{{ __('Email') }}</th>
                                    <th class="fs-12 font-weight-700 border-top-0">{{ __('Date') }}</th>
                                    <th class="fs-12 font-weight-700 border-top-0">{{ __('Commission Earned') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($referrals as $referral)
                                    <tr>
                                        <td>
                                           <span
                                               class="">{{ $loop->iteration }}
                                            </span>
                                        </td>
                                        <td>
                                            {{ $referral->referred->name }}
                                        </td>
                                        <td>
                                            {{ $referral->referred->email }}
                                        </td>
                                        <td>
                                            {{ $referral->created_at }}
                                        </td>
                                        <td>
                                            {{ $referral->commission }}{{ $user->currency }}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END USER PROFILE PAGE -->
@endsection

@section('js')
    <!-- Chart JS -->
    <script src="{{URL::asset('plugins/chart/chart.min.js')}}"></script>
    <script src="{{ URL::asset('plugins/sweetalert/sweetalert2.all.min.js') }}"></script>
    <script type="text/javascript">

        $(document).ready(function () {
            $('#toggleButton').click(function () {
                var currentStatus = $(this).attr("data-status");
                var newStatus = (currentStatus == '0') ? '1' : '0';

                $.ajax({
                    url: '{{ route('admin.user.sms.verification') }}',
                    type: 'POST',
                    data: {
                        status: newStatus,
                        user_id: {{$user->id}},
                        _token: '{{csrf_token()}}' // add CSRF token here
                    },
                    success: function (response) {
                        // Handle success here. For example, update the button text and status:
                        $('#toggleButton').text((newStatus == '0') ? 'OFF' : 'ON');
                        $('#toggleButton').attr("data-status", newStatus);
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        // Handle error here
                        console.log(textStatus, errorThrown);
                    }
                });
            });
        });

        $(function () {

            'use strict';

            let paymentData = JSON.parse(`<?php echo $chart_data['payments']; ?>`);
            let paymentDataset = Object.values(paymentData);
            let delayed2;

            let ctxPayment = document.getElementById('chart-user-payments');
            new Chart(ctxPayment, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: '{{ __('Payments') }} ({{ config('payment.default_system_currency') }}) ',
                        data: paymentDataset,
                        backgroundColor: '#00c851',
                        borderWidth: 1,
                        borderRadius: 20,
                        barPercentage: 0.5,
                        fill: true
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    legend: {
                        display: false,
                        labels: {
                            display: false
                        }
                    },
                    responsive: true,
                    animation: {
                        onComplete: () => {
                            delayed2 = true;
                        },
                        delay: (context) => {
                            let delay = 0;
                            if (context.type === 'data' && context.mode === 'default' && !delayed2) {
                                delay = context.dataIndex * 50 + context.datasetIndex * 5;
                            }
                            return delay;
                        },
                    },
                    scales: {
                        y: {
                            stacked: true,
                            ticks: {
                                beginAtZero: true,
                                font: {
                                    size: 10
                                },
                                stepSize: 50,
                            },
                            grid: {
                                color: '#ebecf1',
                                borderDash: [3, 2]
                            }
                        },
                        x: {
                            stacked: true,
                            ticks: {
                                font: {
                                    size: 10
                                }
                            },
                            grid: {
                                color: '#ebecf1',
                                borderDash: [3, 2]
                            }
                        },
                    },
                    plugins: {
                        tooltip: {
                            cornerRadius: 10,
                            xPadding: 10,
                            yPadding: 10,
                            backgroundColor: '#000000',
                            titleColor: '#FF9D00',
                            yAlign: 'bottom',
                            xAlign: 'center',
                        },
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 10,
                                font: {
                                    size: 10
                                }
                            }
                        }
                    }

                }
            });


            var standardData = JSON.parse(`<?php echo $chart_data['standard_chars']; ?>`);
            var standardDataset = Object.values(standardData);
            var neuralData = JSON.parse(`<?php echo $chart_data['neural_chars']; ?>`);
            var neuralDataset = Object.values(neuralData);

            var ctxChars = document.getElementById('chart-user-chars');
            new Chart(ctxChars, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: '{{ __('Standard Characters Used') }}',
                        data: standardDataset,
                        backgroundColor: '#1e1e2d',
                        borderWidth: 1,
                        borderRadius: 20,
                        barPercentage: 0.5,
                        fill: true
                    }, {
                        label: '{{ __('Neural Characters Used') }}',
                        data: neuralDataset,
                        backgroundColor: '#007bff',
                        borderWidth: 1,
                        borderRadius: 20,
                        barPercentage: 0.5,
                        fill: true
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    legend: {
                        display: false,
                        labels: {
                            display: false
                        }
                    },
                    responsive: true,
                    animation: {
                        onComplete: () => {
                            delayed2 = true;
                        },
                        delay: (context) => {
                            let delay = 0;
                            if (context.type === 'data' && context.mode === 'default' && !delayed2) {
                                delay = context.dataIndex * 50 + context.datasetIndex * 5;
                            }
                            return delay;
                        },
                    },
                    scales: {
                        y: {
                            stacked: true,
                            ticks: {
                                beginAtZero: true,
                                font: {
                                    size: 11
                                },
                                stepSize: 100000,
                            },
                            grid: {
                                color: '#ebecf1',
                                borderDash: [3, 2]
                            }
                        },
                        x: {
                            stacked: true,
                            ticks: {
                                font: {
                                    size: 11
                                }
                            },
                            grid: {
                                color: '#ebecf1',
                                borderDash: [3, 2]
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            cornerRadius: 10,
                            xPadding: 10,
                            yPadding: 10,
                            backgroundColor: '#000000',
                            titleColor: '#FF9D00',
                            yAlign: 'bottom',
                            xAlign: 'center',
                        },
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 10,
                                font: {
                                    size: 10
                                }
                            }
                        }
                    }
                }
            });


            var fileData = JSON.parse(`<?php echo $chart_data['file_minutes']; ?>`);
            var fileDataset = Object.values(fileData);
            var recordData = JSON.parse(`<?php echo $chart_data['record_minutes']; ?>`);
            var recordDataset = Object.values(recordData);
            var liveData = JSON.parse(`<?php echo $chart_data['live_minutes']; ?>`);
            var liveDataset = Object.values(liveData);

            var ctxMinutes = document.getElementById('chart-user-minutes');
            new Chart(ctxMinutes, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: '{{ __('Audio Transcribe Minutes') }}',
                        data: fileDataset,
                        backgroundColor: '#007bff',
                        borderWidth: 1,
                        borderRadius: 20,
                        barPercentage: 0.5,
                        fill: true
                    }, {
                        label: '{{ __('Recording Transcribe Minutes') }}',
                        data: recordDataset,
                        backgroundColor: '#1e1e2d',
                        borderWidth: 1,
                        borderRadius: 20,
                        barPercentage: 0.5,
                        fill: true
                    }, {
                        label: '{{ __('Live Transcribe Minutes') }}',
                        data: liveDataset,
                        backgroundColor: '#FFAB00',
                        borderWidth: 1,
                        borderRadius: 20,
                        barPercentage: 0.5,
                        fill: true
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    legend: {
                        display: false,
                        labels: {
                            display: false
                        }
                    },
                    responsive: true,
                    animation: {
                        onComplete: () => {
                            delayed2 = true;
                        },
                        delay: (context) => {
                            let delay = 0;
                            if (context.type === 'data' && context.mode === 'default' && !delayed2) {
                                delay = context.dataIndex * 50 + context.datasetIndex * 5;
                            }
                            return delay;
                        },
                    },
                    scales: {
                        y: {
                            stacked: true,
                            ticks: {
                                beginAtZero: true,
                                font: {
                                    size: 11
                                },
                                stepSize: 50,
                            },
                            grid: {
                                color: '#ebecf1',
                                borderDash: [3, 2]
                            }
                        },
                        x: {
                            stacked: true,
                            ticks: {
                                font: {
                                    size: 11
                                }
                            },
                            grid: {
                                color: '#ebecf1',
                                borderDash: [3, 2]
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            cornerRadius: 10,
                            xPadding: 10,
                            yPadding: 10,
                            backgroundColor: '#000000',
                            titleColor: '#FF9D00',
                            yAlign: 'bottom',
                            xAlign: 'center',
                        },
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 10,
                                font: {
                                    size: 10
                                }
                            }
                        }
                    }
                }
            });

        });
        // ACTIVATE Transcription
        $(document).on('click', '.agreeTranscriptionButton', function (e) {
            console.log(21)
            e.preventDefault();

            var formData = new FormData();
            formData.append("id", $(this).attr('id'));

            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                method: 'post',
                url: '/admin/user-projects-permission-request-approved',
                data: formData,
                processData: false,
                contentType: false,
                success: function (data) {
                    if (data == 'success') {
                        Swal.fire('{{ __('Permission Completed') }}', '{{ __('Permission of selected user has been passed successfully') }}', 'success');
                    } else {
                        Swal.fire('{{ __('Permission Already Completed') }}', '{{ __('Permission of selected user is already activated') }}', 'error');
                    }
                    location.reload()

                },
                error: function (data) {
                    Swal.fire({type: 'error', title: 'Oops...', text: 'Something went wrong!'})
                }
            })

        });


        // DEACTIVATE Transcription
        $(document).on('click', '.disagreeTranscriptionButton', function (e) {
            console.log(21)

            e.preventDefault();

            var formData = new FormData();
            formData.append("id", $(this).attr('id'));

            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                method: 'post',
                url: '/admin/user-projects-permission-request-disagree',
                data: formData,
                processData: false,
                contentType: false,
                success: function (data) {
                    if (data == 'success') {
                        Swal.fire('{{ __('Permission Failed') }}', '{{ __('Permission of selected user has been failed successfully') }}', 'success');
                    } else {
                        Swal.fire('{{ __('Permission Already Failed') }}', '{{ __('Permission of selected user is already failed') }}', 'error');
                    }
                    location.reload()

                },
                error: function (data) {
                    Swal.fire({type: 'error', title: 'Oops...', text: 'Something went wrong!'})
                }
            })

        });
    </script>
@endsection
