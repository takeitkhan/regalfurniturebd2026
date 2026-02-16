@extends('frontend.layouts.app')

@section('content')

    <?php $tksign = '&#2547; '; ?>
    <div class="main-container container">
        <ul class="breadcrumb">
            <li><a href="#"><i class="fa fa-home"></i></a></li>
            <li><a href="#">Shopping Cart</a></li>
        </ul>

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
                        <div class="table-responsive form-group">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <td class="text-center">Image</td>
                                    <td class="text-left">Product Information</td>
                                    <td class="text-left" style="width: 70px !important;">Quantity</td>
                                    <td class="text-right">Unit Price</td>
                                    <td class="text-right">Total</td>
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
                                        <td class="text-center" width="5%">
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
                                                <b>SKU:</b>
                                                {{ $product_info->sku }}
                                            </div>
                                            <br/>
                                            <div>
                                                <b>Short Details:</b>
                                                {{ !empty($product_info->short_description) ? $product_info->short_description : null }}
                                            </div>
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
                                                <span class="input-group-btn">
                                                    <a href="javascript:void(0)"
                                                       class="btn btn-danger"
                                                       data-toggle="tooltip"
                                                       title="Remove"
                                                       onclick="remove_cart_item({{ $product['item']['productid'] . ', ' . $product['item']['productcode'] }})">
                                                        <i class="fa fa-times-circle"></i>
                                                    </a>
                                                </span>

                                            </div>
                                        </td>
                                        <td class="text-right"
                                            width="160px">{{ $tksign . $product['purchaseprice'] }}</td>
                                        <td class="text-right"
                                            width="160px">{{ $tksign }} {{ number_format($product['purchaseprice'] * $product['qty'])  }}</td>
                                    </tr>

                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="panel-group" id="accordion">
                            <div class="panel panel-default">
                                {{ Form::open(array('url' => '/apply_coupon_voucher', 'method' => 'post', 'value' => 'PATCH', 'id' => 'cleaning_details')) }}
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <i class="fa fa-ticket"></i> Do you Have a Coupon or Voucher?
                                    </h4>
                                </div>
                                <div class="panel-body row">
                                    <div class="cpn">
                                        <div class="col-sm-6 ">
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
                                                 {{ Form::submit('Apply Coupon', ['id'=> 'apply_coupon', 'class' => 'btn btn-primary', 'name' => 'apply_coupon' ]) }}
                                            </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="cpn">
                                        <div class="col-sm-6">
                                            <div class="input-group">
                                                {{ Form::text('voucher_code', $voucher_valu, ['class' => 'form-control', 'id'=> 'voucher_code','placeholder' => 'Enter gift voucher code here...']) }}
                                                <span class="input-group-btn">
                                                 {{ Form::submit('Apply Voucher', ['id'=> 'apply_voucher', 'class' => 'btn btn-primary', 'name' => 'apply_voucher' ]) }}
                                            </span>
                                            </div>
                                        </div>


                                    </div>
                                </div>
                                {{ Form::close() }}
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-4 col-sm-offset-8">
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

                                    {{--<tr>--}}
                                    {{--<td class="text-right">--}}
                                    {{--<strong>Flat Shipping Rate:</strong>--}}
                                    {{--</td>--}}
                                    {{--<td class="text-right">$4.69</td>--}}
                                    {{--</tr>--}}
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
                        <div class="buttons">
                            <div class="pull-right" style="margin: 15px 0px;">
                                <a href="{{ url('checkout/address') }}" class="btn btn-back-two id=" confirm_order">
                                Confirm Order
                                </a>
                            </div>
                            <div class="pull-right" style="margin: 15px 15px;">
                                <a href="javascript:void(0)" class="btn btn-back-one disabled" id="update_cart">
                                    <i class="fa fa-undo"></i> Update Cart
                                </a>
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
