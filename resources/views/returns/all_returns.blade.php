@extends('layouts.app')

@section('title', 'Returns')
@section('sub_title', 'Returns management panel')
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
            <div class="box">
                <?php //dump($returns);?>

                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding" id="reload_me">
                    <table class="table table-hover">
                        <tbody>
                        <tr>
                            <th>#</th>
                            <th>Customer</th>
                            <th>Order info</th>
                            <th>Product info</th>
                            <th style="width: 40%">Massage</th>
                            <th>Action</th>
                        </tr>
                        @foreach($returns as $ret)
                            <tr>
                                <td>{{ $ret->id }}</td>
                                <td>
                                    <b title="Customer Name">CN#</b> {{ $ret->first_name.''.$ret->last_name }}<br>
                                    <b title="Email">E#</b> {{ $ret->email }}<br>
                                    <b title="Telephone">T#</b> {{ $ret->telephone }}
                                </td>
                                <td>
                                    <b title="Date of ordered">OD#</b> {{ $ret->date_ordered }}<br>
                                    <b title="Order ID">OI#</b> {{ $ret->order_id }}<br>
                                    <b title="Reason For Return">RFR#</b> {{ $ret->reason_return }}
                                </td>
                                <td>
                                    <b title="Product Name">PN#</b> {{ $ret->product_name }}<br>
                                    <b title="Product Code">PC#</b> {{ $ret->product_code }}<br>
                                    <b title="Quantity">Qty#</b> {{ $ret->quantity }}<br>
                                    <b title="Is product opened">IPO#</b> {{ $ret->product_opened }}
                                </td>
                                <td>
                                    <b title="Comment">comment#</b> {{ $ret->comment }}

                                </td>
                                <td>
                                    @if($ret->is_active == 1)
                                        <a class="btn btn-xs btn-success"
                                           href="{{ url("quick_returns_approve/{$ret->id}") }}">
                                            <i class="fa fa-check-square-o"></i>
                                        </a>
                                    @else
                                        <a class="btn btn-xs btn-success"
                                           href="{{ url("quick_returns_approve/{$ret->id}") }}">
                                            <i class="fa fa-check"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="box-footer clearfix">
                        {{ $returns->links('component.paginator', ['object' => $returns]) }}
                    </div>
                    <!-- /.pagination pagination-sm no-margin pull-right -->
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>
@endsection
