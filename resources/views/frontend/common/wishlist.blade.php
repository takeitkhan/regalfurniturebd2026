

@extends('frontend.layouts.app')

@section('content')
    <?php $tksign = '&#2547; '; ?>
    <div class="main-container container">




        <!-- breadcrumb area -->
        <section class="breadcrumb-area">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="breadcrumb-warp">
                            <div class="breadcrumb-one">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Wishlist</li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- breadcrumb area -->

        <section>
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="page-title-sg">
                            <h4>Wishlist</h4>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="wishlist-area">

                                    <table class="table">
                                        <thead class="ahlc">
                                        <tr>
                                            <th style="width: 10%">Products</th>
                                            <th style="width:50%">Details</th>
                                            <th style="width:20%">Price</th>
                                            <th style="width:5%">Edit</th>
                                            <th style="width:15%" class="text-center">Move to cart</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse ($wishs as $item)
                                            @php
                                           /// dump($item->products);
                                                $first_image = \App\ProductImages::where('main_pid', $item->products->id)->where('is_main_image', 1)->get()->first();
                                                $get_price =  get_product_price(['main_pid' => $item->products->id]);



                                                $r_price = $get_price['r_price'];
                                                $s_price = $get_price['s_price'];


                                                $save = $r_price - $s_price;

                                            @endphp


                                            <tr class="border-none2">
                                                <td>
                                                    <div class="w-product-price">
                                                        <img src="{{ url($first_image->full_size_directory) }}" alt="{{ $item->products->title }}">


                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="w_product_n">
                                                        <h5><strong>{{ $item->products->title }}</strong></h5>
                                                        <p><b>Item Name: </b> {{ $item->products->sub_title }}</p>
                                                        <p><b>Item code: </b> {{ $item->products->product_code }}</p>
                                                    </div>
                                                </td>
                                                <td>

                                                    <?php
                                                    if ($save > 0) {
                                                        $price = '<strong><s>' . $tksign . number_format($r_price) . '</s></strong><br>';
                                                        $price .= '<strong>' . $tksign . number_format($s_price) . '</strong>';
                                                    } else {
                                                        $price = '<strong>' . $tksign . number_format($r_price) . '</strong>';
                                                    }
                                                    ?>
                                                    {!! $price !!}

                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="group-b">
                                                        <div class="btn-group btn-group-sm" role="group" aria-label="">

                                                            <a href="{{ url('p/' .  $item->products->seo_url) }}" class="btn afwcsfk" >
                                                                <i class="fa fa-eye"></i>
                                                            </a>

                                                            <button type="button" onclick="remove_wishlist_product('{{ $item->products->id }}', '{{ $item->products->product_code }}');" class="btn afvwar"><i class="fa fa-times"></i>
                                                            </button>

{{--                                                            <a class="btn btn-danger"--}}
{{--                                                               href="javascript:void(0)"--}}
{{--                                                               onclick="remove_wishlist_product('{{ $item->products->id }}', '{{ $item->products->product_code }}');"--}}
{{--                                                               data-original-title="Remove">--}}
{{--                                                                <i class="fa fa-times"></i>--}}
{{--                                                            </a>--}}
                                                        </div>
                                                    </div>

                                                </td>
                                                <td class="text-center">
{{--                                                    @php--}}
{{--                                                        dump($get_price);--}}
{{--                                                    @endphp--}}


                                                    <button class="move-to" data-color_id="{{ $get_price['multi_id'] }}" data-size_id="{{ $get_price['multi_id'] }}" data-productid="{{ $item->products->id }}" onclick="add_to_cart_data(this);" data-qty="1" >Add to cart</button>

                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4">
                                                    <div class="text-center">
                                                        No Product Added
                                                    </div>
                                                </td>
                                            </tr>



                                        @endforelse


                                        </tbody>
                                    </table>

                        </div>
                    </div>
                </div>
            </div>
        </section>





    </div>
@endsection