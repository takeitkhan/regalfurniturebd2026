@extends('frontend.layouts.app')

@section('content')
    <?php $tksign = '&#2547; '; ?>
    <div class="main-container container">
        <ul class="breadcrumb">
            <li><a href="#"><i class="fa fa-home"></i></a></li>
            <li><a href="#">My Wish List</a></li>
        </ul>
        
        <div class="row">
            <!--Middle Part Start-->
            <div id="content" class="col-sm-9">
                <h2 class="title">My Wish List</h2>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <td class="text-center">Image</td>
                            <td class="text-left">Product Name</td>
                            <td class="text-right">Unit Price</td>
                            <td class="text-right">Action</td>
                        </tr>
                        </thead>
                        <tbody>
                            @forelse ($wishs as $item)
                            @php
                                $first_image = \App\ProductImages::where('main_pid', $item->products->id)->where('is_main_image', 1)->get()->first();
                                $regularprice = $item->products->local_selling_price;
                                $save = ($item->products->local_selling_price * $item->products->local_discount) / 100;
                                $sp = $regularprice - $save;
                            @endphp
                                <tr>
                                    <td class="text-center">
                                        <a href="{{ url('p/' .  $item->products->seo_url) }}" target="_blank"
                                            title="{{ $item->products->title }}">
                                            <img width="100" src="{{ url($first_image->full_size_directory) }}"
                                                    class="img-1 img-responsive" alt="{{ $item->products->title }}">
                                        </a>
                                    </td>
                                    <td class="text-left">
                                        <a href="{{ url('p/' .  $item->products->seo_url) }}">
                                            {{ $item->products->title }}
                                        </a>
                                    </td>
                                    <td class="text-right">
                                        <div class="price">
                                            <?php
                                            if ($regularprice < $sp) {
                                                $price = '<span class="price-new">' . $tksign . number_format($sp) . '</span>';
                                                $price .= '<span class="price-old">' . $tksign . number_format($regularprice) . '</span>';
                                            } else {
                                                $price = '<span class="price-new">' . $tksign . number_format($sp) . '</span>';
                                            }
                                            ?>
                                            {!! $price !!}
                                        </div>
                                    </td>
                                    <td class="text-right">
                                        <button class="btn btn-primary"
                                                class="addToCart" title="Add to cart"
                                                onclick="add_to_cart('{{ $item->products->id }}','{{ $item->products->product_code }}','{{ $item->products->sku }}','{{ $regularprice }}','{{ $regularprice - $sp }}', '{{ $sp }}', 0, null, 1);">
                                            <i class="fa fa-shopping-cart"></i>
                                        </button>
                                        {{-- <a class="btn btn-danger"
                                            href="javascript:void(0)"
                                            onclick="remove_wishlist_product('{{ $item->products->id }}', '{{ $item->products->product_code }}');"
                                            data-original-title="Remove">
                                            <i class="fa fa-times"></i>
                                        </a> --}}
                                        <a class="btn btn-danger"
                                            href="javascript:void(0)"
                                            onclick="remove_wishlist_product('{{ $item->products->id }}', '{{ $item->products->product_code }}');"
                                            data-original-title="Remove">
                                            <i class="fa fa-times"></i>
                                        </a>
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
            {{-- @include('frontend.common.frontend_user_menu') --}}
        </div>
    </div>
@endsection