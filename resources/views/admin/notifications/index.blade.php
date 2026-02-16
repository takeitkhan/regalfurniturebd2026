@extends('layouts.app')

@section('title', 'Notification')
@section('sub_title', '')
@section('content')
    @php
        $unread = \App\Models\Notification::where('is_read', 0)->orderBy('id', 'desc')->get();
        $read = \App\Models\Notification::where('is_read', 1)->orderBy('id', 'desc')->paginate(20);
    @endphp
    <div class="row">
        <div class="col-md-6">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">Unread Notifications</h3>
                    <div class="box-tools pull-right">

                    </div>
                </div>

                <div class="box-body" style="">
                    <ul class="products-list product-list-in-box">

                        @foreach($unread as $data)
                            @php
                                switch($data->notification_for){
                                    case('order'):
                                        $random= App\Models\OrdersMaster::where('id',  $data->notification_for_id)->first()->order_random ?? false;
                                        $route = $random ? route('orders_singleorders_single', $random).'?info_type=general' : null;
                                        break;
                                     case('order-complaint'):
                                        $random= App\Models\OrdersMaster::where('id',  $data->notification_for_id)->first()->order_random ?? false;
                                        $route = $random ? route('orders_singleorders_single', $random).'?info_type=general' : null;
                                        break;
                                    default:
                                        $route = null;
                                }
                            @endphp
                        <li class="item">
                            <div class="product-img">
                                <i class="fa fa-comments-o"></i>
                            </div>
                            <div class="product-info">
                                <a href="{{$route}}" class="product-title">
                                    {{$data->notification_for}}  <span class="text-muted">{{$data->created_at->format('Y-m-d H:i a')}}</span>
                                    <span class="label pull-right">
                                       <form action="{{route('notifications_read')}}" method="post">
                                           @csrf
                                           <input type="hidden" name="notification_id" value="{{$data->id}}">
                                           <button class="btn btn-xs btn-success" type="submit" title="is read"><i class="fa fa-check"></i></button>
                                       </form>
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
        </div>

        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Already Solved</h3>
                    <div class="box-tools pull-right">

                    </div>
                </div>

                <div class="box-body" style="">
                    <ul class="products-list product-list-in-box">

                        @foreach($read as $data)
                            @php
                                switch($data->notification_for){
                                    case('order'):
                                        $random= App\Models\OrdersMaster::where('id',  $data->notification_for_id)->first()->order_random ?? false;
                                        $route = $random ? route('orders_singleorders_single', $random).'?info_type=general' : null;
                                        break;
                                    default:
                                        $route = null;
                                }
                            @endphp
                            <li class="item">
                                <div class="product-img">
                                    <i class="fa fa-comments-o"></i>
                                </div>
                                <div class="product-info">
                                    <a href="{{$route}}" class="product-title">
                                        {{$data->notification_for}}  <span class="text-muted">{{$data->created_at->format('Y-m-d H:i a')}}</span>
                                        <span class="label pull-right">
                                    </span>
                                    </a>
                                    <span class="product-description">
                                    {{$data->message}}
                                    </span>
                                    <span class="product-description">
                                    Updated at {{$data->updated_at->format('Y-m-d H:i a')}}
                                    </span>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="box-footer clearfix">
                    {{ $read->links('component.paginator', ['object' => $read,'more' => 'some']) }}
                </div>

            </div>
        </div>
    </div>
@endsection
