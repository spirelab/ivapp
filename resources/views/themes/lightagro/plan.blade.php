{{-- @extends($extend_blade) --}}
@extends($theme.'layouts.app')
@section('title',trans('Investment Plan'))

@section('content')
    @include($theme.'sections.investment')
@endsection

