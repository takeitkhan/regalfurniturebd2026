<?php
$flash_rule = array(
    'fs_is_active' => 1

);
$flash_schedule = App\FlashShedule::where($flash_rule)
    ->whereRaw('NOW() BETWEEN fs_start_date AND fs_end_date ')
    ->orderBy('fs_start_date', 'ASC')->get()->first();

//dump($flash_schedule);

//dump($flash_schedule);
if(!empty($flash_schedule)) {
$flash_itmes = App\FlashItem::where(['fi_shedule_id' => $flash_schedule->id])->get();

date_default_timezone_set('Asia/Dhaka');

//dump($flash_schedule->fs_end_date);
$schedule = date_create($flash_schedule->fs_end_date);
////dump($schedule->format('s'));
//$today = date_create(date('Y-m-d h:i:s'));
//$difference = date_diff($today, $schedule);
////dump($schedule);
//
//
//
//if ($difference->y == 0 AND $difference->m == 0 || $difference->d == 0) {
//    $year = date('Y');
//    $month = date('m');
//    $day = date('d');
//    $hour = $difference->h;
//    $min = $difference->i;
//    $second = $difference->s;
//} else {
//    $year = $difference->y;
//    $month = $difference->m;
//    $day = $difference->d;
//    $hour = $difference->h;
//    $min = $difference->i;
//    $second = $difference->s;
//}

//dump($month);
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
                                var homeFtime = new Date('<?php echo $schedule->format('Y'); ?>', '<?php echo $schedule->format('m') - 1; ?>', '<?php echo $schedule->format('d'); ?>', '<?php echo $schedule->format('H'); ?>', '<?php echo $schedule->format('i'); ?>', '<?php echo $schedule->format('s'); ?>');
                                //console.log(homeFtime);
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
                 data-autoplay="yes" data-autoheight="no" data-delay="4" data-speed="0.6"
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

                    if (!empty($first_image->full_size_directory)) {
                        $img = url($first_image->full_size_directory);
                    } else {
                        $img = url('storage/uploads/fullsize/2019-01/default.jpg');
                    }

                    $regularprice = $pro->local_selling_price;
                    $save = $fi->fi_discount;
                    $sp = round($regularprice - $save);

                    $fsp = round($pro->local_selling_price - $fi->fi_discount);
                    ?>
                    <?php


                    echo product_design([
                        'bootstrap_cols' => 'item',
                        'seo_url' => product_seo_url($pro->seo_url, $pro->id),
                        'straight_seo_url' => $pro->seo_url,
                        'title' => $pro->title,
                        'first_image' => $img,
                        'second_image' => !empty($second_image) ? url($second_image->full_size_directory) : null,
                        'discount_rate' => $fi->fi_show_tag,
                        'price' => $sp,
                        'old_price' => $regularprice,
                        'descriptions' => $pro->description,
                        'product_code' => $pro->product_code,
                        'product_sku' => $pro->sku,
                        'product_id' => $pro->id,
                        'product_qty' => 1,
                        'flash_now' => 'Yes',
                        'flash_old_count' => $fi['fi_qty'],
                        'flash_now_count' => $pro->qty,
                        'flash_id' => $fi->id,
                        'emi' => null,
                        'sign' => $tksign
                    ]);

                    ?>
                @endforeach

            </div>
        </div>
    </div>
@endif
<?php } ?>