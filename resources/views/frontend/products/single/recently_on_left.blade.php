@if(!empty($seen))


    <section class="recent-view">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="recently-viewed-title-warp">
                        <div class="section-title recently-viewed-title">
                            <h3>Recently Viewed</h3>
                        </div>
                        <div class="recently-viewed-btn">
                            <a href="#">View All</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="recently-viewed owl-carousel owl-theme">


                        @foreach($seen as $pro)
                        <div class="col-md-12">
                            @php
                                $pro = \App\Product::where('id', $pro['product_id'])->get()->first();
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
                        @endforeach



                </div>
            </div>
        </div>
    </section>


@endif