@extends('frontend.layouts.app')

@section('content')



    <div class="qunans_product-area section-padding">
        <div class="container">
            <div class="row">
                <div class="col-md-3">

                    @php
                        $first_image = App\ProductImages::where('main_pid', $product->id)->where('is_main_image', 1)->get()->first();

                        $second_image = App\ProductImages::where('main_pid', $product->id)->where('is_main_image', 0)->get()->first();

                        $regularprice = $product->local_selling_price;
                        $save = ($product->local_selling_price * $product->local_discount) / 100;
                        $sp = round($regularprice - $save);


                        echo product_design_two([
                            'bootstrap_cols' => 'ptd-4 grit-row',
                            'singular_class' => 'single-product single-category-product',
                            'seo_url' => product_seo_url($product->seo_url, $product->id),
                            'straight_seo_url' => $product->seo_url,
                            'title' => limit_character($product->title, 35),
                            'first_image' => !empty($first_image) ? url($first_image['full_size_directory']) : null,
                            'second_image' => !empty($second_image) ? url($second_image['full_size_directory']) : null,
                            'discount_rate' => $product->local_discount,
                            'price' => $sp,
                            'old_price' => $regularprice,
                            'descriptions' => $product->description,
                            'product_code' => $product->product_code,
                            'product_sku' => $product->sku,
                            'product_id' => $product->id,
                            'product_qty' => 1,
                            'sign' => '&#2547; '
                        ]);
                    @endphp
                    <div class="qu-ans">
                        <button type="button" class="btn qu-ans_one" data-toggle="modal" data-target="#exampleModal">Post Your Comment</button>
                    </div>
                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title modal-title125" id="exampleModalLabel"><span style="font-weight: bold"></span> Post Your Comment and Reaction</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                   @if(Auth::check())
                                   <form action="{{route('product_comment_save',$product->id)}}" method="post">
                                       {{ csrf_field() }}
                                            <div class="form-group">
                                                <label for="comment">Write Comment</label>
                                                <textarea class="form-control" id="comment" rows="3" name="comment"></textarea>
                                            </div>
                                            <input type="submit" class="btn btn-primary btn-success" value="Submit">
                                   </form>
                                   @else
                                   
                                   <div class="text-danger">Sorry you must have to be logged user to write a comment.</div>
                                   
                                   @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-9">
                    <div class="qunans-area">
                        <div class="card">

                            <div class="card-body">
                                <div class="font-weight-bold">Comments ({{$comments->count()}})</div>

                                @if($comments->count())
                                
                                 @foreach($comments as $comment)
                                 
                                    <div class="single-qu">
                                        <p class="q-athur"><i class="fa fa-check-circle"></i> {{ $comment->user->name }}</p>
                                        <p class="qu_pg">{{ $comment->comment }}</p>
                                        <span class="text-muted">{{$comment->created_at->diffForHumans()}} </span>
                                    </div>
                                    
                                 @endforeach
                                 
                                 <div class="">{{$comments->links()}}</div>
                                
                                @else 
                                
                                 @include('frontend.common.error')
                                
                                @endif

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>



@endsection


@section('cusjs')

    <script type="text/javascript">
        //picZoomer
        jQuery(document).ready(function ($) {
            $.noConflict();

            $('.rating input').click(function () {

                $(".rating span").removeClass('checked');
                $(this).parent().addClass('checked');
            });

            $('input:radio').change(function(){
                $(this).prop("checked", true);
            });





        });

    </script>


<style type="text/css">


    .rating {
        float: left;
        width: 300px;
    }

    .rating span {
        float: right;
        position: relative;
    }

    .rating span input {
        position: absolute;
        top: 0px;
        left: 0px;
        opacity: 0;

    }

    .rating span input[type=radio] {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        background: transparent;
        position: relative;
        visibility: hidden;

    }

    .rating span input {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        background: transparent;
        position: relative;
        visibility: hidden;

    }

    .rating span label {
        display: inline-block;

        text-align: center;
        color: #ccc;
        font-size: 30px;
        margin-right: 2px;
        line-height: 30px;
        border-radius: 50%;
        -webkit-border-radius: 50%;
        font-size: 22px;
    }

    .rating span:hover ~ span label,
    .rating span:hover label,
    .rating span.checked label,
    .rating span.checked ~ span label {

        color: #fbe358;
    }
</style>
@endsection


