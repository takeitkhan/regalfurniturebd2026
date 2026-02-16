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
        

                <li class="treeview {{ Request::is('orders') || Request::is('search_orders') || Request::is('orders/prebooking') || Request::is('orders/placed_cod') || Request::is('orders/placed') || Request::is('orders/production') || Request::is('orders/distribution') || Request::is('orders/processing')
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

                        <li class="{{ Request::is('orders') || Request::is('search_orders') ? 'active' : '' }}">
                            <a href="{{ url('orders') }}">
                                <i class="fa fa-circle-o"></i> Orders
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


        </ul>

    </section>
</aside>