<!-- Libs CSS ============================================ -->
{{--<link rel="stylesheet" href="{{ URL::asset('public/frontend/css/bootstrap/css/bootstrap.min.css') }}">--}}
{{--<link href="{{ URL::asset('public/frontend/css/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">--}}
{{--<link href="{{ URL::asset('public/frontend/js/datetimepicker/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">--}}
{{--<link href="{{ URL::asset('public/frontend/js/owl-carousel/owl.carousel.css') }}" rel="stylesheet">--}}
{{--<link href="{{ URL::asset('public/frontend/css/themecss/lib.css') }}" rel="stylesheet">--}}
{{--<link href="{{ URL::asset('public/frontend/css/style.css') }}" rel="stylesheet">--}}
{{--<link href="{{ URL::asset('public/frontend/js/jquery-ui/jquery-ui.min.css') }}" rel="stylesheet">--}}
{{--<link href="{{ URL::asset('public/frontend/js/minicolors/miniColors.css') }}" rel="stylesheet">--}}

<!-- Theme CSS ============================================ -->
{{--<link href="{{ URL::asset('public/frontend/css/themecss/so_searchpro.css') }}" rel="stylesheet">--}}
{{--<link href="{{ URL::asset('public/frontend/css/themecss/so_megamenu.css') }}" rel="stylesheet">--}}
{{--<link href="{{ URL::asset('public/frontend/css/themecss/so-categories.css') }}" rel="stylesheet">--}}
{{--<link href="{{ URL::asset('public/frontend/css/themecss/so-listing-tabs.css') }}" rel="stylesheet">--}}
{{--<link href="{{ URL::asset('public/frontend/css/themecss/so-category-slider.css') }}" rel="stylesheet">--}}
{{--<link href="{{ URL::asset('public/frontend/css/themecss/so-newletter-popup.css') }}" rel="stylesheet">--}}

{{--<link href="{{ URL::asset('public/frontend/css/footer/footer1.css') }}" rel="stylesheet">--}}
<link href="{{ URL::asset('public/frontend/css/plugin.css') }}" rel="stylesheet">
{{--<link id="color_scheme" href="{{ URL::asset('public/frontend/css/theme.css') }}" rel="stylesheet">--}}
<link href="{{ URL::asset('public/frontend/css/style.css') }}" rel="stylesheet">
<link href="{{ URL::asset('public/frontend/css/responsive.css') }}" rel="stylesheet">

<!-- Google web fonts
============================================ -->
<link href='https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700' rel='stylesheet' type='text/css'>
<style type="text/css">
     @php

        $g_setting = App\Models\Setting::get()->first();
    @endphp
    body {
        font-family: 'Poppins', sans-serif;
    }
    @if($g_setting->header_bg != null || $g_setting->header_bg != '')
        .header-area-top {
            background: url({{$g_setting->header_bg}});
        }
    @else
        .header-area-top {
            background: #233040;
        }
    @endif
    .stick-top-pad{
      padding-top:40px;
     }

.alimify-modal-content {
    position: relative;
}

/*.alimify-modal-btn {
    position: absolute;
    right: 20px;
    top: 10px
}
*/

#alimify-modal img {
    width: 100%
}


.alimify-modal-button-close {
    color: #EEE;
    opacity: .5;
    font-size: 30px;
    font-weight: normal;
    background:black;
    padding:2px;
}

.alimify-modal-content {
    position: relative;
    margin: 200px auto !important;
}


.alimify-modal-button-close:hover {
    color: #FFF !important;
}

.product-price-btn.buy-btn.not-available-btn input {
font-size: 11px !important;
padding: 4px 3px !important;
font-size: 14px !important;
border-radius: 3px;
background: #ff001ccc;
}

/*.not-available-btn {*/
/*position: relative;*/
/*top: -146px;*/
/*right: 48px !important;*/
/*width: 257px;*/
/*}*/

a.fb-login-btn {
background: #3b5a9a;
padding: 5px 20px;
display: inline-block;
color: #fff;
font-size: 1rem;
font-weight: 500;
float: left;
position: relative;
top: 15px;
left: 20px;
border-radius: 5px;
}

a.google-login-btn {
    background: #d64131;
    color: #fff;
    position: relative;
    display: inline-block;
    padding: 5px 30px;
    border-radius: 5px;
    margin-bottom: 15px;
    float: right;
    top: 15px;
    right: 1px;
}


</style>
