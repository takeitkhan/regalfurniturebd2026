<?php
$flash_rule = array(
    'fs_is_active' => 1

);
$flash_schedule = App\FlashShedule::where($flash_rule)
    ->whereRaw('NOW() BETWEEN fs_start_date AND fs_end_date ')
    ->orderBy('id', 'ASC')->get()->first();

//dump($flash_schedule);

//dump($flash_schedule);
if(!empty($flash_schedule)) {
$flash_itmes = App\FlashItem::where(['fi_shedule_id' => $flash_schedule->id])->get();
date_default_timezone_set('Asia/Dhaka');

//dump($flash_schedule->fs_end_date);
$schedule = date_create($flash_schedule->fs_end_date);
//dump($schedule);
$today = date_create(date('Y-m-d h:i:s'));
$difference = date_diff($today, $schedule);
dump($difference);

if ($difference->y == 0 AND $difference->m == 0 || $difference->d == 0) {
    $year = date('Y');
    $month = date('m');
    $day = date('d');
    $hour = $difference->h;
    $min = $difference->i;
    $second = $difference->s;
} else {
    $year = $difference->y;
    $month = $difference->m;
    $day = $difference->d;
    $hour = $difference->h;
    $min = $difference->i;
    $second = $difference->s;
}
?>
@if($flash_itmes->count() > 0)
    <div class="head-title">
        <div class="modtitle">
            <span>Flash Sale</span>

            <div class="cslider-item-timer">
                <div class="product_time_maxprice">

                    <div class="item-time">
                        <div class="item-timer">
                            <script type="text/javascript">
                                var austDay = new Date('<?php echo $year; ?>', '<?php echo $month; ?>', '<?php echo $day; ?>', '<?php echo $hour; ?>', '<?php echo $min; ?>', '<?php echo $second; ?>');
                                //console.log(austDay);
                            </script>
                            <div class="defaultCountdown-30"></div>
                        </div>
                    </div>
                </div>
            </div>
            &nbsp; &nbsp; will ends
            <a class="viewall" href="{{ url('flash_products') }}">View All</a>
        </div>
    </div>
    <div class="modcontent">
        <div id="so_deal_1" class="so-deal style1">
            <div class="extraslider-inner products-list yt-content-slider" data-rtl="yes"
                 data-autoplay="no" data-autoheight="no" data-delay="4" data-speed="0.6"
                 data-margin="30" data-items_column00="6" data-items_column0="5"
                 data-items_column1="3" data-items_column2="2" data-items_column3="2"
                 data-items_column4="1" data-arrows="yes" data-pagination="no" data-lazyload="yes"
                 data-loop="yes" data-hoverpause="yes">
                @foreach($flash_itmes as $pro)
                    <?php
                    //dd($pro);
                    $fi = $pro;
                    //dump($fi);
                    $pro = \App\Product::where('id', $pro->fi_product_id)->get()->first();
                    $pro = (object)$pro;
                    $first_image = \App\ProductImages::where('main_pid', $pro->id)->where('is_main_image', 1)->get()->first();
                    $second_image = \App\ProductImages::where('main_pid', $pro->id)->where('is_main_image', 0)->get()->first();

                    $regularprice = $pro->local_selling_price;
                    $save = ($pro->local_selling_price * $pro->local_discount) / 100;
                    $sp = round($regularprice - $save);
                    ?>
                    <div class="item">
                        <div class="item-inner product-layout transition product-grid">
                            <div class="product-item-container">
                                <div class="left-block left-b">
                                    <div class="box-label">
                                        @if (!empty($pro->local_discount) || !empty($fi->fi_show_tag))
                                            @if(!empty($fi->fi_show_tag))
                                                <span class="label-product label-sale">{{ $fi->fi_show_tag . '%' }}</span>
                                            @else
                                                <span class="label-product label-sale">{{ $pro->local_discount . '%' }}</span>
                                            @endif
                                        @endif
                                    </div>
                                    <div class="product-image-container second_img">
                                        <a href="{{ url('p/' .  $pro->seo_url) }}" target="_self"
                                           title="{{ $pro->title }}">
                                            <?php if(!empty($first_image)) { ?>
                                            <img src="{!! url($first_image->full_size_directory) !!}"
                                                 class="img-1 img-responsive" alt="{{ $pro->title }}">
                                            <?php } else { ?>
                                        <?php } ?>
                                            <?php if (!empty($second_image)) { ?>
                                            <img src="{{ url($second_image->full_size_directory) }}"
                                                 class="img-2 img-responsive" alt="{{ $pro->title }}">
                                            <?php } else { ?>
                                            <img src="{{ url($first_image->full_size_directory) }}"
                                                 class="img-2 img-responsive" alt="{{ $pro->title }}">
                                            <?php } ?>
                                        </a>
                                    </div>
                                    <!--quickview-->
                                    <!-- <div class="so-quickview">
                                      <a class="iframe-link btn-button quickview quickview_handler visible-lg" href="quickview.html" title="Quick view" data-fancybox-type="iframe"><i class="fa fa-eye"></i><span>Quick view</span></a>
                                    </div> -->
                                    <!--end quickview-->


                                </div>
                                <div class="right-block">
                                    <div class="button-group so-quickview cartinfo--left">
                                        <button type="button"
                                                class="addToCart"
                                                title="Add to cart"
                                                onclick="add_to_cart('{{ $pro->id }}','{{ $pro->product_code }}','{{ $pro->sku }}','{{ $regularprice }}','{{ $regularprice - $sp }}', '{{ $sp }}', 0, null, 1);">
                                            <span>Add to cart </span>
                                        </button>
                                        {{--<button type="button"--}}
                                        {{--class="wishlist btn-button"--}}
                                        {{--title="Add to Wish List"--}}
                                        {{--onclick="add_to_wishlist('{{ $pro->id }}', '{{ $pro->product_code }}', '{{ $pro->seo_url }}');"--}}
                                        {{--href="javascript:void(0);">--}}
                                        {{--<i class="fa fa-heart-o"></i>--}}
                                        {{--<span>Add to Wish List</span>--}}
                                        {{--</button>--}}
                                        <button type="button"
                                                class="compare btn-button"
                                                title="Compare this Product"
                                                onclick="add_to_compare('{{ $pro->id }}', '{{ $pro->product_code }}', '{{ $pro->seo_url }}');"
                                                href="javascript:void(0);">
                                            <i class="fa fa-balance-scale"></i>
                                            <span>Compare this Product</span>
                                        </button>

                                    </div>

                                    <div class="caption hide-cont">
                                        <?php echo product_review_count($pro->id) ?>
                                        <h4>
                                            <a href="{{ url('p/' .  $pro->seo_url) }}" target="_self"
                                               title="{{ $pro->title }}">
                                                {{ $pro->title }}
                                            </a>
                                        </h4>

                                    </div>
                                    <p class="price">
                                        <?php
                                        if ($sp < $regularprice) {
                                            $price = '<span class="price-new">' . $tksign . $sp . ' ' . '</span>';
                                            $price .= '<span class="price-old">' . $tksign . number_format($regularprice) . '</span>';
                                        } else {
                                            $price = '<span class="price-new">' . $tksign . number_format($sp) . '</span>';
                                        }
                                        ?>
                                        {!! $price !!}
                                    </p>
                                </div>

                                {{--<div class="item-available">--}}
                                {{--<div class="available">--}}
                                {{--<span class="color_width" data-title="77%" data-toggle='tooltip'--}}
                                {{--style="width: 77%">--}}
                                {{--</span>--}}
                                {{--</div>--}}
                                {{--<p class="a2">Sold: <b>51</b></p>--}}
                                {{--</div>--}}
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
@endif
<?php } ?>