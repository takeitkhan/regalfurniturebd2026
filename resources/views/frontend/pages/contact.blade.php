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
                                        <?php
                                            $breadcrumbs = new Creitive\Breadcrumbs\Breadcrumbs;
                                            $breadcrumbs->setDivider('');
                                            $breadcrumbs->addCrumb('<i class="fa fa-home"></i>', url('/'))
                                            ->addCrumb('Contact Us', 'contact');
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
    </br>
      
    <div class="main-container container">
        <div class="row">
            <div id="content" class="col-sm-12">
                <div class="info-contact clearfix row">
                    <div class="col-lg-3 col-sm-3 col-xs-12 info-store">
                        <div class="ct-title"><h4>Our Address</h4></div>
                        </br>
                            @php
                                //dump($widgets);
                                $widget = dynamic_widget($widgets, ['id' => 4]);
                            @endphp
                            {!! $widget !!}
                        <!--<div class="">
                            <address>
                                <div class="address phone clearfix form-group">
                                    <div class="icon icon_one">
                                        <i class="fa fa-home"></i>
                                    </div>
                                    <div class="text">
                                        <strong>PRAN-RFL Center,</strong> <br> 105 Middle Badda, Dhaka -1212, Bangladesh<br/></div>
                                </div>
                                <div class="phone form-group">
                                    <div class="icon icon_one">
                                        <i class="fa fa-phone"></i>
                                    </div>
                                    <div class="text"> <span>Phone:</span> 09613737777</div>
                                </div>

                                <div class="phone form-group">
                                    <div class="icon icon_one">
                                        <i class="fa fa-envelope"></i>
                                    </div>
                                    <div class="text"> <span>Email:</span> info@regalfurniturebd.com</div>
                                </div>
                                <div class="phone form-group">
                                    <div class="icon icon_one">
                                        <i class="fa fa-print"></i>
                                    </div>
                                    <div class="text"><span>FAX:</span>+88 02 883 550</div>
                                </div>
                            </address>
                        </div>!-->
                    </div>
                    <div class="col-lg-5 col-sm-5 col-xs-12 contact-form">
                        
                     <div class="contact-form">
                        <div class="form-horizontal">
                         
                            @if (session('status'))
                                <div class="alert alert-success">
                                    {{ session('status') }}
                                </div>
                            @endif
                            @if ($errors->count())
                                
                            <div class="alert alert-danger">
                                    <ul>
                                        @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    {{ session('status') }}
                                </div>
                            @endif

                            {{ Form::open(array('url' => 'send_email', 'method' => 'post', 'value' => 'PATCH', 'id' => 'send_email')) }}

                            <div class="form-gp-wp row">
                            <div class="col-md-12">
                                <div class="ct-title ct-title8"><h4>Contact Form</h4></div>
                            </div>
                            
                            <div class="col-sm-12">
                                <div class="form-group required">
                                    <!-- <label class="control-label" for="input-name">Your Name</label> -->
                                    {{ Form::text('name', NULl, ['class' => 'form-control', 'placeholder' => 'Enter your name...', 'required' => true]) }}
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group required">
                                   <!--  <label class="control-label" for="input-email">E-Mail Address</label> -->
                                    {{ Form::text('email', NULl, ['class' => 'form-control', 'placeholder' => 'Enter your email...', 'required' => true]) }}
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group required">
                                    <!-- <label class=" control-label" for="input-enquiry">Mobile Number</label> -->
                                       {{ Form::number('number', NULl, ['class' => 'form-control', 'placeholder' => 'Enter your mobile number...', 'required' => true]) }}
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group required">
                                   <!--  <label class=" control-label" for="input-enquiry">Message</label> -->
                                    {{ Form::textarea('description',  NULl, ['class' => 'form-control', 'id' => 'input-enquiry', 'placeholder' => 'Enter your message...', 'required' => true]) }}
                                </div>
                            </div>
                           <div class="col-sm-12">
                                <div class="buttons">
                                    <div class="pull-left">
                                        {{ Form::submit('Send Message', ['class' => 'btn btn-default buttonGray ']) }}
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <div class="ct-title"><h4>Our Location</h4></div>
                    </br>
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3651.0473435891204!2d90.42337821498191!3d23.78132838457413!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755c796c76c1a8b%3A0x7ff1d179fba4c47c!2sPRAN-RFL+GROUP!5e0!3m2!1sen!2sbd!4v1550134663381" width="100%" height="280" frameborder="0" style="border:0" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>
@endsection



