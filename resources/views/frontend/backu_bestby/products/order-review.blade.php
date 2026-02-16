@extends('frontend.layouts.app')

@section('content')
    <?php $data = Session::all(); //dump($data); ?>

    <?php $tksign = '&#2547; '; ?>

    <?php
    if (Session::has('my_coupon')) {
        $get_discount = Session::get('my_coupon');
        $discount = $get_discount['coupon_amount'];

    } else {
        $get_discount = false;
        $discount = 0;
    }
    //dump(round(8800.55))

    ?>

    <!--breadcrumb-area end  -->

    <div class="main-container container">
        <ul class="breadcrumb">
            <li><a href="{{ url('/') }}"><i class="fa fa-home"></i></a></li>
            <li> <a href="{{ url('/view_cart') }}">Shopping Cart</a></li>
            <li> <a href="{{ url('/checkout/address') }}">Delivery Address</a></li>
            <li> <a href="{{ url('/checkout/payment_method') }}">Payment Method</a></li>
            <li>Order Review</li>
        </ul>
        <div class="row">

            <div class="col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="title">
                            <span style="margin-right: 7px; font-size: 18px;">
                                <i class="fa fa-shopping-cart"></i></span>
                            Order Review
                        </h4>
                        <p>
                            Review your order properly before hitting
                            <b>

                                @if(!empty($data['payment_method']['payment_method']) && $data['payment_method']['payment_method'] == 'cash_on_delivery')
                                    Confirm & Get Invoice
                                @else
                                    Pay Now
                                @endif
                            </b>
                            button down below.
                        </p>

                        @include('frontend.common.message_handler')

                        {{--@if(Session::has('cart') && Session::has('user_details') && Session::has('payment_method'))--}}
                    </div>
                    <div class="panel-body">
                        <div class="panel-body-table-area">
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th scope="col">Product</th>
                                    <th scope="col">Details</th>
                                    <th scope="col">Qty</th>
                                    <th scope="col">Unit Price</th>
                                    <th scope="col" class="text-right">Total</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(!empty($cartproducts))
                                    @foreach($cartproducts as $product)
                                        <?php
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
                                            <td class="text-left" width="80px">
                                                <div class="input-group btn-block quantity text-center">
                                                    {{ $product['qty'] }}
                                                    </span>

                                                </div>
                                            </td>
                                            <td class="text-right"
                                                width="160px">{{ $tksign . $product['purchaseprice'] }}</td>
                                            <td class="text-right"
                                                width="160px">{{ $tksign }} {{ number_format($product['purchaseprice'] * $product['qty'])  }}</td>
                                        </tr>

                                    @endforeach


                                    <tr>
                                        <th scope="row" colspan="4" class="text-left">Total Products</th>
                                        <td class="text-right">
                                            @if(!empty($totalqty))
                                                {{ array_sum($totalqty) }}
                                            @endif
                                        </td>
                                    </tr>
                                    <?php

                                    $total_price = array_sum($totalprice);

                                    if ($total_price > $paymentsetting->decidable_amount) {
                                        $deliverycharge = 0;
                                    } else {
                                        if (!empty($data['user_details']) && ($data['user_details']['district'] === 'Dhaka')) {
                                            $deliverycharge = $paymentsetting->inside_dhaka_fee;
                                        } else {
                                            $deliverycharge = $paymentsetting->outside_dhaka_fee;
                                        }
                                    }
                                    $grand_total = $total_price + $deliverycharge - $discount;

                                    Session::put('payment_method.grand_total', $grand_total);
                                    //dump($deliverycharge);

                                    ?>
                                    <tr>
                                        <th scope="row" colspan="4" class="text-left">Total Price</th>
                                        <td class="text-right">
                                            @if(!empty($totalprice))
                                                {{ $tksign . number_format(array_sum($totalprice)) }}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row" colspan="4" class="text-left">Discount Price</th>
                                        <td class="text-right">
                                            {{$tksign . number_format($discount)}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row" colspan="4" class="text-left">
                                            Delivery Charge
                                            @if(!empty($data['user_details']) && ($data['user_details']['district'] == 'Dhaka'))
                                                (Inside Dhaka)
                                            @else
                                                (Outside Dhaka)
                                            @endif
                                        </th>
                                        <td class="text-right">
                                            @if($total_price > $paymentsetting->decidable_amount)
                                                Free Delivery!
                                            @else
                                                @if(!empty($data['user_details']) && ($data['user_details']['district'] === 'Dhaka'))
                                                    {{ $tksign }} {{ $paymentsetting->inside_dhaka_fee }}
                                                @else
                                                    {{ $tksign }} {{ $paymentsetting->outside_dhaka_fee }}
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row" colspan="4" class="text-left">Payment Method</th>
                                        <td class="text-right">
                                            <?php
                                            $data = Session::all();
                                            if (!empty($data['payment_method'])) { ?>
                                            @if($data['payment_method']['payment_method'] == 'cash_on_delivery')
                                                Cash On Delivery
                                            @elseif($data['payment_method']['payment_method'] == 'bkash')
                                                bKash
                                            @elseif($data['payment_method']['payment_method'] == 'debitcredit')
                                                Debit/Credit Card
                                            @endif
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <tr class="bg-gr">
                                        <th scope="row" colspan="4" class="text-left">Grand Total</th>
                                        <td class="text-right" style="font-weight: bold; font-size: 16px;">
                                            {{ $tksign . number_format($grand_total) }}
                                        </td>
                                    </tr>

                                @endif
                                </tbody>
                            </table>
                            <div class="table-box">
                                {{--<a class="btn pull-left btn-one colorwhite" target="_blank" href="{{ url('/') }}">--}}
                                {{--<i class="fa fa-arrow-left"></i>--}}
                                {{--More Buying--}}
                                {{--</a>--}}
                            </div>


                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="pull-right" style="margin: 15px 0px;">
                        <a href="{{ url('checkout/pay_now') }}"
                           id="confirm_order"
                           class="btn btn-back-two">
                            @if(!empty($data['payment_method']['payment_method']) && $data['payment_method']['payment_method'] == 'cash_on_delivery')
                                Confirm & Get Invoice
                            @else
                                Pay Now <i class="fa fa-arrow-right"></i>
                            @endif
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-sm-3"></div>

        </div>
    </div>

@endsection
@section('cusjs')
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $.noConflict();

        });


    </script>
    <style type="text/css">
        tr.CartProduct td {
            color: #666;
        }

        tr.CartProductBlue td {
            color: #FFF;
        }
    </style>
@endsection