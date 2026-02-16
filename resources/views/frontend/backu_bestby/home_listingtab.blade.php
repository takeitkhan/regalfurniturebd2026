<?php
//SELECT product_id, AVG(rating) AS rate FROM `reviews` group by product_id order by rating desc limit 0, 10
//$most_rated = \App\Review::groupBy('product_id')->orderBy('rating', 'desc')->avg('rating')->select( 'product_id', \DB::raw( 'AVG( rating )' ))->get();
//dump($most_rated);
?>

<div class="module listingtab-layout1">

    <div id="so_listing_tabs_1" class="so-listing-tabs first-load">
        <div class="loadeding"></div>
        <div class="ltabs-wrap">
            <div class="ltabs-tabs-container" data-delay="300" data-duration="600"
                 data-effect="starwars" data-ajaxurl="" data-type_source="0" data-lg="5" data-md="3"
                 data-sm="2" data-xs="1" data-margin="30">
                <!--Begin Tabs-->
                <div class="ltabs-tabs-wrap">
                    <span class="ltabs-tab-selected">Bathroom</span>
                    <span class="ltabs-tab-arrow">▼</span>
                    <div class="item-sub-cat">
                        <ul class="ltabs-tabs cf">
                            <li class="ltabs-tab tab-sel" data-category-id="20"
                                data-active-content=".items-category-20">
                                <span class="ltabs-tab-label">Express Delivery</span>
                            </li>
                            <li onclick="get_tab_data('new_arrival')" class="ltabs-tab" data-category-id="18"
                                data-active-content=".items-category-18">
                                <span class="ltabs-tab-label">New Arrivals</span>
                            </li>
                            <li onclick="get_tab_data('most_rated')" class="ltabs-tab " data-category-id="25"
                                data-active-content=".items-category-25">
                                <span class="ltabs-tab-label">Most Rating</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- End Tabs-->
            </div>

            <div class="ltabs-items-container products-list grid">
                @php
                    $recommended = App\Product::where('express_delivery', 'on')->orderBy('id', 'desc')->limit(8)->get()->toArray();
                @endphp
                <div class="ltabs-items ltabs-items-selected items-category-20" data-total="16">

                    <div class="ltabs-items-inner ltabs-slider">


                        @foreach($recommended as $pro)
                            @php
                                $pro = (object)$pro;
                                $first_image = \App\ProductImages::where('main_pid', $pro->id)->where('is_main_image', 1)->get()->first();

                                if (!empty($first_image->full_size_directory)) {
                                    $img = url($first_image->full_size_directory);
                                } else {
                                    $img = url('storage/uploads/fullsize/2019-01/default.jpg');
                                }
                                $second_image = \App\ProductImages::where('main_pid', $pro->id)->where('is_main_image', 0)->get()->first();
                                $regularprice = $pro->local_selling_price;
                                $save = ($pro->local_selling_price * $pro->local_discount) / 100;
                                $sp = round($regularprice - $save);
                            @endphp
                            <div class="item">
                                <div class="item-inner product-layout transition product-grid">
                                    <div class="product-item-container">
                                        <div class="left-block left-b">
                                            @if($pro->new_arrival == 'on')
                                                <div class="box-label">
                                                    <span class="label-product label-new">New</span>
                                                </div>
                                            @endif
                                            <div class="product-image-container second_img">
                                                <a href="{{ url('p/' .  $pro->seo_url) }}" target="_self"
                                                   title="{{ $pro->title }}">
                                                    @if(!empty($first_image))
                                                        <img src="{{ $img }}"
                                                             class="img-1 img-responsive"
                                                             alt="{{ $pro->title }}">
                                                    @endif

                                                    @if(!empty($second_image))
                                                        <img src="{{ url($second_image->full_size_directory) }}"
                                                             class="img-2 img-responsive"
                                                             alt="{{ $pro->title }}">
                                                    @else
                                                        <img src="{{ $img }}"
                                                             class="img-2 img-responsive"
                                                             alt="{{ $pro->title }}">
                                                    @endif
                                                </a>
                                            </div>

                                        </div>
                                        <div class="right-block">
                                            <div class="button-group so-quickview cartinfo--left">
                                                <button type="button" class="addToCart"
                                                        title="Add to cart"
                                                        onclick="add_to_cart('{{ $pro->id }}','{{ $pro->product_code }}','{{ $pro->sku }}','{{ $regularprice }}','{{ $regularprice - $sp }}', '{{ $sp }}', 0, null, 1);">
                                                    <span>Add to cart </span>
                                                </button>
                                                {{--<button type="button" class="wishlist btn-button"--}}
                                                {{--title="Add to Wish List"--}}
                                                {{--onclick="add_to_wishlist('{{ $pro->id }}', '{{ $pro->product_code }}', '{{ $pro->seo_url }}');"--}}
                                                {{--href="javascript:void(0);">--}}
                                                {{--<i class="fa fa-heart-o"></i>--}}
                                                {{--<span>Add to Wish List</span>--}}
                                                {{--</button>--}}
                                                <button type="button" class="compare btn-button"
                                                        title="Compare this Product "
                                                        onclick="add_to_compare('{{ $pro->id }}', '{{ $pro->product_code }}', '{{ $pro->seo_url }}');"
                                                        href="javascript:void(0);">
                                                    <i class="fa fa-retweet"></i>
                                                    <span>Compare this Product</span>
                                                </button>

                                            </div>
                                            <div class="caption hide-cont">

                                                <?php echo product_review($pro->id) ?>
                                                <h4>
                                                    <a href="{{ url('p/' .  $pro->seo_url) }}" target="_self"
                                                       title="{{ $pro->title }}">
                                                        {{ limit_text( $pro->title, 50) }}
                                                    </a>
                                                </h4>

                                            </div>


                                            <p class="price">
                                                <span class="price-new">
                                                    @php
                                                    if ($regularprice < $sp) {
                                                        $price = '<span class="price-new product-price">' . $tksign . number_format($sp) . '</span>';
                                                        $price .= '<span class="price-old">' . $tksign . number_format($regularprice) . '</span>';
                                                    } else {
                                                        $price = '<span class="price-new product-price">' . $tksign . number_format($sp) . '</span>';
                                                    }
                                                    @endphp
                                                    {!! $price !!}
                                                </span>
                                            </p>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        @endforeach


                    </div>
                </div>
                <div class="ltabs-items items-category-18 grid" data-total="16">
                    <div class="ltabs-loading"></div>
                </div>

                <div class="ltabs-items  items-category-25 grid" data-total="16">
                    <div class="ltabs-loading"></div>
                </div>
            </div>

        </div>
    </div>
</div>



