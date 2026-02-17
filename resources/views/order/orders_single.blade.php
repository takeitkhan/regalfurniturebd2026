@extends('layouts.app')

@section('title', 'Order Information')
@section('sub_title', 'all details of an order are here')
<?php $tksign = '&#2547; '; ?>
@section('content')
    @php
        $line = \App\Models\OrdersMaster::where('order_random', request()->id)->first();
        $order_details = App\Models\OrdersDetail::where(['order_random' => $line->order_random])->get();
    @endphp

    <div class="box box-solid">
        <div class="box-header with-border">
            <h3 class="box-title">Order # {{ $order_master->id ?? NULL }}</h3>
        </div>
        <div class="box-body">
            @if(Session::has('success'))
                <div class="col-md-12">
                    <div class="callout callout-success">
                        {{ Session::get('success') }}
                    </div>
                </div>
            @endif

            <div class="col-md-12">
                <div class="row">
                    <div class="btn-group">
                        <a href="?info_type=general" type="button"
                           class="btn {{ (request()->get('info_type') == 'general') ? ' btn-success' : ' btn-default'  }} ">
                            <i class="fa fa-toggle-{{ (request()->get('info_type') == 'general') ? 'on' : 'off'  }}"
                               aria-hidden="true"></i> General
                        </a>
                        <a href="?info_type=customer" type="button"
                           class="btn {{ (request()->get('info_type') == 'customer') ? ' btn-success' : ' btn-default'  }} ">
                            <i class="fa fa-toggle-{{ (request()->get('info_type') == 'general') ? 'on' : 'off'  }}"
                               aria-hidden="true"></i> Customer Activity
                        </a>
                        <a href="?info_type=staff" type="button"
                           class="btn {{ (request()->get('info_type') == 'staff') ? ' btn-success' : ' btn-default'  }} ">
                            <i class="fa fa-toggle-{{ (request()->get('info_type') == 'general') ? 'on' : 'off'  }}"
                               aria-hidden="true"></i> Staff Activity
                        </a>
                        <a href="?info_type=status" type="button"
                           class="btn {{ (request()->get('info_type') == 'status') ? ' btn-success' : ' btn-default'  }} ">
                            <i class="fa fa-toggle-{{ (request()->get('info_type') == 'status') ? 'on' : 'off'  }}"
                               aria-hidden="true"></i> Status & Message
                        </a>
                                <a href="?info_type=activity" type="button"
                                    class="btn {{ (request()->get('info_type') == 'activity') ? ' btn-success' : ' btn-default'  }} ">
                                     <i class="fa fa-toggle-{{ (request()->get('info_type') == 'activity') ? 'on' : 'off'  }}"
                                         aria-hidden="true"></i> Activity Logs
                                </a>
                    </div>
                </div>
            </div>
            <br/>
            <br/>

            @if(request()->get('info_type') == 'general')
                @include('order.order-details.general')
            @elseif(request()->get('info_type') == 'customer')
                @include('order.order-details.customer')
            @elseif(request()->get('info_type') == 'staff')
                @include('order.order-details.staff')
            @elseif(request()->get('info_type') == 'status')
                @include('order.order-details.status')
            @elseif(request()->get('info_type') == 'activity')
                @include('order.order-details.activity_logs')
            @endif

        </div>
    </div>
    {{--    @dump($order_master);--}}
    {{--    @dump($order_details);--}}

@endsection
@section('cusjs')
    <style>
        .invoice {
            margin: 0;
        }

        .title_bb {
            border-bottom: 1px solid #f1f1f1;
            padding-bottom: 5px;
        }
    </style>
@endsection
