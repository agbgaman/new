@extends('layouts.auth')
@section('content')
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Signup</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800;900&display=swap"
          rel="stylesheet">
    <link type="text/css" href="{{asset('front/css/animate.css')}}" rel="stylesheet">
    <link type="text/css" href="{{asset('front/css/bootstrap.css')}}" rel="stylesheet">
    <script src="{{asset('front/js/jquery.min.js')}}"></script>
    <link type="text/css" href="{{asset('front/css/font-awesome.css')}}" rel="stylesheet">
    <link type="text/css" href="{{asset('front/css/style.css')}}" rel="stylesheet">
</head>
<body>


<section class="logmain">
    <div class="logbg">
        <div class="logimg">
            <img src="{{asset('front/images/regbg.jpg')}}"/>

            <div class="imgtext text1">
                <p>Need more information about the work within Dash GTS? <br>Take a look in our FAQ</p>
                <p>Need Help? Contact Support</p>
                <p>
                    Copyright © 2023 Dash GTS All Rights Reserved. <br>Privacy Statement
                </p>
            </div>
        </div>
        <div class="logtext">
            <div class="bg-white">
                <h3>Create your <span class="blueclolor">account</span></h3>
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="logform">
                        <div>
                            <label>Full Name</label>
                            <input id="name" type="text" class="inputtext @error('name') is-invalid @enderror"
                                   name="name" value="{{ old('name') }}" autocomplete="off" autofocus
                                   placeholder="First and Last Names">
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                            @enderror
                        </div>
                        <div>
                            <label>Email Address</label>
                            <input id="email" type="email" class="inputtext @error('email') is-invalid @enderror"
                                   name="email" value="{{ old('email') }}" autocomplete="off"
                                   placeholder="Email Address" required>
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                            @enderror
                        </div>
                        <div>
                            <label>Country</label>
                            <select id="user-country" name="country" class="inputtext"
                                    data-placeholder="Select Your Country" required>
                                @foreach(config('countries') as $value)
                                    <option value="{{ $value }}"
                                            @if(config('settings.default_country') == $value) selected @endif>{{ $value }}</option>
                                @endforeach
                            </select>
                            @error('country')
                            <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                            @enderror

                        </div>
                        <div>
                            <label>Password</label>
                            <input id="password" type="password"
                                   class="inputtext @error('password') is-invalid @enderror" name="password" required
                                   autocomplete="off" placeholder="Password">
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                            @enderror
                        </div>
                        <div>
                            <label for="password-confirm">{{ __('Confirm Password') }}</label>
                            <input id="password-confirm" type="password" class="form-control"
                                   name="password_confirmation" required autocomplete="off"
                                   placeholder="Confirm Password">
                        </div>
                        <div class="form-group mb-3">
                            <div class="d-flex">
                                <label class="custom-switch">
                                    <input type="checkbox" class="custom-switch-input" name="agreement" id="agreement"
                                           {{ old('remember') ? 'checked' : '' }} required>
                                    <span class="custom-switch-indicator"></span>
                                    <span class="custom-switch-description fs-10 text-muted">{{__('By continuing, I agree with your')}} <a
                                            href="{{ route('terms') }}"
                                            class="text-info">{{__('Terms and Conditions')}}</a> {{__('and')}} <a
                                            href="{{ route('privacyPolicies') }}"
                                            class="text-info">{{__('Privacy Policies')}}</a></span>
                                </label>
                            </div>
                        </div>
                        <div>
                            <button type="submit" class="btn bluebg">Create Account</button>
                        </div>

                        <div class="text">
                            Have an account? <a href="{{asset('login')}}">Sign In</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>


@endsection
