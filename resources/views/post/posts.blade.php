@extends('layouts.app')

@php
if(\Request::segment(1) === 'our_showroom'){
    $os = true;
}else{
    $os = false;
}

@endphp

@section('title', (($os)? 'Our Showroom': 'Posts'))
@section('sub_title', (($os)? 'Our Showroom management panel': 'Posts management panel'))
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
                    {{($os)? 'Our Showroom': 'Posts' }}
                        @if ($os)
                        <a href="{{ url('add_showroom') }}" class="btn btn-xs btn-success">
                                <i class="fa fa-plus"></i>
                        </a>
                        @else

                        <a href="{{ url('add_post') }}" class="btn btn-xs btn-success">
                            <i class="fa fa-plus"></i>
                        </a>
                        @endif
                    </h3>

                    <div class="box-tools">
                        <form action="{{url('posts')}}" method="get">
                        <div class="input-group input-group-sm" style="width: 150px;">
                                <input type="text" name="search_key" class="form-control pull-right" placeholder="Search" value="{{request()->search_key}}">
                                <div class="input-group-btn">
                                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                                </div>

                        </div>
                        </form>
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
                        @foreach($posts as $post)
                            <tr>
                                <td>{{ $post->id }}</td>
                                <td>{{ $post->title }}</td>
                                <td>{{ $post->sub_title }}</td>
                                <td>{{ $post->seo_url }}</td>
                                <td>{!!  limit_text($post->description, 20)  !!}</td>
                                <td>{{ $post->is_sticky }}</td>
                                <td>{{ $post->is_active }}</td>
                                <td>{{ $post->created_at }}</td>
                                <td>{{ $post->updated_at }}</td>
                                <td>
                                    @if ($os)
                                    <a class="btn btn-xs btn-success"
                                        href="{{ url("edit_showroom/{$post->id}") }}">
                                        <i class="fa fa-pencil-square-o"></i>
                                    </a>
                                    @else

                                    <a class="btn btn-xs btn-success"
                                       href="{{ url("edit_post/{$post->id}") }}">
                                        <i class="fa fa-pencil-square-o"></i>
                                    </a>
                                    @endif
                                    {{ Form::open(['method' => 'delete', 'route' => ['delete_post', $post->id], 'class' => 'delete_form']) }}
                                    {{ Form::button('<i class="fa fa-times"></i>', array('type' => 'submit', 'class' => 'btn btn-xs btn-danger')) }}
                                    {{ Form::close() }}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="box-footer clearfix">
                        {{ $posts->links('component.paginator', ['object' => $posts]) }}
                    </div>
                    <!-- /.pagination pagination-sm no-margin pull-right -->
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>
@endsection
