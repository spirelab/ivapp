@if(isset($templates['about-us'][0]) && $aboutUs = $templates['about-us'][0])
    <!-- about section -->
    <section class="about-section">
        <div class="container">
            <div class="row g-lg-5 justify-content-between">
                <div class="col-lg-6">
                    <div class="img-box">
                        <img src="{{getFile(config('location.content.path').@$aboutUs->templateMedia()->image)}}" alt="" class="img-fluid img-1" data-aos="fade-right" data-aos-duration="800" data-aos-anchor-placement="center-bottom" />
                        <img src="{{getFile(config('location.content.path').@$aboutUs->templateMedia()->about_image_two)}}" alt="" class="img-fluid img-2" data-aos="fade-up" data-aos-duration="1000" data-aos-anchor-placement="center-bottom" />
                        <div class="icon">
                            <img src="{{getFile(config('location.content.path').@$aboutUs->templateMedia()->about_image_three)}}" alt="" class="img-fluid" data-aos="fade-left" data-aos-duration="1200" data-aos-anchor-placement="center-bottom" />
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="header-text mb-4">
                        <h5>@lang(optional($aboutUs->description)->title)</h5>
                        <h2 class="mb-4">@lang(optional($aboutUs->description)->sub_title)</h2>
                    </div>
                    <p>
                        @lang(optional($aboutUs->description)->short_title)
                    </p>
                    <p>
                        @lang(optional($aboutUs->description)->short_description)
                    </p>
                    <a href="{{ route('about') }}" class="btn-custom mt-4">@lang('Know More')</a>
                </div>
            </div>
        </div>
    </section>
@endif


