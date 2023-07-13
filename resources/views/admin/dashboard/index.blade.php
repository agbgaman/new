@extends('layouts.app')

@section('css')
    <link href="{{URL::asset('plugins/tippy/scale-extreme.css')}}" rel="stylesheet"/>
    <link href="{{URL::asset('plugins/tippy/material.css')}}" rel="stylesheet"/>
@endsection

@section('page-header')
    <!-- PAGE HEADER-->
    <div class="page-header mt-5-7">
        <div class="page-leftheader">
            <h4 class="page-title mb-0">{{ __('Admin Dashboard') }}</h4>
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i
                            class="fa-solid fa-chart-tree-map mr-2 fs-12"></i>{{ __('Admin') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#"> {{ __('Dashboard') }}</a></li>
            </ol>
        </div>
    </div>
    <!--END PAGE HEADER -->
@endsection

@section('content')

    <!-- TOP BOX INFO -->
    <div class="row">
        <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
            <div class="card overflow-hidden border-0">
                <div class="card-body">
                    <div class="d-flex align-items-end justify-content-between">
                        <div>
                            <p class=" mb-3 fs-12 font-weight-bold">{{ __('Total New Users') }} <span
                                    class="text-muted">({{ __('Current Month') }})</span>
                            </p>
                            <h2 class="mb-0"><span
                                    class="number-font fs-20">{{ number_format($total_data_monthly['new_users_current_month'][0]['data']) }}</span><span
                                    class="ml-2 text-muted fs-11 data-percentage-change"><span id="users_change"></span> {{ __('this month') }}</span>
                            </h2>
                        </div>
                        <span class="fs-40 mt-m1"><i class="fa-solid fa-user-check"></i></span>
                    </div>
                    <div class="d-flex mt-2">
                        <div>
                            <span class="text-muted fs-12 mr-1">{{ __('Last Month') }}</span>
                            <span class="number-font fs-12"><i class="fa fa-chain mr-1 text-success"></i>{{ number_format($total_data_monthly['new_users_past_month'][0]['data']) }}</span>
                        </div>
                        <div class="ml-auto">
                            <span class="text-muted fs-12 mr-1">{{ __('Current Year') }} ({{ __('Total') }})</span>
                            <span class="number-font fs-12"><i class="fa fa-bookmark mr-1 text-success"></i>{{ number_format($total_data_yearly['total_new_users'][0]['data']) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
            <div class="card overflow-hidden border-0">
                <div class="card-body">
                    <div class="d-flex align-items-end justify-content-between">
                        <div>
                            <p class=" mb-3 fs-12 font-weight-bold">{{ __('Total New Subscribers') }} <span
                                    class="text-muted">({{ __('Current Month') }})</span></p>
                            <h2 class="mb-0"><span
                                    class="number-font fs-20">{{ number_format($total_data_monthly['new_subscribers_current_month'][0]['data']) }}</span><span
                                    class="ml-2 text-muted fs-11 data-percentage-change"><span
                                        id="subscribers_change"></span> {{ __('this month') }}</span></h2>
                        </div>
                        <span class="text-info fs-40 mt-m1"><i class="fa-solid fa-user-tie-hair"></i></span>
                    </div>
                    <div class="d-flex mt-2">
                        <div>
                            <span class="text-muted fs-12 mr-1">{{ __('Last Month') }}</span>
                            <span class="number-font fs-12"><i class="fa fa-chain mr-1 text-success"></i>{{ number_format($total_data_monthly['new_subscribers_past_month'][0]['data']) }}</span>
                        </div>
                        <div class="ml-auto">
                            <span class="text-muted fs-12 mr-1">{{ __('Current Year') }} ({{ __('Total') }})</span>
                            <span class="number-font fs-12"><i class="fa fa-bookmark mr-1 text-success"></i>{{ number_format($total_data_yearly['total_new_subscribers'][0]['data']) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
            <div class="card overflow-hidden border-0">
                <div class="card-body">
                    <div class="d-flex align-items-end justify-content-between">
                        <div>
                            <p class=" mb-3 fs-12 font-weight-bold">{{ __('Total Income') }} <span class="text-muted">({{ __('Current Month') }})</span>
                            </p>
                            <h2 class="mb-0"><span
                                    class="number-font fs-20">{!! config('payment.default_system_currency_symbol') !!}{{ number_format((float)$total_data_monthly['income_current_month'][0]['data'], 2) }}</span><span
                                    class="ml-2 text-muted fs-11 data-percentage-change"><span
                                        id="income_change"></span> {{ __('this month') }}</span></h2>
                        </div>
                        <span class="text-success fs-40 mt-m1"><i class="fa-solid fa-badge-dollar"></i></span>
                    </div>
                    <div class="d-flex mt-2">
                        <div>
                            <span class="text-muted fs-12 mr-1">{{ __('Last Month') }}</span>
                            <span class="number-font fs-12"><i
                                    class="fa fa-chain mr-1 text-success"></i>{!! config('payment.default_system_currency_symbol') !!}{{ number_format((float)$total_data_monthly['income_past_month'][0]['data'], 2) }}</span>
                        </div>
                        <div class="ml-auto">
                            <span class="text-muted fs-12 mr-1">{{ __('Current Year') }} ({{ __('Total') }})</span>
                            <span class="number-font fs-12"><i
                                    class="fa fa-bookmark mr-1 text-success"></i>{!! config('payment.default_system_currency_symbol') !!}{{ number_format((float)$total_data_yearly['total_income'][0]['data'], 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
            <div class="card overflow-hidden border-0">
                <div class="card-body">
                    <div class="d-flex align-items-end justify-content-between">
                        <div>
                            <p class=" mb-3 fs-12 font-weight-bold">{{ __('Total Estimated Spending') }} <span
                                    class="text-muted">({{ __('Current Month') }})</span></p>
                            <h2 class="mb-0"><span
                                    class="number-font fs-20">${{ number_format((float)$total_data_monthly['spending_current_month'], 2, '.', '') }}</span><span
                                    class="ml-2 text-muted fs-11 data-percentage-change"><span
                                        id="spending_change"></span> {{ __('this month') }}</span></h2>
                        </div>
                        <span class="text-secondary fs-40 mt-m1"><i class="fa-solid fa-badge-percent"></i></span>
                    </div>
                    <div class="d-flex mt-2">
                        <div>
                            <span class="text-muted fs-12 mr-1">{{ __('Last Month') }}</span>
                            <span class="number-font fs-12"><i class="fa fa-chain mr-1 text-danger"></i>${{ number_format((float)$total_data_monthly['spending_past_month'], 2, '.', '') }}</span>
                        </div>
                        <div class="ml-auto">
                            <span class="text-muted fs-12 mr-1">{{ __('Current Year') }} ({{ __('Total') }})</span>
                            <span class="number-font fs-12"><i class="fa fa-bookmark mr-1 text-danger"></i>${{ number_format((float)$total_data_yearly['total_spending'], 2, '.', '') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
            <div class="card overflow-hidden border-0">
                <div class="card-body">
                    <p class=" mb-3 fs-12">{{ __('Free Characters Used') }} <span class="text-muted">({{ __('Current Month') }})</span>
                    </p>
                    <h2 class="mb-2 number-font fs-20">{{ number_format($total_data_monthly['free_chars'][0]['data']) }}</h2>
                    <small class="fs-12 text-muted">{{ __('Compared to Last Month') }} (<span
                            id="free_chars_past"></span>): </small>
                    <span class="fs-12" id="free_chars"></span>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
            <div class="card overflow-hidden border-0">
                <div class="card-body">
                    <p class=" mb-3 fs-12">{{ __('Paid Characters Used') }} <span class="text-muted">({{ __('Current Month') }})</span>
                    </p>
                    <h2 class="mb-2 number-font fs-20">{{ number_format($total_data_monthly['paid_chars'][0]['data']) }}</h2>
                    <small class="fs-12 text-muted">{{ __('Compared to Last Month') }} (<span
                            id="paid_chars_past"></span>): </small>
                    <span class="fs-12" id="paid_chars"></span>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
            <div class="card overflow-hidden border-0">
                <div class="card-body">
                    <p class=" mb-3 fs-12">{{ __('Purchased Characters') }} <span class="text-muted">({{ __('Current Month') }})</span>
                    </p>
                    <h2 class="mb-2 number-font fs-20">{{ number_format($total_data_monthly['purchased_chars'][0]['data']) }}</h2>
                    <small class="fs-12 text-muted">{{ __('Compared to Last Month') }} (<span
                            id="purchased_chars_past"></span>): </small>
                    <span class="fs-12" id="purchased_chars"></span>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
            <div class="card overflow-hidden border-0">
                <div class="card-body">
                    <p class=" mb-3 fs-12">{{ __('Voiceover Studio Tasks') }} <span class="text-muted">({{ __('Current Month') }})</span>
                    </p>
                    <h2 class="mb-2 number-font fs-20">{{ number_format($total_data_monthly['audio_files'][0]['data']) }}</h2>
                    <small class="fs-12 text-muted">{{ __('Compared to Last Month') }} (<span
                            id="audio_files_past"></span>): </small>
                    <span class="fs-12" id="audio_files"></span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
            <div class="card overflow-hidden border-0">
                <div class="card-body">
                    <p class=" mb-3 fs-12">{{ __('Free Minutes Used') }} <span class="text-muted">({{ __('Current Month') }})</span>
                    </p>
                    <h2 class="mb-2 number-font fs-20">{{ number_format((float)$total_data_monthly['free_minutes'][0]['data'] / 60, 2) }}</h2>
                    <small class="fs-12 text-muted">{{ __('Compared to Last Month') }} (<span
                            id="free_minutes_past"></span>): </small>
                    <span class="fs-12" id="free_minutes"></span>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
            <div class="card overflow-hidden border-0">
                <div class="card-body">
                    <p class=" mb-3 fs-12">{{ __('Paid Minutes Used') }} <span class="text-muted">({{ __('Current Month') }})</span>
                    </p>
                    <h2 class="mb-2 number-font fs-20">{{ number_format((float)$total_data_monthly['paid_minutes'][0]['data'] / 60, 2) }}</h2>
                    <small class="fs-12 text-muted">{{ __('Compared to Last Month') }} (<span
                            id="paid_minutes_past"></span>): </small>
                    <span class="fs-12" id="paid_minutes"></span>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
            <div class="card overflow-hidden border-0">
                <div class="card-body">
                    <p class=" mb-3 fs-12">{{ __('Purchased Minutes') }} <span class="text-muted">({{ __('Current Month') }})</span>
                    </p>
                    <h2 class="mb-2 number-font fs-20">{{ number_format($total_data_monthly['purchased_minutes'][0]['data']) }}</h2>
                    <small class="fs-12 text-muted">{{ __('Compared to Last Month') }} (<span
                            id="purchased_minutes_past"></span>): </small>
                    <span class="fs-12" id="purchased_minutes"></span>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
            <div class="card overflow-hidden border-0">
                <div class="card-body">
                    <p class=" mb-3 fs-12">{{ __('Transcribe Studio Tasks') }} <span class="text-muted">({{ __('Current Month') }})</span>
                    </p>
                    <h2 class="mb-2 number-font fs-20">{{ number_format($total_data_monthly['transcribe_tasks'][0]['data']) }}</h2>
                    <small class="fs-12 text-muted">{{ __('Compared to Last Month') }} (<span id="tasks_past"></span>):
                    </small>
                    <span class="fs-12" id="tasks"></span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 col-md-12 mt-3">
            <div class="card overflow-hidden border-0">
                <div class="card-header d-inline border-0">
                    <div>
                        <h3 class="card-title fs-16 mt-3 mb-4"><i
                                class="fa-solid fa-money-check-dollar-pen mr-4 text-info"></i>{{ __('Finance Overview') }}
                        </h3>
                    </div>
                    <div class="row">
                        <div class="col-xl-4 col-md-4 col-sm-12">
                            <div>
                                <h3 class="card-title fs-24 font-weight-800">{!! config('payment.default_system_currency_symbol') !!}{{ number_format((float)$total_data_yearly['total_income'][0]['data'], 2, '.', '') }}</h3>
                            </div>
                            <div class="mb-3">
                                <span class="fs-12 text-muted">{{ __('Total Earnings Current Year') }}</span>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-4 col-sm-12">
                            <div>
                                <h3 class="card-title fs-24 font-weight-800">
                                    ${{ number_format((float)$total_data_yearly['total_spending'], 2, '.', '') }}</h3>
                            </div>
                            <div class="mb-3">
                                <span class="fs-12 text-muted">{{ __('Total Estimated Spending Current Year') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="">
                                <canvas id="chart-total-income" class="h-330"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-12 mt-3">
            <div class="card overflow-hidden border-0">
                <div class="card-header d-inline border-0">
                    <div>
                        <h3 class="card-title fs-16 mt-3 mb-4"><i
                                class="fa-solid fa-users-viewfinder mr-4 text-info"></i>{{ __('Total New Users') }}</h3>
                    </div>
                    <div>
                        <h3 class="card-title fs-24 font-weight-800">{{ number_format($total_data_yearly['total_new_users'][0]['data']) }}</h3>
                    </div>
                    <div class="mb-3">
                        <span class="fs-12 text-muted">{{ __('Total New Registered Users Current Year') }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="">
                                <canvas id="chart-total-users-year" class="h-330"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 col-md-12 mt-3">
            <div class="card overflow-hidden border-0">
                <div class="card-header d-inline border-0">
                    <div>
                        <h3 class="card-title fs-16 mt-3 mb-4"><i
                                class="fa-solid fa-money-check-pen mr-4 text-info"></i>{{ __('Latest Registrations') }}
                        </h3>
                        <a href="{{ route('admin.user.list') }}" class="" id="return-sound"
                           data-tippy-content="{{ __('View All Registered Users') }}."><i
                                class="fa-solid fa-bring-front"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <table class="table" id="database-backup">
                                <thead>
                                <tr role="row">
                                    <th class="fs-12 font-weight-700 border-top-0">{{ __('User') }}</th>
                                    <th class="fs-12 font-weight-700 border-top-0">{{ __('Group') }}</th>
                                    <th class="fs-12 font-weight-700 border-top-0">{{ __('Status') }}</th>
                                    <th class="fs-12 font-weight-700 border-top-0">{{ __('Registered On') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($result as $data)
                                    <tr>
                                        <td>@if ($data->profile_photo_path)
                                                <div class="d-flex">
                                                    <div class="widget-user-image-sm overflow-hidden mr-4"><img
                                                            alt="Avatar" src="{{ $data->profile_photo_path }}"></div>
                                                    <div class="widget-user-name"><span
                                                            class="font-weight-bold">{{ $data->name }}</span><br><span
                                                            class="text-muted">{{ $data->email }}</span></div>
                                                </div>
                                            @else
                                                <div class="d-flex">
                                                    <div class="widget-user-image-sm overflow-hidden mr-4"><img
                                                            alt="Avatar" class="rounded-circle"
                                                            src="{{ URL::asset('img/users/avatar.png') }}"></div>
                                                    <div class="widget-user-name"><span
                                                            class="font-weight-bold">{{ $data->name }}</span><br><span
                                                            class="text-muted">{{ $data->email }}</span></div>
                                                </div>
                                            @endif</td>
                                        <td><span
                                                class="cell-box user-group-{{ $data->group }}">{{ ucfirst($data->group) }}</span>
                                        </td>
                                        <td><span
                                                class="cell-box user-{{ $data->status }}">{{ ucfirst($data->status) }}</span>
                                        </td>
                                        <td><span
                                                class="font-weight-bold">{{ date_format($data->created_at, 'd M Y') }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-12 mt-3">
            <div class="card overflow-hidden border-0">
                <div class="card-header d-inline border-0">
                    <div>
                        <h3 class="card-title fs-16 mt-3 mb-4"><i
                                class="fa-solid fa-money-bill-transfer mr-4 text-info"></i>{{ __('Latest Transactions') }}
                        </h3>
                        <a href="{{ route('admin.finance.transactions') }}" class="" id="return-sound"
                           data-tippy-content="{{ __('View All Transactions') }}."><i
                                class="fa-solid fa-bring-front"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <table class="table" id="database-backup">
                                <thead>
                                <tr role="row">
                                    <th class="fs-12 font-weight-700 border-top-0" width="10%">{{ __('Paid By') }}</th>
                                    <th class="fs-12 font-weight-700 border-top-0" width="10%">{{ __('Status') }}</th>
                                    <th class="fs-12 font-weight-700 border-top-0" width="10%">{{ __('Total') }}</th>
                                    <th class="fs-12 font-weight-700 border-top-0" width="10%">{{ __('Gateway') }}</th>
                                    <th class="fs-12 font-weight-700 border-top-0" width="10%">{{ __('Paid On') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($transaction as $data)
                                    <tr>
                                        <td>@if ($data->profile_photo_path)
                                                <div class="d-flex">
                                                    <div class="widget-user-image-sm overflow-hidden mr-4"><img
                                                            alt="Avatar" src="{{ $data->profile_photo_path }}"></div>
                                                    <div class="widget-user-name"><span
                                                            class="font-weight-bold">{{ $data->name }}</span><br><span
                                                            class="text-muted">{{ $data->email }}</span></div>
                                                </div>
                                            @else
                                                <div class="d-flex">
                                                    <div class="widget-user-image-sm overflow-hidden mr-4"><img
                                                            alt="Avatar" class="rounded-circle"
                                                            src="{{ URL::asset('img/users/avatar.png') }}"></div>
                                                    <div class="widget-user-name"><span
                                                            class="font-weight-bold">{{ $data->name }}</span><br><span
                                                            class="text-muted">{{ $data->email }}</span></div>
                                                </div>
                                            @endif</td>
                                        <td><span
                                                class="cell-box payment-{{ strtolower($data->status) }}">{{ ucfirst($data->status) }}</span>
                                        </td>
                                        <td><span
                                                class="font-weight-bold">{!! config('payment.default_system_currency_symbol') !!}{{ $data->price }}</span>
                                        </td>
                                        <td>@if ($data->gateway == 'PayPal')
                                                <img alt="PayPal Gateway" class="w-60"
                                                     src="{{ URL::asset('img/payments/paypal.svg') }}">
                                            @elseif ($data->gateway == 'Stripe')
                                                <img alt="Stripe Gateway" class="w-40"
                                                     src="{{ URL::asset('img/payments/stripe.svg') }}">
                                            @elseif ($data->gateway == 'Razorpay')
                                                <img alt="Razorpay Gateway" class="w-60"
                                                     src="{{ URL::asset('img/payments/razorpay.svg') }}">
                                            @elseif ($data->gateway == 'Paystack')
                                                <img alt="Paystack Gateway" class="w-60"
                                                     src="{{ URL::asset('img/payments/paystack.svg') }}">
                                            @elseif ($data->gateway == 'BankTransfer')
                                                <img alt="BankTransfer Gateway" class="w-60"
                                                     src="{{ URL::asset('img/payments/bank-transfer.png') }}">
                                            @endif
                                        </td>
                                        <td><span
                                                class="font-weight-bold">{{ date_format($data->created_at, 'd M Y') }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12 mt-3">
            <div class="card overflow-hidden border-0">
                <div class="card-header d-inline border-0">
                    <div>
                        <h3 class="card-title fs-16 mt-3 mb-4"><i
                                class="fa-solid fa-users mr-4 text-info"></i>{{ __('New Registered Users') }}</h3>
                    </div>
                    <div class="mb-3">
                        <span class="fs-12 text-muted">{{ __('Registered Users Current Month') }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="">
                                <canvas id="chart-total-users-month" class="h-330"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-md-6 mt-3">
            <div class="card overflow-hidden border-0">
                <div class="card-header d-inline border-0">
                    <div>
                        <h3 class="card-title fs-16 mt-3 mb-4"><i
                                class="fa-solid fa-users mr-4 text-info"></i>{{ __('Project Remark') }}</h3>
                    </div>
                    <div class="mb-3  justify-content-between" style="display: flex">
                        <div>
                            <span class="fs-12 text-muted">{{ __('Rejected Remarks') }}</span>
                        </div>
                        <div>
                            <select id="remarksProject" class="form-control">
                                @foreach($projects as $project)
                                    <option value="{{$project->id}}">{{$project->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="">
                                <canvas id="myPieChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 mt-3">
            <div class="card overflow-hidden border-0">
                <div class="card-header d-inline border-0">
                    <div>
                        <h3 class="card-title fs-16 mt-3 mb-4"><i
                                class="fa-solid fa-users mr-4 text-info"></i>{{ __('Top Performer') }}</h3>
                    </div>
                    <div class="mb-3  justify-content-between" style="display: flex">
                        <div>
                            <span class="fs-12 text-muted">{{ __('User') }}</span>
                        </div>
                        <div>
                            <select id="performerProject" class="form-control">
                                @foreach($projects as $project)
                                    <option value="{{$project->id}}">{{$project->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="">
                                <canvas id="performerUserChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-md-6 mt-3">
            <div class="card overflow-hidden border-0">
                <div class="card-header d-inline border-0">
                    <div>
                        <h3 class="card-title fs-16 mt-3 mb-4"><i
                                class="fa-solid fa-users mr-4 text-info"></i>{{ __('Project Remark') }}</h3>
                    </div>
                    <div class="mb-3  justify-content-between" style="display: flex">
                        <div>
                            <span class="fs-12 text-muted">{{ __('Rejected Remarks') }}</span>
                        </div>
                        <div>
                            <select id="qaProject" class="form-control">
                                @foreach($projects as $project)
                                    <option value="{{$project->id}}">{{$project->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="">
                                <canvas id="qaPiechart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <!-- Chart JS -->
    <script src="{{URL::asset('plugins/chart/chart.min.js')}}"></script>
    <script src="{{URL::asset('plugins/tippy/popper.min.js')}}"></script>
    <script src="{{URL::asset('plugins/tippy/tippy-bundle.umd.min.js')}}"></script>
    <script type="text/javascript">
        $(function () {

            "use strict";


            $(document).ready(function () {





                var ctx = document.getElementById('myPieChart').getContext('2d');

                var baseColors = [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(128, 0, 0, 0.2)',
                    'rgba(128, 128, 0, 0.2)',
                    'rgba(0, 128, 128, 0.2)',
                    'rgba(128, 0, 128, 0.2)'
                ];

                var chart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: [],
                        datasets: [{
                            data: [],
                            backgroundColor: baseColors
                        }]
                    }
                });

                function getRemarkData() {
                    var projectId = $('#remarksProject').val();
                    console.log(projectId)
                    $.ajax({
                        url: '/admin/dashboard/remarks-data',
                        data: {
                            project_id: projectId
                        },
                        method: 'GET',
                        success: function (data) {
                            console.log(data);
                            updateChart(data);
                        }
                    });
                }

                var updateChart = function (data) {
                    var data = JSON.parse(data);

                    var labels = Object.keys(data);
                    var values = Object.values(data);
                    chart.data.labels = labels;
                    chart.data.datasets[0].data = values;

                    chart.update();
                }

                $('#remarksProject').change(function () {
                    var projectId = $(this).val();
                    $.ajax({
                        url: '/admin/dashboard/remarks-data',
                        data: {
                            project_id: projectId
                        },
                        method: 'GET',
                        success: function (data) {
                            console.log(data);
                            updateChart(data);
                        }
                    });
                });
                getRemarkData();

        });
            $(document).ready(function () {

                var ctx = document.getElementById('qaPiechart').getContext('2d');

                var baseColors = [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(128, 0, 0, 0.2)',
                    'rgba(128, 128, 0, 0.2)',
                    'rgba(0, 128, 128, 0.2)',
                    'rgba(128, 0, 128, 0.2)'
                ];

                var chart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: [],
                        datasets: [{
                            data: [],
                            backgroundColor: baseColors
                        }]
                    }
                });

                function getRemarkData() {
                    var projectId = $('#qaProject').val();
                    console.log(projectId)
                    $.ajax({
                        url: '/admin/dashboard/qa-data',
                        data: {
                            project_id: projectId
                        },
                        method: 'GET',
                        success: function (data) {
                            console.log(data);
                            updateChart(data);
                        }
                    });
                }

                var updateChart = function (data) {
                    var data = JSON.parse(data);

                    var labels = Object.keys(data);
                    var values = Object.values(data);
                    chart.data.labels = labels;
                    chart.data.datasets[0].data = values;

                    chart.update();
                }

                $('#qaProject').change(function () {
                    var projectId = $(this).val();
                    $.ajax({
                        url: '/admin/dashboard/qa-data',
                        data: {
                            project_id: projectId
                        },
                        method: 'GET',
                        success: function (data) {
                            console.log(data);
                            updateChart(data);
                        }
                    });
                });
                getRemarkData();

        });
            $(document).ready(function () {

                var ctx = document.getElementById('performerUserChart').getContext('2d');

                var baseColors = [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(128, 0, 0, 0.2)',
                    'rgba(128, 128, 0, 0.2)',
                    'rgba(0, 128, 128, 0.2)',
                    'rgba(128, 0, 128, 0.2)'
                ];

                var chart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: [],
                        datasets: [{
                            data: [],
                            backgroundColor: baseColors
                        }]
                    }
                });

                function getPerformerData() {
                    var projectId = $('#performerProject').val();
                    console.log(projectId)
                    $.ajax({
                        url: '/admin/dashboard/performer-data',
                        data: {
                            project_id: projectId
                        },
                        method: 'GET',
                        success: function (data) {
                            console.log(data);
                            updatePerformerChart(data);
                        }
                    });
                }

                var updatePerformerChart = function (data) {
                    var data = JSON.parse(data);
                    var values = Object.keys(data);
                    var labels = Object.values(data);
                    console.log(labels,values)

                    chart.data.labels = labels;
                    chart.data.datasets[0].data = values;

                    chart.update();
                }

                $('#performerProject').change(function () {
                    var projectId = $(this).val();
                    $.ajax({
                        url: '/admin/dashboard/performer-data',
                        data: {
                            project_id: projectId
                        },
                        method: 'GET',
                        success: function (data) {
                            console.log(data);
                            updatePerformerChart(data);
                        }
                    });
                });
                getPerformerData();

        });
        // Total Income Chart
        var incomeData = JSON.parse(`<?php echo $chart_data['total_income']; ?>`);

        var incomeDataset = Object.values(incomeData);
        var ctx = document.getElementById('chart-total-income');
        let delayed;

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: '{{ __('Total Income') }} ({{ config('payment.default_system_currency') }}) ',
                    data: incomeDataset,
                    backgroundColor: '#FF9D00',
                    borderWidth: 1,
                    borderRadius: 20,
                    barPercentage: 0.8,
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
                            stepSize: 40,
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

        // Total New User Analysis Chart
        var userMonthlyData = JSON.parse(`<?php echo $chart_data['monthly_new_users']; ?>`);
        var userMonthlyDataset = Object.values(userMonthlyData);
        var ctx = document.getElementById('chart-total-users-month');
        let delayed1;

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31'],
                datasets: [{
                    label: '{{ __('New Registered Users') }} ',
                    data: userMonthlyDataset,
                    backgroundColor: '#007bff',
                    borderWidth: 1,
                    borderRadius: 20,
                    barPercentage: 0.7,
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
                        delayed1 = true;
                    },
                    delay: (context) => {
                        let delay = 0;
                        if (context.type === 'data' && context.mode === 'default' && !delayed1) {
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
                            stepSize: 40,
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

        // Total New User Analysis Chart
        var userYearlyData = JSON.parse(`<?php echo $chart_data['total_new_users']; ?>`);
        var userYearlyDataset = Object.values(userYearlyData);
        var ctx = document.getElementById('chart-total-users-year');
        let delayed3;

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: '{{ __('Total New Registered Users') }} ',
                    data: userYearlyDataset,
                    backgroundColor: '#1e1e2d',
                    borderWidth: 1,
                    borderRadius: 20,
                    barPercentage: 0.8,
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
                        delayed3 = true;
                    },
                    delay: (context) => {
                        let delay = 0;
                        if (context.type === 'data' && context.mode === 'default' && !delayed3) {
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
                            stepSize: 40,
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

        // Percentage Difference First Row
        var users_current_month = JSON.parse(`<?php echo $percentage['users_current']; ?>`);
        var users_past_month = JSON.parse(`<?php echo $percentage['users_past']; ?>`);
        var subscribers_current_month = JSON.parse(`<?php echo $percentage['subscribers_current']; ?>`);
        var subscribers_past_month = JSON.parse(`<?php echo $percentage['subscribers_past']; ?>`);
        var income_current_month = JSON.parse(`<?php echo $percentage['income_current']; ?>`);
        var income_past_month = JSON.parse(`<?php echo $percentage['income_past']; ?>`);
        var spending_current_month = JSON.parse(`<?php echo $percentage['spending_current']; ?>`);
        var spending_past_month = JSON.parse(`<?php echo $percentage['spending_past']; ?>`);


        (users_current_month[0]['data'] == null) ? users_current_month = 0 : users_current_month = users_current_month[0]['data'];
        (users_past_month[0]['data'] == null) ? users_past_month = 0 : users_past_month = users_past_month[0]['data'];
        (subscribers_current_month[0]['data'] == null) ? subscribers_current_month = 0 : subscribers_current_month = subscribers_current_month[0]['data'];
        (subscribers_past_month[0]['data'] == null) ? subscribers_past_month = 0 : subscribers_past_month = subscribers_past_month[0]['data'];
        (income_current_month[0]['data'] == null) ? income_current_month = 0 : income_current_month = income_current_month[0]['data'];
        (income_past_month[0]['data'] == null) ? income_past_month = 0 : income_past_month = income_past_month[0]['data'];
        (spending_current_month == null) ? spending_current_month = 0.0 : spending_current_month = spending_current_month;
        (spending_past_month == null) ? spending_past_month = 0.0 : spending_past_month = spending_past_month;

        var users_current_total = parseInt(users_current_month);
        var users_past_total = parseInt(users_past_month);
        var subscribers_current_total = parseInt(subscribers_current_month);
        var subscribers_past_total = parseInt(subscribers_past_month);
        var income_current_total = parseInt(income_current_month);
        var income_past_total = parseInt(income_past_month);

        var users_change = mainPercentageDifference(users_past_month, users_current_month);
        var subscribers_change = mainPercentageDifference(subscribers_past_month, subscribers_current_month);
        var income_change = mainPercentageDifference(income_past_month, income_current_month);
        var spending_change = mainPercentageDifference(spending_past_month, spending_current_month);

        document.getElementById('users_change').innerHTML = users_change;
        document.getElementById('subscribers_change').innerHTML = subscribers_change;
        document.getElementById('income_change').innerHTML = income_change;
        document.getElementById('spending_change').innerHTML = spending_change;

        // Percentage Difference Second Row
        var free_current_month = JSON.parse(`<?php echo $percentage['free_current']; ?>`);
        var paid_current_month = JSON.parse(`<?php echo $percentage['paid_current']; ?>`);
        var purchased_current_month = JSON.parse(`<?php echo $percentage['purchased_current']; ?>`);
        var audio_current_month = JSON.parse(`<?php echo $percentage['audio_current']; ?>`);

        var free_past_month = JSON.parse(`<?php echo $percentage['free_past']; ?>`);
        var paid_past_month = JSON.parse(`<?php echo $percentage['paid_past']; ?>`);
        var purchased_past_month = JSON.parse(`<?php echo $percentage['purchased_past']; ?>`);
        var audio_past_month = JSON.parse(`<?php echo $percentage['audio_past']; ?>`);

        (free_current_month[0]['data'] == null) ? free_current_month = 0 : free_current_month = free_current_month[0]['data'];
        (paid_current_month[0]['data'] == null) ? paid_current_month = 0 : paid_current_month = paid_current_month[0]['data'];
        (purchased_current_month[0]['data'] == null) ? purchased_current_month = 0 : purchased_current_month = purchased_current_month[0]['data'];
        (audio_current_month[0]['data'] == null) ? audio_current_month = 0 : audio_current_month = audio_current_month[0]['data'];

        (free_past_month[0]['data'] == null) ? free_past_month = 0 : free_past_month = free_past_month[0]['data'];
        (paid_past_month[0]['data'] == null) ? paid_past_month = 0 : paid_past_month = paid_past_month[0]['data'];
        (purchased_past_month[0]['data'] == null) ? purchased_past_month = 0 : purchased_past_month = purchased_past_month[0]['data'];
        (audio_past_month[0]['data'] == null) ? audio_past_month = 0 : audio_past_month = audio_past_month[0]['data'];

        var free_current_total = parseInt(free_current_month);
        var paid_current_total = parseInt(paid_current_month);
        var audio_current_total = parseInt(audio_current_month);
        var purchased_current_total = parseInt(purchased_current_month);

        var free_past_total = parseInt(free_past_month);
        var paid_past_total = parseInt(paid_past_month);
        var audio_past_total = parseInt(audio_past_month);
        var purchased_past_total = parseInt(purchased_past_month);

        var free_change = characterPercentageDifference(free_past_total, free_current_total);
        var paid_change = characterPercentageDifference(paid_past_total, paid_current_total);
        var purchased_change = characterPercentageDifference(purchased_past_total, purchased_current_total);
        var audio_change = characterPercentageDifference(audio_past_total, audio_current_total);

        document.getElementById('free_chars_past').innerHTML = new Intl.NumberFormat().format(free_past_total);
        document.getElementById('paid_chars_past').innerHTML = new Intl.NumberFormat().format(paid_past_total);
        document.getElementById('purchased_chars_past').innerHTML = new Intl.NumberFormat().format(purchased_past_total);
        document.getElementById('audio_files_past').innerHTML = new Intl.NumberFormat().format(audio_past_total);

        document.getElementById('free_chars').innerHTML = free_change;
        document.getElementById('paid_chars').innerHTML = paid_change;
        document.getElementById('purchased_chars').innerHTML = purchased_change;
        document.getElementById('audio_files').innerHTML = audio_change;

        // Percentage Difference Third Row
        var free_current_month_minutes = JSON.parse(`<?php echo $percentage['free_current_minutes']; ?>`);
        var paid_current_month_minutes = JSON.parse(`<?php echo $percentage['paid_current_minutes']; ?>`);
        var purchased_current_month_minutes = JSON.parse(`<?php echo $percentage['purchased_current_minutes']; ?>`);
        var audio_current_month_minutes = JSON.parse(`<?php echo $percentage['task_current']; ?>`);

        var free_past_month_minutes = JSON.parse(`<?php echo $percentage['free_past_minutes']; ?>`);
        var paid_past_month_minutes = JSON.parse(`<?php echo $percentage['paid_past_minutes']; ?>`);
        var purchased_past_month_minutes = JSON.parse(`<?php echo $percentage['purchased_past_minutes']; ?>`);
        var audio_past_month_minutes = JSON.parse(`<?php echo $percentage['task_past']; ?>`);

        (free_current_month_minutes[0]['data'] == null) ? free_current_month_minutes = 0 : free_current_month_minutes = free_current_month_minutes[0]['data'];
        (paid_current_month_minutes[0]['data'] == null) ? paid_current_month_minutes = 0 : paid_current_month_minutes = paid_current_month_minutes[0]['data'];
        (purchased_current_month_minutes[0]['data'] == null) ? purchased_current_month_minutes = 0 : purchased_current_month_minutes = purchased_current_month_minutes[0]['data'];
        (audio_current_month_minutes[0]['data'] == null) ? audio_current_month_minutes = 0 : audio_current_month_minutes = audio_current_month_minutes[0]['data'];

        (free_past_month_minutes[0]['data'] == null) ? free_past_month_minutes = 0 : free_past_month_minutes = free_past_month_minutes[0]['data'];
        (paid_past_month_minutes[0]['data'] == null) ? paid_past_month_minutes = 0 : paid_past_month_minutes = paid_past_month_minutes[0]['data'];
        (purchased_past_month_minutes[0]['data'] == null) ? purchased_past_month_minutes = 0 : purchased_past_month_minutes = purchased_past_month_minutes[0]['data'];
        (audio_past_month_minutes[0]['data'] == null) ? audio_past_month_minutes = 0 : audio_past_month_minutes = audio_past_month_minutes[0]['data'];

        var free_current_total_minutes = parseInt(free_current_month_minutes);
        var paid_current_total_minutes = parseInt(paid_current_month_minutes);
        var audio_current_total_minutes = parseInt(audio_current_month_minutes);
        var purchased_current_total_minutes = parseInt(purchased_current_month_minutes);

        var free_past_total_minutes = parseInt(free_past_month_minutes);
        var paid_past_total_minutes = parseInt(paid_past_month_minutes);
        var audio_past_total_minutes = parseInt(audio_past_month_minutes);
        var purchased_past_total_minutes = parseInt(purchased_past_month_minutes);

        var free_change_minutes = characterPercentageDifference(free_past_total_minutes, free_current_total_minutes);
        var paid_change_minutes = characterPercentageDifference(paid_past_total_minutes, paid_current_total_minutes);
        var purchased_change_minutes = characterPercentageDifference(purchased_past_total_minutes, purchased_current_total_minutes);
        var audio_change_minutes = characterPercentageDifference(audio_past_total_minutes, audio_current_total_minutes);

        document.getElementById('free_minutes_past').innerHTML = (free_past_total_minutes / 60).toFixed(2);
        document.getElementById('paid_minutes_past').innerHTML = (paid_past_total_minutes / 60).toFixed(2);
        document.getElementById('purchased_minutes_past').innerHTML = new Intl.NumberFormat().format(purchased_past_total_minutes);
        document.getElementById('tasks_past').innerHTML = new Intl.NumberFormat().format(audio_past_total_minutes);

        document.getElementById('free_minutes').innerHTML = free_change_minutes;
        document.getElementById('paid_minutes').innerHTML = paid_change_minutes;
        document.getElementById('purchased_minutes').innerHTML = purchased_change_minutes;
        document.getElementById('tasks').innerHTML = audio_change_minutes;

        function characterPercentageDifference(past, current) {
            if (past == 0) {
                var change = (current == 0) ? '<span class="text-muted"> 0% No Change</span>' : '<span class="text-success"> 100% Increase</span>';
                return change;
            } else if (current == 0) {
                var change = (past == 0) ? '<span class="text-muted"> 0% No Change</span>' : '<span class="text-danger"> 100% Decrease</span>';
                return change;
            } else if (past == current) {
                var change = '<span class="text-muted"> 0% No Change</span>';
                return change;
            }

            var difference = current - past;
            var difference_value, result;

            var totalDifference = Math.abs(difference);
            var change = (totalDifference / past) * 100;

            if (difference > 0) {
                result = '<span class="text-success">' + change.toFixed(1) + '% Increase</span>';
            } else if (difference < 0) {
                result = '<span class="text-danger">' + change.toFixed(1) + '% Decrease</span>';
            } else {
                difference_value = '<span class="text-muted">' + change.toFixed(1) + '% No Change</span>';
            }

            return result;
        }

        function mainPercentageDifference(past, current) {
            if (past == 0) {
                var change = (current == 0) ? '<span class="text-muted"> 0%</span>' : '<span class="text-success"><i class="fa fa-caret-up"></i> 100%</span>';
                return change;
            } else if (current == 0) {
                var change = (past == 0) ? '<span class="text-muted"> 0%</span>' : '<span class="text-danger"><i class="fa fa-caret-down"></i> 100%</span>';
                return change;
            } else if (past == current) {
                var change = '<span class="text-muted"> 0%</span>';
                return change;
            }

            var difference = current - past;
            var difference_value, result;

            var totalDifference = Math.abs(difference);
            var change = (totalDifference / past) * 100;

            if (difference > 0) {
                result = '<span class="text-success"><i class="fa fa-caret-up"></i> ' + change.toFixed(1) + '%</span>';
            } else if (difference < 0) {
                result = '<span class="text-danger"><i class="fa fa-caret-down"></i> ' + change.toFixed(1) + '%</span>';
            } else {
                difference_value = '<span class="text-muted"> ' + change.toFixed(1) + '%</span>';
            }

            return result;
        }

        tippy('[data-tippy-content]', {
            animation: 'scale-extreme',
            theme: 'material',
        });
        })
        ;
    </script>
@endsection
