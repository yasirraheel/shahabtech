@php
    $imenu = App\Models\Menu::where('code', 'important_link')->latest()->first();
    $importantLinks = $imenu->items()->where('tempname', activeTemplate())->where('status', Status::ENABLE)->get();
    $cmenu = App\Models\Menu::where('code', 'company_link')->latest()->first();
    $companyLinks = $cmenu->items()->where('tempname', activeTemplate())->where('status', Status::ENABLE)->get();
    $subscribe = getContent('subscribe.content', true);
    $contact = getContent('contact_us.content',true);
    $socialIcons = getContent('social_icon.element',false);
    $blogElements = getContent('blog.element',false,3);
    $policyPages    = getContent('policy_pages.element',false,null,true);
@endphp


<!-- ==================== Footer Start Here ==================== -->
<footer class="footer-area section-bg-light bg-img" style="background-image: url({{asset($activeTemplateTrue.'images/footer-bg.jpg')}})">
    <div class="pb-60 pt-80">
        <div class="container">
            <div class="row justify-content-center gy-5">
                <div class="col-xl-3 col-sm-6">
                    <div class="footer-item">
                        <div class="footer-item__logo">
                            <a href="{{route('home')}}" class="footer-logo-normal">
                            <img src="{{ siteLogo() }}" alt="{{gs('site_name')}}">
                            </a>
                            <a href="{{route('home')}}" class="footer-logo-dark hidden">
                            <img src="{{ siteLogo('dark') }}" alt="{{gs('site_name')}}">
                                </a>
                        </div>
                        <p class="footer-item__desc mb-3">
                            @if(strlen(__($contact?->data_values?->short_description)) >150)
                            {{substr(__($contact?->data_values?->short_description), 0,150).'...' }}
                            @else
                            {{__($contact?->data_values?->short_description)}}
                            @endif
                        </p>

                        <form action="{{route('subscribe')}}" method="POST">
                            @csrf
                            <div class="search-box footer w-100">
                                <input type="text" name="email" class="form--control" placeholder="@lang('Email')">
                                <button type="submit" class="btn btn--base btn--sm">@lang('Subscribe')</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-xl-2 col-sm-6">
                    <div class="footer-item">
                        <h5 class="footer-item__title">@lang('Userful Link')</h5>
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
                <div class="col-xl-4 col-sm-6">
                    <div class="footer-item">
                        <h5 class="footer-item__title">@lang('Latest Feed')</h5>
                        <div class="blog-sidebar">
                            @foreach($blogElements as $item)
                            <div class="latest-blog">
                                <div class="latest-blog__thumb">
                                    <a href="{{ route('blog.details', ['slug' => slug($item->data_values->title), 'id' => $item->id])}}"> <img src="{{getImage(getFilePath('blog').'/'.'thumb_' . $item?->data_values?->blog_image ?? '')}}" alt="blog-img"></a>
                                </div>
                                <div class="latest-blog__content">
                                    <h6 class="latest-blog__title"><a href="{{ route('blog.details', ['slug' => slug($item->data_values->title), 'id' => $item->id])}}">
                                        @if(strlen(__($item?->data_values?->title)) >50)
                                        {{substr(__($item?->data_values?->title), 0,50).'...' }}
                                        @else
                                        {{__($item?->data_values?->title)}}
                                        @endif
                                    </a></h6>
                                    <span class="latest-blog__date">{{showDateTime($item->created_at)}}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6">
                    <div class="footer-item">
                        <h5 class="footer-item__title">@lang('Address')</h5>
                        <ul class="footer-contact-menu">
                            <li class="footer-contact-menu__item">
                                <div class="footer-contact-menu__item-icon">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div class="footer-contact-menu__item-content">
                                    <p>{{__($contact?->data_values?->contact_details)}}</p>
                                </div>
                            </li>
                            <li class="footer-contact-menu__item">
                                <div class="footer-contact-menu__item-icon">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div class="footer-contact-menu__item-content">
                                    <p>{{$contact?->data_values?->email_address}}</p>
                                </div>
                            </li>
                            <li class="footer-contact-menu__item">
                                <div class="footer-contact-menu__item-icon">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <div class="footer-contact-menu__item-content">
                                    <p>{{$contact?->data_values?->contact_number}}</p>
                                </div>
                            </li>
                        </ul>

                        <ul class="social-list">
                            @foreach($socialIcons as $item)
                            <li class="social-list__item"><a href="{{$item->data_values->url}}" class="social-list__link" target="_blank">@php echo $item->data_values->social_icon; @endphp</a> </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
  <!-- Footer Top End-->

    <!-- bottom Footer -->
    <div class="bottom-footer section-bg py-3">
        <div class="container">
            <div class="row gy-2">
                <div class="col-lg-6 col-md-12">
                    <div class="bottom-footer-text"> @php echo $contact?->data_values?->website_footer @endphp</div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="bottom-footer-menu">
                        <ul>
                            @if($importantLinks)
                                @foreach($importantLinks as $k => $data)
                                    @if($data->link_type == 2)
                                        <li>
                                            <a href="{{ $data->url ?? '' }}" target="_blank">{{__($data->title)}}</a>
                                        </li>
                                    @else
                                        <li>
                                            <a href="{{route('pages',[$data->url])}}">{{__($data->title)}}</a>
                                        </li>
                                    @endif
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </footer>
  <!-- ==================== Footer End Here ==================== -->
