<header id="header" class="headerbg homeheader">
    <div class="topbar">
        <div class="centertext">Now earning money is on your fingertips! Explore with GTS</div>
        <div class="tophead">
            <input type="text" name="" value="" placeholder="Search" class="search"/>

        </div>
    </div>
    <div class="topheader">
        <div class="logo" data-aos="fade-down" data-aos-duration="1500">
            <a href="{{route('home')}}"> <img src="{{asset('front/images/logo.png')}}" alt="GTS"/> </a>
        </div>
        <div class="navicon"><span></span></div>
        <div class="topmenu">
            <ul>
                <li><a href="{{route('service')}}">Service</a></li>
                <li><a href="{{route('legal')}}">Legal Policy</a></li>
                <li><a href="{{route('faq')}}">FAQ</a></li>
                <li><a href="{{route('contact')}}">Contact</a></li>
                {{--    		<li><a href="#">Blog</a></li>--}}
                <li><a href="{{route('login')}}" class="logbtn">Login</a></li>
                <li><a href="{{route('register')}}" class="regbtn">Register</a></li>
            </ul>
        </div>
    </div>
</header>
