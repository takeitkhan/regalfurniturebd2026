@extends('layouts.app')

@section('title', 'Product')
@section('sub_title', 'products list')
@section('content')

    @php
        $url_one = \Request::segment(1);

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
                <div class="box-body">


                    {{ Form::open(array('url' => $url_one, 'method' => 'get', 'value' => 'PATCH', 'id' => '')) }}
                    <div class="row">
                        <div class="col-xs-2">
                            <select name="column" required class="form-control select2" style="width: 100%;">
                                <option value="title" {{ (Request::post('column') == 'title') ? 'selected="selected"' : 'selected="selected"' }}>
                                    Title
                                </option>
                                <option value="sub_title" {{ (Request::post('column') == 'sub_title') ? 'selected="selected"' : '' }}>
                                    Sub Title
                                </option>
                                <option value="seo_url" {{ (Request::post('column') == 'seo_url') ? 'selected="selected"' : '' }}>
                                    SEO URL
                                </option>
                                <option value="sku" {{ (Request::post('column') == 'sku') ? 'selected="selected"' : '' }}>
                                    SKU
                                </option>
                                <option value="categories" {{ (Request::post('column') == 'categories') ? 'selected="selected"' : '' }}>
                                    Category ID
                                </option>
                                <option value="material" {{ (Request::post('column') == 'material') ? 'selected="selected"' : '' }}>
                                    Material
                                </option>
                                <option value="color" {{ (Request::post('column') == 'color') ? 'selected="selected"' : '' }}>
                                    Color
                                </option>
                                <option value="description" {{ (Request::post('column') == 'description') ? 'selected="selected"' : '' }}>
                                    Description
                                </option>
                                <option value="short_description" {{ (Request::post('column') == 'short_description') ? 'selected="selected"' : '' }}>
                                    Short Description
                                </option>
                                <option value="dimension" {{ (Request::post('column') == 'dimension') ? 'selected="selected"' : '' }}>
                                    Dimension
                                </option>
                            </select>
                        </div>
                        <div class="col-xs-4">
                            {{ Form::text('search_key', Request::post('search_key'), ['required', 'class' => 'form-control', 'placeholder' => 'Search Keys...']) }}
                        </div>
                        <div class="col-xs-1">
                            {{ Form::submit('Search', ['class' => 'btn btn-success']) }}
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
                    <table class="table table-hover">
                        <tbody>
                        <tr>
                            <th width="5%">#</th>
                            <th width="15%">Image</th>
                            <th width="30%">Title & Details</th>
                            <th width="15%">Price</th>
                            <th width="15%">Date & Status</th>
                            <th width="5%">Action</th>
                        </tr>
                        @foreach($products as $product)
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td>

                                    <?php

                                    $tksign = '৳ ';

                                    //$pro = \App\Product::where('id', $product->main_pid)->get()->first();
                                    $first_image = \App\Models\ProductImages::where('main_pid', $product->id)->where('is_main_image', 1)->get()->first();

                                    if (!empty($first_image->full_size_directory)) {
                                        $img = url($first_image->full_size_directory);
                                    } else {
                                        $img = url('storage/uploads/fullsize/2019-01/default.jpg');
                                    }

                                    $second_image = \App\Models\ProductImages::where('main_pid', $product->id)->where('is_main_image', 0)->get()->first();
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
                                    <!--{!!  limit_text($product->description, 20)  !!}-->
                                </td>
                                <td>
                                    <b title="Regular Price"></b><span style="font-size:16px">{{ $tksign . ($regularprice??0) }}</span>
                                    <br/>
                                    <!--<small><b title="Save Price">SP: </b>{{ $tksign . $save }}</small>
                                    <br/>
                                    <small><b title="Total Save Price">CP: </b>{{ $tksign . $sp }}</small>-->
                                </td>
                                <td>
                                    <!--<small><strong>Exclusive: </strong>{{ $product->is_sticky }}</small>
                                    <br/>
                                    <small><strong>Active: </strong>{{ $product->is_active }}</small>
                                    <br/>-->
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
