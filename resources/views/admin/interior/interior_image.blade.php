<div class="box box-warning">
    <div class="box-header with-border">
        <div class="raw">
            <div class="col-12">

                    <div class="raw">
                        <div class="col-md-8">
                            <a href="javascript:void(0)" id="modal_button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#interiorImage"  onclick="interiorSave('store')"><i class="fa fa-plus"></i></a>


                            <div class="box-body" style="">
                                <div class="box-body table-responsive no-padding" id="">
                                    <table class="table table-hover">
                                    <tbody>
                                        <tr>
                                            <th>Image</th>
                                            <th>Title</th>
                                            <th>Caption</th>
                                            <th>Information</th>
                                            <th>Action</th>
                                            
                                        </tr>
                                        <div>
                                            @foreach ($interior_images as $item)
                                            <tr>
                                                <td><img src="{{asset($item->image->full_size_directory??'')}}" id="show_image_int" height="80px" width="100px" alt="{{ __('') }}"></td>
                                                <input type="hidden" id="int_image_id{{ $item->id }}" value="{{ $item->image_id }}">
                                                <td id="int_title{{ $item->id }}">{{ $item->title }}</td>
                                                <td id="int_caption{{ $item->id }}">{{ $item->caption }}</td>
                                                <td id ="int_info{{ $item->id }}">{{ $item->info }}</td>
                                                <td id="td-edit-delete">

                                                    <a href="javascript:void(0)"  class="btn btn-xs btn-success" data-toggle="modal" data-target="#interiorImage"
                                                    onclick="interiorSave('update', {{ $item->id }})">
                                                        <i class="fa fa-pencil-square-o"></i>
                                                    </a>

                                                    <a class="btn btn-xs btn-danger" onclick="return confirm('Are you Sure ?')"
                                                    href="{{route('admin.interiorImage.delete',$item->id)}}">
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

    </div>
</div>



<div class="modal fade" id="interiorImage" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog">
      <div class="modal-content" style="width: 850px;">
        <form action="" id="interior-form" method="POST">
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
            <h4 class="modal-title">Interior</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        @csrf
                        <input type="hidden" name="interior_id" value="{{ $interior->id }}"  id="interior_id">
                        
                        <div class="form-group">
                            <label for="intTitle" class="control-label">Title:</label>
                            <input type="text" name="title" class="form-control" id="intTitle" required >
                        </div>
                        <div class="control-group hidden-phone">
                            <label class="control-label" for="caption">Caption:</label>
                            <div class="controls">
                              <textarea  name="caption" rows="5" class="form-control" id="caption" required></textarea>
                            </div>
                          </div>
                          <div class="control-group hidden-phone">
                            <label class="control-label" for="caption">Info:</label>
                            <div class="controls">
                              <textarea  name="info" rows="5" class="form-control" id="info" required></textarea>
                            </div>
                          </div>

                          <div class="form-group">
                            <label for="fabric-title" class="control-label">Image:</Title></label>
                            <input  type="text" name="image_id" class="form-control" id="image-id" required >
                            <img src="" style="margin: .2rem;" id="show_image" height="80px" width="100px" alt="{{ __('') }}">
                        </div>


                    </div>
                           

                    <div class="col-md-6">

                            @if(!empty($medias))
                                <div class="row" id="reload_me">
                                    @foreach($medias->take(9) as $media)
                                        <div class="col-md-4">
                                            <div href="#" class="thumbnail">
                                                <img src="{{ url($media->icon_size_directory) }}"
                                                    class="img-responsive"
                                                    id="getImageUrl{{ $media->id }}"
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
        var preData = jQuery('#image-id').val()
        var data = jQuery(identifier).data('id');
        var imgUrl = jQuery("#getImageUrl"+data).attr('src');

        jQuery('#show_image').attr("src", imgUrl)
        
         jQuery('#image-id').val(data);
        
    }

    function interiorSave(action, id){
        
        let srcimage = ''
        let intImage = ''
        let intTitle = ''
        let intCaption = ''
        let intInfo = ''
        let actionUrl = "{{ route('admin.interiorImage.store') }}"

        if(action == 'update'){
        actionUrl ="{{ route('admin.interiorImage.update','noid') }}"
        actionUrl = actionUrl.replace('noid',id)
        srcimage = jQuery("#show_image_int").attr("src")
        intImage = jQuery("#int_image_id"+id).val()
        intTitle = jQuery("#int_title"+id).text()
        intCaption = jQuery("#int_caption"+id).text()
        intInfo = jQuery("#int_info"+id).text()
        

        }
        console.log(intCaption,intInfo)
        jQuery("#interior-form").attr('action', actionUrl)
        jQuery('#show_image').attr("src", srcimage)
        jQuery("#image-id").val(intImage)
        jQuery("#intTitle").val(intTitle)
        jQuery("#caption").val(intCaption)
        jQuery("#info").val(intInfo)
        }
    </script>


