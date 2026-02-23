@php use App\Models\ProductImages; @endphp
@extends('layouts.app')

@section('title', 'Product')
@section('sub_title', 'products list')
@section('content')

    @php
        $url_one = Request::segment(1);

    @endphp
    <div class="row">
        @if(Session::has('success'))
            <div class="col-md-12">
                <div class="callout callout-success">
                    {{ Session::get('success') }}
                </div>
            </div>
        @endif
        <div class="col-md-12">
            <div class="box box-danger">
                <div class="box-header with-border">
                    <h5 class="box-title">Advanced Search</h5>
                </div>
                <div class="box-body compact-search">
                    {{ Form::open(array('url' => '/search_products', 'method' => 'get', 'value' => 'PATCH', 'id' => 'search-form')) }}
                    <div class="row">
                        <div class="col-md-4 col-sm-8 col-xs-12">
                            {{ Form::text('search_term', $getAttribute['search_term']??'', ['class' => 'form-control input-sm', 'placeholder' => 'Product Code / Product Name / Title']) }}
                        </div>
                        <div class="col-md-2 col-sm-4 col-xs-6">
                            {{ Form::text('product_code', $getAttribute['product_code']??'', ['class' => 'form-control input-sm', 'placeholder' => 'Product Code']) }}
                        </div>
                        <div class="col-md-2 col-sm-4 col-xs-6">
                            {{ Form::text('sku', $getAttribute['sku']??'', ['class' => 'form-control input-sm', 'placeholder' => 'SKU']) }}
                        </div>
                        <div class="col-md-2 col-sm-4 col-xs-6">
                            <select name="is_active" class="form-control input-sm">
                                <option value="">Product Status</option>
                                <option value="1" {{ ($getAttribute['is_active']??'') == '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ ($getAttribute['is_active']??'') == '0' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="row" style="margin-top: 6px;">
                        <div class="col-md-2 col-sm-4 col-xs-6">
                            {{ Form::text('price_min', $getAttribute['price_min']??'', ['class' => 'form-control input-sm', 'placeholder' => 'Price Min']) }}
                        </div>
                        <div class="col-md-2 col-sm-4 col-xs-6">
                            {{ Form::text('price_max', $getAttribute['price_max']??'', ['class' => 'form-control input-sm', 'placeholder' => 'Price Max']) }}
                        </div>
                        <div class="col-md-1 col-sm-3 col-xs-6">
                            <div class="input-group date">
                                <div class="input-group-addon">From</div>
                                <input value="{{ $getAttribute['formDate']??'' }}" autocomplete="off" type="text"
                                       name="formDate" id="formDate" class="form-control input-sm datepicker">
                            </div>
                        </div>
                        <div class="col-md-1 col-sm-3 col-xs-6">
                            <div class="input-group date">
                                <div class="input-group-addon">To</div>
                                <input value="{{ $getAttribute['toDate']??''  }}" autocomplete="off" type="text"
                                       name="toDate" id="toDate" class="form-control input-sm datepicker">
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-4 col-xs-6">
                            {{ Form::submit('Search', ['class' => 'btn btn-success btn-sm btn-block']) }}
                        </div>
                        <div class="col-md-2 col-sm-4 col-xs-6">
                            <a class="btn btn-default btn-sm btn-block" href="{{ url('products') }}">Reset</a>
                        </div>
                    </div>

                    {{ Form::close() }}

                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                        Products
                        <a href="{{ url('add_product') }}" class="btn btn-xs btn-success">
                            <i class="fa fa-plus"></i>
                        </a>
                    </h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding" id="reload_me">
                    <table class="table table-hover table-responsive">
                        <tbody>
                        <tr>
                            <th width="5%">#</th>
                            <th width="15%">Image</th>
                            <th width="30%">Title & Details</th>
                            <th width="8%">Variation</th>
                            <th width="15%">Price</th>
                            <th width="5%">Status</th>
                            <th width="8%">Stock Status</th>
                            <th width="15%">Date</th>
                            <th width="5%">Action</th>
                        </tr>
                        @foreach($products as $product)
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td>

                                        <?php

                                        $tksign = '৳ ';

                                        //$pro = \App\Product::where('id', $product->main_pid)->get()->first();
                                        $first_image = ProductImages::where('main_pid', $product->id)->where('is_main_image', 1)->get()->first();

                                        if (!empty($first_image->full_size_directory)) {
                                            $img = url($first_image->full_size_directory);
                                        } else {
                                            $img = url('storage/uploads/fullsize/2019-01/default.jpg');
                                        }

                                        $second_image = ProductImages::where('main_pid', $product->id)->where('is_main_image', 0)->get()->first();
                                        $regularprice = $product->local_selling_price;
                                        $save = ($product->local_selling_price * $product->local_discount) / 100;
                                        $sp = $regularprice - $save;

                                        ?>

                                    <img src="{{ $img }}" class="img-1 img-responsive"
                                         style="width: 50px; height: 50px;"
                                         alt="{{ $product->title }}">

                                </td>

                                <td>
                                    <small>Title: {{ $product->title }}</small>
                                    <br/>
                                    <small>Sub Title: {{ $product->sub_title }}</small>
                                    <br/>
                                    <small>Product Code: <strong>{{ $product->product_code }}</strong></small>
                                    <br/>
                                    <small>Product Category:
                                        <strong>{{ optional($product->category->where('is_attgroup_active')->first())->term_name }}</strong></small>
                                    <br/>
                                    <small>Sort
                                        Order:<strong>{{ optional($product->category->where('is_attgroup_active')->first())->sort_order }}</strong></small>

                                </td>
                                <td>
                                    @if($product->enable_variation === 'off')
                                        <span class="label label-danger">No</span>
                                    @else
                                        <span class="label label-success">Yes</span>
                                    @endif
                                </td>
                                <td>
                                    <b title="Regular Price"></b><span
                                            style="font-size:16px">{{ $tksign . ($regularprice??0) }}</span>
                                    <br/>
                                    <!--<small><b title="Save Price">SP: </b>{{ $tksign . $save }}</small>
                                    <br/>
                                    <small><b title="Total Save Price">CP: </b>{{ $tksign . $sp }}</small>-->
                                </td>
                                <td>
                                    @if ($product->is_active == 1)
                                        <span class="label label-success">Active</span>
                                    @else
                                        <span class="label label-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($product->stock_status == 1)
                                        <span class="label label-success">In Stock</span>
                                    @elseif($product->stock_status == 2)
                                        <span class="label label-default">Sold Out</span>
                                    @else
                                        <span class="label label-danger">Out of Stock</span>
                                    @endif
                                </td>
                                <td>
                                    {{--                                    <!--<small><strong>Exclusive: </strong>{{ $product->is_sticky }}</small>--}}
                                    {{--                                    <br/>--}}
                                    <small><strong>Active: </strong>{{ $product->is_active }}</small>
                                    <br/>
                                    <small><strong>Created: </strong>{{ $product->created_at }}</small>
                                    <br/>
                                    <small><strong>Updated: </strong>{{ $product->updated_at }}</small>
                                </td>
                                <td>
                                    @if (!empty($product->product_set_id))
                                        <a class="btn btn-xs btn-info"
                                           href="{{route('admin.product_set.edit',['id' => $product->product_set_id])}}">
                                            <i class="fa fa-pencil-square-o"></i>
                                        </a>
                                    @else
                                        <a class="btn btn-xs btn-success"
                                           href="{{ url("edit_product/{$product->id}") }}">
                                            <i class="fa fa-pencil-square-o"></i>
                                        </a>
                                    @endif


                                    {{--<a class="btn btn-xs btn-danger delete_form"--}}
                                    {{--href="{{ url('delete_product/' . $product->id) }}"--}}
                                    {{--onclick="return confirm('Are you Sure?')"--}}
                                    {{--title="Delete Now">--}}
                                    {{--<i class="fa fa-times"></i>--}}
                                    {{--</a>--}}
                                    {{--{{ Form::open(['method' => 'delete', 'route' => ['delete_product', $product->id], 'class' => 'delete_form']) }}--}}
                                    {{--{{ Form::button('<i class="fa fa-times"></i>', array('type' => 'submit', 'class' => 'btn btn-xs btn-danger')) }}--}}
                                    {{--{{ Form::close() }}--}}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="box-footer clearfix">
                        {{--{{ $products->links('component.paginator', ['object' => $products]) }}--}}
                        {{ $products->appends(request()->query())->links('component.paginator', ['object' => $products]) }}
                    </div>
                    <!-- /.pagination pagination-sm no-margin pull-right -->
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>
@endsection
