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
                    <div class="line"><span>LOGIN WITH SOCIAL MEDIA</span></div>

                    <div class="actions-total text-center">
                        @if(config('services.twitter.enable') == 'on')
                            <a href="{{ url('/auth/redirect/twitter') }}" data-tippy-content="Login with Twitter"
                               class="btn mr-2" id="login-twitter"><i class="fa-brands fa-twitter"></i></a>
                        @endif
                        @if(config('services.facebook.enable') == 'on')
                            <a href="{{ url('/auth/redirect/facebook') }}" data-tippy-content="Login with Facebook"
                               class="btn mr-2" id="login-facebook"><i class="fa-brands fa-facebook-f"></i></a>
                        @endif
                        @if(config('services.google.enable') == 'on')
                            <a href="{{ url('/auth/redirect/google') }}" data-tippy-content="Login with Google"
                               class="btn mr-2" id="login-google"><i class="fa-brands fa-google"></i></a>
                        @endif
                        @if(config('services.linkedin.enable') == 'on')
                            <a href="{{ url('/auth/redirect/linkedin') }}" data-tippy-content="Login with Linkedin"
                               class="btn mr-2" id="login-linkedin"><i class="fa-brands fa-linkedin-in"></i></a>
                        @endif
                    </div>

                    <div class="line"><span>LOGIN WITH EMAIL</span></div>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="logform">
                            <div>
                                <label>Email Address</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                       name="email" autocomplete="off" placeholder="Email Address" required>
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                                @enderror
                            </div>
                            <div>
                                <label>Password</label>
                                <input id="password" type="password"
                                       class="form-control @error('password') is-invalid @enderror" name="password"
                                       autocomplete="off" placeholder="Password" required>
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                                @enderror
                            </div>
                            <div class="forgot">
                                <label> <input type="checkbox" class="" name="remember"
                                               id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    Keep me logged in</label>
                                @if (Route::has('password.request'))
                                    <a class="text-info fs-12"
                                       href="{{ route('password.request') }}">{{ __('Forgot Your Password?') }}</a>
                                @endif
                            </div>
                            <div>
                                <button type="submit" class="btn bluebg">Login</button>
                                <div class="text">
                                    Don't have an account? <a href="{{asset('register')}}">Sign Up</a>
                                </div>
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
