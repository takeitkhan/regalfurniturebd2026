<?php

namespace App\Http\Controllers\API;

use App\Models\FlashItem;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeVariation;
use App\Models\Review;
use App\Models\Term;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ProductSetInfo;

use App\Models\ProductQuestion;
use App\Repositories\Product\ProductInterface;
use App\Models\ProductSetFabric;

use App\Repositories\ProductQuestion\ProductQuestionInterface;
use App\Repositories\ProductSet\ProductSetInterface;

use App\Models\Image;
use App\Models\ProductSetProduct;
use App\Repositories\Review\ReviewInterface;
use App\Repositories\Term\TermInterface;
use App\Repositories\Comment\CommentInterface;
use App\Repositories\ReviewImage\ReviewImageInterface;
use App\Repositories\SessionData\SessionDataInterface;
use phpDocumentor\Reflection\Types\Boolean;
use App\Repositories\User\UserInterface;
use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use DB;
use App\Models\ProductCategories;
class OldProductController extends Controller
{

    private $product;
    private $product_set;
    private $term;
    private $session_data;
    private $comment;

    private $review;
    private $user;
    private $product_question;
    private $reviewImage;

    public function __construct(ProductInterface         $product, ProductSetInterface $product_set,

                                TermInterface            $term,
                                SessionDataInterface     $session_data,
                                CommentInterface         $comment,
                                ReviewInterface          $reviws,
                                UserInterface            $user,
                                ProductQuestionInterface $product_question,
                                ReviewImageInterface     $reviewImage)
    {
        $this->product = $product;
        $this->product_set = $product_set;
        $this->term = $term;
        $this->session_data = $session_data;
        $this->comment = $comment;
        $this->reviewImage = $reviewImage;
        $this->review = $reviws;
        $this->user = $user;
        $this->product_question = $product_question;
    }


    public function simpleInfo(Request $request)
    {
        $cache_key = 'product-simple-info-'.$request->seo_url;
        $product = Cache::remember($cache_key, 86400, function () use($request)  {

            $seo_url = $request->seo_url;
            $p_info = $this->product->getByAny('seo_url', $seo_url)->first();
            $product = [];
            //        dd($p_info);
            if ($p_info && $p_info->is_active == 1) {

                // $review_count = product_review_count($p_info->id);
                //            if($p_info->variation_show_as == 'Image'){
                //                $getVariation = $this->getProoductAttribute($p_info->id, ['preselect' => true]);
                //            }
                $getVariationImg = $this->getProoductAttribute($p_info->id, ['preselect' => true]);
                //            if($p_info->variation_show_as == 'Text') {
                $getVariation = $this->getProoductVariation($p_info->id);
                //            }
                $mainCategory = ProductCategories::where('main_pid', $p_info->id)->where('is_attgroup_active', 1)->first() ?? null;
                $have_flash = FlashItem::leftJoin('flash_schedules', 'flash_schedules.id', '=', 'flash_items.fi_shedule_id')
                    ->where('flash_items.fi_product_id', $p_info->id)
                    ->where('flash_schedules.fs_is_active', 1)
                    ->where('flash_schedules.fs_start_date', '<=', Carbon::now()->format('Y-m-d h:i:s'))
                    ->where('flash_schedules.fs_end_date', '>=', Carbon::now()->format('Y-m-d h:i:s'))
                    ->orderBy('flash_items.id', 'desc')
                    ->first();
                $product = [
                    'id' => $p_info->id,
                    'seo_url' => $p_info->seo_url,
                    'title' => $p_info->title,
                    'sub_title' => $p_info->sub_title,
                    'short_description' => $p_info->short_description,
                    'description' => strip_tags($p_info->description),
                    'seo_description' => strip_tags($p_info->seo_description),
                    'seo_keywords' => $p_info->seo_keywords,
                    'image' => $p_info->firstImage->full_size_directory,
                    'local_selling_price' => $p_info->local_selling_price,
                    'local_discount' => $p_info->local_discount,
                    'actual_discount' => $p_info->actual_discount,
                    'sku' => $p_info->sku,
                    'price_now' => $p_info->product_price_now,
                    'prebook' => $p_info->product_price_now,
                    'pre_booking' => $p_info->pre_booking,
                    'variation_show_as' =>$p_info->variation_show_as,
                    'variation_layer_start' =>$p_info->variation_layer_start,
                    'disable_buy' => $p_info->disable_buy,
                    'enable_variation' => $p_info->enable_variation,
                    'variation_images' =>$getVariationImg->original,
                    // 'review_count' => $review_count
                    'variations' => $getVariation->original ?? false,
                    'special_notification' => $mainCategory ? Term::where('id', $mainCategory->term_id)->first()->special_notification : false,
                    'flash_item_information' => $have_flash ?? false,
                ];

                // store for recent views
            }

            return $product;
        });
        if (isset($product['id'])) {
            $this->storeRecentViews($request->header('Self-Token'), $product['id']);
        }

//            if($request->cache == 'clear'){
//                Cache::forget($cache_key);
//            }

        return response()->json(compact('product'));

    }

    public function info(Request $request)
    {

        $cache_key = 'product-product-info-' . $request->seo_url;
        $product = Cache::remember($cache_key, 86400, function () use ($request) {

            $seo_url = $request->seo_url;
            $pr_info = $this->product->getByAny('seo_url', $request->seo_url)->first();
            $product = [];

            if ($pr_info && $pr_info->is_active == 1) {

                $attribute_data = \App\Models\ProductAttributesData::leftJoin('attributes', function ($join) {
                    $join->on('productattributesdata.attribute_id', '=', 'attributes.id');
                })->where('main_pid', $pr_info->id)->get();
                $categories = $this->product->getProductCategories($pr_info->id);
                $have_flash = FlashItem::leftJoin('flash_schedules', 'flash_schedules.id', '=', 'flash_items.fi_shedule_id')
                    ->where('flash_items.fi_product_id', $pr_info->id)
                    ->where('flash_schedules.fs_is_active', 1)
                    ->where('flash_schedules.fs_start_date', '<=', Carbon::now()->format('Y-m-d h:i:s'))
                    ->where('flash_schedules.fs_end_date', '>=', Carbon::now()->format('Y-m-d h:i:s'))
                    ->orderBy('flash_items.id', 'desc')
                    ->first();
                $product = [
                    'id' => $pr_info->id,
                    'title' => $pr_info->title,
                    'sub_title' => $pr_info->sub_title,
                    'seo_url' => $pr_info->seo_url,
                    'product_code' => $pr_info->product_code,
                    'sku' => $pr_info->sku,
                    'qty' => $pr_info->qty,
                    'local_selling_price' => $pr_info->local_selling_price,
                    'local_discount' => $pr_info->local_discount,
                    'pre_booking' => $pr_info->pre_booking,
                    'pre_booking_perchantage' => $pr_info->pre_booking_discount,
                    'stock_status' => $pr_info->stock_status,
                    'delivery_area' => $pr_info->delivery_area,
                    'delivery_charge' => $pr_info->delivery_charge,
                    'delivery_time' => $pr_info->delivery_time,
                    'pro_warranty' => $pr_info->pro_warranty,
                    'short_description' => $pr_info->short_description,
                    'purchase_note' => $pr_info->purchase_note,
                    'description' => $pr_info->description,
                    'tags' => $pr_info->tags,
                    'seo_keywords' => $pr_info->seo_keywords,
                    'offer_details' => $pr_info->offer_details,
                    'offer_start_date' => $pr_info->offer_start_date,
                    'offer_end_date' => $pr_info->offer_end_date,
                    'enable_comment' => $pr_info->enable_comment,
                    'enable_review' => $pr_info->enable_review,
                    'enable_variation' => $pr_info->enable_variation,
                    'express_delivery' => $pr_info->express_delivery,
                    'new_arrival' => $pr_info->new_arrival,
                    'best_selling' => $pr_info->best_selling,
                    'flash_sale' => $pr_info->flash_sale,
                    'flash_time' => $pr_info->flash_time,
                    'recommended' => $pr_info->recommended,
                    'disable_buy' => $pr_info->disable_buy,
                    'order_by_phone' => $pr_info->order_by_phone,
                    'multiple_pricing' => $pr_info->multiple_pricing,
                    'emi_available' => $pr_info->emi_available,
                    'position' => $pr_info->position,
                    'enable_timespan' => $pr_info->enable_timespan,
                    'seo_description' => $pr_info->seo_description,
                    'seo_title' => $pr_info->seo_title,
                    'seo_h1' => $pr_info->seo_h1,
                    'seo_h2' => $pr_info->seo_h2,
                    'parent_id' => $pr_info->parent_id,
                    'ar_info' => $pr_info->ar->image ?? null,
                    'attrs' => $attribute_data,
                    'care_info' => $pr_info->careInfo,
                    'categories' => $categories,
                    'flash_item_information' => $have_flash ?? false,
                ];

            }

            return $product;
        });

        if ($request->cache == 'clear') {
            Cache::forget($cache_key);
        }

        return response()->json(compact('product'));

    }

    public function similarProducts(Request $request)
    {

        $cache_key = 'product-similar-product' . $request->seo_url;

        $similar_products = Cache::remember($cache_key, 86400, function () use ($request) {


            $seo_url = $request->seo_url;
            $product = $this->product->getByAny('seo_url', $request->seo_url)->first();
            $similar_products = [];

            if ($product && $product->is_active == 1) {
                $similar_productz = $this->product->get_product_list_by_search_key($product->title, 10);
                // return $similar_productz;
                foreach ($similar_productz as $sm_product) {

                    $item = [
                        'title' => $sm_product->title,
                        'sub_title' => $sm_product->sub_title,
                        'first_image' => $sm_product->firstImage,
                        'second_image' => $sm_product->secondImage,
                        'seo_url' => $sm_product->seo_url,
                        'product_price_now' => $sm_product->product_price_now,
                        'local_selling_price' => $sm_product->local_selling_price,
                        'product_set' => $sm_product->product_set,
                        'actual_discount' => $sm_product->actual_discount,
                        'local_discount' => $sm_product->local_discount
                    ];
                    $similar_products[] = $item;
                }

            }

            return $similar_products;

        });


        if ($request->cache == 'clear') {
            Cache::forget($cache_key);
        }

        return response()->json(compact('similar_products'));

    }


    public function image(Request $request)
    {
//        dd(1);
        $cache_key = 'product-images-videos-degrees-' . $request->seo_url;

        $allData = Cache::remember($cache_key, 86400, function () use ($request) {

            $seo_url = $request->seo_url;
            $product = $this->product->getByAny('seo_url', $request->seo_url)->first();
            $images = [];
            $degree_images = [];
            $ar = null;


            $youtubeLink = "";
            if ($product && $product->is_active) {

                $imageIncludes = [];

                foreach ($product->priorityImages as $single_image) {

                    $img = [
                        'id' => $single_image->id,
                        'full_size_directory' => $single_image->full_size_directory,
                        'icon_size_directory' => $single_image->icon_size_directory
                    ];

                    $images[] = $img;
                    array_push($imageIncludes, $single_image->id);

                }


                foreach ($product->images as $single_image) {
                    if (in_array($single_image->id, $imageIncludes))
                        continue;

                    $img = [
                        'id' => $single_image->id,
                        'full_size_directory' => $single_image->full_size_directory,
                        'icon_size_directory' => $single_image->icon_size_directory
                    ];

                    $images[] = $img;
                }

                $youtubeLink = $product->youtubeVideo->link ?? null;


                $degree_images_db = $product ? $product->threeSixtyDegreeImage : [];
                foreach ($degree_images_db as $degimg) {
                    $degree_images[] = $degimg->image->full_size_directory ?? null;
                }

                $ar = $product->ar->image??null;
            }

            $allData = compact('images', 'youtubeLink', 'degree_images','ar');
            return $allData;
        });


        if ($request->cache == 'clear') {
            Cache::forget($cache_key);
        }

        return response()->json($allData);

    }


    public function sameCategory(Request $request)
    {
        $product = $this->product->getByAny('seo_url', $request->seo_url);
        $categories = $this->product->getProductCategories($product->id);
    }

    public function sameCategoryProductList(Request $request)
    {

        $cache_key = 'product-same-cat-products-' . $request->seo_url;
        $products = Cache::remember($cache_key, 86400, function () use ($request) {

            $seo_url = $request->seo_url;
            $product = $this->product->getByAny('seo_url', $request->seo_url)->first();
            $products = [];

            if ($product && $product->is_active) {

                $categories = $this->product->getProductCategories($product->id);
                $category_id = null;

                if (count($categories) > 0) {
                    $category_id = $categories[0]->term_id;
                }

                if ($category_id != null) {

                    $get_fillter_product = $this->product->getProductByFilter([], [$category_id]);

                    foreach ($get_fillter_product as $single_product) {

                        $categoryProductList = [
                            'title' => $single_product->title,
                            'sub_title' => $single_product->sub_title,
                            'first_image' => $single_product->firstImage,
                            'second_image' => $single_product->secondImage,
                            'seo_url' => $single_product->seo_url,
                            'product_price_now' => $single_product->product_price_now,
                            'local_selling_price' => $single_product->local_selling_price,
                            'product_set' => $single_product->product_set,
                            'actual_discount' => $single_product->actual_discount,
                            'local_discount' => $single_product->local_discount
                        ];

                        $products [] = $categoryProductList;
                    }
                }
            }

            return $products;

        });

        if ($request->cache == 'clear') {
            Cache::forget($cache_key);
        }

        return response()->json(compact('products'));

    }

    public function goesWellWith(Request $request)
    {


        $cache_key = 'product-goes-well-products-' . $request->seo_url;
        $products = Cache::remember($cache_key, 86400, function () use ($request) {


            $seo_url = $request->seo_url;
            $product = $this->product->getByAny('seo_url', $request->seo_url)->first();
            $products = [];

            if ($product && $product->is_active) {
                $categories = $this->product->getProductCategories($product->id)->toArray();
                $category_ids = [];

                if (count($categories) > 0) {
                    $category_ids = array_column($categories, 'term_id');
                }

                if (count($category_ids) > 0) {

                    $get_fillter_product = $this->product->getProductByFilter([
                        'recommended' => "on"
                    ], $category_ids);

                    foreach ($get_fillter_product as $single_product) {

                        $categoryProductList = [
                            'title' => $single_product->title,
                            'sub_title' => $single_product->sub_title,
                            'first_image' => $single_product->firstImage,
                            'second_image' => $single_product->secondImage,
                            'seo_url' => $single_product->seo_url,
                            'product_price_now' => $single_product->product_price_now,
                            'local_selling_price' => $single_product->local_selling_price,
                            'product_set' => $single_product->product_set,
                            'actual_discount' => $single_product->actual_discount,
                            'local_discount' => $single_product->local_discount
                        ];

                        $products [] = $categoryProductList;
                    }
                }

            }

            return $products;

        });

        if ($request->cache == 'clear') {
            Cache::forget($cache_key);
        }
        return response()->json(compact('products'));

    }


    public function otherAlsoSee(Request $request)
    {

        $cache_key = 'product-other-see-products-' . $request->seo_url;
        $products = Cache::remember('product-other-see-products-' . $request->seo_url, 86400, function () use ($request) {

            $seo_url = $request->seo_url;
            $product = $this->product->getByAny('seo_url', $request->seo_url)->first();
            $products = [];

            if ($product && $product->is_active) {

                $categories = $this->product->getProductCategories($product->id);
                $category_id = [];

                if (count($categories) > 0) {
                    $category_id = $categories[0]->term_id;
                }

                if ($category_id != null) {

                    $get_fillter_product = $this->product->getProductByFilter([], [$category_id]);

                    foreach ($get_fillter_product as $single_product) {

                        $categoryProductList = [
                            'title' => $single_product->title,
                            'sub_title' => $single_product->sub_title,
                            'first_image' => $single_product->firstImage,
                            'second_image' => $single_product->secondImage,
                            'seo_url' => $single_product->seo_url,
                            'product_price_now' => $single_product->product_price_now,
                            'local_selling_price' => $single_product->local_selling_price,
                            'product_set' => $single_product->product_set,
                            'actual_discount' => $single_product->actual_discount,
                            'local_discount' => $single_product->local_discount
                        ];

                        $products [] = $categoryProductList;
                    }
                }

            }

            return $products;

        });

        if ($request->cache == 'clear') {
            Cache::forget($cache_key);
        }

        return response()->json(compact('products'));

    }

    public function productSetInfo(Request $request)
    {


        $cache_key = 'product-product-set-info-' . $request->slug;

        $product_set = Cache::remember($cache_key, 86400, function () use ($request) {

            $product_set = [];

            if ($request->slug) {
                $product_set = $this->product_set->getByAny('slug', $request->slug)->first();
                $db_productSetProduct = ProductSetProduct::where('product_set_id', $product_set->id)->get()->keyBy('product_id')->toArray();
                $product_ids = [];
                if (count($db_productSetProduct) > 0) {
                    $product_ids = array_column($db_productSetProduct, 'product_id');
                }
                $products = $this->product->self()->whereIn('id', $product_ids)->get();
                $product_price = 0;
                foreach ($products as $pro) {
                    $product_price += ($db_productSetProduct[$pro->id]['qty'] ?? 1) * $pro->product_price_now;
                }
                $product_set->product_ids = $product_ids;
                $product_set->price_all = $product_price;
                $product_set->image_url = $product_set->image->full_size_directory ?? null;
                unset($product_set->image);
            }

            return $product_set;
        });


        if ($request->cache == 'clear') {
            Cache::forget($cache_key);
        }

        return response()->json(compact('product_set'));
    }

    public function productSetAdditionalInfo(Request $request)
    {


        $cache_key = 'product-product-set-adi-info-' . $request->slug;

        $infos = Cache::remember($cache_key, 86400, function () use ($request) {


            $slug = $request->slug;
            $productSet = $this->product_set->self()->where('slug', $slug)->first();


            $productSetInfo = ProductSetInfo::where('product_set_id', $productSet->id)->get();
            $infos = [];

            foreach ($productSetInfo as $info) {

                $infos[] = [
                    'id' => $info->id,
                    'title' => $info->title,
                    'sub_title' => $info->sub_title,
                    'description' => $info->description,
                ];
            }

            return $infos;
        });

        if ($request->cache == 'clear') {
            Cache::forget($cache_key);
        }

        return response()->json(compact('infos'));
    }


    public function productSetFabric(Request $request)
    {


        $slug = $request->slug;
        $productSet = $this->product_set->self()->where('slug', $slug)->first();

        $productSetFabric = ProductSetFabric::where('product_set_id', $productSet->id)->where('active', true)->get();
        $fabrics = [];

        foreach ($productSetFabric as $fabric) {

            $imagesDB = Image::whereIn('id', explode(",", $fabric->images))->get();
            $images = [];
            foreach ($imagesDB as $image) {
                $images[] = [
                    'icon' => $image->icon_size_directory ?? null,
                    'image_url' => $image->full_size_directory ?? null
                ];
            }

            $fabrics[] = [
                'id' => $fabric->id,
                'title' => $fabric->title,
                'image_url' => $fabric->image->full_size_directory ?? null,
                'images' => $images,
            ];

        }

        return response()->json(compact('fabrics'));

    }

    public function productSetProducts(Request $request)
    {

        $products = [];
        $term = null;

        if (!empty($request->slug)) {
            $product_set = $this->product_set->getByAny('slug', $request->slug)->first();

            //Product finding using product ids

            if ($product_set != null) {

                $term = $this->term->getById($product_set->category_id);

                $db_productSetProduct = ProductSetProduct::where('product_set_id', $product_set->id)->get()->toArray();
                $product_ids = array_column($db_productSetProduct, 'product_id');
                $product_set_qty = [];
                foreach ($db_productSetProduct as $productSetProduct) {
                    $product_set_qty[$productSetProduct['product_id']] = $productSetProduct['qty'];
                }
                // $product_ids =json_decode(json_encode($db_productSetProduct),true);

                // $product_ids = explode(',', $product_set->product_ids);

                $db_products = $this->product->self()->whereIn('id', $product_ids)->with('firstImage')->get();


                foreach ($db_products as $single_product) {
                    $product = [
                        'id' => $single_product->id,
                        'title' => $single_product->title,
                        'sub_title' => $single_product->sub_title,
                        'seo_url' => $single_product->seo_url,
                        'product_code' => $single_product->product_code,
                        'sku' => $single_product->sku,
                        'qty' => $single_product->qty,
                        'price_now' => $single_product->product_price_now,
                        'stock_status' => $single_product->stock_status,
                        'product_set_qty' => $product_set_qty[$single_product->id],
                        'image_url' => $single_product->firstImage->icon_size_directory ?? null,
                    ];
                    $products[] = $product;
                }

            }

        }
        return response()->json(compact('term', 'products'));
    }


    public function getRecentViews(Request $request)
    {
        $self_token = $request->header('Self-Token');

        $self_views_key = "self_views_" . $self_token;
        $product_ids = Cache::get($self_views_key, []);

        $products = [];


        if (is_array($product_ids) && count($product_ids) > 0) {
            $product_ids = array_unique($product_ids);

            $product_db = $this->product->self();
            $products_all = $product_db->whereIn('id', $product_ids)->with('firstImage')->get();

            foreach ($products_all as $single_product) {
                $product = [
                    'seo_url' => $single_product->seo_url,
                    'image_url' => $single_product->firstImage->icon_size_directory ?? null
                ];

                $products[] = $product;
            }
        }


        return response()->json(compact('products'));

    }


    private function storeRecentViews($self_token, $product_id)
    {

        if ($self_token == null || $self_token == "")
            return false;

        $self_views_key = "self_views_" . $self_token;
        $existing_product_ids = Cache::get($self_views_key, []);
        $product_ids = [$product_id];


        if (is_array($existing_product_ids)) {
            $product_ids = array_merge($product_ids, $existing_product_ids);
        }

        // Cache::put($self_views_key, $product_ids, $product_ids);

        return Cache::remember($self_views_key, 18080, function () use ($product_ids) {
            return $product_ids;
        });
    }


    public function commentIndex(Request $request)
    {

        $product = $this->product->getByAny('seo_url', $request->seo_url)->first();

        $comments = [];

        if ($comments) {

            $product_id = $product->id;

            $attr = [
                'item_id' => $product_id,
                'comment_on' => 'products',
                'is_active' => 1
            ];

            $comments = $this->comment->getAll($attr);

        }

        return response()->json([
            'comments' => $comments
        ]);


    }


    public function commentStore(Request $request)
    {

        $user_id = $request->auth()->id();
        $product = $this->product->getByAny('seo_url', $request->seo_url)->first();
        $product_id = $product->id;
        $comment = $request->comment;

        $attr = [
            'user_id' => $user_id,
            'item_id' => $product_id,
            'comment_on' => 'products',
            'comment' => $comment,
            'is_active' => 1
        ];

        $store = $this->comment->create($attr);

        return response()->json([
            'success' => (boolean)$store
        ]);

    }


    public function commentUpdate(Request $request, $id)
    {

        $user_id = $request->auth()->id();
        $product = $this->product->getByAny('seo_url', $request->seo_url)->first();
        $product_id = $product->id;
        $comment = $request->comment->update;

        $attr = [
            'user_id' => $user_id,
            'item_id' => $product_id,
            'comment_on' => 'products',
            'comment' => $request->comment->update,
            'is_active' => 1
        ];

        $store = $this->comment->update($attr, $id);

        return response()->json([
            'success' => (boolean)$store
        ],);

    }


    public function commentDelete(Request $request, $id)
    {

        $user_id = $request->auth()->id();
        $product = $this->product->getByAny('seo_url', $request->seo_url)->first();
        $product_id = $product->id;
        $comment = $request->comment->delete;

        $id = [
            'user_id' => $user_id,
            'item_id' => $product_id,
            'comment_on' => 'products',
            'comment' => $request->comment->delete,
            'is_active' => 1

        ];

        $store = $this->comment->delete($id);

        return response()->json([
            'success' => (boolean)$store
        ]);


    }

    public function reviewStore(Request $request)
    {

        $user_id = auth()->id();
        $seo_url = $request->seo_url;
        $product = $this->product->getByAny('seo_url', $seo_url)->first();
        if ($product == null) {
            return false;
        }

        $reviews = [
            'user_id' => $user_id,
            'vendor_id' => 1,
            'product_id' => $product->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_active' => 1
        ];


        $rev = $this->review->create($reviews);

        return response()->json([
            'success' => (boolean)$rev,
            'result' => $rev
        ]);

    }

    // Review View Method here

    public function reviewView(Request $request)
    {

        $seo_url = $request->seo_url;
        $product = $this->product->getByAny('seo_url', $seo_url)->first();
        if ($product == null) {
            return false;
        }

        $db_review = $this->review->getByAny('product_id', $product->id);

        $reviews = [];
        $image_url_arr = [];
        foreach ($db_review as $review) {

            foreach ($review->reviewImages as $image) {
                $image_url_arr[] = $image->image_url;
            }
            $reviews[$review->id] = [
                'user_name' => $review->user->name ?? null,
                'image' => $image_url_arr,
                'time_ago' => Carbon::parse($review->created_at)->diffForHumans(),
                'rating' => $review->rating,
                'comment' => $review->comment
            ];
        }
        return response()->json(compact('reviews'));

    }


    // Review Update Method Here.....

    public function reviewUpdate(Request $request)
    {

        $user_id = auth()->id();

        $review_id = $request->id;
        if ($user_id == null && $review_id == null) {
            return false;
        }
        $review = $this->review->self()->where('user_id', $user_id)->where("id", $review_id)->first();
        if ($review != null) {
            $reviews = [
                'rating' => $request->rating,
                'comment' => $request->comment,
            ];


            $rev = $this->review->update($review_id, $reviews);

            return response()->json([
                'success' => (boolean)$rev,
                'result' => $rev
            ]);
        } else {
            return response()->json([
                'success' => false
            ]);
        }

    }

    public function reviewDelete(Request $request)
    {
        $user_id = auth()->id();
        $review_id = $request->id;
        if ($review_id == null && $user_id) {
            return false;
        }
        $result = $this->review->self()->where('user_id', $user_id)->where("id", $review_id)->delete();

        if ($result) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'error' => false
            ]);
        }

    }

    // Question Operation Store View Update Delete here....

    public function questionStore(Request $request)
    {
        $user_id = auth()->id();
        $seo_url = $request->seo_url;

        $product = $this->product->getByAny('seo_url', $seo_url)->first();
        if ($product == null) {
            return [
                'success' => false
            ];
        }

        $question = [
            'main_pid' => $product->id,
            'user_id' => $user_id,
            'vendor_id' => 1,
            'qa_type' => 1,
            'description' => $request->description,
            'is_active' => 0
        ];

        $result = $this->product_question->create($question);

        return response()->json([
            'success' => (boolean)$result,
            'result' => $result
        ]);
    }


    public function questionUpdate(Request $request)
    {
        $user_id = auth()->id();

        $question_id = $request->id;
        if ($user_id == null && $question_id == null) {
            return false;
        }

        $question = $this->product_question->self()->where('user_id', $user_id)->where("id", $question_id)->first();

        if ($question != null) {
            $questions = [
                'description' => $request->description,

            ];

            $resutlt = $this->product_question->update($question_id, $questions);

            return response()->json([
                'success' => (boolean)$resutlt,
                'result' => $resutlt
            ]);
        } else {
            return response()->json([
                'success' => false
            ]);
        }
    }

    public function questionDelete(Request $request)
    {
        $user_id = auth()->id();
        $question_id = $request->id;
        if ($question_id == null && $user_id) {
            return false;
        }
        $result = $this->product_question->self()->where('user_id', $user_id)->where("id", $question_id)->delete();

        if ($result) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'error' => false
            ]);
        }

    }

    public function questionIndex(Request $request)
    {
        $seo_url = $request->seo_url;
        $product = $this->product->getByAny('seo_url', $seo_url)->first();

        if ($product == null) {
            return false;
        }

        $limit = $request->limit;

        $questions = [];
        $db_questions = $this->product_question->self()
            ->orderBy('id', 'desc')
            ->where('main_pid', $product->id)
            ->where('qa_type', 1)
            ->where('is_active', true);

        if ($limit != null) {
            $db_questions->take($limit);
        }
        $db_questions = $db_questions->get();

        foreach ($db_questions as $question) {
            $answers = [];
            $db_ans = $question->answers;


            foreach ($db_ans as $ans) {
                $answers[] = [
                    'description' => $ans->description
                ];
            }

            $questions[] = [
                'username' => $question->user->name ?? '',
                'description' => $question->description,
                'time' => Carbon::parse($question->created_at)->diffForHumans(),
                'answers' => $answers,
            ];

        }

        return response()->json(compact('questions'));
    }


    public function imageUpload(Request $request)
    {


        $review_key = $request->review_key;
        $image = $request->hasFile('image');


        $success = false;
        if ($image) {
            $img_original_name = $request->file('image')->getClientOriginalExtension();
            $image_name = time() . '.' . $img_original_name;
            $storage_path = storage_path('app/public/reviewimages/');
            $image_url = $request->file('image')->move($storage_path, $image_name);
            $image_url = $storage_path . $image_name;

            if ($image_url) {
                $reviewImage_arr = [
                    'review_key' => $review_key,
                    'image_url' => $image_url,
                ];

                $success = $this->reviewImage->create($reviewImage_arr);
            }


        }
        $db_reviewImage = $this->reviewImage->getByAny('review_key', $review_key);
        foreach ($db_reviewImage as $image) {
            $reviewImages[] = [
                'review_key' => $image->review_key,
                'image_url' => $image->image_url,
                'created_at' => Carbon::parse($image->created_at)->diffForHumans()
            ];
        }

        if ($success == true) {
            return response()->json([
                'success' => true,
                'reviewimage' => $reviewImages,
                'message' => 'Upload Successfull!'
            ]);
        } else {
            return response()->json(compact('reviewImage_arr'))->with([
                'success' => false,
                'reviewimage' => $reviewImages,
                'message' => 'Something want wrong!'
            ]);
        }

    }


    public function productSetQty(Request $request)
    {

        $productId = $request->id;
        $proudctSetQty = $request->productSetQty;
        \App\Models\ProductSetProduct::where('product_set_id', $request->productSetId)->where('product_id', $productId)->update(["qty" => $proudctSetQty]);
        return response()->json([
            'Success' => "upload success"
        ]);
    }

    public function degreeImages(Request $request)
    {
        $seo_url = $request->seo_url;
        if ($seo_url) {
            $product = $this->product->self()->where('seo_url', $seo_url)->first();

            $degree_images_db = $product ? $product->threeSixtyDegreeImage : [];
            $degree_images = [];
            foreach ($degree_images_db as $degimg) {
                $degree_images[] = $degimg->image->full_size_directory ?? null;
            }

            return response()->json(compact('degree_images'));
        } else {
            return response()->json([
                'images' => []
            ]);
        }
    }


    public function getProductVariaton(Request $request)
    {
        $seo_url = $request->seo_url;
        $productvariations = [];
        $productVariationsList = [];
        $dbproduct = null;
        if ($seo_url) {
            $product_id = $this->product->self()->where('seo_url', $seo_url)->pluck('id');
            $db_productVariations = \App\Models\ProductVariation::where('product_id', $product_id)->get();

            foreach ($db_productVariations as $db_productVariation) {

                if ($db_productVariation->variation_product_id != null) {
                    $variationProduct = $this->product->getById($db_productVariation->variation_product_id);
                    $dbproduct = [
                        'title' => $variationProduct->title,
                        'sub_title' => $variationProduct->sub_title,
                        'first_image' => $variationProduct->firstImage,
                        'second_image' => $variationProduct->secondImage,
                        'seo_url' => $variationProduct->seo_url,
                        'product_price_now' => $variationProduct->product_price_now,
                        'local_selling_price' => $variationProduct->local_selling_price
                    ];

                }

                $productvariations[$db_productVariation->variation_group_id][] = [
                    'id' => $db_productVariation->id,
                    'product_id' => $db_productVariation->product_id,
                    'title' => $db_productVariation->title,
                    'image' => $db_productVariation->image->full_size_directory ?? '',
                    'variationProdcut' => $dbproduct

                ];
            }

            $var_group_data = \App\Models\VariationGroup::get()->keyBy('id');

            $final_variations = [];
            foreach ($productvariations as $key => $productvariation) {

                $var_group = $var_group_data[$key] ?? null;

                if ($var_group) {
                    $final_variations[$key] = [
                        'group' => [
                            'id' => $var_group->id,
                            'title' => $var_group->title,
                        ],
                        'variations' => $productvariation
                    ];
                }

            }

            $productVariationsList = $final_variations;

            return response()->json(compact('productVariationsList'));
        } else {
            return response()->json([
                'error' => 'Something missing!'
            ]);
        }
    }


    public function singleProductReview(Request $request)
    {
        $product_id = $request->id;
        $paginate = $request->paginate ?? 10;
        $getReviews = Review::with('user')->where('product_id', $product_id)->where('is_active', 1);
        $rating = $getReviews->sum('rating') ?? 0;
        return response()->json([
            'status' => 1,
            'data' => $getReviews->take($paginate)->get(),
            'count' => $getReviews->count(),
            'average_rating' => $getReviews->count() > 0 ? round($rating / $getReviews->count()) : 0,
        ]);
    }


    public function singleProductReviewStore(Request $request)
    {

        $attr = [
            'user_id' => $request->user_id,
            'product_id' => $request->product_id,
            'rating' => $request->rating,
            'comment' => $request->review,
            'vendor_id' => 1,
            'is_active' => 1,
        ];

        $done = Review::create($attr);
        return response()->json([
            'status' => $done ? true : false,
            'message' => $done ? 'Thank you for your feedback.' : 'Something is wrong. Please try again.'
        ]);
    }


    public function getProoductAttribute($product_id)
    {
//        dd($product_id);
        /*
        $get = ProductAttribute::getData($product_id);
        $value = [];
        if($get){
            $getAttr = [];
            foreach($get['data'] as $data){
                $decodeValue = json_decode($data->attr_value);
                if($data->attr_type == 'pre-defined'){
                    $v = explode(' | ', $data->attr_value);
                    $ex = [];
                    foreach($decodeValue as $d){
                        $d = explode(' | ', $d);
                        $ex []= $d[0];
                    }
                    $getAttr = $ex;
                }else {
                    $getAttr = $decodeValue;
                }
                $value [] = [
                    'attr_name' => $data->attr_name,
                    'attr_value' => $getAttr,
                ];
            }

        }
        */

        $get = ProductAttributeVariation::where('main_pid', $product_id)->where('is_active', 1)->get();
        $value = [];
//        dd($get);
        foreach ($get as $key => $data) {
//            dd($data->variation_image);
            $img = $data->variation_image ? \App\Models\Image::where('id', $data->variation_image)->first() : false;
            $img = $img ? $img->full_size_directory : false;
            $img = $img ? 'https://admin.regalfurniturebd.com/' . $img : false;

            $preselect = $key == 0 ? true : false;

            $value [$data->id] = [
                'is_first_selected' => $preselect,
                'variation_id' => $data->id,
                'main_pid' => $data->main_pid,
                'main_pcode' => $data->main_pcode,
                'variations' => json_decode($data->variations),
                'product_regular_price' => $data->product_regular_price ?? 0,
                'product_selling_discount' => $data->product_selling_price ?? 0,
                'product_price_now' => !empty($data->product_selling_price) ? $data->product_regular_price - ($data->product_regular_price * $data->product_selling_price / 100) : $data->product_regular_price,
                'save_price' => !empty($data->product_selling_price) ? $data->product_regular_price * $data->product_selling_price / 100 : 0,
                'variation_product_code' => $data->variation_product_code,
                'variation_sub_title' => $data->variation_sub_title,
                'variation_image' => $img ?? false,
                'is_active' =>$data->is_active,
                'disable_buy' =>$data->disable_buy,
            ];
        }
        return response()->json($value);
        //dd(response()->json($value));
    }


    public function getProoductVariation($main_pid)
    {
//        $cache_key = 'get_variation_'.$main_pid;
//        $data = Cache::remember($cache_key, 86490, function () use ($main_pid) {
        $product = Product::where('id', $main_pid)->first();
        $fixedVariation = ProductAttribute::where('product_id', $main_pid)->where('for_this', 'attribute')->orderBy('id', 'desc')->first()->attr_value ?? null;
        $fixedVariation = $fixedVariation ? array_column(json_decode($fixedVariation), 'fixed_variation') : null;
        $fixedVariation = $fixedVariation ? $fixedVariation[0] : null;
        //        dd($fixedVariation);
        $variation = ProductAttributeVariation::where('main_pid', $main_pid)->where('is_active', 1);
        $r = request();
        $getVariationKeyVariation = ProductAttributeVariation::where('main_pid', $main_pid)->where('is_active', 1)->where("variations->{$r->variation_key}->index", $r->variation_key);

        if ($r->base_value) {
            $getVariationKeyVariation->where('main_pid', $main_pid)->where('is_active', 1)->where("variations->{$fixedVariation}->value", $r->base_value);
        }


        if ($r->variation_product_code) {
            //            dd($r->variation_key);
            $variation = $variation->where("variations->{$r->variation_key}->value", $r->variation_value);
        }

        $variation = $variation->get() ?? false;
        $vb = [];
        foreach ($getVariationKeyVariation->get() as $va) {
            $va = json_decode($va->variations);
            $va = $va;
            foreach ($va as $key => $v) {
                if ($key == $r->variation_key) {
                    $vb [] = $v;
                }
            }
        }
        $newArr = collect($vb)->unique('value');
        //        dump();
        //        dd($variation);
        //        exit();
        if ($variation) {
            //New

            $result = [];
            $tempArr = [];
            $mkVariationImgArray = [];
            foreach ($variation as $var) {

                //img
                //                dump($var);
                $variationImages = $var->variation_product_code == $r->variation_product_code ? $var->variation_image : null;

                //                $variationImages = $var->variation_image ?? null;
                $variationImages = $variationImages ? explode('|', $variationImages) : [];
                $serialImage = $variationImages;
//                    dd($variationImages);
                $variationImages = \App\Models\Image::whereIn('id', $variationImages);
                if($serialImage){
                    $variationImages=  $variationImages->orderByRaw(DB::raw("FIELD(id, " . implode(',', $serialImage) . ")"));
                }
                $variationImages = $variationImages->get() ?? [];
                foreach ($variationImages as $im) {
                    $mkVariationImgArray [] = [
                        'id' => $im->id,
                        'url' => 'https://admin.regalfurniturebd.com/' . $im->full_size_directory,
                    ];
                }
                //end

                $jsonDecoded = json_decode($var->variations);
                $jsonDecoded = (array)$jsonDecoded;
                $jsonDecoded = collect($jsonDecoded)->sortBy('sort');
                //                dd($jsonDecoded);
                foreach ($jsonDecoded as $key => $data) {
                    if ($data->show_as == 'Image') {
                        $imageLink = \App\Models\Image::where('id', $data->value)->first()->full_size_directory;
                        $imageLink = 'https://admin.regalfurniturebd.com/' . $imageLink;
                        $data = (object)[
                            'link' => $imageLink,
                            'value' => $data->value,
                            'show_as' => 'Image',
                            'sort' => $data->sort
                        ];
                    }
                    if ($r->variation_key && $key == $r->variation_key && $r->sort == 2) {
                        $tempArr[$key] = [];
                        //                        $tempArr[$key] [] = $data;
                    } else {
                        $tempArr[$key] [] = $data;
                    }
                }
            }
            //            exit();
            //            dd($tempArr);
            //Make unique
            $super_unique = function ($array, $key) {
                $temp_array = [];
                foreach ($array as &$v) {
                    $v = (array)$v;
                    //                    dump($temp_array);
                    if (!isset($temp_array[$v[$key]]))
                        $temp_array[$v[$key]] =& $v;
                }
                $array = array_values($temp_array);
                return $array;
            };

            $i = 0;
            if ($r->variation_key && $r->sort == 2) {
                $tempArr = array_merge_recursive($tempArr, [$r->variation_key => $newArr->toArray()]);
                $tempArr = collect($tempArr)->sortBy('sort');
            }
            //            dd($tempArr);
            foreach ($tempArr as $key => $d) {
                //                $value= array_values(array_unique($d));
                $value = $super_unique((array)$d, 'value');
                $value = collect($value);
                $value->put('fixed_variation', false);
                if ($key == $fixedVariation) {
                    $value->put('fixed_variation', 1);
                }
                $valueArray = $value->toArray();
                //                dump($valueArray);

                $i++;
                if ($r->variation_product_code) {
                    if ($i != 1) {
                        $result[$key] = $value->toArray();
                    }
                } else {
                    if ($i == 1) {
                        $result[$key] = $value->toArray();
                    }

                    //                    $result[$key] = $valueArray;
                }


            }
            //            return 1;
            //            dd($result);
            //            $keys = array_column($result, 'fixed_variation');

            //            array_multisort($keys, SORT_DESC, $result);

            //            dd($result);
            if ($r->sort == 1) {
                if ($product->variation_layer_start == 1) {

                } else {
                    $result = array_slice($result, 0, $r->sort);
                }
            }
            //           exit();
            //            dd($result);
            $data = [
                'status' => 0,
                'data' => $result, //$variation,
                'variation_img' => $mkVariationImgArray ?? false,
                'count' => count($variation) ?? 0,
            ];

        } else {
            $data = [
                'status' => 1,
                'data' => false,
                'variation_img' => false,
                'count' => 0,
            ];
        }
//            return $data;
//        });
        return response()->json($data);
    }




    public function getCustomerSelectProductVariation(Request $request)
    {
        //return $request->variation;

        //if ($request->variation) {
//        $jk = [
//            'Size' => [
//                "value" => "Medium",
//                "show_as" => "Text"
//            ],
//            'Material' => [
//                "value" => "Wooden",
//                "show_as" => "Text"
//            ],
//        ];
//        dd($jk);
//        $cache_key = 'customer_select_variation_'.$request->product_id;
//        $data = Cache::remember($cache_key, 86490, function () use ($request) {
        $jk = $request->variation;
        //        dd($jk);
        $q = ProductAttributeVariation::where('main_pid', $request->product_id)->where('is_active', 1);
        //        return $request->variation['Size']['value'];

        if ($jk) {
            $q = $q->where(function ($query) use ($jk) {
                foreach ($jk as $k => $d) {
                    //                    $query->where(DB::raw("json_extract(variations, '$.$k')"), $d);
                    //$query->where(DB::raw("json_extract(variations, '$.$k')"), $d);
                    $query->where("variations->{$k}->value", $d['value']);
                }
            });
        }
        //        exit();
        $q = $q->orderBy('id', 'desc')->first();
        //        return $q;
        if ($q) {
            $data = $q;
            $datavariation = json_decode($data->variations);
            //            return $datavariation;
            $datavariations = [];
            foreach ($datavariation as $key => $item) {
                $datavariations [$item->sort] = [
                    'label' => $key,
                    'show_as' => $item->show_as,
                    'sort' => $item->sort,
                    'value' => $item->value,
                ];
            }
            $datavariations = collect($datavariations)->sort();
            $data = [
                'is_first_selected' => null,
                'variation_id' => $data->id,
                'main_pid' => $data->main_pid,
                'main_pcode' => $data->main_pcode,
                'variations' => $datavariations, //json_decode($data->variations),
                'product_regular_price' => $data->product_regular_price ?? 0,
                'product_selling_discount' => $data->product_selling_price ?? 0,
                'product_price_now' => !empty($data->product_selling_price) ? $data->product_regular_price - ($data->product_regular_price * $data->product_selling_price / 100) : $data->product_regular_price,
                'save_price' => !empty($data->product_selling_price) ? $data->product_regular_price * $data->product_selling_price / 100 : 0,
                'variation_product_code' => $data->variation_product_code,
                'variation_sub_title' => $data->variation_sub_title,
                'variation_image' => $img ?? false,
                'is_active' =>$data->is_active,
                'disable_buy' =>$data->disable_buy,
            ];
        } else {
            $data = [
                'status' => '203'
            ];
        }
//            return $data;
//        });
        return response()->json($data ?? false);


//        $json = json_encode($request->variation);
//        $json = json_encode($jk);
//
//        DB::raw('SELECT * FROM product_attribute_variations WHERE main_pid = $request->product_id AND  JSON_EXTRACT(variations,\'$.$key\') = "$data" AND  JSON_EXTRACT(variations,'$.Size') = "L"')
//            $sql = DB::raw("SELECT * FROM product_attribute_variations WHERE main_pid = 1619 AND  JSON_EXTRACT(variations) = $json");
//        $sql = DB::select("SELECT * FROM product_attribute_variations WHERE main_pid = 1619 AND  JSON_EXTRACT(variations,'$.Material') = 'Wooden' AND  JSON_EXTRACT(variations,'$.Size') = 'L'");
//        $s = ProductAttributeVariation::where('main_pid', '1619')->where(function ($query) use ($jk) {
//            return $query->select(DB::raw("JSON_EXTRACT(variations,'$.Material') = 'Wooden'"));
//                foreach ($jk as $k => $d){
//                    $query->select(DB::raw("JSON_EXTRACT(variations,'$.$k') = $d"));
//                }
//        })->toSql();
//        dd($s);
        //}
    }

    //Not used
    public function getProoductVariationAnother($main_pid, array $options = array())
    {
        $default = [

        ];

        $new_search_criteria = array_merge($default, $options);

        $variation = ProductAttributeVariation::where('main_pid', $main_pid)->get()->toArray();

        $search_value = array_search("Wooden", $variation);

        dd($search_value);
        if ($variation) {
            $data = [
                'status' => 0,
                'data' => $variation,
            ];

        } else {
            $data = [
                'status' => 1,
                'data' => false,
            ];
        }

        return response()->json($data);
    }

}