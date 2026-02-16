
<div class="main-container container">
        <ul class="breadcrumb">
            <?php $tksign = '&#2547; '; ?>
            <?php
            $breadcrumbs = new Creitive\Breadcrumbs\Breadcrumbs;

            $breadcrumbs->setDivider('');
            $breadcrumbs->addCrumb('<i class="fa fa-home"></i>', url('/'))
                ->addCrumb('Register', 'product');
            echo $breadcrumbs->render();

            $get_url = Request::segment(1);
            //dd($get_url);
            if($get_url == 'returning_customer'){
                $request_url = '/checkout/address';
            }else{
                $request_url = 'my_account';
            }





            ?>
        </ul>
    <div class="row">
        <div id="content" class="col-sm-12">
            <div class="page-login">
                <div class="account-border">
                    <div class="row">
                        @if(Session::has('success'))
                            <div class="col-md-12">
                                <div class="alert alert-success">
                                    <b>{{ Session::get('success') }}</b>
                                </div>
                            </div>
                        @endif
                        {{--@endif--}}
                        @if($errors->any())
                            <div class="col-md-12">
                                <div class="alert alert-danger">
                                    <h4>Warning!</h4>
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        <div class="col-sm-6 new-customer">
                            <div class="well">
                                <h2><i class="fa fa-file-o" aria-hidden="true"></i> New Customer</h2>
                                <p>By creating an account you will be able to shop faster, be up to date on an order's
                                    status, and keep track of the orders you have previously made.</p>
                            </div>
                            <div class="bottom-form">
                                <a href="{{url('/create_an_account')}}" class="btn btn-default pull-right">create An Account</a>
                            <!-- {{ Form::submit('create An Account', ['class' => 'btn btn-default pull-right']) }} -->
                            </div>
                        </div>

                        {{ Form::open(array('url' => 'web_login', 'method' => 'post', 'value' => 'PATCH', 'id' => '')) }}

                        <div class="col-sm-6 customer-login">
                            <div class="well">
                                <h2><i class="fa fa-file-text-o" aria-hidden="true"></i> Returning Customer</h2>
                                <p><strong>I am a returning customer</strong></p>
                                <div class="form-group">
                              
                                    {{ Form::label('email', 'Email Address', array('class' => 'title')) }}
                                    {{ Form::hidden('requet_url', $request_url, null) }}
                                    {{ Form::text('email', old('email'), ['required', 'class' => 'form-control', 'placeholder' => 'Enter Email']) }}
                                </div>
                                <div class="form-group">
                             
                                    {{ Form::label('password', 'Password', array('class' => 'sub_title')) }}
                                    {{ Form::password('password', ['class' => 'form-control', 'placeholder' => 'Enter Password']) }}
                                </div>
                            </div>
                            <div class="bottom-form">
                                <a href="{{ url('/reset_password') }}" class="forgot">Forgotten Password</a>
                                {{ Form::submit('Sign In', ['class' => 'btn btn-default pull-right']) }}
                            </div>
                        </div>

                        {{ Form::close() }}

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>