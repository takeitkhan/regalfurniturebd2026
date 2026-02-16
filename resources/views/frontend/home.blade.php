@extends('frontend.rflus_backup.layouts.app')

@section('content')
    <?php $tksign = '&#2547; '; ?>
    <!-- owl-carousel owl-theme -->

    @php
        $main_slider = App\Models\HomeSetting::first()->main_slider;
        
    @endphp
    @if($main_slider != null)
<div class="container">
        <div id="banner-slider" class="owl-carousel owl-theme">
            @php
                echo home_main_slider(short_code($main_slider));
            @endphp
        </div>
</div>
    @endif 


 <section class="products-area">
        <div class="container">
            <div class="row">
                <div class="home-prosuct-warp">
                    <?php
                    $expressDelivery = \App\Models\Product::where('recommended', 'on')->orderBy('id', 'desc')->limit(5)->get()->toArray();
                    ?>

                    @foreach($expressDelivery as $product)
                        @php
                            $product = (object) $product;

                            $first_image = \App\Models\ProductImages::where('main_pid', $product->id)->where('is_main_image', 1)->get()->first();
                            //dump($first_image);
                            $second_image = \App\Models\ProductImages::where('main_pid', $product->id)->where('is_main_image', 0)->get()->first();

                                   
                            $img = isset($second_image->full_size_directory) ? url($second_image->full_size_directory) : false;
                            $img = !$img && !empty($first_image->full_size_directory) ? url($first_image->full_size_directory) : $img;
                            $img = !$img ? url('storage/uploads/fullsize/2019-01/default.jpg') : $img;
                                
                                
                            $second_image = isset($second_image->full_size_directory) && isset($first_image->full_size_directory) ? url($first_image->full_size_directory) : null;
                             
                                
                                



                            $regularprice = $product->local_selling_price;
                            $save = ($product->local_selling_price * $product->local_discount) / 100;
                            $sp = round($regularprice - $save);

                            //dump($product->id);

                            echo product_design([
                                'bootstrap_cols' => 'pt-2 end-product end-product2',
                                'singular_class' => 'single-product home-product',
                                'seo_url' => product_seo_url($product->seo_url, $product->id),
                                'straight_seo_url' => $product->seo_url,
                                'title' => limit_character($product->title, 35),
                                'first_image' => $img,
                                'second_image' => $second_image,
                                'discount_rate' => $product->local_discount,
                                'price' => $sp,
                                'old_price' => $regularprice,
                                'descriptions' => $product->description,
                                'product_code' => $product->product_code,
                                'product_sku' => $product->sku,
                                'product_id' => $product->id,
                                'product_qty' => 1,
                                'sign' => '&#2547; '
                            ]);

                        @endphp
                    @endforeach


                </div>
            </div>
        </div>
    </section>


<!-- <div class="hero-slider-wp">
    <div class="single-sliderr">


        <div class="slider-images">
            <a href="#"> <img src="https://www.regalfurniturebd.com/uploads/posts/Slider.jpg" alt=""></a>
            <div class="hero-slider-area">
               <img src="http://103.218.26.178:2145/regalf/public/frontend/images/slider/banner/sliderbaner2.jpg" alt="">
            </div>
        </div>

        <div class="container">
               <div class="row">
                   <div class="col-md-12">
                        <div class="slider-area-table">
                            <div class="slider-area-tablecell">

                           

                         </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> -->



<!--     <div class="slider-area owl-carousel owl-theme owl-loaded">
        <div class="single-slider">
            <div class="signle-slider-table">
                <div class="signle-slider-table-cell">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="slider-banner">
                                    <div class="slider-banner-img">
                                        <a href="">
                                            <img src="{{ url('public/frontend/images/slider/banner/sliderbaner1.jpg') }}"
                                                 alt=""></a>
                                        <a href="">
                                            <img src="{{ url('public/frontend/images/slider/banner/sliderbaner2.jpg') }}"
                                                 alt=""></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="single-slider">
            <div class="signle-slider-table">
                <div class="signle-slider-table-cell">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="slider-banner">
                                    <div class="slider-banner-img">
                                        <a href="">
                                            <img src="{{ url('public/frontend/images/slider/banner/sliderbaner1.jpg') }}"
                                                 alt="">
                                        </a>
                                        <a href="">
                                            <img src="{{ url('public/frontend/images/slider/banner/sliderbaner2.jpg') }}"
                                                 alt="">
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="single-slider">
            <div class="signle-slider-table">
                <div class="signle-slider-table-cell">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="slider-banner">
                                    <div class="slider-banner-img">
                                        <a href=""><img
                                                    src="{{ url('public/frontend/images/slider/banner/sliderbaner1.jpg') }}"
                                                    alt=""></a>
                                        <a href=""><img
                                                    src="{{ url('public/frontend/images/slider/banner/sliderbaner2.jpg') }}"
                                                    alt=""></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>



    <section class="products-area">
        <div class="container">
            <div class="row">
                <div class="home-prosuct-warp">
                    @php
                        $express_delivery = App\Models\Product::where('recommended', 'on')->orderBy('id', 'desc')->limit(5)->get()->toArray();

                    @endphp

                    @foreach($express_delivery as $product)
                        @php
                            $product = (object) $product;

                            $first_image = \App\Models\ProductImages::where('main_pid', $product->id)->where('is_main_image', 1)->get()->first();
                            //dump($first_image);
                            $second_image = \App\Models\ProductImages::where('main_pid', $product->id)->where('is_main_image', 0)->get()->first();

                            $regularprice = $product->local_selling_price;
                            $save = ($product->local_selling_price * $product->local_discount) / 100;
                            $sp = round($regularprice - $save);

                            //dump($product->id);

                            echo product_design([
                                'bootstrap_cols' => 'pt-2 end-product end-product2',
                                'singular_class' => 'single-product home-product',
                                'seo_url' => product_seo_url($product->seo_url, $product->id),
                                'straight_seo_url' => $product->seo_url,
                                'title' => limit_character($product->title, 35),
                                'first_image' => !empty($first_image) ? url($first_image['full_size_directory']) : null,
                                'second_image' => !empty($second_image) ? url($second_image['full_size_directory']) : null,
                                'discount_rate' => $product->local_discount,
                                'price' => $sp,
                                'old_price' => $regularprice,
                                'descriptions' => $product->description,
                                'product_code' => $product->product_code,
                                'product_sku' => $product->sku,
                                'product_id' => $product->id,
                                'product_qty' => 1,
                                'sign' => '&#2547; '
                            ]);

                        @endphp
                    @endforeach


                </div>
            </div>
        </div>
    </section> -->

    @include('frontend.home_flashsale')

    <section class="banner-area section-padding hidden-xs">
        <div class="container">
            <div class="row">
            @php
          
                $content = App\Models\HomeSetting::first()->home_banner_one;
                echo home_banner_one(short_code($content));

            @endphp

            </div>
        </div>
    </section>

    @php
        $home_cat = get_home_cat();
        
    @endphp
   
    @if($home_cat)

        <section class="product-category section-padding">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="section-title product-category-title text-center">
                            <h3>Shop by Category</h3>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="product-category-warp">



                        {!! $home_cat !!}

                    </div>
                </div>
            </div>
        </section>
    @endif

   



    @php
        $banner_two = App\Models\HomeSetting::first()->home_banner_two;

    @endphp
    @if($banner_two != null)

        <div class="call-to-action-banner">
            <div class="container">

                <div class="row">
                    <div class="col-md-12">
                        <div class="call-to-banner">
                            @php

                                echo home_banner_two(short_code($banner_two));

                            @endphp

                        </div>
                    </div>
                </div>
            </div>
        </div>

    @endif





    <section class="testimonial-area section-padding">
        <div class="container">

            <div class="testimonial-area-warp">
                <div class="row">
                    <div class="col-md-7">
                        <div class="testimonial-title section-title">
                            <h3>What Client Say About us</h3>
                        </div>
                        @php
                            $testimonials = App\Models\Post::where('categories', '626')->latest()->take(5)->get();


                        @endphp

                        @if($testimonials->count())

                        <div class="testimonial-warp owl-carousel owl-theme">

                            @foreach($testimonials as $line)



                            <div class="testimonial-single">
                                <div class="testimonial-left">
                                    <div class="testimonial-img">
                                        <img src="{{ url(get_icon_img($line->images)) }}"
                                             alt="{{ $line->sub_title }}" style="border: 1px solid gray">
                                    </div>
                                </div>
                                <div class="testimonial-right">
                                    <div class="testimonial-text">
                                        <p><span>“</span> {{ $line->short_description }}
                                            <span>”</span>
                                        </p>
                                    </div>
                                    <div class="testimonial-owrn">
                                        <h4>{{ $line->title }}</h4>
                                        <p>{{ $line->sub_title }}</p>
                                    </div>
                                </div>
                            </div>

                                @endforeach


                        </div>

                        @endif


                    </div>
                    <div class="col-md-5">
                        <div class="newsletter-all">
                            <div class="newsletter-title section-title testimonial-title ">
                                <h3>Newsletter</h3>
                            </div>
                            <div class="newsletter-body">
                                <p>Exclusive Deals & Offers !</p>
                                <p>Subscribe to our newsletter to receive special offers !</p>
                                <div class="newsletter-form">



                                    {{ Form::open(array('url' => 'subscribe_email', 'method' => 'post', 'value' => 'PATCH')) }}

                                    <div class="newsletter-input">
                                        <input type="email" placeholder="Your Email Address" value="" name="email"
                                               class="form-control">
                                        <button class="newsletter-btn" type="submit">Subscribe</button>
                                    </div>
                                    {{ Form::close() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



    @php
        $banner_three = App\Models\HomeSetting::first()->home_banner_three;

    @endphp
    @if($banner_three != null)

        <section class="show-thumb section-padding">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="full-width-baner">
                            @php

                                echo home_banner_tree(short_code($banner_three));

                            @endphp

                        </div>
                    </div>
                </div>
            </div>
        </section>

    @endif

    @php
        $explore_products = App\Models\HomeSetting::first()->explore_products;
    @endphp
    @if($banner_three != null)

    <section class="explore-products section-padding">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-title text-center explore-products-title">
                        <h3>Explore Our Products</h3>
                    </div>
                </div>
            </div>
            <div class="explore-products-warp">
                <div class="row">
                    @php
                        echo home_explore_products(short_code($explore_products));
                    @endphp

                </div>
            </div>
        </div>
    </section>

    @endif

    @php
        $brand_sli = App\Models\HomeSetting::first()->home_brand;


    @endphp
    @if($brand_sli != null)

    <section class="choos-us section-padding">
        <div class="container">
            <div class="choos-us-all">
                <div class="row">
                    <div class="col-md-12">
                        <div class="section-title text-center choose-book-title">
                            <h3>Why Choose Our Products</h3>

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="choose-us-warp owl-carousel owl-theme">
                        @php

                            echo home_brand_slider(short_code($brand_sli));

                        @endphp


                    </div>
                </div>
            </div>
        </div>
    </section>

    @endif

@endsection

@section('cusjs')
    <style type="text/css">
        .product-image-container {
            min-height: 275px;
        }

        .top_one ul.megamenu {
            margin: 0px 0 0 0 !important;
        }

        ul.megamenu {
            margin: -5px 0 0 0;
        }

        .hide_down_arrow:after {
            content: '' !important;
            font-size: 0px !important;
        }

        .hide_before_scroll {
            display: none;
        }

        ul.cd_sub_cat {
            column-count: 3;
            padding: 0 15px;
        }

        ul.cd_sub_cat > li > a {
            font-weight: bold;
        }
        .product-img{
            overflow:hidden;
        }
        .product-eft {
                position: absolute;
                top: -19px;
                left: -47px;
                color: #FFF;
                background: #f93e4b;
                font-size: 13px;
                font-weight: bold;
                -webkit-transform: rotate(-45deg);
                -ms-transform: rotate(-45deg);
                transform: rotate(-45deg);
                padding-left: 38px;
                width: 150px;
                height: 58px;
                opacity: 1;
                -webkit-transition: .3s all ease-out;
                -o-transition: .3s all ease-out;
                transition: .3s all ease-out;
                line-height: 8px;
            
        }
    </style>

    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $.noConflict();
            if ($(window).scrollTop() < 550) {
                $('.tighten').addClass('hide_before_scroll');
                $('.megamenuToogle-pattern .container').addClass('hide_down_arrow');
            }

            $(window).scroll(function () {
                if ($(this).scrollTop() > 550) { // this refers to window
                    //console.log(true);
                    $('.tighten').removeClass('hide_before_scroll');
                    $('.megamenuToogle-pattern .container').removeClass('hide_down_arrow');
                } else {
                    //console.log(false);
                    $('.tighten').addClass('hide_before_scroll');
                    $('.megamenuToogle-pattern .container').addClass('hide_down_arrow');
                }
            });
        });

        function get_tab_data(type) {
            if (type == 'new_arrival') {
                jQuery.ajax({
                    //url: baseurl + '/search_product?search_key=' + data.search_key + '&minprice=' + data.minprice + '&maxprice=' + data.maxprice + '&field=' + data.field + '&type=' + data.type + '&material=' + data.material + '&limit=' + data.limit + '&offset=' + data.offset,
                    url: baseurl + '/get_tab_data?type=' + type,
                    method: 'get',
                    //data: filters,
                    success: function (data) {
                        jQuery('.ltabs-loading').html(data.data);
                    },
                    error: function () {
                        showError('Sorry. Try reload this page and try again.');
                        processing.hide();
                    }
                });
            } else if (type == 'most_rated') {
                alert(type);
            }
        }
    </script>
/*    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $.noConflict();
          $("#myInput").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#myTable tr").filter(function() {
              $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
          });
        });
    </script>*/

@endsection
