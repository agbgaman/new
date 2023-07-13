@extends('layouts.app')
@section('css')
	<!-- Data Table CSS -->
	<link href="{{URL::asset('plugins/datatable/datatables.min.css')}}" rel="stylesheet" />
	<!-- Awselect CSS -->
	<link href="{{URL::asset('plugins/awselect/awselect.min.css')}}" rel="stylesheet" />
	<!-- Sweet Alert CSS -->
	<link href="{{URL::asset('plugins/sweetalert/sweetalert2.min.css')}}" rel="stylesheet" />
@endsection
@section('page-header')
<!-- PAGE HEADER -->
<div class="page-header mt-5-7">
	<div class="page-leftheader">
		<h4 class="page-title mb-0">{{ __('Transcribe Projects') }}</h4>
		<ol class="breadcrumb mb-2">
			<li class="breadcrumb-item"><a href="{{route('user.dashboard')}}"><i class="fa-solid fa-boxes-packing mr-2 fs-12"></i>{{ __('User') }}</a></li>
			<li class="breadcrumb-item" aria-current="page"><a href="{{route('user.transcribe.file')}}"> {{ __('Transcribe Studio') }}</a></li>
			<li class="breadcrumb-item active" aria-current="page"><a href="{{url('/' . $page='#')}}"> {{ __('Transcribe Projects') }}</a></li>
		</ol>
	</div>
</div>
<!-- END PAGE HEADER -->
@endsection
@section('content')	
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12">
			<div class="card border-0">	
				<div class="card-header">
					<h3 class="card-title">{{ __('All Transcribe Projects') }}</h3>
					<a class="refresh-button" href="#" data-tippy-content="Refresh Table"><i class="fa fa-refresh table-action-buttons view-action-button"></i></a>
				</div>			
				<div class="card-body pt-5">
					<div class="row">
						<div class="col-md-6 col-sm-12">
							<div class="row">
								<div class="col-md-7 col-sm-12">
									<div class="form-group" id="textract-project">
										<select id="project" name="project" data-placeholder="{{ __('Select Project Name') }}" data-callback="changeProjectName">	
											<option value="all">{{ __('All Projects') }}</option>
											@foreach ($projects as $project)
												<option value="{{ $project->name }}" @if ($project->name == auth()->user()->default_project) selected @endif> {{ ucfirst($project->name) }}</option>
											@endforeach											
										</select>
									</div>
								</div>
								<div class="col-md-3 col-sm-12">
									<div class="dropdown">
										<button class="btn btn-special create-project mr-2" type="button" id="add-project" data-tippy-content="{{ __('Create New Project') }}" ><i class="fa-solid fa-rectangle-history-circle-plus"></i></button>
										<button class="btn btn-special create-project mr-2" type="button" id="default-project" data-tippy-content="{{ __('Set Default Project') }}"><i class="fa-solid fa-rectangle-vertical-history"></i></button>
										<button class="btn btn-special create-project" type="button" id="delete-project" data-tippy-content="{{ __('Delete Project') }}"><i class="fa-solid fa-folder-xmark"></i></button>												
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-6 col-sm-12">
							<div class="row">
								<div class="col-md col-sm-12 pt-2">
									<span class="fs-12 font-weight-bold">{{ __('Total Results') }}: <span id="total-results">{{ $data_results['total']}}</span></span>												
								</div>
								<div class="col-md col-sm-12 pt-2">
									<span class="fs-12 font-weight-bold">{{ __('Total Time') }}: <span id="total-time">{{ number_format((float)($data_time['total'] / 60), 2, '.', '')}}</span> {{ __('minutes') }}</span>												
								</div>
								<div class="col-md col-sm-12 pt-2">
									<span class="fs-12 font-weight-bold">{{ __('Total Words') }}: <span id="total-words">{{ $data_words['total']}}</span></span>												
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>


		<div class="col-lg-12 col-md-12 col-sm-12">
			<div class="card border-0">
				<div class="card-body pt-2">
					<!-- SET DATATABLE -->
					<table id='userResultTable' class='table' width='100%'>
							<thead>
								<tr>
									<th width="1%"></th>
									<th width="6%">{{ __('Created On') }}</th> 
									<th width="8%">{{ __('File Name') }}</th>
									<th width="9%">{{ __('Language') }}</th>
									<th width="5%">{{ __('Status') }}</th>		
									<th width="2%"><i class="fa fa-music fs-14"></i></th>							
									<th width="2%"><i class="fa fa-cloud-download fs-14"></i></th>								
									<th width="3%">{{ __('Duration') }}</th>									
									<th width="2%">{{ __('Format') }}</th> 																           	     						           	
									<th width="2%">{{ __('Size') }}</th> 																           	     						           	
									<th width="2%">{{ __('Words') }}</th> 																           	     						           	 																           	     						           	
									<th width="4%">{{ __('Mode') }}</th> 																           	     						           	
									<th width="3%">{{ __('Actions') }}</th>								
								</tr>
							</thead>
					</table> <!-- END SET DATATABLE -->
				</div>
			</div>
		</div>
	</div>

	<!-- SET DEFAULT PROJECT MODAL -->
	<div class="modal fade" id="defaultProjectModal" tabindex="-1" role="dialog" aria-labelledby="projectModalLabel" aria-hidden="true" data-bs-keyboard="false">
		<div class="modal-dialog modal-dialog-centered modal-md" role="document">
			<div class="modal-content">
				<div class="modal-header mb-1">
					<h4 class="modal-title" id="myModalLabel"><i class="fa-solid fa-rectangle-vertical-history"></i> {{ __('Select Default Project Name') }}</h4>
					<button type="button" class="btn-close fs-12" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<form action="{{ route('user.transcribe.project.update') }}" method="POST" enctype="multipart/form-data">
					@method('PUT')
					@csrf
					<div class="modal-body pb-0 pl-6 pr-6">        
						<div class="input-box">	
							<select id="set-project" name="project" data-placeholder="{{ __('Select Default Project Name') }}:">			
								@foreach ($projects as $project)
									<option value="{{ $project->name }}"> {{ ucfirst($project->name) }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="modal-footer pr-6 pb-3 modal-footer-awselect">
						<button type="button" class="btn btn-cancel mb-4" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
						<button type="submit" class="btn btn-primary mb-4" id="new-project-button">{{ __('Save') }}</button>
					</div>
				</form>				
			</div>
		</div>
	</div>
	<!-- END SET DEFAULT PROJECT MODAL -->

	<!-- DELETE PROJECT MODAL -->
	<div class="modal fade" id="deleteProjectModal" tabindex="-1" role="dialog" aria-labelledby="projectModalLabel" aria-hidden="true" data-bs-keyboard="false">
		<div class="modal-dialog modal-dialog-centered modal-md" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="myModalLabel"><i class="fa-solid fa-folder-xmark"></i> {{ __('Delete Project Name and Results') }}</h4>
					<button type="button" class="btn-close fs-12" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<form action="{{ route('user.transcribe.project.delete') }}" method="POST" enctype="multipart/form-data">
					@method('DELETE')
					@csrf
					<div class="modal-body pb-0 pl-6 pr-6">        
						<div class="input-box">	
							<p class="text-danger mb-3 fs-12">{{ __('Warning! All transcribe results under this name will be deleted too') }}</p>
							<select id="del-project" name="project" data-placeholder="{{ __('Select Project Name to Delete') }}:">			
								@foreach ($projects as $project)
									<option value="{{ $project->name }}"> {{ ucfirst($project->name) }}</option>
								@endforeach
							</select>							
						</div>
					</div>
					<div class="modal-footer pr-6 pb-3 modal-footer-awselect">
						<button type="button" class="btn btn-cancel mb-4" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
						<button type="submit" class="btn btn-confirm mb-4" id="new-project-button">{{ __('Delete') }}</button>
					</div>
				</form>				
			</div>
		</div>
	</div>
	<!-- END Delete PROJECT MODAL -->
@endsection

@section('js')
	<!-- Data Tables JS -->
	<script src="{{URL::asset('plugins/datatable/datatables.min.js')}}"></script>
	<!-- Awselect JS -->
	<script src="{{URL::asset('plugins/awselect/awselect.min.js')}}"></script>
	<script src="{{ URL::asset('plugins/audio-player/green-audio-player.js') }}"></script>
	<script src="{{URL::asset('plugins/sweetalert/sweetalert2.all.min.js')}}"></script>
	<script src="{{ URL::asset('js/audio-player.js') }}"></script>
	<script src="{{URL::asset('js/awselect.js')}}"></script>
	<script type="text/javascript">
	let table;
		$(function () {

			"use strict";

			$('#default-project').on('click', function() {
				$('#defaultProjectModal').modal('show');
			});

			$('#delete-project').on('click', function() {
				$('#deleteProjectModal').modal('show');
			});

			function format(d) {
				// `d` is the original data object for the row
				return '<div class="slider">'+
							'<table class="details-table">'+
								'<tr>'+
									'<td class="details-title" width="10%">Task Type:</td>'+
									'<td>'+ ((d.type == null) ? '' : d.type) +'</td>'+
								'</tr>'+
								'<tr>'+
									'<td class="details-title" width="10%">Task ID:</td>'+
									'<td>'+ ((d.task_id == null) ? '' : d.task_id) +'</td>'+
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
			table = $('#userResultTable').DataTable({
				"lengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
				responsive: {
					details: {type: 'column'}
				},
				colReorder: true,
				language: {
					"emptyTable": "<div><img id='no-results-img' src='{{ URL::asset('img/files/no-result.png') }}'><br>{{ __('Project does not have any transcribe tasks stored yet') }}</div>",
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
				ajax: "{{ route('user.transcribe.projects') }}",
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
						data: 'file_name',
						name: 'file_name',
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
						data: 'file_size',
						name: 'file_size',
						orderable: true,
						searchable: true
					},			
					{
						data: 'words',
						name: 'words',
						orderable: true,
						searchable: true
					},
					{
						data: 'custom-mode',
						name: 'custom-mode',
						orderable: true,
						searchable: true
					},		
					{
						data: 'actions',
						name: 'actions',
						orderable: false,
						searchable: false
					}
				]
			});
		
			$('#userResultTable tbody').on('click', 'td.details-control', function () {
				let tr = $(this).closest('tr');
				let row = table.row( tr );
		
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
				$("#userResultTable").DataTable().ajax.reload();
			});


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
							url: 'project',
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
			

			// DELETE SYNTHESIZE RESULT
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
							url: 'project/result/delete',
							data: formData,
							processData: false,
							contentType: false,
							success: function (data) {
								if (data == 'success') {
									Swal.fire('{{ __('Result Deleted') }}', '{{ __('Transcribe result has been successfully deleted') }}', 'success');	
									$("#userResultTable").DataTable().ajax.reload();								
								} else {
									Swal.fire('{{ __('Delete Failed') }}', '{{ __('There was an error while deleting this resul') }}t', 'error');
								}      
							},
							error: function(data) {
								Swal.fire('Oops...','{{ __('Something went wrong') }}!', 'error')
							}
						})
					} 
				})
			});
		
		});


		// CHANGE PROJECT NAME
		function changeProjectName(value) {			

			$.get("{{ route('user.transcribe.projects.change') }}", { project: value}, 		
				function(data){
					table = $('#userResultTable').DataTable({
					"lengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
					responsive: {
						details: {type: 'column'}
					},
					destroy: true,
					colReorder: true,
					language: {
						"emptyTable": "<div><img id='no-results-img' src='{{ URL::asset('img/files/no-result.png') }}'><br>{{ __('Project does not have any transcribe tasks stored yet') }}</div>",
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
					data: data['data'],
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
							data: 'file_name',
							name: 'file_name',
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
							data: 'file_size',
							name: 'file_size',
							orderable: true,
							searchable: true
						},	
						{
							data: 'words',
							name: 'words',
							orderable: true,
							searchable: true
						},
						{
							data: 'custom-mode',
							name: 'custom-mode',
							orderable: true,
							searchable: true
						},		
						{
							data: 'actions',
							name: 'actions',
							orderable: false,
							searchable: false
						}
					]
				});

			}).fail(function(){
				console.log("Error getting datatable results");
			});


			$.get("{{ route('user.transcribe.projects.change.stats') }}", { project: value}, 
				function(data){
					document.getElementById('total-results').innerHTML = data['results']['total'];
					document.getElementById('total-time').innerHTML = (data['time']['total'] / 60).toFixed(2);
					document.getElementById('total-words').innerHTML = data['words']['total'];
			});

		}
	</script>
@endsection