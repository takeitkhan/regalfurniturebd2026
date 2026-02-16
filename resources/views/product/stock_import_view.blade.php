@extends('layouts.app')

@section('title', 'Import Stock')
@section('sub_title', 'excel import stock information')
@section('content')
    <div class="row">
        @if(Session::has('success'))
            <div class="col-md-12">
                <div class="callout callout-success">
                    {{ Session::get('success') }}
                </div>
            </div>
        @endif

        {{--@endif--}}
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

        <div class="col-md-4">
            @component('component.form')
                @slot('form_id')
                    import_stock_form
                @endslot
                @slot('title')
                    Import stocks
                @endslot

                @slot('route')
                    import_stock_save
                @endslot

                @slot('fields')
                    <div class="form-group">
                        {{ Form::hidden('user_id', (!empty(\Auth::user()->id) ? \Auth::user()->id : NULL), ['type' => 'hidden']) }}
                        {{ Form::hidden('lang', (!empty($post->lang) ? $post->lang : 'en'), ['type' => 'hidden']) }}
                    </div>
                    <div class="form-group">
                        <a href="stock_import.xlsx" class="btn btn-app">
                            <i class="fa fa-file-excel-o"></i> Excel Demo File
                        </a>
                    </div>
                    <div class="form-group">
                        {!! Form::label('import_file', "Upload your modified file:") !!}
                        <input class="btn btn-lg" type="file" name="import_file"/>
                    </div>
                @endslot
            @endcomponent
        </div>
        <div class="col-md-8">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                        Products Stock Checking
                        <a href="{{ url('import_stock_view') }}" class="btn btn-xs btn-success">
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
                            <th>Depot</th>
                            <th>Product ID</th>
                            <th>Product Code</th>
                            <th>Quantity</th>
                            <th colspan="2">Action</th>
                        </tr>
                        @php
                            $stocks = \App\Models\ProductStock::get();
                        @endphp
                        @foreach($stocks as $stock)
                            <tr>
                                <td>{{ $stock->id }}</td>
                                <td>{{ \App\Models\Depot::where('id', $stock->depot_id)->first()->name ?? NULL }}</td>
                                <td>
                                    <a href="{{ url('edit_product', $stock->product_id) }}">
                                        {{ \App\Models\Product::where('id', $stock->product_id)->first()->title ?? NULL }}
                                    </a>
                                </td>
                                <td>{{ $stock->product_code }}</td>
                                <td>{{ $stock->available_qty }}</td>
                                <td>
                                    {{--                                    <a class="btn btn-xs btn-success"--}}
                                    {{--                                       href="{{ url("edit_user/{$user->id}") }}">--}}
                                    {{--                                        <i class="fa fa-pencil-square-o"></i>--}}
                                    {{--                                    </a>--}}
                                    {{--                                    {{ Form::open(['method' => 'delete', 'route' => ['delete_user', $user->id], 'class' => 'delete_form']) }}--}}
                                    {{--                                    {{ Form::button('<i class="fa fa-times"></i>', array('type' => 'submit', 'class' => 'btn btn-xs btn-danger')) }}--}}
                                    {{--                                    {{ Form::close() }}--}}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="box-footer clearfix">
                        {{--                        {{ $stocks->links('component.paginator', ['object' => $stocks]) }}--}}
                    </div>
                    <!-- /.pagination pagination-sm no-margin pull-right -->
                </div>
                <!-- /.box-body -->
            </div>
        </div>

    </div>
@endsection
@push('scripts')
    <script type="text/javascript">

    </script>
    <style type="text/css">
        .aladagrey {
            background: lightgrey;
        }

        .recordset, .czContainer {
            position: relative;
        }

        .recordset {
            border-bottom: 4px solid white;
        }
    </style>
@endpush
