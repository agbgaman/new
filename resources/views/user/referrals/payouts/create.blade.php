@extends('layouts.app')

@section('css')
	<!-- Awselect CSS -->
	<link href="{{URL::asset('plugins/awselect/awselect.min.css')}}" rel="stylesheet" />
@endsection

@section('page-header')
	<!-- PAGE HEADER -->
	<div class="page-header mt-5-7">
		<div class="page-leftheader">
			<h4 class="page-title mb-0">{{ __('New Payout Request') }}</h4>
			<ol class="breadcrumb mb-2">
				<li class="breadcrumb-item"><a href="{{route('user.dashboard')}}"><i class="fa-solid fa-badge-dollar mr-2 fs-12"></i>{{ __('User') }}</a></li>
				<li class="breadcrumb-item" aria-current="page"><a href="{{ route('user.referral') }}"> {{ __('Affiliate Program') }}</a></li>
				<li class="breadcrumb-item" aria-current="page"><a href="{{ route('user.referral.payout') }}"> {{ __('My Payouts') }}</a></li>
				<li class="breadcrumb-item active" aria-current="page"><a href="{{url('#')}}"> {{ __('New Payout Request') }}</a></li>
			</ol>
		</div>
	</div>
	<!-- END PAGE HEADER -->
@endsection
@section('content')
	<div class="row">
		<div class="col-lg-4 col-md-12 col-xm-12">
			<div class="card overflow-hidden border-0">
				<div class="card-header">
					<h3 class="card-title">{{ __('Create New Payout Request') }}</h3>
				</div>
				<div class="card-body">

					<form action="{{ route('user.referral.payout.store') }}" method="POST" enctype="multipart/form-data">
						@csrf

						<h6 class="fs-12 mb-5 mt-3">{{ __('Minimum amount for all payout request is') }} <span class="font-weight-bold">{{ config('payment.referral.payment.threshold') }} {{ config('payment.default_currency') }}</span></h6>

						<h6 class="fs-12 mb-6 mt-3">{{ __('Your current balance is') }}: <span class="font-weight-bold">{{ auth()->user()->balance }} {{ config('payment.default_currency') }}</span></h6>

						<h6 class="fs-12 mb-6 mt-3">{{ __('Your preferred payout method is') }}: <span class="font-weight-bold">@if (auth()->user()->referral_payment_method == '') {{ __('Not Set') }} <span class="text-muted">({{ __('Please configure it under My Gateway tab') }})</span> @else {{ auth()->user()->referral_payment_method }}@endif</span></h6>

						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12">
								<div class="input-box">
									<h6>{{ __('Total request amount') }}</h6>
									<div class="form-group">
										<input type="number" class="form-control @error('payout') is-danger @enderror" id="payout" name="payout" value="{{ old('payout') }}">
									</div>
									@error('payout')
										<p class="text-danger">{{ $errors->first('payout') }}</p>
									@enderror
								</div>
							</div>
						</div>
						<!-- SAVE CHANGES ACTION BUTTON -->
						<div class="border-0 text-right mb-2 mt-1">
							<a href="{{ route('user.referral.payout') }}" class="btn btn-cancel mr-2">{{ __('Cancel') }}</a>
                            <button type="button" class="btn btn-primary" id="createBtn">{{ __('Create') }}</button>

                        </div>

					</form>

				</div>
			</div>
            <!-- PDF Upload Modal -->
            <div class="modal fade" id="pdfUploadModal" tabindex="-1" role="dialog" aria-labelledby="pdfUploadModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="pdfUploadModalLabel">{{ __('Upload Invoice') }}</h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="file" class="form-control" id="pdfFile" name="pdfFile" accept="application/pdf">
                            <span id="pdfError" class="text-danger d-none">{{ __('Please upload a PDF file.') }}</span>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                            <button type="button" class="btn btn-primary"  id="uploadBtn">{{ __('Upload and Create') }}</button>
                        </div>
                    </div>
                </div>
            </div>
		</div>
	</div>
@endsection

@section('js')
	<!-- Awselect JS -->
	<script src="{{URL::asset('plugins/awselect/awselect.min.js')}}"></script>
	<script src="{{URL::asset('js/awselect.js')}}"></script>
    <script>
        $(document).ready(function() {
            // Open the modal when "Create" button is clicked
            $('#createBtn').on('click', function() {
                $('#pdfUploadModal').modal('show');
            });

            // Handle form submission with AJAX
            $('#uploadBtn').on('click', function() {
                let pdfFile = $('#pdfFile')[0].files[0];

                if (!pdfFile || pdfFile.type !== 'application/pdf') {
                    $('#pdfError').removeClass('d-none');
                    return;
                }

                let formData = new FormData();
                formData.append('pdfFile', pdfFile);

                // Append form data to formData
                formData.append('_token', $('input[name="_token"]').val());
                formData.append('payout', $('#payout').val());

                $.ajax({
                    url: "{{ route('user.referral.payout.store') }}",
                    type: 'POST',
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    data: formData,
                    success: function(response) {
                        // Handle success response
                        $('#pdfUploadModal').modal('hide');
                        window.location.href = "{{ route('user.referral.payout') }}";
                    },
                    error: function(xhr, status, error) {
                        // Handle error response
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            let errorMessage = '';

                            if (errors) {
                                if (errors.payout) {
                                    errorMessage += errors.payout[0] + '\n';
                                }

                                if (errors.pdfFile) {
                                    errorMessage += errors.pdfFile[0];
                                }
                            } else {
                                errorMessage = xhr.responseJSON.error;
                            }

                            alert(errorMessage);
                        } else {
                            // Handle other error types
                        }
                    }
                });
            });
        });
    </script>
@endsection
