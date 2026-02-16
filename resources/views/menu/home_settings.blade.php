@extends('layouts.app')

@section('title', 'Home Settings')
@section('sub_title', 'home settings modification panel')
@section('content')
    <div class="row">
        @if(!empty($settings))
            <?php //owndebugger($settings); ?>
            <?php $setting = $settings[0]; ?>
        @endif
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

        <div class="col-md-12">
            @component('component.form')
                @slot('form_id')
                    @if (!empty($setting->id))
                        setting_forms
                    @else
                        setting_form
                    @endif
                @endslot
                @slot('title')
                    @if (!empty($setting->id))
                        Edit setting
                    @else
                        Add a new setting
                    @endif

                @endslot

                @slot('route')
                    @if (!empty($setting->id))
                        homesetting/{{$setting->id}}/update
                    @else
                        homesetting_save
                    @endif
                @endslot

                @slot('fields')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('cat_first', 'Category First Row', array('class' => 'cat_first')) }}
                                {{ Form::text('cat_first', (!empty($setting->cat_first) ? $setting->cat_first : NULL), ['required', 'class' => 'form-control', 'placeholder' => 'Enter category setting...']) }}
                                <small style="color: gray; font-style: italic;">Pattern: CatID|ImageAlignment|LimitOfProduct</small>
                            </div>
                            <div class="form-group">
                                {{ Form::label('cat_second', 'Category Second Row', array('class' => 'cat_second')) }}
                                {{ Form::text('cat_second', (!empty($setting->cat_second) ? $setting->cat_second : NULL), ['required', 'class' => 'form-control', 'placeholder' => 'Enter category setting...']) }}
                                <small style="color: gray; font-style: italic;">Pattern: CatID|ImageAlignment|LimitOfProduct</small>
                            </div>
                            <div class="form-group">
                                {{ Form::label('cat_third', 'Category Third Row', array('class' => 'cat_third')) }}
                                {{ Form::text('cat_third', (!empty($setting->cat_third) ? $setting->cat_third : NULL), ['required', 'class' => 'form-control', 'placeholder' => 'Enter category setting...']) }}
                                <small style="color: gray; font-style: italic;">Pattern: CatID|ImageAlignment|LimitOfProduct</small>
                            </div>
                            <div class="form-group">
                                {{ Form::label('cat_fourth', 'Category Fourth Row', array('class' => 'cat_fourth')) }}
                                {{ Form::text('cat_fourth', (!empty($setting->cat_fourth) ? $setting->cat_fourth : NULL), ['class' => 'form-control', 'placeholder' => 'Enter category setting...']) }}
                                <small style="color: gray; font-style: italic;">Pattern: CatID|ImageAlignment|LimitOfProduct</small>
                            </div>
                            <div class="form-group">
                                {{ Form::label('cat_fifth', 'Category Fifth Row', array('class' => 'cat_fifth')) }}
                                {{ Form::text('cat_fifth', (!empty($setting->cat_fifth) ? $setting->cat_fifth : NULL), ['class' => 'form-control', 'placeholder' => 'Enter category setting...']) }}
                                <small style="color: gray; font-style: italic;">Pattern: CatID|ImageAlignment|LimitOfProduct</small>
                            </div>
                            <div class="form-group">
                                {{ Form::label('cat_sixth', 'Category Sixth Row', array('class' => 'cat_sixth')) }}
                                {{ Form::text('cat_sixth', (!empty($setting->cat_sixth) ? $setting->cat_sixth : NULL), ['class' => 'form-control', 'placeholder' => 'Enter category setting...']) }}
                                <small style="color: gray; font-style: italic;">Pattern: CatID|ImageAlignment|LimitOfProduct</small>
                            </div>
                            <div class="form-group">
                                {{ Form::label('cat_seventh', 'Category Seventh Row', array('class' => 'cat_seventh')) }}
                                {{ Form::text('cat_seventh', (!empty($setting->cat_seventh) ? $setting->cat_seventh : NULL), ['class' => 'form-control', 'placeholder' => 'Enter category setting...']) }}
                                <small style="color: gray; font-style: italic;">Pattern: CatID|ImageAlignment|LimitOfProduct</small>
                            </div>
                            <div class="form-group">
                                {{ Form::label('cat_eighth', 'Category Eighth Row', array('class' => 'cat_eighth')) }}
                                {{ Form::text('cat_eighth', (!empty($setting->cat_eighth) ? $setting->cat_eighth : NULL), ['class' => 'form-control', 'placeholder' => 'Enter category setting...']) }}
                                <small style="color: gray; font-style: italic;">Pattern: CatID|ImageAlignment|LimitOfProduct</small>
                            </div>
                            <div class="form-group">
                                {{ Form::label('flash_banner', 'Flash Banner', array('class' => 'flash_banner')) }}
                                {{ Form::textarea('flash_banner', (!empty($setting->flash_banner) ? $setting->flash_banner : NULL), ['class' => 'form-control', 'rows' => 5,'placeholder' => 'Enter Short Code']) }}
                                <small style="color: gray; font-style: italic;">Pattern: [ "name" :"Flash Sale", "img" :  "img_id",   "link" : "url"]</small>
                            </div>
                            
                            <div class="form-group">
                                {{ Form::label('main_slider', 'Main Slider', array('class' => 'main_slider')) }}
                                {{ Form::textarea('main_slider', (!empty($setting->main_slider) ? $setting->main_slider : NULL), ['class' => 'form-control', 'rows' => 5,'placeholder' => 'Enter Short Code']) }}
                                <small style="color: gray; font-style: italic;">Pattern: [ "img" :  "img_id",   "link" : "url"]</small>
                            </div>

                            <div class="form-group">
                                {{ Form::label('home_slider', 'Home Slider', array('class' => 'home_slider')) }}
                                {{ Form::textarea('home_slider', (!empty($setting->home_slider) ? $setting->home_slider : NULL), ['class' => 'form-control', 'rows' => 5,'placeholder' => 'Enter Short Code']) }}
                                <small style="color: gray; font-style: italic;">Pattern: [ "img" :  "img_id",   "link" : "url"]</small>
                            </div>

                            <div class="form-group">
                                {{ Form::label('home_banner_one', 'Home Banner One', array('class' => 'home_banner_one')) }}
                                {{ Form::textarea('home_banner_one', (!empty($setting->home_banner_one) ? $setting->home_banner_one : NULL), ['class' => 'form-control', 'rows' => 5,'placeholder' => 'Enter Short Code']) }}
                                <small style="color: gray; font-style: italic;">Pattern: [ "img" :  "img_id",   "link" : "url"]</small>
                            </div>

                            <div class="form-group">
                                {{ Form::label('home_banner_two', 'Home Banner Two', array('class' => 'home_banner_two')) }}
                                {{ Form::textarea('home_banner_two', (!empty($setting->home_banner_two) ? $setting->home_banner_two : NULL), ['class' => 'form-control', 'rows' => 5,'placeholder' => 'Enter Short Code']) }}
                                <small style="color: gray; font-style: italic;">Pattern: [ "img" :  "img_id",   "link" : "url"]</small>
                            </div>
                            <div class="form-group">
                                {{ Form::label('home_banner_three', 'Home Banner Three', array('class' => 'home_banner_three')) }}
                                {{ Form::textarea('home_banner_three', (!empty($setting->home_banner_three) ? $setting->home_banner_three : NULL), ['class' => 'form-control', 'rows' => 5,'placeholder' => 'Enter Short Code']) }}
                                <small style="color: gray; font-style: italic;">Pattern: [ "img" :  "img_id",   "link" : "url"]</small>
                            </div>

                            <div class="form-group">
                                {{ Form::label('home_brand', 'Brand Slider', array('class' => 'home_brand')) }}
                                {{ Form::textarea('home_brand', (!empty($setting->home_brand) ? $setting->home_brand : NULL), ['class' => 'form-control', 'rows' => 5,'placeholder' => 'Enter Short Code']) }}
                                <small style="color: gray; font-style: italic;">Pattern: [ "img" :  "img_id",   "link" : "url"]</small>
                            </div>

                            <div class="form-group">
                                {{ Form::label('home_category', 'Home Category', array('class' => 'home_category')) }}
                                {{ Form::textarea('home_category', (!empty($setting->home_category) ? $setting->home_category : NULL), ['class' => 'form-control', 'rows' => 3,'placeholder' => 'Enter category ID ...']) }}
                                <small style="color: gray; font-style: italic;">Pattern: CatID|CatID.....</small>
                            </div>
                            <div class="form-group">
                                {{ Form::label('explore_products', 'Explore Products', array('class' => 'explore_products')) }}
                                {{ Form::textarea('explore_products', (!empty($setting->explore_products) ? $setting->explore_products : NULL), ['class' => 'form-control', 'rows' => 3,'placeholder' => 'Enter explore products ...']) }}
                                <small style="color: gray; font-style: italic;">Pattern: CatID|CatID.....</small>
                            </div>
                        </div>
                    </div>
                @endslot
            @endcomponent
        </div>

            
    </div>
@endsection