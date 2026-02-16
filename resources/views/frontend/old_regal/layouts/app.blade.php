<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    @php
        if (!empty($settings[0])) {
            $setting = $settings[0];
        } else {
            $setting = \App\Setting::where('id', 1)->get()->first();
        }

        $tksign = '&#2547; ';
    @endphp
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {!! 
    metas($settings, $options = array(
            'url' => config('app.url', 'default'),
            'img_url' => NULL,
            'title' => NULL,
            'description' => NULL,
            'keywords' => NULL
        ))
    !!}
        
    <script type="text/javascript"> var baseurl = "<?php echo url('/'); ?>";</script>
    @include('frontend.layouts.css')
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-TNSXJ44');</script>
    <!-- End Google Tag Manager -->
    
<script type="application/ld+json">
    {
      "@context": "https://schema.org/",
      "@type": "WebSite",
      "name": "Regal Furniture",
      "url": "https://www.regalfurniturebd.com",
      "potentialAction": {
        "@type": "SearchAction",
        "target": "http://regalfurniturebd.com/main_search_form?product_cat={search_term_string}&keyword=&post_type=product",
        "query-input": "required name=search_term_string"
      }
    }
    </script>
</head>
<body class="common-home res layout-1 hidden-scorll">
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TNSXJ44"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
<div id="wrapper" class="wrapper-fluid banners-effect-5">
    {!! $setting->com_analytics !!}
    <div class="pushed">
        <div class="frontend">
            @include('frontend.layouts.header')

            <div class="frontend_content">
                @yield('content')
            </div>

            @include('frontend.layouts.footer')
            @include('frontend.layouts.js')
            @yield('cusjs')

        </div>
    </div>
</div>
{!! $setting->com_chat_box !!}
</body>
</html>