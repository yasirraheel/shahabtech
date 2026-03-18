@extends('admin.layouts.master')
@section('content')
<div class="login_area">
    <div class="login">
        <div class="login__header">
            <h2>@lang('Verification')</h2>
            <p>@lang('Please enter the verification code')</p>
        </div>
        <div class="login__body w-100">
            <form class="form w-100" action="{{ route('admin.password.verify.code') }}" method="POST">
                @csrf
                <div class="form-row">
                    <span class="fas fa-envelope mb-2" aria-hidden="true"></span>
                    <label class="form-label mb-2" for="input">@lang('Verification Code')</label>
                    <div class="verification-code">
                        <input type="text" name="code" class="overflow-hidden" autocomplete="off">
                        <div class="boxes">
                            <span>-</span>
                            <span>-</span>
                            <span>-</span>
                            <span>-</span>
                            <span>-</span>
                            <span>-</span>
                        </div>
                    </div>
                </div>
                <div class="form-row my-2">
                    <a href="{{ route('admin.password.reset') }}" class="forget-text">@lang('Try to send again')</a>
                </div>
                <div class="form-row button-login">
                    <button type="submit" class="btn btn-login btn--primary sign-in">@lang('Verify')</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('style')
    <link rel="stylesheet" href="{{asset('assets/admin/css/auth.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/verification_code.css') }}">
    <style>
        .verification-code::after {
            position: absolute;
            content: '';
            right: -37px;
            width: 35px;
            height: 50px;
            background: transparent;
            z-index: 2;
        }
    </style>
@endpush
@push('script')
<script>
    (function ($) {
        'use strict';
        $('[name=code]').on('input', function () {
            $(this).val(function (i, val) {
                if (val.length >= 6) {
                    $('form').find('button[type=submit]').html('<i class="fa-solid fa-spinner fa-spin"></i>');
                    $('form').find('button[type=submit]').removeClass('disabled');
                    $('form')[0].submit();
                } else {
                    $('form').find('button[type=submit]').addClass('disabled');
                }
                if (val.length > 6) {
                    return val.substring(0, val.length - 1);
                }
                return val;
            });
            for (let index = $(this).val().length; index >= 0; index--) {
                $($('.boxes span')[index]).html('');
            }
        });


    })(jQuery)
</script>
@endpush
