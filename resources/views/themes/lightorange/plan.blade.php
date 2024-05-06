{{-- @extends($extend_blade) --}}
@extends($theme.'layouts.app')
@section('title',trans('Plan'))

@section('content')
    @include($theme.'sections.investment')
    @if(basicControl()->deposit_withdrawals)
        @include($theme.'sections.deposit-withdraw')
    @endif

    @include($theme.'sections.we-accept')
    @include($theme.'sections.faq')

@endsection

