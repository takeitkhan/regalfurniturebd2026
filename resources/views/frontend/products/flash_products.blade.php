@extends('frontend.layouts.app')

@section('content')
    <?php $tksign = '&#2547; ';

     date_default_timezone_set('Asia/Dhaka');
    $schedule = $flash_schedule[0]->fs_end_date??0;
  
// dd($flash_schedule);

    $content = App\HomeSetting::first()->flash_banner;
    $flash_banner = short_code($content)[0];
    

    ?>
    





    <div class="main-container container">

        <div class="row" style="margin-bottom:15px; margin-top: 15px">
            <div class="col-md-12">
                <div class="breadcrumb-warp">
                    <div class="breadcrumb-one">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Flash Sale</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <!--<div class="category-desc">-->
        <!--    <div class="row">-->
        <!--        <div class="col-sm-12">-->
        <!--            <div class="banners">-->
        <!--                <div>-->
        <!--                    <a href="{{ url($flash_banner['link']) }}">-->
        <!--                        <img src="{{ url(get_full_img($flash_banner['img'])) }}" alt=""><br>-->
        <!--                    </a>-->
        <!--                </div>-->
        <!--            </div>-->

        <!--        </div>-->
        <!--    </div>-->
        <!--</div>-->

        <div class="Flash-home-header Flash-home-header_two">
            <div class="flash-onsale_wp">
                <div class="flash-title">Flash Sale</div>
                <div class="flash-onsale">On Sale Now</div>

                <div class="flash-end"> <span class="flash-ending">Ending in</span> <p id="flash-time-countdown" class="flash-time-countdown"></p></div>
            </div>
        </div>

        <div class="row">
            <!--Middle Part Start-->
            <div id="content" class="">
                <div class="products-category">
                    @if($flash_banner['name'])

                    <h3 class="title-category ">{{ $flash_banner['name'] }}</h3>
                    @endif
                    
                    

                    <div class="">
                        <div class="module deals-layout1 deals-layout80">

                            <div class="head-title head-title-fls-cell">
                                <div class="modtitle">
                                    <div class="cslider-item-timer cslider-item-timer_page">
                                        <div class="sell-time">
                                            <ul class="list-unstyled">
                                                <!-- arafat -->
                                                
                                                
                                                @php
                                                    $heading_count = 1;
                                                @endphp

                                                @foreach($flash_schedule as $flash)
                                                    @php

                                                        $today = date('Y-m-d');

                                                       $is_tomorrow = date('Y-m-d',strtotime($flash->fs_start_date));

                                                           date_default_timezone_set('Asia/Dhaka');
                                                           $schedule = date_create($flash->fs_end_date);

                                                       if (new DateTime() > new DateTime($flash->fs_start_date)) {

                                                          $tag_title = '';

                                                       }else{
                                                           if($today == $is_tomorrow){
                                                               $day_sign = '';
                                                           }else{
                                                               $day_sign = ' Tomorrow';
                                                           }




                                                          $tag_title_h =  $schedule->format('h:i a');
                                                          //$tag_title_min =  $schedule->format('i');
                                                          $tag_title = $tag_title_h . $day_sign;
                                                       }



                                                    if($heading_count != 1){
                                                     $flash_class = '';
                                                    }else{
                                                        $flash_class = 'Active';
                                                    }

                                                    @endphp
                                                    @if($tag_title != '')
                                                        <li><a class="{{$flash_class}}"
                                                               href="{{ '#flash_count_'. $heading_count}}">{{$tag_title}}</a>
                                                        </li>
                                                    @endif


                                                    @php
                                                        ++$heading_count;
                                                    @endphp

                                                @endforeach

                                            </ul>
                                        </div>

                                    </div>

                                </div>
                            </div>


                            <div class="all-modcontent">

                                @php
                                    $flash_itme_count = 1;
                                @endphp
                                
                                
                                
                               
                                
                                @foreach($flash_schedule as $flash)
                                
                               

                                    @php


                                        $today = date('Y-m-d');

                                        $is_tomorrow = date('Y-m-d',strtotime($flash->fs_start_date));

                                            date_default_timezone_set('Asia/Dhaka');
                                            $schedule = date_create($flash->fs_end_date);

                                        if (new DateTime() > new DateTime($flash->fs_start_date)) {
                                           $flash_now = 'Yes';
                                           $tag_title = 'On Sale Now';
                                        }else{
                                            if($today == $is_tomorrow){
                                                $day_sign = '';
                                            }else{
                                                $day_sign = ' Tomorrow';
                                            }

                                            $flash_now = 'No';

                                           $tag_title_h =  $schedule->format('h:i a');
                                           $tag_title = $tag_title_h . $day_sign;
                                        }
                                        
                                    

                                     $flash_itmes = App\FlashItem::where(['fi_shedule_id' => $flash->id])->get();

                                    //dump($flash_itmes);

                                    @endphp

                          
                                    <div id="{{ 'flash_count_'. $flash_itme_count}}" class="modcontent modcontentoaf">
                                        <div id="so_deal_1" class="so-deal style1">
                                            <div class="extraslider-inner products-list">
                                                <div class="on-cell">

                                                    <!-- <h3 class="cell-title">{{$tag_title}}</h3> -->

                                                    
                                                    @foreach($flash_itmes as $fi)
                                                        <?php
                                                        //dump();

                                                        //dump($fi['fi_qty']);


                                                        $pro = \App\Product::where('id', $fi->fi_product_id)->get()->first();

                                                        // dump($pro);


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
                                                            if ($flash_now == 'Yes') {
                                                                $save = $fi->fi_discount;
                                                            } else {
                                                                $save = ($pro->local_selling_price * $pro->local_discount) / 100;
                                                            }


                                                            $sp = round($regularprice - $save);

                                                            echo product_design_flash([
                                                                'bootstrap_cols' => 'col-lg-15 col-md-4 col-sm-6 col-xs-12',
                                                                'seo_url' => product_seo_url($pro->seo_url, $pro->id),
                                                                'straight_seo_url' => $pro->seo_url,
                                                                'title' => $pro->title,
                                                                'first_image' => $img,
                                                                'second_image' => !empty($second_image) ? url($second_image->full_size_directory) : null,
                                                                'discount_rate' => (($flash_now == 'Yes') ? $fi->fi_show_tag : null),
                                                                'price' => $sp,
                                                                'old_price' => $regularprice,
                                                                'descriptions' => $pro->description,
                                                                'product_code' => $pro->product_code,
                                                                'product_sku' => $pro->sku,
                                                                'product_id' => $pro->id,
                                                                'product_qty' => 1,
                                                                'flash_now' => $flash_now,
                                                                'flash_old_count' => $fi['fi_qty'],
                                                                'flash_now_count' => $pro->qty,
                                                                'flash_id' => $fi->id,
                                                                'emi' => null,
                                                                'sign' => $tksign
                                                            ]);
                                                        }


                                                        ?>





                                                    @endforeach


                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @php
                                        ++$flash_itme_count;
                                    @endphp

                                @endforeach
                               


                            </div>
                        </div>

                        <!--// End Changed listings-->


                    </div>

                </div>


                <!--Middle Part End-->
            </div>
        </div>
        <!-- //Main Container -->


    </div>


    


@endsection
@section('cusjs')
    <script type="text/javascript">
        //picZoomer


        jQuery(document).ready(function ($) {
            $.noConflict();

            $(window).scroll(function () {
                var scroll = $(window).scrollTop();

                if (scroll > 550) {
                    $(".head-title-fls-cell").addClass("secrall-header-show1"); // you don't need to add a "." in before your class name
                } else {
                    $(".head-title-fls-cell").removeClass("secrall-header-show1");
                }
            });

        });


    </script>


        <script>
                // Set the date we're counting down to
                var diftime = "<?php echo $flash_schedule[0]->fs_end_date??0; ?>";
          
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
        
                    if (distance < 0) {
                        clearInterval(x);
                        document.getElementById("flash-time-countdown").innerHTML = "EXPIRED";
                    }
                }, 1000);
            </script>

@endsection



