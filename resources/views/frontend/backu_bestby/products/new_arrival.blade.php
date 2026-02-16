@extends('frontend.layouts.app')
@section('content')
    <?php $tksign = '&#2547; '; ?>
    <div class="main-container container">
        <ul class="breadcrumb">
            <li><a href="{{ url('/') }}"><i class="fa fa-home"></i></a></li>
            <li><a href="#">New Arrival</a></li>
        </ul>
            <div class="row">
                <div class="col-md-12 col-sm-9">
                    <div class="category-product">
                        <div class="">
                            <?php $i = 1; ?>
                            <div class="products-list row nopadding-xs so-filter-gird grid">
                                <?php $total = $products->count(); ?>
                                @foreach($products as $product)                           

                                    @php
                                        $first_image = App\ProductImages::where('main_pid', $product->id)->where('is_main_image', 1)->get()->first();
                                        //dump($first_image);
                                        $second_image = App\ProductImages::where('main_pid', $product->id)->where('is_main_image', 0)->get()->first();

                                        $regularprice = $product->local_selling_price;
                                        $save = ($product->local_selling_price * $product->local_discount) / 100;
                                        $sp = round($regularprice - $save);

                                        //dump($product->id);

                                        echo product_design([
                                            'bootstrap_cols' => 'product-layout col-lg-15 col-md-4 col-sm-6 col-xs-6',
                                            'seo_url' => product_seo_url($product->seo_url, $product->id),
                                            'straight_seo_url' => $product->seo_url,
                                            'title' => limit_character($product->title, 35),
                                            'first_image' => !empty($first_image) ? url($first_image['full_size_directory']) : null,
                                            'second_image' => !empty($second_image) ? url($second_image['full_size_directory']) : null,
                                            'discount_rate' => $product->local_discount,
                                            'price' => $sp,
                                            'old_price' => $regularprice,
                                            'descriptions' => $product->description,
                                            'product_code' => $product->product_code,
                                            'product_sku' => $product->sku,
                                            'product_id' => $product->id,
                                            'product_qty' => 1,
                                            'sign' => '&#2547; '
                                        ]);

                                    @endphp
                                @endforeach

                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    {{-- @include('frontend.products.pagination') --}}

                    <?php
                    // echo pagination($total, 5, app('request')->input('pages'), '?');
                    ?>
                </div>
            </div>
</div>
@endsection