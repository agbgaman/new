<!-- Back to top -->
<a href="#top" id="back-to-top"><i class="fa fa-angle-double-up"></i></a>

<!-- Jquery -->
<script src="{{URL::asset('plugins/jquery/jquery-3.6.0.min.js')}}"></script>

<!-- Bootstrap 5 -->
<script src="{{URL::asset('plugins/bootstrap-5.0.2/js/bootstrap.bundle.min.js')}}"></script>

<!-- Sidemenu -->
<script src="{{URL::asset('plugins/sidemenu/sidemenu.js')}}"></script>

<!-- P-scroll -->
<script src="{{URL::asset('plugins/p-scrollbar/p-scrollbar.js')}}"></script>
<script src="{{URL::asset('plugins/p-scrollbar/p-scroll.js')}}"></script>

@yield('js')

<!-- Simplebar JS -->
<script src="{{URL::asset('plugins/simplebar/js/simplebar.min.js')}}"></script>

<!-- Tippy JS -->
<script src="{{URL::asset('plugins/tippy/popper.min.js')}}"></script>
<script src="{{URL::asset('plugins/tippy/tippy-bundle.umd.min.js')}}"></script>

<!-- Pie Chart JS -->
<script src="{{URL::asset('plugins/easy-pie-chart/easy-pie-chart.js')}}"></script>

<!-- Custom JS-->
<script src="{{URL::asset('js/custom.js')}}"></script>

<!-- Google Analytics -->
@if (config('services.google.analytics.enable') == 'on')
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('services.google.analytics.id') }}"></script>
    <script type="text/javascript">
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', '{{ config('services.google.analytics.id') }}');
    </script>
@endif

<!-- Mark as Read JS-->
<script type="text/javascript">

    function sendMarkRequest(id = null) {
        return $.ajax("{{ route('user.notifications.mark') }}", {
            method: 'POST',
            data: {"_token": "{{ csrf_token() }}", id}
        });
    }

    var totalNotifications;
    var totalNotifications_a;
    var totalNotifications_b;

    $(function() {     

        $('.mark-as-read').click(function() {
            let request = sendMarkRequest($(this).data('id'));
            request.done(() => {
                $(this).parents('div.dropdown-item').remove();
            });

            document.getElementById("total-notifications").innerHTML = --totalNotifications;
            document.getElementById("total-notifications-a").innerHTML = --totalNotifications_a;
            document.getElementById("total-notifications-b").innerHTML = --totalNotifications_b;
        });
        $('#mark-all').click(function() {
            let request = sendMarkRequest();
            request.done(() => {
                $('div.notify-menu').remove();
            })

            document.getElementById("total-notifications").innerHTML = 0;
        });
    });        

    $(document).ready(function(){
       
        if (document.getElementById("total-notifications")) {
            totalNotifications = "{{ auth()->user()->unreadNotifications->where('type', '<>', 'App\Notifications\GeneralNotification')->count() }}";
            document.getElementById("total-notifications").innerHTML = totalNotifications;
        }  
        if (document.getElementById("total-notifications-a")) {
            totalNotifications_a = "{{ auth()->user()->unreadNotifications->where('type', '<>', 'App\Notifications\GeneralNotification')->count() }}";
            document.getElementById("total-notifications-a").innerHTML = totalNotifications_a;
        }
        if (document.getElementById("total-notifications-b")) {
            totalNotifications_b = "{{ auth()->user()->unreadNotifications->where('type', '<>', 'App\Notifications\GeneralNotification')->count() }}";
            document.getElementById("total-notifications-b").innerHTML = totalNotifications_b;
        }                  
        
    });

    $.fn.exists = function () {
        return this.length > 0;
    };

    tippy('[data-tippy-content]', {
        animation: 'scale-extreme',
        theme: 'material',
    });

    if($('.pie-chart').exists()) {
        $('.pie-chart').each(function() {
            let $element = $(this);
            let pieChartText = $element.attr('data-text') || "100";
            let pieChartPercent = $element.attr('data-percent') || "100";
            $element.easyPieChart({
                size: 60,
                animate: 1000,
                delay: 3000,
                barColor: '#007bff',
                trackColor: '#f5f9fc',
                scaleColor: false,
                lineWidth: 5,
                lineCap: 'square',
                onStep: function(from, to, percent) {
                    if (pieChartText==0) {
                        $element.find('span.percent').text("0");
                    } else {
                        $element.find('span.percent').text(Math.round(pieChartText / pieChartPercent * percent));
                    }
                }
            });
        });
    }
   

    

</script>