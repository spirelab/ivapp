@extends($theme.'layouts.app')
@section('title',__('sms verification'))

@section('content')
    <!-- reset password  -->
    <section class="login-section">
        <div class="container">
            <div class="login-wrapper">
                <div class="row justify-content-center align-items-center">
                    <div class="col-lg-6">
                        <div class="form-wrapper">
                            @if (session('status'))
                                <div class="alert alert-warning alert-dismissible fade show w-100" role="alert">
                                    {{ trans(session('status')) }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                </div>
                            @endif

                            <form action="{{ route('user.smsVerify') }}" method="post">
                                @csrf
                                <div class="row g-4">
                                    <div class="col-12">
                                        <h4>@lang('Sms Verification Code')</h4>
                                    </div>
                                    <div class="form-floating">
                                        <input type="text" name="code" class="form-control" value="{{old('code')}}" placeholder="@lang('Enter Your Code')" autocomplete="off"/>
                                        <label for="floatingInput">@lang('Enter Your Code')</label>
                                        @error('code')<span class="text-danger mt-1">{{ trans($message) }}</span>@enderror
                                        @error('error')<span class="text-danger mt-1">{{ trans($message) }}</span>@enderror
                                    </div>

                                </div>
                                <button class="btn-custom w-100"
                                        type="submit">@lang('Submit')</button>

                                <div class="bottom">
                                    @lang("Didn't get Code? Click to")
                                    <a href="{{route('user.resendCode')}}?type=mobile">@lang('Resend code')</a>
                                    @error('resend')
                                    <p class="text-danger mt-1">{{ trans($message) }}</p>
                                    @enderror
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
