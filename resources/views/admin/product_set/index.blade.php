@extends('layouts.app')

@section('title', 'Product Set')

@section('sub_title', 'manage your slider')

@section('content')
    <div class="row">
        @if(Session::has('success'))
            <div class="col-md-12">
                <div class="callout callout-success">
                    {{ Session::get('success') }}
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="col-md-12">
                <div class="callout callout-danger">
                    <h4>Warning!</h4>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
        <div class="col-md-12">

            <div class="box box-success">
                <div class="box-header">


                    <h3 class="box-title">
                        Product Set
                        <a href="{{ route('admin.product_set.create') }}" class="btn btn-xs btn-success">
                            <i class="fa fa-plus"></i>
                        </a>
                    </h3>

                    <div class="box-tools row">
                    </div>
                </div>
                <div class="box-body" style="">
                    <div class="box-body table-responsive no-padding" id="reload_me">
                        <table class="table table-hover">
                            <tbody>
                                <tr>
                                    <th>#</th>
                                    <th>Tile</th>
                                    <th>Product</th>
                                    <th>Category</th>
                                    <th>Description</th>
                                    <th>Active</th>
                                    <th>Actions</th>
                                </tr>

                                @foreach($product_sets as $product)
                                    <tr>
                                        <td>
                                            <img src="{{ asset($product->image->icon_size_directory??'') }}" height="80px" width="100px" alt="{{ __('') }}">
                                        </td>
                                        <td>
                                            {{$product->title}}
                                        </td>
                                        <td>
                                            <?php
                                            $product_ids = \App\Models\ProductSetProduct::where('product_set_id',$product->id)->pluck('product_id');
                                            $products = \App\Models\Product::whereIn('id',$product_ids)->get();

                                            ?>

                                            @foreach($products as $producti)
                                              <div style="border-bottom:1px solid #ddd;padding:4px;">
                                                <img style="margin: 3px;" width="50px" src="{{asset($producti->image->icon_size_directory??'')}}"/>{{$producti->title}} <br/>
                                              </div>
                                            @endforeach

                                        </td>
                                        <td>
                                        {{$product->category->name}}
                                        </td>
                                        <td style="width:500px">
                                            {{$product->description}}
                                        </td>
                                        <td>
                                        @if($product->productLinked->is_active??0)
                                        Yes
                                        @else
                                        No
                                        @endif
                                        </td>
                                        <td>

                                            <a class="btn btn-xs btn-info"
                                            href="{{route('admin.product_set.edit',['id' => $product->id])}}">
                                                <i class="fa fa-pencil-square-o"></i>
                                            </a>

                                            {{-- <a class="btn btn-xs btn-danger"
                                            href="{{route('admin.common.product_set.delete',$product->id)}}">
                                            <i class="fa fa-trash-o fa-lg"></i>
                                            </a> --}}

                                        </td>


                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="box-footer clearfix">
                            {{  $product_sets->links('component.paginator', ['object' => $product_sets]) }}
                        </div>
                        <!-- /.pagination pagination-sm no-margin pull-right -->
                    </div>

                </div>
                <div class="box-footer clearfix">
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')

@endpush
