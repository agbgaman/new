@extends('layouts.app')

@section('css')
	<!-- Data Table CSS -->
	<link href="{{URL::asset('plugins/datatable/datatables.min.css')}}" rel="stylesheet" />
@endsection

@section('page-header')
	<!-- PAGE HEADER -->
	<div class="page-header mt-5-7">
		<div class="page-leftheader">
{{--			<h4 class="page-title mb-0">{{ __('My Referrals') }}</h4>--}}
			<ol class="breadcrumb mb-2">
				<li class="breadcrumb-item"><a href="{{route('user.dashboard')}}"><i class="fa-solid fa-badge-dollar mr-2 fs-12"></i>{{ __('User') }}</a></li>
				<li class="breadcrumb-item" aria-current="page"><a href="{{ route('user.referral') }}"> {{ __('Affiliate Program') }}</a></li>
				<li class="breadcrumb-item active" aria-current="page"><a href="{{url('#')}}"> {{ __('My Referrals') }}</a></li>
			</ol>
		</div>
	</div>
	<!-- END PAGE HEADER -->
@endsection
@section('content')
	<div class="row">
        <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
            <div class="card overflow-hidden border-0">
                <div class="card-body">
                    <span class="fa fa-money-bill-alt text-primary fs-45 float-right"></span>
                    <p class="mb-3 fs-12 font-weight-bold mt-1">{{ __('Your Earning') }} <span class="text-muted">({{ __('All Time') }})</span></p>
                    <h2 class="mb-0"><span class="number-font-chars">{{ $user->currency .' '. number_format((float)$total_invoices->data, 2, '.', '')  }}</span></h2>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
            <div class="card border-0">
                <div class="card-body">
                    <span class="fa fa-money-bill-alt text-success fs-45 float-right"></span>
                    <p class="mb-3 fs-12 font-weight-bold mt-1">{{ __('Total Earned Commission') }} </p>
                    <h2 class="mb-0"><span class="number-font-chars">{{$user->currency .' '.  number_format((float)$total_commission[0]['data'], 2, '.', '') }}</span></h2>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
            <div class="card overflow-hidden border-0">
                <div class="card-body">
                    <span class="fa fa-users text-primary fs-45 float-right"></span>
                    <p class="mb-3 fs-12 font-weight-bold mt-1">{{ __('Total Teams') }} <span class="text-muted">({{ __('All Time') }})</span></p>
                    <h2 class="mb-0"><span class="number-font-chars">{{ number_format($total_users[0]['data']) }}</span></h2>
                </div>
            </div>
        </div>

        <!-- New column with input box -->
        <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
            <div class="card overflow-hidden border-0">

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
	</div>

	<div class="row">
		<div class="col-lg-12 col-md-12 col-xm-12">
			<div class="card overflow-hidden border-0">
				<div class="card-header">
					<h3 class="card-title">{{ __('Earned Commissions') }} <span class="text-muted">({{ __('All Time') }})</span></h3>
				</div>
				<div class="card-body pt-2">
					<!-- SET DATATABLE -->
					<table id='paymentsReferralTable' class='table' width='100%'>
						<thead>
							<tr>
								<th width="10%" class="fs-10">{{ __('Name') }}</th>
								<th width="12%" class="fs-10">{{ __('Email') }}</th>
								<th width="10%" class="fs-10">{{ __('Mobile Number') }}</th>
								<th width="10%" class="fs-10">{{ __('Project') }}</th>
								<th width="10%" class="fs-10">{{ __('Date') }}</th>
								<th width="7%" class="fs-10">{{ __('Amount') }} </th>
								<th width="7%" class="fs-10">{{ __('Currency') }} </th>
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
    <script src="{{URL::asset('js/link-share.js')}}"></script>
    <script src="{{URL::asset('plugins/datatable/datatables.min.js')}}"></script>
	<script type="text/javascript">
		$(function () {

			"use strict";

			// INITILIZE DATATABLE
			var table = $('#paymentsReferralTable').DataTable({
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
				ajax: "{{ route('user.referral.referrals') }}",
				columns: [
                    {
                        data: 'referral-name',
                        name: 'referral-name',
                        orderable: true,
                        searchable: true
                    },
					{
						data: 'referral-email',
						name: 'referral-email',
						orderable: true,
						searchable: true
					},
					{
						data: 'referral-number',
						name: 'referral-number',
						orderable: true,
						searchable: true
					},
					{
						data: 'project',
						name: 'project',
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
						data: 'custom-commission',
						name: 'custom-commission',
						orderable: true,
						searchable: true
					},
					{
						data: 'earned-currency',
						name: 'earned-currency',
						orderable: true,
						searchable: true
					},

				]
			});

		});
	</script>
@endsection
