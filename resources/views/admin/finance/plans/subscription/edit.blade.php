@extends('layouts.app')

@section('css')
	<!-- Data Table CSS -->
	<link href="{{URL::asset('plugins/awselect/awselect.min.css')}}" rel="stylesheet" />
@endsection

@section('page-header')
	<!-- PAGE HEADER -->
	<div class="page-header mt-5-7"> 
		<div class="page-leftheader">
			<h4 class="page-title mb-0">{{ __('Edit Subscription Plan') }}</h4>
			<ol class="breadcrumb mb-2">
				<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-sack-dollar mr-2 fs-12"></i>{{ __('Admin') }}</a></li>
				<li class="breadcrumb-item" aria-current="page"><a href="{{ route('admin.finance.dashboard') }}"> {{ __('Finance Management') }}</a></li>
				<li class="breadcrumb-item" aria-current="page"><a href="{{ route('admin.finance.plans') }}"> {{ __('Subscription Plans') }}</a></li>
				<li class="breadcrumb-item active" aria-current="page"><a href="{{url('#')}}"> {{ __('Edit Subscription Plan') }}</a></li>
			</ol>
		</div>
	</div>
	<!-- END PAGE HEADER -->
@endsection

@section('content')						
	<div class="row">
		<div class="col-lg-8 col-md-8 col-xm-12">
			<div class="card border-0">
				<div class="card-header">
					<h3 class="card-title">{{ __('Edit Subscription Plan') }}</h3>
				</div>
				<div class="card-body pt-5">									
					<form action="{{ route('admin.finance.plan.update', $id) }}" method="POST" enctype="multipart/form-data">
						@method('PUT')
						@csrf

						<div class="row">	
							<div class="col-lg-6 col-md-6 col-sm-12">						
								<div class="input-box">	
									<h6>{{ __('Plan Status') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
									<select id="plan-status" name="plan-status" data-placeholder="Select Plan Status:">			
										<option value="active" @if ($id->status == 'active') selected @endif>{{ __('Active') }}</option>
										<option value="closed" @if ($id->status == 'closed') selected @endif>{{ __('Closed') }}</option>
									</select>
									@error('plan-status')
										<p class="text-danger">{{ $errors->first('plan-status') }}</p>
									@enderror	
								</div>						
							</div>

							<div class="col-lg-6 col-md-6 col-sm-12">							
								<div class="input-box">								
									<h6>{{ __('Plan Name') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
									<div class="form-group">							    
										<input type="text" class="form-control" id="plan-name" name="plan-name" value="{{ $id->plan_name }}" required>
									</div> 
									@error('plan-name')
										<p class="text-danger">{{ $errors->first('plan-name') }}</p>
									@enderror
								</div> 						
							</div>

							<div class="col-lg-6 col-md-6 col-sm-12">							
								<div class="input-box">								
									<h6>{{ __('Price') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
									<div class="form-group">							    
										<input type="text" class="form-control" id="cost" name="cost" value="{{ $id->price }}" required>
									</div> 
									@error('cost')
										<p class="text-danger">{{ $errors->first('cost') }}</p>
									@enderror
								</div> 						
							</div>

							<div class="col-lg-6 col-md-6 col-sm-12">							
								<div class="input-box">								
									<h6>{{ __('Currency') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
									<select id="currency" name="currency" data-placeholder="{{ __('Select Currency') }}:">			
										@foreach(config('currencies.all') as $key => $value)
											<option value="{{ $key }}" @if($id->currency == $key) selected @endif>{{ $value['name'] }} - {{ $key }} ({{ $value['symbol'] }})</option>
										@endforeach
									</select>
									@error('currency')
										<p class="text-danger">{{ $errors->first('currency') }}</p>
									@enderror
								</div> 						
							</div>

							<div class="col-lg-6 col-md-6 col-sm-12">							
								<div class="input-box">								
									<h6>{{ __('Pricing Plan') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
									<select id="duration" name="duration" data-placeholder="{{ __('Select Payment Frequency') }}:">		
										<option value="monthly" @if ($id->pricing_plan == 'monthly') selected @endif>{{ __('Monthly') }}</option>
										<option value="yearly" @if ($id->pricing_plan == 'yearly') selected @endif>{{ __('Yearly') }}</option>
									</select>
								</div> 						
							</div>

							<div class="col-lg-6 col-md-6 col-sm-12">							
								<div class="input-box">								
									<h6>{{ __('Featured Plan') }}</span></h6>
									<select id="featured" name="featured" data-placeholder="{{ __('Select if Plan is Featured') }}:">		
										<option value=1 @if ($id->featured) selected @endif>{{ __('Yes') }}</option>
										<option value=0 @if (!$id->featured) selected @endif>{{ __('No') }}</option>
									</select>
								</div> 						
							</div>

							<div class="col-lg-6 col-md-6 col-sm-12">							
								<div class="input-box">								
									<h6>{{ __('Free Plan') }}</span></h6>
									<select id="free-plan" name="free-plan" data-placeholder="{{ __('Make this plan a Free Plan?') }}:">		
										<option value=1 @if ($id->free) selected @endif>{{ __('Yes') }}</option>
										<option value=0 @if (!$id->free) selected @endif>{{ __('No') }}</option>
									</select>
								</div> 						
							</div>
						</div>

						<div class="card special-shadow border-0">
							<div class="card-body">
								<h6 class="fs-12 font-weight-bold mb-5"><i class="fa fa-bank text-info fs-14 mr-1 fw-2"></i>{{ __('Payment Gateways Plan IDs') }}</h6>

								<div class="row">								
									<div class="col-lg-6 col-md-6 col-sm-12">							
										<div class="input-box">								
											<h6>{{ __('PayPal Plan ID') }} <span class="text-danger">({{ __('Required for Paypal') }}) <i class="ml-2 text-dark fs-13 fa-solid fa-circle-info" data-tippy-content="You have to get Paypal Plan ID in your Paypal account. Refer to the documentation if you need help with creating one."></i></span></h6>
											<div class="form-group">							    
												<input type="text" class="form-control" id="paypal_gateway_plan_id" name="paypal_gateway_plan_id" value="{{ $id->paypal_gateway_plan_id }}">
											</div> 
											@error('paypal_gateway_plan_id')
												<p class="text-danger">{{ $errors->first('paypal_gateway_plan_id') }}</p>
											@enderror
										</div> 						
									</div>

									<div class="col-lg-6 col-md-6 col-sm-12">							
										<div class="input-box">								
											<h6>{{ __('Stripe Product ID') }} <span class="text-danger">({{ __('Required for Stripe') }}) <i class="ml-2 text-dark fs-13 fa-solid fa-circle-info" data-tippy-content="You have to get Stripe Product ID in your Stripe account. Refer to the documentation if you need help with creating one."></i></span></h6>
											<div class="form-group">							    
												<input type="text" class="form-control" id="stripe_gateway_plan_id" name="stripe_gateway_plan_id" value="{{ $id->stripe_gateway_plan_id }}">
											</div> 
											@error('stripe_gateway_plan_id')
												<p class="text-danger">{{ $errors->first('stripe_gateway_plan_id') }}</p>
											@enderror
										</div> 						
									</div>

									<div class="col-lg-6 col-md-6 col-sm-12">							
										<div class="input-box">								
											<h6>{{ __('Paystack Plan Code') }} <span class="text-danger">({{ __('Required for Paystack') }}) <i class="ml-2 text-dark fs-13 fa-solid fa-circle-info" data-tippy-content="You have to get Paystack Plan ID in your Paystack account. Refer to the documentation if you need help with creating one."></i></span></h6>
											<div class="form-group">							    
												<input type="text" class="form-control" id="paystack_gateway_plan_id" name="paystack_gateway_plan_id" value="{{ $id->paystack_gateway_plan_id }}">
											</div> 
											@error('paystack_gateway_plan_id')
												<p class="text-danger">{{ $errors->first('paystack_gateway_plan_id') }}</p>
											@enderror
										</div> 						
									</div>

									<div class="col-lg-6 col-md-6 col-sm-12">							
										<div class="input-box">								
											<h6>{{ __('Razorpay Plan ID') }} <span class="text-danger">({{ __('Required for Razorpay') }}) <i class="ml-2 text-dark fs-13 fa-solid fa-circle-info" data-tippy-content="You have to get Razorpay Plan ID in your Razorpay account. Refer to the documentation if you need help with creating one."></i></span></h6>
											<div class="form-group">							    
												<input type="text" class="form-control" id="razorpay_gateway_plan_id" name="razorpay_gateway_plan_id" value="{{ $id->razorpay_gateway_plan_id }}">
											</div> 
											@error('razorpay_gateway_plan_id')
												<p class="text-danger">{{ $errors->first('razorpay_gateway_plan_id') }}</p>
											@enderror
										</div> 						
									</div>
								</div>
							</div>						
						</div>

						<div class="card mt-6 mb-7 special-shadow border-0">
							<div class="card-body">
								<h6 class="fs-12 font-weight-bold mb-5"><i class="fa-solid fa-box-circle-check text-info fs-14 mr-1 fw-2"></i>{{ __('Credits') }}</h6>

								<div class="row">
									<div class="col-lg-6 col-md-6 col-sm-12">							
										<div class="input-box">								
											<h6>{{ __('Included Characters') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
											<div class="form-group">							    
												<input type="number" class="form-control" id="characters" name="characters" value="{{ $id->characters }}" required>
											</div> 
											@error('characters')
												<p class="text-danger">{{ $errors->first('characters') }}</p>
											@enderror
										</div> 						
									</div>

									<div class="col-lg-6 col-md-6 col-sm-12">							
										<div class="input-box">								
											<h6>{{ __('Included Minutes') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
											<div class="form-group">							    
												<input type="number" class="form-control" id="minutes" name="minutes" value="{{ $id->minutes }}" required>
											</div> 
											@error('minutes')
												<p class="text-danger">{{ $errors->first('minutes') }}</p>
											@enderror
										</div> 						
									</div>

									<div class="col-sm-12">							
										<div class="input-box">								
											<h6>{{ __('Voiceover Voice Types Included') }}</h6>
											<div class="form-group">							    
												<select id="voice-type" name="voice-type" data-placeholder="{{ __('Select which voice types are included in the plan') }}:">			
													<option value='both' @if($id->voice_type == 'both') selected @endif>{{ ('Both (Standard and Neural Voices)') }}</option>
													<option value='neural' @if($id->voice_type == 'neural') selected @endif>{{ ('Neural Voices Only') }}</option>
													<option value='standard' @if($id->voice_type == 'standard') selected @endif>{{ ('Standard Voices Only') }}</option>
												</select>
											</div> 
											@error('voice-type')
												<p class="text-danger">{{ $errors->first('voice-type') }}</p>
											@enderror
										</div> 						
									</div>

									<div class="col-lg-3 col-md-3 col-sm-12">
										<div id="voice-types" role="radiogroup">
											<span>
												<div class="radio-control mt-4">
													<input type="checkbox" name="unlimited" class="input-control" id="tasks" value="tasks" @if($id->synthesize_tasks == -1) checked @endif>
													<label for="tasks" class="label-control">{{ __('Unlimited Synthesize Tasks') }}</label>
												</div>	
											</span>								
										</div>
									</div>

									<div class="col-lg-9 col-md-9 col-sm-12">							
										<div class="input-box">								
											<h6>{{ __('Voiceover Synthesize Tasks Limit') }}</h6>
											<div class="form-group">							    
												<input type="number" class="form-control" id="synthesize-task" name="synthesize-task" @if($id->synthesize_tasks != -1) value="{{ $id->synthesize_tasks }}" @endif>
											</div> 
											@error('synthesize-task')
												<p class="text-danger">{{ $errors->first('synthesize-task') }}</p>
											@enderror
										</div> 						
									</div>
								</div>
							</div>
						</div>
							
						<div class="row mt-6">
							<div class="col-12">
								<div class="input-box">	
									<h6>{{ __('Primary Heading') }}</h6>
									<div class="form-group">							    
										<input type="text" class="form-control" id="primary-heading" name="primary-heading" value="{{ $id->primary_heading }}">
									</div>
								</div>
							</div>
						</div>

						<div class="row mt-6">
							<div class="col-lg-12 col-md-12 col-sm-12">	
								<div class="input-box">	
									<h6>{{ __('Plan Features') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span> <span class="text-danger ml-3">({{ __('Comma Seperated') }})</span></h6>							
									<textarea class="form-control" name="features" rows="10" >{{ $id->plan_features }}</textarea>
									@error('features')
										<p class="text-danger">{{ $errors->first('features') }}</p>
									@enderror	
								</div>											
							</div>
						</div>
						

						<!-- ACTION BUTTON -->
						<div class="border-0 text-right mb-2 mt-1">
							<a href="{{ route('admin.finance.plans') }}" class="btn btn-cancel mr-2">{{ __('Cancel') }}</a>
							<button type="submit" class="btn btn-primary">{{ __('Save') }}</button>							
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
