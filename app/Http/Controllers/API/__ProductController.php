<?php

namespace App\Http\Controllers\API;

use App\Action\FlashSale;
use App\Action\ProductAttribute;
use App\Action\ProductVariation;
use App\Action\RecentViews;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\ProductAttributesData;
use App\Models\ProductAttributeVariation;
use App\Models\ProductSetInfo;
use App\Models\Review;
use App\Repositories\Comment\CommentInterface;
use App\Repositories\Product\ProductInterface;
use App\Repositories\ProductSet\ProductSetInterface;
use App\Repositories\ProductQuestion\ProductQuestionInterface;
use App\Repositories\Review\ReviewInterface;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\ProductSetFabric;

use App\Models\Image;
use App\Models\ProductSetProduct;
use App\Repositories\Term\TermInterface;
use App\Models\ProductQuestion;
use App\Repositories\ReviewImage\ReviewImageInterface;
use App\Repositories\SessionData\SessionDataInterface;
use phpDocumentor\Reflection\Types\Boolean;
use App\Repositories\User\UserInterface;
use Exception;
use DB;
use App\Models\ProductCategories;

class ProductController extends Controller
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

    public function simpleInfo(
        Request          $request,
        FlashSale        $flashSale,
        RecentViews      $recentViews,
        ProductAttribute $productAttribute,
        ProductVariation $productVariation
    ): JsonResponse {
        $cache_key = 'product-simple-info-'.$request->input('seo_url');
        $product = Cache::remember($cache_key, 86400, function () use (
            $flashSale, $recentViews, $productAttribute, $productVariation, $request
        ) {
            $seo_url = $request->input('seo_url');
            $product = $this->product->getByAny('seo_url', $seo_url)->first();

            if (!$product || $product->is_active != 1) {
                return null;
            }

            $category = $product->category()->where('is_attgroup_active', 1)->first();

            $have_flash = $flashSale->handle($product->id);

            if ($product->enable_variation == 'on') {
                $product->load('attribute_variations');
                $attributes = $productAttribute->handle($product->attribute_variations, $product->fixedVariation());
                $v_combinations = $productVariation->handle($product->id);
            } else {
                $attributes = false;
                $v_combinations = false;
            }

            return ProductResource::make($product)->additional([
                'main_category' => $category,
                'variation' => $attributes,
                'have_flash' => $have_flash,
                'combinations' => $v_combinations,
            ]);
        });

        if (isset($product['id'])) {
            $recentViews->handle($request->header('Self-Token'), $product['id']);
        }

        if ($request->input('cache') == 'clear') {
            Cache::forget($cache_key);
        }

        return response()->json(['product' => $product]);
    }

    public function getCustomerSelectProductVariation(Request $request): JsonResponse
    {
        $variation = ProductAttributeVariation::query()
            ->where('main_pid', $request->input('product_id'))
            ->where('is_active', 1);

        if ($request->input('variation')) {
            $variation = $variation->where(function ($query) use ($request) {
                foreach ($request->input('variation') as $key => $variation) {
                    $query->where("variations->{$key}->value", $variation['value']);
                }
            });
        }

        $variation = $variation->orderBy('id', 'desc')->first();

        if ($variation) {
            $data_variation = json_decode($variation->variations);
            $variations = [];

            foreach ($data_variation as $key => $item) {
                $variations [$item->sort] = [
                    'label' => $key,
                    'show_as' => $item->show_as,
                    'sort' => $item->sort,
                    'value' => $item->value,
                ];
            }
            $variations = collect($variations)->sort();
            $data = [
                'is_first_selected' => null,
                'variation_id' => $variation->id,
                'main_pid' => $variation->main_pid,
                'main_pcode' => $variation->main_pcode,
                'variations' => $variations, //json_decode($data->variations),
                'product_regular_price' => $variation->product_regular_price ?? 0,
                'product_selling_discount' => $variation->product_selling_price ?? 0,
                'product_price_now' => !empty($variation->product_selling_price) ? $variation->product_regular_price - ($variation->product_regular_price * $variation->product_selling_price / 100) : $variation->product_regular_price,
                'save_price' => !empty($variation->product_selling_price) ? $variation->product_regular_price * $variation->product_selling_price / 100 : 0,
                'variation_product_code' => $variation->variation_product_code,
                'variation_sub_title' => $variation->variation_sub_title,
                'variation_image' => $img ?? false,
                'is_active' => $variation->is_active,
                'disable_buy' => $variation->disable_buy,
            ];
        }
        return response()->json(['data' => $data ?? false]);
    }

    public function getProductVariations($product_id, ProductVariation $productVariation): JsonResponse
    {
        $value = $productVariation->handle($product_id);
        return response()->json($value);
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

    public function image(Request $request): JsonResponse
    {
        $seo_url = $request->input('seo_url');
        $cache_key = 'product-images-videos-degrees-'.$seo_url;

        $allData = Cache::remember($cache_key, 86400, function () use ($seo_url) {
            $product = $this->product->getByAny('seo_url', $seo_url)->first();
            
            $images = [];
            $degree_images = [];
            $variation_youtube_links = [];
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
                    $imageIncludes[] = $single_image->id;
                }

                foreach ($product->images as $single_image) {
                    if (in_array($single_image->id, $imageIncludes)) {
                        continue;
                    }

                    $img = [
                        'id' => $single_image->id,
                        'full_size_directory' => $single_image->full_size_directory,
                        'icon_size_directory' => $single_image->icon_size_directory
                    ];

                    $images[] = $img;
                }

                if(!empty($product->attribute_variations)) {
                    foreach($product->attribute_variations as $variation) {                        
                        $variation_youtube_links[] = [
                            'id' => $variation->id,
                            'variation_video' => $variation->variation_video
                        ];
                    }
                }

                $youtubeLink = $product->youtubeVideo->link ?? null;              

                $degree_images_db = $product->threeSixtyDegreeImage;
                foreach ($degree_images_db as $degimg) {
                    $degree_images[] = $degimg->image->full_size_directory ?? null;
                }

                $ar = $product->ar->image ?? null;
            }

            //dd($variation_youtube_links);
            return compact('images', 'youtubeLink', 'degree_images', 'ar', 'variation_youtube_links');
        });


        if ($request->input('cache') == 'clear') {
            Cache::forget($cache_key);
        }

        return response()->json($allData);

    }

    public function info(Request $request, FlashSale $flashSale): JsonResponse
    {
        $cache_key = 'product-product-info-'.$request->input('seo_url');
        $product = Cache::remember($cache_key, 86400, function () use ($flashSale, $request) {

            $pr_info = $this->product->getByAny('seo_url', $request->input('seo_url'))->first();
            $product = [];

            if ($pr_info && $pr_info->is_active == 1) {

                $attribute_data = ProductAttributesData::query()
                    ->leftJoin('attributes', function ($join) {
                        $join->on('productattributesdata.attribute_id', '=', 'attributes.id');
                    })->where('main_pid', $pr_info->id)
                    ->get();

                $categories = $this->product->getProductCategories($pr_info->id);
                $have_flash = $flashSale->handle($pr_info->id);

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

    public function questionIndex(Request $request)
    {
        $seo_url = $request->input('seo_url');
        $product = $this->product->getByAny('seo_url', $seo_url)->first();

        if ($product == null) {
            return false;
        }

        $limit = $request->input('limit');

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

    public function commentIndex(Request $request): JsonResponse
    {
        $product = $this->product->getByAny('seo_url', $request->input('seo_url'))->first();

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

    public function commentStore(Request $request): JsonResponse
    {

        $user_id = $request->auth()->id();
        $product = $this->product->getByAny('seo_url', $request->input('seo_url'))->first();
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

    public function commentUpdate(Request $request, $id): JsonResponse
    {
        $user_id = $request->auth()->id();
        $product = $this->product->getByAny('seo_url', $request->input('seo_url'))->first();
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

    public function commentDelete(Request $request, $id): JsonResponse
    {
        $user_id = $request->auth()->id();
        $product = $this->product->getByAny('seo_url', $request->input('seo_url'))->first();
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

    public function singleProductReview(Request $request): JsonResponse
    {
        $product_id = $request->id;
        $paginate = $request->paginate ?? 10;
        $getReviews = Review::query()->with('user')
            ->where('product_id', $product_id)
            ->where('is_active', 1);
        $rating = $getReviews->sum('rating') ?? 0;
        return response()->json([
            'status' => 1,
            'data' => $getReviews->take($paginate)->get(),
            'count' => $getReviews->count(),
            'average_rating' => $getReviews->count() > 0 ? round($rating / $getReviews->count()) : 0,
        ]);
    }

    public function singleProductReviewStore(Request $request): JsonResponse
    {
        $attr = [
            'user_id' => $request->user_id,
            'product_id' => $request->product_id,
            'rating' => $request->rating,
            'comment' => $request->review,
            'vendor_id' => 1,
            'is_active' => 1,
        ];

        $done = Review::query()->create($attr);
        return response()->json([
            'status' => (bool)$done,
            'message' => $done ? 'Thank you for your feedback.' : 'Something is wrong. Please try again.'
        ]);
    }

    public function similarProducts(Request $request)
    {
        $cache_key = 'product-similar-product'.$request->seo_url;

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

    public function otherAlsoSee(Request $request)
    {

        $cache_key = 'product-other-see-products-'.$request->seo_url;
        $products = Cache::remember('product-other-see-products-'.$request->seo_url, 86400, function () use ($request) {

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

    public function goesWellWith(Request $request)
    {


        $cache_key = 'product-goes-well-products-'.$request->seo_url;
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

    public function sameCategoryProductList(Request $request)
    {

        $cache_key = 'product-same-cat-products-'.$request->seo_url;
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
}