<div class="shopping_cart">
    <div id="cart" class="btn-shopping-cart">
        <a data-loading-text="Loading... "
           href="{{ url('view_cart') }}"
           class="btn-group top_cart dropdown-toggle"
           data-toggle="dropdown"
           aria-expanded="true">
            <div class="shopcart">
                <span class="icon-c"><i class="fa fa-shopping-bag"></i></span>

                <div class="shopcart-inner">
                    <p class="text-shopping-cart">
                        My cart
                    </p>

                    <?php
                    $data = session()->all();
                    if (!empty($data['cart'])) {
                        $cart = $data['cart'];
                        $total_qty = array_sum(array_column($cart->items, 'qty'));
                        $individual_price = array();
                        foreach ($cart->items as $item) {
                            $individual_price[] = $item['purchaseprice'] * $item['qty'];
                        }
                        $totalprice = array_sum($individual_price);

                    } else {
                        $total_qty = 0;
                        $totalprice = number_format(0);
                    }
                    ?>

                    <span class="total-shopping-cart cart-total-full">
                        <span class="items_cart" id="items_count">
                            {!! (!empty($total_qty) ? $total_qty : 0) !!}
                        </span>
                        <span class="items_cart2"> item(s)</span>
                        <span class="items_carts">
                            ( <span id="items_total">{!! (!empty($totalprice) ? $tksign . number_format($totalprice) : 0) !!}</span> )
                        </span>
                    </span>
                </div>
            </div>
        </a>
        <ul class="dropdown-menu pull-right shoppingcart-box" role="menu">
            <li>
                <div>
                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <td class="text-left"><strong>Quantity</strong>
                            </td>
                            <td class="text-right">
                                <span id="items_count">{!! (!empty($total_qty) ? $total_qty : 0) !!}</span> item (s)
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left">
                                <strong>Total</strong>
                            </td>
                            <td class="text-right">
                                <span id="items_total">{!! (!empty($totalprice) ? $tksign . number_format($totalprice) : 0) !!}</span>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <p class="text-right">
                        <a class="btn view-cart" href="{{ url('view_cart') }}">
                            <i class="fa fa-shopping-cart"></i> View Cart
                        </a>
                    </p>
                </div>
            </li>
        </ul>
    </div>
</div>

<ul class="wishlist-comp">
    @if(!empty($data['comparison']))
        <li class="compare compare-pos icon-c">
            <a href="{{ url('view_compare') }}" class="top-link-compare" title="Compare">

                    <i class="fa fa-balance-scale"></i>

            </a>
            <?php
            $comparison = $data['comparison']->items;
            $total = count($comparison);
            ?>
            <div class="comp-ct" id="show_total_compare">{{ $total }}</div>
        </li>
    @else
        <li class="compare compare-pos icon-wc icon-c">
            <a href="{{ url('view_compare') }}" class="top-link-compare" title="Compare">

                    <i class="fa fa-balance-scale"></i>

            </a>
            <div class="comp-ct" id="show_total_compare">0</div>
        </li>
    @endif
    @if (Auth::check())
        @if(!empty($data['wishlist']))
            <li class="wishlist compare-pos icon-c ">
                <a class="compare-pos5" href="{{ url('wishlist') }}" class="top-link-compare" title="Wishlist">

                        <i class="fa fa-heart"></i>

                </a>
                <?php
                $wishlist = $data['wishlist']->items;
                $total = count($wishlist);
                ?>
                <div class="comp-ct" id="show_total_wishlist">{{ $total }}</div>
            </li>
        @else
            <li class="wishlist compare-pos icon-c">
                <a class="compare-pos5" href="{{ url('wishlist') }}" id="wishlist-total" class="top-link-wishlist" title="Wish List (0)">

                        <i class="fa fa-heart"></i>

                </a>
                <div class="comp-ct">0</div>
            </li>
        @endif
    @endif
</ul>