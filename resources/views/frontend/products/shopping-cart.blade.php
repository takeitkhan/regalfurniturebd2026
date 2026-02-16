@extends('frontend.layouts.app')


@section('content')

    <?php $tksign = '&#2547; '; ?>


    <section class="breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="breadcrumb-warp">
                        <div class="breadcrumb-one">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Shopping Cart</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <div class="main-container container">
        <?php

        //dump(Session::get('cart'));

        if (Session::has('my_coupon')) {
            $get_discount = Session::get('my_coupon');
            $discount = $get_discount['coupon_amount'];

        } else {
            $get_discount = false;
            $discount = 0;
        }
        //dump(round(8800.55))

        ?>

        <div class="row">
            <div class="reloader">
                <div id="content" class="col-sm-12">
                    <h2 class="title">Shopping Cart</h2>
                    @if(!empty($cartproducts))
                        <div class="table-responsive reg-hab form-group">
                            <table class="table table-bordered table-bordered_258">
                                <thead>
                                <tr>
                                    <td class="text-center" style="width: 10%;">Image</td>
                                    <td class="text-left" style="width: 55%;">Product Information</td>
                                    <td class="text-left" style="width: 7%;">Quantity</td>
                                    <td class="text-center" style="width: 8%;">Delete</td>
                                    <td class="text-center" style="width: 10%;">Unit Price</td>
                                    <td class="text-center" style="width: 10%;"> <strong>Total</strong></td>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($cartproducts as $product)
                                
                                    <?php
                                    // dump($product);

                                    $product_info = App\Product::find($product['item']['productid']);
                                    $totalqty[] = $product['qty'];
                                    $totalprice[] = $product['purchaseprice'] * $product['qty'];


                                    $first_image = \App\ProductImages::where('main_pid', $product_info->id)->where('is_main_image', 1)->get()->first();

                                    if (!empty($first_image->full_size_directory)) {
                                        $img = url($first_image->full_size_directory);
                                    } else {
                                        $img = url('storage/uploads/fullsize/2019-01/default.jpg');
                                    }
                                    ?>
                                    <tr>
                                        <td class="text-center" width="">
                                            <a href="{!! product_seo_url($product_info->seo_url, $product_info->id) !!}">
                                                <img width="70px"
                                                     src="{{ $img }}"
                                                     alt="{{ product_title($product['item']['productid']) }}"
                                                     title="{{ product_title($product['item']['productid']) }}"
                                                     class="img-thumbnail"/>
                                            </a>
                                        </td>
                                        <td class="text-left">
                                            <a href="{!! product_seo_url($product_info->seo_url, $product_info->id) !!}">
                                                <b>{{ product_title($product['item']['productid']) }}</b>
                                            </a>
                                            <br/>
                                            <div>
                                                <b>Item Name:</b>
                                                {{ $product_info->sub_title }}
                                            </div>

                                            <div>
                                                <b>Item code:</b>
                                                {{ $product_info->product_code }}
                                            </div>

                                            <div>
                                                <b>SKU:</b>
                                                {{ $product_info->sku }}
                                            </div>

{{--                                            <p><b>Item Name: </b> {{ $item->products->sub_title }}</p>--}}
{{--                                            <p><b>Item code: </b> {{ $item->products->product_code }}</p>--}}
                                            
{{--                                            <div class="short_detail_one">--}}
{{--                                                <b>Short Details:</b>--}}
{{--                                                {{ !empty($product_info->short_description) ? $product_info->short_description : null }}--}}
{{--                                            </div>--}}
                                        </td>

                                        <td class="text-left" width="130px">
                                          
                                            <div class="input-group btn-block quantity">

                                                <input name="qty[]"
                                                       type="number"
                                                       min="1"
                                                       id="qty_change_checker"
                                                       value="{{ $product['qty'] }}"
                                                       data-productcode="{{ $product['item']['productcode'] }}"
                                                       data-productid="{{ $product['item']['productid'] }}"
                                                       data-price="{{ $product['item']['purchaseprice'] }}"
                                                       class="form-control"/>
                                                

                                            </div>
                                        </td>

                                        <td class="text-center" width="130px">
                                            <div class="">
                                                <span class="input-group-btn">
                                                    <a href="javascript:void(0)"
                                                       class="btn btn-sm btn-dekleteing dengerdsd"
                                                       title="Remove"
                                                       data-id="{{ $product['item']['productid'] }}"
                                                       data-code="{{ $product['item']['productcode'] }}"
                                                       data-title="{{ product_title($product['item']['productid']) }}"
                                                       data-price="{{ $product['item']['purchaseprice'] }}"
                                                       data-color="{{ $product['item']['size_colo'] }}"
                                                       data-qty="{{ $product['item']['qty'] }}"
                                                       onclick="remove_cart_item(this)">
                                                        <i class="fa fa-times"></i>
                                                    </a>
                                                </span>
                                            </div>
                                        </td>

                                        <td class="text-center"
                                            width="160px">{{ $tksign . $product['purchaseprice'] }}</td>
                                        <td class="text-center" width="160px"><strong>{{ $tksign }} {{ number_format($product['purchaseprice'] * $product['qty'])  }}</strong></td>
                                    </tr>

                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="panel-group" id="accordion">
                            
                                {{ Form::open(array('url' => '/apply_coupon_voucher', 'method' => 'post', 'value' => 'PATCH', 'id' => 'cleaning_details')) }}
                                    <div class="cpn row justify-content-end">

                                        <div class="col-md-6 ">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h4 class="card-header_voucher ">
                                                            <span><i class="fa fa-ticket"></i> </span> Do you have any coupon?
                                                        </h4>
                                                    <div class="input-group">
                                                        <?php
                                                        if (isset($get_discount)) {
                                                            if ($get_discount['coupon_type'] == 'Coupon') {

                                                                $coupon_valu = $get_discount['coupon_code'];
                                                                $voucher_valu = null;

                                                            } elseif ($get_discount['coupon_type'] == 'Voucher') {
                                                                $voucher_valu = $get_discount['coupon_code'];
                                                                $coupon_valu = null;

                                                            } else {
                                                                $coupon_valu = null;
                                                                $voucher_valu = null;
                                                            }
                                                        } else {
                                                            $coupon_valu = null;
                                                            $voucher_valu = null;
                                                        }
                                                        ?>
                                                       
                                                        
                                                        {{ Form::text('coupon_code', $coupon_valu, ['class' => 'form-control', 'id'=> 'coupon_code','placeholder' => 'Enter your coupon here...']) }}
                                                        <span class="input-group-btn">
                                                         {{ Form::submit('Apply Coupon', ['id'=> 'apply_coupon', 'class' => 'btn  bordder-reades', 'name' => 'apply_coupon' ]) }}
                                                    </span>
                                                    </div>
                                                     <div class="sob-total-area">
                                                            <table class="table table-bordered table-bordered_one">
                                                                <tbody>
                                                                <tr>
                                                                    <td class="text-right">
                                                                        <strong>Sub-Total:</strong>
                                                                    </td>
                                                                    <td class="text-right">
                                                                        @if(!empty($totalprice))
                                                                            {{ $tksign }} {{ number_format(array_sum($totalprice)) }}
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="text-right">
                                                                        <strong>Discount</strong>
                                                                    </td>
                                                                    <td class="text-right">{{ $tksign.number_format($discount) }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="text-right">
                                                                        <strong>Total:</strong>
                                                                    </td>
                                                                    <td class="text-right">
                                                                        @if(!empty($totalprice))
                                                                            {{ $tksign }} {{ number_format(array_sum($totalprice)-$discount) }}
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{ Form::close() }}
                                    </div>
                                    <br>
                                    <div class="card card_258">
                                        <div class="card-footer">
                                    <div class="buttons buttons_one">
                                        <div class="pull-right">
                                            <a href="{{ url('checkout/address') }}" class="btn btn-back-two" id=" confirm_order">
                                            Confirm Order <span><i class="fa fa-long-arrow-right"></i></span>
                                            </a>
                                        </div>
                                        <div class="pull-right" style="margin: 0px 15px;">
                                            <a href="javascript:void(0)" class="btn btn-back-one disabled" id="update_cart">
                                                <i class="fa fa-undo"></i> Update Cart
                                            </a>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                                @else
                                    <h3>Opps... You have not added any product on your cart yet.</h3>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>


@endsection

@section('cusjs')
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $.noConflict();

            $(document).on('keyup change', 'input[name="qty[]"]', function () {
                jQuery('#update_cart').removeClass("disabled");
                jQuery('#confirm_order').removeAttr("href");
                jQuery("#confirm_order").removeClass('btn-two');
                jQuery("#confirm_order").addClass('disabled');
                //jQuery("#update_cart").addClass('disabled');
            });

            $("#update_cart").on('click', function () {
                var dataArray = [];
                $('input[name="qty[]"]').each(function () {
                    var qty = $(this).val();
                    var id = $(this).data('productid');
                    var code = $(this).data('productcode');

                    var data = {
                        'qty': qty,
                        'productid': id,
                        'productcode': code,
                    };
                    dataArray.push(data);
                }).promise().done(function () {
                    var m = JSON.stringify(dataArray);

                    $.ajax({
                        url: baseurl + '/update_qty',
                        type: 'POST',
                        dataType: "json",
                        data: {cart: m},
                        success: function (data) {
                            location.reload();
                            update_mini_cart();

                        },
                        error: function () {
                            //alert(data);
                        }
                    });
                });
            });
        });
    </script>
@endsection
