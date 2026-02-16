<div class="shopping_cart shopping_cart_action">
    <div id="cart" class="btn-shopping-cart">
        <a data-loading-text="Loading... "
           href="{{ url('view_cart') }}"
           class="btn-group top_cart dropdown-toggle"
           data-toggle="dropdown"
           aria-expanded="true">
            <div class="shopcart">
                <span class="icon-c"><i class="fa fa-shopping-bag"></i></span>

                <div class="shopcart-inner">
                    <div class="car_skgd">
                         <img src="{{ url('/public/frontend/ico/cart.png') }}" alt="">
                    </div>
                    <!-- <p class="text-shopping-cart">
                        My cart
                    </p> -->

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
                        @if(!empty($total_qty))
                        <span class="items_cart" id="items_count">
                            {!! (!empty($total_qty) ? $total_qty : 0) !!}
                        </span>
                        @endif
                        <span class="items_cart2"> item(s)</span>
                        <!-- <span class="items_carts">
                            ( <span id="items_total">{!! (!empty($totalprice) ? $tksign . number_format($totalprice) : 0) !!}</span> )
                        </span> -->
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
