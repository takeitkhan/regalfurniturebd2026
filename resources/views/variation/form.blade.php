@extends('layouts.app')

@section('title', 'Variation')
@section('sub_title', 'variation add or modification form')
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
        <div class="col-md-12">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs pull-right">
                    @if (!empty($product->id))
                        <li class="{{ Request::is('edit_meta/'. $product->id) ? 'active' : '' }}">
                            <a href="{{ url('edit_meta/'. $product->id) }}">Meta Data</a>
                        </li>
                    @else
                        <li class="{{ Request::is('add_meta') ? 'active' : '' }}">
                            <a href="{{ url('add_meta') }}">Meta Data</a>
                        </li>
                    @endif

                    @if (!empty($product->id))
                        <li class="{{ Request::is('edit_variation/'. $product->id) ? 'active' : '' }}">
                            <a href="{{ url('edit_variation/'. $product->id) }}">Variation</a>
                        </li>
                    @else
                        <li class="{{ Request::is('add_variation') ? 'active' : '' }}">
                            <a href="{{ url('add_variation') }}">Variation</a>
                        </li>
                    @endif

                    @if (!empty($product->id))
                        <li class="{{ Request::is('edit_product/'. $product->id) ? 'active' : '' }}">
                            <a href="{{ url('edit_product/'. $product->id) }}">General Information</a>
                        </li>
                    @else
                        <li class="{{ Request::is('add_product') ? 'active' : '' }}">
                            <a href="{{ url('add_product') }}">General Information</a>
                        </li>
                    @endif
                    <li class="pull-left header"><i class="fa fa-th"></i> Product Information</li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_1-1">
                        <div class="row">
                            <div class="col-md-8">
                                @component('component.form')
                                    @slot('form_id')
                                        @if (!empty($user->id))
                                            variation_forms
                                        @else
                                            variation_form
                                        @endif
                                    @endslot
                                    @slot('title')
                                        @if (!empty($variation->id))
                                            Edit variation
                                        @else
                                            Add a new variation
                                        @endif

                                    @endslot

                                    @slot('route')
                                        @if (!empty($variation->id))
                                            variation/{{$variation->id}}/update
                                        @else
                                            variation_save
                                        @endif
                                    @endslot

                                    @slot('fields')
                                        <div class="form-group text-center">
                                            <p>
                                            <h4>Following product variations form for the variations of same type
                                                product with different
                                                variation. Such as - color, material, selling price and so on. You
                                                can add much as you want
                                                by clicking green add more button under product variation form.
                                            </h4>
                                            </p>
                                        </div>
                                        <?php
                                        if (!empty($product)) {
                                        $product_information = product_attributes($product, FALSE);
                                        ?>
                                        @foreach($product_information as $info)
                                            <?php
                                            $infoss = json_decode($info);
                                            $infos = $infoss[0];
                                            ?>

                                            <div class="row">
                                                <div class="col-xs-6">
                                                    {{ Form::label('product_code', 'Product Code', array('class' => 'product_code')) }}
                                                    {{ Form::text('product_code[]', (!empty($infos->product_code) ? $infos->product_code : NULL), ['', 'class' => 'form-control', 'placeholder' => 'Product Code...']) }}
                                                </div>
                                                <div class="col-xs-6">
                                                    {{ Form::label('color', 'Color', array('class' => 'color')) }}
                                                    {{ Form::text('color[]', (!empty($infos->color) ? $infos->color : NULL), ['class' => 'form-control', 'placeholder' => 'Enter color...']) }}
                                                </div>
                                                <div class="clearfix"></div>
                                                <div class="col-xs-6">
                                                    {{ Form::label('material', 'Material', array('class' => 'material')) }}
                                                    {{ Form::text('material[]', (!empty($infos->material) ? $infos->material : NULL), ['class' => 'form-control', 'placeholder' => 'Enter material...']) }}
                                                </div>
                                                <div class="col-xs-6">
                                                    {{ Form::label('product_sku', 'SKU', array('class' => 'product_sku')) }}
                                                    {{ Form::text('product_sku[]', (!empty($infos->product_sku) ? $infos->product_sku : NULL), ['class' => 'form-control', 'placeholder' => 'Enter stock keeping unit...']) }}
                                                </div>
                                                <div class="clearfix"></div>
                                                <div class="col-xs-6">
                                                    {{ Form::label('selling_price', 'Selling Price', array('class' => 'selling_price')) }}
                                                    {{ Form::text('selling_price[]', (!empty($infos->selling_price) ? $infos->selling_price : NULL), ['', 'class' => 'form-control', 'placeholder' => 'Enter selling price...']) }}
                                                </div>
                                                <div class="col-xs-6">
                                                    {{ Form::label('discount', 'Discount', array('class' => 'discount')) }}
                                                    {{ Form::text('discount[]', (!empty($infos->discount) ? $infos->discount : NULL), ['class' => 'form-control', 'placeholder' => 'Enter discount...']) }}
                                                </div>
                                                <div class="col-xs-6">
                                                    {{ Form::label('opening_system', 'Opening System', array('class' => 'opening_system')) }}
                                                    <select name="opening_system[]" class="form-control">
                                                        <option value="">Select a parent</option>
                                                        {!! select_option_html($terms['data'], $parent = 1, ' ', (!empty($infos->opening_system) ? $infos->opening_system : NULL), FALSE )  !!}
                                                    </select>
                                                </div>
                                                <div class="col-xs-6">
                                                    {{ Form::label('locking_system', 'Locking System', array('class' => 'locking_system')) }}
                                                    <select name="locking_system[]" class="form-control">
                                                        <option value="">Select a parent</option>
                                                        {!! select_option_html($terms['data'], $parent = 7, ' ', (!empty($infos->locking_system) ? $infos->locking_system : NULL), FALSE )  !!}
                                                    </select>
                                                </div>
                                                <div class="clearfix"></div>
                                                <div class="col-xs-6">
                                                    {{ Form::label('part_palla', 'Part/Palla Fiting', array('class' => 'part_palla')) }}

                                                    <select name="part_palla[]" class="form-control">
                                                        <option value="">Select a parent</option>
                                                        {!! select_option_html($terms['data'], $parent = 15, ' ', (!empty($infos->part_palla) ? $infos->part_palla : NULL), FALSE )  !!}
                                                    </select>
                                                </div>
                                                <div class="col-xs-6">
                                                    {{ Form::label('frame_color', 'Frame Color', array('class' => 'frame_color')) }}
                                                    <select name="frame_color[]" class="form-control">
                                                        <option value="">Select a parent</option>
                                                        {!! select_option_html($terms['data'], $parent = 18, ' ', (!empty($infos->frame_color) ? $infos->frame_color : NULL), FALSE )  !!}
                                                    </select>
                                                </div>
                                                <div class="clearfix"></div>
                                                <div class="col-xs-6">
                                                    {{ Form::label('glass_details', 'Glass Details', array('class' => 'glass_details')) }}
                                                    <select name="glass_details[]" class="form-control">
                                                        <option value="">Select a parent</option>
                                                        {!! select_option_html($terms['data'], $parent = 21, ' ', (!empty($infos->glass_details) ? $infos->glass_details : NULL), FALSE )  !!}
                                                    </select>
                                                </div>
                                                <div class="col-xs-6">
                                                    {{ Form::label('lacquered', 'Lacquered', array('class' => 'lacquered')) }}
                                                    <select name="lacquered[]" class="form-control">
                                                        <option value="">Select a parent</option>
                                                        {!! select_option_html($terms['data'], $parent = 26, ' ', (!empty($infos->lacquered) ? $infos->lacquered : NULL), FALSE )  !!}
                                                    </select>
                                                </div>
                                                <div class="clearfix"></div>
                                                <div class="col-xs-6">
                                                    {{ Form::label('size', 'Size', array('class' => 'size')) }}
                                                    <select name="size[]" class="form-control">
                                                        <option value="">Select a parent</option>
                                                        {!! select_option_html($terms['data'], $parent = 26, ' ', (!empty($infos->lacquered) ? $infos->lacquered : NULL), FALSE )  !!}
                                                    </select>
                                                </div>
                                                <div class="col-xs-12">
                                                    {{ Form::label('variation_images', 'Variation Images', array('class' => 'variation_images')) }}

                                                    @if(!empty($infos->variation_images))
                                                        <?php
                                                        $im = image_ids($infos->variation_images, TRUE, TRUE);
                                                        owndebugger($im);

                                                        ?>
                                                        {{ Form::text('variation_images[]', (!empty($infos->variation_images) ? $infos->variation_images : NULL), ['type' => 'text', 'id' => 'image_ids_on_variation', '', 'class' => 'form-control', 'placeholder' => 'Enter image IDs...']) }}
                                                    @else
                                                        {{ Form::text('variation_images[]', NULL, ['type' => 'text', 'id' => 'image_ids_on_variation', 'required', 'class' => 'form-control', 'placeholder' => 'Enter image IDs...']) }}
                                                    @endif
                                                    <small id="show_image_names_on_variation"></small>

                                                </div>
                                            </div>
                                        @endforeach
                                        <?php } else { ?>
                                        <div class="row">
                                            <div class="col-xs-6">
                                                {{ Form::label('product_code', 'Product Code', array('class' => 'product_code')) }}
                                                {{ Form::text('product_code[]', (!empty($infos->product_code) ? $infos->product_code : NULL), ['', 'class' => 'form-control', 'placeholder' => 'Product Code...']) }}
                                            </div>
                                            <div class="col-xs-6">
                                                {{ Form::label('color', 'Color', array('class' => 'color')) }}
                                                {{ Form::text('color[]', (!empty($infos->color) ? $infos->color : NULL), ['class' => 'form-control', 'placeholder' => 'Enter color...']) }}
                                            </div>
                                            <div class="clearfix"></div>
                                            <div class="col-xs-6">
                                                {{ Form::label('material', 'Material', array('class' => 'material')) }}
                                                {{ Form::text('material[]', (!empty($infos->material) ? $infos->material : NULL), ['class' => 'form-control', 'placeholder' => 'Enter material...']) }}
                                            </div>
                                            <div class="col-xs-6">
                                                {{ Form::label('product_sku', 'SKU', array('class' => 'product_sku')) }}
                                                {{ Form::text('product_sku[]', (!empty($infos->product_sku) ? $infos->product_sku : NULL), ['class' => 'form-control', 'placeholder' => 'Enter stock keeping unit...']) }}
                                            </div>
                                            <div class="clearfix"></div>
                                            <div class="col-xs-6">
                                                {{ Form::label('selling_price', 'Selling Price', array('class' => 'selling_price')) }}
                                                {{ Form::text('selling_price[]', (!empty($infos->selling_price) ? $infos->selling_price : NULL), ['', 'class' => 'form-control', 'placeholder' => 'Enter selling price...']) }}
                                            </div>
                                            <div class="col-xs-6">
                                                {{ Form::label('discount', 'Discount', array('class' => 'discount')) }}
                                                {{ Form::text('discount[]', (!empty($infos->discount) ? $infos->discount : NULL), ['class' => 'form-control', 'placeholder' => 'Enter discount...']) }}
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
                                            <div class="clearfix"></div>
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
                                            <div class="clearfix"></div>
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
                                            <div class="clearfix"></div>
                                            <div class="col-xs-6">
                                                {{ Form::label('size', 'Size', array('class' => 'size')) }}
                                                <select name="size[]" class="form-control">
                                                    <option value="">Select a parent</option>
                                                    {!! select_option_html($terms['data'], $parent = 26, ' ', (!empty($infos->lacquered) ? $term->lacquered : NULL), FALSE )  !!}
                                                </select>
                                            </div>
                                            <div class="col-xs-6">
                                                {{ Form::label('variation_images', 'Variation Images', array('class' => 'variation_images')) }}

                                                @if(!empty($product->product_attributes))
                                                    <?php
                                                    $im = image_ids($product, TRUE, TRUE);
                                                    owndebugger($im);

                                                    ?>
                                                    {{ Form::text('variation_images[]', (!empty($im) ? $im : NULL), ['type' => 'text', 'id' => 'image_ids_on_variation', '', 'class' => 'form-control', 'placeholder' => 'Enter image IDs...']) }}
                                                @else
                                                    {{ Form::text('variation_images[]', NULL, ['type' => 'text', 'id' => 'image_ids_on_variation', 'required', 'class' => 'form-control', 'placeholder' => 'Enter image IDs...']) }}
                                                @endif
                                                <small id="show_image_names_on_variation"></small>

                                            </div>
                                        </div>
                                        <?php } ?>
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

                                                            <a
                                                            href="javascript:void(0);"
                                                            data-id="{{ $media->id }}"
                                                            data-option="{{ $media->filename }}"
                                                            class="btn btn-xs btn-primary"
                                                            onclick="use_for_variation(this);"
                                                            role="button">
                                                            Vary
                                                            </a>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="tab_2-2">
                        <div class="row">
                            <div class="form-group text-center">
                                <p>
                                <h4>Following variation variations form for the variations of same type
                                    variation
                                    with
                                    different
                                    variation. Such as - color, material, selling price and so on. You can
                                    add
                                    much
                                    as you want
                                    by clicking green add more button under variation variation form.
                                </h4>
                                </p>


                            </div>
                        </div>
                    </div>
                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="tab_3-2">

                    </div>
                    <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
            </div>
        </div>
    </div>
@endsection
@push('scripts')

<script src="{{ asset('public/plugins/dropzone.js') }}"></script>
<script src="{{ asset('public/js/dropzone-config.js') }}"></script>
<script src="{{ asset('public/js/jquery.czMore-latest.js') }}"></script>


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
</script>
@endpush
