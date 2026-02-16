{{ Form::open(array('url' => $route, 'method' => 'post', 'value' => 'PATCH', 'id' => $form_id, 'files' => true, 'autocomplete' => 'off')) }}
<div class="box box-warning">
    <div class="box-header with-border">
        <h3 class="box-title">{{ $title }}</h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        {{ $fields }}
    </div>
    <!-- /.box-body -->
    <div class="box-footer">
        {{ Form::submit('Save Changes', ['class' => 'btn btn-success']) }}
    </div>
</div>
{{ Form::close() }}

{{--facebook instant article--}}