@extends('frontend.layouts.app')

@section('content')
<!-- {{is_writable(config('session.files'))}}
{{'helloworld'}} -->


          
                    <!-- <div class="panel-heading panel-color"><i class="fa fa-check" aria-hidden="true"></i> Sign In</div> -->
                    <div class="">
                        <div class="">
                            @include('frontend.common.login_form')
                        </div>
                    </div>
                </div>
        
@endsection