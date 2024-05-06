@extends('mobile-payment.layout')
@section('content')
    <section class="pwa-payment-section">
        <div class="container-fluid h-100">
            <div class="row h-100">
                <div class="col h-100 d-flex align-items-center justify-content-center">
                    <div class="pay-box">
                        <div class="d-flex">
                            <div class="img-box">
                                <img
                                    class="img-fluid"
                                    src="{{getFile(config('location.gateway.path').optional($order->gateway)->image)}}"
                                    alt="..."
                                />
                            </div>
                            <div class="text-box">
                                <h4>@lang('Pay') {{round($order->final_amount)}} {{$order->gateway_currency}}</h4>
                                <h5>@lang('To get') {{getAmount($order->amount)}}  {{$basic->currency}}</h5>
                            </div>
                        </div>
                        <button class="btn-custom w-100" id="pay-button">@lang('Pay Now')</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('script')
    <script type="text/javascript"
            src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ $data->client_key }}"></script>

    <script defer>
        var payButton = document.getElementById('pay-button');
        payButton.addEventListener('click', function () {
            window.snap.pay("{{ $data->token }}");
        });
    </script>
@endpush
