@extends('layouts.app')
@php

    if($cv_type == 'Coupon'){
        $coupon_type = 'Coupon';
        $coupon_name = 'Coupons';
        $coupon_tag = 'coupon';

        $coupon_link =  '';

    }else{
        $coupon_type = 'Voucher';
        $coupon_name = 'Vouchers';
        $coupon_tag = 'voucher';
        $coupon_link =  '';
    }


@endphp

@section('title', $coupon_name)
@section('sub_title', 'all '.$coupon_tag.' management panel')
@section('content')

    <div class="row">
        @if(Session::has('success'))
            <div class="col-md-12">
                <div class="callout callout-success">
                    {{ Session::get('success') }}
                </div>
            </div>
        @endif

        {{--@endif--}}
        @if($errors->any())
            <div class="col-md-12">
                <div class="callout callout-danger">
                    <h4>Warning!</h4>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
        <div class="col-md-3" id="signupForm">
            @component('component.form')
                @slot('form_id')
                    @if (!empty($coupon->id))
                        coupon_form333
                    @else
                        coupon_form333
                    @endif
                @endslot
                @slot('title')
                    Add a new {{$coupon_tag}}
                @endslot

                @slot('route')
                    @if (!empty($coupon->id))
                        coupon/{{$coupon->id}}/update
                    @else
                        coupon_save
                    @endif
                @endslot

                @slot('fields')
                    {{ Form::hidden('is_active', (!empty($coupon->is_active) ? $coupon->is_active : 0)) }}
                    {{ Form::hidden('coupon_type',(!empty($coupon_type) ? $coupon_type : NULL)) }}

                    <div class="form-group">
                        {{ Form::label('coupon_code', $coupon_type.' Code', array('class' => 'coupon_code')) }}
                        {{ Form::text('coupon_code', (!empty($coupon->coupon_code) ? $coupon->coupon_code : NULL), ['required', 'class' => 'form-control', 'placeholder' => 'Enter coupon code...']) }}
                    </div>


                    {{--<div class="form-group">--}}
                        {{--<div class='input-group date' id='datetimepicker1'>--}}
                            {{--<input type='text' class="form-control"/>--}}
                                 {{--<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span> </span>--}}
                        {{--</div>--}}
                    {{--</div>--}}






                    <div class="form-group">
                        {{ Form::label('start_date', 'Start Date', array('class' => 'start_date')) }}
                        <?php
                        if (!empty($coupon)) {
                            if ($coupon->start_date && $coupon->end_date) {
                                $converted = strtotime($coupon->start_date);
                                $start_date = date('m/d/Y', $converted);

                                $converted1 = strtotime($coupon->end_date);
                                $end_date = date('m/d/Y', $converted1);
                            } else {
                                $start_date = null;
                                $end_date = null;
                            }
                        }
                        ?>
                        <div class='input-group date' id='datetimepicker1'>

                            {{ Form::text('start_date', (!empty($start_date) ? $start_date : NULL), ['required', 'class' => 'form-control datepicker', 'placeholder' => 'Enter start date...']) }}

                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span> </span>
                        </div>

                    </div>
                    <div class="form-group">
                        {{ Form::label('end_date', 'End Date', array('class' => 'end_date')) }}

                        <div class='input-group date' id='datetimepicker2'>
                          {{ Form::text('end_date', (!empty($end_date) ? $end_date : NULL), ['required', 'id' => 'zdatepicker1', 'class' => 'form-control datepicker', 'placeholder' => 'Enter end date...']) }}
                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                        </div>
                    </div>
                    <div class="form-group">
                        {{ Form::label('amount_type', $coupon_type.' Type', array('class' => 'amount_type')) }}

                        <select name="amount_type" class="form-control" id="amount_type">
                            <option value="Fixed" {{ (!empty($coupon->amount_type) && ($coupon->amount_type == 'Fixed')) ? 'selected="selected"' : null }}>
                                Fixed
                            </option>
                            <option value="Percentage" {{ (!empty($coupon->amount_type) && ($coupon->amount_type == 'Percentage')) ? 'selected="selected"' : null }}>
                                Percentage
                            </option>
                        </select>
                    </div>
                    <div class="form-group">
                        {{ Form::label('price', $coupon_type.' Amount', array('class' => 'price')) }}
                        {{ Form::number('price', (!empty($coupon->price) ? $coupon->price : NULL), ['required','class' => 'form-control', 'id'=> 'price','placeholder' => 'Enter coupon amount...']) }}
                        <small id="amount_guid" class="text-danger"></small>
                    </div>
                    <div class="form-group">
                        {{ Form::label('upto_amount', $coupon_type.' Up To Value', array('class' => 'upto_amount')) }}
                        {{ Form::number('upto_amount', (!empty($coupon->upto_amount) ? $coupon->upto_amount : NULL), ['readonly','class' => 'form-control', 'placeholder' => 'Enter coupon up to value...']) }}

                    </div>
                    <div class="form-group">
                        {{ Form::label('purchase_min', 'Minimum Purchase Amount', array('class' => 'purchase_min')) }}
                        {{ Form::number('purchase_min', (!empty($coupon->purchase_min) ? $coupon->purchase_min : NULL), ['class' => 'form-control', 'placeholder' => 'Enter minimum purchase amount...']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('purchase_range', 'Purchase Range', array('class' => 'purchase_range')) }} <small class="text-muted">EX: 1000-2000</small>
                        {{ Form::text('purchase_range', (!empty($coupon->purchase_range) ? $coupon->purchase_range : NULL), ['class' => 'form-control', 'placeholder' => 'Enter purchase range amount...']) }}

                    </div>

                    <div class="form-group">
                        {{ Form::label('used_limit', 'How many uses', array('class' => 'used_limit')) }}
                        {{ Form::number('used_limit', (!empty($coupon->used_limit) ? $coupon->used_limit : 1), ['required','class' => 'form-control', 'id' => 'used_limit', 'placeholder' => 'Enter how many uses...']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('comment', 'Notes', array('class' => 'comment')) }}
                        {{ Form::text('comment', (!empty($coupon->comment) ? $coupon->comment : NULL), ['class' => 'form-control', 'placeholder' => 'Enter notes...']) }}
                    </div>

                @endslot
            @endcomponent
        </div>
        <div class="col-md-9">

            <div class="box box-success">
                <div class="box-header with-border">

                </div>
                <div class="box-body" style="">
                    <div class="box-body table-responsive no-padding" id="reload_me">
                        <table class="table table-hover">
                            <tbody>
                            <tr>
                                <th>{{$coupon_type}} code</th>
                                <th>Amount</th>


                                <th>Used Limit</th>
                                <th>Minimum Purchase</th>
                                <th>Start Date</th>
                                <th>End Date</th>


                                <th>Notes</th>
                                <th>Is Active</th>
                                <th>Action</th>
                            </tr>

                            @foreach($coupons as $coupon)
                                <tr>
                                    <td>{{ $coupon->coupon_code }}</td>
                                    <td>
                                        {{ $coupon->price }}
                                        {{ ($coupon->amount_type == 'Percentage') ? '%' : 'Fixed'}}
                                    </td>
                                    <td>{{ ($coupon->upto_amount != '') ? $coupon->upto_amount : 'N/A'}}</td>
                                    <td>{{ ($coupon->purchase_min != '') ? $coupon->purchase_min : 'N/A'}}</td>

                                    <td>{{ $coupon->start_date }}</td>
                                    <td>{{ $coupon->end_date }}</td>
                                    <td>{{ $coupon->notes }}</td>
                                    <td>{{ ($coupon->is_active == 1)? 'Active': 'Inactive'}}</td>

                                    <td>
                                        <a class="btn btn-xs btn-success"
                                           href="{{ url("edit_coupon/{$coupon->id}") }}">
                                            <i class="fa fa-pencil-square-o"></i>
                                        </a>

                                        @if($coupon->is_active == 1)
                                            <a class="btn btn-xs btn-success"
                                               href="{{ url("coupon_status/{$coupon->id}/0") }}">
                                                <i class="fa fa-check"></i>
                                            </a>
                                        @else
                                            <a class="btn btn-xs btn-danger"
                                               href="{{ url("coupon_status/{$coupon->id}/1") }}">
                                                <i class="fa fa-pause"></i>
                                            </a>

                                        @endif



                                        {{--{{ Form::open(['method' => 'delete', 'route' => ['delete_schedule', $coupon->id], 'class' => 'delete_form']) }}--}}
                                        {{--{{ Form::button('<i class="fa fa-times"></i>', array('type' => 'submit', 'class' => 'btn btn-xs btn-danger')) }}--}}
                                        {{--{{ Form::close() }}--}}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="box-footer clearfix">
                            {{ $coupons->links('component.paginator', ['object' => $coupons]) }}
                        </div>
                        <!-- /.pagination pagination-sm no-margin pull-right -->
                    </div>

                </div>
                <div class="box-footer clearfix">
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.0.0/jquery.min.js" type="text/javascript"></script>
<script src="https://admin.regalfurniturebd.com/public/cssc/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="https://admin.regalfurniturebd.com/public/cssc/jquery-ui/jquery-ui.min.js"></script>

<!-- datepicker -->
<script src="{{ URL::asset('public/cssc/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>


    <script>

        jQuery(document).ready(function ($) {
            $.noConflict();

            $(function () {
                $('#datetimepicker1').datetimepicker();
                $('#datetimepicker2').datetimepicker();
                $('.datepicker').datetimepicker();
            });

            //
            // $(document).on("focus", '#datepicker1', function (e) {
            //     e.preventDefault();
            //     $("#datepicker1").datepicker({dateFormat: 'yy-mm-dd hh:mm:ss'});
            // });

            $('#coupon_name').blur(function () {
                var m = $(this).val();
                var cute = m.toLowerCase().replace(/ /g, '-').replace(/&amp;/g, 'and').replace(/&/g, 'and').replace(/ ./g, 'dec');

                $('#coupon_css_class, #coupon_css_id, #seo_url').val(cute);
            });


            $('#coupon_name').blur(function () {
                var seo_url = $('#seo_url').val();
                var type = $('#seo_url').data('type');

                if (type == 'create') {
                    var data = {
                        'seo_url': seo_url
                    };

                    //console.log(data);

                    jQuery.ajax({
                        url: baseurl + '/check_if_cat_url_exists',
                        method: 'get',
                        data: data,
                        success: function (data) {
                            $('#seo_url').val(data.url);
                        },
                        error: function () {
                            // showError('Sorry. Try reload this page and try again.');
                            // processing.hide();
                        }
                    });
                }

            });

            //Register_type

            if ($('#amount_type option:selected').val() == 'Fixed') {
                $('#upto_amount').prop('readonly', true);
                $('#purchase_min').prop('required', true);
                $('#upto_amount').val('');
                $('#amount_guid').html('');
            } else {
                $('#upto_amount').prop('readonly', false);
                $('#purchase_min').prop('required', false);
                $('#amount_guid').html('You have to up Percentage');
            }

            $(document).on("change", '#amount_type', function (e) {
                var valu = $('#amount_type option:selected').val();
                if (valu == 'Fixed') {
                    $('#price').val('');
                    $('#upto_amount').prop('readonly', true);
                    $('#purchase_min').prop('required', true);
                    $('#upto_amount').val('');
                    $('#amount_guid').html('');
                } else {
                    $('#price').val('');
                    $('#upto_amount').prop('readonly', false);
                    $('#purchase_min').prop('required', false);
                    $('#amount_guid').html('You have to up Percentage');
                }

            });

            $(document).on("keyup", '#price', function (e) {
                var valu = $(this).val();
                //alert(valu);
                if ($('#amount_type option:selected').val() != 'Fixed') {
                    if (valu > 100) {
                        $(this).val(100);
                    } else if (valu < 1) {
                        $(this).val(1);
                    }

                } else if (valu < 1) {
                    $(this).val(1);
                }

            });


            $(document).on("blur", '#purchase_min', function (e) {

                var self = this;
                var myvalu = $(self).val();
                var type = $('#amount_type option:selected').val();
                var price = $('#price').val();
                if (type == 'Fixed' && price > myvalu) {
                    $('#purchase_min').val('');
                }

            });
            $(document).on("keyup", '#used_limit', function (e) {

                var self = this;
                var myvalu = $(self).val();
                //alert(myvalu);

                if (myvalu > 0) {
                    $('#used_limit').val(myvalu);

                } else {
                    $('#used_limit').val('');
                }

            });


        });
    </script>
    <style type="text/css">
        .datepicker.datepicker-dropdown.dropdown-menu.datepicker-orient-left.datepicker-orient-top {
            z-index: 9999 !important;
        }
    </style>
@endpush
