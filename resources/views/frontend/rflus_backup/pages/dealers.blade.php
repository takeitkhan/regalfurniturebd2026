@extends('frontend.layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="breadcrumb-warp section-margin-two">
                <div class="col-md-12">
                    <div class="breadcrumb">
                        <?php
                        $breadcrumbs = new Creitive\Breadcrumbs\Breadcrumbs;
                        $breadcrumbs->setDivider(' » &nbsp;');
                        $breadcrumbs->addCrumb('Home', url('/'))
                            ->addCrumb('Dealer Shop', 'contact');
                        echo $breadcrumbs->render();
                        ?>
                    </div>
                    <!-- breadcrumb  end-->
                </div>
            </div>
        </div>
    </div>
<div class="main-container container">
        <ul class="breadcrumb">
            <li><a href="#"><i class="fa fa-home"></i></a></li>
            <li><a href="#">Page</a></li>
            <li><a href="#">Our Shop</a></li>
        </ul>

        <div class="row">
            <div id="content" class="col-sm-12 item-article">
                <div class="row box-1-about">
                    <div class="col-md-9 welcome-about-us">
                      <div class="title-about-us">
                        <h2>Nearest Service Point</h2>
                      </div>
                        <div class="select-area">
                          <div class="row">
                            <form action="">
                              <div class="col-md-3">
                                <div class="title-about-us1">
                                  <h4>Select Division</h4>
                                </div>
                                  <select class="form-control">
                                    <option>Default select</option>
                                    <option>Default select</option>
                                    <option>Default select</option>
                                    <option>Default select</option>
                                    <option>Default select</option>
                                  </select>
                              </div>
                              <div class="col-md-3">
                                <div class="title-about-us1">
                                  <h4>District</h4>
                                </div>
                                    <select class="form-control">
                                    <option>Default select</option>
                                    <option>Default select</option>
                                    <option>Default select</option>
                                    <option>Default select</option>
                                    <option>Default select</option>
                                  </select>
                              </div>
                              <div class="col-md-3">
                                <div class="title-about-us1">
                                  <h4>Thana</h4>
                                </div>
                                <select class="form-control">
                                  <option>Default select</option>
                                    <option>Default select</option>
                                    <option>Default select</option>
                                    <option>Default select</option>
                                    <option>Default select</option>
                                </select>
                              </div>
                              <div class="col-md-3">
                                <div class="title-about-us1">
                                  <h4>Upazila</h4>
                                </div>
                                <select class="form-control">
                                  <option>Default select</option>
                                    <option>Default select</option>
                                    <option>Default select</option>
                                    <option>Default select</option>
                                    <option>Default select</option>
                                </select>
                              </div>
                            </form>
                          </div>
                        </div>
                        <div class="welcome-about-us-header">
                        <div class="row">
                          <div class="single-shop-area">
                            
                            <div class="col-md-9">
                              <div class="title-about-us1">
                                <h3>Nearest Service Point1</h3>
                              </div>
                                <div class="">Nulla auctor mauris ut dui luctus semper. In hac habitasse platea dictumst. Duis pellentesque ligula a    risus suscipit dignissim. Nunc non nisl lacus. Integer pharetra lacinia dapibus. Donec eu dolor dui, vel posuere mauris.
                                    <br>
                                    <br>Pellentesque semper congue sodales. In consequat, metus eget con sequat ornare, augue dolor blandit purus, vitae lacinia nisi tellus in erat. Nulla ac justo eget massa aliquet sodales. Maecenas mattis male suada sem, in fringilla massa dapibus quis. Suspendisse aliquam leo id neque auctor molestie. Etiam at nulla tellus.
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="content-about-us">
                                    <div class="image-about-us">
                                        <img src="image/catalog/about/about-us.jpg" alt="Image Client">
                                    </div>
                                </div>
                             </div>
                          </div>
                          <div class="single-shop-area">
                            
                            <div class="col-md-9">
                              <div class="title-about-us1">
                                <h3>Nearest Service Point2</h3>
                              </div>
                                <div class="">Nulla auctor mauris ut dui luctus semper. In hac habitasse platea dictumst. Duis pellentesque ligula a    risus suscipit dignissim. Nunc non nisl lacus. Integer pharetra lacinia dapibus. Donec eu dolor dui, vel posuere mauris.
                                    <br>
                                    <br>Pellentesque semper congue sodales. In consequat, metus eget con sequat ornare, augue dolor blandit purus, vitae lacinia nisi tellus in erat. Nulla ac justo eget massa aliquet sodales. Maecenas mattis male suada sem, in fringilla massa dapibus quis. Suspendisse aliquam leo id neque auctor molestie. Etiam at nulla tellus.

                                </div>
                            </div>
                            <div class="col-md-3">
                              <div class="content-about-us">
                                  <div class="image-about-us">
                                      <img src="image/catalog/about/about-us.jpg" alt="Image Client">
                                  </div>
                              </div>
                             </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-3 why-choose-us">
                        <div class="title-about-us">
                            <h2>Bestbuy Store Location</h2>
                        </div>
                        <div class="content-why">
                           <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3651.7933752855015!2d90.37348185096074!3d23.754746594450555!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755b8ace8ac43b3%3A0x9d1155a7322c28d4!2sBest+Buys+Cameras+%26+Photos!5e0!3m2!1sen!2sbd!4v1544333463440" width="100%" height="530" frameborder="0" style="border:0" allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>






    <div class="">
        <div class="about-area">
            <div class="container">
                <div class="row">
                    <div class="col-md-8">
                        <div class="">
                            <style type="text/css">
                                .custom_dealer_search_head {

                                    padding: 10px 0px;
                                    background: #F6F6F6;
                                    float: left;
                                    clear: both;
                                    width: 100%;
                                    border-bottom: 2px solid #DDD;
                                    border-top: 2px solid #DDD;

                                }

                                .custom_dealer_search_head h2 {

                                    font-size: 16px;

                                }

                                .shop-location {
                                    padding: 10px;
                                    background: #FFFFFF;
                                    float: left;
                                    clear: both;
                                    width: 100%;
                                }

                            </style>
                            <div class="custom_dealer_search_head">
                                <div class="col-md-3">
                                    <h2>Shop type</h2>
                                    <?php $shop_type = ['Dealer point' => 'Dealer point', 'Easy build' => 'Easy build']; ?>
                                    <select id="shop_type" class="form-control">
                                        @foreach($shop_type as $key => $value)
                                            <option
                                                <?php echo(!empty($post) ? ($post->shop_type == $value) ? ' selected="selected" ' : null : null); ?>
                                                value="{{ $value }}">
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <h2>Division</h2>
                                    <select id="division" class="form-control">
                                        <option>Choose a division</option>
                                        @foreach ($divisions as $division)
                                            <option value="{{ $division->division }}">{{ $division->division }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <h2>District</h2>

                                    <select id="district" class="form-control">
                                        <option>Choose a district</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <h2>Upazila</h2>
                                    <select id="thana" class="form-control">
                                        <option>Choose a upazila</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="shop-location">
                            <div class="shop-location-list">


                                {{--<div class="single-news showroom-dels">--}}
                                <div id="contentreloader">
                                    <div class="news-content">
                                        @foreach($posts as $post)

                                            <div class="address-shower">
                                                <div class="col-md-12">

                                                    <a href="javascript:void(0)"
                                                       class="btn btn-sm btn-success pull-right"
                                                       onclick="mapGenerate({{ $post->id }}, {{ $post->latitude }}, {{ $post->longitude }})">
                                                        <i class="fa fa-map-pin"></i> Pin
                                                    </a>

                                                    <div class="mc-header news-text nearest-shop-list">
                                                        <h5>{{ $post->title }}</h5>
                                                        <p>
                                                            <span class="half-title">Address:</span>
                                                            <span class="half-content">{{ $post->address }}</span>
                                                        </p>
                                                        <p>
                                                            <i class="fa fa-phone"></i> {{ $post->phone }}
                                                        </p>

                                                    </div>
                                                </div>
                                            </div>



                                        @endforeach
                                    </div>
                                </div>
                                {{--</div>--}}


                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="lock map-area">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3651.0449902073383!2d90.42313525096105!3d23.781412193431294!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755c796c76c1a8b%3A0x7ff1d179fba4c47c!2sPRAN-RFL+GROUP!5e0!3m2!1sen!2sbd!4v1537611653733"
                                    style="border:0" allowfullscreen="" width="100%"
                                    height="510"
                                    frameborder="0"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('cusjs')
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $.noConflict();

            $('#division').on('change', function () {
                var division = $(this).val();

                $.ajax({
                    url: baseurl + '/get_district_by_division',
                    method: 'get',
                    data: {division: division},
                    success: function (data) {
                        //console.log(data);
                        $('#district').html(data);
                    },
                    error: function () {
                    }
                });
            });

            $('#district').on('change', function () {
                var district = $(this).val();

                $.ajax({
                    url: baseurl + '/get_thana_by_district',
                    method: 'get',
                    data: {district: district},
                    success: function (data) {
                        $('#thana').html(data);
                    },
                    error: function () {
                    }
                });
            });


            $('#thana').on('change', function () {
                var thana = $(this).val();

                $.ajax({
                    url: baseurl + '/get_showroom_by_thana',
                    method: 'get',
                    data: {thana: thana},
                    success: function (data) {
                        $('#contentreloader').html(data);
                        //$('.news-content').html(data);
                    },
                    error: function () {
                    }
                });
            });

        });

        function mapGenerate(id, lat, long) {
            var newmap = '<iframe' +
                ' width="100%" \n' +
                ' height="590" \n' +
                ' frameborder="0" \n' +
                ' scrolling="no" \n' +
                ' marginheight="0" \n' +
                ' marginwidth="0" ' +
                ' src="https://maps.google.com/maps?q=' + lat + ', ' + long + '&hl=es;z=14&amp;output=embed"' +
                ' style="border:0" ' +
                ' allowfullscreen=""></iframe>';

            jQuery('.lock').html(newmap);
        }
    </script>

    <style type="text/css">
        .shop-location {
            background: hsl(0, 0%, 100%) none repeat scroll 0 0;
            padding: 10px;
        }

        .shop-location-list li {
            color: hsl(0, 0%, 20%);
            font-size: 14px;
            padding: 7px;
        }

        .shop-location-list ol {
            padding: 0 23px;
        }

        .shop-location-list {
            height: 400px;
            overflow: auto;
        }

        .map-area {
            background: hsl(0, 0%, 100%) none repeat scroll 0 0;
            margin-bottom: 0;
            padding: 4px;
        }

        .address-shower .col-md-12 {
            background: #F2F5F7;
            padding: 0 10px 10px 25px;
        }

        .address-shower .col-md-12 > a {
            margin: 25px 10px;
            position: absolute;
            right: 14px;
            font-size: 16px;
        }

        .location-input-title h2 {
            color: hsl(0, 0%, 20%);
            font-size: 16px;
            margin: 0;
            padding: 0px 15px 5px 0px;
        }

        .location-input-title > select {
            width: 100% !important;
        }

        .location-select .bootstrap-select.btn-group .dropdown-menu {
            min-height: 245px;
            overflow: auto;
            z-index: 2147483647;
        }

        .location-select .dropdown-menu > li > a:focus, .dropdown-menu > li > a:hover {
            background-color: hsl(205, 90%, 38%);
            color: hsl(0, 0%, 100%);
            text-decoration: none;
        }

        .location-select .bootstrap-select.btn-group .dropdown-menu li a span.text {
            display: block;
            font-size: 14px;
            padding: 2px 0;
        }

        .nearest-shop-list {
            margin-right: 15px;
        }

        .nearest-shop-list > h5 {
            font-size: 18px;
            padding: 8px 0px;
            margin: 1px 0;
        }

        .nearest-shop-list > p {
            color: hsl(0, 0%, 45%);
            margin: 0;
            padding: 0;
        }

        .direction-btn > a {
            display: block;
            margin-top: 25px;
        }

    </style>
@endsection