@if(isset($templates['hero'][0]) && $hero = $templates['hero'][0])

    @push('style')
        <style>
            .home-section {
                background-image: url({{getFile(config('location.content.path').@$hero->templateMedia()->background_image)}}) !important;
            }
        </style>
    @endpush

    <!-- home section -->
    <section class="home-section">
        <div class="container h-100">
            <div class="row h-100 align-items-center justify-content-center">
                <div class="col-lg-8">
                    <div class="text-box text-center">
                        <h5 class="my-4">@lang(@$hero['description']->title) @lang(@$hero['description']->sub_title)</h5>
                        <h1>@lang(@$hero['description']->short_description)</h1>
                        <div class="d-flex align-items-center justify-content-center mt-5">
                            <a class="btn-custom cursor-pointer" href="{{@$hero->templateMedia()->button_link}}">@lang(@$hero['description']->button_name)</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endif


