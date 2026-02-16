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
                                     ->addCrumb('Register', 'product');
                                    echo $breadcrumbs->render();
                                    ?>

                                   
                                </div>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="main-container container">
        @if ($errors->count())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{ Form::open(array('url' => 'web_signup', 'method' => 'post', 'class'=> 'form-horizontal account-register clearfix', 'value' => 'PATCH', 'id' => 'web_signup', 'autocomplete' => 'off')) }}
        <div class="row">
            <div class="col-md-12">
                <h2 class="title">Register Account</h2>
            <p>If you already have an account with us, please login at the <a href="#">login page</a>.</p>
            </div>
        </div>
    <div class="row">
        <div id="content" class="col-md-7">
            <div id="account">
                <div class="card">
                    <div class="card-header">
                        Your Personal Details
                    </div>
                    <div class="card-body">
                        <div class="form-group required" style="display: none;">
                            {{ Form::label('customer_group', 'Customer Group', array('class' => 'control-label')) }}
                            <div class="">
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="customer_group" value="8" checked="checked">
                                        Default
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group required">
                                    {{ Form::label('name', 'Full Name', array('class' => ' control-label')) }}
                                    <div class="">
                                        {{ Form::text('name', NULL, ['required', 'class' => 'form-control', 'id'=> 'name','placeholder' => 'Enter Full Name']) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group required">
                                    {{ Form::label('email', 'Email Address', array('class' => ' control-label')) }}
                                    <div class="">
                                        {{ Form::email('email', NULL, ['class' => 'form-control', 'placeholder' => 'Enter Email Address']) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                 <div class="form-group has-feedback required ">
                                  {{ Form::label('telephone', 'Contact Number', array('class' => ' control-label')) }}
                                  <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                      <div class="input-group-text">+88</div>
                                    </div>
                                     {{ Form::tel('telephone', NULL, ['class' => 'form-control', 'placeholder' => 'Telephone']) }}
                                  </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group has-feedback required">
                                   {{ Form::label('emergency_contact_number', 'Emergency Contact Number', array('class' => ' control-label')) }}
                                   <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                      <div class="input-group-text">+88</div>
                                    </div>
                                     {{ Form::tel('emergency_contact_number', NULL, ['class' => 'form-control', 'placeholder' => 'Emergency Contact Number']) }}
                                  </div>
                                </div>
                            </div>
                        </div>
                        <div>
                    </div>
                    </div>
                </div>
                <br>
                
                <div class="card ">
                    <div class="card-header">Your Password</div>
                    <div class="card-body card-body_258">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group required">
                                    {{ Form::label('password', 'Password', array('class' => ' control-label')) }}
                                    <div class="">
                                        {{ Form::password('password', ['class' => 'form-control', 'placeholder' => 'Password']) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group required">
                                    {{ Form::label('password_confirmation', 'Password Confirm', array('class' => ' control-label')) }}
                                    <div class="">
                                        {{ Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => 'Password Confirm']) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="address">
                <div class="card">
                    <div class="card-header">Your Address</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('company', 'Company', array('class' => ' control-label')) }}
                                    <div class="">
                                        {{ Form::text('company', NULL, ['class' => 'form-control', 'placeholder' => 'Enter company']) }}
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('district', 'District', array('class' => ' control-label')) }}
                                    <div class="">
                
                                        <?php $districts = get_districts(); ?>
                
                                        <select name="district" class="form-control" id="district">
                                            <option value="">Choose your district</option>
                                            @foreach($districts as $dist)
                                                <option value="{{ $dist->district }}"
                                                        id="{{ $dist->district }}" {{ $dist->district == old('district') ? 'selected' : '' }}>
                                                    {{ $dist->district }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('post_code', 'Post Code', array('class' => ' control-label')) }}
                                    <div class="">
                                        {{ Form::text('post_code', NULL, ['class' => 'form-control', 'placeholder' => 'Enter post code']) }}
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ Form::label('address_1', 'Address 1', array('class' => ' control-label')) }}
                                    <div class="">
                                        {{ Form::text('address_1', NULL, ['class' => 'form-control', 'placeholder' => 'Enter Address 1']) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ Form::label('address_2', 'Address 2', array('class' => ' control-label')) }}
                                    <div class="">
                                        {{ Form::text('address_2', NULL, ['class' => 'form-control', 'placeholder' => 'Enter Address 2']) }}
                                    </div>
                                </div>
                            </div>
                         </div>
                    </div>
                 </div>
            </div>
        </div>
    </div>
        
        <br>
        <br>
        
        <div class="row">
            <div class="col-md-12">
                    <div class="card card_258">
                        <div class="card-footer card-footer_258">
                            <div class="buttons">
                                <div class="pull-left card-foofewrf">
                                    <input id="agree" class="box-checkbox" type="checkbox" name="agree" value="1">
                                    <label for="agree">I have read and agree to the <a href="{{ url('page/5/terms-and-conditions') }}" target="__blank" class="agree">
                                            <b>Terms & Condition</b>
                                        </a></label>
                                    
                                </div>
                                <input type="submit" value="Continue" class="btn btn-back-one pull-right">
                            </div>
                        </div>
                    </div>

           
        </div>

            {{ Form::close() }}
</div>
        </div>
    </div>
@endsection