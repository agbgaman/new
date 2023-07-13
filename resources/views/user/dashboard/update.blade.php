@extends('layouts.app')

@section('css')
	<!-- Awselect CSS -->
	<link href="{{URL::asset('plugins/awselect/awselect.min.css')}}" rel="stylesheet" />
	<!-- Sweet Alert CSS -->
	<link href="{{URL::asset('plugins/sweetalert/sweetalert2.min.css')}}" rel="stylesheet" />
@endsection

@section('page-header')
	<!-- EDIT PAGE HEADER -->
	<div class="page-header mt-5-7">
		<div class="page-leftheader">
			<h4 class="page-title mb-0">{{ __('Change Default Settings') }}</h4>
			<ol class="breadcrumb mb-2">
				<li class="breadcrumb-item"><a href="{{route('user.dashboard')}}"><i class="fa-solid fa-chart-tree-map mr-2 fs-12"></i>{{ __('User') }}</a></li>
				<li class="breadcrumb-item" aria-current="page"><a href="{{url('#')}}"> {{ __('My Dashboard') }}</a></li>
				<li class="breadcrumb-item active" aria-current="page"><a href="{{url('#')}}"> {{ __('Change Defaults') }}</a></li>
			</ol>
		</div>
	</div>
	<!-- END PAGE HEADER -->
@endsection

@section('content')
	<!-- EDIT USER PROFILE PAGE -->
	<div class="row">
		<div class="col-xl-3 col-lg-4 col-sm-12">
			<div class="card border-0" id="dashboard-background">
				<div class="widget-user-image overflow-hidden mx-auto mt-5"><img alt="User Avatar" class="rounded-circle" src="@if(auth()->user()->profile_photo_path){{ asset(auth()->user()->profile_photo_path) }} @else {{ URL::asset('img/users/avatar.jpg') }} @endif"></div>
				<div class="card-body text-center">
					<div>
						<h4 class="mb-1 mt-1 font-weight-bold fs-16">{{ auth()->user()->name }}</h4>
						<h6 class="text-muted fs-12">{{ auth()->user()->job_role }}</h6>
						<a href="{{ route('user.dashboard') }}" class="btn btn-primary mt-3 mb-2">{{ __('View Dashboard') }}</a>
					</div>
				</div>
			</div>
		</div>

		<div class="col-xl-9 col-lg-8 col-sm-12">
			<form method="POST" class="w-100" action="{{ route('user.dashboard.update.defaults', [auth()->user()->id]) }}" enctype="multipart/form-data">
				@method('PUT')
				@csrf

				<div class="card border-0">
					<div class="card-header">
						<h3 class="card-title"><i class="fa-sharp fa-solid fa-waveform-lines mr-3 text-info"></i> {{ __('Voiceover Studio Default Language & Voice') }}</h3>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-md-6 col-sm-12">
								<!-- LANGUAGE -->
								<div class="input-box">	
									<h6>{{ __('Default Language') }}</h6>
									<select id="languages" name="language" data-placeholder="Select Default Language:" data-callback="language_select">			
										@foreach ($languages as $language)
											<option value="{{ $language->language_code }}" data-img="{{ URL::asset($language->language_flag) }}" @if (auth()->user()->language == $language->language_code) selected @endif> {{ $language->language }}</option>
										@endforeach
									</select>
								</div> <!-- END LANGUAGE -->
							</div>

							<div class="col-md-6 col-sm-12">
								<!-- VOICE -->
								<div class="input-box">	
									<h6>{{ __('Default Voice') }}</h6>
									<select id="voices" name="voice" data-placeholder="Select Default Voice">			
										@foreach ($voices as $voice)
											<option value="{{ $voice->voice_id }}" 		
												data-img="{{ URL::asset($voice->avatar_url) }}"										
												data-id="{{ $voice->voice_id }}" 
												data-lang="{{ $voice->language_code }}" 
												data-type="{{ $voice->voice_type }}"
												data-gender="{{ $voice->gender }}"
												@if (config('tts.user_neural') == 'disable')
													data-usage= "@if ((auth()->user()->group == 'user') && ($voice->voice_type == 'neural')) avoid-clicks @endif"	
												@endif																							
												@if (auth()->user()->voice == $voice->voice_id) selected @endif
												data-class="@if (auth()->user()->language !== $voice->language_code) remove-voice @endif"> 
												{{ $voice->voice }}  														
											</option>
										@endforeach
									</select>
								</div> <!-- END VOICE -->
							</div>
						</div>					
					</div>
				</div>

				<div class="card border-0">
					<div class="card-header">
						<h3 class="card-title"><i class="fa-sharp fa-solid fa-microphone-lines mr-3 text-info"></i>{{ __('Transcribe Studio Default Languages') }}</h3>
					</div>
					<div class="card-body pb-0">					
						<div class="row">
							<div class="col-lg-6 col-md-6 col-sm-12">
								<div class="input-box">	
									<h6>{{ __('Default Language') }} <span class="text-muted">({{ __('File & Record Transcribe') }})</span></h6>
									<select id="set-voice-types" name="language_file" data-placeholder="Select default language for record & audio file transribe">
										@foreach ($languages_file as $language)
											<option value="{{ $language->id }}" data-img="{{ URL::asset($language->language_flag) }}" @if (config('stt.vendor_logos') == 'show') data-vendor="{{ URL::asset($language->vendor_img) }}" @endif @if (auth()->user()->language_file == $language->id) selected @endif> {{ $language->language }}</option>
										@endforeach	
									</select>
								</div> 					
							</div>

							<div class="col-lg-6 col-md-6 col-sm-12">
								<div class="input-box">	
									<h6>{{ __('Default Language') }} <span class="text-muted">({{ __('Live Transcribe') }})</span></h6>
									<select id="set-ssml-effects" name="language_live" data-placeholder="Select default language for live transribe">			
										@foreach ($languages_live as $language)
											<option value="{{ $language->id }}" data-img="{{ URL::asset($language->language_flag) }}" @if (config('stt.vendor_logos') == 'show') data-vendor="{{ URL::asset($language->vendor_img) }}" @endif @if (auth()->user()->language_live == $language->id) selected @endif> {{ $language->language }}</option>
										@endforeach	
									</select>
								</div> 						
							</div>						
						</div>
					</div>
				</div>

				<div class="card border-0">
					<div class="card-header">
						<h3 class="card-title">{{ __('Default Project') }}</h3>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-md-6 col-sm-12">
								<div class="input-box">	
									<h6>{{ __('Project Name') }}</h6>								
									<select id="project" name="project" data-placeholder="{{ __('Select Default Project Name') }}">	
										@foreach ($projects as $project)
											<option value="{{ $project->name }}" @if (auth()->user()->project == $project->name) selected @endif> {{ ucfirst($project->name) }}</option>
										@endforeach											
									</select>
									@error('project')
										<p class="text-danger">{{ $errors->first('project') }}</p>
									@enderror	
								</div>
							</div>

							<div class="col-md-6 col-sm-12 pt-align">
								<div class="dropdown mt-1">
									<button class="btn btn-special create-project mr-4" type="button" id="add-project" data-tippy-content="{{ __('Create New Project') }}"><i class="fa-solid fa-rectangle-history-circle-plus"></i></button>																																
								</div>
							</div>
						</div>
						<div class="card-footer border-0 text-center">
							<button type="submit" class="btn btn-primary">{{ __('Save') }}</button>							
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
	<script src="{{URL::asset('plugins/sweetalert/sweetalert2.all.min.js')}}"></script>
	<script src="{{URL::asset('js/awselect.js')}}"></script>
	<script>
		$(function() {
			"use strict";
			
			// CREATE NEW PROJECT
			$(document).on('click', '#add-project', function(e) {

				e.preventDefault();

				Swal.fire({
					title: '{{ __('Create New Project') }}',
					showCancelButton: true,
					confirmButtonText: 'Create',
					reverseButtons: true,
					closeOnCancel: true,
					input: 'text',
				}).then((result) => {
					if (result.value) {
						var formData = new FormData();
						formData.append("new-project", result.value);
						$.ajax({
							headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
							method: 'post',
							url: 'defaults/project',
							data: formData,
							processData: false,
							contentType: false,
							success: function (data) {
								if (data['status'] == 'success') {
									Swal.fire('{{ __('Project Created') }}', '{{ __('New project has been successfully created') }}', 'success');	
									location.reload();								
								} else {
									Swal.fire('{{ __('Project Creation Error') }}', data['message'], 'error');
								}      
							},
							error: function(data) {
								Swal.fire({ type: 'error', title: 'Oops...', text: '{{ __('Something went wrong') }}!' })
							}
						})
					} else if (result.dismiss !== Swal.DismissReason.cancel) {
						Swal.fire('{{ __('No Project Name Entered') }}', '{{ __('Make sure to provide a new project name before creating') }}', 'error')
					}
				})
			});
		});
	</script>
@endsection