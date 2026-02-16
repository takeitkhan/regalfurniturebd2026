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
                        <button type="button" class="btn qu-ans_one" data-toggle="modal" data-target="#exampleModal">Post Your Review</button>
                    </div>
                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title modal-title125" id="exampleModalLabel"><span style="font-weight: bold"></span> Post Your Review and Rating</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                <div class="modal-body">
                                    @if($review_per)

                                    @php
                                        $my_review = \App\Review::where(['user_id' => auth()->user()->id, 'product_id' => $product->id, 'is_active' => 1])->get();

                                    @endphp
                                    {{ Form::open(array('url' => '/save_review', 'method' => 'post', 'value' => 'PATCH', 'id' => 'save_review')) }}

                                    <div class="rating">
                                                        <span <?=((@$my_review[0]->rating == 5) ? 'class="checked"' : '')?>>
                                                            <input type="radio" name="rating" id="str5"
                                                                   value="5" <?=((@$my_review[0]->rating == 5) ? 'checked' : '')?>>
                                                            <label for="str5"><i class="fa fa-star"></i></label>
                                                        </span>
                                        <span <?=((@$my_review[0]->rating == 4) ? 'class="checked"' : '')?>>
                                                                <input type="radio" name="rating" id="str4"
                                                                       value="4" <?=((@$my_review[0]->rating == 4) ? 'checked="checked"' : '')?>>
                                                                <label for="str4"><i class="fa fa-star"></i></label>
                                                            </span>
                                        <span <?=((@$my_review[0]->rating == 3) ? 'class="checked"' : '')?>>
                                                                <input type="radio" name="rating" id="str3"
                                                                       value="3" <?=((@$my_review[0]->rating == 3) ? 'checked' : '')?>>
                                                                <label for="str3"><i class="fa fa-star"></i></label>
                                                            </span>
                                        <span <?=((@$my_review[0]->rating == 2) ? 'class="checked"' : '')?>>
                                                                <input type="radio" name="rating" id="str2"
                                                                       value="2" <?=((@$my_review[0]->rating == 2) ? 'checked' : '')?>>
                                                                <label for="str2"><i class="fa fa-star"></i></label>
                                                            </span>
                                        <span <?=((@$my_review[0]->rating == 1) ? 'class="checked"' : '')?>>
                                                                <input type="radio" name="rating" id="str1"
                                                                       value="1" <?=((@$my_review[0]->rating == 1) ? 'checked' : '')?>>
                                                                <label for="str1"><i class="fa fa-star"></i></label>
                                                            </span>
                                    </div>

                                    <textarea class="form-control" name="comment" id="" cols="30" rows="10" placeholder="Your Questions"></textarea>
                                    <div class="qution_oner_tw" style="margin-top: 15px">

                                        {{ Form::submit('Write a review', ['class' => 'btn qu-ans_one', 'name' => 'submit']) }}

                                    </div>

                                    @if(@$my_review[0]->id)
                                        {{ Form::hidden('id', $my_review[0]->id, ['required']) }}
                                    @endif
                                    {{ Form::hidden('product_id', $product->id, ['required', 'id' => 'product_id']) }}
                                    {{ Form::hidden('vendor_id', $product->user_id, ['required', 'id' => 'product_id']) }}
                                    {{ Form::hidden('user_id',auth()->user()->id, ['required', 'id' => 'user_id']) }}

                                    {{ Form::close() }}

                                    @else

                                        <p>Only authentication user can review.</p>
                                        <br/>
                                        <a class="btn btn-success" href="{{url('/login_now')}}">Login now</a>


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
                                @foreach($reviews as $review)
                                    @php
                                    $customer =  App\User::Where('id', $review->user_id)->get()->first();
                                    @endphp

                                    <div class="single-qu">
                                        <p class="qu_pg"><span class="single-qu_on rating_post"> {{ $review->rating }} <i class="fa fa-star"></i></span>: {{ $review->comment }}</p>
                                        <p class="q-athur"><i class="fa fa-check-circle"></i> {{ $customer->name }}</p>

                                    </div>

                                @endforeach


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


