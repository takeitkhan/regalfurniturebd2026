<div class="main-container">
    <div id="content">
        <div class="container">
            <div class="row box-content1">
                <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12 col-left">
                    <div class="module sohomepage-slider ">
                        <div class="yt-content-slider" data-rtl="yes" data-autoplay="no" data-autoheight="no"
                             data-delay="4" data-speed="0.6" data-margin="0" data-items_column00="1"
                             data-items_column0="1" data-items_column1="1" data-items_column2="1"
                             data-items_column3="1" data-items_column4="1" data-arrows="no" data-pagination="yes"
                             data-lazyload="yes" data-loop="no" data-hoverpause="yes">
                            @php
                                $static_cats = dynamic_widget($widgets, ['id' => 18]);
                            @endphp

                            {!! $static_cats !!}
                        </div>

                        <div class="loadeding"></div>
                    </div>
                </div>
              
                  

               <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12 col_fwgr col-right hidden-xs hidden-sm">
                    <div class="bannerstop banners">
                        <div class="row afkokfcj">
                            @php
                                $static_cats = dynamic_widget($widgets, ['id' => 22]);
                            @endphp
                            {!! $static_cats !!}
                            </div>
                        </div>
                    </div>
                </div>

            <div class="block-policy1 hidden-xs hidden-sm">
                <ul>
                    @php
                        $static_cats = dynamic_widget($widgets, ['id' => 21]);
                    @endphp
                    {!! $static_cats !!}
                </ul>
            </div>

            @include('frontend.home2.home2_categories')
            @include('frontend.home_flashsale')

            <div class="banner-text banners hidden-xs hidden-sm">
                <div>
                    @php
                        $static_cats = dynamic_widget($widgets, ['id' => 24]);
                    @endphp
                    {!! $static_cats !!}
                </div>
            </div>

            @include('frontend.home2.home2_cats')

            <div class="banners bannersb hidden-xs hidden-sm">
                <div class="banner">
                    @php
                        $static_cats = dynamic_widget($widgets, ['id' => 25]);
                    @endphp
                    {!! $static_cats !!}
                </div>
            </div>

            @include('frontend.home2.home2_top_rated')

        </div>
    </div>
</div>