@php
    $regularprice = $pro->local_selling_price;
    $save = ($pro->local_selling_price * $pro->local_discount) / 100;
    $sp = $regularprice - $save;
@endphp
<div id="content" class="col-md-9 col-sm-8">
    <div class="product-view">
        <div class="left-content-product">
            <div class="row">
                <div class="content-product-left class-honizol col-md-5 col-sm-12 col-xs-12">
                    <div class="large-image">
                        @foreach($images as $image)
                            @if($image->is_main_image == 1)
                                <?php if(!empty($image)) { ?>
                                <img itemprop="image" class="product-image-zoom"
                                     src="{!! url($image->full_size_directory) !!}"
                                     data-zoom-image="{!! url($image->full_size_directory) !!}"/>
                                <?php } ?>
                            @endif
                        @endforeach
                    </div>
                    <div id="thumb-slider" class="yt-content-slider  owl-drag" data-rtl="yes"
                         data-autoplay="no" data-autoheight="no" data-delay="4" data-speed="0.6"
                         data-margin="10" data-items_column00="4" data-items_column0="4"
                         data-items_column1="3" data-items_column2="4" data-items_column3="4"
                         data-items_column4="3" data-arrows="yes" data-pagination="no" data-lazyload="yes"
                         data-loop="no" data-hoverpause="yes">
                        @foreach($images as $image)
                            @if($image->is_main_image == 1)
                                <?php $active = ' active'; ?>
                            @else
                                <?php $active = null; ?>
                            @endif
                            <a data-index="0" class="img thumbnail {{ $active }}"
                               data-image="{!! url($image->full_size_directory) !!}"
                               data-zoom-image="{!! url($image->full_size_directory) !!}"
                               title="{{ $pro->title }}">
                                <img src="{!! url($image->full_size_directory) !!}" title="{{ $pro->title }}"
                                     alt="{{ $pro->title }}">
                            </a>
                        @endforeach
                    </div>
                </div>
                <div class="content-product-right col-md-6 col-sm-12 col-xs-12">
                    <div class="title-product">
                        <h1>{{ $pro->title }}</h1>
                    </div>
                    @if(!empty($pro->sub_title))
                        <div class="price-tax">
                            {{ $pro->sub_title }}
                        </div>
                    @endif
                    <div class="price-tax-sub">Item Code: {{ $pro->sku }}</div>
                    <div class="box-review form-group">
                        <?php echo product_review($pro->id) ?>
                        <div class="rv-dt">
                            <a class="reviews_button" href=""
                               onclick="$('a[href=\'#tab-review\']').trigger('click'); return false;">
                                (<?php echo product_review_count_only($pro->id) ?>) reviews
                            </a> |
                            <a class="write_review_button" href=""
                               onclick="$('a[href=\'#tab-review\']').trigger('click'); return false;">
                                Write a review
                            </a>
                        </div>
                    </div>
                    <div class="product-label form-group">
                        <?php
                        $regularprice = $pro->local_selling_price;
                        $save = ($pro->local_selling_price * $pro->local_discount) / 100;
                        $sp = $regularprice - $save;
                        ?>

                        @if($pro->local_discount > 0)
                            <span class="regularprice" itemprop="price">
                                Regular Price: {{ $tksign . number_format($regularprice) }}
                            </span>
                            <br/>
                        @endif

                        <div class="product_page_price price" itemprop="offerDetails" itemscope=""
                             itemtype="http://data-vocabulary.org/Offer">


                            <span class="price-new" id="price_tag" itemprop="price">

                                @if($pro->local_discount > 0)
                                    Discount Price ({{ $pro->local_discount }}%): {{ $tksign . number_format($sp) }}
                                @else
                                    Price: {{ $tksign . number_format($sp) }}
                                @endif


                                @if($pro->local_discount > 0)
                                    <span style="font-size: 13px; color: #444; margin-left: 5px;">
                                        (Save {{ $tksign . number_format($save) }})
                                    </span>
                                @endif

                            </span>
                        </div>
                        <div class="stock">
                            <span>Availability:</span>
                            <span class="status-stock">In Stock</span>
                        </div>
                    </div>
                    @if(!empty($pro->emi_available) && $pro->emi_available == 'on')
                        @include('frontend.products.single.left_emi')
                    @endif
                    <div class="short_description form-group">
                        <h4>Overview</h4>
                        {!! $pro->short_description !!}
                    </div>
                    <div id="product">
                        @include('frontend.products.single.colors_sizes')


                        <div class="form-group box-info-product box-info-product-one">
                            <div class="option quantity">
                                <div class="input-group quantity-control" unselectable="on"
                                     style="-webkit-user-select: none;">
                                    <label>Qty</label>
                                    <input class="form-control"
                                           type="text"
                                           name="quantity"
                                           id="quantity"
                                           value="1">
                                    <input type="hidden" name="product_id" value="50">
                                    <span id="qty_down" class="input-group-addon product_quantity_down">−</span>
                                    <span id="qty_up" class="input-group-addon product_quantity_up">+</span>
                                </div>
                            </div>
                            <div class="cart cart_back" id="add_to_cart_btn">
                                <?php //dd($sp); ?>
                                <input type="button" data-toggle="tooltip" title="" value="Add to Cart"
                                       data-loading-text="Loading..." id="button-cart"
                                       class="btn btn-mega btn-lg"
                                       data-productid="{{ $pro->id }}"
                                       data-productcode="{{ $pro->product_code }}"
                                       data-productsku="{{ $pro->sku }}"
                                       data-regularprice="{{ $regularprice }}"
                                       data-saveprice="{{ $save }}"
                                       data-purchaseprice="{{ $sp }}"
                                       data-deliverycharge="0"
                                       data-imageurl=""
                                       data-qty="1"
                                       data-colorsize=""
                                       onclick="add_to_cart_data(this);"
                                       data-original-title="Add to Cart">
                            </div>
                            <div class="add-to-links wish_comp">
                                <ul class="blank list-inline">
                                    {{--<li class="wishlist">--}}
                                    {{--<a class="icons" data-toggle="tooltip" title=""--}}
                                    {{--onclick="add_to_wishlist('{{ $pro->id }}', '{{ $pro->product_code }}', '{{ $pro->seo_url }}');"--}}
                                    {{--href="javascript:void(0);"--}}
                                    {{--data-original-title="Add to Wish List">--}}
                                    {{--<i class="fa fa-heart"></i>--}}
                                    {{--</a>--}}
                                    {{--</li>--}}
                                    <li class="compare">
                                        <a class="icon" data-toggle="tooltip" title=""
                                           onclick="add_to_compare('{{ $pro->id }}', '{{ $pro->product_code }}', '{{ $pro->seo_url }}');"
                                           href="javascript:void(0);"
                                           data-original-title="Compare this Product">
                                            <i class="fa fa-balance-scale"></i>
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
    <div class="producttab">

        <div class="tabsslider horizontal-tabs  col-xs-12">
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#tab-1">Description</a></li>
                <li class="item_nonactive">
                    <a data-toggle="tab" href="#tab-specification">
                        Specification
                    </a>
                </li>
                <li class="item_nonactive"><a data-toggle="tab" href="#tab-review">Reviews ({{$review_count}})</a></li>
            </ul>
            <div class="tab-content review-det_one col-xs-12">
                <div id="tab-1" class="tab-pane fade active in">
                    <div class="col-md-12">
                        {!! $pro->description !!}
                    </div>

                </div>

                <div id="tab-specification" class="tab-pane fade">
                    <div class="col-md-12">
                        <?php
                        $attribute_data = \App\ProductAttributesData::leftJoin('attributes', function ($join) {
                            $join->on('productattributesdata.attribute_id', '=', 'attributes.id');
                        })->where('main_pid', $pro->id)->get();
                        ?>
                        <table class="table table-bordered table-responsive">
                            @foreach($attribute_data as $att)
                                @if(!empty($att->value))
                                    <tr>
                                        <td style="width:30%;">
                                            <span class="field-label">{{ $att->field_label }}</span>
                                        </td>
                                        <td><span class="field-value">{{ $att->value }}</span></td>
                                    </tr>
                                @endif
                            @endforeach

                        </table>
                    </div>
                </div>

                <div id="tab-review" class="tab-pane fade">
                    <div class="review-det">
                        <div class="col-md-7">
                            <div class="main-star">
                                <h3>average rating</h3>
                                <i class="fa fa-star"></i>
                                <p>based on {{$review_count}} rating</p>
                            </div>
                            <div class="lavle-star">

                                <div class="single-stare">
                                    <ul class="list-unstyled">
                                        <li class="lavle-star-left">
                                            <p>5 stars</p>
                                        </li>
                                        <li class="lavle-star-mid">
                                            <div class="progress">
                                                <div class="progress-bar progress-bg-none"
                                                     role="progressbar"
                                                     style="width: 100%"
                                                     aria-valuenow="100"
                                                     aria-valuemin="0"
                                                     aria-valuemax="100"></div>
                                            </div>
                                        </li>
                                        <li class="lavle-star-right">
                                            <span>({{$review_five}})</span>
                                        </li>
                                    </ul>
                                </div>
                                <!-- single-star end -->
                                <div class="single-stare">
                                    <ul class="list-unstyled">
                                        <li class="lavle-star-left">
                                            <p>4 stars</p>
                                        </li>
                                        <li class="lavle-star-mid">
                                            <div class="progress">
                                                <div class="progress-bar progress-bg-none"
                                                     role="progressbar"
                                                     style="width: 80%"
                                                     aria-valuenow="100"
                                                     aria-valuemin="0"
                                                     aria-valuemax="100"></div>
                                            </div>
                                        </li>
                                        <li class="lavle-star-right"><span>({{$review_four}})</span>
                                        </li>
                                    </ul>
                                </div>
                                <!-- single-star end -->
                                <div class="single-stare">
                                    <ul class="list-unstyled">
                                        <li class="lavle-star-left">
                                            <p>3 stars</p>
                                        </li>
                                        <li class="lavle-star-mid">
                                            <div class="progress">
                                                <div class="progress-bar progress-bg-none"
                                                     role="progressbar"
                                                     style="width: 60%"
                                                     aria-valuenow="100"
                                                     aria-valuemin="0"
                                                     aria-valuemax="100"></div>
                                            </div>
                                        </li>
                                        <li class="lavle-star-right">
                                            <span>({{$review_three}})</span>
                                        </li>
                                    </ul>
                                </div>
                                <!-- single-star end -->
                                <div class="single-stare">
                                    <ul class="list-unstyled">
                                        <li class="lavle-star-left">
                                            <p>2 stars</p>
                                        </li>
                                        <li class="lavle-star-mid">
                                            <div class="progress">
                                                <div class="progress-bar progress-bg-none"
                                                     role="progressbar"
                                                     style="width: 40%"
                                                     aria-valuenow="100"
                                                     aria-valuemin="0"
                                                     aria-valuemax="100"></div>
                                            </div>
                                        </li>
                                        <li class="lavle-star-right">
                                            <span>({{$review_two}})</span>
                                        </li>
                                    </ul>
                                </div>
                                <!-- single-star end -->

                                <div class="single-stare">
                                    <ul class="list-unstyled">
                                        <li class="lavle-star-left">
                                            <p>1 stars</p>
                                        </li>
                                        <li class="lavle-star-mid">
                                            <div class="progress">
                                                <div class="progress-bar progress-bg-none"
                                                     role="progressbar"
                                                     style="width: 20%"
                                                     aria-valuenow="100"
                                                     aria-valuemin="0"
                                                     aria-valuemax="100"></div>
                                            </div>
                                        </li>
                                        <li class="lavle-star-right">
                                            <span>({{$review_one}})</span>
                                        </li>
                                    </ul>
                                </div>
                                <!-- single-star end -->
                            </div>


                        </div>
                        <div class="col-md-5">

                            @if($user)
                                @if($is_buy)
                                    @php
                                        $my_review = \App\Review::where(['user_id' => $user->id, 'product_id' => $product->id, 'is_active' => 1])->get();

                                    @endphp
                                    <div class="remamber text-center">

                                        {{ Form::open(array('url' => '/save_review', 'method' => 'post', 'value' => 'PATCH', 'id' => 'save_review')) }}
                                        <h3>Have you used this product before?</h3>

                                        <div class="rating" id="singel_reviews">
                                            <span <?=((@$my_review[0]->rating == 5) ? 'class="checked"' : '')?>>
                                                <input type="radio" name="rating" id="str5"
                                                       value="5" <?=((@$my_review[0]->rating == 5) ? 'checked' : '')?>>
                                                <label for="str5"><i class="fa fa-star"></i></label>
                                            </span>
                                            <span <?=((@$my_review[0]->rating == 4) ? 'class="checked"' : '')?>>
                                                <input type="radio" name="rating" id="str4"
                                                       value="4" <?=((@$my_review[0]->rating == 4) ? 'checked="checked"' : '')?>>
                                                <label for="str4"><i class="fa fa-star"></i></label>
                                            </span>
                                            <span <?=((@$my_review[0]->rating == 3) ? 'class="checked"' : '')?>>
                                                <input type="radio" name="rating" id="str3"
                                                       value="3" <?=((@$my_review[0]->rating == 3) ? 'checked' : '')?>>
                                                <label for="str3"><i class="fa fa-star"></i></label>
                                            </span>
                                            <span <?=((@$my_review[0]->rating == 2) ? 'class="checked"' : '')?>>
                                                <input type="radio" name="rating" id="str2"
                                                       value="2" <?=((@$my_review[0]->rating == 2) ? 'checked' : '')?>>
                                                <label for="str2"><i class="fa fa-star"></i></label>
                                            </span>
                                            <span <?=((@$my_review[0]->rating == 1) ? 'class="checked"' : '')?>>
                                                <input type="radio" name="rating" id="str1"
                                                       value="1" <?=((@$my_review[0]->rating == 1) ? 'checked' : '')?>>
                                                <label for="str1"><i class="fa fa-star"></i></label>
                                            </span>
                                        </div>
                                        {{ Form::textarea('comment', @$my_review[0]->comment, ['rows' => 4, 'class' => 'form-control', 'placeholder' => 'Enter Access Notes','id' => 'access_notes']) }}
                                        <div class="remamber-btn" style="margin-top: 10px; ">
                                            {{ Form::submit('Write a review', ['class' => 'btn btn-primary', 'name' => 'submit']) }}
                                        </div>
                                        @if(@$my_review[0]->id)
                                            {{ Form::hidden('id', $my_review[0]->id, ['required']) }}
                                        @endif
                                        {{ Form::hidden('product_id', $product->id, ['required', 'id' => 'product_id']) }}
                                        {{ Form::hidden('user_id', @$user->id, ['required', 'id' => 'user_id']) }}

                                        {{ Form::close() }}


                                    </div>
                                @else
                                    <h3>Have you used this product before?</h3>
                                @endif
                            @else

                                <h3>Have you used this product before?</h3>
                            @endif
                        </div>

                        <div class="col-md-12">
                            <div class="one-bkcde">
                                <div class="panel panel-default">
                                    <!--    <div class="panel-heading">Panel Heading</div> -->
                                    <div class="review_comment">
                                        <?php
                                        $all_review = \DB::table('reviews as r')
                                            ->where(['r.product_id' => $pro->id, 'r.is_active' => 1])
                                            ->leftJoin('users as u', 'u.id', '=', 'r.user_id')
                                            ->get();
                                        ?>
                                        <ul class="list-unstyled">
                                            @foreach($all_review as $review)
                                                <li>
                                                    <div class="review-post-ct">
                                                        <h3>{{$review->name}}</h3>
                                                        <span> <?php echo review_star($review->rating) ?></span>
                                                        <p>{{$review->comment}}</p>
                                                    </div>
                                                </li>
                                            @endforeach
                                            <li class="rew-btn">
                                                <button class="btn">View More</button>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>

            </div>
        </div>
    </div>
    @include('frontend.products.single.related_on_left')
    @include('frontend.products.single.similar_on_left')
    @include('frontend.products.single.recently_on_left')

</div>