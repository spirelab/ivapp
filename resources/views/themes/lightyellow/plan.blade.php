{{-- @extends($extend_blade) --}}
@extends($theme.'layouts.app')
@section('title',trans('Plan'))

@section('content')
    @include($theme.'sections.investment')
    @include($theme.'sections.why-chose-us')
    @if(basicControl()->deposit_withdrawals)
        @include($theme.'sections.deposit-withdraw')
    @endif
    @include($theme.'sections.faq')
@endsection

