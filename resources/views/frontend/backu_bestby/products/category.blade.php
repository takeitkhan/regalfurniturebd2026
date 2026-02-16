@extends('frontend.layouts.app')
@section('content')
    <?php $tksign = '&#2547; '; ?>
    <div class="container">
        <div class="row">
            <div class="breadcrumb-warp section-margin-two">
                <div class="col-md-12">
                    <div class="breadcrumb">
                        <?php
                        $breadcrumbs = new Creitive\Breadcrumbs\Breadcrumbs;

                        $breadcrumbs->setDivider(' » &nbsp;');
                        $breadcrumbs->addCrumb('Home', url('/'))
                            ->addCrumb(get_term_info(Request::segment(2), 'name'), 'product');
                        echo $breadcrumbs->render();
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="main-banner-section section-padding">
        <div class="container">
            <div class="row">
                <div class="col-md-9 col-sm-9">
                    <div class="category-product">
                        <div class="">
                            <?php $i = 1; ?>
                            <div class="exc-item-warp exc-prdt-warp  category-item">
                                <?php $total = $products->count(); ?>
                                @if(!empty($products))

                                    @foreach ($products as $item)

                                        <div class="category-col col-md-3 col-sm-6 col-xs-12">
                                            <div class="singele-exc-prdt singele-exc-prdt_bk">
                                                <div class="compare">
                                                    <a href="javascript:void(0)"
                                                       class="compare-btn"
                                                       onclick="add_to_compare(
                                                               '{{ $item->id }}',
                                                               '{{ $item->product_code }}',
                                                               '{{ $item->seo_url }}');">
                                                        <i class="fa fa-balance-scale"></i>
                                                    </a>
                                                </div>
                                                <div class="exc-prdt-img">
                                                    <a href="{!! product_seo_url($item->seo_url) !!}">

                                                        <?php
                                                        $images = explode(',', $item->images);
                                                        $imgs = App\Image::find($images);
                                                        ?>

                                                        <img src="{{ get_first_product_image($item->id, $item) }}"
                                                             alt="{{ $imgs[0]['original_name'] }}"/>

                                                    </a>
                                                </div>
                                                <div class="exc-prdt-text">
                                                    <h3>
                                                        <a href="{!! product_seo_url($item->seo_url) !!}">

                                                            {{ $item->sub_title }}

                                                        </a>
                                                    </h3>

                                                    <?php
                                                    $regularprice = $item->local_selling_price;
                                                    $save = ($item->local_selling_price * $item->local_discount) / 100;
                                                    $sp = round($regularprice - $save);
                                                    ?>

                                                    <h2>
                                                        <a href="{!! product_seo_url($item->seo_url) !!}">

                                                            {!! $tksign . number_format($regularprice) !!}

                                                        </a>
                                                    </h2>
                                                    <div class="product-btn">

                                                        <div class="buy-btn">

                                                            @if($item->unit == env('SFT') || $item->unit == env('CFT'))

                                                            @else
                                                                <a type="button"
                                                                   id="buynow"
                                                                   href="javascript:void(0)"
                                                                   class="buy-now"
                                                                   onclick="add_to_cart(
                                                                           '{{ $item->id }}',
                                                                           '{{ $item->product_code }}',
                                                                           '{{ $item->sku }}',
                                                                           '{{ $item->local_selling_price }}',
                                                                           '{{ $save }}',
                                                                           '{{ $sp }}',
                                                                           '{{ $item->delivery_charge }}',
                                                                           null,
                                                                           1);">
                                                                    <i class="fa fa-shopping-cart"></i> Buy
                                                                </a>
                                                            @endif

                                                        </div>
                                                        <div class="detalis-btn">
                                                            <a href="{!! product_seo_url($item->seo_url) !!}">
                                                                details </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    @endforeach

                                @endif

                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    {{--@include('frontend.products.pagination')--}}

                    <?php echo pagination($total, 5, app('request')->input('pages'), '?'); ?>
                </div>
            </div>
        </div>
    </div>
@endsection
@include('frontend.products.search_js')