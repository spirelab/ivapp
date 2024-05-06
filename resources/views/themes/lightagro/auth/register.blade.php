@extends($theme.'layouts.app')
@section('title',__('Register'))


@section('content')
    <!-- login section -->
    <section class="login-section">
        <div class="container">
            <div class="row justify-content-center align-items-center">
                <div class="col-lg-7">
                    <div class="register-form-wrapper">
                        <form action="{{ route('register') }}" method="post">
                            @csrf
                            <div class="row g-4">
                                <div class="col-12">
                                    <h4>@lang('Create An Account')</h4>
                                </div>

                                @if(session()->get('sponsor') != null)
                                    <div class="col-md-12 form-floating">
                                        <input type="text" name="sponsor" id="sponsor" class="form-control" placeholder="{{trans('Sponsor By') }}" value="{{session()->get('sponsor')}}" readonly autocomplete="off"/>
                                        <label for="fname">@lang('Sponsor')</label>
                                    </div>
                                @endif

                                <div class="col-md-6 form-floating">
                                    <input type="text" name="firstname" class="form-control" value="{{old('firstname')}}" placeholder="@lang('First Name')" autocomplete="off"/>
                                    <label for="lname">@lang('Frist Name')</label>
                                    @error('firstname')<span class="text-danger mt-1">@lang($message)</span>@enderror
                                </div>

                                <div class="col-md-6 form-floating">
                                    <input type="text" name="lastname" class="form-control" value="{{old('lastname')}}" placeholder="@lang('Last Name')" autocomplete="off"/>
                                    <label for="lname">@lang('Last Name')</label>
                                    @error('lastname')<span class="text-danger mt-1">@lang($message)</span>@enderror
                                </div>

                                <div class="col-md-6">
                                    @php
                                        $country_code = (string) @getIpInfo()['code'] ?: null;
                                        $myCollection = collect(config('country'))->map(function($row) {
                                            return collect($row);
                                        });
                                        $countries = $myCollection->sortBy('code');
                                    @endphp

                                    <select class="form-select country_code dialCode-change" name="phone_code" id="countrycode" aria-label="Floating label select example">
                                        @foreach(config('country') as $value)
                                            <option value="{{$value['phone_code']}}"
                                                    data-name="{{$value['name']}}"
                                                    data-code="{{$value['code']}}"
                                                {{$country_code == $value['code'] ? 'selected' : ''}}
                                            > {{$value['name']}} ({{$value['phone_code']}})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6 form-floating">
                                    <input type="text" name="phone" class="form-control dialcode-set" id="phone" value="{{old('phone')}}" placeholder="@lang('Phone Number')"/>
                                    <label for="phone">@lang('Phone Number')</label>
                                    @error('phone')
                                    <span class="text-danger mt-1">@lang($message)</span>
                                    @enderror
                                </div>

                                <div class="col-md-6 form-floating">
                                    <input type="text" name="username" class="form-control" value="{{old('username')}}" placeholder="@lang('Username')" autocomplete="off"/>
                                    <label for="username">@lang('Username')</label>
                                    @error('username')<span class="text-danger mt-1">@lang($message)</span>@enderror
                                </div>

                                <input type="hidden" name="country_code" value="{{old('country_code')}}" class="text-dark">

                                <div class="col-md-6 form-floating">
                                    <input type="text" name="email" class="form-control" value="{{old('email')}}" placeholder="@lang('Email Address')" autocomplete="off"/>
                                    <label for="email">@lang('Email address')</label>
                                    @error('email')<span class="text-danger mt-1">@lang($message)</span>@enderror
                                </div>

                                <div class="col-md-6 form-floating">
                                    <input type="password" name="password" id="id_password" class="form-control" placeholder="@lang('Password')"/>
                                    <label for="id_password">@lang('Password')</label>
                                    @error('password')<span class="text-danger mt-1">@lang($message)</span>@enderror
                                </div>

                                <div class="col-md-6 form-floating">
                                    <input type="password" name="password_confirmation" id="id_password" class="form-control" placeholder="@lang('Confirm Password')"/>
                                    <label for="id_password">@lang('Confirm Password')</label>
                                </div>

                                @if(basicControl()->reCaptcha_status_registration)
                                    <div class="col-md-6 box mb-4 form-group">
                                        {!! NoCaptcha::renderJs(session()->get('trans')) !!}
                                        {!! NoCaptcha::display($basic->theme == 'deepblack' ? ['data-theme' => 'dark'] : []) !!}
                                        @error('g-recaptcha-response')
                                        <span class="text-danger mt-1">@lang($message)</span>
                                        @enderror
                                    </div>
                                @endif

                                <div class="col-12">
                                    <div class="links">
                                        <div class="form-check">
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                value=""
                                                id="flexCheckDefault"
                                            />
                                            <label class="form-check-label" for="flexCheckDefault">
                                                @lang('I Agree with the Terms & conditions')
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button class="btn-custom w-100" type="submit">@lang('Create Account')</button>
                            <div class="bottom">
                                @lang('Already have an account?')
                                <a href="{{ route('login') }}">@lang('Login here')</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection


@push('script')
    <script>
        "use strict";
        $(document).ready(function () {
            setDialCode();
            $(document).on('change', '.dialCode-change', function () {
                setDialCode();
            });
            function setDialCode() {
                let currency = $('.dialCode-change').val();
                $('.dialcode-set').val(currency);
            }
        });

    </script>
@endpush
