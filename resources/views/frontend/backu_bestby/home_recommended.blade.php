<h3 class="modtitle">
    <span>Recommended</span>
</h3>
<div class="modcontent">
    <div id="so_extra_slider_1" class="so-extraslider">
        <!-- Begin extraslider-inner -->
        <div class="products-list yt-content-slider extraslider-inner" data-rtl="yes"
             data-pagination="yes" data-autoplay="yes" data-delay="4" data-speed="0.6"
             data-margin="0" data-items_column00="1" data-items_column0="1"
             data-items_column1="1" data-items_column2="1" data-items_column3="2"
             data-items_column4="1" data-arrows="no" data-lazyload="yes" data-loop="yes"
             data-buttonpage="top">

            @php
                $recommended = App\Product::where('recommended', 'on')->orderBy('id', 'desc')->limit(3)->get()->toArray();
            @endphp

            @foreach($recommended as $pro)
                <?php
                $pro = (object)$pro;
                $first_image = \App\ProductImages::where('main_pid', $pro->id)->where('is_main_image', 1)->get()->first();
                $second_image = \App\ProductImages::where('main_pid', $pro->id)->where('is_main_image', 0)->get()->first();
                $regularprice = $pro->local_selling_price;
                $save = ($pro->local_selling_price * $pro->local_discount) / 100;
                $sp = round($regularprice - $save);
                //dump($first_image);
                ?>
                <div class="item">
                    <div class="item-inner product-layout transition product-grid">
                        <div class="product-item-container">
                            <div class="left-block left-b">
                                <div class="product-image-container second_img">
                                    <a href="{{ url('p/' .  $pro->seo_url) }}" target="_self" title="{{ $pro->title }}">
                                        <?php if (!empty($second_image)) { ?>
                                        <img src="{{ url($first_image->full_size_directory) }}"
                                             class="img-1 img-responsive" alt="{{ $pro->title }}">
                                        <img src="{{ URL::asset($second_image->full_size_directory) }}"
                                             class="img-2 img-responsive" alt="{{ $pro->title }}">
                                        <?php } else { ?>
                                        <img src="{{ url($first_image->full_size_directory) }}"
                                             class="img-1 img-responsive" alt="{{ $pro->title }}">
                                        <img src="{{ url($first_image->full_size_directory) }}"
                                             class="img-2 img-responsive" alt="{{ $pro->title }}">
                                        <?php } ?>
                                    </a>
                                </div>
                            </div>
                            <div class="right-block">
                                <div class="button-group so-quickview cartinfo--left">
                                    <button type="button" class="addToCart" title="Add to cart"
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
                                            {{ $pro->title }}
                                        </a>
                                    </h4>

                                </div>
                                <?php
                                if ($regularprice < $sp) {
                                    $price = '<p class="price">' . $tksign . $sp . '</p>';
                                } else {
                                    $price = '<p class="price">' . $tksign . $sp . '</p>';
                                }
                                ?>
                                {!! $price !!}
                            </div>

                        </div>
                    </div>
                </div>

            @endforeach
        </div>
        <!--End extraslider-inner -->
    </div>
</div>