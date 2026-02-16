<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-7">

        <div class="bg-navy color-palette">
            Product Videos for {{ $product->title }}
        </div>

        <div class="box box-warning">
            <div class="box-header">
                <h3 class="box-title">Selected Product Videos</h3>
            </div>
            
            <div id="product_added">
                <form action="{{route('product.youtubevideo.update',$product->id)}}" method="post">
                     {{ csrf_field() }}
                     <div class="row">
                         <div class="col-sm-8">
                              <input type="text" name="youtube" value="http://youtube.com/watch?v={{$product->youtubeVideo->link??''}}" class="form-control" placeholder="Paste your youtube video link">
                         </div>
                         <div class="col-sm-4">
                              <input type="submit" name="submit" value="Submit" class="btn btn-primary btn-success">
                         </div>
                     </div>
                </form>
                <br/>
             
            </div>
        
        </div>
        
    </div>
</div>   