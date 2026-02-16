@php
    $url_req = request();
    $url_two = \Request::segment(2);

@endphp

{{ Form::open(array('url' => '/main_search_form', 'method' => 'get', 'value' => 'PATCH', 'id' => 'main_search_product_form', 'files' => true, 'autocomplete' => 'off')) }}


<div id="search0" class="search input-group form-group">
    <div class="select_category filter_type  icon-select hidden-sm hidden-xs">
        @php
            $categories = \App\Term::where('parent', 1)->get();
        @endphp

        <select class="no-border" name="category" id="main-srch-cat">
            <option value="">All Categories</option>
            @foreach($categories as $category)
                <option value="{{ $category->seo_url }}"{{($category->seo_url == $url_two)? 'selected' : ''}} >{{ $category->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="main-srch-suggest-all">
        <input type="text" id="main-srch-keyword" name="keyword" size="50" class="autosearch-input form-control"
               value="{{(isset($url_req->keyword))? $url_req->keyword : null }}" placeholder="Search here" autocomplete="off">

        <div id="main-srch-suggest" style="display:none">


        </div>
    </div>
    <button type="submit" class="button-search btn btn-primary"><i class="fa fa-search"></i></button>

</div>
{{ Form::close() }}