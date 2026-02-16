@section('cusjs')

    <?php

    $url_one = \Request::segment(1);
    $url_two = \Request::segment(2);
    $cat_url = '/' . $url_one . '/' . $url_two;

    ?>
 
    <script type="text/javascript">

        function OpenSideBar()
        {
             document.getElementById("MysideName").style.width = "250px";
        }
        function CloseSideBar() {
            document.getElementById("MysideName").style.width = "0";
        }
       

        jQuery(document).ready(function ($) {
            $.noConflict();

            
        // document.addEventListener('click',function(e){
        //  if($(e.target).closest('#OpenSarNav,.hidedown').length > 0)
        //  {
        //        return
        //  }
            
        // closeSearchBar()
        // })

        document.addEventListener('click',function(e){
         if($(e.target).closest('#MysideName,.bar').length > 0)
         {
               return
         }
            
        CloseSideBar()
        })



        ///id:slider-range
        // $(".price-filter-range").slider({
        //   range: true,
        //   orientation: "horizontal",
        //   min: 0,
        //   max: 10000,
        //   values: [0, 10000],
        //   step: 100,

        //   slide: function (event, ui) {
        //     if (ui.values[0] == ui.values[1]) {
        //       return false;
        //     }
            
        //     $(".min_price").val(ui.values[0]);
        //     $(".max_price").val(ui.values[1]);
        //   }
        // });

            var s_min = $(".min_price").val();
            var s_max = $(".max_price").val();
            var sr_max = $(".max_price").attr('data-max-range');
            $(".price-filter-range").slider({
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
                    $(".min_price").val(ui.values[0]);
                    $(".max_price").val(ui.values[1]);
                }
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
            $(document).on('click', '.sumbit-price-range', function () {
                main_search();
    
            })

            //id
            $(document).on("change", ".item_sort", function (e) {
                //alert('working');
                var self = this;
                var valu = $('option:selected', self).val();
               // alert(valu);
                $('.sort_by').val(valu);
    
                main_search();
            });

            //id
            $(document).on("change", ".item_count", function (e) {
                var self = this;
                var valu = $('option:selected', self).val();
                $('#sort_show').val(valu);
    
                main_search();
            })

            //id
            $('.filtering input[type="checkbox"]').on('click change', function () {
                main_search();
            });

            ///id
            $('.filtering .keyword_filter_submit').on('click', function () {
                main_search();
            });


            //id
            $('.filtering .keyword_filter_reset').on('click', function () {
                $('.filtering .keyword_filter').val('');
                main_search();
            });
    
            // function main_search() {
            //     var pref_url = baseurl + '{{$cat_url.'?'}}';
            //     var str = $(".filtering").find('input[name!=_token]').serialize();
            //     window.location.replace(pref_url + str);
            // }


            function main_search() {
                var pref_url = baseurl + '{{$cat_url.'?'}}',
                    width = $(window).width(),
                    height = $(window).height(),
                    str;

                    if(width > 965){
                        str = $($(".filtering")[1]).find('input[name!=_token]').serialize()
                    }else {
                        str = $($(".filtering")[0]).find('input[name!=_token]').serialize()
                    }

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
 
        .product-over {
        overflow: visible;
        position: relative;
        display:inline-block;
        width:100%;
       
        }
        
        
        .disable-buy-button {
            margin-top:-50px;
            border: transparent;
            color: #FFF;
            font-weight: 600;
            padding: 2px 20px;
            font-size: 15px;
            border-radius: 4px;
        }
        
    
    @media screen and (max-width: 1920px) {
         .product-price-btn.buy-btn.not-available-btn {
            position: absolute;
            top: 5px;
            left: 5px;
        }
}
/* @media screen and (max-width: 1440px) {*/
/*.product-price-btn.buy-btn.not-available-btn {*/
/*     width:225px;*/
/*     left:6%;*/
/*}}*/
/* @media screen and (max-width: 1366px) {*/
/*.product-price-btn.buy-btn.not-available-btn {*/
/* width:200px;*/
  
/*}}*/
/* @media screen and (max-width: 1280px) {*/
/*.product-price-btn.buy-btn.not-available-btn {*/
/* width:160px;*/
/* left:15%;*/
  
/*}}*/

     @media screen and (max-width: 1024px) {
.product-price-btn.buy-btn.not-available-btn {
    
  
}
  .single-product {
    padding: 0px;
}       
         
         
         
     }
 
  @media screen and (max-width: 768px) {
    /*  .product-price-btn.buy-btn.not-available-btn {*/
    /*   left:25%;*/
    /*}*/
    .product-title h3 {
        font-size: 15px;
    }
    .product-over-right {
       /* width: 100%;*/
        float: none;
    
    }
  
    .wishlist-wp {
        top: 8px;
        transform: translateX(20px);
    }
      
}
    @media screen and (max-width: 640px) {
        .product-price-btn.buy-btn.not-available-btn {
               
        }
    }
   @media screen and (max-width: 480px) {
    .product-price-btn.buy-btn.not-available-btn {
    
    }
    
    .disable-buy-button {
        margin-top: 0px;
        font-weight: 400;
        padding: 2px 20px;
        top: unset;
        bottom: 11px;
        right: 7px;
        font-size: 12px;
        position: absolute;
        border-radius: 4px;
    }
         
     }
      @media screen and (max-width: 360px) {
        /* .product-price-btn.buy-btn.not-available-btn {*/
        
        /*    left: -15%;*/
        
        /*}*/
        .product-price-btn.buy-btn input{
            /* margin-left:0px !important; */
        }
        .product-over-left{
            float:none;
        }
        .product-over-right{
            width:100%;
            float:none;
        }
        .disable-buy-button{
           position: inherit;
            bottom:0px;
            right:0px;
        }
      
       .recently-viewed-btn a {
           font-size:10px;
       }
    }
      
      
  }

      
    </style>

@endsection
