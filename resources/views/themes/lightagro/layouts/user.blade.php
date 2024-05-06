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
    <link rel="stylesheet" href="{{asset($themeTrue.'css/bootstrap.min.css')}}">
    @stack('css-lib')
    <!-- font awesome 6 -->
    <link rel="stylesheet" href="{{asset($themeTrue.'css/all.min.css')}}">
    <link rel="stylesheet" href="{{asset($themeTrue.'css/fontawesome.min.css')}}">
    <!-- aos animation -->
    <link rel="stylesheet" href="{{asset($themeTrue.'css/aos.css')}}">
    <!-- owl carousel -->
    <link rel="stylesheet" href="{{asset($themeTrue.'css/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{asset($themeTrue.'css/owl.theme.default.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset($themeTrue.'css/radialprogress.css')}}">
    <!-- select 2 -->
    <link rel="stylesheet" href="{{asset($themeTrue.'css/select2.min.css')}}">
    <!-- fancybox -->
    <link rel="stylesheet" href="{{asset($themeTrue.'css/fancybox.css')}}">
    <!-- custom css -->
    <link rel="stylesheet" href="{{asset($themeTrue.'css/dashboard.css')}}">
    <script src="{{asset($themeTrue.'js/modernizr.custom.js')}}"></script>

    @stack('style')

</head>
<body @if(session()->get('rtl') == 1) class="rtl" @endif>

<!-- bottom navbar -->
<div class="bottom-nav fixed-bottom d-lg-none">
    <div class="link-item">
        <button onclick="toggleSideMenu()">
            <span class="icon"><i class="fal fa-ellipsis-v-alt"></i></span>
            <span class="text">@lang('Menus')</span>
        </button>
    </div>
    <div class="link-item">
        <a href="{{ route('plan') }}">
            <span class="icon"><i class="fal fa-clipboard-list"></i></span>
            <span class="text">@lang('Plan')</span>
        </a>
    </div>

    <div class="link-item {{menuActive(['user.home'])}}">
        <a href="{{ route('user.home') }}">
            <span class="icon"><i class="fal fa-house"></i></span>
            <span class="text">@lang('Home')</span>
        </a>
    </div>

    <div class="link-item {{menuActive(['user.addFund'])}}">
        <a href="{{ route('user.addFund') }}">
            <span class="icon"><i class="fal fa-funnel-dollar" aria-hidden="true"></i></span>
            <span class="text">@lang('Deposit')</span>
        </a>
    </div>

    <div class="link-item {{menuActive(['user.profile'])}}">
        <a href="{{ route('user.profile') }}">
            <span class="icon"><i class="fal fa-user"></i></span>
            <span class="text">@lang('Profile')</span>
        </a>
    </div>
</div>


<div class="dashboard-wrapper">

    <!------ sidebar ------->
    @include($theme.'partials.sidebar')
    <!-- content -->
    <div id="content">
        <div class="overlay">
            <!-- navbar -->
            <nav class="navbar navbar-expand-lg">
                <div class="container-fluid">
                    <a class="navbar-brand d-lg-none" href="{{route('home')}}"> <img
                            src="{{getFile(config('location.logoIcon.path').'logo.png')}}"
                            alt="{{config('basic.site_title')}}"></a>
                    <button class="sidebar-toggler d-none d-lg-block" onclick="toggleSideMenu()">
                        <i class="fa-sharp fa-light fa-bars-staggered"></i>
                    </button>
                    <!-- navbar text -->
                    <div class="navbar-text">
                        <!-- notification panel -->
                        @include($theme.'partials.pushNotify')
                        <!-- user panel -->
                        @include($theme.'partials.userDropdown')
                    </div>
                </div>
            </nav>
            @yield('contents')
        </div>
    </div>
</div>

@stack('loadModal')


<!-- bootstrap -->
<script src="{{asset($themeTrue.'js/bootstrap.bundle.min.js')}}"></script>
<!-- jquery cdn -->
<script src="{{asset($themeTrue.'js/jquery-3.6.0.min.js')}}"></script>

<script src="{{asset($themeTrue.'js/jquery.waypoints.min.js')}}"></script>
<script src="{{asset($themeTrue.'js/jquery-ui.js')}}"></script>
<script src="{{asset($themeTrue.'js/radialprogress.js')}}"></script>
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
<script src="{{asset($themeTrue.'js/script.js')}}"></script>


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
@stack('script')


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
