@extends('frontend.layouts.app')

@section('content')

    <!-- Main Container  -->
    <div class="main-container container">
        <ul class="breadcrumb">
            <?php
                $breadcrumbs = new Creitive\Breadcrumbs\Breadcrumbs;
                 $breadcrumbs->setDivider('');
                 $breadcrumbs->addCrumb('<i class="fa fa-home"></i>', url('/'))
                    ->addCrumb('Track Your Order', 'Track Your Order');
                echo $breadcrumbs->render();
            ?>
        </ul>
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
                                  <p>Nulla auctor mauris ut dui luctus semper. In hac habitasse platea dictumst. Duis pellentesque ligula a    risus suscipit dignissim. Nunc non nisl lacus. Integer pharetra lacinia dapibus. Donec eu dolor dui, vel posuere mauris.</p>

                                  <p>Nulla auctor mauris ut dui luctus semper. In hac habitasse platea dictumst. Duis pellentesque ligula a    risus suscipit dignissim. Nunc non nisl lacus. Integer pharetra lacinia dapibus. Donec eu dolor dui, vel posuere mauris. Integer pharetra lacinia dapibus. Donec eu dolor dui, vel posuere mauris.Integer pharetra lacinia dapibus. Donec eu dolor dui, vel posuere mauris.</p>

                                </div>
                                <div class="trc-oder-search">
                                  <form action="{{ route('track_order') }}" method="POST" class="navbar-form" role="search">
                                    {{ csrf_field() }}
                                    <label for="track"><strong>Your Order Number :</strong></label>
                                      <div class="input-group add-on">
                                      <input class="form-control" placeholder="Order Number" name="track" id="track" type="text" value="{{ ($track) ? $track->id : '' }}">
                                        <div class="input-group-btn">
                                          <button class="btn btn-default" type="submit">Track Order</button>
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
                            <img src="http://103.218.26.178:2145/bestbuy/storage/uploads/fullsize/2019-03/aa.png" alt="">
                          </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @if ($track)
        <div class="row">
            <div class="col-md-7">
                <div class="title-about-us1">
                    <div class="title-about-us_one">
                      <h3>My Order  Information</h3>
                    </div>
                </div>
                <div class="oder-ast">
                    <p>My Order status: <strong>{{ ucfirst($track->order_status) }}</strong> </p>
                </div>

                <div class="oder-detelse">
                    <ul class="progressbar_product_od text-center">
                      @if ($track->order_status == 'placed')
                      <li class="active">Placed</li>
                      <li>Processing</li>
                      <li>Distribution</li>
                      <li>Production</li>
                      {{-- <li>Refund</li> --}}
                      <li>Done</li>
                      @elseif($track->order_status == 'processing')
                      <li class="active">Placed</li>
                      <li class="active">Processing</li>
                      <li>Distribution</li>
                      <li>Production</li>
                      {{-- <li>Refund</li> --}}
                      <li>Done</li>
                      @elseif($track->order_status == 'distribution')
                      <li class="active">Placed</li>
                      <li class="active">Processing</li>
                      <li class="active">Distribution</li>
                      <li>Production</li>
                      {{-- <li>Refund</li> --}}
                      <li>Done</li>
                      @elseif($track->order_status == 'production')
                      <li class="active">Placed</li>
                      <li class="active">Processing</li>
                      <li class="active">Distribution</li>
                      <li class="active">Production</li>
                      {{-- <li>Refund</li> --}}
                      <li>Done</li>
                      @elseif($track->order_status == 'refund')
                      <li class="active">Placed</li>
                      <li class="active">Processing</li>
                      <li class="active">Distribution</li>
                      <li class="active">Production</li>
                      <li class="active">Refund</li>
                      <li>Done</li>
                      @elseif($track->order_status == 'done')
                      <li class="active">Placed</li>
                      <li class="active">Processing</li>
                      <li class="active">Distribution</li>
                      <li class="active">Production</li>
                      {{-- <li class="active">Refund</li> --}}
                      <li class="active">Done</li>
                      {{-- @elseif($track->order_status == 'deleted')
                      <li class="active">Placed</li>
                      <li class="active">Processing</li>
                      <li class="active">Distribution</li>
                      <li class="active">Production</li>
                      {{-- <li class="active">Refund</li> --}}
                      {{-- <li class="active">Done</li> --}}
                      {{-- <li class="active">Deleted</li> --}}
                      @endif
                    </ul>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="order_summary-list ">
                            <div class="title-about-us1">
                                <div class="title-about-us_one summary_to">
                                  <h4>Order Information</h4>
                                </div>
                            </div>
                            <ul class="list-unstyled">
                                <li>Oder Number: <span><strong>{{ $track->id }}</strong></span></li>
                                <li>Date: <span><strong>{{ $track->order_date->format('m-d-Y') }}</strong></span></li>
                                <li>Quantity: <span><strong>{{ $detail->qty }} item(s)</strong></span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
    <!-- //Main Container -->

@endsection