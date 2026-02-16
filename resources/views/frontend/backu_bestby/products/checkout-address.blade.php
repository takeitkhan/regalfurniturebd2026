@extends('frontend.layouts.app')

@section('content')


    <div class="main-container container">
        <?php $tksign = '&#2547; '; ?>


        <ul class="breadcrumb">
            <li><a href="{{ url('/') }}"><i class="fa fa-home"></i></a></li>
            <li> <a href="{{ url('/view_cart') }}">Shopping Cart</a></li>
            <li>Delivery Address</li>
        </ul>

        @if(Session::has('cart'))
            <div class="row">
                <!--Middle Part Start-->
                <div id="content" class="col-sm-12">
                    <h2 class="title">Delivery Address</h2>
                    <div class="so-onepagecheckout row">
                        <?php
                        $user = Auth::user();
                        $data = Session::all();
                        //dd($user);

                        if ($user == null) {
                            if (!empty($data['user_details'])) {

                                $id = $data['user_details']['user_id'][0];
                                $name = $data['user_details']['name'];
                                $mobile = $data['user_details']['mobile'];
                                $e_mobile = $data['user_details']['emergency_mobile'];
                                $email = $data['user_details']['email'];
                                $address = $data['user_details']['address'];
                                $username = $data['user_details']['username'];
                                $password = '';
                                // $postcode = $data['user_details']['postcode'];
                                $district = $data['user_details']['district'];
                            } else {
                                $id = '';
                                $name = '';
                                $mobile = '';
                                $e_mobile = '';
                                $email = '';
                                $address = '';
                                $username = '';
                                $password = '';
                                // $postcode = '';
                                $district = '';
                            }
                        } else {
                            //dump($user);
                            $id = $user->id;
                            $name = $user->name;
                            $mobile = $user->phone;
                            $e_mobile = $user->emergency_phone;
                            $email = $user->email;
                            $address = $user->address;
                            $username = $user->username;
                            $password = '';
                            // $postcode = $user->postcode;
                            $district = $user->district;
                        }

                        //dump($district);
                        ?>

                        {{ Form::open(array('url' => '/checkout/delivery_address', 'method' => 'post', 'value' => 'PATCH', 'autocomplete' => 'off', 'id' => 'delivery_address')) }}
                        <div class="col-right col-sm-9">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <i class="fa fa-map-marker"></i> Delivery Address
                                                <span style="display:block; font-size: 13px; margin-top: 5px; font-weight: normal;">
                                                        To add a new delivery address, please fill out the form below.
                                                    </span>
                                            </h4>
                                        </div>
                                        <div class="panel-body">

                                            @include('frontend.common.message_handler')

                                            <fieldset id="account">
                                                {{ csrf_field() }}
                                                <div class="form-group">
                                                    {{ Form::hidden('id', $id) }}
                                                </div>

                                                <div class="form-group required">
                                                    {{ Form::label('name', 'Full Name', array('class' => 'control-label', 'for' => 'input-payment-firstname')) }}
                                                    {{ Form::text('name', $name, ['required', 'class' => 'form-control', 'for' => 'input-payment-firstname', 'placeholder' => 'Full Name']) }}
                                                </div>

                                                <div class="form-group has-feedback required">
                                                    {{ Form::label('phone', 'Mobile Number', array('class' => 'control-label', 'for' => 'input-payment-telephone')) }}
                                                    <div class="input-group">
                                                        <span class="input-group-addon">+88 </span>
                                                        {{ Form::text('mobile', $mobile, ['required', 'class' => 'form-control', 'for' => 'input-payment-telephone', 'placeholder' => 'Mobile Number']) }}
                                                    </div>

                                                </div>

                                                <div class="form-group has-feedback required">
                                                    {{ Form::label('emergency_phone', 'Emergency Mobile Number', array('class' => 'control-label', 'for' => 'input-payment-telephone')) }}
                                                    <div class="input-group">
                                                        <span class="input-group-addon">+88 </span>
                                                        {{ Form::text('emergency_mobile', $e_mobile, ['required', 'class' => 'form-control', 'for' => 'input-payment-telephone', 'placeholder' => 'Emergency Mobile Number']) }}
                                                    </div>
                                                </div>
                                                <div class="form-group required">
                                                    {{ Form::label('email', 'Email', array('class' => 'control-label', 'for' => 'input-payment-email')) }}
                                                    {{ Form::email('email', $email, ['required', 'class' => 'form-control', 'for' => 'input-payment-email', 'placeholder' => 'Email ']) }}
                                                    <div class="show_message" style="color: #fb3b4e"></div>
                                                </div>
                                                <div class="form-group required">
                                                    {{ Form::label('districts', 'Choose your districts', array('class' => 'control-label', 'for' => 'input-payment-country')) }}

                                                    <?php $districts = get_districts();  ?>

                                                    <select name="district" class="form-control" id="district"
                                                            required="required">
                                                        {{-- <option value="">Choose your district</option> --}}
                                                        @foreach($districts as $dist)
                                                            {{-- <option value="{{ $dist->district }}"
                                                                    id="{{ $dist->district }}"
                                                                    {{ !empty($district == $dist->district) ? 'selected="selected"' : null }}>
                                                                {{ $dist->district }}
                                                            </option> --}}
                                                            <option value="{{ $dist->district }}" selected>{{ $dist->district }}</option>
                                                        @endforeach
                                                    </select>

                                                    {{ Form::hidden('deliveryfee', null, ['required', 'id' => 'deliveryfee']) }}
                                                </div>
                                                <div class="form-group required">
                                                    {{ Form::label('Thana', 'Choose your location', array('class' => 'control-label', 'for' => 'input-payment-country')) }}
                                                    <select name="thana" class="form-control" id="location"
                                                            required="required">
                                                        {{-- <option value="">Choose your location</option> --}}
                                                        @foreach($thana as $tha)
                                                            <option value="{{ $tha->thana }}" selected>{{ $tha->thana }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </fieldset>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title"><i class="fa fa-book"></i> Your Address</h4>
                                        </div>
                                        <div class="panel-body">
                                            <fieldset id="address" class="required">

                                                <div class="form-group required">
                                                    {{ Form::label('address', 'Address', array('class' => 'control-label', 'for' => 'input-payment-address-1')) }}
                                                    {{ Form::textarea('address', $address, ['required', 'class' => 'form-control', 'for' => 'input-payment-address-2', 'placeholder' => 'Your Address', 'rows' => 3]) }}
                                                </div>

                                                <div class="form-group password-group">
                                                    <?php if (!Auth::check()) { ?>
                                                    <?php
                                                    $checked = (!empty($username) ? ' checked ' : null);
                                                    $required = (!empty($username) ? ' required ' : null);
                                                    ?>
                                                    {!! Form::checkbox('create-account', TRUE, FALSE, ['class' => 'square', 'id' => 'create-account', $checked]) !!}
                                                    {{-- {!! Form::label('permissions', 'Create my user account', ['for' => 'create-account']) !!} --}}
                                                    <label for="create-account">Create my user account</label>
                                                    <div class="passfield"
                                                         style="display: {!! (!empty($username) ? ' block ' : 'none') !!};">
                                                        <div class="form-group">
                                                            {{ Form::label('username', 'Email', array('class' => 'title')) }}
                                                            {{ Form::text('username', !empty($username) ? $username : '', ['id' => 'username', 'class' => 'form-control', 'autocomplete' => 'off', 'readonly' => true,'placeholder' => 'Email ']) }}
                                                        </div>
                                                        <div class="form-group">
                                                            {{ Form::label('password', 'Password', array('class' => 'title')) }}
                                                            {{ Form::password('password', ['id' => 'password', 'class' => 'form-control', 'placeholder' => 'Password', 'autocomplete' => 'off', $required]) }}
                                                        </div>
                                                    </div>
                                                    <?php } ?>
                                                </div>


                                                {{--<div class="checkbox">--}}
                                                {{--<label>--}}
                                                {{--<input type="checkbox" checked="checked" value="1"--}}
                                                {{--name="shipping_address">--}}
                                                {{--If you want to create a new user account--}}
                                                {{--</label>--}}
                                                {{--</div>--}}
                                                {{--<br>--}}
                                                {{--<div class="form-group">--}}
                                                {{--<label for="input-payment-address-2" class="control-label">--}}
                                                {{--Add Comments About Your Order--}}
                                                {{--</label>--}}
                                                {{--<textarea type="text" class="form-control"--}}
                                                {{--id="input-payment-address-2"--}}
                                                {{--placeholder="Add Comments About Your Order" value=""--}}
                                                {{--name="address_2"></textarea>--}}
                                                {{--</div>--}}

                                                {{--<div class="checkbox">--}}
                                                {{--<label>--}}
                                                {{--<input type="checkbox" checked="checked" value="1"--}}
                                                {{--name="shipping_address">--}}
                                                {{--I have read and agree to the--}}
                                                {{--<strong>Terms & Conditions</strong>--}}
                                                {{--</label>--}}
                                                {{--</div>--}}

                                            </fieldset>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    {{--<div class="checkbox">--}}
                                    {{--<label>--}}
                                    {{--<input type="checkbox" checked="checked" value="1"--}}
                                    {{--name="shipping_address">--}}
                                    {{--<strong>--}}
                                    {{--If Delivery Address& Billing Address is Different--}}
                                    {{--</strong>--}}
                                    {{--</label>--}}
                                    {{--</div>--}}
                                    <div class="panel panel-default" style="margin-top: 10px;">
                                        <div class="panel-footer" style="overflow: hidden; border-top: none;">
                                            <div class="buttons carring-btn-gp">

                                                <div class="pull-left">
                                                    <a href="{{ url()->previous() }}" class="btn btn-back-one">
                                                        Back
                                                    </a>
                                                </div>
                                                <div class="pull-right">
                                                    <button class="btn btn-two" type="submit">
                                                        Next
                                                    </button>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{ Form::close() }}

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
        <?php
//        $data = Session::all();
//        if (!empty($data['cart'])) {
//            dump($data['cart']);
//        }
//        if (!empty($data['user_details'])) {
//            dump($data['user_details']);
//        }
//
//        if (!empty($data['payment_method'])) {
//            dump($data['payment_method']);
//        }
        ?>
    </div>
@endsection
@section('cusjs')
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $.noConflict();

            $(function () {
                $('#create-account').on('click', function () {
                    if ($(this).is(":checked")) {
                        $("#username").attr("autocomplete", "off");
                        $("#password").attr("autocomplete", "off");

                        $('.passfield').css('display', 'block');
                        $('#password').attr('required', 'required');
                        $('#username').attr('required', 'required');
                    } else {
                        $("#username").attr("autocomplete", "off");
                        $("#password").attr("autocomplete", "off");

                        $('.passfield').css('display', 'none');
                        $('#password').removeAttr('required', 'required');
                        $('#username').removeAttr('required', 'required');
                    }
                });
            });

            $('#email').on('blur', function () {
                delay(function () {
                    var email = $('#email').val();
                    var data = {
                        'email': email
                    };
                   // alert(email);



                    $.ajax({
                        url: baseurl + '/check_if_email_exists',
                        method: 'get',
                        data: data,
                        success: function (data) {
                            if(data.data == 'Email already exists'){
                                $('#username').val('');
                                $('#email').val('');
                                $('.show_message').html(data.data);
                            }else {
                                $('#username').val(email);
                            }

                           // console.log(data.data);
                        },
                        error: function () {
                            // showError('Sorry. Try reload this page and try again.');
                            // processing.hide();
                        }
                    });
                }, 1000);

            });

            $('#district').on('change', function () {
                var dist = $(this).val();

                if (dist == 'Dhaka') {
                    var fee = '<?php echo get_delivery_fee(true); ?>';
                    $('#deliveryfee').val(fee);
                } else {
                    var fee = '<?php echo get_delivery_fee(false); ?>';
                    $('#deliveryfee').val(fee);
                }
            });

        });

        var delay = (function () {
            var timer = 0;
            return function (callback, ms) {
                clearTimeout(timer);
                timer = setTimeout(callback, ms);
            };
        })();
    </script>
    <style type="text/css">
        .password-group label {
            color: #666666;
            font-weight: 500;
        }
    </style>
@endsection