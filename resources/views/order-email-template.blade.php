<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8" />

    <style>
        table.product_table td {
            padding: 5px;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .text-left {
            text-align: left;
        }
        .soft-yellow {
            background: rgb(245 245 220);
        }
    </style>

</head>
{{--  <body>--}}
{{--    <p><?php echo $messages ?? null ?></p>--}}

{{--  </body>--}}

<body marginheight="0" topmargin="0" marginwidth="0" style="margin: 0px; background-color: #f2f3f8;" leftmargin="0">

@php
    //13023
    $defaultOrderMasterId =  13121; //13062;
    $orderMaster = \App\Models\OrdersMaster::where('id', $data ?? $defaultOrderMasterId)->first();
    $orders = \App\Models\OrdersDetail::where('order_random', $orderMaster->order_random)->get();
@endphp

    <!-- 100% body table -->
<table cellspacing="0" border="0" cellpadding="0" width="100%" bgcolor="#f2f3f8"
       style="@import url(https://fonts.googleapis.com/css?family=Rubik:300,400,500,700|Open+Sans:300,400,600,700); font-family: 'Open Sans', sans-serif;">
    <tr>
        <td>
            <table style="background-color: #f2f3f8; max-width:770px; margin:0 auto;" width="100%" border="0"
                   align="center" cellpadding="0" cellspacing="0">
{{--                <tr>--}}
{{--                    <td style="height:80px;">&nbsp;</td>--}}
{{--                </tr>--}}
{{--                <tr>--}}
{{--                    <td style="text-align:center;">--}}
{{--                        <a href="https://regalfurniturebd.com" title="logo" target="_blank">--}}
{{--                            <img width="100" src="https://regalfurniturebd.com/_nuxt/img/only-logo.681c31c.svg" title="logo" alt="logo">--}}
{{--                        </a>--}}
{{--                    </td>--}}
{{--                </tr>--}}
                <tr>
                    <td style="height:20px;">&nbsp;</td>
                </tr>
                <tr>
                    <td>
                        <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0"
                               style="max-width:770px; background:#fff; border-radius:3px; text-align: center; font-size: 14px; -webkit-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);-moz-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);box-shadow:0 6px 18px 0 rgba(0,0,0,.06);">
                            <tr>
                                <td style="">
                                    <h1 style="background-color :  #f8f8f8; color:#1e1e2d; font-weight:500; margin:0;font-size:20px;font-family:'Rubik',sans-serif; padding: 10px; margin-top: 15px;">
                                        ORDER CONFIRMATION
                                    </h1>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:0 35px;">
{{--                                    <h1 style="color:#1e1e2d; font-weight:500; margin:0;font-size:32px;font-family:'Rubik',sans-serif;">Thank you for your order--}}
{{--                                    </h1>--}}
                                    <p style="text-align: left">
                                        Dear <b>{{$orderMaster->customer_name}}</b>,
                                    </p>
                                    <p style="text-align: left; font-size:14px; color:#455056; margin:8px 0 0; line-height:24px;">
                                        Thank you for shopping with Regalfurniturebd.com. <br>

                                        We will send you another email once the items in your order are shipped. <br>

                                        Please find the summary of your order below:
{{--                                        We've received your order <strong>#{{$orderMaster->id}}</strong> and is now being processed.--}}
                                    </p>

                                    <div class="text-left" style="; font-size:15px;">
{{--                                        <p>--}}
{{--                                            <strong>Address</strong>: {{$orderMaster->address}}--}}
{{--                                        </p>--}}
{{--                                        <p>--}}
{{--                                            <strong>Phone</strong>: {{$orderMaster->phone}}--}}
{{--                                        </p>--}}

{{--                                        <p>--}}
{{--                                            <strong>Payment Method</strong>: {{$orderMaster->payment_method}}--}}
{{--                                        </p>--}}
                                        <table border="0" cellpadding="2" cellspacing="0" style="font-size: 14px; padding: 5px; margin-top: 10px; border :  1px solid #ccc; text-align: left; background-color :  #efefef; width :  100%; ">
                                            <tbody>
                                            <tr>
                                                <td>
                                                    <table border="0" cellpadding="2" cellspacing="0">
                                                        <tbody>
                                                        <tr>
                                                            <td><strong>Order Number</strong></td>
                                                            <td><b>:</b></td>
                                                            <td>{{$orderMaster->id}} </td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>Order Date</strong></td>
                                                            <td><b>:</b></td>
                                                            <td>{{$orderMaster->order_date}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>Payment Method</strong></td>
                                                            <td><b>:</b></td>
                                                            <td>{{$orderMaster->payment_method}} </td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>Coupon Code</strong></td>
                                                            <td><b>:</b></td>
                                                            <td>{{$orderMaster->coupon_code}}  </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>


                                    </div>

                                    <span
                                        style="display:inline-block; vertical-align:middle; margin:0px 0 26px; border-bottom:0px solid #efefef; width:100px;"></span>
                                    <p
                                        style="color:#455056; font-size:14px;line-height:20px; margin:0; font-weight: 500;">
                                    <table class="product_table" cellspacing="0" border="1" cellpadding="0" width="100%"
                                           style="font-size:14px; text-align: left; border-color: #f6f6f6">
                                        <thead>
                                        <tr style="background-color: #e5e5e5;">
                                            <td>Sr No</td>
                                            <td>Product Name</td>
                                            <td class="text-center">SKU</td>
                                            <td class="text-center">Variant-SKU</td>
                                            <td class="text-center">Unit Price</td>
                                            <td class="text-center">Discount</td>
                                            <td class="text-center">Quantity</td>
                                            <td class="text-right">Total Cost</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($orders as $index => $item)
                                            @php
                                                $itemJeson = json_decode($item->item_jeson);
                                                $hasVariation =   $itemJeson->variation_id ?? false;
                                                $variations = $hasVariation ? $itemJeson->variation_info : false;
                                                //dump($variations);
                                            @endphp
                                            <tr>
                                                <td>{{++$index}}</td>
                                                <td>
                                                    {{$item->product_name}} <br>
                                                    {{$itemJeson->sub_title ?? null}}

                                                    @if($hasVariation)
                                                        <br>
                                                        @foreach($variations->variations as $vlabel => $vvalue)

                                                            @if($vvalue && isset($vvalue->value))
                                                                <b>{{$vlabel}}</b>:
                                                                @if($vvalue->show_as == 'Text')
                                                                    {{$vvalue->value ?? false}}
                                                                @endif
                                                                @if($vvalue->show_as == 'Image')
                                                                    <img src="https://admin.regalfurniturebd.com/{{\App\Models\Image::where('id', $vvalue->value)->first()->full_size_directory ?? null}}" alt="" style="width: 30px;">
                                                                @endif
                                                            @endif

                                                        @endforeach
                                                    @endif

                                                </td>
                                                <td class="text-center">{{$hasVariation ? $variations->main_pcode : $item->product_code}}</td>
                                                <td class="text-center">{{$hasVariation ? $item->product_code : false}}</td>
                                                <td class="text-center">৳ {{$item->local_purchase_price}}</td>
                                                <td class="text-center">৳ {{ round($item->local_selling_price-$item->local_purchase_price) ?? 0 }} ({{$item->discount ?? 0}}%) </td>
                                                <td class="text-center">{{$item->qty}}</td>
                                                <td class="text-right">৳ {{$item->qty*$item->local_purchase_price}}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>


                                    <table border="0" cellpadding="2" cellspacing="0" style="font-size: 14px; padding: 5px; margin-top: 10px; border :  1px solid #ccc; text-align: left; background-color :  #ececec; width :  100%; ">
                                        <tbody>
                                        <tr>
                                            <td>
                                                <table border="0" cellpadding="2" cellspacing="0">
                                                    <tbody>
                                                    <tr>
                                                        <td><strong>Total Price</strong></td>
                                                        <td><b>:</b></td>
                                                        <td> ৳ {{$orderMaster->total_amount}}</td>
                                                    </tr>
                                                    @php
                                                        $per = $orderMaster->coupon_type =="Percentage" ? '%' : null;
                                                        $fix = $orderMaster->coupon_type !="Percentage" ? null : '৳';
                                                    @endphp
                                                    <tr>
                                                        <td><strong>Coupon Discount</strong></td>
                                                        <td><b>:</b></td>
                                                        <td>{{$fix}} {{$orderMaster->coupon_discount ?? 0}}{{$per}} </td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Shipping Cost</strong></td>
                                                        <td><b>:</b></td>
                                                        <td> ৳ {{$orderMaster->delivery_fee}} </td>
                                                    </tr>

                                                    <tr>
                                                        <td><strong>Amount to be Paid</strong></td>
                                                        <td><b>:</b></td>
                                                        <td> ৳ {{round($orderMaster->grand_total)}} </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>


                                    <table style="width: 100%; text-align: left; margin-top: 10px;">
                                        <tr>
                                            <td style="">
                                                <table border="0" cellpadding="0" cellspacing="0" style="width :  100%; ">
                                                    <tbody>
                                                    <tr>
                                                        <td align="left" style=""><b><font face="arial" size="2">Shipping Address</font></b></td>
                                                        <td> </td>
                                                        <td style=""><b><font face="arial" size="2">Billing Address</font></b></td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                                {{$orderMaster->customer_name}}<br>
                                                                {{$orderMaster->address}}<br>
                                                                {{$orderMaster->district}},<br>
                                                                Mob: 88-{{$orderMaster->emergency_phone}}<br>
                                                                Email: <a href="mailto:{{$orderMaster->email}}" target="_blank">{{$orderMaster->email}}</a>
                                                        </td>
                                                        <td> </td>
                                                        <td>
                                                            {{$orderMaster->customer_name}}<br>
                                                            {{$orderMaster->address}}<br>
                                                            {{$orderMaster->district}},<br>
                                                            Mob: 88-{{$orderMaster->phone}}<br>
                                                            Email: <a href="mailto:{{$orderMaster->email}}" target="_blank">{{$orderMaster->email}}</a>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style=""><p style="font-size :  13px; ">
                                                    Need to make changes to your order? Please contact us at <b>+09613737777</b> or via
                                                    <b><a style="text-decoration :  none; " href="mailto:info@regalfurniturebd.com" target="_blank">info@regalfurniturebd.com</a></b>.
                                                    <br><br>
                                                    Happy Shopping! <br><br>
                                                    Regards, <br><b> Regalfurniture Team</b><br><br>
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="">
                                                <font face="arial" size="2"><strong>Note:</strong> This is an auto generated mail do not reply.</font></td>
                                        </tr>

                                    </table>

                                    </p>

{{--                                    <a target="_blank" href="https://regalfurniturebd.com/order?order_random={{$orderMaster->order_random}}&order_key={{$orderMaster->secret_key}}"--}}
{{--                                       style="background:#3b3f3d;text-decoration:none !important; display:inline-block; font-weight:500; margin-top:24px; color:#fff;text-transform:uppercase; font-size:14px;padding:10px 24px;display:inline-block;border-radius:50px;">--}}
{{--                                        Invoice--}}
{{--                                    </a>--}}
                                </td>
                            </tr>

                            <tr style="background-color :  #c00; color:#fff; font-weight:500; margin:0;font-size:15px;font-family:'Rubik',sans-serif; padding: 10px; margin-top: 15px;">
                                <td style="">
                                    <h2>Regalfurniturebd.com</h2>
                                </td>

                            </tr>

                            <tr>
                               <td>
                                   <table cellspacing="0" cellpadding="0" border="0" style=" background-color :  #c00; color:#fff; width :  100%; text-align :  center; padding :  0 0 10px; text-transform :  uppercase; font-size :  13px; font-weight :  600; ">
                                       <tbody>
                                       <tr>
                                           <td style="color :  #fff; ">
                                               <img width="52" height="auto" border="0" id="1670928192330100001_imgsrc_url_1"><br><br>
                                               Temperature<br>
                                               Check</td>
                                           <td style="color :  #fff; ">
                                               <img width="52" height="auto" border="0" id="1670928192330100001_imgsrc_url_2"><br><br>
                                               Safe<br>
                                               Packaging</td>
                                           <td style="color :  #fff; ">
                                               <img width="52" height="auto" border="0" id="1670928192330100001_imgsrc_url_3"><br><br>
                                               Regular<br>
                                               Sanitisation</td>
                                       </tr>
                                       </tbody>
                                   </table>
                               </td>
                            </tr>

                            <tr>
                                <td style="padding: 20px 100px; font-weight: 600">
                                    CONNECT WITH US <br>
                                    LIKE, FOLLOW & SUBSCRIBE TO US ON SOCIAL MEDIA
                                    AND STAY UPDATED WITH ALL OUR LATEST OFFERS AND NEW ARRIVALS
                                </td>
                            </tr>
                            <tr>
                                <td style="">
                                    <h4 style="background-color :  #c00; color:#fff; font-weight:500; margin:0;font-size:15px;font-family:'Rubik',sans-serif; padding: 10px; margin-top: 15px;">
                                        Regalfurniturebd.com
                                    </h4>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="height:20px;">&nbsp;</td>
                </tr>

                <tr>
                    <td style="height:80px;">&nbsp;</td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<!--/100% body table-->
</body>
</html>
