
jQuery(document).ready(function ($) {
    $.noConflict();
    

    // $(window).scroll(function () {
    //     var scroll = $(window).scrollTop();

    //     if (scroll > 100) {
    //         $(".header-area").addClass("menu_affix"); // you don't need to add a "." in before your class name
    //     } else {
    //         $(".header-area").removeClass("menu_affix");
    //     }
    // });

    $(document).ready(function () {  
      var top = $('.header-area').offset().top;
      $(window).scroll(function (event) {
        var y = $(this).scrollTop();
            if (y >= top) {
              $('.header-area').addClass('fixed');
              $(".frontend_content").addClass('stick-top-pad')
            } else {
              $('.header-area').removeClass('fixed');
              $(".frontend_content").removeClass('stick-top-pad')
            }
            $('.header-area').width($('.header-area').parent().width());
        });
    });




    /*--------------------------------------------------------------
    ## Owl Carousel Activated
    --------------------------------------------------------------*/
    $('.flash-slider').owlCarousel({
        loop: false,
        nav: true,
        // items: 5,
        autoplay: false,
        autoplaySpeed: 3000,
        autoplayTimeout: 4000,
        navSpeed: 3000,
        dots: false,
        navText: ["<i class='fa fa-chevron-left'></i>", "<i class='fa fa-chevron-right'></i>"],
        // animateOut: 'fadeOut',
        // animateIn: 'fadeIn',
        // mouseDrag: false,
        // touchDrag: true,
        responsive: {
            0: {
                items: 1
            },
            400: {
                items: 2
            },
            600: {
                items: 3
            },
            800: {
                items: 3
            },
            1000: {
                items: 3
            },
            1200: {
                items: 5
            },
            1400: {
                items: 5
            },
        }

    });
   

   /*--------------------------------------------------------------
    ## Owl Carousel Activated
    --------------------------------------------------------------*/
    $('#banner-slider').owlCarousel({
        loop: true,
        nav: true,
        items: 1,
        autoplay: true,
        autoplaySpeed: 4000,
        autoplayTimeout:4000,
        navSpeed: 4000,
        dots: false,
        animateOut: 'fadeOut',
        animateIn: 'fadeIn',
        mouseDrag: true,
        touchDrag: true,
        navText: ["<i class='fa fa-chevron-left'></i>", "<i class='fa fa-chevron-right'></i>"],

    });
    // $('#banner-slider .owl-dots .owl-dot').hover(function () {
    //     $(this).click();
    // });

    /*--------------------------------------------------------------
       ## Owl Carousel Activated
  --------------------------------------------------------------*/
    $('.testimonial-warp').owlCarousel({
    loop:true,
    autoplay:true,
    autoplayTimeout:5000,
    autoplayHoverPause:true,
    nav:false,
    navSpeed:7000,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 1
            },
            1000: {
                items: 1
            }
        }
    })

    /*--------------------------------------------------------------
       ## Owl Carousel Activated
  --------------------------------------------------------------*/
    // $('.flash-slider').owlCarousel({
    //     loop: false,
    //     nav: false,
    //     autoplay: true,
    //     autoplaySpeed: 2000,
    //     autoplayTimeout: 6000,
    //     navSpeed: 1000,
    //     responsive: {
    //         0: {
    //             items: 3
    //         },
    //         400: {
    //             items: 5
    //         },
    //         600: {
    //             items: 5
    //         },
    //         800: {
    //             items: 6
    //         },
    //         1000: {
    //             items: 7
    //         },
    //         1341: {
    //             items: 7
    //         },
    //         1680: {
    //             items: 8
    //         },
    //     }
    // });

/*--------------------------------------------------------------
       ## Owl Carousel Activated
  --------------------------------------------------------------*/
    $('.choose-us-warp').owlCarousel({
        loop: true,
        nav: false,
        autoplay: true,
        autoplayTimeout: 2000,
        autoplayHoverPause:true,
        navSpeed: 1000,
        responsive: {
            0: {
                items: 3
            },
            400: {
                items: 5
            },
            600: {
                items: 5
            },
            800: {
                items: 6
            },
            1000: {
                items: 7
            },
            1341: {
                items: 7
            },
            1680: {
                items: 8
            },
        }
    })

    /*--------------------------------------------------------------
         ## Owl Carousel Activated
    --------------------------------------------------------------*/
    $('.bought-together').owlCarousel({
        loop: false,
        nav: true,
        autoplay: true,
        autoplaySpeed: 1500,
        autoplayTimeout: 400,
        navSpeed: 700,
        navText: ["<i class='fa fa-chevron-left'></i>", "<i class='fa fa-chevron-right'></i>"],
        dots: false,
        responsive: {
            0: {
                items: 2
            },
            400: {
                items: 2
            },
            600: {
                items: 3
            },
            800: {
                items: 3
            },
            1000: {
                items: 3
            },
            1200: {
                items: 4
            },
            1400: {
                items: 5
            },
        }
    })
    /*--------------------------------------------------------------
         ## Owl Carousel Activated
    --------------------------------------------------------------*/
    $('.similar-products').owlCarousel({
        loop: false,
        nav: true,
        autoplay: true,
        autoplaySpeed: 1600,
        autoplayTimeout: 5000,
        navSpeed: 900,
        navText: ["<i class='fa fa-chevron-left'></i>", "<i class='fa fa-chevron-right'></i>"],
        dots: false,
        responsive: {
            0: {
                items: 2
            },
            400: {
                items: 2
            },
            600: {
                items: 3
            },
            800: {
                items: 3
            },
            1000: {
                items: 3
            },
            1200: {
                items: 5
            },
            1400: {
                items: 5
            },
        }
    })

    /*--------------------------------------------------------------
       ## Owl Carousel Activated
  --------------------------------------------------------------*/
    $('.recently-viewed').owlCarousel({
        loop: false,
        nav: true,
        autoplay: true,
        autoplaySpeed: 2000,
        autoplayTimeout: 6000,
        navSpeed: 1000,
        navText: ["<i class='fa fa-chevron-left'></i>", "<i class='fa fa-chevron-right'></i>"],
        dots: false,
        responsive: {
            0: {
                items: 2
            },
            400: {
                items: 2
            },
            600: {
                items: 3
            },
            800: {
                items: 3
            },
            1000: {
                items: 3
            },
            1200: {
                items: 5
            },
            1400: {
                items: 5
            },
        }
    })

    /*--------------------------------------------------------------
       ## Owl Carousel Activated
  --------------------------------------------------------------*/
    $('.piclist').owlCarousel({
        loop: false,
        nav: true,
        autoplay: true,
        margin: 0,
        autoplaySpeed: 2000,
        autoplayTimeout: 6000,
        navSpeed: 1000,
        navText: ["<i class='fa fa-chevron-left'></i>", "<i class='fa fa-chevron-right'></i>"],
        dots: false,
        responsive: {
            0: {
                items: 4
            },
            600: {
                items: 5
            },
            1000: {
                items: 3
            },
            1200: {
                items: 4
            },
            1400: {
                items: 5
            },

        }
    })
    /*--------------------------------------------------------------
         ## Owl Carousel Activated
    --------------------------------------------------------------*/
    $('.emi-bank-warp').owlCarousel({
        loop: false,
        nav: true,
        autoplay: true,
        autoplaySpeed: 2000,
        autoplayTimeout: 6000,
        navSpeed: 1000,
        navText: ["<i class='fa fa-chevron-left'></i>", "<i class='fa fa-chevron-right'></i>"],
        dots: false,
        responsive: {
            0: {
                items: 3
            },
            600: {
                items: 6
            },
            1000: {
                items: 6
            },
            1200: {
                items: 4
            },
            1400: {
                items: 5
            },
        }
    })




    /*--------------------------------------------------------------
             ## product grit
    -------------------------------------------------------------*/

    $(".two-list").click(function () {
        $(".grit-row").addClass("land-two").css("transition", ".6s");
    });
    $(".four-list").click(function () {
        $(".grit-row").removeClass("land-two").css("transition", ".6s");
    });

    

// click event single page

$(".ove-hidenk1").click(function(){
    $(".prosuct-sg-dec_ahrselrc").css('height', '100%');
  });

$(".ove-hidenk1").click(function(){
    $(this).hide();
  });


$(".ove-hidenk2").click(function(){
    $(".specifications").css('height', '100%');
  });

$(".ove-hidenk2").click(function(){
    $(this).hide();
  });

$(".ove-hidenk3").click(function(){
    $(".prosuct-sg-dec-area_002").css('height', '100%');
  });

$(".ove-hidenk3").click(function(){
    $(this).hide();
  });

$(".ove-hidenk4").click(function(){
    $(".prosuct-sg-dec_001").css('height', '100%');
  });

$(".ove-hidenk4").click(function(){
    $(this).hide();
  });



//$('#one').tooltip(options)

}); // End of use strict


jQuery(document).on("click", '.mcbi_add', function (e) {
    var self = this;
    var value = jQuery(self).closest('.mcbi_box').find('.mcbi_view').val();
    if(value){
        var newvalues = parseFloat(value) + 1;
    }else{
        var newvalues = 1;

    }
    //var newvalues = parseFloat(value) + 1;
    jQuery(self).closest('.mcbi_box').find('.mcbi_view').val(newvalues);
    jQuery('#button-cart').attr('data-qty',newvalues);

});

jQuery(document).on("keyup", '.mcbi_view', function (e) {
    var self = this;
    var value =  jQuery(self).val();
    if(value < 1){
        value = 1;
        jQuery(self).val('');
        jQuery('#button-cart').attr('data-qty',value);
    }else {
        jQuery(self).val(value);
        jQuery('#button-cart').attr('data-qty',value);
    }



});


jQuery(document).on("click", '.mcbi_sub', function (e) {
    var self = this;
    var value = jQuery(self).closest('.mcbi_box').find('.mcbi_view').val();
    if(value){
        var value = value;
    }else{
        var value = 2;

    }

    if (value > 1) {
        var newvalues = parseFloat(value) - 1;
        jQuery(self).closest('.mcbi_box').find('.mcbi_view').val(newvalues);
        jQuery('#button-cart').attr('data-qty',newvalues);
    }
    
});

