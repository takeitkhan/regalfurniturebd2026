<div class="row">
    @foreach($order_details as $od)
        @php
            $image_info = App\Models\ProductImages::Where(['main_pid' =>
            $od->product_id])->get()->first();
            $vendor = App\Models\User::Where(['id' => $od->vendor_id])->get()->first();
        @endphp
    @endforeach
        <div class="col-md-6">
            <form action="{{ url('customer_message_save') }}" method="post">
                @csrf
                <input type="hidden" name="order_id" value="{{ $order_master->id ?? NULL }}"/>
                <input type="hidden" name="user_id" value="{{ Auth::user()->id ?? NULL }}"/>
                <input type="hidden" name="request_id" value="{{ request()->id ?? NULL }}"/>
                <input type="hidden" name="message_type" value="customer"/>


                <div class="form-group">
                    <select class="form-control" name="move_status">
                        <option value="normal">Normal Message</option>
                        <option value="placed">Placed</option>
                        <option value="production" disabled>Requested Order</option>
                        <option value="distribution" disabled>XXX</option>
                        <option value="processing">Shipped</option>
                        <option value="refund">Refunded</option>
                        <option value="done">Complete</option>
                        <option value="cancel">Cancelled</option>
                        <option value="confirmed">Need to Shipped</option>
{{--                        <option value="deleted">Deleted</option>--}}
                        <option value="Customer-Unreachable">Customer Unreachable</option>
                        <option value="order-hold">Order Hold</option>
                        <option value="delivered">Delivered</option>
                        <option value="fake-order">Fake Order</option>
                        <option value="paid">Paid</option>
                        <option value="payment-failed">Payment Failed</option>
                        <option value="need-to-refund">Need to Refund</option>
                        <option value="partial-paid">Partial Paid</option>
                        <option value="partial-refunded">Partial Refunded</option>
                    </select>
                </div>
                <div class="form-group">
                    <textarea name="message" class="form-control"></textarea>
                </div>
                <div class="form-group">
                    <input type="checkbox" name="sending_to_customer"> I want to send message to customer
                </div>
                {{ Form::submit('Send Message', ['class' => 'btn btn-success']) }}
            </form>

            <hr/>
            <form action="{{ url('order/payment-status') }}" method="post">
                @csrf
                <input type="hidden" name="order_id" value="{{ $order_master->id ?? NULL }}"/>
                <div class="form-group">
                    <label>Payment Status</label>
                    <select class="form-control" name="payment_term_status">
                        <option value="" disabled {{ ($order_master->payment_term_status ?? null) ? '' : 'selected' }}>Select</option>
                        <option value="Pending" {{ ($order_master->payment_term_status ?? null) == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Successful" {{ ($order_master->payment_term_status ?? null) == 'Successful' ? 'selected' : '' }}>Successful</option>
                        <option value="Success" {{ ($order_master->payment_term_status ?? null) == 'Success' ? 'selected' : '' }}>Success</option>
                        <option value="Failed" {{ ($order_master->payment_term_status ?? null) == 'Failed' ? 'selected' : '' }}>Failed</option>
                        <option value="Partial" {{ ($order_master->payment_term_status ?? null) == 'Partial' ? 'selected' : '' }}>Partial</option>
                        <option value="COD" {{ ($order_master->payment_term_status ?? null) == 'COD' ? 'selected' : '' }}>COD</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Update Payment Status</button>
            </form>

            <hr/>
            <form action="{{ url('order/payment-method') }}" method="post">
                @csrf
                <input type="hidden" name="order_id" value="{{ $order_master->id ?? NULL }}"/>
                <div class="form-group">
                    <label>Payment Method</label>
                    <select class="form-control" name="payment_method">
                        <option value="">Select</option>
                        <option value="cash_on_delivery" {{ ($order_master->payment_method ?? null) == 'cash_on_delivery' ? 'selected' : '' }}>Cash On Delivery</option>
                        <option value="paid_on_hand" {{ ($order_master->payment_method ?? null) == 'paid_on_hand' ? 'selected' : '' }}>Paid on hand directly</option>
                        <option value="debitcredit" {{ ($order_master->payment_method ?? null) == 'debitcredit' ? 'selected' : '' }} disabled>Debit/Credit</option>
                        <option value="mobilebanking" {{ ($order_master->payment_method ?? null) == 'mobilebanking' ? 'selected' : '' }} disabled>Mobile Banking Payment</option>
                        <option value="nagad" {{ ($order_master->payment_method ?? null) == 'nagad' ? 'selected' : '' }} disabled>Nagad Payment</option>
                        <option value="bkash" {{ ($order_master->payment_method ?? null) == 'bkash' ? 'selected' : '' }} disabled>bKash Payment</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Update Payment Method</button>
            </form>


        </div>

    <div class="col-md-6">
        <p class="lead" style="margin-bottom: 5px;">Sent Messages:</p>
        @php
            $messages = \App\Models\Message::where('order_id', $order_master->id)->orderBy('id', 'desc')->where('message_type', 'customer')->get();
        @endphp
        <ul class="timeline timeline-inverse">
            @foreach($messages as $msg)
                <li>
                    @php
                        $user = \App\Models\User::find($msg->user_id);
                    @endphp
                    <div class="timeline-item">
                        <span class="time">
                            <i class="fa fa-clock-o"></i> {{ $msg->created_at }}
                        </span>
                        <h3 class="timeline-header">
                            <a href="javascript:void(0);">{{ $user->name }}</a> make a note
                            on
                            {{ $msg->status ?? NULL }}
                            @if($msg->message_type == 'staff')
                                as <span class="text-blue"> staff note</span>
                            @else
                                <span class="text-danger"> message to customer</span>
                            @endif
                        </h3>
                        <div class="timeline-body">
                            {{ $msg->message ?? NULL }}
                        </div>
                        <div class="timeline-footer">
                            {{--                            <a class="btn btn-primary btn-xs">Read more</a>--}}
{{--                            <a class="btn btn-danger btn-xs">Delete</a>--}}
                            @if($msg->status == 1)
                                <span class="text-success">Sms Sent</span>
                            @endif
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
        {{--        </div>--}}
        {{--        <div class="btn-group">--}}
        {{--            <div class="btn-group">--}}
        {{--                <button--}}
        {{--                    type="button"--}}
        {{--                    class="btn btn-default dropdown-toggle btn-xs"--}}
        {{--                    data-toggle="dropdown"--}}
        {{--                    aria-expanded="false">--}}
        {{--                    Order Status <span class="caret"></span>--}}
        {{--                </button>--}}
        {{--            </div>--}}
        {{--        </div>--}}
        {{--        <ul class="dropdown-menu" role="menu">--}}
        {{--            <li id="move_order">--}}
        {{--                <input type="checkbox"--}}
        {{--                       name="send_sms"--}}
        {{--                       class="send_sms"--}}
        {{--                       data-id="{{ $od->id }}" {{ $od->od_status == 'placed' ?--}}
        {{--                                                    'checked' : "" }}>--}}
        {{--                <a class="order_status_change"--}}
        {{--                   data-status="placed"--}}
        {{--                   data-id="{{ $od->id }}"--}}
        {{--                   href="javascript:void(0)">--}}
        {{--                    Placed--}}
        {{--                </a>--}}
        {{--            </li>--}}
        {{--            <li id="move_order">--}}
        {{--                <input type="checkbox"--}}
        {{--                       name="send_sms"--}}
        {{--                       class="send_sms"--}}
        {{--                       data-id="{{ $od->id }}" {{ $od->od_status == 'production' ?--}}
        {{--                                                    'checked' : "" }}>--}}
        {{--                <a class="order_status_change"--}}
        {{--                   data-status="production"--}}
        {{--                   data-id="{{ $od->id }}"--}}
        {{--                   href="javascript:void(0)">--}}
        {{--                    Production--}}
        {{--                </a>--}}
        {{--            </li>--}}
        {{--            <li id="move_order">--}}
        {{--                <input type="checkbox"--}}
        {{--                       name="send_sms"--}}
        {{--                       class="send_sms"--}}
        {{--                       data-id="{{ $od->id }}" {{ $od->od_status == 'distribution' ?--}}
        {{--                                                    'checked' : "" }}>--}}
        {{--                <a class="order_status_change"--}}
        {{--                   data-status="distribution"--}}
        {{--                   data-id="{{ $od->id }}"--}}
        {{--                   href="javascript:void(0)">--}}
        {{--                    Distribution--}}
        {{--                </a>--}}
        {{--            </li>--}}
        {{--            <li id="move_order">--}}
        {{--                <input type="checkbox"--}}
        {{--                       name="send_sms"--}}
        {{--                       class="send_sms"--}}
        {{--                       data-id="{{ $od->id }}" {{ $od->od_status == 'processing' ?--}}
        {{--                                                    'checked' : "" }}>--}}
        {{--                <a class="order_status_change"--}}
        {{--                   data-status="processing"--}}
        {{--                   data-id="{{ $od->id }}"--}}
        {{--                   href="javascript:void(0)">--}}
        {{--                    Processing--}}
        {{--                </a>--}}
        {{--            </li>--}}
        {{--            <li id="move_order">--}}
        {{--                <input type="checkbox"--}}
        {{--                       name="send_sms"--}}
        {{--                       class="send_sms"--}}
        {{--                       data-id="{{ $od->id }}" {{ $od->od_status == 'refund' ?--}}
        {{--                                                    'checked' : "" }}>--}}
        {{--                <a class="order_status_change"--}}
        {{--                   data-status="refund"--}}
        {{--                   data-id="{{ $od->id }}"--}}
        {{--                   href="javascript:void(0)">--}}
        {{--                    Refund--}}
        {{--                </a>--}}
        {{--            </li>--}}
        {{--            <li id="move_order">--}}
        {{--                <input type="checkbox"--}}
        {{--                       name="send_sms"--}}
        {{--                       class="send_sms"--}}
        {{--                       data-id="{{ $od->id }}" {{ $od->od_status == 'done' ?--}}
        {{--                                                    'checked' : "" }}>--}}
        {{--                <a class="order_status_change"--}}
        {{--                   data-status="done"--}}
        {{--                   data-id="{{ $od->id }}"--}}
        {{--                   href="javascript:void(0)">--}}
        {{--                    Done--}}
        {{--                </a>--}}
        {{--            </li>--}}
        {{--            <li id="move_order">--}}
        {{--                <input type="checkbox"--}}
        {{--                       name="send_sms"--}}
        {{--                       class="send_sms"--}}
        {{--                       data-id="{{ $od->id }}" {{ $od->od_status == 'deleted' ?--}}
        {{--                                                    'checked' : "" }}>--}}
        {{--                <a class="order_status_change"--}}
        {{--                   data-status="deleted"--}}
        {{--                   data-id="{{ $od->id }}"--}}
        {{--                   href="javascript:void(0)">--}}
        {{--                    Delete Order--}}
        {{--                </a>--}}
        {{--            </li>--}}
        {{--        </ul>--}}
    </div>
</div>
