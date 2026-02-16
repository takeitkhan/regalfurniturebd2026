@extends('layouts.app')

@section('title', 'Variation Group')
@section('sub_title', 'Variation Group panel')
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
                        Group Variation
                        <a href="{{ route('add.variation.group') }}" class="btn btn-xs btn-success">
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
                            <th>Slug</th>
                            <th>Active</th>                          
                            <th>Action</th>
                        </tr>
                        @if (!empty($variationGroup))
                            
                        
                        @foreach($variationGroup as $variation)
                            <tr>
                                <td>{{ $variation->id }}</td>
                                <td>
                                    {{ $variation->title }}
                                </td>
                                <td>{{ $variation->slug }}</td>
                                @if ($variation->active == 1)
                                <td>Yes</td>
                                @else
                                <td>No</td>
                                @endif
                                
                              
                                <td>
                                    <a class="btn btn-xs btn-success"
                                       href="{{ url("admin/edit-variation-group/{$variation->id}") }}">
                                        <i class="fa fa-pencil-square-o"></i>
                                    </a>
                                    <a class="btn btn-xs btn-danger"
                                       onclick="alert('Are you sure?')"
                                       href="{{ url("admin/delete-variation-group/{$variation->id}") }}">
                                        <i class="fa fa-times"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        @endif
                        </tbody>
                    </table>
                    <div class="box-footer clearfix">
                        {{ $variationGroup->links('component.paginator', ['object' => $variationGroup]) }}
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
