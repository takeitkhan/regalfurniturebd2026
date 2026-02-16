@php
    $top_rating = App\Review::select(
            'product_id as proid',
            DB::raw('COUNT(product_id) as total_item'),
            DB::raw('SUM(rating) as total_rating'),
            DB::raw('( SUM(rating) / COUNT(product_id)) AS avg_rating')
        )
        ->where(['is_active' => 1])
        ->groupBy('product_id')
        ->orderBy('avg_rating', 'DESC')
        ->get();

@endphp


<div id="so_category_slider_188" class="so-category-slider container-slider module cate-slider1">
    <h4 class="modtitle">Top Rated</h4>

    <div class="modcontent">
        <div class="page-top">
            <div class="item-sub-cat">
                <ul>
                    <li><a href="#" title="View All">View All</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="categoryslider-content">
        <div class="category-slider-inner products-list yt-content-slider force_width" data-rtl="yes"
             data-autoplay="no" data-autoheight="no" data-delay="4" data-speed="0.6" data-margin="30"
             data-items_column00="6" data-items_column0="5" data-items_column1="4"
             data-items_column2="3" data-items_column3="2" data-items_column4="2" data-arrows="yes"
             data-pagination="no" data-lazyload="yes" data-loop="yes" data-hoverpause="yes">


            @foreach($top_rating as $product)
                @php
                    $pro = \App\Product::where('id', $product->proid)->get()->first();
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
                @endphp
            @endforeach
            {{--                        <div class="item">--}}
            {{--                            <div class="item-inner product-layout transition product-grid">--}}
            {{--                                <div class="product-item-container">--}}
            {{--                                    <div class="left-block left-b">--}}
            {{--                                        <div class="box-label">--}}
            {{--                                            <span class="label-product label-sale">-11%</span>--}}
            {{--                                        </div>--}}
            {{--                                        <div class="product-image-container second_img">--}}
            {{--                                            <a href="product.html" target="_self" title="Pastrami bacon">--}}
            {{--                                                <img src="{{ url('public/frontend/image/catalog/demo/product/270/fu3.jpg') }}"--}}
            {{--                                                     class="img-1 img-responsive" alt="image1">--}}
            {{--                                                <img src="{{ url('public/frontend/image/catalog/demo/product/270/fu4.jpg') }}"--}}
            {{--                                                     class="img-2 img-responsive" alt="image2">--}}
            {{--                                            </a>--}}
            {{--                                        </div>--}}

            {{--                                    </div>--}}
            {{--                                    <div class="right-block">--}}
            {{--                                        <div class="button-group so-quickview cartinfo--left">--}}
            {{--                                            <button type="button" class="addToCart" title="Add to cart"--}}
            {{--                                                    onclick="cart.add('60 ');">--}}
            {{--                                                <span>Add to cart </span>--}}
            {{--                                            </button>--}}
            {{--                                            <button type="button" class="wishlist btn-button"--}}
            {{--                                                    title="Add to Wish List" onclick="wishlist.add('60');"><i--}}
            {{--                                                        class="fa fa-heart-o"></i><span>Add to Wish List</span>--}}
            {{--                                            </button>--}}
            {{--                                            <button type="button" class="compare btn-button"--}}
            {{--                                                    title="Compare this Product " onclick="compare.add('60');"><i--}}
            {{--                                                        class="fa fa-retweet"></i><span>Compare this Product</span>--}}
            {{--                                            </button>--}}

            {{--                                        </div>--}}
            {{--                                        <div class="caption hide-cont">--}}
            {{--                                            <div class="ratings">--}}
            {{--                                                <div class="rating-box"><span class="fa fa-stack"><i--}}
            {{--                                                                class="fa fa-star fa-stack-2x"></i></span>--}}
            {{--                                                    <span class="fa fa-stack"><i--}}
            {{--                                                                class="fa fa-star fa-stack-2x"></i></span>--}}
            {{--                                                    <span class="fa fa-stack"><i--}}
            {{--                                                                class="fa fa-star fa-stack-2x"></i></span>--}}
            {{--                                                    <span class="fa fa-stack"><i--}}
            {{--                                                                class="fa fa-star fa-stack-2x"></i></span>--}}
            {{--                                                    <span class="fa fa-stack"><i--}}
            {{--                                                                class="fa fa-star fa-stack-2x"></i></span>--}}
            {{--                                                </div>--}}
            {{--                                                <span class="rating-num">( 2 )</span>--}}
            {{--                                            </div>--}}
            {{--                                            <h4><a href="product.html" title="Pastrami bacon" target="_self">Pastrami--}}
            {{--                                                    bacon</a></h4>--}}

            {{--                                        </div>--}}
            {{--                                        <p class="price">--}}
            {{--                                            <span class="price-new">$85.00</span>--}}
            {{--                                            <span class="price-old">$96.00</span>--}}
            {{--                                        </p>--}}
            {{--                                    </div>--}}
            {{--                                </div>--}}
            {{--                            </div>--}}
            {{--                        </div>--}}

        </div>
    </div>
</div>