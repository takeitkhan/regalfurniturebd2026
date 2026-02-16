@extends('layouts.app')

@section('title', 'Product')
@section('sub_title', 'product add or modification form')
@section('content')
    <div class="row">
        @if(Session::has('success'))
            <div class="col-md-12">
                <div class="callout callout-success">
                    {{ Session::get('success') }}
                </div>
            </div>
        @endif

        {{--@endif--}}
        @if($errors->any())
            <div class="col-md-12">
                <div class="callout callout-danger">
                    <h4>Warning!</h4>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <?php

        use Illuminate\Support\Facades\Request;

        if (!empty($product->id)) {
            $url = 'edit_product/' . $product->id;
        } else {
            $url = 'add_product';
        }

        //dd(Request::get('p'));
        ?>

        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        <div class="nav-tabs-custom">

                            <ul class="nav nav-tabs">
                                @if (!empty($product))

                                    @if ($product->product_set_id != null)
                                        <li class="">
                                            <a href="{{ url('admin/product-set-edit/'.$product->product_set_id) }}"
                                               class="{{ (Request::get('p') == 'ba') ? 'tab-active' : null }}">
                                                Basic
                                            </a>
                                        </li>
                                        <li class="">
                                            <a href="{{ url('admin/product-set-edit/'.$product->product_set_id.'?tab=fabric') }}"
                                               class="{{ (Request::get('p') == 'fabric') ? 'tab-active' : null }}">
                                                Fabric
                                            </a>
                                        </li>
                                        <li class="">
                                            <a href="{{ url('admin/product-set-edit/'.$product->product_set_id.'?tab=info') }}"
                                               class="{{ (Request::get('p') == 'info') ? 'tab-active' : null }}">
                                                Info
                                            </a>
                                        </li>
                                        <li class="">
                                            <a href="{{ url($url . '?p=links') }}"
                                               class="{{ (Request::get('p') == 'links') ? 'tab-active' : null }}">
                                                Links
                                            </a>
                                        </li>
                                    @else
                                        <li class="">
                                            <a href="{{ url($url . '?p=basic') }}"
                                               class="{{ (Request::get('p') == 'basic') ? 'tab-active' : null }}">
                                                Basic Information
                                            </a>
                                        </li>

                                        {{-- <li class="">
                                            <a href="{{ url($url . '?p=pset') }}"
                                               class="{{ (Request::get('p') == 'pset') ? 'tab-active' : null }}">
                                               Product Set
                                            </a>
                                        </li> --}}

                                        @php if(!empty($product->id)) : @endphp
                                        <li class="">
                                            <a href="{{ url($url . '?p=links') }}"
                                               class="{{ (Request::get('p') == 'links') ? 'tab-active' : null }}">
                                                Links
                                            </a>
                                        </li>

                                        <li class="">
                                            <a href="{{ url($url . '?p=others') }}"
                                               class="{{ (Request::get('p') == 'others') ? 'tab-active' : null }}">
                                                Other Information
                                            </a>
                                        </li>
{{--                                        @if($product->enable_variation == 'on')--}}
{{--                                        @else--}}
                                            <li class="">
                                                <a href="{{ url($url . '?p=images') }}"
                                                   class="{{ (Request::get('p') == 'images') ? 'tab-active' : null }}">
                                                    Images
                                                </a>
                                            </li>
{{--                                        @endif--}}
                                        <li class="">
                                            <a href="{{ url($url . '?p=videos') }}"
                                               class="{{ (Request::get('p') == 'videos') ? 'tab-active' : null }}">
                                                Videos
                                            </a>
                                        </li>
                                        <li class="">
                                            <a href="{{ url($url . '?p=360') }}"
                                               class="{{ (Request::get('p') == '360') ? 'tab-active' : null }}">
                                                360 Degree
                                            </a>
                                        </li>

                                        <li class="">
                                            <a href="{{ url($url . '?p=arview') }}"
                                               class="{{ (Request::get('p') == 'arview') ? 'tab-active' : null }}">
                                                AR View
                                            </a>
                                        </li>


                                        {{--                                <li class="">--}}

                                        {{--                                    <a href="{{ url($url . '?p=product-variation') }}"--}}
                                        {{--                                       class="{{ (Request::get('p') == 'product-variation') ? 'tab-active' : null }}">--}}
                                        {{--                                       Variation--}}
                                        {{--                                    </a>--}}

                                        {{--                                </li>--}}

                                        @php $attgroup = App\Models\ProductCategories::where('main_pid', $product->id)->where('is_attgroup_active', 1)->get()->first(); @endphp
                                        @if(!empty($attgroup->term_attgroup))
                                            <li class="">
                                                <a href="{{ url($url . '?p=attributes') }}"
                                                   class="{{ (Request::get('p') == 'attributes') ? 'tab-active' : null }}">
                                                    Attribute Groups
                                                </a>
                                            </li>
                                        @endif
                                        @php endif; @endphp

                                        @if(!empty($product->multiple_pricing) && $product->multiple_pricing == 'on')
                                            <li class="">
                                                <a href="{{ url($url . '?p=multiple_pricing') }}"
                                                   class="{{ (Request::get('p') == 'multiple_pricing') ? 'tab-active' : null }}">
                                                    Multiple Pricing
                                                </a>
                                            </li>
                                        @endif
                                        @if(!empty($product->emi_available) && $product->emi_available == 'on')
                                            <li class="">
                                                <a href="{{ url($url . '?p=emi_available') }}"
                                                   class="{{ (Request::get('p') == 'emi_available') ? 'tab-active' : null }}">
                                                    EMI Informations
                                                </a>
                                            </li>
                                        @endif
                                    @endif

                                    <li class="">
                                        <a href="{{ url($url . '?p=seo-settings') }}"
                                           class="{{ (Request::get('p') == 'seo-settings') ? 'tab-active' : null }}">
                                            Seo Settings
                                        </a>
                                    </li>
                                    @if($product->enable_variation == 'on')
                                        <li class="">
                                            <a href="{{ url($url . '?p=product-attribute') }}"
                                               class="{{ (Request::get('p') == 'product-attribute') ? 'tab-active' : null }}">
                                                Product Attribute
                                            </a>
                                        </li>
                                    @endif
                                @endif
                            </ul>
                            <div class="tab-content">
                                <?php if(Request::get('p') === 'basic' ) { ?>
                                @include('product.basic_information')
                                <?php } else if(!empty($product->id) && Request::get('p') === 'pset') { ?>
                                @include('product.product_set')
                                <?php } else if(!empty($product->id) && Request::get('p') === 'links') { ?>
                                @include('product.links_information')
                                <?php } else if(!empty($product->id) && Request::get('p') == 'others') { ?>
                                @include('product.other_information')
                                <?php } else if(!empty($product->id) && Request::get('p') == 'images') { ?>
                                @include('product.product_images')
                                <?php } else if(!empty($product->id) && Request::get('p') == 'attributes') { ?>
                                @include('product.product_attributes')
                                <?php } else if(!empty($product->id) && Request::get('p') == 'multiple_pricing') { ?>
                                @include('product.multiple_pricing')
                                <?php } else if(!empty($product->id) && Request::get('p') == 'emi_available') { ?>
                                @include('product.emi_available')
                                <?php } else if(!empty($product->id) && Request::get('p') == 'videos') { ?>
                                @includeIf('product.product_videos')
                                <?php } else if(!empty($product->id) && Request::get('p') == '360') { ?>
                                @includeIf('product.product_360degree')
                                <?php } else if(!empty($product->id) && Request::get('p') == 'arview') { ?>
                                @includeIf('product.product_arview')
                                <?php }else if(!empty($product->id) && Request::get('p') == 'product-variation') { ?>
                                @includeIf('product.productVariation')
                                <?php }else if(!empty($product->id) && Request::get('p') == 'seo-settings') { ?>
                                @includeIf('seo.form', [$post_type = 'product', $post_id = $product->id])
                                <?php }else if(!empty($product->id) && Request::get('p') == 'product-attribute') { ?>
                                @includeIf('product.attributes.product_attributes', [$prouduct = $product])
                                <?php } else { ?>
                                @include('product.basic_information')
                                <?php } ?>


                            </div>

                        </div>
                        <!-- /.tab-content -->
                    </div>
                </div>

            </div>
        </div>
    </div>





@endsection
@push('scripts')

<script src="{{ asset('public/plugins/dropzone.js') }}"></script>
<script src="{{ asset('public/js/dropzone-config.js') }}"></script>
<script src="{{ asset('public/js/jquery.czMore-latest.js') }}"></script>


    <script type="text/javascript">


        // function get_id(identifier) {
        //     //alert("data-id:" + jQuery(identifier).data('id') + ", data-option:" + jQuery(identifier).data('option'));
        //
        //
        //     var dataid = jQuery(identifier).data('id');
        //     jQuery('#image_ids').val(
        //         function (i, val) {
        //             return val + (!val ? '' : ', ') + dataid;
        //         });
        //     var option = jQuery(identifier).data('option');
        //     jQuery('#show_image_names').html(
        //         function (i, val) {
        //             return val + (!val ? '' : ', ') + option;
        //         }
        //     );
        // }

        function use_for_variation(identifier) {
            //alert("data-id:" + jQuery(identifier).data('id') + ", data-option:" + jQuery(identifier).data('option'));


            var dataid = jQuery(identifier).data('id');
            jQuery('#image_ids_on_variation' + dataid).val(
                function (i, val) {
                    return val + (!val ? '' : ', ') + dataid;
                });
            var option = jQuery(identifier).data('option');
            jQuery('#show_image_names_on_variation' + dataid).html(
                function (i, val) {
                    return val + (!val ? '' : ', ') + option;
                }
            );
        }


        $("#czContainer, #czContainer1").czMore();

        /**
         *
         */
        jQuery(document).ready(function ($) {
            $.noConflict();

            $('#title').blur(function () {
                var m = $(this).val();
                var cute1 = m.toLowerCase().replace(/ /g, '-').replace(/&amp;/g, 'and').replace(/&/g, 'and').replace(/ ./g, 'dec');
                var cute = cute1.replace(/[`~!@#$%^&*()_|+\=?;:'"”,.<>\{\}\[\]\\\/]/gi, '');

                $('#seo_url').val(cute);
            });

            $('#title').blur(function () {

                //alert(54654);
                var seo_url = $('#seo_url').val();
                var type = $('#seo_url').data('type');

                if (type == 'create') {
                    var data = {
                        'seo_url': seo_url
                    };

                    //console.log(data);

                    jQuery.ajax({
                        url: baseurl + '/check_if_url_exists',
                        method: 'get',
                        data: data,
                        success: function (data) {
                            //alert(data);
                            if (data.url != '') {
                                let r = Math.random().toString(36).substring(7);
                                $('#seo_url').val(data.url + '-' + r)
                            } else {
                                $('#seo_url').val(data.url);
                            }

                        },
                        error: function () {
                            // showError('Sorry. Try reload this page and try again.');
                            // processing.hide();
                        }
                    });
                }

                if (type == 'update') {
                    var data = {
                        'seo_url': seo_url
                    };

                    //console.log(data);

                    jQuery.ajax({
                        url: baseurl + '/check_if_url_exists',
                        method: 'get',
                        data: data,
                        success: function (data) {
                            //alert(data);
                            if (data.url != '') {
                                let r = Math.random().toString(36).substring(7);
                                $('#seo_url').val(data.url + '-' + r)
                            } else {
                                $('#seo_url').val(data.url);
                            }

                        },
                        error: function () {
                            // showError('Sorry. Try reload this page and try again.');
                            // processing.hide();
                        }
                    });
                }

            });

            /** Parent Information Taker **/
            $('#parent_id').blur(function () {
                var id = jQuery("#parent_id").val();

                var data = {
                    'productid': id
                };

                jQuery.ajax({
                    url: baseurl + '/get_product_json_data',
                    method: 'get',
                    data: data,
                    success: function (data) {
                        var obj = JSON.parse(data);
                        jQuery('#title').val(obj.title);
                        jQuery('#sub_title').val(obj.sub_title);
                        jQuery('#seo_url').val(obj.seo_url);
                        jQuery('#wysiwyg').val(obj.wysiwyg);
                        jQuery('#image_ids').val(obj.image_ids);
                        jQuery('#product_code').val(obj.product_code);
                        jQuery('#sku').val(obj.sku);
                        jQuery('#color').val(obj.color);
                        jQuery('#qty').val(obj.qty);
                        jQuery('#material').val(obj.material);
                        jQuery('#dimension').val(obj.dimension);
                        jQuery('#brand').val(obj.brand);
                        jQuery('#vendor').val(obj.vendor);
                        jQuery('#manufacturer').val(obj.manufacturer);
                        jQuery('#supplier').val(obj.supplier);
                        jQuery('#fabrics').val(obj.fabrics);
                        jQuery('#delivery_area').val(obj.delivery_area);
                        jQuery('#delivery_charge').val(obj.delivery_charge);
                        jQuery('#delivery_time').val(obj.delivery_time);
                        jQuery('#offer_details').val(obj.offer_details);
                        jQuery('#date').val(obj.date);
                        jQuery('#date_again').val(obj.date_again);
                        jQuery('#wysiwyg1').val(obj.wysiwyg1);
                        //radio option

                        //selected option
                        jQuery('#frame_color').val(obj.frame_color);
                        console.log(obj);
                        //update_mini_cart();
                    },
                    error: function () {
                        // showError('Sorry. Try reload this page and try again.');
                        // processing.hide();
                    }
                });
            });

            $(document).on('click', '#flash_sale', function (e) {
                if ($(this).is(':checked')) {
                    $('#datewithtime').show();
                    $('.time_box').show();
                } else {
                    $('#datewithtime').hide();
                    $('.time_box').hide();
                }
            });

            $(document).on('keyup', '#related_products_getter', function (e) {
                var search_key = $('#related_products_getter').val();

                var data = {
                    'search_key': search_key
                };
                jQuery.ajax({
                    url: baseurl + '/related_products_getter',
                    method: 'get',
                    data: data,
                    success: function (data) {
                        $('#list_products').fadeIn();
                        $('#list_products').html(data);
                    }
                });
            });

            $(document).on('dblclick', '#dblclick_related', function (e) {
                var pid = $(this).val();
                var mainpid = $(this).data('mainpid');
                var title = $(this).data('title');
                var userid = $(this).data('userid');
                var local_price = $(this).data('local_price');
                var local_discount = $(this).data('local_discount');
                var int_price = $(this).data('int_price');
                var int_discount = $(this).data('int_discount');

                var data = {
                    'product_id': pid,
                    'mainpid': mainpid,
                    'userid': userid,
                    'title': title,
                    'local_price': local_price,
                    'local_discount': local_discount,
                    'int_price': int_price,
                    'int_discount': int_discount,
                };

                //console.log(data);

                jQuery.ajax({
                    url: baseurl + '/add_related_products',
                    method: 'get',
                    data: data,
                    success: function (status) {
                        $('#related_added > ul').append(status.html);
                    }
                });

            });

            $(document).on('dblclick', '#dblclick_cat', function (e) {
                var pid = $(this).val();
                var mainpid = $(this).data('mainpid');
                var title = $(this).data('title');
                var attgroup = $(this).data('attgroup');
                var userid = $(this).data('userid');

                var data = {
                    'userid': userid,
                    'term_id': pid,
                    'mainpid': mainpid,
                    'term_name': title,
                    'term_attgroup': attgroup,
                };

                //console.log(data);

                jQuery.ajax({
                    url: baseurl + '/add_product_categories',
                    method: 'get',
                    data: data,
                    success: function (status) {
                        $('#product_added > table').append(status.html);
                        //window.location.reload(true);
                    }
                });
            });

            $(document).on('click', '#add_color', function (e) {

                var userid = $('#userid').val();
                var mainpid = $('#mainpid').val();
                var type = $('#type').val();
                var color_code = $('#color_code').val();

                var data = {
                    'userid': userid,
                    'color_code': color_code.replace('#', ''),
                    'mainpid': mainpid,
                    'type': type
                };

                //console.log(data);

                jQuery.ajax({
                    url: baseurl + '/add_product_price_combination',
                    method: 'get',
                    data: data,
                    success: function (status) {
                        $('ul#color-chooser').append(status.html);
                        $('#color_code').val('');
                        //window.location.reload(true);
                    }
                });
            });

            $('#AddPhoto').submit(function (event) {
                event.preventDefault();
                var formData = new FormData($(this)[0]);

                $.ajax({
                    url: '{{ url('/multiplepricingphoto') }}',
                    method: 'post',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    headers:
                        {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                    success: function (response) {
                        $('ul#photobox').append(response.html);
                        $('#photo_name').val('');
                    }
                });
            });

            $(document).on('click', '#add_size', function (e) {

                var userid = $('#userid').val();
                var mainpid = $('#mainpid').val();
                var type = $('#size_type').val();
                var size_title = $('#size_title').val();

                var data = {
                    'userid': userid,
                    'mainpid': mainpid,
                    'type': type,
                    'color_code': size_title
                };

                //console.log(data);

                jQuery.ajax({
                    url: baseurl + '/add_product_price_combination',
                    method: 'get',
                    data: data,
                    success: function (status) {
                        $('ul#size_stack').append(status.html);
                        $('#size_title').val('');
                        //window.location.reload(true);
                    }
                });
            });

            $(document).on('click', '#set_attgroup', function (e) {
                var id = $(this).data('id');
                var value = $(this).data('value');
                var type = $(this).data('type');
                //alert($('#unset_attgroup').data('mainpid'));

                if ($('#unset_attgroup').data('type') == 'unset' || $(this)) {
                    var data = {
                        'id': id,
                        'value': value,
                        'type': type,
                        'old_id': $('#unset_attgroup').data('id'),
                        'old_value': $('#unset_attgroup').data('value'),
                        'old_mainpid': $('#unset_attgroup').data('mainpid'),
                    };

                    //console.log(data);

                    jQuery.ajax({
                        url: baseurl + '/is_attgroup_active',
                        method: 'get',
                        data: data,
                        success: function (status) {
                            window.location.reload(true);
                        }
                    });
                } else {

                }
            });

            $(document).on('dblclick', '#use_product_image', function (e) {

                var userid = $(this).data('userid');
                var mainpid = $(this).data('mainpid');
                var media_id = $(this).data('id');
                var filename = $(this).data('filename');
                var fullsize = $(this).data('fullsize');
                var iconsize = $(this).data('iconsize');

                var data = {
                    'userid': userid,
                    'media_id': media_id,
                    'mainpid': mainpid,
                    'filename': filename,
                    'fullsize': fullsize,
                    'iconsize': iconsize
                };

                //console.log(data);

                jQuery.ajax({
                    url: baseurl + '/add_product_images',
                    method: 'get',
                    data: data,
                    success: function (status) {
                        window.location.reload(true);
                    }
                });
            });

            $(document).on('click', '#set_main_image', function (e) {

                var id = $(this).data('id');
                var value = $(this).data('value');
                var type = $(this).data('type');

                if ($('#unset_main_image').data('type') == 'unset' || $(this)) {
                    var data = {
                        'id': id,
                        'value': value,
                        'type': type,
                        'old_id': $('#unset_main_image').data('id'),
                        'old_value': $('#unset_main_image').data('value'),
                    };

                    // console.log(data);

                    jQuery.ajax({
                        url: baseurl + '/is_main_image',
                        method: 'get',
                        data: data,
                        success: function (status) {
                            window.location.reload(true);
                        }
                    });
                } else {

                }
            });

            $(document).on('click', '#save_variation', function (e) {

                var userid = $('#userid').val();
                var mainpid = $('#mainpid').val();
                var color = $('#color').val();
                var type = $('#color option:selected').attr('data-type');
                var size = $('#size').val();
                var item_code = $('#item_code').val();
                var stock = $('#stock').val();
                var dp_price = $('#dp_price').val();
                var regular_price = $('#regular_price').val();
                var selling_price = $('#selling_price').val();
                var is_stock = $('#is_stock option:selected').val();


                var data = {
                    'mainpid': mainpid,
                    'userid': userid,
                    'color_codes': color,
                    'type': type,
                    'size': size,
                    'stock': stock,
                    'item_code': item_code,
                    'dp_price': dp_price,
                    'regular_price': regular_price,
                    'selling_price': selling_price,
                    'is_stock': is_stock,
                };

                //console.log(data);

                jQuery.ajax({
                    url: baseurl + '/save_variation',
                    method: 'get',
                    data: data,
                    success: function (data) {
                        $('#append_to_this').after(data.html);
                    }
                });
            });

            $(document).on('click', '#save_emi_data', function (e) {

                var userid = $('#userid').val();
                var mainpid = $('#mainpid').val();
                var bank_id = $('#bank_id').val();
                var month_range = $('#month_range').val();
                var interest = $('#interest').val();

                var data = {
                    'mainpid': mainpid,
                    'userid': userid,
                    'bank_id': bank_id,
                    'month_range': month_range,
                    'interest': interest
                };

                console.log(data);

                jQuery.ajax({
                    url: baseurl + '/save_emi_data',
                    method: 'get',
                    data: data,
                    success: function (data) {
                        $('#append_to_thiss').after(data.html);
                    }
                });
            });


            $(document).on('keyup', '#cat_search', function (e) {
                var search_param = $(this).val();
                var main_pid = $('#main_pid').val();

                setTimeout(function () {
                    var data = {
                        'search_param': search_param,
                        'main_pid': main_pid
                    };

                    jQuery.ajax({
                        url: baseurl + '/get_categories_on_search',
                        method: 'get',
                        data: data,
                        success: function (data) {
                            jQuery("#replace_with_search").html(data.html);
                        },
                        error: function () {
                            // showError('Sorry. Try reload this page and try again.');
                            // processing.hide();
                        }
                    });
                }, 300);
            });


            $(document).on('keyup', '#product_search_now', function (e) {
                var search_param = $(this).val();
                var main_pid = $('#main_pid_d').val();

                setTimeout(function () {
                    var data = {
                        'search_param': search_param,
                        'main_pid': main_pid
                    };

                    jQuery.ajax({
                        url: baseurl + '/get_products_on_search',
                        method: 'get',
                        data: data,
                        success: function (data) {
                            jQuery("#replace_with_products_search").html(data.html);
                        },
                        error: function () {
                            // showError('Sorry. Try reload this page and try again.');
                            // processing.hide();
                        }
                    });
                }, 300);

            });

        });

        function get_colors_sizes(main_pid) {
            //alert('hasan');
            var data = {
                'main_pid': main_pid
            };

            jQuery.ajax({
                url: baseurl + '/get_colors_sizes',
                method: 'get',
                data: data,
                success: function (data) {
                    jQuery("#input_field_append > tbody").append(data.html);
                },
                error: function () {
                    // showError('Sorry. Try reload this page and try again.');
                    // processing.hide();
                }
            });
        }

        function get_photos(main_pid) {
            var data = {
                'main_pid': main_pid
            };

            jQuery.ajax({
                url: baseurl + '/add_product_price_combination',
                method: 'get',
                data: data,
                success: function (data) {
                    jQuery("#input_field_append > tbody").append(data.html);
                },
                error: function () {
                    // showError('Sorry. Try reload this page and try again.');
                    // processing.hide();
                }
            });
        }


        function add_more_bank(main_pid) {
            var data = {
                'main_pid': main_pid
            };

            jQuery.ajax({
                url: baseurl + '/add_more_bank',
                method: 'get',
                data: data,
                success: function (data) {
                    jQuery("#add_more_bank > tbody").append(data.html);
                },
                error: function () {
                    // showError('Sorry. Try reload this page and try again.');
                    // processing.hide();
                }
            });
        }


    </script>
    <style type="text/css">
        .color-palette {
            height: 35px;
            line-height: 35px;
            text-align: center;
            margin-bottom: 15px;
        }

        .fc-color-picker > li {
            float: left;
            font-size: 45px;
            margin-right: 10px;
            line-height: 45px;
        }

        a.cross_btn {
            font-size: 12px;
            position: absolute;
            top: 0px;
            right: -5px;
            padding: 6px;
            background: #ff7b00;
            line-height: 4px;
            border-radius: 50%;
            height: 18px;
            color: white;
            width: 18px;
            font-weight: bold;
            z-index: 99;
        }

        a.size_cross_btn {
            font-size: 12px;
            position: absolute;
            top: -7px;
            right: -5px;
            padding: 6px;
            background: #ff7b00;
            line-height: 4px;
            border-radius: 50%;
            height: 18px;
            color: white;
            width: 18px;
            font-weight: bold;
            z-index: 99;
        }

        .tab-active {
            color: #0b58a2;
            font-weight: bold;
        }

        .aladagrey {
            background: lightgrey;
        }

        .recordset, .czContainer {
            position: relative;
        }

        .recordset {
            border-bottom: 4px solid white;
        }

        .table-bordered > thead > tr > th, .table-bordered > tbody > tr > th, .table-bordered > tfoot > tr > th, .table-bordered > thead > tr > td, .table-bordered > tbody > tr > td, .table-bordered > tfoot > tr > td {
            border: 1px solid #EEE;
        }


        .acf-fields:after {
            clear: both;
            content: "";
            display: table;
        }

        .acf-fields > .acf-field:first-child {
            border-top-width: 0;
        }

        .acf-fields > .acf-field {
            position: relative;
            margin: 0;
            padding: 15px 12px;
            border-top: #EEEEEE solid 1px !important;
        }

        .acf-field {
            margin: 15px 0;
            clear: both;
        }

        .acf-field, .acf-field .acf-label, .acf-field .acf-input {
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
            position: relative;
        }

        .acf-fields.-left > .acf-field:before {
            content: "";
            display: block;
            position: absolute;
            z-index: 0;
            background: #F9F9F9;
            border-color: #E1E1E1;
            border-style: solid;
            border-width: 0 1px 0 0px;
            top: 0;
            bottom: 0;
            left: 0;
            width: 20%;
        }

        .acf-fields.-left > .acf-field:after {
            clear: both;
            content: "";
            display: table;
        }

        .acf-fields.-left > .acf-field > .acf-label {
            float: left;
            width: 20%;
            margin: 0;
            padding: 0 12px;
        }

        .acf-fields.-left > .acf-field > .acf-input {
            float: left;
            width: 80%;
            margin: 0;
            padding: 0 12px;
        }

        .acf-field .acf-input {
            vertical-align: top;
        }

        #modal_button {
            display: flex;
            float: right;
            border-radius: .5rem;
            margin-bottom: .3rem;
        }
    </style>
@endpush
