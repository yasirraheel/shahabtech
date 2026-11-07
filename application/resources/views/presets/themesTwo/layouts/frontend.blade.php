<!doctype html>
<html lang="{{ config('app.locale') }}" itemscope itemtype="http://schema.org/WebPage">
<head>
    <!-- Meta Pixel Code -->
    <script>
    !function(f,b,e,v,n,t,s)
    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};
    if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
    n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];
    s.parentNode.insertBefore(t,s)}(window, document,'script',
    'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '1120055830992464');
    fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
    src="https://www.facebook.com/tr?id=1120055830992464&ev=PageView&noscript=1"
    /></noscript>
    <!-- End Meta Pixel Code -->
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title> {{ $general->siteName(__($pageTitle)) }}</title>
    @include('includes.seo')
    <!-- Bootstrap CSS -->
    <link href="{{ asset('assets/common/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/common/css/all.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('assets/common/css/line-awesome.min.css')}}">

    <link rel="stylesheet" href="{{asset($activeTemplateTrue.'css/magnific-popup.css')}}">
    <link rel="stylesheet" href="{{asset($activeTemplateTrue.'css/custom-animation.css')}}">
    <link rel="stylesheet" href="{{asset($activeTemplateTrue.'css/slick.css')}}">
    <link rel="stylesheet" href="{{asset($activeTemplateTrue.'css/odometer.css')}}">
    <link rel="stylesheet" href="{{asset($activeTemplateTrue.'css/main.css')}}">

    @stack('style-lib')

    @stack('style')


    <link rel="stylesheet" href="{{ asset($activeTemplateTrue.'css/color.php') }}?color={{ $general->theme_two_base_color }}&secondColor={{ $general->theme_two_secondary_color }}">
   

</head>
<body>

 @include($activeTemplate.'components.loader')
 @include($activeTemplate.'components.header')

 @if(request()->route()->uri != '/')
 @include($activeTemplate.'components.breadcrumb')
 @endif

@yield('content')

@include($activeTemplate.'components.footer')
@include($activeTemplate.'components.cookie')

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="{{asset('assets/common/js/jquery-3.7.1.min.js')}}"></script>
<script src="{{asset('assets/common/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset($activeTemplateTrue.'js/popper.min.js')}}"></script>
<script src="{{asset($activeTemplateTrue.'js/bootstrap.min.js')}}"></script>
<script src="{{asset($activeTemplateTrue.'js/slick.min.js')}}"></script>
<script src="{{asset($activeTemplateTrue.'js/moment.min.js')}}"></script>
<script src="{{asset($activeTemplateTrue.'js/jquery.appear.min.js')}}"></script>
<script src="{{asset($activeTemplateTrue.'js/jquery.magnific-popup.min.js')}}"></script>
<script src="{{asset($activeTemplateTrue.'js/odometer.min.js')}}"></script>
<script src="{{asset($activeTemplateTrue.'js/viewport.jquery.js')}}"></script>
<script src="{{asset($activeTemplateTrue.'js/main.js')}}"></script>
<script src="{{asset($activeTemplateTrue.'js/location.js')}}"></script>

@stack('script-lib')

@stack('script')

@include('includes.plugins')

@include('includes.notify')


<script>
    (function ($) {
        "use strict";

        $(".langSel").on("change", function() {
            window.location.href = "{{route('home')}}/change/"+$(this).val() ;
        });

        $('.policy').on('click',function(){
            $.get('{{route('cookie.accept')}}', function(response){
                $('.cookies-card').addClass('d-none');
            });
        });

        setTimeout(function(){
            $('.cookies-card').removeClass('hide')
        },2000);



    })(jQuery);
</script>

</body>
</html>
