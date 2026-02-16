@section('cusjs')

    <?php

    $url_one = \Request::segment(1);
    $url_two = \Request::segment(2);
    $cat_url = '/' . $url_one ;

    //dd($cat_url);

    ?>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $.noConflict();

            $('#filtering').on('keyup keypress', function(e) {
                var keyCode = e.keyCode || e.which;
                if (keyCode === 13) {
                    e.preventDefault();
                    return false;
                }
            });

            $(document).on('change', '#price_min', function () {
                //alert('price working');
                main_search();

            });



            $(document).on("change", "#item_sort", function (e) {
                //alert('working');
                var self = this;
                var valu = $('option:selected', self).val();
               // alert(valu);
                $('#sort_by').val(valu);

                main_search();
            });
            $(document).on("change", "#item_count", function (e) {
                var self = this;
                var valu = $('option:selected', self).val();
                $('#sort_show').val(valu);

                main_search();
            });

            $(document).on('change', '#price_max', function () {
                //alert('price working');
                main_search();

            });




            $('#filtering input[type="checkbox"]').on('click change', function () {
                main_search();
            });


            $('#filtering #keyword_filter_submit').on('click', function () {
                main_search();
            });

            $('#filtering #keyword_filter_reset').on('click', function () {
                $('#filtering .keyword_filter').val('');
                main_search();
            });

            function main_search() {
                var pref_url = baseurl + '{{$cat_url.'?'}}';
                var str = $("#filtering").find('input[name!=_token]').serialize();
                window.location.replace(pref_url + str);
            }


        });




    </script>

    <style type="text/css">
        .second_img {
            min-height: 290px;
        }

        span.cloud {
            background: #EEEEEE;
            padding: 3px 5px;
            margin-bottom: 3px;
            display: inline-block;
        }

        span.single-cloud-remove {
            color: red;
            padding: 0px 3px;
        }
    </style>

@endsection
