@extends('layouts.app')

@section('title', 'Payment Settings')
@section('sub_title', 'payment settings modification panel')
@section('content')
    <div class="row">
        @if(!empty($payment_settings))
            @php
                $setting = $payment_settings[0];

                if (!empty($setting)) {
                    if ($setting->bkash_active == 1) {
                        $bkash = TRUE;
                    } else {
                        $bkash = FALSE;
                    }
                    if ($setting->nagad_active == 1) {
                        $nagad = TRUE;
                    } else {
                        $nagad = FALSE;
                    }
                    if ($setting->debitcredit_active == 1) {
                        $dcc = TRUE;
                    } else {
                        $dcc = FALSE;
                    }
                    if ($setting->mobilebanking_active == 1) {
                        $mb = TRUE;
                    } else {
                        $mb = FALSE;
                    }
                    if ($setting->citybank_active == 1) {
                        $ctb = TRUE;
                    } else {
                        $ctb = FALSE;
                    }
                    if ($setting->rocket_active == 1) {
                        $rocket = TRUE;
                    } else {
                        $rocket = FALSE;
                    }
                    if ($setting->cashondelivery_active == 1) {
                        $cod = TRUE;
                    } else {
                        $cod = FALSE;
                    }
                    if ($setting->rp_active == 1) {
                        $rpa = TRUE;
                    } else {
                        $rpa = FALSE;
                    }
                } else {
                    $bkash = FALSE;
                    $nagad = FALSE;
                    $dcc = FALSE;
                    $mb = FALSE;
                    $rocket = FALSE;
                    $cod = FALSE;
                }
            @endphp
        @endif
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

        <div class="col-md-12">
            @component('component.form')
                @slot('form_id')
                    @if (!empty($setting->id))
                        setting_forms
                    @else
                        setting_form
                    @endif
                @endslot
                @slot('title')
                    @if (!empty($setting->id))
                        Edit setting
                    @else
                        Add a new setting
                    @endif

                @endslot

                @slot('route')
                    @if (!empty($setting->id))
                        payment_setting/{{$setting->id}}/update
                    @else
                        payment_setting_save
                    @endif
                @endslot

                @slot('fields')
                    <div class="row">
                        <div class="col-md-6">

                            <div class="form-group">
                                {{ Form::label('admin_cell_one', 'Admin Cell One', array('class' => 'admin_cell_one')) }}
                                {{ Form::text('admin_cell_one', (!empty($setting->admin_cell_one) ? $setting->admin_cell_one : NULL), ['required', 'class' => 'form-control', 'placeholder' => 'Enter Main Admin Phone No...']) }}
                            </div>
                            <div class="form-group">
                                {{ Form::label('admin_cell_two', 'Admin Cell Two', array('class' => 'admin_cell_two')) }}
                                {{ Form::text('admin_cell_two', (!empty($setting->admin_cell_two) ? $setting->admin_cell_two : NULL), ['required', 'class' => 'form-control', 'placeholder' => 'Enter Admin Phone No...']) }}
                            </div>
                            <div class="form-group">
                                {{ Form::label('admin_cell_three', 'Admin Cell Three', array('class' => 'admin_cell_three')) }}
                                {{ Form::text('admin_cell_three', (!empty($setting->admin_cell_three) ? $setting->admin_cell_three : NULL), ['class' => 'form-control', 'placeholder' => 'Enter Admin Phone No...']) }}
                            </div>
                            <div class="form-group">
                                {{ Form::label('admin_cell_four', 'Admin Cell Four', array('class' => 'admin_cell_four')) }}
                                {{ Form::text('admin_cell_four', (!empty($setting->admin_cell_four) ? $setting->admin_cell_four : NULL), ['class' => 'form-control', 'placeholder' => 'Enter Admin Phone No...']) }}
                            </div>
                            <div class="form-group">
                                {{ Form::label('admin_cell_five', 'Admin Cell Five', array('class' => 'admin_cell_five')) }}
                                {{ Form::text('admin_cell_five', (!empty($setting->admin_cell_five) ? $setting->admin_cell_five : NULL), ['class' => 'form-control', 'placeholder' => 'Enter Admin Phone No...']) }}
                            </div>


                            <div class="form-group">
                                {{ Form::label('decidable_amount', 'Delivery fee fraction (Inside Dhaka)', array('class' => 'decidable_amount')) }}
                                {{ Form::number('decidable_amount', (!empty($setting->decidable_amount) ? $setting->decidable_amount : 0), ['required', 'class' => 'form-control', 'placeholder' => 'Enter Delivery fee fraction...']) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('inside_dhaka_fee', 'Inside Dhaka', array('class' => 'inside_dhaka_fee')) }}
                                {{ Form::number('inside_dhaka_fee', (!empty($setting->inside_dhaka_fee) ? $setting->inside_dhaka_fee : 0), ['class' => 'form-control', 'placeholder' => 'Enter Inside Dhaka Fee...']) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('outside_dhaka_fee', 'Outside Dhaka', array('class' => 'outside_dhaka_fee')) }}
                                {{ Form::number('outside_dhaka_fee', (!empty($setting->outside_dhaka_fee) ? $setting->outside_dhaka_fee : 0), ['class' => 'form-control', 'placeholder' => 'Enter Outside Dhaka Fee...']) }}
                            </div>


                            <div class="form-group">
                                {{ Form::label('decidable_amount_od', 'Delivery fee fraction (Outside Dhaka)', array('class' => 'decidable_amount_od')) }}
                                {{ Form::number('decidable_amount_od', (!empty($setting->decidable_amount_od) ? $setting->decidable_amount_od : 0), ['required', 'class' => 'form-control', 'placeholder' => 'Enter delivery fee fraction...']) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('inside_dhaka_od', 'Inside Dhaka', array('class' => 'inside_dhaka_od')) }}
                                {{ Form::number('inside_dhaka_od', (!empty($setting->inside_dhaka_od) ? $setting->inside_dhaka_od : 0), ['class' => 'form-control', 'placeholder' => 'Enter Inside Dhaka Fee...']) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('outside_dhaka_od', 'Outside Dhaka', array('class' => 'outside_dhaka_od')) }}
                                {{ Form::number('outside_dhaka_od', (!empty($setting->outside_dhaka_od) ? $setting->outside_dhaka_od : 0), ['class' => 'form-control', 'placeholder' => 'Enter Outside Dhaka Fee...']) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('first_range', 'First Range', array('class' => 'first_range')) }}
                                {{ Form::text('first_range', (!empty($setting->first_range) ? $setting->first_range : NULL), ['class' => 'form-control', 'placeholder' => 'Enter first range...']) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('first_range_discount', 'First Range Discount (In % percentage)', array('class' => 'first_range_discount')) }}
                                {{ Form::text('first_range_discount', (!empty($setting->first_range_discount) ? $setting->first_range_discount : NULL), ['class' => 'form-control', 'placeholder' => 'Enter first range discount...']) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('second_range', 'Second Range', array('class' => 'second_range')) }}
                                {{ Form::text('second_range', (!empty($setting->second_range) ? $setting->second_range : NULL), ['class' => 'form-control', 'placeholder' => 'Enter second range...']) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('second_range_discount', 'Second Range Discount (In % percentage)', array('class' => 'second_range_discount')) }}
                                {{ Form::text('second_range_discount', (!empty($setting->second_range_discount) ? $setting->second_range_discount : NULL), ['class' => 'form-control', 'placeholder' => 'Enter second range discount...']) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('bkash_active', 'bKash', array('class' => 'bkash_active')) }}
                                <div class="radio">
                                    <label>
                                        {{ Form::radio('bkash_active', 1, ($bkash == TRUE) ? TRUE : FALSE, ['class' => 'radio']) }}
                                        Active
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        {{ Form::radio('bkash_active', 0, ($bkash == FALSE) ? TRUE : FALSE, ['class' => 'radio']) }}
                                        Deactive
                                    </label>
                                </div>
                                {{ Form::label('image_bkash', 'bKash Logo', array('class' => 'image_bkash')) }}
                                {{ Form::text('image_bkash', (!empty($setting->image_bkash) ? $setting->image_bkash : NULL), ['class' => 'form-control', 'placeholder' => 'Enter bKash Logo...']) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('nagad_active', 'Nagad', array('class' => 'nagad_active')) }}
                                <div class="radio">
                                    <label>
                                        {{ Form::radio('nagad_active', 1, ($nagad == TRUE) ? TRUE : FALSE, ['class' => 'radio']) }}
                                        Active
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        {{ Form::radio('nagad_active', 0, ($nagad == FALSE) ? TRUE : FALSE, ['class' => 'radio']) }}
                                        Deactive
                                    </label>
                                </div>
                                {{ Form::label('image_nagad', 'Nagad Logo', array('class' => 'image_nagad')) }}
                                {{ Form::text('image_nagad', (!empty($setting->image_nagad) ? $setting->image_nagad : NULL), ['class' => 'form-control', 'placeholder' => 'Enter Nagad Logo...']) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('mobilebanking_active', 'Mobile Banking', array('class' => 'mobilebanking_active')) }}
                                <div class="radio">
                                    <label>
                                        {{ Form::radio('mobilebanking_active', 1, ($mb == TRUE) ? TRUE : FALSE, ['class' => 'radio']) }}
                                        Active
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        {{ Form::radio('mobilebanking_active', 0, ($mb == FALSE) ? TRUE : FALSE, ['class' => 'radio']) }}
                                        Deactive
                                    </label>
                                </div>

                                {{ Form::label('image_mobilebanking', 'Mobile Banking Logo', array('class' => 'image_mobilebanking')) }}
                                {{ Form::text('image_mobilebanking', (!empty($setting->image_mobilebanking) ? $setting->image_mobilebanking : NULL), ['class' => 'form-control', 'placeholder' => 'Enter Mobile Banking Logo...']) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('cashondelivery_active', 'Cash On Delivery', array('class' => 'cashondelivery_active')) }}
                                <div class="radio">
                                    <label>
                                        {{ Form::radio('cashondelivery_active', 1, ($cod == TRUE) ? TRUE : FALSE, ['class' => 'radio']) }}
                                        Active
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        {{ Form::radio('cashondelivery_active', 0, ($cod == FALSE) ? TRUE : FALSE, ['class' => 'radio']) }}
                                        Deactive
                                    </label>
                                </div>
                                {{ Form::label('image_cashondelivery', 'Cash on delivery Logo', array('class' => 'image_cashondelivery')) }}
                                {{ Form::text('image_cashondelivery', (!empty($setting->image_cashondelivery) ? $setting->image_cashondelivery : NULL), ['class' => 'form-control', 'placeholder' => 'Enter Cash on delivery Logo...']) }}

                            </div>


                        </div>
                        <div class="col-md-6">

                            <div class="form-group">
                                {{ Form::label('debitcredit_active', 'Debit Or Credit Card', array('class' => 'debitcredit_active')) }}
                                <div class="radio">
                                    <label>
                                        {{ Form::radio('debitcredit_active', 1, ($dcc == TRUE) ? TRUE : FALSE, ['class' => 'radio']) }}
                                        Active
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        {{ Form::radio('debitcredit_active', 0, ($dcc == FALSE) ? TRUE : FALSE, ['class' => 'radio']) }}
                                        Deactive
                                    </label>
                                </div>
                                {{ Form::label('image_debitcredit', 'Debit Credit Logo', array('class' => 'image_debitcredit')) }}
                                {{ Form::text('image_debitcredit', (!empty($setting->image_debitcredit) ? $setting->image_debitcredit : NULL), ['class' => 'form-control', 'placeholder' => 'Enter Debit Credit Logo...']) }}
                            </div>
                            <div class="form-group">
                                {{ Form::label('rocket_active', 'Rocket', array('class' => 'rocket_active')) }}
                                <div class="radio">
                                    <label>
                                        {{ Form::radio('rocket_active', 1, ($rocket == TRUE) ? TRUE : FALSE, ['class' => 'radio']) }}
                                        Active
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        {{ Form::radio('rocket_active', 0, ($rocket == FALSE) ? TRUE : FALSE, ['class' => 'radio']) }}
                                        Deactive
                                    </label>
                                </div>

                                {{ Form::label('image_rocket', 'Rocket Logo', array('class' => 'image_rocket')) }}
                                {{ Form::text('image_rocket', (!empty($setting->image_rocket) ? $setting->image_rocket : NULL), ['class' => 'form-control', 'placeholder' => 'Enter Rocket Logo...']) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('citybank_active', 'City Bank', array('class' => 'citybank_active')) }}
                                <div class="radio">
                                    <label>
                                        {{ Form::radio('citybank_active', 1, ($ctb == TRUE) ? TRUE : FALSE, ['class' => 'radio']) }}
                                        Active
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        {{ Form::radio('citybank_active', 0, ($ctb == FALSE) ? TRUE : FALSE, ['class' => 'radio']) }}
                                        Deactive
                                    </label>
                                </div>
                                {{ Form::label('image_citybank', 'Citybank Logo', array('class' => 'image_citybank')) }}
                                {{ Form::text('image_citybank', (!empty($setting->image_citybank) ? $setting->image_citybank : NULL), ['class' => 'form-control', 'placeholder' => 'Enter Citybank Logo...']) }}
                            </div>

                            <fieldset>
                                <legend>Reward Points Settings</legend>
                                <div class="form-group">
                                    {{ Form::label('rp_active', 'Is Reward Points Available?', array('class' => 'rp_active')) }}
                                    <div class="radio">
                                        <label>
                                            {{ Form::radio('rp_active', 1, ($rpa == TRUE) ? TRUE : FALSE, ['class' => 'radio']) }}
                                            Active
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            {{ Form::radio('rp_active', 0, ($rpa == FALSE) ? TRUE : FALSE, ['class' => 'radio']) }}
                                            Deactive
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('rp_fraction', 'Reward points fraction', array('class' => 'rp_fraction')) }}
                                    {{ Form::text('rp_fraction', (!empty($setting->rp_fraction) ? $setting->rp_fraction : NULL), ['class' => 'form-control', 'placeholder' => 'Enter reward points fraction...']) }}
                                    <small>Enter minimum amount of fraction for reward point</small>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('rp_point', 'Reward points)', array('class' => 'rp_fraction')) }}
                                    {{ Form::text('rp_point', (!empty($setting->rp_point) ? $setting->rp_point : NULL), ['class' => 'form-control', 'placeholder' => 'Enter reward point...']) }}
                                    <small>
                                        Enter how many point will earn a customer based on above mentioned fraction
                                    </small>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('rp_convert_tk', 'Reward points to TK conversion)', array('class' => 'rp_convert_tk')) }}
                                    {{ Form::text('rp_convert_tk', (!empty($setting->rp_convert_tk) ? $setting->rp_convert_tk : NULL), ['class' => 'form-control', 'placeholder' => 'Enter reward point to TK...']) }}
                                    <small>
                                        Enter how many BDT will earn a customer based on above mentioned point
                                    </small>
                                </div>
                            </fieldset>

                        </div>
                    </div>
                @endslot
            @endcomponent
        </div>
    </div>
@endsection
