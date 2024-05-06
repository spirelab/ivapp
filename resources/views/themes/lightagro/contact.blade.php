@extends($theme.'layouts.app')
@section('title',trans($title))

@section('content')
    <!-- contact section -->
    <div class="contact-section">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="header-text">
                        <h5>@lang($contact->heading)</h5>
                        <h3>@lang(@$contact->sub_heading)</h3>
                        <p>
                            @lang(@$contact->short_details)
                        </p>
                    </div>
                    @if(isset($contentDetails['social']))
                        <div class="social-links">
                            <h5 class="">@lang('Follow our social media')</h5>
                            <div>
                                @foreach($contentDetails['social'] as $data)
                                    <a href="{{@$data->content->contentMedia->description->link}}" class="facebook">
                                        <i class="{{@$data->content->contentMedia->description->icon}}"></i>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <div class="col-lg-2"></div>
                <div class="col-lg-6">
                    <div class="form-box">
                        <form action="{{route('contact.send')}}" method="post">
                            @csrf
                            <div class="row g-4">
                                <div class="input-box col-md-6">
                                    <input class="form-control" type="text" name="name" value="{{old('name')}}"
                                           placeholder="@lang('Full name')"/>
                                    @error('name')
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                                <div class="input-box col-md-6">
                                    <input class="form-control" type="email" name="email" value="{{old('email')}}"
                                           placeholder="@lang('Email address')"/>
                                    @error('email')
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                                <div class="input-box col-12">
                                    <input type="text" name="subject" value="{{old('subject')}}" class="form-control"
                                           placeholder="@lang('Subject')"/>
                                    @error('subject')
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                                <div class="input-box col-12">
                                    <textarea class="form-control" name="message" cols="30" rows="3"
                                              placeholder="@lang('Your message')">{{old('message')}}</textarea>
                                    @error('message')
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                                <div class="input-box col-12">
                                    <button class="btn-custom w-100" type="submit">@lang('Send Message')</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
