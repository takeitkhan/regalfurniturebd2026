jQuery(document).ready(function ($) {
    $.noConflict();

    $(window).scroll(function () {
        var scroll = $(window).scrollTop();

        if (scroll > 100) {
            $(".header-area").addClass("menu_affix"); // you don't need to add a "." in before your class name
        } else {
            $(".header-area").removeClass("menu_affix");
        }
    });


    /*--------------------------------------------------------------
    ## Owl Carousel Activated
    --------------------------------------------------------------*/
    $('.slider-area').owlCarousel({
        loop: true,
        nav: false,
        items: 1,
        autoplay: true,
        autoplaySpeed: 3000,
        autoplayTimeout: 4000,
        navSpeed: 3000,
        dots: true,
        animateOut: 'fadeOut',
        animateIn: 'fadeIn',
        mouseDrag: false,
        touchDrag: true,

    });
    $('.slider-area .owl-dots .owl-dot').hover(function () {
        $(this).click();
    });

    /*--------------------------------------------------------------
       ## Owl Carousel Activated
  --------------------------------------------------------------*/
    $('.testimonial-warp').owlCarousel({
        loop: true,
        nav: false,
        autoplay: true,
        autoplaySpeed: 2000,
        autoplayTimeout: 6000,
        navSpeed: 1000,
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
    });

    /*--------------------------------------------------------------
       ## Owl Carousel Activated
  --------------------------------------------------------------*/
    $('.choose-us-warp').owlCarousel({
        loop: true,
        nav: false,
        autoplay: true,
        autoplaySpeed: 2000,
        autoplayTimeout: 6000,
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
    });

    /*--------------------------------------------------------------
         ## Owl Carousel Activated
    --------------------------------------------------------------*/
    $('.bought-together').owlCarousel({
        loop: true,
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
                items: 2
            },
            800: {
                items: 2
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
    });
    /*--------------------------------------------------------------
         ## Owl Carousel Activated
    --------------------------------------------------------------*/
    $('.similar-products').owlCarousel({
        loop: true,
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
                items: 2
            },
            800: {
                items: 2
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
    });

    /*--------------------------------------------------------------
       ## Owl Carousel Activated
  --------------------------------------------------------------*/
    $('.recently-viewed').owlCarousel({
        loop: true,
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
                items: 4
            },
            1400: {
                items: 5
            },
        }
    });

    /*--------------------------------------------------------------
       ## Owl Carousel Activated
  --------------------------------------------------------------*/
    $('.piclist').owlCarousel({
        loop: true,
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
    });
    /*--------------------------------------------------------------
         ## Owl Carousel Activated
    --------------------------------------------------------------*/
    $('.emi-bank-warp').owlCarousel({
        loop: true,
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
    });
    /*--------------------------------------------------------------
             ## product grit
    -------------------------------------------------------------*/

    $(".two-list").click(function () {
        $(".grit-row").addClass("land-two").css("transition", ".6s");
    });
    $(".four-list").click(function () {
        $(".grit-row").removeClass("land-two").css("transition", ".6s");
    });


});


jQuery(document).on("click", '.mcbi_add', function (e) {
    var self = this;
    var value = jQuery(self).closest('.mcbi_box').find('.mcbi_view').val();
    var newvalues = parseFloat(value) + 1;
    jQuery(self).closest('.mcbi_box').find('.mcbi_view').val(newvalues);

});

jQuery(document).on("click", '.mcbi_sub', function (e) {
    var self = this;
    var value = jQuery(self).closest('.mcbi_box').find('.mcbi_view').val();
    if (value > 1) {
        var newvalues = parseFloat(value) - 1;
        jQuery(self).closest('.mcbi_box').find('.mcbi_view').val(newvalues);
    }
});

function openNav() {
    document.getElementById("mySidenav").style.width = "250px";
}

function closeNav() {
    document.getElementById("mySidenav").style.width = "0";
}


jQuery(window).bind('resize load', function () {
    if (jQuery(this).width() < 767) {
        jQuery('.collapse').removeClass('show');
        jQuery('.collapse').addClass('out');
    }
});


  