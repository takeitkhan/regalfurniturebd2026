@php
    $regularprice = $pro->local_selling_price;
    $save = ($pro->local_selling_price * $pro->local_discount) / 100;
    $sp = $regularprice - $save;

    $is_flash = is_flash_item($product->id);
    if($is_flash){
        $flash_id = $is_flash['flash_item'];
    }else{
        $flash_id = null;
    }

    //dump($is_flash['discount_tag']);

@endphp
<?php
$regularprice = $pro->local_selling_price;
if ($is_flash) {
    $save = $is_flash['discount'];
} else {
    $save = ($pro->local_selling_price * $pro->local_discount) / 100;
}

$sp = $regularprice - $save;
?>

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

                    @if($is_flash)
                        @php
                            date_default_timezone_set('Asia/Dhaka');
                            $schedule = date_create($is_flash['end_time']);
                            $sole =  $is_flash['old_qty'] - $pro->qty;
                            $sole_par = $sole * 100 / $is_flash['old_qty'];
                        @endphp
                        <script type="text/javascript">
                            var homeFtime = new Date('<?php echo $schedule->format('Y'); ?>', '<?php echo $schedule->format('m') - 1; ?>', '<?php echo $schedule->format('d'); ?>', '<?php echo $schedule->format('H'); ?>', '<?php echo $schedule->format('i'); ?>', '<?php echo $schedule->format('s'); ?>');
                        </script>
                        <div class="input-group" id="single-flash-tag">
                            <span class="input-group-addon"><b>Flash Sale</b></span>
                            <div class="form-control">
                                <div class="item-timer-mini">
                                    <div class="item-timer-mini2">
                                        <span>End in :</span>
                                        <div class="defaultCountdown-mini"></div>
                                    </div>
                                </div>
                                <div class="soldout pull-right">
                                    <div style="width: 35%;float: left;" class="progresssafl">Sold
                                        <span>{{$sole}}</span></div>

                                    <div style="width: 65%; float: left;" class="progress">
                                        <div class="progress-bar" role="progressbar" aria-valuenow="{{$sole_par}}"
                                             aria-valuemin="0" aria-valuemax="100" style="width:{{$sole_par.'%'}}">
                                            <span class="sr-only">{{$sole_par.'%'}}</span>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                    @endif
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
                            </a> | 
                            <div id="demo" class="need-share-button-default"></div>
                        </div>
                    </div>
                    <div class="product-label form-group">
                        <div id="price_tag">

                        </div>

                        {{--@if($pro->local_discount > 0)--}}
                        {{--<span class="regularprice" itemprop="price">--}}
                        {{--Regular Price: {{ $tksign . number_format($regularprice) }}--}}
                        {{--</span>--}}
                        {{--<br/>--}}
                        {{--@endif--}}

                        {{--<div class="product_page_price price" itemprop="offerDetails" itemscope=""--}}
                        {{--itemtype="http://data-vocabulary.org/Offer">--}}


                        {{--<span class="price-new" itemprop="price">--}}

                        {{--@if($pro->local_discount > 0)--}}
                        {{--Discount Price ({{ $pro->local_discount }}%): {{ $tksign . number_format($sp) }}--}}
                        {{--@else--}}
                        {{--Price: {{ $tksign . number_format($sp) }}--}}
                        {{--@endif--}}


                        {{--@if($pro->local_discount > 0)--}}
                        {{--<span style="font-size: 13px; color: #444; margin-left: 5px;">--}}
                        {{--(Save {{ $tksign . number_format($save) }})--}}
                        {{--</span>--}}
                        {{--@endif--}}

                        {{--</span>--}}
                        {{--</div>--}}
                        <div class="stock">
                            <span>Availability : </span>
                            @if ($pro->stock_status == 2 || $stockout == true)
                            <span style="color:red;">
                                <i class="fa fa-times" aria-hidden="true"></i>
                                Sold Out
                            </span>
                            @else
                            <span style="color:green;">
                                <i class="fa fa-check" aria-hidden="true"></i>
                                In Stock
                            </span>
                            @endif
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
                                           value="1" max="5">
                                    <input type="hidden" name="product_id" value="50">
                                    <span id="qty_down" class="input-group-addon product_quantity_down">−</span>
                                    <span id="qty_up" class="input-group-addon product_quantity_up">+</span>
                                </div>
                            </div>
                            <div class="cart cart_back" id="add_to_cart_btn">
                                @php
                                    if(!empty($pro->emi_available) && $pro->emi_available == 'on'){
                                        $emis_nulls = \App\Emi::where('main_pid', $pro->id)->whereRaw('interest IS NULL')->groupBy('bank_id')->get()->first();
                                        if(isset($emis_nulls->month_range)){
                                            $emi_manth = $emis_nulls->month_range;
                                        }else{
                                            $emi_manth = 1;
                                        }

                                    }else{
                                        $emi_manth = 'off';

                                    }
                                @endphp
                                @if($pro->disable_buy == 'on')
                                    <input type="button" data-toggle="tooltip" title=""
                                           value="{{ ($pro->disable_buy == 'on')? 'Disable Buy Now': 'Add to Cart' }}"
                                           class="btn btn-mega btn-lg"
                                           data-original-title="{{ ($pro->disable_buy == 'on')? 'Disable Buy Now': 'Add to Cart' }}"
                                            {{ ($pro->disable_buy == 'on')? 'disabled': '' }}>
                                @else
                                    @if ($pro->stock_status == 2 || $stockout == true)
                                        <input type="button" disabled data-toggle="tooltip" title=""
                                        value="{{ ($pro->disable_buy == 'on')? 'Disable Buy Now': 'Sold Out' }}"
                                        class="btn btn-mega btn-lg"
                                        data-original-title="{{ ($pro->disable_buy == 'on') ? 'Disable Buy Now': 'Sold Out' }}"
                                        {{ ($pro->disable_buy == 'on')? 'disabled': '' }}>
                                     @else
                                        <input type="button" data-toggle="tooltip" title=""
                                           value="{{ ($pro->disable_buy == 'on')? 'Disable Buy Now': 'Add to Cart' }}"
                                           data-loading-text="Loading..." id="button-cart"
                                           class="btn btn-mega btn-lg"
                                           data-color_id = ""
                                           data-size_id = ""
                                           data-productid="{{ $pro->id }}"
                                           data-qty="1"
                                           onclick="add_to_cart_data(this);"
                                           data-original-title="{{ ($pro->disable_buy == 'on')? 'Disable Buy Now': 'Add to Cart' }}"
                                            {{ ($pro->disable_buy == 'on')? 'disabled': '' }}>
                                    @endif
                                @endif
                            </div>




                            <div class="add-to-links wish_comp">
                                <ul class="blank list-inline">
                                    @auth
                                    <li class="wishlist">
                                        {{-- <a class="icons" data-toggle="tooltip" title="Add to Wish List"
                                        onclick="add_to_wishlist('{{ $pro->id }}', '{{ $pro->product_code }}', '{{ $pro->seo_url }}');"
                                        href="javascript:void(0);"
                                        data-original-title="Add to Wish List">
                                        <i class="fa fa-heart"></i>
                                        </a> --}}
                                        <a class="icons" data-toggle="tooltip" title="Add to Wish List" id="addwishlist" href="javascript:void(0);">
                                        <i class="fa fa-heart"></i>
                                        </a>
                                    </li>
                                    @endauth
                                    <li class="compare">
                                        <a class="icon" data-toggle="tooltip" title="Compare this Product"
                                           onclick="add_to_compare('{{ $pro->id }}', '{{ $pro->product_code }}', '{{ $pro->seo_url }}');"
                                           href="javascript:void(0);"
                                           data-original-title="Compare this Product">
                                            <i class="fa fa-balance-scale"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>

                        </div>

                        @if($pro->order_by_phone == 'on')
                            @php
                                $order_call = App\Setting::first()->order_phone;
                            @endphp
                            @if($order_call != null)
                                <br>
                                <div class="order_by_phone">Order by Phone Call: <span>{{ $order_call }}</span></div>
                             @endif
                        @endif
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