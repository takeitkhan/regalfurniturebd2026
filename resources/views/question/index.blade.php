@extends('layouts.app')


@section('title', 'Product Qutions')
@section('sub_title', 'all product qutions management panel')
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

                </div>
                <div class="box-body" style="">
                    <div class="box-body table-responsive no-padding" id="reload_me">
                        <table class="table table-hover">
                            <tbody>
                            <tr>
                                <th>#</th>
                                <th>Customer</th>
                                <th>Product</th>
                                <th>Quetion</th>
                                <th>Ansewer</th>
                                <th>Action</th>
                            </tr>

                            @foreach($questions as $line)
                                @php
                                    $pro = App\Models\Product::where('id', $line->main_pid)->get()->first();
                                    $cus = App\Models\User::where('id', $line->user_id)->get()->first();
                                    $ven = App\Models\User::where('id', $line->vendor_id)->get()->first();
                                    $ans = App\Models\ProductQuestion::where(['que_id' => $line->id, 'qa_type' => 2])->get();
                                @endphp
                                <tr>
                                    <td>
                                        {{$line->id}}
                                    </td>
                                    <td>
                                        {{$cus->name}} <br>
                                        {{$cus->phone}} <br>
                                        {{$cus->email}}
                                    </td>
                                    <td>
                                         {{$pro->title}} <br>
                                         {{$pro->sub_title}}<br>
                                        <a href="{{ url('/product/'.$pro->seo_url) }}">View</a>

                                    </td>

                                    <td>{!! $line->description !!}</td>
                                    <td>{{ $ans->count() }}</td>



                                    <td>


                                        <button type="button" class="btn btn-xs btn-success" data-toggle="modal" data-target="#questionModal{{$line->id}}">
                                            <i class="fa fa-plus-square"> A:</i>
                                        </button>

                                        @if($line->is_active == 1)
                                            <a class="btn btn-xs btn-success"
                                               href="{{ url('questions_isActive/'.$line->id) }}">
                                                <i class="fa fa-check"></i>
                                            </a>
                                        @else
                                            <a class="btn btn-xs btn-success"
                                               href="{{ url('questions_isActive/'.$line->id) }}">
                                                <i class="fa fa-check-square-o"></i>
                                            </a>

                                        @endif



                                        <div class="modal fade" id="questionModal{{$line->id}}" tabindex="-1" role="dialog" aria-labelledby="questionModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title modal-title125" id="exampleModalLabel"><span style="font-weight: bold">Q:</span> {!! $line->description !!}</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">

                                                        @foreach($ans as $lin)
                                                           Ans:  {!! $lin->description !!} </br>

                                                            @endforeach
                                                            </br>
                                                        {{ Form::open(array('url' => '/product_question_ans/'.$line->id, 'method' => 'post', 'value' => 'PATCH', 'id' => 'product_question_post')) }}
                                                        <textarea class="form-control" name="post" id="" cols="30" rows="10" placeholder="Your Questions"></textarea>
                                                        <div class="qution_oner_tw" style="margin-top: 15px">

                                                            {{ Form::submit('Post Your Question', [ 'class' => 'btn qu-ans_one','name' => 'submit_post' ]) }}

                                                        </div>
                                                        {{ Form::close() }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>



                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="box-footer clearfix">
                            {{ $questions->links('component.paginator', ['object' => $questions]) }}
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
    <style type="text/css">

    </style>
@endpush