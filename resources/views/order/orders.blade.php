@extends('layouts.app')

@section('title', 'Orders')
@section('sub_title', 'all Requisitions are here')
<?php $tksign = '&#2547; '; ?>
@section('content')
    <div class="row">
        @if(Session::has('success'))
            <div class="col-md-12">
                <div class="callout callout-success">
                    {{ Session::get('success') }}
                </div>
            </div>
        @endif
        <div class="col-md-12">
            <div class="box box-danger">
                <div class="box-header with-border">
                    <h5 class="box-title">Advanced Search</h5>
                </div>
                <div class="box-body compact-search">
                    {{ Form::open(array('url' => '/search_orders', 'method' => 'get', 'value' => 'PATCH', 'id' => 'search-form')) }}
                    <div class="row">
                        <div class="col-md-4 col-sm-8 col-xs-12">
                            {{ Form::text('search_term', $getAttribute['search_term']??'', ['class' => 'form-control input-sm', 'placeholder' => 'Order ID / Order Random / Customer / Phone / Email / Product Code / Product Name']) }}
                        </div>
                        <div class="col-md-2 col-sm-4 col-xs-6">
                            <select name="order_status" class="form-control input-sm">
                                <option value="">Order Status</option>
                                <option value="placed" {{ ($getAttribute['order_status']??'') == 'placed' ? 'selected' : '' }}>Placed</option>
                                <option value="production" {{ ($getAttribute['order_status']??'') == 'production' ? 'selected' : '' }}>Requested Order</option>
                                <option value="distribution" {{ ($getAttribute['order_status']??'') == 'distribution' ? 'selected' : '' }}>NULL</option>
                                <option value="processing" {{ ($getAttribute['order_status']??'') == 'processing' ? 'selected' : '' }}>Shipped</option>
                                <option value="refund" {{ ($getAttribute['order_status']??'') == 'refund' ? 'selected' : '' }}>Refunded</option>
                                <option value="done" {{ ($getAttribute['order_status']??'') == 'done' ? 'selected' : '' }}>Complete</option>
                                <option value="cancel" {{ ($getAttribute['order_status']??'') == 'cancel' ? 'selected' : '' }}>Cancelled</option>
                                <option value="confirmed" {{ ($getAttribute['order_status']??'') == 'confirmed' ? 'selected' : '' }}>Need to Shipped</option>
                                <option value="Customer-Unreachable" {{ ($getAttribute['order_status']??'') == 'Customer-Unreachable' ? 'selected' : '' }}>Customer Unreachable</option>
                                <option value="order-hold" {{ ($getAttribute['order_status']??'') == 'order-hold' ? 'selected' : '' }}>Order Hold</option>
                                <option value="delivered" {{ ($getAttribute['order_status']??'') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="fake-order" {{ ($getAttribute['order_status']??'') == 'fake-order' ? 'selected' : '' }}>Fake Order</option>
                                <option value="paid" {{ ($getAttribute['order_status']??'') == 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="payment-failed" {{ ($getAttribute['order_status']??'') == 'payment-failed' ? 'selected' : '' }}>Payment Failed</option>
                                <option value="need-to-refund" {{ ($getAttribute['order_status']??'') == 'need-to-refund' ? 'selected' : '' }}>Need to Refund</option>
                                <option value="partial-paid" {{ ($getAttribute['order_status']??'') == 'partial-paid' ? 'selected' : '' }}>Partial Paid</option>
                                <option value="partial-refunded" {{ ($getAttribute['order_status']??'') == 'partial-refunded' ? 'selected' : '' }}>Partial Refunded</option>
                                <option value="deleted" {{ ($getAttribute['order_status']??'') == 'deleted' ? 'selected' : '' }}>Deleted</option>
                            </select>
                        </div>
                        <div class="col-md-2 col-sm-4 col-xs-6">
                            <select name="payment_method" class="form-control input-sm">
                                <option value="">Payment Method</option>
                                <option value="cash_on_delivery" {{ ($getAttribute['payment_method']??'') == 'cash_on_delivery' ? 'selected' : '' }}>Cash On Delivery</option>
                                <option value="paid_on_hand" {{ ($getAttribute['payment_method']??'') == 'paid_on_hand' ? 'selected' : '' }}>Cash on Hand</option>
                                <option value="debitcredit" {{ ($getAttribute['payment_method']??'') == 'debitcredit' ? 'selected' : '' }}>Debit/Credit</option>
                                <option value="mobilebanking" {{ ($getAttribute['payment_method']??'') == 'mobilebanking' ? 'selected' : '' }}>Mobile Banking</option>
                                <option value="nagad" {{ ($getAttribute['payment_method']??'') == 'nagad' ? 'selected' : '' }}>Nagad</option>
                                <option value="bkash" {{ ($getAttribute['payment_method']??'') == 'bkash' ? 'selected' : '' }}>bKash</option>
                            </select>
                        </div>
                        <div class="col-md-2 col-sm-4 col-xs-6">
                            <select name="payment_term_status" class="form-control input-sm">
                                <option value="">Payment Status</option>
                                <option value="Pending" {{ ($getAttribute['payment_term_status']??'') == 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="Successful" {{ ($getAttribute['payment_term_status']??'') == 'Successful' ? 'selected' : '' }}>Successful</option>
                                <option value="Success" {{ ($getAttribute['payment_term_status']??'') == 'Success' ? 'selected' : '' }}>Success</option>
                                <option value="Failed" {{ ($getAttribute['payment_term_status']??'') == 'Failed' ? 'selected' : '' }}>Failed</option>
                                <option value="Partial" {{ ($getAttribute['payment_term_status']??'') == 'Partial' ? 'selected' : '' }}>Partial</option>
                                <option value="COD" {{ ($getAttribute['payment_term_status']??'') == 'COD' ? 'selected' : '' }}>COD</option>
                            </select>
                        </div>
                        <div class="col-md-2 col-sm-4 col-xs-6">
                            <select name="order_from" class="form-control input-sm">
                                <option value="">Order Source</option>
                                @php
                                    $orderFrom = ['website', 'custom', 'one click Buy'];
                                @endphp
                                @foreach($orderFrom as $val)
                                    <option value="{{$val}}" {{isset($getAttribute['order_from']) && $getAttribute['order_from'] == $val ? 'selected' : null}}>{{$val}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row" style="margin-top: 6px;">
                        <div class="col-md-1 col-sm-3 col-xs-6">
                            <div class="input-group date">
                                <div class="input-group-addon">From</div>
                                <input value="{{ $getAttribute['formDate']??'' }}" autocomplete="off" type="text"
                                       name="formDate" id="formDate" class="form-control input-sm datepicker">
                            </div>
                        </div>
                        <div class="col-md-1 col-sm-3 col-xs-6">
                            <div class="input-group date">
                                <div class="input-group-addon">To</div>
                                <input value="{{ $getAttribute['toDate']??''  }}" autocomplete="off" type="text"
                                       name="toDate" id="toDate" class="form-control input-sm datepicker">
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-4 col-xs-6">
                            {{ Form::text('amount_min', $getAttribute['amount_min']??'', ['class' => 'form-control input-sm', 'placeholder' => 'Amount Min']) }}
                        </div>
                        <div class="col-md-2 col-sm-4 col-xs-6">
                            {{ Form::text('amount_max', $getAttribute['amount_max']??'', ['class' => 'form-control input-sm', 'placeholder' => 'Amount Max']) }}
                        </div>
                        <div class="col-md-2 col-sm-4 col-xs-6">
                            {{ Form::submit('Search', ['class' => 'btn btn-success btn-sm btn-block']) }}
                        </div>
                        <div class="col-md-2 col-sm-4 col-xs-6">
                            <a class="btn btn-default btn-sm btn-block" href="{{ url('orders') }}">Reset</a>
                        </div>
                        <div class="col-md-2 col-sm-4 col-xs-6">
                            <button type="submit"
                                    id="exportExcel"
                                    class="btn btn-info btn-sm btn-block"
                                    formaction="{{ url('export_orders') }}"
                                    formmethod="get">
                                Export Excel
                            </button>
                        </div>
                    </div>

                    {{ Form::close() }}

                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="box">
                <div class="box-header bulk_status_box d-none">
                    <div class="row">
                        <div class="col-xs-2">
                            <div class="form-group">
                                <select name="order_status" id="ch_order_status" class="form-control">
                                    <option value="">Choose a status</option>
                                    <option value="placed">Placed</option>
                                    <option value="production">Requested Order</option>
                                    <option value="distribution" disabled>NULL</option>
                                    <option value="processing">Shipped</option>
                                    <option value="refund">Refunded</option>
                                    <option value="done">Complete</option>
                                    <option value="cancel">Cancelled</option>
                                    <option value="confirmed">Need to Shipped</option>
                                    <option value="Customer-Unreachable">Customer Unreachable</option>
                                    <option value="order-hold">Order Hold</option>
                                    <option value="delivered">Delivered</option>
                                    <option value="fake-order">Fake Order</option>
                                    <option value="paid">Paid</option>
                                    <option value="payment-failed">Payment Failed</option>
                                    <option value="need-to-refund">Need to Refund</option>
                                    <option value="partial-paid">Partial Paid</option>
                                    <option value="partial-refunded">Partial Refunded</option>
                                    <option value="deleted">Delete</option>
                                </select>
                            </div>

                        </div>
                        <div class="col-xs-1">
                            <div class="form-group">
                                <button type="submit" id="change_status" class="btn btn-primary">Apply</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-body table-responsive no-padding" id="reload_me">
                    <table class="table table-hover">
                        <tbody>
                        <tr>
                            <th><input type="checkbox" id="select_all"></th>
                            <th title="Order ID">ID</th>
                            <th>Order Status</th>
                            <th>Payment Status</th>
                            <th>Payment Method</th>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Phone</th>
                            <th style="width: 170px;">Address</th>
                            <th>Total</th>
                            <th>Source</th>
                        </tr>
                        @foreach($orders as $line)
                            @php
                                //dump($line);
                                //$image_info  = App\Models\ProductImages::Where(['main_pid' => $line->product_id])->get()->first();
                               // $vendor  = App\Models\User::Where(['id' => $line->vendor_id])->get()->first();
                                $order_details  = App\Models\OrdersDetail::Where(['order_random' => $line->order_random])->get();
                                $total = 0;
                                if(auth()->user()->isVendor()){
                                    foreach ($order_details as $cod){
                                    $vendor  = App\Models\User::Where(['id' => $cod->vendor_id])->get()->first();
                                        if(auth()->user()->id == $vendor->id){
                                        $total += $cod->local_purchase_price;
                                        }
                                     }
                                } else{
                                    $total +=  $line->total_amount;
                                }
                            @endphp
                            @php
                                $orderStatusLabels = [
                                    'placed' => 'Placed',
                                    'production' => 'Requested Order',
                                    'distribution' => 'NULL',
                                    'processing' => 'Shipped',
                                    'refund' => 'Refunded',
                                    'done' => 'Complete',
                                    'cancel' => 'Cancelled',
                                    'confirmed' => 'Need to Shipped',
                                    'Customer-Unreachable' => 'Customer Unreachable',
                                    'order-hold' => 'Order Hold',
                                    'delivered' => 'Delivered',
                                    'fake-order' => 'Fake Order',
                                    'paid' => 'Paid',
                                    'payment-failed' => 'Payment Failed',
                                    'need-to-refund' => 'Need to Refund',
                                    'partial-paid' => 'Partial Paid',
                                    'partial-refunded' => 'Partial Refunded',
                                    'deleted' => 'Deleted'
                                ];
                                $orderStatusText = $orderStatusLabels[$line->order_status] ?? $line->order_status;
                            @endphp
                            <tr>
                                <td>
                                    <input type="checkbox" name="order_ids[]" class="select-row"
                                           value="{{ $line->id }}">
                                </td>
                                <td title="Order ID">
                                    <a href="{{ url('orders_single/' . $line->order_random) }}?info_type=general">
                                        Order # {{ $line->id }}
                                    </a>
                                </td>
                                <td>
                                    <label>
                                        {{ $orderStatusText }}
                                    </label>
                                </td>
                                <td style="{{ $line->payment_term_status == 'Successful' ? 'background-color:rgba(194, 255, 238);' : ($line->payment_method == 'cash_on_delivery' ? 'background-color:rgba(242, 238, 203);' : '')}}">
                                    {{ $line->payment_term_status ?? '-' }}
                                </td>
                                <td>
                                    {{ $line->payment_method ?? '-' }}
                                </td>
                                <td>
                                    <b>Order Date: </b>{{ $line->order_date ?? null }} <br>

                                    @php
                                        $maxday = App\Models\OrdersDetail::select('product_arrive_times_day')
                                                    ->where('order_random', $line->order_random)->get()
                                                    ->max('product_arrive_times_day') ?? false;

                                    @endphp
                                    {!!  $maxday ? '<b>Arrive Day:</b> '.$maxday. ' days' : false !!}
                                </td>
                                <td>
                                    <a href="mailto:{{ $line->email ?? '#' }}">@</a>
                                    {{ $line->customer_name ?? null }}
                                </td>
                                <td><a href="tel:{{ $line->email ?? '#' }}">{{ $line->phone ?? null }}</a></td>
                                <td style="max-width: 180px; width: 170px; white-space: normal; word-break: break-word;">
                                    {{$line->address}}, {{$line->district}}
                                </td>
                                <td>৳{{  number_format($line->grand_total) ?? null }}</td>
                                <td>{{$line->order_from}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="box-footer clearfix">
                        {{ $orders->appends(\Illuminate\Support\Arr::except($getAttribute ?? [], ['page']))->links('component.paginator', ['object' => $orders,'more' => 'some']) }}
                        @if(request()->get('debug_pagination') == '1')
                            <div class="text-muted" style="margin-top:6px; font-size:12px;">
                                total={{ $orders->total() }}, perPage={{ $orders->perPage() }}, current={{ $orders->currentPage() }}, last={{ $orders->lastPage() }}, count={{ $orders->count() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('cusjs')
    <script type="text/javascript">
		jQuery(document).ready(function ($) {
			$.noConflict();

			$('.order_status_change').on('click', function () {

				var id = $(this).data('id');
				var status = $(this).data('status');
				var checked = this.parentElement.children[0].checked;

				var data = {
					'id': id,
					'status': status,
					'send_sms': checked
				};

				//console.log(data);

				$.ajax({
					url: baseurl + '/orders/move',
					method: 'get',
					data: data,
					success: function (data) {
						location.reload();
					},
					error: function () {
						// showError('Sorry. Try reload this page and try again.');
						// processing.hide();
					}
				});
			});


			$('#change_status').on('click', function (e) {
				e.preventDefault();

				var os_items = "";
				var sms_items = "";

				var order_status = $('#ch_order_status').val();

				// order status items
				var checked = $('input[name="order_ids[]"]:checked').length > 0;
				if (checked) {
					var os_list = "";
					$("input[name='order_ids[]']").each(function () {
						if (this.checked === true) {
							// os = order status
							os_list += (os_list === "" ? this.value : "_" + this.value);
						}
					});
					console.log(os_list);
				}

				// sms items
				var sms_checked = $('input[name="sms[]"]:checked').length > 0;
				if (sms_checked) {
					var sms_list = "";
					$("input[name='sms[]']").each(function () {
						if (this.checked == true) {
							// os = order status
							sms_list += (sms_list == null ? this.value : this.value + "_");
						}
					});
					console.log(sms_list);
				}

				var data = {
					'os_items': os_list,
					'sms_items': sms_list,
					'order_status': order_status
				};


				console.log(data);

				$.ajax({
					url: baseurl + '/orders/bulk_move',
					method: 'get',
					data: data,
					success: function (data) {
						location.reload();
					},
					error: function () {
						// showError('Sorry. Try reload this page and try again.');
						// processing.hide();
					}
				});

			});

			$('#select_all').on('click', function () {
				const checked = this.checked;
				$('input[name="order_ids[]"]').each(function () {
					this.checked = checked;
				});
			})
		});

        $('#exportExcel').on('click', function () {
            const form = $('#search-form');
            form.find('input.export-order-id').remove();

            const checked = $('input[name="order_ids[]"]:checked');
            if (checked.length > 0) {
                checked.each(function () {
                    $('<input>', {
                        type: 'hidden',
                        name: 'order_ids[]',
                        value: this.value,
                        class: 'export-order-id'
                    }).appendTo(form);
                });
            }

            $('<input>', {
                type: 'hidden',
                name: 'pre_booking_order',
                value: '0',
                class: 'export-order-id'
            }).appendTo(form);
        });

		//Date picker
		$('.datepicker').datepicker({
			autoclose: true
		})
    </script>
    <script>
    </script>
    <style type="text/css">
        .compact-search .row {
            margin-left: -5px;
            margin-right: -5px;
        }
        .compact-search [class*="col-"] {
            padding-left: 5px;
            padding-right: 5px;
            margin-bottom: 6px;
        }
        .compact-search .input-group .input-group-addon {
            padding: 4px 8px;
        }
        .search_box .box-title {
            font-size: 15px;
        }

        .search_box {
            padding: 10px;
        }

        .search_box .box-body {
            background: floralwhite;
        }

        input.send_sms {
            width: 10%;
            padding-top: 3px !important;
            /*position: absolute;*/
            margin-left: 3px;
        }

        a.order_status_change {
            width: 100%;
            margin-top: -25px;
        }
    </style>
@endsection
