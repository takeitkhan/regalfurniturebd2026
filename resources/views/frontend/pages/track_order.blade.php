@extends('frontend.layouts.app')

@section('content')


    <section class="breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="breadcrumb-warp">
                        <div class="breadcrumb-one">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Track Your Order</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <br>
  

    <!-- Main Container  -->
    <div class="main-container container">

        <div class="row">
            <div id="content" class="col-sm-12 item-article">
                <div class="row box-1-about">
                    <div class="col-md-7">
                        <div class="welcome-about-us-header">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="title-about-us1">
                                        <div class="title-about-us_one">
                                            <h3>Track Your Order</h3>
                                        </div>
                                    </div>
                                    <div class="trc-content">
                                        @php
                                            //dump($widgets);
                                            $widget = dynamic_widget($widgets, ['id' => 2]);
                                        @endphp
                                        {!! $widget !!}
                                    </div>
                                    <div class="trc-oder-search">
                                        @include('frontend.common.message_handler')
                                        <form action="{{ route('track_order') }}" method="POST" class="navbar-form" role="search">
                                            {{ csrf_field() }}
                                            <label><strong>Your Order Number: </strong></label>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                         <input class="form-control p-2 mb-2" placeholder="Order Number" name="order_number" id="order_number" type="text" value="{{ ($track) ? $track->id : '' }}">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input class="form-control p-2 " placeholder="Phone Number" name="phone_number" id="phone_number" type="text" value="{{ ($track) ? $track->phone : '' }}">
                                                     </div>
                                                     <div class="col-md-12">
                                                        <div class="input-group-btn">
                                                            <button class="btn btn-default btn-default_trck" type="submit">Track Order</button>
                                                        </div>
                                                     </div>
                                                </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-5 why-choose-us">
                        <div class="content-why">
                            <div class="oder-prosec">
                                @php
                                    //dump($widgets);
                                    $widget = dynamic_widget($widgets, ['id' => 3]);
                                @endphp
                                {!! $widget !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if ($track)
            @php

                $orders = App\OrdersDetail::Where(['order_random' => $track->order_random])->get();


            @endphp
            <div class="row">
                <div class="col-md-7">

                    <div class="order_summary-list ">
                        <div class="title-about-us1">
                            <div class="title-about-us_one summary_to">
                                <h4>My Order  Information</h4>
                            </div>
                        </div>
                        <ul class="list-unstyled">
                            <li>Oder Number: <span><strong>{{ $track->id }}</strong></span></li>
                            <li>Date: <span><strong>{{ $track->order_date->format('m-d-Y') }}</strong></span></li>
                            <li>Quantity: <span><strong>{{ $orders->sum('qty') }} item(s)</strong></span></li>
                        </ul>
                    </div>

                    <div class="item-track">
                        @foreach($orders as $list)
                            <div class="oder-ast">

                                <p>{{ $list->product_name }} status: <strong>{{ ucfirst($list->od_status) }}</strong> </p>
                               
                            </div>

                            <div class="oder-detelse">
                                <ul class="progressbar_product_od text-center">
                                    @if ($list->od_status == 'placed')
                                        <li class="active">Placed</li>
                                        <li>Processing</li>
                                        <li>Distribution</li>
                                        <li>Production</li>
                                        <li>Done</li>
                                    @elseif($list->od_status == 'processing')
                                        <li class="active">Placed</li>
                                        <li class="active">Processing</li>
                                        <li>Distribution</li>
                                        <li>Production</li>
                                        <li>Done</li>
                                    @elseif($list->od_status == 'distribution')
                                        <li class="active">Placed</li>
                                        <li class="active">Processing</li>
                                        <li class="active">Distribution</li>
                                        <li>Production</li>

                                        <li>Done</li>
                                    @elseif($list->od_status == 'production')
                                        <li class="active">Placed</li>
                                        <li class="active">Processing</li>
                                        <li class="active">Distribution</li>
                                        <li class="active">Production</li>
                                        <li>Done</li>
                                    @elseif($list->od_status == 'refund')
                                        <li class="active">Placed</li>
                                        <li class="active">Processing</li>
                                        <li class="active">Distribution</li>
                                        <li class="active">Production</li>
                                        <li class="active">Refund</li>
                                        <li>Done</li>
                                    @elseif($list->od_status == 'done')
                                        <li class="active">Placed</li>
                                        <li class="active">Processing</li>
                                        <li class="active">Distribution</li>
                                        <li class="active">Production</li>
                                        <li class="active">Done</li>

                                    @endif
                                </ul>
                            </div>

                        @endforeach

                    </div>




                </div>
            </div>
        @endif
    </div>
    <!-- //Main Container -->

@endsection