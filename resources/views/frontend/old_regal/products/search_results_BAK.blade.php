@extends('frontend.layouts.app')

@section('content')

    <?php $tksign = '&#2547; '; ?>

    <?php

    $url_one = \Request::segment(1);
    $url_two = \Request::segment(2);
    $cat_url = '/' . $url_one . '/' . $url_two;

    $url_req = request();

    ?>
    <section class="products-area-category section-padding">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-sm-4">
                    {{ Form::open(array('url' => 'search?cat=' . $category_info->name, 'method' => 'post', 'value' => 'PATCH', 'id' => 'filtering', 'files' => true, 'autocomplete' => 'off')) }}

                    {{ Form::hidden('sort_by', null , ['id' => 'sort_by']) }}
                    {{ Form::hidden('sort_show', 25 , ['id' => 'sort_show']) }}

                    <div class="category-page-title">
                        <h2><a href="#">Shoe Rack</a></h2>
                    </div>
                    <div class="category-sidebar-area">
                        <div class="table_cell" style="z-index: 103;">
                            <legend>
                                <small>Search on this category</small>
                            </legend>
                            {{ Form::text('keyword', (isset($url_req->keyword))? $url_req->keyword : null , [
                            'class' => 'keyword_filter form-control',
                            'size'=> '50',
                            'autocomplete'=> 'off',
                            'placeholder'=> 'Search',

                            ]) }}
                        </div>
                        <footer class="bottom_box" style="margin-top: 15px">
                            <div class="buttons_row">
                                <a class="button_grey button_submit" href="javascript: void(0)" style="padding:3px 8px"
                                   id="keyword_filter_submit">Search</a>
                                <a class="button_grey filter_reset" href="javascript: void(0)" style="padding:3px 8px"
                                   id="keyword_filter_reset">Reset</a>
                                <a class="button_grey filter_reset" href="{{url($cat_url)}}" style="padding:3px 8px"
                                >Clear All</a>
                            </div>
                            <!--Back To Top-->
                            <div class="back-to-top"><i class="fa fa-angle-up"></i></div>
                        </footer>
                        <br>

                        <div class="category-sidebar-title">
                            <h2>CATEGORIES</h2>
                        </div>

                        <div class="sidebar-pneal">
                            <div class="box-category">
                                <ul class="list-unstyled one-cat">
                                    <li>
                                        @if(isset($filter_cat['parent_cat']->name))
                                            <a href="{{ url('/c/'.$filter_cat['parent_cat']->seo_url) }}">{{ $filter_cat['parent_cat']->name }}</a>
                                        @endif
                                        <ul class="list-unstyled">
                                            @if(isset($filter_cat['sub_menu']->name))

                                                <li>
                                                    <a href="{{ url('/c/'.$filter_cat['sub_menu']->seo_url) }}"
                                                       style="font-weight: bold">
                                                        <i class="fa fa-angle-double-down"></i>{{ $filter_cat['sub_menu']->name }}
                                                    </a>
                                                </li>
                                                @if(isset($filter_cat['child_menu']->name))
                                                    <ul class="list-unstyled child_cat_sng" style="padding-left: 15px">
                                                        <li>
                                                            <a href="{{ url('/c/'.$filter_cat['child_menu']->seo_url) }}">
                                                                <i class="fa fa-angle-double-left"></i>{{ $filter_cat['child_menu']->name }}
                                                            </a>
                                                        </li>
                                                    </ul>
                                                @else

                                                    @if(isset($filter_cat['child_menu']))
                                                        <li>
                                                            <ul class="list-unstyled child_cat_list"
                                                                style="padding-left: 15px">
                                                                @foreach($filter_cat['child_menu'] as $child_menu)

                                                                    <li><a href="{{ url('/c/'.$child_menu->seo_url) }}"><i
                                                                                    class="fa fa-angle-double-right"></i>{{$child_menu->name}}
                                                                        </a></li>
                                                                @endforeach
                                                            </ul>

                                                        </li>
                                                    @endif

                                                @endif
                                            @else
                                                <li>

                                                    <ul class="list-unstyled">
                                                        @foreach($filter_cat['sub_menu'] as $sub_menu)
                                                            <li>
                                                                <a href="{{ url('/c/'.$sub_menu->seo_url) }}">
                                                                    <i class="fa fa-angle-double-right"></i>
                                                                    {{$sub_menu->name}}
                                                                </a>
                                                            </li>
                                                        @endforeach
                                                    </ul>

                                                </li>

                                            @endif


                                        </ul>
                                    </li>
                                </ul>
                            </div>
                            <div class="table_cell">
                                <fieldset>
                                    <legend>Price</legend>
                                    <div class="range">
                                        Range :
                                        {{$tksign}}<span
                                                class="min_val"> {{(isset($url_req->price_min)) ? $url_req->price_min : 0}}</span>
                                        -
                                        {{$tksign}}<span
                                                class="max_val"> {{(isset($url_req->price_min)) ? $url_req->price_min : 500000}}</span>
                                        <input type="hidden" name="price_min" class="min_value" id="price_min"
                                               value="{{(isset($url_req->price_min)) ? $url_req->price_min : 0}}">
                                        <input type="hidden" name="price_max" class="max_value" id="price_max"
                                               value="{{(isset($url_req->price_max)) ? $url_req->price_max : 500000}}">
                                    </div>
                                    <div id="slider"
                                         class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all">
                                        <div class="ui-slider-range ui-widget-header ui-corner-all"></div>
                                        <span class="ui-slider-handle ui-state-default ui-corner-all"
                                              style="left: 10.0635%;"></span>
                                        <span class="ui-slider-handle ui-state-default ui-corner-all"
                                              style="left: 81.633%;"></span>
                                        <div class="ui-slider-range ui-widget-header ui-corner-all"
                                             style="left: 21.0635%; width: 60.5695%;"></div>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="panel-group sidebar-panel-group" id="accordion">
                                <div class="card panel sidebar-panel">
                                    <div class="panel-heading sidebar-panel-heading" id="headingOne">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-target="#collapseOne" aria-expanded="true"
                                               aria-controls="collapseOne">
                                                Bedroom
                                            </a>
                                        </h4>
                                    </div>

                                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                                         data-parent="#accordion">
                                        <div class="panel-body sidebar-panel-body">
                                            <div class="sidebar-category-list">
                                                <ul class="list-unstyled">
                                                    <li><a href="#">Almirah </a></li>
                                                    <li><a href="#">Beds </a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="slidecontainer">
                                <input type="range" min="50" max="100" value="50" class="slider" id="myRange">
                                <p>Price: <span id="demo"></span></p>
                            </div>

                            <div class="panel-group sidebar-panel-group" id="accordion" role="tablist"
                                 aria-multiselectable="true">

                                <div class="card panel sidebar-panel">
                                    <div class="panel-heading sidebar-panel-heading" id="headingOne1">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-target="#collapseOne1" aria-expanded="true"
                                               aria-controls="collapseOne1">
                                                Discount
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseOne1" class="collapse show" aria-labelledby="headingOne1"
                                         data-parent="#accordion">
                                        <div class="panel-body sidebar-panel-body">
                                            <div class="sidebar-category-btn">
                                                <label class="comone">
                                                    <input type="checkbox" checked="checked">
                                                    <span class="checkmark"></span>
                                                    One
                                                </label>
                                                <label class="comone">
                                                    <input type="checkbox">
                                                    <span class="checkmark"></span>
                                                    Two
                                                </label>
                                                <label class="comone">
                                                    <input type="checkbox">
                                                    <span class="checkmark"></span>
                                                    Three
                                                </label>
                                                <label class="comone">
                                                    <input type="checkbox">
                                                    <span class="checkmark"></span>
                                                    Four
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default sidebar-panel">
                                    <div class="panel-heading sidebar-panel-heading" role="tab" id="headingTwo">
                                        <h4 class="panel-title">
                                            <a class="collapsed" role="button" data-toggle="collapse"
                                               data-parent="#accordion" href="#collapseTwo" aria-expanded="false"
                                               aria-controls="collapseTwo">
                                                Bed Size
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel"
                                         aria-labelledby="headingTwo">
                                        <div class="panel-body sidebar-panel-body">
                                            <div class="sidebar-category-btn">
                                                <label class="comone">
                                                    <input type="checkbox" checked="checked">
                                                    <span class="checkmark"></span>
                                                    One
                                                </label>
                                                <label class="comone">
                                                    <input type="checkbox">
                                                    <span class="checkmark"></span>
                                                    Two
                                                </label>
                                                <label class="comone">
                                                    <input type="checkbox">
                                                    <span class="checkmark"></span>
                                                    Three
                                                </label>
                                                <label class="comone">
                                                    <input type="checkbox">
                                                    <span class="checkmark"></span>
                                                    Four
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    {{ Form::close() }}
                </div>

                <div class="col-md-9 col-sm-8">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="min-category-img category-img-padding">
                                <a href="category.html">
                                    <img src="{{ url('public/frontend/images/min-catecory/5.jpg') }}" alt="">
                                </a>
                            </div>
                        </div>

                        @foreach($products as $product)
                            @php
                                $first_image = App\ProductImages::where('main_pid', $product->proid)->where('is_main_image', 1)->get()->first();
                                //dd($product);
                                $second_image = App\ProductImages::where('main_pid', $product->proid)->where('is_main_image', 0)->get()->first();

                                $regularprice = $product->local_selling_price;
                                $save = ($product->local_selling_price * $product->local_discount) / 100;
                                $sp = round($regularprice - $save);

                                //dump($product->id);

                                echo product_design([
                                    'bootstrap_cols' => 'ptd-4 grit-row',
                                    'singular_class' => 'single-product single-category-product',
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
                                    'product_id' => $product->proid,
                                    'product_qty' => 1,
                                    'sign' => '&#2547; '
                                ]);
                            @endphp

                        @endforeach


                    </div>
                    <div class="more-product">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="recently-viewed-btn text-center">
                                    <a href="#">More Products...</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <section class="recent-view">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="recently-viewed-title-warp">
                        <div class="section-title recently-viewed-title">
                            <h3>Recently Viewed</h3>
                        </div>
                        <div class="recently-viewed-btn">
                            <a href="#">View All</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="recently-viewed owl-carousel owl-theme">
                    <div class="col-md-12">
                        <div class="single-product single-category-product">
                            <div class="product-img">
                                <a href="#">
                                    <img src="{{ url('public/frontend/images/products/1.png') }}" alt="product">
                                    <h3 class="product-eft ">30% OFF</h3>
                                </a>
                            </div>
                            <div class="product-over">
                                <div class="product-over-left">
                                    <div class="product-title">
                                        <h3><a href="#">Shoe Rack</a></h3>
                                        <h3 class="mdl"><a href="#">SRH-111-1-3-63</a></h3>
                                    </div>
                                    <div class="prodict-price">
                                        <div class="ct-price">
                                            <p>৳ 6,500</p>
                                        </div>
                                        <div class="old-price">
                                            <p><span class="old-pc">৳ 7,800</span> <span
                                                        class="save-pc"> Save ৳ 1,400</span></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="product-over-right">
                                    <div class="product-price-btn details-btn">
                                        <a href="#">Details</a>
                                    </div>
                                    <div class="product-price-btn buy-btn">
                                        <a href="#">Buy</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="single-product single-category-product">
                            <div class="product-img">
                                <a href="#">
                                    <img src="{{ url('public/frontend/images/products/1.png') }}" alt="product">
                                    <h3 class="product-eft ">30% OFF</h3>
                                </a>
                            </div>
                            <div class="product-over">
                                <div class="product-over-left">
                                    <div class="product-title">
                                        <h3><a href="#">Shoe Rack</a></h3>
                                        <h3 class="mdl"><a href="#">SRH-111-1-3-63</a></h3>
                                    </div>
                                    <div class="prodict-price">
                                        <div class="ct-price">
                                            <p>৳ 6,500</p>
                                        </div>
                                        <div class="old-price">
                                            <p><span class="old-pc">৳ 7,800</span> <span
                                                        class="save-pc"> Save ৳ 1,400</span></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="product-over-right">
                                    <div class="product-price-btn details-btn">
                                        <a href="#">Details</a>
                                    </div>
                                    <div class="product-price-btn buy-btn">
                                        <a href="#">Buy</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="single-product single-category-product">
                            <div class="product-img">
                                <a href="#">
                                    <img src="{{ url('public/frontend/images/products/1.png') }}" alt="product">
                                    <h3 class="product-eft ">30% OFF</h3>
                                </a>
                            </div>
                            <div class="product-over">
                                <div class="product-over-left">
                                    <div class="product-title">
                                        <h3><a href="#">Shoe Rack</a></h3>
                                        <h3 class="mdl"><a href="#">SRH-111-1-3-63</a></h3>
                                    </div>
                                    <div class="prodict-price">
                                        <div class="ct-price">
                                            <p>৳ 6,500</p>
                                        </div>
                                        <div class="old-price">
                                            <p><span class="old-pc">৳ 7,800</span> <span
                                                        class="save-pc"> Save ৳ 1,400</span></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="product-over-right">
                                    <div class="product-price-btn details-btn">
                                        <a href="#">Details</a>
                                    </div>
                                    <div class="product-price-btn buy-btn">
                                        <a href="#">Buy</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="single-product single-category-product">
                            <div class="product-img">
                                <a href="#">
                                    <img src="{{ url('public/frontend/images/products/1.png') }}" alt="product">
                                    <h3 class="product-eft ">30% OFF</h3>
                                </a>
                            </div>
                            <div class="product-over">
                                <div class="product-over-left">
                                    <div class="product-title">
                                        <h3><a href="#">Shoe Rack</a></h3>
                                        <h3 class="mdl"><a href="#">SRH-111-1-3-63</a></h3>
                                    </div>
                                    <div class="prodict-price">
                                        <div class="ct-price">
                                            <p>৳ 6,500</p>
                                        </div>
                                        <div class="old-price">
                                            <p><span class="old-pc">৳ 7,800</span> <span
                                                        class="save-pc"> Save ৳ 1,400</span></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="product-over-right">
                                    <div class="product-price-btn details-btn">
                                        <a href="#">Details</a>
                                    </div>
                                    <div class="product-price-btn buy-btn">
                                        <a href="#">Buy</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="single-product single-category-product">
                            <div class="product-img">
                                <a href="#">
                                    <img src="{{ url('public/frontend/images/products/1.png') }}" alt="product">
                                    <h3 class="product-eft ">30% OFF</h3>
                                </a>
                            </div>
                            <div class="product-over">
                                <div class="product-over-left">
                                    <div class="product-title">
                                        <h3><a href="#">Shoe Rack</a></h3>
                                        <h3 class="mdl"><a href="#">SRH-111-1-3-63</a></h3>
                                    </div>
                                    <div class="prodict-price">
                                        <div class="ct-price">
                                            <p>৳ 6,500</p>
                                        </div>
                                        <div class="old-price">
                                            <p><span class="old-pc">৳ 7,800</span> <span
                                                        class="save-pc"> Save ৳ 1,400</span></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="product-over-right">
                                    <div class="product-price-btn details-btn">
                                        <a href="#">Details</a>
                                    </div>
                                    <div class="product-price-btn buy-btn">
                                        <a href="#">Buy</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="single-product single-category-product">
                            <div class="product-img">
                                <a href="#">
                                    <img src="{{ url('public/frontend/images/products/1.png') }}" alt="product">
                                    <h3 class="product-eft ">30% OFF</h3>
                                </a>
                            </div>
                            <div class="product-over">
                                <div class="product-over-left">
                                    <div class="product-title">
                                        <h3><a href="#">Shoe Rack</a></h3>
                                        <h3 class="mdl"><a href="#">SRH-111-1-3-63</a></h3>
                                    </div>
                                    <div class="prodict-price">
                                        <div class="ct-price">
                                            <p>৳ 6,500</p>
                                        </div>
                                        <div class="old-price">
                                            <p><span class="old-pc">৳ 7,800</span> <span
                                                        class="save-pc"> Save ৳ 1,400</span></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="product-over-right">
                                    <div class="product-price-btn details-btn">
                                        <a href="#">Details</a>
                                    </div>
                                    <div class="product-price-btn buy-btn">
                                        <a href="#">Buy</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="single-product single-category-product">
                            <div class="product-img">
                                <a href="#">
                                    <img src="{{ url('public/frontend/images/products/1.png') }}" alt="product">
                                    <h3 class="product-eft ">30% OFF</h3>
                                </a>
                            </div>
                            <div class="product-over">
                                <div class="product-over-left">
                                    <div class="product-title">
                                        <h3><a href="#">Shoe Rack</a></h3>
                                        <h3 class="mdl"><a href="#">SRH-111-1-3-63</a></h3>
                                    </div>
                                    <div class="prodict-price">
                                        <div class="ct-price">
                                            <p>৳ 6,500</p>
                                        </div>
                                        <div class="old-price">
                                            <p><span class="old-pc">৳ 7,800</span> <span
                                                        class="save-pc"> Save ৳ 1,400</span></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="product-over-right">
                                    <div class="product-price-btn details-btn">
                                        <a href="#">Details</a>
                                    </div>
                                    <div class="product-price-btn buy-btn">
                                        <a href="#">Buy</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="single-product single-category-product">
                            <div class="product-img">
                                <a href="#">
                                    <img src="{{ url('public/frontend/images/products/1.png') }}" alt="product">
                                    <h3 class="product-eft ">30% OFF</h3>
                                </a>
                            </div>
                            <div class="product-over">
                                <div class="product-over-left">
                                    <div class="product-title">
                                        <h3><a href="#">Shoe Rack</a></h3>
                                        <h3 class="mdl"><a href="#">SRH-111-1-3-63</a></h3>
                                    </div>
                                    <div class="prodict-price">
                                        <div class="ct-price">
                                            <p>৳ 6,500</p>
                                        </div>
                                        <div class="old-price">
                                            <p><span class="old-pc">৳ 7,800</span> <span
                                                        class="save-pc"> Save ৳ 1,400</span></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="product-over-right">
                                    <div class="product-price-btn details-btn">
                                        <a href="#">Details</a>
                                    </div>
                                    <div class="product-price-btn buy-btn">
                                        <a href="#">Buy</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


@endsection
@include('frontend.products.search_js')