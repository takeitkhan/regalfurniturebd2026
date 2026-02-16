@extends('frontend.layouts.app')

@section('content')

    <?php $tksign = '&#2547; '; ?>
    <div class="main-container container">
        <ul class="breadcrumb">
            <li><a href="#"><i class="fa fa-home"></i></a></li>
            <li><a href="#">Shopping Cart</a></li>
        </ul>

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

        <div class="reloader">
            <div id="content" class="col-sm-12">
                <h2 class="title">Payment Canceled</h2>

                @include('frontend.common.message_handler')

                <?php
                Session::forget('cart');
                Session::forget('user_details');
                Session::forget('payment_method');
                ?>

            </div>
        </div>
    </div>
@endsection
@section('cusjs')
@endsection