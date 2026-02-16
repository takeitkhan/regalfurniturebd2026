@extends('frontend.layouts.app')

@section('content')
  <section class="breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-md-9">
                    <div class="breadcrumb-warp">
                        <div class="breadcrumb-one">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">My Account</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <br>
    

    <div class="main-container container">
        @if (Route::has('login'))
            <div class="row">
                <div class="col-sm-9" id="content">
                    <h2 class="title">My Account</h2>
                    <p class="lead">
                        Hello, <strong>{{ Auth::user()->name }}</strong> - To update your account information.
                    </p>
                    @include('frontend.common.message_handler')

                    {{ Form::open(array('url' => '/profile_update', 'method' => 'post', 'value' => 'PATCH', 'id' => 'delivery_address')) }}
                    {{csrf_field()}}
                    <?php
                    $user = Auth::user();

                    if (!empty($user)) {
                        $name = $user->name;
                        $id = $user->id;
                        $phone = $user->phone;
                        $e_phone = $user->emergency_phone;
                        $email = $user->email;
                        $address = $user->address;
                        $username = $user->username;
                        $password = '';
                        $postcode = $user->postcode;
                        $district = $user->district;
                    } else {
                        $name = '';
                        $id = '';
                        $phone = '';
                        $e_phone = '';
                        $email = '';
                        $address = '';
                        $username = '';
                        $password = '';
                        $postcode = '';
                        $district = '';
                    }
                    ?>

                    {{ Form::hidden('user_id', !empty(Auth::user()->id) ? Auth::user()->id : NULL) }}
                    <div class="row">
                        <div class="col-sm-6">
                            <div id="personal-details">
                                <div class="card">
                                    <div class="card-header">
                                        Personal Details
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group required">
                                            {{ Form::label('name', 'Full Name', array('class' => 'name')) }}
                                            {{ Form::text('name', $name, ['required', 'class' => 'form-control', 'placeholder' => 'Full Name']) }}
                                        </div>
                                        <div class="form-group required">
                                            {{ Form::label('phone', 'Mobile Number', array('class' => 'title')) }}
                                            {{ Form::text('phone', $phone, ['required', 'class' => 'form-control', 'placeholder' => 'Mobile Number']) }}
                                        </div>
        
                                        <div class="form-group">
                                            {{ Form::label('email', 'Email', array('class' => 'title')) }}
                                            {{ Form::email('email', $email, ['required', 'class' => 'form-control', 'placeholder' => 'Email ']) }}
                                            <div class="show_message"></div>
                                        </div>
        
                                        <div class="form-group required">
                                            {{ Form::label('emergency_phone', 'Emergency Mobile Number', array('class' => 'title')) }}
                                            {{ Form::text('emergency_phone', $e_phone, ['required', 'class' => 'form-control', 'placeholder' => 'Emergency Mobile Number']) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                        </div>
                        <div class="col-sm-6">
                            <div class="card">
                                <div class="card-header">
                                    Change Password
                                </div>
                                <div class="card-body">
                                    <div class="form-group required">
                                        <label for="input-password" class="control-label">Old Password</label>
                                        <input type="password" class="form-control" placeholder="Old Password" value=""
                                               name="old-password">
                                    </div>
                                    <div class="form-group required">
                                        <label for="input-password" class="control-label">New Password</label>
                                        <input type="password" class="form-control" placeholder="New Password" value=""
                                               name="new-password">
                                    </div>
                                    <div class="form-group required">
                                        <label for="input-confirm" class="control-label">New Password Confirm</label>
                                        <input type="password" class="form-control" id="input-confirm"
                                               placeholder="New Password Confirm" value="" name="new-confirm">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="card">
                                <div id="address">
                                    <div class="card-header">
                                        Address
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group required">
                                            {{ Form::label('address', 'Address', array('class' => 'address')) }}
                                            {{ Form::text('address', !empty($address) ? $address : '', ['id' => 'address_1', 'class' => 'form-control', 'placeholder' => 'Address']) }}
                                        </div>
                                        <div class="form-group required">
                                            {{ Form::label('address_2', 'Secondary Address', array('class' => 'address_2')) }}
                                            {{ Form::text('address_2', $address, ['required', 'class' => 'form-control', 'placeholder' => 'Secondary Address']) }}
                                        </div>
        
                                        <?php
                                        if (!Auth::check()) {
                                        $checked = (!empty($username) ? ' checked ' : null);
                                        $required = (!empty($username) ? ' required ' : null);
                                        ?>
                                        {!! Form::checkbox('create-account', TRUE, FALSE, ['class' => 'square', 'id' => 'create-account', $checked]) !!}
                                        {!! Form::label('permissions', 'Create my user account') !!}
        
                                        <div class="passfield"
                                             style="display: {!! (!empty($username) ? ' block ' : 'none') !!};">
                                            <div class="form-group">
                                                {{ Form::label('username', 'Username', array('class' => 'title')) }}
                                                {{ Form::text('username', !empty($username) ? $username : '', ['id' => 'username', 'class' => 'form-control', 'placeholder' => 'Username ']) }}
                                            </div>
                                            <div class="form-group">
                                                {{ Form::label('password', 'Password', array('class' => 'title')) }}
                                                {{ Form::password('password', ['id' => 'password', 'class' => 'form-control', 'placeholder' => 'Password', $required]) }}
                                            </div>
                                        </div>
                                        <?php } ?>
        
                                        <div class="form-group required">
                                            {{ Form::label('postcode', 'Post Code', array('class' => 'postcode')) }}
                                            {{ Form::text('postcode', !empty($postcode) ? $postcode : '', ['id' => 'postcode', 'class' => 'form-control', 'placeholder' => 'Post Code']) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        <div class="col-sm-6">
                            <div class="card">
                                <div class="card-header">
                                    <div id="shipping-address">
                                        Shipping Address
                                    </div>
                                </div>
                                    <div class="card-body"> 
                                        <div class="form-group">
                                            {{ Form::label('company', 'Company', array('class' => 'company')) }}
                                            {{ Form::text('company', !empty($company) ? $company : null, ['required', 'class' => 'form-control', 'placeholder' => 'Company']) }}
                                        </div>
        
                                        <div class="form-group required">
                                            <?php $districts = get_districts(); ?>
        
                                            <select name="district" class="form-control" id="district"
                                                    required="required">
                                                <option value="">Choose your district</option>
                                                @foreach($districts as $dist)
                                                    <option value="{{ $dist->district }}"
                                                            id="{{ $dist->district }}" {{ !empty($district == $dist->district) ? 'selected="selected"' : null }}>
                                                        {{ $dist->district }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    


                    <div class="buttons clearfix">
                        <div class="pull-right">
                            <input type="submit" name="update_profile" class="btn btn-md btn-back-one"
                                   value="Save Changes">
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
                @include('frontend.common.frontend_user_menu')
            </div>
    </div>

    @endif
@endsection