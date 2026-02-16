@extends('layouts.app')

@section('title', 'Product')
@section('sub_title', 'product add or modification form')
@section('content')
    <div class="row">
        @if(Session::has('success'))
            <div class="col-md-12">
                <div class="callout callout-success">
                    {{ Session::get('success') }}
                </div>
            </div>
        @endif

        {{--@endif--}}
        @if($errors->any())
            <div class="col-md-12">
                <div class="callout callout-danger">
                    <h4>Warning!</h4>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
        
        <div class="col-md-8">
            @component('component.form')
                @slot('form_id')
                    @if (!empty($user->id))
                        product_forms
                    @else
                        product_form
                    @endif
                @endslot
                @slot('title')
                    @if (!empty($product->id))
                        Edit product
                    @else
                        Add a new product
                    @endif

                @endslot

                @slot('route')
                    @if (!empty($product->id))
                        product/{{$product->id}}/update
                    @else
                        product_save
                    @endif
                @endslot

                @slot('fields')
                    <div class="form-group">
                        {{ Form::hidden('user_id', (!empty(\Auth::user()->id) ? \Auth::user()->id : NULL), ['type' => 'hidden']) }}
                        {{ Form::hidden('lang', (!empty($post->lang) ? $post->lang : 'en'), ['type' => 'hidden']) }}
                        {{ Form::label('title', 'Title', array('class' => 'title')) }}
                        {{ Form::text('title', (!empty($product->title) ? $product->title : NULL), ['required', 'class' => 'form-control', 'id' => 'title',  'placeholder' => 'Enter title...']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('sub_title', 'Sub Title', array('class' => 'sub_title')) }}
                        {{ Form::text('sub_title', (!empty($product->sub_title) ? $product->sub_title : NULL), ['required', 'class' => 'form-control', 'placeholder' => 'Enter sub_title...']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('seo_url', 'SEO URL', array('class' => 'seo_url')) }}
                        {{ Form::text('seo_url', (!empty($product->seo_url) ? $product->seo_url : NULL), ['type' => 'email', 'required', 'class' => 'form-control', 'id' => 'seo_url', 'placeholder' => 'Enter seo URL...']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('description', 'Description', array('class' => 'description')) }}
                        {{ Form::textarea('description', (!empty($product->description) ? $product->description : NULL), ['required', 'class' => 'form-control', 'id' => 'wysiwyg', 'placeholder' => 'Enter details content...']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('images', 'Image ID/s', array('class' => 'images')) }}
                        @if(!empty($product->product_attributes))
                            <?php $im = image_ids($product, TRUE, TRUE); ?>
                            {{ Form::text('images', (!empty($im) ? $im : NULL), ['type' => 'text', 'id' => 'image_ids', 'required', 'class' => 'form-control', 'placeholder' => 'Enter image IDs...']) }}
                        @else
                            {{ Form::text('images', NULL, ['type' => 'text', 'id' => 'image_ids', 'required', 'class' => 'form-control', 'placeholder' => 'Enter image IDs...']) }}
                        @endif
                        <small id="show_image_names"></small>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                @if(!empty($product))
                                    <?php $attributes_p = product_attributes($product, TRUE); ?>
                                    {!! $attributes_p['photos'] !!}
                                @endif

                                {{ Form::label('is_sticky', 'Is it sticky?', array('class' => 'is_sticky')) }}
                                <div class="radio">
                                    <label>
                                        {{ Form::radio('is_sticky', 1, ((!empty($product) ? $product->is_sticky == 1 : 1) ? TRUE : FALSE), ['class' => 'radio']) }}
                                        Yes. This product will be marked as selected top product
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        {{ Form::radio('is_sticky', 0, ((!empty($product) ? $product->is_sticky == 0 : 0) ? TRUE : FALSE), ['class' => 'radio']) }}
                                        No. This product will no be marked as selected top product
                                    </label>
                                </div>

                                {{ Form::label('is_active', 'Will it be active?', array('class' => 'is_active')) }}
                                <div class="radio">
                                    <label>
                                        {{ Form::radio('is_active', 1, ((!empty($product) ? $product->is_active == 1 : 1) ? TRUE : FALSE), ['class' => 'radio']) }}
                                        Yes. This product will publish
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        {{ Form::radio('is_active', 0, ((!empty($product) ? $product->is_active == 0 : 0) ? TRUE : FALSE), ['class' => 'radio']) }}
                                        No. This product will save as draft
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="box box-primary">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">Product Categories <span id="photoCounter"></span></h3>
                                        <div class="col-xs-12" style="margin-top: 15px;">
                                            <div class="pre-scrollable">
                                                <?php
                                                if (!empty($product)) {
                                                    $ids = category_ids($product);
                                                }

                                                ?>
                                                {!! category_h_checkbox_html($categories, $parent = 100, $name = 'category_id', !empty($ids) ? $ids : array()) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr/>
                    <div class="form-group text-center">
                        <p>
                        <h4>Following product variations form for the variations of same type product with different
                            variation. Such as - color, material, selling price and so on. You can add much as you want
                            by clicking green add more button under product variation form.
                        </h4>
                        </p>
                    </div>
                    <div class="form-group">
                        <div class="box box-danger">
                            <div class="box-header with-border">
                                <h3 class="box-title">Product Variations</h3>
                            </div>
                            <div class="box-body aladagrey">
                                <?php
                                if (!empty($product)) {
                                $product_information = product_attributes($product, FALSE);
                                $infoss[] = json_decode($product_information['values']);
                                $infoss[] = $product_information['id'];
                                ?>
                                {{ Form::hidden('info_id', (!empty($infoss[1]) ? $infoss[1] : NULL), ['type' => 'hidden']) }}
                                @foreach($infoss[0] as $infos)
                                    <div class="row">
                                        <div class="border4">
                                            <div class="col-xs-6">
                                                {{ Form::label('product_code', 'Product Code', array('class' => 'product_code')) }}
                                                {{ Form::text('product_code[]', (!empty($infos->product_code) ? $infos->product_code : NULL), ['', 'class' => 'form-control', 'placeholder' => 'Product Code...']) }}
                                            </div>
                                            <div class="col-xs-6">
                                                {{ Form::label('color', 'Color', array('class' => 'color')) }}
                                                {{ Form::text('color[]', (!empty($infos->color) ? $infos->color : NULL), ['class' => 'form-control', 'placeholder' => 'Enter color...']) }}
                                            </div>
                                            <div class="col-xs-6">
                                                {{ Form::label('material', 'Material', array('class' => 'material')) }}
                                                {{ Form::text('material[]', (!empty($infos->material) ? $infos->material : NULL), ['class' => 'form-control', 'placeholder' => 'Enter material...']) }}
                                            </div>
                                            <div class="col-xs-6">
                                                {{ Form::label('product_sku', 'SKU', array('class' => 'product_sku')) }}
                                                {{ Form::text('product_sku[]', (!empty($infos->product_sku) ? $infos->product_sku : NULL), ['class' => 'form-control', 'placeholder' => 'Enter stock keeping unit...']) }}
                                            </div>
                                            <div class="col-xs-6">
                                                {{ Form::label('selling_price', 'Selling Price', array('class' => 'selling_price')) }}
                                                {{ Form::input('number', 'selling_price[]', (!empty($infos->selling_price) ? $infos->selling_price : NULL), ['', 'class' => 'form-control', 'placeholder' => 'Enter selling price...']) }}
                                            </div>
                                            <div class="col-xs-6">
                                                {{ Form::label('discount', 'Discount (In % - percentage)', array('class' => 'discount')) }}
                                                {{ Form::input('number', 'discount[]', (!empty($infos->discount) ? $infos->discount : NULL), ['class' => 'form-control', 'max' => '50', 'placeholder' => 'Enter discount...']) }}
                                                {{--{{ Form::text('discount[]', (!empty($infos->discount) ? $infos->discount : NULL), ['class' => 'form-control', 'placeholder' => 'Enter discount...']) }}--}}
                                            </div>
                                            <div class="col-xs-6">
                                                {{ Form::label('opening_system', 'Opening System', array('class' => 'opening_system')) }}
                                                <select name="opening_system[]" class="form-control">
                                                    <option value="">Select a parent</option>
                                                    {!! select_option_html($terms['data'], $parent = 1, ' ', (!empty($infos->opening_system) ? $infos->opening_system : NULL), FALSE) !!}
                                                </select>
                                            </div>
                                            <div class="col-xs-6">
                                                {{ Form::label('locking_system', 'Locking System', array('class' => 'locking_system')) }}
                                                <select name="locking_system[]" class="form-control">
                                                    <option value="">Select a parent</option>
                                                    {!! select_option_html($terms['data'], $parent = 7, ' ', (!empty($infos->locking_system) ? $infos->locking_system : NULL), FALSE) !!}
                                                </select>
                                            </div>
                                            <div class="col-xs-6">
                                                {{ Form::label('part_palla', 'Part/Palla Fiting', array('class' => 'part_palla')) }}
                                                <select name="part_palla[]" class="form-control">
                                                    <option value="">Select a parent</option>
                                                    {!! select_option_html($terms['data'], $parent = 15, ' ', (!empty($infos->part_palla) ? $infos->part_palla : NULL), FALSE ) !!}
                                                </select>
                                            </div>
                                            <div class="col-xs-6">
                                                {{ Form::label('frame_color', 'Frame Color', array('class' =>
                                                'frame_color')) }}
                                                <select name="frame_color[]" class="form-control">
                                                    <option value="">Select a parent</option>
                                                    {!! select_option_html($terms['data'], $parent = 18, ' ', (!empty($infos->frame_color) ? $infos->frame_color : NULL), FALSE ) !!}
                                                </select>
                                            </div>
                                            <div class="col-xs-6">
                                                {{ Form::label('glass_details', 'Glass Details', array('class' => 'glass_details')) }}
                                                <select name="glass_details[]" class="form-control">
                                                    <option value="">Select a parent</option>
                                                    {!! select_option_html($terms['data'], $parent = 21, ' ', (!empty($infos->glass_details) ? $infos->glass_details : NULL), FALSE ) !!}
                                                </select>
                                            </div>
                                            <div class="col-xs-6">
                                                {{ Form::label('lacquered', 'Lacquered', array('class' => 'lacquered')) }}
                                                <select name="lacquered[]" class="form-control">
                                                    <option value="">Select a parent</option>
                                                    {!! select_option_html($terms['data'], $parent = 26, ' ', (!empty($infos->lacquered) ? $infos->lacquered : NULL), FALSE ) !!}
                                                </select>
                                            </div>
                                            <div class="col-xs-6">
                                                {{ Form::label('size', 'Size', array('class' => 'size')) }}
                                                <select name="size[]" class="form-control">
                                                    <option value="">Select a parent</option>
                                                    {!! select_option_html($terms['data'], $parent = 29, ' ', (!empty($infos->size) ? $infos->size : NULL), FALSE ) !!}
                                                </select>
                                            </div>

                                            <div class="clearfix"></div>
                                            <div class="col-xs-6">
                                                {{ Form::label('variation_images', 'Variation Images', array('class' => 'variation_images')) }}

                                                @if(!empty($infos->variation_images))
                                                    <?php $im = image_ids($infos->variation_images, TRUE, TRUE); ?>
                                                    {{ Form::text('variation_images[]', (!empty($infos->variation_images) ? $infos->variation_images : NULL), ['type' => 'text', 'id' => 'image_ids_on_variation', '', 'class' => 'form-control', 'placeholder' => 'Enter image IDs...']) }}
                                                @else
                                                    {{ Form::text('variation_images[]', NULL, ['type' => 'text', 'id' => 'image_ids_on_variation', 'required', 'class' => 'form-control', 'placeholder' => 'Enter image IDs...']) }}
                                                @endif
                                                <small id="show_image_names_on_variation"></small>
                                                <br/>
                                                <div style="background: lightgoldenrodyellow;">
                                                    <div style="padding: 5px;">{{ Form::label('recent_images', 'Variation Uploads', array('class' => 'recent_images')) }}</div>
                                                    @if(!empty($infos->variation_images))
                                                        <?php if(is_array(images_by_ids($infos->variation_images))) { ?>
                                                        @foreach(images_by_ids($infos->variation_images) as $img)
                                                            {!! $img !!}
                                                        @endforeach
                                                        <?php } else { ?>
                                                        {!! images_by_ids($infos->variation_images) !!}
                                                        <?php } ?>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>
                                            <div class="form-group"
                                                 style="background: aliceblue; overflow: hidden; margin: 15px;">
                                                <div class="col-xs-12">{{ Form::label('recent_images', 'Recent Uploads', array('class' => 'recent_images')) }}</div>
                                                <div class="col-xs-12">
                                                    <div class="row">
                                                        @foreach($medias as $media)
                                                            <div class="col-xs-1 col-md-1">
                                                                <div href="#" class="thumbnail">
                                                                    <img src="{{ url($media->icon_size_directory) }}"
                                                                         class="img-responsive"
                                                                         style="max-height: 80px; height: 50px;"/>
                                                                    <div class="text-center">
                                                                        <span>{{ $media->id }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>

                                                </div>

                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <hr style="border-bottom: 4px solid #FFF;"/>
                                    </div>
                                @endforeach
                                <div class="row" id="czContainer1">
                                    <div id="first">
                                        <div class="recordset">
                                            <div class="col-xs-6">
                                                {{ Form::label('product_code', 'Product Code', array('class' => 'product_code')) }}
                                                {{ Form::text('product_code[]', NULL, ['', 'class' => 'form-control', 'placeholder' => 'Product Code...']) }}
                                            </div>
                                            <div class="col-xs-6">
                                                {{ Form::label('color', 'Color', array('class' => 'color')) }}
                                                {{ Form::text('color[]', NULL, ['class' => 'form-control', 'placeholder' => 'Enter color...']) }}
                                            </div>
                                            <div class="clearfix"></div>
                                            <div class="col-xs-6">
                                                {{ Form::label('material', 'Material', array('class' => 'material')) }}
                                                {{ Form::text('material[]', NULL, ['class' => 'form-control', 'placeholder' => 'Enter material...']) }}
                                            </div>
                                            <div class="col-xs-6">
                                                {{ Form::label('product_sku', 'SKU', array('class' => 'product_sku')) }}
                                                {{ Form::text('product_sku[]', NULL, ['class' => 'form-control', 'placeholder' => 'Enter stock keeping unit...']) }}
                                            </div>
                                            <div class="clearfix"></div>
                                            <div class="col-xs-6">
                                                {{ Form::label('selling_price', 'Selling Price', array('class' => 'selling_price')) }}
                                                {{ Form::input('number', 'selling_price[]', NULL, ['', 'class' => 'form-control', 'placeholder' => 'Enter selling price...']) }}
                                            </div>
                                            <div class="col-xs-6">
                                                {{ Form::label('discount', 'Discount (In % - percentage)', array('class' => 'discount')) }}
                                                {{ Form::input('number', 'discount[]', NULL, ['class' => 'form-control', 'max' => '50', 'placeholder' => 'Enter discount...']) }}
                                            </div>
                                            <div class="col-xs-6">
                                                {{ Form::label('opening_system', 'Opening System', array('class' => 'opening_system')) }}
                                                <select name="opening_system[]" class="form-control">
                                                    <option value="">Select a parent</option>
                                                    {!! select_option_html($terms['data'], $parent = 1, ' ', NULL, FALSE )  !!}
                                                </select>
                                            </div>
                                            <div class="col-xs-6">
                                                {{ Form::label('locking_system', 'Locking System', array('class' => 'locking_system')) }}
                                                <select name="locking_system[]" class="form-control">
                                                    <option value="">Select a parent</option>
                                                    {!! select_option_html($terms['data'], $parent = 7, ' ', NULL, FALSE )  !!}
                                                </select>
                                            </div>
                                            <div class="clearfix"></div>
                                            <div class="col-xs-6">
                                                {{ Form::label('part_palla', 'Part/Palla Fiting', array('class' => 'part_palla')) }}

                                                <select name="part_palla[]" class="form-control">
                                                    <option value="">Select a parent</option>
                                                    {!! select_option_html($terms['data'], $parent = 15, ' ', NULL, FALSE )  !!}
                                                </select>
                                            </div>
                                            <div class="col-xs-6">
                                                {{ Form::label('frame_color', 'Frame Color', array('class' => 'frame_color')) }}
                                                <select name="frame_color[]" class="form-control">
                                                    <option value="">Select a parent</option>
                                                    {!! select_option_html($terms['data'], $parent = 18, ' ', NULL, FALSE )  !!}
                                                </select>
                                            </div>
                                            <div class="clearfix"></div>
                                            <div class="col-xs-6">
                                                {{ Form::label('glass_details', 'Glass Details', array('class' => 'glass_details')) }}
                                                <select name="glass_details[]" class="form-control">
                                                    <option value="">Select a parent</option>
                                                    {!! select_option_html($terms['data'], $parent = 21, ' ', NULL, FALSE )  !!}
                                                </select>
                                            </div>
                                            <div class="col-xs-6">
                                                {{ Form::label('lacquered', 'Lacquered', array('class' => 'lacquered')) }}
                                                <select name="lacquered[]" class="form-control">
                                                    <option value="">Select a parent</option>
                                                    {!! select_option_html($terms['data'], $parent = 26, ' ', NULL, FALSE )  !!}
                                                </select>
                                            </div>
                                            <div class="clearfix"></div>
                                            <div class="col-xs-6">
                                                {{ Form::label('size', 'Size', array('class' => 'size')) }}
                                                <select name="size[]" class="form-control">
                                                    <option value="">Select a parent</option>
                                                    {!! select_option_html($terms['data'], $parent = 29, ' ', (!empty($infos->size) ? $infos->size : NULL), FALSE ) !!}
                                                </select>
                                            </div>
                                            <div class="col-xs-12">
                                                {{ Form::label('variation_images', 'Variation Images', array('class' => 'variation_images')) }}

                                                @if(!empty($product->product_attributes))
                                                    <?php
                                                    $im = image_ids($product, TRUE, TRUE);
                                                    //owndebugger($im);
                                                    ?>
                                                    {{ Form::text('variation_images[]', NULL, ['type' => 'text', 'id' => 'image_ids_on_variation', '', 'class' => 'form-control', 'placeholder' => 'Enter image IDs...']) }}
                                                @else
                                                    {{ Form::text('variation_images[]', NULL, ['type' => 'text', 'id' => 'image_ids_on_variation', 'required', 'class' => 'form-control', 'placeholder' => 'Enter image IDs...']) }}
                                                @endif
                                                <small id="show_image_names_on_variation"></small>
                                                <br/>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>
                                </div>
                                <?php } else { ?>
                                <div class="row" id="czContainer">
                                    <div id="first">
                                        <div class="recordset">
                                            <div class="col-xs-6">
                                                {{ Form::label('product_code', 'Product Code', array('class' => 'product_code')) }}
                                                {{ Form::text('product_code[]', (!empty($infos->product_code) ? $infos->product_code : NULL), ['', 'class' => 'form-control', 'placeholder' => 'Product Code...']) }}
                                            </div>
                                            <div class="col-xs-6">
                                                {{ Form::label('color', 'Color', array('class' => 'color')) }}
                                                {{ Form::text('color[]', (!empty($infos->color) ? $infos->color : NULL), ['class' => 'form-control', 'placeholder' => 'Enter color...']) }}
                                            </div>
                                            <div class="col-xs-6">
                                                {{ Form::label('material', 'Material', array('class' => 'material')) }}
                                                {{ Form::text('material[]', (!empty($infos->material) ? $infos->material : NULL), ['class' => 'form-control', 'placeholder' => 'Enter material...']) }}
                                            </div>
                                            <div class="col-xs-6">
                                                {{ Form::label('product_sku', 'SKU', array('class' => 'product_sku')) }}
                                                {{ Form::text('product_sku[]', (!empty($infos->product_sku) ? $infos->product_sku : NULL), ['class' => 'form-control', 'placeholder' => 'Enter stock keeping unit...']) }}
                                            </div>
                                            <div class="col-xs-6">
                                                {{ Form::label('selling_price', 'Selling Price', array('class' => 'selling_price')) }}
                                                {{ Form::input('number', 'selling_price[]', (!empty($infos->selling_price) ? $infos->selling_price : NULL), ['', 'class' => 'form-control', 'placeholder' => 'Enter selling price...']) }}
                                            </div>
                                            <div class="col-xs-6">
                                                {{ Form::label('discount', 'Discount (In % - percentage)', array('class' => 'discount')) }}
                                                {{ Form::input('number', 'discount[]', (!empty($infos->discount) ? $infos->discount : NULL), ['class' => 'form-control', 'max' => '50', 'placeholder' => 'Enter discount...']) }}
                                            </div>
                                            <div class="col-xs-6">
                                                {{ Form::label('opening_system', 'Opening System', array('class' => 'opening_system')) }}
                                                <select name="opening_system[]" class="form-control">
                                                    <option value="">Select a parent</option>
                                                    {!! select_option_html($terms['data'], $parent = 1, ' ', (!empty($infos->opening_system) ? $term->opening_system : NULL), FALSE )  !!}
                                                </select>
                                            </div>
                                            <div class="col-xs-6">
                                                {{ Form::label('locking_system', 'Locking System', array('class' => 'locking_system')) }}
                                                <select name="locking_system[]" class="form-control">
                                                    <option value="">Select a parent</option>
                                                    {!! select_option_html($terms['data'], $parent = 7, ' ', (!empty($infos->locking_system) ? $term->locking_system : NULL), FALSE )  !!}
                                                </select>
                                            </div>
                                            <div class="col-xs-6">
                                                {{ Form::label('part_palla', 'Part/Palla Fiting', array('class' => 'part_palla')) }}

                                                <select name="part_palla[]" class="form-control">
                                                    <option value="">Select a parent</option>
                                                    {!! select_option_html($terms['data'], $parent = 15, ' ', (!empty($infos->part_palla) ? $term->part_palla : NULL), FALSE )  !!}
                                                </select>
                                            </div>
                                            <div class="col-xs-6">
                                                {{ Form::label('frame_color', 'Frame Color', array('class' => 'frame_color')) }}
                                                <select name="frame_color[]" class="form-control">
                                                    <option value="">Select a parent</option>
                                                    {!! select_option_html($terms['data'], $parent = 18, ' ', (!empty($infos->frame_color) ? $term->frame_color : NULL), FALSE )  !!}
                                                </select>
                                            </div>
                                            <div class="col-xs-6">
                                                {{ Form::label('glass_details', 'Glass Details', array('class' => 'glass_details')) }}
                                                <select name="glass_details[]" class="form-control">
                                                    <option value="">Select a parent</option>
                                                    {!! select_option_html($terms['data'], $parent = 21, ' ', (!empty($infos->glass_details) ? $term->glass_details : NULL), FALSE )  !!}
                                                </select>
                                            </div>
                                            <div class="col-xs-6">
                                                {{ Form::label('lacquered', 'Lacquered', array('class' => 'lacquered')) }}
                                                <select name="lacquered[]" class="form-control">
                                                    <option value="">Select a parent</option>
                                                    {!! select_option_html($terms['data'], $parent = 26, ' ', (!empty($infos->lacquered) ? $term->lacquered : NULL), FALSE )  !!}
                                                </select>
                                            </div>
                                            <div class="col-xs-6">
                                                {{ Form::label('size', 'Size', array('class' => 'size')) }}
                                                <select name="size[]" class="form-control">
                                                    <option value="">Select a parent</option>
                                                    {!! select_option_html($terms['data'], $parent = 29, ' ', (!empty($infos->size) ? $infos->size : NULL), FALSE ) !!}
                                                </select>
                                            </div>
                                            <div class="col-xs-12">
                                                {{ Form::label('variation_images', 'Variation Images', array('class' => 'variation_images')) }}

                                                @if(!empty($product->product_attributes))
                                                    <?php
                                                    $im = image_ids($product, TRUE, TRUE);
                                                    //owndebugger($im);
                                                    ?>
                                                    {{ Form::text('variation_images[]', (!empty($im) ? $im : NULL), ['type' => 'text', 'id' => 'image_ids_on_variation', '', 'class' => 'form-control', 'placeholder' => 'Enter image IDs...']) }}
                                                @else
                                                    {{ Form::text('variation_images[]', NULL, ['type' => 'text', 'id' => 'image_ids_on_variation', 'required', 'class' => 'form-control', 'placeholder' => 'Enter image IDs...']) }}
                                                @endif
                                                <small id="show_image_names_on_variation"></small>
                                                <br/>
                                            </div>
                                            <div class="col-xs-12">{{ Form::label('recent_images', 'Recent Uploads', array('class' => 'recent_images')) }}</div>
                                            <div class="col-xs-12">
                                                @foreach($medias as $media)
                                                    <div class="col-xs-6 col-md-3">
                                                        <div href="#" class="thumbnail">
                                                            <img src="{{ url($media->icon_size_directory) }}"
                                                                 class="img-responsive"
                                                                 style="max-height: 80px; min-height: 80px;"/>
                                                            <div class="caption text-center">
                                                                <p>{{ $media->id }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <div class="clearfix"></div>

                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                @endslot
            @endcomponent
        </div>
        <div class="col-md-4">
            @component('component.dropzone')
            @endcomponent
            @if(!empty($medias))
                <div class="row" id="reload_me">
                    @foreach($medias as $media)
                        <div class="col-xs-6 col-md-3">
                            <div href="#" class="thumbnail">
                                <img src="{{ url($media->icon_size_directory) }}"
                                     class="img-responsive"
                                     style="max-height: 80px; min-height: 80px;"/>
                                <div class="caption text-center">
                                    <p>
                                        <a
                                                href="javascript:void(0);"
                                                data-id="{{ $media->id }}"
                                                data-option="{{ $media->filename }}"
                                                class="btn btn-xs btn-primary"
                                                onclick="get_id(this);"
                                                role="button">
                                            Use
                                        </a>

                                        {{--<a--}}
                                        {{--href="javascript:void(0);"--}}
                                        {{--data-id="{{ $media->id }}"--}}
                                        {{--data-option="{{ $media->filename }}"--}}
                                        {{--class="btn btn-xs btn-primary"--}}
                                        {{--onclick="use_for_variation(this);"--}}
                                        {{--role="button">--}}
                                        {{--Vary--}}
                                        {{--</a>--}}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
@endsection
@push('scripts')
<script src="{{ URL::asset('public/plugins/dropzone.js') }}"></script>
<script src="{{ URL::asset('public/js/dropzone-config.js') }}"></script>
<script src="{{ URL::asset('public/js/jquery.czMore-latest.js') }}"></script>
<script type="text/javascript">
    function get_id(identifier) {
        //alert("data-id:" + jQuery(identifier).data('id') + ", data-option:" + jQuery(identifier).data('option'));


        var dataid = jQuery(identifier).data('id');
        jQuery('#image_ids').val(
            function (i, val) {
                return val + (!val ? '' : ', ') + dataid;
            });
        var option = jQuery(identifier).data('option');
        jQuery('#show_image_names').html(
            function (i, val) {
                return val + (!val ? '' : ', ') + option;
            }
        );
    }

    function use_for_variation(identifier) {
        //alert("data-id:" + jQuery(identifier).data('id') + ", data-option:" + jQuery(identifier).data('option'));


        var dataid = jQuery(identifier).data('id');
        jQuery('#image_ids_on_variation' + dataid).val(
            function (i, val) {
                return val + (!val ? '' : ', ') + dataid;
            });
        var option = jQuery(identifier).data('option');
        jQuery('#show_image_names_on_variation' + dataid).html(
            function (i, val) {
                return val + (!val ? '' : ', ') + option;
            }
        );
    }


    $("#czContainer, #czContainer1").czMore();

    /**
     *
     */
    jQuery(document).ready(function ($) {
        $.noConflict();

        $('#title').blur(function () {
            var m = $(this).val();
            var cute = m.toLowerCase().replace(/ /g, '-');

            $('#seo_url').val(cute);
        });

    });
</script>
<style type="text/css">
    .aladagrey {
        background: lightgrey;
    }

    .recordset, .czContainer {
        position: relative;
    }

    .recordset {
        border-bottom: 4px solid white;
    }
</style>
@endpush
