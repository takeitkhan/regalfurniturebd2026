@extends('layouts.app')

@section('title', 'Categories')
@section('sub_title', 'all category management panel')
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

        @if(!empty($term->id))
            <div class="col-md-12">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="">
                            <a href="{{ url("edit_term/{$term->id}") }}?p=ba"
                               class="{{ (Request::get('p') == 'ba') ? 'tab-active' : 'text-muted' }}">
                                Basic
                            </a>
                        </li>
                        <li class="">
                            <a href="{{ url("edit_term/{$term->id}") }}?p=seo-settings"
                               class="{{ (Request::get('p') == 'seo-settings') ? 'tab-active text-primary' : 'text-muted' }}">
                                Seo Information
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        @endif
        <?php if(!empty($term->id) && Request::get('p') == 'seo-settings') { ?>
            <div class="col-md-8">
                <div class="box box-warning">
                    <div class="box-body">
                        @includeIf('seo.form', [$post_type = 'category', $post_id = $term->id])
                    </div>
                </div>
            </div>
        <?php } else { ?>
            <div class="col-md-8" id="signupForm">

                @component('component.form')
                    @slot('form_id')
                        @if (!empty($term->id))
                            term_form333
                        @else
                            term_form333
                        @endif
                    @endslot
                    @slot('title')
                        Add a new term
                    @endslot

                    @slot('route')
                        @if (!empty($term->id))
                            term/{{$term->id}}/update
                        @else
                            term_save
                        @endif
                    @endslot

                    @slot('fields')
                        <div class="form-group">
                            {{ Form::label('term_name', 'Term Name', array('class' => 'term_name')) }}
                            {{ Form::text('term_name', (!empty($term->name) ? $term->name : NULL), ['required', 'class' => 'form-control', 'placeholder' => 'Enter term name...']) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('seo_url', 'SEO URL', array('class' => 'seo_url')) }}
                            {{ Form::text('seo_url', (!empty($term->seo_url) ? $term->seo_url : NULL), ['required', 'data-type' => (!empty($product->id) ? 'update' : 'create'), 'id' => 'seo_url', 'class' => 'form-control', 'placeholder' => 'Enter seo url...']) }}
                        </div>
                        {{-- <div class="form-group">
                            {{ Form::label('sub_menu_width', 'Sub Menu Width', array('class' => 'sub_menu_width')) }}
                            {{ Form::text('sub_menu_width', (!empty($term->sub_menu_width) ? $term->sub_menu_width : NULL), ['id' => 'sub_menu_width', 'class' => 'form-control', 'placeholder' => 'Enter sub menu width...']) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('column_count', 'How many Columns', array('class' => 'column_count')) }}
                            {{ Form::number('column_count', (!empty($term->column_count) ? $term->column_count : NULL), ['id' => 'column_count', 'max' => 6, 'class' => 'form-control', 'placeholder' => 'Enter column count...']) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('with_sub_menu', 'With sub menu', array('class' => 'with_sub_menu')) }}
                            <select name="with_sub_menu" class="form-control">
                                @if(!empty($term))
                                    <?php $val = (int)$term->with_sub_menu; ?>
                                    <option value="0">Select sub menu</option>
                                    <option value="1" {!! ($val == 1) ? 'selected="selected"' : null !!}>Yes</option>
                                    <option value="0" {!! ($val == 0) ? 'selected="selected"' : null !!}>No</option>
                                @else
                                    <option value="0">Select sub menu</option>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                @endif
                            </select>
                        </div> --}}

                        <div class="form-group">
                            {{ Form::label('description', 'Description', array('class' => 'description')) }}
                            {{ Form::textarea('description', (!empty($term->description) ? $term->description : NULL), ['required', 'class' => 'form-control', 'id' => 'wysiwyg', 'placeholder' => 'Enter details content...']) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('keywords', 'Keywords', array('class' => 'keywords')) }}
                            {{ Form::textarea('term_keywords', (!empty($term->term_keywords) ? $term->term_keywords : NULL), ['required', 'class' => 'form-control', 'id' => 'wysiwyg', 'placeholder' => 'Enter keywords...']) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('seo_h1', 'H1', array('class' => 'seo_h1')) }}
                            {{ Form::textarea('seo_h1', (!empty($term->seo_h1) ? $term->seo_h1 : NULL), ['class' => 'form-control', 'id' => '', 'placeholder' => 'Enter H1 ...', 'rows' => 5]) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('seo_h2', 'H2', array('class' => 'seo_h2')) }}
                            {{ Form::textarea('seo_h2', (!empty($term->seo_h2) ? $term->seo_h2 : NULL), [ 'class' => 'form-control', 'id' => '', 'placeholder' => 'Enter H2 ...', 'rows' => 5]) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('seo_h3', 'H3', array('class' => 'seo_h3')) }}
                            {{ Form::textarea('seo_h3', (!empty($term->seo_h3) ? $term->seo_h3 : NULL), [ 'class' => 'form-control', 'id' => '', 'placeholder' => 'Enter H3 ...', 'rows' => 5]) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('seo_h4', 'H4', array('class' => 'seo_h4')) }}
                            {{ Form::textarea('seo_h4', (!empty($term->seo_h4) ? $term->seo_h4 : NULL), [ 'class' => 'form-control', 'id' => '', 'placeholder' => 'Enter H4 ...', 'rows' => 5]) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('seo_h5', 'H5', array('class' => 'seo_h5')) }}
                            {{ Form::textarea('seo_h5', (!empty($term->seo_h5) ? $term->seo_h5 : NULL), [ 'class' => 'form-control', 'id' => '', 'placeholder' => 'Enter H5 ...', 'rows' => 5]) }}
                        </div>

                        {{-- <div class="form-group">
                            {{ Form::label('term_css_class', 'Term CSS Class', array('class' => 'term_css_class')) }}
                            {{ Form::text('term_css_class', (!empty($term->cssclass) ? $term->cssclass : NULL), ['required', 'class' => 'form-control', 'placeholder' => 'Enter term css class. Use space for multiple class...']) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('term_css_id', 'Term CSS ID', array('class' => 'term_id')) }}
                            {{ Form::text('term_css_id', (!empty($term->cssid) ? $term->cssid : NULL), ['class' => 'form-control', 'placeholder' => 'Enter term single css ID...']) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('term_menu_icon', 'Term Menu Icon', array('class' => 'term_menu_icon')) }}
                            {{ Form::text('term_menu_icon', (!empty($term->term_menu_icon) ? $term->term_menu_icon : NULL), ['class' => 'form-control', 'placeholder' => 'icons']) }}
                        </div> --}}
                        {{-- <div class="form-group">
                            {{ Form::label('term_menu_arrow', 'Term Menu Arrow', array('class' => 'term_menu_arrow')) }}
                            {{ Form::text('term_menu_arrow', (!empty($term->term_menu_arrow) ? $term->term_menu_arrow : NULL), ['class' => 'form-control', 'placeholder' => 'Enter term menu arrow...']) }}
                        </div> --}}
                        <div class="form-group">
                            {{ Form::label('page_image', 'Term Page Image', array('class' => 'page_image')) }}
                            {{ Form::text('page_image', (!empty($term->page_image) ? $term->page_image : NULL), ['class' => 'form-control', 'placeholder' => 'Enter term page image...']) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('home_image', 'Term Home Image', array('class' => 'home_image')) }}
                            {{ Form::text('home_image', (!empty($term->home_image) ? $term->home_image : NULL), ['class' => 'form-control', 'placeholder' => 'Enter term home image...']) }}
                        </div>
                        <fieldset>
                            {{-- <legend>Banner Aria:</legend> --}}
                          <div class="form-group">
                                {{ Form::label('bannar1', 'Term Banner', array('class' => 'banner1')) }}
                                {{ Form::text('banner1', (!empty($term->banner1) ? $term->banner1 : NULL), ['class' => 'form-control', 'placeholder' => 'Enter an image id...']) }}
                            </div>
                            {{--   <div class="form-group">
                                {{ Form::label('bacnner', 'Banner 02', array('class' => 'banner2')) }}
                                {{ Form::text('banner2', (!empty($term->banner2) ? $term->banner2 : NULL), ['class' => 'form-control', 'placeholder' => 'Enter term home image...']) }}
                            </div> --}}
                        </fieldset>

                            <div class="form-group">
                                {{ Form::label('special_notification', 'Special Noification', array('class' => 'special_notification')) }}
                                {{ Form::textarea('special_notification', (!empty($term->special_notification) ? $term->special_notification : NULL), ['class' => 'form-control', 'placeholder' => '']) }}
                            </div>

                        <div class="form-group">
                            {{ Form::label('term_position', 'Term Position', array('class' => 'term_position')) }}
                            <?php
                            $info = App\Models\Term::latest()->first();
                            if (!empty($info)) {
                                $id = $info->id + 1;
                            } else {
                                $id = 1;
                            }

                            ?>
                            {{ Form::text('term_position', (!empty($term->position) ? $term->position : $id), ['class' => 'form-control', 'placeholder' => 'Enter term name...', 'readonly' => 'readonly']) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('term_parent', 'Term Parent', array('class' => 'term_parent')) }}

                            {{--{{ Form::select('term_parent', $cats, (!empty($term->parent) ? $term->parent : ''), array('id' => 'term_parent', 'class' => 'form-control')) }}--}}
                            <?php
                            //owndebugger($cats['data']); exit;
                            $categories = $cats['data'];
                            ?>
                            <select name="term_parent" class="form-control">
                                <option value="">Select a parent</option>
                                {!! select_option_html($categories, $parent = 0, ' ', (!empty($term->parent) ? $term->parent : NULL), FALSE )  !!}
                            </select>
                        </div>

                        <div class="form-group">
                            {{ Form::label('connected_with', 'Connectivity with attribute group', array('class' => 'connected_with')) }}

                            {{--{{ Form::select('term_parent', $cats, (!empty($term->parent) ? $term->parent : ''), array('id' => 'term_parent', 'class' => 'form-control')) }}--}}
                            <?php
                            $attgroups = App\Models\Attgroup::where('is_active', 1)->get();
                            ?>
                            <select name="connected_with" class="form-control">
                                <option value="">Select a attribute group</option>
                                @foreach($attgroups as $attgroup)
                                    @if(!empty($term) && $attgroup->id == $term->connected_with)
                                        @php
                                            $selected = 'selected="selected"';
                                        @endphp
                                    @else
                                        @php
                                            $selected = '';
                                        @endphp
                                    @endif

                                    <option value="{{ $attgroup->id }}" {{ $selected }}>{{ $attgroup->group_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- textarea -->
                        <div class="form-group">
                            {{ Form::label('is_published', 'Publish or Not', array('class' => 'is_published')) }}
                            {{ Form::select('is_published', [1 => 'Yes', 0 => 'No'], (!empty($term->is_published) ? $term->is_published : 0), ['class' => 'form-control']) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('in_product_home', 'Publish In Product home ?', array('class' => 'in_product_home')) }}
                            {{ Form::select('in_product_home', [1 => 'Yes', 0 => 'No'], (!empty($term->in_product_home) ? $term->in_product_home : 0), ['class' => 'form-control']) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('term_type', 'Term Type', array('class' => 'term_type')) }}
                            {{ Form::select('term_type', ['category' => 'Category', 'others' => 'Others'], (!empty($term->type) ? $term->type : NULL), ['class' => 'form-control']) }}
                        </div>
                    @endslot
                @endcomponent
            </div>
            <div class="col-md-4">

                <div class="box box-success">
                    <div class="box-header with-border flex ">
                        <h3 class="box-title box-success ">Categories
                            <a href="{{ url('terms') }}" class="btn btn-xs btn-success">
                                <i class="fa fa-plus"></i>
                            </a>
                        </h3>


                        <h3 class="box-title box-success ">
                            <a href="{{ url('terms/serialise') }}" class="btn btn-xs btn-success">
                                Categorie Serialize
                            </a>
                        </h3>

                        <div class="box-tools">
                            <div class="input-group input-group-sm" style="width: 150px;">
                                <input type="text" value="" id="getNameForSearch" onkeyup="searchTheCategory()" class="form-control pull-right" placeholder="Search">

                                <div class="input-group-btn">
                                    <button type="submit" onclick="searchTheCategory()" class="btn btn-default"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-body" style="max-height: 350px; overflow-x: scroll;">
                        {!! select_option_html($categories, $parent = 0,'', (!empty($term->parent) ? $term->parent : NULL), TRUE )  !!}
                    </div>
                    <div class="box-footer clearfix">
                        {{--{{ $terms->links('component.paginator', ['object' => $terms]) }}--}}
                    </div>
                </div>
            </div>
        <?php }  ?>
    </div>
@endsection

@push('scripts')

    <script>

        function searchTheCategory() {
            var result = [];
            var SearchName = jQuery("#getNameForSearch").val();

            jQuery(".on_terms li").each((id, elem) => {

                let elemTxt = elem.innerText.split(" ").filter(Boolean)

                const newEl = elemTxt.filter(function( item ){

                    return item.toLowerCase().includes(SearchName.toLowerCase())
                })

                if(newEl.length > 0){
                    jQuery(elem).css('display','block')
                }else{
                    jQuery(elem).css('display','none')
                }

            });

        }

        <?php if(!empty($term->id)) { ?>

        <?php } else { ?>

        jQuery(document).ready(function ($) {
            $.noConflict();

            $('#term_name').blur(function () {
                var m = $(this).val();
                var cute1 = m.toLowerCase().replace(/ /g, '-').replace(/&amp;/g, 'and').replace(/&/g, 'and').replace(/ ./g, 'dec');
                var cute = cute1.replace(/[`~!@#$%^&*()_|+\=?;:'"”,.<>\{\}\[\]\\\/]/gi, '');

                $('#term_css_class, #term_css_id, #seo_url').val(cute);
            });


            $('#term_name').blur(function () {
                var seo_url = $('#seo_url').val();
                var type = $('#seo_url').data('type');

                if (type == 'create') {
                    var data = {
                        'seo_url': seo_url
                    };

                    //console.log(data);

                    jQuery.ajax({
                        url: baseurl + '/check_if_cat_url_exists',
                        method: 'get',
                        data: data,
                        success: function (data) {
                            $('#seo_url').val(data.url);
                        },
                        error: function () {
                            // showError('Sorry. Try reload this page and try again.');
                            // processing.hide();
                        }
                    });
                }

            });
        });
        <?php } ?>


    </script>
    <style type="text/css">
        ul.on_terms {
            margin: 0;
            padding-left: 20px;
        }

        ul.on_terms li {
            border: 1px solid #EEE;
            margin: 2px;
            padding: 3px;
            border-right: 0;
            border-top: 0;
            border-left: 0;
        }
    </style>
@endpush
