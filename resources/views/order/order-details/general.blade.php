<div class="col-md-2">
    <div class="row">
        <h4 class="box-title title_bb"><b>Order Information</b></h4>
        <p>
            <strong>Order ID:</strong>
            {{ $order_master->id ?? NULL }}
        </p>
        <h4 class="box-title title_bb"><b>Customer Information</b></h4>
        <p>
            <strong>Name:</strong>
            {{ $order_master->customer_name ?? NULL }}
        </p>
        <p>
            <strong>Phone:</strong>
            <a href="tel: {{ $order_master->phone }}">
                {{ $order_master->phone ?? NULL }}
            </a>
        </p>
        <p>
            <strong>Email:</strong>
            <a href="emailto: {{ $order_master->phone }}">
                {{ $order_master->email ?? NULL }}
            </a>
        </p>
        <p>
            <strong>Emergency Phone:</strong>
            <a href="tel: {{ $order_master->emergency_phone }}">
                {{ $order_master->emergency_phone ?? NULL }}
            </a>
        </p>
        <br/>
        <br/>

        <h4 class="box-title title_bb"><b>Customer Address</b></h4>
        <p>{{ $order_master->address ?? NULL }}</p>
        <p>{{ $order_master->district ?? NULL }}</p>
    </div>
</div>

<div class="col-md-10">
    <section class="invoice">

        <div class="row">
            <div class="col-xs-12">
                <h2 class="page-header">
                    <i class="fa fa-globe"></i>
                    <b>Regal Furniture Ltd.</b>
                    <small class="pull-right">
                        PRAN Center, 105 Middle Badda, Dhaka - 1212, Bangladesh
                    </small>
                </h2>
            </div>

        </div>

        <div class="row invoice-info">
            <div class="col-sm-4 invoice-col">
                From
                <address>
                    <strong>Regal Furniture Ltd.</strong><br>
                    PRAN Center, 105 Middle Badda<br>
                    Dhaka - 1212, Bangladesh<br>
                    Phone: 09613737777<br>
                    Email: info@regalfurniturebd.com
                </address>
            </div>

            <div class="col-sm-4 invoice-col">
                <b>Order Status:</b> {{ $order_master->order_status ?? NULL }}<br>
                <b>Order ID:</b> {{ $order_master->id ?? NULL }}<br>
                <b>Order Date:</b> {{ $order_master->order_date ?? NULL }}<br>
                <b>Payment Method:</b> {{ $order_master->payment_method ?? NULL }}<br>
                <b>Payment Status:</b> {{ $order_master->payment_term_status ?? NULL }}
            </div>
            <div class="col-sm-4 invoice-col">
                <a target="_blank"
                   class="btn btn-success"
                   href="{{ env('FRONTEND_URL','https://regalfurniturebd.com')}}/order?order_random={{ request()->id }}&order_key={{ $line->secret_key }}">
                    View Complete Invoice
                </a>
            </div>

        </div>


        <div class="row">
            <div class="col-xs-12 table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
{{--                        <th>Available Stock</th>--}}
                        <th>SL No</th>
                        <th>Product</th>
                        <th>Product Code</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Discount</th>
                        <th>Subtotal</th>
                        <th>Approximate arrive time</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        $subTotal = [];
                        $totalDiscount = [];
                    @endphp
                    @foreach($order_details as $key => $order)
                        <tr>
{{--                            <td>--}}
{{--                                <!--Later after stock management-->--}}
{{--                            </td>--}}
                            <td>{{++$key}}</td>
                            @php
                                //dump($order);
                                $itemJeson =  json_decode($order->item_jeson);
                                $product_id = $itemJeson->productid ?? null;
                                $get_product_info = $product_id ? App\Models\Product::where('id', $product_id)->first() : null;
                            @endphp
                            <td>
                                <a target="_blank" href="https://regalfurniturebd.com/product/{{$get_product_info->seo_url}}">
                                    {{ $order->product_name ?? NULL }}
                                </a>
{{--                                <br> {{$get_product_info->sub_title}} <br>--}}
                                <br>{{ $itemJeson->sub_title ?? null }}
                            </td>
                            <td>{{ $order->product_code ?? NULL }}</td>
                            <td>{!! $tksign !!}{{ round($order->local_purchase_price) ?? 0 }}</td>
                            <td>{{ $order->qty ?? NULL }}</td>
                            @php
                                $discountAmount =round( $order->local_selling_price-$order->local_purchase_price) ?? 0;
                                $subTotal[] = round($order->qty * $order->local_purchase_price-$discountAmount)
                            @endphp
                            <td>{!! $tksign !!}{{ $totalDiscount []= $discountAmount }} ({{$order->discount ?? 0}}%) </td>
                            <td>{!! $tksign !!}{{  round($order->qty * $order->local_purchase_price - $discountAmount) }}</td>
                            <td>{{$order->product_arrive_times}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

        </div>

        <div class="row">

            <div class="col-xs-6">

            </div>
            <div class="col-xs-6">
                {{--                            <p class="lead">Amount Due 2/22/2014</p>--}}
                <div class="table-responsive">
                    <table class="table">
                        <tbody>
                        <tr>
                            <th style="width:50%">Subtotal:</th>
                            <td>{!! $tksign !!}{{ array_sum($subTotal) ?? 0 }}</td>
                        </tr>
{{--                        <tr>--}}
{{--                            <th>Discount</th>--}}
{{--                            <td>{!! $tksign !!}{{ array_sum($totalDiscount) ?? 0 }}</td>--}}
{{--                        </tr>--}}
                        <tr>
                            @php
                               $couponDiscount = $order_master->coupon_discount ?? 0;
                            @endphp
                            <th>Coupon Discount</th>
                            <td>
                                @if($order_master->coupon_type == 'Percentage')
                                    {{ $order_master->coupon_discount ?? 0 }}%
                                    @php
                                        $couponDiscount = array_sum($subTotal)*$couponDiscount/100;
                                    @endphp
                                @else
                                    {!! $tksign !!}{{ $couponDiscount }}
                                @endif

                            </td>
                        </tr>
                        <tr>
                            <th>Delivery Charge:</th>
                            <td>{!! $tksign !!}{{ $order_master->delivery_fee ?? 0 }}</td>
                        </tr>
                        <tr>
                            <th>Grand Total:</th>
                            <td>{!! $tksign !!}{{ (array_sum($subTotal)-$couponDiscount) + $order_master->delivery_fee }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>


        {{--                    <div class="row no-print">--}}
        {{--                        <div class="col-xs-12">--}}
        {{--                            <a href="invoice-print.html" target="_blank" class="btn btn-default"><i--}}
        {{--                                    class="fa fa-print"></i> Print</a>--}}
        {{--                            <button type="button" class="btn btn-success pull-right"><i class="fa fa-credit-card"></i>--}}
        {{--                                Submit Payment--}}
        {{--                            </button>--}}
        {{--                            <button type="button" class="btn btn-primary pull-right" style="margin-right: 5px;">--}}
        {{--                                <i class="fa fa-download"></i> Generate PDF--}}
        {{--                            </button>--}}
        {{--                        </div>--}}
        {{--                    </div>--}}
    </section>
</div>
