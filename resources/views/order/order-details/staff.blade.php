<div class="row">
    <div class="col-md-6">
        <form action="{{ url('staff_note_save') }}" method="post">
            @csrf
            <p class="lead" style="margin-bottom: 5px;">Make a note:</p>
            <div class="form-group">
                <input type="hidden" name="order_id" value="{{ $order_master->id ?? NULL }}"/>
                <input type="hidden" name="user_id" value="{{ Auth::user()->id ?? NULL }}"/>
                <input type="hidden" name="request_id" value="{{ request()->id ?? NULL }}"/>
                <input type="hidden" name="message_type" value="staff"/>
                <textarea name="message" class="form-control" style="margin-bottom: 5px" rows="5"
                          placeholder="Enter notes..."></textarea>
            </div>


            <div class="form-group">
                Notes On:
                <select class="form-control" name="notes_on">
                    <option value="general">General Note</option>
                    <option value="production">Note for Requested Order</option>
                    <option value="distribution">Note for NULL</option>
                    <option value="processing">Note for Shipped</option>
                    <option value="refund">Note for Refunded</option>
                    <option value="done">Note for Complete</option>
                    <option value="deleted">Note for Deleted</option>
                    <option value="order-hold">Note for Order Hold</option>
                    <option value="delivered">Note for Delivered</option>
                    <option value="fake-order">Note for Fake Order</option>
                    <option value="paid">Note for Paid</option>
                    <option value="payment-failed">Note for Payment Failed</option>
                    <option value="need-to-refund">Note for Need to Refund</option>
                    <option value="partial-paid">Note for Partial Paid</option>
                    <option value="partial-refunded">Note for Partial Refunded</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary btn-sm">
                Submit
            </button>
        </form>
    </div>
    <div class="col-md-6">
        <p class="lead" style="margin-bottom: 5px;">Staff notes:</p>
        @php
            $messages = \App\Models\Message::where('order_id', $order_master->id)->where('message_type', 'staff')->get();
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
                            <a class="btn btn-danger btn-xs">Delete</a>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</div>

@section('cusjs')
    <style>
        .timeline:before {
            content: none;
            position: none;
            top: 0;
            bottom: 0;
            width: 0px;
            background: #ddd;
            left: 0px;
            margin: 0;
            border-radius: 0px;
        }

        .timeline > li > .timeline-item {
            margin-left: 0px;
        }
    </style>
@endsection


