<div class="box box-warning">
    <div class="box-header with-border">
        <div class="raw">
            <div class="col-12">
                    <div class="raw">
                        <div class="col-md-8">
                            <a href="javascript:void(0)" id="modal_button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#infoModal" onclick="infoSave('store')"><i class="fa fa-plus"></i></a>

                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <th class="th">Title</th>
                                            <th >Subtitle</th>
                                            <th>Description</th>
                                            <th>Acitve</th>
                                            <th>Acitve</th>
                                        </tr>
                                        <div>
                                            @foreach ($infos as $info)
                                            <tr>
                                                <td class="th" id="info_title{{ $info->id }}" >{{ $info->title }}</td>
                                                <td id="info_subtitle{{ $info->id }}">{{ $info->sub_title }}</td>
                                                <td id="info_description{{ $info->id }}">{{ $info->description }}</td>
                                                @if ($info->active == 1)
                                                <td>Yes</td>
                                                @else
                                                <td>No</td>
                                                @endif
                                                <td id="td-edit-delete">

                                                    <a href="javascript:void(0)" class="btn btn-xs btn-success" data-toggle="modal" data-target="#infoModal"
                                                    onclick="infoSave('update', {{ $info->id }})">
                                                        <i class="fa fa-pencil-square-o"></i>
                                                    </a>

                                                    <a class="btn btn-xs btn-danger"
                                                    href="{{route('admin.product_set.info.delete',$info->id)}}">
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





<div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&#x2716;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Info</h4>
      </div>
      <form action="" method="post", id="info-form">
          @csrf
        <div class="modal-body col-md-12">
            <input type="hidden" name="product_set_id" value="{{ $productSet->id }}"  id="product_set_id">
                    <div class="form-group">
                        <label for="info-title" class="control-label">Title</label>
                        <input type="text" name="title" class="form-control" id="info-title">
                    </div>
                    <div class="form-group">
                        <label for="info-subtitle" class="control-label">Subtitle</label>
                        <input type="text" name="sub_title" class="form-control" id="info-subtitle">
                    </div>
                    <div class="form-group">
                        <label for="info-description" class="control-label">Description:</label>
                        <textarea class="form-control" name="description" id="message-text"></textarea>
                    </div>
                    <div class="form-group">
                            {{ Form::label('active', 'Active', array('class' => 'active')) }}
                            {{ Form::select('active', [0 => 'No', 1 => 'Yes'],'', ['required', 'id' => 'info-acitve', 'class' => 'form-control']) }}
                    </div>
        </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <input type="submit" class="btn btn-primary" value="Save">
      </div>
    </form>
    </div>
  </div>
</div>


<script type="text/javascript">

function infoSave(action, id){
        let infoTitle = ''
        let infoSubtitle = ''
        let infoDescription = ''
        let infocActive = 1
        let actionUrl = "{{ route('admin.product_set.info.store') }}"

    if(action == 'update'){
         actionUrl ="{{ route('admin.product_set.info.update','noid') }}"
         actionUrl = actionUrl.replace('noid',id)
         infoTitle = jQuery("#info_title"+id).text()
         infoSubtitle = jQuery("#info_subtitle"+id).text()
         infoDescription = jQuery("#info_description"+id).text()
         infocActive = 1

    }
    console.log(actionUrl, infoTitle, infoSubtitle, infoDescription)

    jQuery("#info-form").attr('action', actionUrl)
    jQuery("#info-title").val(infoTitle)
    jQuery("#info-subtitle").val(infoSubtitle)
    jQuery("#message-text").val(infoDescription)
    jQuery("#info-acitve").val(infocActive)
}



</script>
