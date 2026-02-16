<section class="similar-product">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="similar-product-title text-center">
                    <h2>Same product in some different technical variation</h2>
                </div>
            </div>
        </div>
        <div class="row">

            @foreach($similar as $item)
                <?php
                $current_product_id = (int)Request::segment(2);
                //dump($current_product_id);
                ?>
                @if($item->id === $current_product_id)
                    {{--<h3 class="text-center">No similar product found</h3>--}}
                @else
                    <div class="similar-warp">
                        <div class="col-sm-4 col-md-2">
                            <div class="similar-product-img">
                                <a href="{!! product_seo_url($item->seo_url) !!}">
                                    <img
                                            src="{{ get_first_product_image($item->id, $item) }}"
                                            alt="">
                                </a>
                            </div>
                        </div>
                        <div class="ohdt">
                            <div class="col-sm-12 col-md-10 ">
                                <div class="compare">
                                    <a style="margin-right: 20px; margin-top: 5px;" href="javascript:void(0)"
                                       class="compare-btn"
                                       onclick="add_to_compare(
                                               '{{ $item->id }}',
                                               '{{ $item->product_code }}',
                                               '{{ $item->seo_url }}');">
                                        <i class="fa fa-balance-scale"></i>
                                    </a>
                                </div>
                                <div class="similar-product-content">
                                    <div class="row">
                                        <a href="{!! product_seo_url($item->seo_url) !!}">
                                            <div class="col-md-5 col-sm-5">
                                            <span class="similar-product-lft">
                                                <div class="sl-pdt-det-header sl-pdt-det-header_ons border-none text-center">
                                                    <h2> {!! $item->title !!} </h2>
                                                    <div class="price">

                                                        @if($item->discount == null)
                                                            <h5>
                                                                Price:
                                                                <span class="higileight"> {{ $tksign . number_format($item->local_selling_price) }}</span>

                                                                @if($item->unit == env('SFT'))
                                                                    <span class="unit_p">/sft</span>
                                                                @elseif($item->unit == env('CFT'))
                                                                    <span class="unit_p">/cft</span>
                                                                @elseif($item->unit == env('PIECE'))
                                                                    <span class="unit_p">/pcs</span>
                                                                @endif
                                                            </h5>
                                                        @else
                                                            <h4>
                                                                Regular Price: <span> {{ $tksign . $item->local_selling_price }}</span>
                                                            </h4>
                                                            <h5>
                                                                <span style="color: blue;">
                                                                    ({{ $item->local_discount }}%)
                                                                </span>
                                                                Discounted Price:
                                                                <span class="higileight"> {{ $tksign . discounted_price($item, NULL) }}</span>
                                                                <small>
                                                                    (Save {{ $tksign . save_price($item, FALSE) }})
                                                                </small>
                                                            </h5>
                                                        @endif

                                                    </div>
                                                </div>
                                            </span>
                                            </div>
                                            <div class="col-md-7 col-sm-7">
                                                <div class="sl-pdt-det-body similar-body">
                                                    <div class="sl-pdt-det-list similar-list">
                                                        <ul>
                                                            {{--<li>--}}
                                                            {{--@if($item->material !== null || $item->product_code !==--}}
                                                            {{--null)--}}
                                                            {{--<b>Product Code:</b>--}}
                                                            {{--<span>{{ $item->product_code }}</span>--}}
                                                            {{--, <b>Material:</b>--}}
                                                            {{--<span> {{ $item->material }}</span>--}}
                                                            {{--@endif--}}
                                                            {{--</li>--}}
                                                            @if($item->opening_system !== null)
                                                                <li>
                                                                    <b>Opening System:</b>
                                                                    <span> {{ get_term_info($item->opening_system, 'name') }}</span>
                                                                </li>
                                                            @endif
                                                            @if($item->size !== null)
                                                                <li>
                                                                    <b>Size:</b>
                                                                    <span>{{ get_term_info($item->size, 'name') }}</span>
                                                                </li>
                                                            @endif
                                                            @if($item->locking_system !== null)
                                                                <li>
                                                                    <b>Locking System:</b>
                                                                    <span>{{ get_term_info($item->locking_system, 'name') }}</span>
                                                                </li>
                                                            @endif
                                                            @if($item->part_palla !== null)
                                                                <li>
                                                                    <b>Part/Palla:</b>
                                                                    <span>{{ get_term_info($item->part_palla, 'name') }}</span>
                                                                </li>
                                                            @endif

                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach

        </div>
    </div>
</section>