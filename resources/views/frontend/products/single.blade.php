@extends('frontend.layouts.app')

@section('content')
    @php
        $regularprice = $pro->local_selling_price;
        $save = ($pro->local_selling_price * $pro->local_discount) / 100;
        $sp = $regularprice - $save;
    @endphp

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
     //dump($pro->id);
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

<section class="breadcrumb-area">
<div class="col-md-12">
        <div class="breadcrumb-warp">
		<div class="breadcrumb-one">
			<nav aria-label="breadcrumb">

				<ul class="breadcrumb">
					<li><a href="{{ url('/') }}"><i class="fa fa-home"></i></a></li>
					<li>
						<?php $cat_info = \App\Term::where('id', $categories[0]['term_id'])->get()->first(); //dump($cat_info); ?>
						<a href="{{ url('/c/'. $cat_info['seo_url']) }}">
						{{ $cat_info['name'] }}
						</a>
					</li>
					<li><a href="javascript:void(0)">{{ $pro->title }}</a></li>
				</ul>

			</nav>
		</div>
	</div>
</div>
</section>

    <div class="main-container">
        <div class="row">
           @include('frontend.products.single.left')
            {{--@include('frontend.products.single.right')--}}
        </div>
    </div>

@endsection
@section('cusjs')


<script>
dataLayer.push({
  'event': 'ProductDetailPage',			//used for creating GTM trigger
  'ecommerce': {
    'detail': {
      'actionField': {'list': '{{ $cat_info['name'] }}'},    // 'detail' actions have an optional list property.
      'products': [{
        'name': '{{$pro->title}}',         // Name is required
        'id': '{{$pro->id}}',				      // ID/SKU is required
        'price': '{{$sp}}',
        'brand': 'Regal',
        'category': '{{ $cat_info['name'] }}',
// 	 'vendor': 'Vector Bazar',
        // 'variant': 'Red',
// 	 'position': '0',
      }]
     }
  }
});
</script>


<script type="application/ld+json">
{
  "@context":"https://schema.org",
  "@type": "Product",
  "productID": "{{ $pro->sku }}",
  "sku": "{{ $pro->sku }}",
  "name": "{{ $pro->title }}",
  "description": "{{ $pro->description }}",
  "url": "{{ url('product/' . $pro->seo_url) }}",
  "image":
        @foreach($images as $image)
            @if($image->is_main_image == 1)
                @if(!empty($image))
    	            "{!! url($image->full_size_directory) !!}"
                @endif
            @endif
        @endforeach
		,
  "brand":"facebook",
  "aggregateRating": {
    "@type": "AggregateRating",
    "ratingValue": "88",
    "bestRating": "100",
    "ratingCount": "20"
  },
  "offers": [
    {
      "@type": "Offer",
      "price": "{{ $sp }}",
      "priceCurrency": "BDT",
      "priceValidUntil": "",
      "url": "{{ url('product/' . $pro->seo_url) }}",
      "itemCondition": "https://schema.org/NewCondition",
      "availability": "https://schema.org/InStock"
    }
  ]
}
</script>

<style>
   .zoom-warp{
        border:1px solid #ddd;
    }
    

    .view-product-image img{
        max-width:530px!important;
    }
    .spritespin{
        z-index:9999;
    }
    .spritespin:hover{
        cursor: move;
        cursor: grab;
        cursor: -moz-grab;
        cursor: -webkit-grab;
    }
    .spritespin:active {
        cursor: grabbing;
        cursor: -moz-grabbing;
        cursor: -webkit-grabbing;
    }

    .t60degreeview .drag_drop {
        position: absolute;
        margin: 0 auto;
        bottom: 6%;
        right: 32%;
        left: 28%;
        background: #a0a0a0c9;
        color: #fff;
        font-weight: 600;
        width: 200px;
        font-size: 15px;
        padding: 3px 0px 5px 0px;
        margin-top: 1px;
        border-radius: 9px;
        -webkit-user-select: none;
        -khtml-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        z-index: 10000;
    }
    @media screen and (max-width: 480px) {
        zoomContainer{
            display:none;
        }
    }
    @media screen and (max-width: 360px) {
      .t60degreeview .drag_drop {
          left:15% !important;
      }
      
      .spritespin.spritespin-instance.with-canvas {
          width: 100% !important;
          
      }
    
    }
    
    .t60degreeview span.drag_left {
        font-size: 30px;
        cursor:pointer;
        padding:0px 16px 0px 7px;
    }
    .t60degreeview span.drag_right {
        font-size: 30px;
        cursor:pointer;
        padding:0px 7px 0px 16px;
    }
    .t60degreeview span.drag_right a,.t60degreeview span.drag_left a{
        color:#fff;
        font-weight:900
        font-size:20px;
    }
    .piclist li {
        margin-top: 1px!important;
        margin-bottom: 1px!important;
        margin: 0px !important;
        float: left;
    }
    .spritespin.spritespin-instance.with-canvas {
        position: relative;
    }
    .embed-youtube-video {
        margin: 0px;
        float: left;
        display: inline-flex;
        width:100%;
    }
    div#gal1 {
        /*border: 1px solid #ddd;*/
       /* width: 100%;*/
        /*float: left;*/
    }
    .share_buttons ul.eagles_buttons {
        list-style: none;
        list-style-type: none;
    }
    ul.eagles_buttons li {
        display: inline;
    }
</style>
<script src='{{asset('public/frontend/js/vendor/spritespin.min.js')}}' type='text/javascript'></script>
<script>
 jQuery(document).ready(function ($) {
     
  const mainImageWidth  = $(".view-product-image img")[0].offsetWidth,
        mainImageHeight = $(".view-product-image img")[0].offsetHeight;
     
     console.log('kjsjajsj',$($(".zoom-lg")[0].children[0])[0].offsetWidth,'helloworld')
     
     
  $('.spritespin').spritespin({
    source: [
    @foreach($pro->threeSixtyDegreeImage as $sixtyDegree)
    '/{{$sixtyDegree->image->full_size_directory}}',
    @endforeach
    ],
    width: mainImageWidth,
    height: mainImageHeight,
    //responsive: true,
    sense: -1,
    loop: true,
    animate: false,
    plugins: [
      '360',
      'drag'
    ],
     onFrame: function(e, data) {
    //console.log(data.frames,'hello')
    },
    onInit: function(e, data) {
        
        $('.t60degreeview span.drag_left').click(function(){
            const frames = data.frames,
                  frame  = data.frame,
                  move   = frame-1;
            SpriteSpin.updateFrame(data, move);
        })
        
        $('.t60degreeview span.drag_right').click(function(){
            const frames = data.frames,
                  frame  = data.frame,
                  move   = frame+1;
            SpriteSpin.updateFrame(data, move);
        })
    }
  });
  
  $('.embed-youtube-video iframe').width(mainImageWidth)
  $('.embed-youtube-video iframe').height(mainImageHeight)
  $('.zoom-warp').width(mainImageWidth)
 

  
  $('.detect-gallery-type').click(function(){
      const type = this.dataset.type
      
      if(type == '360degree'){
          $('.t60degreeview').css('display','')
          $('.embed-youtube-video').css('display','none')
          $('.zoom-lg').css('display','none')
      }else if(type == 'image'){
          $('.t60degreeview').css('display','none')
          $('.embed-youtube-video').css('display','none')
          $('.zoom-lg').css('display','')         
      }else if(type == 'youtube'){
          $('.t60degreeview').css('display','none')
          $('.embed-youtube-video').css('display','')
          $('.zoom-lg').css('display','none')   
      }
      
      
  })
  
})
</script>

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

            master_pricing();




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
                
                // console.log($('.product-image-zoom').css('position','static'))

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

            
            $('body').on('click','.item-color-front a img',function(a){
                a.preventDefault();
                
                      var zoomElement = $('.zoom-lg #img_01');

                        // destroy old one
                        zoomElement.removeData('zoom-image');
                        $('.zoomContainer').remove();
                        
                        // set new one
                        zoomElement.attr('data-zoom-image', this.src);
                        zoomElement.attr('src', this.src);
                        
                        // reinitial elevatezoom
                        zoomElement.elevateZoom();
                    
                    // $('.zoomWrapper img.product-image-zoom').unwrap();
                    $(".zoom-lg #img_01").unbind("touchmove");

            })


        });




        function master_pricing() {
            // alert('workig');
            var main_pid =  {{$pro->id}};
            var  item_code = {{$pro->sku}}
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
                    console.log(data);
                    jQuery('#xhmx_color').html(data.data['color']);
                    jQuery('#xhmx_size').html(data.data['size']);
                    jQuery('#price_tag').html(data.data['price']);
                    
                    if(data.data['item_code'] !=null){
                        item_code = data.data['item_code'];
                    }
                     jQuery('#v-itemcode').html(item_code);
                    
                    
                    // master_price();

                },
                error: function () {

                }
            });
            //alert(stories_price);
        }


    </script>

    <script>

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
    </style>
@endsection
