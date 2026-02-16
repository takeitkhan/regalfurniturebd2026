<header id="header" class=" typeheader-1">
    <div class="header-top hidden-compact1">
        <div class="container">
            <div class="row">
                <div class="header-top-left col-lg-7 col-md-8 col-sm-6 col-xs-4">
                    <div class="hidden-md hidden-sm hidden-xs welcome-msg">
                        <marquee>
                            <?php $policy_widget_1 = dynamic_widget($widgets, ['id' => 19]); ?>
                            {!! $policy_widget_1 !!}
                        </marquee>
                    </div>
                    <ul class="top-link list-inline hidden-lg ">
                        <li class="account" id="my_account">
                            <a href="#" title="My Account " class="btn-xs dropdown-toggle" data-toggle="dropdown">
                                <span class="hidden-xs">My Account </span> <span class="fa fa-caret-down"></span>
                            </a>
                            <ul class="dropdown-menu ">
                                <li>
                                    <a href="{{ url('create_an_account') }}">
                                        <i class="fa fa-user"></i> Register
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ url('login_now') }}">
                                        <i class="fa fa-pencil-square-o"></i> Login
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="header-middle-warp">
        <div class="header-middle">
            <div class="container">
                <div class="row">
                    <div class="navbar-logo col-lg-2 col-md-3 col-sm-4 col-xs-12">
                        <div class="logo">
                            <a href="{{ url('/') }}">
                                <img src="{{ $setting->com_logourl }}"
                                     title="{{ $setting->com_name }}"
                                     alt="{{ $setting->com_name }}"/>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-7 col-md-6 col-sm-5">
                        <div class="search-header-w search-header-w_one">
                            <div class="icon-search hidden-lg hidden-md hidden-sm">
                                <i class="fa fa-search"></i>
                            </div>

                            <div id="sosearchpro" class="sosearchpro-wrapper so-search ">
                                @include('frontend.common.product_search')
                            </div>
                        </div>
                    </div>
                    <div class="middle-right shopping_cart_bl col-lg-3 col-md-3 col-sm-3">
                        @include('frontend.layouts.mini_cart')
                    </div>
                </div>

            </div>
        </div>


        <!-- //Header center -->

        <!-- Header Bottom -->
        <div class="header-bottom hidden-compact">
            <div class="container">
                <div class="row">
                    <div class="bottom1 menu-vertical col-lg-2 col-md-3 col-sm-3">
                        <div class="responsive so-megamenu megamenu-style-dev ">
                            <div class="so-vertical-menu ">
                                <nav class="navbar-default">

                                    <div class="container-megamenu vertical">
                                        <div id="menuHeading">
                                            <div class="megamenuToogle-wrapper">
                                                <div class="megamenuToogle-pattern">
                                                    <div class="container hide_down_arrow">
                                                        <div>
                                                            <span></span>
                                                            <span></span>
                                                            <span></span>
                                                        </div>
                                                        All Categories
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="navbar-header">
                                            <button type="button" id="show-verticalmenu" data-toggle="collapse"
                                                    class="navbar-toggle">
                                                <i class="fa fa-bars"></i>
                                                <span> All Categories </span>
                                            </button>
                                        </div>
                                        <div class="vertical-wrapper tighten">
                                            <span id="remove-verticalmenu" class="fa fa-times"></span>
                                            <div class="megamenu-pattern">
                                                <div class="container-mega">
                                                    @php
                                                        $categories = \App\Term::where('parent', 1)->get();
                                                    @endphp
                                                    <ul class="megamenu tr_megamenu">
                                                        @php
                                                            $i = 0;
                                                        @endphp
                                                        @foreach($categories as $term)
                                                            <?php
                                                            $wsm = (int) $term->with_sub_menu;
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
                                                                <p class=" close-menu"></p>
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

                                                            @php
                                                                $i++
                                                            @endphp
                                                        @endforeach
                                                        <li class="tr_loadmore">
                                                            <i class="fa fa-plus-square-o"></i>
                                                            <span class="more-view">More Categories</span>
                                                        </li>

                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </nav>
                            </div>
                        </div>

                    </div>

                    <!-- Main menu -->
                    <div class="main-menu-w col-lg-10 col-md-9">
                        <div class="responsive so-megamenu megamenu-style-dev">
                            <nav class="navbar-default">
                                <div class=" container-megamenu  horizontal open ">
                                    <div class="navbar-header mb-button ">
                                        <button type="button" id="show-megamenu" data-toggle="collapse"
                                                class="navbar-toggle">
                                            <span class="icon-bar"></span>
                                            <span class="icon-bar"></span>
                                            <span class="icon-bar"></span>
                                        </button>
                                    </div>

                                    <div class="megamenu-wrapper">
                                        <span id="remove-megamenu" class="fa fa-times"></span>
                                        <div class="megamenu-pattern">
                                            <div class="container-mega ">
                                                <?php $parent_items = get_parent_menus(1); ?>
                                                <ul class="megamenu" data-transition="slide" data-animationtime="250">
                                                    @if(isset($parent_items))
                                                        @foreach($parent_items as $parent)
                                                            <li>
                                                                <a href="{{ $parent->link }}">{{ $parent->label }}</a>
                                                            </li>
                                                        @endforeach
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </nav>
                        </div>
                    </div>
                    <!-- //end Main menu -->
                    <div class="bottom3">
                        <div class="telephone-area">
                            <div class="telephone hidden-xs hidden-sm hidden-md">
                                <ul class="blank">
                                    <!-- <li><a href="order-information.html"><i class="fa fa-truck"></i>track your order</a>
                                    </li> -->
                                    <li><a href="#"><i class="fa fa-phone-square"></i>Hotline: 09613737777</a></li>
                                </ul>
                            </div>
                            <div class="signin-w hidden-md hidden-sm hidden-xs">
                                <ul class="signin-link blank">
                                    @if (Auth::check())
                                        <li class="log login">
                                            <i class="fa fa-lock"></i>
                                            <a class="link-lg" href="{{ url('my_account') }}">My Account </a>
                                            or <a href="{{ url('logout') }}">Logout</a>
                                        </li>
                                    @else
                                        <li class="log login">
                                            <i class="fa fa-lock"></i>
                                            <a class="link-lg" href="{{ url('login_now') }}">Login </a>
                                            or <a href="{{ url('create_an_account') }}">Register</a>
                                        </li>
                                    @endif

                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</header>
<!-- //Header Container  -->
<!-- sticky menu -->
<header class="secrall-header hidden-sm hidden-xs">
    <div id="header" class=" typeheader-1">
        <div class="header-middle-warp header-middle-warp1">
            <div class="header-middle header-middle1">
                <div class="container">
                    <div class="row">
                        <div class="bottom1 menu-vertical col-lg-2 col-md-3 col-sm-4 col-xs-12">
                            <div class="responsive so-megamenu megamenu-style-dev ">
                                <div class="so-vertical-menu ">
                                    <nav class="navbar-default">
                                        <div class="container-megamenu vertical">
                                            <div id="menuHeading">
                                                <div class="megamenuToogle-wrapper">
                                                    <div class="megamenuToogle-pattern">
                                                        <div class="container container-back hide_down_arrow">
                                                            <div>
                                                                <span></span>
                                                                <span></span>
                                                                <span></span>
                                                            </div>
                                                            All Categories
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="navbar-header">
                                                <button type="button" id="show-verticalmenu" data-toggle="collapse"
                                                        class="navbar-toggle">
                                                    <i class="fa fa-bars"></i>
                                                    <span>  All Categories     </span>
                                                </button>
                                            </div>
                                            <div class="vertical-wrapper tighten" id="hide_before_scroll">
                                                <span id="remove-verticalmenu" class="fa fa-times"></span>
                                                <div class="megamenu-pattern">
                                                    <div class="container-mega top_one">
                                                        @php
                                                            $categories = \App\Term::where('parent', 1)->get();
                                                        @endphp
                                                        <ul class="megamenu tr_megamenu">
                                                            @php
                                                                $i = 0;
                                                            @endphp
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
                                                                    <p class=" close-menu"></p>
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
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                </li>

                                                                @php
                                                                    $i++
                                                                @endphp
                                                            @endforeach
                                                            <li class="tr_loadmore">
                                                                <i class="fa fa-plus-square-o"></i>
                                                                <span class="more-view">More Categories</span>
                                                            </li>

                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </nav>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-5 col-xs-12">
                            <div class="area-flixe">
                                <div class="responsive so-megamenu megamenu-style-dev">
                                    <nav class="navbar-default">
                                        <div class=" container-megamenu  horizontal open ">
                                            <div class="navbar-header">
                                                <button type="button" id="show-megamenu" data-toggle="collapse"
                                                        class="navbar-toggle">
                                                    <span class="icon-bar"></span>
                                                    <span class="icon-bar"></span>
                                                    <span class="icon-bar"></span>
                                                </button>
                                            </div>
                                            <div class="megamenu-wrapper">
                                                <span id="remove-megamenu" class="fa fa-times"></span>
                                                <div class="megamenu-pattern">
                                                    <div class="container-mega">
                                                        <ul class="megamenu" data-transition="slide"
                                                            data-animationtime="250">
                                                            <li class="">
                                                                <a href="{{ url('/') }}">Home </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </nav>
                                </div>
                                <div class="search-header-w  container-mega-left">
                                    <div class="icon-search hidden-lg hidden-md hidden-sm">
                                        <i class="fa fa-search"></i>
                                    </div>

                                    <div id="sosearchpro" class="sosearchpro-wrapper so-search ">
                                        @include('frontend.common.product_search')
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="middle-right middle-right1 col-lg-3 col-md-3 col-sm-3">
                            @include('frontend.layouts.mini_cart')
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- sticky menu -->