@extends('layouts.app')

@php
$url_slug = \Request::segment(1);
if($url_slug === 'add_showroom' || $url_slug ==='edit_showroom'){
    $os = true;
}else{
    $os = false;
}

@endphp

@section('title', (($os)? 'Showroom': 'Post'))

@section('sub_title', (($os)? 'Showroom': 'Post') .'add or modification form')
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
        @if(!empty($post->id))
            <div class="col-md-12">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="">
                            <a href="{{ url("edit_post/{$post->id}") }}?p=ba"
                               class="{{ (Request::get('p') == 'ba') ? 'tab-active' : 'text-muted' }}">
                                Basic
                            </a>
                        </li>
                        <li class="">
                            <a href="{{ url("edit_post/{$post->id}") }}?p=seo-settings"
                               class="{{ (Request::get('p') == 'seo-settings') ? 'tab-active text-primary' : 'text-muted' }}">
                                Seo Information
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        @endif
        <?php if(!empty($post->id) && Request::get('p') == 'seo-settings') { ?>
        <div class="col-md-8">
            <div class="box box-warning">
                <div class="box-body">
                    @includeIf('seo.form', [$post_type = 'post', $post_id = $post->id])
                </div>
            </div>
        </div>
        <?php } else { ?>
            <div class="col-md-8">
                @component('component.form')
                    @slot('form_id')
                        @if (!empty($user->id))
                            post_forms
                        @else
                            post_form
                        @endif
                    @endslot

                    @slot('title')
                        @if (!empty($post->id))
                            Edit {{ ($os)? 'showroom': 'post' }}
                        @else
                            Add a new {{ ($os)? 'showroom': 'post' }}
                        @endif

                    @endslot

                    @slot('route')
                        @if (!empty($post->id))
                            post/{{$post->id}}/update
                        @else
                            post_save
                        @endif
                    @endslot

                    @slot('fields')
                        <div class="form-group">
                            {{ Form::hidden('user_id', (!empty(\Auth::user()->id) ? \Auth::user()->id : NULL), ['type' => 'hidden']) }}
                            {{ Form::hidden('lang', (!empty($post->lang) ? $post->lang : 'en'), ['type' => 'hidden']) }}
                            {{ Form::label('title', 'Title', array('class' => 'title')) }}
                            {{ Form::text('title', (!empty($post->title) ? $post->title : NULL), ['required', 'class' => 'form-control', 'placeholder' => 'Enter title...']) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('sub_title', 'Sub Title', array('class' => 'sub_title')) }}
                            {{ Form::text('sub_title', (!empty($post->sub_title) ? $post->sub_title : NULL), ['class' => 'form-control', 'placeholder' => 'Enter sub_title...']) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('seo_url', 'Search Engine Friendly URL', array('class' => 'seo_url')) }}
                            {{ Form::text('seo_url', (!empty($post->seo_url) ? $post->seo_url : NULL), ['type' => 'text', 'required', 'class' => 'form-control', 'placeholder' => 'Enter seo URL...']) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('author', 'Author/Contact Person', array('class' => 'author')) }}
                            {{ Form::text('author', (!empty($post->author) ? $post->author : NULL), ['type' => 'text', 'class' => 'form-control', 'placeholder' => 'Enter author...']) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('description', 'Description', array('class' => 'description')) }}
                            {{ Form::textarea('description', (!empty($post->description) ? $post->description : NULL), ['required', 'class' => 'form-control', 'id' => 'wysiwyg', 'placeholder' => 'Enter details content...']) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('images', 'Image ID/s', array('class' => 'images')) }}
                            @if(!empty($post->images))
                                <?php
                                //$im = image_ids($product->images, TRUE, TRUE);
                                $im = $post->images;
                                ?>
                                {{ Form::text('images', (!empty($im) ? $im : NULL), ['type' => 'text', 'id' => 'image_ids', 'class' => 'form-control', 'placeholder' => 'Enter image IDs...']) }}
                            @else
                                {{ Form::text('images', NULL, ['type' => 'text', 'id' => 'image_ids', 'class' => 'form-control', 'placeholder' => 'Enter image IDs...']) }}
                            @endif
                            <small id="show_image_names"></small>

                        </div>
                        <div class="form-group">
                            @if(!empty($post->images))
                                <?php
                                $images = explode(',', $post->images);

                                $html = null;
                                foreach ($images as $image) :
                                    $img = App\Models\Image::find($image);
                                    //dump($img);
                                    $html .= '<img src="' . url($img->icon_size_directory) . '" alt="' . $img->original_name . '" class="margin" style="max-width: 80px; max-height: 80px; border: 1px dotted #ddd;">';
                                    //$html .= '<span>' . $img->id . '</span>';
                                    $html .= '<a href="' . url('delete_attribute', ['id' => $img->id]) . '">x</a>';
                                    //$attributes_p = product_attributes($product, TRUE);
                                endforeach;
                                //die();
                                ?>
                                {!! $html !!}
                            @endif
                        </div>
                        <div class="row">
                            <div class="col-md-6">

                                <div class="nav-tabs-custom">

                                    <ul class="nav nav-tabs">
                                        <li class="active">
                                            <a href="#tab_1" data-toggle="tab" aria-expanded="true">
                                                Basic
                                            </a>
                                        </li>
                                        <li class="">
                                            <a href="#tab_2" data-toggle="tab" aria-expanded="false">
                                                Other
                                            </a>
                                        </li>

                                    </ul>
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="tab_1">


                                            <div class="form-group">
                                                {{ Form::label('youtube', 'Youtube', array('class' => 'youtube')) }}
                                                {{ Form::text('youtube', (!empty($post->youtube) ? $post->youtube : NULL), ['class' => 'form-control', 'placeholder' => 'Enter youtube...']) }}
                                            </div>

                                            <div class="form-group">
                                                {{ Form::label('lang', 'Language', array('class' => 'lang')) }}
                                                <div class="radio">
                                                    <label>
                                                        {{ Form::radio('lang', 1, (!empty($post) ? ((!empty($post->lang) ? $post->lang == 1 : 1) ? TRUE : FALSE) : TRUE), ['class' => 'radio']) }}
                                                        English
                                                    </label>
                                                </div>
                                                <div class="radio">
                                                    <label>
                                                        {{ Form::radio('lang', 0, (!empty($post) ? ((!empty($post->lang) ? $post->lang == 0 : 0) ? FALSE : TRUE) : null), ['class' => 'radio']) }}
                                                        Bengali
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                {{ Form::label('is_auto_post', 'Is facebook auto post?', array('class' => 'is_auto_post')) }}
                                                <div class="radio">
                                                    <label>
                                                        {{ Form::radio('is_auto_post', 1, (!empty($post) ? (($post->is_auto_post == 1) ? TRUE : FALSE) : TRUE), ['class' => 'radio']) }}
                                                        Yes
                                                    </label>
                                                </div>
                                                <div class="radio">
                                                    <label>
                                                        {{ Form::radio('is_auto_post', 0, (!empty($post) ? (($post->is_auto_post == 0) ? TRUE : FALSE) : null), ['class' => 'radio']) }}
                                                        No
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                {{ Form::label('short_description', 'Short Description', array('class' => 'short_description')) }}
                                                {{ Form::textarea('short_description', (!empty($post->short_description) ? $post->short_description : NULL), ['class' => 'form-control', 'placeholder' => 'Enter short description...']) }}
                                            </div>
                                            <div class="form-group">
                                                {{ Form::label('is_upcoming', 'Is it upcoming?', array('class' => 'is_upcoming')) }}
                                                <div class="radio">
                                                    <label>
                                                        {{ Form::radio('is_upcoming', 1, (!empty($post) ? (($post->is_upcoming == 1) ? TRUE : FALSE) : TRUE), ['class' => 'radio']) }}
                                                        Yes
                                                    </label>
                                                </div>
                                                <div class="radio">
                                                    <label>
                                                        {{ Form::radio('is_upcoming', 0, (!empty($post) ? (($post->is_upcoming == 0) ? TRUE : FALSE) : null), ['class' => 'radio']) }}
                                                        No
                                                    </label>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="tab-pane" id="tab_2">

                                            <div class="form-group">
                                                {{ Form::label('brand', 'Brand', array('class' => 'brand')) }}
                                                {{ Form::text('brand', (!empty($post->brand) ? $post->brand : NULL), ['class' => 'form-control', 'placeholder' => 'Enter brand...']) }}
                                            </div>
                                            <div class="form-group">
                                                {{ Form::label('phone', 'Phone', array('class' => 'phone')) }}
                                                {{ Form::text('phone', (!empty($post->phone) ? $post->phone : NULL), ['class' => 'form-control', 'placeholder' => 'Enter phone...']) }}
                                            </div>
                                            <div class="form-group">
                                                {{ Form::label('opening_hours', 'Opening Hours', array('class' => 'opening_hours')) }}
                                                {{ Form::text('opening_hours', (!empty($post->opening_hours) ? $post->opening_hours : NULL), ['class' => 'form-control', 'placeholder' => 'Enter opening hours...']) }}
                                            </div>
                                            <div class="form-group">
                                                {{ Form::label('latitude', 'Latitude', array('class' => 'latitude')) }}
                                                {{ Form::text('latitude', (!empty($post->latitude) ? $post->latitude : NULL), ['class' => 'form-control', 'placeholder' => 'Enter latitude...']) }}
                                            </div>
                                            <div class="form-group">
                                                {{ Form::label('longitude', 'Longitude', array('class' => 'longitude')) }}
                                                {{ Form::text('longitude', (!empty($post->longitude) ? $post->longitude : NULL), ['class' => 'form-control', 'placeholder' => 'Enter longitude...']) }}
                                            </div>
                                            <div class="form-group">
                                                {{ Form::label('phone_numbers', 'Phone Numbers', array('class' => 'phone_numbers')) }}
                                                {{ Form::text('phone_numbers', (!empty($post->phone_numbers) ? $post->phone_numbers : NULL), ['class' => 'form-control', 'placeholder' => 'Enter phone numbers...']) }}
                                            </div>
                                            <div class="form-group">
                                                {{ Form::label('address', 'Address', array('class' => 'address')) }}
                                                {{ Form::textarea('address', (!empty($post->address) ? $post->address : NULL), ['class' => 'form-control', 'placeholder' => 'Enter address...']) }}
                                            </div>

                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-6">

                                <div class="form-group">
                                    {{ Form::label('is_sticky', 'Is it sticky?', array('class' => 'is_sticky')) }}
                                    <div class="radio">
                                        <label>
                                            {{ Form::radio('is_sticky', 1, (!empty($post) ? (($post->is_sticky == 1) ? TRUE : FALSE) : TRUE), ['class' => 'radio']) }}
                                            Yes. This post will be marked as selected top post
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            {{ Form::radio('is_sticky', 0, (!empty($post) ? (($post->is_sticky == 0) ? TRUE : FALSE) : null), ['class' => 'radio']) }}
                                            No. This post will no be marked as selected top post
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('is_active', 'Will it be active?', array('class' => 'is_active')) }}
                                    <div class="radio">
                                        <label>
                                            {{ Form::radio('is_active', 1, (!empty($post) ? (($post->is_active == 1) ? TRUE : FALSE) : TRUE), ['class' => 'radio']) }}
                                            Yes. This post will publish
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            {{ Form::radio('is_active', 0, (!empty($post) ? (($post->is_active == 0) ? TRUE : FALSE) : null), ['class' => 'radio']) }}
                                            No. This post will save as draft
                                        </label>
                                    </div>
                                </div>
                                @if (!$os)
                                <div class="box box-primary">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">Post Categories <span id="photoCounter"></span></h3>

                                        <div class="col-xs-12 post_cats" style="margin-top: 15px;">
                                            <div class="pre-scrollable">
                                                <?php
                                                //dump($categories);

                                                if (!empty($post->categories)) {
                                                    $ids = explode(',', $post->categories);
                                                }
                                                $parent = 2;
                                                $parents = ($parent) ? $parent : NULL;
                                                ?>
                                                {!! category_h_checkbox_html($categories, $parents, $name = 'categories', !empty($ids) ? $ids : array()) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                @if ($os)
                                {{ Form::hidden('categories[]', 651, []) }}
                                @endif



                                <div class="box box-primary">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">Based On Location <span id="photoCounter"></span></h3>
                                        <div class="col-xs-12 post_cats" style="margin-top: 15px;">

                                            <div class="form-group">
                                                {{ Form::label('thana', 'Search from dropdown and select a thana', array('class' => 'thana')) }}
                                                <?php
                                                $thanas = get_thana();
                                                //dd($thanas);
                                                ?>
                                                <select class="form-control select2" style="width: 100%;" name="thana">
                                                    @foreach($thanas as $thana)
                                                        <option
                                                            <?php echo(!empty($post) ? ($post->thana == $thana->thana) ? ' selected="selected" ' : null : null); ?>
                                                            value="{{ $thana->thana }}">
                                                            {{ $thana->thana }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="box box-primary">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">Based On Location <span id="photoCounter"></span></h3>
                                        <div class="col-xs-12 post_cats" style="margin-top: 15px;">

                                            <div class="form-group">
                                                {{ Form::label('thana', 'Search from dropdown and select a shop type', array('class' => 'thana')) }}
                                                <?php $shop_type = get_shop_type() ?>
                                                {{-- {{ dd($shop_type) }} --}}
                                                <select class="form-control select2" style="width: 100%;" name="shop_type">
                                                    @foreach($shop_type as $key => $value)
                                                        <?php //dd($stype); ?>
                                                        <option
                                                            <?php echo(!empty($post) ? ($post->shop_type == $value) ? ' selected="selected" ' : null : null); ?>
                                                            value="{{ $value }}">
                                                            {{ $value }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                        </div>
                                    </div>
                                </div>

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
                            <div class="col-xs-6 col-md-4">
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
        <?php }  ?>
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

        /**
         *
         */
        jQuery(document).ready(function ($) {
            $.noConflict();

            $('#title').blur(function () {
                var m = $(this).val();
                var cute1 = m.toLowerCase().replace(/ /g, '-').replace(/&amp;/g, 'and').replace(/&/g, 'and').replace(/ ./g, 'dec');
                var cute = cute1.replace(/[`~!@#$%^&*()_|+\=?;:'"”,.<>\{\}\[\]\\\/]/gi, '');

                $('#seo_url').val(cute);
            });

        });
    </script>
@endpush
