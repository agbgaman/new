@extends('layouts.app')

@section('css')
	<!-- Data Table CSS -->
	<link href="{{URL::asset('plugins/awselect/awselect.min.css')}}" rel="stylesheet" />
@endsection

@section('page-header')
	<!-- PAGE HEADER -->
	<div class="page-header mt-5-7">
		<div class="page-leftheader">
			<h4 class="page-title mb-0">{{ __('Transcribe Studio Settings') }}</h4>
			<ol class="breadcrumb mb-2">
				<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-boxes-packing mr-2 fs-12"></i>{{ __('Admin') }}</a></li>
				<li class="breadcrumb-item" aria-current="page"><a href="{{ route('admin.studio.dashboard') }}"> {{ __('Studio Management') }}</a></li>
				<li class="breadcrumb-item active" aria-current="page"><a href="#"> {{ __('Transcribe Studio Settings') }}</a></li>
			</ol>
		</div>
	</div>
	<!-- END PAGE HEADER -->
@endsection
@section('content')
	<!-- ALL CSP CONFIGURATIONS -->
	<div class="row">
		<div class="col-lg-8 col-md-12 col-xm-12">
			<div class="card overflow-hidden border-0">
				<div class="card-header">
					<h3 class="card-title">{{ __('Transcribe Studio Settings') }}</h3>
				</div>
				<div class="card-body">

					<!-- STT SETTINGS FORM -->
					<form action="{{ route('admin.transcribe.settings.store') }}" method="POST" enctype="multipart/form-data">
						@csrf

						<div class="row">
							<div class="col-lg-6 col-md-6 col-sm-12">
								<div class="input-box">
									<h6>{{ __('Default Language') }} <span class="text-muted">({{ __('Record & File Transcribe') }})</span></h6>
			  						<select id="set-voice-types" name="set-language-file" data-placeholder="Select default language for record & file transribe">
										@foreach ($languages_file as $language)
											<option value="{{ $language->id }}" data-img="{{ URL::asset($language->language_flag) }}" @if (config('stt.vendor_logos') == 'show') data-vendor="{{ URL::asset($language->vendor_img) }}" @endif @if (config('stt.language.file') == $language->id) selected @endif> {{ $language->language }}</option>
										@endforeach
									</select>
								</div>
							</div>

							<div class="col-lg-6 col-md-6 col-sm-12">
								<div class="input-box">
									<h6>{{ __('Default Language') }} <span class="text-muted">({{ __('Live Transcribe') }})</span></h6>
			  						<select id="set-ssml-effects" name="set-language-live" data-placeholder="Select default language for live transribe">
										@foreach ($languages_live as $language)
											<option value="{{ $language->id }}" data-img="{{ URL::asset($language->language_flag) }}" @if (config('stt.vendor_logos') == 'show') data-vendor="{{ URL::asset($language->vendor_img) }}" @endif @if (config('stt.language.live') == $language->id) selected @endif> {{ $language->language }}</option>
										@endforeach
									</select>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-lg-6 col-md-6 col-sm-12">
								<div class="input-box">
									<h6>{{ __('Maximum Audio File Size') }} <span class="text-muted">({{ __('in MB') }})</span> <i class="ml-2 fa fa-info info-notification" data-tippy-content="{{ __('Maximum audio file size for AWS can be up to 2GB. It is a hard limit by AWS. GCP do not have hard limits on audio file sizes') }}."></i></h6>
									<div class="form-group">
										<input type="text" class="form-control @error('set-max-size') is-danger @enderror" id="set-max-size" name="set-max-size" placeholder="Ex: 10" value="{{ config('stt.max_size_limit') }}" required>
										@error('set-max-size')
											<p class="text-danger">{{ $errors->first('set-max-size') }}</p>
										@enderror
									</div>
								</div>
							</div>

							<div class="col-lg-6 col-md-6 col-sm-12">
								<div class="input-box">
									<h6>{{ __('Allowed Audio File Formats') }} <span class="text-muted">({{ __('Comma Separated') }})</span> <i class="ml-2 fa fa-info info-notification" data-tippy-content="AWS supported audio formats: MP3 | MP4 | FLAC | WAV | OGG | WebM. GCP supported audio formats: FLAC | WAV"></i></h6>
									<div class="form-group">
										<input type="text" class="form-control @error('set-file-format') is-danger @enderror" id="set-file-format" name="set-file-format" placeholder="Ex: 10" value="{{ config('stt.file_format') }}" required>
										@error('set-file-format')
											<p class="text-danger">{{ $errors->first('set-file-format') }}</p>
										@enderror
									</div>
								</div>
							</div>

							<div class="col-lg-6 col-md-6 col-sm-12">
								<div class="input-box">
									<h6>{{ __('Max Audio Length for Record & File Transcribe') }} <span class="text-muted">({{ __('in Minutes') }}) ({{ __('for User') }})</span> <i class="ml-2 fa fa-info info-notification" data-tippy-content="{{ __('Maximum audio length for AWS is 240 Minutes. Maximum audio length for GCP is 480 Minutes. It is a hard limit set by vendors') }}."></i></h6>
									<div class="form-group">
										<input type="text" class="form-control @error('set-max-length-file-none') is-danger @enderror" id="set-max-length-file-none" name="set-max-length-file-none" placeholder="Ex: 240" value="{{ config('stt.max_length_limit_file_none') }}" required>
										@error('set-max-length-file-none')
											<p class="text-danger">{{ $errors->first('set-max-length-file-none') }}</p>
										@enderror
									</div>
								</div>
							</div>

							<div class="col-lg-6 col-md-6 col-sm-12">
								<div class="input-box">
									<h6>{{ __('Max Audio Length for Record & File Transcribe') }} <span class="text-muted">({{ __('in Minutes') }}) ({{ __('for Subscribers & Admins') }})</span> <i class="ml-2 fa fa-info info-notification" data-tippy-content="{{ __('Maximum audio length for AWS is 240 Minutes. Maximum audio length for GCP is 480 Minutes. It is a hard limit set by vendors') }}."></i></h6>
									<div class="form-group">
										<input type="text" class="form-control @error('set-max-length-file') is-danger @enderror" id="set-max-length-file" name="set-max-length-file" placeholder="Ex: 240" value="{{ config('stt.max_length_limit_file') }}" required>
										@error('set-max-length-file')
											<p class="text-danger">{{ $errors->first('set-max-length-file') }}</p>
										@enderror
									</div>
								</div>
							</div>

							<div class="col-lg-6 col-md-6 col-sm-12">
								<div class="input-box">
									<h6>{{ __('Max Audio Length for Live Transcribe') }} <span class="text-muted">({{ __('in Seconds') }}) ({{ __('for Users') }}) <i class="ml-2 fa fa-info info-notification" data-tippy-content="{{ __('Only for AWS. Can be set up to 240 Minutes') }}."></i></span></h6>
									<div class="form-group">
										<input type="text" class="form-control @error('set-max-length-live-none') is-danger @enderror" id="set-max-length-live-none" name="set-max-length-live-none" placeholder="Ex: 240" value="{{ config('stt.max_length_limit_live_none') }}" required>
										@error('set-max-length-live-none')
											<p class="text-danger">{{ $errors->first('set-max-length-live-none') }}</p>
										@enderror
									</div>
								</div>
							</div>

							<div class="col-lg-6 col-md-6 col-sm-12">
								<div class="input-box">
									<h6>{{ __('Max Audio Length for Live Transcribe') }} <span class="text-muted">({{ __('in Minutes') }}) ({{ __('for Subscribers & Admins') }}) <i class="ml-2 fa fa-info info-notification" data-tippy-content="{{ __('Only for AWS. Can be set up to 240 Minutes') }}."></i></span></h6>
									<div class="form-group">
										<input type="text" class="form-control @error('set-max-length-live') is-danger @enderror" id="set-max-length-live" name="set-max-length-live" placeholder="Ex: 240" value="{{ config('stt.max_length_limit_live') }}" required>
										@error('set-max-length-live')
											<p class="text-danger">{{ $errors->first('set-max-length-live') }}</p>
										@enderror
									</div>
								</div>
							</div>
						</div>


						<div class="row">
							<div class="col-lg-6 col-md-6 col-sm-12">
								<div class="input-box">
									<h6>{{ __('Cloud Vendor Logos') }} <span class="text-muted">({{ __('Only for User Panel') }})</span></h6>
			  						<select id="vendor-logo" name="vendor-logo" data-placeholder="Show or Hide Vendor Logos on User side">
										<option value="show" @if ( config('stt.vendor_logos')  == 'show') selected @endif>{{ __('Show') }}</option>
										<option value="hide" @if ( config('stt.vendor_logos')  == 'hide') selected @endif>{{ __('Hide') }}</option>
									</select>
								</div>
							</div>

							<div class="col-lg-6 col-md-6 col-sm-12">
								<div class="input-box">
									<h6>{{ __('Speaker Identification') }} <span class="text-muted">({{ __('Default Mode') }}) <i class="ml-2 fa fa-info info-notification" data-tippy-content="{{ __('Most GCP Languages do not support this feature. All AWS Languages support this feature') }}."></i></span></h6>
			  						<select id="speaker-identification" name="speaker-identification" data-placeholder="Change speaker identification default mode">
										<option value='enable' @if ( config('stt.speaker_identification')  == 'enable') selected @endif>{{ __('Enable') }}</option>
										<option value='disable' @if ( config('stt.speaker_identification')  == 'disable') selected @endif>{{ __('Disable') }}</option>
									</select>
								</div>
							</div>

							<div class="col-lg-6 col-md-6 col-sm-12">
								<div class="input-box">
									<h6>{{ __('Live Transcription TextArea') }} <span class="text-muted">({{ __('Default Mode') }}) <i class="ml-2 fa fa-info info-notification" data-tippy-content="{{ __('You can enable Transcription area box in live Transcription') }}."></i></span></h6>
			  						<select id="featured" name="live-transcription-text-area" data-placeholder="Live Transcription TextArea">
										<option value='enable' @if ( config('stt.live_transcription_text_area')  == 'enable') selected @endif>{{ __('Enable') }}</option>
										<option value='disable' @if ( config('stt.live_transcription_text_area')  == 'disable') selected @endif>{{ __('Disable') }}</option>
									</select>
								</div>
							</div>
						</div>

						<div class="card border-0 special-shadow mb-7">
							<div class="card-body">

								<h6 class="fs-12 font-weight-bold mb-4"><i class="fa fa-gift text-info fs-14 mr-2"></i>{{ __('Free Tier Options') }} <span class="text-muted">({{ __('User Group') }})</span></h6>

								<div class="row">
									<div class="col-lg-6 col-md-6 col-sm-12">
										<!-- FREE TIER MINUTES -->
										<div class="input-box">
											<h6>{{ __('Free Welcome Minutes') }} <span class="text-muted">({{ __('For New Registered Users') }})</span></h6>
											<div class="form-group">
												<input type="text" class="form-control @error('free-minutes') is-danger @enderror" id="free-minutes" name="free-minutes" placeholder="Ex: 2000" value="{{ config('stt.free_minutes') }}" required>
												@error('free-minutes')
													<p class="text-danger">{{ $errors->first('free-minutes') }}</p>
												@enderror
											</div>
										</div> <!-- END FREE TIER MINUTES -->
									</div>
								</div>
							</div>
						</div>

						<div class="card border-0 special-shadow">
							<div class="card-body">
								<h6 class="fs-12 font-weight-bold mb-4"><img src="{{URL::asset('img/csp/aws-sm.png')}}" class="fw-2 mr-2" alt="">Amazon Web Services</h6>

								<div class="form-group">
									<label class="custom-switch">
										<input type="checkbox" name="enable-aws" class="custom-switch-input" @if ( config('stt.enable.aws')  == 'on') checked @endif>
										<span class="custom-switch-indicator"></span>
										<span class="custom-switch-description">{{ __('Use AWS') }}</span>
									</label>
								</div>

								<div class="form-group mb-4">
									<label class="custom-switch">
										<input type="checkbox" name="enable-aws-live" class="custom-switch-input" @if ( config('stt.enable.aws_live')  == 'on') checked @endif>
										<span class="custom-switch-indicator"></span>
										<span class="custom-switch-description">{{ __('Enable Live Transcription Feature') }}</span>
									</label>
								</div>

								<div class="row">
									<div class="col-lg-6 col-md-6 col-sm-12">
										<!-- ACCESS KEY -->
										<div class="input-box">
											<h6>AWS Access Key</h6>
											<div class="form-group">
												<input type="text" class="form-control @error('set-aws-access-key') is-danger @enderror" id="aws-access-key" name="set-aws-access-key" value="{{ config('services.aws.key') }}" autocomplete="off">
												@error('set-aws-access-key')
													<p class="text-danger">{{ $errors->first('set-aws-access-key') }}</p>
												@enderror
											</div>
										</div> <!-- END ACCESS KEY -->
									</div>

									<div class="col-lg-6 col-md-6 col-sm-12">
										<!-- SECRET ACCESS KEY -->
										<div class="input-box">
											<h6>AWS Secret Access Key</h6>
											<div class="form-group">
												<input type="text" class="form-control @error('set-aws-secret-access-key') is-danger @enderror" id="aws-secret-access-key" name="set-aws-secret-access-key" value="{{ config('services.aws.secret') }}" autocomplete="off">
												@error('set-aws-secret-access-key')
													<p class="text-danger">{{ $errors->first('set-aws-secret-access-key') }}</p>
												@enderror
											</div>
										</div> <!-- END SECRET ACCESS KEY -->
									</div>

									<div class="col-lg-6 col-md-6 col-sm-12">
										<!-- ACCESS KEY -->
										<div class="input-box">
											<h6>Amazon S3 Bucket Name</small></h6>
											<div class="form-group">
												<input type="text" class="form-control @error('set-aws-bucket') is-danger @enderror" id="aws-bucket" name="set-aws-bucket" value="{{ config('services.aws.bucket') }}" autocomplete="off">
												@error('set-aws-bucket')
													<p class="text-danger">{{ $errors->first('set-aws-bucket') }}</p>
												@enderror
											</div>
										</div> <!-- END ACCESS KEY -->
									</div>

									<div class="col-lg-6 col-md-6 col-sm-12">
										<!-- AWS REGION -->
										<div class="input-box">
											<h6>Set AWS Region</h6>
											  <select id="set-aws-region" name="set-aws-region" data-placeholder="Select Default AWS Region:">
												<option value="us-east-1" @if ( config('services.aws.region')  == 'us-east-1') selected @endif>US East (N. Virginia) us-east-1</option>
												<option value="us-east-2" @if ( config('services.aws.region')  == 'us-east-2') selected @endif>US East (Ohio) us-east-2</option>
												<option value="us-west-1" @if ( config('services.aws.region')  == 'us-west-1') selected @endif>US West (N. California) us-west-1</option>
												<option value="us-west-2" @if ( config('services.aws.region')  == 'us-west-2') selected @endif>US West (Oregon) us-west-2</option>
												<option value="ap-east-1" @if ( config('services.aws.region')  == 'ap-east-1') selected @endif>Asia Pacific (Hong Kong) ap-east-1</option>
												<option value="ap-south-1" @if ( config('services.aws.region')  == 'ap-south-1') selected @endif>Asia Pacific (Mumbai) ap-south-1</option>
												<option value="ap-northeast-3" @if ( config('services.aws.region')  == 'ap-northeast-3') selected @endif>Asia Pacific (Osaka-Local) ap-northeast-3</option>
												<option value="ap-northeast-2" @if ( config('services.aws.region')  == 'ap-northeast-2') selected @endif>Asia Pacific (Seoul) ap-northeast-2</option>
												<option value="ap-southeast-1" @if ( config('services.aws.region')  == 'ap-southeast-1') selected @endif>Asia Pacific (Singapore) ap-southeast-1</option>
												<option value="ap-southeast-2" @if ( config('services.aws.region')  == 'ap-southeast-2') selected @endif>Asia Pacific (Sydney) ap-southeast-2</option>
												<option value="ap-northeast-1" @if ( config('services.aws.region')  == 'ap-northeast-1') selected @endif>Asia Pacific (Tokyo) ap-northeast-1</option>
												<option value="eu-central-1" @if ( config('services.aws.region')  == 'eu-central-1') selected @endif>Europe (Frankfurt) eu-central-1</option>
												<option value="eu-west-1" @if ( config('services.aws.region')  == 'eu-west-1') selected @endif>Europe (Ireland) eu-west-1</option>
												<option value="eu-west-2" @if ( config('services.aws.region')  == 'eu-west-2') selected @endif>Europe (London) eu-west-2</option>
												<option value="eu-south-1" @if ( config('services.aws.region')  == 'eu-south-1') selected @endif>Europe (Milan) eu-south-1</option>
												<option value="eu-west-3" @if ( config('services.aws.region')  == 'eu-west-3') selected @endif>Europe (Paris) eu-west-3</option>
												<option value="eu-north-1" @if ( config('services.aws.region')  == 'eu-north-1') selected @endif>Europe (Stockholm) eu-north-1</option>
												<option value="me-south-1" @if ( config('services.aws.region')  == 'me-south-1') selected @endif>Middle East (Bahrain) me-south-1</option>
												<option value="sa-east-1" @if ( config('services.aws.region')  == 'sa-east-1') selected @endif>South America (São Paulo) sa-east-1</option>
												<option value="ca-central-1" @if ( config('services.aws.region')  == 'ca-central-1') selected @endif>Canada (Central) ca-central-1</option>
												<option value="af-south-1" @if ( config('services.aws.region')  == 'af-south-1') selected @endif>Africa (Cape Town) af-south-1</option>
											</select>
										</div> <!-- END AWS REGION -->
									</div>

								</div>

							</div>
						</div>


						<div class="card overflow-hidden border-0 special-shadow">
							<div class="card-body">

								<h6 class="fs-12 font-weight-bold mb-4"><img src="{{URL::asset('img/csp/gcp-sm.png')}}" class="fw-2 mr-2" alt="">{{ __('GCP Settings') }}</h6>

								<div class="form-group mb-4">
									<label class="custom-switch">
										<input type="checkbox" name="enable-gcp" class="custom-switch-input" @if ( config('stt.enable.gcp')  == 'on') checked @endif>
										<span class="custom-switch-indicator"></span>
										<span class="custom-switch-description">{{ __('Use GCP') }}</span>
									</label>
								</div>

								<div class="row">
									<div class="col-lg-6 col-md-6 col-sm-12">
										<!-- ACCESS KEY -->
										<div class="input-box">
											<h6>{{ __('GCP Configuration File Path') }}</h6>
											<div class="form-group">
												<input type="text" class="form-control @error('gcp-configuration-path') is-danger @enderror" id="gcp-configuration-path" name="gcp-configuration-path" value="{{ config('services.gcp.key_path') }}" autocomplete="off">
												@error('gcp-configuration-path')
													<p class="text-danger">{{ $errors->first('gcp-configuration-path') }}</p>
												@enderror
											</div>
										</div> <!-- END ACCESS KEY -->
									</div>
									<div class="col-lg-6 col-md-6 col-sm-12">
										<div class="input-box">
											<h6>GCP Storage Bucket Name</h6>
											<div class="form-group">
												<input type="text" class="form-control @error('gcp-bucket') is-danger @enderror" id="gcp-bucket" name="gcp-bucket" value="{{ config('services.gcp.bucket') }}" autocomplete="off">
												@error('gcp-bucket')
													<p class="text-danger">{{ $errors->first('gcp-bucket') }}</p>
												@enderror
											</div>
										</div>
									</div>
								</div>

							</div>
						</div>


						<!-- SAVE CHANGES ACTION BUTTON -->
						<div class="border-0 text-right mb-2 mt-1">
							<a href="{{ route('admin.studio.dashboard') }}" class="btn btn-cancel mr-2">{{ __('Cancel') }}</a>
							<button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
						</div>

					</form>
					<!-- END STT SETTINGS FORM -->
				</div>
			</div>
		</div>
	</div>
	<!-- END ALL CSP CONFIGURATIONS -->
@endsection

@section('js')
	<!-- Awselect JS -->
	<script src="{{URL::asset('plugins/awselect/awselect-custom.js')}}"></script>
	<script src="{{URL::asset('js/awselect.js')}}"></script>
@endsection
