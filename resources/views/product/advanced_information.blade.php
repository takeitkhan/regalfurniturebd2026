<div class="box box-warning">
    <div class="box-header with-border">
        <h3 class="box-title">Advanced Information</h3>
    </div>
    <div class="form-group">
        {{ Form::label('size', 'Size', array('class' => 'size')) }}
        <select name="size" class="form-control">
            <option value="">Select a parent</option>
            {!! select_option_html($terms['data'], $parent = env('SIZE_PARENT_CAT'), ' ', (!empty($product->size) ? $product->size : NULL), FALSE ) !!}
        </select>
    </div>
    
    <div class="form-group">
        {{ Form::label('opening_system', 'Opening System', array('class' => 'opening_system')) }}
        <select name="opening_system" class="form-control">
            <option value="">Select a parent</option>
            {!! select_option_html($terms['data'], $parent = env('OPENING_SYSTEM_PARENT_CAT'), ' ', (!empty($product->opening_system) ? $product->opening_system : NULL), FALSE) !!}
        </select>
    </div>
    <div class="form-group">
        {{ Form::label('locking_system', 'Locking System', array('class' => 'locking_system')) }}
        <select name="locking_system" class="form-control">
            <option value="">Select a parent</option>
            {!! select_option_html($terms['data'], $parent = env('LOCKING_SYSTEM_PARENT_CAT'), ' ', (!empty($product->locking_system) ? $product->locking_system : NULL), FALSE) !!}
        </select>
    </div>
    <div class="form-group">
        {{ Form::label('part_palla', 'Part/Palla Fiting', array('class' => 'part_palla')) }}
        <select name="part_palla" class="form-control">
            <option value="">Select a parent</option>
            {!! select_option_html($terms['data'], $parent = env('PART_PALLA_PARENT_CAT'), ' ', (!empty($product->part_palla) ? $product->part_palla : NULL), FALSE ) !!}
        </select>
    </div>
    <div class="form-group">
        {{ Form::label('frame_color', 'Frame Color', array('class' =>
        'frame_color')) }}
        <select name="frame_color" class="form-control">
            <option value="">Select a parent</option>
            {!! select_option_html($terms['data'], $parent = env('FRAME_COLOR_PARENT_CAT'), ' ', (!empty($product->frame_color) ? $product->frame_color : NULL), FALSE ) !!}
        </select>
    </div>
    <div class="form-group">
        {{ Form::label('glass_details', 'Glass Details', array('class' => 'glass_details')) }}
        <select name="glass_details" class="form-control">
            <option value="">Select a parent</option>
            {!! select_option_html($terms['data'], $parent = env('GLASS_DETAILS_PARENT_CAT'), ' ', (!empty($product->glass_details) ? $product->glass_details : NULL), FALSE ) !!}
        </select>
    </div>
    <div class="form-group">
        {{ Form::label('lacquered', 'Lacquered', array('class' => 'lacquered')) }}
        <select name="lacquered" class="form-control">
            <option value="">Select a parent</option>
            {!! select_option_html($terms['data'], $parent = env('LACQUERED_PARENT_CAT'), ' ', (!empty($product->lacquered) ? $product->lacquered : NULL), FALSE ) !!}
        </select>
    </div>

    <div class="form-group">
        {{ Form::label('dimension', 'Dimension', array('class' => 'dimension')) }}
        {{ Form::text('dimension', (!empty($product->dimension) ? $product->dimension : NULL), ['class' => 'form-control', 'placeholder' => 'Enter dimension...']) }}
    </div>
</div>