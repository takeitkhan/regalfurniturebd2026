<?php
$flash_rule = array(
    'fs_is_active' => 1

);
$flash_schedule = App\Models\FlashShedule::where($flash_rule)
    ->whereRaw('NOW() BETWEEN fs_start_date AND fs_end_date ')
    ->orderBy('fs_start_date', 'ASC')->get()->first();


if(!empty($flash_schedule)) {
$flash_itmes = App\Models\FlashItem::where(['fi_shedule_id' => $flash_schedule->id])->get();

date_default_timezone_set('Asia/Dhaka');

$schedule = date_create($flash_schedule->fs_end_date);

//dump($schedule);

?>
@if($flash_itmes->count() > 0)



    <section class="banner-area1 section-padding ">
        <div class="container">
           <div class="banner-area-flash">
                <div class="row">
                    <div class="col-md-12">
                        <div class="Flash-home-header">

                            
                                <div class="flash-onsale_wp">
                                    <div class="flash-title">Flash Sale</div>
                                    <div class="flash-onsale">On Sale Now</div>

                                    <div class="flash-end"> <span class="flash-ending">Ending in</span> <p id="flash-time-countdown" class="flash-time-countdown"></p></div>
                                </div>

                            <div class="flash-more"><a href="{{ url('flash_products') }}">SHOP MORE</a></div>
                        </div>
                    </div>
                    </div>
               
         
           
                <div class="home-prosuct-warp home-prosuct-warp_two">
                     <div class="row">
                        <div class="flash-slider owl-carousel owl-theme">

                            @foreach($flash_itmes as $pro)

                                <?php
                                // dd($pro);
                                $fi = $pro;
                                //dump($fi);
                                $pro = \App\Models\Product::where('id', $pro->fi_product_id)->get()->first();
                                $pro = (object)$pro;
                                $first_image = \App\Models\ProductImages::where('main_pid', $pro->id)->where('is_main_image', 1)->get()->first();
                                $second_image = \App\Models\ProductImages::where('main_pid', $pro->id)->where('is_main_image', 0)->get()->first();

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


                                echo product_design_flash([
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
            </div>
        </div>
    </section>

    <script>
        // Set the date we're counting down to
        var diftime = "<?php echo $flash_schedule->fs_end_date; ?>";
        
        var countDownDate = new Date(diftime).getTime();

 
        // Update the count down every 1 second
        var x = setInterval(function() {

            // Get todays date and time
            var now = new Date().getTime();

            // Find the distance between now and the count down date
            var distance = countDownDate - now;
           // alert(distance);

            // Time calculations for days, hours, minutes and seconds
            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                if(days > 0){
                    days = '<span>' + days + "</span>: ";
                }else{
                    days = "";
                }
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));

            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // Output the result in an element with id="demo"
            document.getElementById("flash-time-countdown").innerHTML = days  + '<span>' + hours  + "</span>: " + '<span>' + minutes + "</span>: " + '<span>' + seconds + "</span>";

            // If the count down is over, write some text
            if (distance < 0) {
                clearInterval(x);
                document.getElementById("flash-time-countdown").innerHTML = "EXPIRED";
            }
        }, 1000);
    </script>


@endif
<?php } ?>