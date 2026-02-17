@extends('layouts.app')


@section('title', 'Dashboard')
@section('sub_title', 'a quick overview or charts of whole software')

@section('content')

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif   

    <!-- Optional: Show current filter range -->
    @if(request('start_date') && request('end_date'))
        <p class="text-muted">
            Showing results from <strong>{{ request('start_date') }}</strong> to
            <strong>{{ request('end_date') }}</strong>.
        </p>
    @endif

    <div class="row">
        @php
            $boxes = [
                ['count' => $total_product, 'label' => 'Products', 'desc' => 'Uploaded Products', 'icon' => 'fa-product-hunt', 'color' => 'bg-aqua'],
                ['count' => $total_orders, 'label' => 'Orders', 'desc' => 'Orders Till Today', 'icon' => 'fa-shopping-cart', 'color' => 'bg-yellow'],
                ['count' => $placed_orders, 'label' => 'Orders Placed', 'desc' => 'Orders Placed', 'icon' => 'fa-shopping-cart', 'color' => 'bg-green'],
                ['count' => $processing_orders, 'label' => 'Orders Shipped', 'desc' => 'Orders Shipped', 'icon' => 'fa-spinner', 'color' => 'bg-primary'],
                ['count' => $confirmed_orders, 'label' => 'Need to Shipped', 'desc' => 'Need to Shipped', 'icon' => 'fa-check-circle', 'color' => 'bg-purple'],
                ['count' => $production_orders, 'label' => 'Requested Order', 'desc' => 'Requested Order', 'icon' => 'fa-industry', 'color' => 'bg-orange'],
                ['count' => $complete_orders, 'label' => 'Complete Orders', 'desc' => 'Complete Orders', 'icon' => 'fa-flag-checkered', 'color' => 'bg-navy'],
                ['count' => $customer_unreachable_orders, 'label' => 'Customer Unreachable', 'desc' => 'Customer Unreachable Orders', 'icon' => 'fa-phone-slash', 'color' => 'bg-maroon'],
                ['count' => $cancelled_orders, 'label' => 'Cancelled Orders', 'desc' => 'Cancelled Orders', 'icon' => 'fa-times-circle', 'color' => 'bg-red'],
                ['count' => $refund_orders, 'label' => 'Refunded Orders', 'desc' => 'Refunded Orders', 'icon' => 'fa-undo', 'color' => 'bg-teal'],
            ];
        @endphp

        @foreach ($boxes as $box)
            <div class="col-lg-3 col-xs-6">
                <div class="small-box {{ $box['color'] }}">
                    <div class="inner d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0">{{ $box['count'] }}</h3>
                            <p class="mb-0">{{ $box['label'] }}</p>
                        </div>
                        <div class="dropdown">
                            <a href="#" class="text-white" data-toggle="dropdown" aria-haspopup="true"
                               aria-expanded="false">
                                <i class="fa fa-ellipsis-v"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right p-2">
                                <a class="dropdown-item" href="?filter=7days">Last 7 Days</a>
                                <a class="dropdown-item" href="?filter=30days">Last 30 Days</a>
                                <a class="dropdown-item" href="?filter=today">Today</a>
                                <a class="dropdown-item" href="?filter=custom">Custom Range</a>
                            </div>
                        </div>
                    </div>
                    <div class="icon">
                        <i class="fa {{ $box['icon'] }}"></i>
                    </div>
                    <a href="javascript:void(0)" class="small-box-footer">
                        {{ $box['desc'] }}
                        <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        @endforeach
    </div>
    @if (Auth::user()->isAdmin())
    <div class="row">
        <div class="col-md-4">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Products Management</h3>
                </div>
                <div class="box-body">
                    <a class="btn btn-app" href="{{ url('add_product') }}">
                        <i class="fa fa-plus-circle"></i> Add Product
                    </a>
                    <a class="btn btn-app" href="{{ url('products') }}">
                        {{--<span class="badge bg-green">300</span>--}}
                        <i class="fa fa-barcode"></i> Products
                    </a>
                    <a class="btn btn-app" href="{{ url('orders') }}">
                        {{--<span class="badge bg-teal">67</span>--}}
                        <i class="fa fa-inbox"></i> Orders
                    </a>
                </div>
            </div>
            <!-- /.box -->
        </div>
        <div class="col-md-4">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Users Management</h3>
                </div>
                <div class="box-body">
                    <a class="btn btn-app" href="{{ url('add_user') }}">
                        <i class="fa fa-plus-circle"></i> Add User
                    </a>
                    <a class="btn btn-app" href="{{ url('users') }}">
                        {{--<span class="badge bg-purple">891</span>--}}
                        <i class="fa fa-users"></i> Users
                    </a>
                </div>
            </div>
            <!-- /.box -->
        </div>
        <div class="col-md-4">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Global Configuration</h3>
                </div>
                <div class="box-body">
                    <a class="btn btn-app" href="{{ url('settings') }}">
                        <i class="fa fa-industry"></i> Site
                    </a>
                    <a class="btn btn-app" href="{{ url('settings') }}">
                        <i class="fa fa-industry"></i> Administrator
                    </a>
                    <a class="btn btn-app" href="{{ url('settings') }}">
                        <i class="fa fa-code"></i> SEO Setting
                    </a>
                    <a class="btn btn-app" href="{{ url('settings') }}">
                        <i class="fa fa-facebook"></i> FB Setting
                    </a>
                    <a class="btn btn-app" href="{{ url('settings') }}">
                        <i class="fa fa-google"></i> Google Setting
                    </a>
                </div>
            </div>
            <!-- /.box -->
        </div>
    </div>
    @endif
    @if (Auth::user()->isAdmin())
    <div class="row">
        <div class="col-md-4">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Posts Management</h3>
                </div>
                <div class="box-body">
                    <a class="btn btn-app" href="{{ url('add_post') }}">
                        <i class="fa fa-plus-circle"></i> Add Post
                    </a>
                    <a class="btn btn-app" href="{{ url('posts') }}">
                        {{--<span class="badge bg-green">300</span>--}}
                        <i class="fa fa-list-alt"></i> Posts
                    </a>
                    <a class="btn btn-app" href="{{ url('terms') }}">
                        {{--<span class="badge bg-green">30</span>--}}
                        <i class="fa fa-list"></i> Categories
                    </a>
                    <a class="btn btn-app" href="{{ url('widgets') }}">
                        {{--<span class="badge bg-red">531</span>--}}
                        <i class="fa fa-gears"></i> Widgets
                    </a>
                </div>
            </div>
            <!-- /.box -->
        </div>
        <div class="col-md-4">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Pages Management</h3>
                </div>
                <div class="box-body">
                    <a class="btn btn-app" href="{{ url('add_page') }}">
                        <i class="fa fa-plus-circle"></i> Add Page
                    </a>
                    <a class="btn btn-app" href="{{ url('pages') }}">
                        {{--<span class="badge bg-green">300</span>--}}
                        <i class="fa fa-list-alt"></i> Pages
                    </a>
                </div>
            </div>
            <!-- /.box -->
        </div>
        <div class="col-md-4">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Medias or Files Management</h3>
                </div>
                <div class="box-body">
                    <a class="btn btn-app" href="{{ url('medias/all') }}">
                        <i class="fa fa-plus-circle"></i> Add File
                    </a>
                    <a class="btn btn-app" href="{{ url('medias/all') }}">
                        <i class="fa fa-list-alt"></i> Medias
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Activity Logs</h3>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Time</th>
                                <th>User</th>
                                <th>Action</th>
                                <th>Entity</th>
                                <th>Old</th>
                                <th style="width: 240px;">New</th>
                                <th>Note</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse(($activity_logs ?? []) as $log)
                                <tr>
                                    <td>{{ $log->created_at }}</td>
                                    <td>{{ $log->user->name ?? 'System' }}</td>
                                    <td>{{ $log->action }}</td>
                                    <td>{{ $log->entity_type }}{{ $log->entity_id ? ' #' . $log->entity_id : '' }}</td>
                                    <td>{{ \Illuminate\Support\Str::limit(json_encode($log->old_values), 120) }}</td>
                                    <td style="width: 240px; max-width: 240px; word-break: break-word;">
                                        @php $newValues = (array) ($log->new_values ?? []); @endphp
                                        @if(!empty($newValues))
                                            @foreach($newValues as $k => $v)
                                                <div><strong>{{ $k }}:</strong> {{ is_array($v) ? implode(', ', $v) : $v }}</div>
                                            @endforeach
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ \Illuminate\Support\Str::limit($log->note, 120) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7">No activity logs found.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('cusjs')
@endsection