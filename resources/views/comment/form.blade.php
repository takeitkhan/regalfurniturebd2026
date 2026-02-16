@extends('layouts.app')

@section('title', 'Commnet')
@section('sub_title', 'Commnet add or modification form')
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

        <div class="col-md-8">
            @component('component.form')
                @slot('form_id')
                    @if (!empty($user->id))
                        commnet_forms
                    @else
                        commnet_form
                    @endif
                @endslot
                @slot('title')
                    @if (!empty($commnet->id))
                        Edit commnet
                    @else
                        Add a new commnet
                    @endif

                @endslot

                @slot('route')
                    @if (!empty($commnet->id))
                        commnet/{{$commnet->id}}/update
                    @else
                        commnet_save
                    @endif
                @endslot

                @slot('fields')
                    <div class="form-group">
                        {{ Form::hidden('user_id', (!empty(\Auth::user()->id) ? \Auth::user()->id : NULL), ['type' => 'hidden']) }}
                        {{ Form::hidden('lang', (!empty($commnet->lang) ? $commnet->lang : 'en'), ['type' => 'hidden']) }}
                        {{ Form::label('title', 'Title', array('class' => 'title')) }}
                        {{ Form::text('title', (!empty($commnet->title) ? $commnet->title : NULL), ['required', 'class' => 'form-control', 'placeholder' => 'Enter title...']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('sub_title', 'Sub Title', array('class' => 'sub_title')) }}
                        {{ Form::text('sub_title', (!empty($commnet->sub_title) ? $commnet->sub_title : NULL), ['class' => 'form-control', 'placeholder' => 'Enter sub_title...']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('seo_url', 'Search Engine Friendly URL', array('class' => 'seo_url')) }}
                        {{ Form::text('seo_url', (!empty($commnet->seo_url) ? $commnet->seo_url : NULL), ['type' => 'text', 'required', 'class' => 'form-control', 'placeholder' => 'Enter seo URL...']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('description', 'Description', array('class' => 'description')) }}
                        {{ Form::textarea('description', (!empty($commnet->description) ? $commnet->description : NULL), ['required', 'class' => 'form-control', 'id' => 'wysiwyg', 'placeholder' => 'Enter details content...']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('images', 'Image ID/s', array('class' => 'images')) }}
                        @if(!empty($commnet->images))
                            <?php 
                            // $im = image_ids($commnet);
                            $im = $commnet->images;
                            ?>
                            {{ Form::text('images', (!empty($im) ? $im : NULL), ['type' => 'text', 'id' => 'image_ids', 'class' => 'form-control', 'placeholder' => 'Enter image IDs...']) }}
                        @else
                            {{ Form::text('images', NULL, ['type' => 'text', 'id' => 'image_ids', 'class' => 'form-control', 'placeholder' => 'Enter image IDs...']) }}
                        @endif

                        <small id="show_image_names"></small>
                    </div>
                    <div class="form-group">
                        @if(!empty($commnet))
                            {{--                            {!!  uploaded_photos($commnet) !!}--}}
                        @endif
                    </div>
                    <div class="form-group">
                        {{ Form::label('is_sticky', 'Is it sticky?', array('class' => 'is_sticky')) }}
                        <div class="radio">
                            <label>
                                {{ Form::radio('is_sticky', 1, ((!empty($commnet) ? $commnet->is_sticky == 1 : 1) ? TRUE : FALSE), ['class' => 'radio']) }}
                                Yes. This commnet will be marked as selected top commnet
                            </label>
                        </div>
                        <div class="radio">
                            <label>

                                {{ Form::radio('is_sticky', 0, ((!empty($commnet) ? $commnet->is_sticky == 0 : 0) ? TRUE : FALSE), ['class' => 'radio']) }}
                                No. This commnet will no be marked as selected top commnet
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        {{ Form::label('is_active', 'Will it be active?', array('class' => 'is_active')) }}
                        <div class="radio">
                            <label>
                                {{ Form::radio('is_active', 1, ((!empty($commnet) ? $commnet->is_active == 1: 1) ? TRUE : FALSE), ['class' => 'radio']) }}
                                Yes. This commnet will publish
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                {{ Form::radio('is_active', 0, ((!empty($commnet) ? $commnet->is_active == 0 : 0) ? TRUE : FALSE), ['class' => 'radio']) }}
                                No. This commnet will save as draft
                            </label>
                        </div>
                    </div>
                @endslot
            @endcomponent
        </div>
        <div class="col-md-4">


            @component('component.dropzone')
            @endcomponent



            @if(!empty($medias))
                <div class="row" id="reload_me">
                    @foreach($medias as $media)
                        <div class="col-xs-6 col-md-3">
                            <div href="#" class="thumbnail">
                                <img src="{{ url($media->icon_size_directory) }}"
                                     class="img-responsive"
                                     style="max-height: 80px; min-height: 80px;"/>
                                <div class="caption text-center">
                                    <p>
                                        <a
                                                href="javascript:void(0);"
                                                data-id="{{ $media->id }}"
                                                data-option="{{ $media->filename }}"
                                                class="btn btn-xs btn-primary"
                                                onclick="get_id(this);"
                                                role="button">
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
@endsection
@push('scripts')

<script src="{{ asset('public/plugins/dropzone.js') }}"></script>
<script src="{{ asset('public/js/dropzone-config.js') }}"></script>
<script src="{{ asset('public/js/jquery.czMore-latest.js') }}"></script>


<script type="text/javascript">
    function get_id(identifier) {
        //alert("data-id:" + jQuery(identifier).data('id') + ", data-option:" + jQuery(identifier).data('option'));


        var dataid = jQuery(identifier).data('id');
        jQuery('#image_ids').val(
            function (i, val) {
                return val + (!val ? '' : ', ') + dataid;
            });
        var option = jQuery(identifier).data('option');
        jQuery('#show_image_names').html(
            function (i, val) {
                return val + (!val ? '' : ', ') + option;
            }
        );
    }
</script>
@endpush
