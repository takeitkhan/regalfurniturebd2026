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
                                    <li class="breadcrumb-item active" aria-current="page">Reset Password</li>
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
    <div class="about-area">
        <div class="">
            
            <?php if(!empty($message)) { ?>
                <div class="row">
                    <div class="col-md-5 clearfix">
                        <div class="alert alert-danger">
                            <?php echo $message; ?>
                        </div>
                    </div>
                    <div class="col-md-7"></div>
                </div>
            <?php } ?>
            
            <div class="row">
                
                <div class="col-md-5">
                    <div class="about-text">
                        
                        
                        {{ Form::open(array('url' => 'reset_password', 'method' => 'post', 'value' => 'PATCH', 'id' => '')) }}
    
                            <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                                <label for="email">E-Mail Address</label>
                                
                                <input id="email" type="email" class="form-control" name="email" value="{{ $email or old('email') }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <p><small>If your email registered before we will send you email and password reset URL.</small></p>
                            <div class="form-group">
                                <input type="submit" class="btn btn-back-two" value="Reset Password" name="submit" />
                            </div>
                            
                        {{ Form::close() }}
                        
                        
                        
                    </div>   
                </div>
            </div>
        </div>
    </div>
@endsection
