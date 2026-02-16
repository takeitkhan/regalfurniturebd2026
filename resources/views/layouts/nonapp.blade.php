
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'default') }}</title>
        @include('layouts.css')
        @yield('css_head')
        @include('layouts.js_head')
    </head>
    <body class="hold-transition login-page">
        <div class="login-box">
            <div class="login-logo">
                <a href="{{ url('/') }}">{{ config('app.name', 'default') }}</a>
            </div>
            <!-- /.login-logo -->
            <div class="login-box-body">
                <p class="login-box-msg">@yield('welcome_msg')</p>
                
                @yield('content')

            </div>
            <!-- /.login-box-body -->
        </div>
        <!-- ./wrapper -->
        @include('layouts.js')
        @yield('cusjs')              
    </body>
</html>