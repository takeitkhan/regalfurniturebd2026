@extends('layouts.app')

@section('title', 'Most Selling Products')
@section('sub_title', 'all most selling products are here')
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
            <div class="box">
                <div class="box-body table-responsive no-padding" id="reload_me">
                    <table class="table table-hover">
                        <tbody>
                        <tr>
                            <th title="Product ID">PID</th>
                            <th title="Image">Image</th>
                            <th title="Product Title">Product Title</th>
                            <th title="Product Code">Product Code</th>
                            <th>Total Quantity</th>
                            <th>Total Amount</th>
                            <th title="Product Backend URL">BURL</th>
                            <th title="Product Frontend URL">FURL</th>
                        </tr>
                        @foreach($most_sold as $line)
                            @php
                                $pro = \App\Models\Product::where('id', $line->product_id)->get()->first();
                                // dd($pro->firstImage);
                            @endphp
                            <tr>
                                <td>{{ $line->product_id }}</td>
                                <td><img src="{{ asset($pro->firstImage->full_size_directory??null) }}" height="60px" width="80px" alt="" srcset=""></td>
                                <td>{{ $pro->title }}</td>
                                <td>{{ $pro->product_code }}</td>
                                <td>{{ $line->total_qty }}</td>
                                <td>{{ $tksign . $line->total_amount }}</td>
                                <td>
                                    <a class="btn btn-xs btn-primary" href="{{ url('/edit_product/' . $pro->id) }}"
                                       target="_blank">
                                        <i class="fa fa-link"></i>
                                    </a>
                                </td>
                                <td>
                                    <a class="btn btn-xs btn-info" href="{{ url('/p/' . $pro->seo_url) }}"
                                       target="_blank">
                                        <i class="fa fa-link"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="box-footer clearfix">
                        {{ $most_sold->links('component.paginator', ['object' => $most_sold]) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection