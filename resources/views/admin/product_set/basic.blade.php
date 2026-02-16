@component('component.form')
                @slot('form_id')
                    @if (!empty($productSet->id))
                        slider_form333
                    @else
                        slider_form333
                    @endif
                @endslot

                @slot('title')
                    {{isset($productSet->id) ? 'Edit Data' : 'Add new data'}}
                @endslot
        
                @slot('route')
                    @if (!empty($productSet->id))
                        {{route('admin.common.product_set.update',$productSet->id)}}
                    @else
                        {{route('admin.common.product_set.store')}}
                    @endif
                @endslot
        
                @slot('fields')
                    {{method_field(isset($district) ? 'PUT' : 'POST')}}
                    {{csrf_field()}}

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('title', 'Title', array('class' => 'title')) }}
                            {{ Form::text('title',$productSet->title??'', ['required', 'class' => 'form-control', 'placeholder' => 'Enter Title...']) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('slug', 'Seo URL', array('class' => 'title')) }}
                            {{ Form::text('slug',$productSet->slug??'', ['required', 'readonly' => true, 'class' => 'form-control', 'placeholder' => 'Enter Title...']) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('subtitle', 'Subtitle', array('class' => 'subtitle')) }}
                            {{ Form::text('subtitle',$productSet->subtitle??'', ['required', 'class' => 'form-control', 'placeholder' => 'Enter Subtitle...']) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('category_id', 'Category', array('class' => 'category_id')) }}
                            {{ Form::select('category_id', $terms,$productSet->category_id??0, ['required', 'class' => 'form-control']) }}
                        </div>


                        <div class="form-group">
                            {{ Form::label('description', 'Description', array('class' => 'description')) }}
                            {{ Form::textarea('description', (!empty($productSet->description) ? $productSet->description : NULL), ['required', 'class' => 'form-control', 'id' => 'wysiwyg', 'placeholder' => 'Enter details content...']) }}
                        </div>
                        <?php 
                        if(!empty($productSet->id)){
                            $active = \App\Models\Product::where('product_set_id',$productSet->id)->select('is_active')->first();
                        }
  
                        ?>
                        <div class="form-group">
                            {{ Form::label('active', 'Active', array('class' => 'active')) }}
                
                            {{ Form::select('active', [0 => 'No', 1 => 'Yes'],$active->is_active??0, ['required', 'class' => 'form-control']) }}
                        </div>



                    </div>
                    <div class="col-md-6">

                        <div class="" style="border: 1px solid #ddd; padding: 0px 5px 0px 5px; margin-bottom: 10px; margin-top: 2px">
                            <div class="form-group">
                                <label for="product" class="product">Product</label>
                                <input type="text" id="product-search-key" class="form-control" onkeyup="productSetProductSearch(this)">
                                <input type="hidden" value="{{ $productSet->id??'' }}" id="productSetId">
                            </div>

                            <div class="" id="product-results">

                                <div style="height:400px;border-top:1px solid #ddd;overflow:auto;">
                                    <table class="table" id="product-search-results">
                                        <tbody>

                                        </tbody>
                                    </table>

                                    <table class="table" id="product-results" @if(empty($products))
                                        style="display: none;"
                                    @endif>
                                        <span class="text-muted">Selected Products</span>
                                        <tbody>
                                            @if (!empty($products))
                                                @foreach ($products as $item)
                                                <tr>
                                                    <td width="30px;">

                                                        <label class="checkbox-container">
                                                            <input type="checkbox" checked name="products[]" value="{{ $item['id'] }}"  id="isvalue{{ $item['id'] }}">
                                                            <span class="checkmark"></span>
                                                        </label>
                                                    </td>
                                                    <td width="90px"><img src="{{url($item['first_image'])}}" alt="" width="80px;"></td>
                                                    <td>

                                                        <strong>{{ $item['title'] }}</strong><br/>
                                                        <span>{{ $item['sub_title'] }}</span><br/>
                                                        <span>
                                                            Product Code: <strong>{{ $item['product_code'] }}</strong><br/>
                                                        </span>
                                                        <span>
                                                            Price :  <strong>৳. {{ $item['price_now'] }}</strong>
                                                        </span>

                                                    </td>
                                                    <td width="80em">
                                                    <input type="number" id="qty{{ $item['id'] }}" name="qty[{{ $item['id'] }}]" class="form-control" value="{{ $item['product_set_qty'] }}" onchange="setProductSetQty({{ $item['id'] }})">
                                                    
                                                    </td>
                                                </tr>
                                                @endforeach
                                            @endif

                                        </tbody>
                                </table>


                                </div>

                            </div>
                        </div>


                        <div class="form-group">
                            {{ Form::label('image_id', 'Image', array('class' => 'image_id')) }}
                            {{ Form::text('image_id',$productSet->image_id??0, ['required', 'class' => 'form-control', 'placeholder' => 'Enter image id...']) }}
                            <img src="{{asset($productSet->image->icon_size_directory??'')}}" style="margin: .3rem;" id="setImage" height="100px" width="150px" alt="{{ __('') }}">
                        </div>




                    </div>

                </div>
                @endslot
@endcomponent

<div class="row">
    <div class="col-md-4">
        @component('component.dropzone')
        @endcomponent
    </div>

    <div class="col-md-8">
        <div class="image-section col-md-12">

            @if(!empty($medias))
                <div class="row" id="reload_me">
                    @foreach($medias as $media)
                        <div class="col-xs-3 col-md-2">
                            <div href="#" class="thumbnail">
                                <img src="{{ url($media->icon_size_directory) }}"
                                    class="img-responsive"
                                    id="getIamgeUrl{{ $media->id }}"
                                    style="max-height: 80px; min-height: 80px;"/>
                                <div class="caption text-center">
                                    <p>
                                        <a
                                                href="javascript:void(0);"
                                                data-id="{{ $media->id }}"
                                                data-option="{{ $media->filename }}"
                                                class="btn btn-xs btn-primary"
                                                onclick="get_id(this);"
                                                role="button">
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


@push('scripts')

<script src="{{ asset('public/plugins/dropzone.js') }}"></script>
<script src="{{ asset('public/js/dropzone-config.js') }}"></script>
<script src="{{ asset('public/js/jquery.czMore-latest.js') }}"></script>


<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script type="text/javascript">

        function get_id(identifier) {
            //alert("data-id:" + jQuery(identifier).data('id') + ", data-option:" + jQuery(identifier).data('option'));
            var dataid = jQuery(identifier).data('id');
            
             let imageurl = jQuery("#getIamgeUrl"+dataid).attr("src")
            
             jQuery("#setImage").attr("src", imageurl)
            
            jQuery('#image_id').val(dataid);
        }


        let setTime = setTimeout(null,1500);

        function setProductSetQty(id){
            
            var id = jQuery("#isvalue"+id).val()  
            var qty = jQuery("#qty"+id).val() 
            clearTimeout(setTime)
            setTime = setTimeout(function(){
                postThroughApi(id, qty)
            },1500)
        }

        function postThroughApi(id,qty){
            var productSetId = jQuery("#productSetId").val()
            
                axios.post('/api/product/product-set-qty', {
                    id: id,
                    productSetQty: qty,
                    productSetId: productSetId
                })
                .then(function (response) {
                     
                    Swal.fire({
                    position: 'top-end',
                    title: 'Quantity Saved',
                    showConfirmButton: false,
                    timer: 1500
                    })
                })
                .catch(function (error) {
                    console.log(error);
                });
        }

    </script>  
@endpush



