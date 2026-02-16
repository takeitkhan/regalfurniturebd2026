@extends('layouts.app')


@section('title', 'One Click Buy Now')


@section('sub_title', '')


@section('content')

    <div class="row">
        @if(Session::has('success'))
            <div class="col-md-12">
                <div class="callout callout-success">
                    {{ Session::get('success') }}
                </div>
            </div>
        @endif

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
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    <table class="table table-sm">
                        <thead>
                        <tr>
                            <td>ID</td>
                            <td>Date</td>
                            <td>Customer Info</td>
                            <td>Product Info</td>
                            <td>Status</td>
                            <td>Action</td>
                        </tr>
                        </thead>
                        <tbody>

                            @php
                                $orders = \App\Models\Oneclickbuy::orderBy('id', 'desc')->get();
                            @endphp
                            @foreach($orders as $order)
                            <tr>
                                <td>{{$order->id}}</td>
                                <td>
                                    {{$order->created_at->format('Y-m-d')}}
                                </td>
                                <td>
                                    <small>
                                        <strong>Name:</strong> {{$order->customer_name}} <br>
                                        <strong>Email:</strong> {{$order->customer_email}} <br>
                                        <strong>Phone:</strong> {{$order->customer_phone}} <br>
                                        <strong>Address:</strong> {{$order->customer_address}} <br>
                                    </small>
                                </td>

                                <td>
                                    @php
                                        $product = \App\Models\Product::where('id', $order->product_id)->first();
                                    @endphp
                                    <small>
                                        <strong>Name:</strong> {{$product->title}} <br>
                                        <strong>Sub title:</strong> {{$product->sub_title}} <br>
                                        <strong>Code:</strong> {{$product->product_code}} <br>
                                        <strong>SKU:</strong> {{$product->sku}} <br>
                                    </small>
                                </td>
                                <td>
                                    <small>
                                        <strong>Status:</strong> {{$order->order_status}}
                                    </small>
                                </td>
                                <td>
                                    @if($order->order_status == 'pending')
                                        <a href="{{route('order.custom_order')}}?from-oneclickbuy={{$order->id}}" class="btn btn-info btn-sm">
                                            Make order
                                        </a>
                                        <form onSubmit="if(!confirm('Are you confirm to action?')){return false;}" action="{{route('order.one_click_buy_now_update')}}" method="post" style="display: inline-block">
                                            @csrf
                                            <input type="hidden" name="id" value="{{$order->id}}">
                                            <input type="hidden" name="order_status" value="cancel">
                                            <button class="btn btn-warning btn-sm" type="submit">
                                                Cancel
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


@endsection



@push('scripts')

@endpush
