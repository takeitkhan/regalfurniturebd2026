<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
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
                    Edit Product Basic Information
                @else
                    Product Basic Information
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
                <div class="col-md-7 col-sm-12 col-xs-12">
                    <div class="row">
                        <div class="form-group">
                            @if(!empty($product->seo_url))
                                <a class="btn btn-xs btn-info" href="{{ url('p/' . $product->seo_url) }}"
                                   target="_blank">
                                    &nbsp;View Product
                                </a>
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('parent_id', 'Parent Product ID', array('class' => 'parent_id')) }}
                            @if (!empty($product->parent_id))
                                {{ Form::number('parent_id', (!empty($product->parent_id) ? $product->parent_id : NULL), ['class' => 'form-control', 'id' => 'parent', 'placeholder' => 'Enter parent product id...']) }}
                            @else
                                {{ Form::number('parent_id', (!empty($product->parent_id) ? $product->parent_id : NULL), ['class' => 'form-control','id'=>'parent_id', 'placeholder' => 'Enter parent product id...']) }}
                            @endif
                        </div>
                        <div class="form-group">
                            {{ Form::hidden('user_id', (!empty(\Auth::user()->id) ? \Auth::user()->id : NULL), ['type' => 'hidden']) }}
                            {{ Form::hidden('lang', (!empty($post->lang) ? $post->lang : 'en'), ['type' => 'hidden']) }}

                            {{ Form::hidden('p_request', (!empty(request()->get('p')) ? request()->get('p') : 'basic'), ['type' => 'hidden']) }}

                            {{ Form::label('title', 'Title', array('class' => 'title')) }}
                            {{ Form::text('title', (!empty($product->title) ? $product->title : NULL), ['required', 'class' => 'form-control', 'id' => 'title',  'placeholder' => 'Enter title...']) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('sub_title', 'Sub Title', array('class' => 'sub_title')) }}
                            {{ Form::text('sub_title', (!empty($product->sub_title) ? $product->sub_title : NULL), ['required', 'class' => 'form-control', 'placeholder' => 'Enter sub_title...']) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('seo_url', 'SEO URL', array('class' => 'seo_url')) }}
                            {{ Form::text('seo_url', (!empty($product->seo_url) ? $product->seo_url : NULL), ['required', 'data-type' => (!empty($product->id) ? 'update' : 'create'), 'class' => 'form-control', 'id' => 'seo_url', 'placeholder' => 'Enter seo URL...', 'readonly' => true]) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('short_description', 'Short Description', array('class' => 'short_description')) }}
                            {{ Form::textarea('short_description', (!empty($product->short_description) ? $product->short_description : NULL), ['class' => 'form-control', 'rows' => 3,  'placeholder' => 'Enter short content...']) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('description', 'Description', array('class' => 'description')) }}
                            {{ Form::textarea('description', (!empty($product->description) ? $product->description : NULL), ['required', 'class' => 'form-control', 'id' => 'wysiwyg', 'placeholder' => 'Enter details content...']) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('purchase_note', 'Purchase Note', array('class' => 'purchase_note')) }}
                            {{ Form::textarea('purchase_note', (!empty($product->purchase_note) ? $product->purchase_note : NULL), ['required', 'rows' => 2, 'class' => 'form-control', 'placeholder' => 'Enter short content...']) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('product_code', 'Product Code', array('class' => 'product_code')) }}
                            {{ Form::text('product_code', (!empty($product->product_code) ? $product->product_code : NULL), ['', 'class' => 'form-control', 'placeholder' => 'Product Code...']) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('sku', 'SKU', array('class' => 'sku')) }}
                            {{ Form::text('sku', (!empty($product->sku) ? $product->sku : NULL), ['class' => 'form-control', 'placeholder' => 'Enter stock keeping unit...']) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('qty', 'Quantity', array('class' => 'qty')) }}
                            {{ Form::number('qty', (!empty($product->qty) ? $product->qty : NULL), ['class' => 'form-control', 'placeholder' => 'Enter quantity...']) }}
                        </div>

                        @if (!empty($product))
                            <div class="form-group">
                                {{ Form::label('new_arrival_serial', 'New Arrival Serial', array('class' => 'new_arrival_serial')) }}
                                {{ Form::number('new_arrival_serial', (!empty($product->new_arrival_serial) ? $product->new_arrival_serial : 0), ['class' => 'form-control', 'placeholder' => 'Enter Serial...']) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('recommended_serial', 'Recomended Serial', array('class' => 'recommended_serial')) }}
                                {{ Form::number('recommended_serial', (!empty($product->recommended_serial) ? $product->recommended_serial : 0), ['class' => 'form-control', 'placeholder' => 'Enter Serial...']) }}
                            </div>
                        @endif


                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    {{ Form::label('local_selling_price', 'Local Selling Price', array('class' => 'local_selling_price')) }}
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            {{ Form::radio('local', 1, ((!empty($product->local) ? $product->local == 1 : 1) ? TRUE : FALSE), ['class' => 'radio']) }}
                                        </span>
                                        {{ Form::input('number', 'local_selling_price', (!empty($product->local_selling_price) ? $product->local_selling_price : '0'), ['', 'class' => 'form-control', 'placeholder' => 'Enter local selling price...']) }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    {{ Form::label('local_discount', 'Local Selling Discount with %', array('class' => 'local_discount')) }}
                                    {{ Form::input('number', 'local_discount', (!empty($product->local_discount) ? $product->local_discount : ''), ['', 'class' => 'form-control', 'placeholder' => 'Enter discount...']) }}
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    {{ Form::label('dp_price', 'Dealer Price for employees', array('class' => 'dp_price')) }}
                                    {{ Form::input('number', 'dp_price', (!empty($product->dp_price) ? $product->dp_price : ''), ['', 'class' => 'form-control', 'placeholder' => 'Enter DP price...']) }}
                                </div>
                                <div class="col-md-6">
                                    {{ Form::label('pre_booking_discount', 'Pre Booking %', array('class' => 'pre_booking_discount')) }}
                                    {{ Form::input('number', 'pre_booking_discount', (!empty($product->pre_booking_discount) ? $product->pre_booking_discount : ''), ['', 'class' => 'form-control', 'placeholder' => '0']) }}
                                </div>
                            </div>
                        </div>
                        <div class="form-group">

                            <div class="row">
                                <div class="col-md-6">
                                    {{ Form::label('intl_selling_price', 'International Selling Price', array('class' => 'intl_selling_price')) }}
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            {{ Form::radio('intl', 1, ((!empty($product->intl) ? $product->intl == 1 : 1) ? TRUE : FALSE), ['class' => 'radio']) }}
                                        </span>
                                        {{ Form::input('number', 'intl_selling_price', (!empty($product->intl_selling_price) ? $product->intl_selling_price : '0'), ['', 'class' => 'form-control', 'placeholder' => 'Enter international selling price...']) }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    {{ Form::label('local_discount', 'International Selling Discount with %', array('class' => 'local_discount')) }}
                                    {{ Form::input('number', 'intl_discount', (!empty($product->intl_discount) ? $product->intl_discount : '0'), ['', 'class' => 'form-control', 'placeholder' => 'Enter discount...']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-5 col-sm-12 col-xs-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            {{ Form::label('is_sticky', 'Is it exclusive?', array('class' => 'is_sticky')) }}

                            @if(!empty($product) && $product->is_sticky == '1')
                                <div class="radio">
                                    <label>
                                        {!! Form::radio('is_sticky', 1, true, array('id'=>'is_sticky', 'class'=>'radio')) !!}
                                        Yes. This product will be marked as selected top product
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        {!! Form::radio('is_sticky', 0, false, array('id'=>'is_sticky', 'class'=>'radio')) !!}
                                        No. This product will no be marked as selected top product
                                    </label>
                                </div>
                            @else
                                <div class="radio">
                                    <label>
                                        {!! Form::radio('is_sticky', 1, false, array('id'=>'is_sticky', 'class'=>'radio')) !!}
                                        Yes. This product will be marked as selected top product
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        {!! Form::radio('is_sticky', 0, true, array('id'=>'is_sticky', 'class'=>'radio')) !!}
                                        No. This product will no be marked as selected top product
                                    </label>
                                </div>
                            @endif

                            {{ Form::label('is_active', 'Will it be Published?', array('class' => 'is_active')) }}
                            @if(!empty($product) && $product->is_active == '1')
                                <div class="radio">
                                    <label>
                                        {!! Form::radio('is_active', 1, true, array('id'=>'is_active', 'class'=>'radio')) !!}
                                        Yes. This product will publish
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        {!! Form::radio('is_active', 0, false, array('id'=>'is_active', 'class'=>'radio')) !!}
                                        No. This product will save as draft
                                    </label>
                                </div>
                            @else
                                <div class="radio">
                                    <label>
                                        {!! Form::radio('is_active', 1, false, array('id'=>'is_active', 'class'=>'radio')) !!}
                                        Yes. This product will publish
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        {!! Form::radio('is_active', 0, true, array('id'=>'is_active', 'class'=>'radio')) !!}
                                        No. This product will save as draft
                                    </label>
                                </div>
                            @endif
                            <hr/>
                        </div>
                    </div>
                    <div class="box box-primary">
                        <div class="box-header with-border">

                            <?php

                            //                            dump($product->express_delivery);
                            //                            dump($product->new_arrival);
                            //                            dump($product->enable_comment);
                            //                            dump($product->enable_review);
                            //                            dump($product->best_selling);
                            //                            dump($product->recommended);
                            //                            dump($product->multiple_pricing);
                            //                            dump($product->emi_available);

                            ?>

                            {{ Form::label('availability_tags', 'Availability Tags', array('class' => 'availability_type')) }}

                            <div class="checkbox">
                                <label>
                                    {{ Form::checkbox(
                                        'enable_variation',
                                        !empty($product->enable_variation) && $product->enable_variation == 'on' ? 'on' : 'off',
                                        !empty($product->enable_variation) && $product->enable_variation == 'on' ? 'checked="checked"' : '',
                                        ['class' => 'checkbox']
                                    ) }}
                                    Enable variation
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    {{ Form::checkbox(
                                        'express_delivery',
                                        !empty($product->express_delivery) && $product->express_delivery == 'on' ? 'on' : 'off',
                                        !empty($product->express_delivery) && $product->express_delivery == 'on' ? 'checked="checked"' : '',
                                        ['class' => 'checkbox']
                                    ) }}
                                    Express Delivery
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    {{ Form::checkbox(
                                        'new_arrival',
                                        !empty($product->new_arrival) && $product->new_arrival == 'on' ? 'on' : 'off',
                                        !empty($product->new_arrival) && $product->new_arrival == 'on' ? 'checked="checked"' : '',
                                        ['class' => 'checkbox']
                                    ) }}
                                    New Arrival
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    {{ Form::checkbox(
                                        'enable_comment',
                                        !empty($product->enable_comment) && $product->enable_comment == 'on' ? 'on' : 'off',
                                        !empty($product->enable_comment) && $product->enable_comment == 'on' ? 'checked="checked"' : '',
                                        ['class' => 'checkbox']
                                    ) }}
                                    Enable Comment
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    {{ Form::checkbox(
                                        'enable_review',
                                        !empty($product->enable_review) && $product->enable_review == 'on' ? 'on' : 'off',
                                        !empty($product->enable_review) && $product->enable_review == 'on' ? 'checked="checked"' : '',
                                        ['class' => 'checkbox']
                                    ) }}
                                    Enable Review
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    {{ Form::checkbox(
                                        'best_selling',
                                        !empty($product->best_selling) && $product->best_selling == 'on' ? 'on' : 'off',
                                        !empty($product->best_selling) && $product->best_selling == 'on' ? 'checked="checked"' : '',
                                        ['class' => 'checkbox']
                                    ) }}
                                    Best Selling
                                </label>
                            </div>
                            {{ Form::hidden('flash_sale', 'on') }}
                            {{--<div class="checkbox">--}}
                            {{--<label>--}}
                            {{--{{ Form::checkbox(--}}
                            {{--'flash_sale',--}}
                            {{--!empty($product->flash_sale) && $product->flash_sale == 'on' ? 'on' : 'off',--}}
                            {{--!empty($product->flash_sale) && $product->flash_sale == 'on' ? 'checked="checked"' : '',--}}
                            {{--['class' => 'checkbox', 'id' => 'flash_sale']--}}
                            {{--) }}--}}
                            {{--Flash Sale--}}
                            {{--</label>--}}
                            {{--</div>--}}
                            {{--@if($product->flash_sale == 'on')--}}
                            {{--<div class="form-group time_box">--}}
                            {{--{{ Form::label('flash_time', 'Flash Time', array('class' => 'flash_time')) }}--}}
                            {{--{{ Form::text('flash_time', (!empty($product->flash_time) ? $product->flash_time : NULL), ['class' => 'form-control', 'id' => 'datewithtime', 'placeholder' => 'Product flash time...']) }}--}}
                            {{--</div>--}}
                            {{--@endif--}}
                            <div class="checkbox">
                                <label>
                                    {{ Form::checkbox(
                                        'recommended',
                                        !empty($product->recommended) && $product->recommended == 'on' ? 'on' : 'off',
                                        !empty($product->recommended) && $product->recommended == 'on' ? 'checked="checked"' : '',
                                        ['class' => 'checkbox']
                                    ) }}
                                    Recommended
                                </label>
                            </div>

                            <div class="checkbox">
                                <label>
                                    {{ Form::checkbox(
                                        'multiple_pricing',
                                        !empty($product->multiple_pricing) && $product->multiple_pricing == 'on' ? 'on' : 'off',
                                        !empty($product->multiple_pricing) && $product->multiple_pricing == 'on' ? 'checked="checked"' : '',
                                        ['class' => 'checkbox']
                                    ) }}
                                    Multiple pricing availability
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    {{ Form::checkbox(
                                        'emi_available',
                                        !empty($product->emi_available) && $product->emi_available == 'on' ? 'on' : 'off',
                                        !empty($product->emi_available) && $product->emi_available == 'on' ? 'checked="checked"' : '',
                                        ['class' => 'checkbox']
                                    ) }}
                                    EMI (equal monthly installment) availability
                                </label>
                            </div>

                            <div class="checkbox">
                                <label>
                                    {{ Form::checkbox(
                                        'disable_buy',
                                        !empty($product->disable_buy) && $product->disable_buy == 'on' ? 'on' : 'off',
                                        !empty($product->disable_buy) && $product->disable_buy == 'on' ? 'checked="checked"' : '',
                                        ['class' => 'checkbox']
                                    ) }}
                                    Disable buy
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    {{ Form::checkbox(
                                        'order_by_phone',
                                        !empty($product->order_by_phone) && $product->order_by_phone == 'on' ? 'on' : 'off',
                                        !empty($product->order_by_phone) && $product->order_by_phone == 'on' ? 'checked="checked"' : '',
                                        ['class' => 'checkbox']
                                    ) }}
                                    Enable order by phone
                                </label>
                            </div>


                            <div class="checkbox">
                                <label>
                                    {{ Form::checkbox(
                                        'enable_timespan',1,
                                        !empty($product->enable_timespan) && $product->enable_timespan == 1 ? 'checked="checked"' : '',
                                        ['class' => 'checkbox']
                                    ) }}
                                    Enable timespan
                                </label>
                            </div>

                            <div class="checkbox">
                                <label>
                                    {{ Form::checkbox(
                                        'pre_booking',1,
                                        !empty($product->pre_booking) && $product->pre_booking == 1 ? 'checked="checked"' : '',
                                        ['class' => 'checkbox']
                                    ) }}
                                    Pre Booking
                                </label>
                            </div>

                            <div class="checkbox">
                                <label>
                                    {{ Form::checkbox(
                                        'cash_on_delivery',1,
                                        !empty($product->cash_on_delivery) && $product->cash_on_delivery == 1 ? 'checked="checked"' : '',
                                        ['class' => 'checkbox']
                                    ) }}
                                    Cash on delivery
                                </label>
                            </div>


                        </div>
                    </div>

                    <div class="box box-primary">
                        <div class="box-header with-border">

                            <div class="form-group">
                                {{ Form::label('seo_h1', 'H1 for your product page', array('class' => 'seo_h1')) }}
                                {{ Form::text('seo_h1', (!empty($product->seo_h1) ? $product->seo_h1 : NULL), ['class' => 'form-control', 'placeholder' => 'Product page H1...']) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('seo_h2', 'H2 for your product page', array('class' => 'seo_h2')) }}
                                {{ Form::text('seo_h2', (!empty($product->seo_h2) ? $product->seo_h2 : NULL), ['class' => 'form-control', 'placeholder' => 'Product page H2...']) }}
                            </div>


                            <div class="form-group">
                                {{ Form::label('seo_title', 'SEO Title', array('class' => 'seo_title')) }}
                                {{ Form::text('seo_title', (!empty($product->seo_title) ? $product->seo_title : NULL), ['class' => 'form-control', 'placeholder' => 'Product SEO Title...']) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('seo_description', 'SEO Description', array('class' => 'seo_description')) }}
                                {{ Form::textarea('seo_description', (!empty($product->seo_description) ? $product->seo_description : NULL), ['rows' => 2, 'class' => 'form-control', 'placeholder' => 'Enter SEO description...']) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('seo_keywords', 'SEO Keywords', array('class' => 'seo_keywords')) }}
                                {{ Form::textarea('seo_keywords', (!empty($product->seo_keywords) ? $product->seo_keywords : NULL), ['rows' => 2, 'class' => 'form-control', 'placeholder' => 'Enter SEO keywords...']) }}
                            </div>


                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-6">
                            {{ Form::label('stock_status', 'Stock Status', array('class' => 'stock_status')) }}
                            <div class="radio">
                                <label>
                                    {{ Form::radio('stock_status', 1, ((!empty($product->stock_status) ? $product->stock_status == 1 : 1) ? TRUE : FALSE), ['class' => 'radio']) }}
                                    In Stock
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    {{ Form::radio('stock_status', 0, ((!empty($product->stock_status) ? $product->stock_status == 0 : 0) ? TRUE : FALSE), ['class' => 'radio']) }}
                                    Out of Stock
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    {{ Form::radio('stock_status', 2, ((!empty($product->stock_status) ? $product->stock_status == 2 : 0) ? TRUE : FALSE), ['class' => 'radio']) }}
                                    Sold Out
                                </label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            {{ Form::label('product_type', 'Product Type', array('class' => 'product_type')) }}
                            <div class="radio">
                                <label>
                                    {{ Form::radio('product_type', 1, ((!empty($product->product_type) ? $product->product_type == 1 : 1) ? TRUE : FALSE), ['class' => 'radio']) }}
                                    Virtual
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    {{ Form::radio('product_type', 0, ((!empty($product->product_type) ? $product->product_type == 0 : 0) ? TRUE : FALSE), ['class' => 'radio']) }}
                                    Downloadable
                                </label>
                            </div>
                        </div>
                    </div>

                </div>
            @endslot
        @endcomponent
    </div>
</div>


