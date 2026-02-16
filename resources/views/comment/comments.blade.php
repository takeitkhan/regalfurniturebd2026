@extends('layouts.app')

@section('title', 'Comments')
@section('sub_title', 'Comments management panel')
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
                <div class="box-header">
                    <h3 class="box-title">
                        Comments
                        <a href="{{ url('add_comment') }}" class="btn btn-xs btn-success">
                            <i class="fa fa-plus"></i>
                        </a>
                    </h3>

                    <div class="box-tools">
                        <div class="input-group input-group-sm" style="width: 150px;">
                            <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">

                            <div class="input-group-btn">
                                <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding" id="reload_me">
                    <table class="table table-hover">
                        <tbody>
                        <tr>
                            <th>#</th>
                            <th style="max-width: 100px;" title="Name/Email/Phone">N/E/P</th>
                            <th style="max-width: 180px;" title="Add your reply">Reply</th>
                            <th style="max-width: 180px;">Comment</th>
                            <th>Date Created/ Updated</th>
                            <th>Action</th>
                        </tr>
                        @foreach($comments as $comment)
                            @if($comment->parent_id == null)
                                <tr>
                                    <td>{{ $comment->id }}</td>
                                    <td style="max-width: 100px;">
                                        {{ $comment->commenter }}<br/>
                                        {{ $comment->commenter_email }}
                                        <br/>{{ $comment->commenter_phone }}
                                    </td>
                                    <td style="max-width: 180px;">
                                        @php
                                            $exists = App\Models\Comment::where('parent_id', $comment->id)->get()->first();
                                            //dd($exists);
                                        @endphp
                                        @if($exists == null)
                                            <a class="btn btn-xs btn-success"
                                               href="{{ url('add_reply?comment_id=' . $comment->id) }}">Add Reply</a>
                                        @else
                                            {!!  limit_text($exists->comment, 100)  !!}
                                        @endif
                                    </td>
                                    <td style="max-width: 180px;">{!!  limit_text($comment->comment, 500)  !!}</td>
                                    <td>
                                        @if($comment->comment_on === 'products')
                                            @php
                                                $product = App\Models\Product::where('id', $comment->item_id)->get()->first();
                                                echo 'Pro: <a target="_blank" href="'. url('product/' . $product->seo_url) .'">' . $product->title . '</a>';
                                            @endphp
                                        @elseif($comment->comment_on === 'posts')

                                        @endif
                                        <br/>
                                        {{ $comment->created_at }}<br/>
                                        {{ $comment->updated_at }}
                                    </td>
                                    <td>
                                        @if($comment->is_active == 1)
                                            <a class="btn btn-xs btn-success"
                                               href="{{ url("quick_comment_approve/{$comment->id}") }}">
                                                <i class="fa fa-check-square-o"></i>
                                            </a>
                                        @else
                                            <a class="btn btn-xs btn-success"
                                               href="{{ url("quick_comment_approve/{$comment->id}") }}">
                                                <i class="fa fa-check"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                    <div class="box-footer clearfix">
                        {{ $comments->links('component.paginator', ['object' => $comments]) }}
                    </div>
                    <!-- /.pagination pagination-sm no-margin pull-right -->
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>
@endsection

@section('cusjs')
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $.noConflict();
            $('#myModal').modal();
        });
    </script>
@endsection