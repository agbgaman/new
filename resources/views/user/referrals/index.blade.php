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
			<h4 class="page-title mb-0">{{ __('Account') }}</h4>
			<ol class="breadcrumb mb-2">
				<li class="breadcrumb-item"><a href="{{route('user.dashboard')}}"><i class="fa-solid fa-badge-dollar mr-2 fs-12"></i>{{ __('User') }}</a></li>
				<li class="breadcrumb-item active" aria-current="page"><a href="{{ route('user.referral') }}"> {{ __('Account') }}</a></li>
			</ol>
		</div>
	</div>
	<!-- END PAGE HEADER -->
@endsection

@section('content')

    <div class="row">
        <div class="col-lg-12 col-md-12 col-xm-12">
            <div class="card border-0">
                <div class="card-header flex justify-content-between">
                    <h3 class="card-title">{{ __('Accounts Statement') }}</h3>
                </div>
                <div class="card-body pt-2">
                    <!-- BOX CONTENT -->
                    <div class="box-content">

                        <!-- DATATABLE -->
                        <table id='listFoldersTable' class='table listFoldersTable' width='100%'>
                            <thead>
                            <tr>
                                <td width="7%">{{ __('Created On') }}</td>
                                <th width="5%">{{ __('Project Name') }}</th>
                                <td width="7%">{{ __('User') }}</td>
                                <th width="7%">{{ __('Accepted Data') }}</th>
                                <th width="7%">{{ __('Rejected Data') }}</th>
                                <th width="8%">{{ __('Earning') }}</th>
                            </tr>
                            </thead>
                        </table>
                        <!-- END DATATABLE -->
                    </div> <!-- END BOX CONTENT -->
                </div>
            </div>
        </div>
    </div>

    <div class="row">
		<div class="col-lg-6 col-md-12 col-xm-12">
			<div class="card overflow-hidden border-0">
				<div class="card-body pt-7 pb-6">
					<h3 class="card-title fs-20 text-center">{{ __('Affiliate Program') }}</h3>
					<p class="fs-12 text-center pl-6 pr-6 mb-6">{{ $referral['referral_headline'] }}</p>
                    <div class="row text-center justify-content-md-center">
                        <div class="col-md-3 col-sm-12 referral-box special-shadow">
                            <div class="d-flex align-items-center justify-content-center">
                                <div class="mr-2"><span class="fa fa-money-bill-alt fs-40"></span></div> <!-- icon wrapped inside a div -->
                                <div><span class="">{{auth()->user()->balance}}</span></div> <!-- text wrapped inside a div -->
                            </div>
                            <a class="fs-12 font-weight-bold" href="{{route('user.invoices.index')}}">{{ __('Total Income') }}</a>
                        </div>
                    </div>

                    <div class="row text-center justify-content-md-center">
						<div class="col-md-3 col-sm-12 referral-box special-shadow">
							<div><i class="fa-solid fa-user-group fs-25"></i></div>
							<a class="fs-12 font-weight-bold" href="{{ route('user.referral.referrals') }}">{{ __('My Referrals') }}</a>
						</div>
						<div class="col-md-3 col-sm-12 referral-box special-shadow">
							<div><i class="fa-solid fa-money-check-dollar-pen fs-25"></i></div>
							<a class="fs-12 font-weight-bold" href="{{ route('user.referral.payout') }}">{{ __('My Payouts') }}</a>
						</div>
						<div class="col-md-3 col-sm-12 referral-box special-shadow">
							<div><i class="fa-solid fa-wallet fs-25"></i></div>
							<a class="fs-12 font-weight-bold" href="{{ route('user.referral.gateway') }}">{{ __('My Payment Details') }}</a>
						</div>
					</div>

					<div class="row mt-7 ml-4 mr-4">
						<div class="col-md-12 col-sm-12">
							<div class="input-box">
								<h6 class="fs-12 font-weight-bold poppins">{{ __('My Referral URL') }}</h6>
								<div class="form-group d-flex referral-social-icons">
									<input type="text" class="form-control" id="email" readonly value="{{ config('app.url') }}/?ref={{ auth()->user()->referral_id }}">
									<div class="ml-2">
										<a href="" class="btn create-project pb-2" id="actions-copy" data-link="{{ config('app.url') }}/?ref={{ auth()->user()->referral_id }}" data-tippy-content="Copy Referral Link"><i class="fa fa-link"></i></a>
									</div>
								</div>
							</div>
						</div>
                    </div>

{{--					<div class="row mt-5 ml-4 mr-4">--}}
{{--						<div class="col-md-6 col-sm-12">--}}
{{--							<div class="input-box">--}}
{{--								<h6 class="fs-12 font-weight-bold poppins">{{ __('My Earned Commissions') }}</h6>--}}
{{--								<p class="fs-12">{!! config('payment.default_system_currency_symbol') !!}{{ number_format((float)$total_commission[0]['data'], 2, '.', '') }} {{ config('payment.default_currency') }}</p>--}}
{{--							</div>--}}
{{--						</div>--}}
{{--						<div class="col-md-6 col-sm-12">--}}
{{--							<div class="input-box">--}}
{{--								<h6 class="fs-12 font-weight-bold poppins">{{ __('Referral Commission Rate') }}</h6>--}}
{{--								<p class="fs-12">{{ config('payment.referral.payment.commission') }}%</p>--}}
{{--							</div>--}}
{{--						</div>--}}
{{--					</div>--}}

{{--					<div class="row mt-5 ml-4 mr-4">--}}
{{--						<div class="col-md-6 col-sm-12">--}}
{{--							<div class="input-box">--}}
{{--								<h6 class="fs-12 font-weight-bold poppins">{{ __('Referral Policy') }}</h6>--}}
{{--								<p class="fs-12">{{ __('First Successful Subscription Plan Registration') }}</p>--}}
{{--							</div>--}}
{{--						</div>--}}
{{--					</div>--}}

{{--					<div class="row mt-5 ml-4 mr-4">--}}
{{--						<div class="col-md-12 col-sm-12">--}}
{{--							<div class="input-box">--}}
{{--								<h6 class="fs-12 font-weight-bold poppins mb-3">{{ __('Referral Guidelines') }}</h6>--}}
{{--								<pre class="fs-12 referral-guideline">{{ $referral['referral_guideline'] }}</pre>--}}
{{--							</div>--}}
{{--						</div>--}}
{{--					</div>--}}

				</div>
			</div>
		</div>

		<div class="col-lg-6 col-md-12 col-xm-12">
			<div class="card overflow-hidden border-0">
				<div class="card-body pt-7 pb-6">
					<h3 class="card-title fs-20 text-center">{{ __('How it Works') }}</h3>

					<div class="row text-center justify-content-md-center mt-7">
						<div class="col-lg-3 col-md-3 col-sm-4">
							<div class="referral-icon-user">
								<i class="fa-solid fa-message-check"></i>
								<h6 class="mt-3 fs-12 font-weight-bold">{{ __('Send Invitation') }}</h6>
								<p class="fs-12">{{ __('Send your referral link to your friends and tell them how cool is') }} {{ config('app.name') }}!</p>
							</div>
						</div>
						<div class="col-lg-3 col-md-3 col-sm-4">
							<div class="referral-icon-user">
								<i class="fa-solid fa-user-check"></i>
								<h6 class="mt-2 fs-12 font-weight-bold">{{ __('Crowd Services') }}</h6>
								<p class="fs-12">{{ __('Let them register to our Dash Platform') }}.</p>
							</div>
						</div>
						<div class="col-lg-3 col-md-3 col-sm-4">
							<div class="referral-icon-user">
								<i class="fa-solid fa-badge-percent"></i>
								<h6 class="mt-2 fs-12 font-weight-bold">{{ __('Get Commissions') }}</h6>
								<p class="fs-12">{{ __('Earn commission 20% for their task completion') }}!</p>
							</div>
						</div>
					</div>

					<form id="" action="{{ route('user.referral.email') }}" method="post" enctype="multipart/form-data">
						@csrf
						<div class="row mt-6 ml-4 mr-4">
							<div class="col-md-12">
								<h6 class="fs-12 font-weight-bold">{{ __('Invite your friends') }}</h6>
								<div class="input-box">
									<h6>{{ __('Insert your friends email address and send him an invitations to join ') }} {{ config('app.name') }}!</h6>
									<div class="input-group file-browser">
										<input type="email" class="form-control @error('email') is-danger @enderror border-right-0 browse-file" id="email" name="email" placeholder="Email address">
										<label class="input-group-btn">
											<button class="btn btn-primary special-btn" id="invite-friends-button">
												{{ __('Send') }}
											</button>
										</label>
									</div>
									@error('email')
										<p class="text-danger">{{ $errors->first('email') }}</p>
									@enderror
								</div>

								<input type="text" name="subject" value="Introduction to join {{ config('app.name') }}" hidden>
								<input type="text" name="message" value="I want to refer you to this awesome data archival service that I'm using! You can register via this link: {{ config('app.url') }}/?ref={{ auth()->user()->referral_id }}" hidden>
							</div>
						</div>
					</form>

					<div class="row mt-6 ml-4 mr-4">
						<h6 class="fs-12 ml-3 font-weight-bold">{{ __('Share the referral link') }}</h6>
						<h6 class="fs-12 ml-3">{{ __('You can also share your referral link by copying and sending it or sharing it on your social media profiles') }}.</h6>
						<div class="col-md-8 col-sm-12">
							<div class="input-box">
								<div class="form-group">
									<input type="text" class="form-control" id="email" readonly value="{{ config('app.url') }}/?ref={{ auth()->user()->referral_id }}">
								</div>
							</div>
						</div>
						<div class="col-md-4 col-sm-12 referral-social-icons text-right">
							<div class="actions-total">
								<a href="https://www.facebook.com/sharer/sharer.php?u={{ config('app.url') }}/?ref={{ auth()->user()->referral_id }}&t=Registration Link" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;" target="_blank" class="btn actions-total-buttons" id="actions-facebook" data-tippy-content="Share in Facebook"><i class="fa-brands fa-facebook-f"></i></a>
								<a href="https://www.linkedin.com/shareArticle?mini=true&url={{ config('app.url') }}/?ref={{ auth()->user()->referral_id }}&title=Registration Link" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;" target="_blank" class="btn actions-total-buttons" id="actions-linkedin" data-tippy-content="Share in Linkedin"><i class="fa-brands fa-linkedin"></i></a>
								<a href="http://www.reddit.com/submit?url={{ config('app.url') }}/?ref={{ auth()->user()->referral_id }}" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;" target="_blank" class="btn actions-total-buttons" id="actions-reddit" data-tippy-content="Share in Reddit"><i class="fa-brands fa-reddit"></i></a>
								<a href="https://twitter.com/share?url={{ config('app.url') }}/?ref={{ auth()->user()->referral_id }}&text=Registration Link" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;" target="_blank" class="btn actions-total-buttons" id="actions-twitter" data-tippy-content="Share in Twitter"><i class="fa-brands fa-twitter"></i></a>

							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>

@endsection

@section('js')
	<!-- Link Share JS -->
	<script src="{{URL::asset('js/link-share.js')}}"></script>
    <script src="{{URL::asset('plugins/datatable/datatables.min.js')}}"></script>
    <script src="{{URL::asset('plugins/sweetalert/sweetalert2.all.min.js')}}"></script>
    <script type="text/javascript">
        $(function () {

            "use strict";

            var table = $('#listFoldersTable').DataTable({
                "lengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
                responsive: true,
                colReorder: true,
                "order": [[ 0, "desc" ]],
                language: {
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
                ajax: "{{ route('user.invoices.index') }}",
                columns: [
                    {
                        data: 'created-on',
                        name: 'created-on',
                        orderable:  true,
                        searchable: true
                    },
                    {
                        data: 'project-name',
                        name: 'project-name',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'userName',
                        name: 'userName',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'accepted_data',
                        name: 'accepted_data',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'rejected_data',
                        name: 'rejected_data',
                        orderable: true,
                        searchable: true
                    },
                    //
                    // {
                    //     data: 'referral_email',
                    //     name: 'referral_email',
                    //     orderable: true,
                    //     searchable: true
                    // },
                    {
                        data: 'earning-currency',
                        name: 'earning-currency',
                        orderable: true,
                        searchable: true
                    },
                    // {
                    //     data: 'commission',
                    //     name: 'commission',
                    //     orderable: true,
                    //     searchable: true
                    // },
                    // {
                    //     data: 'actions',
                    //     name: 'actions',
                    //     orderable: false,
                    //     searchable: false
                    // },
                ]
            });

            // DELETE CONFIRMATION
            $(document).on('click', '.deleteUserButton', function(e) {

                e.preventDefault();

                Swal.fire({
                    title: '{{ __('Confirm Text Deletion') }}',
                    text: '{{ __('Warning! This action will delete text permanently') }}',
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
                            url: 'csv-delete',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function (data) {
                                if (data == 'success') {
                                    Swal.fire('{{ __('Text Deleted') }}', '{{ __('Text has been successfully deleted') }}', 'success');
                                    $("#listFoldersTable").DataTable().ajax.reload();
                                } else {
                                    Swal.fire('{{ __('Delete Failed') }}', '{{ __('There was an error while deleting this text') }}', 'error');
                                }
                            },
                            error: function(data) {
                                Swal.fire({ type: 'error', title: 'Oops...', text: '{{ __("Something went wrong") }}!' })
                            }
                        })
                    }
                })
            });

        });
    </script>

@endsection
