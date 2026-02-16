<?php

namespace App\Repositories\Product;


use App\Models\Product;
use App\Models\ProductAttributesData;
use App\Models\ProductCategories;
use App\Models\ProductImages;
use App\Models\Productpricecombination;
use App\Models\RelatedProducts;

class EloquentProduct implements ProductInterface
{
    private $model;

    /**
     * EloquentProduct constructor.
     * @param  Product  $model
     */
    public function __construct(Product $model)
    {
        $this->model = $model;
    }

    public function self()
    {
        return $this->model;
    }

    /**
     *
     */
    public function getAll(array $options = array())
    {

        $default = array(
            'column' => null,
            'search_key' => null
        );

        $new = array_merge($default, $options);

        if (!empty($new['search_key']) && !empty($new['column'])) {
            //dd($this->model->whereRaw('' . $new['column'] . ' like "%' . $new['search_key'] . '%"')->toSql());
            return $this->model
                ->where(['user_id'])
                ->whereRaw(''.$new['column'].' like "%'.$new['search_key'].'%"')
                ->orderBy('id', 'desc')
                ->paginate(10);
        } else {
            return $this->model
                ->orderBy('id', 'desc')
                ->paginate(10);
        }

    }

    /**
     * @param $column
     * @param $value
     * @return mixed
     */
    public function getByAny($column, $value)
    {
        return $this->model->where($column, $value)->with('images', 'firstImage', 'attribute_variations')->get();
    }

    public function getByFilter(array $filter = [], $orderBy = ['id' => 'desc'], $limit = 0)
    {
        $filter = array_merge($filter, [
            'is_active' => 1
        ]);

        $products = $this->model->where($filter);


        if ($orderBy != null && is_array($orderBy) && count($orderBy) > 0) {
            $order_column = array_key_first($orderBy);
            $order_value = $orderBy[$order_column];
            $products = $products->orderBy($order_column, $order_value);
        }

        if ($limit !== 0 && $limit !== null) {
            $products = $products->limit($limit);
        }

        $products = $products->with('firstImage', 'secondImage', 'attribute_variations')->get();


        return $products = $products->map(function ($product) {
          
            // Check if attribute_variations exist and are not empty
            if ($product->enable_variation == 'on') {
                // Initialize sold_out_status as 'on'
                $product->sold_out_status = 'on';

                // Loop through attribute variations
                foreach ($product->attribute_variations as $variation) {
                    if ($variation->disable_buy === 'off') {
                        // If any variation has disable_buy set to 'off', set sold_out_status to 'off'
                        $product->sold_out_status = 'off';
                        break; // Exit loop early since we found an applicable variation
                    }
                }
            } elseif ($product->disable_buy === 'on') {
                $product->sold_out_status = 'on';
            }

            return $product;
        });

    }

    /**
     * @param  array  $att
     * @return mixed
     */
    public function create(array $att)
    {
        return $this->model->create($att);
    }

    /**
     * @param $id
     * @param  array  $att
     * @return mixed
     */
    public function update($id, array $att)
    {
        $todo = $this->getById($id);
        $todo->update($att);
        return $todo;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getById($id)
    {
        return $this->model->findOrFail($id);
    }

    public function delete($id)
    {
        $this->getById($id)->delete();
        $attributesdata = ProductAttributesData::where('main_id', $id)->delete();
        $product_categories = ProductCategories::where('main_id', $id)->delete();
        $productpricecombination = Productpricecombination::where('main_id', $id)->delete();
        $productimages = ProductImages::where('main_id', $id)->delete();
        $relatedproducts = RelatedProducts::where('main_id', $id)->delete();

        return true;
    }

    /**
     * Extra Methods
     */

    public function getAllExclusive($skip = null, $take = null)
    {
        return $this->model
            ->orderBy('updated_at', 'desc')
            ->orderBy('id', 'desc')
            ->where('is_sticky', 1)
            ->skip($skip)->take($take)
            ->get();
    }

    //public function getProductsOnSearch($skip = null, $take = null, $keyword = null)
    public function getProductsOnSearch(array $options = array(), $totalrowcount = null)
    {

        $default = array(
            'search_key' => null,
            'category' => null,
            'min_price' => !empty($minprice) ? $minprice : null,
            'max_price' => !empty($maxprice) ? $maxprice : null,
            'column' => !empty($field) ? $field : null,
            'sort_type' => !empty($type) ? $type : null,
            'limit' => 10,
            'offset' => 0
        );

        $no = array_merge($default, $options);

        //dd($no);

        if (!empty($no['limit'])) {
            $limit = $no['limit'];
        } else {
            $limit = 10;
        }

        if (!empty($no['offset'])) {
            $offset = $no['offset'];
        } else {
            $offset = 0;
        }

        if (!empty($no['sort_type'])) {
            $orderBy = $no["column"]." ".$no["sort_type"];
        } else {
            $orderBy = 'local_selling_price asc';
        }

        if (!empty($no['max_price'])) {
            $price_btw = 'local_selling_price BETWEEN "'.$no['min_price'].'" AND "'.$no['max_price'].'"';
        } else {
            $price_btw = 'local_selling_price BETWEEN 0 AND 5000000';
        }
        // LOC = line of code

        if (!empty($no['search_key']) && $no['search_key'] != 'undefined') {
            if ($totalrowcount == true) {

                return $this->model
                    ->orWhere('title', 'like', "%{$no["search_key"]}%")
                    ->orWhere('sub_title', 'like', "%{$no["search_key"]}%")
                    ->orWhere('sku', 'like', "%{$no["search_key"]}%")
                    ->orWhere('product_code', 'like', "%{$no["search_key"]}%")
                    ->paginate($limit)
                    ->get()->count();

            } else {

                return $this->model
                    ->leftJoin('productcategories', function ($join) {
                        $join->on('products.id', '=', 'productcategories.main_pid');
                    })
                    ->orWhere('products.title', 'like', "%{$no["search_key"]}%")
                    ->orWhere('products.sub_title', 'like', "%{$no["search_key"]}%")
                    ->orWhere('products.sku', 'like', "%{$no["search_key"]}%")
                    ->orWhere('products.product_code', 'like', "%{$no["search_key"]}%")
                    ->orWhereIn('productcategories.main_pid', implode(',', $no['category']))
                    ->paginate($limit)
                    //->toSql();
                    ->get();

            }

        } else {
            if ($totalrowcount == true) {
                return $this->model
                    ->whereRaw('parent_id IS NULL')
                    //->whereRaw('FIND_IN_SET(' . implode(',', $categories) . ', categories)')
                    ->whereRaw($price_btw)
                    ->orderByRaw($orderBy)
                    ->get()->count();
            } else {
                return $this->model
                    ->leftJoin('productcategories AS pc', function ($join) {
                        $join->on('products.id', '=', 'pc.main_pid');
                    })
                    ->whereIn('pc.term_id', $no['category'])
                    ->whereRaw('parent_id IS NULL')
                    ->whereRaw($price_btw)
                    ->orderByRaw($orderBy)
                    ->offset($offset)->limit($limit)
                    //->toSql();
                    ->select(['products.*', 'pc.*', 'products.id AS proid'])
                    ->orderBy('products.id', 'desc')
                    ->paginate(5);
            }
        }

    }


    public function getProductByFilter(array $options = array(), $cat)
    {
        $delet_arr = array(
            'price_min' => null,
            'price_max' => null,
            'page' => null,
            'sort_by' => null,
            'sort_show' => 16,
            'keyword' => null,
            'recommended' => null
        );

//        dd($delet_arr);

        // $cat = [120,121,122,146,150,151,154,155,156,157,158,16,160,161,164,172,173,187,20,599,604,86];

        $new = array_merge($delet_arr, $options);
        $keyword = arr_delete($options, $delet_arr);
        $row_count = 0;

        // dd($keyword);
        foreach ($keyword as $key => $value) {
            ++$row_count;
        }

        $result = $this->model->leftJoin('productcategories AS pc', function ($join) {
            $join->on('pc.main_pid', '=', 'products.id');
        })
            ->leftjoin('product_attribute_variations AS pav', 'pav.main_pid', 'products.id'); //nipun
        /*
    ->leftJoin('productattributesdata AS pda', function ($join) {
        $join->on('pda.main_pid', '=', 'products.id');
    });
        */
        $result = $result->where(['products.is_active' => 1]);
        if ($cat) {
            $result = $result->where(function ($query) use ($cat) {
                foreach ($cat as $ct) {
                    $query->orWhere('pc.term_id', $ct);
                }
            });
        }
        // ->whereIn('pc.term_id', $cat);
        if (isset($new['price_min'])) {
            $result = $result->whereBetween('products.local_selling_price', [$new['price_min'], $new['price_max']]);
        }
        if (isset($new['keyword'])) {
            $result = $result->where(function ($query) use ($new) {
                $query->orWhere('products.title', 'like', "%{$new["keyword"]}%");
                $query->orWhere('products.sub_title', 'like', "%{$new["keyword"]}%");
                $query->orWhere('products.product_code', 'like', "%{$new["keyword"]}%");
                $query->orWhere('products.sku', 'like', "%{$new["keyword"]}%");
                $query->orWhere('pav.variation_product_code', 'like', '%'.$new["keyword"].'%'); //nipun
                $query->orWhere('pav.variation_sub_title', 'like', '%'.$new["keyword"].'%'); //nipun
            });
        }
        /*
        $result = $result->where(function ($query) use ($keyword, $row_count) {
            foreach ($keyword as $key => $value) {
                $query->orWhere(function ($query) use ($value, $key) {
                    $query->Where('pda.key', $key);
                    //$query->orWhere('pda.key',  $key);
                    $query->Where(function ($query) use ($value, $key) {
                        if (is_array($value)) {
                            foreach ($value as $v) {
                                $query->orWhere('pda.value', $v);
                            }
                        }

                    });
                });

            }
        });
        */
        $result = $result->select(
            'products.title', 'products.sub_title', 'products.sku', 'products.seo_url', 'products.local_selling_price',
            'products.local_discount', 'products.dp_price',
            'products.enable_variation', 'products.is_active', 'products.variation_show_as',
            'products.id', 'products.product_set_id',
            'products.id as proid', 'pav.variation_product_code', 'pav.variation_sub_title'); // nipun
//        $result = $result->select('products.*', 'products.id as proid');
        $result = $result->groupBy('products.id');


        //dd($row_count);
        if ($row_count > 1) {
            $result = $result->havingRaw('COUNT(*) = '.$row_count);
        }


        if ($new['sort_by'] == 'title_asc') {
            $result = $result->orderBy('products.title', 'asc');

        } elseif ($new['sort_by'] == 'title_desc') {
            $result = $result->orderBy('products.title', 'desc');

        } elseif ($new['sort_by'] == 'price_asc') {
            $result = $result->orderBy('products.local_selling_price', 'asc');

        } elseif ($new['sort_by'] == 'price_desc') {
            $result = $result->orderBy('products.local_selling_price', 'desc');

        } else {
            $result = $result->orderBy('products.id', 'desc');
        }

        $result = $result->where('products.is_active', true);

        //$result = $result->with('firstImage', 'secondImage', );


        $result = $result->with('firstImage', 'secondImage', 'attribute_variations', 'product_set');        

        // $result = $result->toSql();
        $result = $result->paginate($new['sort_show']);
        //dd($result);
        return $result;
    }

    public function getProductCategories($id)
    {
        return ProductCategories::where('main_pid', $id)->get();
    }

    public function getSimilarProduct($id)
    {
        return RelatedProducts::where('main_pid', $id)->get();
    }

    public function getProductImages($id)
    {
        return ProductImages::where('main_pid', $id)->get();
    }

    public function getProductAttributesData($id)
    {
        return ProductAttributesData::where('main_pid', $id)->get();
    }

    public function get_product_list_by_search_key($search_key, $limit = null)
    {
        $model = $this->model->where('title', 'LIKE', '%'.$search_key.'%');
        if ($limit != null) {
            $model->take($limit);
        }
        return $model->with('firstImage', 'secondImage')->where('is_active', 1)->get();
    }

    /**
     * @param  array  $options
     */
    public function get_search_product_ajax(array $options = array())
    {
        $dufault = array(
            'keyword' => null,
            'cat' => null
        );

        $new = array_merge($dufault, $options);
        //dd($new);

        $result = $this->model
            ->leftJoin('productcategories AS pc', function ($join) {
                $join->on('pc.main_pid', '=', 'products.id');
            })
            ->leftjoin('product_attribute_variations AS pv', 'pv.main_pid', 'products.id')
            ->leftJoin('terms AS t', function ($join) {
                $join->on('t.id', '=', 'pc.term_id');
            });
        $result = $result->where(['products.is_active' => 1]);
        if ($new['cat'] != null) {
            $result = $result->where('pc.term_id', $new['cat']);
        }


        $result = $result->where(function ($query) use ($new) {
            $subTitle = preg_match('/^[^ ].* .*[^ ]$/', $new['keyword']);
            $query->orWhere('products.title', 'like', "%{$new["keyword"]}%");
            $query->orWhere('products.sub_title', 'like', "%{$new["keyword"]}%");
            $query->orWhere('products.product_code', 'like', "%{$new["keyword"]}%");
            $query->orWhere('products.sku', 'like', "%{$new["keyword"]}%");
            $query->orWhere('products.sku', 'like', "%{$new["keyword"]}%");

            $query->orWhere('pv.variation_sub_title', 'like', '%'.$new["keyword"].'%'); // nipun
            $query->orWhere('pv.variation_product_code', 'like', $new["keyword"]); //nipun

        });
        $result = $result->select('products.*', 't.id as cat_id', 't.seo_url as t_url', 'pv.variation_sub_title',
            'pv.variation_product_code'); // nipun
//        $result = $result->select('products.*', 't.id as cat_id', 't.seo_url as t_url');
        $result = $result->groupBy('products.id');

        $result = $result->take(10)->get();

        return $result;


    }

    public function get_search_first_product_cat(array $options = array())
    {
        $dufault = array(
            'keyword' => null,
            'cat' => null
        );

        $new = array_merge($dufault, $options);
        //dd($new);

        $result = $this->model->where(['products.is_active' => 1])
            ->leftJoin('productcategories AS pc', function ($join) {
                $join->on('pc.main_pid', '=', 'products.id');
            })
            ->leftJoin('terms AS t', function ($join) {
                $join->on('t.id', '=', 'pc.term_id');
            });

        if ($new['cat'] != null) {
            $result = $result->where('pc.term_id', $new['cat']);
            $result = $result->where('pc.term_id', '!=', null);
        }


        $result = $result->where(function ($query) use ($new) {
            $query->orWhere('products.title', 'like', "%{$new["keyword"]}%");
            $query->orWhere('products.sub_title', 'like', "%{$new["keyword"]}%");
            $query->orWhere('products.product_code', 'like', "%{$new["keyword"]}%");
            $query->orWhere('products.sku', 'like', "%{$new["keyword"]}%");
        });
        $result = $result->select('pc.term_id as cat_id', 't.seo_url as t_url');

        $result = $result->groupBy('pc.term_id')->get();

        //dd($result);

        return $result;


    }

    /**
     *
     */
    public function getAllWhere(array $options = array(), array $where = array())
    {

        $default = array(
            'column' => null,
            'search_key' => null
        );

        $new = array_merge($default, $options);

        if (!empty($new['search_key']) && !empty($new['column'])) {
            //dd($this->model->whereRaw('' . $new['column'] . ' like "%' . $new['search_key'] . '%"')->toSql());
            return $this->model
                ->where($where)
                ->whereRaw(''.$new['column'].' like "%'.$new['search_key'].'%"')
                ->orderBy('id', 'desc')
                ->paginate(10);
        } else {
            return $this->model
                ->where($where)
                ->orderBy('id', 'desc')
                ->paginate(10);
        }

    }


    public function getAllWhereByRole(array $options = array(), array $where = array())
    {

        $default = array(
            'column' => null,
            'search_key' => null
        );

        $new = array_merge($default, $options);

        if (!empty($new['search_key']) && !empty($new['column'])) {
            //dd($this->model->whereRaw('' . $new['column'] . ' like "%' . $new['search_key'] . '%"')->toSql());
            $data = $this->model;
            if (auth()->user()->isVendor()) {
                $data = $data->where(['user_id' => auth()->user()->id]);
            }

            $data = $data->where($where)
                ->whereRaw(''.$new['column'].' like "%'.$new['search_key'].'%"')
                ->orderBy('id', 'desc')
                ->paginate(10);
            return $data;
        } else {
            $data = $this->model;
            if (auth()->user()->isVendor()) {
                $data = $data->where(['user_id' => auth()->user()->id]);
            }

            $data = $data->where($where)->orderBy('id', 'desc')
                ->paginate(10);
            return $data;
        }

    }

    public function getAllByRole(array $options = array())
    {

        $default = array(
            'column' => null,
            'search_key' => null
        );

        $new = array_merge($default, $options);


        if (!empty($new['search_key']) && !empty($new['column'])) {
            //dd($this->model->whereRaw('' . $new['column'] . ' like "%' . $new['search_key'] . '%"')->toSql());
            $data = $this->model;
            if (auth()->user()->isVendor()) {
                $data = $data->where(['user_id' => auth()->user()->id]);
            }

            $data = $data->leftJoin('product_attribute_variations as pav', 'pav.main_pid', 'products.id')
                ->select('products.*', 'pav.variation_sub_title', 'pav.variation_product_code'); // nipun

            $data = $data->whereRaw(''.$new['column'].' like "%'.$new['search_key'].'%"');

            //$subTitle = preg_match('/^[^ ].* .*[^ ]$/', $new['search_key']);
            //dd($subTitle);
//            dd($new['search_key']);

            $data = $data->orWhere('pav.variation_product_code', 'like', '%'.$new['search_key'].'%'); // nipun
            $data = $data->orWhere('pav.variation_sub_title', 'like', '%'.$new['search_key'].'%'); // nipun

            //$data = $data->whereRaw('variation_sub_title','Like', '%'.$subTitle.'%');
//            ->groupBy('id')
            $data = $data->orderBy('products.id', 'desc')->groupBy('id')->paginate(10);
//            dd($data);
            return $data;
        } else {
            $data = $this->model;
            if (auth()->user()->isVendor()) {
                $data = $data->where(['user_id' => auth()->user()->id]);
            }

            $data = $data->orderBy('id', 'desc')
                ->paginate(10);

            return $data;
        }

    }
}
