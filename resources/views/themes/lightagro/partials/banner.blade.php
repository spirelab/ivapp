<!-- PAGE-BANNER -->
<style>
    .banner-section {
        background-image: url({{getFile(config('location.logo.path').'pertial_banner.jpg')}}) !important;
    }
</style>

@if(!request()->routeIs('home'))
    <!-- banner section -->
    <section class="banner-section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h3>@yield('title')</h3>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">@lang('Home')</a></li>
                            <li class="breadcrumb-item active" aria-current="page">@yield('title')</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>
@endif
