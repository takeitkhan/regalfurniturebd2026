@extends('frontend.layouts.app')

@section('content')
    <?php $tksign = '&#2547; '; ?>

    <div class="main-container container">
        <?php $tksign = '&#2547; '; ?>


        <ul class="breadcrumb">
            <li><a href="{{ url('/') }}"><i class="fa fa-home"></i></a></li>
            <li> <a href="{{ url('/view_cart') }}">Shopping Cart</a></li>
            <li> <a href="{{ url('/checkout/address') }}">Delivery Address</a></li>
            <li>Payment Method</li>
        </ul>




        <div class="row">
            @if(Session::has('cart') && Session::has('user_details'))
                <div class="col-md-12">
                    <h2 class="title">Payment Method</h2>

                    <div class="row">
                        <div class="col-md-9">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4>{{ $tksign }} Payment Method</h4>
                                </div>
                                {{ Form::open(array('url' => '/checkout/store_payment_method', 'method' => 'post', 'value' => 'PATCH', 'id' => 'payment_method')) }}
                                <div class="panel-body">
                                    @include('frontend.common.message_handler')

                                    <div class="please-panel">
                                        <p>
                                            Please select the preferred Payment method to use on this order.
                                        </p>
                                        <p>
                                            All transactions are secure and encrypted, and we neverstore. To learn more,
                                            please view our privacy policy.
                                        </p>
                                    </div>

                                    <div class="redio-btm-area_one">

                                        @if($paymentsetting->citybank_active == TRUE)
                                            <div class="debit-single">

                                                {{ Form::radio('payment_method', 'citybank', FALSE, ['id' => 'test2', 'class' => 'radio', 'required']) }}

                                                <label for="test2">
                                                    <strong>
                                                        City Bank
                                                    </strong>
                                                </label>
                                                <h2>
                                                    @if($paymentsetting->image_citybank)
                                                      <img src="{{ $paymentsetting->image_citybank }}" alt="City Bank"/>
                                                    @endif

                                                </h2>
                                                <span>(You can use any bank debit & credit card over here)</span>
                                            </div>
                                        @endif


                                        @if($paymentsetting->rocket_active == TRUE)
                                            <div class="debit-single">

                                                {{ Form::radio('payment_method', 'rocket', FALSE, ['id' => 'test3', 'class' => 'radio', 'required']) }}

                                                <label for="test3">
                                                    <strong>
                                                        Rocket
                                                    </strong>
                                                </label>
                                                <h2>
                                                    @if($paymentsetting->image_rocket)
                                                    <img src="{{ $paymentsetting->image_rocket }}" alt="Rocket"/>
                                                    @endif

                                                </h2>
                                                <span>(You can use any Rocket number)</span>
                                            </div>
                                        @endif
                                        @if($paymentsetting->bkash_active == TRUE)
                                            <div class="debit-single">

                                                {{ Form::radio('payment_method', 'bkash', FALSE, ['id' => 'test4', 'class' => 'radio', 'required']) }}

                                                <label for="test4">
                                                    <strong>
                                                        bKash
                                                    </strong>
                                                </label>
                                                <h2>
                                                    @if($paymentsetting->image_bkash)
                                                    <img src="{{ $paymentsetting->image_bkash }}" alt="bKash"/>
                                                    @endif

                                                </h2>
                                                <span>(You can use any bKash number)</span>
                                            </div>
                                        @endif
                                        @if($paymentsetting->mobilebanking_active == TRUE)
                                            <div class="debit-single">

                                                {{ Form::radio('payment_method', 'mobilebanking', FALSE, ['id' => 'test5', 'class' => 'radio', 'required']) }}

                                                <label for="test5">
                                                    <strong>
                                                        Mobile Banking
                                                    </strong>
                                                </label>
                                                <h2>
                                                    @if($paymentsetting->image_mobilebanking)
                                                    <img src="{{ $paymentsetting->image_mobilebanking }}" alt="Mobile Banking"/>
                                                    @endif

                                                </h2>
                                                <span>(You can use any bKash number)</span>
                                            </div>
                                        @endif

                                        @if($paymentsetting->debitcredit_active == TRUE)
                                            <div class="debit-single">

                                                {{ Form::radio('payment_method', 'debitcredit', FALSE, ['id' => 'test6', 'class' => 'radio', 'required']) }}

                                                <label for="test6">
                                                    <strong>
                                                        Debit or Credit Card
                                                    </strong>
                                                </label>
                                                <h2>
                                                    @if($paymentsetting->image_debitcredit)
                                                    <img src="{{ $paymentsetting->image_debitcredit }}"
                                                         alt="Debit or Credit Card"/>
                                                    @endif
                                                </h2>
                                                <span>(You can use any Debit or Credit Card)</span>
                                            </div>
                                        @endif
                                        @if($paymentsetting->cashondelivery_active == TRUE)
                                            <div class="debit-single">

                                                {{ Form::radio('payment_method', 'cash_on_delivery', FALSE, ['id' => 'test7', 'class' => 'radio', 'required']) }}

                                                <label for="test7">
                                                    <strong>
                                                        Cash on delivery
                                                    </strong>
                                                </label>
                                                <div>
                                                    @if($paymentsetting->image_cashondelivery)
                                                    <img src="{{ $paymentsetting->image_cashondelivery }}"
                                                        alt="Cash on delivery">
                                                    @endif
                                                </div>
                                                <span></span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input id="terms_check"
                                                   type="checkbox"
                                                   name="terms_check" {{ old('remember') ? 'checked' : ''}}>
                                            I have read and agree to the
                                            <a style="color: #0A70B9" href="#">
                                                <strong>
                                                    Terms & Conditions
                                                </strong>
                                            </a>
                                        </label>
                                    </div>

                                    <div class="form-group">
                                        <?php
                                        $data = Session::all();
                                        if (!empty($data['cart'])) {
                                            foreach ($data['cart']->items as $item) {
                                                $totalqty[] = $item['qty'];
                                                $totalprice[] = $item['purchaseprice'] * $item['qty'];
                                            }
                                        }
                                        $total_price = array_sum($totalprice);
                                        $deliverycharge = $data['user_details']['deliveryfee'];
                                        $grand_total = $total_price + $deliverycharge;
                                        ?>

                                        {{ Form::hidden('total_amount', $total_price) }}
                                        {{ Form::hidden('grand_total', $grand_total) }}
                                    </div>

                                </div>
                                <div class="panel-footer" style="overflow: hidden; border-top: none;">
                                    <div class="buttons carring-btn-gp">
                                        <div class="pull-left">
                                            <a href="{{ url()->previous() }}" class="btn btn-back-one ">
                                                Back
                                            </a>
                                        </div>
                                        <div class="pull-right">
                                            <button id="checkout_payment_method"
                                                    class="btn pull-right btn-two"
                                                    type="submit">
                                                Next
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                {{ Form::close() }}
                                <?php
//                                $data = Session::all();
//                                if (!empty($data['cart'])) {
//                                    dump($data['cart']);
//                                }
//                                if (!empty($data['user_details'])) {
//                                    dump($data['user_details']);
//                                }
//
//                                if (!empty($data['payment_method'])) {
//                                    dump($data['payment_method']);
//                                }
                                ?>
                            </div>
                        </div>
                        <div class="col-left col-sm-3">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title"><i class="fa fa-sign-in"></i> Create an Account or Login
                                    </h4>
                                </div>
                                <div class="panel-body bun-stp blcke">
                                    <a class="active" href="{{ url('checkout/address') }}">Guest Checkout</a>
                                    <a href="{{ url('login_now') }}">Returning Customer</a>
                                    <a href="{{ url('create_an_account') }}">Register Account</a>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
        </div>
        @else
            <h3>Opps... You have not added any product on your cart yet.</h3>
        @endif
    </div>

@endsection
@section('cusjs')
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $.noConflict();

            $('#checkout_payment_method').on('click', function () {
                if ($("#terms_check").prop('checked') == true) {
                    $('label.login-bar').css('border', '0px solid red');
                    $('label.login-bar').css('padding', '3px');
                } else {
                    $('label.login-bar').css('border', '1px solid red');
                    $('label.login-bar').css('padding', '3px');
                }
            });

        });
    </script>
    <style type="text/css">
        .checkbox label, .radio label {
            color: #666;
        }

        label.login-bar {
            padding: 3px;
        }
    </style>
@endsection