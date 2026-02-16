
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
                                    // $breadcrumbs = new Creitive\Breadcrumbs\Breadcrumbs;

                                    // $breadcrumbs->setDivider('');
                                    //  $breadcrumbs->setDivider('');
                                    // $breadcrumbs->addCrumb('Home', url('/'))
                                    //  ->addCrumb('Login & Register', 'product');
                                    // echo $breadcrumbs->render();
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
    <div class="row">
        <div id="content" class="col-sm-12">
            <div class="page-login">
                <div class="account-border">
                    <div class="row justify-content-md-center">

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

                        <!-- <div class="col-sm-6 new-customer">
                            <div class="well">
                                <h2><i class="fa fa-file-o" aria-hidden="true"></i> New Customer</h2>
                                <p>By creating an account you will be able to shop faster, be up to date on an order's
                                    status, and keep track of the orders you have previously made.</p>
                            </div>
                            <div class="bottom-form">
                             <a href="{{url('/create_an_account')}}" class="btn btn-default sing_in pull-right">create An Account</a>
                          
                            </div>
                        </div> -->

                        <div class="col-md-4 customer-login">
                            {{ Form::open(array('url' => 'web_login', 'method' => 'post', 'value' => 'POST', 'id' => '')) }}
                           {{ method_field('POST') }}

                            <div class="well">
                                <div class="icone-login"><i class="fa fa-user"></i></div>
                                <h2>Returning Customer</h2>
                                <p><strong>I am a returning customer</strong></p>
                                <div class="form-group">
                                    
                                    {{ Form::label('email', 'Email Address', array('class' => 'title')) }}
                                    {{ Form::text('email', old('email'),
                                    ['required', 'class' => 'form-control', 'placeholder' => 'Enter Email']) }}
                                </div>
                                <div class="form-group">
                                    
                                    {{ Form::label('password', 'Password', array('class' => 'sub_title')) }}
                                    {{ Form::password('password', ['class' => 'form-control', 'placeholder' => 'Enter Password']) }}
                                </div>
                            </div>
                            <div class="bottom-form">
                                <a href="{{ url('/reset_password') }}" class="forgot">Forgotten Password</a>

                                {{ Form::submit('Sign In', ['class' => 'btn btn-default btn-back-two  pull-right']) }}

                                <a href="{{url('/create_an_account')}}" class="btn btn-default sing_in_five sing_in pull-right">Create An Account</a>
                                
                                
                                <!-- <a class="fb-login-btn" href="{{url('/social/auth/redirect/facebook')}}">Login With Facebook</a>
                                <div class="d-lg-none" style="clear: both;"></div>
                                <a class="google-login-btn" href="{{url('/social/auth/redirect/google')}}">Login With Google</a> -->
                                
                            </div>

                        {{ Form::close() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>