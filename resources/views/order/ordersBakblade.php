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
                {{ Form::open(array('url' => '/search_orders', 'method' => 'get', 'value' => 'PATCH', 'id' =>
                'search-form')) }}
                <div class="row fixed" style="display:flex;">
                    <div class="col-xs-2">
                        <select value="{{ !empty($getattrebute->column) }}" name="column" id="columnName" required
                                class="form-control select2" style="width: 100%;">
                            <option value="customer_name" {{ (Request::post(
                            'column') == 'customer_name') ? 'selected="selected"' : 'selected="selected"' }}>
                            Customer Name
                            </option>
                            <option value="phone" {{ (Request::post(
                            'column') == 'phone') ? 'selected="selected"' : '' }}>
                            Phone
                            </option>
                            <option value="emergency_phone" {{ (Request::post(
                            'column') == 'emergency_phone') ? 'selected="selected"' : '' }}>
                            Emergency Phone
                            </option>
                            <option value="address" {{ (Request::post(
                            'column') == 'address') ? 'selected="selected"' : '' }}>
                            Address
                            </option>
                            <option value="email" {{ (Request::post(
                            'column') == 'email') ? 'selected="selected"' : '' }}>
                            Email
                            </option>
                            <option value="order_status" {{ (Request::post(
                            'column') == 'order_status') ? 'selected="selected"' : '' }}>
                            Order Status
                            </option>
                            <option value="payment_method" {{ (Request::post(
                            'column') == 'payment_method') ? 'selected="selected"' : '' }}>
                            Payment Method
                            </option>
                            <option value="order_random" {{ (Request::post(
                            'column') == 'order_random') ? 'selected="selected"' : '' }}>
                            Order Random
                            </option>
                            <option value="om.id" {{ (Request::post(
                            'column') == 'om.id') ? 'selected="selected"' : '' }}>
                            Order ID
                            </option>

                        </select>
                    </div>

                    <div class="col-xs-3">
                        {{ Form::text('search_key', $getAttribute['search_key']??'', ['autocomplete' => 'off','id' =>
                        "searchKeyword", 'class' => 'form-control', 'placeholder' => 'Search Keys...']) }}
                    </div>

                    <div class="input-group date col-xs-2" style="margin-right: 5px;">
                        <div class="input-group-addon">
                            Form
                        </div>
                        <input value="{{ $getAttribute['formDate']??'' }}" autocomplete="off" type="text"
                               name="formDate" id="formDate" class="form-control datepicker">
                    </div>

                    <div class="input-group date col-xs-2" style="margin-right: 5px;">
                        <div class="input-group-addon">
                            To
                        </div>
                        <input value="{{ $getAttribute['toDate']??''  }}" autocomplete="off" type="text" name="toDate"
                               id="toDate" class="form-control datepicker">
                    </div>

                    <div class="col-xs-1">
                        {{ Form::submit('Search', ['class' => 'btn btn-success']) }}
                    </div>

                    {{ Form::close() }}

                    <div class="col-xs-1" style="float: right; margin-right: 12px;">
                        <a class="btn btn-success" id="exportExcel" onclick="getFormData()" href="javascript:void(0)">Export
                            Excel</a>
                    </div>

                </div>


            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="box">
            <div class="box-header">
                <div class="row">
                    <div class="col-xs-3">
                        <div class="form-group">
                            <select name="order_status" id="ch_order_status" class="form-control">
                                <option value="">Choose a status</option>
                                <option value="production">Production</option>
                                <option value="distribution">Distribution</option>
                                <option value="processing">Processing</option>
                                <option value="refund">Refund</option>
                                <option value="delivered">Delivered</option>
                                <option value="deleted">Delete</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-1">
                        <div class="form-group">
                            <input type="button" id="change_status" class="btn btn-sm btn-default" value="Apply">
                        </div>
                    </div>
                    <div class="col-xs-4"></div>
                </div>
            </div>

            <div class="search_box">

            </div>


            <div class="box-body table-responsive no-padding" id="reload_me">
                <table class="table table-hover">
                    <tbody>
                    <tr>
                        <th title="Order Status &amp; SMS">OS</th>
                        <th>Order ID</th>

                        <th title="Customer Details">Customer</th>
                        <th>Action</th>
                        <th title="Approximate Delivery Date">Delivery Date</th>
                        <th title="Order Informations">Order Infos</th>
                        <th title="Total Purchased Amount">Amount</th>
                    </tr>
                    </tbody>
                </table>


                <div class="box-group" id="accordion">
                    @foreach($orders as $line)
                    @php

                    //dump($line);

                    //$image_info = App\Models\ProductImages::Where(['main_pid' => $line->product_id])->get()->first();
                    // $vendor = App\Models\User::Where(['id' => $line->vendor_id])->get()->first();
                    $order_details = App\Models\OrdersDetail::Where(['order_random' => $line->order_random])->get();
                    $total = 0;


                    if(auth()->user()->isVendor()){
                    foreach ($order_details as $cod){
                    $vendor = App\Models\User::Where(['id' => $cod->vendor_id])->get()->first();
                    if(auth()->user()->id == $vendor->id){

                    $total += $cod->local_purchase_price;

                    }


                    }
                    }else{

                    $total += $line->total_amount;

                    }


                    @endphp
                    <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                    <div class="panel"
                         style="{{$line->payment_term_status == 'Successful' ? 'background-color:rgba(194, 255, 238);' : ($line->payment_method == 'cash_on_delivery' ? 'background-color:rgba(242, 238, 203);' : '')}}">
                        <div class="box-header with-border row">
                            <div class="col-xs-1">
                                <label>
                                    <input type="checkbox"
                                           class="checkbox"
                                           name="id[]"
                                           value="{{ $line->id }}"> A
                                </label>
                            </div>
                            <div class="col-xs-1">
                                {{ $line->id }}

                            </div>
                            <div class="col-xs-2">
                                <small>
                                    <b>{{ $line->customer_name }}</b><br/>
                                    {!! nl2br($line->address) !!}<br/>
                                    <b>P# </b>{{ $line->phone }}
                                    <b>EP# </b>
                                    {{-- {{ $line->emergency_phone }}--}}
                                </small>

                            </div>
                            <div class="col-xs-2">

                                <div class="btn-group">
                                    <div class="btn-group">
                                        <button
                                            type="button"
                                            class="btn btn-default dropdown-toggle btn-xs"
                                            data-toggle="dropdown"
                                            aria-expanded="false">
                                            Action <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li>
                                                <!-- <a target="_blank"
                                                   href="{{ url('/invoice?order_id='. $line->id .'&singel=No&random_code='. $line->order_random .'&secret_key=' . $line->secret_key) }}">
                                                    View Invoice
                                                </a> -->
                                                <a target="_blank"
                                                   href="{{env('FRONTEND_URL','https://regalfurniturebd.com')}}/order?order_random={{$line->order_random}}&order_key={{$line->secret_key}}">View
                                                    Invoice</a>
                                            </li>

                                            <li>
                                                {{-- <a target="_blank" --}}
                                                        {{--
                                                        href="{{ url('/invoice?order_id='. $line->id .'&random_code='. $line->order_random .'&secret_key=' . $line->secret_key) }}">--}}
                                                    {{-- Chnage Statu--}}
                                                    {{-- </a>--}}

                                                <a data-toggle="collapse" data-parent="#accordion"
                                                   href="{{ '#collapse'.$line->id }}"
                                                   aria-expanded="false" class="collapsed">
                                                    Chnage Status
                                                </a>

                                            </li>

                                        </ul>
                                    </div>
                                </div>

                            </div>
                            <div class="col-xs-2">
                                {{-- @if(!empty($line->delivery_date))--}}
                                {{-- {{ $line->delivery_date }}--}}
                                {{-- @else--}}
                                {{-- <a class="btn btn-info btn-xs" href="javascript:void(0)" data-toggle="modal" --}}
                                        {{-- data-target="#myModal_{{ $line->id }}">--}}
                                    {{-- Add Delivery Date--}}
                                    {{-- </a>--}}

                                {{--
                                <div class="modal fade" id="myModal_{{ $line->id }}" tabindex="-1" role="dialog" --}}
                                     {{-- aria-labelledby="myModalLabel">--}}
                                    {{--
                                    <div class="modal-dialog" role="document">--}}
                                        {{--
                                        <div class="modal-content">--}}
                                            {{--
                                            <div class="modal-header">--}}
                                                {{--
                                                <button type="button" class="close" data-dismiss="modal" --}}
                                                        {{-- aria-label="Close">--}}
                                                    {{-- <span aria-hidden="true">&times;</span>--}}
                                                    {{--
                                                </button>
                                                --}}
                                                {{-- <h4 class="modal-title" id="myModalLabel">--}}
                                                    {{-- Approximate Delivery Date--}}
                                                    {{-- </h4>--}}
                                                {{--
                                            </div>
                                            --}}
                                            {{-- {{ Form::open(array('url' => '/save_or_update_delivery_date', 'method'
                                            => 'post', 'value' => 'PATCH', 'id' => '', 'autocomplete' => 'off')) }}--}}
                                            {{--
                                            <div class="modal-body">--}}

                                                {{--
                                                <div class="row">--}}
                                                    {{--
                                                    <div class="col-xs-12">--}}
                                                        {{-- {{ Form::hidden('order_id', !empty($line->id) ? $line->id :
                                                        'none', ['required', 'class' => 'form-control']) }}--}}
                                                        {{-- {{ Form::text('appr_delivery_date',
                                                        Request::post('appr_delivery_date'), ['required', 'id' =>
                                                        'datepicker', 'class' => 'form-control', 'placeholder' =>
                                                        'Choose a date...']) }}--}}
                                                        {{--
                                                    </div>
                                                    --}}
                                                    {{--
                                                </div>
                                                --}}

                                                {{--
                                            </div>
                                            --}}
                                            {{--
                                            <div class="modal-footer">--}}
                                                {{--
                                                <button type="button" class="btn btn-default" --}}
                                                        {{-- data-dismiss="modal">Close--}}
                                                    {{--
                                                </button>
                                                --}}
                                                {{-- {{ Form::submit('Save', ['class' => 'btn btn-success']) }}--}}
                                                {{--
                                            </div>
                                            --}}
                                            {{-- {{ Form::close() }}--}}
                                            {{--
                                        </div>
                                        --}}
                                        {{--
                                    </div>
                                    --}}
                                    {{--
                                </div>
                                --}}
                                {{-- @endif--}}

                            </div>
                            <div class="col-xs-2">


                                <b title="Payment Stauts">PS:</b><font
                                    color="{{$line->payment_term_status == 'Successful' ? 'green': 'orange'}}">{{
                                    $line->payment_term_status }}</font><br/>

                                <b title="Order Date">OD:</b> {{ $line->order_date }}<br/>

                                <b title="Payment Method">PM:</b> {{ $line->payment_method }} <br/>

                            </div>
                            <div class="col-xs-2">
                                <div title="Total">
                                    <b>Total : </b> ৳ {{ number_format($total) }}
                                </div>
                                @if(!(auth()->user()->isVendor()))
                                <div title="Grand Total">
                                    <b>Grand Total : </b> ৳ {{ number_format($line->grand_total) }}
                                </div>

                                @endif


                            </div>


                        </div>

                        <div id="{{ 'collapse'.$line->id }}" class="panel-collapse collapse" aria-expanded="false"
                             style="height: 0px;">
                            @foreach($order_details as $od)


                            @php
                            $image_info = App\Models\ProductImages::Where(['main_pid' =>
                            $od->product_id])->get()->first();
                            $vendor = App\Models\User::Where(['id' => $od->vendor_id])->get()->first();
                            @endphp
                            @if(auth()->user()->isVendor())
                            @if(auth()->user()->id == $vendor->id)

                            <div class="box-body row">
                                <div class="col-xs-1">#</div>
                                <div class="col-xs-1">
                                    <img src="{{ $image_info->icon_size_directory }}" style="height: 80px; width: auto">
                                </div>
                                <div class="col-xs-2">
                                    <small>
                                        <b>{{ $od->product_name }}</b><br/>

                                        <b>Product Code# </b>{{ $od->product_code }}<br/>
                                        <b>Item Code# </b>{{ $od->item_code }}<br/>
                                        <b>Vendor # </b> <a href="{{ url('/shop/'.$vendor->id) }}" target="_blank">
                                            {{ (($vendor->company)? $vendor->company : $vendor->name) }}
                                        </a><br/>

                                    </small>
                                </div>
                                <div class="col-xs-2">

                                    <div class="btn-group">
                                        <div class="btn-group">
                                            <button
                                                type="button"
                                                class="btn btn-default dropdown-toggle btn-xs"
                                                data-toggle="dropdown"
                                                aria-expanded="false">
                                                Order Status <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu" role="menu">
                                                <li>
                                                    <a target="_blank"
                                                       href="{{env('FRONTEND_URL','https://regalfurniturebd.com')}}/order?order_random={{$line->order_random}}&order_key={{$line->secret_key}}">
                                                        View Invoice
                                                    </a>
                                                </li>
                                                <li id="move_order">
                                                    <input type="checkbox"
                                                           name="send_sms"
                                                           class="send_sms"
                                                           data-id="{{ $od->id }}" {{ $od->od_status == 'placed' ?
                                                    'checked' : "" }}>
                                                    <a class="order_status_change"
                                                       data-status="placed"
                                                       data-id="{{ $od->id }}"
                                                       href="javascript:void(0)">
                                                        Placed
                                                    </a>
                                                </li>
                                                <li id="move_order">
                                                    <input type="checkbox"
                                                           name="send_sms"
                                                           class="send_sms"
                                                           data-id="{{ $od->id }}" {{ $od->od_status == 'production' ?
                                                    'checked' : "" }}>
                                                    <a class="order_status_change"
                                                       data-status="production"
                                                       data-id="{{ $od->id }}"
                                                       href="javascript:void(0)">
                                                        Production
                                                    </a>
                                                </li>
                                                <li id="move_order">
                                                    <input type="checkbox"
                                                           name="send_sms"
                                                           class="send_sms"
                                                           data-id="{{ $od->id }}" {{ $od->od_status == 'distribution' ?
                                                    'checked' : "" }}>
                                                    <a class="order_status_change"
                                                       data-status="distribution"
                                                       data-id="{{ $od->id }}"
                                                       href="javascript:void(0)">
                                                        Distribution
                                                    </a>
                                                </li>
                                                <li id="move_order">
                                                    <input type="checkbox"
                                                           name="send_sms"
                                                           class="send_sms"
                                                           data-id="{{ $od->id }}" {{ $od->od_status == 'processing' ?
                                                    'checked' : "" }}>
                                                    <a class="order_status_change"
                                                       data-status="processing"
                                                       data-id="{{ $od->id }}"
                                                       href="javascript:void(0)">
                                                        Processing
                                                    </a>
                                                </li>
                                                <li id="move_order">
                                                    <input type="checkbox"
                                                           name="send_sms"
                                                           class="send_sms"
                                                           data-id="{{ $od->id }}" {{ $od->od_status == 'refund' ?
                                                    'checked' : "" }}>
                                                    <a class="order_status_change"
                                                       data-status="refund"
                                                       data-id="{{ $od->id }}"
                                                       href="javascript:void(0)">
                                                        Refund
                                                    </a>
                                                </li>
                                                <li id="move_order">
                                                    <input type="checkbox"
                                                           name="send_sms"
                                                           class="send_sms"
                                                           data-id="{{ $od->id }}" {{ $od->od_status == 'done' ?
                                                    'checked' : "" }}>
                                                    <a class="order_status_change"
                                                       data-status="done"
                                                       data-id="{{ $od->id }}"
                                                       href="javascript:void(0)">
                                                        Done
                                                    </a>
                                                </li>
                                                <li id="move_order">
                                                    <input type="checkbox"
                                                           name="send_sms"
                                                           class="send_sms"
                                                           data-id="{{ $od->id }}" {{ $od->od_status == 'deleted' ?
                                                    'checked' : "" }}>
                                                    <a class="order_status_change"
                                                       data-status="deleted"
                                                       data-id="{{ $od->id }}"
                                                       href="javascript:void(0)">
                                                        Delete Order
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-xs-2">
                                    @if(!empty($od->delivery_date))
                                    {{ $od->delivery_date }}
                                    @else
                                    <a class="btn btn-info btn-xs" href="javascript:void(0)" data-toggle="modal"
                                       data-target="#myModal_{{ $od->id }}">
                                        Add Delivery Date
                                    </a>

                                    <div class="modal fade" id="myModal_{{ $od->id }}" tabindex="-1" role="dialog"
                                         aria-labelledby="myModalLabel">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                    <h4 class="modal-title" id="myModalLabel">
                                                        Approximate Delivery Date
                                                    </h4>
                                                </div>
                                                {{ Form::open(array('url' => '/save_or_update_delivery_date', 'method'
                                                => 'post', 'value' => 'PATCH', 'id' => '', 'autocomplete' => 'off')) }}
                                                <div class="modal-body">

                                                    <div class="row">
                                                        <div class="col-xs-12">
                                                            {{ Form::text('order_id', !empty($line->id) ? $line->id :
                                                            'none', ['required', 'class' => 'form-control']) }}
                                                            {{ Form::text('appr_delivery_date',
                                                            Request::post('appr_delivery_date'), ['required', 'id' =>
                                                            'datepicker', 'class' => 'form-control', 'placeholder' =>
                                                            'Choose a date...']) }}
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default"
                                                            data-dismiss="modal">Close
                                                    </button>
                                                    {{ Form::submit('Save', ['class' => 'btn btn-success']) }}
                                                </div>
                                                {{ Form::close() }}
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                <div class="col-xs-2">

                                    {{-- @php--}}

                                    {{-- dump($od);--}}
                                    {{-- @endphp--}}


                                    <b title="Order Stauts">OS:</b>

                                    @if ($od->od_status == 'placed')
                                    <small
                                        style="color:white;background-color:coral;padding:2px 2px 2px 4px;border-radius: 3px;">
                                        Placed
                                    </small>
                                    @elseif ($od->od_status == 'production')
                                    <small
                                        style="color:white;background-color:cornflowerblue;padding:2px 2px 2px 4px;border-radius: 3px;">
                                        Production
                                    </small>
                                    @elseif ($od->od_status == 'distribution')
                                    <small
                                        style="color:white;background-color:darkcyan;padding:2px 2px 2px 4px;border-radius: 3px;">
                                        Distribution
                                    </small>
                                    @elseif ($od->od_status == 'processing')
                                    <small
                                        style="color:white;background-color:green;padding:2px 2px 2px 4px;border-radius: 3px;">
                                        Processing
                                    </small>
                                    @elseif ($od->od_status == 'refund')
                                    <small
                                        style="color:white;background-color:gray;padding:2px 2px 2px 4px;border-radius: 3px;">
                                        Refund
                                    </small>
                                    @elseif ($od->od_status == 'done')
                                    <small
                                        style="color:white;background-color:lightseagreen;padding:2px 2px 2px 4px;border-radius: 3px;">
                                        Done
                                    </small>
                                    @elseif ($od->od_status == 'deleted')
                                    <small
                                        style="color:white;background-color:hotpink;padding:2px 2px 2px 4px;border-radius: 3px;">
                                        Deleted
                                    </small>
                                    @endif


                                </div>
                                <div class="col-xs-2">

                                    <div title="Total">
                                        <b>Total : </b> ৳ {{ number_format($od->local_purchase_price) }}
                                    </div>


                                </div>

                            </div>
                            @endif
                            @else
                            <div class="box-body row">
                                <div class="col-xs-1">#</div>
                                <div class="col-xs-1">
                                    <img src="{{ $image_info->icon_size_directory }}" style="height: 80px; width: auto">
                                </div>
                                <div class="col-xs-2">
                                    <small>
                                        <b>{{ $od->product_name }}</b><br/>

                                        <b>Product Code# </b>{{ $od->product_code }}<br/>
                                        <b>Item Code# </b>{{ $od->item_code }}<br/>
                                        <b>Vendor # </b> <a href="{{ url('/shop/'.$vendor->id) }}" target="_blank">
                                            {{ (($vendor->company)? $vendor->company : $vendor->name) }}
                                        </a><br/>

                                    </small>
                                </div>
                                <div class="col-xs-2">

                                    <div class="btn-group">
                                        <div class="btn-group">
                                            <button
                                                type="button"
                                                class="btn btn-default dropdown-toggle btn-xs"
                                                data-toggle="dropdown"
                                                aria-expanded="false">
                                                Order Status <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu" role="menu">
                                                <li>
                                                    <a target="_blank"
                                                       href="{{ env('FRONTEND_URL','https://regalfurniturebd.com')}}/order?order_random={{$line->order_random}}&order_key={{$line->secret_key}}">
                                                        View Invoice
                                                    </a>
                                                </li>
                                                <li id="move_order">
                                                    <input type="checkbox"
                                                           name="send_sms"
                                                           class="send_sms"
                                                           data-id="{{ $od->id }}" {{ $od->od_status == 'placed' ?
                                                    'checked' : "" }}>
                                                    <a class="order_status_change"
                                                       data-status="placed"
                                                       data-id="{{ $od->id }}"
                                                       href="javascript:void(0)">
                                                        Placed
                                                    </a>
                                                </li>
                                                <li id="move_order">
                                                    <input type="checkbox"
                                                           name="send_sms"
                                                           class="send_sms"
                                                           data-id="{{ $od->id }}" {{ $od->od_status == 'production' ?
                                                    'checked' : "" }}>
                                                    <a class="order_status_change"
                                                       data-status="production"
                                                       data-id="{{ $od->id }}"
                                                       href="javascript:void(0)">
                                                        Production
                                                    </a>
                                                </li>
                                                <li id="move_order">
                                                    <input type="checkbox"
                                                           name="send_sms"
                                                           class="send_sms"
                                                           data-id="{{ $od->id }}" {{ $od->od_status == 'distribution' ?
                                                    'checked' : "" }}>
                                                    <a class="order_status_change"
                                                       data-status="distribution"
                                                       data-id="{{ $od->id }}"
                                                       href="javascript:void(0)">
                                                        Distribution
                                                    </a>
                                                </li>
                                                <li id="move_order">
                                                    <input type="checkbox"
                                                           name="send_sms"
                                                           class="send_sms"
                                                           data-id="{{ $od->id }}" {{ $od->od_status == 'processing' ?
                                                    'checked' : "" }}>
                                                    <a class="order_status_change"
                                                       data-status="processing"
                                                       data-id="{{ $od->id }}"
                                                       href="javascript:void(0)">
                                                        Processing
                                                    </a>
                                                </li>
                                                <li id="move_order">
                                                    <input type="checkbox"
                                                           name="send_sms"
                                                           class="send_sms"
                                                           data-id="{{ $od->id }}" {{ $od->od_status == 'refund' ?
                                                    'checked' : "" }}>
                                                    <a class="order_status_change"
                                                       data-status="refund"
                                                       data-id="{{ $od->id }}"
                                                       href="javascript:void(0)">
                                                        Refund
                                                    </a>
                                                </li>
                                                <li id="move_order">
                                                    <input type="checkbox"
                                                           name="send_sms"
                                                           class="send_sms"
                                                           data-id="{{ $od->id }}" {{ $od->od_status == 'done' ?
                                                    'checked' : "" }}>
                                                    <a class="order_status_change"
                                                       data-status="done"
                                                       data-id="{{ $od->id }}"
                                                       href="javascript:void(0)">
                                                        Done
                                                    </a>
                                                </li>
                                                <li id="move_order">
                                                    <input type="checkbox"
                                                           name="send_sms"
                                                           class="send_sms"
                                                           data-id="{{ $od->id }}" {{ $od->od_status == 'deleted' ?
                                                    'checked' : "" }}>
                                                    <a class="order_status_change"
                                                       data-status="deleted"
                                                       data-id="{{ $od->id }}"
                                                       href="javascript:void(0)">
                                                        Delete Order
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-xs-2">
                                    @if(!empty($od->delivery_date))
                                    {{ $od->delivery_date }}
                                    @else
                                    <a class="btn btn-info btn-xs" href="javascript:void(0)" data-toggle="modal"
                                       data-target="#myModal_{{ $od->id }}">
                                        Add Delivery Date
                                    </a>

                                    <div class="modal fade" id="myModal_{{ $od->id }}" tabindex="-1" role="dialog"
                                         aria-labelledby="myModalLabel">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                    <h4 class="modal-title" id="myModalLabel">
                                                        Approximate Delivery Date
                                                    </h4>
                                                </div>
                                                {{ Form::open(array('url' => '/save_or_update_delivery_date', 'method'
                                                => 'post', 'value' => 'PATCH', 'id' => '', 'autocomplete' => 'off')) }}
                                                <div class="modal-body">

                                                    <div class="row">
                                                        <div class="col-xs-12">
                                                            {{ Form::hidden('order_id', !empty($od->id) ? $od->id :
                                                            'none', ['required', 'class' => 'form-control']) }}
                                                            {{ Form::text('appr_delivery_date',
                                                            Request::post('appr_delivery_date'), ['required', 'id' =>
                                                            'datepicker', 'class' => 'form-control', 'placeholder' =>
                                                            'Choose a date...']) }}
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default"
                                                            data-dismiss="modal">Close
                                                    </button>
                                                    {{ Form::submit('Save', ['class' => 'btn btn-success']) }}
                                                </div>
                                                {{ Form::close() }}
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                <div class="col-xs-2">


                                    <b title="Order Stauts">OS:</b>

                                    @if ($od->od_status == 'placed')
                                    <small
                                        style="color:white;background-color:coral;padding:2px 2px 2px 4px;border-radius: 3px;">
                                        Placed
                                    </small>
                                    @elseif ($od->od_status == 'production')
                                    <small
                                        style="color:white;background-color:cornflowerblue;padding:2px 2px 2px 4px;border-radius: 3px;">
                                        Production
                                    </small>
                                    @elseif ($od->od_status == 'distribution')
                                    <small
                                        style="color:white;background-color:darkcyan;padding:2px 2px 2px 4px;border-radius: 3px;">
                                        Distribution
                                    </small>
                                    @elseif ($od->od_status == 'processing')
                                    <small
                                        style="color:white;background-color:green;padding:2px 2px 2px 4px;border-radius: 3px;">
                                        Processing
                                    </small>
                                    @elseif ($od->od_status == 'refund')
                                    <small
                                        style="color:white;background-color:gray;padding:2px 2px 2px 4px;border-radius: 3px;">
                                        Refund
                                    </small>
                                    @elseif ($od->od_status == 'done')
                                    <small
                                        style="color:white;background-color:lightseagreen;padding:2px 2px 2px 4px;border-radius: 3px;">
                                        Done
                                    </small>
                                    @elseif ($od->od_status == 'deleted')
                                    <small
                                        style="color:white;background-color:hotpink;padding:2px 2px 2px 4px;border-radius: 3px;">
                                        Deleted
                                    </small>
                                    @endif


                                </div>
                                <div class="col-xs-2">

                                    <div title="Total">
                                        <b>Total : </b> ৳ {{ number_format($od->local_purchase_price) }}
                                    </div>


                                </div>

                            </div>
                            @endif


                            @endforeach

                        </div>
                    </div>

                    @endforeach
                </div>


                <div class="box-footer clearfix">
                    {{ $orders->links('component.paginator', ['object' => $orders,'more' => 'some']) }}
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
