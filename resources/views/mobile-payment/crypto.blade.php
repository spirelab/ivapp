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
                                <h4>@lang('Pay') {{getAmount($data->amount)}} {{$data->currency}}</h4>
                            </div>
                        </div>
                        <p>@lang('TO:') <span id="myParagraph">{{ $data->sendto }}</span></p>
                        <button class="btn-custom w-100" onclick="copyText()" id="btn-confirm">@lang('Copy')</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('script')
    <script>
        'use strict'
        function copyText() {
            var textToCopy = document.getElementById("myParagraph").innerText;
            var tempInput = document.createElement("textarea");
            tempInput.value = textToCopy;
            document.body.appendChild(tempInput);
            tempInput.select();
            document.execCommand("copy");
            document.body.removeChild(tempInput);
            Notiflix.Notify.Success("Text copied to clipboard: " + textToCopy);
        }
    </script>
@endpush
