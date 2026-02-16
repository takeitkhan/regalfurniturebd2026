@extends('layouts.app')

@section('title', 'Variations')
@section('sub_title', 'all variation management panel')
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
        <div class="col-md-3" id="signupForm">
            @component('component.form')
                @slot('form_id')
                    @if (!empty($variation->id))
                        variation_forms
                    @else
                        variation_form
                    @endif
                @endslot
                @slot('title')
                    Add a new variation
                @endslot

                @slot('route')
                    @if (!empty($variation->id))
                        variation/{{$variation->id}}/update
                    @else
                        variation_save
                    @endif
                @endslot
                @slot('fields')
                    <div class="form-group">
                        {{ Form::hidden('user_id', (!empty(\Auth::user()->id) ? \Auth::user()->id : NULL), ['type' => 'hidden']) }}
                        {{ Form::label('label_name', 'Label', array('class' => 'label_name')) }}
                        {{ Form::text('label_name', (!empty($variation->label_name) ? $variation->label_name : NULL), ['required', 'class' => 'form-control', 'placeholder' => 'Enter label...']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('show_on', 'Show On/Work With', array('class' => 'show_on')) }}
                        {{ Form::text('show_on', (!empty($variation->show_on) ? $variation->show_on : NULL), ['required', 'class' => 'form-control', 'placeholder' => 'Enter show on...']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('field_name', 'Field Name', array('class' => 'field_name')) }}
                        {{ Form::text('field_name', (!empty($variation->field_name) ? $variation->field_name : NULL), ['required', 'class' => 'form-control', 'placeholder' => 'Enter field name...']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('field_values', 'Values', array('class' => 'field_values')) }}
                        {{ Form::textarea('field_values', (!empty($variation->field_values) ? $variation->field_values : NULL), ['class' => 'form-control', 'placeholder' => 'Enter field values...']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('field_type', 'Field Type', array('class' => 'field_type')) }}
                        {{ Form::text('field_type', (!empty($variation->field_type) ? $variation->field_type : NULL), ['class' => 'form-control', 'placeholder' => 'Enter field type...']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('field_attributes', 'Field Attributes', array('class' => 'field_attributes')) }}
                        {{ Form::text('field_attributes', (!empty($variation->field_attributes) ? $variation->field_attributes : NULL), ['class' => 'form-control', 'placeholder' => 'Enter field attributes...']) }}
                    </div>
                @endslot
            @endcomponent
        </div>
        <div class="col-md-9">

            <div class="box box-success">
                <div class="box-header with-border">
                    <div class="box-header">
                        <h3 class="box-title">
                            Variations
                            <a href="{{ url('variations') }}" class="btn btn-xs btn-success">
                                <i class="fa fa-plus"></i>
                            </a>
                        </h3>

                        <div class="box-tools">
                            <div class="input-group input-group-sm" style="width: 150px;">
                                <input type="text" name="table_search" class="form-control pull-right"
                                       placeholder="Search">

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
                                {{--'user_id', 'show_on', 'label', 'field_name', 'field_values', 'field_type', 'field_attributes', 'is_active'--}}
                                <th></th>
                                <th>Show on</th>
                                <th>Label</th>
                                <th>Field Name</th>
                                <th>Values</th>
                                <th>Field Type</th>
                                <th>Field Attributes</th>
                                <th>Is active</th>
                                <th>Action</th>
                            </tr>
                            @foreach($variations as $variation)
                                <tr>
                                    <td>{{ $variation->id }}</td>
                                    <td>{{ $variation->show_on }}</td>
                                    <td>{{ $variation->label_name }}</td>
                                    <td>{{ $variation->field_name }}</td>
                                    <td>{{ $variation->field_values }}</td>
                                    <td>{{ $variation->field_type }}</td>
                                    <td>{{ $variation->field_attributes }}</td>
                                    <td>{{ $variation->created_at }}</td>
                                    <td>
                                        <a type="button" href="{{ url('edit_variation/'. $variation->id) }}"
                                           class="btn btn-xs btn-success"><i class="fa fa-pencil-square-o"></i>
                                        </a>
                                        {{ Form::open(['method' => 'delete', 'route' => ['variation_delete', $variation->id], 'class' => 'delete_form']) }}
                                        {{ Form::button('<i class="fa fa-times"></i>', array('type' => 'submit', 'class' => 'btn btn-xs btn-danger')) }}
                                        {{ Form::close() }}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="box-footer clearfix">
                            {{ $variations->links('component.paginator', ['object' => $variations]) }}
                        </div>
                        <!-- /.pagination pagination-sm no-margin pull-right -->
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>

</script>
@endpush