@extends('frontend.layouts.app')

@section('content')
    <div class="main-container container">
        <ul class="breadcrumb">
            <li><a href="#"><i class="fa fa-home"></i></a></li>
            <li><a href="#">Account</a></li>
            <li><a href="#">Register</a></li>
        </ul>

        {{ Form::open(array('url' => 'web_signup', 'method' => 'post', 'class'=> 'form-horizontal account-register clearfix', 'value' => 'PATCH', 'id' => 'web_signup', 'autocomplete' => 'off')) }}

        <div id="content" class="col-md-6">
            <h2 class="title">Register Account</h2>
            <p>If you already have an account with us, please login at the <a href="#">login page</a>.</p>

            <fieldset id="account">
                <legend>Your Personal Details</legend>
                <div class="form-group required" style="display: none;">
                    {{ Form::label('customer_group', 'Customer Group', array('class' => 'col-sm-2 control-label')) }}
                    <div class="col-sm-10">
                        <div class="radio">
                            <label>
                                <input type="radio" name="customer_group" value="8" checked="checked">
                                Default
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group required">
                    {{ Form::label('name', 'Full Name', array('class' => 'col-sm-2 control-label')) }}
                    <div class="col-sm-10">
                        {{ Form::text('name', NULL, ['required', 'class' => 'form-control', 'id'=> 'name','placeholder' => 'Enter Full Name']) }}
                    </div>
                </div>
                <div class="form-group required">
                    {{ Form::label('email', 'Email Address', array('class' => 'col-sm-2 control-label')) }}
                    <div class="col-sm-10">
                        {{ Form::email('email', NULL, ['class' => 'form-control', 'placeholder' => 'Enter Email Address']) }}
                    </div>
                </div>

                 <div class="form-group has-feedback required">
                  {{ Form::label('telephone', 'Contact Number', array('class' => 'col-sm-2 control-label')) }}
                    <div class="col-sm-10">
                        <div class="input-group">
                        <span class="input-group-addon">+88 </span>
                            {{ Form::tel('telephone', NULL, ['class' => 'form-control', 'placeholder' => 'Telephone']) }}
                         </div>
                  </div>
                </div>
              
                <div class="form-group has-feedback required">
                   {{ Form::label('emergency_contact_number', 'Emergency Contact Number', array('class' => 'col-sm-2 control-label')) }}
                   <div class="col-sm-10">
                      <div class="input-group">
                        <span class="input-group-addon">+88 </span>
                         {{ Form::tel('emergency_contact_number', NULL, ['class' => 'form-control', 'placeholder' => 'Emergency Contact Number']) }}
                      </div>
                  </div>
                </div>
            </fieldset>

            <fieldset id="address">
                <legend>Your Address</legend>
                <div class="form-group">
                    {{ Form::label('company', 'Company', array('class' => 'col-sm-2 control-label')) }}
                    <div class="col-sm-10">
                        {{ Form::text('company', NULL, ['class' => 'form-control', 'placeholder' => 'Enter company']) }}
                    </div>
                </div>

                <div class="form-group">
                    {{ Form::label('address_1', 'Address 1', array('class' => 'col-sm-2 control-label')) }}
                    <div class="col-sm-10">
                        {{ Form::text('address_1', NULL, ['class' => 'form-control', 'placeholder' => 'Enter Address 1']) }}
                    </div>
                </div>

                <div class="form-group">
                    {{ Form::label('address_2', 'Address 2', array('class' => 'col-sm-2 control-label')) }}
                    <div class="col-sm-10">
                        {{ Form::text('address_2', NULL, ['class' => 'form-control', 'placeholder' => 'Enter Address 2']) }}
                    </div>
                </div>

                <div class="form-group">
                    {{ Form::label('district', 'District', array('class' => 'col-sm-2 control-label')) }}
                    <div class="col-sm-10">

                        <?php $districts = get_districts(); ?>

                        <select name="district" class="form-control" id="district" required="required">
                            <option value="">Choose your district</option>
                            @foreach($districts as $dist)
                                <option value="{{ $dist->district }}"
                                        id="{{ $dist->district }}">
                                    {{ $dist->district }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    {{ Form::label('post_code', 'Post Code', array('class' => 'col-sm-2 control-label')) }}
                    <div class="col-sm-10">

                        {{ Form::text('post_code', NULL, ['class' => 'form-control', 'placeholder' => 'Enter post code']) }}
                    </div>
                </div>

            </fieldset>
            <fieldset>

                <legend>Your Password</legend>
                <div class="form-group required">
                    {{ Form::label('password', 'Password', array('class' => 'col-sm-2 control-label')) }}
                    <div class="col-sm-10">
                        {{ Form::password('password', ['class' => 'form-control']) }}
                    </div>
                </div>
                <div class="form-group required">
                    {{ Form::label('password_confirm', 'Password Confirm', array('class' => 'col-sm-2 control-label')) }}
                    <div class="col-sm-10">
                        {{ Form::password('password_confirm', ['class' => 'form-control']) }}
                    </div>
                </div>

            </fieldset>
            {{--<fieldset>--}}
            {{--<legend>Newsletter</legend>--}}

            {{--<div class="form-group">--}}
            {{--{{ Form::label('post_code', 'Post Code', array('class' => 'col-sm-2 control-label')) }}--}}
            {{--<div class="col-sm-10">--}}
            {{--{{ Form::text('post_code', NULL, ['class' => 'form-control', 'placeholder' => 'Enter post code']) }}--}}
            {{--</div>--}}
            {{--</div>--}}

            {{--<div class="form-group">--}}
            {{--{{ Form::label('subscribe', 'Subscribe', array('class' => 'col-sm-2 control-label')) }}--}}
            {{--<div class="col-sm-10">--}}
            {{--<input type="radio" id="test1" name="radio-group" checked>--}}
            {{--<label for="test1">Yes</label>--}}
            {{--<input type="radio" id="test2" name="radio-group" checked>--}}
            {{--<label for="test2">No</label>--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--</fieldset>--}}
            <div class="buttons">
                <div class="pull-right">
                    I have read and agree to the
                    <a href="{{ url('pricing_tables') }}" class="agree">
                        <b>Pricing Tables</b>
                    </a>
                    <input class="box-checkbox" type="checkbox" name="agree" value="1"> &nbsp;
                    <input type="submit" value="Continue" class="btn btn-back-one">
                </div>
            </div>

            {{ Form::close() }}

        </div>
    </div>
@endsection