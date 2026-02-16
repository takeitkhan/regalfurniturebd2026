@extends('layouts.app')

@section('title', 'Variation Group')
@section('sub_title', 'Variation Group add or modification form')
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
            @if(!empty($variationGroup->id))
                {{ Form::open(array('url' => 'admin/update-variation-group/'. $variationGroup->id, 'method' => 'post', 'value' => 'PATCH', 'id' => 'add_attgroup', 'files' => false)) }}
                {{ Form::hidden('group_id', $variationGroup->id, ['type' => 'hidden']) }}
            @else
                {{ Form::open(array('url' => 'admin/store-variation-group', 'method' => 'post', 'value' => 'PATCH', 'id' => 'add_attgroup', 'files' => false)) }}
            @endif

            <div class="form-group">
                {{ Form::label('title', 'Variation Group Title', array('class' => 'group_name')) }}
                {{ Form::text('title', (!empty($variationGroup->title) ? $variationGroup->title : NULL), ['required', 'id' => 'group_name', 'class' => 'form-control', 'placeholder' => 'Enter Title...']) }}
            </div>

            <div class="form-group">
                {{ Form::label('group_name_slug', 'Variation Group Slug', array('class' => 'group_name_slug')) }}
                {{ Form::text('slug', (!empty($variationGroup->slug) ? $variationGroup->slug : NULL), ['required', 'id' => 'group_name_slug',  'class' => 'form-control', 'placeholder' => 'Enter Group Name Slug...', 'readonly' => true]) }}
            </div>

            <div class="form-group">
                {{ Form::label('active', 'Active', array('class' => 'active')) }}
                {{ Form::select('active', [0 => 'No', 1 => 'Yes'],$variationGroup->active??'', ['required', 'class' => 'form-control']) }}
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