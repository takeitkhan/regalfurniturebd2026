@extends('frontend.layouts.app')

@section('content')
    <div class="main-container container">
        <ul class="breadcrumb">
            <?php $tksign = '&#2547; '; ?>
            <?php
            $breadcrumbs = new Creitive\Breadcrumbs\Breadcrumbs;

            $breadcrumbs->setDivider('');
            $breadcrumbs->addCrumb('<i class="fa fa-home"></i>', url('/'))
                ->addCrumb('Reset Password', 'product');
            echo $breadcrumbs->render();
            ?>
        </ul>

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
                                <input type="submit" class="btn btn-primary" value="Reset Password" name="submit" />
                            </div>
                            
                        {{ Form::close() }}
                        
                        
                        
                    </div>   
                </div>
            </div>
        </div>
    </div>
@endsection
