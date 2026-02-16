{{ Form::open(array('url' => url()->current(), 'method' => 'get', 'value' => 'PATCH', 'id' => 'searcher')) }}
<!-- sidebar-area section start -->
<div class="sidebar-pg-mu">
    <div class="panel panel-default">
        <div class="panel-heading pl-heading">
            <h4 class="panel-title">
                <a class="collapseWill" data-toggle="collapse" href="#categorization">
                    <span class="pull-left"><i class="fa fa-caret-right"></i></span> Category
                </a>
            </h4>
        </div>
        <div class="panel-body">
            {{--{!! category_sidebar_menu_on_category_page($categories, $parent = 100, $seperator = ' ', $cid = NULL) !!}--}}
        </div>
    </div>
</div>

<div class="panel-group" id="accordionNo">
    <input type="hidden"
           value="{{ (!empty(app('request')->path())) ? app('request')->path() : null }}"
           name="category_path"/>
    <input type="hidden"
           value="{{ (!empty($category_id) ? $category_id : null) }}"
           name="category_id"/>
    <input type="hidden"
           value="{{ !empty(app('request')->input('pages')) ? app('request')->input('pages') : 1 }}"
           name="pages"/>

    <div class="panel panel-default side-bar-menu">
        <div class="panel-heading pl-heading">
            <h4 class="panel-title">
                <a class="collapseWill" data-toggle="collapse" href="#collapsePrice">
                    <span class="pull-left"><i class="fa fa-caret-right"></i></span>
                    &nbsp;Price
                </a>
            </h4>
        </div>

        <div id="collapsePrice" class="panel-collapse collapse in " aria-expanded="true">
            <div class="panel-body priceFilterBody">
                <div class="radio-item">
                    <input type="radio" id="range" name="range" value=""
                           <?php echo((app('request')->input('maxprice') == 2000000) ? 'checked' : null) ?>
                           data-minprice="0" data-maxprice="2000000">
                    <label for="ritem16">Any Price</label>
                </div>
                <!-- single-button -->
                <br>
                <div class="radio-item">
                    <input type="radio" id="range" name="range" value=""
                           <?php echo((app('request')->input('maxprice') == 3000) ? 'checked' : null) ?>
                           data-minprice="0" data-maxprice="3000">
                    <label for="ritem15">Under 3000TK</label>
                </div>
                <br>
                <!-- single-button -->
                <div class="radio-item">
                    <input type="radio" id="range" name="range" value=""
                           <?php echo((app('request')->input('maxprice') == 5000) ? 'checked' : null) ?>
                           data-minprice="3000" data-maxprice="5000">
                    <label for="ritem14">3000 TK - 5000TK</label>
                </div>
                <br>
                <!-- single-button -->
                <div class="radio-item">
                    <input type="radio" id="range" name="range" value=""
                           <?php echo((app('request')->input('maxprice') == 10000) ? 'checked' : null) ?>
                           data-minprice="5000" data-maxprice="10000">
                    <label for="ritem13">5,000TK - 10,000TK</label>
                </div>
                <br>
                <!-- single-button -->
                <div class="radio-item">
                    <input type="radio" id="range" name="range" value=""
                           <?php echo((app('request')->input('maxprice') == 20000) ? 'checked' : null) ?>
                           data-minprice="10000" data-maxprice="20000">
                    <label for="ritem12">10,000TK - 20,000TK</label>
                </div>
                <!-- single-button -->
                <div class="radio-item">
                    <input type="radio" id="range" name="range" value=""
                           <?php echo((app('request')->input('maxprice') == 30000) ? 'checked' : null) ?>
                           data-minprice="20000" data-maxprice="30000">
                    <label for="ritem11">20,000TK - 30,000TK</label>
                </div>
                <br>
                <!-- single-button -->
                <div class="radio-item">
                    <input type="radio" id="range" name="range" value=""
                           <?php echo((app('request')->input('maxprice') == 30000) ? 'checked' : null) ?>
                           data-minprice="30000" data-maxprice="">
                    <label for="ritem10">Abobe 30,000TK</label>
                </div>
                <!-- single-button -->
            </div>
        </div>
    </div>
    <div class="panel panel-default side-bar-menu">
        <div class="panel-heading pl-heading">
            <h4 class="panel-title">
                <a class="collapseWill" data-toggle="collapse" href="#sortbyprice">
                    <span class="pull-left"><i class="fa fa-caret-right"></i></span> &nbsp;
                    Sort By
                </a>
            </h4>
        </div>
        <div id="sortbyprice" class="panel-collapse collapse in ">
            <div class="panel-body priceFilterBody pl-object">

                <div class="radio-item">
                    <input type="radio"
                           id="price_sort"
                           name="price_sort"
                           value="all"
                           data-field="id"
                           data-type="desc"
                    <?php echo((app('request')->input('field') == 'id' && app('request')->input('type') == 'desc') ? 'checked' : null) ?>>
                    <label for="ritem4">Any</label>
                </div>
                <br>
                <!-- single-button -->
                <div class="radio-item">
                    <input type="radio"
                           id="price_sort"
                           name="price_sort"
                           value="local_selling_price"
                           data-field="local_selling_price"
                           data-type="asc"
                    <?php echo((app('request')->input('field') == 'local_selling_price' && app('request')->input('type') == 'asc') ? 'checked' : null) ?>>
                    <label for="ritem3">Low to High</label>
                </div>
                <br>
                <!-- single-button -->
                <div class="radio-item">
                    <input type="radio"
                           id="price_sort"
                           name="price_sort"
                           value="local_selling_price"
                           data-field="local_selling_price"
                           data-type="desc"
                    <?php echo((app('request')->input('field') == 'local_selling_price' && app('request')->input('type') == 'desc') ? 'checked' : null) ?>>
                    <label for="ritem2">High to Low</label>
                </div>
                <br>

                <div class="radio-item">
                    <input type="radio"
                           id="price_sort"
                           name="price_sort"
                           value="local_selling_price"
                           data-field="id"
                           data-type="desc"
                    <?php echo((app('request')->input('field') == 'id' && app('request')->input('type') == 'desc') ? 'checked' : null) ?>>
                    <label for="ritem1">Newest</label>
                </div>
                <br>
                <!-- single-button -->
                <div class="radio-item">
                    <input type="radio"
                           id="price_sort"
                           name="price_sort"
                           value="local_selling_price"
                           data-field="id"
                           data-type="asc"
                    <?php echo((app('request')->input('field') == 'id' && app('request')->input('type') == 'asc') ? 'checked' : null) ?>>
                    <label for="ritem1">Oldest</label>
                </div>

            </div>
        </div>
    </div>

</div>
{{ Form::close() }}