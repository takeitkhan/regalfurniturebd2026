<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\HomeSetting;
use App\Models\Term;
use App\Repositories\Dashboard\DashboardInterface;
use App\Repositories\FlashItem\FlashItemInterface;
use App\Repositories\FlashShedule\FlashSheduleInterface;
use App\Repositories\HomeSetting\HomeSettingInterface;
use App\Repositories\Media\MediaInterface;
use App\Repositories\Post\PostInterface;
use App\Repositories\Product\ProductInterface;
use App\Repositories\ProductSet\ProductSetInterface;
use App\Repositories\Setting\SettingInterface;
use Illuminate\Http\Request;
use App\Repositories\Slider\SliderInterface;
use App\Repositories\TagGallery\TagGalleryInterface;
use App\Repositories\Term\TermInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{

    private $slider;
    private $dashboard;
    private $home_setting;
    private $product;
    private $post;
    private $media;
    private $tag_gallery;
    private $term;
    private $product_set;
    private $flash_schedule;
    private $flash_item;
    private $setting;

    public function __construct(
        SliderInterface       $slider,
        DashboardInterface    $dashboard,
        HomeSettingInterface  $home_setting,
        SettingInterface      $setting,
        ProductInterface      $product,
        PostInterface         $post,
        MediaInterface        $media,
        TagGalleryInterface   $tag_gallery,
        TermInterface         $term,
        ProductSetInterface   $product_set,
        FlashSheduleInterface $flash_schedule,
        FlashItemInterface    $flash_item
    )
    {
        $this->slider = $slider;
        $this->dashboard = $dashboard;
        $this->home_setting = $home_setting;
        $this->product = $product;
        $this->post = $post;;
        $this->media = $media;
        $this->tag_gallery = $tag_gallery;
        $this->term = $term;
        $this->product_set = $product_set;

        $this->flash_schedule = $flash_schedule;
        $this->flash_item = $flash_item;
        $this->setting = $setting;
    }


    public function test(Request $request)
    {


        return Cache::remember('test', 900, function () use ($request) {

            return $this->slider->getByFilter([
                'active' => true,
                'device' => $request->device ?? 0,
                'type' => $request->type ?? 0
            ]);

        });
    }

    public function settings(Request $request)
    {
        $cache_key = "settings";
        $settings = $this->setting->getById(1);

        if ($request->cache == 'clear') {
            Cache::forget($cache_key);
        }

        return response()->json([
            'setting' => $settings
        ]);
    }

    public function sliders(Request $request)
    {

        $cache_key = 'home-sliders-' . ($request->type ?? 0) . '-' . ($request->device ?? 0);

        $sliders = Cache::remember($cache_key, 86400, function () use ($request) {

            return $this->slider->getByFilter([
                'active' => true,
                'device' => $request->device ?? 0,
                'type' => $request->type ?? 0
            ]);
        });

        if ($request->cache == 'clear') {
            Cache::forget($cache_key);
        }

        return response()->json([
            'request' => $request->all(),
            'sliders' => $sliders
        ]);
    }


    public function topCategory(Request $request)
    {

        $cache_key = "home-top-category";
        $requests = $request->all();
        $category = Cache::remember($cache_key, 86400, function () use ($request) {
            $cats_data = HomeSetting::first()->home_category;


            $category = [];
            if ($cats_data != null) {
                $cats = explode('|', $cats_data);

                $category_db = Term::whereIn('id', $cats)->with('home_img')->get()->keyBy('id');

                $category = [];

                foreach ($cats as $cat) {
                    $cat = $category_db[$cat] ?? false;

                    if ($cat) {
                        $category[] = $cat;
                    }
                }
            }

            return $category;
        });

        if ($request->cache == 'clear') {
            Cache::forget($cache_key);
        }

        return response()->json(compact('requests', 'category'));
    }


    public function topOffers(Request $request)
    {
        $cache_key = "home-offers";
        $products = Cache::remember($cache_key, 86400, function () {

            return $this->product->getByFilter([
                'recommended' => "on",
            ], [
                "recommended_serial" => "asc"
            ], 4);
        });

        if ($request->cache == 'clear') {
            Cache::forget($cache_key);
        }

        return response()->json(compact('products'));
    }

    public function prebookings(Request $request)
    {

        $cache_key = "home-prebookings";
        $allVars = Cache::remember($cache_key, 86400, function () {

            $info = $this->dashboard->getById(15);
            $products = [];

            if ($info && $info->is_active) {

                $products = $this->product->getByFilter(['pre_booking' => 1,
                ], [
                    "id" => "desc"
                ], 4);
            }

            $allVars = compact('info', 'products');

            return $allVars;
        });

        if ($request->cache == 'clear') {
            Cache::forget($cache_key);
        }
        return response()->json($allVars);
    }


    public function newArrivals(Request $request)
    {
        $cache_key = "home-new-arrivals";
        $products = Cache::remember($cache_key, 86400, function () {

            return $this->product->getByFilter([
                'new_arrival' => "on"
            ], [
//                "new_arrival_serial" => "desc"
                "id" => "desc"
            ], 15);

        });


        if ($request->cache == 'clear') {
            Cache::forget($cache_key);
        }

        return response()->json(compact('products'));
    }


    public function flashSales(Request $request)
    {


        $flash_sale = Cache::remember('home-flash-sale', 900, function () use ($request) {

            $flash_schedule = $this->flash_schedule->currentFlash();
            $flash_items = [];

            if ($flash_schedule) {
                $flash_items = $this->flash_item->getByAny('fi_shedule_id', $flash_schedule->id);
            }


            $allVars = [
                'flash_schedule' => $flash_schedule,
                'flash_items' => $flash_items
            ];

            return $allVars;

        });


        if ($request->cache == 'clear') {
            Cache::forget('home-flash-sale');
        }

        return response()->json($flash_sale);
    }

    public function tagGallary(Request $request)
    {

        $cache_key = 'home-tag-gallery-' . $request->seo_url . "page-" . $request->page;
        $allVars = Cache::remember($cache_key, 86400, function () use ($request) {

            $page = $request->page ?? 1;
            $page = $page < 1 ? 1 : $page;
            $limit = $page * 10;

            $category = null;
            if ($request->seo_url) {
                $category = $this->term->self()->where('seo_url', $request->seo_url)->first();
            }

            $tag_gallaryz = $this->tag_gallery->getByFilter([], ['id' => 'desc'], $limit);

            $tag_gallary = [];
            $terms = [];

            foreach ($tag_gallaryz as $tg) {

                if ($category == null) {
                    $tag_gallary[] = [
                        'url' => $tg->url,
                        'url_type' => $tg->url_type,
                        'term_name' => $tg->term->name ?? '',
                        'term_url' => $tg->term->seo_url ?? '',
                        'image_url' => $tg->image->full_size_directory,
                    ];

                }

                $terms[$tg->term->id ?? 0] = [
                    'term_name' => $tg->term->name ?? '',
                    'term_url' => $tg->term->seo_url ?? ''
                ];
            }

            $terms = array_slice($terms, 0, 7);

            if ($category != null) {
                $tag_gallaryz = $this->tag_gallery->getByFilter([
                    'category_id' => $category->id
                ], [
                    'id' => 'desc'
                ], 10);

                $tag_gallary = [];


                foreach ($tag_gallaryz as $tg) {
                    $tag_gallary[] = [
                        'url' => $tg->url,
                        'url_type' => $tg->url_type,
                        'term_name' => $tg->term->name ?? '',
                        'term_url' => $tg->term->seo_url,
                        'image_url' => $tg->image->full_size_directory,
                    ];
                }

            }

            $allVars = compact('terms', 'tag_gallary');

            return $allVars;
        });

        if ($request->cache == 'clear') {
            Cache::forget($cache_key);
        }
        return response()->json($allVars);
    }

    public function newsEvents(Request $request)
    {
        $cache_key = 'home-news-events-' . $request->limit;

        $postsArr = Cache::remember($cache_key, 86400, function () use ($request) {
            $postz = $this->post->getByArr([
                'categories' => 661,
                'limit' => $request->limit ? $request->limit : 10
            ]);

            $totalData = $this->post->getByArr([
                'categories' => 661,
            ]);
            $dataInfo =  [
                'total_count' => count($totalData),
            ];

            $posts = [];

            foreach ($postz as $post) {

                $image_url = null;
                $imgIds = explode(",", $post->images);
                if (count($imgIds) > 0) {
                    $media = $this->media->getByAny('id', $imgIds[0])->first();
                    if ($media) {
                        $image_url = $media->full_size_directory;
                    }
                }

                $posts[] = [
                    'id' => $post->id,
                    'title' => $post->title,
                    'seo_url' => $post->seo_url,
                    'image_url' => $image_url,
                    'short_description' => $post->short_description,
                    'created_at' => $post->created_at->format('d F Y')
                ];
            }
//                return $posts;
                return [
                    'posts' => $posts,
                    'dataInfo' => $dataInfo,
                ];
            });
            $dataInfo = $postsArr['dataInfo'];
            $posts = $postsArr['posts'];
            return response()->json(compact('posts', 'dataInfo'));

        /*

        $cache_key = 'home-news-events-' . $request->limit;
        $posts = Cache::remember($cache_key, 86400, function () use ($request) {

            $postz = $this->post->getByArr([
                'categories' => 661,
                'limit' => $request->limit ? $request->limit : 10
            ]);


            $posts = [];


            foreach ($postz as $post) {

                $image_url = null;
                $imgIds = explode(",", $post->images);
                if (count($imgIds) > 0) {
                    $media = $this->media->getByAny('id', $imgIds[0])->first();
                    if ($media) {
                        $image_url = $media->full_size_directory;
                    }
                }

                $posts[] = [
                    'title' => $post->title,
                    'seo_url' => $post->seo_url,
                    'image_url' => $image_url,
                    'short_description' => $post->short_description
                ];
            }

            return $posts;
        });

        if ($request->cache == 'clear') {
            Cache::forget($cache_key);
        }

        return response()->json(compact('posts'));
        */
    }


    public function blogPost(Request $request)
    {
//        $cache_key = 'home-news-events-' . $request->limit;
        $cache_key = 'home-blogs-' . $request->limit;

        $postsArr = Cache::remember($cache_key, 86400, function () use ($request) {
            $postz = $this->post->getByArr([
                'categories' => 753,
                'limit' => $request->limit ? $request->limit : 10
            ]);

            $totalData = $this->post->getByArr([
                'categories' => 753,
            ]);
            $dataInfo =  [
                'total_count' => count($totalData),
            ];

            $posts = [];


            foreach ($postz as $post) {

                $image_url = null;
                $imgIds = explode(",", $post->images);
                if (count($imgIds) > 0) {
                    $media = $this->media->getByAny('id', $imgIds[0])->first();
                    if ($media) {
                        $image_url = $media->full_size_directory;
                    }
                }

                $posts[] = [
                    'id' => $post->id,
                    'title' => $post->title,
                    'seo_url' => $post->seo_url,
                    'image_url' => $image_url,
                    'short_description' => $post->short_description,
                    'created_at' => $post->created_at->format('d F Y')
                ];
            }

//            return response()->json(compact('posts', 'dataInfo'));
//            return $posts;
            return [
                'posts' => $posts,
                'dataInfo' => $dataInfo,
            ];
        });

        if ($request->cache == 'clear') {
            Cache::forget($cache_key);
        }
//        return $postsArr;
        $dataInfo = $postsArr['dataInfo'];
        $posts = $postsArr['posts'];
//        return response()->json(compact('posts'));
        return response()->json(compact('posts', 'dataInfo'));
        /*

        $cache_key = 'home-news-events-' . $request->limit;
        $posts = Cache::remember($cache_key, 86400, function () use ($request) {

            $postz = $this->post->getByArr([
                'categories' => 661,
                'limit' => $request->limit ? $request->limit : 10
            ]);


            $posts = [];


            foreach ($postz as $post) {

                $image_url = null;
                $imgIds = explode(",", $post->images);
                if (count($imgIds) > 0) {
                    $media = $this->media->getByAny('id', $imgIds[0])->first();
                    if ($media) {
                        $image_url = $media->full_size_directory;
                    }
                }

                $posts[] = [
                    'title' => $post->title,
                    'seo_url' => $post->seo_url,
                    'image_url' => $image_url,
                    'short_description' => $post->short_description
                ];
            }

            return $posts;
        });

        if ($request->cache == 'clear') {
            Cache::forget($cache_key);
        }

        return response()->json(compact('posts'));
        */
    }

    // Blog Details Method

    public function blogDetails(Request $request)
    {

        $cache_key = 'home-blog-details-' . ($request->seo_url);
//        $post = Cache::remember($cache_key, 86400, function () use ($request) {
//
//
//            $db_post = $this->post->getByAny('seo_url', $request->seo_url)->first();
//            $imagesurl = [];
//            $post = null;
//
//            if ($db_post) {
//
//                $imageIds = explode(",", $db_post->images);
//                $images = $this->media->self()->whereIn('id', $imageIds)->get();
//
//                foreach ($images as $image) {
//                    $imagesurl[] = $image->full_size_directory;
//                }
//
//
//                $date_time = Carbon::parse($db_post->created_at)->diffForHumans();
//
//                $post = [
//                    'id' => $db_post->id,
//                    'title' => $db_post->title,
//                    'sub_title' => $db_post->sub_title,
//                    'images' => $imagesurl,
//                    'image' => $imagesurl[0] ?? null,
//                    'description' => $db_post->description,
//                    'short_description' => $db_post->short_description,
//                    'time' => $date_time
//                ];
//
//            }
//
//            return $post;
//
//        });
//
//        if ($request->cache == 'clear') {
//            Cache::forget($cache_key);
//        }
        $post = Cache::remember($cache_key, 86400, function () use ($request) {
        $db_post = $this->post->getByAny('seo_url', $request->seo_url)->first();
        $imagesurl = [];
        $post = null;

        if ($db_post) {

            $imageIds = explode(",", $db_post->images);
            $images = $this->media->self()->whereIn('id', $imageIds)->get();

            foreach ($images as $image) {
                $imagesurl[] = $image->full_size_directory;
            }


            $date_time = Carbon::parse($db_post->created_at)->diffForHumans();

            $post = [
                'id' => $db_post->id,
                'title' => $db_post->title,
                'sub_title' => $db_post->sub_title,
                'images' => $imagesurl,
                'image' => $imagesurl[0] ?? null,
                'description' => $db_post->description,
                'short_description' => $db_post->short_description,
                'time' => $date_time
            ];

        }
            return $post;
        });
        return response()->json(compact('post'));
    }

    //Returning Term/Categories

    public function allTerms(Request $request)
    {

        $cache_key = 'home-all-terms';
        $terms = Cache::remember($cache_key, 86400, function () use ($request) {

            $alltarms = $this->term->getByAny('parent', 1);
            $terms = [];
            foreach ($alltarms as $t) {
                $terms[$t->id] = [
                    'id' => $t->id,
                    'name' => $t->name,
                    'seo_url' => $t->seo_url,
                    'home_image' => $t->home_image->full_size_directory ?? null,
                    'page_image' => $t->page_image->full_size_directory ?? null
                ];
            }

            return $terms;
        });


        if ($request->cache == 'clear') {
            Cache::forget($cache_key);
        }


        return response()->json(compact('terms'), 200);
    }

    public function productSet(Request $request)
    {

        $cache_key = "home-product-set";
        $product_sets = Cache::remember('home-product-set', 86400, function () {

            $product_sets = [];
            $db_productSet = $this->product_set->self()->where('active', true)->take(10)->orderBy('id', 'desc')->get();
            foreach ($db_productSet as $productSet) {
                $product_ids = explode(",", $productSet->product_ids);
                $product_price = $this->product->self()->whereIn('id', $product_ids)->get()->sum('product_price_now');

                $product_sets[] = [
                    'title' => $productSet->title,
                    'slug' => $productSet->slug,
                    'image_url' => $productSet->image->full_size_directory ?? null,
                    'price' => $product_price
                ];

            }

            return $product_sets;

        });

        if ($request->cache == 'clear') {
            Cache::forget($cache_key);
        }

        return response()->json(compact('product_sets'));

    }

}
