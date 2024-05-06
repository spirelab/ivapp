@extends($theme.'layouts.app')
@section('title',trans('Plan'))

@section('content')
    @include($theme.'sections.investment')
    @include($theme.'sections.why-chose-us')
    @if(basicControl()->top_investor)
        @include($theme.'sections.investor')
    @endif
    @if(basicControl()->deposit_withdrawals)
        @include($theme.'sections.deposit-withdraw')
    @endif
    @include($theme.'sections.testimonial')
    @include($theme.'sections.faq')
    @include($theme.'sections.we-accept')
@endsection

