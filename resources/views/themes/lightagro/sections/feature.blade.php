@if(isset($contentDetails['feature']))
    @if(0 < count($contentDetails['feature']))
        <!-- statistics area start -->
        <section class="achievement-section">
            <div class="container">
                <div class="row g-4 align-items-center justify-content-evenly">

                    @foreach($contentDetails['feature'] as $feature)
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="achievement-box" data-aos="fade-up" data-aos-duration="800"
                                 data-aos-anchor-placement="center-bottom">
                                <div class="icon-box">
                                    <img src="{{getFile(config('location.content.path').@$feature->content->contentMedia->description->image)}}" alt="@lang('feature image')" />
                                </div>

                                <h4><span class="counter">@lang(optional($feature->description)->information)</span></h4>
                                <h5>@lang(optional($feature->description)->title)</h5>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endif
<!-- statistics_area_end -->
