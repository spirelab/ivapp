@extends($theme.'layouts.app')
@section('title',trans('Plan'))

@section('content')
    @include($theme.'sections.investment')
    @if(basicControl()->deposit_withdrawals)
        @include($theme.'sections.deposit-withdraw')
    @endif
    @include($theme.'sections.faq')
    @include($theme.'sections.we-accept')
@endsection

