@extends('frontend.rflus_backup.layouts.app')

@section('content')
    <?php $tksign = '&#2547; '; ?>
    <!-- owl-carousel owl-theme -->

    <div class="slider-area owl-carousel owl-theme owl-loaded">
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
                        $express_delivery = App\Product::where('recommended', 'on')->orderBy('id', 'desc')->limit(5)->get()->toArray();
                    @endphp

                    @foreach($express_delivery as $product)
                        @php
                            $product = (object) $product;

                            $first_image = \App\ProductImages::where('main_pid', $product->id)->where('is_main_image', 1)->get()->first();
                            //dump($first_image);
                            $second_image = \App\ProductImages::where('main_pid', $product->id)->where('is_main_image', 0)->get()->first();

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
    </section>

    <section class="banner-area section-padding hidden-xs">
        <div class="container">
            <div class="row">
                <div class="pt-3">
                    <div class="thumb-banner">
                        <a href="#">
                            <img src="{{ url('public/frontend/images/banner/1.jpg') }}" alt="baner-img">
                        </a>
                    </div>
                </div>

                <div class="pt-3">
                    <div class="thumb-banner">
                        <a href="#">
                            <img src="{{ url('public/frontend/images/banner/2.jpg') }}" alt="baner-img">
                        </a>
                    </div>
                </div>

                <div class="pt-3">
                    <div class="thumb-banner">
                        <a href="#">
                            <img src="{{ url('public/frontend/images/banner/3.jpg') }}" alt="baner-img">
                        </a>
                    </div>
                    <div class="">
                        <div class="sms-banner">
                            <div class="ptd-5 pt-5_one">
                                <div class="thumb-banner">
                                    <a href="#">
                                        <img src="{{ url('public/frontend/images/banner/4.jpg') }}" alt="baner-img">
                                    </a>
                                </div>
                            </div>
                            <div class="ptd-5 pt-5_one">
                                <div class="thumb-banner">
                                    <a href="#">
                                        <img src="{{ url('public/frontend/images/banner/5.jpg') }}" alt="baner-img">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <section class="product-category section-padding">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-title product-category-title text-center">
                        <h3>Product Category</h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="product-category-warp">

                    <div class="pt-2">
                        <div class="single-product-category">
                            <span class="product-category-left"><a href="#"><img
                                            src="{{ url('public/frontend/images/product-category/product-category1.png') }}"
                                            alt="product-category-img"></a></span>
                            <span class="product-category-right"><h4><a href="">Bedroom</a></h4><span
                                        class="view-details"><a href="#">View Details</a></span></span>
                        </div>
                    </div>
                    <div class="pt-2">
                        <div class="single-product-category">
                            <span class="product-category-left"><a href="#"><img
                                            src="{{ url('public/frontend/images/product-category/product-category2.png') }}"
                                            alt="product-category-img"></a></span>
                            <span class="product-category-right"><h4><a href="">Dining room</a></h4><span
                                        class="view-details"><a href="#">View Details</a></span></span>
                        </div>
                    </div>
                    <div class="pt-2">
                        <div class="single-product-category">
                            <span class="product-category-left"><a href="#"><img
                                            src="{{ url('public/frontend/images/product-category/product-category3.png') }}"
                                            alt="product-category-img"></a></span>
                            <span class="product-category-right"><h4><a href="">Living room</a></h4><span
                                        class="view-details"><a href="#">View Details</a></span></span>
                        </div>
                    </div>
                    <div class="pt-2">
                        <div class="single-product-category">
                            <span class="product-category-left"><a href="#"><img
                                            src="{{ url('public/frontend/images/product-category/product-category4.png') }}"
                                            alt="product-category-img"></a></span>
                            <span class="product-category-right"><h4><a href="">Office room</a></h4><span
                                        class="view-details"><a href="#">View Details</a></span></span>
                        </div>
                    </div>
                    <div class="pt-2">
                        <div class="single-product-category">
                            <span class="product-category-left"><a href="#"><img
                                            src="{{ url('public/frontend/images/product-category/product-category5.png') }}"
                                            alt="product-category-img"></a></span>
                            <span class="product-category-right"><h4><a href="">Industrial  Furniture</a></h4><span
                                        class="view-details"><a href="#">View Details</a></span></span>
                        </div>
                    </div>
                    <div class="pt-2">
                        <div class="single-product-category">
                            <span class="product-category-left"><a href="#"><img
                                            src="{{ url('public/frontend/images/product-category/product-category6.png') }}"
                                            alt="product-category-img"></a></span>
                            <span class="product-category-right"><h4><a href="">Kitchen room</a></h4><span
                                        class="view-details"><a href="#">View Details</a></span></span>
                        </div>
                    </div>
                    <div class="pt-2">
                        <div class="single-product-category">
                            <span class="product-category-left"><a href="#"><img
                                            src="{{ url('public/frontend/images/product-category/product-category7.png') }}"
                                            alt="product-category-img"></a></span>
                            <span class="product-category-right"><h4><a href="">Pillow&  Mattress </a></h4><span
                                        class="view-details"><a href="#">View Details</a></span></span>
                        </div>
                    </div>
                    <div class="pt-2">
                        <div class="single-product-category">
                            <span class="product-category-left"><a href="#"><img
                                            src="{{ url('public/frontend/images/product-category/product-category8.png') }}"
                                            alt="product-category-img"></a></span>
                            <span class="product-category-right"><h4><a href="">Hospital Furniture</a></h4><span
                                        class="view-details"><a href="#">View Details</a></span></span>
                        </div>
                    </div>
                    <div class="pt-2">
                        <div class="single-product-category">
                            <span class="product-category-left"><a href="#"><img
                                            src="{{ url('public/frontend/images/product-category/product-category9.png') }}"
                                            alt="product-category-img"></a></span>
                            <span class="product-category-right"><h4><a
                                            href="">Classroom, Kids  Furniture & Toys</a></h4><span
                                        class="view-details"><a href="#">View Details</a></span></span>
                        </div>
                    </div>
                    <div class="pt-2">
                        <div class="single-product-category">
                            <span class="product-category-left"><a href="#"><img
                                            src="{{ url('public/frontend/images/product-category/product-category10.png') }}"
                                            alt="product-category-img"></a></span>
                            <span class="product-category-right"><h4><a href="">Miscellaneous</a></h4><span
                                        class="view-details"><a href="#">View Details</a></span></span>
                        </div>
                    </div>
                    <div class="pt-2">
                        <div class="single-product-category">
                            <span class="product-category-left"><a href="#"><img
                                            src="{{ url('public/frontend/images/product-category/product-category11.png') }}"
                                            alt="product-category-img"></a></span>
                            <span class="product-category-right"><h4><a href="">Workstation</a></h4><span
                                        class="view-details"><a href="#">View Details</a></span></span>
                        </div>
                    </div>
                    <div class="pt-2">
                        <div class="single-product-category">
                            <span class="product-category-left"><a href="#"><img
                                            src="{{ url('public/frontend/images/product-category/product-category12.png') }}"
                                            alt="product-category-img"></a></span>
                            <span class="product-category-right"><h4><a href="">Caino Furniture</a></h4><span
                                        class="view-details"><a href="#">View Details</a></span></span>
                        </div>
                    </div>
                    <div class="pt-2">
                        <div class="single-product-category">
                            <span class="product-category-left"><a href="#"><img
                                            src="{{ url('public/frontend/images/product-category/product-category13.png') }}"
                                            alt="product-category-img"></a></span>
                            <span class="product-category-right"><h4><a href="">Crystal  Furniture</a></h4><span
                                        class="view-details"><a href="#">View Details</a></span></span>
                        </div>
                    </div>
                    <div class="pt-2">
                        <div class="single-product-category">
                            <span class="product-category-left"><a href="#"><img
                                            src="{{ url('public/frontend/images/product-category/product-category14.png') }}"
                                            alt="product-category-img"></a></span>
                            <span class="product-category-right"><h4><a href="">Home Decor</a></h4><span
                                        class="view-details"><a href="#">View Details</a></span></span>
                        </div>
                    </div>
                    <div class="pt-2">
                        <div class="single-product-category">
                            <span class="product-category-left"><a href="#"><img
                                            src="{{ url('public/frontend/images/product-category/product-category15.png') }}"
                                            alt="product-category-img"></a></span>
                            <span class="product-category-right"><h4><a href="">Upcoming</a></h4><span
                                        class="view-details"><a href="#">View Details</a></span></span>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
    <div class="call-to-action-banner">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="call-to-banner">
                        <a href="#"><img src="{{ url('public/frontend/images/what-banner-full.jpg') }}" alt=""></a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <section class="testimonial-area section-padding">
        <div class="container">
            <div class="testimonial-area-warp">
                <div class="row">
                    <div class="col-md-7">
                        <div class="testimonial-title section-title">
                            <h3>What Client Say About us</h3>
                        </div>
                        <div class="testimonial-warp owl-carousel owl-theme">

                            <div class="testimonial-single">
                                <div class="testimonial-left">
                                    <div class="testimonial-img">
                                        <img src="{{ url('public/frontend/images/testimonial/test1.jpg') }}"
                                             alt="testimonial">
                                    </div>
                                </div>
                                <div class="testimonial-right">
                                    <div class="testimonial-text">
                                        <p><span>“</span> When potential customers are researching you online, they're
                                            getting to know you by way of the content of your website. Understandably,
                                            many
                                            of them might be skeptical or hesitant to trust you right away.
                                            <span>”</span>
                                        </p>
                                    </div>
                                    <div class="testimonial-owrn">
                                        <h4>Chief Executive Officer (CEO)</h4>
                                        <p>Electoral Group</p>
                                    </div>
                                </div>
                            </div>
                            <div class="testimonial-single">
                                <div class="testimonial-left">
                                    <div class="testimonial-img">
                                        <img src="{{ url('public/frontend/images/testimonial/test1.jpg') }}"
                                             alt="testimonial">
                                    </div>
                                </div>
                                <div class="testimonial-right">
                                    <div class="testimonial-text">
                                        <p><span>“</span> When potential customers are researching you online, they're
                                            getting to know you by way of the content of your website. Understandably,
                                            many
                                            of them might be skeptical or hesitant to trust you right away.
                                            <span>”</span>
                                        </p>
                                    </div>
                                    <div class="testimonial-owrn">
                                        <h4>Chief Executive Officer (CEO)</h4>
                                        <p>Electoral Group</p>
                                    </div>
                                </div>
                            </div>
                            <div class="testimonial-single">
                                <div class="testimonial-left">
                                    <div class="testimonial-img">
                                        <img src="{{ url('public/frontend/images/testimonial/test1.jpg') }}"
                                             alt="testimonial">
                                    </div>
                                </div>
                                <div class="testimonial-right">
                                    <div class="testimonial-text">
                                        <p><span>“</span> When potential customers are researching you online, they're
                                            getting to know you by way of the content of your website. Understandably,
                                            many
                                            of them might be skeptical or hesitant to trust you right away.
                                            <span>”</span>
                                        </p>
                                    </div>
                                    <div class="testimonial-owrn">
                                        <h4>Chief Executive Officer (CEO)</h4>
                                        <p>Electoral Group</p>
                                    </div>
                                </div>
                            </div>

                        </div>
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
                                    <form action="">
                                        <div class="newsletter-input">
                                            <input type="email" placeholder="Your Email Address" value="" name=""
                                                   class="form-control">
                                            <button class="newsletter-btn" type="submit">Subscribe</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="show-thumb section-padding">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="full-width-baner">
                        <a href="#"><img src="{{ url('public/frontend/images/full-width-bannder.jpg') }}"
                                         alt="full-width-baner"></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

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
                    <div class="col-md-3 col-sm-6 col-xs-6">
                        <div class="single-explore-products">
                            <div class="single-explore-list">
                                <ul class="nav nav-stacked" id="accordion1">
                                    <li class="colp_nav">
                                        <a class="" data-toggle="collapse" data-target="#collapseOne"
                                           aria-expanded="true" aria-controls="collapseOne">
                                            <strong>Bedroom Furniture</strong>
                                        </a>
                                        <ul id="collapseOne" class="collapse show list-unstyled"
                                            aria-labelledby="headingOne" data-parent="#accordion">
                                            <li><a href="">Almirah</a></li>
                                            <li><a href="">Alna</a></li>
                                            <li><a href="">Bed</a></li>
                                            <li><a href="">Bedside Table</a></li>
                                            <li><a href="">Chest Of Drawer</a></li>
                                            <li><a href="">Comfy Mattress</a></li>
                                            <li><a href="">Computer Table</a></li>
                                            <li><a href="">Cup Board</a></li>
                                            <li><a href="">Dressing Table & Seater</a></li>
                                            <li><a href="">Reading Table</a></li>
                                            <li><a href="">Rocking Chair</a></li>
                                            <li><a href="">Wardrobe</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6 col-xs-6">
                        <div class="single-explore-products">
                            <div class="single-explore-list">
                                <ul class="nav nav-stacked" id="accordion1">
                                    <li class="colp_nav">
                                        <a data-toggle="collapse" data-target="#collapse15One" aria-expanded="true"
                                           aria-controls="collapse15One">
                                            <strong>Living Room Furniture</strong>
                                        </a>
                                        <ul id="collapse15One" class="collapse show list-unstyled"
                                            aria-labelledby="headingOne" data-parent="#accordion">
                                            <li><a href="">Center Table</a></li>
                                            <li><a href="">Divan</a></li>
                                            <li><a href="">Multipurpose Shelf</a></li>
                                            <li><a href="">Shoe Rack</a></li>
                                            <li><a href="">Sofa</a></li>
                                            <li><a href="">Tv Trolly & Cabinet</a></li>
                                            <li><a href=""><strong>Dinning Room Furniture</strong></a></li>
                                            <li><a href="">Dining Table</a></li>
                                            <li><a href="">Dining Chair</a></li>
                                            <li><a href="">Showcase</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-6">
                        <div class="single-explore-products">
                            <div class="single-explore-list">
                                <ul class="nav nav-stacked" id="accordion1">
                                    <li class="colp_nav">
                                        <a data-toggle="collapse" data-target="#collapse12One" aria-expanded="true"
                                           aria-controls="collapse12One">
                                            <strong>Miscellaneous Furniture</strong>
                                        </a>
                                        <ul id="collapse12One" class="collapse show list-unstyled"
                                            aria-labelledby="headingOne" data-parent="#accordion">
                                            <li><a href="">Kitchen Cabinet</a></li>
                                            <li><a href="">Kids Furniture</a></li>
                                            <li><a href="">Metal Stool</a></li>
                                            <li><a href=""><strong>Industrial Furniture</strong></a></li>
                                            <li><a href="">Cable Tray</a></li>
                                            <li><a href="">Display Rack</a></li>
                                            <li><a href="">Heavy Duty Rack</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-6">
                        <div class="single-explore-products">
                            <div class="single-explore-list">
                                <ul class="nav nav-stacked" id="accordion1">
                                    <li class="colp_nav">
                                        <a data-toggle="collapse" data-target="#collapse11One" aria-expanded="true"
                                           aria-controls="collapse11One">
                                            <strong>Office Furniture</strong>
                                        </a>
                                        <ul id="collapse11One" class="collapse show list-unstyled"
                                            aria-labelledby="headingOne" data-parent="#accordion">
                                            <li><a href="">Office Table</a></li>
                                            <li><a href="">Classroom Chair</a></li>
                                            <li><a href="">File Cabinet</a></li>
                                            <li><a href="">Office Almirah</a></li>
                                            <li><a href="">Reception Table</a></li>
                                            <li><a href="">Swivel Chair</a></li>
                                            <li><a href="">Visitor Chair</a></li>
                                            <li><a href="">Waiting Chair</a></li>
                                        </ul>

                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-6">
                        <div class="single-explore-products">
                            <div class="single-explore-list">
                                <ul class="nav nav-stacked" id="accordion1">
                                    <li class="colp_nav">
                                        <a data-toggle="collapse" data-target="#collapse1One" aria-expanded="true"
                                           aria-controls="collapse1One"><strong>Others</strong></a>

                                        <ul id="collapse1One" class="collapse show list-unstyled"
                                            aria-labelledby="headingOne" data-parent="#accordion">
                                            <li><a href="">Caino Furniture</a></li>
                                            <li><a href="">Crystal Furnituer</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

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

                        <div class="choose-single">
                            <div class="col-md-12">
                                <a href=""><img src="{{ url('public/frontend/images/choose-products/8.png') }}" alt=""></a>
                            </div>
                        </div>
                        <div class="choose-single">
                            <div class="col-md-12">
                                <a href=""><img src="{{ url('public/frontend/images/choose-products/8.png') }}" alt=""></a>
                            </div>
                        </div>
                        <div class="choose-single">
                            <div class="col-md-12">
                                <a href=""><img src="{{ url('public/frontend/images/choose-products/8.png') }}" alt=""></a>
                            </div>
                        </div>
                        <div class="choose-single">
                            <div class="col-md-12">
                                <a href=""><img src="{{ url('public/frontend/images/choose-products/8.png') }}" alt=""></a>
                            </div>
                        </div>
                        <div class="choose-single">
                            <div class="col-md-12">
                                <a href=""><img src="{{ url('public/frontend/images/choose-products/8.png') }}" alt=""></a>
                            </div>
                        </div>
                        <div class="choose-single">
                            <div class="col-md-12">
                                <a href=""><img src="{{ url('public/frontend/images/choose-products/8.png') }}" alt=""></a>
                            </div>
                        </div>
                        <div class="choose-single">
                            <div class="col-md-12">
                                <a href=""><img src="{{ url('public/frontend/images/choose-products/8.png') }}" alt=""></a>
                            </div>
                        </div>
                        <div class="choose-single">
                            <div class="col-md-12">
                                <a href=""><img src="{{ url('public/frontend/images/choose-products/8.png') }}" alt=""></a>
                            </div>
                        </div>
                        <div class="choose-single">
                            <div class="col-md-12">
                                <a href=""><img src="{{ url('public/frontend/images/choose-products/8.png') }}" alt=""></a>
                            </div>
                        </div>
                        <div class="choose-single">
                            <div class="col-md-12">
                                <a href=""><img src="{{ url('public/frontend/images/choose-products/8.png') }}" alt=""></a>
                            </div>
                        </div>
                        <div class="choose-single">
                            <div class="col-md-12">
                                <a href=""><img src="{{ url('public/frontend/images/choose-products/8.png') }}" alt=""></a>
                            </div>
                        </div>
                        <div class="choose-single">
                            <div class="col-md-12">
                                <a href=""><img src="{{ url('public/frontend/images/choose-products/8.png') }}" alt=""></a>
                            </div>
                        </div>
                        <div class="choose-single">
                            <div class="col-md-12">
                                <a href=""><img src="{{ url('public/frontend/images/choose-products/8.png') }}" alt=""></a>
                            </div>
                        </div>
                        <div class="choose-single">
                            <div class="col-md-12">
                                <a href=""><img src="{{ url('public/frontend/images/choose-products/8.png') }}" alt=""></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

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

@endsection
