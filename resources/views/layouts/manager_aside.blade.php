<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <ul class="sidebar-menu" data-widget="tree">
            <li class="treeview {{ Request::is('dashboard') || Request::is('most_sold') || Request::is('never_sold') ? 'active menu-open' : '' }}">
                <a href="">
                    <i class="fa fa-newspaper-o"></i> <span> Reports Dashboard</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="{{ url('/dashboard') }}">
                            <i class="fa fa-th"></i> <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/most_sold') }}">
                            <i class="fa fa-th"></i> <span> Most Selling</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/never_sold') }}">
                            <i class="fa fa-th"></i> <span> Never Sold</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="treeview {{ Request::is('orders') || Request::is('orders/prebooking') || Request::is('search_orders') || Request::is('orders/placed_cod') || Request::is('orders/placed') || Request::is('orders/production') || Request::is('orders/distribution') || Request::is('orders/processing')
                    || Request::is('orders/done') || Request::is('orders/refund') || Request::is('orders/cod')
                    || Request::is('orders/deleted') ? 'active menu-open' : '' }}">
                <a href="">
                    <i class="fa fa-newspaper-o"></i> <span> Orders</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ Request::is('orders') || Request::is('search_orders') ? 'active' : '' }}">
                        <a href="{{ url('orders') }}">
                            <i class="fa fa-circle-o"></i> Orders
                        </a>
                    </li>

                    <li class="{{ Request::is('orders/custom-order') || Request::is('orders/custom-order') ? 'active' : '' }}">
                        <a href="{{ url('orders/custom-order') }}">
                            <i class="fa fa-circle-o"></i> Custom Order
                        </a>
                    </li>

                    <li class="{{ Request::is('orders/one-click-buy-now') || Request::is('orders/one-click-buy-now') ? 'active' : '' }}">
                        <a href="{{ url('orders/one-click-buy-now') }}">
                            <i class="fa fa-circle-o"></i> One Click Buy Now
                        </a>
                    </li>

                    {{--
                    <li class="{{ Request::is('orders/prebooking') ? 'active' : '' }}">
                        <a href="{{ url('orders/prebooking?preBooking=show') }}">
                            <i class="fa fa-circle-o"></i> Prebooking Orders
                        </a>
                    </li>

                    <li class="{{ Request::is('orders/date_between') || Request::is('orders/date_between') ? 'active' : '' }}">
                        <a href="{{ url('orders/date_between') }}">
                            <i class="fa fa-circle-o"></i> Date Between
                        </a>
                    </li>

                    <li class="{{ Request::is('orders/placed_cod') || Request::is('orders/placed_cod') ? 'active' : '' }}">
                        <a href="{{ url('orders/placed_cod') }}">
                            <i class="fa fa-circle-o"></i> COD Placed Orders
                        </a>
                    </li>

                    <li class="{{ Request::is('orders/placed_opo') || Request::is('orders/placed_opo') ? 'active' : '' }}">
                        <a href="{{ url('orders/placed_opo') }}">
                            <i class="fa fa-circle-o"></i> Online Paid Orders
                        </a>
                    </li>

                    <li class="{{ Request::is('orders/placed') || Request::is('orders/placed') ? 'active' : '' }}">
                        <a href="{{ url('orders/placed') }}">
                            <i class="fa fa-circle-o"></i> Placed Orders
                        </a>
                    </li>

                    <li class="{{ Request::is('orders/production') || Request::is('orders/production') ? 'active' : '' }}">
                        <a href="{{ url('orders/production') }}">
                            <i class="fa fa-circle-o"></i> Order Production
                        </a>
                    </li>

                    <li class="{{ Request::is('orders/processing') || Request::is('orders/processing') ? 'active' : '' }}">
                        <a href="{{ url('orders/processing') }}">
                            <i class="fa fa-circle-o"></i> Order Processing
                        </a>
                    </li>

                    <li class="{{ Request::is('orders/distribution') || Request::is('orders/distribution') ? 'active' : '' }}">
                        <a href="{{ url('orders/distribution') }}">
                            <i class="fa fa-circle-o"></i> Order Distribution
                        </a>
                    </li>

                    <li class="{{ Request::is('orders/done') || Request::is('orders/done') ? 'active' : '' }}">
                        <a href="{{ url('orders/done') }}">
                            <i class="fa fa-circle-o"></i> Order Done
                        </a>
                    </li>

                    <li class="{{ Request::is('orders/refund') || Request::is('orders/refund') ? 'active' : '' }}">
                        <a href="{{ url('orders/refund') }}">
                            <i class="fa fa-circle-o"></i> Order Refunded
                        </a>
                    </li>

                    <li class="{{ Request::is('orders/cod') || Request::is('orders/cod') ? 'active' : '' }}">
                        <a href="{{ url('orders/cod') }}">
                            <i class="fa fa-circle-o"></i> Cash On Delivery
                        </a>
                    </li>

                    <li class="{{ Request::is('orders/deleted') || Request::is('orders/deleted') ? 'active' : '' }}">
                        <a href="{{ url('orders/deleted') }}">
                            <i class="fa fa-circle-o"></i> Order Deleted
                        </a>
                    </li>
                    --}}

                    <li class="{{ Request::is('orders/temporary') || Request::is('orders/temporary') ? 'active' : '' }}">
                        <a href="{{ url('orders/temporary') }}">
                            <i class="fa fa-circle-o"></i> Temporary Order List
                        </a>
                    </li>
                    <li class="separator"></li>
                    <li class="{{ Request::is('export_orders') ? 'active' : '' }}">
                        <a target="_blank" href="{{ url('export_orders') }}">
                            <i class="fa fa-download"></i> Excel Export
                        </a>
                    </li>
                </ul>
            </li>

            <li class="treeview {{ Request::is('products') ||
                        Request::is('products_express_delivery') ||
                        Request::is('products_enable_comment') ||
                        Request::is('products_enable_review') ||
                        Request::is('products_new_arrival') ||
                        Request::is('products_best_selling') ||
                        Request::is('products_recommended') ||
                        Request::is('admin/variation-group') ||
                        Request::is('products_disable_buy') ||
                        Request::is('export_products') ||
                        Request::is('import_stock') ||
                        Request::is('admin/product-set*') ||
                        Request::is('import_stock_view') ? 'active menu-open' : '' }}">
                <a href="">
                    <i class="fa fa-newspaper-o"></i> <span> Products</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ Request::is('add_product') || Request::is('add_product') ? 'active' : '' }}">
                        <a href="{{ url('add_product') }}">
                            <i class="fa fa-newspaper-o"></i> Add Product
                        </a>
                    </li>
                    <li class="{{ Request::is('products') || Request::is('products') ? 'active' : '' }}">
                        <a href="{{ url('products') }}">
                            <i class="fa fa-newspaper-o"></i> Products
                        </a>
                    </li>
                    <li class="{{ Request::is('import_stock_view') || Request::is('import_stock_view') ? 'active' : '' }}">
                        <a href="{{ url('import_stock_view') }}">
                            <i class="fa fa-upload" aria-hidden="true"></i> Import Product Stock
                        </a>
                    </li>
                    <li class="{{ Request::is('admin/product-set/create*') ? 'active' : '' }}">
                        <a href="{{ route('admin.product_set.create') }}">
                            <i class="fa fa-newspaper-o"></i> Add Product Set
                        </a>
                    </li>

                    <li class="{{ Request::is('admin/product-set') ? 'active' : '' }}">
                        <a href="{{ route('admin.product_set.index') }}">
                            <i class="fa fa-newspaper-o"></i> Product Set
                        </a>
                    </li>

                    <li class="{{ Request::is('products_express_delivery') || Request::is('products_express_delivery') ? 'active' : '' }}">
                        <a href="{{ url('products_express_delivery') }}">
                            <i class="fa fa-angle-right"></i> Express Delivery
                        </a>
                    </li>
                    <li class="{{ Request::is('products_enable_comment') || Request::is('products_enable_comment') ? 'active' : '' }}">
                        <a href="{{ url('products_enable_comment') }}">
                            <i class="fa fa-angle-right"></i> Enable Comment
                        </a>
                    </li>
                    <li class="{{ Request::is('products_enable_review') || Request::is('products_enable_review') ? 'active' : '' }}">
                        <a href="{{ url('products_enable_review') }}">
                            <i class="fa fa-angle-right"></i> Enable Review
                        </a>
                    </li>
                    <li class="{{ Request::is('products_new_arrival') || Request::is('products_new_arrival') ? 'active' : '' }}">
                        <a href="{{ url('products_new_arrival') }}">
                            <i class="fa fa-angle-right"></i> New Arrival
                        </a>
                    </li>
                    <li class="{{ Request::is('products_best_selling') || Request::is('products_best_selling') ? 'active' : '' }}">
                        <a href="{{ url('products_best_selling') }}">
                            <i class="fa fa-angle-right"></i> Best Selling
                        </a>
                    </li>
                    <li class="{{ Request::is('products_recommended') || Request::is('products_recommended') ? 'active' : '' }}">
                        <a href="{{ url('products_recommended') }}">
                            <i class="fa fa-angle-right"></i> Recommended
                        </a>
                    </li>

                    <li class="{{ Request::is('admin/variation-group') || Request::is('admin/variation-group') ? 'active' : '' }}">
                        <a href="{{ url('admin/variation-group') }}">
                            <i class="fa fa-angle-right"></i> Variation group
                        </a>
                    </li>
                    <li class="{{ Request::is('products_disable_buy') || Request::is('products_disable_buy') ? 'active' : '' }}">
                        <a href="{{ url('products_disable_buy') }}">
                            <i class="fa fa-angle-right"></i> Disable Buy
                        </a>
                    </li>
                </ul>
            </li>

            <li class="treeview {{ Request::is('users') || Request::is('add_user') ? 'active menu-open' : '' }}">
                <a href="">
                    <i class="fa fa-users"></i> <span>Contacts Management</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ Request::is('users') || Request::is('users') ? 'active' : '' }}">
                        <a href="{{ url('users') }}">
                            <i class="fa fa-users"></i> Users
                        </a>
                    </li>
                    <li class="{{ Request::is('add_user') || Request::is('add_user') ? 'active' : '' }}">
                        <a href="{{ url('add_user') }}">
                            <i class="fa fa-user-plus"></i> Add user
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </section>
</aside>
