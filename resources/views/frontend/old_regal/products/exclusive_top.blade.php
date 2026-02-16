<div class="col-sm-12 col-md-9">
    <div class="exc-prdt-warp">
        <div class="section-title">
            <h1>Exclusive Products</h1>
        </div>
        <div class="exc-item-warp">
            <?php $tksign = '&#2547; '; ?>
            <?php $i = 0; ?>

            <?php
            //dd($t_exclusive);
            ?>
            @foreach($t_exclusive as $exclusive_product)
                @if (!empty($exclusive_product))
                    <div class="col-md-3 col-sm-6 col-xs-6">
                        <div class="singele-exc-prdt">
                            <div class="compare">
                                <a href="javascript:void(0)"
                                   class="compare-btn"
                                   onclick="add_to_compare(
                                           '{{ $exclusive_product->id }}',
                                           '{{ $exclusive_product->product_code }}',
                                           '{{ $exclusive_product->seo_url }}');">
                                    <i class="fa fa-balance-scale"></i>
                                </a>
                            </div>
                            <div class="exc-prdt-img">
                                <a href="{!! product_seo_url($exclusive_product->seo_url, $exclusive_product->id) !!}">
                                    <?php
                                    $images = explode(',', $exclusive_product->images);
                                    $imgs = App\Image::find($images);
                                    ?>

                                    <img src="{{ url($imgs[0]['full_size_directory']) }}"
                                         alt="{{ $imgs[0]['original_name'] }}"/>

                                </a>
                            </div>

                            <div class="exc-prdt-text">
                                <?php
                                $regularprice = $exclusive_product->local_selling_price;
                                $save = ($exclusive_product->local_selling_price * $exclusive_product->local_discount) / 100;
                                $sp = $regularprice - $save;
                                ?>
                                <h3>
                                    <a href="{!! product_seo_url($exclusive_product->seo_url, $exclusive_product->id) !!}">
                                        {{ limit_text($exclusive_product->sub_title, 4) }}
                                    </a>
                                    <br/>
                                    <a class="cat-nane" href="{!! product_seo_url($exclusive_product->seo_url, $exclusive_product->id) !!}">
                                        {{ limit_text($exclusive_product->title, 4) }}
                                    </a>
                                </h3>
                                <h2>

                                    @if(!empty($exclusive_product->local_discount))
                                        <a href="{!! product_seo_url($exclusive_product->seo_url, $exclusive_product->id) !!}">
                                            {{ $tksign }} {{ number_format($sp) }}
                                            <span>{{ $tksign }} {{ $exclusive_product->local_selling_price }}</span>
                                            @if($exclusive_product->unit == env('SFT'))
                                                <span class="unit_p">/sft</span>
                                            @elseif($exclusive_product->unit == env('CFT'))
                                                <span class="unit_p">/cft</span>
                                            @elseif($exclusive_product->unit == env('PIECE'))
                                                <span class="unit_p">/pcs</span>
                                            @endif
                                        </a>
                                    @else

                                        <a href="{!! product_seo_url($exclusive_product->seo_url, $exclusive_product->id) !!}">
                                            {{ $tksign }} {{ number_format($exclusive_product->local_selling_price) }}

                                            @if($exclusive_product->unit == env('SFT'))
                                                <span class="unit_p">/sft</span>
                                            @elseif($exclusive_product->unit == env('CFT'))
                                                <span class="unit_p">/cft</span>
                                            @elseif($exclusive_product->unit == env('PIECE'))
                                                <span class="unit_p">/pcs</span>
                                            @endif
                                        </a>
                                    @endif

                                </h2>
                                <div class="product-btn">

                                    <div class="buy-btn">
                                        @if($exclusive_product->unit == env('SFT') || $exclusive_product->unit == env('CFT'))

                                        @else
                                            <a type="button"
                                               id="buynow"
                                               href="javascript:void(0)"
                                               class="buy-now"
                                               onclick="add_to_cart(
                                                       '{{ $exclusive_product->id }}',
                                                       '{{ $exclusive_product->product_code }}',
                                                       '{{ $exclusive_product->sku }}',
                                                       '{{ $exclusive_product->local_selling_price }}',
                                                       '{{ $save }}',
                                                       '{{ $sp }}',
                                                       '{{ $exclusive_product->delivery_charge }}',
                                                       null,
                                                       1);">
                                                <i class="fa fa-shopping-cart"></i> Buy
                                            </a>
                                        @endif
                                    </div>

                                    <div class="detalis-btn">
                                        <a href="{!! product_seo_url($exclusive_product->seo_url, $exclusive_product->id) !!}">details</a>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <?php $i++; ?>
            @endforeach
        </div>
    </div>
</div>

