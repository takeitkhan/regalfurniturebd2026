<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="bg-navy color-palette">
            Categories and related products connection for {{ $product->title }}
        </div>
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
                    Edit Product Information
                @endif
            @endslot
            
            @slot('route')
                @if (!empty($product->id))
                    other_information/{{$product->id}}/update
                @endif
            @endslot
            @slot('fields')
                {{ Form::hidden('p_request', (!empty(request()->get('p')) ? request()->get('p') : 'basic'), ['type' => 'hidden']) }}
                <div class="form-group">
                    {{ Form::label('lang', 'Language', array('class' => 'lang')) }}
                    <div class="radio">
                        <label>
                            {{ Form::radio('lang', 1, ((!empty($product->lang) ? $product->lang == 1 : 1) ? TRUE : TRUE), ['class' => 'radio']) }}
                            English
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            {{ Form::radio('lang', 0, ((!empty($product->lang) ? $product->lang == 0 : 0) ? FALSE : FALSE), ['class' => 'radio']) }}
                            Bengali
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    {{ Form::label('delivery_area', 'Delivery Area', array('class' => 'delivery_area')) }}
                    {{ Form::text('delivery_area', (!empty($product->delivery_area) ? $product->delivery_area : NULL), ['class' => 'form-control', 'placeholder' => 'Enter delivery_area...']) }}
                </div>
                <div class="form-group">
                    {{ Form::label('delivery_charge', 'Delivery Charge', array('class' => 'delivery_charge')) }}
                    {{ Form::text('delivery_charge', (!empty($product->delivery_charge) ? $product->delivery_charge : NULL), ['class' => 'form-control', 'placeholder' => 'Enter delivery charge...']) }}
                </div>
                <div class="form-group">
                    {{ Form::label('delivery_time', 'Delivery Time', array('class' => 'delivery_time')) }}
                    {{ Form::text('delivery_time', (!empty($product->delivery_time) ? $product->delivery_time : NULL), ['class' => 'form-control', 'placeholder' => 'Enter delivery time...']) }}
                </div>
                <div class="form-group">
                    {{ Form::label('pro_warranty', 'warranty', array('class' => 'pro_warranty')) }}
                    {{ Form::text('pro_warranty', (!empty($product->pro_warranty) ? $product->pro_warranty : NULL), ['class' => 'form-control', 'placeholder' => 'Enter warranty...']) }}
                </div>
                <div class="form-group">
                    {{ Form::label('offer_details', 'Offer Details', array('class' => 'offer_details')) }}
                    {{ Form::text('offer_details', (!empty($product->offer_details) ? $product->offer_details : NULL), ['class' => 'form-control', 'placeholder' => 'Enter offer details...']) }}
                </div>
                <div class="form-group">
                    {{ Form::label('offer_start_date', 'Offer Start Date', array('class' => 'offer_start_date')) }}
                    {{ Form::text('offer_start_date', (!empty($product->offer_start_date) ? $product->offer_start_date : NULL), ['id' => 'date','class' => 'form-control', 'placeholder' => 'Enter offer start date...']) }}
                </div>
                <div class="form-group">
                    {{ Form::label('offer_end_date', 'Offer End Date', array('class' => 'offer_end_date')) }}
                    {{ Form::text('offer_end_date', (!empty($product->offer_end_date) ? $product->offer_end_date : NULL), ['id' => 'date_again','class' => 'form-control', 'placeholder' => 'Enter offer end date...']) }}
                </div>

                <div class="form-group">
                    {{ Form::label('careInfo', 'Care Information', array('class' => 'description')) }}
                    {{ Form::textarea('careInfo', $product->careInfo??'', ['class' => 'form-control', 'id' => 'wysiwyg', 'placeholder' => 'Enter details content...']) }}
                </div>

                <div class="form-group">
                    {{ Form::label('tags', 'Tags', array('class' => 'tags')) }}
                    {{ Form::textarea('tags', (!empty($product->tags) ? $product->tags : NULL), ['class' => 'form-control', 'id' => 'wysiwyg1', 'placeholder' => 'Enter tags...']) }}
                </div>

            @endslot
        @endcomponent
    </div>
</div>

