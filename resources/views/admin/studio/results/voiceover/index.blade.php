@extends('layouts.app')

@section('css')
	<!-- Data Table CSS -->
	<link href="{{URL::asset('plugins/datatable/datatables.min.css')}}" rel="stylesheet" />
	<!-- Sweet Alert CSS -->
	<link href="{{URL::asset('plugins/sweetalert/sweetalert2.min.css')}}" rel="stylesheet" />
@endsection

@section('page-header')
	<!-- PAGE HEADER -->
	<div class="page-header mt-5-7">
		<div class="page-leftheader">
			<h4 class="page-title mb-0">{{ __('Voiceover Studio Results') }}</h4>
			<ol class="breadcrumb mb-2">
				<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-boxes-packing mr-2 fs-12"></i>{{ __('Admin') }}</a></li>
				<li class="breadcrumb-item" aria-current="page"><a href="{{ route('admin.studio.dashboard') }}"> {{ __('Studio Management') }}</a></li>
				<li class="breadcrumb-item active" aria-current="page"><a href="#"> {{ __('Voiceover Studio Results') }}</a></li>
			</ol>
		</div>
	</div>
	<!-- END PAGE HEADER -->
@endsection

@section('content')	
	<!-- ALL USERS PROCESSED TEXT RESULTS -->
	<div class="row">
		<div class="col-lg-12 col-md-12 col-xm-12">
			<div class="card border-0">
				<div class="card-header">
					<h3 class="card-title">{{ __('All Synthesized Results') }}</h3>
				</div>
				<div class="card-body pt-2">
					<!-- BOX CONTENT -->
					<div class="box-content">
							<!-- SET DATATABLE -->
							<table id='allResultsTable' class='table' width='100%'>
									<thead>
										<tr>
											<th width="3%"></th>
											<th width="7%">{{ __('Plan Type') }}</th>
											<th width="7%">{{ __('Created On') }}</th> 
											<th width="8%">{{ __('User') }}</th>
											<th width="5%">{{ __('Vendor') }}</th>
											<th width="8%">{{ __('Language') }}</th>
											<th width="7%">{{ __('Voice') }}</th>
											<th width="7%">{{ __('Gender') }}</th>
											<th width="9%">{{ __('Voice Engine') }}</th>
											<th width="4%"><i class="fa fa-music fs-14"></i></th>							
											<th width="4%"><i class="fa fa-cloud-download fs-14"></i></th>								
											<th width="5%">{{ __('Format') }}</th>
											<th width="5%">{{ __('Chars') }}</th>	    										 						           	
											<th width="5%">{{ __('Actions') }}</th>
										</tr>
									</thead>
							</table> <!-- END SET DATATABLE -->
					</div> <!-- END BOX CONTENT -->
				</div>
			</div>
		</div>
	</div>
	<!-- END ALL USERS PROCESSED TEXT RESULTS -->
@endsection

@section('js')
	<!-- Data Tables JS -->
	<script src="{{URL::asset('plugins/datatable/datatables.min.js')}}"></script>
	<!-- Green Audio Players JS -->
	<script src="{{ URL::asset('plugins/audio-player/green-audio-player.js') }}"></script>
	<script src="{{URL::asset('plugins/sweetalert/sweetalert2.all.min.js')}}"></script>
	<script src="{{ URL::asset('js/audio-player.js') }}"></script>
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
									'<td class="details-title" width="10%">Title:</td>'+
									'<td>'+ ((d.title == null) ? '' : d.title) +'</td>'+
								'</tr>'+
								'<tr>'+
									'<td class="details-title" width="10%">Text Clean:</td>'+
									'<td>'+ d.text +'</td>'+
								'</tr>'+
								'<tr>'+
									'<td class="details-title" width="10%">Text Raw:</td>'+
									'<td>'+ d.text_raw +'</td>'+
								'</tr>'+
								'<tr>'+
									'<td class="details-result" width="10%">Synthesized Result:</td>'+
									'<td><audio controls preload="none">' +
										'<source src="'+ d.result +'" type="audio/mpeg">' +
									'</audio></td>'+
								'</tr>'+
							'</table>'+
						'</div>';
			}

			// INITILIZE DATATABLE
			var table = $('#allResultsTable').DataTable({
				"lengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
				responsive: {
					details: {type: 'column'}
				},
				colReorder: true,
				language: {
					"emptyTable": "<div><img id='no-results-img' src='{{ URL::asset('img/files/no-result.png') }}'><br>No synthesized text results yet</div>",
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
				ajax: "{{ route('admin.voiceover.results') }}",
				columns: [{
						"className":      'details-control',
						"orderable":      false,
						"searchable":     false,
						"data":           null,
						"defaultContent": ''
					},{
						data: 'custom-plan-type',
						name: 'custom-plan-type',
						orderable: true,
						searchable: true
					},
					{
						data: 'created-on',
						name: 'created-on',
						orderable: true,
						searchable: true
					},
					{
						data: 'username',
						name: 'username',
						orderable: true,
						searchable: true
					},	
					{
						data: 'vendor',
						name: 'vendor',
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
						data: 'voice',
						name: 'voice',
						orderable: true,
						searchable: true
					},
					{
						data: 'gender',
						name: 'gender',
						orderable: true,
						searchable: true
					},
					{
						data: 'custom-voice-type',
						name: 'custom-voice-type',
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
						data: 'result_ext',
						name: 'result_ext',
						orderable: true,
						searchable: true
					},
					{
						data: 'characters',
						name: 'characters',
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

			$('#allResultsTable tbody').on('click', 'td.details-control', function () {
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

			
			// DELETE SYNTHESIZE RESULT
			$(document).on('click', '.deleteResultButton', function(e) {

				e.preventDefault();

				Swal.fire({
					title: '{{ __('Confirm Result Deletion') }}',
					text: '{{ __('It will permanently delete this synthesize result') }}',
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
							url: 'result/delete',
							data: formData,
							processData: false,
							contentType: false,
							success: function (data) {
								if (data == 'success') {
									Swal.fire('{{ __('Result Deleted') }}', '{{ __('Synthesize result has been successfully deleted') }}', 'success');	
									$("#allResultsTable").DataTable().ajax.reload();								
								} else {
									Swal.fire('{{ __('Delete Failed') }}', '{{ __('There was an error while deleting this result') }}', 'error');
								}      
							},
							error: function(data) {
								Swal.fire('Oops...','Something went wrong!', 'error')
							}
						})
					} 
				})
			});

		});
	</script>
@endsection