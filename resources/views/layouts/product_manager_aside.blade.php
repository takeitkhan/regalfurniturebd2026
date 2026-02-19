<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <ul class="sidebar-menu" data-widget="tree">
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
        </ul>
    </section>
</aside>
