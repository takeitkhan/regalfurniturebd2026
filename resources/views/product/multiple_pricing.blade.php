<?php $tksign = '&#2547; '; ?>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="bg-navy color-palette">
            Product multiple pricing for {{ $product->title }}
        </div>
        <div class="row">
            <div class="col-md-3 col-sm-12">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Add Color(s)</h3>
                    </div>
                    <div class="box-body">
                        <div class="btn-group" style="width: 100%; margin-bottom: 10px;">

                            <?php $colors = \App\Productpricecombination::where('type', 'color')->where('main_pid', $product->id)->get(); ?>

                            <ul class="fc-color-picker" id="color-chooser">
                                @foreach($colors as $color)
                                    <li style="position: relative;">
                                        <a href="{{ url('delete_productpricecombination/' . $color->id) }}"
                                           class="cross_btn"
                                           onclick="return confirm('Are you Sure ?')">
                                            x
                                        </a>
                                        <a style="color: {{ '#'.$color->value }}">
                                            <i class="fa fa-square"></i>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="input-group">
                            {{ Form::hidden('userid', (!empty(\Auth::user()->id) ? \Auth::user()->id : NULL), ['required','id'=> 'userid']) }}

                            {{ Form::hidden('mainpid', !empty($product->id) ? $product->id : NULL, ['required','id'=> 'mainpid']) }}

                            {{ Form::hidden('type', 'color', ['required','id'=> 'type']) }}

                            {{ Form::text('color_code', NULL, ['class' => 'form-control my-colorpicker1 colorpicker-element', 'id'=> 'color_code', 'placeholder' => 'Enter color code...']) }}

                            <div class="input-group-btn">
                                <button id="add_color" type="button" class="btn btn-primary btn-flat">Add</button>
                            </div>

                        </div>
                    </div>
                </div>

                <H3 class="text-center">OR</H3><hr>

                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title"> Add Photo(s)</h3>
                    </div>
                    <div class="box-body">
                        <div class="btn-group" style="width: 100%; margin-bottom: 10px;">

                            <?php $photos = \App\Productpricecombination::where('type', 'photo')->where('main_pid', $product->id)->get(); ?>

                            <ul class="fc-color-picker" id="photobox">
                                @foreach($photos as $photo)
                                    <li style="position: relative;">
                                        <a href="{{ url('delete_productpricecombination/' . $photo->id) }}"
                                            class="cross_btn"
                                            onclick="return confirm('Are you Sure ?')">
                                            x
                                        </a>
                                        <div class="photo text-center" data-toggle="tooltip" title="{{ $photo->value }}">
                                            <img style="border-radius:5px;" src="{{ asset('public/pmp_img/' . $photo->value) }}" alt="" width="40" height="40">
                                            <div style="font-size: 12px;margin: -12px;padding: 0;font-weight: bold;">{{ $photo->id }}</div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        
                        <form action="#" id="AddPhoto" class="input-group" method="post" enctype="multipart/form-data">
                            {{ csrf_field() }}

                            <input type="hidden" name="userid" value="{{ !empty(\Auth::user()->id) ? \Auth::user()->id : NULL }}">

                            <input type="hidden" name="mainpid" value="{{ !empty($product->id) ? $product->id : NULL }}">

                            <input type="hidden" name="type" value="photo">

                            <input type="file" name="photo_name" id="photo_name">

                            <div class="input-group-btn">
                                <button type="submit" id="add_photo" class="btn btn-primary btn-flat">Add</button>
                            </div>
                        </form>
                    </div>
                </div>

                <br>

                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">Add Size(s)</h3>
                    </div>
                    <div class="box-body">
                        <div class="btn-group" style="width: 100%; margin-bottom: 10px;">

                            <?php $sizes = \App\Productpricecombination::where('type', 'size')->where('main_pid', $product->id)->get(); ?>


                            <div class="box-body no-padding">
                                <div class="nav nav-pills nav-stacked" id="size_stack">
                                    <?php
                                    $colors = array(
                                        'text-red', 'text-yellow', 'text-light-blue'
                                    );
                                    $rand_keys = array_rand($colors);
                                    ?>
                                    @foreach($sizes as $size)
                                        <button class="btn bg-purple btn-flat margin" style="position: relative;">
                                            {{ $size->value }}
                                            <a href="{{ url('delete_productpricecombination/' . $size->id) }}"
                                               class="size_cross_btn"
                                               onclick="return confirm('Are you Sure ?')">
                                                x
                                            </a>
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>


                        <div class="input-group">
                            {{ Form::hidden('userid', (!empty(\Auth::user()->id) ? \Auth::user()->id : NULL), ['required','id'=> 'userid']) }}
                            {{ Form::hidden('mainpid', !empty($product->id) ? $product->id : NULL, ['required','id'=> 'mainpid']) }}
                            {{ Form::hidden('type', 'size', ['required','id'=> 'size_type']) }}
                            {{ Form::text('size_title', NULL, ['required', 'class' => 'form-control', 'id'=> 'size_title', 'placeholder' => 'Enter size...']) }}
                            <div class="input-group-btn">
                                <button id="add_size" type="button" class="btn btn-primary btn-flat">Add</button>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
            <div class="col-md-8 col-sm-11">
                <?php $title = 'Add size, color and price combination'; ?>

                <table class="table table-bordered" id="input_field_append">
                    <tbody style="position: relative;">
                    <button class="btn btn-xs btn-more" style="position: absolute; bottom: -5px; right: 15px;"
                            onclick="get_colors_sizes('<?php echo $product->id; ?>');">
                        Add More
                    </button>
                    <tr id="append_to_this">
                        <th style="width: 5%">#</th>
                        <th style="width: 10%">Color</th>
                        <th style="width: 15%">Size</th>
                        <th>Item code</th>
                        <th style="width: 10%">Qty</th>
                        <th>DP Price</th>
                        <th>R. Price</th>
                        <th>S. Price</th>
                        <th>available</th>
                    </tr>

                    <?php $pcom_datas = App\Pcombinationdata::where('main_pid', $product->id)->orderBy('id', 'desc')->get(); ?>

                    @foreach($pcom_datas as $pcd)
                        <?php
                            //dump($pcd);
                        ?>
                        <tr>
                            <td>{{ $pcd->id }}</td>
                            <td>
                                @if ($pcd->type == 0)
                                <span style="background-color: {{ '#' . $pcd->color_codes }} !important; padding: 5px; display: block; width: 20px; height: 20px;"></span>
                                @else
                                    <img src="{{ asset('public/pmp_img/' . $pcd->color_codes) }}" width="20" height="20">
                                @endif
                            </td>

                            <td>
                                {{ $pcd->size }}
                            </td>

                            <td>
                                    {{  $pcd->item_code }}

                            </td>

                            <td>
                                {{ $pcd->stock }}
                            </td>
                            <td>
                                {{ ($pcd->dp_price) ? $tksign .$pcd->dp_price : '' }}
                            </td>
                            <td>
                                {{ ($pcd->regular_price) ? $tksign .$pcd->regular_price : ''  }}
                            </td>
                            <td>
                                {{ ($pcd->selling_price) ? $tksign .$pcd->selling_price : ''  }}
                            </td>
                            <td style="position: relative;">
                                @php
                                if($pcd->stock > 0){
                                    if($pcd->is_stock = 1){
                                    $is_stock = 'Available';
                                    }else{
                                     $is_stock = 'Out of stock';
                                    }
                                }else{
                                    $is_stock = 'Out of stock';
                                }
                                @endphp
                                {{ $is_stock }}


                                <div class="small_btn"
                                     style="position: absolute; top: 6px; bottom: 0; right: -47px;">
                                    <a href="{{ url('delete_pcomdata/' . $pcd->id) }}"
                                       class="btn btn-xs btn-danger"
                                       onclick="return confirm('Are you Sure ?')">
                                        <i class="fa fa-times-circle" aria-hidden="true"></i> Del
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>