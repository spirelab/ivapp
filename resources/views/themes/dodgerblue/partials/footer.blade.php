<!-- footer section -->
<footer class="footer-section">
    <div class="overlay">
        <div class="container">
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
                                    <a href="{{@$data->content->contentMedia->description->link}}" target="_blank">
                                        <i class="{{@$data->content->contentMedia->description->icon}}"></i>
                                    </a>
                                @endforeach
                            </div>
                        @endif

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

                <div class="col-lg-3 col-md-6 {{(session()->get('rtl') == 1) ? 'pe-lg-5': 'ps-lg-5'}}">
                    <div class="footer-box">
                        <h4>{{trans('Useful Links')}}</h4>
                        <ul>
                            <li>
                                <a href="{{route('home')}}">@lang('Home')</a>
                            </li>
                            <li>
                                <a href="{{route('about')}}">@lang('About')</a>
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

                @if(isset($templates['news-letter'][0]) && $newsLetter = $templates['news-letter'][0])
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-box">
                            <h4>@lang($newsLetter->description->title)</h4>
                            <form action="{{route('subscribe')}}" method="post">
                                @csrf
                                <div class="input-box">
                                    <input type="email" name="email" class="form-control"
                                           placeholder="@lang('Email Address')" autocomplete="off"/>
                                    <button type="submit" class="btn-action-icon"><i class="fas fa-paper-plane"></i>
                                    </button>
                                </div>
                                <p class="mt-3"><i
                                        class="fa-duotone fa-circle-check"></i> @lang('I agree to all terms and policies')
                                </p>
                            </form>
                        </div>
                    </div>
                @endif
            </div>


            <div class="d-flex copyright justify-content-between align-items-center">
                <div>
                    <span> @lang('Copyright') &copy; {{date('Y')}} @lang($basic->site_title) @lang('All Rights Reserved') </span>
                </div>

                @php
                    $languageArray = json_decode($languages, true);
                @endphp

                <div class="language-dropdown-items">
                    <button type="button" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ asset($themeTrue.'img/flag/usa.png') }}" alt="">
                        <span>@lang('English')</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-start">
                        @foreach ($languageArray as $key => $lang)
                            <li>
                                <a href="{{route('language',$key)}}" class="dropdown-item">
                                    <span
                                        class="flag-icon flag-icon-{{strtolower($key)}}"></span>
                                    <span>{{$lang}}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

    </div>
    </div>
</footer>

