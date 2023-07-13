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
			<h4 class="page-title mb-0">{{ __('Languages Customization') }}</h4>
			<ol class="breadcrumb mb-2">
				<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-boxes-packing mr-2 fs-12"></i>{{ __('Admin') }}</a></li>
				<li class="breadcrumb-item" aria-current="page"><a href="{{ route('admin.studio.dashboard') }}"> {{ __('Studio Management') }}</a></li>
				<li class="breadcrumb-item active" aria-current="page"><a href="#"> {{ __('Languages Customization') }}</a></li>
			</ol>
		</div>
		<div class="page-rightheader">			
			<a id="activateAllLanguages" href="#" class="btn btn-primary mt-1">{{ __('Activate All') }}</a>
			<a id="deactivateAllLanguages" href="#" class="btn btn-primary mt-1">{{ __('Deactivate All') }}</a>
		</div>
	</div>
	<!-- END PAGE HEADER -->
@endsection

@section('content')	
	<div class="row">
		<div class="col-lg-12 col-md-12 col-xm-12">
			<div class="card border-0">
				<div class="card-header">
					<h3 class="card-title">{{ __('All Transcribe Studio Languages') }}</h3>
				</div>
				<div class="card-body pt-2">
					<!-- BOX CONTENT -->
					<div class="box-content">
							<!-- SET DATATABLE -->
							<table id='allResultsTable' class='table' width='100%'>
									<thead>
										<tr>									
											<th width="5%">{{ __('Status') }}</th> 
											<th width="2%">{{ __('Vendor') }}</th>
											<th width="10%">{{ __('Language') }}</th>
											<th width="7%">{{ __('Language Code') }}</th>
											<th width="7%">{{ __('Speaker Identity') }}</th>
											<th width="5%">{{ __('Transcribe') }}</th>							
											<th width="7%">{{ __('Updated On') }}</th>	    										 						           	
											<th width="5%">{{ __('Actions') }}</th>
										</tr>
									</thead>
							</table> <!-- END SET DATATABLE -->
					</div> <!-- END BOX CONTENT -->
				</div>
			</div>
		</div>
	</div>
@endsection

@section('js')
	<!-- Data Tables JS -->
	<script src="{{URL::asset('plugins/datatable/datatables.min.js')}}"></script>
	<script src="{{URL::asset('plugins/sweetalert/sweetalert2.all.min.js')}}"></script>
	<script type="text/javascript">
		$(function () {

			"use strict";

			// INITILIZE DATATABLE
			var table = $('#allResultsTable').DataTable({
				"lengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
				responsive: {
					details: {type: 'column'}
				},
				"order": [[3, "asc"]],
				colReorder: true,
				language: {
					"emptyTable": "<div><img id='no-results-img' src='{{ URL::asset('img/files/no-result.png') }}'><br>Activate preferred cloud vendor first</div>",
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
				ajax: "{{ route('admin.transcribe.languages') }}",
				columns: [
					{
						data: 'custom-status',
						name: 'custom-status',
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
						data: 'language_code',
						name: 'language_code',
						orderable: true,
						searchable: true
					},
					{
						data: 'speaker',
						name: 'speaker',
						orderable: true,
						searchable: true
					},
					{
						data: 'custom-type',
						name: 'custom-type',
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
						data: 'actions',
						name: 'actions',
						orderable: false,
						searchable: false
					},
				]
			});

			
			// UPDATE LANGUAGE NAME
			$(document).on('click', '.changeLanguageNameButton', function(e) {

				e.preventDefault();

				Swal.fire({
					title: '{{ __('Update Language Name') }}',
					showCancelButton: true,
					confirmButtonText: '{{ __('Update') }}',
					reverseButtons: true,
					input: 'text',
				}).then((result) => {
					if (result.value) {
						var formData = new FormData();
						formData.append("name", result.value);
						formData.append("id", $(this).attr('id'));
						$.ajax({
							headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
							method: 'post',
							url: 'language/update',
							data: formData,
							processData: false,
							contentType: false,
							success: function (data) {
								if (data == 'success') {
									Swal.fire('{{ __('Language Name Update') }}', '{{ __('Language name has been successfully updated') }}', 'success');
									$("#allResultsTable").DataTable().ajax.reload();
								} else {
									Swal.fire('{{ __('Update Error') }}', '{{ __('Language name was not updated correctly') }}', 'error');
								}      
							},
							error: function(data) {
								Swal.fire('Update Error', data.responseJSON['error'], 'error');
							}
						})
					} else if (result.dismiss !== Swal.DismissReason.cancel) {
						Swal.fire('{{ __('No Language Name Entered') }}', '{{ __('Make sure to provide a name before updating') }}', 'error')
					}
				})
			});


			// ACTIVATE LANGUAGE
			$(document).on('click', '.activateLanguageButton', function(e) {

				e.preventDefault();

				var formData = new FormData();
				formData.append("id", $(this).attr('id'));

				$.ajax({
					headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
					method: 'post',
					url: 'languages/language/activate',
					data: formData,
					processData: false,
					contentType: false,
					success: function (data) {
						if (data == 'success') {
							Swal.fire('{{ __('Language Activated') }}', '{{ __('Language of the selected vendor has been activated successfully') }}', 'success');
							$("#allResultsTable").DataTable().ajax.reload();
						} else {
							Swal.fire('{{ __('Language Already Active') }}', '{{ __('Selected language of the vendor is already activated') }}', 'error');
						}      
					},
					error: function(data) {
						Swal.fire({ type: 'error', title: 'Oops...', text: 'Something went wrong!' })
					}
				})

			});


			// DEACTIVATE LANGUAGE
			$(document).on('click', '.deactivateLanguageButton', function(e) {

				e.preventDefault();

				var formData = new FormData();
				formData.append("id", $(this).attr('id'));

				$.ajax({
					headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
					method: 'post',
					url: 'languages/language/deactivate',
					data: formData,
					processData: false,
					contentType: false,
					success: function (data) {
						if (data == 'success') {
							Swal.fire('{{ __('Language Deactivated') }}', '{{ __('Language of the selected vendor has been deactivated successfully') }}', 'success');
							$("#allResultsTable").DataTable().ajax.reload();
						} else {
							Swal.fire('{{ __('Language Already Deactive') }}', '{{ __('Selected language of the vendor is already deactivated') }}', 'error');
						}      
					},
					error: function(data) {
						Swal.fire({ type: 'error', title: 'Oops...', text: 'Something went wrong!' })
					}
				})

			});


			// ACTIVATE ALL LANGUAGES
			$(document).on('click', '#activateAllLanguages', function(e) {

				e.preventDefault();

				$.ajax({
					headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
					method: 'get',
					url: 'languages/activate/all',
					processData: false,
					contentType: false,
					success: function (data) {
						if (data == 'success') {
							Swal.fire('{{ __('All Languages Activated') }}', '{{ __('All languages were successfully activated') }}', 'success');
							$("#allResultsTable").DataTable().ajax.reload();
						}   
					},
					error: function(data) {
						Swal.fire({ type: 'error', title: 'Oops...', text: 'Something went wrong!' })
					}
				})

			});


			// ACTIVATE ALL LANGUAGES
			$(document).on('click', '#deactivateAllLanguages', function(e) {

				e.preventDefault();

				$.ajax({
					headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
					method: 'get',
					url: 'languages/deactivate/all',
					processData: false,
					contentType: false,
					success: function (data) {
						if (data == 'success') {
							Swal.fire('{{ __('All Languages Deactivated') }}', '{{ __('All languages were successfully deactivated') }}', 'success');
							$("#allResultsTable").DataTable().ajax.reload();
						}   
					},
					error: function(data) {
						Swal.fire({ type: 'error', title: 'Oops...', text: 'Something went wrong!' })
					}
				})

			});

		});
	</script>
@endsection