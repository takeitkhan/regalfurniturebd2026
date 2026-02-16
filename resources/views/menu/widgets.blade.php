@extends('layouts.app')

@section('title', 'Widgets')
@section('sub_title', 'widgets or sidebar management panel')
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
        <div class="col-md-7" id="signupForm">
            
            @component('component.form')
                @slot('form_id')
                    @if (!empty($widget->id))
                        widget_forms
                    @else
                        widget_form
                    @endif
                @endslot
                @slot('title')
                    Add a new widget
                @endslot

                @slot('route')
                    @if (!empty($widget->id))
                        widget/{{$widget->id}}/update
                    @else
                        widget_save
                    @endif
                @endslot

                @slot('fields')
                <!-- text input -->
                    {{--@if (!empty($widget->id))--}}
                    {{--{{ Form::hidden('widget_id', $widget->id, ['required']) }}--}}
                    {{--@endif--}}

                    <div class="form-group">
                        {{ Form::label('widget_name', 'Widget Name', array('class' => 'widget_name')) }}
                        {{ Form::text('widget_name', (!empty($widget->name) ? $widget->name : NULL), ['required', 'class' => 'form-control', 'placeholder' => 'Enter widget name...']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('widget_content', 'Widget Content', array('class' => 'widget_content')) }}
                        {{ Form::textarea('widget_content', (!empty($widget->description) ? $widget->description : NULL), ['required', 'class' => 'form-control', 'id' => 'wysiwyg', 'placeholder' => 'Enter widget content...']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('widget_css_class', 'Widget CSS Class', array('class' => 'widget_css_class')) }}
                        {{ Form::text('widget_css_class', (!empty($widget->cssclass) ? $widget->cssclass : NULL), ['required', 'class' => 'form-control', 'placeholder' => 'Enter widget css class. Use space for multiple class...']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('widget_id', 'Widget CSS ID', array('class' => 'widget_id')) }}
                        {{ Form::text('widget_id', (!empty($widget->cssid) ? $widget->cssid : NULL), ['class' => 'form-control', 'placeholder' => 'Enter widget single css ID...']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('widget_position', 'Widget Position', array('class' => 'widget_position')) }}

                        @if (!empty($widget->id))
                            <?php $position = $widget->position; ?>
                        @else
                            <?php
                            $position = \App\Models\Dashboard::orderBy('id', 'desc')->get();
                            if ($position->count() > 0) {
                                $position = $position->first()->id + 1;
                            } else {
                                $position = null;
                            }

                            ?>
                        @endif

                        {{ Form::text('widget_position', (!empty($position) ? $position : NULL), ['class' => 'form-control', 'placeholder' => 'Enter widget name...', 'readonly' => true]) }}
                    </div>

                    <!-- textarea -->
                    <div class="form-group">
                        {{ Form::label('widget_type', 'Widget Type', array('class' => 'widget_type')) }}
                        {{ Form::select('widget_type', ['adsense' => 'Adsense', 'text' => 'Text', 'links' => 'Links', 'shortcode' => 'Shortcode'], (!empty($widget->type) ? $widget->type : NULL), ['class' => 'form-control', 'placeholder' => 'Pick a type...']) }}
                    </div>
                    <div class="form-group">
                        <?php //dump($widget); ?>
                        {{ Form::label('is_active', 'Will it be Active?', array('class' => 'is_active')) }}
                        {{ Form::select('is_active', ['1' => 'True', '0' => 'False'], (!empty($widget->is_active) && $widget->is_active == 1 ? $widget->is_active : 0), ['class' => 'form-control', 'placeholder' => 'Will it be active...']) }}
                    </div>
                @endslot
            @endcomponent
        </div>
        <div class="col-md-5">
            @foreach($widgets as $b)
                <div class="box box-default collapsed-box">
                    <div class="box-header with-border">
                        <h3 class="box-title box-success">{{ $b->name }}</h3>

                        <div class="box-tools pull-right">
                            <a type="button"
                               class="btn btn-box-tool"
                               data-widget="collapse"
                               data-toggle="tooltip"
                               title=""
                               data-original-title="Collapse">
                                <i class="fa fa-minus"></i>
                            </a>
                            <a type="button"
                               href="{{ url("/edit_widget/{$b->id}") }}"
                               class="btn btn-box-tool"
                               data-toggle="tooltip"
                               title="">
                                <i class="fa fa-pencil-square-o"></i>
                            </a>
                            <a type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip"
                               title="" data-original-title="Remove">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
                    </div>
                    <div class="box-body" style="">
                        {!! $b->description !!}
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer" style="">
                        Position: {!! $b->position !!} | CSS Class: {{ $b->cssclass }} | Created @ {{ $b->created_at }}
                        | Modified @ {{ $b->updated_at }}
                        <span class="pull-right">
                            {{ Form::open(['method' => 'delete', 'route' => ['delete_widget', $b->id], 'class' => 'delete_form']) }}
                            {{ Form::button('<i class="fa fa-times"></i>', array('type' => 'submit', 'class' => 'btn btn-xs btn-danger')) }}
                            {{ Form::close() }}
                        </span>
                    </div>
                    <!-- /.box-footer-->
                </div>
            @endforeach
        </div>
    </div>
@endsection

@push('scripts')
    <script>

    </script>
@endpush