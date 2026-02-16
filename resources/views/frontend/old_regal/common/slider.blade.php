<!-- main-banner-area section start -->

<div class="main-banner-section section-padding">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <!-- sidebar-area section start -->
                <!-- <div class="sidebar-area"> -->

                <div class="side-bar-menu hidden-xs hidden-sm">
                    <?php
                    //                    $side_bar_menu = dynamic_widget($widgets, array(
                    //                        'id' => 1,
                    //                        'heading' => NULL
                    //                    ));
                    ?>
                    {{--{!!  $side_bar_menu !!}--}}
                    {!! category_sidebar_menu_on_home_page(get_product_categories(), $parent = 100, $seperator = ' ', $cid = NULL) !!}
                    {{--{!! category_sidebar_menu($categories, $parent = 100, ' ', NULL)  !!}--}}
                </div>

<!--side-bar-menu-mobile  -->

                <div class="side-bar-menu-mobile visible-xs visible-sm">
                    <?php
                    //                    $side_bar_menu = dynamic_widget($widgets, array(
                    //                        'id' => 1,
                    //                        'heading' => NULL
                    //                    ));
                    ?>
                    {{--{!!  $side_bar_menu !!}--}}
                    {!! category_sidebar_menu_on_home_page(get_product_categories(), $parent = 100, $seperator = ' ', $cid = NULL) !!}
                    {{--{!! category_sidebar_menu($categories, $parent = 100, ' ', NULL)  !!}--}}
                </div>
                <!-- </div> -->
                <!-- sidebar-area section end -->
            </div>
            <!--col-end-->
            <!--slider-area-section start-->
            <div class="col-sm-12 col-md-9">
                <div class="slider-area">
                    <div class="slider-warp owl-carousel owl-theme">
                        <!--slider-start-->
                        <?php
                        $side_bar_menu = dynamic_widget($widgets, array(
                            'id' => 3,
                            'heading' => NULL
                        ));
                        ?>
                        {!!  $side_bar_menu !!}
                    </div>
                    <!--slider-end-->

                </div>
                <!--main-bnnr-service-start-->
            </div>

        </div>
    </div>
</div>
<!-- slider-area section end -->
<div class="slider-overlay">
    <div class="container">
        <div class="row">
            <div class="col-md-offset-3 col-md-9">
                <div class="col-md-12">
                    <?php
                    $side_bar_menu = dynamic_widget($widgets, array(
                        'id' => 4,
                        'heading' => NULL
                    ));
                    ?>
                    {!!  $side_bar_menu !!}
                </div>
            </div>
        </div>
        <!--slider-area-section end-->
    </div>
</div>
