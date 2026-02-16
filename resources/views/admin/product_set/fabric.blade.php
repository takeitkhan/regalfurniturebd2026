<div class="box box-warning">
    <div class="box-header with-border">
        <div class="raw">
            <div class="col-12">
            
                    <div class="raw">
                        <div class="col-md-12">
                            <a href="javascript:void(0)" id="modal_button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#fabricModel"  onclick="fabricSave('store')"><i class="fa fa-plus"></i></a>

                                <table class="table" cellspacing="0">
                                    <tbody>
                                        <tr>
                                            <th>Image</th>
                                            <th >Title</th>
                                            <th>Images</th>
                                            <th>Active</th>
                                            <th>Action</th>
                                            
                                        </tr>
                                        <div>
                                            @foreach ($productSet_fabric as $item)
                                           
                                            
                                            
                                            
                                            <tr>
                                                <td style="display: block;">
                                                
                                                    <img src="{{ asset(  $item->image->icon_size_directory ??'') }}" style="display: block; vertical-align: bottom;" height="80px" width="100px" > 
                                                 
                                                    <input id="image_id_fab{{ $item->id }}" type="hidden" value="{{ $item->image_id }}"/>
                                                 </td>
                                                <td id="fabric_title_fab{{ $item->id }}">{{ $item->title }}</td>
                                                <td id="fabric_image_fab{{ $item->id }}">{{ $item->images }}</td>
                                                @if ($item->active == 1)
                                                <td>Yes</td>
                                                @else
                                                <td>No</td>
                                                @endif
                                                <td id="td-edit-delete">

                                                    <a href="javascript:void(0)"  class="btn btn-xs btn-success" data-toggle="modal" data-target="#fabricModel"
                                                    onclick="fabricSave('update', {{ $item->id }})">
                                                        <i class="fa fa-pencil-square-o"></i>
                                                    </a>

                                                    <a class="btn btn-xs btn-danger" onclick="return confirm('Are you Sure ?')"
                                                    href="{{route('admin.product_set.fabric.delete',$item->id)}}">
                                                    <i class="fa fa-trash-o fa-lg"></i>
                                                    </a>

                                                </td>


                                            </tr>
                                            @endforeach

                                        </div>

                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="fabricModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog">
      <div class="modal-content" style="width: 850px;">
        <form action="" id="fabric-form" method="POST">
            <div class="modal-header">

                <div class="row">

                    <div class="col-6 m-5">
                        <input type="submit" style="margin-right: 1.5rem;" value="Save" name="submit" class="btn btn-sm btn-success pull-right"/>
                    </div>
                    <div class="col-6">
                        <button type="button" style="color: #bd0202; margin-left: 1.5rem;" class="close pull-left" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&#x2716;</span>
                          </button>
                    </div>

                </div>
            <h4 class="modal-title">Fabric</h4>
            </div>
            <div class="modal-body">

                    <div class="row">

                            <div class="col-md-6">
                                        @csrf
                                        <input type="hidden" name="product_set_id" value="{{ $productSet->id }}"  id="product_set_id">
                                        <div class="form-group">
                                            <label for="fabric-title" class="control-label">Title</Title></label>
                                            <input  type="text" name="title" class="form-control" id="fabric-title" required >
                                        </div>
                                        <div class="form-group">
                                            <label for="fabric-image-id" class="control-label">Image:</label>
                                            <input type="text" name="image_id" class="form-control" id="fabric-image-id" required >
                                        </div>
                                        <div class="form-group">
                                            <label for="fabric-image-ids" class="control-label">Images:</label>
                                            <textarea class="form-control" name="images" id="fabric-image-ids"></textarea>
                                        </div>
                                        <div class="form-group">
                                                {{ Form::label('active', 'Active', array('class' => 'active')) }}
                                                {{ Form::select('active', [0 => 'No', 1 => 'Yes'],'', ['required', 'class' => 'form-control','id' =>'fabric-active']) }}
                                        </div>

                            </div>
                     

                            <div class="col-md-6">

                                @if(!empty($medias))
                                    <div class="row" id="reload_me">
                                        @foreach($medias->take(6) as $media)
                                            <div class="col-md-4">
                                                <div href="#" class="thumbnail">
                                                    <img src="{{ url($media->icon_size_directory) }}"
                                                        class="img-responsive"
                                                        id="getImgeUrl"
                                                        
                                                        style="height:80px; width:100px;"/>
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


  <script src="{{ asset('public/plugins/dropzone.js') }}"></script>
  <script src="{{ asset('public/js/dropzone-config.js') }}"></script>
  <script src="{{ asset('public/js/jquery.czMore-latest.js') }}"></script>
  
  

<script type="text/javascript">

    function get_id(identifier) {
        var preData = jQuery('#fabric-image-ids').val()
        var imageUrl = jQuery("#getImgeUrl").attr("src")
        var data = jQuery(identifier).data('id');
        if(preData == ""){
            var newData= data    
        }else{
            var newData= preData+","+data
        }
        
       

         jQuery('#fabric-image-ids').val(newData);
    }

    function fabricSave(action, id){
        
        let fabricTitle = ''
        let fabricImageId = ''
        let fabricImageIds = ''
        let fabricActive = 1
        let actionUrl = "{{ route('admin.product_set.fabric.store') }}"

        if(action == 'update'){
        actionUrl ="{{ route('admin.product_set.fabric.update','noid') }}"
        actionUrl = actionUrl.replace('noid',id)
        fabricTitle = jQuery("#fabric_title_fab"+id).text()
        fabricImageId = jQuery("#image_id_fab"+id).val()
        fabricImageIds = jQuery("#fabric_image_fab"+id).text()
        fabricActive = 1

        }

        jQuery("#fabric-form").attr('action', actionUrl)
        jQuery("#fabric-title").val(fabricTitle)
        jQuery("#fabric-image-id").val(fabricImageId)
        jQuery("#fabric-image-ids").val(fabricImageIds)
        jQuery("#fabric-active").val(fabricActive)
        }
    </script>


