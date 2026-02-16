@extends('frontend.layouts.app')

@section('content')
    <?php $tksign = '&#2547; '; ?>

    @include('frontend.home2.home2')

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

        /*.hide_down_arrow:after {
            content: '' !important;
            font-size: 0px !important;
        }*/

        .hide_before_scroll {
            display: none;
        }

        /**  **/
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
