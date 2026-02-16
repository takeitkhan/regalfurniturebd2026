<div class="box xbox-success">
    <div class="box-body">
        <strong>To create variation options, use blue "Add" button.</strong>
        <br/>
        <div class="input-group input-group-sm">
            <select class="form-control select_group">
                <option value="custom">Custom Product Attribute</option>
                @foreach($attrGroup as $g)
                    <option data-items="{{json_encode($g->items)}}" value="{{$g->id}}">{{$g->group_name}}</option>
                @endforeach
            </select>
            <span class="input-group-btn">
                <button type="button" class="btn btn-info add_group">Add</button>
            </span>
        </div>
    </div>
</div>

<form action="{{route('product.attribute.store')}}" method="post">
    <div class="box xbox-success">
        <div class="box-header with-border">
            <h3 class="box-title">Create Variation Options</h3>
        </div>
        <div class="box-body">
            @csrf
            <input type="hidden" name="product_id" value="{{$prouduct_id}}">
            <div class="field_append sortable_attribute">
                @if($getAttr)
                    <input type="hidden" name="product_attr_id" value="{{$getAttr['id']}}">
                    @foreach($getAttr['data'] as $key => $data)
                        @php
                            $num = 's'.$key;
                            $attrValue = json_decode($data->attr_value);
                            $attrImages = json_decode($data->attr_images);
                            $disable = $data->attr_type == 'pre-defined' ? 'readonly' : null;
                        @endphp
                        <div class="row" style="margin-bottom: 5px;" data-row="{{$num}}">
                            <input class="form-control" type="hidden" name="attr[{{$num}}][attr_type]"
                                   value="{{$data->attr_type}}">

                            <div class="col-md-2">
                                <label for="default">Will it be default?</label>
                                <div class="">
                                    <input type="radio" name="fixed_variation"
                                           {{ isset($data->fixed_variation) && $data->fixed_variation == $data->attr_name  ? 'checked' : null }}
                                           value="{{$data->attr_name}}"/> Make it default
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label for="default">Variation Show as -</label>
                                <div>
                                    <input id="variation_show_as_decision" type="radio"
                                           name="attr[{{$num}}][attr_textarea_decision]"
                                           {{ isset($data->attr_show_as_decision) && $data->attr_show_as_decision == 'Text'  ? 'checked' : null }}
                                           value="Text"/> Text
                                    &nbsp;&nbsp;&nbsp;
                                    <input id="variation_show_as_decision" type="radio"
                                           name="attr[{{$num}}][attr_textarea_decision]"
                                           {{ isset($data->attr_show_as_decision) && $data->attr_show_as_decision == 'Image'  ? 'checked' : null }}
                                           value="Image"/> Image
                                </div>
                            </div>
                            <div class="col-md-7" style="background: #f1f1f1;">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="default">Variation Label</label>
                                        <input {{$disable}} required="" type="text" name="attr[{{$num}}][attr_name]"
                                               value="{{$data->attr_name}}" class="form-control">
                                    </div>
                                    <div class="col-md-8">
                                        <label for="default">Variation Values (<small>Enter comma separated data in Text or Image Textarea</small>)</label>
                                        <div class="form-group">

                                            @if($data->attr_type == 'pre-defined')
                                                <input type="hidden" name="attr[{{$num}}][predefine_id]"
                                                       value="{{$data->attr_group_id}}">
                                                @php
                                                    $getThisAttrItem = \DB::table('product_attribute_group_items')
                                                                        ->where('group_id', $data->attr_group_id)->get();

                                                @endphp
                                                <select name="attr[{{$num}}][attr_value][]" required class="form-control asd"
                                                        multiple=""
                                                        data-placeholder="Select a value" style="width: 100%" ; tabindex="-1"
                                                        aria-hidden="true">
{{--                                                    @foreach($attrValue as $v)--}}
                                                    @foreach($getThisAttrItem as $v)
                                                        @php
                                                            //$name = explode('|', $v);
                                                        $value = $v->item_name.'|'.$v->id;
                                                        @endphp
{{--                                                        <option value="{{$v}}" selected>{{$name[0]}}</option>--}}
                                                        <option value="{{$value}}"
                                                                {{in_array($value, $attrValue) ? 'selected' : false}}
                                                        >{{$v->item_name}}</option>
                                                    @endforeach
                                                </select>

                                                <br/>
                                                Or
                                                <br/>
                                                <label>Show as image</label>
                                                <textarea class="form-control" placeholder=""
                                                          name="attr[{{$num}}][attr_images]">{{implode('|', $attrImages)}}</textarea>
                                                <small>Enter image IDs by | separating values. Ex: 3583|3554</small>

                                            @else
                                            <label>Show as text</label>
                                            <textarea class="form-control" placeholder=""
                                                      name="attr[{{$num}}][attr_value]">{{implode('|', $attrValue)}}</textarea>
                                            <small>Enter attributes by | separating values. Ex: item1|item2</small>
                                            <br/>
                                            Or
                                            <br/>
                                            <label>Show as image</label>

                                            <textarea class="form-control" placeholder=""
                                                      name="attr[{{$num}}][attr_images]">{{implode('|', $attrImages)}}</textarea>
                                            <small>Enter image IDs by | separating values. Ex: 3583|3554</small>
                                          @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <label>&nbsp;</label>
                                <button type="button" class="remove_row btn btn-white btn-sm text-danger"
                                        data-id="{{$num}}">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

        </div>
        <div class="box-footer">
            <button type="submit" class="btn btn-success">Submit</button>
        </div>
    </div>
</form>

@section('cusjs')
@parent
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" ></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script>
        $( ".sortable_attribute" ).sortable({
        });
    </script>
@endsection
