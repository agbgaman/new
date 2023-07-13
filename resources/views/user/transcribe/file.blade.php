@extends('layouts.app')
@section('css')
	<!-- Data Table CSS -->
	<link href="{{URL::asset('plugins/datatable/datatables.min.css')}}" rel="stylesheet" />
	<!-- Awselect CSS -->
	<link href="{{URL::asset('plugins/awselect/awselect.min.css')}}" rel="stylesheet" />
	<!-- FilePond CSS -->
	<link href="{{URL::asset('plugins/filepond/filepond.css')}}" rel="stylesheet" />
	<!-- Green Audio Players CSS -->
	<link href="{{ URL::asset('plugins/audio-player/green-audio-player.css') }}" rel="stylesheet" />
	<!-- Sweet Alert CSS -->
	<link href="{{URL::asset('plugins/sweetalert/sweetalert2.min.css')}}" rel="stylesheet" />
@endsection
@section('page-header')
<!-- PAGE HEADER -->
<div class="page-header mt-5-7">
	<div class="page-leftheader">
		<h4 class="page-title mb-0">{{ __('File Transcribe Studio') }}</h4>
		<ol class="breadcrumb mb-2">
			<li class="breadcrumb-item"><a href="{{route('user.transcribe.file')}}"><i class="fa-solid fa-file-music mr-2 fs-12"></i>{{ __('User') }}</a></li>
			<li class="breadcrumb-item active" aria-current="page"><a href="{{url('#')}}"> {{ __('File Transcribe Studio') }}</a></li>
		</ol>
	</div>
</div>
<!-- END PAGE HEADER -->
@endsection
@section('content')
	<div class="row">
		<div class="col-lg-3 col-md-12 col-sm-12">
			<form id="transcribe-audio" action="{{ route('user.transcribe.transcribe') }}" method="POST" enctype="multipart/form-data">
				@csrf

				<div class="card border-0">
					<div class="card-body p-0">

						<!-- CONTAINER FOR AUDIO FILE UPLOADS-->
						<div id="upload-container">

							<!-- DRAG & DROP MEDIA FILES -->
							<div class="select-file">
								<input type="file" name="filepond" id="filepond" class="filepond" required  />
							</div>
							@error('audiofile')
								<p class="text-danger">{{ $errors->first('audiofile') }}</p>
							@enderror

						</div> <!-- END CONTAINER FOR AUDIO FILE UPLOADS-->

					</div>
				</div>

				<div class="card border-0">
					<div class="card-body p-5 pb-0">

						<div class="row">
							<div class="col-sm-12">
								<div class="form-group">
									<h6 class="task-heading">{{ __('Audio File Language') }}</h6>
									<select id="languages" name="language" data-placeholder="{{ __('Select audio language') }}" data-callback="processLanguageFeature">
										@foreach ($languages as $language)
											<option value="{{ $language->id }}" data-img="{{ URL::asset($language->language_flag)}}" @if (config('stt.vendor_logos') == 'show') data-vendor="{{ URL::asset($language->vendor_img) }}" @endif  @if (auth()->user()->language_file == $language->id) selected @endif> {{ $language->language }}</option>
										@endforeach
									</select>
									@error('language')
										<p class="text-danger">{{ $errors->first('language') }}</p>
									@enderror
								</div>
							</div>

							<div class="col-sm-12" id="removable-type">
								<h6 class="task-heading">{{ __('Speaker Identification') }}</h6>
								<select id="type" name="identify" data-placeholder="{{ __('Enable or Disable Speaker Identification') }}" onchange="displaySpeakerIdentification()">
									<option value="true" @if (config('stt.speaker_identification') == 'enable') selected @endif>{{ __('Enable') }}</option>
									<option value="false" @if (config('stt.speaker_identification') == 'disable') selected @endif>{{ __('Disable') }}</option>
								</select>
								@error('type')
									<p class="text-danger">{{ $errors->first('type') }}</p>
								@enderror
							</div>

							<div class="col-sm-12" id="removable-speaker">
								<div id="speakers-box">
									<h6 class="task-heading">{{ __('Number of Speakers') }}</h6>
									<select id="speakers" name="speakers" data-placeholder="{{ __('Select max number of speakers in the audio file') }}">
										<option value="2" selected="selected">2 {{ __('People') }}</option>
										<option value="3">3 {{ __('People') }}</option>
										<option value="4">4 {{ __('People') }}</option>
										<option value="5">5 {{ __('People') }}</option>
									</select>
								</div>
							</div>

							<div class="col-sm-12">
								<div class="row">
									<div class="col-md-10">
										<div class="form-group">
											<h6 class="task-heading">{{ __('Project Name') }}</h6>
											<select id="project" name="project" data-placeholder="{{ __('Select Project Name') }}">
												<option value="all">{{ __('All Projects') }}</option>
												@foreach ($projects as $project)
													<option value="{{ $project->name }}" @if (auth()->user()->default_project == $project->name) selected @endif> {{ ucfirst($project->name) }}</option>
												@endforeach
											</select>
											@error('project')
												<p class="text-danger">{{ $errors->first('project') }}</p>
											@enderror
										</div>
									</div>
									<div class="col-md-2 pl-1 pt-align">
										<button class="btn btn-special create-project" type="button" id="add-project" data-tippy-content="{{ __('Create New Project') }}" ><i class="fa-solid fa-rectangle-history-circle-plus"></i></button>
									</div>
								</div>
							</div>
						</div>

						<div class="card-footer border-0 text-center p-0">
							<span id="processing"><img src="{{ URL::asset('/img/svgs/processing.svg') }}" alt=""></span>
							<button type="submit" class="btn btn-primary main-action-button" id="transcribe">{{ __('transcribe') }}</button>
						</div>

					</div>
				</div>

			</form>
		</div>

		<div class="col-lg-9 col-md-12 col-sm-12">
			<div class="card border-0">
				<div class="card-header">
					<h3 class="card-title">{{ __('Current Day Tasks') }}</h3>
					<a class="refresh-button" href="#" data-tippy-content="Refresh Table"><i class="fa fa-refresh table-action-buttons view-action-button"></i></a>
				</div>
				<div class="card-body pt-2">
					<!-- SET DATATABLE -->
					<table id='audioResultsTable' class='table' width='100%'>
							<thead>
								<tr>
									<th width="1%"></th>
									<th width="6%">{{ __('Created On') }}</th>
									<th width="5%">{{ __('Task ID') }}</th>
									<th width="10%">{{ __('Language') }}</th>
									<th width="7%">{{ __('Status') }}</th>
									<th width="3%"><i class="fa fa-music fs-14"></i></th>
									<th width="2%"><i class="fa fa-cloud-download fs-14"></i></th>
									<th width="2%">{{ __('Duration') }}</th>
									<th width="2%">{{ __('Format') }}</th>
									<th width="3%">{{ __('Actions') }}</th>
								</tr>
							</thead>
					</table> <!-- END SET DATATABLE -->
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
@section('js')
	<!-- Green Audio Players JS -->
	<script src="{{ URL::asset('plugins/audio-player/green-audio-player.js') }}"></script>
	<script src="{{ URL::asset('js/audio-player.js') }}"></script>
	<!-- FilePond JS -->
	<script src={{ URL::asset('plugins/filepond/filepond.min.js') }}></script>
	<script src={{ URL::asset('plugins/filepond/filepond-plugin-file-validate-size.min.js') }}></script>
	<script src={{ URL::asset('plugins/filepond/filepond-plugin-file-validate-type.min.js') }}></script>
	<script src={{ URL::asset('plugins/filepond/filepond.jquery.js') }}></script>
	<!-- Data Tables JS -->
	<script src="{{URL::asset('plugins/datatable/datatables.min.js')}}"></script>
	<script src="{{URL::asset('plugins/sweetalert/sweetalert2.all.min.js')}}"></script>
	<!-- Awselect JS -->
	<script src="{{URL::asset('plugins/awselect/awselect-custom.js')}}"></script>
	<script src="{{URL::asset('js/transcribe-file.js')}}"></script>
	<script src="{{URL::asset('js/awselect.js')}}"></script>
	<script type="text/javascript">
		$(function () {

			"use strict";

			function format(d) {
				// `d` is the original data object for the row
				return '<div class="slider">'+
							'<table class="details-table">'+
								'<tr>'+
									'<td class="details-title" width="10%">Project Name:</td>'+
									'<td>'+ ((d.project == null) ? '' : d.project) +'</td>'+
								'</tr>'+
								'<tr>'+
									'<td class="details-title" width="10%">File Name:</td>'+
									'<td>'+ ((d.file_name == null) ? '' : d.file_name) +'</td>'+
								'</tr>'+
								'<tr>'+
									'<td class="details-title" width="10%">Task Type:</td>'+
									'<td>'+ ((d.type == null) ? '' : d.type) +'</td>'+
								'</tr>'+
								'<tr>'+
									'<td class="details-title" width="10%">Transcript:</td>'+
									'<td>'+ ((d.text == null) ? '' : d.text) +'</td>'+
								'</tr>'+
								'<tr>'+
									'<td class="details-result" width="10%">Audio File:</td>'+
									'<td><audio controls preload="none">' +
										'<source src="'+ d.result +'" type="'+ d.audio_type +'">' +
									'</audio></td>'+
								'</tr>'+
							'</table>'+
						'</div>';
			}

			// INITILIZE DATATABLE
			var table = $('#audioResultsTable').DataTable({
				"lengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
				responsive: {
					details: {type: 'column'}
				},
				colReorder: true,
				language: {
					"emptyTable": "<div><img id='no-results-img' src='{{ URL::asset('img/files/no-result.png') }}'><br>{{ __('No transcribe tasks submitted yet') }}</div>",
					search: "<i class='fa fa-search search-icon'></i>",
					lengthMenu: '_MENU_ ',
					paginate : {
						first    : '<i class="fa fa-angle-double-left"></i>',
						last     : '<i class="fa fa-angle-double-right"></i>',
						previous : '<i class="fa fa-angle-left"></i>',
						next     : '<i class="fa fa-angle-right"></i>'
					}
				},
				pagingType : 'full_numbers',
				processing: true,
				serverSide: true,
				ajax: "{{ route('user.transcribe.file') }}",
				columns: [{
						"className":      'details-control',
						"orderable":      false,
						"searchable":     false,
						"data":           null,
						"defaultContent": ''
					},
					{
						data: 'created-on',
						name: 'created-on',
						orderable: true,
						searchable: true
					},
					{
						data: 'task_id',
						name: 'task_id',
						orderable: true,
						searchable: true
					},
					{
						data: 'custom-language',
						name: 'custom-language',
						orderable: true,
						searchable: true
					},
					{
						data: 'custom-status',
						name: 'custom-status',
						orderable: true,
						searchable: true
					},
					{
						data: 'single',
						name: 'single',
						orderable: true,
						searchable: true
					},
					{
						data: 'download',
						name: 'download',
						orderable: true,
						searchable: true
					},
					{
						data: 'custom-length',
						name: 'custom-length',
						orderable: true,
						searchable: true
					},
					{
						data: 'format',
						name: 'format',
						orderable: true,
						searchable: true
					},
					{
						data: 'actions',
						name: 'actions',
						orderable: false,
						searchable: false
					},
				]
			});


			$('#audioResultsTable tbody').on('click', 'td.details-control', function () {
				var tr = $(this).closest('tr');
				var row = table.row( tr );

				if ( row.child.isShown() ) {
					// This row is already open - close it
					$('div.slider', row.child()).slideUp( function () {
						row.child.hide();
						tr.removeClass('shown');
					} );
				}
				else {
					// Open this row
					row.child( format(row.data()), 'no-padding' ).show();
					tr.addClass('shown');

					$('div.slider', row.child()).slideDown();
				}
			});


			$('.refresh-button').on('click', function(e){
				e.preventDefault();
				$("#audioResultsTable").DataTable().ajax.reload();
			});


			$('#add-project').on('click', function() {
				$('#projectModal').modal('show');
			});


			// CREATE NEW PROJECT
			$(document).on('click', '#add-project', function(e) {

				e.preventDefault();

				Swal.fire({
					title: '{{ __('Create New Project') }}',
					showCancelButton: true,
					confirmButtonText: '{{ __('Create') }}',
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
							url: 'speech-to-text/project',
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
								Swal.fire({ type: 'error', title: 'Oops...', text: 'Something went wrong!' })
							}
						})
					} else if (result.dismiss !== Swal.DismissReason.cancel) {
						Swal.fire('{{ __('No Project Name Entered') }}', '{{ __('Make sure to provide a new project name before creating') }}', 'error')
					}
				})
			});


			// DELETE TRANSCRIBE RESULT
			$(document).on('click', '.deleteResultButton', function(e) {

				e.preventDefault();

				Swal.fire({
					title: '{{ __('Confirm Result Deletion') }}',
					text: '{{ __('It will permanently delete this transcribe result') }}',
					icon: 'warning',
					showCancelButton: true,
					confirmButtonText: '{{ __('Delete') }}',
					reverseButtons: true,
				}).then((result) => {
					if (result.isConfirmed) {
						var formData = new FormData();
						formData.append("id", $(this).attr('id'));
						$.ajax({
							headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
							method: 'post',
							url: 'speech-to-text/result/delete',
							data: formData,
							processData: false,
							contentType: false,
							success: function (data) {
								if (data == 'success') {
									Swal.fire('{{ __('Result Deleted') }}', '{{ __('Transcribe result has been successfully deleted') }}', 'success');
									$("#audioResultsTable").DataTable().ajax.reload();
								} else {
									Swal.fire('{{ __('Delete Failed') }}', '{{ __('There was an error while deleting this result') }}', 'error');
								}
							},
							error: function(data) {
								Swal.fire({ type: 'error', title: 'Oops...', text: 'Something went wrong!' })
							}
						})
					}
				})
			});


		});
	</script>
@endsection
