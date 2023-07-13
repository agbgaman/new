@extends('layouts.app')

@section('css')
    <!-- Data Table CSS -->
    <link href="{{URL::asset('plugins/awselect/awselect.min.css')}}" rel="stylesheet" />
@endsection

@section('page-header')
    <!-- PAGE HEADER -->
    <div class="page-header mt-5-7">
        <div class="page-leftheader">
            <h4 class="page-title mb-0">{{ __('New Price') }}</h4>
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-sack-dollar mr-2 fs-12"></i>{{ __('Admin') }}</a></li>
                <li class="breadcrumb-item" aria-current="page"><a href="{{ route('admin.finance.dashboard') }}"> {{ __('Finance Management') }}</a></li>
                <li class="breadcrumb-item" aria-current="page"><a href="{{ route('admin.price') }}"> {{ __('Price') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#"> {{ __('New Price') }}</a></li>
            </ol>
        </div>
    </div>
    <!-- END PAGE HEADER -->
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12 col-xm-12">
            <div class="card border-0">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Create Price') }}</h3>
                </div>
                <div class="card-body pt-5">
                    <form action="{{ route('admin.price.update',['id'=>$price->id]) }}" method="POST" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf

                        <div class="row">

                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="input-box">
                                    <h6>{{ __('Plan Status') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
                                    <select id="plan-status" name="price_status" data-placeholder="{{ __('Select Plan Status') }}:">
                                        <option value="active" @if ($price->status == 'active') selected @endif>{{ __('Active') }}</option>
                                        <option value="closed" @if ($price->status == 'closed') selected @endif>{{ __('Closed') }}</option>
                                    </select>
                                    @error('price_status')
                                    <p class="text-danger">{{ $errors->first('price_status') }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="input-box">
                                    <h6>{{ __('Price Name') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span> </h6>
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="price_name" name="price_name" value="{{ old('price_name', $price->price_name) }}"  required>
                                    </div>
                                    @error('price_name')
                                    <p class="text-danger">{{ $errors->first('price_name') }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="input-box">
                                    <h6>{{ __('Currency') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
                                    <select id="currency" name="currency" data-placeholder="{{ __('Select Currency') }}:">
                                        @foreach(config('currencies.all') as $key => $value)
                                            <option value="{{ $key }}" @if($price->currency == $key) selected @endif>{{ $value['name'] }} - {{ $key }} ({{ $value['symbol'] }})</option>
                                        @endforeach
                                    </select>
                                    @error('currency')
                                    <p class="text-danger">{{ $errors->first('currency') }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="input-box">
                                    <h6>{{ __('Commission') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
                                    <div class="form-group">
                                        <input type="number" step="0.01" class="form-control" id="commission" name="commission" value="{{ old('commission', $price->commission) }}" required>
                                    </div>
                                    @error('price')
                                    <p class="text-danger">{{ $errors->first('price') }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="card mt-6 mb-7 special-shadow border-0">
                            <div class="card-body">
                                <h6 class="fs-12 font-weight-bold mb-5"><i class="fa-solid fa-box-circle-check text-info fs-14 mr-1 fw-2"></i>{{ __('Prices') }}</h6>

                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="input-box">
                                            <h6>{{ __('Images Price') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
                                            <div class="form-group">
                                                <input type="number" class="form-control" id="image_price" name="image_price" value="{{ old('image_price', $price->image_price) }}" required>
                                            </div>
                                            @error('image_price')
                                            <p class="text-danger">{{ $errors->first('image_price') }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="input-box">
                                            <h6>{{ __('Text Price') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
                                            <div class="form-group">
                                                <input type="number" class="form-control" id="text_price" name="text_price" value="{{ old('text_price', $price->text_price) }}" required>
                                            </div>
                                            @error('text_price')
                                            <p class="text-danger">{{ $errors->first('text_price') }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="input-box">
                                            <h6>{{ __('COCO Price') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
                                            <div class="form-group">
                                                <input type="number" class="form-control" id="coco_price" name="coco_price" value="{{ old('coco_price', $price->coco_price) }}" required>
                                            </div>
                                            @error('coco_price')
                                            <p class="text-danger">{{ $errors->first('coco_price') }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- ACTION BUTTON -->
                        <div class="border-0 text-right mb-2 mt-1">
                            <a href="{{ route('admin.finance.prepaid') }}" class="btn btn-cancel mr-2">{{ __('Cancel') }}</a>
                            <button type="submit" class="btn btn-primary">{{ __('Create') }}</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <!-- Awselect JS -->
    <script src="{{URL::asset('plugins/awselect/awselect.min.js')}}"></script>
    <script src="{{URL::asset('js/awselect.js')}}"></script>
@endsection
