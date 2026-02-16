<?php
// First Cat
$first_cat = $homesettig->cat_first;
$fcc = explode('|', $first_cat);
//dump($fc);
$term_first = \App\Term::where('id', $fcc[0])->get()->first();
$products_first = \App\ProductCategories::leftJoin('products', function ($join) {
    $join->on('productcategories.main_pid', '=', 'products.id');
})->where(['productcategories.term_id' => $term_first->id])->limit(15)->get();

//dump($products_first);
// Second Cat
$cat_second = $homesettig->cat_second;
$sc = explode('|', $cat_second);
$term_second = \App\Term::where('id', $sc[0])->get()->first();
$products_second = \App\ProductCategories::leftJoin('products', function ($join) {
    $join->on('productcategories.main_pid', '=', 'products.id');
})->where(['productcategories.term_id' => $term_second->id])->limit(15)->get();

// Third Cat
$cat_third = $homesettig->cat_third;
$tc = explode('|', $cat_third);
$term_third = \App\Term::where('id', $tc[0])->get()->first();
$products_third = \App\ProductCategories::leftJoin('products', function ($join) {
    $join->on('productcategories.main_pid', '=', 'products.id');
})->where(['productcategories.term_id' => $term_third->id])->limit(15)->get();
//dd($products_third);

// Fourth Cat
if (!empty($homesettig->cat_fourth)) {
    $cat_fourth = $homesettig->cat_fourth;
    $fc = explode('|', $cat_fourth);
    $term_fourth = \App\Term::where('id', $fc[0])->get()->first();
    $products_fourth = \App\ProductCategories::leftJoin('products', function ($join) {
        $join->on('productcategories.main_pid', '=', 'products.id');
    })->where(['productcategories.term_id' => $term_fourth->id])->limit(15)->get();
}

// Fifth Cat
if (!empty($homesettig->cat_fifth)) {
    $cat_fifth = $homesettig->cat_fifth;
    $fic = explode('|', $cat_fifth);
    $term_fifth = \App\Term::where('id', $fic[0])->get()->first();
    $products_fifth = \App\ProductCategories::leftJoin('products', function ($join) {
        $join->on('productcategories.main_pid', '=', 'products.id');
    })->where(['productcategories.term_id' => $term_fifth->id])->limit(15)->get();
}

// Sixth Cat
if (!empty($homesettig->cat_sixth)) {
    $cat_sixth = $homesettig->cat_sixth;
    $tc = explode('|', $cat_sixth);
    $term_sixth = \App\Term::where('id', $tc[0])->get()->first();
    $products_sixth = \App\ProductCategories::leftJoin('products', function ($join) {
        $join->on('productcategories.main_pid', '=', 'products.id');
    })->where(['productcategories.term_id' => $term_sixth->id])->limit(15)->get();
}


// Seventh Cat
if (!empty($homesettig->cat_seventh)) {
    $cat_seventh = $homesettig->cat_seventh;
    $tc = explode('|', $cat_seventh);
  $term_seventh = \App\Term::where('id', $tc[0])->get()->first();
    $products_seventh = \App\ProductCategories::leftJoin('products', function ($join) {
        $join->on('productcategories.main_pid', '=', 'products.id');
       })->where(['productcategories.term_id' => $term_seventh->id])->limit(15)->get();
}

// Eighth Cat
if (!empty($homesettig->cat_eighth)) {
    $cat_eighth = $homesettig->cat_eighth;
    $tc = explode('|', $cat_eighth);
    $term_eighth = \App\Term::where('id', $tc[0])->get()->first();
    $products_eighth = \App\ProductCategories::leftJoin('products', function ($join) {
        $join->on('productcategories.main_pid', '=', 'products.id');
    })->where(['productcategories.term_id' => $term_eighth->id])->limit(15)->get();
}


?>
<div id="so_category_slider_1" class="so-category-slider container-slider module cateslider1">
    <div class="modcontent">
        <div class="page-top">
            <div class="page-title-categoryslider">
                <a href="{{ url('c/' . $term_first->seo_url) }}" title="{{ $term_first->name }}"
                   target="_self">
                    {{ $term_first->name }}
                </a>
            </div>
            <div class="item-sub-cat">
                <ul>
                    <?php
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
                    ?>
                    <li>
                        <a class="viewall" style="color: #fb3b4e;"
                           href="{{ url('c/' . $term_first->seo_url) }}">
                            View All
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="categoryslider-content">
            <div class="item-cat-image" style="min-height: 351px;">
                <a href="{{ url('c/' . $term_first->seo_url) }}" title="{{ $term_first->name }}" target="_self">
                    <img class="categories-loadimage" alt="{{ $term_first->name }}"
                         src="{{ $term_first->home_image }}">
                </a>
            </div>
            <div class="slider category-slider-inner products-list yt-content-slider" data-rtl="yes"
                 data-autoplay="yes" data-autoheight="no" data-delay="4" data-speed="0.4"
                 data-margin="30" data-items_column00="4" data-items_column0="4"
                 data-items_column1="2" data-items_column2="1" data-items_column3="2"
                 data-items_column4="1" data-arrows="yes" data-pagination="no" data-lazyload="yes"
                 data-loop="yes" data-hoverpause="yes">

                @foreach($products_first as $product)
                    <?php
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
                            'bootstrap_cols' => 'item',
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
                    ?>
                @endforeach

            </div>
        </div>
    </div>
</div>


<div id="so_category_slider_1" class="so-category-slider container-slider module cateslider2">
    <div class="modcontent">
        <div class="page-top">
            <div class="page-title-categoryslider">
                <a href="{{ url('c/' . $term_second->seo_url) }}" title="{{ $term_second->name }}"
                   target="_self">
                    {{ $term_second->name }}
                </a>
            </div>
            <div class="item-sub-cat">
                <ul>
                    <?php
                    $terms = App\Term::where('parent', $sc[0])->get();
                    $i = 0;

                    foreach ($terms as $t) {
                        echo '<li><a href="' . url('c/' . $t->seo_url) . '"  title="' . $t->name . '" target="_self">' . $t->name . '</a></li>';
                        if ($i == 3) {
                            break;
                        }
                        $i++;
                    }
                    ?>
                    <li>
                        <a class="viewall" style="color: #fb3b4e;"
                           href="{{ url('c/' . $term_second->seo_url) }}">
                            View All
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="categoryslider-content">
            <div class="item-cat-image" style="min-height: 351px;">
                <a href="{{ url('c/' . $term_second->seo_url) }}" title="{{ $term_second->name }}" target="_self">
                    <img class="categories-loadimage" alt="{{ $term_second->name }}"
                         src="{{ $term_second->home_image }}">
                </a>
            </div>
            <div class="slider category-slider-inner products-list yt-content-slider" data-rtl="yes"
                 data-autoplay="yes" data-autoheight="no" data-delay="4" data-speed="0.6"
                 data-margin="30" data-items_column00="4" data-items_column0="4"
                 data-items_column1="2" data-items_column2="1" data-items_column3="2"
                 data-items_column4="1" data-arrows="yes" data-pagination="no" data-lazyload="yes"
                 data-loop="yes" data-hoverpause="yes">

                @foreach($products_second as $product)
                    <?php
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
                        'bootstrap_cols' => 'item',
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
                    ?>
                @endforeach

            </div>
        </div>
    </div>
</div>

<div id="so_category_slider_1" class="so-category-slider container-slider module cateslider1">
    <div class="modcontent">
        <div class="page-top">
            <div class="page-title-categoryslider">
                <a href="{{ url('c/' . $t->seo_url) }}" title="{{ $t->name }}"
                   target="_self">
                    {{ $term_third->name }}
                </a>
            </div>
            <div class="item-sub-cat">
                <ul>
                    <?php
                    $terms = App\Term::where('parent', $tc[0])->get();
                    $i = 0;

                    foreach ($terms as $t) {
                        echo '<li><a href="' . url('c/' . $t->seo_url) . '" title="' . $t->name . '" target="_self">' . $t->name . '</a></li>';
                        if ($i == 3) {
                            break;
                        }
                        $i++;
                    }
                    ?>
                    <li>
                        <a class="viewall" style="color: #fb3b4e;"
                           href="{{ url('c/' . $term_third->seo_url) }}">
                            View All
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="categoryslider-content">
            <div class="item-cat-image" style="min-height: 351px;">
                <a href="{{ url('c/' . $term_third->seo_url) }}" title="{{ $term_third->name }}" target="_self">
                    <img class="categories-loadimage" alt="{{ $term_third->name }}"
                         src="{{ $term_third->home_image }}">
                </a>
            </div>
            <!-- <div class="slider category-slider-inner products-list yt-content-slider" data-rtl="yes"
                 data-autoplay="no" data-autoheight="no" data-delay="4" data-speed="0.6"
                 data-margin="30" data-items_column00="4" data-items_column0="4"
                 data-items_column1="2" data-items_column2="1" data-items_column3="2"
                 data-items_column4="1" data-arrows="yes" data-pagination="no" data-lazyload="yes"
                 data-loop="yes" data-hoverpause="yes"> -->
                 <div class="slider category-slider-inner products-list yt-content-slider" data-rtl="yes"
                 data-autoplay="yes" data-autoheight="no" data-delay="4" data-speed="0.8"
                 data-margin="30" data-items_column00="4" data-items_column0="4"
                 data-items_column1="2" data-items_column2="1" data-items_column3="2"
                 data-items_column4="1" data-arrows="yes" data-pagination="no" data-lazyload="yes"
                 data-loop="yes" data-hoverpause="yes">

                @foreach($products_third as $product)
                    <?php
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
                            'bootstrap_cols' => 'item',
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
                    ?>
                @endforeach
            </div>

        </div>
    </div>
</div>

@if(!empty($term_fourth))
    <div id="so_category_slider_1" class="so-category-slider container-slider module cateslider2">
        <div class="modcontent">
            <div class="page-top">
                <div class="page-title-categoryslider">
                    <a href="{{ url('c/' . $term_fourth->seo_url) }}" title="{{ $term_fourth->name }}"
                       target="_self">
                        {{ $term_fourth->name }}
                    </a>
                </div>
                <div class="item-sub-cat">
                    <ul>
                        <?php
                        $terms_f = App\Term::where('parent', $fc[0])->get();
                        $i = 0;

                        foreach ($terms_f as $t) {
                            echo '<li><a href="' . url('c/' . $t->seo_url) . '"  title="' . $t->name . '" target="_self">' . $t->name . '</a></li>';
                            if ($i == 3) {
                                break;
                            }
                            $i++;
                        }
                        ?>
                        <li>
                            <a class="viewall" style="color: #fb3b4e;"
                               href="{{ url('c/' . $term_fourth->seo_url) }}">
                                View All
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="categoryslider-content">
                <div class="item-cat-image" style="min-height: 351px;">
                    <a href="{{ url('c/' . $term_fourth->seo_url) }}" title="{{ $term_fourth->name }}" target="_self">
                        <img class="categories-loadimage" alt="{{ $term_fourth->name }}"
                             src="{{ $term_fourth->home_image }}">
                    </a>
                </div>
               <!--  <div class="slider category-slider-inner products-list yt-content-slider" data-rtl="yes"
                     data-autoplay="no" data-autoheight="no" data-delay="4" data-speed="0.6"
                     data-margin="30" data-items_column00="4" data-items_column0="4"
                     data-items_column1="2" data-items_column2="1" data-items_column3="2"
                     data-items_column4="1" data-arrows="yes" data-pagination="no" data-lazyload="yes"
                     data-loop="yes" data-hoverpause="yes"> -->
                     <div class="slider category-slider-inner products-list yt-content-slider" data-rtl="yes"
                 data-autoplay="yes" data-autoheight="no" data-delay="4" data-speed="0.5"
                 data-margin="30" data-items_column00="4" data-items_column0="4"
                 data-items_column1="2" data-items_column2="1" data-items_column3="2"
                 data-items_column4="1" data-arrows="yes" data-pagination="no" data-lazyload="yes"
                 data-loop="yes" data-hoverpause="yes">

                    @foreach($products_fourth as $product)
                        <?php
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

                            echo product_design([
                                'bootstrap_cols' => 'item',
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
                        ?>
                    @endforeach

                </div>
            </div>
        </div>
    </div>
@endif

@if(!empty($term_fifth))
    <div id="so_category_slider_1" class="so-category-slider container-slider module cateslider1">
        <div class="modcontent">
            <div class="page-top">
                <div class="page-title-categoryslider">
                    <a href="{{ url('c/' . $t->seo_url) }}" title="{{ $t->name }}"
                       target="_self">
                        {{ $term_fifth->name }}
                    </a>
                </div>
                <div class="item-sub-cat">
                    <ul>
                        <?php
                        $terms = App\Term::where('parent', $fic[0])->get();
                        $i = 0;

                        foreach ($terms as $t) {
                            echo '<li><a href="' . url('c/' . $t->seo_url) . '" title="' . $t->name . '" target="_self">' . $t->name . '</a></li>';
                            if ($i == 3) {
                                break;
                            }
                            $i++;
                        }
                        ?>
                        <li>
                            <a class="viewall" style="color: #fb3b4e;"
                               href="{{ url('c/' . $term_fifth->seo_url) }}">
                                View All
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="categoryslider-content">
                <div class="item-cat-image" style="min-height: 351px;">
                    <a href="{{ url('c/' . $term_fifth->seo_url) }}" title="{{ $term_fifth->name }}" target="_self">
                        <img class="categories-loadimage" alt="{{ $term_fifth->name }}"
                             src="{{ $term_fifth->home_image }}">
                    </a>
                </div>
               <!--  <div class="slider category-slider-inner products-list yt-content-slider" data-rtl="yes"
                     data-autoplay="no" data-autoheight="no" data-delay="4" data-speed="0.6"
                     data-margin="30" data-items_column00="4" data-items_column0="4"
                     data-items_column1="2" data-items_column2="1" data-items_column3="2"
                     data-items_column4="1" data-arrows="yes" data-pagination="no" data-lazyload="yes"
                     data-loop="yes" data-hoverpause="yes"> -->
                     <div class="slider category-slider-inner products-list yt-content-slider" data-rtl="yes"
                 data-autoplay="yes" data-autoheight="no" data-delay="4" data-speed="0.8"
                 data-margin="30" data-items_column00="4" data-items_column0="4"
                 data-items_column1="2" data-items_column2="1" data-items_column3="2"
                 data-items_column4="1" data-arrows="yes" data-pagination="no" data-lazyload="yes"
                 data-loop="yes" data-hoverpause="yes">

                    @foreach($products_fifth as $product)
                        <?php
                        $pro = \App\Product::where('id', $product->main_pid)->get()->first();
                        if (!empty($pro)) {
                            $first_image = \App\ProductImages::where('main_pid', $pro->id)->where('is_main_image', 1)->get()->first();

                            if (!empty($first_image->full_size_directory)) {
                                $img = url($first_image->full_size_directory);
                            } else {
                                $img = url('storage/uploads/fullsize/2019-01/default.jpg');
                            }
                            //dd($pro->id);
                            $second_image = \App\ProductImages::where('main_pid', $pro->id)->where('is_main_image', 0)->get()->first();

                            $regularprice = $pro->local_selling_price;
                            $save = ($pro->local_selling_price * $pro->local_discount) / 100;
                            $sp = $regularprice - $save;

                            echo product_design([
                                'bootstrap_cols' => 'item',
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
                        ?>
                    @endforeach
                </div>

            </div>
        </div>
    </div>
@endif