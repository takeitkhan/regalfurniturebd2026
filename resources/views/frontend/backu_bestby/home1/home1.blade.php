<div class="main-container container">
    <div id="content">
        <div class="content-top-w row">
            @include('frontend.home_cat_slides')
        </div>
        <div class="row content-main-w">

            <div class="col-lg-2 col-md-3 col-sm-4 col-xs-12 main-left">
                <div class="module hidden-xs">
                <?php $banners_banners_mk = dynamic_widget($widgets, ['id' => 1]); ?>
                {!! $banners_banners_mk !!}
                <!-- <div class="banners banners2">
                            <div class="banner">
                                <a href="#">
                                    <img src="{{ URL::asset('public/frontend/image/banner/sidebanner/1.jpg') }}" alt="image">
                                </a>
                            </div>
                        </div> -->
                </div>

                <div class="module product-simple extra-layout1">
                    @include('frontend.home_latestproducts')
                </div>
                <div class="module hidden-xs">
                    <?php $policy_widget_1 = dynamic_widget($widgets, ['id' => 3]); ?>
                    {!! $policy_widget_1 !!}
                </div>

                <div class="module extra">
                    @include('frontend.home_recommended')
                </div>


                <div class="module hidden-xs">
                    <?php $static_cats_15 = dynamic_widget($widgets, ['id' => 15]); ?>
                    {!! $static_cats_15 !!}
                </div>

                <br/>
                @include('frontend.home_express_delivery')
            </div>


            <div class="col-lg-10 col-md-9 col-sm-8 col-xs-12 main-right">
                <div class="aaea hidden-xs">
                    <?php $static_cats = dynamic_widget($widgets, ['id' => 2]); ?>
                    {!! $static_cats !!}
                </div>
                <br>
                <br>
                <div class="module deals-layout1">
                    @include('frontend.home_flashsale')
                </div>
                <div class="arafasrt hidden-xs">
                    <?php $home_banner3 = dynamic_widget($widgets, ['id' => 4]); ?>
                    {!! $home_banner3 !!}
                </div>
                @include('frontend.home_cats')
                <div class="arafasrt hidden-xs">
                    <?php $home_banner5 = dynamic_widget($widgets, ['id' => 5]); ?>
                    {!! $home_banner5 !!}
                </div>
                {{--@include('frontend.home_listingtab')--}}
                <div class="arafasrt hidden-xs">
                    <?php $static_cats = dynamic_widget($widgets, ['id' => 17]); ?>
                    {{--{!! $static_cats !!}--}}
                </div>
            </div>
        </div>
    </div>
</div>