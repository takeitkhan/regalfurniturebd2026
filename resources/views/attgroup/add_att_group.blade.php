@extends('layouts.app')

@section('title', 'Attribute')
@section('sub_title', 'Attribute add or modification form')
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

        <div class="col-md-5">
            @if(!empty($attgroup->id))
                {{ Form::open(array('url' => 'attgroup/'. $attgroup->id .'/update', 'method' => 'post', 'value' => 'PATCH', 'id' => 'add_attgroup', 'files' => false)) }}
                {{ Form::hidden('group_id', $attgroup->id, ['type' => 'hidden']) }}
            @else
                {{ Form::open(array('url' => 'add_attgroup_save', 'method' => 'post', 'value' => 'PATCH', 'id' => 'add_attgroup', 'files' => false)) }}
            @endif

            <div class="form-group">
                {{ Form::hidden('user_id', (!empty(\Auth::user()->id) ? \Auth::user()->id : NULL), ['type' => 'hidden']) }}
            </div>

            <div class="form-group">
                {{ Form::label('group_name', 'Field Group Name', array('class' => 'group_name')) }}
                {{ Form::text('group_name', (!empty($attgroup->group_name) ? $attgroup->group_name : NULL), ['required', 'id' => 'group_name', 'class' => 'form-control', 'placeholder' => 'Enter Group Name...']) }}
            </div>

            <div class="form-group">
                {{ Form::label('group_name_slug', 'Field Group Slug', array('class' => 'group_name_slug')) }}
                {{ Form::text('group_name_slug', (!empty($attgroup->group_name_slug) ? $attgroup->group_name_slug : NULL), ['required', 'id' => 'group_name_slug',  'class' => 'form-control', 'placeholder' => 'Enter Group Name Slug...', 'readonly' => true]) }}
            </div>

            <div class="form-group">
                {{ Form::label('position', 'Field Group Position', array('class' => 'position')) }}
                <?php
                $latest = \App\Models\Attgroup::orderBy('id', 'desc')->first();
                $new = $latest->id;
                ?>
                {{ Form::text('position', (!empty($attgroup->position) ? $attgroup->position : $new + 1), ['required', 'class' => 'form-control', 'placeholder' => 'Enter Group Position...', 'readonly' => true]) }}
            </div>
            <div class="form-group">
                {{ Form::submit('Save Changes', ['class' => 'btn btn-success']) }}
            </div>
            {{ Form::close() }}
        </div>
    </div>
@endsection


@push('scripts')
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $.noConflict();

            $(document).on('keyup blur', '#group_name', function () {
                var m = $(this).val();
                var cute1 = m.toLowerCase().replace(/ /g, '-').replace(/&amp;/g, 'and').replace(/&/g, 'and').replace(/ ./g, 'dec');
                var cute = cute1.replace(/[`~!@#$%^&*()_|+\=?;:'"”,.<>\{\}\[\]\\\/]/gi, '');

                $('#group_name_slug').val(cute);
            });
        });
    </script>
@endpush