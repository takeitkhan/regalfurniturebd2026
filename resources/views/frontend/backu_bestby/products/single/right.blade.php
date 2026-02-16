<aside class="col-sm-4 col-md-3 content-aside" id="column-left">
    <div class="right-single-bar">
        <div class="having-area">
            @php
                $order_call = App\Setting::first()->order_phone;

            @endphp
            @if($order_call != null)
                <div class="dilie-det">
                    <p><span><i class="fa fa-volume-control-phone"></i></span>Having trouble placing the order?
                    </p>


                    <p>Please Call:{{ $order_call }}</p>
                </div>

            @endif

            <div class="delivery sub-site-title">
                <h3>Delivery Option</h3>
                @if($pro->express_delivery == 'on')
                    <img src="{{ url('public/img/sub-site-img.png') }}" alt="">
                    <div class="one-dt">
                        <button class="btn" type="button" class="btn btn-primary" data-toggle="modal"
                                data-target=".bd-example-modal-lg">View Details
                        </button>
                    </div>
                    <!-- modal strat -->
                    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog"
                         aria-labelledby="myLargeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">
                                        Delivery & Installation details
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p>Usually delivered in</p>
                                    <p><strong>7-8 days</strong></p>
                                    <p>Enter pincode for exact delivery dates/charges</p>
                                    <p><strong>Installation Details</strong></p>
                                    <p>This product doesn't require installation</p>
                                    <p><strong>Flipkart Assured</strong></p>
                                    <p>The 'Flipkart Assured' badge is a seal of quality and speed. Products
                                        with this badge meet our extensive quality & packaging guidelines along
                                        with having faster delivery times.</p>
                                </div>
                                <div class="modal-footer">
                                    <p>*Free Delivery on F-Assured purchases over $500</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <p>Delivery Area : {{ $pro->delivery_area }}</p>
                <p>Delivery Times : {{ $pro->delivery_time }}</p>
            </div>
            @if($pro->pro_warranty != null)
            <div class="sub-site-title">
                <h3>Warranty:</h3>
                <p>{{ $pro->pro_warranty }}</p>
            </div>
            @endif
            <div class="sub-site-title">

                @php
                    $pyment_setting = App\PaymentSetting::first();



                @endphp
                <h3>Payment Methods:</h3>

                {!! ($pyment_setting->bkash_active != 0)? '<p>-bkash payments</p>': null !!}
                {!! ($pyment_setting->rocket_active != 0)? '<p>-rocket payments</p>': null !!}
                {!! ($pyment_setting->mobilebanking_active != 0)? '<p>-other mobile banking payments</p>': null !!}
                {!! ($pyment_setting->debitcredit_active != 0)? '<p>-Debit/ Credit card payments</p>': null !!}
                {!! ($pyment_setting->citybank_active != 0)? '<p>-City Bank</p>': null !!}
                {!! ($pyment_setting->cashondelivery_active != 0)? '<p>-Cash On Delivery</p>': null !!}



            </div>
        </div>
    </div>

    <div class="module product-simple">
        @include('frontend.home_latestproducts')
    </div>
    <div class="module banner-left hidden-xs ">
        <div class="banner-sidebar banners">
            <div>
                <a title="Banner Image" href="#">
                    <?php $policy_widget_1 = dynamic_widget($widgets, ['id' => 12]); ?>
                    {!! $policy_widget_1 !!}
                </a>
            </div>
        </div>
    </div>
    <div class="module banner-left hidden-xs ">
        <div class="banner-sidebar banners">
            <div>
                <a title="Banner Image" href="#">
                    <?php $policy_widget_1 = dynamic_widget($widgets, ['id' => 13]); ?>
                    {!! $policy_widget_1 !!}
                </a>
            </div>
        </div>
    </div>
</aside>