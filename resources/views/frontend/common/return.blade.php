@extends('frontend.layouts.app')

@section('content')

    <!-- Main Container  -->
    <div class="main-container container">
        <ul class="breadcrumb">
            <li><a href="#"><i class="fa fa-home"></i></a></li>
            <li><a href="#">Return</a></li>
        </ul>

        <div class="row">
            <!--Middle Part Start-->
            <div id="content" class="col-sm-9">
                <h2 class="title">Product Returns</h2>
                <p>Please complete the form below to request product returns.</p>


                {{ Form::open(array('url' => 'return_save', 'method' => 'post', 'class'=> 'form-horizontal clearfix', 'value' => 'PATCH', 'id' => 'return_save')) }}

                <fieldset>
                    <legend>Order Information</legend>
                    <div class="form-group required">
                        {{ Form::label('first_name', 'First Name', array('class' => 'col-sm-2 control-label')) }}
                        <div class="col-sm-10">
                            {{ Form::text('first_name', NULL, ['required', 'class' => 'form-control', 'id' => 'first_name', 'placeholder' => 'First Name']) }}

                        </div>
                    </div>
                    <div class="form-group required">
                        {{ Form::label('last_name', 'Last Name', array('class' => 'col-sm-2 control-label')) }}

                        <div class="col-sm-10">
                            {{ Form::text('last_name', NULL, ['required', 'class' => 'form-control', 'id' => 'last_name', 'placeholder' => 'Last Name']) }}

                        </div>
                    </div>
                    <div class="form-group required">
                        {{ Form::label('email', 'E-Mail', array('class' => 'col-sm-2 control-label')) }}
                        <div class="col-sm-10">
                            {{ Form::email('email', NULL, ['required', 'class' => 'form-control', 'id' => 'email', 'placeholder' => 'E-Mail']) }}

                        </div>
                    </div>
                    <div class="form-group required">


                        {{ Form::label('telephone', 'Telephone', array('class' => 'col-sm-2 control-label')) }}
                        <div class="col-sm-10">
                            {{ Form::text('telephone', NULL, ['required', 'class' => 'form-control', 'id' => 'telephone', 'placeholder' => 'Telephone']) }}

                        </div>
                    </div>
                    <div class="form-group required">
                        {{ Form::label('order_id', 'Order ID', array('class' => 'col-sm-2 control-label')) }}
                        <div class="col-sm-10">

                            {{ Form::text('order_id', NULL, ['required', 'class' => 'form-control', 'id' => 'order_id', 'placeholder' => 'Order ID']) }}

                        </div>
                    </div>
                    <div class="form-group">
                        {{ Form::label('date_ordered', 'Order Date', array('class' => 'col-sm-2 control-label')) }}

                        <div class="col-sm-3">
                            <div class="input-group date">

                                {{ Form::text('date_ordered', NULL, ['required','data-date-format'=>'YYYY-MM-DD', 'class' => 'form-control', 'id' => 'date_ordered', 'placeholder' => 'Order Date']) }}

                                <span class="input-group-btn">
							<button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
							</span>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <fieldset>
                    <legend>Product Information</legend>
                    <div class="form-group required">
                        {{ Form::label('product_name', 'Product Name', array('class' => 'col-sm-2 control-label')) }}
                        <div class="col-sm-10">
                            {{ Form::text('product_name', NULL, ['required', 'class' => 'form-control', 'id' => 'product_name', 'placeholder' => 'Product Name']) }}

                        </div>
                    </div>
                    <div class="form-group required">
                        {{ Form::label('product_code', 'Product Code', array('class' => 'col-sm-2 control-label')) }}

                        <div class="col-sm-10">
                            {{ Form::text('product_code', NULL, ['required', 'class' => 'form-control', 'id' => 'product_code', 'placeholder' => 'Product Code']) }}


                        </div>
                    </div>
                    <div class="form-group">
                        {{ Form::label('quantity', 'Quantity', array('class' => 'col-sm-2 control-label')) }}

                        <div class="col-sm-10">
                            {{ Form::text('quantity', NULL, ['required', 'class' => 'form-control', 'id' => 'quantity', 'placeholder' => 'Quantity']) }}

                        </div>
                    </div>
                    <div class="form-group required">
                        {{ Form::label('', 'Returns', array('class' => 'col-sm-2 control-label')) }}
                        <div class="col-sm-10">
                            <div class="radio">
                                <input type="radio" id="test1" name="reason_return" value="Dead On Arrival" checked>
                                <label for="test1">Dead On Arrival</label>
                                <input type="radio" id="test2" name="reason_return" value="Order Error">
                                <label for="test2">Order Error</label>
                                <input type="radio" id="test3" name="reason_return" value="Received Wrong Item">
                                <label for="test3">Received Wrong Item</label>
                                <input type="radio" id="test4" name="reason_return" value="Other">
                                <label for="test4">Other</label>
                            </div>

                        </div>
                    </div>
                    <div class="form-group required">
                        {{ Form::label('', 'Product is opened', array('class' => 'col-sm-2 control-label')) }}

                        <div class="col-sm-10">
                            <input type="radio" id="test9" name="product_opened" value="Yes" checked>
                            <label for="test9">Yes</label>
                            <input type="radio" id="test10" name="product_opened" value="No">
                            <label for="test10">No</label>
                        </div>
                    </div>
                    <div class="form-group">
                        {{ Form::label('comment', 'Other details', array('class' => 'col-sm-2 control-label')) }}

                        <div class="col-sm-10">
                            <textarea class="form-control" id="comment" placeholder="Other details" rows="10"
                                      name="comment"></textarea>
                        </div>
                    </div>
                </fieldset>
                <div class="buttons clearfix">
                    <div class="pull-left"><a class="btn btn-default buttonGray" href="">Back</a>
                    </div>
                    <div class="pull-right">
                        {{ Form::submit('Submit', ['class' => 'btn btn-back-two']) }}

                    </div>
                </div>
                {{ Form::close() }}


            </div>
            <!--Middle Part End-->
            <!--Right Part Start -->
            <aside class="col-sm-3 hidden-xs content-aside col-md-3" id="column-right">
                <h2 class="subtitle">Account</h2>
                <div class="list-group">
                    <ul class="list-item">
                        <li><a href="login.html">Login</a>
                        </li>
                        <li><a href="register.html">Register</a>
                        </li>
                        <li><a href="#">Forgotten Password</a>
                        </li>
                        <li><a href="#">My Account</a>
                        </li>
                        <li><a href="#">Address Books</a>
                        </li>
                        <li><a href="wishlist.html">Wish List</a>
                        </li>
                        <li><a href="#">Order History</a>
                        </li>
                        <li><a href="#">Downloads</a>
                        </li>
                        <li><a href="#">Reward Points</a>
                        </li>
                        <li><a href="#">Returns</a>
                        </li>
                        <li><a href="#">Transactions</a>
                        </li>
                        <li><a href="#">Newsletter</a>
                        </li>
                        <li><a href="#">Recurring payments</a>
                        </li>
                    </ul>
                </div>
            </aside>
            <!--Right Part End -->
        </div>
    </div>
    <!-- //Main Container -->

@endsection