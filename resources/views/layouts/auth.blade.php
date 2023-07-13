<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<!-- Meta data -->
		<meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="">
	    <meta name="keywords" content="">
	    <meta name="description" content="">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Title -->
        <title>{{ config('app.name') }}</title>

		<!-- Style css -->
		<link href="{{URL::asset('plugins/tippy/scale-extreme.css')}}" rel="stylesheet" />
		<link href="{{URL::asset('plugins/tippy/material.css')}}" rel="stylesheet" />

		@include('layouts.header')
        <script type='text/javascript'>
            window.smartlook||(function(d) {
                var o=smartlook=function(){ o.api.push(arguments)},h=d.getElementsByTagName('head')[0];
                var c=d.createElement('script');o.api=new Array();c.async=true;c.type='text/javascript';
                c.charset='utf-8';c.src='https://web-sdk.smartlook.com/recorder.js';h.appendChild(c);
            })(document);
            smartlook('init', '48ad407efd143a50d6ce1a3c7361cf8a98058a22', { region: 'eu' });
        </script>
	</head>

	<body class="app sidebar-mini">

		<!-- Page -->
		<div class="page">
			<div class="page-main">

				<!-- App-Content -->
				<div class="main-content">
					<div class="side-app">

						@yield('content')

					</div>
				</div>

		</div><!-- End Page -->

		@include('layouts.footer-frontend')

	</body>
</html>


