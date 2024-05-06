<!-- pricing section -->
@if(isset($templates['investment'][0]) && $investment = $templates['investment'][0])
    <section class="pricing-section {{ requestLastSegment() == 'plan' ? 'pricing-page' : '' }}">
        <div class="container">
            <div class="row">
                <div class="header-text text-center">
                    <h5>@lang(optional($investment->description)->title)</h5>
                    <h2>@lang(optional($investment->description)->sub_title)</h2>
                    <p class="mx-auto">@lang(optional($investment->description)->short_details)</p>
                </div>
            </div>

            <div class="row justify-content-center g-4 g-lg-5">
                @foreach($plans as $k => $data)
                    @php
                        $getTime = \App\Models\ManageTime::where('time', $data->schedule)->first();
                    @endphp
                    <div class="col-lg-4 col-md-6">
                        <div class="pricing-box" data-aos="fade-up" data-aos-duration="800"
                             data-aos-anchor-placement="center-bottom">
                            <h4>@lang($data->name)</h4>
                            <h3 class="text-primary">{{$data->price}}</h3>
                            @if ($data->profit_type == 1)
                                <h5>{{getAmount($data->profit)}}{{'%'}} @lang('Every') {{trans($getTime->name)}}</h5>
                            @else
                                <h5>{{trans($basic->currency_symbol)}}{{getAmount($data->profit)}} @lang('Every') {{trans($getTime->name)}}</h5>
                            @endif
                            <ul class="list-unstyled">
                                <li>@lang('Profit For') <span
                                        class="bg-primary"> {{($data->is_lifetime ==1) ? trans('Lifetime') : trans('Every').' '.trans($getTime->name)}}</span>
                                </li>
                                <li>@lang('Capital will back') <span
                                        class="bg-{{($data->is_capital_back ==1) ? 'success':'danger'}}">{{($data->is_capital_back ==1) ? trans('Yes'): trans('No')}}</span>
                                </li>
                                @if($data->is_lifetime == 0)
                                    <li>@lang('Total')  {{trans($data->profit*$data->repeatable)}} {{($data->profit_type == 1) ? '%': trans($basic->currency)}}
                                        +
                                        @if($data->is_capital_back == 1)
                                            <span class="bg-success">@lang('Capital')</span>
                                        @endif
                                    </li>

                                @else
                                @endif
                                <li>@lang('Lifetime Earning')</li>
                            </ul>
                            <button class="btn-custom investNow" type="button" data-bs-toggle="modal"
                                    data-bs-target="#investModal" data-price="{{$data->price}}" data-resource="{{$data}}">
                                @lang('Invest now')
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif


<!-- Modal -->
<div class="modal fade" id="investNowModal" tabindex="-1" aria-labelledby="investModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="investModalLabel">@lang('Invest Now')</h4>
                <button type="button" class="close-btn" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fal fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <h5 class="title plan-name"></h5>
                    <p class="price-range"></p>
                    <p class="profit-details"></p>
                    <p class="profit-validity"></p>
                </div>
                <form class="login-form" id="invest-form" action="{{route('user.purchase-plan')}}" method="post">
                    @csrf
                    <div class="row g-3 align-items-end">
                        <div class="input-box col-12">
                            <select class="form-select" aria-label="Default select example" name="balance_type">
                                @auth
                                    <option
                                        value="balance">@lang('Deposit Balance - '.$basic->currency_symbol.getAmount(auth()->user()->balance))</option>
                                    <option
                                        value="interest_balance">@lang('Interest Balance -'.$basic->currency_symbol.getAmount(auth()->user()->interest_balance))</option>
                                @endauth
                                    <option value="checkout">@lang('Checkout')</option>
                            </select>
                        </div>
                        <div class="input-box col-12">
                            <div class="input-group">
                                <input type="text" class="form-control invest-amount" name="amount" id="amount" value="{{old('amount')}}" onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')" autocomplete="off" placeholder="@lang('Enter amount')" />
                                <span class="input-group-text show-currency"></span>
                            </div>
                        </div>
                        <input type="hidden" name="plan_id" class="plan-id">
                        <div class="input-box col-12">
                            <button class="btn-custom w-100"><i class="fal fa-wallet"></i>@lang('Invest Now')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('script')
    <script>
        "use strict";
        (function ($) {
            $(document).on('click', '.investNow', function () {
                var planModal = new bootstrap.Modal(document.getElementById('investNowModal'))
                planModal.show()
                let data = $(this).data('resource');
                let price = $(this).data('price');
                let symbol = "{{trans($basic->currency_symbol)}}";
                let currency = "{{trans($basic->currency)}}";
                $('.price-range').text(`@lang('Invest'): ${price}`);

                if (data.fixed_amount == '0') {
                    $('.invest-amount').val('');
                    $('#amount').attr('readonly', false);
                } else {
                    $('.invest-amount').val(data.fixed_amount);
                    $('#amount').attr('readonly', true);
                }

                $('.profit-details').html(`@lang('Interest'): ${(data.profit_type == '1') ? `${data.profit} %` : `${data.profit} ${currency}`}`);
                $('.profit-validity').html(`@lang('Per') ${data.schedule} @lang('hours') ,  ${(data.is_lifetime == '0') ? `${data.repeatable} @lang('times')` : `@lang('Lifetime')`}`);
                $('.plan-name').text(data.name);
                $('.plan-id').val(data.id);
                $('.show-currency').text("{{config('basic.currency')}}");
            });

        })(jQuery);
    </script>

    @if(count($errors) > 0 )
        <script>
            @foreach($errors->all() as $key => $error)
            Notiflix.Notify.Failure("@lang($error)");
            @endforeach
        </script>
    @endif
@endpush

