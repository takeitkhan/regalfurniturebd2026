<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'default') }}</title>
    <script type="text/javascript"> var baseurl = "<?php echo url('/'); ?>";</script>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    @include('layouts.css')
    @include('layouts.js_head')
</head>
<body class="hold-transition skin-green sidebar-mini">
<?php $tksign = '&#2547; '; ?>
<div class="wrapper">

@include('layouts.header')

@if(auth()->user()->isAdmin())
    @include('layouts.aside_l')
@elseif(auth()->user()->isManager())
    @include('layouts.manager_aside')
@elseif(auth()->user()->isOrderViewer())
    @include('layouts.order_viewer_aside')
@endif


<!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header" style="border-bottom: 1px solid #ddd; padding-bottom: 10px;">
            <h1>@yield('title')
                <small>@yield('sub_title')</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">@yield('title')</li>
            </ol>
        </section>
        <div class="content">
            @yield('content')
        </div>
    </div>

    @include('layouts.footer')
    @include('layouts.aside_r')

</div>
<!-- ./wrapper -->
@include('layouts.js')
@yield('cusjs')
</body>
</html>