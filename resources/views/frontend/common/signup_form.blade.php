<div id="content">
    <h2 class="title">Register Account</h2>
    <p>If you already have an account with us, please login at the <a href="#">login page</a>.</p>
    <form action="" method="post" enctype="multipart/form-data"
          class="form-horizontal account-register clearfix">
        <fieldset id="account">
            <legend>Your Personal Details</legend>
            <div class="form-group required" style="display: none;">
                <label class="col-sm-2 control-label">Customer Group</label>
                <div class="col-sm-10">
                    <div class="radio">
                        <label>
                            <input type="radio" name="customer_group_id" value="1" checked="checked">
                            Default
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-firstname">First Name</label>
                <div class="col-sm-10">
                    <input type="text" name="firstname" value="" placeholder="First Name"
                           id="input-firstname" class="form-control">
                </div>
            </div>
            <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-lastname">Last Name</label>
                <div class="col-sm-10">
                    <input type="text" name="lastname" value="" placeholder="Last Name" id="input-lastname"
                           class="form-control">
                </div>
            </div>
            <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-email">E-Mail</label>
                <div class="col-sm-10">
                    <input type="email" name="email" value="" placeholder="E-Mail" id="input-email"
                           class="form-control">
                </div>
            </div>
            <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-telephone">Telephone</label>
                <div class="col-sm-10">
                    <input type="tel" name="telephone" value="" placeholder="Telephone" id="input-telephone"
                           class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="input-fax">Fax</label>
                <div class="col-sm-10">
                    <input type="text" name="fax" value="" placeholder="Fax" id="input-fax"
                           class="form-control">
                </div>
            </div>
        </fieldset>
        <fieldset id="address">
            <legend>Your Address</legend>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="input-company">Company</label>
                <div class="col-sm-10">
                    <input type="text" name="company" value="" placeholder="Company" id="input-company"
                           class="form-control">
                </div>
            </div>
            <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-address-1">Address 1</label>
                <div class="col-sm-10">
                    <input type="text" name="address_1" value="" placeholder="Address 1"
                           id="input-address-1" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="input-address-2">Address 2</label>
                <div class="col-sm-10">
                    <input type="text" name="address_2" value="" placeholder="Address 2"
                           id="input-address-2" class="form-control">
                </div>
            </div>
            <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-city">City</label>
                <div class="col-sm-10">
                    <input type="text" name="city" value="" placeholder="City" id="input-city"
                           class="form-control">
                </div>
            </div>
            <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-postcode">Post Code</label>
                <div class="col-sm-10">
                    <input type="text" name="postcode" value="" placeholder="Post Code" id="input-postcode"
                           class="form-control">
                </div>
            </div>
            <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-country">Country</label>
                <div class="col-sm-10">
                    <select name="country_id" id="input-country" class="form-control">
                        <option value=""> --- Please Select ---</option>
                        <option value="244">Aaland Islands</option>
                        <option value="1">Afghanistan</option>
                        <option value="2">Albania</option>
                        <option value="3">Algeria</option>
                        <option value="4">American Samoa</option>
                        <option value="5">Andorra</option>
                        <option value="6">Angola</option>
                    </select>
                </div>
            </div>
            <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-zone">Region / State</label>
                <div class="col-sm-10">
                    <select name="zone_id" id="input-zone" class="form-control">
                        <option value=""> --- Please Select ---</option>
                        <option value="3513">Aberdeen</option>
                        <option value="3514">Aberdeenshire</option>
                        <option value="3515">Anglesey</option>
                        <option value="3516">Angus</option>
                    </select>
                </div>
            </div>
        </fieldset>
        <fieldset>
            <legend>Your Password</legend>
            <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-password">Password</label>
                <div class="col-sm-10">
                    <input type="password" name="password" value="" placeholder="Password" id="input-password" class="form-control">
                </div>
            </div>
            <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-confirm">Password Confirm</label>
                <div class="col-sm-10">
                    <input type="password" name="confirm" value="" placeholder="Password Confirm"
                           id="input-confirm" class="form-control">
                </div>
            </div>
        </fieldset>
        <fieldset>
            <legend>Newsletter</legend>
            <div class="form-group">
                <label class="col-sm-2 control-label">Subscribe</label>
                <div class="col-sm-10">
                    <input type="radio" id="test1" name="radio-group" checked>
                    <label for="test1">Yes</label>
                    <input type="radio" id="test2" name="radio-group" checked>
                    <label for="test2">No</label>
                </div>
            </div>
        </fieldset>
        <div class="buttons">
            <div class="pull-right">
                I have read and agree to the <a href="#" class="agree"><b> Pricing Tables</b></a>
                <input class="box-checkbox" type="checkbox" name="agree" value="1"> &nbsp;
                <input type="submit" value="Continue" class="btn btn-back-one">
            </div>
        </div>
    </form>
</div>


<div class="main-container container">
    <!--  <ul class="breadcrumb">
         <li><a href="#"><i class="fa fa-home"></i></a></li>
         <li><a href="#">Account</a></li>
         <li><a href="#">Register</a></li>
     </ul> -->

    <div class="row">

        <div id="content">
            <h2 class="title">Register Account</h2>
            <p>If you already have an account with us, please login at the <a href="#">login page</a>.</p>

            {{ Form::open(array('url' => 'web_signup', 'method' => 'post', 'class'=> 'form-horizontal account-register clearfix', 'value' => 'PATCH', 'id' => 'web_signup')) }}
            <fieldset id="account">
                <legend>Your Personal Details</legend>
                <div class="form-group required" style="display: none;">

                    {{ Form::label('customer_group_id', 'Customer Group', array('class' => 'col-sm-2 control-label')) }}
                    <div class="col-sm-10">
                        <div class="radio">
                            <label>
                                <input type="radio" name="customer_group_id" value="1" checked="checked"> Default
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
                    <label class="col-sm-2 control-label" for="input-email">Email Address</label>
                    <div class="col-sm-10">


                    </div>
                </div>
                <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-telephone">Telephone</label>
                    <div class="col-sm-10">



                    </div>
                </div>
            </fieldset>
            <fieldset id="address">
                <legend>Your Address</legend>
                <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-address-1">Address 1</label>
                    <div class="col-sm-10">
                        <input type="text" name="address_1" value="" placeholder="Address 1" id="input-address-1"
                               class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-address-2">Address 2</label>
                    <div class="col-sm-10">
                        <input type="text" name="address_2" value="" placeholder="Address 2" id="input-address-2"
                               class="form-control">
                    </div>
                </div>
                <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-city">City</label>
                    <div class="col-sm-10">
                        <input type="text" name="city" value="" placeholder="City" id="input-city" class="form-control">
                    </div>
                </div>
                <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-postcode">Post Code</label>
                    <div class="col-sm-10">
                        <input type="text" name="postcode" value="" placeholder="Post Code" id="input-postcode"
                               class="form-control">
                    </div>
                </div>
                <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-country">Country</label>
                    <div class="col-sm-10">
                        <select name="country_id" id="input-country" class="form-control">
                            <option value=""> --- Please Select ---</option>
                            <option value="Bangladesh">Bangladesh</option>

                        </select>
                    </div>
                </div>
                <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-zone">Region / State</label>
                    <?php $districts = \App\District::groupBy('district')->get(); ?>
                    <div class="col-sm-10">
                        <select name="zone_id" id="input-zone" class="form-control">
                            <option value=""> --- Please Select ---</option>
                            @foreach($districts as $district)
                                <option value="{{$district->district}}">{{$district->district}}</option>

                            @endforeach


                        </select>
                    </div>
                </div>
            </fieldset>
            <fieldset>
                <legend>Your Password</legend>
                <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-password">Password</label>
                    <div class="col-sm-10">

                        {{ Form::password('password', ['class' => 'form-control', 'id' =>'input-password', 'placeholder' => 'Enter Password']) }}
                    </div>
                </div>
                <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-confirm">Password Confirm</label>
                    <div class="col-sm-10">
                        {{ Form::password('confirm_password', ['class' => 'form-control', 'id'=>'input-confirm', 'placeholder' => 'Enter Confirm password']) }}
                    </div>
                </div>
            </fieldset>
            <fieldset>
                <legend>Newsletter</legend>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Subscribe</label>
                    <div class="col-sm-10">
                        <input type="radio" id="test1" name="radio-group" checked>
                        <label for="test1">Yes</label>
                        <input type="radio" id="test2" name="radio-group" checked>
                        <label for="test2">No</label>
                    </div>
                </div>
            </fieldset>
            <div class="buttons">
                <div class="pull-right">I have read and agree to the <a href="#" class="agree"><b>Pricing Tables</b></a>
                    <input class="box-checkbox" type="checkbox" name="agree" value="1"> &nbsp;
                    {{ Form::submit('Create Account', ['class' => 'btn btn-back-one']) }}
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>