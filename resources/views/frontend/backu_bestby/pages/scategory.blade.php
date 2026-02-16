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

    <div class="">
        <div class="about-area">
            <div class="container">
                <div class="row">
                    <div class="col-md-4">
                        <div class="location-input-area">

                            <div class="location-input-warp">
                                <div class="location-input-title">
                                    <h2>Shop type:</h2>
                                </div>
                                <div class="location-select">
                                    <?php $shop_type = ['Dealer point' => 'Dealer point', 'Easy build' => 'Easy build']; ?>
                                    <select class="selectpicker" id="shoptype_id" data-live-search="true">
                                        @foreach($shop_type as $key => $value)
                                            <?php //dd($stype); ?>
                                            <option
                                                <?php echo(!empty($post) ? ($post->shop_type == $value) ? ' selected="selected" ' : null : null); ?>
                                                value="{{ $value }}">
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="location-input-warp">
                                <div class="location-input-title">
                                    <h2>District:</h2>
                                </div>
                                <div class="location-select">
                                    @php
                                        $districts = get_districts();
                                        //dd($districts);
                                    @endphp
                                    <select class="selectpicker" id="district_id" data-live-search="true">
                                        @foreach($districts as $district)
                                            <option value="{{ $district->district }}">{{ $district->district }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="location-input-warp">
                                <div class="location-input-title">
                                    <h2>Upazila:</h2>
                                </div>
                                <div class="location-select">

                                    @php
                                        $thanas = get_thanas_by_district('Tangail');
                                    @endphp

                                    <select class="selectpicker" id="thana_id" data-live-search="true">
                                        <option>All Upazila</option>
                                        @foreach($thanas as $thana)
                                            <option value="{{ $thana->thana }}">{{ $thana->thana }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="location-btn-warp">
                                <button id="dealer_finder" class="btn location-btn" type="button">Search</button>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-4">
                        <div class="shop-location">
                            <div class="shop-location-list">
                                <ul>
                                    <li>
                                        <a href="#">
                                            <h5>Regal-Emporium-Shyamoli</h5>
                                            <small>24/2, block B khilji road (41 Ring road),
                                                Shymoli Phone: Ariful Islam (01844658172)
                                            </small>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="map-area">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3651.0449902052187!2d90.42314061540667!3d23.78141219350676!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755c796c76c1a8b%3A0x7ff1d179fba4c47c!2z4Kaq4KeN4Kaw4Ka-4KajLeCmhuCmsOCmj-Cmq-Cmj-CmsiDgppfgp43gprDgp4Hgpqo!5e0!3m2!1sbn!2sbd!4v1533791497711"
                                    width="100%" height="400px" frameborder="0" style="border:0"
                                    allowfullscreen></iframe>
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

        .location-input-warp {
            display: flex;
            justify-content: left;
        }

        .location-input-title h2 {
            color: hsl(0, 0%, 20%);
            font-size: 16px;
            margin: 0;
            padding: 6px 15px 6px 0px;
            width: 110px;
        }

        .location-input-warp {
            margin: 18px 0px;
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

        .dropdown-menu.open {
            border: 1px solid hsl(0, 0%, 60%);
        }

        .location-btn-warp {
            /*text-align: right;*/
        }

        .location-btn {
            background: hsl(205, 90%, 38%) none repeat scroll 0 0;
            color: hsl(0, 0%, 100%);
            font-size: 14px;
            /* margin-left: 15px; */
            margin-top: 10px;
            padding: 7px 16px;
            right: 11%;
            float: right;
            position: relative;
        }

        .location-btn:hover {
            background: #07558C;
            color: #FFF
        }
    </style>
@endsection