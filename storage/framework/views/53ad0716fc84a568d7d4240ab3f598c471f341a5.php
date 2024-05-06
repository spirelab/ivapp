<!DOCTYPE html>
<!--[if lt IE 7 ]>
<html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]>
<html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]>
<html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!-->
<html class="no-js" lang="en" <?php if(session()->get('rtl') == 1): ?> dir="rtl" <?php endif; ?> >
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>

    <?php echo $__env->make('partials.seo', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&family=Open+Sans&family=Ubuntu:wght@300;400;500;700&display=swap">

    <link rel="stylesheet" type="text/css" href="<?php echo e(asset($themeTrue.'css/jquery-ui.min.css')); ?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset($themeTrue.'css/bootstrap.min.css')); ?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset($themeTrue.'css/all.min.css')); ?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset($themeTrue.'css/icofont.min.css')); ?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset($themeTrue.'css/animate.css')); ?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset($themeTrue.'css/flags.css')); ?>"/>

    <?php echo $__env->yieldPushContent('css-lib'); ?>

    <link rel="stylesheet" type="text/css" href="<?php echo e(asset($themeTrue.'css/slick.css')); ?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset($themeTrue.'css/slick-theme.css')); ?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset($themeTrue.'css/owl.carousel.min.css')); ?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset($themeTrue.'css/owl.theme.default.min.css')); ?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset($themeTrue.'css/radialprogress.css')); ?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset($themeTrue.'css/perfect-scrollbar.css')); ?>">

    <link rel="stylesheet" href="<?php echo e(asset($themeTrue.'css/color.php')); ?>?primaryColor=<?php echo e(str_replace('#','',$basic->base_color)); ?>">

    <script src="<?php echo e(asset($themeTrue.'js/modernizr.custom.js')); ?>"></script>

    <style>
        @media    only screen and (max-width: 423px) {
            .xs-dropdown-menu {
                transform: translateX(-20px) !important;
            }

        }
    </style>
<?php echo $__env->yieldPushContent('style'); ?>

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script type="application/javascript" src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script type="application/javascript" src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>

<?php if(auth()->guard()->guest()): ?>
    <?php echo $__env->make($theme.'partials.topbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php else: ?>
    <?php echo $__env->make($theme.'partials.topbar-auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?>

<?php if(auth()->guard()->guest()): ?>
    <nav id="navbar">
        <div class="container">
            <div class="navbar navbar-expand-md flex-wrap">
                <a class="navbar-brand" href="<?php echo e(url('/')); ?>">
                    <img src="<?php echo e(getFile(config('location.logoIcon.path').'logo.png')); ?>"
                         alt="<?php echo e(config('basic.site_title')); ?>">
                </a>

                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#investmentnavbar">
					<span class="menu-icon">
						<span></span>
						<span></span>
						<span></span>
					</span>
                </button>

                <div class="collapse navbar-collapse" id="investmentnavbar">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('home')); ?>"><?php echo app('translator')->get('Home'); ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('plan')); ?>"><?php echo e(trans('Plan')); ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('about')); ?>"><?php echo app('translator')->get('About Us'); ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('blog')); ?>"><?php echo app('translator')->get('Blog'); ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('faq')); ?>"><?php echo app('translator')->get('FAQ'); ?></a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('contact')); ?>"><?php echo app('translator')->get('Contact'); ?></a>
                        </li>

                        <?php if(auth()->guard()->check()): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo e(route('user.home')); ?>"><?php echo app('translator')->get('Dashboard'); ?></a>
                            </li>
                        <?php endif; ?>
                    </ul>
                    <ul class="navbar-nav nav-registration  d-none d-md-block">
                        <?php if(auth()->guard()->guest()): ?>
                            <li class="nav-item login-signup"><a
                                    href="javascript:void(0)"><span><?php echo app('translator')->get('Login'); ?></span></a>
                            </li>
                        <?php endif; ?>

                        <?php if(auth()->guard()->check()): ?>
                            <li class="nav-item">
                                <a href="<?php echo e(route('logout')); ?>"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><span><?php echo app('translator')->get('Logout'); ?></span></a>
                                <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="d-none">
                                    <?php echo csrf_field(); ?>
                                </form>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
<?php endif; ?>

<?php if(auth()->guard()->check()): ?>
    <!-- NAVBAR | NAVBAR-LOGGEDIN -->
    <nav id="navbar" class="navbar-loggedin">
        <div class="container">
            <div class="navbar navbar-expand-md flex-wrap" id="pushNotificationArea">
                <div class="d-flex">

                    <a class="navbar-brand" href="<?php echo e(route('home')); ?>">
                        <img src="<?php echo e(getFile(config('location.logoIcon.path').'logo.png')); ?>" alt="homepage">
                    </a>
                </div>

                <div class="account d-flex d-md-none">
                    <div class="d-flex">
                        <div class="dropdown account-dropdown responsive-menus">
                            <a class="dropdown-toggle" role="button" data-toggle="dropdown" href="#">
                                <i class="icofont-home"></i>
                            </a>
                            <div class="xs-dropdown-menu xs-dropdown-menu1 dropdown-menu dropdown-right">
                                <div class="dropdown-content">
                                    <a class="dropdown-item" href="<?php echo e(route('home')); ?>"><?php echo app('translator')->get('Home'); ?></a>
                                    <a class="dropdown-item" href="<?php echo e(route('plan')); ?>"><?php echo e(trans('Plan')); ?></a>
                                    <a class="dropdown-item" href="<?php echo e(route('about')); ?>"><?php echo app('translator')->get('About Us'); ?></a>
                                    <a class="dropdown-item" href="<?php echo e(route('blog')); ?>"><?php echo app('translator')->get('Blog'); ?></a>
                                    <a class="dropdown-item" href="<?php echo e(route('faq')); ?>"><?php echo app('translator')->get('FAQ'); ?></a>
                                    <a class="dropdown-item" href="<?php echo e(route('contact')); ?>"><?php echo app('translator')->get('Contact'); ?></a>

                                </div>
                            </div>
                        </div>
                    </div>
                    <ul class="d-flex ml-20">
                        <?php echo $__env->make($theme.'partials.pushNotify', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <?php echo $__env->make($theme.'partials.profile-menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </ul>
                </div>

                <div class="collapse navbar-collapse justify-content-end justify-content-md-between"
                     id="investmentnavbar">
                    <ul class="navbar-nav d-none d-md-flex">
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('home')); ?>"><?php echo app('translator')->get('Home'); ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('plan')); ?>"><?php echo e(trans('Plan')); ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('about')); ?>"><?php echo app('translator')->get('About Us'); ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('blog')); ?>"><?php echo app('translator')->get('Blog'); ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('faq')); ?>"><?php echo app('translator')->get('FAQ'); ?></a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('contact')); ?>"><?php echo app('translator')->get('Contact'); ?></a>
                        </li>
                    </ul>
                    <div class="account">
                        <ul class="d-flex">
                            <?php echo $__env->make($theme.'partials.pushNotify', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            <?php echo $__env->make($theme.'partials.profile-menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <!-- /NAVBAR -->
<?php endif; ?>


<?php echo $__env->make($theme.'partials.banner', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php echo $__env->yieldContent('content'); ?>


<?php echo $__env->make($theme.'partials.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>



<?php echo $__env->yieldPushContent('extra-content'); ?>

<?php echo $__env->make($theme.'partials.modal-form', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<script src="<?php echo e(asset($themeTrue.'js/jquery-3.5.1.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/global/js/jquery-ui.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/global/js/popper.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/global/js/bootstrap.min.js')); ?>"></script>
<?php echo $__env->yieldPushContent('extra-js'); ?>

<script src="<?php echo e(asset('assets/global/js/notiflix-aio-2.7.0.min.js')); ?>"></script>
<script src="<?php echo e(asset($themeTrue.'js/fontawesome.min.js')); ?>"></script>
<script src="<?php echo e(asset($themeTrue.'js/wow.min.js')); ?>"></script>
<script src="<?php echo e(asset($themeTrue.'js/jquery.flagstrap.min.js')); ?>"></script>
<script src="<?php echo e(asset($themeTrue.'js/slick.min.js')); ?>"></script>
<script src="<?php echo e(asset($themeTrue.'js/owl.carousel.min.js')); ?>"></script>
<script src="<?php echo e(asset($themeTrue.'js/multi-animated-counter.js')); ?>"></script>
<script src="<?php echo e(asset($themeTrue.'js/radialprogressOld.js')); ?>"></script>


<script src="<?php echo e(asset('assets/global/js/pusher.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/global/js/vue.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/global/js/axios.min.js')); ?>"></script>

<script src="<?php echo e(asset($themeTrue.'js/script.js')); ?>"></script>

<?php if(auth()->guard()->check()): ?>
    <?php if(config('basic.push_notification') == 1): ?>
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
                    axios.get("<?php echo e(route('user.push.notification.show')); ?>")
                        .then(function (res) {
                            app.items = res.data;
                        })
                },
                readAt(id, link) {
                    let app = this;
                    let url = "<?php echo e(route('user.push.notification.readAt', 0)); ?>";
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
                    let url = "<?php echo e(route('user.push.notification.readAll')); ?>";
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
                    let pusher = new Pusher("<?php echo e(env('PUSHER_APP_KEY')); ?>", {
                        encrypted: true,
                        cluster: "<?php echo e(env('PUSHER_APP_CLUSTER')); ?>"
                    });
                    let channel = pusher.subscribe('user-notification.' + "<?php echo e(Auth::id()); ?>");
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
    <?php endif; ?>
<?php endif; ?>

<?php echo $__env->yieldPushContent('script'); ?>
<?php if(session()->has('success')): ?>
    <script>
        Notiflix.Notify.Success("<?php echo app('translator')->get(session('success')); ?>");
    </script>
<?php endif; ?>

<?php if(session()->has('error')): ?>
    <script>
        Notiflix.Notify.Failure("<?php echo app('translator')->get(session('error')); ?>");
    </script>
<?php endif; ?>

<?php if(session()->has('warning')): ?>
    <script>
        Notiflix.Notify.Warning("<?php echo app('translator')->get(session('warning')); ?>");
    </script>
<?php endif; ?>


<script>
    $(document).ready(function () {
        $(".language").find("select").change(function () {
            window.location.href = "<?php echo e(route('language')); ?>/" + $(this).val()
        })
    })

</script>


<?php echo $__env->make('plugins', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

</body>
</html>
<?php /**PATH /www/wwwroot/invest.xtake.com/resources/views/themes/deepblue/layouts/app.blade.php ENDPATH**/ ?>