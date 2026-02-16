@extends('layouts.app')

@section('title', 'Product')
@section('sub_title', 'add products today')
@section('content')
    <div class="row">
        @if(Session::has('success'))
            <div class="col-md-12">
                <div class="callout callout-success">
                    {{ Session::get('success') }}
                </div>
            </div>
        @endif
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                        Posts
                        <a href="{{ url('add_product') }}" class="btn btn-xs btn-success">
                            <i class="fa fa-plus"></i>
                        </a>
                    </h3>
                    
                    <div class="box-tools">
                        <div class="input-group input-group-sm" style="width: 150px;">
                            <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">

                            <div class="input-group-btn">
                                <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding" id="reload_me">
                    <table class="table table-hover">
                        <tbody>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Sub Title</th>
                            <th>SF URL</th>
                            <th>Description</th>
                            <th>Is Sticky</th>
                            <th>Is Active</th>
                            <th>Date Created</th>
                            <th>Date Updated</th>
                            <th>Action</th>
                        </tr>
                        @foreach($products as $product)
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td>{{ $product->title }}</td>
                                <td>{{ $product->sub_title }}</td>
                                <td>{{ $product->seo_url }}</td>
                                <td>{!!  limit_text($product->description, 20)  !!}</td>
                                <td>{{ $product->is_sticky }}</td>
                                <td>{{ $product->is_active }}</td>
                                <td>{{ $product->created_at }}</td>
                                <td>{{ $product->updated_at }}</td>
                                <td>
                                    <a class="btn btn-xs btn-success"
                                       href="{{ url("edit_product/{$product->id}") }}">
                                        <i class="fa fa-pencil-square-o"></i>
                                    </a>
                                    {{ Form::open(['method' => 'delete', 'route' => ['delete_product', $product->id], 'class' => 'delete_form']) }}
                                    {{ Form::button('<i class="fa fa-times"></i>', array('type' => 'submit', 'class' => 'btn btn-xs btn-danger')) }}
                                    {{ Form::close() }}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="box-footer clearfix">
                        {{ $products->links('component.paginator', ['object' => $products]) }}
                    </div>
                    <!-- /.pagination pagination-sm no-margin pull-right -->
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>
@endsection
