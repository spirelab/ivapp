@if(isset($templates['testimonial'][0]) && $testimonial = $templates['testimonial'][0])
    <!-- testimonial section -->
    <section class="testimonial-section">
        <div class="overlay">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="header-text text-center">
                            <h5>@lang(optional($testimonial->description)->title)</h5>
                            <h2>@lang(optional($testimonial->description)->sub_title)</h2>
                            <p class="mx-auto">@lang(optional($testimonial->description)->short_title)</p>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    @if(isset($contentDetails['testimonial']))
                        <div class="col-lg-7">
                            <div class="testimonial-wrapper">
                                <div
                                    class="testimonials {{(session()->get('rtl') == 1) ? 'client-testimonials-rtl': 'client-testimonials'}} owl-carousel">
                                    @foreach($contentDetails['testimonial'] as $key => $data)
                                        <div class="review-box">
                                            <div class="img-box">
                                                <img src="{{getFile(config('location.content.path').@$data->content->contentMedia->description->image)}}" alt=""/>
                                            </div>
                                            <div class="text">
                                                <img class="quote" src="{{ asset($themeTrue.'img/icon/quote.png') }}" alt=""/>
                                                <p>
                                                    @lang(@$data->description->description)
                                                </p>
                                                <h4>@lang(@$data->description->name)</h4>
                                                <span class="title">@lang(@$data->description->designation)</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endif
