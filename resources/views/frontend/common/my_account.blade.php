@extends('frontend.layouts.app')

@section('content')
    <?php $tksign = '&#2547; '; ?>

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
                                    //  ->addCrumb('My Account', 'product');
                                    // echo $breadcrumbs->render();
                                    ?>

                                    <!-- <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Shopping Cart</li> -->
                                </div>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <div class="main-container container">
        <!-- <ul class="breadcrumb">
            <li><a href="#"><i class="fa fa-home"></i></a></li>
            <li><a href="#">Account</a></li>
            <li><a href="#">My Account</a></li>
        </ul> -->
        @if (Route::has('login'))
            <?php $user = Auth::user(); ?>
            <div class="row">
                <div class="col-sm-10" id="content">
                    <h2 class="title title_21">Orders Informations</h2>
                    <p class="lead">
                        Hello, <strong>{{ Auth::user()->name }}</strong> - To update your account information.
                    </p>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-header user-oder-pnl">Purchase Quantity</div>
                                <div class="card-body oder-pnl-bdy oder-pnl-bdy_ct">
                                    <?php
                                    $total = \App\Models\OrdersDetail::where('user_id', $user->id)->sum('qty');
                                    $total_qty = (int)$total;
                                    echo $total_qty;
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-header user-oder-pnl">Total Amount</div>
                                <div class="card-body oder-pnl-bdy oder-pnl-bdy_ct">
                                    <?php
                                    $total = \App\Models\OrdersDetail::where('user_id', $user->id)->sum('local_purchase_price');
                                    $total_purchase = $total;
                                    echo $tksign . number_format($total_purchase);
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-header user-oder-pnl">Personal Information</div>
                                <div class="card-body oder-pnl-p">
                                    <h4>{{ $user->name }}</h4>
                                    <p>Email # {{ $user->email }}</p>
                                    <p>Phone # {{ $user->phone }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-header user-oder-pnl">Delivery Information</div>
                                <div class="card-body oder-pnl-p">
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