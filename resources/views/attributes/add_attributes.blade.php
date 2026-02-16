@extends('layouts.app')

@section('title', 'Attribute')
@section('sub_title', 'Attribute add or modification form')
@section('content')

    <?php
    if (!empty($att)) {
        //dump($att);
    }
    ?>
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

        <div class="col-md-10">
            <div class="panel panel-info">

                <div class="panel-heading">
                    <a href="{{ url('add_attributes/' . $attgroup->id) }}" class="btn btn-xs btn-success pull-right">
                        + Add new field
                    </a>
                    <h4 class="panel-title">
                        @if(Request::get('single'))
                            You are editing field from {{ $attgroup->group_name }} attribute group
                        @else
                            + Add Attribute
                        @endif

                    </h4>
                </div>
                <div class="panel-body">
                    @if(!empty($attgroup->id) && !empty($att->id))
                        {{ Form::open(array('url' => 'attribute/'.$att->id.'/update', 'method' => 'post', 'value' => 'PATCH', 'id' => 'attribute_save', 'files' => true, 'autocomplete' => 'off')) }}
                    @else
                        {{ Form::open(array('url' => 'attribute_save', 'method' => 'post', 'value' => 'PATCH', 'id' => 'attribute_save', 'files' => true, 'autocomplete' => 'off')) }}
                    @endif

                    {{ Form::hidden('user_id', (!empty(\Auth::user()->id) ? \Auth::user()->id : NULL), ['type' => 'hidden']) }}
                    {{ Form::hidden('attgroup_id', (!empty($attgroup->attgroup_id) ? $attgroup->attgroup_id : Request::segment(2)), ['required']) }}


                    <div class="row">
                        <div class="panel-content-wrap">
                            <div class="col-md-4">
                                <div class="panel-content">
                                    <h4>Field Label</h4>
                                    <p>
                                        This is the name which will appear on the EDIT page
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="panel-content-input">
                                    {{ Form::text('field_label', (!empty($att->field_label) ? $att->field_label : NULL), ['required', 'class' => 'form-control', 'id' => 'field_label', 'placeholder' => 'Field Label...']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="panel-content-wrap">
                            <div class="col-md-4">
                                <div class="panel-content">
                                    <h4>
                                        Field Name
                                    </h4>
                                    <p>
                                        Single word, no spaces. Underscores and dashes allowed
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="panel-content-input">
                                    {{ Form::text('field_name', (!empty($att->field_name) ? $att->field_name : NULL), ['required', 'class' => 'form-control', 'id' => 'field_name', 'readonly' => true, 'placeholder' => 'Field Name...']) }}

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="panel-content-wrap">
                            <div class="col-md-4">
                                <div class="panel-content">
                                    <h4>Field Type</h4>
                                    <p>
                                        Single word, no spaces. Underscores and dashes allowed
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-8">
                                @php
                                    $types = array(
                                        'text' => 'Text',
                                        'textarea' => 'Textarea',
                                        'number' => 'Number',
                                        'email' => 'Email',
                                        'url' => 'URL',
                                        'password' => 'Password',
                                        'select' => 'Select/Dropdown',
                                        'checkbox' => 'Checkbox',
                                        'radio' => 'Radio Button'
                                    );
                                @endphp

                                <div class="panel-content-input">
                                    <div class="form-group">
                                        <select class="form-control" name="field_type" id="field_type_selector">
                                            @foreach($types as $key => $type)
                                                <option value="{{ $key }}"
                                                <?php if (!empty($att->field_type)) {
                                                    echo ($key == $att->field_type) ? 'selected="selected"' : null;
                                                }?>>
                                                    {{ $type }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="panel-content-wrap">
                            <div class="col-md-4">
                                <div class="panel-content">
                                    <h4>
                                        Instructions
                                    </h4>
                                    <p>
                                        Instructions for authors. Shown when submitting data
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="panel-content-input">
                                    {{ Form::text('instructions', (!empty($att->instructions) ? $att->instructions : NULL), ['class' => 'form-control', 'placeholder' => 'Field Instructions...']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="panel-content-wrap">
                            <div class="col-md-4">
                                <div class="panel-content">
                                    <h4>
                                        Required?
                                    </h4>
                                    <p>
                                        Instructions for authors. Shown when submitting data
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="panel-content-input">
                                    <div class="form-group">

                                        <div class="radio" style="display: inline-flex;">
                                            <label>
                                                <input type="radio" name="is_required"
                                                       id="optionsRadios1"
                                                       value="1" {{ !empty($att) ? ($att->is_required=="1") ? "checked" : "" : null }}>
                                                Yes
                                            </label>
                                        </div>
                                        &nbsp;&nbsp;&nbsp;
                                        <div class="radio" style="display: inline-flex;">
                                            <label>
                                                <input type="radio" name="is_required"
                                                       id="optionsRadios2"
                                                       value="0" {{ !empty($att) ? ($att->is_required=="0") ? "checked" : "" : null }}>
                                                No
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="panel-content-wrap">
                            <div class="col-md-4">
                                <div class="panel-content">
                                    <h4>
                                        Field Capability
                                    </h4>
                                    <p>
                                        Instructions for field capability on frontend and backend
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="panel-content-input">
                                    <?php //dump($att); ?>
                                    <div class="form-group">
                                        <div class="radio" style="display: inline-flex;">
                                            <label>
                                                <input type="radio" name="field_capability"
                                                       id="optionsRadios1" value="filterable"
                                                        {!! !empty($att->field_capability) && $att->field_capability == 'filterable' ? 'checked="checked"' : null !!}>
                                                Filterable
                                            </label>
                                        </div>
                                        <div class="radio" style="display: inline-flex;">
                                            <label>
                                                <input type="radio" name="field_capability"
                                                       id="optionsRadios1" value="searchable"
                                                        {!! !empty($att->field_capability) && $att->field_capability == 'searchable' ? 'checked="checked"' : null !!}>
                                                Searchable
                                            </label>
                                        </div>
                                        <div class="radio" style="display: inline-flex;">
                                            <label>
                                                <input type="radio" name="field_capability"
                                                       id="optionsRadios3" value="both"
                                                        {!! !empty($att->field_capability) && $att->field_capability == 'both' ? 'checked="checked"' : null !!}>
                                                Both
                                            </label>
                                        </div>
                                        <div class="radio" style="display: inline-flex;">
                                            <label>
                                                <input type="radio" name="field_capability"
                                                       id="optionsRadios3" value="none"
                                                        {!! !empty($att->field_capability) && $att->field_capability == 'none' ? 'checked="checked"' : null !!}>
                                                None
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="panel-content-wrap">
                            <div class="col-md-4">
                                <div class="panel-content">
                                    <h4>
                                        Default Value?
                                    </h4>
                                    <p>
                                        Use Following Pattern
                                    </p>
                                    <p>
                                        <small>
                                            <h3>Dropdown/Select or Checkbox or Radio</h3>
                                            <h4>Key:Value|Key1:Value1|Key2:Value2</h4>
                                        </small>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="panel-content-input">
                                    {{ Form::textarea('default_value', (!empty($att->default_value) ? $att->default_value : NULL), ['rows' => 3,  'id' => 'default_value', 'class' => 'form-control', 'placeholder' => 'Default Value...']) }}
                                    <span class="help-block"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="panel-content-wrap">
                            <div class="col-md-4">
                                <div class="panel-content">
                                    <h4>Placeholder Text</h4>
                                    <p>Appears before the input</p>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div>
                                    {{ Form::text('placeholder', (!empty($att->placeholder) ? $att->placeholder : NULL), ['class' => 'form-control', 'placeholder' => 'Placeholder...']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="panel-content-wrap">
                            <div class="col-md-4">
                                <div class="panel-content">
                                    <h4>Field CSS ID</h4>
                                    <p>Put field css ID</p>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div>
                                    {{ Form::text('css_id', (!empty($att->css_id) ? $att->css_id : NULL), ['class' => 'form-control', 'readonly' => true, 'placeholder' => 'CSS ID...', 'id' => 'css_id']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="panel-content-wrap">
                            <div class="col-md-4">
                                <div class="panel-content">
                                    <h4>Field CSS Class</h4>
                                    <p>Put field css class</p>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div>
                                    {{ Form::text('css_class', (!empty($att->css_class) ? $att->css_class : 'form-control'), ['class' => 'form-control', 'readonly' => true, 'placeholder' => 'CSS Class...']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="panel-content-wrap">
                            <div class="col-md-4">
                                <div class="panel-content">
                                    <h4>
                                        Number Range
                                    </h4>
                                    <p>
                                        Put your minimum and maximum range
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="panel-content-input">
                                            {{ Form::text('minimum', (!empty($att->minimum) ? $att->minimum : 1), ['class' => 'form-control', 'placeholder' => 'Field minimum...']) }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="panel-content-input">
                                            {{ Form::text('maximum', (!empty($att->maximum) ? $att->maximum : 100), ['class' => 'form-control', 'placeholder' => 'Field maximum...']) }}
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    {{--<div class="row">--}}
                    {{--<div class="panel-content-wrap">--}}
                    {{--<div class="col-md-4">--}}
                    {{--<div class="panel-content">--}}
                    {{--<h4>--}}
                    {{--Field Prepend (Optional)--}}
                    {{--</h4>--}}
                    {{--<p>--}}
                    {{--You are able to add HTML--}}
                    {{--</p>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="col-md-8">--}}
                    {{--<div class="panel-content-input">--}}
                    {{--{{ Form::text('prepend', (!empty($att->prepend) ? $att->prepend : NULL), ['class' => 'form-control', 'placeholder' => 'Field Prepend...']) }}--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}

                    {{--<div class="row">--}}
                    {{--<div class="panel-content-wrap">--}}
                    {{--<div class="col-md-4">--}}
                    {{--<div class="panel-content">--}}
                    {{--<h4>--}}
                    {{--Field Append (Optional)--}}
                    {{--</h4>--}}
                    {{--<p>--}}
                    {{--You are able to add HTML--}}
                    {{--</p>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="col-md-8">--}}
                    {{--<div class="panel-content-input">--}}
                    {{--{{ Form::text('append', (!empty($att->append) ? $att->append : NULL), ['class' => 'form-control', 'placeholder' => 'Field Append...']) }}--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}


                    <div class="row">
                        <div class="panel-content-wrap">
                            <div class="col-md-4">
                                <?php
                                if (!empty($att)) {
                                    $position_latest = $att->position;
                                } else {
                                    $atts = App\Models\Attribute::where(['attgroup_id' => Request::segment(2)])->orderBy('position', 'desc')->get()->first();
                                    if ($atts == null) {
                                        $position_latest = 0;
                                    } else {
                                        $position_latest = ($atts->position + 1);
                                    }

                                }
                                ?>
                                {{ Form::hidden('position', $position_latest) }}
                            </div>
                            <div class="col-md-8">
                                {{ Form::submit('Save', ['class' => 'btn btn-success', 'id' => 'submitBtn']) }}
                            </div>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>

            </div>
            <div class="reloader">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-md-2">Label</div>
                            <div class="col-md-2">Name</div>
                            <div class="col-md-2">Placeholder</div>
                            <div class="col-md-2">Type</div>
                            <div class="col-md-1 text-center">Position</div>
                            <div class="col-md-1">Action</div>
                        </div>
                    </div>
                    <div class="panel-body">

                        @if (count($attributes))

                            <div id="sortable">

                                @php $i = 1 @endphp
                                @foreach($attributes as $attribute)
                                    <div id="item_{{ $attribute->position }}"
                                         data-id="{{ $attribute->id }}"
                                         data-position="{{ $attribute->position }}"
                                         class="panel-heading">
                                        <div class="row styler"
                                             style="padding: 15px 0; border-bottom: 1px solid #EEE; cursor: pointer;">
                                            <div class="col-md-2">
                                                <h5>{{ $attribute->field_label }}</h5>
                                            </div>
                                            <div class="col-md-2">
                                                {{ $attribute->field_name }}
                                            </div>
                                            <div class="col-md-2">
                                                {{ $attribute->placeholder }}
                                            </div>
                                            <div class="col-md-2">
                                                {{ $attribute->field_type }}
                                            </div>
                                            <div class="col-md-1">
                                                <div class="position-number text-center">
                                                <span class="position-cls">
                                                    {{ $attribute->position }}
                                                </span>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                            <span class="label-info">
                                                <ul class="list-unstyled">
                                                    <li>
                                                        <a class="btn btn-xs btn-success" style="color: #FFF;"
                                                           href="{{ url('add_attributes/' . (!empty($attgroup->attgroup_id) ? $attgroup->attgroup_id : Request::segment(2)) . '?single='. $attribute->id) }}">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="btn btn-xs btn-danger" style="color: #FFF;"
                                                           onclick="alert('Are you sure?')"
                                                           href="{{ url("delete_attribute?attribute_id={$attribute->id}&attgroup_id={$attribute->attgroup_id}") }}">
                                                            <i class="fa fa-times"></i>
                                                        </a>
                                                    </li>
                                                    {{--<li>--}}
                                                    {{--<a href="#">Delete</a>--}}
                                                    {{--</li>--}}
                                                </ul>
                                            </span>
                                            </div>
                                        </div>
                                    </div>
                                    @php $i++ @endphp
                                @endforeach

                            </div>

                            <span class="sorting_query"></span>

                        @else

                            <p>No fields. Fill up the add attribute form and save to create your first field.</p>

                        @endif

                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
@push('scripts')
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $.noConflict();

            $("div#sortable").sortable({
                axis: 'y',
                start: function (e, ui) {
                    var old_position = $(this).sortable('toArray');
                },
                update: function (event, ui) {
                    var new_position = $(this).sortable('toArray');
                },
                stop: function (e, ui) {

                    var arr = $(this).sortable('toArray');


                    var thearray = [];

                    $.each(arr, function (index, value) {
                        //console.log(index);
                        //console.log(value);

                        var newdata = {
                            'c_position': index,
                            'p_position': $('#' + value).data('position'),
                            'data_id': $('#' + value).data('id')
                        };

                        //console.log(newdata);

                        thearray.push(newdata);

                        //var data_id = $('#' + value).data('id');
                        //var data_position = $('#' + value).data('position');

                        //console.log(data_id);
                        //console.log(data_position);
                    });

                    //console.log(thearray);

                    $.ajax({
                        url: baseurl + '/sortable_update',
                        data: {'data': JSON.stringify(thearray)},
                        type: 'GET',
                        success: function (data) {
                            //console.log(data);
                            /** jQuery("div.reloader").load(baseurl + "{{ '/' . Request::segment(1) . '/' . Request::segment(2) }}  div.reloader"); **/
                            location.reload();
                        },
                        error: function () {
                            // showError('Sorry. Try reload this page and try again.');
                            // processing.hide();
                        }
                    });

                }
            });
            $("#sortable").disableSelection();

            $(document).on('blur keyup', '#field_label', function () {
                var m = $(this).val();
                var cute1 = m.toLowerCase().replace(/ /g, '_').replace(/&amp;/g, 'and').replace(/&/g, 'and').replace(/ ./g, 'dec').replace(/-/g, '_');
                var cute = cute1.replace(/[`~!@#$%^&*()|+\=?;:'"”,.<>\{\}\[\]\\\/]/gi, '');

                $('#field_name').val(cute);
                $('#css_id').val(cute);
            });


            $(document).on('blur keyup change', '#field_type_selector', function () {
                var value = $(this).val();

                if (value == 'select' || value == 'checkbox' || value == 'radio') {
                    $(document).on('blur keyup', '#default_value', function () {
                        const regex = /[a-zA-Z0-9]:[a-zA-Z0-9]+\|/g;
                        const str = $(this).val();
                        //const str = `JavaScript Pattern Queue:Bold Actress|Them:Into gsdgsd|Information Now:Queue Manager|Red:Red|Blue:Blue`;
                        const isExists = regex.test(str);

                        if (isExists == true) {
                            console.log('true');
                            $('#submitBtn').attr('disabled', false);
                            $('.help-block').html('');
                            $(this).css('border-color', '#d2d6de');
                        } else {
                            console.log('false');
                            $('#submitBtn').attr('disabled', true);
                            $('.help-block').html('Pattern are not correct. Please check pattern from left.');
                            $('.help-block').css('color', 'red');
                            $(this).css('border-color', 'red');
                        }
                    });
                } else {

                }
            });


        });
    </script>

    <style type="text/css">
        .label-info ul li {
            display: inline-block;
        }

        .label-info ul li a {
            display: inline-block;
            font-size: 13px;
            color: #666;
            margin-right: 5px;
        }

        .label-info ul li a:hover {
            background: none;
            color: red
        }

        .position-cls {
            display: inline-block;
            border: 1px solid #DDD;
            width: 25px;
            height: 25px;
            text-align: center;
            line-height: 24px;
            border-radius: 50%;
            color: #666;
        }

        .label-info ul li {
            display: inline-block;
        }

        .label-info ul li a {
            display: inline-block;
            font-size: 13px;
            color: #666;
            margin-right: 5px;
        }

        .label-info ul li a:hover {
            background: none;
            color: red
        }

        .panel-content-wrap {
            padding: 5px;
        }

        div#sortable > .panel-heading {
            margin: 0px !important;
            padding: 0px !important;
        }

        .panel-content h4 {
            margin: 0;
            padding: 0;
            font-weight: 600;
            font-size: 14px;
        }

        .panel-content p {
            font-size: 12px;
            font-weight: 400;
        }

        .styler h5 {
            font-weight: bold;
        }
    </style>
@endpush
