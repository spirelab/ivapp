@extends($theme.'layouts.app')
@section('title', trans('FAQ'))

@section('content')
    <!-- faq section -->
    @if(isset($templates['faq'][0]) && $faq = $templates['faq'][0])
        @if(0 < count($contentDetails['faq']))
            <section class="faq-section">
                <div class="container">
                    <div class="row g-4 g-lg-5 justify-content-center">
                        <div class="col-lg-4">
                            <div class="header-text">
                                <h3>@lang(@$faq->description->title)</h3>
                                <p class="mt-4 mb-5">
                                    @lang(@$faq->description->sub_title)
                                </p>
                                <div class="mail-to">
                                    @lang(@$faq->description->short_details) <br>

                                    @if(isset($templates['contact-us'][0]) && $contact = $templates['contact-us'][0])
                                        <a class="text-primary"
                                           href="mailto:{{ @$contact->description->email }}">@lang(@$contact->description->email)</a>
                                        <i class="fa-duotone fa-question"></i>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @if(isset($contentDetails['faq']))
                            <div class="col-lg-8">
                                <div class="accordion" id="accordionExample">
                                    @foreach($contentDetails['faq'] as $k => $data)
                                        <div class="accordion-item">
                                            <h5 class="accordion-header {{(session()->get('rtl') == 1) ? 'isRtl': ''}}" id="heading{{$k++}}">
                                                <button
                                                    class="accordion-button {{($k != 1) ? 'collapsed': '' }}"
                                                    type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#collapse{{$k}}"
                                                    aria-expanded="{{($k == 1) ? 'true' : 'false'}}"
                                                    aria-controls="collapse{{$k}}"
                                                >
                                                    <span class="index">{{ $k < 10 ? '0'.$k : $k}}</span>
                                                    @lang(@$data->description->title)
                                                </button>
                                            </h5>
                                            <div
                                                id="collapse{{$k}}"
                                                class="accordion-collapse collapse {{($k == 1) ? 'show' : ''}}"
                                                aria-labelledby="heading{{$k}}"
                                                data-bs-parent="#accordionExample"
                                            >
                                                <div class="accordion-body">
                                                    @lang(@$data->description->description)
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </section>
        @endif
    @endif
@endsection
