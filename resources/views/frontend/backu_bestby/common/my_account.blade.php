@extends('frontend.layouts.app')

@section('content')
    <?php $tksign = '&#2547; '; ?>

    <div class="main-container container">
        <ul class="breadcrumb">
            <li><a href="{{ url('/') }}"><i class="fa fa-home"></i></a></li>
            <li>My Account</li>
        </ul>
        @if (Route::has('login'))
            <?php $user = Auth::user(); ?>
            <div class="row">
                <div class="col-sm-9" id="content">
                    <h2 class="title">Orders Informations</h2>
                    <p class="lead">
                        Hello, <strong>{{ Auth::user()->name }}</strong> - To update your account information.
                    </p>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="panel panel-default">
                                <div class="panel-heading user-oder-pnl">Purchase Quantity</div>
                                <div class="panel-body oder-pnl-bdy">
                                    <?php
                                    $total = \App\OrdersDetail::where('user_id', $user->id)->sum('qty');
                                    $total_qty = (int)$total;
                                    echo $total_qty;
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="panel panel-default">
                                <div class="panel-heading user-oder-pnl">Total Amount</div>
                                <div class="panel-body oder-pnl-bdy">
                                    <?php
                                    $total = \App\OrdersDetail::where('user_id', $user->id)->sum('local_purchase_price');
                                    $total_purchase = $total;
                                    echo $tksign . number_format($total_purchase);
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="panel panel-default">
                                <div class="panel-heading user-oder-pnl">Personal Information</div>
                                <div class="panel-body oder-pnl-p">
                                    <h4>{{ $user->name }}</h4>
                                    <p>Email # {{ $user->email }}</p>
                                    <p>Phone # {{ $user->phone }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="panel panel-default">
                                <div class="panel-heading user-oder-pnl">Delivery Information</div>
                                <div class="panel-body oder-pnl-p">
                                    <p>
                                        Address: {!! nl2br($user->address) !!}
                                    </p>
                                    <p>Phone # {{ $user->phone }}</p>
                                    <p>Emer. Phone # {{ $user->emergency_phone }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="about-text">

                    </div>
                </div>
                @include('frontend.common.frontend_user_menu')
            </div>
        @endif
    </div>

@endsection