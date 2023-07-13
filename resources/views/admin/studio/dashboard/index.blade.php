@extends('layouts.app')

@section('page-header')
	<!--PAGE HEADER -->
	<div class="page-header mt-5-7">
		<div class="page-leftheader">
			<h4 class="page-title mb-0">{{ __('Studio Dashboard') }}</h4>
			<ol class="breadcrumb mb-2">
				<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-boxes-packing mr-2 fs-12"></i>{{ __('Admin') }}</a></li>
				<li class="breadcrumb-item" aria-current="page"><a href="{{ route('admin.studio.dashboard') }}"> {{ __('Studio Management') }}</a></li>
				<li class="breadcrumb-item active" aria-current="page"><a href="#"> {{ __('Studio Dashboard') }}</a></li>
			</ol>
		</div>
	</div>
	<!--END PAGE HEADER -->
@endsection

@section('content')	
	<div class="row">
		<div class="col-lg-12 col-md-12">
			<div class="card overflow-hidden border-0">
				<div class="card-header">
					<h3 class="card-title"><i class="fa-sharp fa-solid fa-waveform-lines mr-2 fs-12"></i>{{ __('Voiceover Studio Usage') }} <span class="text-muted">({{ __('Current Year') }})</span></h3>
				</div>
				<div class="card-body pb-0">
					<div class="row">
						<div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
							<div class="card overflow-hidden special-shadow border-0 mt-2">
								<div class="card-body">
									<div class="d-flex align-items-end justify-content-between">
										<div>
											<p class=" mb-3 fs-12 font-weight-bold">{{ __('AWS Characters Used') }} <span class="text-muted">({{ __('Current Month') }})</span></p>
											<h2 class="mb-0"><span class="number-font-chars">{{ number_format($vendor_data['aws_month'][0]['data']) }}</span></h2>									
										</div>
										<img src="{{URL::asset('img/csp/aws-lg.png')}}" class="csp-brand-img" alt="AWS Logo">
									</div>
									<div class="d-flex mt-2">
										<div>
											<span class="text-muted fs-12 mr-1">{{ __('Current Year') }} ({{ __('Total Usage') }}):</span>
											<span class="number-font fs-12"><i class="fa fa-bookmark mr-1 text-info"></i>{{ number_format($vendor_data['aws_year'][0]['data']) }}</span>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
							<div class="card overflow-hidden special-shadow border-0 mt-2">
								<div class="card-body">
									<div class="d-flex align-items-end justify-content-between">
										<div>
											<p class=" mb-3 fs-12 font-weight-bold">{{ __('GCP Characters Used') }} <span class="text-muted">({{ __('Current Month') }})</span></p>
											<h2 class="mb-0"><span class="number-font-chars">{{ number_format($vendor_data['gcp_month'][0]['data']) }}</span></h2>
										</div>
										<img src="{{URL::asset('img/csp/gcp-lg.png')}}" class="csp-brand-img" alt="GCP Logo">
									</div>
									<div class="d-flex mt-2">
										<div>
											<span class="text-muted fs-12 mr-1">{{ __('Current Year') }} ({{ __('Total Usage') }})</span>
											<span class="number-font fs-12"><i class="fa fa-bookmark mr-1 text-info"></i>{{ number_format($vendor_data['gcp_year'][0]['data']) }}</span>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
							<div class="card overflow-hidden special-shadow border-0 mt-2">
								<div class="card-body">
									<div class="d-flex align-items-end justify-content-between">
										<div>
											<p class=" mb-3 fs-12 font-weight-bold">{{ __('Azure Characters Used') }} <span class="text-muted">({{ __('Current Month') }})</span></p>
											<h2 class="mb-0"><span class="number-font-chars">{{ number_format($vendor_data['azure_month'][0]['data']) }}</span></h2>
										</div>
										<img src="{{URL::asset('img/csp/azure-lg.png')}}" class="csp-brand-img" alt="Azure Logo">
									</div>
									<div class="d-flex mt-2">
										<div>
											<span class="text-muted fs-12 mr-1">{{ __('Current Year') }} ({{ __('Total Usage') }})</span>
											<span class="number-font fs-12"><i class="fa fa-bookmark mr-1 text-info"></i>{{ number_format($vendor_data['azure_year'][0]['data']) }}</span>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
							<div class="card overflow-hidden special-shadow border-0 mt-2">
								<div class="card-body">
									<div class="d-flex align-items-end justify-content-between">
										<div>
											<p class=" mb-3 fs-12 font-weight-bold">{{ __('IBM Characters Used') }} <span class="text-muted">({{ __('Current Month') }})</span></p>
											<h2 class="mb-0"><span class="number-font-chars">{{ number_format($vendor_data['ibm_month'][0]['data']) }}</span></h2>
										</div>
										<img src="{{URL::asset('img/csp/ibm-lg.png')}}" class="csp-brand-img" alt="IBM Logo">
									</div>
									<div class="d-flex mt-2">
										<div>
											<span class="text-muted fs-12 mr-1">{{ __('Current Year') }} ({{ __('Total Usage') }})</span>
											<span class="number-font fs-12"><i class="fa fa-bookmark mr-1 text-info	"></i>{{ number_format($vendor_data['ibm_year'][0]['data']) }}</span>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<!-- CSP ANALYSIS & CHARACTER USAGE METRICS -->
					<div class="row">
						<div class="col-xl-4  col-md-12">
							<div class="card overflow-hidden special-shadow border-0">
								<div class="card-header">
									<h3 class="card-title">{{ __('Cloud Vendor TTS Service Usage') }} <span class="text-muted">({{ __('Current Year') }})</span></h3>
								</div>
								<div class="card-body">
									<div class="country-card">
										<div class="mb-3">
											<div class="d-flex">
												<span class="fs-12 font-weight-semibold"><img src="{{URL::asset('img/csp/aws-sm.png')}}" class="w-5 h-5 mr-2" alt="">Amazon Web Services</span>
												<div class="ml-auto"><span class="text-success mr-1"></span><span class="number-font fs-14">{{ number_format($vendor_data['aws_year'][0]['data']) }}</span> <span class="text-muted fs-12">(<span id="aws"></span>)</span></div>
											</div>
											<div class="progress h-2  mt-1">
												<div class="progress-bar progress-bar-striped progress-bar-animated bg-warning" id="aws-bar"></div>
											</div>
										</div>
										<div class="mb-3">
											<div class="d-flex">
												<span class="fs-12 font-weight-semibold"><img src="{{URL::asset('img/csp/gcp-sm.png')}}" class="w-5 h-5 mr-2" alt="">Google Cloud Platform</span>
												<div class="ml-auto"><span class="text-success mr-1"></span><span class="number-font fs-14">{{ number_format($vendor_data['gcp_year'][0]['data']) }}</span> <span class="text-muted fs-12">(<span id="gcp"></span>)</span></div>
											</div>
											<div class="progress h-2  mt-1">
												<div class="progress-bar progress-bar-striped progress-bar-animated bg-success" id="gcp-bar"></div>
											</div>
										</div>
										<div class="mb-3">
											<div class="d-flex">
												<span class="fs-12 font-weight-semibold"><img src="{{URL::asset('img/csp/azure-sm.png')}}" class="w-5 h-5 mr-2" alt="">Microsoft Azure</span>
												<div class="ml-auto"><span class="text-danger mr-1"></span><span class="number-font fs-14">{{ number_format($vendor_data['azure_year'][0]['data']) }}</span> <span class="text-muted fs-12">(<span id="azure"></span>)</span></div>
											</div>
											<div class="progress h-2  mt-1">
												<div class="progress-bar progress-bar-striped progress-bar-animated bg-info" id="azure-bar"></div>
											</div>
										</div>
										<div class="mb-0">
											<div class="d-flex">
												<span class="fs-12 font-weight-semibold"><img src="{{URL::asset('img/csp/ibm-sm.png')}}" class="w-5 h-5 mr-2 pb-1" alt="">IBM Cloud</span>
												<div class="ml-auto"><span class="text-success mr-1"></span><span class="number-font fs-14">{{ number_format($vendor_data['ibm_year'][0]['data']) }}</span> <span class="text-muted fs-12">(<span id="ibm"></span>)</span></div>
											</div>
											<div class="progress h-2  mt-1">
												<div class="progress-bar progress-bar-striped progress-bar-animated bg-dark" id="ibm-bar"></div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-xl-8 col-md-12">
							<div class="row">
								<div class="col-lg-6 col-md-6 col-xm-12">
									<div class="card overflow-hidden special-shadow border-0">
										<div class="card-body">
											<p class=" mb-3 mt-1 fs-12">{{ __('Free Characters Used') }} <span class="text-muted">({{ __('Current Month') }})</span></p>
											<h2 class="mb-2 number-font-light">{{ number_format($tts_data_monthly['free_chars'][0]['data']) }}</h2>
											<small class="fs-12 text-muted">{{ __('Compared to Last Month') }} (<span id="free_chars_past"></span>): </small>
											<span class="fs-12" id="free_chars"></span>
										</div>									
									</div>
								</div>
								<div class="col-lg-6 col-md-6 col-xm-12">
									<div class="card overflow-hidden special-shadow border-0">
										<div class="card-body">
											<p class=" mb-3 mt-1 fs-12">{{ __('Standard Characters Used') }} <span class="text-muted">({{ __('Current Month') }})</span></p>
											<h2 class="mb-2 number-font-light">{{ number_format($tts_data_monthly['standard_chars'][0]['data']) }}</h2>
											<small class="fs-12 text-muted">{{ __('Compared to Last Month') }} (<span id="standard_chars_past"></span>):</small>
											<span class="fs-12" id="standard_chars"></span>
										</div>
									</div>
								</div>
								<div class="col-lg-6 col-md-6 col-xm-12">
									<div class="card overflow-hidden special-shadow border-0 mt-1">
										<div class="card-body">
											<p class=" mb-3 mt-1 fs-12">{{ __('Paid Characters Used') }} <span class="text-muted">({{ __('Current Month') }})</span></p>
											<h2 class="mb-2 number-font-light">{{ number_format($tts_data_monthly['paid_chars'][0]['data']) }}</h2>
											<small class="fs-12 text-muted">{{ __('Compared to Last Month') }} (<span id="paid_chars_past"></span>): </small>
											<span class="fs-12" id="paid_chars"></span>
										</div>
									</div>
								</div>				
								<div class="col-lg-6 col-md-6 col-xm-12">
									<div class="card overflow-hidden special-shadow border-0 mt-1">
										<div class="card-body">
											<p class=" mb-3 mt-1 fs-12">{{ __('Neural Characters Used ') }}<span class="text-muted">({{ __('Current Month') }})</span></p>
											<h2 class="mb-2 number-font-light">{{ number_format($tts_data_monthly['neural_chars'][0]['data']) }}</h2>
											<small class="fs-12 text-muted">{{ __('Compared to Last Month') }} (<span id="neural_chars_past"></span>):</small>
											<span class="fs-12" id="neural_chars"></span>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- END CSP ANALYSIS & CHARACTER USAGE METRICS -->

					<!-- CURRENT YEAR USAGE ANALYTICS -->
					<div class="row">
						<div class="col-lg-12 col-md-12">
							<div class="card overflow-hidden special-shadow border-0 mt-2">
								<div class="card-header">
									<h3 class="card-title">{{ __('Characters Usage') }} <span class="text-muted">({{ __('Current Year') }})</span></h3>
								</div>
								<div class="card-body">
									<div class="row mb-5 mt-2">
										<div class="col">
											<p class="mb-1 fs-12">{{ __('Total Free Characters') }}</p>
											<h3 class="mb-0 fs-20 number-font">{{ number_format($tts_data_yearly['total_free_chars'][0]['data']) }}</h3>
										</div>
										<div class="col ">
											<p class=" mb-1 fs-12">{{ __('Total Paid Characters') }}</p>
											<h3 class="mb-0 fs-20 number-font">{{ number_format($tts_data_yearly['total_paid_chars'][0]['data']) }}</h3>
										</div>
										<div class="col">
											<p class=" mb-1 fs-12">{{ __('Total Standard Characters') }}</p>
											<h3 class="mb-0 fs-20 number-font">{{ number_format($tts_data_yearly['total_standard_chars'][0]['data']) }}</h3>
										</div>
										<div class="col ">
											<p class=" mb-1 fs-12">{{ __('Total Neural Characters') }}</p>
											<h3 class="mb-0 fs-20 number-font">{{ number_format($tts_data_yearly['total_neural_chars'][0]['data']) }}</h3>
										</div>
										<div class="col ">
											<p class=" mb-1 fs-12">{{ __('Total Audio Files Created') }}</p>
											<h3 class="mb-0 fs-20 number-font">{{ number_format($tts_data_yearly['total_audio_files'][0]['data']) }}</h3>
										</div>
										<div class="col ">
											<p class=" mb-1 fs-12">{{ __('Total Listen Mode Results') }}</p>
											<h3 class="mb-0 fs-20 number-font">{{ number_format($tts_data_yearly['total_listen_results'][0]['data']) }}</h3>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-12 col-md-12 col-sm-12">
											<div class="">
												<canvas id="chart-tts-dashboard" class="h-400"></canvas>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- CURRENT YEAR USAGE ANALYTICS -->
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-12 col-md-12">
			<div class="card overflow-hidden border-0 mt-3">
				<div class="card-header">
					<h3 class="card-title"><i class="fa-solid fa-microphone-lines mr-2 fs-12"></i>{{ __('Transcribe Studio Usage') }} <span class="text-muted">({{ __('Current Year') }})</span></h3>
				</div>
				<div class="card-body pb-0">
					<div class="row">
						<div class="col-lg-4 col-sm-12 no-gutters">
							<div class="col-lg-12 col-md-12 col-sm-12">
								<div class="card overflow-hidden border-0 mt-2 special-shadow">
									<div class="card-body">
										<div class="d-flex align-items-end justify-content-between">
											<div>
												<p class=" mb-3 fs-12 font-weight-bold">{{ __('AWS Minutes Used') }} <span class="text-muted">({{ __('Current Month') }})</span></p>
												<h2 class="mb-0"><span class="number-font-chars">{{ number_format((float)$vendor_data['aws_month_transcribe'][0]['data'] / 60, 2) }}</span></h2>									
											</div>
											<img src="{{URL::asset('img/csp/aws-lg.png')}}" class="csp-brand-img" alt="AWS Logo">
										</div>
										<div class="d-flex mt-2">
											<div>
												<span class="text-muted fs-12 mr-1">{{ __('Current Year') }} ({{ __('Total Usage') }}):</span>
												<span class="number-font fs-12"><i class="fa fa-bookmark mr-1 text-info"></i>{{ number_format((float)$vendor_data['aws_year_transcribe'][0]['data'] / 60, 2) }}</span>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-12 col-md-12 col-sm-12">
								<div class="card overflow-hidden border-0 mb-4 special-shadow">
									<div class="card-body">
										<div class="d-flex align-items-end justify-content-between">
											<div>
												<p class=" mb-3 fs-12 font-weight-bold">{{ __('GCP Minutes Used') }} <span class="text-muted">({{ __('Current Month') }})</span></p>
												<h2 class="mb-0"><span class="number-font-chars">{{ number_format((float)$vendor_data['gcp_month_transcribe'][0]['data'] / 60, 2) }}</span></h2>
											</div>
											<img src="{{URL::asset('img/csp/gcp-lg.png')}}" class="csp-brand-img" alt="GCP Logo">
										</div>
										<div class="d-flex mt-2">
											<div>
												<span class="text-muted fs-12 mr-1">{{ __('Current Year') }} ({{ __('Total Usage') }})</span>
												<span class="number-font fs-12"><i class="fa fa-bookmark mr-1 text-info"></i>{{ number_format((float)$vendor_data['gcp_year_transcribe'][0]['data'] / 60, 2) }}</span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-4 col-sm-12 no-gutters">
							<div class="col-lg-12 col-md-12 col-sm-12">
								<div class="card overflow-hidden border-0 mt-2 special-shadow">
									<div class="card-body">
										<p class=" mb-3 mt-1 fs-12 font-weight-bold">{{ __('Free Minutes Used') }} <span class="text-muted">({{ __('Current Month') }})</span></p>
										<h2 class="mb-2 number-font-chars">{{ number_format((float)$tts_data_monthly['free_minutes'][0]['data'] / 60, 2) }}</h2>
										<small class="fs-12 text-muted">{{ __('Compared to Last Month') }} (<span id="free_minutes_past"></span>): </small>
										<span class="fs-12" id="free_minutes"></span>
									</div>									
								</div>
							</div>
							<div class="col-lg-12 col-md-12 col-sm-12">
								<div class="card overflow-hidden border-0 mb-4 special-shadow">
									<div class="card-body">
										<p class=" mb-3 mt-1 fs-12 font-weight-bold">{{ __('Paid Minutes Used') }} <span class="text-muted">({{ __('Current Month') }})</span></p>
										<h2 class="mb-2 number-font-chars">{{ number_format((float)$tts_data_monthly['paid_minutes'][0]['data'] / 60, 2) }}</h2>
										<small class="fs-12 text-muted">{{ __('Compared to Last Month') }} (<span id="paid_minutes_past"></span>): </small>
										<span class="fs-12" id="paid_minutes"></span>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-4 col-sm-12 no-gutters">
							<div class="col-xl-12 col-md-12">
								<div class="card overflow-hidden border-0 mt-2 special-shadow">
									<div class="card-header">
										<h3 class="card-title">{{ __('Cloud Vendor STT Service Usage') }} <span class="text-muted">({{ __('Current Year') }})</span></h3>
									</div>
									<div class="card-body">
										<div class="country-card">
											<div class="mb-5 mt-2">
												<div class="d-flex">
													<span class="fs-12 font-weight-semibold"><img src="{{URL::asset('img/csp/aws-sm.png')}}" class="w-5 h-5 mr-2" alt="">Amazon Web Services</span>
													<div class="ml-auto"><span class="text-success mr-1"></span><span class="number-font fs-14">{{ number_format((float)$vendor_data['aws_year_transcribe'][0]['data'] / 60, 2) }}</span> <span class="text-muted fs-12">(<span id="aws-transcribe"></span>)</span></div>
												</div>
												<div class="progress h-2  mt-1">
													<div class="progress-bar progress-bar-striped progress-bar-animated bg-warning" id="aws-bar-transcribe"></div>
												</div>
											</div>
											<div class="mb-4">
												<div class="d-flex">
													<span class="fs-12 font-weight-semibold"><img src="{{URL::asset('img/csp/gcp-sm.png')}}" class="w-5 h-5 mr-2" alt="">Google Cloud Platform</span>
													<div class="ml-auto"><span class="text-success mr-1"></span><span class="number-font fs-14">{{ number_format((float)$vendor_data['gcp_year_transcribe'][0]['data'] / 60, 2) }}</span> <span class="text-muted fs-12">(<span id="gcp-transcribe"></span>)</span></div>
												</div>
												<div class="progress h-2  mt-1">
													<div class="progress-bar progress-bar-striped progress-bar-animated bg-success" id="gcp-bar-transcribe"></div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12">
							<div class="card overflow-hidden special-shadow border-0 mt-2">
								<div class="card-header">
									<h3 class="card-title">{{ __('Task Types') }} <span class="text-muted">({{ __('Current Year') }})</span></h3>
								</div>
								<div class="card-body">
									<div class="row mb-5 mt-2">
										<div class="col-md col-sm-12">
											<p class=" mb-1 fs-12">{{ __('Total Audio Files Transcribed') }}</p>
											<h3 class="mb-0 fs-20 number-font">{{ number_format($tts_data_yearly['total_file_transcribe'][0]['data']) }}</h3>
										</div>
										<div class="col-md col-sm-12">
											<p class=" mb-1 fs-12">{{ __('Total Record & Transcribe Tasks') }}</p>
											<h3 class="mb-0 fs-20 number-font">{{ number_format($tts_data_yearly['total_recording_transcribe'][0]['data']) }}</h3>
										</div>
										<div class="col-md col-sm-12">
											<p class=" mb-1 fs-12">{{ __('Total Live Transcribe Tasks') }}</p>
											<h3 class="mb-0 fs-20 number-font">{{ number_format($tts_data_yearly['total_live_transcribe'][0]['data']) }}</h3>
										</div>
										<div class="col-md col-sm-12">
											<p class=" mb-1 fs-12">{{ __('Total Words Generated') }}</p>
											<h3 class="mb-0 fs-20 number-font">{{ number_format($tts_data_yearly['total_words'][0]['data']) }}</h3>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-12 col-md-12 col-sm-12">
											<div class="">
												<canvas id="chart-stt-audio" class="h-400"></canvas>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<!-- CURRENT YEAR USAGE ANALYTICS -->
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12">
							<div class="card overflow-hidden border-0">
								<div class="card-header">
									<h3 class="card-title">{{ __('Minutes Usage') }} <span class="text-muted">({{ __('Current Year') }})</span></h3>
								</div>
								<div class="card-body">
									<div class="row mb-5 mt-2">
										<div class="col-md-3 col-sm-12">
											<p class="mb-1 fs-12">{{ __('Total Free Minutes') }}</p>
											<h3 class="mb-0 fs-20 number-font">{{ number_format((float)$tts_data_yearly['total_free_minutes'][0]['data'] / 60, 2) }}</h3>
										</div>
										<div class="col-md-3 col-sm-12">
											<p class=" mb-1 fs-12">{{ __('Total Paid Minutes') }}</p>
											<h3 class="mb-0 fs-20 number-font">{{ number_format((float)$tts_data_yearly['total_paid_minutes'][0]['data'] / 60, 2) }}</h3>
										</div>						
									</div>
									<div class="row">
										<div class="col-lg-12 col-md-12 col-sm-12">
											<div class="">
												<canvas id="chart-stt-minutes" class="h-400"></canvas>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- CURRENT YEAR USAGE ANALYTICS -->
				</div>
			</div>
		</div>
	</div>

@endsection

@section('js')
	<!-- Chart JS -->
	<script src="{{URL::asset('plugins/chart/chart.min.js')}}"></script>
	<script type="text/javascript">
		$(function() {
	
			'use strict';
			
			var freeData = JSON.parse(`<?php echo $chart_data['free_chars']; ?>`);
			var freeDataset = Object.values(freeData);
			var paidData = JSON.parse(`<?php echo $chart_data['paid_chars']; ?>`);
			var paidDataset = Object.values(paidData);
			let delayed;

			var ctx = document.getElementById('chart-tts-dashboard');
			new Chart(ctx, {
				type: 'bar',
				data: {
					labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
					datasets: [{
						label: '{{ __('Free Characters Used') }} ',
						data: freeDataset,
						backgroundColor: '#007bff',
						borderWidth: 1,
						borderRadius: 40,
						barPercentage: 0.6,
						fill: true
					}, {
						label: '{{ __('Paid Characters Used') }}',
						data: paidDataset,
						backgroundColor:  '#1e1e2d',
						borderWidth: 1,
						borderRadius: 40,
						barPercentage: 0.6,
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
							delayed = true;
						},
						delay: (context) => {
							let delay = 0;
							if (context.type === 'data' && context.mode === 'default' && !delayed) {
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
									size: 10
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

			// Vendor Usage
			var aws_year = JSON.parse(`<?php echo $percentage['aws_year']; ?>`);				
			var azure_year = JSON.parse(`<?php echo $percentage['azure_year']; ?>`);			
			var gcp_year= JSON.parse(`<?php echo $percentage['gcp_year']; ?>`);				
			var ibm_year = JSON.parse(`<?php echo $percentage['ibm_year']; ?>`);

			(aws_year[0]['data'] == null) ? aws_year = 0 : aws_year = aws_year[0]['data'];
			(azure_year[0]['data'] == null) ? azure_year = 0 : azure_year = azure_year[0]['data'];
			(gcp_year[0]['data'] == null) ? gcp_year = 0 : gcp_year = gcp_year[0]['data'];
			(ibm_year[0]['data'] == null) ? ibm_year = 0 : ibm_year = ibm_year[0]['data'];

			var aws_total = parseInt(aws_year);	
			var azure_total = parseInt(azure_year);	
			var ibm_total = parseInt(ibm_year);	
			var gcp_total = parseInt(gcp_year);

			var total = aws_total + azure_total + ibm_total + gcp_total;

			var aws = vendorPercentage(aws_total, total);
			var azure = vendorPercentage(azure_total, total);
			var gcp = vendorPercentage(gcp_total, total);
			var ibm = vendorPercentage(ibm_total, total);
			
			document.getElementById('aws').innerHTML = aws;
			document.getElementById('azure').innerHTML = azure;
			document.getElementById('gcp').innerHTML = gcp;
			document.getElementById('ibm').innerHTML = ibm;

			document.getElementById('aws-bar').style.width = aws;
			document.getElementById('azure-bar').style.width = azure;
			document.getElementById('gcp-bar').style.width = gcp;
			document.getElementById('ibm-bar').style.width = ibm;

			// Percentage Difference
			var free_current_month = JSON.parse(`<?php echo $percentage['free_current']; ?>`);				
			var paid_current_month = JSON.parse(`<?php echo $percentage['paid_current']; ?>`);			
			var standard_current_month= JSON.parse(`<?php echo $percentage['standard_current']; ?>`);				
			var neural_current_month = JSON.parse(`<?php echo $percentage['neural_current']; ?>`);

			(free_current_month[0]['data'] == null) ? free_current_month = 0 : free_current_month = free_current_month[0]['data'];
			(paid_current_month[0]['data'] == null) ? paid_current_month = 0 : paid_current_month = paid_current_month[0]['data'];
			(standard_current_month[0]['data'] == null) ? standard_current_month = 0 : standard_current_month = standard_current_month[0]['data'];
			(neural_current_month[0]['data'] == null) ? neural_current_month = 0 : neural_current_month = neural_current_month[0]['data'];

			var free_current_total = parseInt(free_current_month);	
			var paid_current_total = parseInt(paid_current_month);	
			var neural_current_total = parseInt(neural_current_month);	
			var standard_current_total = parseInt(standard_current_month);


			var free_past_month = JSON.parse(`<?php echo $percentage['free_past']; ?>`);				
			var paid_past_month = JSON.parse(`<?php echo $percentage['paid_past']; ?>`);			
			var standard_past_month= JSON.parse(`<?php echo $percentage['standard_past']; ?>`);				
			var neural_past_month = JSON.parse(`<?php echo $percentage['neural_past']; ?>`);

			(free_past_month[0]['data'] == null) ? free_past_month = 0 : free_past_month = free_past_month[0]['data'];
			(paid_past_month[0]['data'] == null) ? paid_past_month = 0 : paid_past_month = paid_past_month[0]['data'];
			(standard_past_month[0]['data'] == null) ? standard_past_month = 0 : standard_past_month = standard_past_month[0]['data'];
			(neural_past_month[0]['data'] == null) ? neural_past_month = 0 : neural_past_month = neural_past_month[0]['data'];

			var free_past_total = parseInt(free_past_month);	
			var paid_past_total = parseInt(paid_past_month);	
			var neural_past_total = parseInt(neural_past_month);	
			var standard_past_total = parseInt(standard_past_month);

			document.getElementById('free_chars_past').innerHTML = new Intl.NumberFormat().format(free_past_total);
			document.getElementById('paid_chars_past').innerHTML = new Intl.NumberFormat().format(paid_past_total);
			document.getElementById('standard_chars_past').innerHTML = new Intl.NumberFormat().format(standard_past_total);
			document.getElementById('neural_chars_past').innerHTML = new Intl.NumberFormat().format(neural_past_total);

			var free_change = vendorPercentageDifference(free_past_total, free_current_total);
			var paid_change = vendorPercentageDifference(paid_past_total, paid_current_total);
			var standard_change = vendorPercentageDifference(standard_past_total, standard_current_total);
			var neural_change = vendorPercentageDifference(neural_past_total, neural_current_total);

			document.getElementById('free_chars').innerHTML = free_change;
			document.getElementById('paid_chars').innerHTML = paid_change;
			document.getElementById('standard_chars').innerHTML = standard_change;
			document.getElementById('neural_chars').innerHTML = neural_change;

			// Transcribe studio data
			var fileData = JSON.parse(`<?php echo $chart_data['file_minutes']; ?>`);
			var fileDataset = Object.values(fileData);
			var recordData = JSON.parse(`<?php echo $chart_data['record_minutes']; ?>`);
			var recordDataset = Object.values(recordData);
			var liveData = JSON.parse(`<?php echo $chart_data['live_minutes']; ?>`);
			var liveDataset = Object.values(liveData);

			var ctx = document.getElementById('chart-stt-audio');
			new Chart(ctx, {
				type: 'bar',
				data: {
					labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
					datasets: [{
						label: '{{ __('Audio Transcribe Minutes') }}',
						data: fileDataset,
						backgroundColor: '#007bff',
						borderWidth: 1,
						borderRadius: 40,
						barPercentage: 0.6,
						fill: true
					}, {
						label: '{{ __('Recording Transcribe Minutes') }}',
						data: recordDataset,
						backgroundColor:  '#1e1e2d',
						borderWidth: 1,
						borderRadius: 40,
						barPercentage: 0.6,
						fill: true
					}, {
						label: '{{ __('Live Transcribe Minutes') }}',
						data: liveDataset,
						backgroundColor:  '#FFAB00',
						borderWidth: 1,
						borderRadius: 40,
						barPercentage: 0.6,
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
							delayed = true;
						},
						delay: (context) => {
							let delay = 0;
							if (context.type === 'data' && context.mode === 'default' && !delayed) {
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
								stepSize: 100,
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
			
			var freeData = JSON.parse(`<?php echo $chart_data['free_minutes']; ?>`);
			var freeDataset = Object.values(freeData);
			var paidData = JSON.parse(`<?php echo $chart_data['paid_minutes']; ?>`);
			var paidDataset = Object.values(paidData);

			var ctx = document.getElementById('chart-stt-minutes');
			new Chart(ctx, {
				type: 'bar',
				data: {
					labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
					datasets: [{
						label: '{{ __('Free Minutes Used') }}',
						data: freeDataset,
						backgroundColor: '#007bff',
						borderWidth: 1,
						borderRadius: 40,
						barPercentage: 0.6,
						fill: true
					}, {
						label: '{{ __('Paid Minutes Used') }}',
						data: paidDataset,
						backgroundColor:  '#1e1e2d',
						borderWidth: 1,
						borderRadius: 40,
						barPercentage: 0.6,
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
							delayed = true;
						},
						delay: (context) => {
							let delay = 0;
							if (context.type === 'data' && context.mode === 'default' && !delayed) {
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
								stepSize: 100,
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

			// Vendor Usage
			var aws_year_transcribe = JSON.parse(`<?php echo $percentage['aws_year_transcribe']; ?>`);							
			var gcp_year_transcribe= JSON.parse(`<?php echo $percentage['gcp_year_transcribe']; ?>`);				

			(aws_year_transcribe[0]['data'] == null) ? aws_year_transcribe = 0 : aws_year_transcribe = aws_year_transcribe[0]['data'];
			(gcp_year_transcribe[0]['data'] == null) ? gcp_year_transcribe = 0 : gcp_year_transcribe = gcp_year_transcribe[0]['data'];

			var aws_total_transcribe = parseInt(aws_year_transcribe);	
			var gcp_total_transcribe = parseInt(gcp_year_transcribe);

			var total_transcribe = aws_total_transcribe + gcp_total_transcribe;

			var aws_transcribe = vendorPercentage(aws_total_transcribe, total_transcribe);
			var gcp_transcribe = vendorPercentage(gcp_total_transcribe, total_transcribe);
			
			document.getElementById('aws-transcribe').innerHTML = aws_transcribe;
			document.getElementById('gcp-transcribe').innerHTML = gcp_transcribe;

			document.getElementById('aws-bar-transcribe').style.width = aws_transcribe;
			document.getElementById('gcp-bar-transcribe').style.width = gcp_transcribe;

			// Percentage Difference
			var free_current_month_transcribe = JSON.parse(`<?php echo $percentage['free_current_transcribe']; ?>`);				
			var paid_current_month_transcribe = JSON.parse(`<?php echo $percentage['paid_current_transcribe']; ?>`);			

			(free_current_month_transcribe[0]['data'] == null) ? free_current_month_transcribe = 0 : free_current_month_transcribe = free_current_month_transcribe[0]['data'];
			(paid_current_month_transcribe[0]['data'] == null) ? paid_current_month_transcribe = 0 : paid_current_month_transcribe = paid_current_month_transcribe[0]['data'];

			var free_current_total_transcribe = parseInt(free_current_month_transcribe);	
			var paid_current_total_transcribe = parseInt(paid_current_month_transcribe);	

			var free_past_month_transcribe = JSON.parse(`<?php echo $percentage['free_past_transcribe']; ?>`);				
			var paid_past_month_transcribe = JSON.parse(`<?php echo $percentage['paid_past_transcribe']; ?>`);			

			(free_past_month_transcribe[0]['data'] == null) ? free_past_month_transcribe = 0 : free_past_month_transcribe = free_past_month_transcribe[0]['data'];
			(paid_past_month_transcribe[0]['data'] == null) ? paid_past_month_transcribe = 0 : paid_past_month_transcribe = paid_past_month_transcribe[0]['data'];

			var free_past_total_transcribe = parseInt(free_past_month_transcribe);	
			var paid_past_total_transcribe = parseInt(paid_past_month_transcribe);	

			var free_view_transcribe = free_past_total_transcribe / 60;
			var paid_view_transcribe = paid_past_total_transcribe / 60;

			document.getElementById('free_minutes_past').innerHTML = free_view_transcribe.toFixed(2);
			document.getElementById('paid_minutes_past').innerHTML = paid_view_transcribe.toFixed(2);

			var free_change_transcribe = vendorPercentageDifference(free_past_total_transcribe, free_current_total_transcribe);
			var paid_change_transcribe = vendorPercentageDifference(paid_past_total_transcribe, paid_current_total_transcribe);

			document.getElementById('free_minutes').innerHTML = free_change_transcribe;
			document.getElementById('paid_minutes').innerHTML = paid_change_transcribe;

			function vendorPercentage(value, total){
				if (total == 0) {
           			 return 0;
        		}

        		return ((value / total) * 100).toFixed(2) + '%';
			} 

			function vendorPercentageDifference(past, current) {
				if (past == 0) {
					var change = (current == 0) ? '<span class="text-muted"> 0% No Change</span>' : '<span class="text-success"> 100% Increase</span>';   					
					return change;
				} else if(current == 0) {
					var change = (past == 0) ? '<span class="text-muted"> 0% No Change</span>' : '<span class="text-danger"> 100% Decrease</span>';
					return change;
				} else if(past == current) {
					var change = '<span class="text-muted"> 0% No Change</span>';
					return change; 
				}

				var difference = current - past;
    			var difference_value;
				var result;
    			
				var totalDifference = Math.abs(difference);
				var change = (totalDifference/past) * 100;				

				if (difference > 0) {
					result = '<span class="text-success">' + change.toFixed(1) + '% Increase</span>';
				} else if(difference < 0) {
					result = '<span class="text-danger">' + change.toFixed(1) + '% Decrease</span>';
				} else {
					difference_value = '<span class="text-muted">' + change.toFixed(1) + '% No Change</span>';
				}

				return result;
			}
		});		
	</script>
@endsection