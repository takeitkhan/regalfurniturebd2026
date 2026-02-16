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

                <div class="col-md-2 col-sm-4">
                    {{ Form::open(array('url' => 'search?cat=' . $category_info->name, 'method' => 'post', 'value' => 'PATCH', 'id' => 'filtering', 'files' => true, 'autocomplete' => 'off')) }}

                    {{ Form::hidden('sort_by', null , ['id' => 'sort_by']) }}
                    {{ Form::hidden('sort_show', 25 , ['id' => 'sort_show']) }}

                    <div class="category-sidebar-area">
                        <div class="category-sidebar-title">
                            <h2>CATEGORIES</h2>
                        </div>
                        <div class="sidebar-pneal">

                            <div class="panel-group sidebar-panel-group" id="accordion">
                                <div class="card panel sidebar-panel">
                                    <div class="panel-heading sidebar-panel-heading" id="headingOne">
                                        <h4 class="panel-title">
                                            @if(isset($filter_cat['parent_cat']->name))
                                            <a data-toggle="collapse" data-target="#collapseOne" aria-expanded="true"
                                               aria-controls="collapseOne">
                                                {{ $filter_cat['parent_cat']->name }}
                                            </a>

                                            @endif
                                        </h4>
                                    </div>

                                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                                         data-parent="#accordion">
                                        <div class="panel-body sidebar-panel-body">
                                            <div class="sidebar-category-list">
                                                <ul class="list-unstyled">
                                                    @if(isset($filter_cat['sub_menu']->name))

                                                        <li>
                                                            <a href="{{ url('/c/'.$filter_cat['sub_menu']->seo_url) }}"
                                                               style="font-weight: bold">
                                                                {{ $filter_cat['sub_menu']->name }}
                                                            </a>
                                                        </li>
                                                        @if(isset($filter_cat['child_menu']->name))
                                                            <ul class="list-unstyled child_cat_sng" style="padding-left: 15px">
                                                                <li>
                                                                    <a href="{{ url('/c/'.$filter_cat['child_menu']->seo_url) }}">
                                                                        {{ $filter_cat['child_menu']->name }}
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        @else

                                                            @if(isset($filter_cat['child_menu']))
                                                                <li>
                                                                    <ul class="list-unstyled child_cat_list"
                                                                        style="padding-left: 15px">
                                                                        @foreach($filter_cat['child_menu'] as $child_menu)

                                                                            <li><a href="{{ url('/c/'.$child_menu->seo_url) }}">
                                                                                    {{$child_menu->name}}
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

                                                                            {{$sub_menu->name}}
                                                                        </a>
                                                                    </li>
                                                                @endforeach
                                                            </ul>

                                                        </li>

                                                    @endif


                                                </ul>

                                            </div>
                                        </div>
                                    </div>




                                </div>



                            </div>

                            <div class="price-range-block">

                                <h4>Price Range</h4>

                                <div id="slider-range" class="price-filter-range" name="rangeInput"></div>
                                <div class="max-pc_wp" style="margin:30px auto">

                                    <div class="max-pc">
                                        <p>Min: </p>
                                        <input type="text" name="price_min" min=0  id="min_price" class="price-range-field" value="{{(isset($url_req->price_min)) ? $url_req->price_min : 0}}" data-min-range="0" />
                                    </div>
                                    <div class="max-pc">
                                        <p>Max: </p>
                                        <input type="text" name="price_max" min=0  id="max_price" class="price-range-field" value="{{(isset($url_req->price_max)) ? $url_req->price_max : 200000}}" data-max-range="200000"/>
                                    </div>

                                    <div class="max-pc">
                                      
                                        <a class="btn btn-sm" id="sumbit-price-range" href="javascript: void(0)">Search &nbsp;<i class="fa fa-angle-double-right" aria-hidden="true"></i></a>
                                    </div>
                                </div>        
                            </div>
                            <div class="panel-group sidebar-panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                @php
                                    $fli_count = 1;
                                @endphp

                            @foreach($filters_att as $filter)

                                    <div class="card panel sidebar-panel">

                                        <div class="panel-heading sidebar-panel-heading" id="headingOne1">
                                            <h4 class="panel-title">
                                                <a href="javascript: void(0)" data-toggle="collapse" data-target="{{'#collapse'.$fli_count}}" aria-expanded="true" aria-controls="{{'collapse'.$fli_count}}">
                                                    {{ $filter->field_label }}
                                                </a>
                                            </h4>
                                        </div>




                                    @php
                                        $att_data = explode('|',$filter->default_value);
                                    @endphp


                                        <div id="{{'collapse'.$fli_count}}" class="collapse show" aria-labelledby="{{'collapse'.$fli_count}}"
                                             data-parent="#accordion">
                                            <div class="panel-body sidebar-panel-body">
                                                <div class="sidebar-category-btn">

                                        @foreach($att_data as $data)
                                            @php
                                                $valu = explode(':',$data);
                                            @endphp
                                            @if(isset($valu[1]))
                                                @php
                                                    $field_name = $filter->field_name;
                                                    if(isset($url_req->$field_name)){
                                                        if (in_array($valu[1], $url_req->$field_name)){
                                                               $checked = 'checked';
                                                              }else{
                                                              $checked = '';
                                                              }


                                                    }else{
                                                    $checked = '';
                                                    }




                                                @endphp


                                                <label class="comone">
                                                    <input name="{{ $filter->field_name.'[]' }}" id="chkbox"
                                                           type="checkbox"
                                                           value="{{ $valu[1] }}" {{ $checked }}>
                                                    <span class="checkmark"></span> {{ $valu[1] }}
                                                </label>
                                            @endif
                                        @endforeach


                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @php
                                        ++$fli_count;
                                    @endphp

                            @endforeach



                            </div>
                        </div>

                    </div>
                    {{ Form::close() }}
                </div>


       

                <div class="col-md-10 col-sm-8">
                    <div class="row">
                        
                        <div class="col-md-12">
                            <div class="min-category-img category-img-padding">
                                @if($category_info->banner1) 
                                    <a href="javascript:void(0);">
                                        <img src="{{ $category_info->banner1 }}" alt="">
                                    </a>
                                @else
                                <a href="javascript:void(0);">
                                    <img src="{{ url('public/frontend/images/min-catecory/5.jpg') }}" alt="">
                                </a>

                                @endif
                            </div>
                        </div>

                        
                       
                            <div class="col-md-12">
                                <div class="expane-col-icone-wp">
                                    <div class="row">
                                        <div class="col-8">
                                            <div class="sort-by_wp">
                                                <div class="sort-by">
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
                                                
                                   
                                    
                                                <div class="page-showe">
                                                    <div class="page-showe-wp">
                                                       <div class="text-page-showe">show: </div> 
                                                    <div class="showe-select">
                                                        <select class="form-control" id="item_count"  name="item_count">
                                                            <option value="16" {{ ($url_req->sort_show == '16')? 'selected' : '' }}>16
                                                            </option>
                                                            <option value="32" {{ ($url_req->sort_show == '32')? 'selected' : '' }}>32
                                                            </option>
                                                            <option value="48" {{ ($url_req->sort_show == '48')? 'selected' : '' }}>48
                                                            </option>
                                                            <option value="100" {{ ($url_req->sort_show == '100')? 'selected' : '' }}>100
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
                                                    <li><a class="two-list two-list_common" href="javascript:void(0);"><i class="fa fa-th"></i></a></li>
                                                    <li><a class="four-list two-list_common two-list_common_onasf" href="javascript:void(0);"><i class="fa fa-th-list"></i></a></li>
                                                    
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            @if($products->count() > 0)
                        
                        @foreach($products as $product)
                            <div class="ptd-4 grit-row">
                            @php
                                
                                //dd($product);
                                $second_image = App\ProductImages::where('main_pid', $product->proid)->where('is_main_image', 0)->get()->first();

                                $first_image = App\ProductImages::where('main_pid', $product->proid)->where('is_main_image', 1)->get()->first();
                                
                                   
                                $img = isset($second_image->full_size_directory) ? url($second_image->full_size_directory) : false;
                                $img = !$img && !empty($first_image->full_size_directory) ? url($first_image->full_size_directory) : $img;
                                $img = !$img ? url('storage/uploads/fullsize/2019-01/default.jpg') : $img;
                                
                                
                                $second_image = isset($second_image->full_size_directory) && isset($first_image->full_size_directory) ? url($first_image->full_size_directory) : null;
                             
                                
                                
                                $regularprice = $product->local_selling_price;
                                $save = ($product->local_selling_price * $product->local_discount) / 100;
                                $sp = round($regularprice - $save);

                                //dump($product->id);

                                echo product_design_two([
                                    'bootstrap_cols' => 'ptd-5product grit-row',
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

                        @endforeach


                    </div>

                    <div class="product-filter product-filter-bottom filters-panel">
                        <div class="">
                

                            {{ $products->appends(request()->query())->links('component.fpaginator', ['object' => $products]) }}

                        </div>
                    </div>
                    
                    @else
                    @include('frontend.common.error')
                    
                    
                    @endif
                  
                </div>
            </div>
        </div>
    </section>


@include('frontend.products.single.recently_on_left')

@endsection
@include('frontend.products.search_js')

<script>
$(document).ready(function() { 
$('div').css('display','block');
})
</script>