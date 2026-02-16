<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-5">

        <div class="bg-navy color-palette">
            AR for {{ $product->title }}
        </div>

        <div class="box box-warning">
            <div class="box-header">
                <h3 class="box-title">Selected AR</h3>
                <form method="post" action="{{route('product.ar.update',$product->id)}}">
                    {{csrf_field()}}
                    <div class="row">
                        <div class="col-sm-8">
                            <input type="text" id="degree360" name="ar" class="form-control"
                                   value="{{$product->ar->link??''}}">
                        </div>
                        <div class="col-sm-4">
                            <input type="submit" name="submit" value="Submit" class="btn btn-primary btn-success">
                        </div>
                    </div>
                </form>
                @if($product->ar->link??false)
                    <a href="/{{$product->ar->image->full_size_directory??''}}" target="_blank">Preview</a>
                @endif
            </div>
        </div>

    </div>

    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-5">

        @component('component.dropzone')
        @endcomponent
        @if(!empty($medias))
            <div id="reload_me">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Media Informations</th>
                        <th style="width: 20%;">Image</th>
                        <th style="width: 5%;">Action</th>
                    </tr>
                    </thead>

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
                                    data-id="{{ $media->id }}"
                                    class="btn btn-xs btn-success use_product_image"
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

<style>
    #sortable {
        list-style-type: none;
        margin: 0;
        padding: 0;
        width: 100%;
    }

    #sortable li {
        margin: 0 3px 3px 3px;
        padding: 0.4em;
    }

    #sortable li span {
        position: absolute;
        margin-left: -1.3em;
    }
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"
        integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css"
      integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g=="
      crossorigin="anonymous" referrerpolicy="no-referrer"/>
<script>
    jQuery(document).ready(function ($) {
        $.noConflict();

        $(document).on('click', '.use_product_image', function () {
            var imageId = this.dataset.id,
                degree360 = $("#degree360");
            degree360.val(`${imageId}`)
        })

        // $(document).on('keyup', '.degreePostionChange', function () {
        //     var dataId = this.dataset.id;
        //     $.get(`../update_product_360_degree_position/${dataId}/${this.value}`);
        //     ('#product_added').load(location.href + " " + '#product_added');
        // });

        // " xroute('calculator_setting_list_sorting') }}?id= request()->id",

        $('#sortable').sortable({
            axis: 'y',
            cursor: 'move',
            update: function (event, ui) {
                var data = $(this).sortable('serialize');
                $.ajax({
                    data: data,
                    type: 'GET',
                    headers: {
                        'X-CSRF-Token': "{{ csrf_token() }}"
                    },
                    url: "{{ route('product.imagesorting') }}",
                    success: function (output) {

                        if (output.status == 1) {
                            toastr.info(output.message);
                            //window.location.reload();
                        }

                        // jQuery(window).load(function () {
                        //     alert();
                        //     //$("div#reload_me").load(window.location.assign(href) + " div#reload_me");
                        //     location.reload();
                        // });
                    }
                });
            }
        });
    })
</script>
