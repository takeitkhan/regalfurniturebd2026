@extends('frontend.layouts.app')

@section('content')
 
    <div class="main-container container">
        <ul class="breadcrumb">
            <?php
                $breadcrumbs = new Creitive\Breadcrumbs\Breadcrumbs;
                 $breadcrumbs->setDivider('');
                 $breadcrumbs->addCrumb('<i class="fa fa-home"></i>', url('/'))
                    ->addCrumb('Contact Us', 'contact');
                echo $breadcrumbs->render();
            ?>
        </ul>

        <div class="row">
            <div id="content" class="col-sm-12">
                <div class="page-title">
                    <h2>Contact Us</h2>
                </div>


               <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3651.0473435891204!2d90.42337821498191!3d23.78132838457413!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755c796c76c1a8b%3A0x7ff1d179fba4c47c!2sPRAN-RFL+GROUP!5e0!3m2!1sen!2sbd!4v1550134663381" width="100%" height="350" frameborder="0" style="border:0" allowfullscreen></iframe>

                <div class="info-contact clearfix">
                    <div class="col-lg-4 col-sm-4 col-xs-12 info-store">
                        <div class="row">
                            <div class="name-store">
                                <h3>Your Store</h3>
                            </div>
                            <address>
                                <div class="address clearfix form-group">
                                    <div class="icon">
                                        <i class="fa fa-home"></i>
                                    </div>
                                    <div class="text">
                                        <strong>PRAN-RFL Center,</strong> <br> 105 Middle Badda, Dhaka -1212, Bangladesh<br/></div>
                                </div>
                                <div class="phone form-group">
                                    <div class="icon">
                                        <i class="fa fa-phone"></i>
                                    </div>
                                    <div class="text"> Phone: 09613 737 777</div>
                                </div>

                                <div class="phone form-group">
                                    <div class="icon">
                                        <i class="fa fa-envelope"></i>
                                    </div>
                                    <div class="text"> Email: info@rflus.Com</div>
                                </div>
                            <!--     <div class="comment">
                                Maecenas euismod felis et purus consectetur, quis fermentum velition. Aenean egestas quis turpis vehicula.Maecenas euismod felis et purus consectetur, quis fermentum velition.
                                Aenean egestas quis turpis vehicula.It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.
                                The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English.
                                </div> -->
                            </address>
                        </div>
                    </div>
                    <div class="contact-form">
                          

                    <div class="col-lg-8 col-sm-8 col-xs-12 contact-form">
                        <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                            @if (session('status'))
                                <div class="alert alert-success">
                                    {{ session('status') }}
                                </div>
                                @endif
                                 {{ Form::open(array('url' => 'send_email', 'method' => 'post', 'value' => 'PATCH', 'id' => 'send_email')) }}
                        <fieldset>
                            <legend>Contact Form</legend>
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-name">Your Name</label>
                                <div class="col-sm-10">
                                 {{ Form::text('name', NULl, ['class' => 'form-control', 'placeholder' => 'Enter your name...', 'required' => true]) }}
                                </div>
                            </div>
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-email">E-Mail Address</label>
                                <div class="col-sm-10">
                                    {{ Form::text('email', NULl, ['class' => 'form-control', 'placeholder' => 'Enter your email...', 'required' => true]) }}
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="col-sm-2 control-label" for="input-enquiry">Mobile Number</label>
                                    <div class="col-sm-10">
                                       {{ Form::number('number', NULl, ['class' => 'form-control', 'placeholder' => 'Enter your mobile number...', 'required' => true]) }}
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="col-sm-2 control-label" for="input-enquiry">Enquiry</label>
                                    <div class="col-sm-10">
                                         {{ Form::textarea('description',  NULl, ['class' => 'form-control', 'id' => 'input-enquiry', 'placeholder' => 'Enter your message...', 'required' => true]) }}
                                        
                                    </div>
                                </div>
                            </fieldset>
                            <div class="buttons">
                                <div class="pull-right">
                                    {{ Form::submit('Send', ['class' => 'btn btn-default buttonGray']) }}
                                    
                                </div>
                            </div>
                            {{ Form::close() }}
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection



