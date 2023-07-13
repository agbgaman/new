<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>GTS</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800;900&display=swap"
          rel="stylesheet">
    <link type="text/css" href="{{asset('front/css/animate.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('front/css/aos.css')}}">
    <link type="text/css" href="{{asset('front/css/bootstrap.css')}}" rel="stylesheet">
    <link type="text/css" href="{{asset('front/css/owl.carousel.min.css')}}" rel="stylesheet">
    <link type="text/css" href="{{asset('front/css/owl.theme.default.min.css')}}" rel="stylesheet">
    <script src="{{asset('front/js/jquery.min.js')}}"></script>
    <script src="{{asset('front/js/owl.carousel.min.js')}}"></script>
    <link type="text/css" href="{{asset('front/css/font-awesome.css')}}" rel="stylesheet">
    <link type="text/css" href="{{asset('front/css/style.css')}}" rel="stylesheet">
</head>
<body>
<div class="heighthead"></div>
@include('frontend.layouts.header')

@yield('content')

@include('frontend.layouts.footer')


<script>
    $(document).ready(function() {
        if (Cookies.get('visited') === undefined) {
            $('#cookieModal').modal('show');
        }

        $('#accept').click(function() {
            Cookies.set('visited', 'true', { expires: 7 });
            $('#cookieModal').modal('hide');
        });
    });
    $(document).ready(function () {

//AOS.init();
        /*-- Scroll Up/Down add class --*/
        var lastScrollTop = 0;
        $(window).scroll(function(event){
            var header = $(this).scrollTop();
            if (header > lastScrollTop){
                //$('header').addClass('static');
                $('header').removeClass('fixed');
                //$('.navicon').removeClass('fixed');
                $('.heighthead').removeClass('fixed');

            } else{
                $('header').addClass('fixed');
                //$('.navicon').addClass('fixed');
                $('.heighthead').addClass('fixed');
                //$('header').removeClass('static');
            }
            lastScrollTop = header;
            if ($(this).scrollTop() < 33){
                $('header').removeClass("fixed");
                //$('.navicon').removeClass('fixed');
                $('.heighthead').removeClass('fixed');
            }
        });

        $( "body" ).click(function() {
            $('.navicon').removeClass('active');
            $('.topmenu').removeClass('showmenu');
        });

        $( ".navicon" ).click(function(e) {
            e.stopPropagation();
            $(this).toggleClass('active');
            $( ".topmenu" ).toggleClass('showmenu');
        });

        $(".sub-menu").before("<div class='ddclick'></div>");
        $( ".ddclick" ).click(function() {
            $(this).toggleClass('active');
            $(this).next().slideToggle();
        });

        $(".topmenu").click(function(e){
            e.stopPropagation();
        });
    });

    $('.homeslider').owlCarousel({
        items: 1,
        animateOut: 'fadeOut',
        loop: true,
        margin: 10,
    });

    $( ".titles" ).click(function() {
        $(this).next().slideToggle();
    });
</script>
</body>
</html>
