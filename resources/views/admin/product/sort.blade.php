@extends('layouts.app')

@section('title', 'Sort Product')
@section('sub_title', 'Category list')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                        Categories
                    </h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding" id="reload_me">
                    <table class="table table-hover table-responsive">
                        <tbody>
                        <tr>
                            <th width="5%">#</th>
                            <th width="20%">Category</th>
                            <th width="10%">Product Count</th>
                            <th width="15%">Date</th>
                            <th width="5%">Action</th>
                        </tr>
                        @foreach($categories as $category)
                            <tr>
                                <td>{{ $category->id }}</td>
                                <td>{{ $category->name }}</td>
                                <td><span class="badge bg-green">{{ $category->products_count }}</span>
                                </td>
                                <td>
                                    <small><strong>Created: </strong>{{ $category->created_at }}</small>
                                    <br/>
                                    <small><strong>Updated: </strong>{{ $category->updated_at }}</small>
                                </td>

                                <td>
                                    <a href="{{ route('products.category_wise_sort', $category->id) }}"
                                       class="btn btn-xs btn-primary">
                                        <i class="fa fa-sort"></i> Sort
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="box-footer clearfix">
                        {{--{{ $products->links('component.paginator', ['object' => $products]) }}--}}
                        {{ $categories->links('component.paginator', ['object' => $categories]) }}
                    </div>
                    <!-- /.pagination pagination-sm no-margin pull-right -->
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>
@endsection
