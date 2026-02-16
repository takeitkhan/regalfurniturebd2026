<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="bg-navy color-palette">
            Product Attributes for {{ $product->title }}
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
                    Attribute Based Product Information
                @endif
            @endslot
            
            @slot('route')
                @if (!empty($product->id))
                    attribute_based_information/{{$product->id}}/update
                @endif
            @endslot
            @slot('fields')
                <div style="margin-left: -11px; margin-right: -11px;">
                    @php
                        $attgroup = App\Models\ProductCategories::where('main_pid', $product->id)->where('is_attgroup_active', 1)->get()->first();
                    @endphp

                    @if(!empty($attgroup->term_attgroup))

                        @php
                            $exists = App\Models\ProductAttributesData::where('main_pid', $product->id)->get()->count();
                        @endphp

                        <div class="inside acf-fields -left">
                            @if (!empty($product->id) && $exists != 0)
                                <input type="hidden" value="Yes" name="on_update"/>
                                {{ Form::hidden('p_request', (!empty(request()->get('p')) ? request()->get('p') : 'basic'), ['type' => 'hidden']) }}
                                @php
                                    $attributes = App\Models\Attribute::where('attgroup_id', $attgroup->term_attgroup)->orderBy('position', 'asc')->get();

                                    foreach ($attributes as $field) {

                                        //dump($field);
                                        $inserted_attribute = App\Models\ProductAttributesData::where('main_pid', $product->id)
                                        ->where('attgroup_id', $field->attgroup_id)
                                        ->where('key', $field->field_name)
                                        ->orderBy('id', 'asc')->get()->first();
                                        //dump($inserted_attribute);
                                        gen_field($field->field_type,
                                            [
                                                'data_save_id' => isset($inserted_attribute->id) ? $inserted_attribute->id : null,
                                                'user_id' => (!empty(\Auth::user()->id) ? \Auth::user()->id : NULL),
                                                'field_id' => !empty($field->id) ? $field->id : null,
                                                'main_pid' => $product->id,
                                                'attgroup_id' => $field->attgroup_id,
                                                'field_label' => $field->field_label,
                                                'field_name' => $field->field_name,
                                                'css_class' => $field->css_class,
                                                'css_id' => $field->css_id,
                                                'value' => !empty($inserted_attribute->value) ? $inserted_attribute->value : null,
                                                'default_value' => $field->default_value,
                                                'placeholder' => $field->placeholder,
                                                'is_required' => $field->is_required,
                                                'minimum' => $field->minimum,
                                                'maximum' => $field->maximum,
                                                'instructions' => $field->instructions
                                            ]);


                                        //dump($field);
    //                                    $att_info = App\Models\Attribute::where('id', $field->attribute_id)->get()->first();
    //                                    if (!empty($field->key)) {
    //
    //                                        if (!empty($field->id) && !empty($field->value)) {
    //                                            $exploded = explode(',', $field->value);
    //                                            if (is_array($exploded)) {
    //                                                $value = $exploded;
    //                                            } else {
    //                                                $value = $exploded[0];
    //                                            }
    //                                        } else {
    //                                            $value = $att_info->default_value;
    //                                        }
    //
    //                                        gen_field($att_info->field_type,
    //                                            [
    //                                                'data_save_id' => !empty($field->id) ? $field->id : null,
    //                                                'user_id' => (!empty(\Auth::user()->id) ? \Auth::user()->id : NULL),
    //                                                'field_id' => $field->attribute_id,
    //                                                'main_pid' => $field->main_pid,
    //                                                'attgroup_id' => $field->attgroup_id,
    //                                                'field_label' => $att_info->field_label,
    //                                                'field_name' => $field->key,
    //                                                'css_class' => $att_info->css_class,
    //                                                'css_id' => $att_info->css_id,
    //                                                'value' => $value,
    //                                                'default_value' => $att_info->default_value,
    //                                                'placeholder' => $att_info->placeholder,
    //                                                'is_required' => $att_info->is_required,
    //                                                'minimum' => $att_info->minimum,
    //                                                'maximum' => $att_info->maximum,
    //                                                'instructions' => $att_info->instructions
    //                                            ]);
    //                                    }
                                    }
                                @endphp
                            @else

                                @php
                                    $attributes = App\Models\Attribute::where('attgroup_id', $attgroup->term_attgroup)->orderBy('position', 'asc')->get();
                                    foreach ($attributes as $field) {

                                        gen_field($field->field_type,
                                            [
                                                'data_save_id' => NULL,
                                                'user_id' => (!empty(\Auth::user()->id) ? \Auth::user()->id : NULL),
                                                'field_id' => $field->id,
                                                'main_pid' => $product->id,
                                                'attgroup_id' => $field->attgroup_id,
                                                'field_label' => $field->field_label,
                                                'field_name' => $field->field_name,
                                                'css_class' => $field->css_class,
                                                'css_id' => $field->css_id,
                                                'value' => $field->default_value,
                                                'default_value' => $field->default_value,
                                                'placeholder' => $field->placeholder,
                                                'is_required' => $field->is_required,
                                                'minimum' => $field->minimum,
                                                'maximum' => $field->maximum,
                                                'instructions' => $field->instructions
                                            ]);
                                    }
                                @endphp
                            @endif
                        </div>

                    @endif
                </div>
            @endslot
        @endcomponent
    </div>
</div>

