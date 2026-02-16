@component('component.form')
    @slot('form_id')
        @if (!empty($interior->id))
            slider_form333
        @else
            slider_form333
        @endif
    @endslot
    @slot('title')
        {{ isset($interior->id) ? 'Edit Data' : 'Add new data' }}
    @endslot

    @slot('route')
        @if (!empty($interior->id))
            {{ route('admin.interior.update', $interior->id) }}
        @else
            {{ route('admin.interior.store') }}
        @endif
    @endslot

    @slot('fields')
        <!-- {//{//method_field(isset($interior->id) ? 'PUT' : 'POST')//}//} -->
        {{ csrf_field() }}


        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('title', 'Title', ['class' => 'title']) }}
                    {{ Form::text('title', $interior->title ?? '', ['required', 'class' => 'form-control', 'placeholder' => 'Enter Title...']) }}
                </div>


                <div class="form-group">
                    {{ Form::label('sub_title', 'Sub Title', ['class' => 'sub_title']) }}
                    {{ Form::text('sub_title', $interior->sub_title ?? '', ['required', 'class' => 'form-control', 'placeholder' => 'Enter Sub Title...']) }}
                </div>

                <div class="form-group">
                    {{ Form::label('category_id', 'Category', ['class' => 'category_id']) }}
                    {{ Form::select('category_id', $terms, $interior->category_id ?? 0, ['required', 'class' => 'form-control']) }}
                </div>

                <div class="form-group">
                    {{ Form::label('active', 'Active', ['class' => 'active']) }}
                    {{ Form::select('active', [0 => 'No', 1 => 'Yes'], $interior->active ?? '', ['required', 'class' => 'form-control']) }}
                </div>
                <div class="form-group">
                    {{ Form::label('image_id', 'Image', ['class' => 'image_id']) }}
                    {{ Form::text('image_id', $interior->image_id ?? 0, ['required', 'class' => 'form-control', 'placeholder' => 'Enter image id...']) }}
                    <img style="margin:.5rem;" src="{{ asset($interior->image->full_size_directory ?? '') }}" id="setImageUrl"
                        height="100px" width="150px" alt="{{ __('') }}">
                </div>


            </div>
            <div class="col-md-6">



                @if (!empty($medias))
                    <div class="row" id="reload_me">
                        @foreach ($medias as $media)
                            <div class="col-xs-4 col-md-3">
                                <div href="#" class="thumbnail">
                                    <img src="{{ url($media->icon_size_directory) }}" class="img-responsive"
                                        id="getImageurl{{ $media->id }}" style="max-height: 80px; min-height: 80px;" />
                                    <div class="caption text-center">
                                        <p>
                                            <a href="javascript:void(0);" data-id="{{ $media->id }}"
                                                data-option="{{ $media->filename }}" class="btn btn-xs btn-primary"
                                                onclick="get_id(this);" role="button">
                                                Use
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif


            </div>

        </div>
    @endslot
@endcomponent
@component('component.dropzone')
@endcomponent



<script src="{{ asset('public/plugins/dropzone.js') }}"></script>
<script src="{{ asset('public/js/dropzone-config.js') }}"></script>
<script src="{{ asset('public/js/jquery.czMore-latest.js') }}"></script>


<script type="text/javascript">
    function get_id(identifier) {
        //alert("data-id:" + jQuery(identifier).data('id') + ", data-option:" + jQuery(identifier).data('option'));

        console.log(identifier)
        var dataid = jQuery(identifier).data('id');
        var imageurl = jQuery("#getImageurl" + dataid).attr('src');

        jQuery('#image_id').val(dataid);
        jQuery('#setImageUrl').attr('src', imageurl)

    }
</script>
