@php
    $post_id = isset($post_id) ? $post_id : null;
    $post_type = isset($post_type) ? $post_type : null;
    $check= \App\Models\SeoSettings::where('post_id', $post_id)->where('post_type', $post_type)->first();
    if($check){
        $meta = \App\Models\SeoSettings::getMeta($check->id);
        $meta = (object)$meta;
    }
@endphp
<div class="row">
    <div class="col-md-12">
        <form action="{{route('admin.common.seo.settings.store')}}" method="post">
            @csrf
            @if($check)
                <input type="hidden" name="seo_id" value="{{$check->id}}">
            @endif
            <input type="hidden" name="post_id" value="{{$post_id}}">
            <input type="hidden" name="post_type" value="{{$post_type}}">
            <div class="form-group">
                <label for="metatitle" class="metatitle">Meta Title</label>
                <input class="form-control" placeholder="Enter meta title..." name="meta_title" type="text" value="{{$meta->meta_title ?? null}}">
            </div>
            <div class="form-group">
                <label for="metadescription" class="metadescription">Meta Description</label>
                <textarea  class="form-control" placeholder="Enter meta description..." name="meta_description" cols="50" rows="10">{{$meta->meta_description ?? null}}</textarea>
            </div>
            <div class="form-group">
                <label for="metakeywords" class="metakeywords">Meta Keywords</label>
                <textarea  class="form-control" placeholder="Enter meta keywords..." name="meta_keywords" cols="50" rows="10">{{$meta->meta_keywords ?? null}}</textarea>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-success">Submit</button>
            </div>
        </form>
    </div>
</div>
