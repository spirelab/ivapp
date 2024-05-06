@extends($theme.'layouts.user')
@section('title')
    {{ 'Pay with '.optional($order->gateway)->name ?? '' }}
@endsection


@section('contents')
<section class="transaction-history mt-5">
    <div class="container-fluid">
         <div class="row">
                <div class="col">
                     <div class="header-text-full">
                            <h3 class="ms-3">{{ 'Pay with '.optional($order->gateway)->name ?? '' }}</h3>
                     </div>
                </div>
         </div>

         <div class="row">
                <div class="col">
                    <div class="row justify-content-center">

                        <div class="col-md-8">
                            <div class="card secbg">
                                <div class="card-body text-center bg-light">
                                    <h3 class="card-title">@lang('Payment Preview')</h3>

                                    <h4> @lang('PLEASE SEND EXACTLY') <span
                                            class="text-success"> {{ getAmount($data->amount) }}</span> {{$data->currency}}
                                    </h4>
                                    <h5>@lang('TO') <span class="text-success"> {{ $data->sendto }}</span></h5>
                                    <img src="{{$data->img}}" alt="..">
                                    <h4 class="text-color font-weight-bold mt-3">@lang('SCAN TO SEND')</h4>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
         </div>
    </div>
</section>


@endsection

