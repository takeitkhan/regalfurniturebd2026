<?php

namespace App\Http\Controllers\API;

use App\Mail\ContactFormEmail;
use App\Models\SeoSettings;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use App\Mail\Emailing;
use App\Models\District;
use App\Models\SessionData;
use App\Repositories\Dashboard\DashboardInterface;
use App\Repositories\District\DistrictInterface;
use App\Repositories\FlashItem\FlashItemInterface;
use App\Repositories\FlashShedule\FlashSheduleInterface;
use App\Repositories\Page\PageInterface;
use App\Repositories\Post\PostInterface;
use App\Repositories\Product\ProductInterface;
use App\Repositories\ProductSet\ProductSetInterface;
use App\Repositories\SessionData\SessionDataInterface;
use App\Repositories\Setting\SettingInterface;
use Illuminate\Http\Request;
use App\Repositories\Slider\SliderInterface;
use App\Repositories\Term\TermInterface;
use Carbon\Carbon;
use Harimayco\Menu\Models\MenuItems;
use Illuminate\Support\Facades\Cache;

class CommonController extends Controller
{

    private $slider;
    private $product;
    private $session_data;
    private $dashboard;
    private $page;
    private $product_set;
    private $district;
    private $post;
    private $terms;
    private $setting;
    private $flash_item;
    private $flash_schedule;

    public function __construct(
        SliderInterface $slider,
        ProductInterface $product,
        SessionDataInterface $session_data,
        DashboardInterface $dashboard,
        PageInterface $page,
        ProductSetInterface $product_set,
        DistrictInterface $district,
        PostInterface $post,
        TermInterface $terms,
        SettingInterface $setting,
        FlashItemInterface $flash_item,
        FlashSheduleInterface $flash_schedule

    ) {
        $this->slider = $slider;
        $this->product = $product;
        $this->session_data = $session_data;
        $this->dashboard = $dashboard;
        $this->page = $page;
        $this->product_set = $product_set;
        $this->district = $district;
        $this->post = $post;
        $this->terms = $terms;
        $this->setting = $setting;

        $this->flash_item = $flash_item;
        $this->flash_schedule = $flash_schedule;
    }

    public function testing()
    {
        return [
            'hello' => 'Hello Bangladesh'
        ];
    }

    public function main_search_product_ajax(Request $request)
    {

        if ($request->get('cat') != null || $request->get('cat') != '') {

            $cat_cache_key = 'common-main-search'.md5($request->cat);
            if ($request->cache == 'clear') {
                Cache::forget($cat_cache_key);
            }

            $category = Cache::remember($cat_cache_key, 86400, function () use ($request) {

                return $this->terms->getByAny('seo_url', $request->cat)->first();
            });

            $cat = $category->id;
        } else {
            $cat = null;
        }

        $default = [
            'keyword' => $request->get('keyword'),
            'cat' => $cat
        ];


        $common_product_cache_key = 'common-main-search-products'.md5(json_encode($default));

        if ($request->cache == 'clear') {
            Cache::forget($common_product_cache_key);
        }

        $products = Cache::remember($common_product_cache_key, 86400, function () use ($default) {
            return $this->product->get_search_product_ajax($default);
        });

        $search = [];
        $terms = [];
        // dd($products);
        $q = $request->get('keyword');

        $common_cat_cache_key = 'common-main-search-terms'.md5($q);
        if ($request->cache == 'clear') {
            Cache::forget($common_cat_cache_key);
        }

        $db_terms = Cache::remember($common_cat_cache_key, 86400, function () use ($q) {

            return $this->terms->self()->where(function ($query) use ($q) {

                return $query->Where('terms.name', 'like', "%{$q}%")
                    ->orWhere('terms.seo_url', 'like', "%{$q}%")
                    ->orWhere('terms.description', 'like', "%{$q}%");

            })->where('terms.parent', '!=', null)->get();

        });


        foreach ($products as $item) {

            $item_info = [
                'url' => $item->t_url,
                'title' => $item->title,
                'sub_title' => $item->sub_title

            ];
            $search[] = $item_info;
        }

        foreach ($db_terms as $term) {

            $terms[] = [
                'url' => $term->seo_url,
                'name' => $term->name,
                'type' => $term->type

            ];


        }

        return response()->json(compact('search', 'terms'));


    }

    public function addToCompare(Request $request)
    {
        $self_token = $request->header('Self-Token');
        $product_id = $request->pid;


        if ($self_token == null || $self_token == "") {
            return false;
        }

        $self_compare_key = "compare_".$self_token;
        $existing_session = $this->session_data->getFirstByKey($self_compare_key);


        $product_ids = [$product_id];


        if ($existing_session) {
            $existing_product_ids = json_decode($existing_session->session_data);

            if (is_array($existing_product_ids)) {

                $existing_product_ids = array_filter($existing_product_ids);
                $product_ids = array_slice(array_merge($product_ids, $existing_product_ids), 0, 5);

            }

        }

        $product_ids = json_encode($product_ids);

        $store = $this->session_data->updateByKey($self_compare_key, $product_ids);

        $success = false;
        $product = null;

        if ($store) {
            $success = true;
            $db_product = $this->product->getByAny('id', $product_id)->first();

            if ($db_product) {
                $product = [
                    'title' => $db_product->title,
                    'seo_url' => $db_product->seo_url,
                    'image_url' => $db_product->firstImage->full_size_directory ?? null
                ];
            }
        }

        return response()->json(compact('success', 'product'));

    }


    public function viewCompare(Request $request)
    {
        $self_token = $request->header('Self-Token');
        $products = [];


        if ($self_token == null || $self_token == "") {
            return [];
        }


        $self_compare_key = "compare_".$self_token;
        $existing_session = $this->session_data->getFirstByKey($self_compare_key);


        if ($existing_session) {
            $existing_product_ids = json_decode($existing_session->session_data, true);
            $db_products = $this->product->self();
            $db_products = $db_products->whereIn('id', $existing_product_ids)->with('firstImage')->get();

            //  dd($db_products);

            foreach ($db_products as $db_product) {

                // $attribute_data = \App\Models\ProductAttributesData::select("attributes.default_value")->leftJoin('attributes', function ($join) {
                //     $join->on('productattributesdata.attribute_id', '=', 'attributes.id');
                //     })->where('main_pid', $db_product->id)->get();

                $attr = [];

                // return $attribute_data;

                // if($attribute_data->default_value){
                //     $expAttr = explode("|",$attribute_data->default_value);
                //     $attr = array_map(function($item){
                //         $exp = explode(":",$item);
                //         return $exp;
                //     },$expAttr);
                // }

                $product_item = [
                    'id' => $db_product->id,
                    'image_url' => $db_product->firstImage->full_size_directory ?? null,
                    'seo_url' => $db_product->seo_url,
                    'title' => $db_product->title,
                    'sub_title' => $db_product->sub_title,
                    'product_name' => $db_product->product_name,
                    'short_description' => $db_product->short_description,
                    'price_now' => $db_product->product_price_now,
                    'attr' => $attr
                ];

                $products[] = $product_item;

            }
        }

        return response()->json(compact('products'));
    }


    public function flashSales(Request $request)
    {

        $flash_sale = Cache::remember('common-flash-sale', 86400, function () {


            $flash_schedule = $this->flash_schedule->currentFlash();
            $flash_items = [];

            if ($flash_schedule) {
                $flash_items = $this->flash_item->getWhere(['fi_shedule_id' => $flash_schedule->id]);
            }

            $allVars = [
                'flash_schedule' => $flash_schedule,
                'flash_items' => $flash_items
            ];


            return $allVars;
        });

        if ($request->cache == 'clear') {
            Cache::forget('common-flash-sale');
        }


        return response()->json($flash_sale);
    }

    public function removeCompare(Request $request)
    {
        $self_token = $request->header('Self-Token');
        $product_id = $request->pid;


        if ($self_token == null || $self_token == "") {
            return false;
        }

        $self_compare_key = "compare_".$self_token;
        $existing_session = $this->session_data->getFirstByKey($self_compare_key);
        $product_ids = [];

        if ($existing_session) {
            $existing_product_ids = json_decode($existing_session->session_data, true);

            if (is_array($existing_product_ids)) {

                $filter_rr = array_filter($existing_product_ids, function ($item) use ($product_id) {
                    return $item != $product_id;
                });

                $product_ids = (array) $filter_rr;

            }

        }

        $product_ids = json_encode($product_ids, true);

        $store = $this->session_data->updateByKey($self_compare_key, $product_ids);

        $success = false;

        if ($store) {
            $success = true;
        }

        return response()->json(compact('success'));

    }


    public function footer(Request $request)
    {


        $menus = Cache::remember('common-footer-menus', 86400, function () {

            $menus = [];
            $menus[] = [
                'title' => 'QUICK NAVIGATION',
                'data' => get_parent_menus(9)
            ];

            $menus[] = [
                'title' => 'KNOWLEDGE BASE',
                'data' => get_parent_menus(10)
            ];

            $menus[] = [
                'title' => 'INFORMATION',
                'data' => get_parent_menus(3)
            ];


            $menus[] = [
                'title' => 'OTHERS',
                'data' => get_parent_menus(6)
            ];

            return $menus;
        });

        $support_db = Cache::remember('common-footer-support', 86400, function () {
            $this->dashboard->getById(1);
        });
        $support = $support_db->description ?? '';

        if ($request->cache == 'clear') {
            Cache::forget('common-footer-menus');
            Cache::forget('common-footer-support');
        }

        return response()->json(compact('menus', 'support'));

    }

    public function header(Request $request)
    {


//       $allVars = Cache::remember('common-header-vars', 86400, function ()  {

        $menus_db = get_parent_menus(1);
        $child_menu_db = get_parent_menus(11);
        $child_sub_menu_db = get_parent_menus(12);

        $menus = [];

        foreach ($menus_db as $menu) {
            $menus[] = [
                'label' => $menu->label,
                'link' => $menu->link
            ];
        }


        $child_menus = [];

        foreach ($child_menu_db as $child_menu) {
            $child_menus[] = [
                'label' => $child_menu->label,
                'link' => $child_menu->link
            ];
        }


        $child_sub_menus = [];

        foreach ($child_sub_menu_db as $child_sub_menu) {
            $child_sub_menus[] = [
                'label' => $child_sub_menu->label,
                'link' => $child_sub_menu->link
            ];
        }

        $offer_db = $this->dashboard->getById(12);
        $offer_title = $offer_db->description ?? 'Top Offers';

        $category_db = $this->dashboard->getById(13);
        $category_title = $category_db->description ?? 'Top categories';


        $flash_sale_db = $this->dashboard->getById(18);
        $flash_sale_title = $flash_sale_db->description ?? 'Flash Sale';

        return compact('menus', 'child_menus', 'child_sub_menus', 'offer_title', 'category_title', 'flash_sale_title');

//        });
//
//        if($request->cache == 'clear'){
//            Cache::forget('common-header-vars');
//        }
//
//        return response()->json($allVars);
    }

    public function page(Request $request)
    {
        $id = $request->id;
        $cache_key = 'common-post-page-'.$id;
//        $page = Cache::remember($cache_key, 2, function () use($id)  {
//
//            $db_pages = $this->page->getById($id);
//            $page =[
//                 'id' =>$db_pages->id,
//                // 'user_id' =>$request->user_id,
//                'title' =>$db_pages->title,
//                'sub_title' =>$db_pages->sub_title,
//                'seo_url' =>$db_pages->seo_url,
//                'description' =>$db_pages->description,
//                'images' =>$db_pages->images,
//                'is_sticky' =>$db_pages->is_sticky,
//                'lang' =>$db_pages->lang,
//                'created_at' =>Carbon::parse($db_pages->created_at)->format('Y-m-d H:i'),
//            ];
//
//
//            return $page;
//        });
//
//        if($request->cache == 'clear'){
//            Cache::forget($cache_key);
//        }


        $db_pages = $this->page->getById($id);
        $page = [
            'id' => $db_pages->id,
            // 'user_id' =>$request->user_id,
            'title' => $db_pages->title,
            'sub_title' => $db_pages->sub_title,
            'seo_url' => $db_pages->seo_url,
            'description' => $db_pages->description,
            'images' => $db_pages->images,
            'is_sticky' => $db_pages->is_sticky,
            'lang' => $db_pages->lang,
            'created_at' => Carbon::parse($db_pages->created_at)->format('Y-m-d H:i'),
        ];

        return response()->json(compact('page'));
    }

    public function topOffers(Request $request)
    {
        $products = Cache::remember('common-top-offers', 86400, function () {

            $products = $this->product->getByFilter([
                'recommended' => "on",
            ], [
                "id" => "desc"
            ]);

            return $products;
        });

        if ($request->cache == 'clear') {
            Cache::forget('common-top-offers');
        }

        return response()->json(compact('products'));
    }


    public function productSet(Request $request)
    {

        $product_sets = Cache::remember('common-product-sets', 86400, function () {
            $product_sets = [];
            $db_productSet = $this->product_set->self()->where('active', true)->orderBy('id', 'desc')->get();
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
            Cache::forget('common-product-sets');
        }

        return response()->json(compact('product_sets'));


    }

    public function districts(Request $request)
    {

        $districts = Cache::remember('common-districts', 86400, function () {


            $districts = [];
            $db_district = District::groupBy('district')->get();

            foreach ($db_district as $district) {
                $districts[] = [
                    'id' => $district->id,
                    'division' => $district->division,
                    'district' => $district->district,
                    'thana' => $district->thana,
                    'postoffice' => $district->postoffice,
                    'postcode' => $district->postcode,
                ];
            }

            return $districts;

        });

        if ($request->cache == 'clear') {
            Cache::forget('common-districts');
        }

        return response()->json(compact('districts'));
    }

    public function districtsByDivision($division_id)
    {
        $district = District::where('division', $division_id)->groupBy('district')->get();
        return response()->json($district ?? null);
    }


    public function getUserLocation(Request $r)
    {
        /*
        $long = $r->longitude;
        $lat = $r->latitude;
        $getName = file_get_contents('https://api.bigdatacloud.net/data/reverse-geocode-client?latitude='.$lat.'&longitude='.$long);
        $data = json_decode($getName);
        $name = $data->locality; //'Kalihati';
        */
        $name = $r->location;
        $dis = District::select('district')->where('thana', 'LIKE', '%'.$name.'%')
            ->orWhere('district', 'LIKE', '%'.$name.'%')
            ->orWhere('division', 'LIKE', '%'.$name.'%')
            ->first();
        return [
            'district' => $dis->district ?? null,
            'thana' => $name ?? null
        ];
    }

    public function showrooms(Request $request)
    {


        $cache_key = 'common-showrooms-'.$request->district;
        //$showrooms = Cache::remember('common-showrooms-'.$request->district, 86400, function () use($request)  {


        $showrooms = [];
        if ($request->district != null) {

            $db_showroomList = $this->post->self()->where('categories', 651)
                ->where('thana', $request->thana)
                ->orWhere('district', $request->district)
                ->orderBy('id', 'DESC')->where('shop_type', '!=', 'Chatbuy')->get();
        } else {
            $db_showroomList = $this->post->self()->where('categories', 651)->orderBy('id', 'DESC')->where('shop_type',
                '!=', 'Chatbuy')->get();
        }

        foreach ($db_showroomList as $showroom) {
            $showrooms[] = [
                'id' => $showroom->id,
                'title' => $showroom->title,
                'sub_title' => $showroom->sub_title,
                'seo_url' => $showroom->seo_url,
                'description' => $showroom->description,
                'division' => $showroom->division,
                'district' => $showroom->district,
                'phone' => $showroom->phone,
                'address' => $showroom->address,
                'latitude' => $showroom->latitude,
                'longitude' => $showroom->longitude
            ];
        }

        //return $showrooms;

        //});

//        if($request->cache == 'clear'){
//            Cache::forget($cache_key);
//        }

        return response()->json(compact('showrooms'));
    }


    public function allTerms(Request $request)
    {


        $terms = Cache::remember('common-all-terms', 86400, function () use ($request) {

            $terms = [];
            $alltarms = $this->terms->self()->where('type', "category")->where('is_published', 1)->orderBy('serial',
                'ASC')->get();
            foreach ($alltarms as $t) {
                $terms[$t->id] = [
                    'parent' => $t->parent,
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
            Cache::forget('common-all-terms');
        }

        return response()->json(compact('terms'), 200);
    }

    public function menuItems(Request $request)
    {
        $menu_items = Cache::remember('common-menu-items', 900, function () use ($request) {

            return MenuItems::orderBy('sort', 'ASC')->get();
        });

        return response()->json(compact('menu_items'));

    }


    public function send_email()
    {
        request()->validate(
            [
                'email' => 'required|email',
                'name' => 'required',
                'number' => 'required|min:11|max:11',
                'description' => 'required|min:10',
            ]
        );

        $settings = $this->setting->getAll();

        $name = request()->get('name');
        $email = request()->get('email');
        $number = request()->get('number');
        $description = request()->get('description');

        $setting = $settings[0];

        $data = [
            'com_name' => $setting->com_name,
            'description' => $description,
            'name' => $name,
            'email' => $email,
            'number' => $number,
            'subject' => 'Message through contact us form',
            'description' => $description
        ];

        Mail::to($setting->com_email)->send(new Emailing($data));
        $data = [

            'name' => $name,
            'email' => $email,
            'number' => $number,
            'description' => $description
        ];
        return response()->json(compact('data'));
    }

    public function getChatbuy()
    {

        // got it ?

        $chatbuy = $this->post->self()->where('shop_type', "Chatbuy")->get();
        return response()->json(compact('chatbuy'));
    }

    public function witgetBYId(Request $request)
    {

        $widget_cache_key = 'common-widget-id'.$request->id;
        $widget = null;
        $self_token = $request->header('Self-Token');
        // preload methodologies
        $preload_token = $self_token."_preload";
        $preload = null;
        if ($request->id == 5) {
            $preload = Cache::get($preload_token, null);
            Cache::put($preload_token, true, 3600);
        }

        if ($request->id && $preload == null) {

            $widget = Cache::remember('common-widget-id'.$request->id, 86400, function () use ($request) {

                $widgets = $this->dashboard->getById($request->id);

                if ($widgets != null) {

                    $widget = [
                        'name' => $widgets->name,
                        'type' => $widgets->type,
                        'cssid' => $widgets->cssid,
                        'cssclass' => $widgets->cssclass,
                        'description' => $widgets->description,
                        'special' => $widgets->special,
                        'is_active' => $widgets->is_active
                    ];

                }

                return $widget ?? null;

            });
        }

        if ($request->cache == 'clear') {
            Cache::forget($widget_cache_key);
            Cache::forget($preload_token);
        }


        return response()->json(compact('widget'));

    }


    public function getSiteMap()
    {


        $category = $this->terms
            ->self()
            ->select("seo_url")
            ->where('type', "category")
            ->where('id', '!=', 1)
            ->where('is_published', 1)
            ->orderBy('serial', 'ASC')
            ->get();


        $products = $this->product->self()->select("id", "product_code", "title", "seo_url", "description",
            "stock_status", "local_selling_price", "local_discount")->with('firstImage')->where('is_active', 1)->get();


        $products_db = [];

        foreach ($products as $product) {

            $categories = $this->product->getProductCategories($product->id);
            $category = null;
            $product->price_now = $product->product_price_now;
            if (count($categories)) {
                $category = \App\Models\Term::where('id', $categories[0]['term_id'])->get()->first();
                $product->category = $category;
            }


            $attribute_data = \App\Models\ProductAttributesData::leftJoin('attributes', function ($join) {
                $join->on('productattributesdata.attribute_id', '=', 'attributes.id');
            })->where('main_pid', $product->id)->first();


            if ($attribute_data) {
                $product->attr = $attribute_data;
            }

            $products_db[] = $product;
        }


        return response()->json([
            'category' => $category,
            'products' => $products_db
        ]);
    }

    //Contact Form Submit
    public function submitContactForm(Request $request)
    {
        $data = [
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'message' => $request->message,
        ];

        $toAddress = 'rfl707@rflgroupbd.com';
        $subject = 'Contact Form Notification';
        $done = Mail::to($toAddress)->send(new ContactFormEmail($data, $subject));
        return response()->json([
            'status' => 1,
            'message' => 'Thank you for contacting us! We will respond shortly.'
        ]);

    }

    public function getSeoContent(Request $r)
    {
        $r = (object) $r->params;
        $data = SeoSettings::where('post_id', $r->post_id)->where('post_type', $r->post_type)->first();
        if ($data) {
            $meta = \App\Models\SeoSettings::getMeta($data->id);
            $meta = $meta;
            return response()->json([
                'status' => '1',
//                'setting' => count($arr) > 0 ? $arr : false,
                'setting' => $meta,
            ]);
        } else {
            return response()->json([
                'status' => '0',
                'setting' => false,
            ]);
        }
    }

}
