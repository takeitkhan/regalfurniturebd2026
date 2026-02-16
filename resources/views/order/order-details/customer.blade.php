<div class="row">
    <div class="col-md-6">
        <p class="lead" style="margin-bottom: 0px;">Order Complaint</p>

        <div class="text-muted well well-sm no-shadow" style="margin-top: 5px;">
            <ul class="products-list product-list-in-box">
                @php
                    $complaint = \App\Models\Notification::where('notification_for', 'order-complaint')
                                ->where('notification_for_id', $order_master->id)
                                ->orderBy('id', 'desc')->get();
                @endphp
                @foreach($complaint as $data)
                    <li class="item">
                        <div class="product-img">
                            <i class="fa fa-comments-o"></i>
                        </div>
                        <div class="product-info">
                            <a href="javascript:void(0)" class="product-title">
                                {{$data->notification_for}}  <span class="text-muted">{{$data->created_at->format('Y-m-d H:i a')}}</span>
                                <span class="label pull-right">
                                            @if($data->is_read == 0)
                                        <form action="{{route('notifications_read')}}" method="post">
                                               @csrf
                                               <input type="hidden" name="notification_id" value="{{$data->id}}">
                                               <button class="btn btn-xs btn-success" type="submit" title="is read"><i class="fa fa-check"></i></button>
                                           </form>
                                    @else
                                        <span class="text-green">  Solved</span>
                                    @endif
                                        </span>
                            </a>
                            <span class="product-description">
                                        {{$data->message}}
                                    </span>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="col-md-6">
        <p class="lead" style="margin-bottom: 0px;">Customer notes:</p>
        <div class="text-muted well well-sm no-shadow" style="margin-top: 5px;">
            @if(!empty($line->notes))
                {{ $line->notes }}
            @endif
        </div>
    </div>

</div>
