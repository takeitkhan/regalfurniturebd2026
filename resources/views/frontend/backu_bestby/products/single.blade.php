@extends('frontend.layouts.app')

@section('content')


    <?php
    $tksign = '&#2547; ';
    $user = Auth::user();

    if ($user) {
        $is_buy = \App\OrdersDetail::where(['user_id' => $user->id, 'product_id' => $pro->id, 'is_active' => 1])->get()->count();
    } else {
        $is_buy = null;
    }


    // dd($pro);
    $product = $pro;
    $this_review = \App\Review::where(['product_id' => $product->id, 'is_active' => 1])->get();
    // dump($this_review);
    $review_one = 0;
    $review_two = 0;
    $review_three = 0;
    $review_four = 0;
    $review_five = 0;
    $review_count = 0;
    $review_total = 0;
    foreach ($this_review as $review) {
        if ($review->rating == 1) {
            ++$review_one;
        }
        if ($review->rating == 2) {
            ++$review_two;
        }
        if ($review->rating == 3) {
            ++$review_three;
        }
        if ($review->rating == 4) {
            ++$review_four;
        }
        if ($review->rating == 5) {
            ++$review_five;
        }
        $review_total += $review->rating;
        ++$review_count;
    }

    ?>

    <div class="main-container container">
        <ul class="breadcrumb">
            <li><a href="{{ url('/') }}"><i class="fa fa-home"></i></a></li>
            <li>
                <?php 
                    $cat_info = \App\Term::where('id', $categories[0]['term_id'])->get()->first(); //dump($cat_info);
                    ?>
                <a href="{{ url('/c/'. $cat_info['seo_url']) }}">
                    {{ $cat_info['name'] }}
                </a>
            </li>
            <li>{{ $pro->title }}</li>
        </ul>
        <div class="alert alert-success" style='display:none;'>
            <strong>Success!</strong> Indicates a successful or positive action.
        </div>
        <div class="row">
            @include('frontend.products.single.left')
            @include('frontend.products.single.right')
        </div>
    </div>

    @php
        $regularprice = $pro->local_selling_price;
         $is_flash = is_flash_item($product->id);
         if($is_flash){
            $save = $is_flash['discount_tag'];
         }else{
             $save = ($pro->local_selling_price * $pro->local_discount) / 100;
         }

       $sp = $regularprice - $save;
    @endphp

@endsection
@section('cusjs')
    <script type="text/javascript">
        //picZoomer
        function color_choosed(id, main_pid) {
            var data = {
                'id': id,
                'main_pid': main_pid
            };

            jQuery.ajax({
                url: baseurl + '/modify_variation',
                method: 'get',
                data: data,
                success: function (data) {
                    jQuery('#price_tag').html(data.price_tag);
                    jQuery('#add_to_cart_btn').html(data.add_to_cart_btn);
                    //console.log(data.price_tag);
                    //console.log(data.add_to_cart_btn);
                    //$('#show_message').html(data.message);
                },
                error: function () {
                }
            });
        }


        function size_choosed(id, main_pid) {
            alert(id);
        }


        jQuery(document).ready(function ($) {
            $.noConflict();
            //$('.picZoomer').picZoomer();

            //切换图片
             //alert('working');

           // master_color();
            master_pricing();

            // $('input:radio[name=credit-card]').change(function () {
            //     master_color();
            // });
            // $(document).on("change", 'input:radio[name=size_radio]', function (e) {
            //
            //     master_price();
            // });

            // $(document).on("change", '#emi_checkbox', function (e) {
            //     if ($('input#emi_checkbox').is(':checked')) {
            //         var price = $('#button-cart').attr('data-purchaseprice');
            //         $('#emi_pur').attr('data-price',price);
            //        // alert(price);
            //     }
            // });


            $(document).on("keyup change", '#quantity', function (e) {
                var qty = $("#quantity").val();
                $("#button-cart").attr('data-qty', qty);
                //alert(qty);
            });

            $(document).on('keyup change', '#quantity', function () {
                var value = $(this).val();
                if ((value !== '') && (value.indexOf('.') === -1)) {
                    $(this).val(Math.max(Math.min(value, 5), -5));
                }
            });

            //
            $('.rating input').click(function () {
                //  alert('working');
                $(".rating span").removeClass('checked');
                $(this).parent().addClass('checked');
            });

            $('input:radio').change(
                function () {
                    $(this).prop("checked", true);
                    //checkrating();
                }
            );

            // alert('working');


            $('.piclist li').on('click', function (event) {
                var $pic = $(this).find('img');
                var $buynow = $("#buynow").data('imageurl');
                $('.picZoomer-pic').attr('src', $pic.attr('src'));
                $('#buynow').data('imageurl', $buynow.data('imageurl'));
            });

            $('#combinition').on('change', function (event) {
                event.preventDefault();

                var val = $(this).val();
                //var n = val.split('|');

                //var id = n[1];
                //var item = n[0];

                var url = window.location.pathname;
                window.location.replace(url + '?product_code=' + val);

            });

            $('#plus, #minus').on('click', function (e) {

            });


            $('#width, #length').on('change keyup paste', function () {

                if ($('#length').val() == '') {
                    $('#unit_msg').html('Length can not be left blank');
                    $('#show_unit_values').val('');
                } else if ($('#width').val() == '') {
                    $('#unit_msg').html('Width can not be left blank');
                    $('#show_unit_values').val('');
                } else if ($('#width').val() !== '' || $('#length').val() !== '') {
                    $('#unit_msg').html('');

                    var w = $('#width').val();
                    var l = $('#length').val();

                    var sft = w * l;

                    $('#show_unit_values').val(sft);
                } else {
                    var w = $('#width').val();
                    var l = $('#length').val();

                    var sft = w * l;

                    $('#show_unit_values').val(sft);

                }

                var suv = $('#show_unit_values').val();

                if (suv < 1 || suv == '') {
                    $('button#buynow').prop('disabled', true);
                } else {
                    $('button#buynow').prop('disabled', false);
                }

            });


            $('#width_c, #length_c, #thickness_c').on('change keyup paste', function () {

                if ($('#length_c').val() == '') {
                    $('#unit_msg').html('Length can not be left blank');
                    $('#show_unit_values').val('');
                } else if ($('#width_c').val() == '') {
                    $('#unit_msg').html('Width can not be left blank');
                    $('#show_unit_values').val('');
                } else if ($('#thickness_c').val() == '') {
                    $('#unit_msg').html('Thickness can not be left blank');
                    $('#show_unit_values').val('');
                } else if ($('#thickness_c').val() !== '' || $('#width_c').val() !== '' || $('#length_c').val() !== '') {
                    $('#unit_msg').html('');
                } else {
                    var w = $('#width_c').val();
                    var l = $('#length_c').val();
                    var t = $('#thickness_c').val();

                    var cft = w * l * t;

                    $('#show_unit_values').val(cft);

                }

                var suv = $('#show_unit_values').val();

                if (suv < 1 || suv == '') {
                    $('button#buynow').prop('disabled', true);
                } else {
                    $('button#buynow').prop('disabled', false);
                }

            });

            $('#comment_form').on('click', function () {

                $.ajax({
                    url: baseurl + '/comment_save',
                    method: 'post',
                    success: function (data) {
                        location.reload();
                        //$('#show_message').html(data.message);
                    },
                    error: function () {
                    }
                });

            });

            $(document).on("click", "input:checkbox", function () {
                // in the handler, 'this' refers to the box clicked on
                var $box = $(this);
                if ($box.is(":checked")) {
                    var group = "input:checkbox[name='" + $box.attr("name") + "']";
                    $(group).prop("checked", false);
                    $box.prop("checked", true);
                } else {
                    $box.prop("checked", false);
                }
            });



            $(document).on('click', '.item-color-front', function () {

                var color_id = $(this).attr('data-color');
                $('.item-color-front.active').removeClass('active');
                $(this).addClass('active');
                $('#button-cart').attr('data-color_id', color_id);
                $('#button-cart').attr('data-size_id', '');
                master_pricing();

            });

            $(document).on('click', '.item-size-front', function () {
                var size_id = $(this).attr('data-color');
                $('.item-size-front.active').removeClass('active');
                $(this).addClass('active');
                $('#button-cart').attr('data-size_id', size_id);
                master_pricing();

            });

            $(document).on('click', '#emi_pur', function () {
                var checked = $("input:checkbox").is(":checked");

                if (checked) {
                    //alert($("input:checkbox:checked").val());
                    var value = $("input:checkbox:checked").val();
                    var id = $("input:checkbox:checked").data('id');
                    var bank_id = $("input:checkbox:checked").data('bank');
                    var main_pid = $("input:checkbox:checked").data('main_pid');

                    var data = {
                        'value': value,
                        'plan_id': id,
                        'bank_id': bank_id,
                        'main_pid': main_pid
                    };

                    console.log(data);

                    $.ajax({
                        url: baseurl + '/set_emi',
                        method: 'get',
                        data: data,
                        success: function (data) {
                            location.reload();
                            //$('#show_message').html(data.message);
                        },
                        error: function () {
                        }
                    });


                } else {
                    $('.showMessage').html('<span style="color: red;">Choose your preferred plan first</span>');
                }
            });


        });


        // function master_color() {
        //     //alert('workig');
        //     var main_pid = jQuery("input:radio[name=credit-card]:checked").attr('data-product');
        //     var color_codes = jQuery("input:radio[name=credit-card]:checked").attr('data-color');
        //
        //     var color_price = jQuery("input:radio[name=size_radio]:checked").attr('data-price');
        //
        //     jQuery.ajax({
        //         url: baseurl + '/get_size_price_data',
        //
        //         method: 'get',
        //         data: {'main_pid': main_pid, 'color_codes': color_codes},
        //         success: function (data) {
        //             //alert(data);
        //             jQuery('#xhmx_size').html(data.data);
        //             master_price();
        //
        //         },
        //         error: function () {
        //
        //         }
        //     });
        //     //alert(stories_price);
        // }

        {{--function master_price() {--}}

            {{--var tksign = "{{ $tksign }}";--}}
            {{--var v_price = '';--}}
            {{--var price = '';--}}
            {{--var loc_price = jQuery("#button-cart").attr('data-regularprice');--}}
            {{--var discount = jQuery("#button-cart").attr('data-discount-tag');--}}
            {{--var save = jQuery("#button-cart").attr('data-saveprice');--}}
            {{--var emi = jQuery("#button-cart").attr('data-emi');--}}
            {{--var color_price = jQuery("input:radio[name=size_radio]:checked").attr('data-price');--}}


            {{--if (color_price != null) {--}}
                {{--loc_price = color_price;--}}
            {{--}--}}

          //   price = parseFloat(loc_price) - parseFloat(save);
          // // alert(emi);
          //
          //
          //   if (loc_price != price) {
          //
          //       v_price = '<p class="price"><span class="price-new">Price : ' + tksign + numberFormat(price) + ' </span><span class="price-old">' + tksign + numberFormat(loc_price) + '</span></p>';
          //       v_price = ''
          //           +'<span class="regularprice" itemprop="price"> Regular Price: ' + tksign + numberFormat(loc_price) + '</span> <br>'
          //           +'<div class="product_page_price price" itemprop="offerDetails" itemscope="" itemtype="http://data-vocabulary.org/Offer">'
          //           +'<span class="price-new" id="price_tag" itemprop="price"> Discount Price ('+discount+'%): ' + tksign + numberFormat(price) + ''
          //           + '<span style="font-size: 13px; color: #444; margin-left: 5px;">(Save ' + tksign + numberFormat(save) + ')</span>'
          //           + '</span></div>';
          //
          //   } else {
          //       v_price = '<p class="price"><span class="price-new"> Price : ' + tksign + numberFormat(price) + ' </span></p>';
          //
          //   }
          //
          //   var emi_rate = 'no';
          //
          //   if(emi !='off'){
          //       emi_rate = tksign + numberFormat(parseFloat(price) / parseFloat(emi),2);
          //   }
          //
          //   jQuery("#price_tag").html(v_price);
          //   jQuery("#button-cart").attr('data-purchaseprice', price);
          //   jQuery("#button-cart").attr('data-regularprice', loc_price);
          //   jQuery("#emi_pur").attr('data-price', price);
          //   jQuery("#emi_pur").attr('data-emi', emi);
          //   if(emi_rate !='no') {
          //       jQuery("#emi_price_tag").html(emi_rate);

            {{--}--}}

            {{--// alert(color_price);--}}
        {{--}--}}

        function master_pricing() {
           // alert('workig');
            var main_pid =  jQuery('#button-cart').attr('data-productid');
            //var color = jQuery('#button-cart').attr('data-color_id');
            var color = jQuery('#button-cart').attr('data-color_id');
            var size = jQuery('#button-cart').attr('data-size_id');
            var type = '';

            jQuery.ajax({
                url: baseurl + '/get_product_pricing',

                method: 'get',
                data: {'main_pid': main_pid, 'color': color, 'size' : size,  'type' : type},
                success: function (data) {
                    //alert(data.data['color']);
                     console.log(data.data['color']);
                     jQuery('#xhmx_color').html(data.data['color']);
                     jQuery('#xhmx_size').html(data.data['size']);
                     jQuery('#price_tag').html(data.data['price']);
                    // master_price();

                },
                error: function () {

                }
            });
            //alert(stories_price);
        }
        //
        // function master_pricing_color() {
        //     // alert('workig');
        //     var main_pid =  jQuery('#button-cart').attr('data-productid');
        //     //var color = jQuery('#button-cart').attr('data-color_id');
        //     var color = jQuery('#button-cart').attr('data-color_id');
        //     var size = jQuery('#button-cart').attr('data-size_id');
        //     var type = '';
        //
        //     jQuery.ajax({
        //         url: baseurl + '/get_product_pricing_color',
        //
        //         method: 'get',
        //         data: {'main_pid': main_pid, 'color_size': color, 'size' : size,  'type' : type},
        //         success: function (data) {
        //             //alert(data.data['color']);
        //             console.log(data.data['color']);
        //             jQuery('#xhmx_color').html(data.data['color']);
        //             jQuery('#xhmx_size').html(data.data['size']);
        //             // master_price();
        //
        //         },
        //         error: function () {
        //
        //         }
        //     });
        //     //alert(stories_price);
        // }

    </script>

<link rel="stylesheet" href="{{ asset('public/frontend/css/needsharebutton.min.css') }}">
<script src="{{ asset('public/frontend/js/needsharebutton.min.js') }}"></script>
<script>
    new needShareDropdown(document.getElementById('demo'), {
        iconStyle: 'box',
        boxForm: 'horizontal',
        position: 'middleRight',
        networks: 'Facebook,Twitter,Pinterest,Linkedin'
    });

    (function($){
        $("#addwishlist").click(function(){
            $.ajax({
                url: "{{ route('add_to_wishlist') }}",
                type: 'get',
                data: {
                    'product_id': {{ $product->id }}
                },
                success: function(result){
                    // console.log(result);
                    jQuery('.alert').toggle();
                    jQuery('div.alert').html(result.message);
                    jQuery("div#show_total_wishlist").html(result.total);
                }
            });
        });
    })(jQuery);
</script>
    <style type="text/css">
        span.regularprice {
            font-size: 18px;
            font-weight: normal;
            text-decoration: line-through;
            color: #333333;
        }

        .force_margin {
            margin-left: -4px;
            margin-right: -4px;
        }

        .welly {
            min-height: 10px;
            padding: 5px 10px;
            margin-bottom: 5px;
            background-color: #f5f5f5;
            border: 1px solid #e3e3e3;
            border-radius: 3px;
            -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .05);
            box-shadow: inset 0 1px 1px rgba(0, 0, 0, .05);
            margin-left: 50px;
        }

        .answer .icone-area-rightA > p {
            margin-top: 5px !important;
        }

        .answer .icone-area-leftA > p {
            margin-top: 5px;
        }

        .icone-area-warp {
            display: flex;
            justify-content: left;
        }

        .icone-area-rightQ, .icone-area-rightA {
            width: 100%;
        }

        .icone-area-leftQ p, .icone-area-leftA p {
            position: relative;
            border-radius: 3px;
            margin-top: 12px;
            margin-right: 20px;
            text-align: center;
            padding: 0px 9px 0px 9px;
            font-size: 14px;
        }

        .icone-area-leftQ p {
            color: #FFFFFF;
            background: #25a5d8;
        }

        .icone-area-leftA p {
            color: #FFFFFF;
            background: #9e9e9e;
        }

        .icone-area-leftQ p:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 51%;
            width: 0;
            height: 0;
            border: 6px solid transparent;
            border-bottom: 0;
            border-left: 0;
            margin-left: -9px;
            margin-bottom: -6px;
            border-top-color: #25a5d8;
        }

        .icone-area-leftA p:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 51%;
            width: 0;
            height: 0;
            border: 6px solid transparent;
            border-bottom: 0;
            border-left: 0;
            margin-left: -9px;
            margin-bottom: -6px;
            border-top-color: #9e9e9e;
        }

        div.custom_attributes {
            background: #f3f3f3;
            padding: 10px;
            min-height: 250px;
            border-radius: 5px;
            -webkit-border-radius: 5px;
            -moz-border-radius: 5px;
        }

        span.field-label {
            color: #666666;
            font-weight: bold;
        }

        span.field-value {
            font-weight: normal;
        }

        .rating {
            float: left;
            width: 300px;
        }

        .rating span {
            float: right;
            position: relative;
        }

        .rating span input {
            position: absolute;
            top: 0px;
            left: 0px;
            opacity: 0;

        }

        .rating span input[type=radio] {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            background: transparent;
            position: relative;
            visibility: hidden;

        }

        .rating span input {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            background: transparent;
            position: relative;
            visibility: hidden;

        }

        .rating span label {
            display: inline-block;

            text-align: center;
            color: #ccc;
            font-size: 30px;
            margin-right: 2px;
            line-height: 30px;
            border-radius: 50%;
            -webkit-border-radius: 50%;
            font-size: 22px;
        }

        .rating span:hover ~ span label,
        .rating span:hover label,
        .rating span.checked label,
        .rating span.checked ~ span label {

            color: #fbe358;
        }
        .order_by_phone {
            font-size: 19px;
            color: #f43b4e;
            display: block;
        }
        .order_by_phone span {
            font-weight: bold;
            color: #212121;
        }
    </style>
@endsection