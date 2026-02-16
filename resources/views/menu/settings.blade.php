@extends('layouts.app')

@section('title', 'Global Settings')
@section('sub_title', 'global settings modification panel')
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
                        setting/{{$setting->id}}/update
                    @else
                        setting_save
                    @endif
                @endslot

                @slot('fields')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('com_name', 'Company Name', array('class' => 'name')) }}
                                {{ Form::text('com_name', (!empty($setting->com_name) ? $setting->com_name : NULL), ['required', 'class' => 'form-control', 'placeholder' => 'Enter name...']) }}
                            </div>
                            <div class="form-group">
                                {{ Form::label('slogan', 'Company Slogan', array('class' => 'slogan')) }}
                                {{ Form::text('com_slogan', (!empty($setting->com_slogan) ? $setting->com_slogan : NULL), ['required', 'class' => 'form-control', 'placeholder' => 'Enter company slogan...']) }}
                            </div>
                            <div class="form-group">
                                {{ Form::label('eshtablished', 'Company Eshtablished', array('class' => 'eshtablished')) }}
                                {{ Form::text('com_eshtablished', (!empty($setting->com_eshtablished) ? $setting->com_eshtablished : NULL), ['required', 'class' => 'form-control', 'placeholder' => 'Enter company eshtablished...']) }}
                            </div>
                            <div class="form-group">
                                {{ Form::label('licensecode', 'Company License Code', array('class' => 'licensecode')) }}
                                {{ Form::text('com_licensecode', (!empty($setting->com_licensecode) ? $setting->com_licensecode : NULL), ['required', 'class' => 'form-control', 'placeholder' => 'Enter company license code...']) }}
                            </div>
                            <div class="form-group">
                                {{ Form::label('logo', 'Logo URL', array('class' => 'logo')) }}
                                {{ Form::url('com_logourl', (!empty($setting->com_logourl) ? $setting->com_logourl : NULL), ['required', 'class' => 'form-control', 'placeholder' => 'Enter logo URL...']) }}
                            </div>
                            <div class="form-group">
                                {{ Form::label('header', 'Header URL', array('class' => 'header')) }}
                                {{ Form::url('com_headerurl', (!empty($setting->com_headerurl) ? $setting->com_headerurl : NULL), ['required', 'class' => 'form-control', 'placeholder' => 'Enter header URL...']) }}
                            </div>
                            <div class="form-group">
                                {{ Form::label('header_bg', 'Header Backgroun Image URL', array('class' => 'header_bg')) }}
                                {{ Form::url('header_bg', (!empty($setting->header_bg) ? $setting->header_bg : NULL), ['class' => 'form-control', 'placeholder' => 'Enter Header Backgroun Image URL...']) }}
                            </div>
                            <div class="form-group">
                                {{ Form::label('phone', 'Company Phone', array('class' => 'phone')) }}
                                {{ Form::text('com_phone', (!empty($setting->com_phone) ? $setting->com_phone : NULL), [ 'class' => 'form-control', 'placeholder' => 'Enter company phone...']) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('order_phone', 'Order By Phone', array('class' => 'order_phone')) }}
                                {{ Form::text('order_phone', (!empty($setting->order_phone) ? $setting->order_phone : NULL), ['class' => 'form-control', 'placeholder' => 'Enter order phone...']) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('email', 'Company Email', array('class' => 'email')) }}
                                {{ Form::text('com_email', (!empty($setting->com_email) ? $setting->com_email : NULL), ['required', 'class' => 'form-control', 'placeholder' => 'Enter company email...']) }}
                            </div>
                            <div class="form-group">
                                {{ Form::label('address', 'Address', array('class' => 'address')) }}
                                {{ Form::text('com_address', (!empty($setting->com_address) ? $setting->com_address : NULL), ['required', 'class' => 'form-control', 'placeholder' => 'Enter address...']) }}
                            </div>
                            <div class="form-group">
                                {{ Form::label('workinghours', 'Company Working Hours', array('class' => 'workinghours')) }}
                                {{ Form::text('com_workinghours', (!empty($setting->com_workinghours) ? $setting->com_workinghours : NULL), ['required', 'class' => 'form-control', 'placeholder' => 'Enter company working hours...']) }}
                            </div>
                            <div class="form-group">
                                {{ Form::label('adminphoto', 'Admin Photo URL', array('class' => 'adminphoto')) }}
                                {{ Form::url('com_adminphotourl', (!empty($setting->com_adminphotourl) ? $setting->com_adminphotourl : NULL), ['required', 'class' => 'form-control', 'placeholder' => 'Enter amin photo URL...']) }}
                            </div>
                            <div class="form-group">
                                {{ Form::label('adminname', 'Admin Name', array('class' => 'adminname')) }}
                                {{ Form::text('com_adminname', (!empty($setting->com_adminname) ? $setting->com_adminname : NULL), ['required', 'class' => 'form-control', 'placeholder' => 'Enter admin name...']) }}
                            </div>
                            <div class="form-group">
                                {{ Form::label('adminphone', 'Admin Phone', array('class' => 'adminphone')) }}
                                {{ Form::text('com_adminphone', (!empty($setting->com_adminphone) ? $setting->com_adminphone : NULL), ['required', 'class' => 'form-control', 'placeholder' => 'Enter admin phone...']) }}
                            </div>
                            <div class="form-group">
                                {{ Form::label('adminemail', 'Admin Email', array('class' => 'adminemail')) }}
                                {{ Form::email('com_adminemail', (!empty($setting->com_adminemail) ? $setting->com_adminemail : NULL), ['required', 'class' => 'form-control', 'placeholder' => 'Enter email...']) }}
                            </div>
                            <div class="form-group">
                                {{ Form::label('website', 'Website', array('class' => 'website')) }}
                                {{ Form::text('com_website', (!empty($setting->com_website) ? $setting->com_website : NULL), ['required', 'class' => 'form-control', 'placeholder' => 'Enter website...']) }}
                            </div>
                            <div class="form-group">
                                {{ Form::label('facebookpageid', 'Facebook Page ID', array('class' => 'facebookpageid')) }}
                                {{ Form::text('com_facebookpageid', (!empty($setting->com_facebookpageid) ? $setting->com_facebookpageid : NULL), ['required', 'class' => 'form-control', 'placeholder' => 'Enter facebook page id...']) }}
                            </div>
                            <div class="form-group">
                                {{ Form::label('favicon', 'Favicon URL', array('class' => 'favicon')) }}
                                {{ Form::text('com_favicon', (!empty($setting->com_favicon) ? $setting->com_favicon : NULL), ['required', 'class' => 'form-control', 'placeholder' => 'Enter favicon URL...']) }}
                            </div>
                            <div class="form-group">
                                {{ Form::label('timezone', 'Timezone (Asia/Dhaka)', array('class' => 'timezone')) }}
                                {{ Form::text('com_timezone', (!empty($setting->com_timezone) ? $setting->com_timezone : NULL), ['required', 'class' => 'form-control', 'placeholder' => 'Enter timezone...']) }}
                            </div>


                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="showroom_location_popup">Location Showroom Popup</label>
                                @php
                                    $showroom_location_popup = ['show' => 'Show', 'hide' => 'Hide'];
                                @endphp

                                <div class="radio">
                                    @foreach($showroom_location_popup as $index => $item)
                                    <label style="margin: 0px 5px;">
                                        <input type="radio" name="showroom_location_popup" id="" value="{{$index}}"
                                            {{!empty($setting) && $setting->showroom_location_popup == $index ? 'checked' : null}}
                                        >
                                        {{$item}}
                                    </label>
                                    @endforeach
                                </div>


                            </div>
                            <?php /*
                            <div class="form-group">
                                {{ Form::label('special_notification_product_single_page', 'Special Notification for Product Single Page', array('class' => 'special_notification_product_single_page')) }}
                                {{ Form::textarea('special_notification_product_single_page', (!empty($setting->special_notification_product_single_page) ? $setting->special_notification_product_single_page : NULL), ['class' => 'form-control', 'placeholder' => '...']) }}
                            </div>
                            */ ?>
                            <div class="form-group">
                                {{ Form::label('metatitle', 'Meta Title', array('class' => 'metatitle')) }}
                                {{ Form::text('com_metatitle', (!empty($setting->com_metatitle) ? $setting->com_metatitle : NULL), ['required', 'class' => 'form-control', 'placeholder' => 'Enter meta title...']) }}
                            </div>
                            <div class="form-group">
                                {{ Form::label('metadescription', 'Meta Description', array('class' => 'metadescription')) }}
                                {{ Form::textarea('com_metadescription', (!empty($setting->com_metadescription) ? $setting->com_metadescription : NULL), ['required', 'class' => 'form-control', 'placeholder' => 'Enter meta description...']) }}
                            </div>
                            <div class="form-group">
                                {{ Form::label('metakeywords', 'Meta Keywords', array('class' => 'metakeywords')) }}
                                {{ Form::textarea('com_metakeywords', (!empty($setting->com_metakeywords) ? $setting->com_metakeywords : NULL), ['required', 'class' => 'form-control', 'placeholder' => 'Enter meta keywords...']) }}
                            </div>
                            <div class="form-group">
                                {{ Form::label('googlemap', 'Google Map Code', array('class' => 'googlemap')) }}
                                {{ Form::textarea('com_addressgooglemap', (!empty($setting->com_addressgooglemap) ? $setting->com_addressgooglemap : NULL), ['class' => 'form-control', 'placeholder' => 'Enter google map code...']) }}
                            </div>
                            <div class="form-group">
                                {{ Form::label('analytics', 'Google Analytics Code', array('class' => 'analytics')) }}
                                {{ Form::textarea('com_analytics', (!empty($setting->com_analytics) ? $setting->com_analytics : NULL), ['class' => 'form-control', 'placeholder' => 'Enter google analytics code...']) }}
                            </div>
                            <div class="form-group">
                                {{ Form::label('chat_box', 'Chat Box Script', array('class' => 'chat_box')) }}
                                {{ Form::textarea('com_chat_box', (!empty($setting->com_chat_box) ? $setting->com_chat_box : NULL), ['class' => 'form-control', 'placeholder' => 'Enter chat box code...']) }}
                            </div>




                        </div>
                    </div>
                @endslot
            @endcomponent
        </div>
    </div>
@endsection
