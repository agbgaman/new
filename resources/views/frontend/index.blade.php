<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800;900&display=swap"
          rel="stylesheet">
    {{--    <link type="text/css" href="{{asset('front/css/animate.css')}}" rel="stylesheet">--}}
    {{--    <link rel="stylesheet" href="{{asset('front/css/aos.css')}}">--}}
    <link type="text/css" href="{{asset('front/css/bootstrap.css')}}" rel="stylesheet">
    <link type="text/css" href="{{asset('front/css/owl.carousel.min.css')}}" rel="stylesheet">
    <link type="text/css" href="{{asset('front/css/owl.theme.default.min.css')}}" rel="stylesheet">
    <script src="{{asset('front/js/jquery.min.js')}}"></script>
    <script src="{{asset('front/js/owl.carousel.min.js')}}"></script>
    <link type="text/css" href="{{asset('front/css/font-awesome.css')}}" rel="stylesheet">
    <link type="text/css" href="{{asset('front/css/style.css')}}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>

</head>
<body>
<style>
    .dropdown-item {
        transition: all .2s;
    }

    .dropdown-item:hover {
        background-color: #205E7B;
        color: white;
    }

    .dropdown-toggle::after {
        transition: transform .2s;
    }

    .show > .dropdown-toggle::after {
        transform: rotate(180deg);
    }

    .navbar {
        box-shadow: 0 2px 4px rgba(0, 0, 0, .1);
    }

    .nav-item {
        transition: all .2s;
    }

    .nav-item:hover {
        background-color: #f8f9fa;
    }
    .collapse:not(.show) {
        display: contents;
    }
</style>
<div class="heighthead"></div>
<header id="header" class="headerbg homeheader">
    <div class="topbar">
        <div class="centertext">Now earning money is on your fingertips! Explore with GTS</div>
        <div class="tophead">
            <input type="text" name="" value="" placeholder="Search" class="search"/>

        </div>
    </div>
    <div class="topheader">
        <div class="logo" data-aos="fade-down" data-aos-duration="1500">
            <a href="{{route('home')}}"> <img src="https://dash.gts.ai/front/images/logo.png" alt="GTS"/> </a>
        </div>
        <div class="navicon"><span></span></div>
        <nav class="navbar navbar-expand-lg navbar-light bg-light " style="background: #EFF5FF !important;">

            <div class="topmenu">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Service
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{route('engineRelevance')}}">Image Annotation & Video
                                    Annotation</a>
                                <a class="dropdown-item" href="{{route('transcription')}}">Audio Data Transcription</a>
                                <a class="dropdown-item" href="{{route('surveys')}}">Video Data Transcription</a>
                                <a class="dropdown-item" href="{{route('translations')}}">ADAS Annotation</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown2" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Collection
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown2">
                                <a class="dropdown-item" href="{{route('engineRelevance')}}">Image Datasets For Machine
                                    Learning</a>
                                <a class="dropdown-item" href="{{route('surveys')}}">Video Dataset Collection</a>
                                <a class="dropdown-item" href="{{route('translations')}}">Speech Data Collection</a>
                                <a class="dropdown-item" href="{{route('transcription')}}">Text Data Collection</a>
                                <a class="dropdown-item" href="{{route('translations')}}">ADAS DATA COLLECTION</a>
                            </div>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="{{route('legal')}}">Legal Policy</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{route('faq')}}">FAQ</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{route('contact')}}">Contact</a></li>
                        <li class="nav-item"><a class="logbtn nav-link " href="{{route('login')}}">Login</a></li>
                        <li class="nav-item"><a class="regbtn nav-link" href="{{route('register')}}">Register</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</header>


<div class="homeslider owl-carousel owl-theme">
    <div class="item">
        <img src="https://dash.gts.ai/front/images/banner.jpg" alt="banner"/>
        <div class="slide">
            <div class="col-md-6">
                <div class="btitle">Join the World with us</div>
                <div class="bcontent">
                    <p>Work from anywhere in the world.</p>
                    <a href="{{route('register')}}" class="morebtn border-white text-white">Signup</a> <a
                        href="{{route('login')}}" class="morebtn bg-white ">Login</a>
                </div>
            </div>
        </div>
    </div>

</div>

<section class="wearebg">
    <div class="container">
        <div class="row">
            <div class="col-md-6 leftsec fonts">
                <p data-aos="fade-right" data-aos-duration="800" data-aos-offset="0" data-aos-easing="ease-in-back">
                    <small>JOIN GLOBAL COMMUNITY</small></p>
                <h2 data-aos="fade-left" data-aos-duration="1300" data-aos-offset="0" data-aos-easing="ease-in-back">We
                    are GTS</h2>
                <p data-aos="fade-up" data-aos-duration="1500" data-aos-offset="0">GTS Dash is an online hub for project
                    collaborators seeking to participate in various projects in the text, audio, video, and image
                    categories. Collaborators can sign up and select from a variety of projects, each offering different
                    payments as benefits</p>
                <a href="#" class="morebtn" data-aos-duration="2000" data-aos-offset="0" data-aos-easing="ease-in-back">Read
                    more</a>
            </div>
        </div>
    </div>
</section>

<section class="communitybg">
    <div class="container">
        <div class="row">
            <div class="col-md-6 leftsec" data-aos="fade-up" data-aos-duration="1000" data-aos-offset="0"
                 data-aos-easing="ease-in-back">
                <p><small>JOIN GLOBAL COMMUNITY</small></p>
                <h2>Make money. From anywhere, anytime!</h2>
                <p>Enhance your happiness by attaining greater financial independence. Join our thriving community and
                    effortlessly earn money securely from your desired location.</p>
                <a href="{{route('register')}}" class="morebtn border-white text-white" data-aos="fade-up"
                   data-aos-duration="1500" data-aos-offset="0" data-aos-easing="ease-in-back">Signup</a> <a
                    href="{{route('login')}}" class="morebtn bg-white " data-aos="fade-up" data-aos-duration="1500"
                    data-aos-offset="0" data-aos-easing="ease-in-back">Login</a>
            </div>
        </div>
    </div>
</section>


<section class="wearebg flexible">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="leftimg1" data-aos="fade-left" data-aos-duration="1000" data-aos-offset="0"
                     data-aos-easing="ease-in-back">
                    <img src="https://dash.gts.ai/front/images/flexible.png" alt="GTS"/>
                </div>
            </div>
            <div class="col-md-6 leftsec" data-aos="fade-up" data-aos-duration="1000" data-aos-offset="0"
                 data-aos-easing="ease-in-back">
                <p><small>WORK FROM ANYWHERE</small></p>
                <h2>Flexible Work <span>Opportunities</span></h2>
                <p>Join our global work-from-home opportunities: varied projects, from short surveys to long-term
                    endeavors. Utilize your social media interest, mobile device proficiency, linguistics degree, online
                    research skills, or passion for multimedia. Find the perfect fit among our diverse options.</p>

            </div>
        </div>
    </div>
</section>

<section class="communitybg weekly">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 leftsec" data-aos="fade-left" data-aos-duration="1000" data-aos-offset="0"
                 data-aos-easing="ease-in-back">
                <p><small>GET PAID WEEKLY</small></p>
                <h2>Generate income through micro tasking.</h2>
                <p>After successfully completing your work and gaining client approval, you can conveniently receive
                    regular and secure payments.</p>
                <ul>
                    <li>Secure payment providers like Paypal & Payoneer</li>
                    <li>Weekly payments</li>
                    <li>Decide for yourself when you get paid.</li>
                </ul>
            </div>

            <div class="col-md-6" data-aos="fade-up" data-aos-duration="1000" data-aos-offset="0"
                 data-aos-easing="ease-in-back">
                <img src="https://dash.gts.ai/front/images/weekly.jpg" alt="GTS"/>
            </div>
        </div>
    </div>
</section>

<section class="wearebg flexible">
    <div class="container">
        <div class="row align-items-center">

            <div class="col-md-6 leftsec" data-aos="fade-up" data-aos-duration="1000" data-aos-offset="0"
                 data-aos-easing="ease-in-back">
                <p><small>JOIN GLOBAL COMMUNITY</small></p>
                <h2>Meet entrepreneurial <span>taskers like you.</span></h2>
                <p>Join a community of 240,000+ taskers.</p>

            </div>
            <div class="col-md-6">
                <div class="counting">
                    <ul>
                        <li data-aos="fade-left" data-aos-duration="1200" data-aos-offset="0"
                            data-aos-easing="ease-in-back">
                            <span>240,000+ <small>Total Taskers</small></span>
                        </li>
                        <li data-aos="fade-left" data-aos-duration="1400" data-aos-offset="0"
                            data-aos-easing="ease-in-back">
                            <span>$15M <small>Total Earnings</small></span>
                        </li>
                        <li data-aos="fade-left" data-aos-duration="1600" data-aos-offset="0"
                            data-aos-easing="ease-in-back">
                            <span>90+ <small>Countries</small></span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="communitybg weekly">
    <div class="container">
        <div class="greadbg">
            <div class="row align-items-center">

                <div class="col-md-6 ">
                    <div class="gimg">
                        <img src="https://dash.gts.ai/front/images/great.jpg" alt="GTS"/>
                    </div>
                </div>
                <div class="col-md-6 findtext p-5" data-aos="fade-up" data-aos-duration="1000" data-aos-offset="0"
                     data-aos-easing="ease-in-back">
                    <h3>Find great work</h3>
                    <p>Discover work-from-home options: surveys, projects, social media, mobile skills, linguistics
                        degree, online research, multimedia passion. Find your fit.</p>
                    <ul>
                        <li>Work from anywhere.</li>
                        <li>Use your skills.</li>
                        <li>Get paid, earn money.</li>
                    </ul>
                    <a href="#" class="morebtn bg-white" data-aos="fade-up" data-aos-duration="1500" data-aos-offset="0"
                       data-aos-easing="ease-in-back">Explore</a> <a href="#" class="morebtn border-0 text-white"
                                                                     data-aos="fade-up" data-aos-duration="1700"
                                                                     data-aos-offset="0" data-aos-easing="ease-in-back">How
                        it works?</a>
                </div>
            </div>

        </div>
    </div>
</section>


<section class="wearebg flexible">
    <div class="container">
        <div class="row">
            <div class="col-md-12 leftsec text-center" data-aos="fade-up" data-aos-duration="1000" data-aos-offset="0"
                 data-aos-easing="ease-in-back">
                <p><small>JOIN GLOBAL COMMUNITY</small></p>
                <h2>Do tasks, get paid. <span>It’s that simple</span></h2>
                <p>Join a community of 240,000+ taskers.</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 text-center">
                <div class="wbox bg-white" data-aos="fade-up" data-aos-duration="1000" data-aos-offset="0"
                     data-aos-easing="ease-in-back">
                    <div class="img">
                        <img src="https://dash.gts.ai/front/images/boximg1.jpg" alt="GTS"/>
                    </div>
                    <div class="p-3">
                        <h5>Learn Easily</h5>
                        <p>Learn how to do tasks with our quick online courses or free hands-on training</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-center" data-aos="fade-up" data-aos-duration="1400" data-aos-offset="0"
                 data-aos-easing="ease-in-back">
                <div class="wbox bg-white">
                    <div class="img">
                        <img src="https://dash.gts.ai/front/images/boximg2.jpg" alt="GTS"/>
                    </div>
                    <div class="p-3">
                        <h5>Complete Tasks</h5>
                        <p>Work on tasks from projects you’ve unlocked</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-center" data-aos="fade-up" data-aos-duration="1600" data-aos-offset="0"
                 data-aos-easing="ease-in-back">
                <div class="wbox bg-white">
                    <div class="img">
                        <img src="https://dash.gts.ai/front/images/boximg3.jpg" alt="GTS"/>
                    </div>
                    <div class="p-3">
                        <h5>Get Paid Weekly</h5>
                        <p>Get paid fast via PayPal or AirTM based on your quality & number of tasks completed</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>


<section class="communitybg sefebg">
    <div class="container">

        <div class="row align-items-center">
            <div class="col-md-6" data-aos="fade-up" data-aos-duration="1000" data-aos-offset="0"
                 data-aos-easing="ease-in-back">
                <img src="https://dash.gts.ai/front/images/safe.jpg" alt="GTS"/>
            </div>
            <div class="col-md-6 safetext text-white">
                <div class="row">
                    <div class="col-md-12 starbox" data-aos="fade-up" data-aos-duration="1200" data-aos-offset="0"
                         data-aos-easing="ease-in-back">
                        <p>SAFETY FIRST <img src="https://dash.gts.ai/front/images/star.png" alt="GTS" height="24"/></p>
                        <h2>Safe and Secure</h2>
                        <p>Join our global work-from-home opportunities: varied projects, from short surveys to
                            long-term endeavors. Utilize your social media interest, mobile device proficiency,
                            linguistics degree, online research skills, or passion for multimedia. Find the perfect fit
                            among our diverse options.</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6" data-aos="fade-up" data-aos-duration="1400" data-aos-offset="0"
                         data-aos-easing="ease-in-back">
                        <img src="https://dash.gts.ai/front/images/icon1.png" alt="GTS"/>
                        <h5>Pay with confidence</h5>
                        <p>Experience safe and secure payments across 39+ currencies with the Milestone Payments system.
                            Ensure satisfaction with the work before releasing payments.</p>
                    </div>
                    <div class="col-md-6" data-aos="fade-up" data-aos-duration="1600" data-aos-offset="0"
                         data-aos-easing="ease-in-back">
                        <img src="https://dash.gts.ai/front/images/icon2.png" alt="GTS"/>
                        <h5>24/7 Support</h5>
                        <p>We're committed to providing assistance whenever you need it. Our dedicated representatives
                            are available round the clock, 24/7, to address any concerns or queries you may have.</p>
                    </div>
                </div>

            </div>
        </div>


    </div>
</section>


<section class="wearebg appsec">
    <div class="container">
        <div class="row">
            <div class="col-md-6 leftsec" data-aos="fade-up" data-aos-duration="1000" data-aos-offset="0"
                 data-aos-easing="ease-in-back">
                <p><small>Get Started With Your Dream Earnings</small></p>
                <h2>Download The Mobile App <span>Now</span></h2>
                <p><img src="https://dash.gts.ai/front/images/app.png" alt="GTS" width="130"/> <img
                        src="https://dash.gts.ai/front/images/gpay.png" width="130" alt="GTS"/> COMING SOON!</p>
            </div>
            <div class="col-md-6 text-center">
                <div class="mobileimg">
                    <img src="https://dash.gts.ai/front/images/mobile.png" alt="GTS"/>
                </div>
            </div>
        </div>

    </div>
</section>


<section class="communitybg different">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 leftsec" data-aos="fade-up" data-aos-duration="1000" data-aos-offset="0"
                 data-aos-easing="ease-in-back">
                <p><small>JOIN GLOBAL COMMUNITY</small></p>
                <h2>What makes us different?</h2>
                <p>Dashoffers a unique combination of diverse, high-quality tasks, a supportive community, and reliable
                    payments.</p>
                <a href="#" class="morebtn border-white text-white">Join Now</a>
            </div>
            <div class="col-md-6 safetext text-white">
                <div class="row">
                    <div class="col-md-6" data-aos="fade-up" data-aos-duration="1000" data-aos-offset="0"
                         data-aos-easing="ease-in-back">
                        <img src="https://dash.gts.ai/front/images/icon3.png" alt="GTS"/>
                        <h5>+1 Million Microtasks</h5>
                        <p>Unlock endless job opportunities with microtasking, and earn money anytime, anywhere in the
                            world.</p>
                    </div>
                    <div class="col-md-6" data-aos="fade-up" data-aos-duration="1200" data-aos-offset="0"
                         data-aos-easing="ease-in-back">
                        <img src="https://dash.gts.ai/front/images/icon4.png" alt="GTS"/>
                        <h5>+50K HitApps</h5>
                        <p>We are one of the UHRS providers with the highest HitApp availability. Benefit from our
                            partner platform.</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6" data-aos="fade-up" data-aos-duration="1400" data-aos-offset="0"
                         data-aos-easing="ease-in-back">
                        <img src="https://dash.gts.ai/front/images/icon5.png" alt="GTS"/>
                        <h5>+230K Solved Support Tickets</h5>
                        <p>Need help? Our dedicated helpdesk is 24/7 available to quickly resolve any issues or requests
                            you may have, whether you prefer to connect with our community or a support agent. We’re
                            committed to taking care of.</p>
                    </div>
                    <div class="col-md-6" data-aos="fade-up" data-aos-duration="1600" data-aos-offset="0"
                         data-aos-easing="ease-in-back">
                        <img src="https://dash.gts.ai/front/images/icon6.png" alt="GTS"/>
                        <h5>ISO Certified Since</h5>
                        <p>We have been providing a high-quality and safe working environment for over 15 years and
                            comply with international safety standards.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<section class="wearebg flexible">
    <div class="container">
        <div class="row">
            <div class="col-md-12 leftsec text-center" data-aos="fade-up" data-aos-duration="1000" data-aos-offset="0"
                 data-aos-easing="ease-in-back">
                <p><small>THE PLACE WHERE IT ALL HAPPENS</small></p>
                <h2>Workplace</h2>
                <p>No matter where you are or how much time you have, you’re guaranteed to find the right job for
                    you.</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 text-center">
                <div class="wbox bg-white">
                    <div class="img" data-aos="fade-up" data-aos-duration="1000" data-aos-offset="0"
                         data-aos-easing="ease-in-back">
                        <img src="https://dash.gts.ai/front/images/iconbg1.jpg" alt="GTS"/>
                    </div>
                    <div class="p-3" data-aos="fade-up" data-aos-duration="1600" data-aos-offset="0"
                         data-aos-easing="ease-in-back">
                        <h5>Find the right Job</h5>
                        <p>No matter where you are or how much time you have, you’re guaranteed to find the right job
                            for you.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-center">
                <div class="wbox bg-white">
                    <div class="img" data-aos="fade-up" data-aos-duration="1000" data-aos-offset="0"
                         data-aos-easing="ease-in-back">
                        <img src="https://dash.gts.ai/front/images/iconbg2.jpg" alt="GTS"/>
                    </div>
                    <div class="p-3" data-aos="fade-up" data-aos-duration="1600" data-aos-offset="0"
                         data-aos-easing="ease-in-back">
                        <h5>Never miss a job</h5>
                        <p>Activate push notifications in the GTS app and we’ll immediately let you know when a new job
                            is available. That way, you’ll never miss an opportunity!</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-center">
                <div class="wbox bg-white">
                    <div class="img" data-aos="fade-up" data-aos-duration="1000" data-aos-offset="0"
                         data-aos-easing="ease-in-back">
                        <img src="https://dash.gts.ai/front/images/iconbg3.jpg" alt="GTS"/>
                    </div>
                    <div class="p-3" data-aos="fade-up" data-aos-duration="1600" data-aos-offset="0"
                         data-aos-easing="ease-in-back">
                        <h5>Your account balance always in sight</h5>
                        <p>You can easily track what you’ve earned and how much of it is available for payment.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>


<section class="communitybg different">
    <div class="container">
        <div class="row ">
            <div class="col-md-12 leftsec text-center" data-aos="fade-up" data-aos-duration="1000" data-aos-offset="0"
                 data-aos-easing="ease-in-back">
                <p><small>THE PLACE WHERE IT ALL HAPPENS</small></p>
                <h2>Workplace</h2>
                <p>No matter where you are or how much time you have, you’re guaranteed to find the right job for
                    you.</p>
                <a href="#" class="morebtn bg-white " data-aos="fade-up" data-aos-duration="1400" data-aos-offset="0"
                   data-aos-easing="ease-in-back">Work on your smartphone</a>
                <a href="#" class="morebtn border-white text-white" data-aos="fade-up" data-aos-duration="1600"
                   data-aos-offset="0" data-aos-easing="ease-in-back">Work on your desktop or notebook</a>
            </div>
        </div>
        <div class="slider1 owl-carousel owl-theme">
            <div class="item">
                <img src="https://dash.gts.ai/front/images/mobile1.png" alt="banner"/>
            </div>
            <div class="item">
                <img src="https://dash.gts.ai/front/images/mobile2.png" alt="banner"/>
            </div>
            <div class="item">
                <img src="https://dash.gts.ai/front/images/mobile3.png" alt="banner"/>
            </div>
            <div class="item">
                <img src="https://dash.gts.ai/front/images/mobile1.png" alt="banner"/>
            </div>
            <div class="item">
                <img src="https://dash.gts.ai/front/images/mobile4.png" alt="banner"/>
            </div>
        </div>
    </div>
</section>


<section class="wearebg flexible">
    <div class="container">
        <div class="row">
            <div class="col-md-12 leftsec text-center" data-aos="fade-up" data-aos-duration="1000" data-aos-offset="0"
                 data-aos-easing="ease-in-back">
                <p><small>THE PLACE WHERE IT ALL HAPPENS</small></p>
                <h2>Discover a platform that <br>always has the right job for you</h2>
                <p>No matter where you are or how much time you have, you’re guaranteed to find the right job for
                    you.</p>
            </div>
        </div>
        <div class="itemslide owl-carousel owl-theme">
            <div class="item">
                <div class="wbox bg-white">
                    <div class="img">
                        <img src="https://dash.gts.ai/front/images/iconbg1.jpg" alt="GTS"/>
                    </div>
                    <div class="p-3">
                        <h5>Find the right Job</h5>
                        <p>No matter where you are or how much time you have, you’re guaranteed to find the right job
                            for you.</p>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="wbox bg-white">
                    <div class="img">
                        <img src="https://dash.gts.ai/front/images/iconbg2.jpg" alt="GTS"/>
                    </div>
                    <div class="p-3">
                        <h5>Never miss a job</h5>
                        <p>Activate push notifications in the GTS app and we’ll immediately let you know when a new job
                            is available. That way, you’ll never miss an opportunity!</p>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="wbox bg-white">
                    <div class="img">
                        <img src="https://dash.gts.ai/front/images/iconbg3.jpg" alt="GTS"/>
                    </div>
                    <div class="p-3">
                        <h5>Your account balance always in sight</h5>
                        <p>You can easily track what you’ve earned and how much of it is available for payment.</p>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="wbox bg-white">
                    <div class="img">
                        <img src="https://dash.gts.ai/front/images/iconbg2.jpg" alt="GTS"/>
                    </div>
                    <div class="p-3">
                        <h5>Never miss a job</h5>
                        <p>Activate push notifications in the GTS app and we’ll immediately let you know when a new job
                            is available. That way, you’ll never miss an opportunity!</p>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="wbox bg-white">
                    <div class="img">
                        <img src="https://dash.gts.ai/front/images/iconbg3.jpg" alt="GTS"/>
                    </div>
                    <div class="p-3">
                        <h5>Your account balance always in sight</h5>
                        <p>You can easily track what you’ve earned and how much of it is available for payment.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<section class="communitybg different">
    <div class="container">
        <div class="row ">
            <div class="col-md-12 leftsec text-center" data-aos="fade-up" data-aos-duration="1000" data-aos-offset="0"
                 data-aos-easing="ease-in-back">
                <p><small>JOIN GLOBAL COMMUNITY</small></p>
                <h2>GTS Dash</h2>
                <p>Experience the benefits of <span>Dashfirsthand</span> by signing up today and trying it out for
                    yourself!</p>
                <a href="{{route('register')}}" class="morebtn border-white text-white" data-aos="fade-up"
                   data-aos-duration="1400" data-aos-offset="0" data-aos-easing="ease-in-back">Signup</a> <a
                    href="{{route('login')}}" class="morebtn bg-white " data-aos="fade-up" data-aos-duration="1600"
                    data-aos-offset="0" data-aos-easing="ease-in-back">Login</a>
            </div>
        </div>

    </div>
</section>


<footer class="footerbg">
    <div class="container">
        <div class="fmenu">
            <div class="fbox">
                <h5>Solutions</h5>
                <ul>
                    <li><a href="{{route('engineRelevance')}}">Image Annotation & Video Annotation</a></li>
                    <li><a href="{{route('transcription')}}">Audio Data Transcription</a></li>
                    <li><a href="{{route('surveys')}}">Video Data Transcription</a></li>
                    <li><a href="{{route('translations')}}">ADAS Annotation</a></li>
                </ul>
            </div>
            <div class="fbox">
                <h5>Collection</h5>
                <ul>
                    <li><a href="{{route('engineRelevance')}}">Image Datasets For Machine Learning</a></li>
                    <li><a href="{{route('surveys')}}">Video Dataset Collection</a></li>
                    <li><a href="{{route('translations')}}">Speech Data Collection</a></li>
                    <li><a href="{{route('transcription')}}">Text Data Collection</a></li>
                    <li><a href="{{route('translations')}}">ADAS DATA COLLECTION</a></li>
                </ul>
            </div>
            <div class="fbox" data-aos="fade-up">
                <h5>Robotic Process Automation</h5>
                <ul>
                    <li><a href="#">Process Identification</a></li>
                    <li><a href="#">Implementation</a></li>
                    <li><a href="#">Tool Selection</a></li>
                    <li><a href="#">RPA (COE)</a></li>
                </ul>
            </div>
            <div class="fbox">
                <h5>Our Company</h5>
                <ul>
                    <li><a href="{{route('contact')}}">Contact</a></li>
                    <li><a href="{{route('service')}}">Services</a></li>
                    <li><a href="{{route('privacyPolicies')}}">Privacy Policy</a></li>
                    <li><a href="{{route('cookiePolicy')}}">Cookie Policy</a></li>
                    <li><a href="{{route('privacyStatement')}}">Privacy Statement</a></li>
                </ul>
            </div>
            <div class="fbox">
                <h5>Modern Slavery Policy</h5>
                <ul>
                    <li><a href="{{route('antiCorruptionPolicy')}}">Environment, Social, and Governance</a></li>
                    <li><a href="{{route('privacyStatement')}}">Manage My Data</a></li>
                    <li><a href="{{route('cookiePolicy')}}">Cookies Settings</a></li>
                </ul>
            </div>
        </div>

        <div class="fbottom">
            <div class="fleft d-flex">
                <h6>GLOBOSE TECHNOLOGY SOLUTIONS PRIVATE LIMITED</h6>
                <ul class="d-flex">
                    <li><a href="{{route('privacyPolicies')}}">Privacy Policy</a></li>
                    <li><a href="{{route('terms')}}">Terms & Conditions</a></li>
                    <li><a href="{{route('legal')}}">Legal Policy</a></li>
                </ul>
            </div>
            <div class="fright">
                <ul class="d-flex">
                    <li><a href="https://www.facebook.com/GloboseTechnologySolutions/"><i
                                class="fa fa-facebook"></i></a></li>
                    <li><a href="https://twitter.com/GTS_AI/"><i class="fa fa-twitter"></i></a></li>
                    <li><a href="https://www.instagram.com/gts_ai_data/"><i class="fa fa-instagram"></i></a></li>
                    <li><a href="https://www.linkedin.com/company/gtsaidata/"><i class="fa fa-linkedin"></i></a></li>
                    <li><a href="https://gts.ai/"><i class="fa fa-google"></i></a></li>
                    <li><a href="https://www.youtube.com/@gtsaidata7850"><i class="fa fa-youtube"></i></a></li>
                </ul>
            </div>

        </div>
        <div data-aos="fade-up" data-aos-duration="2000" data-aos-offset="0" data-aos-easing="ease-in-back">
            <p>All rights are reserved © 2023</p></div>

    </div>
</footer>
<style>
    @media (min-width: 768px) {
        /* For desktop screens */
        .modal-dialog-centered {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: calc(100% - (1.75rem * 2));
        }
    }

    @media (max-width: 767px) {
        /* For mobile screens */
        .modal-dialog-centered {
            display: flex;
            justify-content: center;
            margin: 0.5rem auto !important;
            max-width: 500px !important;
        }
    }


</style>
<div  class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" id="cookieModal"
     data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog  modal-lg">" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center">
                <img src="https://dash.gts.ai/front/images/logo.png" alt="GTS"/>
                <h5 class="modal-title">Cookie Policy</h5>
            </div>
            <div class="modal-body">
                <h6>
                    Privacy Preference Centre
                </h6>
                <p>When you visit any website, it may store or retrieve information on your browser, mostly in the form
                    of cookies. This information might be about you, your preferences or your device and is mostly used
                    to make the site work as you expect it to. The information does not usually directly identify you,
                    but it can give you a more personalised web experience. Because we respect your right to privacy,
                    you can choose not to allow some types of cookies. Click on the different category headings to find
                    out more and change our default settings. However, blocking some types of cookies may impact your
                    experience of the site and the services we are able to offer.</p>
                <h6>
                    <a href="{{route('cookiePolicy')}}">Cookie Policy</a>
                </h6>
{{--                <div class="container">--}}
{{--                    <h2>Manage Consent Preferences</h2>--}}
{{--                    <div class="accordion" id="cookieAccordion">--}}

{{--                        <div class="faqbg">--}}
{{--                            <div class="titles">Performance Cookie</div>--}}
{{--                            <div class="faqtext">--}}
{{--                                <p>These cookies are necessary for the website to function and cannot be switched off in--}}
{{--                                    our systems. They are usually only set in response to actions made by you which--}}
{{--                                    amount to a request for services, such as setting your privacy preferences, logging--}}
{{--                                    in or filling in forms. You can set your browser to block or alert you about these--}}
{{--                                    cookies, but some parts of the site will not then work. These cookies do not store--}}
{{--                                    any personally identifiable information.</p>--}}
{{--                            </div>--}}
{{--                        </div>--}}
                        {{--                        <div class="faqbg">--}}
                        {{--                            <div class="titles">Strictly Necessary Cookies</div>--}}
                        {{--                            <div class="faqtext">--}}
                        {{--                                <p>These cookies are necessary for the website to function and cannot be switched off in our systems. They are usually only set in response to actions made by you which amount to a request for services, such as setting your privacy preferences, logging in or filling in forms. You can set your browser to block or alert you about these cookies, but some parts of the site will not then work. These cookies do not store any personally identifiable information.</p>--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}
                        {{--                        <div class="faqbg">--}}
                        {{--                            <div class="titles">Targeting Cookies</div>--}}
                        {{--                            <div class="faqtext">--}}
                        {{--                                <p>These cookies may be set through our site by our advertising partners. They may be used by those companies to build a profile of your interests and show you relevant adverts on other sites. They do not store directly personal information, but are based on uniquely identifying your browser and internet device. If you do not allow these cookies, you will experience less targeted advertising.</p>--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}
                        {{--                        <div class="faqbg">--}}
                        {{--                            <div class="titles">Functional Cookies</div>--}}
                        {{--                            <div class="faqtext">--}}
                        {{--                                <p>These cookies enable the website to provide enhanced functionality and personalisation. They may be set by us or by third-party providers whose services we have added to our pages. If you do not allow these cookies then some or all of these services may not function properly.</p>--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}
{{--                    </div>--}}

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="accept">Accept All</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Reject All</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/js-cookie@2.2.1/src/js.cookie.min.js"></script>

    {{--<script src="{{asset('front/js/aos.js')}}"></script>--}}
    <script>
        $(document).ready(function() {
            // Function to check screen size and remove class accordingly
            function checkScreenSize() {
                var newWindowWidth = $(window).width();
                if (newWindowWidth < 768) { // Width for mobile screens
                    $('.modal-dialog').removeClass('modal-dialog-centered');
                } else {
                    $('.modal-dialog').addClass('modal-dialog-centered');
                }
            }

            // Run on document load and on window resize
            checkScreenSize();
            $(window).resize(checkScreenSize);
        });

        $(document).ready(function () {
            if (Cookies.get('visited') === undefined) {
                $('#cookieModal').modal('show');
            }

            $('#accept').click(function () {
                Cookies.set('visited', 'true', {expires: 7});
                $('#cookieModal').modal('hide');
            });
        });
        $(document).ready(function () {

            // AOS.init();
            /*-- Scroll Up/Down add class --*/
            var lastScrollTop = 0;
            $(window).scroll(function (event) {
                var header = $(this).scrollTop();
                if (header > lastScrollTop) {
                    //$('header').addClass('static');
                    $('header').removeClass('fixed');
                    //$('.navicon').removeClass('fixed');
                    $('.heighthead').removeClass('fixed');

                } else {
                    $('header').addClass('fixed');
                    //$('.navicon').addClass('fixed');
                    $('.heighthead').addClass('fixed');
                    //$('header').removeClass('static');
                }
                lastScrollTop = header;
                if ($(this).scrollTop() < 33) {
                    $('header').removeClass("fixed");
                    //$('.navicon').removeClass('fixed');
                    $('.heighthead').removeClass('fixed');
                }
            });


            $("body").click(function () {
                $('.navicon').removeClass('active');
                $('.topmenu').removeClass('showmenu');
            });

            $(".navicon").click(function (e) {
                e.stopPropagation();
                $(this).toggleClass('active');
                $(".topmenu").toggleClass('showmenu');
            });

            $(".sub-menu").before("<div class='ddclick'></div>");
            $(".ddclick").click(function () {
                $(this).toggleClass('active');
                $(this).next().slideToggle();
            });

            $(".topmenu").click(function (e) {
                e.stopPropagation();
            });
        });

        $('.homeslider').owlCarousel({
            items: 1,
            animateOut: 'fadeOut',
            loop: true,
            margin: 10,
        });


        $('.slider1').owlCarousel({
            loop: true,
            margin: 40,
            items: 2,
            responsiveClass: true,
            responsive: {
                0: {
                    items: 1,
                    nav: true
                },
                600: {
                    items: 2,
                    nav: false
                },
                1000: {
                    items: 3,
                    nav: true,
                    loop: false
                }
            }
        });


        $('.itemslide').owlCarousel({
            loop: true,
            margin: 0,
            items: 2,
            responsiveClass: true,
            responsive: {
                0: {
                    items: 1,
                    nav: true
                },
                600: {
                    items: 2,
                    nav: false
                },
                1000: {
                    items: 3,
                    nav: true,
                    loop: false
                }
            }
        })
    </script>
</body>
</html>
