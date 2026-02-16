@extends('layouts.nonapp')
@section('css_head')
<link rel="stylesheet" href="{{ URL::asset('public/plugins/iCheck/square/blue.css') }}" />
@endsection

@section('content')

<div class="login-box-body">
    <p class="login-box-msg">Sign in to start your session</p>
    <!--<form class="form-horizontal" method="POST" action="{{ route('login') }}">-->
    
    {{ Form::open(array('url' => 'login', 'class' => 'form-horizontal')) }}   
        {{ csrf_field() }}
        
        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}  has-feedback">
            {{ Form::text('email', old('email'), ['class' => 'form-control', 'id' => 'email', 'required' => true, 'autofocus' => true]) }}
            
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            @if ($errors->has('email'))
            <span class="help-block">
                <strong>{{ $errors->first('email') }}</strong>
            </span>
            @endif
        </div>
        
        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}  has-feedback">
            {{ Form::password('password', ['class'=>'form-control', 'id' => 'password', 'required' => true]) }}
            
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            @if ($errors->has('password'))
            <span class="help-block">
                <strong>{{ $errors->first('password') }}</strong>
            </span>
            @endif
        </div>
        
        <div class="row">
            <div class="row">
                <div class="col-xs-8">
                    <div class="icheck">
                        <label>
                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                        </label>
                    </div>
                </div>
                <!-- /.col -->
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                </div>
                <!-- /.col -->
            </div>
        </div>
    {{ Form::close() }}
    
    <a class="btn btn-link" href="{{ route('password.request') }}">I forgot my password</a><br>
    <a href="{{ url('/register') }}" class="text-center">Create a new account</a>

</div>
<strong>Copyright © <?php echo date("Y") ?> <a href="http://tritiyo.com" target="_blank">Tritiyo Limited</a>.</strong> All rights reserved.
<br/>
<b>{{ env('app_name', null) }}</b>, <b>Version</b> {{ env('app_version', null) }}
@endsection



@section('cusjs')
<script src="{{ URL::asset('public/plugins/iCheck/icheck.min.js') }}"></script>
<script>
$(function () {
    $('input').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        increaseArea: '20%' // optional
    });
});
</script>  
@endsection