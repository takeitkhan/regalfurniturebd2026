

<div class="header-area-dk">
    <div class="header-area-top">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-sm-3">
                    <div class="home-logo">
                        <a href="{{ url('/') }}">
                            <img src="{{ $setting->com_logourl }}"
                                 title="{{ $setting->com_name }}"
                                 alt="{{ $setting->com_name }}"/>
                        </a>
                    </div>
                </div>

                <div class="col-md-9 col-sm-9">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="header-top-bar-wap">
                                <div class="header-top-bar">
                                    <ul class="list-unstyled">
                                        <li><a href="#"><span><i class="fa fa-lock"></i></span> Sign In</a></li>
                                        <li><a href="#"><span><i class="fa fa-unlock"></i></span> Sign Out</a></li>
                                        <li><a href="#"><span><i class="fa fa-truck"></i></span> Track My Order</a></li>
                                    </ul>
                                </div>
                                <div class="hotline">
                                    <ul class="list-unstyled">
                                        <li><a href="#"><span>Hotline</span> 09613737777</a></li>
                                    </ul>
                                </div>
                                <div class="socile">
                                    <ul class="list-unstyled">
                                        <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                                        <li><a href="#"><i class="fa fa-youtube"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8 col-sm-8">
                            <div class="home-search">
                                @include('frontend.common.product_search')
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4">
                            <div class="cart-area">
                                <ul class="list-unstyled">
                                    <li>
                                        <a href="#">
                                            Cart
                                            <img src="{{ url('public/frontend/images/cart.png') }}" alt="">
                                            <span class="cart-count">0</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <header>
        <div class="header-area">
            <div class="container">
                <div class="row">
                    <div class="col-md-7 col-sm-6 col-xs-7">
                        <div class="header-meu text-left">
                            <ul class="list-unstyled">
                                <li><a class="active" href="{{ url('/') }}">Home</a></li>
                                <li>
                                    <a href="#">Products</a>
                                    <ul style="">
                                        <div class="container-mega">
                                            @php
                                                $categories = \App\Term::where('parent', 1)->get();
                                            @endphp
                                            <ul class="megamenu tr_megamenu">
                                                <?php $i = 0; ?>
                                                @foreach($categories as $term)
                                                    <?php
                                                    $wsm = (int)$term->with_sub_menu;
                                                    if ($wsm == 1) {
                                                        $wsm_cls = 'with-sub-menu hover';
                                                    } else {
                                                        $wsm_cls = '';
                                                    }
                                                    if ($i > 9) {
                                                        $display_none = "none";
                                                    } else {
                                                        $display_none = '';
                                                    }
                                                    ?>
                                                    <li class="item-vertical {{ $wsm_cls }}"
                                                        style="display: <?php echo $display_none; ?>;">

                                                        <a href="{{ url('c/' . $term->seo_url) }}"
                                                           class="clearfix">
                                                            @if(!empty($term->term_menu_icon))
                                                                <img style="max-width: 16px; max-height: 16px;"
                                                                     src="{{ $term->term_menu_icon }}"
                                                                     alt="icon">
                                                            @endif
                                                            <span>{{ $term->name }}</span>
                                                            @if(!empty($term->term_menu_arrow))
                                                                {!! $term->term_menu_arrow  !!}
                                                            @endif
                                                        </a>

                                                        @if($wsm == 1)
                                                            <div class="sub-menu" data-subwidth="60"
                                                                 style="width: <?php echo !empty($term->sub_menu_width) ? $term->sub_menu_width : null; ?>; display: none; right: 0px;">
                                                                <div class="content" style="display: none;">
                                                                    <div class="row">

                                                                        {{ get_dynamic_category($term->id) }}

                                                                        {{--{!! $term->description !!}--}}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </ul>
                                </li>
                                <li><a href="#">Showroom</a></li>
                                <li><a href="#">Contact Us</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-5 col-sm-6 col-xs-5">
                        <div class="header-meu text-right">
                            <ul class="list-unstyled">
                                <li><a href="#"><span><i class="fa fa-address-book"></i></span> Voucher</a></li>
                                <li><a href="#"><span><i class="fa fa-certificate"></i></span> Special Offer</a></li>
                                <li><a href="#"><span><i class="fa fa-file-text-o"></i></span> Wishlist</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
</div>
<div class="header-area-mobile">
    <div class="header-area-top">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="header-top-bar-wap header-top-bar-wap_one">
                        <div class="header-top-bar">
                            <ul class="list-unstyled">
                                <li><a href=""><span><i class="fa fa-lock"></i></span> Sign In</a></li>
                                <li><a href=""><span><i class="fa fa-unlock"></i></span> Sign Out</a></li>
                                <li><a href=""><span><i class="fa fa-truck"></i></span> Track My Order</a></li>
                            </ul>
                        </div>
                        <div class="hotline_back">
                            <div class="hotline">
                                <ul class="list-unstyled">
                                    <li><a href=""><span>Hotline</span> 09613737777</a></li>
                                </ul>
                            </div>
                            <div class="socile socile_top-mb">
                                <ul class="list-unstyled">
                                    <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                                    <li><a href="#"><i class="fa fa-youtube"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3 col-sm-4 col-xs-4 frous-fal">
                    <div class="home-logo">
                        <a href="#">
                            <img src="{{ url('public/frontend/images/logo.png') }}" alt="logo">
                        </a>
                    </div>
                </div>
                <div class="col-md-9 col-sm-8 col-xs-8 frous-fal_one">
                    <div class="row">
                        <div class="col-md-8 col-sm-12">
                            <div class="home-search">
                                <form method="get" role="search" class="" action="">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="" value="" name="s"
                                               title="">
                                        <div class="input-group-btn">
                                            <button type="submit" class="btn sensor-seh-btn" value="">
                                                <i class="fa fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="header-meu text-left moabi_header_list_menu_mb moabi_header_list_menu">
                                <ul class="list-unstyled">
                                    <li><a href="#"><span><i class="fa fa-address-book"></i></span> Voucher</a></li>
                                    <li><a href="#"><span><i class="fa fa-certificate"></i></span> Special Offer</a></li>
                                    <li><a href="#"><span><i class="fa fa-file-text-o"></i></span> Wishlist</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <header>
        <div class="header-area">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="warpper-mb-m">
                            <div class="header_responsive_menu">
                                <div id="mySidenav" class="sidenav">
                                    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
                                    <div class="home-logo mobail_logo">
                                        <a href="#"><img src="{{ url('public/frontend/images/logo.png') }}" alt="logo"></a>
                                    </div>
                                    <ul class="mobail_menu_list">
                                        <li>
                                            <a href="{{ url('/') }}">
                                                Home<span><i class="fa fa-chevron-right" aria-hidden="true"></i></span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="category.html">
                                                Products
                                                <span><i class="fa fa-chevron-right" aria-hidden="true"></i></span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                Showroom
                                                <span>
                                                    <i class="fa fa-chevron-right" aria-hidden="true"></i>
                                                </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                Quick Navigation
                                                <span>
                                                    <i class="fa fa-chevron-right" aria-hidden="true"></i>
                                                </span>
                                            </a>
                                            <ul>
                                                <li><a href="">About Us</a></li>
                                                <li><a href="">Contact Us</a></li>
                                                <li><a href="">Career</a></li>
                                                <li><a href="">News & Events</a></li>
                                                <li><a href="">Terms & Conditions</a></li>
                                            </ul>
                                        </li>

                                        <li>
                                            <a href="#">
                                                Khowledge base
                                                <span>
                                                    <i class="fa fa-chevron-right" aria-hidden="true"></i>
                                                </span>
                                            </a>
                                            <ul>
                                                <li><a href="">Affiliates</a></li>
                                                <li><a href="">FAQ</a></li>
                                                <li><a href="">Return Policy</a></li>
                                                <li><a href="">Sitemap</a></li>
                                                <li><a href="">EMI</a></li>
                                            </ul>
                                        </li>
                                        <li>
                                            <a href="#">
                                                Information<span><i class="fa fa-chevron-right" aria-hidden="true"></i></span>
                                            </a>
                                            <ul>
                                                <li><a href="">New Arrival</a></li>
                                                <li><a href="">Installment</a></li>
                                                <li><a href="">Payments</a></li>
                                                <li><a href="">Delivery</a></li>
                                                <li><a href="">Warranty</a></li>
                                            </ul>
                                        </li>
                                        <li>
                                            <a href="#">
                                                Others
                                                <span><i class="fa fa-chevron-right" aria-hidden="true"></i></span>
                                            </a>
                                            <ul>
                                                <li><a href="">How to buy product</a></li>
                                                <li><a href="">Update Sofa Fabric Stock</a></li>
                                            </ul>
                                        </li>

                                    </ul>
                                </div>
                                <span class="one-mobile" style="cursor:pointer" onclick="openNav()">&#9776;</span>
                            </div>

                            <div class="cart-area cart-area_mobile">
                                <ul class="list-unstyled">
                                    <li>
                                        <a href="">
                                            Cart <img src="{{ url('public/frontend/images/cart.png') }}" alt="">
                                            <span class="cart-count">0</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <script>
        function openNav() {
            document.getElementById("mySidenav").style.width = "250px";
        }

        function closeNav() {
            document.getElementById("mySidenav").style.width = "0";
        }
    </script>
</div>

@if(Request::is('/'))
@else
    <section class="breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="breadcrumb-warp">
                        <div class="breadcrumb-one">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Rack</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="expane-col-icone text-right">
                            <ul class="list-unstyled">
                                <li>
                                    <a class="four-list" href="#">
                                        <img src="{{ url('public/frontend/images/2.png') }}" alt="">
                                    </a>
                                </li>
                                <li>
                                    <a class="two-list" href="#">
                                        <img src="{{ url('public/frontend/images/1.png') }}" alt="">
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif