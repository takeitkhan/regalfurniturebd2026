<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="more-product-title">
                <h3>You May also like</h3>
            </div>
        </div>
    </div>
    <div class="force_margin">
        <div class="more-product-warp">

            <?php
            $cats = explode(',', $product->categories);

            $default = array(
                'category_id' => array($cats[0]),
                'limit' => 6,
                'post_id' => null
            );

            $ymal = you_may_also_like($default);

            ?>
            @foreach($ymal as $item)
                <div class="col-md-2 col-sm-6">
                    <div class="singele-exc-prdt tab-product-single">
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
                        <div class="exc-prdt-img tab-product-img">
                            <a href="{!! product_seo_url($item->seo_url) !!}">
                                <img
                                        src="{!! get_first_product_image($item->id, $item) !!}"
                                        alt="exc-prdt">
                            </a>
                        </div>
                        <div class="exc-prdt-text">
                            <h3>
                                <a href="{!! product_seo_url($item->seo_url) !!}">
                                    {!! $item->title !!}
                                </a>
                            </h3>
                            <h2>
                                <a href="{!! product_seo_url($item->seo_url) !!}">

                                    @if($item->local_discount == null)
                                        {{ $tksign . number_format($item->local_selling_price) }}

                                        @if($item->unit == env('SFT'))
                                            <span class="unit_p">/sft</span>
                                        @elseif($item->unit == env('CFT'))
                                            <span class="unit_p">/cft</span>
                                        @elseif($item->unit == env('PIECE'))
                                            <span class="unit_p">/pcs</span>
                                        @endif

                                    @else
                                        {{ discounted_price($item, NULL) }}
                                        <span> {{ $tksign . $item->local_selling_price }} </span>

                                        @if($item->unit == env('SFT'))
                                            <span class="unit_p">/sft</span>
                                        @elseif($item->unit == env('CFT'))
                                            <span class="unit_p">/cft</span>
                                        @elseif($item->unit == env('PIECE'))
                                            <span class="unit_p">/pcs</span>
                                        @endif
                                    @endif

                                </a>
                            </h2>
                            <div class="product-btn">
                                <div class="buyy-btn">
                                    @if($item->local_discount == null)
                                        <?php $regularprice = local_selling_price($item, TRUE); ?>
                                        <?php $save = null; ?>
                                        <?php $sp = local_selling_price($item, TRUE); ?>
                                    @else
                                        <?php $regularprice = local_selling_price($item, TRUE); ?>
                                        <?php $save = save_price($item, TRUE); ?>
                                        <?php $sp = discounted_price($item, TRUE); ?>
                                    @endif

                                    @if($item->unit == env('SFT') || $item->unit == env('CFT'))

                                    @else
                                        <button class="btn buy-now"
                                                onclick="add_to_cart(
                                                        '{{ $item->id }}',
                                                        '{{ $item->product_code }}',
                                                        '{{ $item->product_code }}',
                                                        '{{ $regularprice }}',
                                                        '{{ $save }}',
                                                        '{{ $sp }}',
                                                        null,
                                                        null,
                                                        null);">
                                            <i class="fa fa-shopping-cart"></i>
                                            Buy
                                        </button>
                                    @endif
                                </div>
                                <div class="detalis-btn">
                                    <a href="{!! product_seo_url($item->seo_url) !!}">detalis</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
</div>
