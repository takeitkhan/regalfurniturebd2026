@extends('layouts.app')

@section('title', 'View Gallery')

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
                        View Gallery
                        <a href="{{ route('admin.interior.create') }}" class="btn btn-xs btn-success">
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
                                    <th>Image</th>
                                    <th>Parent</th>
                                    <th>Category</th>
                                    <th>Title</th>
                                    <th>Sub Title</th>
                                    <th>Active</th>
                                    <th>Actions</th>
                                </tr>

                                @foreach($interior_list as $interior)
                                    <tr>
                                        <td>
                                           <img src="{{asset($interior->image->full_size_directory??'')}}" height="80px" Width="100px" alt="{{ __('') }}">
                                        </td>
                                        <td>{{$interior->category->parent_cat->name??""}}</td>
                                        <td>{{$interior->category->name??""}}</td>
                                        <td>
                                            {{$interior->title}}
                                        </td>
                                        <td>
                                            {{ $interior->sub_title }}
                                        </td>
                                        <td>
                                        @if ($interior->active == 1)
                                            Yes
                                        @else
                                            No
                                        @endif
                                        </td>
                                        <td>

                                            <a class="btn btn-xs btn-info"
                                            href="{{route('admin.interior.edit',['id' => $interior->id])}}">
                                                <i class="fa fa-pencil-square-o"></i>
                                            </a>

                                            <a class="btn btn-xs btn-danger"
                                            href="{{route('admin.interior.delete',$interior->id)}}">
                                            <i class="fa fa-trash-o fa-lg"></i>
                                            </a>

                                        </td>


                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{-- <div class="box-footer clearfix">
                            {{ $query ? '' : $interiors->links('component.paginator', ['object' => $interiors]) }}
                        </div> --}}
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
