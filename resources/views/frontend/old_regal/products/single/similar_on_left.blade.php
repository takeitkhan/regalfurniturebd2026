<?php
$substr = substr($pro->title, 0, 6);
$similar = \App\Product::where('title', 'like', '%' . $substr . '%')->get();
//dd($similar);
?>
@if(!empty($similar)  && count($similar) > 0)

    <div class="related titleLine products-list grid module ">
        <h3 class="modtitle">Similar Products</h3>

        <div class="releate-products yt-content-slider products-list" data-rtl="no" data-loop="yes"
             data-autoplay="no" data-autoheight="no" data-autowidth="no" data-delay="4" data-speed="0.6"
             data-margin="30" data-items_column00="5" data-items_column0="5" data-items_column1="3"
             data-items_column2="3" data-items_column3="2" data-items_column4="1" data-arrows="yes"
             data-pagination="no" data-lazyload="yes" data-hoverpause="yes">


            @foreach($similar as $pro)
                @php
                    $pro = \App\Product::where('id', $pro->id)->get()->first();
                    $first_image = \App\ProductImages::where('main_pid', $pro->id)->where('is_main_image', 1)->get()->first();

                    if (!empty($first_image->full_size_directory)) {
                        $img = url($first_image->full_size_directory);
                    } else {
                        $img = url('storage/uploads/fullsize/2019-01/default.jpg');
                    }

                    $second_image = \App\ProductImages::where('main_pid', $pro->id)->where('is_main_image', 0)->get()->first();
                    $regularprice = $pro->local_selling_price;
                    $save = ($pro->local_selling_price * $pro->local_discount) / 100;
                    $sp = $regularprice - $save;

                    echo product_design([
                        'bootstrap_cols' => 'item',
                        'seo_url' => product_seo_url($pro->seo_url, $pro->id),
                        'title' => $pro->title,
                        'first_image' => $img,
                        'second_image' => !empty($second_image) ? url($second_image->full_size_directory) : null,
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
            @endforeach


        </div>
    </div>

@endif