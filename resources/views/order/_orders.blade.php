@extends('layouts.app')

@section('title', 'Orders')
@section('sub_title', 'all Requisitions are here')
<?php $tksign = '&#2547; '; ?>
@section('content')
    <div class="row">
        @if(Session::has('success'))
            <div class="col-md-12">
                <div class="callout callout-success">
                    {{ Session::get('success') }}
                </div>
            </div>
        @endif
        <div class="col-md-12">
            <div class="box box-danger">
                <div class="box-header with-border">
                    <h5 class="box-title">Advanced Search</h5>
                </div>
                <div class="box-body">
                    {{ Form::open(array('url' => '/search_orders', 'method' => 'get', 'value' => 'PATCH', 'id' => 'search-form')) }}
                    <div class="row fixed" style="display:flex;">
                        <div class="col-xs-2">
                            <select value="{{ !empty($getattrebute->column) }}" name="column" id="columnName" required
                                    class="form-control select2" style="width: 100%;">
                                <option
                                    value="customer_name" {{ (Request::post('column') == 'customer_name') ? 'selected="selected"' : 'selected="selected"' }}>
                                    Customer Name
                                </option>
                                <option
                                    value="phone" {{ (Request::post('column') == 'phone') ? 'selected="selected"' : '' }}>
                                    Phone
                                </option>
                                <option
                                    value="emergency_phone" {{ (Request::post('column') == 'emergency_phone') ? 'selected="selected"' : '' }}>
                                    Emergency Phone
                                </option>
                                <option
                                    value="address" {{ (Request::post('column') == 'address') ? 'selected="selected"' : '' }}>
                                    Address
                                </option>
                                <option
                                    value="email" {{ (Request::post('column') == 'email') ? 'selected="selected"' : '' }}>
                                    Email
                                </option>
                                <option
                                    value="order_status" {{ (Request::post('column') == 'order_status') ? 'selected="selected"' : '' }}>
                                    Order Status
                                </option>
                                <option
                                    value="payment_method" {{ (Request::post('column') == 'payment_method') ? 'selected="selected"' : '' }}>
                                    Payment Method
                                </option>
                                <option
                                    value="order_random" {{ (Request::post('column') == 'order_random') ? 'selected="selected"' : '' }}>
                                    Order Random
                                </option>
                                <option
                                    value="om.id" {{ (Request::post('column') == 'om.id') ? 'selected="selected"' : '' }}>
                                    Order ID
                                </option>

                            </select>
                        </div>

                        <div class="col-xs-3">
                            {{ Form::text('search_key', $getAttribute['search_key']??'', ['autocomplete' => 'off','id' => "searchKeyword", 'class' => 'form-control', 'placeholder' => 'Search Keys...']) }}
                        </div>

                        <div class="input-group date col-xs-2" style="margin-right: 5px;">
                            <div class="input-group-addon">
                                From
                            </div>
                            <input value="{{ $getAttribute['formDate']??'' }}" autocomplete="off" type="text"
                                   name="formDate" id="formDate" class="form-control datepicker">
                        </div>

                        <div class="input-group date col-xs-2" style="margin-right: 5px;">
                            <div class="input-group-addon">
                                To
                            </div>
                            <input value="{{ $getAttribute['toDate']??''  }}" autocomplete="off" type="text"
                                   name="toDate" id="toDate" class="form-control datepicker">
                        </div>

                        <div class="input-group date col-xs-2" style="margin-right: 5px;">
                            <select name="order_from" id="" class="form-control">
                                <option value="">Order Source</option>
                                @php
                                    $orderFrom = ['website', 'custom', 'one click Buy'];
                                @endphp
                                @foreach($orderFrom as $val)
                                <option value="{{$val}}" {{isset($getAttribute['order_from'] ) && $getAttribute['order_from'] == $val ? 'selected' : null}}>{{$val}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-xs-1">
                            {{ Form::submit('Search', ['class' => 'btn btn-success']) }}
                        </div>

                        {{ Form::close() }}

                        <div class="col-xs-1" style="float: right; margin-right: 12px;">
                            <a class="btn btn-success" id="exportExcel" onclick="getFormData()"
                               href="javascript:void(0)">Export Excel</a>
                        </div>

                    </div>


                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="box">
{{--                <div class="box-header">--}}
{{--                    <div class="row">--}}
{{--                        <div class="col-xs-3">--}}
{{--                            <div class="form-group">--}}
{{--                                <select name="order_status" id="ch_order_status" class="form-control">--}}
{{--                                    <option value="">Choose a status</option>--}}
{{--                                    <option value="production">Production</option>--}}
{{--                                    <option value="distribution">Distribution</option>--}}
{{--                                    <option value="processing">Processing</option>--}}
{{--                                    <option value="refund">Refund</option>--}}
{{--                                    <option value="delivered">Delivered</option>--}}
{{--                                    <option value="deleted">Delete</option>--}}
{{--                                </select>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="col-xs-1">--}}
{{--                            <div class="form-group">--}}
{{--                                <input type="button" id="change_status" class="btn btn-sm btn-default" value="Apply">--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="col-xs-4"></div>--}}
{{--                    </div>--}}
{{--                </div>--}}

                <div class="box-body table-responsive no-padding" id="reload_me">
                    <table class="table table-hover">
                        <tbody>
                        <tr>
                            <th title="Order ID">ID</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Total</th>
                            <th>Source</th>
                        </tr>
                        @foreach($orders as $line)
                            @php
                                //dump($line);
                                //$image_info  = App\Models\ProductImages::Where(['main_pid' => $line->product_id])->get()->first();
                               // $vendor  = App\Models\User::Where(['id' => $line->vendor_id])->get()->first();
                                $order_details  = App\Models\OrdersDetail::Where(['order_random' => $line->order_random])->get();
                                $total = 0;
                                if(auth()->user()->isVendor()){
                                    foreach ($order_details as $cod){
                                    $vendor  = App\Models\User::Where(['id' => $cod->vendor_id])->get()->first();
                                        if(auth()->user()->id == $vendor->id){
                                        $total += $cod->local_purchase_price;
                                        }
                                     }
                                } else{
                                    $total +=  $line->total_amount;
                                }
                            @endphp
                            <tr>
                                <td title="Order ID">
                                    <a href="{{ url('orders_single/' . $line->order_random) }}?info_type=general">
                                        Order # {{ $line->id }}
                                    </a>
                                </td>
                                <td style="{{ $line->payment_term_status == 'Successful' ? 'background-color:rgba(194, 255, 238);' : ($line->payment_method == 'cash_on_delivery' ? 'background-color:rgba(242, 238, 203);' : '')}}">
                                    <label>
{{--                                        <input type="checkbox"--}}
{{--                                               class="checkbox"--}}
{{--                                               name="id[]"--}}
{{--                                               value="{{ $line->id }}"> --}}

                                        {{$line->order_status}}
                                    </label>
                                </td>
                                <td>
                                   <b>Order Date: </b>{{ $line->order_date ?? NULL }} <br>

                                    @php
                                        $maxday = App\Models\OrdersDetail::select('product_arrive_times_day')
                                                    ->where('order_random', $line->order_random)->get()
                                                    ->max('product_arrive_times_day') ?? false;

                                    @endphp
                                     {!!  $maxday ? '<b>Arrive Day:</b> '.$maxday. ' days' : false !!}
                                </td>
                                <td>
                                    <a href="mailto:{{ $line->email ?? '#' }}">@</a>
                                    {{ $line->customer_name ?? NULL }}
                                </td>
                                <td><a href="tel:{{ $line->email ?? '#' }}">{{ $line->phone ?? NULL }}</a></td>
                                <td>{{$line->address}}, {{$line->district}}</td>
                                <td>৳{{  number_format($line->grand_total) ?? NULL }}</td>
                                <td>{{$line->order_from}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="box-footer clearfix">
                        {{ $orders->appends($_GET)->links('component.paginator', ['object' => $orders,'more' => 'some']) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('cusjs')
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $.noConflict();

            $('.order_status_change').on('click', function () {


                var id = $(this).data('id');
                var status = $(this).data('status');
                var checked = this.parentElement.children[0].checked;

                var data = {
                    'id': id,
                    'status': status,
                    'send_sms': checked
                };

                //console.log(data);

                $.ajax({
                    url: baseurl + '/orders/move',
                    method: 'get',
                    data: data,
                    success: function (data) {
                        location.reload();
                    },
                    error: function () {
                        // showError('Sorry. Try reload this page and try again.');
                        // processing.hide();
                    }
                });
            });


            $('#change_status').on('click', function (e) {
                e.preventDefault();

                var os_items = "";
                var sms_items = "";

                var order_status = $('#ch_order_status').val();

                // order status items
                var checked = $('input[name="id[]"]:checked').length > 0;
                if (checked) {
                    var os_list = "";
                    $("input[name='id[]']").each(function () {
                        if (this.checked == true) {
                            // os = order status
                            os_list += (os_list == "" ? this.value : "_" + this.value);
                        }
                    });
                    console.log(os_list);
                }

                // sms items
                var sms_checked = $('input[name="sms[]"]:checked').length > 0;
                if (sms_checked) {
                    var sms_list = "";
                    $("input[name='sms[]']").each(function () {
                        if (this.checked == true) {
                            // os = order status
                            sms_list += (sms_list == null ? this.value : this.value + "_");
                        }
                    });
                    console.log(sms_list);
                }

                var data = {
                    'os_items': os_list,
                    'sms_items': sms_list,
                    'order_status': order_status
                };


                console.log(data);

                $.ajax({
                    url: baseurl + '/orders/bulk_move',
                    method: 'get',
                    data: data,
                    success: function (data) {
                        location.reload();
                    },
                    error: function () {
                        // showError('Sorry. Try reload this page and try again.');
                        // processing.hide();
                    }
                });

            });
        });

        function getFormData() {
            // let column = jQuery('#columnName').val()
            // let keyword = jQuery('#searchKeyword').val()
            // let formDate = jQuery('#formDate').val()
            // let toDate = jQuery('#toDate').val()
            // if(formDate === '' && toDate === ''){
            //     toDate = new Date().toISOString().slice(0, 10)

            //     let d = new Date();
            //     d.setDate(1);
            //     d.setMonth(d.getMonth() - 1);
            //     d.setHours(0,0,0,0);
            //     let lastMonthStart = new Date(d);
            //     formDate = lastMonthStart.toISOString().slice(0,10)

            // }

            // if(keyword == ""){
            //     keyword = null
            // }

            const formData = jQuery("#search-form").serialize()

            jQuery('#exportExcel').attr("href", `export_orders?${formData}` + "&pre_booking_order=" + 0)

        }

        //Date picker
        $('.datepicker').datepicker({
            autoclose: true
        })
    </script>
    <style type="text/css">
        .search_box .box-title {
            font-size: 15px;
        }

        .search_box {
            padding: 10px;
        }

        .search_box .box-body {
            background: floralwhite;
        }

        input.send_sms {
            width: 10%;
            padding-top: 3px !important;
            /*position: absolute;*/
            margin-left: 3px;
        }

        a.order_status_change {
            width: 100%;
            margin-top: -25px;
        }
    </style>
@endsection
