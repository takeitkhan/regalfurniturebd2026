/**
 *
 * @param productid
 * @param productcode
 * @param productsku
 * @param regularprice
 * @param saveprice
 * @param purchaseprice
 * @param deliverycharge
 * @param imageurl
 * @param qty
 */
item_cart_load();
item_compare_load();
item_wishlist_load();
function add_to_cart(productid, productcode, productsku, regularprice, saveprice, purchaseprice, deliverycharge, imageurl, qty, colorsize_id) {

    var quantity = jQuery('#quantity').val();
    //alert(qty);

    if (quantity !== null && quantity > 1) {
        var qty1 = jQuery('#quantity').val();
    } else {
        var qty1 = qty;
    }

    var suv = jQuery('#show_unit_values').val();

    //alert(suv);

    if (suv !== undefined && suv !== null) {
        var npp = suv * purchaseprice;
    } else {
        var npp = 1 * purchaseprice;
    }
    //alert(npp);

    var data = {
        'productid': productid,
        'productcode': productcode,
        'productsku': productsku,
        'regularprice': parseInt(regularprice),
        'saveprice': parseInt(saveprice),
        'purchaseprice': parseInt(npp),
        'deliverycharge': parseInt(deliverycharge),
        'imageurl': imageurl,
        'qty': qty1,
        'colorsize_id': colorsize_id
    };

    jQuery.ajax({
        url: baseurl + '/add_to_cart',
        method: 'get',
        data: data,
        success: function(data) {
            console.log(data);
            jQuery("#getCode").html(data.data);
            jQuery("#getCodeModal").modal('show');
            jQuery("span#items_total").html(data.total_amount);
            jQuery("span#items_count").html(data.total_qty);
            jQuery("span#items_total").html(data.total_amount);
            jQuery("span#items_count").html(data.total_qty);
        },
        error: function() {
            // showError('Sorry. Try reload this page and try again.');
            // processing.hide();
        }
    });
}

function add_to_cart_data(self) {

    var main_pid = jQuery(self).attr('data-productid');
    var size = jQuery(self).attr('data-size_id');
    var color = jQuery(self).attr('data-color_id');
    var qty = jQuery(self).attr('data-qty');


    var data = {
        'main_pid': main_pid,
        'size': size,
        'color': color,
        'qty': qty,
    };

    jQuery.ajax({
        url: baseurl + '/add_to_cart',
        method: 'get',
        data: data,
        success: function(data) {

            if(data.report == 'Yes'){
                jQuery("#getCode").html(data.data);
                jQuery("#getCodeModal").modal('show');
                jQuery("span#items_total").html(data.total_amount);
                jQuery("span#items_count").html(data.total_qty);
                jQuery("span#items_total").html(data.total_amount);
                jQuery("span#items_count").html(data.total_qty);
                
                // Google Datalayer
                    dataLayer.push({
                                    'event': 'addToCart',			//used for creating GTM trigger
                                    'ecommerce': {
                                                    'currencyCode': 'BDT',   
                                                'add': {
                                                      'products': [{
                                                        'id': main_pid,
                                                        'name': data.product_title,
                                                        'price': data.product_pprice,
                                                        'brand': 'Regal',
                                                        'category': data.cat_name,
                                                        'variant': color,
                                                        'dimension1': size,
                                                        'position': 0,
                                                        'quantity': qty
                                                      }]
                                                }
                                              }
                                            });
            
                
                item_cart_load();
            }else {
                alert('Please Select color and size');
            }

        },
        error: function() {
            // showError('Sorry. Try reload this page and try again.');
            // processing.hide();
        }
    });
    item_cart_load();
}

function item_cart_load() {

    jQuery.ajax({
        url: baseurl + '/item_cart_load',
        method: 'get',
        success: function(data) {
            console.log(data);
            jQuery("#cart_item_ajax").html(data.html);
        },
        error: function() {
            // showError('Sorry. Try reload this page and try again.');
            // processing.hide();
        }
    });
}



/**
 *
 * @param id
 */



function remove_cart_item(self) {



    swal({
        title: "Are you sure?",
        text: "Once deleted, you will not be able to recover this imaginary file!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then((willDelete) => {

        if (willDelete) {
            var post_data = {
                'productid': jQuery(self).attr('data-id'),
                'productcode': jQuery(self).attr('data-code'),
            };

            jQuery.ajax({
                url: baseurl + '/remove_cart_item',
                method: 'get',
                data: post_data,
                success: function(data) {
                    jQuery("span#items_total").html(data.total_amount);
                    jQuery("span#items_count").html(data.total_qty);
                    jQuery("span#items_total").html(data.total_amount);
                    jQuery("span#items_count").html(data.total_qty);
                    
                        dataLayer.push({
                          'event': 'removeFromCart',			//used for creating GTM trigger
                           'ecommerce': {  
                            'remove': {
                              'products': [{
                                'id': post_data.productid,
                                'name': jQuery(self).attr('data-title'),
                                'price': jQuery(self).attr('data-price'),
                                'brand': 'Regal',
                                'category': 'Furniture',
                                'variant': jQuery(self).attr('data-color'),
                                'dimension1': 'M',
                                'position': 0,
                                'quantity': jQuery(self).attr('data-qty')
                              }]
                            }
                          }
                        });

                    location.reload();
                    //jQuery("div.reloader").load(baseurl + "/view_cart div.reloader");
                },
                error: function() {
                    // showError('Sorry. Try reload this page and try again.');
                    // processing.hide();
                }
            });
        } else {
            swal("Your imaginary file is safe!");
        }


    });

}


/**
 *
 * @param productid
 * @param productcode
 */
function add_to_compare(productid, productcode, seo_url) {

    var data = {
        'productid': productid,
        'productcode': productcode,
        'seo_url': seo_url
    };

    jQuery.ajax({
        url: baseurl + '/add_to_compare',
        method: 'get',
        data: data,
        success: function(data) {
            jQuery("#getCode").html(data.data);
            jQuery("#getCodeModal").modal('show');
            jQuery("div#show_total_compare").html(data.total_item);
            console.log(data.total_item);
        },
        error: function() {
            // showError('Sorry. Try reload this page and try again.');
            // processing.hide();
        }
    });

}

/**
 *
 * @param id
 */
function remove_compare_product(id, code) {

    var data = {
        'productid': id,
        'productcode': code,
    };

    jQuery.ajax({
        url: baseurl + '/remove_compare_product',
        method: 'get',
        data: data,
        success: function(data) {
            //update_compare_box();
            location.reload();
            //jQuery("div.reloader").load(baseurl + " div.reloader");
        },
        error: function() {
            // showError('Sorry. Try reload this page and try again.');
            // processing.hide();
        }
    });
}

/**
 *
 * @param productid
 * @param productcode
 */
function add_to_wishlist(productid, productcode, seo_url) {

    var data = {
        'productid': productid,
        'productcode': productcode,
        'seo_url': seo_url
    };

    jQuery.ajax({
        url: baseurl + '/add_to_wishlist',
        method: 'get',
        data: data,
        success: function(data) {
            jQuery("#getCode").html(data.data);
            jQuery("#getCodeModal").modal('show');
            jQuery("div#show_total_wishlist").html(data.total_item);
            
    
            if(data.success){
                dataLayer.push({
                  'event': 'addToWishlist',			//used for creating GTM trigger
                  'ecommerce': {
                     'currencyCode': 'BDT',   
                    'add': {
                      'products': [{
                        'id': data.product.id,
                        'name': data.product.name,
                        'price': '0',
                        'brand': 'Regal',
                        'category': data.product.cat_name,
                        'variant': '',
                        'dimension1': '',
                        'position': 0,
                        'quantity': 1
                      }]
                    }
                  }
                });                
            }

            
            
        },
        error: function() {
            // showError('Sorry. Try reload this page and try again.');
            // processing.hide();
        }
    });

}

/**
 *
 * @param id
 */
function remove_wishlist_product(id, code) {




    swal({
        title: "Are you sure?",
        text: "Once deleted, you will not be able to recover this imaginary file!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then((willDelete) => {

        if (willDelete) {
            var data = {
                'productid': id,
                'productcode': code,
            };

            jQuery.ajax({
                url: baseurl + '/remove_wishlist_product',
                method: 'get',
                data: data,
                success: function(data) {

                    location.reload();

                },
                error: function() {
                    // showError('Sorry. Try reload this page and try again.');
                    // processing.hide();
                }
            });



        } else {
            swal("Your imaginary file is safe!");
        }


    });


}


/**
 *
 * @param val
 * @returns {*}
 */
function commaSeparateNumber(val) {
    while (/(\d+)(\d{3})/.test(val.toString())) {
        val = val.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
    }
    return val;
}

/**
 *
 * @param number
 * @param decimals
 * @param dec_point
 * @param thousands_sep
 */
function numberFormat(number, decimals, dec_point, thousands_sep) {
    // http://kevin.vanzonneveld.net/techblog/article/javascript_equivalent_for_phps_number_format/
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function(n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}

function add_to_compare_all(self) {

      var productid = jQuery(self).attr('data-proid');
      var productcode = jQuery(self).attr('data-procode');
      var seo_url = jQuery(self).attr('data-url');
      var multi_id = jQuery(self).attr('data-multi');


        var data = {
            'productid': productid,
            'productcode': productcode,
            'seo_url': seo_url,
            'multi_id': multi_id
        };

        jQuery.ajax({
            url: baseurl + '/add_to_compare',
            method: 'get',
            data: data,
            success: function(data) {
                jQuery("#getCode").html(data.data);
                jQuery("#getCodeModal").modal('show');

                item_compare_load();
                //console.log(data.total_item);

            },
            error: function() {
                // showError('Sorry. Try reload this page and try again.');
                // processing.hide();
            }
        });
}

function add_to_wish_list(self) {
    var proid = jQuery(self).attr('data-proid');
    //alert(proid);

    jQuery.ajax({

        url: baseurl + '/add_to_wishlist',
        type: 'get',
        data: {
            'product_id': proid
        },
        success: function(result) {

            if(result.type == 1){
                swal({
                    title: result.message,
                    icon: "success"
                });
            }else if(result.type == 2){
                swal({
                    title: result.message,
                    icon: "warning"
                })
            }else if(result.type == 3){
                swal({
                    title: result.message,
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                    .then((willDelete) => {
                        if (willDelete) {
                            window.location = baseurl+"/login_now";
                        }
                    });
            }

            item_wishlist_load();
        }
    });
}

/* ---------------------------------------------------
	Searche script
-------------------------------------------------- */

jQuery(document).ready(function($) {
    $.noConflict();

    $("#main-srch-suggest").hide();

    $(document).click(function(event) {
        //if you click on anything except the modal itself or the "open modal" link, close the modal
        if (!$(event.target).closest("main-srch-suggest").length) {
            $("body").find("#main-srch-suggest").hide();
        }
    });


    $(document).on("keyup", '#main-srch-keyword', function(e) {
        var self = this;
        var keyword = $(self).val();
        var cat = $('#main-srch-cat').val();

        jQuery.ajax({
            url: baseurl + '/main_search_product_ajax',
            method: 'get',
            data: { 'keyword': keyword, 'cat': cat },
            success: function(data) {
                //console.log(data);
                //alert(data);
                $('#main-srch-suggest').show();
                $('#main-srch-suggest').html(data.html);
            },
            error: function() {

            }
        });


    });


});

function item_compare_load() {

    jQuery.ajax({
        url: baseurl + '/item_compare_load',
        method: 'get',
        success: function(data) {

            jQuery("#show_total_compare").html(data.html);


        },
        error: function() {

        }
    });
}

function item_wishlist_load() {

    jQuery.ajax({
        url: baseurl + '/item_wishlist_load',
        method: 'get',
        success: function(data) {

            jQuery("#show_total_wishlist").html(data.html);
        },
        error: function() {

        }
    });
}