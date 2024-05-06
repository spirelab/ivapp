@extends('admin.layouts.app')
@section('title')
    @lang('App Setting')
@endsection
@section('content')

    <div class="card card-primary m-0 m-md-4 my-4 m-md-0 shadow">
        <div class="card-body">

            <form method="post" action="" class="needs-validation base-form">
                @csrf
                <div class="row">
                    <div class="form-group col-md-4">
                        <label class="font-weight-bold">@lang('APP COLOR')</label>
                        <input type="color" name="app_color"
                               value="{{ old('app_color') ?? $control->app_color ?? '#6777ef' }}"
                               required="required" class="form-control ">
                        @error('app_color')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-sm-4 col-12">
                        <label class="font-weight-bold">@lang('APP VERSION')</label>
                        <input type="text" name="app_version"
                               value="{{ old('app_version') ?? $control->app_version ?? '1.1.0' }}"
                               required="required" class="form-control ">

                        @error('app_version')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-sm-4 col-12">
                        <label class="font-weight-bold">@lang('APP BUILD')</label>
                        <input type="text" name="app_build"
                               value="{{ old('app_build') ?? $control->app_build ?? '25,26,27' }}"
                               required="required" class="form-control ">

                        @error('app_build')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>


                    <div class="form-group col-sm-6 col-md-4 col-lg-3 ">
                        <label class="text-dark">@lang('Is Major Version')</label>
                        <div class="custom-switch-btn">
                            <input type='hidden' value='1' name='is_major'>
                            <input type="checkbox" name="is_major" class="custom-switch-checkbox"
                                   id="is_major"
                                   value="0" {{($control->is_major == 0) ? 'checked' : ''}} >
                            <label class="custom-switch-checkbox-label" for="is_major">
                                <span class="custom-switch-checkbox-inner"></span>
                                <span class="custom-switch-checkbox-switch"></span>
                            </label>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn waves-effect waves-light btn-rounded btn-primary btn-block mt-3"><span><i
                            class="fas fa-save pr-2"></i> @lang('Save Changes')</span></button>
            </form>
        </div>
    </div>
@endsection

