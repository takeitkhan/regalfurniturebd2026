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
    {!! metas($settings, $options = array(
            'url' => config('app.url', 'default'),
            'img_url' => NULL,
            'title' => NULL,
            'description' => NULL,
            'keywords' => NULL
        ))  !!}
    <script type="text/javascript"> var baseurl = "<?php echo url('/'); ?>";</script>
    @include('frontend.layouts.css')
</head>
<body class="common-home res layout-1 hidden-scorll">
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