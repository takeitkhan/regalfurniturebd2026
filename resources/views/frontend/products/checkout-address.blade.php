@extends('frontend.layouts.app')

@section('content')

    <section class="breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="breadcrumb-warp">
                        <div class="breadcrumb-one">
                            <nav aria-label="breadcrumb">
                                <div class="breadcrumb breadcrumb_one ">
                                    <?php $tksign = '&#2547; '; ?>
                                        <?php
                                        $breadcrumbs = new Creitive\Breadcrumbs\Breadcrumbs;

                                        $breadcrumbs->setDivider('');
                                         $breadcrumbs->setDivider('');
                                        $breadcrumbs->addCrumb('Home', url('/'))
                                         ->addCrumb('Delivery Address', 'product');
                                        echo $breadcrumbs->render();
                                        ?>

                                    <!-- <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Shopping Cart</li> -->
                                </div>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>




    <div class="main-container container">

    <!--     <ul class="breadcrumb">
            <?php $tksign = '&#2547; '; ?>
            <?php
            $breadcrumbs = new Creitive\Breadcrumbs\Breadcrumbs;

            $breadcrumbs->setDivider('');
            $breadcrumbs->addCrumb('<i class="fa fa-home"></i>', url('/'))
                ->addCrumb('Delivery Address', 'product');
            echo $breadcrumbs->render();
            ?>
        </ul> -->



        @if(Session::has('cart'))
            <div class="row">
                <!--Middle Part Start-->
                <div id="content" class="col-sm-12">
                    <h2 class="title">Delivery Address</h2>
                    <div class="so-onepagecheckout row">
                        <?php
                        $user = Auth::user();
                        $data = Session::all();

                        if ($user == null) {
                            if (!empty($data['user_details'])) {
                                //dd($data['user_details']);
                                $id = $data['user_details']['user_id'][0]??'';
                                $name = $data['user_details']['name']??'';
                                $phone = $data['user_details']['phone']??'';
                                $e_phone = $data['user_details']['emergency_phone']??'';
                                $email = $data['user_details']['email']??'';
                                $address = $data['user_details']['address']??'';
                                $username = $data['user_details']['username']??'';
                                $password = '';
                                $postcode = $data['user_details']['postcode']??'';
                                $district = $data['user_details']['district']??'';
                            } else {
                                $id = '';
                                $name = '';
                                $phone = '';
                                $e_phone = '';
                                $email = '';
                                $address = '';
                                $username = '';
                                $password = '';
                                $postcode = '';
                                $district = '';
                            }
                        } else {
                            $id = $user->id??'';
                            $name = $user->name??'';
                            $phone = $user->phone??'';
                            $e_phone = $user->emergency_phone??'';
                            $email = $user->email??'';
                            $address = $user->address??'';
                            $username = $user->username??'';
                            $password = '';
                            $postcode = $user->postcode??'';
                            $district = $user->district??'';
                        }

                        //dump($district);
                        ?>
                        <div class="col-md-12">
                        {{ Form::open(array('url' => '/checkout/delivery_address', 'method' => 'post', 'value' => 'PATCH', 'autocomplete' => 'off', 'id' => 'delivery_address')) }}
                        
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card card_258">
                                        <div class="card-header card-header_258">
                                            <h4 class="address-p_title">
                                                <i class="fa fa-map-marker"></i> Delivery Address
                                                <span style="display:block; font-size: 13px; margin-top: 5px; font-weight: normal;">
                                                        To add a new delivery address, please fill out the form below.
                                                </span>
                                            </h4>
                                        </div>
                                        <div class="card-body card-body_258">
                                            @include('frontend.common.message_handler')
                                            <div id="account">
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
                                                    <div class="input-group mb-3">
                                                      <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1">+88</span>
                                                      </div>
                                                        {{ Form::text('mobile', $phone, ['required', 'class' => 'form-control', 'for' => 'input-payment-telephone', 'placeholder' => 'Mobile Number']) }}
                                                    </div>
                                                </div>

                                                <div class="form-group has-feedback required">
                                                    {{ Form::label('emergency_phone', 'Emergency Mobile Number', array('class' => 'control-label', 'for' => 'input-payment-telephone')) }}
                                                    <div class="input-group mb-3">
                                                      <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1">+88</span>
                                                      </div>
                                                      {{ Form::text('emergency_mobile', $e_phone, ['required', 'class' => 'form-control', 'for' => 'input-payment-telephone', 'placeholder' => 'Emergency Mobile Number']) }}
                                                    </div>
                                                </div>
                                                

                                                <div class="form-group required">
                                                    {{ Form::label('email', 'Email', array('class' => 'control-label', 'for' => 'input-payment-email')) }}
                                                    {{ Form::email('email', $email, ['required', 'class' => 'form-control', 'for' => 'input-payment-email', 'placeholder' => 'Email ']) }}
                                                    <div class="show_message"></div>
                                                </div>
                                                <div class="form-group required">
                                                    {{ Form::label('districts', 'Choose your districts', array('class' => 'control-label', 'for' => 'input-payment-country')) }}

                                                    <?php $districts = get_districts();  ?>

                                                    <select name="district" class="form-control" id="district" required="required">
                                                        <option value="">Choose your district</option>
                                                        @foreach($districts as $dist)
                                                            <option value="{{ $dist->district }}"
                                                                    id="{{ $dist->district }}"
                                                                    {{ !empty($district == $dist->district) ? 'selected="selected"' : null }}>
                                                                {{ $dist->district }}
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                    {{ Form::hidden('deliveryfee', null, ['required', 'id' => 'deliveryfee']) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card card_258">
                                        <div class="card-header card-header_258">
                                            <h4 class="address-p_title"><i class="fa fa-book"></i> Your Address</h4>
                                        </div>
                                        <div class="card-body">
                                            <div id="address" class="required">

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
                                                    {!! Form::label('permissions', 'Create my user account') !!}

                                                    <div class="passfield"
                                                         style="display: {!! (!empty($username) ? ' block ' : 'none') !!};">
                                                        <div class="form-group">
                                                            {{ Form::label('username', 'Username', array('class' => 'title')) }}
                                                            {{ Form::text('username', !empty($username) ? $username : '', ['id' => 'username', 'class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => 'Username ']) }}
                                                        </div>
                                                        <div class="form-group">
                                                            {{ Form::label('password', 'Password', array('class' => 'title')) }}
                                                            {{ Form::password('password', ['id' => 'password', 'class' => 'form-control', 'placeholder' => 'Password', 'autocomplete' => 'off', $required]) }}
                                                        </div>
                                                    </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="card card_258" style="margin-top: 10px;">
                                        <div class="card-footer" style="overflow: hidden; border-top: none;">
                                            <div class="buttons carring-btn-gp">

                                                <div class="pull-left">
                                                    <a href="{{ url()->previous() }}" class="btn btn-back-one">
                                                        <span><i class="fa fa-long-arrow-left"></i></span> Back
                                                    </a>
                                                </div>
                                                <div class="pull-right">
                                                    <button class="btn btn-back-two" type="submit">
                                                        Next <span><i class="fa fa-long-arrow-right"></i></span>
                                                    </button>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        
                        {{ Form::close() }}
                        </div>

                        <!-- <div class="col-md-3">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class=""><i class="fa fa-sign-in"></i> Create an Account or Login
                                    </h4>
                                </div>
                                <div class="card-body bun-stp blcke">
                                    <a class="active" href="{{ url('checkout/address') }}">Guest Checkout</a>
                                    <a href="{{ url('login_now') }}">Returning Customer</a>
                                    <a href="{{ url('create_an_account') }}">Register Account</a>
                                </div>
                            </div>
                        </div> -->
                    </div>
                </div>
            </div>
        @else
            <h3>Opps... You have not added any product on your cart yet.</h3>
        @endif
        <?php
        // $data = Session::all();
        // if (!empty($data['cart'])) {
        //     dump($data['cart']);
        // }
        // if (!empty($data['user_details'])) {
        //     dump($data['user_details']);
        // }

        // if (!empty($data['payment_method'])) {
        //     dump($data['payment_method']);
        // }
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

            $('#email').on('keyup', function () {
                delay(function () {
                    var data = {
                        'email': $(this).val()
                    };

                    $.ajax({
                        url: baseurl + '/check_if_email_exists',
                        method: 'get',
                        data: data,
                        success: function (data) {
                            $('.show_message').html(data);
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