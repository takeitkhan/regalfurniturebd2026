<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-5">

        <div class="bg-navy color-palette">
            360 Degree image frame for {{ $product->title }}
        </div>

        <div class="box box-warning">
            <div class="box-header">
                <h3 class="box-title">Selected 360Degree Frame</h3>
                <form method="post" action="{{route('product.360degree.update',$product->id)}}">
                    {{csrf_field()}}
                    <div class="row">
                        <div class="col-sm-8">
                            <input type="text" id="degree360" name="degree360" class="form-control"
                                   value="{{implode(",",$product->threeSixtyDegreeImage->pluck('link')->toArray())}}">
                        </div>
                        <div class="col-sm-4">
                            <input type="submit" name="submit" value="Submit" class="btn btn-primary btn-success">
                        </div>
                    </div>
                </form>
            </div>

            <div id="reload_me">
                @if($product->id)

                    <ul class="row" id="sortable">
                        @foreach($product->threeSixtyDegreeImage as $degree)
                            <li style="cursor: move; position: relative;" id="item-{{ $degree->id }}"
                                class="ui-state-default {{ $degree->id == request()->get('section_id') ? $success : null }}">
                                <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
                                <div style="position: absolute; right: 5px; top: 5px;">
                                    <a style=" color: #ffffff;" class="btn btn-sm btn-danger"
                                       onclick="return confirm(\'Are you Sure ?\')"
                                       href="{{route('product.videos360.delete',$degree->id)}}">
                                        <i class="fa fa-times"></i> Delete
                                    </a>
                                </div>
                                <img
                                    src="https://admin.regalfurniturebd.com/{{ $degree->image->icon_size_directory }}"
                                    style="max-height: 60px;"/>
                                {{  $degree->image->filename }}
                            </li>
                        @endforeach
                    </ul>
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
            degree360.val(`${degree360.val()},${imageId}`)
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


{{--                    <table class="table table-bordered" style="width: 100%">--}}
{{--                        <thead>--}}
{{--                        <tr>--}}
{{--                            <th style="width:2%;">#Serial</th>--}}
{{--                            <th style="width:10%;">Frame</th>--}}
{{--                            <th style="width: 25%;">Frame Information</th>--}}
{{--                            <th style="width: 10%;">Action</th>--}}
{{--                        </tr>--}}
{{--                        </thead>--}}
{{--                            <tr style="cursor: move;" id="item-{{ $degree->id }}"--}}
{{--                                class="{{ $degree->id == request()->get('section_id') ? $success : null }}">--}}
{{--                                <td><input type="text" value="{{$degree->position}}" data-id="{{ $degree->id }}"--}}
{{--                                           class="degreePostionChange" size="3"></td>--}}
{{--                                <td><img src="{{url($degree->image->icon_size_directory)}}" class="img-responsive"--}}
{{--                                         style="max-height: 60px;"/></td>--}}
{{--                                <td>--}}
{{--                                    <small>--}}
{{--                                        <strong>Position: </strong>{{$degree->position}}<br/>--}}
{{--                                        <strong>ImageId: </strong>{{$degree->link}}<br/>--}}
{{--                                        <strong>FN: </strong>{{  $degree->image->filename }}<br/>--}}
{{--                                        <strong>Frame: </strong>{{$degree->image->full_size_directory}}<br/>--}}
{{--                                    </small>--}}
{{--                                </td>--}}
{{--                                <td>--}}
{{--                                    <a onclick="return confirm(\'Are you Sure ?\')"--}}
{{--                                       href="{{route('product.videos360.delete',$degree->id)}}"><i--}}
{{--                                            class="fa fa-times"></i></a>--}}
{{--                                </td>--}}
{{--                            </tr>--}}
{{--                    </table>--}}
