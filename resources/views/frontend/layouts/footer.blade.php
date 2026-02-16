<footer>
    <div class="footer-top hide-dt text-center section-padding">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="single-footer">
                        <div class="footer-title">
                            <h3>SUPPORT</h3>
                        </div>
                        <div class="footer-list">
                            @php
                                //dump($widgets);
                                $widget = dynamic_widget($widgets, ['id' => 1]);
                            @endphp
                            {!! $widget !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- This area hide-mobile-menu 550px start-->
    <div class="footer-top footer-top_none section-padding">
        <div class="container">
            <div class="row">

                <div class="pt-2">
                    <div class="single-footer">
                        <div class="footer-title">
                            <h3>SUPPORT</h3>
                        </div>
                        <div class="footer-list">
                            @php
                                //dump($widgets);
                                $widget = dynamic_widget($widgets, ['id' => 1]);
                            @endphp
                            {!! $widget !!}
                        </div>
                    </div>
                </div>


                <div class="pt-2">
                    <div class="single-footer">
                        <div class="footer-title">
                            <h3>QUICK NAVIGATION</h3>
                        </div>
                        <div class="footer-list">
                            <?php $parent_items = get_parent_menus(9); ?>
                            <ul class="list-unstyled">

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
                <div class="pt-2">
                    <div class="single-footer">
                        <div class="footer-title">
                            <h3>KNOWLEDGE BASE</h3>
                        </div>
                        <div class="footer-list">


                            <?php $parent_items = get_parent_menus(10); ?>
                            <ul class="list-unstyled">

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
                <div class="pt-2">
                    <div class="single-footer">
                        <div class="footer-title">
                            <h3>INFORMATION</h3>
                        </div>
                        <div class="footer-list">

                            <?php $parent_items = get_parent_menus(3); ?>
                            <ul class="list-unstyled">

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
                <div class="pt-2">
                    <div class="single-footer">
                        <div class="footer-title">
                            <h3>OTHERS</h3>
                        </div>
                        <div class="footer-list">
                            <?php $parent_items = get_parent_menus(6); ?>
                            <ul class="list-unstyled">

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
    <!-- This area hide-mobile-menu 550px  end-->

    <div class="footer-btm">
        <div class="container">
            <div class="row">
                <!--       <div class="col-md-2">
                        <div class="message-option">
                          <a> <i></i> </a>
                        </div>
                      </div> -->
                <div class="col-md-7">
                    <div class="copy-right">
                        <p>
                            Copyright © 2019 Regal Furniture. All right reserved.<!-- |
                            Developed by <a href="https://tritiyo.com/" target=”_blank”>
                                Tritiyo Limited
                            </a>-->
                        </p>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="pay-list text-right">
                        <ul class="list-unstyled">
                            <li>
                                <a href="#">
                                    <img src="{{ url('public/frontend/images/pay/visa.png') }}" alt="">
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <img src="{{ url('public/frontend/images/pay/rocket.png') }}" alt="">
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <img src="{{ url('public/frontend/images/pay/mastercardd.png') }}" alt="">
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <img src="{{ url('public/frontend/images/pay/islamic-bank--mobile-banking.png') }}"
                                         alt="">
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <img src="{{ url('public/frontend/images/pay/islamic-bank--Internet-banking.png') }}"
                                         alt="">
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <img src="{{ url('public/frontend/images/pay/IFIC-mobile-banking.png') }}" alt="">
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <img src="{{ url('public/frontend/images/pay/dbbl-nexus.png') }}" alt="">
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <img src="{{ url('public/frontend/images/pay/Bikash.png') }}" alt="">
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <img src="{{ url('public/frontend/images/pay/american-express.png') }}" alt="">
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

{{--<div class="modal fade" id="getCodeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">--}}
    {{--<div class="modal-dialog modal-lg">--}}
        {{--<div class="modal-content">--}}
            {{--<div class="modal-header">--}}
                {{--<button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
                    {{--<span aria-hidden="true">&times;</span>--}}
                {{--</button>--}}
                {{--<h4 class="modal-title" id="myModalLabel"> New Added Product </h4>--}}
            {{--</div>--}}
            {{--<div class="modal-body" id="getCode" style="overflow-x: auto;">--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
{{--</div>--}}

<div class="modal fade" id="getCodeModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">New Added Product</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body" id="getCode">

            </div>
            {{--<!-- Modal footer -->--}}
            {{--<div class="modal-footer">--}}
                {{--<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>--}}
            {{--</div>--}}

        </div>
    </div>
</div>

@php
    $static_cats = widget_params($widgets, ['id' => 5]);
    //dd($static_cats);
@endphp

<!--@if (Cookie::get('popup') != true)-->
    @if($static_cats->is_active)
        <div id="arafta" class="scopp modal fade" role="dialog">
            <div class="modal-dialog arafartgvwlg">
                <div class="arafas-btn">
                    <a type="button" class="close" id="cookie" data-dismiss="modal">
                        &times;
                    </a>
                </div>
                <div class="arafat-modul">
                    {!! $static_cats->description !!}
                </div>
            </div>
        </div>
    @endif
<!--@endif-->



<?php  $alim_preload_ads = widget_params($widgets, ['id' => 5]);  ?>

<!--@if($alim_preload_ads->is_active)-->
<!--<div id="alimify-modal" class="modal fade" role="dialog">-->
<!--    <div class="modal-dialog">-->
<!--        <div class="alimify-modal-btn">-->
<!--            <a type="button" class="close alimify-modal-button-close" id="cookie" data-dismiss="modal">×</a>-->
<!--        </div>-->
<!--        <div class="alimify-modal-content">-->
<!--            {!!$alim_preload_ads->description!!}-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->
<!--@endif-->



<div id="overlay"> <div class="spinner"></div> </div> 