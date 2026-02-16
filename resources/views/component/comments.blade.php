<?php
$comments = App\Comment::where('item_id', $product->id)->where('is_active', 1)->orderBy('id', 'desc')->get();
//dd($comments);
?>
@if(!empty($comments))
    <h4>All Comments and answered on different questions</h4>
    @foreach($comments as $comment)
        @if($comment->parent_id == null)
            <div class="panel panel-default">
                <div class="panel-body">


                    <div class="icone-area-warp">
                        <div class="icone-area-leftQ">
                            <p>Q</p>
                        </div>
                        <div class="icone-area-rightQ">
                            <h3>
                                {{ $comment->commenter }}
                                <small> | Posted @ {{ $comment->created_at }}</small>
                            </h3>
                            <p>
                                {{ $comment->comment }}
                            </p>
                        </div>
                    </div>

                    @php
                        $exists = App\Comment::where('parent_id', $comment->id)->get()->first();
                        //dd($exists);
                    @endphp
                    <div class="clearfix"></div>

                    @if($exists != null)

                        <div class="welly">
                            {{--<div class="action pull-right">--}}
                            {{--<a class="btn btn-xs" href="javascript:void(0)"><i class="fa fa-times"></i></a>--}}
                            {{--</div>--}}
                            <div class="icone-area-warp answer">
                                <div class="icone-area-leftA">
                                    <p>A</p>
                                </div>
                                <div class="icone-area-rightA">
                                    <p>
                                        {{ $exists->comment }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <hr style="border-top: 1px dashed #DDDDDD;"/>

                    @endif


                </div>
            </div>
        @endif
    @endforeach
@endif