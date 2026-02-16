<?php

use Illuminate\Support\Facades\Auth;
?>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">

        <div class="bg-navy color-palette">
            Categories and related products connection for {{ $product->title }}
        </div>

        @if (!empty($product->id))
            <?php

$route = 'product_link/' . $product->id . '/update'; ?>
        @else
            <?php $route = 'product_link_save'; ?>
        @endif
        
        {{ Form::open(array('url' => $route, 'method' => 'post', 'value' => 'PATCH', 'id' => 'product_linking', 'files' => true, 'autocomplete' => 'off')) }}

        <div class="row">
            <div class="col-md-7 col-sm-7 col-xs-12">
                <div class="box box-warning">
                    <div class="box-header">
                        <h3 class="box-title">
                            Selected Product Categories
                        </h3>
                    </div>
                    <div class="box-body">

                        <div id="product_added">
                            <?php
                            if (!empty($product->id)) :
                                $added = App\Models\ProductCategories::where('main_pid', $product->id)->groupBy('term_id')->get();

                                echo '<table class="table table-bordered" style="width: 100%">';
                                echo '<tr>';
                                echo '<th style="width: 35%;">Category Name</th>';
                                echo '<th style="width: 50%;">Attribute Group</th>';
                                echo '<th style="width: 15%;" class="text-center">Action</th>';
                                echo '</tr>';

                                foreach ($added as $pro) :
                                    echo '<tr>';
                                    echo '<td>';
                                    echo $pro->term_name;
                                    echo '</td>';

                                    echo '<td>';
                                    $attgroup = App\Models\Attgroup::where('id', $pro->term_attgroup)->get()->first();

                                    if ($pro->is_attgroup_active == 1) {
                                        echo '<a id="unset_attgroup" data-mainpid="' . $product->id . '" data-id="' . $pro->id . '" data-value="0" data-type="unset" href="javascript:void(0)" class="btn btn-danger btn-xs pull-right">Unset</a>';
                                    } else {
                                        echo '<a id="set_attgroup" data-id="' . $pro->id . '" data-value="1" data-type="set" href="javascript:void(0)" class="btn btn-success btn-xs pull-right">Set</a>';
                                    }

                                    echo !empty($attgroup->group_name) ? $attgroup->group_name : null;
                                    echo '</td>';

                                    echo '<td class="text-center">';
                                    echo '<a onclick="return confirm(\'Are you Sure ?\')" href="' . url('delete_productcategory/' . $pro->id) . '"><i class="fa fa-times"></i></a>';
                                    echo '</td>';

                                    echo '</tr>';
                                endforeach;
                                echo '</table>';
                            endif;
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-1 col-sm-1 col-xs-12">
            </div>
            <div class="col-md-4 col-sm-4 col-xs-12">
                <div class="box box-warning">
                    <div class="box-header">
                        <h3 class="box-title">
                            {{ Form::label('product_categories_getter', 'Choose Product Categories', array('class' => 'product_categories_getter')) }}
                        </h3>
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" id="main_pid" class="form-control" value="{{ $product->id }}"/>
                                <input type="text" placeholder="Search on categories" id="cat_search"
                                       class="form-control"/>
                            </div>
                        </div>

                        <?php $terms = App\Models\Term::where('parent', 1)->orderBy('name', 'asc')->get(); ?>
                        <select class="form-control" multiple style="min-height: 250px;" id="replace_with_search">
                            @foreach ($terms as $term)
                                <option id="dblclick_cat"
                                        value="{{ $term->id }}"
                                        data-mainpid="{{ !empty($product->id) ? $product->id : null }}"
                                        data-userid="{!! (!empty(\Auth::user()->id) ? \Auth::user()->id : NULL) !!}"
                                        data-title="{{ $term->name }}"
                                        data-attgroup="{{ $term->connected_with }}">
                                    {{ $term->name }}
                                </option>

                                <?php
                                $sub_terms = \App\Models\Term::where('parent', $term->id)->orderBy('name', 'asc')->get();
                                foreach ($sub_terms as $sub_term) : ?>
                                <option id="dblclick_cat" value="<?php echo $sub_term->id; ?>"
                                        data-mainpid="<?php echo !empty($product->id) ? $product->id : null; ?>"
                                        data-userid="<?php echo(!empty(Auth::user()->id) ? Auth::user()->id : NULL); ?>"
                                        data-title="<?php echo $sub_term->name; ?>"
                                        data-attgroup="<?php echo $sub_term->connected_with; ?>">
                                    &nbsp;&nbsp;&nbsp;<?php echo $sub_term->name; ?>
                                </option>
                                <?php
                                $sub_termss = \App\Models\Term::where('parent', $sub_term->id)->orderBy('name', 'asc')->get();
                                foreach ($sub_termss as $sub_terms) : ?>
                                <option id="dblclick_cat" value="<?php echo $sub_terms->id; ?>"
                                        data-mainpid="<?php echo !empty($product->id) ? $product->id : null; ?>"
                                        data-userid="<?php echo(!empty(Auth::user()->id) ? Auth::user()->id : NULL); ?>"
                                        data-title="<?php echo $sub_terms->name; ?>"
                                        data-attgroup="<?php echo $sub_terms->connected_with; ?>">
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $sub_terms->name; ?>
                                </option>
                                <?php endforeach; ?>
                                <?php endforeach; ?>

                            @endforeach
                        </select>
                        <div class="box-footer">Use double click to add a category as add a product category</div>
                    </div>
                </div>

            </div>
        </div>

        <div class="row">
            <div class="col-md-7 col-sm-7 col-xs-12">
                <div class="box box-warning">
                    <div class="box-header">
                        <h3 class="box-title">
                            Selected Bought Together Products
                        </h3>
                    </div>
                    <div class="box-body">
                        <div id="related_added">
                            <?php
                            if (!empty($product->id)) :

                                $added = App\Models\RelatedProducts::where('main_pid', $product->id)->groupBy('product_id')->get();

                                echo '<ul style="margin: 0; padding-left: 15px;">';
                                foreach ($added as $pro) :
                                    echo '<li style="border-bottom: 1px solid #555;">';
                                    echo '<a onclick="return confirm(\'Are you Sure ?\')" href="' . url('delete_relatedproduct/' . $pro->id) . '"><i class="fa fa-times pull-right"></i></a>';
                                    echo '<a href="">' . $pro->title . '</a>';
                                    echo '</li>';
                                endforeach;
                                echo '</ul>';
                            endif;
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-1 col-sm-1 col-xs-12">
            </div>

            <div class="col-md-4 col-sm-4 col-xs-12">
                <div class="box box-warning">
                    <div class="box-header">
                        <h3 class="box-title">
                            {{ Form::label('related_products_getter', 'Choose Bought Together Products', array('class' => 'related_products')) }}
                        </h3>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <input type="hidden" id="main_pid_d" class="form-control" value="{{ $product->id }}"/>
                            <input type="text" placeholder="Search on products" id="product_search_now"
                                   class="form-control"/>
                        </div>
                    </div>
                    <?php $products = App\Models\Product::orderBy('title')->get(); ?>
                    <select class="form-control" multiple style="min-height: 250px;" id="replace_with_products_search">
                        @foreach ($products as $p)
                            <option id="dblclick_related"
                                    value="{{ $p->id }}"
                                    data-mainpid="{{ !empty($product->id) ? $product->id : null }}"
                                    data-userid="{!! (!empty(\Auth::user()->id) ? \Auth::user()->id : NULL) !!}"
                                    data-title="{{ $p->title }}"
                                    data-local_price="{{ $p->local_selling_price }}"
                                    data-local_discount="{{ $p->local_discount }}"
                                    data-int_price="{{ $p->intl_selling_price }}"
                                    data-int_discount="{{ $p->intl_discount }}">
                                {{ $p->title }}
                            </option>
                        @endforeach
                    </select>

                    <div class="box-footer">Use double click to add a product as add a related products</div>
                </div>
            </div>


        </div>
        {{ Form::close() }}
    </div>
</div>


