<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- METADATA -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta content="" name="description">
    <meta content="" name="author">
    <meta name="keywords" content=""/>

    <!-- CSRF TOKEN -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- TITLE -->
    <title>{{ config('app.name', 'Polly') }}</title>

    @include('layouts.header')
    <script type='text/javascript'>
        window.smartlook || (function (d) {
            var o = smartlook = function () {
                o.api.push(arguments)
            }, h = d.getElementsByTagName('head')[0];
            var c = d.createElement('script');
            o.api = new Array();
            c.async = true;
            c.type = 'text/javascript';
            c.charset = 'utf-8';
            c.src = 'https://web-sdk.smartlook.com/recorder.js';
            h.appendChild(c);
        })(document);
        smartlook('init', '48ad407efd143a50d6ce1a3c7361cf8a98058a22', {region: 'eu'});

        // Include the identify function with custom data
        smartlook('identify', 123, {
            "name": "{{auth()->user()->name}}", // Wrapped Blade syntax in quotes
            "email": "{{auth()->user()->email}}", // Wrapped Blade syntax in quotes
        });
    </script>
    <!-- Include Driver.js CSS -->
    <link rel="stylesheet" href="https://unpkg.com/driver.js/dist/driver.min.css">
</head>
<style>
    #driver-highlighted-element-stage {
        display: none !important;
    }
</style>
<body class="app sidebar-mini">

<!-- LOADER -->
<div id="preloader">
    <img src="{{URL::asset('img/svgs/loader.svg')}}" alt="loader">
</div>
<!-- END LOADER -->

<!-- PAGE -->
<div class="page">
    <div class="page-main">

        @include('layouts.nav-aside')

        <!-- APP CONTENT -->
        <div class="app-content main-content">

            <div class="side-app">

                @include('layouts.nav-top')

                @include('layouts.flash')

                @yield('page-header')

                @yield('content')

            </div>
        </div>
        <!-- END APP CONTENT -->
        @include('layouts.footer')

    </div>
</div><!-- END PAGE -->

@include('layouts.footer-backend')


<!-- Include Driver.js script -->
<script src="https://unpkg.com/driver.js/dist/driver.min.js"></script>

<script>

    $(document).ready(function () {
        $('#preloader').hide();
        // Instantiate the Driver

        var driver = new Driver({
            // Options
            animate: true,
            opacity: 0.75,
            doneBtnText: 'Done',
            closeBtnText: 'Close',
            nextBtnText: 'Next',
            prevBtnText: 'Previous',
            onReset: () => {
            },
            onComplete: () => {
                // Note the use of currentPage here
                localStorage.setItem(`tour_completed_${currentPage}`, 'true');
                console.log('completed');
            }
        });


// Define the steps
        var steps = [
            {
                element: '#update-profile',
                popover: {
                    title: 'Step one',
                    description: 'Please click on "Update Profile" to complete your profile first',
                    position: 'right'
                },
                onPage: '/account/dashboard'
            },
            // {
            //     element: '#update-button',
            //     popover: {
            //         title: 'Second step',
            //         description: 'Please complete your profile',
            //         position: 'left'
            //     },
            //     onPage: '/account/dashboard/edit',
            // },

            {
                element: '#projects',
                popover: {
                    title: 'Second step',
                    description: 'Please go to "Projects" and apply for a project',
                    position: 'right'
                },
                onPage: '/account/dashboard'
            },
            {
                element: '#notifications',
                popover: {
                    title: 'Third step',
                    description: 'Notifications from the system will appear here',
                    position: 'right'
                },
                onPage: '/account/dashboard'
            },
            {
                element: '#support-requests',
                popover: {
                    title: 'Fourth step',
                    description: 'Please go to Support Requests for more information',
                    position: 'right'
                },
                onPage: '/account/dashboard'
            },
            {
                element: '#teams',
                popover: {
                    title: 'Fifth step',
                    description: 'You can see your all referral here and your commission',
                    position: 'right'
                },
                onPage: '/account/dashboard'
            },
            {
                element: '#accounts',
                popover: {
                    title: 'sixth step',
                    description: 'You can see your all earnings here',
                    position: 'right'
                },
                onPage: '/account/dashboard',
                onNext: () => {
                    if (driver.currentStep === driver.steps.length - 1) {
                        localStorage.setItem(`tour_completed_${currentPage}`, 'true');
                        console.log('Tour completed, localStorage set');
                    }
                }
            }
            // dashboarddd more steps here
        ];

        // get current page
        let currentPage = window.location.pathname;

        console.log(currentPage, 'currentPage');
        // find steps for the current page
        let pageSteps = steps.filter(step => step.onPage === currentPage);

        console.log(pageSteps, 'pageSteps')
        console.log(pageSteps.length, 'pageSteps.length')
        console.log(localStorage.getItem('tour_completed'), 'localStorage.getItem')
        console.log(localStorage.getItem(`tour_completed_${currentPage}`), 'localStorage.getItem')
        if (pageSteps.length > 0) {
            // if there are steps for this page, define and start them
            driver.defineSteps(pageSteps);
            if (!localStorage.getItem(`tour_completed_${currentPage}`)) {
                driver.start();
            }
        }

    });

</script>
</body>
</html>


