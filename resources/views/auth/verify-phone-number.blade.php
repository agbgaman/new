@extends('layouts.auth')

@section('content')
    <div class="container-fluid justify-content-center">
        <div class="row h-100vh align-items-center background-white">
            <div class="col-md-7 col-sm-12 text-center background-special h-100 align-middle p-0" id="login-background">
                <div class="login-bg"></div>
            </div>

            <div class="col-md-5 col-sm-12 h-100">
                <div class="card-body pr-10 pl-10 pt-10">

                    <h3 class="text-center font-weight-bold mb-8">{{__('Verify Phone Number')}}</h3>

                    <form method="POST" action="{{ route('verify-phone-number-code') }}" id="verify-phone-number-form">
                        @csrf

                        @if (session('status') == 'verification-code-sent')
                            <div class="alert alert-login alert-success mb-8">
                                {{ __('A verification code has been sent to your phone number.') }}
                            </div>
                        @endif

                        <div class="float">
                            <label for="phone-number" style="display: inline-block;">{{ __('Phone Number') }}</label>
                            <h4 style="display: inline-block; margin-left: 10px;">{{ auth()->user()->phone_number }}</h4>
                        </div>



                        <div class="form-group">
                            <label for="verification-code">{{ __('Verification Code') }}</label>
                            <input type="text"
                                   class="form-control"
                                   id="verification-code"
                                   name="verification_code"
                                   required>
                        </div>
                        <br>
                        <div class="form-group mb-0 text-center">
                            <button type="submit" class="btn btn-primary mr-2">{{ __('Verify Phone Number') }}</button>
                        </div>

                    </form>

                    <div class="text-center">
                        <p class="fs-10 text-muted mt-2">or <a class="text-info" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('Logout') }}</a></p>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>

                </div>

                <footer class="footer" id="login-footer">
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-md-12 col-sm-12 fs-10 text-muted text-center">
                                Copyright © {{ date("Y") }} <a href="{{ config('app.url') }}">{{ config('app.name') }}</a>. {{ __('All rights reserved') }}
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
    </div>

    <script>
        // Get the phone number input field, verification code input field, and verify button
        var phoneNumberInput = document.getElementById('phone-number');
        var verificationCodeInput = document.getElementById('verification-code');
        var verifyButton = document.querySelector('button[type="submit"]');

        // Add a click event listener to the verify button
        verifyButton.addEventListener('click', function(event) {
            // Prevent the form from submitting automatically
            event.preventDefault();

            // Get the phone number and verification code values
            var phoneNumber = phoneNumberInput.value;
            var verificationCode = verificationCodeInput;
    </script>
