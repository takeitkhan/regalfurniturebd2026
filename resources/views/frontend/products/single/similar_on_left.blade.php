<?php
$substr = substr($pro->title, 0, 6);
$similar = \App\Product::where('title', 'like', '%' . $substr . '%')->get();
//dd($similar);
?>
@if(!empty($similar)  && count($similar) > 0)

    <section class="recent-view">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="recently-viewed-title-warp">
                        <div class="section-title recently-viewed-title">
                            <h3>Similar Products</h3>
                        </div>
                        <div class="recently-viewed-btn">
                            <a href="#">View All</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="similar-products owl-carousel owl-theme">

                    {{--<div class="col-md-12">--}}

                        {{--<div class="single-product single-category-product">--}}
                            {{--<div class="product-img">--}}
                                {{--<a href="#">--}}
                                    {{--<img src="images/products/1.png" alt="product">--}}
                                    {{--<h3 class="product-eft ">30% OFF</h3>--}}
                                {{--</a>--}}
                            {{--</div>--}}
                            {{--<div class="product-over">--}}
                                {{--<div class="product-over-left">--}}
                                    {{--<div class="product-title">--}}
                                        {{--<h3><a href="#">Shoe Rack</a></h3>--}}
                                        {{--<h3 class="mdl"><a href="#">SRH-111-1-3-63</a></h3>--}}
                                    {{--</div>--}}
                                    {{--<div class="prodict-price">--}}
                                        {{--<div class="ct-price">--}}
                                            {{--<p>৳ 6,500</p>--}}
                                        {{--</div>--}}
                                        {{--<div class="old-price">--}}
                                            {{--<p><span class="old-pc">৳ 7,800</span>  <span class="save-pc"> Save ৳ 1,400</span></p>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                                {{--<div class="product-over-right">--}}
                                    {{--<div class="product-price-btn details-btn">--}}
                                        {{--<a href="#">Details</a>--}}
                                    {{--</div>--}}
                                    {{--<div class="product-price-btn buy-btn">--}}
                                        {{--<a  href="#">Buy</a>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    @foreach($similar as $pro)
                    @php 
                    $pro = \App\Product::where('id', $pro->id)->get()->first();
                    @endphp
                    
                    
                    @if($pro->is_active)
                    <div class="col-md-12">
                        @php
                            $first_image = \App\ProductImages::where('main_pid', $pro->id)->where('is_main_image', 1)->get()->first();

                            $second_image = \App\ProductImages::where('main_pid', $pro->id)->where('is_main_image', 0)->get()->first();
                            
                            $img = isset($second_image->full_size_directory) ? url($second_image->full_size_directory) : false;
                            $img = !$img && !empty($first_image->full_size_directory) ? url($first_image->full_size_directory) : $img;
                            $img = !$img ? url('storage/uploads/fullsize/2019-01/default.jpg') : $img;
                                
                                
                            $second_image = isset($second_image->full_size_directory) && isset($first_image->full_size_directory) ? url($first_image->full_size_directory) : null;
                        
                            
                            
                            $regularprice = $pro->local_selling_price;
                            $save = ($pro->local_selling_price * $pro->local_discount) / 100;
                            $sp = $regularprice - $save;

                            echo product_design_two([
                                'bootstrap_cols' => 'item',
                                'seo_url' => product_seo_url($pro->seo_url, $pro->id),
                                'title' => $pro->title,
                                'first_image' => $img,
                                'second_image' => $second_image,
                                'discount_rate' => $pro->local_discount,
                                'price' => $sp,
                                'old_price' => $regularprice,
                                'descriptions' => $pro->description,
                                'product_code' => $pro->product_code,
                                'product_sku' => $pro->sku,
                                'product_id' => $pro->id,
                                'product_qty' => 1,
                                'sign' => '&#2547; '
                            ]);
                            
                        @endphp
                        </div>
                        @endif
                    @endforeach

                </div>
            </div>
        </div>
    </section>



@endif