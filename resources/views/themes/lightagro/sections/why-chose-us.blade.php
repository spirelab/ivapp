@if(isset($templates['why-chose-us'][0]) && $whyChoseUs = $templates['why-chose-us'][0])
    <!-- feature section -->
    <section class="feature-section">
        <div class="container">
            <div class="row">
                <div class="header-text text-center">
                    <h5>@lang(optional($whyChoseUs->description)->title)</h5>
                    <h3>@lang(optional($whyChoseUs->description)->sub_title)</h3>
                    <p class="mx-auto">@lang(optional($whyChoseUs->description)->short_details)</p>
                </div>
            </div>

            @if(isset($contentDetails['why-chose-us']))
                <div class="row g-4 g-lg-5">
                    @foreach($contentDetails['why-chose-us'] as $item)
                        <div class="col-lg-6 col-md-6">
                            <div class="feature-box" >
                                <div class="icon-box">
                                    <img src="{{getFile(config('location.content.path').optional(optional(optional($item->content)->contentMedia)->description)->image)}}" alt=""/>
                                </div>
                                <div class="text-box">
                                    <h4>@lang(optional($item->description)->title)</h4>
                                    <p>
                                        @lang(optional($item->description)->information)
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            @endif
        </div>
    </section>
@endif
