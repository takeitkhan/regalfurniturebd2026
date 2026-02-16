@section('cusjs')

    <?php

    $url_one = \Request::segment(1);
    $url_two = \Request::segment(2);
    $cat_url = '/' . $url_one ;

    //dd($cat_url);

    ?>
    <script>
function openNav() {
  document.getElementById("mySidenav").style.width = "250px";
}

function closeNav() {
  document.getElementById("mySidenav").style.width = "0";
}

</script>
    <script type="text/javascript">
/*
        jQuery(document).ready(function ($) {
            $.noConflict();

            var s_min = $("#min_price").val();
            var s_max = $("#max_price").val();
            var sr_max = $("#max_price").attr('data-max-range');
            //id
            $(".slider-range").slider({
                range: true,
                orientation: "horizontal",
                min: 0,
                max: sr_max,
                values: [s_min, s_max],
                step: 100,

                slide: function (event, ui) {
                    if (ui.values[0] == ui.values[1]) {
                    return false;
                    }
                    $("#min_price").val(ui.values[0]);
                    $("#max_price").val(ui.values[1]);
                }
            });
            //id
            $(document).on('click', '.sumbit-price-range', function () {
                main_search();

            });

            //id
            $('.filtering').on('keyup keypress', function(e) {
                var keyCode = e.keyCode || e.which;
                if (keyCode === 13) {
                    e.preventDefault();
                    return false;
                }
            });

            //id
            $(document).on("change", ".item_sort", function (e) {
                //alert('working');
                var self = this;
                var valu = $('option:selected', self).val();
                // alert(valu);
                //id
                $('.sort_by').val(valu);

                main_search();
            });

            //id
            $(document).on("change", ".item_count", function (e) {
                var self = this;
                var valu = $('option:selected', self).val();
                $('.sort_show').val(valu); //id

                main_search();
            });

            //id
            $(document).on('change', '.price_max', function () {
                //alert('price working');
                main_search();

            });

            //id
            $('.filtering input[type="checkbox"]').on('click change', function () {
                main_search();
            });

            ///id
            $('.filtering .keyword_filter_submit').on('click', function () {
                main_search();
            });

            ///id
            $('.filtering .keyword_filter_reset').on('click', function () {

                //id
                $('.filtering .keyword_filter').val('');
                main_search();
            });

            function main_search() {
                var pref_url = baseurl + '{{$cat_url.'?'}}';

                //id
                var str = $(".filtering").find('input[name!=_token]').serialize();
                window.location.replace(pref_url + str);
            }


        });
        */
    </script>
    <script>
       /* var slider = document.getElementById("myRange");
        var output = document.getElementById("demo");
        output.innerHTML = slider.value;

        slider.oninput = function() {
            output.innerHTML = this.value;
        }*/
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