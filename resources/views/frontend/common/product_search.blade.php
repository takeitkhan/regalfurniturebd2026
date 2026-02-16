@php
    $url_req = request();
    $url_two = \Request::segment(2);

@endphp





 <div class="electro-navbar-inner">
  {{ Form::open(array('url' => '/main_search_form', 'method' => 'get', 'value' => 'PATCH', 'class'=>'navbar-search', 'id' => 'main_search_product_form', 'files' => true, 'autocomplete' => 'off')) }}
      <div class="input-group">
        <div class="input-group-addon search-categories"> 
           @php
            $categories = \App\Models\Term::where('parent', 1)->get();
        @endphp
            <select name="product_cat" id="product_cat" class="postform resizeselect" style="width: 207px;">
              <option value="0" selected="selected">All Categories</option>
              @foreach($categories as $category)
              <option value="{{ $category->seo_url }}"{{($category->seo_url == $url_two)? 'selected' : ''}} >{{ $category->name }}</option>
            @endforeach
             </select>
          </div>
        <div class="input-search-field"> 

          <input type="text" id="main-srch-keyword" name="keyword" class="form-control search-field product-search-field" size="50" value="{{(isset($url_req->keyword))? $url_req->keyword : null }}" class="form-control" placeholder="Search here" autocomplete="off" aria-label="Search here" aria-describedby="basic-addon2">
          <div id="main-srch-suggest" style="display:none"></div>
        </div>
          <div class="input-group-btn"> 
            <input type="hidden" id="search-param" name="post_type" value="product"> 
            <button type="submit" class="btn btn-secondary">
              <i class="fa fa-search"></i>
            </button>
          </div>
        </div>
      {{ Form::close() }}
  </div>



<!--   <div class="electro-navbar-inner">
    <form class="navbar-search" method="get" action="" autocomplete="off"> 
      <label class="sr-only screen-reader-text" for="search">Search for:</label>
      <div class="input-group">
        <div class="input-group-addon search-categories"> 
            <select name="product_cat" id="product_cat" class="postform resizeselect" style="width: 207px;">
              <option value="0" selected="selected">All Categories</option>
              <option class="level-0" value="accessories">Accessories</option>
              <option class="level-0" value="cameras-photography">Cameras &amp; Photography</option>
              <option class="level-0" value="computer-components">Computer Components</option>
              <option class="level-0" value="gadgets">Gadgets</option>
              <option class="level-0" value="home-entertainment">Home Entertainment</option>
              <option class="level-0" value="laptops-computers">Laptops &amp; Computers</option>
              <option class="level-0" value="printers-ink">Printers &amp; Ink</option>
              <option class="level-0" value="smart-phones-tablets">Smart Phones &amp; Tablets</option>
              <option class="level-0" value="tv-audio">TV &amp; Audio</option>
              <option class="level-0" value="video-games-consoles">Video Games &amp; Consoles</option>
             </select>
          </div>
        <div class="input-search-field"> 
          <input type="text" id="search" class="form-control search-field product-search-field" dir="ltr" value="" name="s" placeholder="Search for Products" autocomplete="off">
        </div>
          <div class="input-group-btn"> 
            <input type="hidden" id="search-param" name="post_type" value="product"> 
            <button type="submit" class="btn btn-secondary">
              <i class="fa fa-search"></i>
            </button>
          </div>
        </div>
      </form>
  </div> -->