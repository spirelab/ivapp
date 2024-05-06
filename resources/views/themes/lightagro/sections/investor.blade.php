@if(isset($templates['investor'][0]) && $investor = $templates['investor'][0])
    @if(0 < count($investors))
        <!-- top investor section -->
        <section class="top-investor">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="header-text text-center">
                            <h5>@lang(optional($investor->description)->title)</h5>
                            <h3>@lang(optional($investor->description)->sub_title)</h3>
                            <p class="mx-auto">
                                @lang(optional($investor->description)->short_title)
                            </p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div
                            class="investor-wrapper {{(session()->get('rtl') == 1) ? 'investors-rtl': 'investors'}} owl-carousel">
                            @foreach($investors->take(4) as $item)
                                <div class="investor-box">
                                    <div class="img-box">
                                        <img src="{{getFile(config('location.user.path').optional($item->user)->image) }}" class="img-fluid" alt="@lang('Investor Image Missing')"/>
                                        <h6 class="title">@lang('Investor')</h6>
                                    </div>
                                    <div class="text-box">
                                        <h6>@lang(optional($item->user)->username)</h6>
                                        <h5>@lang('Invested') {{$basic->currency_symbol}}{{getAmount($item->totalAmount)}}</h5>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
@endif
