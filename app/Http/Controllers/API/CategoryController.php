<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Repositories\Attribute\AttributeInterface;
use App\Repositories\Product\ProductInterface;
use App\Repositories\ProductSet\ProductSetInterface;
use App\Repositories\TagGallery\TagGalleryInterface;
use App\Repositories\Term\TermInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CategoryController extends Controller
{
    private $term;
    private $attribute;
    private $product;
    private $tag_gallery;
    private $product_set;

    public function __construct(TermInterface       $term, AttributeInterface $attribute,
                                ProductInterface    $product,
                                TagGalleryInterface $tag_gallery,
                                ProductSetInterface $product_set)
    {
        $this->term = $term;
        $this->attribute = $attribute;
        $this->product = $product;
        $this->tag_gallery = $tag_gallery;
        $this->product_set = $product_set;
    }


    public function categoryInfo(Request $request)
    {
//        dd(1);
        $requests = $request->all();
        $cache_key = 'category-category-info-' . $request->seo_url;

        $info = Cache::remember($cache_key, 86400, function () use($request)  {

            $info = $this->term->getByAny('seo_url', $request->seo_url)->first();

            if ($info) {
                if (!empty($info->banner1)) {
                    $tern_banner_image = Image::where('id', $info->banner1)->first();
                    $tern_banner_image = $tern_banner_image ? 'https://admin.regalfurniturebd.com/' . $tern_banner_image->full_size_directory : false;
                } else {
                    $tern_banner_image = null;
                }
                $info = [
                    'id' => $info->id,
                    'name' => $info->name,
                    'seo_url' => $info->seo_url,
                    'seo_h1' => $info->seo_h1 ?? null,
                    'seo_h2' => $info->seo_h2  ?? null,
                    'seo_h3' => $info->seo_h3  ?? null,
                    'seo_h4' => $info->seo_h4  ?? null,
                    'seo_h5' => $info->seo_h5  ?? null,
                    'description' => $info->description  ?? null,
                    'keywords' => $info->term_keywords  ?? null,
                    'banner1' => $tern_banner_image ?? false,
                ];
            }

            return $info;

        });

        if($request->cache == 'clear'){
            Cache::forget($cache_key);
        }



        return response()->json(compact('requests', 'info'));
    }


    public function subCategory(Request $request)
    {
//        dd(1);
        $requests = $request->all();

        $cache_key = 'category-category-subcat-' . $request->seo_url;
        $category = Cache::remember($cache_key, 86400, function () use($request)  {

            $parent = $this->term->getByAny('seo_url', $request->seo_url)->first();
            $category = [];

            if ($parent != null) {

                if ($parent->id == 1) {
                    $category = $this->term->self()->where('type', "category")
                        ->where('id', '!=', 1)->where('is_published', 1)->where('in_product_home', 1)
                        ->orderBy('serial', 'ASC')->with('home_img', 'page_img', 'sub_cats', 'banner_img')->get();
                } else {
                    $category = $this->term->getByFilter([
                        'parent' => $parent->id,
                        'is_published' => 1
                    ]);
                }
                // $category = $this->term->getByFilter([
                //     'parent' => $parent->id,
                //     'is_published' => 1
                // ]);
            }

            return $category;
        });

        if($request->cache == 'clear'){
            Cache::forget($cache_key);
        }

        return response()->json(compact('requests', 'category'));
    }


    public function recommended(Request $request)
    {
        $requests = $request->all();
        $cache_key = 'category-category-recommended' . $request->seo_url;

        $products = Cache::remember($cache_key, 86400, function () use ($request) {

            $category = $this->term->getByAny('seo_url', $request->seo_url)->first();
            $products = [];

            if ($category) {
                $products = $this->product->getProductByFilter([], [$category->id]);
            }

            return $products;
        });

        if ($request->cache == 'clear') {
            Cache::forget($cache_key);
        }

        return response()->json(compact('requests', 'products'));
    }


    public function getFilter(Request $request)
    {
        $seo_url = $request->seo_url;
        $cache_key = 'category-category-filter' . $seo_url;
        $allVars = Cache::remember($cache_key, 86400, function () use ($seo_url) {


            $category = $this->term->getByAny('seo_url', $seo_url)->first();
            $filters_cat = [];
            $filters_att = [];

            if ($category) {
                $filters_cat = get_filters_cat($category->id);
                $con_att = $category->connected_with;
                $filters_att = $this->attribute->getFilter($con_att);
            }

            return compact('filters_cat', 'filters_att');
        });

        if ($request->cache == 'clear') {
            Cache::forget($cache_key);
        }

        return response()->json($allVars);
    }


    public function products(Request $request)
    {
        $seo_url = $request->seo_url;
        $get_keyworld = $request->only(['page', 'price_min', 'price_max', 'sort_by', 'sort_show', 'keyword']);

        $requests = $request->all();
        $filteringData = [];

        foreach ($requests as $key => $val) {
            if (strpos($key, 'filterby_') !== false && $key != 'filterby_category') {
                $filteringData[strtolower(str_replace('filterby_', '', $key))] = explode('|', $val);
            }
        }

        $get_keyworld = array_merge($get_keyworld, $filteringData);


        $cache_key = md5("category-category-products-$seo_url-" . md5(json_encode($get_keyworld)));


//
//        $allVars =  Cache::remember($cache_key, 1800,
//                function () use($request,$requests,$seo_url,$get_keyworld)  {
//
//                    //return response()->json($get_keyworld);
//
//
//                });

        $allVars = Cache::remember($cache_key, 1800,
            function () use ($request, $requests, $seo_url, $get_keyworld) {

                //return response()->json($get_keyworld);
                $category = false;
                $products = false;
                $categories = false;
                $filters_att = false;
                $filters_cat = false;
                $view_cat = [];

                $category = $this->term->getByAny('seo_url', $seo_url)->first();

//                $category = $this->term->getByAny('seo_url', $seo_url)->first();

//                dd($category);

                if (!empty($category->id) && $category->id != 1) {
                    $filters_cat = get_filters_cat($category->id);
                    // dd($filters_cat);

                    $con_att = $category->connected_with;
                    $filters_atts = $this->attribute->getFilter($con_att);

                    $filters_att = [];
                    foreach ($filters_atts as $f_at) {
                        $p_attr = explode('|', $f_at->default_value);

                        $f_at->ProccessDefault = array_map(function ($pr_data) {
                            $e2 = explode(':', $pr_data);
                            return $e2;
                        }, $p_attr);

                        $filters_att[] = $f_at;
                    }

                    $view_cat = get_all_sub_cat($category->id);

                }

                if ($request->filterby_category && $request->filterby_category != "") {
                    $view_cat = explode("|", $request->filterby_category);
                }
                // print_r($view_cat);
                // exit;

                $products = $this->product->getProductByFilter($get_keyworld, $view_cat);

                $allVars = compact(
                    'requests',
                    'products'
                );

                return $allVars;

            }
        );

        if ($request->cache == 'clear') {
            Cache::forget($cache_key);
        }


        return response()->json($allVars);



    }

    public function tagGallery(Request $request)
    {

        $seo_url = $request->seo_url;
        $cache_key = 'category-category-tag-gal' . $seo_url;
        $tag_gallery = Cache::remember($cache_key, 86400, function () use ($seo_url) {


            $category = $this->term->getByAny('seo_url', $seo_url)->first();

            // dd($category);

            $tag_galleryz = $this->tag_gallery->getByFilter([
                'category_id' => $category->id
            ], [
                'id' => 'asc'
            ], 10);

            $tag_gallery = [];
            $terms = [];


            foreach ($tag_galleryz as $tg) {
                $tag_gallery[] = [
                    'url' => $tg->url,
                    'url_type' => $tg->url_type,
                    'id' => $tg->id,
                    'category_id' => $tg->term->seo_url,
                    'image_url' => $tg->image->full_size_directory,

                ];
            }

            return array_slice($tag_gallery, 0, 7);
        });
        if ($request->cache == 'clear') {
            Cache::forget($cache_key);
        }
        return response()->json(compact('tag_gallery'));

    }

    public function productSet(Request $request)
    {


        $cache_key = 'category-category-product-set' . $request->seo_url;

        $product_sets = Cache::remember($cache_key, 86400, function () use ($request) {

            $term = $this->term->getByAny('seo_url', $request->seo_url)->first();
            $product_sets = [];
            $filterPriceMin = $request->price_min ?? 0;
            $filterPriceMax = $request->price_max ?? 200000;


            if ($term != null) {

                $category_id = $term->id;
                $db_product_sets = $this->product_set->getByAny('category_id', $category_id);

                foreach ($db_product_sets as $db_product) {

                    $priceAll = $db_product->price_all;

                    if (($priceAll >= $filterPriceMin) && ($priceAll <= $filterPriceMax)) {

                        $product_sets[] = [
                            'image_url' => $db_product->image->icon_size_directory ?? null,
                            'id' => $db_product->id,
                            'title' => $db_product->title,
                            'slug' => $db_product->slug,
                            'price_all' => $priceAll,
                            'type' => 'product_set'
                        ];

                    }


                }

            }


            return $product_sets;
        });

        if ($request->cache == 'clear') {
            Cache::forget($cache_key);
        }

        return response()->json(compact('product_sets'));
    }


}
