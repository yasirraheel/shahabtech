@extends('admin.layouts.app')
@section('panel')
    <div class="row gy-4">
        <div class="col-xxl-6">
            <div class="card br--solid radius--base p-16">
                <h5 class="card-title mb-4 border-bottom pb-3">@lang('Profile Information')</h5>
                <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-5">
                            <x-image-uploader name="image" :imagePath="getImage(getFilePath('adminProfile').'/'. $admin->image,getFileSize('adminProfile'))" :size="getFileSize('adminProfile')" :isImage="true" class="w-100" id="uploadLogo3" :required="false" />
                        </div>


                        <div class="col-md-7">
                            <div class="form-group ">
                                <label>@lang('Name')</label>
                                <input class="form-control" type="text" name="name" value="{{ $admin->name }}"
                                    required>
                            </div>
                            <div class="form-group">
                                <label>@lang('Email')</label>
                                <input class="form-control" type="email" name="email" value="{{ $admin->email }}"
                                    required>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn--primary">@lang('Save Changes')</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </div>
        <div class="col-xxl-6">
            <div class="card br--solid radius--base p-16">
                <h5 class="card-title mb-4 border-bottom pb-3">@lang('Change Password')</h5>
                <form action="{{ route('admin.password.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label>@lang('New Password')</label>
                        <div class="input-group">
                            <input id="admin-new-password" class="form-control" type="password" name="password" required autocomplete="new-password">
                            <button type="button" class="input-group-text password-toggle" data-target="#admin-new-password"
                                aria-label="@lang('Show password')" title="@lang('Show password')">
                                <i class="fas fa-eye-slash"></i>
                            </button>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>@lang('Confirm Password')</label>
                        <div class="input-group">
                            <input id="admin-confirm-password" class="form-control" type="password" name="password_confirmation" required autocomplete="new-password">
                            <button type="button" class="input-group-text password-toggle" data-target="#admin-confirm-password"
                                aria-label="@lang('Show password')" title="@lang('Show password')">
                                <i class="fas fa-eye-slash"></i>
                            </button>
                        </div>
                    </div>
                    <button type="submit" class="btn btn--primary float-end">@lang('Change Password')</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        (function ($) {
            'use strict';

            $('.password-toggle').on('click', function () {
                const button = $(this);
                const input = $(button.data('target'));
                const icon = button.find('i');
                const isPassword = input.attr('type') === 'password';

                input.attr('type', isPassword ? 'text' : 'password');
                icon.toggleClass('fa-eye-slash', !isPassword);
                icon.toggleClass('fa-eye', isPassword);
                button.attr('aria-label', isPassword ? '@lang('Hide password')' : '@lang('Show password')');
                button.attr('title', isPassword ? '@lang('Hide password')' : '@lang('Show password')');
            });
        })(jQuery);
    </script>
@endpush

