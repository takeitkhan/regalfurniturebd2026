@php
    $prouduct_id = $product->id;
    $prouduct_code = $product->product_code;
    $attrGroup = \App\Models\ProductAttributeGroup::with('items')->orderBy('id', 'desc')->get();
//    dump($attrGroup);
    $getAttr = \App\Models\ProductAttribute::getData($prouduct_id);
//dump($getAttr);
//exit();
@endphp

<input type="hidden" name="product_id" value="{{$prouduct_id}}">
<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li class="active">
            <a href="#tab_1" data-toggle="tab" aria-expanded="true">
                Attribute: To create variation
            </a>
        </li>
        @if($getAttr)
            <li class="xactive">
                <a href="#tab_2" data-toggle="tab" aria-expanded="false">
                    Product: Create variation
                </a>
            </li>
        @endif
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
            <div class="row">
                <div class="col-md-12">
                    @include('product.attributes.attribute_create_form')
                </div>
            </div>
        </div>

        <div class="tab-pane " id="tab_2">
            <div class="row">

                    @include('product.attributes.variation')

            </div>
        </div>
    </div>

</div>


{{--<div class="row">--}}
{{--    <!-- Product Attribute -->--}}
{{--    <div class="col-md-12">--}}
{{--        @include('product.attributes.attribute_create_form')--}}
{{--    </div>--}}

{{--    <!-- Product Variation -->--}}
{{--    <div class="col-md-12">--}}
{{--        <div class="box">--}}
{{--            <div class="box-header">--}}
{{--                <h3 class="box-title">Create Variation</h3>--}}
{{--            </div>--}}
{{--            <div class="box-body">--}}
{{--                @include('product.attributes.variation')--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}

{{--</div>--}}


@section('cusjs')
    @parent
    <link rel="stylesheet" href="https://select2.github.io/select2-bootstrap-theme/css/select2-bootstrap.css">
    <style>
        .nav-tabs-custom > .tab-content {
            background: #f9f9f9;
            padding: 10px;
            border-bottom-right-radius: 3px;
            border-bottom-left-radius: 3px;
        }
    </style>
    <script>
        jQuery(document).ready(function ($) {
            let n = 0;
            $('button.add_group').click(() => {
                let selectGroupCls = $('select.select_group');
                let selected_group = selectGroupCls.find(':selected').val();
                let disableClass = selected_group == 'custom' ? '' : 'readonly';
                let items = selectGroupCls.find(':selected').data('items')
                let attrType = selected_group == 'custom' ? 'custom' : 'pre-defined';
                //console.log(items)
                let text = selected_group == 'custom' ? '' : selectGroupCls.find(':selected').text();
                n = n + 1;
                let html = `<div class="row" style="margin-bottom: 5px;" data-row=${n}>
                                   <input type="hidden" name="attr[${n}][attr_type]" value="${attrType}">
                                <div class="col-md-2">
                                    <label for="default">Will it be default?</label>
                                    <div class="">
                                        <input class="fixed_variation${n}" type="radio" name="fixed_variation"
                                            value="${text}"/> Make it default
                                    </div>
                                </div>

                            <div class="col-md-2">
                                <label for="default">Variation Show as -</label>
                                <div>
                                <input id="variation_show_as_decision" type="radio"
                                       name="attr[${n}][attr_textarea_decision]"
                                        value="Text"/> Text
                                        &nbsp;&nbsp;&nbsp;
                                     <input id="variation_show_as_decision" type="radio"
                                            name="attr[${n}][attr_textarea_decision]"
                                            value="Image"/> Image
                                 </div>
                             </div>
                                <div class="col-md-7" style="background: #f1f1f1;">
                                <div class="row">
                                 <div class="col-md-4">
                                     <div class="form-group">
                                        <label for="default">Variation Label</label>
                                         <input data-id="${n}" required ${disableClass} type="text" name="attr[${n}][attr_name]" value="${text}" class="form-control appened_attr_name" placeholder="Enter variation title...">
                                                                </div>
                                                            </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="">&nbsp</label>`;

                                        if (selected_group == 'custom') {
                                            html += ` <label for="default">Variation Values (<small>Enter comma separated data in Text or Image Textarea</small>)</label>`
                                            html += `<div class="form-group">
                                                        <label>Show as text</label>
                                                        <textarea required class="form-control" placeholder="Enter variation values... Pipe (|) separated" name="attr[${n}][attr_value]"></textarea>
                                                        <small>Enter attributes by | separating values. Ex: item1|item2</small>
                                                        <br/>
                                                        Or
                                                        <br/>
                                                        <label>Show as image</label>
                                                        <textarea class="form-control" placeholder=""
                                                                  name="attr[${n}][attr_images]"></textarea>
                                                        <small>Enter image IDs by | separating values. Ex: 3583|3554</small></div>
                                            `;
                                        } else {

                                            html += `<input type="hidden" name="attr[${n}][predefine_id]" value="${selected_group}">`
                                            html += `<label>Show as text</label>`
                                            html += `<select name="attr[${n}][attr_value][]" required class="form-control asd"  multiple="" data-placeholder="Select a value" style="width: 100%"; tabindex="-1" aria-hidden="true">`;
                                            items.forEach(function (item, index) {
                                                html += `<option value="${item.item_name}|${item.id}">${item.item_name}</option>`;
                                            })
                                            html += `</select>`;

                                            html += `
                                                        <br/>
                                                        Or
                                                        <br/>
                                                        <label>Show as image</label>
                                                        <textarea class="form-control" placeholder=""
                                                                  name="attr[${n}][attr_images]"></textarea>
                                                        <small>Enter image IDs by | separating values. Ex: 3583|3554</small>

                                            `;

                                        }

                                    html += `</div>
                                            </div>
                                                </div>
                                            </div>
                                             <div class="col-md-1">
                                                <label for="" style="display:block">&nbsp;</label>
                                                <button type="button" class="remove_row btn btn-white btn-sm text-danger"  data-id="${n}"><i class="fa fa-trash"></i></button>
                                             </div>
                                        </div>`;

                $('.field_append').prepend(html);
                select2Refresh()

                selectGroupCls.val(null)
                n = n;
            }) //End

            $('.field_append').on('click', 'button.remove_row', function (e) {
                e.preventDefault()
                let id = $(this).data('id');

                let msg = confirm('Are you sure to remove');
                if (msg) {
                    $(`.field_append .row[data-row="${id}"]`).remove()
                }
            })//End

            function select2Refresh() {
                return $(".asd").select2({
                    theme: 'bootstrap'
                }); //refresh Select2
            }

            select2Refresh();


            $('body').on('keyup', 'input.appened_attr_name', function (){
                let id = $(this).data('id');
                let value = $(this).val();
                $('.fixed_variation'+id).val(value)
                // alert(id)
                // $().val($(this).val())
            })

            $('#variation_show_as_decision').prop(function () {
                alert(3);
            });
        })
    </script>

@endsection
