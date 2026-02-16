@php
    // First Cat
    $first_cat = $homesettig->cat_first;
    $fcc = explode('|', $first_cat);
    //dd($fcc[0]);
    $term_first = \App\Term::where('id', $fcc[0])->get()->first();
    $products_first = \App\ProductCategories::leftJoin('products', function ($join) {
        $join->on('productcategories.main_pid', '=', 'products.id');
    })->where(['productcategories.term_id' => $term_first->id])->limit(8)->get();

    $products_first_best_selling = \App\ProductCategories::leftJoin('products', function ($join) {
        $join->on('productcategories.main_pid', '=', 'products.id');
    })->where(['productcategories.term_id' => $term_first->id, 'products.best_selling' => 'on'])->limit(6)->get()->toArray();

    // Second Cat
    $cat_second = $homesettig->cat_second;
    $sc = explode('|', $cat_second);
    $term_second = \App\Term::where('id', $sc[0])->get()->first();
    $products_second = \App\ProductCategories::leftJoin('products', function ($join) {
        $join->on('productcategories.main_pid', '=', 'products.id');
    })->where(['productcategories.term_id' => $term_second->id])->limit(8)->get();

    $products_second_best_selling = \App\ProductCategories::leftJoin('products', function ($join) {
        $join->on('productcategories.main_pid', '=', 'products.id');
    })->where(['productcategories.term_id' => $term_second->id, 'products.best_selling' => 'on'])->limit(6)->get()->toArray();

    // Third Cat
    $cat_third = $homesettig->cat_third;
    $tc = explode('|', $cat_third);
    $term_third = \App\Term::where('id', $tc[0])->get()->first();
    $products_third = \App\ProductCategories::leftJoin('products', function ($join) {
        $join->on('productcategories.main_pid', '=', 'products.id');
    })->where(['productcategories.term_id' => $term_third->id])->limit(8)->get();
    //dd($products_third);


    $products_third_best_selling = \App\ProductCategories::leftJoin('products', function ($join) {
        $join->on('productcategories.main_pid', '=', 'products.id');
    })->where(['productcategories.term_id' => $term_third->id, 'products.best_selling' => 'on'])->limit(6)->get()->toArray();

@endphp

<div class="row box-content2">
    <div class="col-lg-10 col-md-12 col-sm-12 col-xs-12">
        <!-- Technology -->
        <div id="so_category_slider_189"
             class="so-category-slider container-slider module cate-slider2">
            <div class="modcontent">
                <div class="page-top">
                    <div class="page-title-categoryslider">
                        <a href="{{ url('c/' . $term_first->seo_url) }}" title="{{ $term_first->name }}"
                           target="_self">
                            {{ $term_first->name }}
                        </a>
                    </div>
                    <div class="menu-icone visible-xs">
                        <a href="javascript:void(0);">
                            <span><i class="fa fa-bars"></i></span>
                        </a>
                    </div>

                    <div class="item-sub-cat">
                        <div class="visible-xs">
                            <div class="menu-tab-mobile">
                                <ul>
                                    @php
                                        //dump($fcc[0]);
                                        $terms_f = App\Term::where('parent', $fcc[0])->get();
                                        //dump($terms_f);
                                        $i = 0;
                                        foreach ($terms_f as $t) {
                                            echo '<li><a href="' . url('c/' . $t->seo_url) . '" title="' . $t->name . '" target="_self">' . $t->name . '</a></li>';
                                            if ($i == 3) {
                                                break;
                                            }
                                            $i++;
                                        }
                                    @endphp
                                    <li>
                                        <a class="viewall" style="color: #fb3b4e;"
                                           href="{{ url('c/' . $term_first->seo_url) }}">
                                            View All
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <ul class="hidden-xs">
                            @php
                                //dump($fcc[0]);
                                $terms_f = App\Term::where('parent', $fcc[0])->get();
                                //dump($terms_f);
                                $i = 0;
                                foreach ($terms_f as $t) {
                                    echo '<li><a href="' . url('c/' . $t->seo_url) . '" title="' . $t->name . '" target="_self">' . $t->name . '</a></li>';
                                    if ($i == 3) {
                                        break;
                                    }
                                    $i++;
                                }
                            @endphp
                            <li>
                                <a class="viewall" style="color: #fb3b4e;"
                                   href="{{ url('c/' . $term_first->seo_url) }}">
                                    View All
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="categoryslider-content show preset01-4 preset02-4 preset03-3 preset04-2 preset05-1">

                <div class="slider category-slider-inner products-list" data-rtl="yes"
                     data-autoplay="no" data-autoheight="no" data-delay="4" data-speed="0.6" data-margin="30"
                     data-items_column00="4" data-items_column0="4" data-items_column1="4" data-items_column2="3"
                     data-items_column3="2" data-items_column4="1" data-arrows="no" data-pagination="no"
                     data-lazyload="yes" data-loop="no" data-hoverpause="yes">

                    @foreach($products_first as $product)
                        @php
                            $pro = \App\Product::where('id', $product->main_pid)->get()->first();
                            if (!empty($pro)) {
                                $first_image = \App\ProductImages::where('main_pid', $pro->id)->where('is_main_image', 1)->get()->first();

                                if (!empty($first_image->full_size_directory)) {
                                    $img = url($first_image->full_size_directory);
                                } else {
                                    $img = url('storage/uploads/fullsize/2019-01/default.jpg');
                                }

                                $second_image = \App\ProductImages::where('main_pid', $pro->id)->where('is_main_image', 0)->get()->first();
                                //dd($second_image);
                                $regularprice = $pro->local_selling_price;
                                $save = ($pro->local_selling_price * $pro->local_discount) / 100;
                                $sp = round($regularprice - $save);

                                echo product_design([
                                    'bootstrap_cols' => 'col-md-3 col-sm-4 col-xs-6',
                                    'seo_url' => product_seo_url($pro->seo_url, $pro->id),
                                    'straight_seo_url' => $pro->seo_url,
                                    'title' => $pro->title,
                                    'first_image' => $img,
                                    'second_image' => !empty($second_image) ? url($second_image->full_size_directory) : null,
                                    'discount_rate' => $pro->local_discount,
                                    'price' => $sp,
                                    'old_price' => $regularprice,
                                    'descriptions' => $pro->description,
                                    'product_code' => $pro->product_code,
                                    'product_sku' => $pro->sku,
                                    'product_id' => $pro->id,
                                    'product_qty' => 1,
                                    'sign' => $tksign
                                ]);
                            }
                        @endphp
                    @endforeach
                </div>
                <div class="item-cat-image box-items">
                    <h3>Best Selling</h3>
                    <div class="product-feature row">

                        @foreach($products_first_best_selling as $pro)
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

                            <div class="item col-lg-12 col-md-4 col-sm-4 col-xs-6">
                                <div class="item-inner">
                                    <div class="image">
                                        <a href="{{ url('p/' .  $pro->seo_url) }}" target="_self"
                                           title="{{ $pro->title }}">
                                            @if(!empty($first_image))
                                                <img src="{{ $img }}"
                                                     alt="{{ $pro->title }}">
                                            @endif
                                        </a>
                                    </div>
                                    <div class="caption">
                                        @php
                                            echo product_review($pro->id);
                                        @endphp

                                        <h4 class="item-title">
                                            <a href="{{ url('p/' .  $pro->seo_url) }}" target="_self"
                                               title="{{ $pro->title }}">
                                                {{ limit_text( $pro->title, 2) }}
                                            </a>
                                        </h4>

                                        <div class="content_price price">
                                            @php
                                                if ($regularprice < $sp) {
                                                    $price = '<span class="price-new product-price">' . $tksign . number_format($sp) . '</span>';
                                                    $price .= '<span class="price-old">' . $tksign . number_format($regularprice) . '</span>';
                                                } else {
                                                    $price = '<span class="price-new product-price">' . $tksign . number_format($sp) . '</span>';
                                                }
                                            @endphp
                                            {!! $price !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>

            </div>
        </div>

        {{--        <!-- Fashion & Accessories -->--}}
        <div id="so_category_slider_190"
             class="so-category-slider container-slider module cate-slider2">
            <div class="modcontent">
                <div class="page-top">
                    <div class="page-title-categoryslider">
                        <a href="{{ url('c/' . $term_second->seo_url) }}" title="{{ $term_second->name }}"
                           target="_self">
                            {{ $term_second->name }}
                        </a>

                    </div>

                    <div class="menu-icone visible-xs">
                        <a href="javascript:void(0);">
                            <span><i class="fa fa-bars"></i></span>
                        </a>
                    </div>
                    <div class="item-sub-cat">
                        <div class="visible-xs">
                            <div class="menu-tab-mobile">
                                <ul>
                                    @php
                                        $terms = App\Term::where('parent', $sc[0])->get();
                                        $i = 0;

                                        foreach ($terms as $t) {
                                            echo '<li><a href="' . url('c/' . $t->seo_url) . '"  title="' . $t->name . '" target="_self">' . $t->name . '</a></li>';
                                            if ($i == 3) {
                                                break;
                                            }
                                            $i++;
                                        }
                                    @endphp
                                    <li>
                                        <a class="viewall" style="color: #fb3b4e;"
                                           href="{{ url('c/' . $term_second->seo_url) }}">
                                           
                                            View All
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <ul class="hidden-xs">
                            @php
                                $terms = App\Term::where('parent', $sc[0])->get();
                                $i = 0;

                                foreach ($terms as $t) {
                                    echo '<li><a href="' . url('c/' . $t->seo_url) . '"  title="' . $t->name . '" target="_self">' . $t->name . '</a></li>';
                                    if ($i == 3) {
                                        break;
                                    }
                                    $i++;
                                }
                            @endphp
                            <li>
                                <a class="viewall" style="color: #fb3b4e;"
                                   href="{{ url('c/' . $term_second->seo_url) }}">
                                    View All
                                </a>
                            </li>
                        </ul>

                    </div>
                </div>
            </div>
            <div class="categoryslider-content show preset01-4 preset02-4 preset03-3 preset04-2 preset05-1">

                <div class="slider category-slider-inner products-list" data-rtl="yes"
                     data-autoplay="no" data-autoheight="no" data-delay="4" data-speed="0.6"
                     data-margin="30" data-items_column00="4" data-items_column0="4"
                     data-items_column1="4" data-items_column2="3" data-items_column3="2"
                     data-items_column4="1" data-arrows="no" data-pagination="no" data-lazyload="yes"
                     data-loop="no" data-hoverpause="yes">

                    @foreach($products_second as $product)
                        @php
                            $pro = \App\Product::where('id', $product->main_pid)->get()->first();
                            $first_image = \App\ProductImages::where('main_pid', $pro->id)->where('is_main_image', 1)->get()->first();

                            if (!empty($first_image->full_size_directory)) {
                                $img = url($first_image->full_size_directory);
                            } else {
                                $img = url('storage/uploads/fullsize/2019-01/default.jpg');
                            }

                            $second_image = \App\ProductImages::where('main_pid', $pro->id)->where('is_main_image', 0)->get()->first();

                            $regularprice = $pro->local_selling_price;
                            $save = ($pro->local_selling_price * $pro->local_discount) / 100;
                            $sp = $regularprice - $save;

                            echo product_design([
                                'bootstrap_cols' => 'col-md-3 col-sm-4 col-xs-6',
                                'seo_url' => product_seo_url($pro->seo_url, $pro->id),
                                'straight_seo_url' => $pro->seo_url,
                                'title' => $pro->title,
                                'first_image' => $img,
                                'second_image' => !empty($second_image) ? url($second_image->full_size_directory) : null,
                                'discount_rate' => $pro->local_discount,
                                'price' => $sp,
                                'old_price' => $regularprice,
                                'descriptions' => $pro->description,
                                'product_code' => $pro->product_code,
                                'product_sku' => $pro->sku,
                                'product_id' => $pro->id,
                                'product_qty' => 1,
                                'sign' => '&#2547; '
                            ]);
                        @endphp
                    @endforeach

                </div>
                <div class="item-cat-image box-items">
                    <h3>Best Selling</h3>
                    <div class="product-feature row">
                        @foreach($products_second_best_selling as $pro)
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

                            <div class="item col-lg-12 col-md-4 col-sm-4 col-xs-6">
                                <div class="item-inner">
                                    <div class="image">
                                        <a href="{{ url('p/' .  $pro->seo_url) }}" target="_self"
                                           title="{{ $pro->title }}">
                                            @if(!empty($first_image))
                                                <img src="{{ $img }}"
                                                     alt="{{ $pro->title }}">
                                            @endif
                                        </a>
                                    </div>
                                    <div class="caption">
                                        @php
                                            echo product_review($pro->id);
                                        @endphp

                                        <h4 class="item-title">
                                            <a href="{{ url('p/' .  $pro->seo_url) }}" target="_self"
                                               title="{{ $pro->title }}">
                                                {{ limit_text( $pro->title, 2) }}
                                            </a>
                                        </h4>

                                        <div class="content_price price">
                                            @php
                                                if ($regularprice < $sp) {
                                                    $price = '<span class="price-new product-price">' . $tksign . number_format($sp) . '</span>';
                                                    $price .= '<span class="price-old">' . $tksign . number_format($regularprice) . '</span>';
                                                } else {
                                                    $price = '<span class="price-new product-price">' . $tksign . number_format($sp) . '</span>';
                                                }
                                            @endphp
                                            {!! $price !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>

            </div>
        </div>

        {{--        <!-- Health & Beauty -->--}}
        <div id="so_category_slider_191"
             class="so-category-slider container-slider module cate-slider2">
            <div class="modcontent">
                <div class="page-top">
                    <div class="page-title-categoryslider">
                        <a href="{{ url('c/' . $t->seo_url) }}" title="{{ $t->name }}"
                           target="_self">
                            {{ $term_third->name }}
                        </a>
                    </div>
                    <div class="menu-icone visible-xs">
                        <a href="javascript:void(0);">
                            <span><i class="fa fa-bars"></i></span>
                        </a>
                    </div>
                    <div class="item-sub-cat">
                        <div class="visible-xs">
                            <div class="menu-tab-mobile">
                                <ul>
                                    @php
                                        $terms = App\Term::where('parent', $tc[0])->get();
                                        $i = 0;

                                        foreach ($terms as $t) {
                                            echo '<li><a href="' . url('c/' . $t->seo_url) . '" title="' . $t->name . '" target="_self">' . $t->name . '</a></li>';
                                            if ($i == 3) {
                                                break;
                                            }
                                            $i++;
                                        }
                                    @endphp
                                    <li>
                                        <a class="viewall" style="color: #fb3b4e;"
                                           href="{{ url('c/' . $term_third->seo_url) }}">
                                            View All
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <ul class="hidden-xs">
                            @php
                                $terms = App\Term::where('parent', $tc[0])->get();
                                $i = 0;

                                foreach ($terms as $t) {
                                    echo '<li><a href="' . url('c/' . $t->seo_url) . '" title="' . $t->name . '" target="_self">' . $t->name . '</a></li>';
                                    if ($i == 3) {
                                        break;
                                    }
                                    $i++;
                                }
                            @endphp
                            <li>
                                <a class="viewall" style="color: #fb3b4e;"
                                   href="{{ url('c/' . $term_third->seo_url) }}">
                                    View All
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="categoryslider-content show preset01-4 preset02-4 preset03-3 preset04-2 preset05-1">

                <div class="slider category-slider-inner products-list" data-rtl="yes"
                     data-autoplay="no" data-autoheight="no" data-delay="4" data-speed="0.6"
                     data-margin="30" data-items_column00="4" data-items_column0="4"
                     data-items_column1="4" data-items_column2="3" data-items_column3="2"
                     data-items_column4="1" data-arrows="no" data-pagination="no" data-lazyload="yes"
                     data-loop="no" data-hoverpause="yes">

                    @foreach($products_third as $product)
                        @php
                            $pro = \App\Product::where('id', $product->main_pid)->get()->first();
                            if (!empty($pro)) {
                                $first_image = \App\ProductImages::where('main_pid', $pro->id)->where('is_main_image', 1)->get()->first();

                                if (!empty($first_image->full_size_directory)) {
                                    $img = url($first_image->full_size_directory);
                                } else {
                                    $img = url('storage/uploads/fullsize/2019-01/default.jpg');
                                }
                                $second_image = \App\ProductImages::where('main_pid', $pro->id)->where('is_main_image', 0)->get()->first();
                                $regularprice = $pro->local_selling_price;
                                $save = ($pro->local_selling_price * $pro->local_discount) / 100;
                                $sp = $regularprice - $save;

                                //dump($sp);

                                echo product_design([
                                    'bootstrap_cols' => 'col-md-3 col-sm-4 col-xs-6',
                                    'seo_url' => product_seo_url($pro->seo_url, $pro->id),
                                    'straight_seo_url' => $pro->seo_url,
                                    'title' => $pro->title,
                                    'first_image' => $img,
                                    'second_image' => !empty($second_image) ? url($second_image->full_size_directory) : null,
                                    'discount_rate' => $pro->local_discount,
                                    'price' => $sp,
                                    'old_price' => $regularprice,
                                    'descriptions' => $pro->description,
                                    'product_code' => $pro->product_code,
                                    'product_sku' => $pro->sku,
                                    'product_id' => $pro->id,
                                    'product_qty' => 1,
                                    'sign' => '&#2547; '
                                ]);
                            }
                        @endphp
                    @endforeach
                </div>
                <div class="item-cat-image box-items">
                    <h3>Best Selling</h3>
                    <div class="product-feature row">

                        @foreach($products_third_best_selling as $pro)
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

                            <div class="item col-lg-12 col-md-4 col-sm-4 col-xs-6">
                                <div class="item-inner">
                                    <div class="image">
                                        <a href="{{ url('p/' .  $pro->seo_url) }}" target="_self"
                                           title="{{ $pro->title }}">
                                            @if(!empty($first_image))
                                                <img src="{{ $img }}"
                                                     alt="{{ $pro->title }}">
                                            @endif
                                        </a>
                                    </div>
                                    <div class="caption">
                                        @php
                                            echo product_review($pro->id);
                                        @endphp

                                        <h4 class="item-title">
                                            <a href="{{ url('p/' .  $pro->seo_url) }}" target="_self"
                                               title="{{ $pro->title }}">
                                                {{ limit_text( $pro->title, 2) }}
                                            </a>
                                        </h4>

                                        <div class="content_price price">
                                            @php
                                                if ($regularprice < $sp) {
                                                    $price = '<span class="price-new product-price">' . $tksign . number_format($sp) . '</span>';
                                                    $price .= '<span class="price-old">' . $tksign . number_format($regularprice) . '</span>';
                                                } else {
                                                    $price = '<span class="price-new product-price">' . $tksign . number_format($sp) . '</span>';
                                                }
                                            @endphp
                                            {!! $price !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>

            </div>
        </div>

    </div>
    <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12 hidden-xs hidden-sm">
        @php
            $static_cats = dynamic_widget($widgets, ['id' => 28]);
        @endphp
        {!! $static_cats !!}
    </div>
</div>