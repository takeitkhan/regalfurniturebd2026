@extends('layouts.app')

@section('title', 'Attributes Management')
@section('sub_title', 'attributes management panel')
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
                        Attributes
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
                            <th>Attribute Name</th>
                            <th>Is Active</th>
                            <th>Date Created</th>
                            <th>Date Updated</th>
                            <th>Action</th>
                        </tr>
                        @foreach($attributes as $attribute)
                            <tr>
                                <td>{{ $attribute->id }}</td>
                                <td>
                                    {{ $attribute->title }}
                                </td>
                                <td>{{ $attribute->is_active }}</td>
                                <td>{{ $attribute->created_at }}</td>
                                <td>{{ $attribute->updated_at }}</td>
                                <td>
                                    <a class="btn btn-xs btn-success"
                                       href="{{ url("edit_page/{$attribute->id}") }}">
                                        <i class="fa fa-pencil-square-o"></i>
                                    </a>
                                    <a class="btn btn-xs btn-danger"
                                       onclick="alert('Are you sure?')"
                                       href="{{ url("delete_attribute/{$attribute->id}") }}">
                                        <i class="fa fa-times"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="box-footer clearfix">
                        {{ $attributes->links('component.paginator', ['object' => $attributes]) }}
                    </div>
                    <!-- /.pagination pagination-sm no-margin pull-right -->
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>
@endsection

@section('cusjs')
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $.noConflict();


        });
    </script>
@endsection
