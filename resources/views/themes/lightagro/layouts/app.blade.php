<!DOCTYPE html>
<!--[if lt IE 7 ]>
<html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]>
<html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]>
<html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!-->
<html class="no-js" lang="en" @if(session()->get('rtl') == 1) dir="rtl" @endif >
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>

    @include('partials.seo')

    <!-- bootstrap 5 -->
    <link rel="stylesheet" href="{{asset($themeTrue.'css/bootstrap.min.css')}}"/>

    @stack('css-lib')

    <!-- font awesome 6 -->
    <link rel="stylesheet" href="{{asset($themeTrue.'css/all.min.css')}}"/>
    <link rel="stylesheet" href="{{asset($themeTrue.'css/fontawesome.min.css')}}"/>
    <!-- aos animation -->
    <link rel="stylesheet" href="{{asset($themeTrue.'css/aos.css')}}"/>
    <!-- owl carousel -->
    <link rel="stylesheet" href="{{asset($themeTrue.'css/owl.carousel.min.css')}}"/>
    <link rel="stylesheet" href="{{asset($themeTrue.'css/owl.theme.default.min.css')}}"/>
    <!-- select 2 -->
    <link rel="stylesheet" href="{{asset($themeTrue.'css/select2.min.css')}}"/>
    <!-- fancybox -->
    <link rel="stylesheet" href="{{asset($themeTrue.'css/fancybox.css')}}"/>
    <!-- custom css -->
    <link rel="stylesheet" href="{{asset($themeTrue.'css/style.css')}}"/>

    @stack('style')

</head>

<body class="">

<!-- pre loader -->
<div id="preloader">
    <img src="{{ asset($themeTrue.'img/icon/plant.gif') }}" alt="" class="loader"/>
</div>
<!-- preloader_area_end -->

<!-- TOPBAR -->
@include($theme.'partials.topbar')
<!-- /TOPBAR -->

@include($theme.'partials.banner')

@yield('content')

@include($theme.'partials.footer')

@stack('extra-content')


<!-- bootstrap -->
<script src="{{asset($themeTrue.'js/bootstrap.bundle.min.js')}}"></script>
<!-- jquery cdn -->
<script src="{{asset($themeTrue.'js/jquery-3.6.0.min.js')}}"></script>
<!-- counter up -->
<script src="{{asset($themeTrue.'js/jquery.counterup.min.js')}}"></script>
<script src="{{asset($themeTrue.'js/jquery.waypoints.min.js')}}"></script>
<!-- aos animation -->
<script src="{{asset($themeTrue.'js/aos.js')}}"></script>
<!-- owl carousel -->
<script src="{{asset($themeTrue.'js/owl.carousel.min.js')}}"></script>
<!-- select 2 -->
<script src="{{asset($themeTrue.'js/select2.min.js')}}"></script>
<!-- fancy box -->
<script src="{{asset($themeTrue.'js/fancybox.umd.js')}}"></script>
<!-- social js -->
<script src="{{asset($themeTrue.'js/socialSharing.js')}}"></script>



@stack('extra-js')
<script src="{{asset('assets/global/js/notiflix-aio-2.7.0.min.js')}}"></script>
<script src="{{asset('assets/global/js/pusher.min.js')}}"></script>
<script src="{{asset('assets/global/js/vue.min.js')}}"></script>
<script src="{{asset('assets/global/js/axios.min.js')}}"></script>

<!-- custom script -->
<script src="{{asset($themeTrue.'js/script.js')}}"></script>


@stack('script')

<script>
    'use strict'

    // pre loader
    const preloader = document.getElementById("preloader");
    window.addEventListener("load", () => {
        setTimeout(() => {
            preloader.style.cssText = `opacity: 0; visibility: hidden;`;
        }, 1000);
    });

    // COUNTER UP
    $(".counter").counterUp({
        delay: 10,
        time: 3000,
    });

</script>

@auth
    @if(config('basic.push_notification') == 1)
        <script>
            'use strict';
            let pushNotificationArea = new Vue({
                el: "#pushNotificationArea",
                data: {
                    items: [],
                },
                mounted() {
                    this.getNotifications();
                    this.pushNewItem();
                },
                methods: {
                    getNotifications() {
                        let app = this;
                        axios.get("{{ route('user.push.notification.show') }}")
                            .then(function (res) {
                                app.items = res.data;
                            })
                    },
                    readAt(id, link) {
                        let app = this;
                        let url = "{{ route('user.push.notification.readAt', 0) }}";
                        url = url.replace(/.$/, id);
                        axios.get(url)
                            .then(function (res) {
                                if (res.status) {
                                    app.getNotifications();
                                    if (link != '#') {
                                        window.location.href = link
                                    }
                                }
                            })
                    },
                    readAll() {
                        let app = this;
                        let url = "{{ route('user.push.notification.readAll') }}";
                        axios.get(url)
                            .then(function (res) {
                                if (res.status) {
                                    app.items = [];
                                }
                            })
                    },
                    pushNewItem() {
                        let app = this;
                        // Pusher.logToConsole = true;
                        let pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
                            encrypted: true,
                            cluster: "{{ env('PUSHER_APP_CLUSTER') }}"
                        });
                        let channel = pusher.subscribe('user-notification.' + "{{ Auth::id() }}");
                        channel.bind('App\\Events\\UserNotification', function (data) {
                            app.items.unshift(data.message);
                        });
                        channel.bind('App\\Events\\UpdateUserNotification', function (data) {
                            app.getNotifications();
                        });
                    }
                }
            });
        </script>
    @endif
@endauth


@if (session()->has('success'))
    <script>
        Notiflix.Notify.Success("@lang(session('success'))");
    </script>
@endif

@if (session()->has('error'))
    <script>
        Notiflix.Notify.Failure("@lang(session('error'))");
    </script>
@endif

@if (session()->has('warning'))
    <script>
        Notiflix.Notify.Warning("@lang(session('warning'))");
    </script>
@endif

@include('plugins')





<script>
    var root = document.querySelector(':root');
    root.style.setProperty('--primary', '{{config('basic.base_color')??'#ffb300'}}');
</script>
</body>
</html>
