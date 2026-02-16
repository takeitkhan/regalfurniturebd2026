@extends('frontend.layouts.app')

@section('content')
    <?php $tksign = '&#2547; ';
    //dump($orders_master);
    $datalayerProducts = [];
    ?>

      <section class="breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="breadcrumb-warp">
                        <div class="breadcrumb-one">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Order History</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <div class="main-container container">
       <!--  <ul class="breadcrumb">
            <li><a href="#"><i class="fa fa-home"></i></a></li>
            <li><a href="#">Order History</a></li>
        </ul> -->

        <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-4" style="text-align: center;">
            </div>
            <div class="col-md-4">
                <div class="row">
                    <div class="col-md-4"></div>
                    <div class="col-md-2">
                        <input class="btn btn-success" type="button" onclick="printDiv('printableArea')" value="Print"/>
                    </div>
                    <div class="col-md-6">
                        {{ Form::open(array('url' => '/pdf/invoice', 'method' => 'post', 'value' => 'PATCH', 'id' => 'pdf_invoice')) }}
                        {{ Form::hidden('order_id', $orders_master->id, ['class' => 'btn btn-success']) }}
                        {{ Form::hidden('random_code', $orders_master->order_random, ['class' => 'btn btn-success']) }}
                        {{ Form::hidden('secret_key', $orders_master->secret_key, ['class' => 'btn btn-success']) }}
                        {{ Form::submit('Download Invoice PDF', ['class' => 'btn btn-primary']) }}
                        {{ Form::close() }}
                    </div>
                </div>


            </div>
        </div>
    </div>

    <section class="prosuct-view-section">
        <div class="container">
            <div class="row" style="margin: 0 auto;">
                <div class="col-md-12">

                    @include('frontend.common.message_handler')
                    <?php //$settings = $settings->first(); ?>
                    <div class="pdf" id="printableArea">
                        <div class="container" id="content">
                            <table style="width:100%;font-family: 'Lato', sans-serif;">
                                <tr>
                                    <td style="width:60%;">
                                        <img src="{{ $settings->first()->com_logourl }}" alt="{{ $settings->first()->com_name }}"
                                             style="width: 165px; height:auto;"/>
                                    </td>
                                    <td style="width:40%;text-align: right;">
                                        <div>
                                            <strong>Hotline #: {{ $settings->first()->com_phone }}</strong>
                                        </div>

                                    </td>
                                </tr>

                            </table>
                            <?php //dump($orders_master) ?>
                            <table style="width:100%;font-family: 'Lato', sans-serif;">
                                <tr>
                                    <td>
                                        <p style="background-color: #e9e9e9; padding: 10px; border-radius: 5px 5px 0 0; margin-bottom:0">
                                            Order Summary
                                        </p>
                                        <div style="border: 1px solid #eee; width:100%">

                                            <p style="padding: 0 15px;">
                                                <strong>Status</strong> # {{ $orders_master->order_status }}<br>
                                                <strong>Order Number</strong> # {{ $orders_master->id }}<br>
                                                <strong>Order Date</strong> # {{ $orders_master->order_date }} <br>
                                            </p>
                                        </div>

                                    </td>
                                </tr>

                            </table>


                            <table style="width:100%;font-family: 'Lato', sans-serif;">
                                <tr>
                                    <td style="width:48%">
                                        <p style="background-color: #e9e9e9; padding: 10px; border-radius: 5px 5px 0 0; margin-bottom:0">
                                            Billing Delivery Address
                                        </p>
                                        <div style="border: 1px solid #eee; width:100%">

                                            <p style="padding: 0 15px;">
                                                <strong>Name</strong> # {!! nl2br($orders_master->customer_name) !!}<br>
                                                <strong>Mobile</strong> # {{ $orders_master->phone }}<br>
                                                <strong>Emergency Mobile</strong>
                                                # {{ $orders_master->emergency_phone }} <br>
                                                <strong>Customer Email</strong> # {{ $orders_master->email }} <br>
                                                <strong>Address</strong> # {{ $orders_master->address }} <br>
                                            </p>
                                        </div>

                                    </td>
                                    <td style="width:4%">


                                    </td>
                                    <td style="width:48%">
                                        <p style="background-color: #e9e9e9; padding: 10px; border-radius: 5px 5px 0 0; margin-bottom:0">
                                            Payment Information</p>
                                        <div style="border: 1px solid #eee; width:100%">

                                            <p style="padding: 0 15px;">
                                                <strong>Payment Method</strong> #
                                                @if($orders_master->payment_method == 'cash_on_delivery')
                                                    Cash On Delivery
                                                @else
                                                    {{ $orders_master->payment_method }}
                                                @endif
                                                <br>
                                                <strong>
                                                    Payment Status</strong>#
                                                    @if($orders_master->payment_term_status)
                                                        {{ $orders_master->payment_term_status }}
                                                    @else
                                                @endif
                                                <br>
                                                <br><br>

                                            </p>
                                        </div>
                                    </td>
                                </tr>

                            </table>
                            <br><br>

                            <table style="width:100%; border-collapse: collapse; border: 1px solid #ccc;font-family: 'Lato', sans-serif;">
                                <tr style="background-color: #e9e9e9;">
                                    <td colspan="6">
                                        <p style="background-color: #e9e9e9; border-radius: 5px 5px 0 0; margin-bottom:0; border-bottom:1px solid #ccc">
                                            Order Details
                                        </p>
                                    </td>
                                </tr>
                                <tr style="border: 1px solid #ccc; background-color: #e9e9e9;">
                                    <td style="border: 1px solid #ccc;padding:5px;"><strong>Image</strong></td>
                                    <td style="border: 1px solid #ccc;padding:5px;"><strong>Product Name</strong></td>
                                    <td style="border: 1px solid #ccc;padding:5px;"><strong>Product Code</strong></td>
                                    <td style="border: 1px solid #ccc;padding:5px;"><strong>Price</strong></td>
                                    <td style="border: 1px solid #ccc;padding:5px;"><strong>Qty</strong></td>
                                    <td style="border: 1px solid #ccc;padding:5px;"><strong>Totals</strong></td>
                                </tr>
                                @if(!empty($orders_detail))
                                    @foreach($orders_detail as $line)
                                        <?php
                                        $item = App\Product::find($line->product_id);
                                        $totalqty[] = $line->qty;
                                        $totalprice[] = $line->local_purchase_price * $line->qty;
                                        ?>
                                        <tr style="border: 1px solid #ccc;">
                                            <td style="border: 1px solid #eee; padding:5px;;">
                                                @php
                                                    $pro = (object)$line;
                                                    //dd($pro);
                                                    $first_image = \App\ProductImages::where('main_pid', $pro->product_id)->where('is_main_image', 1)->get()->first();
                                                    

                                                    if (!empty($first_image->full_size_directory)) {
                                                        $img = url($first_image->full_size_directory);
                                                    } else {
                                                        $img = url('storage/uploads/fullsize/2019-01/default.jpg');
                                                    }
                                                    $second_image = \App\ProductImages::where('main_pid', $pro->id)->where('is_main_image', 0)->get()->first();
                                                    $regularprice = $pro->local_selling_price;
                                                    $save = ($pro->local_selling_price * $pro->local_discount) / 100;
                                                    $sp = $regularprice - $save;
                                                   // dump($item->sub_title);
                                                @endphp

                                                <img src="{{ $img }}" style="height: 70px; width: 70px;"/>
                                            </td>
                                            <td style="border: 1px solid #ccc;padding:5px;">{{ $line->product_name }}<br> {{$item->sub_title}}</td>
                                            <td style="border: 1px solid #ccc;padding:5px; text-align: center;">{{ $line->product_code }}</td>
                                            <td style="border: 1px solid #ccc;padding:5px;">
                                                BDT {{ number_format($item->local_selling_price) }} /=
                                            </td>
                                            <td style="border: 1px solid #ccc;padding:5px;">{{ $line->qty }}</td>
                                            <td style="border: 1px solid #ccc;padding:5px;">
                                                BDT {{ number_format($line->local_purchase_price * $line->qty) }}/=
                                            </td>
                                        </tr>
                                    @php 
                                    $datalayerProducts[] = [
                                    'id' => $line->id,
                                    'name' => $line->product_name,
                                    'price' => $line->local_purchase_price,
                                    'brand' => 'Regal',
                                    'category' => '',
                                    'variant' => '',
                                    'quantity' => $line->qty,
                                    'coupon' => ''
                                    ];
                                    
                                    @endphp
                                    
                                    @endforeach
                                @endif

                                <tr style="background: #e9e9e9; border: 1px solid #eee; text-align:right;">
                                    <td colspan="5" style="border: 1px solid #eee; padding: 10px;">
                                        <b>Total Price</b>
                                    </td>
                                    <td style="border: 1px solid #eee;padding: 10px">
                                        <b>
                                            @if(!empty($orders_master->total_amount))
                                                BDT {{ number_format($orders_master->total_amount) }}
                                            @endif
                                        </b>
                                    </td>
                                </tr>

                                <tr style="background: #e9e9e9; border: 1px solid #eee; text-align:right;">
                                    <td colspan="5" style="border: 1px solid #eee; padding: 10px;">
                                        <b>Delivery Fee</b>
                                    </td>
                                    <td style="border: 1px solid #eee;padding: 10px">
                                        <b>
                                            @if(!empty($orders_master->total_amount))
                                                BDT {{ $orders_master->delivery_fee }}
                                            @endif
                                        </b>
                                    </td>
                                </tr>
                                <tr style="background: #e9e9e9; border: 1px solid #eee; text-align:right;">
                                    <td colspan="5" style="border: 1px solid #eee; padding: 10px;">
                                        <b>Discount</b>
                                        {{ ($orders_master->coupon_type == '')? '': '('.$orders_master->coupon_type.')' }}
                                    </td>
                                    <td style="border: 1px solid #eee;padding: 10px">
                                        <b>

                                            @if(!empty($orders_master->coupon_discount))
                                                BDT {{ number_format($orders_master->coupon_discount) }}
                                            @endif
                                        </b>
                                    </td>
                                </tr>

                                <tr style="background: #e9e9e9; border: 1px solid #eee; text-align:right;">
                                    <td colspan="5" style="border: 1px solid #eee; padding: 10px;">
                                        <b>Grand Total</b>
                                    </td>
                                    <td style="border: 1px solid #eee;padding: 10px">
                                        <b>
                                            @if(!empty($orders_master->grand_total))
                                                BDT {{ number_format($orders_master->grand_total) }}
                                            @endif
                                        </b>
                                    </td>
                                </tr>
                            </table>
                            <br><br><br>

                            {!! $settings->first()->com_address  !!}

                        </div>
                        @push('scripts')
                            <link href="https://fonts.googleapis.com/css?family=Lato:100,100i,300,300i,400,400i,700,700i,900,900i"
                                  rel="stylesheet">
                        @endpush

                    </div>
                    <?php
                    //dump($orders_master);
                    //dump($orders_master);
                    //Session::flush();

                    Session::forget('cart');
                    Session::forget('user_details');
                    Session::forget('payment_method');
                    Session::forget('my_coupon');
                    ?>

                </div>
            </div>
        </div>
    </section>
@endsection
@section('cusjs')
    <script type="text/javascript" src="{{ URL::asset('public/frontend/js/jquery.print.js') }}"></script>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $.noConflict();

        });


        function printDiv(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }

    </script>
    
  <script>
dataLayer.push({
  'event': 'transaction',	//used for creating GTM trigger 
  'ecommerce': {
    'purchase': {
      'actionField': {
        'id': '{{$orders_master->id}}',        	 // Transaction ID. Required           
        'affiliation': 'Regal',
        'revenue': '{{number_format($orders_master->grand_total)}}',   	 // Total transaction value (incl. tax and shipping)
        'tax':'',
        'shipping': '',
        'coupon': '{{$orders_master->coupon_code}}'
      },
	  'products': <?php echo json_encode($datalayerProducts);  ?> //expand this array if more product exists
    }
  }
});
</script>
  
    
    <style type="text/css">
        .panel-body {
            color: #666;
        }

        .invoice-title h2, .invoice-title h3 {
            display: inline-block;
        }

        .table > tbody > tr > .no-line {
            border-top: none;
        }

        .table > thead > tr > .no-line {
            border-bottom: none;
        }

        .table > tbody > tr > .thick-line {
            border-top: 2px solid;
        }
    </style>
@endsection