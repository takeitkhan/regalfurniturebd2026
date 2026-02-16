@extends('frontend.layouts.app')

@section('content')

    <?php $tksign = '&#2547; '; ?>

    <?php

    $url_one = \Request::segment(1);
    $url_two = \Request::segment(2);
    $cat_url = '/' . $url_one . '/' . $url_two;

    $url_req = request();

   // dump($categories);


    ?>
    <div class="main-container container">
        <ul class="breadcrumb">
            <li><a href="#"><i class="fa fa-home"></i></a></li>
            <li><a href="#">Search</a></li>
        </ul>


        <div class="row">


            <aside class="col-sm-4 col-md-3 content-aside" id="column-left">

                <div class="module category-style">
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
                                </footer><br>

                                <span class="modtitle-tit">RELATED CATEGORIES</span>

                                <ul class="list-unstyled child_cat_sng">
                                    @foreach($categories as $item)
                                    <li style="font-weight: normal;"><i class="fa fa-angle-double-right"></i> <a href="{{ url('/c/'.$item->seo_url) }}">{{ $item->name }}</a></li>
                                        @endforeach

                                </ul>

                                {{--<ul class="list-unstyled one-cat">--}}
                                    {{--<li>--}}
                                        {{--@if(isset($filter_cat['parent_cat']->name))--}}
                                            {{--<a href="{{ url('/c/'.$filter_cat['parent_cat']->seo_url) }}">{{ $filter_cat['parent_cat']->name }}</a>--}}
                                        {{--@endif--}}
                                        {{--<ul class="list-unstyled">--}}
                                            {{--@if(isset($filter_cat['sub_menu']->name))--}}

                                                {{--<li>--}}
                                                    {{--<a href="{{ url('/c/'.$filter_cat['sub_menu']->seo_url) }}"--}}
                                                       {{--style="font-weight: bold">--}}
                                                        {{--<i class="fa fa-angle-double-down"></i>{{ $filter_cat['sub_menu']->name }}--}}
                                                    {{--</a>--}}
                                                {{--</li>--}}
                                                {{--@if(isset($filter_cat['child_menu']->name))--}}
                                                    {{--<ul class="list-unstyled child_cat_sng" style="padding-left: 15px">--}}
                                                        {{--<li>--}}
                                                            {{--<a href="{{ url('/c/'.$filter_cat['child_menu']->seo_url) }}">--}}
                                                                {{--<i class="fa fa-angle-double-left"></i>{{ $filter_cat['child_menu']->name }}--}}
                                                            {{--</a>--}}
                                                        {{--</li>--}}
                                                    {{--</ul>--}}
                                                {{--@else--}}

                                                    {{--@if(isset($filter_cat['child_menu']))--}}
                                                        {{--<li>--}}
                                                            {{--<ul class="list-unstyled child_cat_list"--}}
                                                                {{--style="padding-left: 15px">--}}
                                                                {{--@foreach($filter_cat['child_menu'] as $child_menu)--}}

                                                                    {{--<li><a href="{{ url('/c/'.$child_menu->seo_url) }}"><i--}}
                                                                                    {{--class="fa fa-angle-double-right"></i>{{$child_menu->name}}--}}
                                                                        {{--</a></li>--}}
                                                                {{--@endforeach--}}
                                                            {{--</ul>--}}

                                                        {{--</li>--}}
                                                    {{--@endif--}}

                                                {{--@endif--}}
                                            {{--@else--}}
                                                {{--<li>--}}

                                                    {{--<ul class="list-unstyled">--}}
                                                        {{--@foreach($filter_cat['sub_menu'] as $sub_menu)--}}
                                                            {{--<li>--}}
                                                                {{--<a href="{{ url('/c/'.$sub_menu->seo_url) }}">--}}
                                                                    {{--<i class="fa fa-angle-double-right"></i>--}}
                                                                    {{--{{$sub_menu->name}}--}}
                                                                {{--</a>--}}
                                                            {{--</li>--}}
                                                        {{--@endforeach--}}
                                                    {{--</ul>--}}

                                                {{--</li>--}}

                                            {{--@endif--}}


                                        {{--</ul>--}}
                                    {{--</li>--}}
                                {{--</ul>--}}
                            </h3>

                            <ul id="cat_accordion" class="list-group">





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

                                {{--@foreach($filters_att as $filter)--}}

                                    {{--<li class="hadchild">--}}
                                        {{--<a href="javascript: void(0)"--}}
                                           {{--class="cutom-parent cutom-parent2 active">{{ $filter->field_label }}</a>--}}
                                        {{--<span class="button-view  fa fa-plus-square-o"></span>--}}
                                        {{--@php--}}
                                            {{--$att_data = explode('|',$filter->default_value);--}}
                                        {{--@endphp--}}
                                        {{--<ul style="display: block;" class="sidebar-category-btn">--}}
                                            {{--@foreach($att_data as $data)--}}
                                                {{--@php--}}
                                                    {{--$valu = explode(':',$data);--}}
                                                {{--@endphp--}}
                                                {{--@if(isset($valu[1]))--}}
                                                    {{--@php--}}
                                                        {{--$field_name = $filter->field_name;--}}
                                                        {{--if(isset($url_req->$field_name)){--}}
                                                            {{--if (in_array($valu[1], $url_req->$field_name)){--}}
                                                                   {{--$checked = 'checked';--}}
                                                                  {{--}else{--}}
                                                                  {{--$checked = '';--}}
                                                                  {{--}--}}


                                                        {{--}else{--}}
                                                        {{--$checked = '';--}}
                                                        {{--}--}}




                                                    {{--@endphp--}}


                                                    {{--<label class="comone">--}}
                                                        {{--<input name="{{ $filter->field_name.'[]' }}" id="chkbox"--}}
                                                               {{--type="checkbox"--}}
                                                               {{--value="{{ $valu[1] }}" {{ $checked }}>--}}
                                                        {{--<span class="checkmark"></span> {{ $valu[1] }}--}}
                                                    {{--</label>--}}
                                                {{--@endif--}}
                                            {{--@endforeach--}}

                                        {{--</ul>--}}
                                    {{--</li>--}}
                                {{--@endforeach--}}
                            </ul>
                        </div>
                    </div>

                    {{ Form::close() }}

                </div>
            </aside>


            <div id="content" class="col-md-9 col-sm-8">
                <div class="products-category">
                    {{--<h3 class="title-category ">{{ $category_info->name }}</h3>--}}
                    {{--<div class="category-desc">--}}
                        {{--<div class="row">--}}
                            {{--<div class="col-sm-12">--}}
                                {{--<div class="banners">--}}
                                    {{--<div>--}}
                                        {{--@if(!empty($category_info->page_image))--}}
                                            {{--<img src="{{ $category_info->page_image }}"--}}
                                                 {{--alt="{{ $category_info->name }}">--}}
                                            {{--<br>--}}
                                        {{--@endif--}}
                                    {{--</div>--}}
                                {{--</div>--}}

                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    <div class="product-filter product-filter-top filters-panel">
                        <div class="row">
                            <div class="col-md-5 col-sm-3 col-xs-12 view-mode">

                                <div class="list-view">
                                    <button class="btn btn-default grid active" data-view="grid" data-toggle="tooltip"
                                            data-original-title="Grid">
                                        <i class="fa fa-th"></i>
                                    </button>
                                    <button class="btn btn-default list" data-view="list" data-toggle="tooltip"
                                            data-original-title="List">
                                        <i class="fa fa-th-list"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="short-by-show form-inline text-right col-md-7 col-sm-9 col-xs-12">
                                <div class="form-group short-by">
                                    <label class="control-label" for="item_sort">
                                        Sort By:
                                    </label>
                                    <select class="form-control" name="item_sort" id="item_sort">
                                        <option value="" {{ ($url_req->sort_by == 'title_asc' || $url_req->sort_by == null)? 'selected' : '' }}>
                                            Default
                                        </option>
                                        <option value="title_asc" {{ ($url_req->sort_by == 'title_asc')? 'selected' : '' }}>
                                            Name (A - Z)
                                        </option>
                                        <option value="title_desc" {{ ($url_req->sort_by == 'title_desc')? 'selected' : '' }}>
                                            Name (Z - A)
                                        </option>
                                        <option value="price_asc" {{ ($url_req->sort_by == 'price_asc')? 'selected' : '' }}>
                                            Price (Low &gt; High)
                                        </option>
                                        <option value="price_desc" {{ ($url_req->sort_by == 'price_desc')? 'selected' : '' }}>
                                            Price (High &gt; Low)
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    {{--<input name="item_counts" value="30">--}}
                                    <label class="control-label" for="item_count">
                                        Show:
                                    </label>
                                    <select id="item_count" class="form-control" name="item_count">
                                        <option value="25" {{ ($url_req->sort_show == '25')? 'selected' : '' }}>25
                                        </option>
                                        <option value="50" {{ ($url_req->sort_show == '50')? 'selected' : '' }}>50
                                        </option>
                                        <option value="75" {{ ($url_req->sort_show == '75')? 'selected' : '' }}>75
                                        </option>
                                        <option value="100" {{ ($url_req->sort_show == '100')? 'selected' : '' }}>100
                                        </option>
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="products-list row nopadding-xs so-filter-gird grid">

                        @foreach($products as $product)

                            <?php //dump($product->id); ?>

                            @php
                                $first_image = \App\ProductImages::where('main_pid', $product->proid)->where('is_main_image', 1)->get()->first();
                                //dump($first_image);
                                $second_image = \App\ProductImages::where('main_pid', $product->proid)->where('is_main_image', 0)->get()->first();

                                $regularprice = $product->local_selling_price;
                                $save = ($product->local_selling_price * $product->local_discount) / 100;
                                $sp = round($regularprice - $save);

                                //dump($product->id);

                                echo product_design([
                                    'bootstrap_cols' => 'product-layout col-lg-15 col-md-4 col-sm-6 col-xs-6',
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
                    <div class="product-filter product-filter-bottom filters-panel">
                        <div class="row">
                            {{--<div class="col-sm-6 text-left"></div>--}}
                            {{--<div class="col-sm-6 text-right">Showing 1 to {{ $products->count() }}--}}
                            {{--of {{ $products->count() }} (1 Pages)--}}
                            {{--</div>--}}

                            {{ $products->appends(request()->query())->links('component.fpaginator', ['object' => $products]) }}

                            {{--{{ $users->appends(request()->query())->links() }}--}}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
@include('frontend.products.search_key_js')