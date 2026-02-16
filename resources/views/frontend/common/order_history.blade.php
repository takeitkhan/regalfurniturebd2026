@extends('frontend.layouts.app')

@section('content')
    <?php $tksign = '&#2547; '; ?>
    <div class="main-container container">
        <ul class="breadcrumb">
            <li><a href="{{ url('/') }}"><i class="fa fa-home"></i></a></li>
            <li><a href="#">Account</a></li>
            <li><a href="#">My Orders</a></li>
        </ul>

        <div class="row">
            <div id="content" class="col-sm-9">
                <h2 class="title">My Orders</h2>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <td style="width:15%; text-align: left;"></td>
                            <td style="width:30%; text-align: left;">Product Name</td>
                            <td style="width:10%; text-align: center;">Order ID</td>
                            <td style="width:10%; text-align: left;">Order Date</td>
                            <td style="width:15%">Unit Price</td>
                            <td style="width:10%">Qty</td>
                            <td style="width:15%">Total</td>
                            <td style="width:5%">Status</td>
                            <td style="width:10%; text-align: center;">Invoice</td>
                        </tr>
                        </thead>
                        <tbody>
                        @if(!empty($orders_detail))
                            @foreach($orders_detail as $line)

                                <?php
                                //dd($line);
                                $orders_master = \App\OrdersMaster::where(['order_random' => $line->order_random, 'secret_key' => $line->secret_key])->get()->first();
                                $product = \App\Product::where(['id' => $line->product_id])->get()->first();
                                ?>
                                @if(!empty($product))
                                    <tr class="CartProduct">
                                        <td class="CartProductThumb cart-pd-thumb">
                                            <div>
                                                <?php
                                                $first_image = \App\ProductImages::where('main_pid', $line->product_id)->where('is_main_image', 1)->get()->first();

                                                if (!empty($first_image->full_size_directory)) {
                                                    $img = url($first_image->full_size_directory);
                                                } else {
                                                    $img = url('storage/uploads/fullsize/2019-01/default.jpg');
                                                }
                                                ?>

                                                <a href="javascript:void(0)">
                                                    <img
                                                            src="{{ $img }}"
                                                            alt="img" style="opacity: 1;">
                                                </a>
                                            </div>
                                        </td>
                                        <td>
                                            <h4>
                                                <a href="javascript:void(0);">
                                                    <small>{{ $line->product_name }}</small>
                                                </a>
                                            </h4>
                                        </td>
                                        <td style="text-align: center;">
                                            <h4>
                                                <a href="javascript:void(0);">
                                                    <small>{{ !empty($orders_master->id) ? $orders_master->id : NULL }}</small>
                                                </a>
                                            </h4>
                                        </td>
                                        <td>
                                            <h4>
                                                <a href="javascript:void(0);">
                                                    <small>{{ $line->created_at }}</small>
                                                </a>
                                            </h4>
                                        </td>
                                        <td>
                                            <div class="price price-one">
                                                <span>
                                                    {{ $tksign . number_format($product->local_selling_price) }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="checker-one">
                                            <div class="price price-one">
                                                <span>
                                                    {{ $line->qty }}
                                                </span>
                                            </div>
                                        </td>

                                        <td class="price price-one">
                                            <span>
                                                {{ $tksign . number_format($line->local_purchase_price * $line->qty) }}
                                            </span>
                                        </td>

                                        <td class="price price-one">
                                            <span>
                                                {{ !empty($orders_master->order_status) ? $orders_master->order_status : NULL }}
                                            </span>
                                        </td>

                                        <td style="text-align: center;">
                                            @if($orders_master)
                                            @if($orders_master->order_status == 'DECLINED' || $orders_master->order_status == 'CANCELED')

                                            @else
                                                <a class=" btn btn-large"
                                                   href="{{ url('invoice?order_id=' . (!empty($orders_master->id) ? $orders_master->id : NULL) . '&random_code=' . $line->order_random . '&secret_key=' . $line->secret_key) }}">
                                                    <i class="fa fa-file"></i>
                                                </a>
                                            @endif
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        @endif

                        </tbody>
                    </table>
                </div>
            </div>
            @include('frontend.common.frontend_user_menu')
        </div>
    </div>
@endsection
