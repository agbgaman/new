@extends('layouts.app')

@section('css')
	<!-- Data Table CSS -->
	<link href="{{URL::asset('plugins/datatable/datatables.min.css')}}" rel="stylesheet" />
@endsection

@section('page-header')
	<!-- PAGE HEADER -->
	<div class="page-header mt-5-7">
		<div class="page-leftheader">
			<h4 class="page-title mb-0"> {{ __('Use Cases Manager') }}</h4>
			<ol class="breadcrumb mb-2">
				<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa fa-globe mr-2 fs-12"></i>{{ __('Admin') }}</a></li>
				<li class="breadcrumb-item" aria-current="page"><a href="{{url('#')}}"> {{ __('Frontend Management') }}</a></li>
				<li class="breadcrumb-item active" aria-current="page"><a href="{{url('#')}}"> {{ __('Use Cases Manager') }}</a></li>
			</ol>
		</div>
		<div class="page-rightheader">
			<a href="{{ route('admin.settings.usecase.create') }}" class="btn btn-primary mt-1">{{ __('Create New Use Case') }}</a>
		</div>
	</div>
	<!-- END PAGE HEADER -->
@endsection
@section('content')						
	<div class="row">
		<div class="col-lg-12 col-md-12 col-xm-12">
			<div class="card border-0">
				<div class="card-header">
					<h3 class="card-title">{{ __('Created Use Cases List') }}</h3>
				</div>
				<div class="card-body pt-2">
					<!-- SET DATATABLE -->
					<table id='usecasesTable' class='table' width='100%'>
							<thead>
								<tr>
									<th width="7%">{{ __('Created Date') }}</th>
									<th width="10%">{{ __('Case Title') }}</th>
									<th width="20%">{{ __('Case Text') }}</th>
									<th width="7%">{{ __('Image File Name') }}</th>											
									<th width="7%">{{ __('Audio File Name') }}</th>																				
									<th width="5%">{{ __('Actions') }}</th>
								</tr>
							</thead>
					</table> <!-- END SET DATATABLE -->
				</div>
			</div>
		</div>
	</div>
@endsection

@section('js')
	<!-- Data Tables JS -->
	<script src="{{URL::asset('plugins/datatable/datatables.min.js')}}"></script>
	<script type="text/javascript">
		$(function () {

			"use strict";
			
			// INITILIZE DATATABLE
			var table = $('#usecasesTable').DataTable({
				"lengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
				responsive: true,
				colReorder: true,
				"order": [[ 1, "desc" ]],
				language: {
					"emptyTable": "<div>No use cases created yet</div>",
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
				ajax: "{{ route('admin.settings.usecase') }}",
				columns: [
					{
						data: 'created-on',
						name: 'created-on',
						orderable: true,
						searchable: true
					},					
					{
						data: 'title',
						name: 'title',
						orderable: true,
						searchable: true
					},
					{
						data: 'text',
						name: 'text',
						orderable: true,
						searchable: true
					},
					{
						data: 'image_url',
						name: 'image_url',
						orderable: true,
						searchable: true	
					},
					{
						data: 'audio_url',
						name: 'audio_url',
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

			// DELETE REVIEW
			$(document).on('click', '.deleteReviewButton', function(e) {

				e.preventDefault();

				Swal.fire({
					title: '{{ __('Confirm Use Case Deletion') }}',
					text: '{{ __('It will permanently delete this use case') }}',
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
							url: 'usecase/delete',
							data: formData,
							processData: false,
							contentType: false,
							success: function (data) {
								if (data == 'success') {
									Swal.fire('{{ __('Use Case Deleted') }}', '{{ __('Use case has been successfully deleted') }}', 'success');	
									$("#usecasesTable").DataTable().ajax.reload();								
								} else {
									Swal.fire('{{ __('Delete Failed') }}', '{{ __('There was an error while deleting this use case') }}', 'error');
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