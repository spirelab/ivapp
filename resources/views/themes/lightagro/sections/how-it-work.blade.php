@if(isset($templates['how-it-work'][0]) && $howItWork = $templates['how-it-work'][0])
    <!-- how it works -->
    @php
        $totalContents = $contentDetails['how-it-work'];
    @endphp
    <section class="how-it-works @if(session()->get('rtl') == 1) rtl @endif">
        <div class="container">
            <div class="row">
                <div class="col-lg-6"></div>

                <div class="col-lg-6">
                    <div class="header-text">
                        <h5>@lang(optional($howItWork->description)->sub_title)</h5>
                        <h3>@lang(optional($howItWork->description)->title)</h3>
                    </div>

                    <div class="work-box-wrapper">
                        @foreach($totalContents as $k =>  $item)
                            <div class="work-box" data-aos="fade-left" data-aos-duration="800"
                                 data-aos-anchor-placement="center-bottom">
                                <div class="number">
                                    <h3>0{{ ++$k }}</h3>
                                </div>
                                <div class="text">
                                    <h4>@lang(optional($item->description)->title)</h4>
                                    <p>@lang(optional($item->description)->short_description)</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>
        <div class="img-box-wrapper">
            <div class="img-box">
                <img src="{{getFile(config('location.content.path').@$howItWork->templateMedia()->image)}}" class="img-fluid" alt=""/>
                <div class="icon">
                    <img src="{{getFile(config('location.content.path').@$howItWork->templateMedia()->image_two)}}" alt=""/>
                </div>
            </div>
        </div>
    </section>
@endif
