@extends('layouts.app')

@section('title', 'Tag Gallery')

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
        
        <div class="col-md-3" id="signupForm">
            @component('component.form')
                @slot('form_id')
                    @if (!empty($tag_gallery->id))
                        slider_form333
                    @else
                        slider_form333
                    @endif
                @endslot
                @slot('title')
                    {{isset($tag_gallery->id) ? 'Edit Data' : 'Add new data'}}
                @endslot

                @slot('route')
                    @if (!empty($tag_gallery->id))
                        {{route('admin.common.catgallary.update',$tag_gallery->id)}}
                    @else
                        {{route('admin.common.catgallary.store')}}
                    @endif
                @endslot

                @slot('fields')
                    {{method_field(isset($district) ? 'PUT' : 'POST')}}
                    {{csrf_field()}}

                    <div class="form-group">
                        {{ Form::label('image_id', 'Image', array('class' => 'image')) }}
                        {{ Form::text('image_id', $tag_gallery->image_id??'', ['required', 'class' => 'form-control', 'placeholder' => 'Enter Image ID...']) }}
                    </div>

                    <div class="form-group">
                        {{ Form::label('category', 'Category', array('class' => 'category')) }}
                        {{ Form::select('category_id', $terms,$tag_gallery->category_id??0, ['required', 'class' => 'form-control']) }}
                    </div>


                    <div class="form-group">
                        {{ Form::label('url', 'URL', array('class' => 'url')) }}
                        {{ Form::text('url', $tag_gallery->url??'', ['required', 'class' => 'form-control', 'placeholder' => 'Enter URL...']) }}
                    </div>
                    
                    <div class="form-group">
                        {{ Form::label('internal', 'URL Type', array('class' => 'internal')) }}
                        {{ Form::select('url_type', [0 => 'External', 1 => 'Internal'],$tag_gallery->url_type??0, ['required', 'class' => 'form-control']) }}
                    </div>

                    <div class="form-group">
                        {{ Form::label('active', 'Active', array('class' => 'active')) }}
                        {{ Form::select('active', [0 => 'No', 1 => 'Yes'],$tag_gallery->active??0, ['required', 'class' => 'form-control']) }}
                    </div>


                @endslot
            @endcomponent
        </div>
        <div class="col-md-9">

            <div class="box box-success">
                <div class="box-header">
                    
                    
                    <h3 class="box-title">
                        Tag Gallery
                        <a href="{{ route('admin.common.slider') }}" class="btn btn-xs btn-success">
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
                                <th>Category</th>
                                <th>URL</th>
                                <th>URL Type</th>
                                <th>Active</th>
                                <th>Action</th>
                            </tr>

                            @foreach($tag_galleries as $tag_gallery)
                                <tr>
                                    <td>
                                        <img src="{{asset($tag_gallery->image->icon_size_directory??'')}}" height="60px" width="60px"/>
                                    </td>
                                    <td> {{$tag_gallery->term->name??''}} g</td>
                                    <td>{{ $tag_gallery->url }}</td>
                                    <td>
                                      @if($tag_gallery->internal)
                                       Internal
                                      @else
                                       External
                                      @endif
                                    </td>
                                    <td>
                                      @if($tag_gallery->active)
                                       Yes
                                      @else
                                       No
                                      @endif
                                    </td>
                                    <td>

                                        <a class="btn btn-xs btn-info"
                                           href="{{route('admin.common.catgallary')}}?id={{$tag_gallery->id}}">
                                            <i class="fa fa-pencil-square-o"></i>
                                        </a>

                                        <a class="btn btn-xs btn-danger"
                                           href="{{route('admin.common.catgallary.delete',$tag_gallery->id)}}">
                                            <i class="fa fa-pencil-square-o"></i>
                                        </a>

                                    </td>


                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="box-footer clearfix">
                            {{ $query ? '' : $tag_galleries->links('component.paginator', ['object' => $tag_galleries]) }}
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
