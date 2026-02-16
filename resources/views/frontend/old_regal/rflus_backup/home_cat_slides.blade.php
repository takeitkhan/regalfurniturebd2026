<div class="col-lg-2 col-md-12 col-sm-12 col-xs-12 main-left">
    <!-- <div class="module col1 hidden-sm hidden-xs"></div> -->
    <div class="responsive so-megamenu megamenu-style-dev hidden-sm hidden-xs hidden-md">
        <div class="so-vertical-menu ">
            <nav class="navbar-default">
                <div class="container-megamenu vertical">
                    <div class="navbar-header">
                        <button type="button" id="show-verticalmenu" data-toggle="collapse" class="navbar-toggle">
                            <i class="fa fa-bars"></i>
                            <span>All Categories</span>
                        </button>
                    </div>
                    <div class="vertical-wrapper">
                        <span id="remove-verticalmenu" class="fa fa-times"></span>
                        <div class="megamenu-pattern">
                            <div class="container-mega">
                                @php
                                    $categories = \App\Term::where('parent', 1)->get();
                                @endphp
                                <ul class="megamenu tr_megamenu_02">
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
                                            <a href="{{ url('c/' . $term->seo_url) }}" class="clearfix">
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
                                    <li class="tr_loadmore_02">
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

<div class="col-lg-10 col-md-12 col-sm-12 col-xs-12 main-right">
    <div class="slider-container row">
        <div class="col-lg-9 col-md-12 col-sm-12 col-xs-12 col2">
            <div class="module sohomepage-slider ">
                <div class="yt-content-slider" data-rtl="yes" data-autoplay="no" data-autoheight="no"
                     data-delay="4" data-speed="0.6" data-margin="0" data-items_column00="1"
                     data-items_column0="1" data-items_column1="1" data-items_column2="1"
                     data-items_column3="1" data-items_column4="1" data-arrows="no"
                     data-pagination="yes" data-lazyload="yes" data-loop="yes" data-hoverpause="yes">
                    <?php $static_cats = dynamic_widget($widgets, ['id' => 18]); ?>
                    {!! $static_cats !!}
                </div>
                <div class="loadeding"></div>
            </div>
        </div>


        @php
            $best_selling = App\Product::where('best_selling', 'on')->orderBy('id', 'desc')->limit(8)->get()->toArray();
            //dd($best_selling);
            $first = array_slice($best_selling, 0, 4);
            $second = array_slice($best_selling, 4, 8);
        @endphp

        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12 col3">
            <div class="module product-simple extra-layout1">
                <h3 class="modtitle">
                    <span>Best Selling</span>
                </h3>
                <div class="modcontent">
                    <div id="so_extra_slider_1" class="so-extraslider">
                        <div class="yt-content-slider extraslider-inner" data-rtl="yes"
                             data-pagination="yes" data-autoplay="no" data-delay="4" data-speed="0.6"
                             data-margin="0" data-items_column00="1" data-items_column0="1"
                             data-items_column1="1" data-items_column2="1" data-items_column3="1"
                             data-items_column4="1" data-arrows="no" data-lazyload="yes" data-loop="no"
                             data-buttonpage="top">
                            <div class="item ">
                                @foreach($first as $pro)
                                    @php
                                        $pro = (object)$pro;
                                        $first_image = \App\ProductImages::where('main_pid', $pro->id)->where('is_main_image', 1)->get()->first();

                                        if (!empty($first_image->full_size_directory)) {
                                            $img = url($first_image->full_size_directory);
                                        } else {
                                            $img = url('storage/uploads/fullsize/2019-01/default.jpg');
                                        }

                                        $regularprice = $pro->local_selling_price;
                                        $save = ($pro->local_selling_price * $pro->local_discount) / 100;
                                        $sp = round($regularprice - $save);
                                    @endphp
                                    <div class="product-layout item-inner style1 ">
                                        <div class="item-image">
                                            <div class="item-img-info">
                                                <a href="{{ url('p/' .  $pro->seo_url) }}" target="_self"
                                                   title="{{ $pro->title }}">
                                                    @if(!empty($first_image))
                                                        <img src="{{ $img }}"
                                                             alt="{{ $pro->title }}">
                                                    @endif
                                                </a>
                                            </div>

                                        </div>
                                        <div class="item-info">
                                            <div class="item-title">
                                                <a href="{{ url('p/' .  $pro->seo_url) }}" target="_self"
                                                   title="{{ $pro->title }}">
                                                    {{ limit_text( $pro->title, 50) }}
                                                </a>
                                            </div>
                                            <?php echo product_review($pro->id) ?>
                                            <div class="content_price price">
                                                <?php
                                                if ($regularprice < $sp) {
                                                    $price = '<span class="price-new product-price">' . $tksign . number_format($sp) . '</span>';
                                                    $price .= '<span class="price-old">' . $tksign . number_format($regularprice) . '</span>';
                                                } else {
                                                    $price = '<span class="price-new product-price">' . $tksign . number_format($sp) . '</span>';
                                                }
                                                ?>
                                                {!! $price !!}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="item ">
                                @foreach($second as $pro)

                                    @php
                                        $pro = (object)$pro;
                                        $first_image = \App\ProductImages::where('main_pid', $pro->id)->where('is_main_image', 1)->get()->first();

                                        if (!empty($first_image->full_size_directory)) {
                                            $img = url($first_image->full_size_directory);
                                        } else {
                                            $img = url('storage/uploads/fullsize/2019-01/default.jpg');
                                        }

                                        $regularprice = $pro->local_selling_price;
                                        $save = ($pro->local_selling_price * $pro->local_discount) / 100;
                                        $sp = round($regularprice - $save);
                                    @endphp

                                    <div class="product-layout item-inner style1 ">
                                        <div class="item-image">
                                            <div class="item-img-info">
                                                <a href="{{ url('p/' .  $pro->seo_url) }}" target="_self"
                                                   title="{{ $pro->title }}">
                                                    <img src="{{ $img }}"
                                                         alt="{{ $pro->title }}">
                                                </a>
                                            </div>

                                        </div>
                                        <div class="item-info">
                                            <div class="item-title">
                                                <a href="{{ url('p/' .  $pro->seo_url) }}" target="_self"
                                                   title="{{ $pro->title }}">
                                                    {{ limit_text( $pro->title, 50) }}
                                                </a>
                                            </div>
                                            <?php echo product_review($pro->id) ?>
                                            <div class="content_price price">
                                                <?php
                                                if ($regularprice < $sp) {
                                                    $price = '<span class="price-new product-price">' . $tksign . number_format($sp) . '</span>';
                                                    $price .= '<span class="price-old">' . $tksign . number_format($regularprice) . '</span>';
                                                } else {
                                                    $price = '<span class="price-new product-price">' . $tksign . number_format($sp) . '</span>';
                                                }
                                                ?>
                                                {!! $price !!}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <!--End extraslider-inner -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>