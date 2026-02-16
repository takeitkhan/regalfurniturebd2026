@if (Auth::check())
    <aside class="col-sm-3 hidden-xs content-aside col-md-3" id="column-right">
        <h2 class="subtitle">Account</h2>
        <div class="list-group">
            <ul class="list-item">

                @if (Auth::user()->isAdmin() || Auth::user()->isVendor())
                    <li><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ url('/change_password') }}">Change Password</a></li>
                    <li><a href="{{ url('/my_account') }}">My Account</a></li>
                    <li><a href="{{ url('/profile_update') }}">Profile Update</a></li>
                    <li><a href="{{ url('/wishlist') }}">Wish List</a></li>
                    <li><a href="{{ url('/order_history') }}">Order History</a></li>
                    <li><a href="{{ url('/reward_points') }}">Reward Points</a></li>
                    <li><a href="{{ url('/return_request') }}">Return Request</a></li>
                    <li>
                        <a href="{{ url('/logout') }}">
                            <i class="fa fa-sign-out"></i> Logout
                        </a>
                    </li>
                @else
                    <li><a href="{{ url('/change_password') }}">Change Password</a></li>
                    <li><a href="{{ url('/my_account') }}">My Account</a></li>
                    <li><a href="{{ url('/profile_update') }}">Profile Update</a></li>
                    {{--<li><a href="{{ url('/wishlist') }}">Wish List</a></li>--}}
                    <li><a href="{{ url('/order_history') }}">Order History</a></li>
                    {{--<li><a href="{{ url('/reward_points') }}">Reward Points</a></li>--}}
                    {{--<li><a href="{{ url('/return_request') }}">Return Request</a></li>--}}
                    <li>
                        <a href="{{ url('/logout') }}">
                            <i class="fa fa-sign-out"></i> Logout
                        </a>
                    </li>
                @endif

            </ul>
        </div>
    </aside>
@endif