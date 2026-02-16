<header class="main-header">
    <!-- Logo -->
    <a class="logo" href="{{ url('/') }}">
        {{ config('app.name', 'Laravel') }}
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li>
                    @php
                        $notificationsCount = \App\Models\Notification::where('is_read', 0)->count();
                    @endphp
                    <a href="{{ route('notifications_index') }}"><i class="fa fa-comments-o"></i> Notifications <span class="label label-danger">{{$notificationsCount}}</span></a>
                </li>
                <li>
                    <a href="{{ url('/') }}" target="_blank"><i class="fa fa-link"></i> Website </a>
                </li>
                <li class="dropdown user user-menu">
                    <a href="" class="dropdown-toggle" data-toggle="dropdown">
                        {{--<img src="{{ URL::asset('storage/app/public/img/user2-160x160.jpg') }}" class="user-image" alt="User Image">--}}
                        <span class="hidden-xs">
                            <?php $user = Auth::user(); ?>
                            {{ $user->name }}
                        </span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            {{--<img src="{{ URL::asset('storage/app/public/img/user2-160x160.jpg') }}" class="img-circle" alt="User Image">--}}

                            <p>
                                <small>Member since Nov. 2011</small>
                            </p>
                        </li>
                        <!-- <li class="user-body"> -->
                        <!-- <div class="row">
                            <div class="col-xs-4 text-center">
                                <a href="">Followers</a>
                            </div>
                            <div class="col-xs-4 text-center">
                                <a href="">Sales</a>
                            </div>
                            <div class="col-xs-4 text-center">
                                <a href="">Friends</a>
                            </div>
                        </div> -->
                        <!-- /.row -->
                        <!-- </li> -->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="" class="btn btn-default btn-flat">Profile</a>
                            </div>
                            <div class="pull-right">
                                <a href="{{ url('/signout') }}" class="btn btn-default btn-flat">Sign out</a>
                            </div>
                        </li>
                    </ul>
                </li>
                <!-- Control Sidebar Toggle Button -->
                <li>
                    <a href="" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                </li>
            </ul>
        </div>
    </nav>
</header>
<!-- Left side column. contains the logo and sidebar -->
