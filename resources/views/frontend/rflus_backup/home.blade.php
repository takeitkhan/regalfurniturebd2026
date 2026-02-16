@extends('frontend.rflus_backup.layouts.app')

@section('content')
    <?php $tksign = '&#2547; '; ?>
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
    </div>
    <!-- //Main Container -->
@endsection

@section('cusjs')
    <style type="text/css">
        .product-image-container {
            min-height: 275px;
        }

        .top_one ul.megamenu {
            margin: 0px 0 0 0 !important;
        }

        ul.megamenu {
            margin: -5px 0 0 0;
        }

        .hide_down_arrow:after {
            content: '' !important;
            font-size: 0px !important;
        }

        .hide_before_scroll {
            display: none;
        }

        ul.cd_sub_cat {
            column-count: 3;
            padding: 0 15px;
        }

        ul.cd_sub_cat > li > a {
            font-weight: bold;
        }
    </style>

    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $.noConflict();
            if ($(window).scrollTop() < 550) {
                $('.tighten').addClass('hide_before_scroll');
                $('.megamenuToogle-pattern .container').addClass('hide_down_arrow');
            }

            $(window).scroll(function () {
                if ($(this).scrollTop() > 550) { // this refers to window
                    //console.log(true);
                    $('.tighten').removeClass('hide_before_scroll');
                    $('.megamenuToogle-pattern .container').removeClass('hide_down_arrow');
                } else {
                    //console.log(false);
                    $('.tighten').addClass('hide_before_scroll');
                    $('.megamenuToogle-pattern .container').addClass('hide_down_arrow');
                }
            });
        });

        function get_tab_data(type) {
            if (type == 'new_arrival') {
                jQuery.ajax({
                    //url: baseurl + '/search_product?search_key=' + data.search_key + '&minprice=' + data.minprice + '&maxprice=' + data.maxprice + '&field=' + data.field + '&type=' + data.type + '&material=' + data.material + '&limit=' + data.limit + '&offset=' + data.offset,
                    url: baseurl + '/get_tab_data?type=' + type,
                    method: 'get',
                    //data: filters,
                    success: function (data) {
                        jQuery('.ltabs-loading').html(data.data);
                    },
                    error: function () {
                        showError('Sorry. Try reload this page and try again.');
                        processing.hide();
                    }
                });
            } else if (type == 'most_rated') {
                alert(type);
            }
        }
    </script>

@endsection
