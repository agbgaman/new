@extends('layouts.auth')

@section('content')
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Login</title>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800;900&display=swap"
              rel="stylesheet">
        <link type="text/css" href="{{asset('front/css/animate.css')}}" rel="stylesheet">
        {{--    <link type="text/css" href="{{asset('front/css/bootstrap.css')}}" rel="stylesheet">--}}
        <script src="{{asset('front/js/jquery.min.js')}}"></script>
        <link type="text/css" href="{{asset('front/css/font-awesome.css')}}" rel="stylesheet">
        <link type="text/css" href="{{asset('front/css/style.css')}}" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"/>

    </head>
    <body>


    <section class="logmain">
        <div class="logbg">
            <div class="logimg">
                <img src="https://dash.gts.ai/front/images/loginimg.jpg"/>
                <div class="imgtext">
                    <p><span>JOIN GLOBAL COMMUNITY</span></p>
                    <h2>Make money. From anywhere, anytime!</h2>
                    <p>Enhance your happiness by attaining greater financial independence. Join our thriving community
                        and
                        effortlessly earn money securely from your desired location.</p>
                </div>
            </div>
            <!-- Put the message here -->

            <div class="logtext">
                <div class="bg-white">

                    <h3>Welcome Back to <span>GTS Dash</span></h3>

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        <div class="divider">
                            <div class="divider-text text-muted">
                                <small>{{__('Provide a New Password')}}</small>
                            </div>
                        </div>
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">

                        <div class="logform">
                            <div class="input-box">
                                <label for="email" class="fs-12 font-weight-bold">{{ __('Email Address') }}</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="off"  placeholder="Email Address" required>
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                                @enderror
                            </div>

                            <div class="input-box">
                                <label for="password" class="fs-12 font-weight-bold">{{ __('Password') }}</label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="off" placeholder="Password">
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                                @enderror
                            </div>

                            <div class="input-box">
                                <label for="password-confirm" class="fs-12 font-weight-bold">{{ __('Confirm Password') }}</label>
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="off" placeholder="Confirm Password">
                            </div>
                            <div class="forgot_password">
                                <button type="submit" class="btn btn-primary mr-2">{{ __('Reset Password') }}</button>

                            </div>


                            <div class="text">
                                By continuing, you agree to our <a href="{{ route('terms') }}">Terms and Conditions</a>
                                and
                                <a href="{{route('privacyPolicies')}}">Privacy Policy</a> Copyright © 2023 <a
                                    href="{{route('home')}}">GTS Dash</a>. All rights
                                reserved
                            </div>
                        </div>
                    </form>
                </div>


            </div>
        </div>
    </section>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        @if(Session::has('success'))
        toastr.success("{{ Session::get('success') }}");
        @endif

        @if ($errors->any())
        @foreach ($errors->all() as $error)
        toastr.error("{{ $error }}");
        @endforeach
        @endif
    </script>
@endsection
