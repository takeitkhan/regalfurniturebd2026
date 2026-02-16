<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-7">

        <div class="bg-navy color-palette">
            Product Variation for {{ $product->title }}
        </div>

        <div class="box box-warning">
            {{-- <div class="box-header">
                <h3 class="box-title">Product Variation</h3>
            </div> --}}
            <a href="javascript:void(0)" id="modal_button" class="btn btn-sm btn-success" data-toggle="modal"
                data-target="#productVariation" onclick="productVarianteUpdate('store')">
                <i class="fa fa-plus"></i>
            </a>
            <input type="hidden" value="{{ $product->id }}" id="getProductId" />

            <div id="product_added">
                @if ($product->id)
                    <table class="table table-bordered" style="width: 100%">
                        <tr>
                            <th>#</th>
                            <th>Variation Group</th>
                            <th>Title</th>
                            <th>Image</th>
                            <th>Activ</th>
                            <th>Action</th>
                        </tr>
                        @foreach ($productVariations as $variant)
                            <tr>
                                <td>{{ $variant->id }}</td>
                                <td>{{ $variant->variationGroup->title }} <input type="hidden"
                                        id="variantGroupId{{ $variant->id }}"
                                        value="{{ $variant->variation_group_id }}" /></td>
                                <td id="productVariantTitle{{ $variant->id }}">{{ $variant->title }}</td>
                                <td> <input type="hidden" id="productVariantImage{{ $variant->id }}"
                                        value="{{ $variant->image_id }}" /> <img
                                        src="{{ asset($variant->image->icon_size_directory ?? '') }}" height="80px"
                                        width="100px" alt="{{ __('') }}"> </td>
                                <td id="productVariantActive{{ $variant->id }}">
                                    {{ $variant->active }}
                                    <input type="hidden" id="dbvariation_product_id{{ $variant->id }}"
                                        value="{{ $variant->variation_product_id }}" />
                                </td>
                                <td>
                                    <a href="javascript:void(0)" class="btn btn-xs btn-success" data-toggle="modal"
                                        data-target="#productVariation"
                                        onclick="productVarianteUpdate('update', {{ $variant->id }})">
                                        <i class="fa fa-pencil-square-o"></i>
                                    </a>

                                    <a class="btn btn-xs btn-danger" onclick="return confirm('Are you Sure ?')"
                                        href="{{ route('delete.product.variation', $variant->id) }}">
                                        <i class="fa fa-trash-o fa-lg"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach

                    </table>
                @endif
            </div>
        </div>

    </div>


</div>


<!-- start Product Variation Model Section -->

<div class="modal fade" id="productVariation" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog">
        <div class="modal-content" style="width: 850px;">
            <form action="{{ route('add.product.variation') }}" id="product_variation_form" method="POST">
                <div class="modal-header">

                    <div class="row">

                        <div class="col-6 m-5">
                            <input type="submit" style="margin-right: 1.5rem;" value="Save" name="submit"
                                class="btn btn-sm btn-success pull-right" />
                        </div>
                        <div class="col-6">
                            <button type="button" style="color: #bd0202; margin-left: 1.5rem; outline:none; "
                                class="close pull-left" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&#x2716;</span>
                            </button>
                        </div>

                    </div>
                    <h4 class="modal-title">Product Variation</h4>
                </div>
                <div class="modal-body">

                    <div class="row">

                        <div class="col-md-6">
                            @csrf
                            <input type="hidden" name="product_id" id="setProductId" value="">
                            <div class="form-group">
                                {{ Form::label('variation_group_id', 'Variation Group', ['class' => 'category_id']) }}
                                {{ Form::select('variation_group_id', $variation_groups, $productSet->category_id ?? 0, ['required', 'id' => 'variation_group_id', 'class' => 'form-control']) }}
                            </div>
                            <div class="form-group">
                                <label for="variation_product_id" class="control-label">Variation Product Id</Title>
                                    </label>
                                <input type="text" name="variation_product_id" class="form-control"
                                    id="variation_product_id">
                            </div>

                            <div class="form-group">
                                <label for="product_variation_title" class="control-label">Title</Title></label>
                                <input type="text" name="product_variation_title" class="form-control"
                                    id="product_variation_title">
                            </div>

                            <div class="form-group">
                                <label for="product_variation_image" class="control-label">Image:</label>
                                <input type="text" name="product_variation_image" class="form-control"
                                    id="product_variation_image">
                            </div>
                            <div class="form-group">
                                {{ Form::label('active', 'Active', ['class' => 'active']) }}
                                {{ Form::select('active', [0 => 'No', 1 => 'Yes'], '', ['required', 'class' => 'form-control', 'id' => 'product_variation_active']) }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            @if (!empty($medias))
                                <div class="row" id="reload_me">
                                    @foreach ($medias->take(6) as $media)
                                        <div class="col-md-4">
                                            <div href="#" class="thumbnail">
                                                <img src="{{ url($media->icon_size_directory) }}"
                                                    class="img-responsive" id="getImgeUrl"
                                                    style="height:80px; width:100px;" />
                                                <div class="caption text-center">
                                                    <p>
                                                        <a href="javascript:void(0);" data-id="{{ $media->id }}"
                                                            data-option="{{ $media->filename }}"
                                                            class="btn btn-xs btn-primary"
                                                            onclick="get_image_id(this);" role="button">
                                                            Use
                                                        </a>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                </div>

                            @endif

                        </div>

                    </div>

                </div>
            </form>
            <div style="margin: .8rem;">
                @component('component.dropzone')
                @endcomponent
            </div>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<!--  end product variation model ..... -->

<script src="{{ asset('public/plugins/dropzone.js') }}"></script>
<script src="{{ asset('public/js/dropzone-config.js') }}"></script>
<script src="{{ asset('public/js/jquery.czMore-latest.js') }}"></script>


<script type="text/javascript">
    // script for product Variation model start....
    function productVarianteUpdate(action, id) {
        jQuery('#setProductId').val(jQuery("#getProductId").val())
        let variantGroupId = ''
        let productVariantTitle = ''
        let productVariantImage = ''
        let dbvariation_product_id = ''
        let productVariantActive = 1
        let actionUrl = "{{ route('add.product.variation') }}"

        if (action == 'update') {
            actionUrl = "{{ route('update.product.variation', 'noid') }}"
            actionUrl = actionUrl.replace('noid', id)
            variantGroupId = jQuery("#variantGroupId" + id).val()
            productVariantTitle = jQuery("#productVariantTitle" + id).text()
            productVariantImage = jQuery("#productVariantImage" + id).val()
            dbvariation_product_id = jQuery("#dbvariation_product_id" + id).val()
            productVariantActive = 1
            console.log(actionUrl, productVariantTitle, productVariantActive)

        }

        jQuery("#product_variation_form").attr('action', actionUrl)
        jQuery("#variation_group_id").val(variantGroupId)
        jQuery("#product_variation_title").val(productVariantTitle)
        jQuery("#product_variation_image").val(productVariantImage)
        jQuery("#variation_product_id").val(dbvariation_product_id)
        jQuery("#product_variation_active").val(productVariantActive)
    }


    function get_image_id(identifier) {


        var data = jQuery(identifier).data('id');


        jQuery('#product_variation_image').val(data);
    }
    // product variation model script end here.... 
</script>
