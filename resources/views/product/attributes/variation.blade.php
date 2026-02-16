@php use App\Models\ProductAttributeVariation; @endphp
@php use App\Models\Image; @endphp
<form action="{{route('product.variation.store')}}" method="post">
    @csrf
    @php
        $getVariations = ProductAttributeVariation::where('main_pid', $prouduct_id)->get();
        $variationShowAs = ['Image', 'Text', 'None'];
    @endphp
    <div class="form-group" style="display: none">
        <label for="">Variation Show As</label>

        <select name="variation_show_as" id="" class="form-control">
            @foreach($variationShowAs as $data)
                <option value="{{$data}}" {{$product->variation_show_as == $data ? 'selected' : null}}>{{$data}}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="">Start Variation Layer From</label>
        <select name="variation_layer_start" id="" class="form-control" required>
            <option value="1" {{$product->variation_layer_start == 1 ? 'selected' : null}}>First</option>
            <option value="2" {{$product->variation_layer_start == 2 ? 'selected' : null}}>Second</option>
        </select>
    </div>

    <div class="form-group">
        <label for="">Variation</label>
        <a class="btn btn-info btn-xs add_variation">Create</a>
    </div>


    <input type="hidden" name="product_id" value="{{$prouduct_id}}">
    <input type="hidden" name="product_code" value="{{$prouduct_code}}">

    <div class="variation_template">
        <div class="row" style="margin: 0px;">
            @if(count($getVariations) > 0)
                @foreach($getVariations as $v)
                    @php
                        $row = 's'.$v->id;
                    @endphp
                    <div class="col-md-3 row_delete" data-row="{{$row}}">
                        <div class="row panel panel-info" style="padding: 5px; margin-right: 2px; position: relative">
                            <a class="btn btn-danger remove_row"
                               style="right: 7px; position: absolute; padding: 0px 5px;z-index: 1;"
                               data-id="{{$row}}"><i class="fa fa-trash"></i></a>
                            <div class="col-md-12 px-2">
                                <div class="form-group">
                                    <label for="">Sub title</label>
                                    <input required class="form-control input-sm" type="text"
                                           name="variation[{{$row}}][sub_title]" id=""
                                           value="{{$v->variation_sub_title}}">
                                </div>
                            </div>
                            <div class="col-md-12 px-2">
                                <div class="form-group">
                                    <label for="">Combination Name</label>
                                    <input required class="form-control input-sm" type="text"
                                           name="variation[{{$row}}][combination_name]" id=""
                                           value="{{$v->combination_name}}">
                                </div>
                            </div>
                            <div class="col-md-6 px-2">
                                <div class="form-group">
                                    <label for="">Product Code</label>
                                    <input required class="form-control input-sm" type="text"
                                           name="variation[{{$row}}][product_code]" id=""
                                           value="{{$v->variation_product_code}}">
                                </div>
                            </div>
                            <div class="col-md-6 px-2">
                                <div class="form-group">
                                    <label for="">Regular Price</label>
                                    <input required class="form-control input-sm" type="number"
                                           name="variation[{{$row}}][product_regular_price]" id=""
                                           value="{{$v->product_regular_price}}">
                                </div>
                            </div>

                            <div class="col-md-6 px-2">
                                <div class="form-group">
                                    <label for="">Selling Discount </label>
                                    <input class="form-control input-sm" type="number"
                                           name="variation[{{$row}}][product_selling_price]" id=""
                                           value="{{$v->product_selling_price}}">
                                    <small>Use value of Percentage. Ex: 10</small>
                                </div>
                            </div>
                            <div class="col-md-6 px-2">
                                <div class="form-group">
                                    <label for="">Variation Images</label>
                                    <input class="form-control input-sm" type="text"
                                           name="variation[{{$row}}][variation_image]" id=""
                                           value="{{$v->variation_image}}">
                                    <small>Enter image IDs by | separating values</small>
                                </div>
                            </div>
                            <div class="col-md-12 px-2">
                                <div class="form-group">
                                    <label for="">Variation Video</label>
                                    <input class="form-control input-sm" type="text"
                                           name="variation[{{$row}}][variation_video]" id=""
                                           value="{{$v->variation_video}}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" {{$v->disable_buy == 'on' ? 'checked="checked"' : '',}}
                                        value="on" name="variation[{{$row}}][disable_buy]" id="">
                                        Disable buy
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" {{$v->is_active == 1 ? 'checked="checked"' : '',}}
                                        value="1" name="variation[{{$row}}][is_active]" id="">
                                        Is Active
                                    </label>
                                </div>
                            </div>
                            {{--                @dump($getAttr['data'])--}}
                            @foreach($getAttr['data'] as $key => $data)
                                @php
                                    $attrValue = json_decode($data->attr_value);
                                    //dump($attrValue);
                                    //$attrValue = explode('|', $attrValue[0]) ?? [];
                                    $attrImage = json_decode($data->attr_images);
                                    $savedAttrValue =  (array) json_decode($v->variations);
            //                        dd($savedAttrValue[$data->attr_name]);
                                   // dump($savedAttrValue[$data->attr_name]);
                                    $savedAttrValue = $savedAttrValue[$data->attr_name]->value ?? '';
                                @endphp

                                <div class="col-md-{{$data->attr_show_as_decision == 'Image' ? '12' : '6'}} px-2">
                                    <div class="form-group">
                                        <label for="">&nbsp; {{$data->attr_name}}</label>
                                        @if($data->attr_show_as_decision == 'Text')
                                            <input type="hidden"
                                                   name="variation[{{$row}}][items][{{$data->attr_name}}][show_as]"
                                                   value="Text"/>
                                            <select name="variation[{{$row}}][items][{{$data->attr_name}}][value]"
                                                    class="form-control input-sm">
                                                <option value="">Select Any</option>
                                                @foreach($attrValue as $k => $attr)
                                                    @php
                                                        $attrval = $data->attr_type == 'pre-defined' ? explode('|', $attr)[0] :  $attr;
                                                    @endphp
                                                    <option value="{{$attrval}}"
                                                            {{!empty($savedAttrValue) && $savedAttrValue == $attrval ? 'selected' : null}}>{{$attrval}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @endif

                                        @if($data->attr_show_as_decision == 'Image')
                                            @if($attrImage)
                                                @php
                                                    $imgIds = Image::whereIn('id', $attrImage)->get();
                                                @endphp
                                                <div class="form-group">
                                                    <input type="hidden"
                                                           name="variation[{{$row}}][items][{{$data->attr_name}}][show_as]"
                                                           value="Image"/>
                                                    @foreach($imgIds as $link)

                                                        <input type="radio"
                                                               name="variation[{{$row}}][items][{{$data->attr_name}}][value]"
                                                               value="{{$link->id}}" id="img{{$link->id}}"
                                                                {{!empty($savedAttrValue) && $savedAttrValue == $link->id ? 'checked' : null}}
                                                        >
                                                        <label for="img{{$link->id}}"> <img style="width: 30px;"
                                                                                            src="{{url('/')}}/{{$link->icon_size_directory}}"></label>
                                                    @endforeach
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </div>

                            @endforeach

                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-success ">Submit</button>
    </div>

</form>

{{--@include('product.attributes.variation_test')--}}

@section('cusjs')
    @parent

    <script>
		jQuery(document).ready(function ($) {
			let row = 0;
			$('a.add_variation').click(function () {
				row = row + 1;
				let html = `
            <div class="col-md-3 row_delete"  data-row="${row}">

                <div class="row panel panel-info" style="padding: 5px; margin-right: 2px; position: relative">
                    <a class="btn btn-danger remove_row" style="right: 7px; position: absolute; padding: 0px 5px; z-index: 1" data-id="${row}"><i class="fa fa-trash"></i></a>
                    <div class="col-md-12 px-2">
                        <div class="form-group">
                            <label for="">Sub Title</label>
                            <input required class="form-control input-sm" type="text" name="variation[${row}][sub_title]" id="" value="">
                        </div>
                    </div>
                    <div class="col-md-12 px-2">
                        <div class="form-group">
                            <label for="">Combination Name</label>
                            <input required class="form-control input-sm" type="text" name="variation[${row}][combination_name]" id="" value="">
                        </div>
                    </div>
                    <div class="col-md-6 px-2">
                        <div class="form-group">
                            <label for="">Product Code</label>
                            <input required class="form-control input-sm" type="text" name="variation[${row}][product_code]" id="" value="">
                        </div>
                    </div>
                    <div class="col-md-6 px-2">
                        <div class="form-group">
                            <label for="">Regular Price</label>
                            <input required class="form-control input-sm" type="number" name="variation[${row}][product_regular_price]" id="" value="">
                        </div>
                    </div>

                     <div class="col-md-6 px-2">
                        <div class="form-group">
                            <label for="">Selling Discount</label>
                            <input class="form-control input-sm" type="number" name="variation[${row}][product_selling_price]" id="" value="">
                            <small>Use value of Percentage. Ex: 10</small>
                        </div>
                    </div>

                     <div class="col-md-6 px-2">
                        <div class="form-group">
                            <label for="">Variation Images</label>
                            <input class="form-control input-sm" type="text" name="variation[${row}][variation_image]" id="" value="">
                             <small>Enter image IDs by | separating values </small>
                        </div>
                    </div>

                     <div class="col-md-6 px-2">
                        <div class="form-group">
                            <input class="" type="checkbox" name="variation[${row}][disbale_buy]" id="" value="on"> Disable Buy
                        </div>
                    </div>
                    <div class="col-md-6 px-2">
                        <div class="form-group">
                            <input class="" type="checkbox" name="variation[${row}][is_active]" id="" value="1"> is Active
                        </div>
                    </div>
                    @if(!empty($getAttr))
                @foreach($getAttr['data'] as $key => $data)
                @php
                    $attrValue = json_decode($data->attr_value);
                    //$attrValue = explode('|', $attrValue[0]) ?? [];
                    $attrImage = json_decode($data->attr_images);

                @endphp
				<div class="col-md-{{$data->attr_show_as_decision == 'Image' ? '12' : '6'}} px-2">
                                    <div class="form-group">
                                    <label for="">&nbsp; {{$data->attr_name}}</label>
                                    @if($data->attr_show_as_decision == 'Text')
				<input type="hidden" name="variation[${row}][items][{{$data->attr_name}}][show_as]" value="Text" />
                                        <select name="variation[${row}][items][{{$data->attr_name}}][value]" class="form-control input-sm">
                                            <option value="">Select Any</option>
                                            @foreach($attrValue as $attr)
                @php
                    $attrval = $data->attr_type == 'pre-defined' ? explode('|', $attr)[0] :  $attr;
                @endphp
				<option value="{{$attrval}}">{{$attrval}}</option>
                                            @endforeach
				</select>
@endif

                @if($data->attr_show_as_decision == 'Image')
                @if($attrImage)
                @php
                    $imgIds = Image::whereIn('id', $attrImage)->get();
                @endphp
				<div class="form-group">
                  <input type="hidden" name="variation[${row}][items][{{$data->attr_name}}][show_as]" value="Image" />
                                                @foreach($imgIds as $link)
				<input type="radio" name="variation[${row}][items][{{$data->attr_name}}][value]" value="{{$link->id}}" id="img{{$link->id}}">
                                                    <label for="img{{$link->id}}"> <img style="width: 30px;" src="{{url('/')}}/{{$link->icon_size_directory}}"></label>
                                                @endforeach
				</div>
@endif
                @endif

				</div>
            </div>
@endforeach
                @endif
				</div>


            </div>`;
				$('.variation_template').prepend(html)
				row = row;
			})


			$('.variation_template').on('click', 'a.remove_row', function (e) {
				e.preventDefault()
				let id = $(this).data('id');

				let msg = confirm('Are you sure to remove');
				if (msg) {
					$(`.variation_template .row_delete[data-row="${id}"]`).remove()
				}
			})//End
		})
    </script>
    <style>
        .px-2 {
            padding-left: 2px !important;
            padding-right: 2px !important;
        }

        .row.ui-sortable-handle:hover {
            cursor: all-scroll;
        }
    </style>
@endsection
