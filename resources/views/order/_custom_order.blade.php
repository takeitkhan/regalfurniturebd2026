@extends('layouts.app')


@section('title', 'Custom Order')


@section('sub_title', '')


@section('content')

    <div class="row">
        @if(Session::has('success'))
            <div class="col-md-12">
                <div class="callout callout-success">
                    {{ Session::get('success') }}
                </div>
            </div>
        @endif

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
        @php
            $dc = \App\Models\PaymentSetting::where('id', 1)->first();
            $insideDhakaCharge = $dc->inside_dhaka_od ?? 0;
            $outsideDhakaCharge = $dc->outside_dhaka_od ?? 0;
            $fromOneClickBuy = request()->get('from-oneclickbuy');
            $getfromOneClickBuy = $fromOneClickBuy ? \App\Models\Oneclickbuy::where('id', $fromOneClickBuy)->first() : null;
        @endphp
        <form action="{{route('order.custom_order_store')}}" method="post" class="customOrderForm">
            <input type="hidden" name="order_source" value="{{$fromOneClickBuy ? 'one click Buy' : 'custom'}}">
            <div class="col-md-3" id="signupForm">
            <div class="box box-success">
                <div class="box-body with-border">
                    <div class="form-group">
                        {{ Form::text('customerName', $getfromOneClickBuy->customer_name ?? null, ['required', 'class' => 'form-control', 'placeholder' => 'Customer Name...','id' => 'customerName']) }}
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::text('phone', $getfromOneClickBuy->customer_phone ?? null, ['required', 'class' => 'form-control', 'placeholder' => 'Phone...','id' => 'phone']) }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::text('emergencyPhone', $getfromOneClickBuy->customer_phone ?? null, ['required', 'class' => 'form-control', 'placeholder' => 'Emergency Phone ...','id' => 'emergencyPhone']) }}
                            </div>
                        </div>
                    </div>


                    <div class="form-group">
                        {{ Form::text('email', $getfromOneClickBuy->customer_email ?? null, ['required', 'class' => 'form-control', 'placeholder' => 'Enter email...','id' => 'email']) }}
                    </div>
{{--                    <div class="row">--}}
{{--                        <div class="col-md-6">--}}
{{--                            <div class="form-group">--}}
{{--                                {!! Form::select('divsion', ['' => 'Select division'], $image->active??1,['class' => 'divisionField form-control']) !!}--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                        <div class="col-md-6">--}}
{{--                            <div class="form-group">--}}
{{--                                {!! Form::select('district', ['' => 'Select district'], $image->active??1,['class' => 'districtField form-control']) !!}--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                    <div class="form-group">
                        {{--                        {!! Form::select('thana', ['' => 'Select thana'], $image->active??1,['class' => 'thanaField form-control']) !!}--}}
                        @php
                            $divisions = ['Dhaka', 'Chittagong', 'Khulna', 'Sylhet', 'Barisal', 'Rajshahi', 'Rangpur'];
                        @endphp
                        <select class="form-control" id="division_select">
                            <option value="" selected disabled>Select Division</option>
                            @foreach($divisions as $divi)
                                <option value="{{$divi}}">{{$divi}}</option>
                            @endforeach
{{--                            <option value="Dhaka" data-charge="{{$insideDhakaCharge}}">Dhaka</option>--}}
{{--                            <option value="Outside Dhaka" data-charge="{{$outsideDhakaCharge}}">Outside Dhaka</option>--}}
                        </select>
                    </div>
                    <div class="form-group">
{{--                        {!! Form::select('thana', ['' => 'Select thana'], $image->active??1,['class' => 'thanaField form-control']) !!}--}}
                        <select name="district" class="form-control" id="district_select">
                            <option value="" selected disabled>Select District</option>
{{--                            <option value="Dhaka" data-charge="{{$insideDhakaCharge}}">Dhaka</option>--}}
{{--                            <option value="Outside Dhaka" data-charge="{{$outsideDhakaCharge}}">Outside Dhaka</option>--}}
                        </select>
                    </div>

                    <div class="form-group">
                        {{ Form::text('address', $getfromOneClickBuy->customer_address ?? null, ['required', 'class' => 'form-control', 'placeholder' => 'Enter Address...','id' => 'address']) }}
                    </div>

                    <div class="form-group">
                        {{ Form::text('notes', '', ['required', 'class' => 'form-control', 'placeholder' => 'Enter notes...','id' => 'notes']) }}
                    </div>

                    <div class="form-group">
                        {!! Form::select('paymentmethod', ['cash_on_delivery' => 'Cash on delivery', 'debitcredit' => 'Debit/Credit Card','mobilebanking' => 'Mobile Banking'], $image->active??1,['class' => 'form-control','onChange' => 'savePaymentMethod()','id' => 'paymentmethod']) !!}
                    </div>
                    <div class="row">
                        <div class="col-md-6">

                            {{Form::label('payment_term_status','Payment Status',array('class' => 'Payment Status'))}}
                            <div class="form-group">
                                {!! Form::select('payment_term_status', ['COD' => 'COD', 'Pending' => 'Pending', 'Success' => 'Success','Canceled' => 'Cancelled'], $image->active??1,['class' => 'form-control']) !!}
                            </div>
                        </div>

                        <div class="col-md-6">

                            {{Form::label('order_status','Order Status',array('class' => 'order-status'))}}
                            <div class="form-group">
                                {!! Form::select('order_status', ['placed' => 'Placed', 'recieved' => 'Recieved','processing' => 'Processing','picked' => 'Picked', 'shipped' => 'Shipped', 'delivered' => 'Delivered', 'canceled' => 'Cancelled'], $image->active??1,['class' => 'form-control']) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            <div class="col-md-9">

                <div class="box box-success">
                    <div class="box-header with-border">
                        Products
                        <a href="javascript:void(0)" class="btn btn-xs btn-success pull-right" id="go-to-add-product-button"><i class="fa fa-align-justify"></i></a>
                    </div>
                    <div class="box-body" style="">
                        <div class="box-body table-responsive no-padding">

                            <div class="order-input-number text-center justify-content-center" style="margin-bottom:10px;">
                                <div class="row">

                                    <div id="loadProductResult" style="display:inline">

                                    </div>
                                </div>
                                <!--<a href="javascript:void(0)" onclick="loadProductFromInput()" class="btn btn-xs btn-warning">Load</a>-->
                                <!--<a href="javascript:void(0)" onclick="addToCart()" class="btn btn-xs btn-success">ADD</a>-->
                                <div class="texr-center" id="product-cart-button-click-response"></div>
                            </div>


                            <div class="add-new-product" xstyle="display:none;">
    {{--                            <div class="font-weight-bold border-bottom" style="font-weight:bold;">--}}
    {{--                                Add Product--}}
    {{--                            </div>--}}
                                <div class="row productSelectArea">

                                    <div class="col-md-3">
    {{--                                    <input type="text" class="form-control" id="product-title" placeholder="Title">--}}
                                        <label for="">Select product</label>
                                        <select class="form-control order-input-search" style="" id="order-input-search" >
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <input type="hidden" class="form-control" id="product-id" placeholder="">
                                        <input type="hidden" class="form-control" id="product-image" placeholder="">
                                        <input type="hidden" class="form-control" id="product-title" placeholder="">
                                        <input type="hidden" class="form-control" id="product-sub-title" placeholder="">
                                        <label for="">Code</label>
                                        <input type="text" class="form-control" id="product-code" placeholder="Code">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="">&nbsp;SKU </label>
                                        <input type="text" class="form-control" id="product-sku" placeholder="SKU">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="">QTY</label>
                                        <input type="text" class="form-control" id="qty" placeholder="Qty">
                                    </div>


                                    <div class="col-md-2">
                                        <label for="">Price</label>
                                        <input type="text" class="form-control" id="product-price" placeholder="Price">
                                    </div>


                                </div>
                                <div class="row pull-right" style="margin:5px 2px;">
                                    <input type="button" class="btn btn-success" id="add-product-button" value="ADD"/>
                                </div>

                            </div>

                        </div>

                        <div class="cart-products">
                                @csrf
                                <table class="table table-hover">
                                    <tbody id="cartBody">
                                        @php
                                            $getOneClickProduct = $getfromOneClickBuy ? explode(',', $getfromOneClickBuy->product_id ?? null) : false;

                                        @endphp
                                        @if($getOneClickProduct)
                                            <input type="hidden" name="oneclickbuy_id" value="{{$getfromOneClickBuy->id}}">
                                        @foreach($getOneClickProduct as $product)
                                            @php
                                                $product = \App\Models\Product::where('id', $product)->first();
                                                $productID = $product->id;
                                                $productName = $product->title;
                                                $productCode = $product->product_code;
                                                $productPrice = $product->local_selling_price;
                                                $productImage = \App\Models\ProductImages::where('main_pid', $productID)->first()->icon_size_directory;
                                                $qty = 1;
                                                //dd($product);
                                            @endphp
                                            <tr data-row_id="{{$productID}}" id="productRow">
                                                <td>
                                                    <input type="hidden" name="product[${productID}][product_id]" value="{{$productID}}">
                                                    <input type="hidden" name="product[${productID}][product_name]" value="{{$productName}}">
                                                    <input type="hidden" name="product[${productID}][product_code]" value="{{$productCode}}">
                                                    <img src="<?php echo url('/') ?>/{{$productImage}}" width="100"  />
                                                </td>
                                                <td>
                                                    <a href=""><strong></strong></a>{{$productName}}<br/>
                                                    <strong>SKU: </strong> {{$productCode}}<br/>
                                                    <strong>Item Code: {{$productCode}}</strong><br/>
                                                    <strong>Short Details: {{$product->sub_title}}</strong>
                                                </td>
                                                <td>
                                                    <input class="change-qty" type="number" name="product[${productID}][qty]" maxlength="2" min="1" value="{{$qty}}" max="99" style="width: 4em">
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td>৳ <input name="product[${productID}][price]" type="number" value="{{$productPrice}}" class="change-price" data-productcode="" maxlength="2" min="1" style="width:4em"></td>
                                                <td><strong>৳<span class="this_product_total_price">{{$qty * $productPrice}}</span></strong></td>
                                                <td>
                                                    <a href="javascript:void(0)" class="btn btn-xs btn-danger remove_row" data-product_id="{{$productID}}">
                                                        <i class="fa fa-times"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        @endif
        {{--                            <td class="text-center text-danger" style="padding:10px;">No Product found...</td>--}}
                                    </tbody>
                                </table>


                        </div>

                    </div>
                    <div class="box-footer clearfix text-center">

                        <div class="pull-right" style="display:inline; text-align: end">
                            <div style="padding: 3px;">
                                <div style="display:inline;">Sub Total</div>
                                <input readonly type="number" name="total_price" id="the_total_price" style="width:4em;display:inline;">
                            </div>
                            <div style="padding: 3px;">
                                <div style="display:inline;">Delivery Charge</div>
                                <input type="number" value="0" min="0" name="delivery_price" id="the-delivery-charge"  style="width:4em;display:inline;">
                            </div>
                            <div style="padding: 3px;">
                                <div style="display:inline;">Grand Total</div>
                                <input readonly type="number" name="grand_total" id="the-grand-total"  style="width:4em;display:inline;">
                            </div>
                            <button type="button" zid="submitButton"  onclick="submitProductCart()" class="btn btn-success">Submit</button>
                        </div>
    {{--                    <input type="submit" name="submit" value="UPDATE" onclick="updateProductCart()" class="btn btn-warning"/>--}}
{{--                        <input type="submit" id="submitButton" name="submit" value="SUBMIT" onclick="submitProductCart()" class="btn btn-success"/>--}}

                    </div>
                </div>
            </div>
        </form>
    </div>


@endsection



@push('scripts')
    <link rel="stylesheet" href="{{asset('public/cssc/select2/dist/css/select2.min.css')}}">


    <script>
        jQuery(document).ready(function ($){
            $('#order-input-search').select2({
                ajax: {
                    url: '{{route('custom_order_search_poduct')}}', //'https://rflbestbuy.com/api/shop/search_products',
                    delay: 350,
                    data: function (params) {
                        var query = {
                            keyword: params.term,
                        }

                        return query;
                    },
                    processResults: function (data) {

                        const products = data.products.data.map(function(item){

                            return {
                                id: item.id,
                                text: item.title+'-'+item.sub_title
                            }
                        })
                        console.log(data)
                        return {
                            results: products,
                        };
                    }

                },
                minimumInputLength: 3,
                allowClear: true,
            });


            $('#order-input-search').on('change', function(){
                let getVal = $(this).val()
                $.ajax({
                    url : "{{route('custom_order_select_poduct')}}?productId="+getVal,
                    method : "GET",
                    success: function(res){
                        console.log(res.product)
                        let product = res.product;
                        // alert(product.id)
                        $('#product-id').val(product.id);
                        $('#product-image').val(product.first_image['icon_size_directory'])
                        $('#product-code').val(product.product_code)
                        $('#product-sku').val(product.product_code)
                        $('#product-title').val(product.title)
                        $('#product-sub-title').val(product.sub_title)
                        $('#qty').val(1)
                        $('#product-price').val(product.product_price_now)
                    },
                })
                // console.log(getVal)
                // loadProduct(getVal)

            })

            $('#add-product-button').on('click', function(){
                let area = '.productSelectArea'
                var errors = 0;
                let check =  $(area + ' input')
                    check.map(function(){
                       if(!$(this).val()){
                           errors++
                       }
                    })
                    if(errors == 0) {
                        let productID = $(area + ' #product-id').val();
                        let productImage = $(area + ' #product-image').val();
                        let productName = $(area + ' #product-title').val();
                        let productCode = $(area + ' #product-code').val();
                        let qty = $(area + ' #qty').val();
                        let productPrice = $(area + ' #product-price').val();

                        let html = `<tr data-row_id="${productID}" id="productRow">
                                    <td>
                                        <input type="hidden" name="product[${productID}][product_id]" value="${productID}">
                                        <input type="hidden" name="product[${productID}][product_name]" value="${productName}">
                                        <input type="hidden" name="product[${productID}][product_code]" value="${productCode}">
                                        <img src="<?php echo url('/') ?>/${productImage}" width="100"  />
                                    </td>
                                    <td>
                                        <a href=""><strong></strong></a>${productName}<br/>
                                        <strong>SKU: </strong> ${productCode}<br/>
                                        <strong>Item Code: ${productCode}</strong><br/>
                                        <strong>Short Details: </strong>
                                    </td>
                                    <td>
                                        <input class="change-qty" type="number" name="product[${productID}][qty]" maxlength="2" min="1" value="${qty}" max="99" style="width: 4em">
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td>৳ <input name="product[${productID}][price]" type="number" value="${productPrice}" class="change-price" data-productcode="" maxlength="2" min="1" style="width:4em"></td>
                                    <td><strong>৳<span class="this_product_total_price">${qty * productPrice}</span></strong></td>
                                    <td>
                                        <a href="javascript:void(0)" class="btn btn-xs btn-danger remove_row" data-product_id="${productID}">
                                            <i class="fa fa-times"></i>
                                        </a>
                                    </td>
                                </tr>`;
                        $('#cartBody').prepend(html)
                        totalPrice();

                        $('select.order-input-search option').remove()
                        $(area + ' input').val(null)

                    }
                // $('.order-input-search').val(null).trigger('change');

            })

            $('.cart-products').on('click', 'a.remove_row', function(e){
                e.preventDefault()
                let id = $(this).data('product_id');
                $('tr[data-row_id = "'+id+'"]').remove()
                totalPrice();
                grandTotal();
            })
        })

        $('select#division_select').change(function(){
            let dvision = $(this).find(':selected').val();
            $.ajax({
                method : 'get',
                url : '{{url('/')}}/api/common/districts-by-diviison/'+dvision,
                success: function(res){
                    let html = '<option value="" selected="" disabled="">Select District</option>';
                    res.forEach((item, index)=>{
                        let DelCharge = item.district == 'Dhaka' ? '{{$insideDhakaCharge}}' : '{{$outsideDhakaCharge}}';
                        html +=`<option value="${item.district}" data-charge="${DelCharge}">${item.district}</option>`
                    })
                    $('select#district_select').empty().html(html)
                }
            })
        })

        $('select#district_select').change(function(){
            let charge = $(this).find(':selected').data('charge');
            $('#the-delivery-charge').val(charge)
            totalPrice()
        })
        function submitProductCart(){
            $('form.customOrderForm').submit()
        }

        $('#cartBody').on('keyup', 'input.change-qty, input.change-price', function(){
            let dataRowId = $(this).closest('tr').data('row_id');
            let mkClass = `tr[data-row_id=${dataRowId}]`
            let qty = $(mkClass+' input.change-qty').val();
            let price = $(mkClass+' input.change-price').val();
            let total = parseInt(qty*price);
                $(mkClass+ ' span.this_product_total_price').text(total);

            totalPrice()
        })

        function totalPrice(){
            let total = 0;
            $('.this_product_total_price').each(function(){
                total += parseInt($(this).text())
            })
            $('#the_total_price').val(total)


            let dCharge =  parseInt($('#the-delivery-charge').val() ?? 0);
            $('#the-grand-total').val(parseInt(dCharge+total))


            // return total;
            // console.log(total)
        }
        totalPrice();

        $('#the-delivery-charge').keyup(function (){
            let dcharge = parseInt($(this).val());
            totalPrice()

        })
        //
        // function grandTotal(){
        //     // let dCharge =  parseInt($('#the-delivery-charge').val() ?? 0);
        //     // let amount = parseInt(totalPrice)
        //     $('#the-grand-total').val(123)
        //     // return amount
        // }

    </script>


    <?php /*
    <script>
        let cartProductId = false,
            cartBuyQty = 1,
            cartBuyColorId = false,
            cartBuySizeId = false,
            cartBuyTypeId = false,
            productDetails = false,
            theToken = 'thisistokenforonbehalfbuy',
            itemQuantityUpdate = {},
            disableSubmit = false,
            adminDeliveryCharge = 1;




        function loadProductFromInput(){
            const productURI = `{{url('/api/shop/product_details')}}?productId=`,
                productId = document.getElementById('order-input-search').value,
                apiURI = productURI+productId


            jQuery.get( apiURI, function( data ) {

                cartProductId = data.product.id
                productDetails = data

                let colorHTML = '',
                    colorImgURI = `{{url('public/pmp_img')}}/`
                data.product.colors.forEach(function(item){
                    colorHTML += `<img src="${colorImgURI+item.color_codes}" data-colorId=${item.id} data-sizesd='${JSON.stringify(item.sizes)}' onclick="loadSizes(this)" width="30" style="border: 1px solid #ddd;"/>`
                })

                const html = `
    <div class="" style="display:inline">

        <div class="" style="display:inline">
            Color:
        ${colorHTML}
        </div>

        <div id="sizesHTML" style="display:inline">

        </div>

        </div>
    `



                jQuery('#loadProductResult').html(html)

            }).fail(function(){
                productDetails = false
                const html = `<div class="alert-danger" style="display:inline">product not found..</div>`
                jQuery('#loadProductResult').html(html)

            })


        }


        function loadSizes(its){
            const sizes = JSON.parse(its.dataset.sizesd);

            activeColsi(its)

            cartBuyColorId = its.dataset.colorid

            if(sizes.length < 1){
                jQuery('#sizesHTML').html('')
                return
            }

            cartBuySizeId = sizes[0].id

            let sizeHTML = 'Sizes:',
                sizeImgURI = `{{url('public/pmp_img')}}/`

            sizes.forEach(function(size){
                sizeHTML += `<img src="${sizeImgURI+size.color_codes}" data-sizeId=${size.id} onclick="sizeChoose(this)" width="30" style="border: 1px solid #ddd;"/>`
            })

            jQuery('#sizesHTML').html(sizeHTML)
        }

        function sizeChoose(its){
            activeColsi(its)
            cartBuySizeId = its.dataset.sizeid

        }

        function activeColsi(its){
            const allElement = Object.values(its.parentElement.children)

            allElement.forEach(function(ele){
                ele.style.borderColor = '#ddd'
            })

            its.style.borderColor = 'green'
        }


        function addToCart(){

            const orderInputSearch = jQuery('#order-input-search')
            if(!cartProductId){
                cartProductId = orderInputSearch.val()
            }

            if(!cartBuyColorId && productDetails && productDetails.product && productDetails.product.colors.length > 0){
                cartBuyColorId = productDetails.product.colors[0].id
            }

            if(!cartBuySizeId && productDetails && productDetails.product && productDetails.product.colors.length > 0){
                if(productDetails.product.colors[0].sizes.length > 0){
                    cartBuySizeId = productDetails.product.colors[0].sizes[0].id
                }
            }

            const addCartURI = `{{url('api/shop/add_to_cart')}}?self_token=${theToken}&main_pid=${cartProductId}&qty=${cartBuyQty}&color=${cartBuyColorId}&size=${cartBuySizeId}&type=`

            jQuery.get(addCartURI,function(data){
                console.log(data)
                jQuery('#product-cart-button-click-response').html(`<p class="text-success">Product Successfully added to cart</p>`)
                if(cartProductId){
                    orderInputSearch.val('').trigger('change');
                }


                loadCart()
            }).fail(function(){
                jQuery('#product-cart-button-click-response').html(`<p class="text-danger">Product do not added to cart</p>`)
            })

        }


        function loadCart(){
            const cartURI = `{{url('api/shop/cart')}}?self_token=${theToken}&admin_delivery_charge=${adminDeliveryCharge}`


            jQuery.get(cartURI,function(data){
                if(!data){
                    jQuery("#cartBody").html(`<td class="text-center text-danger" style="padding:10px;">No Product found...</td>`)
                    return
                }
                const shipmentArray = Object.values(data.groupByShippingTime)

                let shipmentHTML = `<tr>
                                <th>Image</th>
                                <th>Product Information</th>
                                <th>Quantity</th>
                                <th>Color</th>
                                <th>Size</th>
                                <th>Unit Price</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>`


                shipmentArray.forEach(function(shipment){

                    shipmentHTML += `<tr class="alert-soft-info">
                                <td><strong>Time</strong> : ${shipment.timeFrame},</td>
                                <td><strong>Delivery Charge</strong> : ${shipment.delivery_charge}</td>
                            </tr>`

                    const shipmentItems = Object.values(shipment.items)
                    let imgURI = `{{url('/')}}/`

                    shipmentItems.forEach(function(product){
                        console.log(product)
                        shipmentHTML += `<tr>
                                <td>
                                    <img src="${imgURI+(product.item.info.image ? product.item.info.image.icon_size_directory : '')}" width="100"  />
                                </td>
                                <td>
                                    <a href=""><strong>${product.item.info.title}</strong></a><br/>
                                    <strong>SKU: </strong>${product.item.info.sku}<br/>
                                    <strong>Item Code: </strong>${product.item.productcode}<br/>
                                    <strong>Short Details: </strong>${product.item.info.short_description}
                                </td>
                                <td>
                                    <input type="number" onChange="productQuantityChange(this)" data-productcode="${product.item.productcode}" maxlength="2" min="1" value="${product.qty}" max="99" style="width: 4em">
                                </td>
                                <td></td>
                                <td></td>
                                <td>৳ <input type="number" onChange="changePrice(this)" value="${product.purchaseprice}" class="change-price" data-productcode="${product.item.productcode}" maxlength="2" min="1" style="width:4em"></td>
                                <td><strong>৳ ${product.purchaseprice*product.qty}</strong></td>
                                <td>
                                    <a href="javascript:void(0)" class="btn btn-xs btn-danger" data-productcode="${product.item.productcode}" onclick="removeProduct(this)">
                                        <i class="fa fa-times"></i>
                                    </a>
                                </td>
                            </tr>`
                    })

                })
                jQuery("#cartBody").html(shipmentHTML)


                jQuery("#total-amount").html(`<b>Total: </b>৳ ${(parseInt(data.totalprice)||0)+(parseInt(data.totalDeliveryCharge)||0)}`)
                jQuery("#the-delivery-charge").val(data.totalDeliveryCharge)

                saveDeliveryLocation()
                savePaymentMethod()
                saveUserDetails()
                adminDeliveryCharge = 2;


            }).fail(function(){
                console.log('failed....')
                saveDeliveryLocation()
                savePaymentMethod()
                saveUserDetails()
                adminDeliveryCharge = 2;

            })


        }


        function removeProduct(its){

            if(!confirm("Are you sure ?")){
                return
            }

            const apiURI = `{{url('api/shop/remove_cart_item')}}?self_token=${theToken}&product_code=${its.dataset.productcode}`

            jQuery.get(apiURI,function(data){
                loadCart()
                console.log('get removed')
            }).fail(function(){
                loadCart()
                console.log('fail removed')
            })

        }


        function productQuantityChange(its){

            disableSubmit = true
            itemQuantityUpdate[its.dataset.productcode] = its.value
            jQuery("#submitButton").attr('disabled',true)
            console.log(itemQuantityUpdate)
        }


        function changePrice(e){
            console.log(e.value,e.dataset.productcode)
                {{--//{{route('admin.order.updateProductPrice')}}--}}
            const apiURI = `?_token={{csrf_token()}}&price=${e.value}&code=${e.dataset.productcode}`;

            jQuery.get(apiURI,function(data){

                if(!data){
                    return
                }
                loadCart()

                console.log(data,'change pricing...')

            }).fail(function(){

            })


        }


        function updateProductCart(){

            if(!disableSubmit){
                return
            }

            const apiURI = `{{url('api/shop/update_cart')}}`
            jQuery.post(apiURI,{ self_token: theToken, items: itemQuantityUpdate },function(data){

                console.log(data,'updated')
            }).fail(function(){

                console.log('helloweb')

            });
            jQuery("#submitButton").attr('disabled',false)
            disableSubmit = false
            loadCart()

        }


        async function submitProductCart(){
            if(disableSubmit){
                return
            }
            await saveDeliveryLocation()
            await savePaymentMethod()
            await saveUserDetails()
            await paynow()
        }



        function paynow(){
            const apiURI = `{{url('api/shop/pay_now')}}?self_token=${theToken}&onbehalf_pm_status=${jQuery("#payment_term_status").val()}&onbehalf_od_status=${jQuery("#order_status").val()}`

            jQuery.get(apiURI,function(data){
                const ob_URI = `{{url('orders')}}?column=ob_order&search_key=1`
                window.location.replace(ob_URI);
            }).fail(function(){

            })
        }







        function loadDivision(){
            const apiURI = `{{url('api/common/divisions')}}`

            let divisionHTML = '<option value="">Select division</option>'
            jQuery.get(apiURI,function(data){

                if(!(Array.isArray(data))){
                    return
                }

                data.forEach(function(division){
                    divisionHTML += `<option value="${division.division}">${division.division}</option>`
                })

                console.log(divisionHTML,'division')

                jQuery(".divisionField").html(divisionHTML)

            }).fail(function(){

            })
        }


        function loadDistrict(){
            const apiURI = `{{url('api/common/getDistrictsByDivision')}}?division=${jQuery('.divisionField').val()}`


            let districtHTML = '<option value="">Select district</option>'
            jQuery.get(apiURI,function(data){

                if(!(Array.isArray(data))){
                    return
                }

                data.forEach(function(division){
                    districtHTML += `<option value="${division.district}">${division.district}</option>`
                })

                console.log(districtHTML,'division')

                jQuery(".districtField").html(districtHTML)

            }).fail(function(){

            })

            loadThana()
        }

        jQuery('.divisionField').change(function(){
            loadDistrict()
        })


        function loadThana(){
            const apiURI = `{{url('api/common/getThanaByDistrict')}}?district=${jQuery('.districtField').val()}`


            let thanaHTML = '<option value="">Select thana</option>'
            jQuery.get(apiURI,function(data){

                if(!(Array.isArray(data))){
                    return
                }

                data.forEach(function(division){
                    thanaHTML += `<option value="${division.thana}">${division.thana}</option>`
                })

                console.log(thanaHTML,'division')

                jQuery(".thanaField").html(thanaHTML)

            }).fail(function(){

            })
        }

        jQuery('.districtField').change(function(){
            loadThana()
        })


        function loadUserDetails(){
            const apiURI = `{{url('api/shop/checkoutUserDetails')}}?self_token=${theToken}`


            jQuery.get(apiURI,function(data){
                if(!data){
                    return
                }

                jQuery('#customerName').val(data.name)
                jQuery('#phone').val(data.phone)
                jQuery('#emergencyPhone').val(data.emergency_phone)
                jQuery('#email').val(data.email)
                jQuery('#address').val(data.address)
                jQuery('#notes').val(data.notes)


            }).fail(function(){

            })
        }

        loadUserDetails()



        function loadUserDeliveryLocation(){
            const apiURI = `{{url('api/shop/getUserDeliveryLocation')}}?self_token=${theToken}`


            jQuery.get(apiURI,function(data){
                if(!data){
                    return
                }

                // console.log(jQuery('.divisionField'))

                jQuery(`.divisionField option[value='${data.division}']`).attr('selected','selected').change();
                setTimeout(function(){
                    jQuery(`.districtField option[value='${data.district}']`).attr('selected','selected').change();
                },200)

                setTimeout(function(){
                    jQuery(`.thanaField option[value='${data.thana}']`).attr('selected','selected').change();
                },300)

            }).fail(function(){

            })
        }

        setTimeout(function(){
            loadUserDeliveryLocation()
        },100)


        function loadPaymentMethod(){
            const apiURI = `{{url('api/shop/checkoutUserPaymentMethod')}}?self_token=${theToken}`


            jQuery.get(apiURI,function(data){

                if(!data){
                    return
                }
                jQuery(`#paymentmethod option[value=${data.payment_method}]`).attr('selected','selected').change();

                console.log(data,'paymethod loading...')

            }).fail(function(){

            })
        }

        loadPaymentMethod()


        function saveUserDetails(){
            //post method
            const apiURI = `{{url('api/shop/submitCheckoutUserDetails')}}`,
                user = {
                    name: jQuery('#customerName').val(),
                    phone: jQuery('#phone').val(),
                    emergency_phone: jQuery('#emergencyPhone').val(),
                    email: jQuery('#email').val(),
                    division: jQuery('.divisionField').val(),
                    district: jQuery('.districtField').val(),
                    thana: jQuery('.thanaField').val(),
                    deliveryfee: 0,
                    address: jQuery('#address').val(),
                    dba: 0,
                    dba_name: '',
                    dba_division: '',
                    dba_district: '',
                    dba_thana: '',
                    dba_address: '',
                    notes: jQuery('#notes').val(),
                    username:jQuery('#email').val().split('@')[0],
                    password:'habijabisocketon',
                    create: 0,
                    logged: 0
                }


            jQuery.post(apiURI,{ self_token: theToken, user: user },function(data){

                console.log(data,'user information updated')

            }).fail(function(){

                console.log('user info not updated..')

            });
        }


        function savePaymentMethod(){
            //post method
            const apiURI = `{{url('api/shop/storePaymentMethod')}}`

            jQuery.post(apiURI,{ self_token: theToken, term_check: true,payment_method: jQuery('#paymentmethod').val() },function(data){

                console.log(data,'paymentmethod updated')
                return
            }).fail(function(){

                console.log('payment method failed...')
                return

            });

        }


        function updateDeliveryCharge(e)
        {

            const apiURI = `{{route('order.updateDeliveryCharge')}}?_token={{csrf_token()}}&charge=${e.value}`;

            jQuery.get(apiURI,function(data){

                if(!data){
                    return
                }
                adminDeliveryCharge = 1;
                loadCart()

                console.log(data,'change delivery charge...')

            }).fail(function(){

            })



        }

        jQuery('#paymentmethod').change(function(){
            savePaymentMethod()
        })

        function saveDeliveryLocation(){
            //post method
            const apiURI = `{{url('api/shop/storeUserDeliveryLocation')}}`

            jQuery.post(apiURI,{ self_token: theToken,division: jQuery('.divisionField').val() ,district: jQuery('.districtField').val() ,thana: jQuery('.thanaField').val() },function(data){

                console.log(data,'delivery location updated')

            }).fail(function(){

                console.log('delivery location not updated')

            });

        }

        jQuery(".thanaField").change(function(){
            saveDeliveryLocation()
        })


        loadDivision()
        loadCart()
    </script>

    <script>

        jQuery( document ).ready(function() {

            jQuery('#order-input-search').select2({
                ajax: {
                    url: '{{url("/api/shop/search_products")}}', //'https://rflbestbuy.com/api/shop/search_products',
                    delay: 350,
                    data: function (params) {
                        var query = {
                            keyword: params.term,
                        }

                        return query;
                    },
                    processResults: function (data) {

                        const products = data.products.data.map(function(item){

                            return {
                                id: item.id,
                                text: item.title
                            }
                        })

                        return {
                            results: products
                        };
                    }

                },
                minimumInputLength: 3,
                allowClear: true,
            })

            jQuery('#order-input-search').change(function(){
                cartProductId = jQuery(this).val()
                console.log('ss')
                addToCart()
            })


            jQuery("#go-to-add-product-button").click(function(){
                const inputId = jQuery(".order-input-number");
                inputId.css('display',inputId[0].style.display == 'none' ? 'block' : 'none');

                const productAdd = jQuery(".add-new-product");

                console.log(productAdd)

                productAdd.css('display',productAdd[0].style.display == 'none' ? 'block' : 'none');

            })

            jQuery("#add-product-button").click(function(){

                const title = jQuery("#product-title").val(),
                    sku = jQuery("#product-sku").val(),
                    code = jQuery("#product-code").val(),
                    short = jQuery("#product-short").val(),
                    price = jQuery("#product-price").val();

                const apiURI = `{{route('order.create.product')}}`

                jQuery.post(apiURI,{ title,sku,code,price,short_details: short,"_token": "{{ csrf_token() }}" },function(data){

                    cartProductId = data.id

                    addToCart()

                    jQuery("#product-title").val('')
                    jQuery("#product-sku").val('')
                    jQuery("#product-code").val('')
                    jQuery("#product-short").val('')
                    jQuery("#product-price").val('')

                }).fail(function(){

                    console.log('product not added..')

                });

            })



        });

    </script>
*/ ?>

    <style>
        .alert-soft-info, .alert-soft-info:hover {
            color: #31708f;
            background-color: #d9edf7 !important;
            border-color: #bce8f1;
        }

    </style>
@endpush
