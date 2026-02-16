@extends('layouts.app')

@section('title', 'Slider')

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
                    @if (!empty($slider->id))
                        slider_form333
                    @else
                        slider_form333
                    @endif
                @endslot
                @slot('title')
                    {{isset($slider->id) ? 'Edit Data' : 'Add new data'}}
                @endslot

                @slot('route')
                    @if (!empty($slider->id))
                        {{route('admin.common.slider.update',$slider->id)}}
                    @else
                        {{route('admin.common.slider.store')}}
                    @endif
                @endslot

                @slot('fields')
                    {{method_field(isset($district) ? 'PUT' : 'POST')}}
                    {{csrf_field()}}
                    <div class="form-group">
                        {{ Form::label('type', 'Type', array('class' => 'type')) }}
                        {{ Form::select('type', [0 => 'Main Slider', 1 => 'Featured Slider'],$slider->type??0, ['required', 'class' => 'form-control']) }}
                    </div>


                    <div class="form-group">
                        {{ Form::label('image_id', 'Image', array('class' => 'image')) }}
                        {{ Form::text('image_id', $slider->image_id??'', ['required', 'class' => 'form-control', 'placeholder' => 'Enter Image ID...']) }}
                    </div>

                    <div class="form-group">
                        {{ Form::label('title', 'Title', array('class' => 'title')) }}
                        {{ Form::text('title', $slider->title??'', ['class' => 'form-control', 'placeholder' => 'Enter Title']) }}
                    </div>

                    <div class="form-group">
                        {{ Form::label('description', 'Description', array('class' => 'title')) }}
                        {{ Form::text('description', $slider->description??'', ['class' => 'form-control', 'placeholder' => 'Enter description']) }}
                    </div>

                    <div class="form-group">
                        {{ Form::label('color_code', 'Color Code', array('class' => 'url')) }}
                        {{ Form::text('color_code', $slider->color_code??'', ['class' => 'form-control','id' => 'colorCodeId','onload' => 'colorCode()','onKeyUp' => 'colorCode()', 'placeholder' => 'Enter Color Code...']) }}
                        <div id="changeColor" style="background-color:#{{ $slider->color_code??null }}">

                        </div>
                    </div>

                    <div class="form-group">
                        {{ Form::label('text_color', 'Text Color', array('class' => 'url', 'id' => 'text_color' ,'style' => 'color: #'.!empty($slider->text_color??''))) }}
                        {{ Form::text('text_color', $slider->text_color??'', ['class' => 'form-control','id' => 'textColorId','onload' => 'textColor()','onKeyUp' => 'textColor()', 'placeholder' => 'Enter Text Color...']) }}

                    </div>
                    <div class="form-group">
                        {{ Form::label('border_bottom', 'Border Bottom', array('class' => 'border_bottom', 'id' => 'border_bottom' )) }}
                        {{ Form::text('border_bottom', $slider->border_bottom??'', ['class' => 'form-control','placeholder' => 'Enter Border...']) }}

                    </div>
                    <div class="form-group">
                        {{ Form::label('url', 'URL', array('class' => 'url')) }}
                        {{ Form::text('url', $slider->url??'', ['required', 'class' => 'form-control', 'placeholder' => 'Enter URL...']) }}
                    </div>

                    <div class="form-group">
                        {{ Form::label('internal', 'URL Type', array('class' => 'internal')) }}
                        {{ Form::select('internal', [0 => 'External', 1 => 'Internal'],$slider->internal??0, ['required', 'class' => 'form-control']) }}
                    </div>

                     <div class="form-group">
                        {{ Form::label('device', 'Device', array('class' => 'device')) }}
                        {{ Form::select('device', [0 => 'Web', 1 => 'Mobile'],$slider->device??0, ['required', 'class' => 'form-control']) }}
                    </div>

                    <div class="form-group">
                        {{ Form::label('Position', 'Position', array('class' => 'position')) }}
                        {{ Form::number('position', $slider->position??0, ['', 'class' => 'form-control']) }}
                    </div>


                    <div class="form-group">
                        {{ Form::label('active', 'Active', array('class' => 'active')) }}
                        {{ Form::select('active', [0 => 'No', 1 => 'Yes'],$slider->active??0, ['required', 'class' => 'form-control']) }}
                    </div>


                @endslot
            @endcomponent
        </div>
        <div class="col-md-9">

            <div class="box box-success">
                <div class="box-header">


                    <h3 class="box-title">
                        Sliders
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
                                <th>Type</th>
                                <th>Image</th>
                                <th>URL</th>
                                <th>URL Type</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Device</th>
                                <th>Position</th>
                                <th>Active</th>
                                <th>Action</th>
                            </tr>

                            @foreach($sliders as $slider)
                                <tr>
                                     <td>
                                    @if($slider->type == 0)
                                     Main Slider
                                    @elseif($slider->type == 1)
                                     Featured Slider
                                    @endif
                                    </td>
                                    <td>

                                     <img src="{{asset($slider->image->icon_size_directory??'')}}" height="60px" width="60px"/>
                                    </td>
                                    <td>{{ $slider->url }}</td>
                                    <td>
                                      @if($slider->internal)
                                       Internal
                                      @else
                                       External
                                      @endif
                                    </td>
                                    <td>{{$slider->title}}</td>
                                    <td>{{$slider->description}}</td>
                                    <td>
                                      @if($slider->device == 0)
                                       Web
                                      @elseif($slider->device == 1)
                                       Mobile
                                      @endif
                                    </td>
                                    <td>{{ $slider->position }}</td>
                                    <td>
                                      @if($slider->active)
                                       Yes
                                      @else
                                       No
                                      @endif
                                    </td>
                                    <td>

                                        <a class="btn btn-xs btn-info"
                                           href="{{route('admin.common.slider')}}?id={{$slider->id}}">
                                            <i class="fa fa-pencil-square-o"></i>
                                        </a>

                                        <a class="btn btn-xs btn-danger"
                                           href="{{route('admin.common.slider.delete',$slider->id)}}">
                                            <i class="fa fa-pencil-square-o"></i>
                                        </a>

                                    </td>


                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="box-footer clearfix">
                            {{ $query ? '' : $sliders->links('component.paginator', ['object' => $sliders]) }}
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

    <script>

        function colorCode () {
            let color = jQuery("#colorCodeId").val();
            if(color != null){
                jQuery("#changeColor").css("background-color", "#"+color);
            }else{
                jQuery("#changeColor").css("background-color", '');
            }


        }

        function textColor () {
            let color = jQuery("#textColorId").val();
            if(color != null){
                jQuery("#text_color").css("color", "#"+color);
            }else{
                jQuery("#text_color").css("color", '');
            }

                console.log(color)
        }


    </script>

@endpush
<style>
    #changeColor{
        height: .5rem;
        width: 100%;
    }
</style>
