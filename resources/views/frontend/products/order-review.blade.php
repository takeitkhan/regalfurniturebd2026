@extends('frontend.layouts.app')

@section('content')
    <?php $data = Session::all(); ?>

    <?php $tksign = '&#2547; '; ?>

    <?php
    if (Session::has('my_coupon')) {
        $get_discount = Session::get('my_coupon');
        $discount = $get_discount['coupon_amount'];

    }else{
        $get_discount = false;
        $discount = 0;
    }
    //dump(round(8800.55))

    ?>
    <section class="breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-md-9">
                    <div class="breadcrumb-warp">
                        <div class="breadcrumb-one">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Order Review</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!--breadcrumb-area end  -->
    <div class="main-container container">
        <div class="row">

            <div class="col-sm-9">
                <br>
                <div class="card card_258">
                    <div class="card-header card-header_258">
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
                            <br>
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
                                        
                                        
                                        <script>
                                            dataLayer.push({
                                              'event': 'checkout',			//used for creating GTM trigger
                                              'ecommerce': {
                                                  'checkout': {
                                                    'actionField': {'step': 1},
                                                  'products': [{
                                                    'id': {{$product_info->id}},
                                                    'name': '{{$product_info->title}}',
                                                    'price': '{{$product['purchaseprice']}}',
                                                    'brand': 'Regal',
                                                    'category': '',
                                                    'variant': '',
                                                    'dimension1': '',
                                                    'position': 0,
                                                    'quantity': {{$product['qty']}}
                                                  }]
                                                }
                                              }
                                            });
                                       </script>


                                    @endforeach


                                    <tr>
                                        <th scope="row" colspan="4" class="text-left">Total Products</th>
                                        <td class="text-right">
                                            @if(!empty($totalqty))
                                                {{ array_sum($totalqty) }}
                                            @endif
                                        </td>
                                    </tr>
                                    @php
                                        //if (!empty($data['user_details'])) {
                                             //dump($data['user_details']);
                                        //}
                                        $total_price = array_sum($totalprice);
                                        //dd($total_price);
                                        $grand_total = $total_price + $data['user_details']['deliveryfee'] - $discount;
                                    @endphp
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
                                            {{ $tksign . $data['user_details']['deliveryfee'] }}
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
        
        <?php
        // $data = Session::all();
        // if (!empty($data['cart'])) {
        //     dump($data['cart']);
        // }
        // if (!empty($data['user_details'])) {
        //     dump($data['user_details']);
        // }

        // if (!empty($data['payment_method'])) {
        //     dump($data['payment_method']);
        // }
        ?>
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