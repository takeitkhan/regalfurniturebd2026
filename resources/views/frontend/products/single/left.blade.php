@php
$regularprice = $pro->local_selling_price;
$save = ($pro->local_selling_price * $pro->local_discount) / 100;
$sp = $regularprice - $save;
$questions = App\ProductQuestion::Where(['main_pid' => $pro->id, 'qa_type' => 1])->take(5)->get();
$reviews = App\Review::Where(['product_id' => $pro->id, 'is_active' => 1])->take(5)->get();
@endphp
<style>
/*.desktops{*/
/*    display: block;*/
/*}*/
/*.mobshow{*/
/*    display: none;*/
/*}*/
@media screen and (max-width: 480px) {
/*.desktops{*/
/*    display: none;*/
/*}*/
/*.mobshow{*/
/*    display: block;*/
/*}*/
}
.product-image-zoom {
-ms-touch-action: pan-y;
touch-action: pan-y;
}
div.share_buttons {
margin: 25px 0;
}
p.share_text {
font-weight: bold;
font-size: 17px;
font-style: italic;
display: inline-table;
margin-right: 10px;
}
.share_buttons ul.eagles_buttons {
list-style: none;
list-style-type: none;
display: inline-table;
}
ul.eagles_buttons li {
display: inline;
background: #EEEEEE;
border: 1px solid #E3E3E3;
margin-right: 5px;
padding: 5px 0;
}
ul.eagles_buttons li a {
padding: 2px 10px;
display: inline-block;
}
ul.eagles_buttons li a:hover {
background: #ed0b31;
color: #FFFFFF;
}
</style>

<div id="content" class="col-md-12">
    <div class="single-page-area">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-lg-4 col-sm-12 sidebar_one">
                    <div class="zoom-warp">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 view-product-image">
                                @if($pro->threeSixtyDegreeImage->count())
                                <div class="t60degreeview" style="display:none;">
                                    <div class="spritespin"></div>
                                    <div class="drag_drop"><span class="drag_left"><a href="javascript:void(0)">&#8592;</a></span>Drag to rotate<span class="drag_right"><a href="javascript:void(0)">&#8594;</a></span></div>
                                </div>
                                @endif
                                @if(isset($pro->youtubeVideo->link))
                                <div class="embed-youtube-video" style="display:none;">
                                    <iframe style="padding:0;border:0;" src="https://www.youtube.com/embed/{{$pro->youtubeVideo->link??''}}"></iframe>
                                </div>
                                @endif
                                
                                <div class="zoom-lg desktops">
                                    @foreach($images as $image)
		                            @if($image->is_main_image == 1)
				                    <?php if(!empty($image)) { ?>
		                                <div class="zoomWrapper">
        						            <img id="img_01" itemprop="image" class="product-image-zoom"
        						            src="{!! url($image->full_size_directory) !!}"
        						            data-zoom-image="{!! url($image->full_size_directory) !!}"/>
						                </div>
				                    <?php } ?>
		                            @endif
                                    @endforeach
                                </div>
                                
                            </div>
                            <div class="col-md-12">
                                <div id="gal1" class="zoom-list" style="margin-top: 0px;">
                                    <ul class="list-unstyled piclist owl-carousel owl-theme">
                                        @if($pro->threeSixtyDegreeImage->count())
                                        <li>
                                            <a href="javascript:void(0)" class="detect-gallery-type" data-type="360degree"><img src="{{asset('public/frontend/img/360-degree.png')}}"></a>
                                        </li>
                                        @endif
                                        
                                        @if(isset($pro->youtubeVideo->link))
                                        <li>
                                            <a href="javascript:void(0)" class="detect-gallery-type" data-type="youtube"><img src="{{asset('public/frontend/img/video-icon.png')}}"></a>
                                        </li>
                                        @endif
                                        
                                        @foreach($images as $image)
                                        @if($image->is_main_image == 1)
                                        <?php $active = ' active'; ?>
                                        @else
                                        <?php $active = null; ?>
                                        @endif
                                        <li>
                                            <a class="{{ $active }} detect-gallery-type" data-type="image" href="#" data-image="{!! url($image->full_size_directory) !!}" data-zoom-image="{!! url($image->full_size_directory) !!}">
                                                <img id="img_01" src="{!! url($image->full_size_directory) !!}" />
                                            </a>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-8 col-sm-12 content_one">
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="single-page-contant single-page-contant-left">
                                <!-- top -->
                                <div class="single-page-contant-top">
 
                                            <div class="title-product">
                                                <h1></h1>
                                            </div>
                                            <div class="single-page-pd-title">
                                                <h3>{{ $pro->title }}</h3>
                                            </div>
                                            @if(!empty($pro->sub_title))
                                            <div class="single-page-pd-item">
                                                <h4>Item Name: <span>{{ $pro->sub_title }}</span></h4>
                                            </div>
                                            @endif
                                            <div class="single-page-contant-det">
                                                <p>Product Code : <span id="v-itemcode">{{ $pro->sku }}</span></p>
                                            </div>
                                            <div class="single-page-contant-rd">
                                                <?php echo product_review_count($pro->id) ?>
                                                {{--<div class="single-page-contant-cs-rev">--}}
                                                    {{--<p>1,470 Answer Questions</p>--}}
                                                {{--</div>--}}
                                            </div>
                                            <div id="price_tag">
                                            </div>
                                            <div class="price">
                                                <div id="price_tag">
                                                </div>
                                                <div class="price-btm">
                                                    @if($pro->emi_available == 'on')
                                                    <p>0% EMI Available : <span class="emi">Min Tk. 3.331/ month</span> <a class="emi-bank-dt" href="#">EMI Bank Details</a></p>
                                                    <div class="emi-bank-dt-img">
                                                        <ul class="emi-bank-warp owl-carousel owl-theme list-unstyled">
                                                            <li><a href=""><img src="{{ url('/') }}/storage/uploads/fullsize/2019-03/bank6.png" alt=""></a></li>
                                                            <li><a href=""><img src="{{ url('/') }}/storage/uploads/fullsize/2019-03/bank7.png" alt=""></a></li>
                                                            <li><a href=""><img src="{{ url('/') }}/storage/uploads/fullsize/2019-03/bank5.png" alt=""></a></li>
                                                            <li><a href=""><img src="{{ url('/') }}/storage/uploads/fullsize/2019-03/bank1.png" alt=""></a></li>
                                                            <li><a href=""><img src="{{ url('/') }}/storage/uploads/fullsize/2019-03/bank3.png" alt=""></a></li>
                                                            <li><a href=""><img src="{{ url('/') }}/storage/uploads/fullsize/2019-03/bank4.png" alt=""></a></li>
                                                            <li><a href=""><img src="{{ url('/') }}/storage/uploads/fullsize/2019-03/bank2.png" alt=""></a></li>
                                                            
                                                        </ul>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                    
                                </div>
                                <!-- mid -->
                                <div class="single-page-contant-mid">
                                    <div class="single-page-contant-det">
                                        <?php
                                        $attribute_data2 = \App\ProductAttributesData::leftJoin('attributes', function ($join) {
                                        $join->on('productattributesdata.attribute_id', '=', 'attributes.id');
                                        })->where('main_pid', $pro->id)->get();
                                        ?>
                                        @foreach($attribute_data2 as $att)
                                        @if($att->key == 'dimension' || $att->key == 'material')
                                        <p>{{ $att->field_label }} : <span>{{ $att->value }}</span></p>
                                        @endif
                                        @endforeach
                                        @include('frontend.products.single.colors_sizes')
                                        <div class="mcbi_box">
                                            <div class="mcbi_increase mcbi_horizontal">
                                                <span>Quantity :</span>
                                                <div class="quantity-warp">
                                                    <button type="button" class="mcbi_sub mcbi_sub1">-</button>
                                                    <input type="text" class="mcbi_view" value="1"/>
                                                    <button type="button" class="mcbi_add mcbi_add2">+</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="buy-sg-area ">
                                        <div class="buy-sg-btn">

                                            @if(($pro->enable_timespan == 1 && $pro->disable_buy == 'on') || $pro->disable_buy == 'off')
                                            <a href="javascript:void(0)"
                                                id="button-cart"
                                                data-color_id = ""
                                                data-size_id = ""
                                                data-qty="1"
                                                data-productid="{{ $pro->id }}"
                                                onclick="add_to_cart_data(this);">
                                                Buy Now
                                            </a>
                                            @elseif($pro->disable_buy == 'on')
                                                <a href="javascript:void(0)" id="button-cart">
                                                    Stock Out
                                                </a>
                                            @endif
                                            
                                            <!--@if($pro->disable_buy == 'on')-->
                                            <!--<a href="javascript:void(0)" id="button-cart">-->
                                            <!--    Stock Out-->
                                            <!--</a>-->
                                            <!--@else-->
                                            <!--<a href="javascript:void(0)"-->
                                            <!--    id="button-cart"-->
                                            <!--    data-color_id = ""-->
                                            <!--    data-size_id = ""-->
                                            <!--    data-qty="1"-->
                                            <!--    data-productid="{{ $pro->id }}"-->
                                            <!--    onclick="add_to_cart_data(this);">-->
                                            <!--    Buy Now-->
                                            <!--</a>-->
                                            <!--@endif-->
                                        </div>
                                        <div class="buy-sg-vt">
                                            <a data-toggle="tooltip" data-placement="top" id="addwishlist" onclick="add_to_wish_list(this);" href="javascript:void(0);" data-proid="{{ $pro->id }}"><i class="fa fa-heart-o"></i></a>
                                        </div>
                                    </div>
                                    <div class="share_buttons">
                                        <p class="share_text">Share</p>
                                        
                                        <ul class="eagles_buttons">
                                            <li><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u={{ url('product/' . $pro->seo_url) }}&amp;src=sdkpreparse"><i class="fa fa-facebook"></i></a></li>
                                            <li><a target="_blank" href="https://twitter.com/share?url={{ url('product/' . $pro->seo_url) }}&text={{ $pro->title }}"><i class="fa fa-twitter"></i></a></li>
                                            <li><a target="_blank" href="https://pinterest.com/pin/create/bookmarklet/?url={{ url('product/' . $pro->seo_url) }}&description={{ $pro->title }}"><i class="fa fa-pinterest"></i></a></li>
                                            <li><a target="_blank" href="http://www.linkedin.com/shareArticle?url={{ url('product/' . $pro->seo_url) }}&title={{ $pro->title }}"><i class="fa fa-linkedin"></i></a></li>
                                            <li><a target="_blank" href="http://www.stumbleupon.com/submit?url={{ url('product/' . $pro->seo_url) }}&title={{ $pro->title }}"><i class="fa fa-stumbleupon"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <!-- btm -->
                            </div>
                            
                            <div class="right-single-bar">
                                <div class="having-area">
                                    <div class="dilie-det_oner">
                                        <div class="dilie-det_oner-wp">
                                            <i class="fa fa-volume-control-phone"></i>
                                        </div>
                                        <div class="dilie-det">
                                            <div class="sub-site-title">
                                                <h3>Order By Call:</h3>
                                            </div>
                                            @php
                                            $g_setting = App\Setting::first();
                                            
                                            @endphp
                                            
                                            @if($g_setting->order_phone)
                                            
                                            @php
                                            $order_phones = explode(',',$g_setting->order_phone);
                                            foreach($order_phones as $op){
                                                echo '<p>'.$op.'</p>';
                                            }
                                            @endphp
                                            @endif
                                            
                                            
                                        </div>
                                    </div>

                                    
                                    <div class="dilie-det_oner">
                                        <div class="dilie-det_oner-wp">
                                            <i class="fa fa-truck"></i>
                                        </div>
                                        <div class="delivery sub-site-title">
                                            <h3>Delivery Option</h3>
                                            <p>Area : All over Bangladesh</p>
                                            
                                         @if($pro->enable_timespan == 1 && $pro->disable_buy == 'on')
                                         @php
                                         $cat_info = \App\Term::where('id', $categories[0]['term_id'])->first();
                                         $timespan = $cat_info->timespan->timespan??false;
                                         @endphp
                                         
                                         @if($timespan)                                            
                                            <p>Times:<span> {{$timespan}}</span></p>
                                            <p class="text-muted">{{$cat_info->timespan->description}}</p>
                                         @endif
                                         
                                         @elseif($pro->disable_buy == 'off')
                                         
                                            <p>Times:<span> {{$widgets[11]->description??''}}</span></p>

                                         @endif
                                        </div>
                                    </div>
                                    
                                    <div class="dilie-det_oner">
                                        <div class="dilie-det_oner-wp">
                                            <i class="fa fa-gg-circle"></i>
                                        </div>
                                        <div class="sub-site-title">
                                            <h3>Warranty:</h3>
                                            <p>1 year Service Warranty</p>
                                        </div>
                                    </div>
                                    <div class="dilie-det_oner">
                                        <div class="dilie-det_oner-wp">
                                            <i class="fa fa-yelp"></i>
                                        </div>
                                        <div class="sub-site-title">
                                            <h3>Payment Methods:</h3>
                                            <p>Mobile Banking</p>
                                            <p>Debit/ Credit card payments</p>
                                        </div>
                                    </div>
                                    <div class="dilie-det_oner">
                                        <div class="dilie-det_oner-wp">
                                            <i class="fa fa-lock"></i>
                                        </div>
                                        <div class="sub-site-title sub-site-title_oamsr">
                                            <h3>Payments security guaranteed:</h3>
                                            <ul class="list-unstyled">
                                                <li><img src="{{ url('/') }}/public/frontend/images/pay/visa.png" alt=""></li>
                                                <li><img src="{{ url('/') }}/public/frontend/images/pay/rocket.png" alt=""></li>
                                                <li><img src="{{ url('/') }}/public/frontend/images/pay/mastercardd.png" alt=""></li>
                                                <li><img src="{{ url('/') }}/public/frontend/images/pay/islamic-bank--mobile-banking.png" alt=""></li>
                                                <li><img src="{{ url('/') }}/public/frontend/images/pay/islamic-bank--Internet-banking.png" alt=""></li>
                                                <li><img src="{{ url('/') }}/public/frontend/images/pay/IFIC-mobile-banking.png" alt=""></li>
                                                <li><img src="{{ url('/') }}/public/frontend/images/pay/dbbl-nexus.png" alt=""></li>
                                                <li><img src="{{ url('/') }}/public/frontend/images/pay/Bikash.png" alt=""></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!--<div class="right-single-bar">-->
                            <!--    <div class="having-area">-->
                            <!--        <div class="dilie-det">-->
                            <!--            <p><span><i class="fa fa-volume-control-phone"></i></span>Having trouble placing the order?</p>-->
                            <!--            <p>Please Call:09613737777</p>-->
                            <!--        </div>-->
                            <!--        <div class="delivery sub-site-title">-->
                            <!--            <h3>Delivery Option</h3>-->
                            <!--            <img src="{{ url('/') }}/storage/uploads/fullsize/2019-03/sub-site-img.png" alt="">-->
                            <!--            <p>Delivery Area : All over Bangladesh</p>-->
                            <!--            <p>Delivery Times : 7-10 Days</p>-->
                            <!--        </div>-->
                            <!--        <div class="sub-site-title">-->
                            <!--            <h3>Warranty:</h3>-->
                            <!--            <p>1 year</p>-->
                            <!--        </div>-->
                            <!--        <div class="sub-site-title">-->
                            <!--            <h3>Payment Methods:</h3>-->
                            <!--            <p>-bkash payments</p>-->
                            <!--            <p>-rocket payments</p>-->
                            <!--            <p>-other mobile banking payments</p>-->
                            <!--            <p>-EMI payments</p>-->
                            <!--            <p>-Debit/ Credit card payments</p>-->
                            <!--        </div>-->
                            <!--    </div>-->
                            <!--</div>-->
                            
                        </div>
                    </div>
                    <!--  -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="prosuct-sg-dec-all">
                                <div class="prosuct-sg-dec prosuct-sg-dec_ahrselrc_new">
                                    <div class="prosuct-sg-dec-title">
                                        <h3>Product Description</h3>
                                    </div>
                                    <div class="prosuct-sg-dec-area prosuct-pt-dt">
                                        <div class="prosuct-sg-dec-text prosuct-sg-dec-text-on">
                                            {!! $pro->description !!}
                                        </div>
                                        
                                    </div>
                                    <!--<div class="prosuct-sg-dec-area prosuct-pt-dt">-->
                                    <!--    <div class="prosuct-sg-dec-thumb prosuct-sg-dec-thumb2 ">-->
                                    <!--        <img src="images/222121.png" alt="product">-->
                                    <!--    </div>-->
                                    <!--    <div class="prosuct-sg-dec-text prosuct-sg-dec-text-on">-->
                                    <!--        <h3>100% Termite Resistant </h3>-->
                                    <!--        <p>Powered by the DuraPower technology, this Philips trimmer is designed to optimize power consumption so it lasts four times more than regular trimmers. Once fully charged, this trimmer offers you up to 30 minutes of uninterrupted usage. Its stainless steel blades sharpen themselves while you are trimming, so you can trim yourself every day as effectively as you did on day one. </p>-->
                                    <!--    </div>-->
                                    <!--</div>-->
                                    <!--<div class="prosuct-sg-dec-area prosuct-pt-dt">-->
                                    <!--    <div class="prosuct-sg-dec-text prosuct-sg-dec-text-on">-->
                                    <!--        <h3>lasting Performance</h3>-->
                                    <!--        <p>Powered by the DuraPower technology, this Philips trimmer is designed to optimize power consumption so it lasts four times more than regular trimmers. Once fully charged, this trimmer offers you up to 30 minutes of uninterrupted usage. Its stainless steel blades sharpen themselves while you are trimming, so you can trim yourself every day as effectively as you did on day one. </p>-->
                                    <!--    </div>-->
                                    <!--    <div class="prosuct-sg-dec-thumb prosuct-sg-dec-thumb2">-->
                                    <!--        <img src="images/222121.png" alt="product">-->
                                    <!--    </div>-->
                                    <!--</div>-->
                                </div>
                                <!--
                                <div class="ove-hidenk ove-hidenk1">
                                    <div class="prosuct-sg-dec-text" title="View all features">
                                        <a href="javascript:void(0);">View all features</a>
                                    </div>
                                </div>
                                -->
                                <!--  -->
                                <div class="specifications">
                                    <div class="prosuct-sg-dec-title">
                                        <h3>Specifications</h3>
                                    </div>
                                    <div class="prosuct-sg-dec-area">
                                        <div class="prosuct-sg-dec-text">
                                            <div class="single-page-contant-det">
                                                <?php
                                                $attribute_data = \App\ProductAttributesData::leftJoin('attributes', function ($join) {
                                                $join->on('productattributesdata.attribute_id', '=', 'attributes.id');
                                                })->where('main_pid', $pro->id)->get();
                                                ?>
                                                @foreach($attribute_data as $att)
                                                @if(!empty($att->value))
                                                <p>{{ $att->field_label }} : <span>{{ $att->value }}</span></p>
                                                @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="ove-hidenk ove-hidenk2">
                                    <div class="prosuct-sg-dec-text">
                                        <a href="javascript:void(0);">View all features</a>
                                    </div>
                                </div>
                                <div class="prosuct-sg-dec prosuct-sg-dec-area_002">
                                    <div class="prosuct-sg-dec-title">
                                        <h3>Ratings and Reviews
                                        <a class="pull-right btn-default btn" href="{{ url('product_rating/'.$pro->id) }}">More Reviews</a>
                                        </h3>
                                    </div>
                                    <div class="prosuct-sg-dec-area ">
                                        @foreach($reviews as $review)
                                        @php
                                        $customer =  App\User::Where('id', $review->user_id)->get()->first();
                                        @endphp
                                        <div class="single-qu">
                                            <p class="qu_pg"><span class="single-qu_on rating_post"> {{ $review->rating }} <i class="fa fa-star"></i></span>: {{ $review->comment }}</p>
                                            <p class="q-athur"><i class="fa fa-check-circle"></i> {{ $customer->name }}</p>
                                        </div>
                                        @endforeach
                                        <div class="qu-ans">
                                            <button type="button" class="btn qu-ans_one" data-toggle="modal" data-target="#reviewsModal">Post Your Review</button>
                                        </div>
                                        
                                        <div class="modal fade" id="reviewsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title modal-title125" id="exampleModalLabel"><span style="font-weight: bold"></span> Post Your Review and Rating
                                                        </h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        @if(auth()->check())
                                                        @php
                                                        $my_review = \App\Review::where(['user_id' => auth()->user()->id, 'product_id' => $product->id, 'is_active' => 1])->get();
                                                        @endphp
                                                        {{ Form::open(array('url' => '/save_review', 'method' => 'post', 'value' => 'PATCH', 'id' => 'save_review')) }}
                                                        <div class="rating">
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
                                                        <textarea class="form-control" name="comment" id="" cols="30" rows="10" placeholder="Your Questions"></textarea>
                                                        <div class="qution_oner_tw" style="margin-top: 15px">
                                                            {{ Form::submit('Write a review', ['class' => 'btn qu-ans_one', 'name' => 'submit']) }}
                                                        </div>
                                                        @if(@$my_review[0]->id)
                                                        {{ Form::hidden('id', $my_review[0]->id, ['required']) }}
                                                        @endif
                                                        {{ Form::hidden('product_id', $product->id, ['required', 'id' => 'product_id']) }}
                                                        {{ Form::hidden('vendor_id', $product->user_id, ['required', 'id' => 'product_id']) }}
                                                        {{ Form::hidden('user_id',auth()->user()->id, ['required', 'id' => 'user_id']) }}
                                                        {{ Form::close() }}
                                                        @else
                                                        <p>Only authentication user can review.</p>
                                                        <br/>
                                                        <a class="btn btn-success" href="{{url('/login_now')}}">Login now</a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- <div class="ove-hidenk ove-hidenk3">
                                    <div class="prosuct-sg-dec-text" title="View all features">
                                        <a href="javascript:void(0);">View all features</a>
                                    </div>
                                </div>-->
                                
                                <div class="prosuct-sg-dec prosuct-sg-dec-area_002">
                                    <div class="prosuct-sg-dec-title">
                                        <h3>Comments and Reactions
                                        <a class="pull-right btn-default btn" href="{{ route('product_comments',$pro->id) }}">More Comments</a>
                                        </h3>
                                    </div>
                                    
                                    <div class="prosuct-sg-dec-area ">
                                        @foreach($pro->comments()->limit(5)->get() as $comment)
                                        
                                        <div class="single-qu">
                                            <p class="qu_pg">{{$comment->comment??''}}</p>
                                            <p class="q-athur"><i class="fa fa-check-circle"></i> {{$comment->user->name??''}}, {{$comment->created_at->diffForHumans()}}</p>
                                        </div>
                                        @endforeach
                                        <div class="qu-ans">
                                            <button type="button" class="btn qu-ans_one" data-toggle="modal" data-target="#commentModal">Post Your Comment</button>
                                        </div>
                                        
                                        <div class="modal fade" id="commentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title modal-title125" id="exampleModalLabel"><span style="font-weight: bold"></span> Post Your Comment and Reaction</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        @if(Auth::check())
                                                        <form action="{{route('product_comment_save',$product->id)}}" method="post">
                                                            {{ csrf_field() }}
                                                            <div class="form-group">
                                                                <label for="comment">Write Comment</label>
                                                                <textarea class="form-control" id="comment" rows="3" name="comment"></textarea>
                                                            </div>
                                                            <input type="submit" class="btn btn-primary btn-success" value="Submit">
                                                        </form>
                                                        @else
                                                        
                                                        <div class="text-danger">Sorry you must have to be logged user to write a comment.</div>
                                                        
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div style="clear: both;" class="clear"></div>
                                
                                <div class="prosuct-sg-dec prosuct-sg-dec_001">
                                    <div class="prosuct-sg-dec-title">
                                        <h3>Questions and Answers
                                       <a class="pull-right btn-default btn" href="{{ url('product_questions/'.$pro->id) }}">More Questions</a>
                                        </h3>
                                    </div>
                                    <div class="prosuct-sg-dec-area">
                                        @foreach($questions as $que)
                                        @php
                                        $customer =  App\User::Where('id', $que->user_id)->get()->first();
                                        $answer = App\ProductQuestion::Where('que_id', $que->id)->get();
                                        @endphp
                                        <div class="single-qu">
                                            <p class="qu_pg"><span class="single-qu_on">Q</span>: {{ $que->description }}</p>
                                            <p class="q-athur"><i class="fa fa-check-circle"></i> {{ $customer->name }}</p>
                                            @foreach($answer as $ans)
                                            @php
                                            $admin =  App\User::Where('id', $que->user_id)->get()->first();
                                            @endphp
                                            <p class="qu_pg_on"><span class="single-qu_on">A</span>: {{ $ans->description }}</p>
                                            <p class="q-athur"><i class="fa fa-check-circle"></i> {{$admin->name}}</p>
                                            @endforeach
                                        </div>
                                        @endforeach
                                        <div class="qu-ans">
                                            <button type="button" class="btn qu-ans_one" data-toggle="modal" data-target="#questionModal">Post Your Questions</button>
                                        </div>
                                       
                                        <div class="modal fade" id="questionModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title modal-title125" id="exampleModalLabel"><span style="font-weight: bold">Q:</span> Post Your Questions</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        {{ Form::open(array('url' => '/product_question_post/'.$product->id, 'method' => 'post', 'value' => 'PATCH', 'id' => 'product_question_post')) }}
                                                        <textarea class="form-control" name="post" id="" cols="30" rows="10" placeholder="Your Questions"></textarea>
                                                        <div class="qution_oner_tw" style="margin-top: 15px">
                                                            {{ Form::submit('Post Your Question', [ 'class' => 'btn qu-ans_one','name' => 'submit_post' ]) }}
                                                        </div>
                                                        {{ Form::close() }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--<div class="ove-hidenk ove-hidenk4">
                                    <div class="prosuct-sg-dec-text" title="View all features">
                                        <a href="javascript:void(0);">View all features</a>
                                    </div>
                                </div>-->
                            </div>
                        </div>
                    </div>
                    <!--  -->
                </div>
            </div>
        </div>
    </div>
    @include('frontend.products.single.similar_on_left')
    @include('frontend.products.single.recently_on_left')
</div>
