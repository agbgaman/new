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

                    <div >
                        <span>
                            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                        </span>
                    </div>
                    <br>
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="logform">
                            <div class="input-box mb-6">
                                <label for="email" class="fs-12 font-weight-bold">{{ __('Email Address') }}</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="off"  placeholder="Email Address" required>
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                                @enderror
                            </div>

                            <div class="form-group mb-0 text-center">
                                <button type="submit" class="btn btn-primary mr-2">{{ __('Email Password Reset Link') }}</button>
                                <p class="fs-10 text-muted mt-2">or <a class="text-info" href="{{ route('login') }}">{{ __('Login') }}</a></p>
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
