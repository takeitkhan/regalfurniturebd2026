<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <!--<div class="user-panel">-->
        <!--    <div class="pull-left image">-->
    <!--        <img src="{{ URL::asset('storage/app/public/img/sam.png') }}" class="img-circle"-->
        <!--             alt="User Image">-->
        <!--    </div>-->
        <!--    <div class="pull-left info">-->
        <!--        <p>-->
    <!--            <?php $user = Auth::user(); ?>-->
    <!--            {{ $user->name }}-->
        <!--        </p>-->
        <!--        <a href="javascript:void(0)">-->
        <!--            <i class="fa fa-circle text-success"></i> Online-->
        <!--        </a>-->
        <!--    </div>-->
        <!--</div>-->

        <ul class="sidebar-menu" data-widget="tree">
            @if (Auth::user()->isAdmin() || Auth::user()->isVendor())
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
            @endif

            @if (Auth::user()->isAdmin() || Auth::user()->isVendor())
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
            @endif

            @if (Auth::user()->isAdmin())
                <li class="treeview {{ Request::is('attributes') || Request::is('add_attributes') || Request::is('add_attributes/{id}') || Request::is('attgroups') || Request::is('add_attgroup') ? 'active menu-open' : '' }}">
                    <a href="">
                        <i class="fa fa-newspaper-o"></i> <span> Attributes</span>
                        <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="{{ Request::is('add_attgroup') || Request::is('add_attgroup') ? 'active' : '' }}">
                            <a href="{{ url('add_attgroup') }}">
                                <i class="fa fa-newspaper-o"></i> Add Attribute Group
                            </a>
                        </li>
                        <li class="{{ Request::is('attgroups') || Request::is('attgroups') ? 'active' : '' }}">
                            <a href="{{ url('attgroups') }}">
                                <i class="fa fa-newspaper-o"></i> Attribute Groups
                            </a>
                        </li>
                    </ul>
                </li>

            @endif

            @if (Auth::user()->isAdmin())
                <li class="{{ Request::is('terms') || Request::is('terms') ? 'active' : '' }}">
                    <a href="{{ url('terms') }}">
                        <i class="fa fa-th-list"></i> Categories
                    </a>
                </li>
            @endif

            @if (Auth::user()->isAdmin() || Auth::user()->isVendor())
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
                        @if (Auth::user()->isAdmin())
                            <li class="{{ Request::is('export_products') || Request::is('export_products') ? 'active' : '' }}">
                                <a href="{{ url('export_products') }}">
                                    <i class="fa fa-angle-right"></i> Export Products
                                </a>
                            </li>


                            <li class="{{ Request::is('import_products_view') || Request::is('import_products_view') ? 'active' : '' }}">
                                <a href="{{ url('import_products_view') }}">
                                    <i class="fa fa-angle-right"></i> Import Products
                                </a>
                            </li>

                            <li class="{{ Request::routeIs('product.attribute.group') || Request::routeIs('product.attribute.group') ? 'active' : '' }}">
                                <a href="{{ route('product.attribute.group') }}">
                                    <i class="fa fa-angle-right"></i> Attributes (new)
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

            @if (Auth::user()->isAdmin())
                <li class="treeview {{ Request::is('users') || Request::is('add_user') || Request::is('newsletters')  ? 'active menu-open' : '' }}">
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
                        <li class="divider"></li>
                        <li class="{{ Request::is('newsletters') || Request::is('newsletters') ? 'active' : '' }}">
                            <a href="{{ url('newsletters') }}">
                                <i class="fa fa-envelope-o"></i> Newsletter
                            </a>
                        </li>

                        <li class="{{ Request::is('export_newsletters') ? 'active' : '' }}">
                            <a href="{{ url('export_newsletters') }}">
                                <i class="fa fa-download"></i> Export Newsletters
                            </a>
                        </li>

                    </ul>
                </li>
            @endif

            @if (Auth::user()->isAdmin())
                <li class="{{ Request::is('medias/all') || Request::is('medias/all') ? 'active' : '' }}">
                    <a href="{{ url('medias/all') }}">
                        <i class="fa fa-file-o"></i> Files
                    </a>
                </li>
            @endif

            @if (Auth::user()->isAdmin())
                <li class="{{ Request::is('posts') || Request::is('posts') ? 'active' : '' }}">
                    <a href="{{ url('posts') }}">
                        <i class="fa fa-newspaper-o"></i> Posts
                    </a>
                </li>
            @endif

            @if (Auth::user()->isAdmin())
                <li class="{{ Request::is('pages') || Request::is('pages') ? 'active' : '' }}">
                    <a href="{{ url('pages') }}">
                        <i class="fa fa-newspaper-o"></i> Pages
                    </a>
                </li>
            @endif

            @if (Auth::user()->isAdmin())
            <li class="{{ Request::is('admin/interiors') || Request::is('admin/interiors') ? 'active' : '' }}">
                <a href="{{ url('admin/interiors') }}">
                    <i class="fa fa-newspaper-o"></i> View Gallery
                </a>
            </li>
        @endif

         @if (Auth::user()->isAdmin())
                <li class="{{ Request::is('our_depots') || Request::is('our_depots') ? 'active' : '' }}">
                    <a href="{{ route('depot.index') }}">
                        <i class="fa fa-users"></i> Our Depot
                    </a>
                </li>

                <li class="treeview {{ Request::is('our_showroom') ? 'active menu-open' : '' }}">
                    <a href="">
                        <i class="fa fa-users"></i> <span>Our Showroom</span>
                        <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="{{ Request::is('our_showroom') ? 'active' : '' }}">
                            <a href="{{ url('our_showroom') }}">
                                <i class="fa fa-users"></i> Showrooms
                            </a>
                        </li>
                        @if (Auth::user()->isAdmin())


                            <li class="{{ Request::is('export_showrooms') || Request::is('export_showrooms') ? 'active' : '' }}">
                                <a href="{{ url('export_showrooms') }}">
                                    <i class="fa fa-angle-right"></i> Export Showrooms
                                </a>
                            </li>


                            <li class="{{ Request::is('import_showrooms_view') || Request::is('import_showrooms_view') ? 'active' : '' }}">
                                <a href="{{ url('import_showrooms_view') }}">
                                    <i class="fa fa-angle-right"></i> Import Showrooms
                                </a>
                            </li>
                        @endif

                    </ul>
                </li>
            @endif

            @if (Auth::user()->isAdmin())
                <li class="treeview {{ Request::is('admin_district*') || Request::is('admin/slider*')  || Request::is('admin/catgallary*') || Request::is('commentss') || Request::is('banks') || Request::is('menus') || Request::is('widgets') || Request::is('all_returns') || Request::is('review') ? 'active menu-open' : '' }}">
                    <a href="">
                        <i class="fa fa-television"></i> <span> Common Things</span>
                        <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="{{ Request::is('banks') || Request::is('banks') ? 'active' : '' }}">
                            <a href="{{ url('banks') }}">
                                <i class="fa fa-comments-o"></i> Banks
                            </a>
                        </li>
                        <li class="{{ Request::is('commentss') || Request::is('commentss') ? 'active' : '' }}">
                            <a href="{{ url('commentss') }}">
                                <i class="fa fa-comments-o"></i> Comments
                            </a>
                        </li>
                        <li class="{{ Request::is('admin/catgallary*') ? 'active' : '' }}">
                            <a href="{{ url('admin/catgallary') }}">
                                <i class="fa fa-book"></i> Image Tag Gallary
                            </a>
                        </li>
                        <li class="{{ Request::is('admin/slider*') ? 'active' : '' }}">
                            <a href="{{ url('admin/slider') }}">
                                <i class="fa fa-book"></i> Slider
                            </a>
                        </li>

                        <li class="{{ Request::is('menus') || Request::is('menus') ? 'active' : '' }}">
                            <a href="{{ url('menus') }}">
                                <i class="fa fa-th-list"></i> Menus
                            </a>
                        </li>
                        <li class="{{ Request::is('widgets') || Request::is('widgets') ? 'active' : '' }}">
                            <a href="{{ url('widgets') }}">
                                <i class="fa fa-book"></i> Widgets
                            </a>
                        </li>
                        <li class="{{ Request::is('all_returns') || Request::is('all_returns') ? 'active' : '' }}">
                            <a href="{{ url('all_returns') }}">
                                <i class="fa fa-random"></i> Return Management
                            </a>
                        </li>
                        <li class="{{ Request::is('review') || Request::is('review') ? 'active' : '' }}">
                            <a href="{{ url('review') }}">
                                <i class="fa fa-thumbs-o-up"></i> Reviews
                            </a>
                        </li>

                        <li class="{{ Request::is('admin_district*') ? 'active' : '' }}">
                            <a href="{{ route('admin_district.index') }}">
                                <i class="fa fa-newspaper-o"></i> District
                            </a>
                        </li>
                    </ul>
                </li>
            @endif

            @if (Auth::user()->isAdmin() || Auth::user()->isVendor())
                <li class="treeview {{ Request::is('coupons') || Request::is('vouchers') ? 'active menu-open' : '' }}">
                    <a href="">
                        <i class="fa fa-money"></i> <span> Coupon/Voucher</span>
                        <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="{{ Request::is('coupons') || Request::is('coupons') ? 'active' : '' }}">
                            <a href="{{ url('coupons') }}">
                                <i class="fa fa-cogs"></i> Coupons
                            </a>
                        </li>
                        <li class="{{ Request::is('vouchers') || Request::is('vouchers') ? 'active' : '' }}">
                            <a href="{{ url('vouchers') }}">
                                <i class="fa fa-cogs"></i> Vouchers
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="{{ Request::is('flash_schedule') || Request::is('flash_schedule') || Request::is('flash_item')  ? 'active' : '' }}">
                    <a href="{{ url('flash_schedule') }}">
                        <i class="fa fa-free-code-camp"></i> Flash Sale Management
                    </a>
                </li>

            @endif

            @if (Auth::user()->isAdmin())
                <li class="{{ Request::is('questions')  ? 'active' : '' }}">
                    <a href="{{ url('questions') }}">
                        <i class="fa fa-file-audio-o" aria-hidden="true"></i> Questions and Answers
                    </a>
                </li>
            @endif

            @if (Auth::user()->isAdmin())
                <li class="treeview {{ Request::is('admin/delivery*') ? 'active menu-open' : '' }}">
                    <a href="">
                        <i class="fa fa-newspaper-o"></i> <span> Delivery Time</span>
                        <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="{{ Request::is('admin/delivery/category')? 'active' : '' }}">
                            <a href="{{ route('admin.delivery.categories') }}">
                                <i class="fa fa-newspaper-o"></i> By Category
                            </a>
                        </li>
                        <li class="{{ Request::is('admin/delivery/timespan') ? 'active' : '' }}">
                            <a href="{{ route('admin.delivery.timespan') }}">
                                <i class="fa fa-newspaper-o"></i> Timespan
                            </a>
                        </li>
                    </ul>
                </li>

            @endif

            @if (Auth::user()->isAdmin())
                <li class="treeview {{ Request::is('admin/others*') ? 'active menu-open' : '' }}">
                    <a href="">
                        <i class="fa fa-asterisk"></i> <span> Others</span>
                        <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="{{ Request::is('admin/others/addfabricPost')? 'active' : '' }}">
                            <a href="{{ route('admin.edit.other.fabricPost') }}">
                                <i class="fa fa-newspaper-o"></i> Add Fabric Post
                            </a>
                        </li>
                        <li class="{{ Request::is('admin/others/fabricPost')? 'active' : '' }}">
                            <a href="{{ route('admin.other.fabricPost') }}">
                                <i class="fa fa-newspaper-o"></i> Fabric Post
                            </a>
                        </li>


                    </ul>
                </li>

            @endif

            @if (Auth::user()->isAdmin())
                <li class="divider"></li>
                <li class="treeview {{ Request::is('settings') || Request::is('homesettings') || Request::is('payment_settings') ? 'active menu-open' : '' }}">
                    <a href="">
                        <i class="fa fa-cogs"></i> <span> Settings</span>
                        <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="{{ Request::is('settings') || Request::is('settings') ? 'active' : '' }}">
                            <a href="{{ url('settings') }}">
                                <i class="fa fa-cogs"></i> <span>Global Settings</span>
                            </a>
                        </li>
                        <li class="{{ Request::is('homesettings') || Request::is('homesettings') ? 'active' : '' }}">
                            <a href="{{ url('homesettings') }}">
                                <i class="fa fa-cogs"></i> <span>Home Settings</span>
                            </a>
                        </li>
                        <li class="{{ Request::is('payment_settings') || Request::is('payment_settings') ? 'active' : '' }}">
                            <a href="{{ url('payment_settings') }}">
                                <i class="fa fa-money"></i> <span>Payment Settings</span>
                            </a>
                        </li>
                    </ul>
                </li>
            @endif
            @if (Auth::user()->isAdmin())
                <li class="">
                    <a target="_blank" href="{{ url('clear-cache') }}">
                        <i class="fa fa-newspaper-o"></i> Cache Clear
                    </a>
                </li>
            @endif

        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
