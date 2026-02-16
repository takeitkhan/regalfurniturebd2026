@extends('frontend.layouts.app')

@section('content')
    <div class="slider-page">
        <div class="single-slider-page">
            <div class="single-slider-page-table">
                <div class="single-slider-page-table-cell">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="services-text">
                                    <h1 class="services-tailte-text">Reset Password</h1>
                                    <?php
                                    $breadcrumbs = new Creitive\Breadcrumbs\Breadcrumbs;

                                    $breadcrumbs->setDivider(' » &nbsp;');
                                    $breadcrumbs->addCrumb('Home', url('/'))
                                        ->addCrumb('Reset Password', 'page');
                                    echo $breadcrumbs->render();
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>




    <div class="about-area">
        <div class="container">
            <div class="row">
                <div class="col-md-5">
                    <div class="about-text">
                        
                        <?php
                            if(!empty($message)) {
                                echo $message;
                            }
                        ?>
                        
                        {{ Form::open(array('url' => 'retrieve_password', 'method' => 'post', 'value' => 'PATCH', 'id' => '')) }}
    
                            <input type="hidden" class="form-control" name="user_id" value="{{ $user->id }}" required>
                            
                            <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                                <label for="email">New Password</label>
                                <input type="password" class="form-control" name="password" required>
                            </div>
                            <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                                <label for="email">New Password</label>
                                <input type="password" class="form-control" name="confirm_password" required>
                            </div>
                            
                            <p><small>Enter same password two time to reset your password.</small></p>
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
