@extends('frontend.layouts.app')

@section('content')


    <div class="main-container container">
        <ul class="breadcrumb">
            <?php $tksign = '&#2547; '; ?>
            <?php
            $breadcrumbs = new Creitive\Breadcrumbs\Breadcrumbs;

            $breadcrumbs->setDivider('');
            $breadcrumbs->addCrumb('<i class="fa fa-home"></i>', url('/'))
                ->addCrumb('Delivery Address', 'product');
            echo $breadcrumbs->render();
            ?>
        </ul>

            <div class="row">
                <!--Middle Part Start-->
                <div id="content" class="col-sm-12">

                    <div class="so-onepagecheckout row">


                        {{ Form::open(array('url' => '/save_vendor', 'method' => 'post', 'value' => 'PATCH', 'autocomplete' => 'off', 'id' => 'delivery_address')) }}
                        <div class="col-right col-sm-12">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                Be A Vendor
                                            </h4>
                                        </div>
                                        <div class="panel-body">

                                            @include('frontend.common.message_handler')

                                            <fieldset id="account">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        {{ csrf_field() }}

                                                        <div class="form-group required">
                                                            {{ Form::label('name', 'Full Name', array('class' => 'control-label', 'for' => 'input-payment-firstname')) }}
                                                            {{ Form::text('name', null, ['required', 'class' => 'form-control', 'for' => 'input-payment-firstname', 'placeholder' => 'Full Name']) }}
                                                        </div>

                                                        <div class="form-group has-feedback required">
                                                            {{ Form::label('phone', 'Mobile Number', array('class' => 'control-label', 'for' => 'input-payment-telephone')) }}
                                                            <div class="input-group">
                                                                <span class="input-group-addon">+88 </span>
                                                                {{ Form::text('phone', null, ['required', 'class' => 'form-control', 'for' => 'input-payment-telephone', 'placeholder' => 'Mobile Number']) }}
                                                            </div>
                                                        </div>

                                                        <div class="form-group has-feedback required">
                                                            {{ Form::label('emergency_phone', 'Emergency Mobile Number', array('class' => 'control-label', 'for' => 'input-payment-telephone')) }}
                                                            <div class="input-group">
                                                                <span class="input-group-addon">+88 </span>
                                                                {{ Form::text('emergency_mobile', null, ['required', 'class' => 'form-control', 'for' => 'input-payment-telephone', 'placeholder' => 'Emergency Mobile Number']) }}
                                                            </div>
                                                        </div>

                                                        <div class="form-group required">
                                                            {{ Form::label('email', 'Email', array('class' => 'control-label', 'for' => 'input-payment-email')) }}
                                                            {{ Form::email('email', null, ['required', 'class' => 'form-control', 'for' => 'input-payment-email', 'placeholder' => 'Email ']) }}
                                                            <div class="show_message" style="color: #fb3b4e"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group required">
                                                            {{ Form::label('birthday', 'Birthday', array('class' => 'control-label', 'for' => 'input-payment-birthday')) }}
                                                            {{ Form::date('birthday', null, ['required', 'class' => 'form-control', 'for' => 'input-payment-birthday', 'placeholder' => 'Birthday']) }}
                                                        </div>



                                                        <div class="form-group required">
                                                            {{ Form::label('gender', 'Gender', array('class' => 'gender')) }}

                                                            <select name="gender" class="form-control" id="gender" required="required">
                                                                <option value="Male">Male</option>
                                                                <option value="Female">Female</option>
                                                                <option value="Others">Others</option>
                                                            </select>
                                                        </div>
                                                        

                                                        <div class="form-group required">
                                                            {{ Form::label('marital_status', 'Marital Status', array('class' => 'marital_status')) }}

                                                           <select name="marital_status" class="form-control" id="marital_status" required="required">
                                                                <option value="Married">Married</option>
                                                                <option value="Single">Single</option>
                                                                <option value="Widow">Widow</option>
                                                                <option value="Others">Others</option>
                                                            </select>

                                                        </div>


                                                        <div class="form-group required">
                                                            {{ Form::label('father', 'Father', array('class' => 'father')) }}
                                                            {{ Form::text('father', (!empty($user->father) ? $user->father : NULL), ['class' => 'form-control', 'placeholder' => 'Enter father...']) }}
                                                        </div>


                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group required">
                                                            {{ Form::label('mother', 'Mother', array('class' => 'mother')) }}
                                                            {{ Form::text('mother', (!empty($user->mother) ? $user->mother : NULL), ['class' => 'form-control', 'placeholder' => 'Enter mother...']) }}
                                                        </div>

                                                        <div class="form-group required">
                                                            {{ Form::label('address', 'Address', array('class' => 'address')) }}
                                                            {{ Form::text('address', (!empty($user->address) ? $user->address : NULL), ['class' => 'form-control', 'placeholder' => 'Enter address...']) }}
                                                        </div>

                                                        <div class="form-group required">
                                                            {{ Form::label('company', 'Company', array('class' => 'company')) }}
                                                            {{ Form::text('company', (!empty($user->company) ? $user->company : NULL), ['class' => 'form-control', 'placeholder' => 'Enter company...']) }}
                                                        </div>

                                                        <div class="form-group required">
                                                            {{ Form::label('password', 'Password', array('class' => 'password')) }}
                                                            {{ Form::text('password', NULL, ['type' => 'password', 'class' => 'form-control', 'placeholder' => 'Enter new password...']) }}
                                                        </div>

                                                    </div>
                                                </div>

                                            </fieldset>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-12">

                                    <div class="panel panel-default" style="margin-top: 10px;">
                                        <div class="panel-footer" style="overflow: hidden; border-top: none;">
                                            <div class="buttons carring-btn-gp">


                                                <div class="pull-right">
                                                    <button class="btn btn-two" type="submit">
                                                        Submit
                                                    </button>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{ Form::close() }}


                    </div>
                </div>
            </div>


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