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
                           <button type="button" class="btn qu-ans_one" data-toggle="modal" data-target="#exampleModal">Post Your Questions</button>
                        </div>
                        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title modal-title125" id="exampleModalLabel"><span style="font-weight: bold">Q:</span> Post Your Questions</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <div class="modal-body">
                                  {{ Form::open(array('url' => '/product_question_post/'.$product->id, 'method' => 'post', 'value' => 'PATCH', 'id' => 'product_question_post')) }}
                                    <textarea class="form-control" name="post" id="" cols="30" rows="10" placeholder="Your Questions"></textarea>
                                    <div class="qution_oner_tw" style="margin-top: 15px">

                                        {{ Form::submit('Post Your Question', [ 'class' => 'btn qu-ans_one','name' => 'submit_post' ]) }}

                                    </div>
                                  {{ Form::close() }}
                              </div>
                            </div>
                          </div>
                        </div>
                    </div>

                <div class="col-md-9">
                    <div class="qunans-area">
                        <div class="card">
                            
                            <div class="card-body">
                                @if($questions->count() > 0)
                                @foreach($questions as $que)
                                    @php
                                        $customer =  App\User::Where('id', $que->user_id)->get()->first();
                                        $answer = App\ProductQuestion::Where('que_id', $que->id)->get();

                                    @endphp


                                <div class="single-qu">
                                    <p class="qu_pg"><span class="single-qu_on">Q</span>: {{ $que->description }}</p>
                                    <p class="q-athur"><i class="fa fa-check-circle"></i> {{ $customer->name }}</p>
                                    @foreach($answer as $ans)

                                        @php
                                            $admin =  App\User::Where('id', $que->user_id)->get()->first();
                                        @endphp

                                    <p class="qu_pg_on"><span class="single-qu_on">A</span>: {{ $ans->description }}</p>
                                    <p class="q-athur"><i class="fa fa-check-circle"></i> {{$admin->name}}</p>
                                        @endforeach
                                </div>
                                @endforeach
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