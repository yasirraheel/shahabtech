@php
    $hmenu = App\Models\Menu::where('code', 'header_menu')->latest()->first();
    $pages = $hmenu->items()->where('tempname', activeTemplate())->where('status', Status::ENABLE)->get();
@endphp

<!-- ==================== Header End Here ==================== -->
<div class="header" id="header">
    <div class="container">
        <nav class="navbar navbar-expand-lg">
            <div class="logo-area">
                <a class="navbar-brand logo" href="{{route('home')}}"><img src="{{ siteLogo('dark') }}" alt="{{ gs('site_name')}}"><div class="logo-bg"></div></a>

            </div>
            <button class="navbar-toggler header-button" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span id="hiddenNav"><i class="las la-bars"></i></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav nav-menu mx-auto ps-lg-4 ps-0">
                    @if($pages)
                        @foreach($pages as $k => $data)
                            @if($data->link_type == 2)
                                <li class="nav-item">
                                    <a href="{{ $data->url ?? '' }}" target="_blank" class="nav-link">{{__($data->title)}}</a>
                                </li>
                            @else
                                <li class="nav-item">
                                    <a href="{{route('pages',[$data->url])}}" class="nav-link {{ route('pages',[$data->url]) == url()->current() ? 'active' : null }}">{{__($data->title)}}</a>
                                </li>
                            @endif
                        @endforeach
                    @endif
                </ul>
                <div class="nav-end d-lg-flex d-md-flex d-block align-items-center py-lg-0 py-1">
                    <div class="d-flex mx-2">
                        <div class="icon">
                            <i class="fa-solid fa-globe"></i>
                        </div>
                        <select class="select-dir langSel">
                            @foreach($languages as $language)
                            <option value="{{ $language->code }}" @if(Session::get('lang')===$language->code)
                                selected @endif>{{ __($language->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                    @auth
                    <a class="btn--base mt-2 mt-lg-0" href="{{route('user.home')}}">@lang('Dashboard')</a>
                    @else
                    <a class="btn--base mt-2 mt-lg-0" href="{{route('user.login')}}">@lang('Sign In')</a>
                    @endauth
                </div>
            </div>
        </nav>
    </div>
</div>
<!-- ==================== Header End Here ==================== -->
