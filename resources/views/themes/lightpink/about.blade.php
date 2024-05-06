@extends($theme.'layouts.app')
@section('title',trans('About Us'))

@section('content')
    @include($theme.'sections.about-us')
    @include($theme.'sections.feature')
    @include($theme.'sections.testimonial')
    @include($theme.'sections.why-chose-us')
    @include($theme.'sections.how-it-work')
    @if(basicControl()->top_investor)
        @include($theme.'sections.investor')
    @endif
    @include($theme.'sections.faq')
    @include($theme.'sections.we-accept')
@endsection
