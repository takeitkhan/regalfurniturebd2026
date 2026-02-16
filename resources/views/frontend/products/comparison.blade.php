@extends('frontend.layouts.app')

@section('content')
    <?php $tksign = '&#2547; '; ?>


 <section class="breadcrumb-area">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="breadcrumb-warp">
                    <div class="breadcrumb-one">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Product Comparison</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


    <div class="main-container container">
       <!--  <ul class="breadcrumb">
            <li><a href="#"><i class="fa fa-home"></i></a></li>
            <li><a href="#">Product Comparison</a></li>
        </ul>
 -->
        <div class="row">
            @if (Session::has('comparison'))
                <div id="content" class="col-sm-12">
                    <h2 class="title">Product Comparison</h2>


                    <?php
                    $limit = 3;
                    $oldcomparison = Session::get('comparison');
                    ?>
                   @if (!empty($oldcomparison->items) && count($oldcomparison->items) > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <td colspan="4"><strong>Product Details</strong></td>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <th>Image</th>
                                    @php
                                        $i = 0
                                    @endphp
                                    @foreach($oldcomparison->items as $title)
                                        <?php
                                        $product = App\Product::where('id', $title['item']['productid'])->first();
                                        $pro = (object)$product;
                                        $first_image = \App\ProductImages::where('main_pid', $pro->id)->where('is_main_image', 1)->get()->first();
                                        $second_image = \App\ProductImages::where('main_pid', $pro->id)->where('is_main_image', 0)->get()->first();
                                        ?>
                                        @if($i <= 2)
                                            <th width="25%">
                                                <div class="fst_image">
                                                    <a href="{{ url('p/' .  $pro->seo_url) }}" target="_self"
                                                       title="{{ $pro->title }}">
                                                        <?php if(!empty($first_image)) { ?>
                                                        <img width="100%"
                                                             src="{!! url($first_image->full_size_directory) !!}"
                                                             class="img-1 img-responsive" alt="{{ $pro->title }}">
                                                        <?php } ?>
                                                    </a>
                                                </div>
                                            </th>
                                        @endif
                                        @php
                                            $i++
                                        @endphp
                                    @endforeach
                                </tr>
                                <tr>
                                    <td>Product Name</td>
                                    @php
                                        $i = 0
                                    @endphp
                                    @foreach($oldcomparison->items as $title)
                                        <?php $product = App\Product::where('id', $title['item']['productid'])->first(); ?>
                                        @if($i <= 2)
                                          
                                            <th width="25%">
                                                <a href="{{ url('/product/'.$product->seo_url) }}">
                                                    {!! $product->title . '<br>'. $product->sub_title !!}
                                                </a>
                                            </th>
                                        @endif
                                        @php
                                            $i++
                                        @endphp
                                    @endforeach
                                </tr>

                                <tr>
                                    <td>Short Description</td>
                                    @php
                                        $i = 0
                                    @endphp
                                    @foreach($oldcomparison->items as $title)
                                        <?php $product = App\Product::where('id', $title['item']['productid'])->first(); ?>
                                        @if($i <= 2)
                                            <th width="25%">
                                                {{ $product->short_description }}
                                            </th>
                                        @endif
                                        @php
                                            $i++
                                        @endphp
                                    @endforeach
                                </tr>
                                <tr>
                                    <td>Price</td>

                                    @php
                                        $i = 0
                                    @endphp
                                    @foreach($oldcomparison->items as $title)
                                        <?php
                                        $product = App\Product::where('id', $title['item']['productid'])->first();

                                        $pro = (object)$product;
                                        $regularprice = $pro->local_selling_price;
                                        $save = ($pro->local_selling_price * $pro->local_discount) / 100;
                                        $sp = $regularprice - $save;
                                        ?>
                                        @if($i <= 2)
                                            <td style="text-align: center; color: red;font-weight: bold;">
                                                <div class="price">
                                                    <?php
                                                    if ($regularprice < $sp) {
                                                        $price = '<span class="price-new">' . $tksign . number_format($sp) . '</span>';
                                                        $price .= '<span class="price-old">' . $tksign . number_format($regularprice) . '</span>';
                                                    } else {
                                                        $price = '<span class="price-new">' . $tksign . number_format($sp) . '</span>';
                                                    }
                                                    ?>
                                                    {!! $price !!}
                                                </div>
                                            </td>
                                        @endif
                                        @php
                                            $i++
                                        @endphp
                                    @endforeach
                                </tr>

                                <tr>
                                    <td>Specifications</td>
                                    @php
                                        $i = 0
                                    @endphp
                                    @foreach($oldcomparison->items as $title)
                                        <?php $product = App\Product::where('id', $title['item']['productid'])->first(); ?>
                                        @if($i <= 2)
                                        <th width="25%" style="text-align: left">
                                                <?php
                                                $attribute_data = \App\ProductAttributesData::leftJoin('attributes', function ($join) {
                                                    $join->on('productattributesdata.attribute_id', '=', 'attributes.id');
                                                })->where('main_pid', $pro->id)->get();
                                                ?>

                                                @foreach($attribute_data as $att)
                                                    @if(!empty($att->value))
                                                            <p>{{ $att->field_label }} : <span>{{ $att->value }}</span></p>


                                                    @endif
                                                @endforeach

                                        </th>

                                            
                                        @endif
                                        @php
                                            $i++
                                        @endphp
                                    @endforeach
                                </tr>

                                <tr>
                                    <td></td>

                                    @php
                                        $i = 0
                                    @endphp
                                    @foreach($oldcomparison->items as $title)
                                        <?php
                                        $product = App\Product::where('id', $title['item']['productid'])->first();


                                        $pro = (object)$product;
                                        ?>
                                        @if($i <= 2)
                                            <td>
                                                <div class="button_cp">
                                                    <a href="javascript:void(0)"
                                                       class="btn btn-block1"
                                                       onclick="remove_compare_product('{{ $pro->id }}', '{{ $pro->product_code }}');">
                                                        REMOVE
                                                    </a>
                                                     <button type="button"
                                                            class="btn btn-block"
                                                            title="Add to cart"
                                                            data-qty="1"
                                                            data-color_id="{{ $title['item']['multi_id'] }}"
                                                            data-size_id=" {{ $title['item']['multi_id'] }}"
                                                            data-productid="{{ $pro->id }}"
                                                            onclick="add_to_cart_data(this);"
                                                            >
                                                        <span>Add to cart </span>
                                                    </button>
                                                </div>
                                            </td>
                                        @endif
                                        @php
                                            $i++
                                        @endphp
                                    @endforeach
                                </tr>
                                </tbody>
                            </table>
                        </div>
                   
                    @endif
                </div>
            @else
                <div id="content" class="col-sm-12">
                    No Product Added
                </div>
            @endif
        </div>
    </div>
@endsection