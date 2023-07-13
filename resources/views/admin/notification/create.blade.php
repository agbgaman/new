@extends('layouts.app')

@section('css')
	<!-- Awselect CSS -->
	<link href="{{URL::asset('plugins/awselect/awselect.min.css')}}" rel="stylesheet" />
	<link href="{{URL::asset('plugins/bootstrap-3.3.4/bootstrap.min.css')}}" rel="stylesheet" />
	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet"/>
@endsection

@section('page-header')
	<!-- PAGE HEADER -->
	<div class="page-header mt-5-7">
		<div class="page-leftheader">
			<h4 class="page-title mb-0">{{ __('New Mass Notification') }}</h4>
			<ol class="breadcrumb mb-2">
				<li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}"><i class="fa-solid fa-message-exclamation mr-2 fs-12"></i>{{ __('Admin') }}</a></li>
				<li class="breadcrumb-item" aria-current="page"><a href="{{ route('admin.notifications') }}"> {{ __('Mass Notifications') }}</a></li>
				<li class="breadcrumb-item active" aria-current="page"><a href="{{url('#')}}"> {{ __('New Mass Notification') }}</a></li>
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
	<div class="row">
		<div class="col-lg-6 col-md-6 col-xm-12">
			<div class="card overflow-hidden border-0">
				<div class="card-header">
					<h3 class="card-title">{{ __('Create New Notification') }}</h3>
				</div>
				<div class="card-body pt-5">
					<form action="{{ route('admin.notifications.store') }}" method="POST" enctype="multipart/form-data">
						@csrf

						<div class="row">

							<div class="col-lg-6 col-md-6 col-sm-12">
								<div class="input-box">
									<h6>{{ __('Notification Type') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
									<select id="notification-type" name="notification-type" data-placeholder="{{ __('Select Notification Type') }}:">
										<option value="Info" selected>{{ __('Info') }}</option>
										<option value="Announcement">{{ __('Announcement') }}</option>
										<option value="Marketing">{{ __('Marketing') }}</option>
										<option value="Warning">{{ __('Warning') }}</option>
									</select>
									@error('notification-type')
										<p class="text-danger">{{ $errors->first('notification-type') }}</p>
									@enderror
								</div>
							</div>

							<div class="col-lg-6 col-md-6 col-sm-12">
								<div class="input-box">
									<h6>{{ __('Notification Action') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
									<select id="notification-action" name="notification-action" data-placeholder="{{ __('Select User Action Type') }}:">
										<option value="No Action Needed" selected>{{ __('No Action Needed') }}</option>
										<option value="Action Required">{{ __('Action Required') }}</option>
									</select>
									@error('notification-action')
										<p class="text-danger">{{ $errors->first('notification-action') }}</p>
									@enderror
								</div>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-12">
								<div class="input-box">
									<h6>{{ __('Country') }} </h6>
									<select id="country" name="country" data-placeholder="Select Country">
                                        <option selected>{{ __('Select Country') }}</option>
                                        @foreach(config('countries') as $value)
                                            <option value="{{ $value }}"
                                                    selected >{{ $value }}</option>
                                        @endforeach									</select>
									@error('country')
										<p class="text-danger">{{ $errors->first('country') }}</p>
									@enderror
								</div>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-12">
								<div class="input-box select_users boot_multiselect">
									<h6>{{ __('Users') }} </h6>
									<select id="users" name="user[]" data-placeholder="Select Users" multiple="multiple">
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }} - {{$user->email}}</option>
                                        @endforeach
									</select>
								</div>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-12">
								<div class="form-group">
									<label class="form-label fs-12">{{ __('Notification To') }}<span class="text-required"><i class="fa-solid fa-asterisk"></i></span></label>
								</div>
								<div class="form-group">
									<input class="form-check-input" type="checkbox" name="notification-to[]" id="panel_notification" value="Panel">
									<label class="form-label fs-12" for="panel_notification">{{ __('Panel Notification') }}</label>
								</div>
								<div class="form-group">
									<input class="form-check-input" type="checkbox" name="notification-to[]" id="whatsapp_notification" value="WhatsApp">
									<label class="form-label fs-12" for="whatsapp_notification">{{ __('WhatsApp Notification') }}</label>
								</div>
								<div class="form-group">
									<input class="form-check-input" type="checkbox" name="notification-to[]" id="email_notification" value="email">
									<label class="form-label fs-12" for="email_notification">{{ __('Email Notification') }}</label>
								</div>
								@error('notification-to')
									<p class="text-danger">{{ $errors->first('notification-to') }}</p>
								@enderror
							</div>
						</div>

						<div class="row mt-2">
							<div class="col-lg-12 col-md-12 col-sm-12">
								<div class="input-box">
									<h6>{{ __('Subject') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
									<div class="form-group">
										<input type="text" class="form-control" id="notification-subject" name="notification-subject" required>
									</div>
									@error('notification-subject')
										<p class="text-danger">{{ $errors->first('notification-subject') }}</p>
									@enderror
								</div>
							</div>
						</div>

						<div class="row mt-2">
							<div class="col-lg-12 col-md-12 col-sm-12">
								<div class="input-box">
									<h6>{{ __('Notification Message') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
									<textarea class="form-control" name="notification-message" rows="10"></textarea>
									@error('notification-message')
										<p class="text-danger">{{ $errors->first('notification-message') }}</p>
									@enderror
								</div>
							</div>
						</div>

						<!-- ACTION BUTTON -->
						<div class="border-0 text-right mb-2 mt-1">
							<a href="{{ route('admin.notifications') }}" class="btn btn-cancel mr-2">{{ __('Return') }}</a>
							<button type="submit" class="btn btn-primary">{{ __('Send') }}</button>
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
	<script type="text/javascript" src="{{URL::asset('plugins/bootstrap-3.3.4/bootstrap.min.js')}}"></script>
	<script src="{{URL::asset('plugins/bootstrap-multiselect/bootstrap-multiselect.js')}}"></script>
	<link href="{{URL::asset('plugins/bootstrap-multiselect/bootstrap-multiselect.css')}}" rel="stylesheet" />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
	<script src="{{URL::asset('js/notification.js')}}"></script>
	<script src="{{URL::asset('js/awselect.js')}}"></script>
    <!-- Ckeditor Cdn -->
    <script src="https://cdn.ckeditor.com/4.17.2/full-all/ckeditor.js"></script>
@endsection
