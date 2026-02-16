<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-7">

        <div class="bg-navy color-palette">
            Product Images for {{ $product->title }}
        </div>

        <div class="box box-warning">
            <div class="box-header">
                <h3 class="box-title">Selected Product Images</h3>
            </div>
            
            <div id="product_added">
                <?php
                if (!empty($product->id)) :

                    $added = App\Models\ProductImages::where('main_pid', $product->id)->groupBy('media_id')->get();
                    //dump($added);

                    echo '<table class="table table-bordered" style="width: 100%">';
                    echo '<tr>';
                    echo '<th style="width: 25%;">Image</th>';
                    echo '<th style="width: 60%;">Image Information</th>';
                    echo '<th style="width: 10%;">Image Type</th>';
                    echo '<th style="width: 5%;" class="text-center">Action</th>';
                    echo '</tr>';

                    foreach ($added as $pro) :
                        echo '<tr>';

                        echo '<td>';
                        echo '<img src="' . url($pro->icon_size_directory) . '" class="img-responsive" style="max-height: 60px;"/>';
                        echo '</td>';

                        echo '<td>';
                        echo '<small>';
                        echo '<strong>FN: </strong>' . $pro->filename . '<br/>';
                        echo '<strong>Full: </strong>' . $pro->full_size_directory . '<br/>';
                        echo '<strong>Icon: </strong>' . $pro->icon_size_directory . '<br/>';
                        echo '</small>';
                        echo '</td>';

                        echo '<td class="text-center">';

                        echo "
                        <select onchange='changeImageId($pro->id)' id='main_image_setup_$pro->id'>
                            <option value='0' ".($pro->is_main_image == 0?'selected':'').">Default</option>
                            <option value='1' ".($pro->is_main_image == 1?'selected':'').">Primary</option>
                            <option value='2' ".($pro->is_main_image == 2?'selected':'').">Secondary</option>
                        </select>
                        ";
                        // if ($pro->is_main_image == 1) {
                        //     echo '<a id="unset_main_image" data-id="' . $pro->id . '" data-value="0" data-type="unset" href="javascript:void(0)" class="btn btn-danger btn-xs">Unset</a>';
                        // } else {
                        //     echo '<a id="set_main_image"  data-id="' . $pro->id . '" data-value="1" data-type="set" href="javascript:void(0)" class="btn btn-success btn-xs">Set</a>';
                        // }
                        echo '</td>';

                        echo '<td class="text-center">';
                        echo '<a onclick="return confirm(\'Are you Sure ?\')" href="' . url('delete_productimage/' . $pro->id) . '"><i class="fa fa-times"></i></a>';
                        echo '</td>';

                        echo '</tr>';
                    endforeach;
                    echo '</table>';
                endif;
                ?>
            </div>
        </div>

    </div>

    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-5">

        @component('component.dropzone')
        @endcomponent
        @if(!empty($medias))
            <div class="" id="reload_me">
                <table class="table table-bordered">
                    <tr>
                        <th>#</th>
                        <th>Media Informations</th>
                        <th style="width: 20%;">Image</th>
                        <th style="width: 5%;">Action</th>
                    </tr>
                    @foreach($medias as $media)
                        <tr>
                            <td>{{ $media->id }}</td>
                            <td>
                                <small>
                                    <strong>FN: </strong>{{ $media->filename }}<br/>
                                    <strong>Full: </strong>{{ $media->full_size_directory }}<br/>
                                    <strong>Icon: </strong>{{ $media->icon_size_directory }}
                                </small>
                            </td>
                            <td>
                                <img src="{{ url($media->icon_size_directory) }}"
                                     class="img-responsive"
                                     style="max-height: 60px;"/>
                            </td>
                            <td>
                                <a
                                        href="javascript:void(0);"
                                        data-userid="{!! (!empty(\Auth::user()->id) ? \Auth::user()->id : NULL) !!}"
                                        data-mainpid="{{ !empty($product->id) ? $product->id : null }}"
                                        data-id="{{ $media->id }}"
                                        data-filename="{{ $media->filename }}"
                                        data-fullsize="{{ $media->full_size_directory }}"
                                        data-iconsize="{{ $media->icon_size_directory }}"
                                        class="btn btn-xs btn-success"
                                        id="use_product_image"
                                        role="button">
                                    Use
                                </a>

                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        @endif
    </div>
</div>


<script>

    function changeImageId(id)
    {

        const val = document.getElementById('main_image_setup_'+ id).value,
              product_id = '{{$product->id}}';


              jQuery.ajax({
                        url: baseurl + '/update_image_setup',
                        method: 'get',
                        data: {
                            id: id,
                            val: val,
                            product_id: product_id
                        },
                        success: function (status) {
                            window.location.reload(true);
                        }
                    });


        console.log(id,parseInt(val), parseInt(product_id))

    }
</script>