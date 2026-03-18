@extends('admin.layouts.app')
@section('panel')
    <div class="row gy-4 justify-content-start mb-none-30">
        <div class="col-xxl-3 col-xl-3 col-lg-12">
            @include('admin.components.navigate_sidebar')
        </div>

        <div class="col-xxl-9 col-xl-9 col-lg-12 mb-30">
            <form action="{{ route('admin.setting.update') }}" method="POST">
                @csrf
                <div class="row gy-4">
                    <div class="col-xxl-8 col-xl-8">
                        <div class="card bg--white br--solid radius--base p-16">
                            <h5 class="mb-3">@lang('Basic Control')</h5>

                            <div class="row gy-4 mb-4 pb-3">
                                <div class="col-md-6 col-xs-12">
                                    <label class="required"> @lang('Site Title')</label>
                                    <input class="form-control" type="text" name="site_name" required
                                        value="{{ $general->site_name }}">
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <label class="required">@lang('Currency')</label>
                                    <input class="form-control" type="text" name="cur_text" required
                                        value="{{ $general->cur_text }}">
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <label class="required">@lang('Currency Symbol')</label>
                                    <input class="form-control" type="text" name="cur_sym" required
                                        value="{{ $general->cur_sym }}">
                                </div>




                                <div class="col-md-6 col-xs-12 time-zone">
                                    <label> @lang('Timezone')</label>
                                    <select class="select2-basic form-control form-select" name="timezone">
                                        @foreach ($timezones as $key => $timezone)
                                            <option value="{{ $key }}" @selected($key == $currentTimezone)>
                                                {{ __($timezone) }}</option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="col-md-6 col-xs-12 time-zone">
                                    <label> @lang('RTL/LTR')</label>
                                    <select class="select2-basic form-control form-select" name="rtl">
                                        <option value="1" {{$general->rtl == 1? 'selected':''}}>@lang('RTL')</option>
                                        <option value="2" {{$general->rtl == 2? 'selected':''}}>@lang('LTR')</option>
                                    </select>
                                </div>


                                @if ($general->active_template == 'default')
                                <div class="col-md-6 col-xs-12">
                                    <label>@lang('Default Theme Base Color')</label>
                                    <div class="form-group color--select position-relative">
                                        <div class="colorInputWrapper">
                                            <input class="form-control colorPicker" type='text' value="{{ gs('base_color') }}">
                                            <input class="form-control colorCode" name="base_color" type="text" value="{{ gs('base_color') }}">
                                        </div>
                                    </div>
                                </div>
                                @elseif($general->active_template == 'themesTwo')
                                <div class="col-md-6 col-xs-12">
                                    <label>@lang('Theme Two Base Color')</label>
                                    <div class="form-group color--select position-relative">
                                        <div class="colorInputWrapper">
                                            <input class="form-control colorPicker" type='text' value="{{ gs('theme_two_base_color') }}">
                                            <input class="form-control colorCode" name="theme_two_base_color" type="text" value="{{ gs('theme_two_base_color') }}">
                                        </div>
                                    </div>
                                </div>
                                @elseif($general->active_template == 'themesThree')
                                <div class="col-md-6 col-xs-12">
                                    <label>@lang('Theme Three Base Color')</label>
                                    <div class="form-group color--select position-relative">
                                        <div class="colorInputWrapper">
                                            <input class="form-control colorPicker" type='text' value="{{ gs('theme_three_base_color') }}">
                                            <input class="form-control colorCode" name="theme_three_base_color" type="text" value="{{ gs('theme_three_base_color') }}">
                                        </div>
                                    </div>
                                </div>
                                @elseif($general->active_template == 'themesFour')
                                <div class="col-md-6 col-xs-12">
                                    <label>@lang('Theme Four Base Color')</label>
                                    <div class="form-group color--select position-relative">
                                        <div class="colorInputWrapper">
                                            <input class="form-control colorPicker" type='text' value="{{ gs('theme_four_base_color') }}">
                                            <input class="form-control colorCode" name="theme_four_base_color" type="text" value="{{ gs('theme_four_base_color') }}">
                                        </div>
                                    </div>
                                </div>

                                @else

                                <div class="col-md-6 col-xs-12">
                                    <label>@lang('Theme Five Base Color')</label>
                                    <div class="form-group color--select position-relative">
                                        <div class="colorInputWrapper">
                                            <input class="form-control colorPicker" type='text' value="{{ gs('theme_five_base_color') }}">
                                            <input class="form-control colorCode" name="theme_five_base_color" type="text" value="{{ gs('theme_five_base_color') }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 col-xs-12">
                                    <label>@lang('Theme Five Secondary Color')</label>
                                    <div class="form-group color--select position-relative">
                                        <div class="colorInputWrapper">
                                            <input class="form-control colorPicker" type='text' value="{{ gs('theme_five_secondary_color') }}">
                                            <input class="form-control colorCode" name="theme_five_secondary_color" type="text" value="{{ gs('theme_five_secondary_color') }}">
                                        </div>
                                    </div>
                                </div>

                                @endif




















                            </div>
                            <div class="row">
                                <div class="col text-end">
                                    <button type="submit" class="btn btn--primary">@lang('Save Changes')</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-4 col-xl-4">
                        <div class="card bg--white br--solid radius--base p-16">
                            <h5 class="mb-3">@lang('Control Panel')</h5>
                            <div class="row gy-2">
                                <div class="form-group col-12 d-flex justify-content-between align-items-center">
                                    <label class="fw--500 mb-0">@lang('User Registration')</label>
                                    <label class="switch m-0">
                                        <input type="checkbox" class="toggle-switch" name="registration" {{ $general->registration ? 'checked' : null }}>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                                <div class="form-group col-12 d-flex justify-content-between align-items-center">
                                    <label class="fw--500 mb-0">@lang('Email Verification')</label>
                                    <label class="switch m-0">
                                        <input type="checkbox" class="toggle-switch" name="ev" {{ $general->ev ? 'checked' : null }}>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                                <div class="form-group col-12 d-flex justify-content-between align-items-center">
                                    <label class="fw--500 mb-0">@lang('Email Notification')</label>
                                    <label class="switch m-0">
                                        <input type="checkbox" class="toggle-switch" name="en" {{ $general->en ? 'checked' : null }}>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                                <div class="form-group col-12 d-flex justify-content-between align-items-center">
                                    <label class="fw--500 mb-0">@lang('Mobile Verification')</label>
                                    <label class="switch m-0">
                                        <input type="checkbox" class="toggle-switch" name="sv" {{ $general->sv ? 'checked' : null }}>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                                <div class="form-group col-12 d-flex justify-content-between align-items-center">
                                    <label class="fw--500 mb-0">@lang('SMS Notification')</label>
                                    <label class="switch m-0">
                                        <input type="checkbox" class="toggle-switch" name="sn" {{ $general->sn ? 'checked' : null }}>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                                <div class="form-group col-12 d-flex justify-content-between align-items-center">
                                    <label class="fw--500 mb-0">@lang('Terms & Condition')</label>
                                    <label class="switch m-0">
                                        <input type="checkbox" class="toggle-switch" name="agree" {{ $general->agree ? 'checked' : null }}>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                                <div class="form-group col-12 d-flex justify-content-between align-items-center">
                                    <label class="fw--500 mb-0">@lang('Secure Password')</label>
                                    <label class="switch m-0">
                                        <input type="checkbox" class="toggle-switch" name="secure_password" {{ $general->secure_password ? 'checked' : null }}>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                                <div class="form-group col-12 d-flex justify-content-between align-items-center mb-0">
                                    <label class="fw--500 mb-0">@lang('Force SSL')</label>
                                    <label class="switch m-0">
                                        <input type="checkbox" class="toggle-switch" name="force_ssl" {{ $general->force_ssl ? 'checked' : null }}>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('script-lib')
    <script src="{{ asset('assets/admin/js/spectrum.js') }}"></script>
    <script src="{{asset('assets/common/js/select2.min.js')}}"></script>
@endpush

@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/spectrum.css') }}">
    <link rel="stylesheet" href="{{asset('assets/common/css/select2.min.css')}}">
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            $('.select2-basic').select2({
                dropdownParent: $('.time-zone')
            });

            $('.colorPicker').each(function() {
                let colorInput = $(this).siblings('.colorCode');
                let currentColor = colorInput.val();
                $(this).spectrum({
                    color: `#${currentColor}`,
                    showInput: true,
                    preferredFormat: "hex",
                    change: function(color) {
                        colorInput.val(color.toHex().replace(/^#/, ''));
                    }
                });
            });

            $('.colorCode').on('input', function() {
                let clr = $(this).val().trim();
                let colorPicker = $(this).siblings('.colorPicker');
                if (/^[0-9A-Fa-f]{6}$/.test(clr)) {
                    colorPicker.spectrum("set", `#${clr}`);
                }
            });

        })(jQuery);
    </script>
@endpush
