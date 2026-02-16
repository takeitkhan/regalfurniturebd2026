@extends('frontend.layouts.app')

@section('content')

<?php $tksign = '&#2547; '; ?>

<?php

    $url_one = \Request::segment(1);
    $url_two = \Request::segment(2);
    $cat_url = '/' . $url_one . '/' . $url_two;

    $url_req = request();
    $dataLayerProducts = [];

    // dump($categories);

    ?>

<div class="main-container container">
   
    @if($products->count() > 0)
    <ul class="breadcrumb">
        <li><a href="#"><i class="fa fa-home"></i></a></li>
        <li><a href="#">Search</a></li>
    </ul>

 <div class="seachSidenav">
        <div class="d-lg-none">
          <span style="font-size:30px;cursor:pointer" class="hidedown bar" onclick="openSearchBar()">
            <i class="fa fa-angle-double-right"></i>
            <i class="fa fa-angle-double-right"></i>Search Filter
             </span>
        </div>        
        <div id="OpenSarNav" class="sidenav2">
            <a href="javascript:void(0)" class="closebtn2 d-lg-none" onclick="closeSearchBar()">&times;</a>
        <div class="col-md-12 col-sm-12 responsive_sidebar mt-5 d-lg-none" >

            <div class="category-sidebar-area">
                <h3 class="modtitle">Filters</h3>
                {{ Form::open(array('url' => 'search', 'method' => 'post', 'value' => 'PATCH', 'id' => 'filtering', 'files' => true, 'autocomplete' => 'off')) }}

                {{ Form::hidden('sort_by', null , ['id' => 'sort_by']) }}
                {{ Form::hidden('sort_show', 25 , ['id' => 'sort_show']) }}


                <div class="modcontent">
                    <div class="box-category">
                        <h3 class="modtitle-single">
                            <div class="table_cell" style="z-index: 103;">
                                <legend><small>Search on this category</small></legend>
                                {{ Form::text('keyword', (isset($url_req->keyword))? $url_req->keyword : null , [
                                    'class' => 'keyword_filter form-control',
                                    'size'=> '50',
                                    'autocomplete'=> 'off',
                                    'placeholder'=> 'Search',

                                    ]) }}
                            </div>

                            <div class="bottom_box">
                                <div class="buttons_row">
                                    <a class="button_grey button_submit  submit_one-hab" href="javascript: void(0)"
                                        style="padding:3px 8px" id="keyword_filter_submit">Search</a>
                                    <a class="button_grey filter_reset submit_one-hab" href="javascript: void(0)"
                                        style="padding:3px 8px" id="keyword_filter_reset">Reset</a>
                                    
                                </div>
                                <!--Back To Top-->
                                <!-- <div class="back-to-top"><i class="fa fa-angle-up"></i></div> -->
                            </div>


                            <span class="modtitle-tit">RELATED CATEGORIES</span>

                            <ul class="list-unstyled child_cat_sng">
                                @foreach($categories as $item)
                                <li style="font-weight: normal;"><i class="fa fa-angle-double-right"></i> <a
                                        href="{{ url('/c/'.$item->seo_url) }}">{{ $item->name }}</a></li>
                                @endforeach

                            </ul>


                        </h3>


                        <div class="price-range-block">

                            <h4>Price Range</h4>

                            <div id="slider-range" class="price-filter-range" name="rangeInput"></div>
                            <div class="max-pc_wp" style="margin:30px auto">

                                <div class="max-pc">
                                    <p>Min: </p>
                                    <input type="number" name="price_min" min=0 id="min_price" class="price-range-field"
                                        value="{{(isset($url_req->price_min)) ? $url_req->price_min : 0}}"
                                        data-min-range="0" />
                                </div>
                                <div class="max-pc">
                                    <p>Max: </p>
                                    <input type="number" name="price_max" min=0 id="max_price" class="price-range-field"
                                        value="{{(isset($url_req->price_max)) ? $url_req->price_max : 200000}}"
                                        data-max-range="200000" />
                                </div>

                                <div class="max-pc">

                                    <a class="btn btn-sm" id="sumbit-price-range" href="javascript: void(0)"><i
                                            class="fa fa-angle-double-right" aria-hidden="true"></i></a>
                                </div>
                            </div>
                        </div>
                        <ul id="cat_accordion" class="list-group">


                        </ul>
                    </div>
                </div>

                {{ Form::close() }}

            </div>
        </div>
            
    </div>
 </div>

    <div class="row">

        <div class="col-md-2 col-sm-12 d-lg-block" style="display: none">

            <div class="category-sidebar-area">
                <h3 class="modtitle">Filters</h3>
                {{ Form::open(array('url' => 'search', 'method' => 'post', 'value' => 'PATCH', 'id' => 'filtering', 'files' => true, 'autocomplete' => 'off')) }}

                {{ Form::hidden('sort_by', null , ['id' => 'sort_by']) }}
                {{ Form::hidden('sort_show', 25 , ['id' => 'sort_show']) }}


                <div class="modcontent">
                    <div class="box-category">
                        <h3 class="modtitle-single">
                            <div class="table_cell" style="z-index: 103;">
                                <legend><small>Search on this category</small></legend>
                                {{ Form::text('keyword', (isset($url_req->keyword))? $url_req->keyword : null , [
                                    'class' => 'keyword_filter form-control',
                                    'size'=> '50',
                                    'autocomplete'=> 'off',
                                    'placeholder'=> 'Search',

                                    ]) }}
                            </div>

                            <div class="bottom_box">
                                <div class="buttons_row">
                                    <a class="button_grey button_submit  submit_one-hab" href="javascript: void(0)"
                                        style="padding:3px 8px" id="keyword_filter_submit">Search</a>
                                    <a class="button_grey filter_reset submit_one-hab" href="javascript: void(0)"
                                        style="padding:3px 8px" id="keyword_filter_reset">Reset</a>
                                   
                                </div>
                                <!--Back To Top-->
                                <!-- <div class="back-to-top"><i class="fa fa-angle-up"></i></div> -->
                            </div><br>

                            <span class="modtitle-tit">RELATED CATEGORIES</span>

                            <ul class="list-unstyled child_cat_sng">
                                @foreach($categories as $item)
                                <li style="font-weight: normal;"><i class="fa fa-angle-double-right"></i> <a
                                        href="{{ url('/c/'.$item->seo_url) }}">{{ $item->name }}</a></li>
                                @endforeach

                            </ul>


                        </h3>


                        <div class="price-range-block">

                            <h4>Price Range</h4>

                            <div id="slider-range" class="price-filter-range" name="rangeInput"></div>
                            <div class="max-pc_wp" style="margin:30px auto">

                                <div class="max-pc">
                                    <p>Min: </p>
                                    <input type="number" name="price_min" min=0 id="min_price" class="price-range-field"
                                        value="{{(isset($url_req->price_min)) ? $url_req->price_min : 0}}"
                                        data-min-range="0" />
                                </div>
                                <div class="max-pc">
                                    <p>Max: </p>
                                    <input type="number" name="price_max" min=0 id="max_price" class="price-range-field"
                                        value="{{(isset($url_req->price_max)) ? $url_req->price_max : 200000}}"
                                        data-max-range="200000" />
                                </div>

                                <div class="max-pc">

                                    <a class="btn btn-sm" id="sumbit-price-range" href="javascript: void(0)"><i
                                            class="fa fa-angle-double-right" aria-hidden="true"></i></a>
                                </div>
                            </div>
                        </div>
                        <ul id="cat_accordion" class="list-group">


                        </ul>
                    </div>
                </div>

                {{ Form::close() }}

            </div>
        </div>
        <div class="col-md-12 col-lg-10 col-sm-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="min-category-img category-img-padding">
                        <!-- <a href="category.html">
                                            <img src="{{ url('public/frontend/images/min-catecory/5.jpg') }}" alt="">
                                        </a>-->
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="expane-col-icone-wp">
                        <div class="row">
                            <div class="col-8">
                                <div class="sort-by_wp">
                                    <div class="sort-by">
                                        <select class="form-control" name="item_sort" id="item_sort">
                                            <option value=""
                                                {{ ($url_req->sort_by == 'title_asc' || $url_req->sort_by == null)? 'selected' : '' }}>
                                                Default
                                            </option>
                                            <option value="title_asc"
                                                {{ ($url_req->sort_by == 'title_asc')? 'selected' : '' }}>
                                                Name (A - Z)
                                            </option>
                                            <option value="title_desc"
                                                {{ ($url_req->sort_by == 'title_desc')? 'selected' : '' }}>
                                                Name (Z - A)
                                            </option>
                                            <option value="price_asc"
                                                {{ ($url_req->sort_by == 'price_asc')? 'selected' : '' }}>
                                                Price (Low &gt; High)
                                            </option>
                                            <option value="price_desc"
                                                {{ ($url_req->sort_by == 'price_desc')? 'selected' : '' }}>
                                                Price (High &gt; Low)
                                            </option>


                                        </select>
                                    </div>



                                    <div class="page-showe">
                                        <div class="page-showe-wp">
                                            <div class="text-page-showe">show: </div>
                                            <div class="showe-select">
                                                <select class="form-control" id="item_count" name="item_count">
                                                    <option value="16"
                                                        {{ ($url_req->sort_show == '16')? 'selected' : '' }}>16
                                                    </option>
                                                    <option value="32"
                                                        {{ ($url_req->sort_show == '32')? 'selected' : '' }}>32
                                                    </option>
                                                    <option value="48"
                                                        {{ ($url_req->sort_show == '48')? 'selected' : '' }}>48
                                                    </option>
                                                    <option value="100"
                                                        {{ ($url_req->sort_show == '100')? 'selected' : '' }}>100
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="expane-col-icone text-right">
                                    <ul class="list-unstyled">
                                        <li><a class="two-list two-list_common" href="javascript:void(0);"><i
                                                    class="fa fa-th"></i></a></li>
                                        <li><a class="four-list two-list_common two-list_common_onasf"
                                                href="javascript:void(0);"><i class="fa fa-th-list"></i></a></li>

                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                @foreach($products as $key => $product)
                <div class="ptd-4 grit-row">
                    @php
                    $first_image = App\ProductImages::where('main_pid', $product->proid)->where('is_main_image',
                    1)->get()->first();
                    //dd($product);
                    $second_image = App\ProductImages::where('main_pid', $product->proid)->where('is_main_image',
                    0)->get()->first();

                    $img = isset($second_image->full_size_directory) ? url($second_image->full_size_directory) : false;
                    $img = !$img && !empty($first_image->full_size_directory) ? url($first_image->full_size_directory) :
                    $img;
                    $img = !$img ? url('storage/uploads/fullsize/2019-01/default.jpg') : $img;


                    $second_image = isset($second_image->full_size_directory) &&
                    isset($first_image->full_size_directory) ? url($first_image->full_size_directory) : null;


                    $regularprice = $product->local_selling_price;
                    $save = ($product->local_selling_price * $product->local_discount) / 100;
                    $sp = round($regularprice - $save);

                    //dump($product->id);

                    echo product_design_two([
                    'bootstrap_cols' => 'ptd-4 grit-row',
                    'singular_class' => 'single-product single-category-product',
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
                    'product_id' => $product->proid,
                    'product_qty' => 1,
                    'sign' => '&#2547; '
                    ]);
                    @endphp
                </div>
                
                @php
                $dataLayerProducts[] = [
                'name' => $product->title,
                'id' => $product->id,
                'price' => $sp,
                'brand' => 'Regal',
                'category' => '',
                'variant' => '',
                'list' => 'Search Results',
                'position' => $key
                ];
                @endphp

                @endforeach


            </div>

            <div class="product-filter product-filter-bottom filters-panel">
                <div class="row">


                    {{ $products->appends(request()->query())->links('component.fpaginator', ['object' => $products]) }}

                </div>
            </div>



        </div>
    </div>


    @else
    @include('frontend.common.error')


    @endif
</div>




<script>
function openSearchBar() {
  document.getElementById("OpenSarNav").style.width = "250px";
}

function closeSearchBar() {
  document.getElementById("OpenSarNav").style.width = "0";
}

</script>
<script>
dataLayer.push({
'event': 'productImpressions',			//used for creating GTM trigger
  'ecommerce': {
    'currencyCode': 'BDT',                      
    'impressions': <?php echo json_encode($dataLayerProducts) ?>
  }
});
</script>


@endsection
@include('frontend.products.search_key_js')