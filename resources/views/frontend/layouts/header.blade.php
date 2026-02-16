<div class="header-area-dk">
    <div class="header-area-top">
        <div class="container">
            <div class="row">
                <div class="col-md-2 col-sm-3">
                    <div class="home-logo">
                        <a href="{{ url('/') }}">
                            <img src="{{ $setting->com_logourl }}"
                                 title="{{ $setting->com_name }}"
                                 alt="{{ $setting->com_name }}"/>
                        </a>
                    </div>
                </div>

                @php
                    //dump($setting);
                @endphp

                <div class="col-md-10 col-sm-9">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="header-top-bar-wap">
                                <div class="header-top-bar">
                                    <ul class="list-unstyled">
                                        @if (Auth::check())
                                            <li><a href="{{ url('my_account') }}"><span><i class="fa fa-user"></i></span> My Account</a></li>
                                            <li><a href="{{ url('logout') }}"><span><i class="fa fa-unlock"></i></span> Sign Out</a></li>
                                        @else
                                            <li><a href="{{ url('login_now') }}"><span><i class="fa fa-lock"></i></span> Sign In</a></li>
                                        @endif
                                        <li><a href="{{ url('/track_order') }}"><span><i class="fa fa-truck"></i></span> Track My Order</a></li>
                                    </ul>
                                </div>
                                <div class="hotline">
                                    <ul class="list-unstyled">
                                        <li><a href="#"><span>Hotline</span> {{ $setting->com_phone }}</a></li>
                                    </ul>
                                </div>
                                <div class="socile">
                                    <ul class="list-unstyled">
                                        <li><a target="_blank" href="https://www.facebook.com/regalfurniturebangladesh"><i class="fa fa-facebook"></i></a></li>
                                        <li><a target="_blank" href="https://www.youtube.com/channel/UCzHC1qmvTSli0ItjMNreL3A"><i class="fa fa-youtube"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-10 col-sm-8">
                            <div class="home-search">
                                @include('frontend.common.product_search')
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-4">
                            <div class="cart-area">
                                <ul class="list-unstyled">
                                    <li>
                                        <a href="{{ url('view_cart') }}">
                                            Cart
                                            <img src="{{ url('public/frontend/images/cart.png') }}" alt="">
                                            <div id="cart_item_ajax"> </div>

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

    <header class="header-area-wp"><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <div class="header-area">
            <div class="container">
                <div class="row">
                    <div class="col-md-7 col-sm-6 col-xs-7">
                        <div class="header-meu text-left">

                            <?php $parent_items = get_parent_menus(1); ?>
                            <ul class="list-unstyled">
                                @if(isset($parent_items))
                                    @foreach($parent_items as $parent)
                                        <li>
                                            @php
                                                $sub_menu = get_sub_menu_list($parent->id);
                                            @endphp
                                            <a class="{{ (request()->url() == $parent->link)? 'active': '' }}" href="{{ $parent->link }}">{{$parent->label}}</a>
                                            @if($sub_menu)
                                                <ul style="">
                                                    <div class="container-mega">
                                                        @php
                                                            //$categories = \App\Term::where('parent', 1)->get();
                                                            $categories = $sub_menu;
                                                        @endphp
                                                        <ul class="megamenu tr_megamenu">
                                                            <?php $i = 0; ?>
                                                            @foreach($categories as $term)
                                                           
                                                                <li class="item-vertical {{$term->class}}" 
                                                                >
                                                                    <a href="{{ $term->link }}"
                                                                       class="clearfix">
                                                                        <span>{{ $term->label }}</span>
                                                                    </a>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </ul>
                                            @endif
                                        </li>

                                    @endforeach
                                @endif
                            </ul>



                        </div>
                    </div>
                    <div class="col-md-5 col-sm-6 col-xs-5">
                        <div class="header-meu_oarv  header-meu_right text-right">
                               <a href="{{ url('/view_compare') }}"><span><i class="fa fa-balance-scale"></i></span>  <span class="one-top-wpcp" id="show_total_compare"></span> </a>

                                <a href="{{ url('/wishlist') }}"><span><i class="fa fa-heart"></i></span> <span class="one-top-wpcp" id="show_total_wishlist"></span> </a>
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
                                    <li><a href="https://www.facebook.com/regalfurniturebangladesh"><i class="fa fa-facebook"></i></a></li>
                                    <li><a href="https://www.youtube.com/channel/UCzHC1qmvTSli0ItjMNreL3A"><i class="fa fa-youtube"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3 col-sm-4 col-xs-4 frous-fal">
                    <div class="home-logo">
                        <a href="{{ url('/') }}">
                            <img src="{{ url('public/frontend/images/logo.png') }}" alt="logo">
                        </a>
                       
                    </div>
                </div>
                <div class="col-md-9 col-sm-8 col-xs-8 frous-fal_one">
                    <div class="row">
                        <div class="col-md-8 col-sm-12">
                            <div class="home-search">
                                
                                  @include('frontend.common.product_search')
                                  
                                <!--<form method="get" role="search" class="" action="">-->
                                <!--    <div class="input-group">-->
                                <!--        <input type="text" class="form-control" placeholder="" value="" name="s"-->
                                <!--               title="">-->
                                <!--        <div class="input-group-btn">-->
                                <!--            <button type="submit" class="btn sensor-seh-btn" value="">-->
                                <!--                <i class="fa fa-search"></i>-->
                                <!--            </button>-->
                                <!--        </div>-->
                                <!--    </div>-->
                                <!--</form>-->
                            </div>
                            <div class="header-meu text-left moabi_header_list_menu_mb moabi_header_list_menu">
                                <ul class="list-unstyled">
                                    <li><a href="#"><span><i class="fa fa-address-book"></i></span> Voucher</a></li>
                                    <li><a href="#"><span><i class="fa fa-certificate"></i></span> Special Offer</a></li>
                                    <li><a href="{{ url('/wishlist') }}"><span><i class="fa fa-file-text-o"></i></span> Wishlist</a></li>
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
                                         <?php $parent_items = get_parent_menus(1); ?>
                                          
                                                @if(isset($parent_items))
                                                    @foreach($parent_items as $parent)
                                                        <li>
                                                            @php
                
                                                                $sub_menu = get_sub_menu_list($parent->id);
                
                                                            @endphp
                                                            <a class="{{ (request()->url() == $parent->link)? 'active': '' }}" href="{{ $parent->link }}">{{$parent->label}}</a>
                                                            @if($sub_menu)
                                                                <ul style="">
                                                                    <div class="">
                                                                        @php
                                                                            //$categories = \App\Term::where('parent', 1)->get();
                                                                            $categories = $sub_menu;
                                                                        @endphp
                                                                        <ul class="">
                                                                            <?php $i = 0; ?>
                                                                            @foreach($categories as $term)
                                                                           
                                                                                <li class=" {{$term->class}}" 
                                                                                >
                                                                                    <a href="{{ $term->link }}"
                                                                                       class="clearfix">
                                                                                        <span>{{ $term->label }}</span>
                                                                                    </a>
                                                                                </li>
                                                                            @endforeach
                                                                        </ul>
                                                                    </div>
                                                                </ul>
                
                                                            @endif
                
                
                                                        </li>
                
                                                    @endforeach
                                                @endif
                                                
                                        <li>
                                            <a href="#">
                                                Quick Navigation
                                                <span>
                                                    <i class="fa fa-chevron-right" aria-hidden="true"></i>
                                                </span>
                                            </a>
                                            
                                             <?php $parent_items = get_parent_menus(9); ?>
                                                <ul class="list-unstyled">
                    
                                                    @if(isset($parent_items))
                                                        @foreach($parent_items as $parent)
                                                            <li>
                                                                <a href="{{ $parent->link }}">{{ $parent->label }}</a>
                                                            </li>
                                                        @endforeach
                                                    @endif
                    
                                                </ul>
                                        </li>

                                        <li>
                                            <a href="#">
                                                Khowledge base
                                                <span>
                                                    <i class="fa fa-chevron-right" aria-hidden="true"></i>
                                                </span>
                                            </a>
                                           
                                            <?php $parent_items = get_parent_menus(10); ?>
                                            <ul class="list-unstyled">
                
                                                @if(isset($parent_items))
                                                    @foreach($parent_items as $parent)
                                                        <li>
                                                            <a href="{{ $parent->link }}">{{ $parent->label }}</a>
                                                        </li>
                                                    @endforeach
                                                @endif
                
                                            </ul>
                                        </li>
                                        <li>

                                            <a href="#">
                                                Information<span><i class="fa fa-chevron-right" aria-hidden="true"></i></span>
                                            </a>
                                            <?php $parent_items = get_parent_menus(3); ?>
                                                <ul class="list-unstyled">
                    
                                                    @if(isset($parent_items))
                                                        @foreach($parent_items as $parent)
                                                            <li>
                                                                <a href="{{ $parent->link }}">{{ $parent->label }}</a>
                                                            </li>
                                                        @endforeach
                                                    @endif
                    
                                                </ul>


                                        </li>
                                        <li>
                                            <a href="#">
                                                Others
                                                <span><i class="fa fa-chevron-right" aria-hidden="true"></i></span>
                                            </a>
                                            <?php $parent_items = get_parent_menus(6); ?>
                                                <ul class="list-unstyled">
                    
                                                    @if(isset($parent_items))
                                                        @foreach($parent_items as $parent)
                                                            <li>
                                                                <a href="{{ $parent->link }}">{{ $parent->label }}</a>
                                                            </li>
                                                        @endforeach
                                                    @endif
                    
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

</div>

@if(Request::is('/'))
@else

@endif