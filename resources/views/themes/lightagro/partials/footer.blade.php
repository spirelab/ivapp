<!-- footer section -->
<footer class="footer-section">
    <div class="overlay">
        <div class="container">
            @if(isset($templates['news-letter'][0]) && $newsLetter = $templates['news-letter'][0])
                <div class="row mb-5 justify-content-center">
                    <div class="col-lg-6">
                        <form action="{{route('subscribe')}}" method="post">
                            @csrf
                            <div class="newsletter text-center">
                                <h4>@lang(optional($newsLetter->description)->title)</h4>
                                <div class="input-group">
                                    <input type="email" class="form-control" name="email"
                                           placeholder="@lang('Email Address')"/>
                                    <button class="btn-custom" type="submit">@lang('Subscribe')</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

            <div class="row gy-5 gy-lg-0">
                <div class="col-lg-3 col-md-6">
                    <div class="footer-box">
                        <a class="navbar-brand" href="{{ route('home') }}"> <img
                                src="{{getFile(config('location.logoIcon.path').'logo.png')}}" alt=""/></a>
                        @if(isset($contactUs['contact-us'][0]) && $contact = $contactUs['contact-us'][0])
                            <p class="company-bio">
                                @lang(strip_tags(@$contact->description->footer_short_details))
                            </p>
                        @endif

                        @if(isset($contentDetails['social']))
                            <div class="social-links">
                                @foreach($contentDetails['social'] as $data)
                                    <a href="{{@$data->content->contentMedia->description->link}}" class="facebook"
                                       target="_blank">
                                        <i class="{{@$data->content->contentMedia->description->icon}}"></i>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 {{(session()->get('rtl') == 1) ? 'pe-lg-5': 'ps-lg-5'}}">
                    <div class="footer-box">
                        <h5>{{trans('Useful Links')}}</h5>
                        <ul>
                            <li>
                                <a href="{{route('home')}}">@lang('Home')</a>
                            </li>
                            <li>
                                <a href="{{route('about')}}">@lang('About')</a>
                            </li>
                            <li>
                                <a href="{{route('plan')}}">@lang('Plan')</a>
                            </li>
                            <li>
                                <a href="{{route('blog')}}">@lang('Blog')</a>
                            </li>
                            <li>
                                <a href="{{route('contact')}}">@lang('Contact')</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="footer-box">
                        <h5>@lang('OUR SERVICES')</h5>
                        @isset($contentDetails['support'])
                            <ul>
                                @foreach($contentDetails['support'] as $data)
                                    <li><a href="{{route('getLink', [slug(@$data->description->title), @$data->content_id])}}">@lang(@$data->description->title)</a></li>
                                @endforeach
                                <li><a href="{{route('faq')}}">@lang('FAQ')</a></li>
                            </ul>
                        @endisset
                    </div>
                </div>

                @if(isset($contactUs['contact-us'][0]) && $contact = $contactUs['contact-us'][0])
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-box">
                            <h4>{{trans('get in touch')}}</h4>
                            <ul>
                                <li>
                                    <span>@lang(@$contact->description->email)</span>
                                </li>

                                <li>
                                    <span>@lang(@$contact->description->phone)</span>
                                </li>

                                <li>
                                    <span>@lang(@$contact->description->address)</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                @endif
            </div>
            <div class="d-flex copyright justify-content-between align-items-center">
                <div>
                    <span> @lang('All rights reserved') &copy; {{date('Y')}} @lang('by') <a
                            href="{{ route('home') }}">@lang($basic->site_title)</a> </span>
                </div>

                @php
                    $languageArray = json_decode($languages, true);
                @endphp

                <div class="language-dropdown-items">
                    <button type="button" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <span>@lang('English')</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-start">
                        @foreach ($languageArray as $key => $lang)
                            <li>
                                <a href="{{route('language',$key)}}" class="dropdown-item">
                                    <span>{{$lang}}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>
