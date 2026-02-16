<footer class="footer-container typefooter-1 bg-footer-s">
    <div class="footer-top">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">

                    @php
                        $static_cats = dynamic_widget($widgets, ['id' => 10]);
                    @endphp
                    {!! $static_cats !!}

                </div>

                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="module newsletter-footer1">
                        <div class="newsletter">

                            <div class="title-block hidden-sm hidden-md">
                                <div class="page-heading font-title ">
                                    Signup for Newsletter
                                </div>

                            </div>

                            <div class="block_content block_content1">
                                {{ Form::open(array('url' => 'subscribe_email', 'class' => 'form-group form-inline signup send-mail', 'method' => 'post', 'value' => 'PATCH', 'id' => 'subscribe')) }}
                                <div class="redio-btm-area">
                                    <input type="radio" id="test1" name="gender" value="Male" checked>
                                    <label for="test1">Male</label>
                                    <br>
                                    <input type="radio" id="test2" name="gender" value="Female">
                                    <label for="test2">Female</label>
                                </div>
                                <div class="form-group">
                                    <div class="input-box">
                                        <input type="email" placeholder="Your email address..." value=""
                                               class="form-control" id="txtemail" name="email_address" size="55">
                                    </div>
                                    <div class="subcribe">
                                        <button class="btn btn-primary btn-default font-title" type="submit"
                                                onclick="return subscribe_newsletter();" name="submit">
                                            Subscribe
                                        </button>
                                    </div>
                                </div>
                                {{ Form::close() }}
                            </div>
                            <!--/.modcontent-->

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-middle ">
        <div class="container">
            <div class="row">
                <div class="col-lg-2 col-md-4 col-sm-4 col-xs-6">
                    {{-- <div class="col-lg-2 col-md-4 col-sm-4 col-xs-6 col-infos"> --}}
                    <div class="infos-footer">
                        <a href="{{ url('/') }}">
                            <img src="{{ $setting->com_logourl }}"
                                 title="{{ $setting->com_name }}"
                                 alt="{{ $setting->com_name }}"/>
                        </a>

                        <?php $static_cats = dynamic_widget($widgets, ['id' => 9]); ?>
                        {!! $static_cats !!}

                    </div>
                </div>

                <div class="col-lg-2 col-md-4 col-sm-4 col-xs-6 col-style">
                    <div class="box-information box-footer">
                        <div class="module clearfix">
                            <h3 class="modtitle">Information</h3>
                            <div class="modcontent">
                                <?php $parent_items = get_parent_menus(3); ?>
                                <ul class="menu">
                                    @if(isset($parent_items))
                                        @foreach($parent_items as $parent)
                                            <li>
                                                <a href="{{ $parent->link }}">{{ $parent->label }}</a>
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2 col-md-4 col-sm-4 col-xs-6 col-style">
                    <div class="box-account box-footer">
                        <div class="module clearfix">
                            <h3 class="modtitle">Help</h3>
                            <div class="modcontent">
                                <?php $parent_items = get_parent_menus(8); ?>
                                <ul class="menu">
                                    @if(isset($parent_items))
                                        @foreach($parent_items as $parent)
                                            <li>
                                                <a href="{{ $parent->link }}">{{ $parent->label }}</a>
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2 col-md-4 col-sm-4 col-xs-6 col-clear">
                    <div class="box-service box-footer">
                        <div class="module clearfix">
                            <h3 class="modtitle">Policy</h3>
                            <div class="modcontent">
                                <?php $parent_items = get_parent_menus(5); ?>
                                <ul class="menu">
                                    @if(isset($parent_items))
                                        @foreach($parent_items as $parent)
                                            <li>
                                                <a href="{{ $parent->link }}">{{ $parent->label }}</a>
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2 col-md-4 col-sm-4 col-xs-6 col-style">
                    <div class="box-service box-footer">
                        <div class="module clearfix">
                            <h3 class="modtitle">Others</h3>
                            <div class="modcontent">
                                <?php $parent_items = get_parent_menus(6); ?>
                                <ul class="menu">
                                    @if(isset($parent_items))
                                        @foreach($parent_items as $parent)
                                            <li>
                                                <a href="{{ $parent->link }}">{{ $parent->label }}</a>
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-4 col-xs-6 col-style">
                    <div class="box-service box-footer">
                        <div class="module clearfix">
                            <h3 class="modtitle">Let Us Help You</h3>
                            <div class="modcontent">
                                <?php $parent_items = get_parent_menus(7); ?>
                                <ul class="menu">
                                    @if(isset($parent_items))
                                        @foreach($parent_items as $parent)
                                            <li>
                                                <a href="{{ $parent->link }}">{{ $parent->label }}</a>
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <?php $static_cats = dynamic_widget($widgets, ['id' => 11]); ?>
    {!! $static_cats !!}
    <div class="back-to-top"><i class="fa fa-angle-up"></i></div>
</footer>


<div class="modal fade" id="getCodeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel"> New Added Product </h4>
            </div>
            <div class="modal-body" id="getCode" style="overflow-x: auto;">
            </div>
        </div>
    </div>
</div>

<?php
$static_cats = widget_params($widgets, ['id' => 20]);
//dump($static_cats);
?>
@if($static_cats->is_active)
    <div id="arafta" class="modal fade" role="dialog">
        <div class="modal-dialog arafartgvwlg">
            <div class="arafas-btn">
                <a type="button" class="close" data-dismiss="modal">
                    &times;
                </a>
            </div>
            <div class="arafat-modul">
                {!! $static_cats->description !!}
            </div>
        </div>
    </div>
@endif


{{-- <div class="modal hide fade" id="myModal">
   <div class="modal-header">
       <a class="close" data-dismiss="modal">×</a>
       <h3>Modal header</h3>
   </div>
   <div class="modal-body">
       <p>One fine body…</p>
   </div>
   <div class="modal-footer">
       <a href="#" class="btn">Close</a>
       <a href="#" class="btn btn-primary">Save changes</a>
   </div>
</div> --}}