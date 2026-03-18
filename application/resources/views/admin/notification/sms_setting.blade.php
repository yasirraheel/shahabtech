@extends('admin.layouts.app')
@section('panel')
    <div class="row gy-4">

        <div class="col-xxl-3 col-xl-3 col-lg-12">
            @include('admin.components.navigate_sidebar')
        </div>

        <div class="col-xxl-9 col-xl-9 col-lg-12 mb-30">
            <div class="row">
                <div class="col">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link"
                                href="{{ route('admin.setting.notification.templates') }}">@lang('All Templates')</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"
                                href="{{ route('admin.setting.notification.global') }}">@lang('Global Template')</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.setting.notification.email') }}">@lang('Email Config')
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active"
                                href="{{ route('admin.setting.notification.sms') }}">@lang('SMS Config')
                            </a>
                        </li>
                    </ul>
                </div>
            </div>


            <div class="col-md-12">
                <div class="card p-16 br--solid radius--base bg--white">
                    <form action="" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>@lang('Sms Send Method')</label>
                            <select name="sms_method" class="form-control form-select">
                                <option value="nexmo" @if (isset($general->sms_config?->name) && $general->sms_config?->name == 'nexmo') selected @endif>
                                    @lang('Nexmo')</option>
                                <option value="twilio" @if (isset($general->sms_config?->name) && $general->sms_config?->name == 'twilio') selected @endif>
                                    @lang('Twilio')</option>
                                <option value="custom" @if (isset($general->sms_config?->name) && $general->sms_config?->name == 'custom') selected @endif>
                                    @lang('Custom API')</option>
                            </select>
                        </div>

                        <div class="row mt-4 d-none configForm" id="clickatell">
                            <div class="col-md-12">
                                <h6 class="mb-2">@lang('Clickatell Configuration')</h6>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="fw--500">@lang('API Key') </label>
                                    <input type="text" class="form-control" placeholder="@lang('API Key')"
                                        name="clickatell_api_key"
                                        value="{{ $general->sms_config?->clickatell?->api_key ?? '' }}" />
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4 d-none configForm" id="nexmo">
                            <div class="col-md-12">
                                <h6 class="mb-4 fw--500">@lang('Nexmo Configuration')</h6>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="">@lang('API Key') </label>
                                    <input type="text" class="form-control" placeholder="@lang('API Key')" name="nexmo_api_key" value="{{ $general->sms_config?->nexmo?->api_key ?? '' }}" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="">@lang('API Secret') </label>
                                    <input type="text" class="form-control" placeholder="@lang('API Secret')" name="nexmo_api_secret" value="{{ $general->sms_config?->nexmo?->api_secret ?? '' }}" />
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4 d-none configForm" id="twilio">
                            <div class="col-md-12">
                                <h6 class="mb-4 fw--500">@lang('Twilio Configuration')</h6>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="">@lang('Account SID') </label>
                                    <input type="text" class="form-control" placeholder="@lang('Account SID')" name="account_sid" value="{{ $general->sms_config?->twilio?->account_sid ?? '' }}" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="">@lang('Auth Token') </label>
                                    <input type="text" class="form-control" placeholder="@lang('Auth Token')" name="auth_token" value="{{ $general->sms_config?->twilio?->auth_token ?? '' }}" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="">@lang('From Number') </label>
                                    <input type="text" class="form-control" placeholder="@lang('From Number')" name="from" value="{{ $general->sms_config?->twilio?->from ?? '' }}" />
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4 d-none configForm" id="custom">
                            <div class="col-md-12">
                                <h6 class="mb-4 fw--500">@lang('Custom API')</h6>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="">@lang('API URL') </label>
                                    <div class="input-group">
                                        <span class="input-group-text api--methods">
                                            <select name="custom_api_method" class="method-select form-control form-select">
                                                <option value="get">@lang('GET')</option>
                                                <option value="post">@lang('POST')</option>
                                            </select>
                                        </span>
                                        <input type="text" class="form-control" name="custom_api_url" value="{{ $general->sms_config?->custom->url ?? '' }}" placeholder="@lang('API URL')" />
                                    </div>
                                </div>
                            </div>
                            <hr class="my-4">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-md-12 d-flex flex-wrap gap-3 mb-4">
                                        <h6>Short Code:</h6>
                                        <ul class="d-flex flex-wrap gap-3">
                                            <li>
                                                <span class="short-codes">@{{ message }}
                                                    <span class="copy-icon"><i class="fa-regular fa-copy"></i></span>
                                                </span>
                                            </li>

                                            <li>
                                                <span class="short-codes">@{{ number }}
                                                    <span class="copy-icon"><i class="fa-regular fa-copy"></i></span>
                                                </span>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="card mb-3 dyna-card">
                                            <div class="card-header d-flex justify-content-between">
                                                <h5 class="">@lang('Headers')</h5>
                                                <button type="button"
                                                    class="btn btn--primary btn-sm float-right addHeader"><i class="fa-solid fa-plus"></i> @lang('Add') </button>
                                            </div>
                                            <div class="card-body">
                                                <div class="headerFields">
                                                    @for ($i = 0; $i < count($general->sms_config->custom->headers->name); $i++)
                                                        <div class="row gy-4 mt-3">
                                                            <div class="col-md-5">
                                                                <input type="text" name="custom_header_name[]"
                                                                    class="form-control"
                                                                    value="{{ $general->sms_config?->custom?->headers?->name[$i] ?? '' }}"
                                                                    placeholder="@lang('Headers Name')">
                                                            </div>
                                                            <div class="col-md-5 col-10">
                                                                <input type="text" name="custom_header_value[]"
                                                                    class="form-control"
                                                                    value="{{ $general->sms_config?->custom?->headers?->value[$i] ?? '' }}"
                                                                    placeholder="@lang('Headers Value')">
                                                            </div>
                                                            <div class="col-md-2 col-2">
                                                                <button type="button"
                                                                    class="btn btn--danger removeHeader h-100"><i class="fa-solid fa-xmark"></i></button>
                                                            </div>
                                                        </div>
                                                    @endfor
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card mb-3 dyna-card">
                                            <div class="card-header d-flex justify-content-between">
                                                <h5 class="">@lang('Body')</h5>
                                                <button type="button" class="btn btn--primary btn-sm float-right addBody"><i class="fa-solid fa-plus"></i> @lang('Add') </button>
                                            </div>
                                            <div class="card-body">
                                                <div class="bodyFields">
                                                    @for ($i = 0; $i < count($general->sms_config->custom->body->name); $i++)
                                                        <div class="row gy-4 mt-3">
                                                            <div class="col-md-5">
                                                                <input type="text" name="custom_body_name[]" class="form-control" value="{{ $general->sms_config?->custom?->body?->name[$i] ?? ''}}" placeholder="@lang('Body Name')">
                                                            </div>
                                                            <div class="col-md-5 col-10">
                                                                <input type="text" name="custom_body_value[]" value="{{ $general->sms_config?->custom?->body?->value[$i] ?? '' }}" class="form-control" placeholder="@lang('Body Value')">
                                                            </div>
                                                            <div class="col-md-2 col-2">
                                                                <button type="button" class="btn btn--danger btn-block removeBody h-100"><i class="fa-solid fa-xmark"></i></button>
                                                            </div>
                                                        </div>
                                                    @endfor
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn--primary">@lang('Save Changes')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    {{-- TEST MAIL MODAL --}}
    <div id="testSMSModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Test SMS Setup')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <form action="{{ route('admin.setting.notification.sms.test') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>@lang('Sent to') </label>
                                    <input type="text" name="mobile" class="form-control"
                                        placeholder="@lang('Mobile')">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary">@lang('Send')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


@push('breadcrumb-plugins')
    <button type="button" data-bs-target="#testSMSModal" data-bs-toggle="modal" class="btn btn--primary btn-sm">@lang('Test SMS')</button>
@endpush

@push('style')
    <style>
        .method-select {
            padding: 2px 7px;
        }
    </style>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";

            var method = '{{ $general->sms_config->name ?? '' }}';

            if (!method) {
                method = 'clickatell';
            }

            smsMethod(method);
            $('select[name=sms_method]').on('change', function() {
                var method = $(this).val();
                smsMethod(method);
            });

            function smsMethod(method) {
                $('.configForm').addClass('d-none');
                if (method != 'php') {
                    $(`#${method}`).removeClass('d-none');
                }
            }

            $('.addHeader').on('click', function() {
                var html = `
                    <div class="row mt-3">
                        <div class="col-md-5">
                            <input type="text" name="custom_header_name[]" class="form-control" placeholder="@lang('Headers Name')">
                        </div>
                        <div class="col-md-5">
                            <input type="text" name="custom_header_value[]" class="form-control" placeholder="@lang('Headers Value')">
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn--danger btn-block removeHeader h-100"><i class="fa-solid fa-xmark"></i></button>
                        </div>
                    </div>
                `;
                $('.headerFields').append(html);

            })
            $(document).on('click', '.removeHeader', function() {
                $(this).closest('.row').remove();
            })

            $('.addBody').on('click', function() {
                var html = `
                    <div class="row mt-3">
                        <div class="col-md-5">
                            <input type="text" name="custom_body_name[]" class="form-control" placeholder="@lang('Body Name')">
                        </div>
                        <div class="col-md-5">
                            <input type="text" name="custom_body_value[]" class="form-control" placeholder="@lang('Body Value')">
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn--danger btn-block removeBody h-100"><i class="fa-solid fa-xmark"></i></button>
                        </div>
                    </div>
                `;
                $('.bodyFields').append(html);

            })
            $(document).on('click', '.removeBody', function() {
                $(this).closest('.row').remove();
            })

            $('select[name=custom_api_method]').val('{{ $general->sms_config?->custom?->method ?? '' }}');

        })(jQuery);
    </script>
@endpush
