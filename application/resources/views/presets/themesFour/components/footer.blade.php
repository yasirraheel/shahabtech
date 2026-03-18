@php
    $imenu = App\Models\Menu::where('code', 'important_link')->latest()->first();
    $importantLinks = $imenu->items()->where('tempname', activeTemplate())->where('status', Status::ENABLE)->get();
    $cmenu = App\Models\Menu::where('code', 'company_link')->latest()->first();
    $companyLinks = $cmenu->items()->where('tempname', activeTemplate())->where('status', Status::ENABLE)->get();
    $contact = getContent('contact_us.content',true);
    $socialIcons = getContent('social_icon.element',false);
@endphp

<!-- ==================== Footer Start ==================== -->
<footer class="footer-area pt-120">
    <div class="container">
        <div class="row justify-content-center g-5">
            <div class="col-xl-3 col-sm-6">
                <div class="footer-item">
                    <div class="footer-item__logo">
                        <a href="{{route('home')}}">
                            <img src="{{ siteLogo() }}" alt="{{ gs('site_name') }}">
                        </a>
                    </div>
                    <p class="footer-item__desc">
                        @if (strlen(__(strip_tags($contact?->data_values?->short_description))) > 60)
                            {{ substr(__(strip_tags($contact?->data_values?->short_description)), 0, 60) . '...' }}
                        @else
                            {{__(strip_tags($contact?->data_values?->short_description)) }}
                        @endif
                    </p>
                    <ul class="social-list mt-3">
                        @foreach($socialIcons as $item)
                        <li class="social-list__item"><a href="{{$item?->data_values?->url}}" class="social-list__link">@php echo $item?->data_values?->social_icon; @endphp</a> </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="col-xl-2 col-sm-6">
                <div class="footer-item">
                    <h5 class="footer-item__title">@lang('Important Links')</h5>
                    <ul class="footer-menu">
                        @if($importantLinks)
                            @foreach($importantLinks as $k => $data)
                                @if($data->link_type == 2)
                                    <li class="footer-menu__item">
                                        <a href="{{ $data->url ?? '' }}" target="_blank" class="footer-menu__link">{{__($data->title)}}</a>
                                    </li>
                                @else
                                    <li class="footer-menu__item">
                                        <a href="{{route('pages',[$data->url])}}" class="footer-menu__link">{{__($data->title)}}</a>
                                    </li>
                                @endif
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>

            <div class="col-xl-2 col-sm-6">
                <div class="footer-item">
                    <h5 class="footer-item__title">@lang('Company Links')</h5>
                    <ul class="footer-menu">
                        @if($companyLinks)
                            @foreach($companyLinks as $k => $data)
                                @if($data->link_type == 2)
                                    <li class="footer-menu__item">
                                        <a href="{{ $data->url ?? '' }}" target="_blank" class="footer-menu__link">{{__($data->title)}}</a>
                                    </li>
                                @else
                                    <li class="footer-menu__item">
                                        <a href="{{route('pages',[$data->url])}}" class="footer-menu__link">{{__($data->title)}}</a>
                                    </li>
                                @endif
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>

            <div class="col-xl-2 col-sm-6">
                <div class="footer-item">
                    <h5 class="footer-item__title">@lang('Address')</h5>
                    <ul class="footer-menu">
                        <li class="footer-menu__item"><a href="tel:{{$contact?->data_values?->contact_number}}">{{$contact?->data_values?->contact_number}}</a></li>
                        <li class="footer-menu__item"><a href="mailto:{{$contact?->data_values?->email_address}}">{{$contact?->data_values?->email_address}}</a></li>
                        <li class="footer-menu__item">
                            {{__($contact?->data_values?->contact_details)}}
                        </li>
                    </ul>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6">
                <div class="footer-item">
                    <h5 class="footer-item__title">@lang('Newsletter')</h5>
                    <p class="mb-2">@lang('Subscribe our latest update')</p>
                    <form action="{{route('subscribe')}}" method="POST">
                        @csrf
                        <div class="form-group">
                            <input type="text" class="form--control" name="email" placeholder="@lang('Email')">
                            <button type="submit"><i class="fas fa-paper-plane"></i></button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
    <div class="copyright">
        @php echo $contact?->data_values?->website_footer; @endphp
    </div>
</footer>
<!-- ==================== Footer End ==================== -->
