@extends('layouts.app')


@section('title', 'Flash Shedule')
@section('sub_title', 'all flash shedule management panel')
@section('content')
    @php
        $tksign = "৳"; //"&#2547;";
    @endphp


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
        <div class="col-md-3" id="signupForm">
            <div class="box box-warning">
                <div class="box-header">
                    <h3 class="box-title">
                        {{ Form::label('related_products_getter', 'Choose Bought Together Products', array('class' => 'related_products')) }}
                    </h3>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <input type="hidden" id="main_pid_d" class="form-control" value="" />
                        <input type="text" placeholder="Search on products" id="product_search_now" class="form-control" />
                    </div>
                </div>
                <?php $products = App\Models\Product::orderBy('title')->get(); ?>
                <select class="form-control" multiple style="min-height: 250px;" id="replace_with_products_search">
                    @foreach ($products as $p)
                        <option id="dblclick_related" value="{{ $p->id }}"
                            data-mainpid="{{ !empty($product->id) ? $product->id : null }}"
                            data-userid="{!! (!empty(\Auth::user()->id) ? \Auth::user()->id : NULL) !!}"
                            data-title="{{ $p->title }}" data-local_price="{{ $p->local_selling_price }}"
                            data-local_discount="{{ $p->local_discount }}" data-int_price="{{ $p->intl_selling_price }}"
                            data-int_discount="{{ $p->intl_discount }}">
                            {{ $p->title . ' (' . $p->sub_title . ')'}}
                        </option>
                    @endforeach
                </select>

                <div class="box-footer">Use double click to add a product as add a related products</div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="box box-success">
                <div class="box-header with-border">
                </div>
                <div class="box-body" style="">
                    <div class="box-body table-responsive no-padding" id="reload_me">
                        <table class="table table-hover">
                            <tbody>
                                <tr style="background: #0b93d5; color: #fff">

                                    <th colspan="2">
                                        Name: {{ $schedule->fs_name }}<br>
                                        Discription: {{ $schedule->fs_description }}
                                    </th>
                                    <th>
                                        Start Time<br>
                                        {{ date('d-M-Y h:i A', strtotime($schedule->fs_start_date)) }}

                                    </th>
                                    <th>
                                        End Time<br>
                                        {{ date('d-M-Y h:i A', strtotime($schedule->fs_end_date)) }}

                                    </th>
                                    <th>
                                        Status<br>
                                        {{ ($schedule->fs_is_active == 1) ? 'Active' : 'Inactive' }}
                                    </th>

                                </tr>

                                <tr>
                                    <th>Product Image</th>
                                    <th>Product Info</th>
                                    <th>Product Price</th>
                                    <th>Discount</th>
                                    <th>Action</th>
                                </tr>

                                @foreach($itmes as $itme)
                                    @php
                                        $product = \App\Models\Product::where('id', $itme->fi_product_id)->get()->first();
                                        $main_image = \App\Models\ProductImages::where(['main_pid' => $itme->fi_product_id, 'is_main_image' => 1])->get()->first();
                                    @endphp
                                    <tr>
                                        <td>
                                            <img src="{{ url($main_image->full_size_directory ?? "") }}"
                                                style="height:50px; max-width:100px">
                                        </td>
                                        <td>
                                            <b>Name: </b> {{ $product->title }}<br>
                                            <b>ID: </b> {{ $itme->fi_product_id }}<br>
                                            <b>SKU: </b> {{ $product->sku }}
                                        </td>
                                        <td>
                                            <b>Price: </b> {{ $tksign . number_format($product->local_selling_price) }}<br>
                                            <b>QTY: </b> {{ $product->qty }}<br>
                                        </td>
                                        <td>
                                            <b>Discount: </b> {{ $tksign . number_format($itme->fi_discount) }}<br>
                                            <b>Dis Price: </b>
                                            {{ $tksign . number_format($product->local_selling_price - $itme->fi_discount) }}<br>
                                            <b>Percentage: </b> {{ $itme->fi_show_tag }} %
                                        </td>
                                        <td>
                                            <button class="btn btn-xs btn-info" onclick="edit_itmes(this)"
                                                data-product="{{ $itme->fi_product_id }}" data-schedule="{{ $schedule->id }}">
                                                <i class="fa fa-pencil-square-o"></i>
                                            </button>
                                            <a class="btn btn-xs btn-danger delete_form"
                                                href="{{ url('/delete_flash_item/' . $itme->id) }}"
                                                onclick="return confirm('You are attempting to remove this category forever. Are you Sure?')"
                                                title="Delete Now">
                                                <i class="fa fa-times"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div id="related_added">
                        </div>
                        <div class="box-footer clearfix">
                            {{ $itmes->links('component.paginator', ['object' => $itmes]) }}
                        </div>
                    </div>
                </div>
                <div class="box-footer clearfix">
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>
    <!-- Moment.js (Required for datetimepicker) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>

    <!-- Bootstrap DateTimePicker -->
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/js/tempusdominus-bootstrap-4.min.js"></script>
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/css/tempusdominus-bootstrap-4.min.css" />


    <script>

        jQuery(document).ready(function ($) {
            // Ensure jQuery and Bootstrap DateTimePicker are loaded
            //console.log(typeof $.fn.datetimepicker); // Should log "function"

            // Initialize datetimepicker correctly
            $('#datetimepicker1').datetimepicker({
                format: 'YYYY-MM-DD HH:mm'
            });

            $('#datetimepicker2').datetimepicker({
                format: 'YYYY-MM-DD HH:mm'
            });
        });

        jQuery(document).ready(function ($) {
            $.noConflict();
            $(document).on('keyup', '#product_search_now', function (e) {
                var search_param = $(this).val();
                var main_pid = $('#main_pid_d').val();

                setTimeout(function () {
                    var data = {
                        'search_param': search_param,
                        'main_pid': main_pid
                    };

                    $.ajax({
                        url: baseurl + '/get_products_on_search',
                        method: 'get',
                        data: data,
                        success: function (data) {
                            $("#replace_with_products_search").html(data.html);
                        },
                        error: function () {
                            // showError('Sorry. Try reload this page and try again.');
                            // processing.hide();
                        }
                    });
                }, 300);

            });
        });


        jQuery(document).on('dblclick', '#dblclick_related', function (e) {
            var pid = jQuery(this).val();
            var fi_shedule_id = '{{$schedule->id}}';
            var data = {
                'fi_product_id': pid,
                'fi_shedule_id': fi_shedule_id,
            };

            jQuery.ajax({
                url: baseurl + '/add_schedule_products',
                method: 'get',
                data: data,
                success: function (status) {                    
                    jQuery("#getCode").html(status.html);

                    // Debugging: Check if modal exists in the DOM
                    setTimeout(function () {
                        if (jQuery("#getCodeModal").length) {
                            console.log("Modal found! Attempting to show...");
                            jQuery("#getCodeModal").removeClass("fade").modal('show');
                            jQuery("#getCodeModal").modal('show');
                        } else {
                            console.error("Modal #getCodeModal not found in DOM!");
                        }
                    }, 200);
                }
            });

        });


        function edit_itmes(self) {
            var pid = $(self).attr('data-product');
            var fi_shedule_id = $(self).attr('data-schedule');
            var data = {
                'fi_product_id': pid,
                'fi_shedule_id': fi_shedule_id,

            };

            $.ajax({
                url: baseurl + '/add_schedule_products',
                method: 'get',
                data: data,
                success: function (status) {
                    $("#getCode").html(status.html);
                    // Wait for modal to be added to DOM, then show it
                    setTimeout(function () {
                        if ($("#getCodeModal").length) {
                            $("#getCodeModal").modal('show');
                        } else {
                            console.error("Modal #getCodeModal not found in DOM!");
                        }
                    }, 200);
                }
            });
        }

    </script>
    <style type="text/css">
        .datepicker.datepicker-dropdown.dropdown-menu.datepicker-orient-left.datepicker-orient-top {
            z-index: 9999 !important;
        }
    </style>
@endpush