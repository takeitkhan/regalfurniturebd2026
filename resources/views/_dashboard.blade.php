@extends('layouts.app')


@section('title', 'Dashboard')
@section('sub_title', 'a quick overview or charts of whole software')

@section('content')

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-aqua">
                <div class="inner">
                    @php
                        $total_product = App\Models\Product::count('id');
                        //dd($products);
                    @endphp
                    <h3>{{ $total_product }}</h3>
                    <p>Products</p>
                </div>
                <div class="icon">
                    <i class="fa fa-product-hunt"></i>
                </div>
                <a href="javascript:void(0)" class="small-box-footer">
                    Uploaded Products
                    <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-yellow">
                <div class="inner">
                    @php
                        $total_orders = App\Models\OrdersMaster::count('id');
                        // dd($products);
                    @endphp
                    <h3>{{ $total_orders }}</h3>

                    <p>Orders</p>
                </div>
                <div class="icon">
                    <i class="fa fa-shopping-cart"></i>
                </div>
                <a href="javascript:void(0)" class="small-box-footer">
                    Orders Till Today
                    <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>
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
@endsection

@section('cusjs')
@endsection