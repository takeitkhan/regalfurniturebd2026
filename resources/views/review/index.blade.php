@extends('layouts.app')

@section('title', 'Reviews')
@section('sub_title', 'Reviews management panel')
@section('content')
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

        <div class="col-md-12">

            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title box-success">Review &nbsp; &nbsp;

                    </h3>
                </div>
                <div class="box-body" style="">
                    <div class="box-body table-responsive no-padding" id="reload_me">
                        <table class="table table-hover">
                            <tbody>
                            <tr>
                                <th>ID</th>
                                <th>Customer Name</th>
                                <th>Product Name</th>
                                <th>Ratting</th>
                                <th>Status</th>
                                <th>Action</th>

                            </tr>
                            {{--{{dd($newsletters)}}--}}

                            @foreach($reviews as $review)
                                @php
                                    $product_info = \App\Models\Product::where(['id'=>$review->product_id])->get();
                                    $customer_info = \App\Models\User::where(['id'=>$review->user_id])->get();
                                   // dump($product_info);
                                @endphp
                                <tr>
                                    <td>{{ $review->id }}</td>
                                    <td>
                                        <a xhref="{{product_seo_url($product_info[0]->seo_url )}}" href="http://regalfurniturebd.com/product/{{$product_info[0]->seo_url}}" target="_blank" style="color: #333">
                                        <b>Name# </b>
                                            {{ $product_info[0]->title }}
                                        </a><br>

                                        <b>ID#</b> {{$review->product_id}}
                                    </td>
                                    <td>
                                        <b>Name# </b>{{ $customer_info[0]->name }}<br>
                                        <b>Name# </b>{{ $customer_info[0]->phone }}<br>
                                        <b>ID#</b> {{$customer_info[0]->email}}
                                    </td>
                                    <td>{{ $review->rating.' stars'}}</td>
                                    <td>{{ (($review->is_active == 1)? 'Active': 'Inactive') }}</td>

                                    <td>
                                        @if($review->is_active == 1)
                                            <a class="btn btn-xs btn-success"
                                               href="{{ url("quick_review_approve/{$review->id}/0") }}">
                                                <i class="fa fa-check-square-o"></i>
                                            </a>
                                        @else
                                            <a class="btn btn-xs btn-warning"
                                               href="{{ url("quick_review_approve/{$review->id}/1") }}">
                                                <i class="fa fa-pause"></i>
                                            </a>
                                        @endif
                                    </td>

                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                        <div class="box-footer clearfix">
                            {{ $reviews->links('component.paginator', ['object' => $reviews]) }}
                        </div>
                        <!-- /.pagination pagination-sm no-margin pull-right -->
                    </div>

                </div>
                <div class="box-footer clearfix">
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        jQuery(document).ready(function ($) {
            $.noConflict();

        });
    </script>
@endpush
