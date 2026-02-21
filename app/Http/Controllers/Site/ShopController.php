<?php

namespace App\Http\Controllers\Site;

use App\Models\Cart;
use App\Models\Comparison;
use App\Models\Comment;
use App\Repositories\Attribute\AttributeInterface;
use App\Repositories\Dashboard\DashboardInterface;
use App\Repositories\OrdersDetail\OrdersDetailInterface;
use App\Repositories\OrdersMaster\OrdersMasterInterface;
use App\Repositories\Page\PageInterface;
use App\Repositories\PaymentSetting\PaymentSettingInterface;
use App\Repositories\Post\PostInterface;
use App\Repositories\Product\ProductInterface;
use App\Repositories\ProductQuestion\ProductQuestionInterface;
use App\Repositories\Review\ReviewInterface;
use App\Repositories\Role_user\Role_userInterface;
use App\Repositories\Setting\SettingInterface;
use App\Repositories\Temporaryorder\TemporaryorderInterface;
use App\Repositories\Term\TermInterface;
use App\Repositories\User\UserInterface;
use App\Wishlist;
use App\Models\ActivityLog;
use DOMDocument;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redirect;
use PDF;
use Session;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\District;
use App\OrdersDetail;
use App\OrdersMaster;
use App\Product;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    private $data = [];
    private $setting;
    private $page;
    /**
     * @var PostInterface
     */
    private $post;
    /**
     * @var ProductInterface
     */
    private $product;
    /**
     * @var DashboardInterface
     */
    private $dashboard;
    /**
     * @var TermInterface
     */
    private $term;
    /**
     * @var AttributeInterface
     */
    private $attribute;
    /**
     * @var Role_userInterface
     */
    private $role_user;
    /**
     * @var TemporaryorderInterface
     */
    private $temporaryorder;
    /**
     * @var OrdersMasterInterface
     */
    private $ordersmaster;
    /**
     * @var OrdersDetailInterface
     */
    private $ordersdetail;
    /**
     * @var ReviewInterface
     */
    private $review;
    /**
     * @var ProductQuestionInterface
     */
    private $productQuestion;

    /**
     * ShopController constructor.
     * @param PaymentSettingInterface $paymentsetting
     * @param SettingInterface $setting
     * @param PageInterface $page
     * @param PostInterface $post
     * @param ProductInterface $product
     * @param DashboardInterface $dashboard
     * @param TermInterface $term
     * @param AttributeInterface $attribute
     * @param UserInterface $user
     * @param Role_userInterface $role_user
     * @param TemporaryorderInterface $temporaryorder
     * @param OrdersMasterInterface $ordersmaster
     * @param OrdersDetailInterface $ordersdetail
     * @internal param array $data
     */
    public function __construct(
        PaymentSettingInterface $paymentsetting,
        SettingInterface $setting,
        PageInterface $page,
        PostInterface $post,
        ProductInterface $product,
        DashboardInterface $dashboard,
        TermInterface $term,
        AttributeInterface $attribute,
        UserInterface $user,
        Role_userInterface $role_user,
        TemporaryorderInterface $temporaryorder,
        OrdersMasterInterface $ordersmaster,
        ReviewInterface $review,
        OrdersDetailInterface $ordersdetail,
        ProductQuestionInterface $productQuestion
    ) {
        $this->paymentsetting = $paymentsetting;
        $this->setting = $setting;
        $this->page = $page;
        $this->user = $user;
        $this->post = $post;
        $this->product = $product;
        $this->dashboard = $dashboard;
        $this->term = $term;
        $this->attribute = $attribute;
        $this->role_user = $role_user;
        $this->temporaryorder = $temporaryorder;
        $this->ordersmaster = $ordersmaster;
        $this->ordersdetail = $ordersdetail;
        $this->review = $review;

        // $this->middleware('auth')->only('success');
        $this->productQuestion = $productQuestion;
    }

    /**
     * @param Request $request
     */
    public function search_product(Request $request)
    {
        //dd($request);
        if(!empty($request->fbclid)) {
            $query = request()->query();
            //dd($query);
            unset($query['fbclid']);
            $url = url()->current() . (!empty($query) ? '/?' . http_build_query($query) : '');
            //dd($url);
            return redirect()->to($url);
        } else if(!empty($request->gclid)) {
            $query = request()->query();
            //dd($query);
            unset($query['gclid']);
            $url = url()->current() . (!empty($query) ? '/?' . http_build_query($query) : '');
            //dd($url);
            return redirect()->to($url);
        } else {

            $path = $request->path();
            $seo_url = explode('/', $path);
            $settings = $this->setting->getAll();
            $posts = $this->post->getAll();
            $widgets = $this->dashboard->getAll();
            $search_key = $request->q;
            $minprice = $request->minprice;
            $maxprice = $request->maxprice;
            $limit = $request->limit;

            $category = $this->term->getByAny('seo_url', !empty($seo_url[1]) ? $seo_url[1] : 100)->first();

            if ($request->session()->exists('seen')) {
                $seen_products = $request->session()->get('seen.products');
            } else {
                $seen_products = [];
            }

            // dd($category);

            if (!empty($category->id)) {
                $filters_cat = get_filters_cat($category->id);
                $con_att = $category->connected_with;
                $filters_att = $this->attribute->getFilter($con_att);
                $get_keyworld = $request->all();

                //dd($category->id);
                //$view_cat = array($category->id);
                $view_cat = get_all_sub_cat($category->id);

                $get_fillter_product = $this->product->getProductByFilter($get_keyworld, $view_cat);

                $default = [
                    'type' => 'category',
                    'limit' => 500,
                    'offset' => 0
                ];
                $cats = $this->get_product_categories($default);
                $categories = $cats->toArray();
                //dd($settings);
                return view('frontend.products.search_results')
                    ->with([
                        'settings' => $settings,
                        'posts' => $posts,
                        'widgets' => $widgets,
                        'categories' => $categories,
                        'seen' => $seen_products,
                        'products' => $get_fillter_product,
                        'category_info' => $category,
                        'category_id' => $request->category_id,
                        'filter_cat' => $filters_cat,
                        'filters_att' => $filters_att
                    ]);
            } else {
                return redirect('/');
            }

        }
    }

    /**
     * @param $id
     * @param $slug
     * @return $this
     */
    public function get_products_by_category($id, $slug = null)
    {
        $settings = $this->setting->getAll();
        $posts = $this->post->getAll();
        $widgets = $this->dashboard->getAll();

        $currentPage = app('request')->input('pages');
        //dd($settings);
        $default = [
            'search_key' => null,
            'category' => !empty($id) ? [$id] : null,
            'limit' => 24,
            'offset' => 0 + (($currentPage - 1) * 24)
        ];

        $products = $this->product->getProductsOnSearch($default);

        $default = [
            'type' => 'category',
            'limit' => 500,
            'offset' => 0
        ];
        $cats = $this->get_product_categories($default);
        $categories = $cats->toArray();

        return view('frontend.products.category')
            ->with(['settings' => $settings, 'posts' => $posts, 'widgets' => $widgets, 'categories' => $categories, 'products' => $products]);
    }

    /**
     * @param array $options
     * @return mixed
     */
    public function get_product_categories(array $options = [])
    {
        $default = [
            'type' => 'category',
            'limit' => 10,
            'offset' => 0
        ];

        $optionss = array_merge($default, $options);

        return $this->term->get_terms_by_options($optionss);
    }

    /**
     * @param $id Product ID
     * @param $slug URL slug
     * @return $this frontend.products.single get_webpage
     */
    public function product_details(Request $request)
    {
        $url = $request->segment(2);

        //dd($url);
        $settings = $this->setting->getAll();
        $pages = $this->page->getAll();
        $posts = $this->post->getAll();
        $products = $this->product->getAll([]);
        $widgets = $this->dashboard->getAll()->keyBy('id');

        $p = $this->product->getByAny('seo_url', $url);
        $product = $p->first();

        if (!empty($product)) {
            // register this product on session
            $attributes = [
                'product_id' => $product->id,
                'seo_url' => $product->seo_url,
                'seen_time' => date('Y-m-d h:i:s')
            ];

            if ($request->session()->exists('seen')) {
                $seen_products = $request->session()->get('seen.products');
            } else {
                $seen_products = [];
            }
            if (array_search($product->id, array_column($seen_products, 'product_id')) !== false) {
            } else {
                $request->session()->push('seen.products', $attributes);
            }

            $categories = $this->product->getProductCategories($product->id);
            $similar = $this->product->getSimilarProduct($product->id);
            $images = $this->product->getProductImages($product->id);
            $attributes = $this->product->getProductAttributesData($product->id);

            $order = OrdersDetail::where('product_id', $product->id)->get()->first();
            if ($product->qty <= 0) {
                $stockout = true;
                if ($order) {
                    // return $stock->qty;
                    $available = $product->qty - $order->qty;
                    if ($available <= 0) {
                        $stockout = true;
                    } else {
                        $stockout = false;
                    }
                }
            } else {
                $stockout = false;
                if ($order) {
                    // return $stock->qty;
                    $available = $product->qty - $order->qty;
                    if ($available <= 0) {
                        $stockout = true;
                    } else {
                        $stockout = false;
                    }
                }
            }

            if(!$product->is_active){
                return redirect('/');
            }

            return view('frontend.products.single')
                ->with(['settings' => $settings,
                    'pages' => $pages,
                    'posts' => $posts,
                    'products' => $products,
                    'widgets' => $widgets,
                    'pro' => $product,
                    'categories' => $categories,
                    'similar' => $similar,
                    'images' => $images,
                    'attributes' => $attributes,
                    'seen' => $seen_products,
                    'stockout' => $stockout,
                ]);
        } else {
            return view('error')
                ->with(['']);
        }
    }

    /**
     * @param Request $request
     */
    public function change_combinition(Request $request)
    {
        dump($request->get('id'));
        dump($request->get('product_code'));
    }

    public function modify_variation(Request $request)
    {
        $variation_id = $request->get('id');
        $main_pid = $request->get('main_pid');

        $variation = \App\Pcombinationdata::where('main_pid', $main_pid)->get()->first();
        $pro = $this->product->getById($main_pid)->first();

        //dump($variation->price);
        //dd($pro);

        $regularprice = $variation->price;
        $save = ($pro->local_selling_price * $pro->local_discount) / 100;
        $sp = $regularprice - $save;

        $tksign = '&#2547; ';

        if ($pro->local_discount > 0) {
            $price_tag = 'Discount Price (' . $pro->local_discount . '%): ' . $tksign . number_format($sp);
        } else {
            $price_tag = 'Price: ' . $tksign . number_format($sp);
        }

        $add_to_cart_btn = '<input type="button"
        data-toggle="tooltip" title="" value="Add to Cart" data-loading-text="Loading..."
        id="button-cart" class="btn btn-mega btn-lg"
        onclick="add_to_cart("' . $pro->id . ' , ' . $pro->product_code . ', ' . $pro->sku . ', ' . $variation->price . ', ' . ($regularprice - $sp) . ', ' . $sp . ', 0, null, 1)"
        data-original-title="Add to Cart">';

        return response()->json(['price_tag' => $price_tag, 'add_to_cart_btn' => $add_to_cart_btn]);
    }

    public function unset_emi(Request $request)
    {
        $request->session()->has('emi_data') ? $request->session()->forget('emi_data') : null;

        return redirect()->back();
    }

    public function set_emi(Request $request)
    {
        //dd($request);
        $attributes = [
            'value' => $request->get('value'),
            'plan_id' => $request->get('plan_id'),
            'bank_id' => $request->get('bank_id'),
            'main_pid' => $request->get('main_pid')
        ];

        $request->session()->has('emi_data') ? $request->session()->forget('emi_data') : null;
        $request->session()->put('emi_data.value', $attributes['value']);
        $request->session()->put('emi_data.plan_id', $attributes['plan_id']);
        $request->session()->put('emi_data.bank_id', $attributes['bank_id']);
        $request->session()->put('emi_data.main_pid', $attributes['main_pid']);

        return response()->json(['message' => 'Your preferred plan has been saved successfully.']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function add_to_cart(Request $request)
    {
        $product = [
            'productid' => $request->get('main_pid'),
            'size' => $request->get('size'),
            'color' => $request->get('color'),
            'qty' => $request->get('qty'),
        ];

        //dd($product);

        $product_ifo = $this->product->getById($product['productid']);

        $is_falsh = is_flash_itme($product['productid']);
        if (($product_ifo->enable_timespan == 1 && $product_ifo->disable_buy == 'on') || $product_ifo->disable_buy == 'off') {
            if ($product_ifo['multiple_pricing'] == 'on') {
                if ($product['color'] != null) {
                    $color_info = \App\Pcombinationdata::where(['id' => $product['color'], 'main_pid' => $product['productid']])->first();
                    $get_color = [
                        'color_codes' => $color_info->color_codes,
                        'main_pid' => $product['productid']
                    ];
                } else {
                    $get_color = [];
                }
                if ($product['size'] != null) {
                    $size_info = \App\Pcombinationdata::where(['id' => $product['size'], 'main_pid' => $product['productid']])->first();
                    $get_size = [
                        'size' => $size_info->size,
                        'main_pid' => $product['productid']
                    ];
                } else {
                    $get_size = [];
                }
                $color_size_arr = array_merge($get_color, $get_size);
                if ($color_size_arr) {
                    $m_price_infos = \App\Pcombinationdata::where($color_size_arr)->get();
                    if ($m_price_infos->count() == 1) {
                        $m_price_info = $m_price_infos->first();

                        $final_sp = $m_price_info->selling_price;
                        $is_dp = 'No';
                        $flash_discount = ($is_falsh) ? $is_falsh->fi_discount : null;
                        $final_sp = $final_sp - $flash_discount;
                        $price_dif = $m_price_info->regular_price - $final_sp;
                        $dis_tag = round(($price_dif * 100) / $m_price_info->regular_price);
                        $add_cat = [
                            'productid' => $request->get('main_pid'),
                            'productcode' => $product_ifo->product_code . 'CIC' . $m_price_info->id,
                            'size_colo' => $m_price_info->id,
                            'purchaseprice' => $final_sp,
                            'qty' => $request->get('qty'),
                            'is_dp' => $is_dp,
                            'flash_discount' => $flash_discount,
                            'item_code' => $m_price_info->item_code,
                            'dis_tag' => $dis_tag,
                            'pre_price' => $m_price_info->regular_price
                        ];
                    } else {
                        $add_cat = [];
                    }
                } else {
                    $add_cat = [];
                }
            } else {
                $get_data = [
                    'main_pid' => $product['productid'],
                    'color' => $product['color'],
                    'size' => $product['size'],
                    'type' => null
                ];

                $get_price = get_product_price($get_data);

                $final_sp = $get_price['s_price'];
                $is_dp = 'No';
                $flash_discount = ($is_falsh) ? $is_falsh->fi_discount : null;
                $final_sp = $final_sp - $flash_discount;
                // dd($get_price);

                $add_cat = [
                    'productid' => $request->get('main_pid'),
                    'productcode' => $product_ifo->product_code,
                    'size_colo' => null,
                    'purchaseprice' => $get_price['s_price'],
                    'qty' => $request->get('qty'),
                    'is_dp' => null,
                    'flash_discount' => $flash_discount,
                    'item_code' => null,
                    'dis_tag' => $get_price['save'],
                    'pre_price' => $get_price['r_price']
                ];
            }
        } else {
            $add_cat = [];
        }
        if ($add_cat) {
            //dd($add_cat);

            $oldcart = Session::has('cart') ? Session::get('cart') : null;
            //dd($oldcart);
            $cart = new Cart($oldcart);


            //$cart->add($product, $request->get('productcode'));
            $cart->add($add_cat, $add_cat['productcode']);

            $request->session()->put('cart', $cart);

            $newcart = Session::has('cart') ? Session::get('cart') : null;
            $ncart = new Cart($newcart);

            //dd($ncart);

            if (!empty($ncart->items)) {
                $total_qty = array_sum(array_column($ncart->items, 'qty'));
                $individual_price = [];
                foreach ($ncart->items as $item) {
                    //dd($item);
                    $individual_price[] = $item['purchaseprice'] * $item['qty'];
                }
                $totalprice = array_sum($individual_price);
            } else {
                $total_qty = 0;
                $totalprice = number_format(0);
            }

            $pro = $this->product->getById($request->get('main_pid'));
            $categories = $this->product->getProductCategories($pro->id);
            $cat_info = \App\Term::where('id', $categories[0]['term_id'])->get()->first();
            $cat_name = $cat_info['name']??'';
            //dd($pro);

            $first_image = \App\ProductImages::where('main_pid', $pro->id)->where('is_main_image', 1)->get()->first();

            //dd($first_image);

            $regularprice = $pro->local_selling_price;
            $save = ($pro->local_selling_price * $pro->local_discount) / 100;
            $sp = $regularprice - $save;
            $tksign = '&#2547; ';

            if ($sp < $regularprice) {
                $price = '<span class="price-new">' . $tksign . number_format($sp) . '</span>';
            //$price .= '<span class="price-old">' . $tksign . number_format($sp) . '</span>';
            } else {
                $price = '<span class="price-new">' . $tksign . number_format($sp) . '</span>';
            }

            // left portion
            $html = '<div class="product-view quickview-w">';
            $html .= '<div class="left-content-product row">';
            $html .= '<div class="content-product-left content-product-left1 class-honizol col-md-7 col-sm-12 col-xs-12">';
            $html .= '<div class="row">';
            $html .= '<div class="col-md-12">';
            $html .= '<h4 class="title-area-cart-v"> 1 new item have been added to your cart</h4>';
            $html .= '</div>';
            $html .= '<div class="col-md-4">';
            $html .= '<div class="cart-img">';
            // dd(url($first_image->icon_size_directory));
            $html .= '<a href="' . product_seo_url($pro->seo_url, $pro->id) . '" target="_self" title="' . $pro->title . '">';
            if (!empty($first_image)) {
                $html .= '<img src="' . url($first_image->icon_size_directory) . '" alt="' . $pro->title . '">';
            }
            $html .= '</a>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '<div class="col-md-8">';
            $html .= '<div class="content-product-right_wp">';
            $html .= '<div class="content-product-right_a">';

            $html .= '<div class=cart-prouct-title><h1>' . $pro->title . '</h1></div>';
            $html .= '</div>';
            $html .= '<div class="content-product-right_b">';
            $html .= '<div class="product-label form-group">';
            $html .= '<div class="product_page_price1 price" itemprop="offerDetails" itemscope="" itemtype="">' . $tksign . number_format($add_cat['purchaseprice']) . '</div></div>';
            $html .= '</div>';
            $html .= '</div>';

            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';

            // right portion
            $html .= '<div class="content-product-right content-product-right1 col-md-5 col-sm-12 col-xs-12">';
            $html .= '<div class="row">';
            $html .= '<div class="col-md-12">';
            $html .= '<h4 class="title-area-cart-v title-area-cart-gf">My Shopping Cart <span class="total-item-list-a">(' . $total_qty . ' items)</span>';
            $html .= '</h4>';
            $html .= '</div>';

            $html .= '<div class="col-md-12"><div class="title-product">';
            $html .= '<ul class="list-unstyled">';
            $html .= '<li>Subtotal <span class="badge">' . $tksign . number_format($totalprice) . '</span></li>';
            $html .= '<li class="top-banner-cart">Total <span class="badge">' . $tksign . number_format($totalprice) . '</span></li>';
            $html .= '</ul>';
            $html .= '</div>';

            $html .= '</div>';

            $html .= '<div class=" col-md-12 product_btnd">';

            $html .= '<a href="javascript:void(0);" data-dismiss="modal" class="bk-btnc text-right"> <i class="fa fa-angle-left"></i> <span>Continue Shopping</span></a>';

            $html .= '<a href="' . url('view_cart') . '" class="btn btn-default  view_one pull-right "><span>View Cart</span></a>';

            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';

            if (Session::has('my_coupon')) {
                $coupons = Session::get('my_coupon');
                coupon_voucher_verify($coupons['coupon_type'], $coupons['coupon_code']);
                //dd(22);
            }

            return response()->json(['data' => $html, 'total_amount' => number_format($totalprice), 'total_qty' => $total_qty, 'report' => 'Yes','product_title' => $product_ifo->title??'','product_pprice' => $get_price['s_price']??0,'cat_name' => $cat_name??'']);
        } else {
            return response()->json(['data' => null, 'total_amount' => null, 'total_qty' => null, 'report' => 'No']);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove_cart_item(Request $request)
    {
        $id = $request->get('productid');
        $code = $request->get('productcode');

        $oldcart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldcart);
        unset($cart->items[$code]);
        $request->session()->put('cart', $cart);
        return back();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function update_qty(Request $request)
    {
        $cart = $request->get('cart');
        $cartData = json_decode($cart);
        $oldcart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldcart);

        $item = [];
        $i = 0;
        foreach ($cartData as $key => $val) {
            if ($key === $i) {
                $item[$val->productcode] = [
                    'qty' => $val->qty,
                    'purchaseprice' => $cart->items[$val->productcode]['purchaseprice'],
                    'item' => $cart->items[$val->productcode]['item']
                ];
            }
            $i++;
        }

        $mm = array_replace($cart->items, $item);
        $cart->items = $mm;

        $request->session()->put('cart', $cart);
        if (Session::has('my_coupon')) {
            $coupons = Session::get('my_coupon');
            coupon_voucher_verify($coupons['coupon_type'], $coupons['coupon_code']);
        }

//        return redirect()->route('view_cart');
        return response()->json(['data' => $cart]);
        //return view('frontend.products.shopping-cart');
    }

    /** Shopping Cart Part */

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view_cart(Request $request)
    {
        //dd($request->get('status'));

        $settings = $this->setting->getAll();
        $widgets = $this->dashboard->getAll();

        if (!Session::has('cart')) {
            return view('frontend.products.shopping-cart', ['settings' => $settings, 'widgets' => $widgets, 'cartproducts' => null]);
        }

        $oldcart = Session::get('cart');
        $cart = new Cart($oldcart);

        //dd($cart);

        return view('frontend.products.shopping-cart')
            ->with(['settings' => $settings, 'cartproducts' => $cart->items, 'widgets' => $widgets]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function checkout_address()
    {
        $settings = $this->setting->getAll();
        $widgets = $this->dashboard->getAll();

        if (!Session::has('cart')) {
            return view('frontend.products.shopping-cart', ['settings' => $settings, 'widgets' => $widgets, 'cartproducts' => null]);
        }

        $oldcart = Session::get('cart');
        $cart = new Cart($oldcart);

        $total_price = [];
        foreach($cart->items as $item) {
            $item_qty = $item['qty'];

            $item_price = $item['purchaseprice'];

            $total_price[] = $item_qty * $item_price;
        }
        $total_amount = array_sum($total_price);


        $attributes = [
            'currency' => 'BDT',
            'total_amount' => $total_amount,
            'grand_total' => null,
            'payment_method' => null,
            'terms_check' => null
        ];

        Session::put('payment_method', $attributes);
        Session::save();

        $thana = District::whereIn('thana', ['Badda', 'Uttara', 'Mirpur', 'Rampura', 'Malibagh', 'Gulshan', 'Baridhara'])->groupBy('thana')->get();

        return view('frontend.products.checkout-address', compact('thana'))
            ->with([
                'settings' => $settings,
                'cartproducts' => $cart->items,
                'widgets' => $widgets,
                'totalprice' => $cart->totalprice,
                'totalqty' => $cart->totalqty
            ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function checkout_delivery_address(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|min:3|max:255',
                'mobile' => 'required|min:11|max:11',
                'emergency_mobile' => 'required|min:11|max:11|different:mobile',
                'email' => 'required',
                'address' => 'required',
            ]
        );
        if ($validator->fails()) {
            return redirect('checkout/address')
                ->withErrors($validator)
                ->withInput();
        } else {
            $paymentsetting = $this->paymentsetting->getAll();
            $paymentsetting = $paymentsetting->first();
            $pm = Session::get('payment_method');

            //dump($pm['total_amount']);
            //dd($request->get('district'));


            if ($request->get('district') == 'Dhaka' && $pm['total_amount'] < $paymentsetting->decidable_amount) {
                $deliverycharge = $paymentsetting->inside_dhaka_fee;
            } else if($request->get('district') == 'Dhaka' && $pm['total_amount'] > $paymentsetting->decidable_amount) {
                $deliverycharge = $paymentsetting->outside_dhaka_fee;
            } else if($request->get('district') != 'Dhaka' && $pm['total_amount'] < $paymentsetting->decidable_amount_od) {
                $deliverycharge = $paymentsetting->inside_dhaka_od;
            } else if($request->get('district') != 'Dhaka' && $pm['total_amount'] > $paymentsetting->decidable_amount_od) {
                $deliverycharge = $paymentsetting->outside_dhaka_od;
            }

            if (!empty($request->get('password')) && !empty($request->get('username'))) {
                $attributes = [
                    'name' => $request->get('name'),
                    'phone' => $request->get('mobile'),
                    'emergency_phone' => $request->get('emergency_mobile'),
                    'email' => $request->get('email'),
                    'district' => $request->get('district'),
                    'thana' => $request->get('thana'),
                    'deliveryfee' => $deliverycharge,
                    'address' => $request->get('address'),
                    'username' => $request->get('username'),
                    'password' => bcrypt($request->get('password')),
                    'is_active' => 1
                ];
            } else {
                $email_exploded = explode('@', $request->get('email'));
                $username = $email_exploded[0];
                $attributes = [
                    'name' => $request->get('name'),
                    'phone' => $request->get('mobile'),
                    'emergency_phone' => $request->get('emergency_mobile'),
                    'email' => $request->get('email'),
                    'district' => $request->get('district'),
                    'thana' => $request->get('thana'),
                    'deliveryfee' => $deliverycharge,
                    'address' => $request->get('address'),
                    'username' => $username,
                    'password' => bcrypt('12345678'),
                    'is_active' => 1
                ];
            }

            Session::put('user_details', $attributes);
            Session::save();

            try {
                $user = $this->user->create($attributes);
                if (!empty($user)) {
                    //dd($user);
                    $attributes_role = [
                        'role_id' => 8,
                        'user_id' => $user->id
                    ];

                    $adding_user_id = [
                        'user_id' => [$user->id]
                    ];

                    $natt = array_merge($attributes, $adding_user_id);
                    Session::put('user_details', $natt);
                    Session::save();

                    //Session::push('user_details.user_id', $user->id);

                    try {
                        $this->role_user->create($attributes_role);
                        return redirect('/checkout/payment_method')->with('success', 'Your contact and address has been saved');
                    } catch (\Illuminate\Database\QueryException $ex) {
                        $errorCode = $ex->errorInfo[1];
                        if ($errorCode == '1062') {
                            return back()->with('failed', $ex->errorInfo[2]);
                        } else {
                            return back()->with('failed', 'Something went wrong');
                        }
                    }
                } else {
                    return back()->with('failed', 'Something went wrong');
                }
            } catch (\Illuminate\Database\QueryException $ex) {
                //dd($ex);
                $errorCode = $ex->errorInfo[1];

                $data = Session::all();

                if ($errorCode === 1062) {
                    //dd($data['user_details']['email']);
                    $user = \App\User::where('email', $data['user_details']['email'])->get()->first();
                    $user->address = $data['user_details']['address'];
                    $user->save(['username' => false, 'email' => false, 'password' => false, 'name' => false, 'phone' => false, 'emrgency_phone' => false]);

                    $adding_user_id = [
                        'user_id' => [$user->id]
                    ];

                    $natt = array_merge($attributes, $adding_user_id);
                    Session::put('user_details', $natt);
                    $data = Session::all();
                    //dd($data);
                    //dd($user);
                    //$this->user->update($request->get('id'), $attributes);
                    return redirect('/checkout/payment_method')->with('success', 'Your contact and address has been saved')->send();
                //return back()->with('failed', $ex->errorInfo[2]);
                } else {
                    return back()->with('failed', $ex->errorInfo[2]);
                }
            }
        }

        if (Session::has('my_coupon')) {
            $coupons = Session::get('my_coupon');
            coupon_voucher_verify($coupons['coupon_type'], $coupons['coupon_code']);
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function checkout_payment_method()
    {
        $settings = $this->setting->getAll();
        $paymentsetting = $this->paymentsetting->getAll();
        //dd($paymentsetting);
        $widgets = $this->dashboard->getAll();

        if (Session::has('my_coupon')) {
            $coupons = Session::get('my_coupon');
            coupon_voucher_verify($coupons['coupon_type'], $coupons['coupon_code']);
        }

        return view('frontend.products.checkout-payment-method')
            ->with(['settings' => $settings, 'paymentsetting' => $paymentsetting->first(), 'widgets' => $widgets]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function store_payment_method(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'total_amount' => 'required',
                'grand_total' => 'required',
                'terms_check' => 'required',
                'payment_method' => 'required'
            ]
        );

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        } else {
            //dump(Session::all());
            //die();

            if (Session::has('my_coupon')) {
                $coupons = Session::get('my_coupon');
                $discount = $coupons['coupon_amount'];
            } else {
                $discount = 0;
            }

            $attributes = [
                'currency' => 'BDT',
                'total_amount' => $request->get('total_amount'),
                'grand_total' => $request->get('grand_total') - $discount,
                'payment_method' => $request->get('payment_method'),
                'terms_check' => $request->get('terms_check')
            ];

            //dd($attributes);

            Session::put('payment_method', $attributes);

            if (Session::has('my_coupon')) {
                $coupons = Session::get('my_coupon');
                coupon_voucher_verify($coupons['coupon_type'], $coupons['coupon_code']);
            }

            try {
                return redirect('/checkout/review_order')->with('success', 'Your preferred payment method has been selected successfully');
            } catch (\Illuminate\Database\QueryException $ex) {
                return redirect('/checkout/payment_method')->withErrors($ex->getMessage());
            }
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function review_order()
    {
        //dump(Session::all());

        $settings = $this->setting->getAll();
        $paymentsetting = $this->paymentsetting->getAll();
        $widgets = $this->dashboard->getAll();
        //
        //        if (Session::has('cart')) {
        //            return view('frontend.products.shopping-cart', ['settings' => $settings, 'paymentsetting' => $paymentsetting->first(), 'widgets' => $widgets, 'cartproducts' => null]);
        //        }

        $oldcart = Session::get('cart');
        $cart = new Cart($oldcart);

        // dd(Session::all());

        return view('frontend.products.order-review')
            ->with(['settings' => $settings, 'cartproducts' => $cart->items, 'paymentsetting' => $paymentsetting->first(), 'widgets' => $widgets]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function check_if_email_exists(Request $request)
    {
        $email = $request->get('email');
        $data = $this->user->getByEmail($email);

        if (!empty($data->id)) {
            return response()->json(['data' => 'Email already exists']);
        } else {
            return response()->json(['data' => 'You can register']);
        }
    }

    /**
     *
     */
    public function pay_now()
    {
        $data = Session::all();

        //dd($data);
        $rand = str_random(15);
        $secret_key = str_random(50);

        $attributes = [
            '_token' => json_encode($data['_token']),
            '_previous' => json_encode($data['_previous']),
            'cart' => json_encode($data['cart']),
            'coupon_details' => (isset($data['my_coupon'])) ? json_encode($data['my_coupon']) : null,
            'user_details' => json_encode($data['user_details']),
            'payment_method' => json_encode($data['payment_method'])
        ];

        $temp_ensuring = $this->temporaryorder->create($attributes);

        if ($temp_ensuring) {
            //dd($data);
            $this->place_order($data, $rand, $secret_key);
        }
    }

    /**
     * @param $data
     * @param $rand
     * @param $secret_key
     * @return \Illuminate\Http\RedirectResponse
     */
    public function place_order($data, $rand, $secret_key)
    {
        //dd($data);

        $user_id = !empty($data['user_details']['user_id']) ? $data['user_details']['user_id'][0] : null;

        if ($data['payment_method']['payment_method'] == 'cash_on_delivery') {
            $order_detail_created = order_detail_create($data, $rand, $secret_key, $user_id);

            $orders_master_attributes = order_master_create($data, $rand, $secret_key, $user_id);

            $mm = adminSMSConfig($this->paymentsetting->getById(1));
            $admins_nos = implode(',', $mm);

            //sendSMS($orders_master_attributes['phone'] . ',' . $admins_nos, 'Order has been placed. Order ID: ' );
            //dd($orders_master_attributes['phone'] . ',' . $admins_nos);
            try {
                //dd($orders_master_attributes);
                $orderplacing = $this->ordersmaster->create($orders_master_attributes);
                $orderplacing->payment_term_status = 'Pending';
                $orderplacing->save();


                if (!empty($orderplacing)) {
                    $msg_for_customer = 'Order has been placed. Order ID: ' . $orderplacing->id;
                    sendSMS($orders_master_attributes['phone'] . ',' . $admins_nos, $msg_for_customer);
                    redirect('/checkout/success?order_id=' . $orderplacing->id . '&random_code=' . $rand . '&secret_key=' . $secret_key)->send();
                } else {
                    return back()->with('failed', 'Something went wrong');
                }
            } catch (\Illuminate\Database\QueryException $ex) {
                $errorCode = $ex->errorInfo[1];

                if ($errorCode === 1062) {
                    return back()->with('failed', $ex->errorInfo[2]);
                } else {
                    return back()->with('failed', $ex->errorInfo[2]);
                }
            }
        } elseif ($data['payment_method']['payment_method'] == 'bkash') {

            $order_detail_created = order_detail_create($data, $rand, $secret_key, $user_id);

            $orders_master_attributes = order_master_create($data, $rand, $secret_key, $user_id);

            $mm = adminSMSConfig($this->paymentsetting->getById(1));


            $admins_nos = implode(',', $mm);
            // dd($admins_nos);

            //sendSMS($orders_master_attributes['phone'] . ',' . $admins_nos, 'Order has been placed. Order ID: ' );
            //dd($orders_master_attributes['phone'] . ',' . $admins_nos);
            try {
                //dd($orders_master_attributes);
                $orderplacing = $this->ordersmaster->create($orders_master_attributes);
                $orderplacing->payment_term_status = 'Pending';
                $orderplacing->save();
                if (!empty($orderplacing)) {
                    $msg_for_customer = 'Order has been placed. Order ID: ' . $orderplacing->id;
                    sendSMS($orders_master_attributes['phone'] . ',' . $admins_nos, $msg_for_customer);
                    redirect('/checkout/success?order_id=' . $orderplacing->id . '&random_code=' . $rand . '&secret_key=' . $secret_key)->send();
                } else {
                    return back()->with('failed', 'Something went wrong');
                }
            } catch (\Illuminate\Database\QueryException $ex) {
                $errorCode = $ex->errorInfo[1];

                if ($errorCode === 1062) {
                    return back()->with('failed', $ex->errorInfo[2]);
                } else {
                    return back()->with('failed', $ex->errorInfo[2]);
                }
            }


        } elseif ($data['payment_method']['payment_method'] == 'citybank') {
            $gt = $data['payment_method']['grand_total'] * 100;
            $surl = htmlentities(url('checkout/success?rand=' . $rand . '&secret_key=' . $secret_key));
            $curl = htmlentities(url('checkout/cancel?rand=' . $rand . '&secret_key=' . $secret_key));
            $furl = htmlentities(url('checkout/fail?rand=' . $rand . '&secret_key=' . $secret_key));

            $xml_data = '<?xml version="1.0" encoding="UTF-8"?>';
            $xml_data .= '<TKKPG>';
            $xml_data .= '<Request>';
            $xml_data .= '<Operation>CreateOrder</Operation>';
            $xml_data .= '<Language>EN</Language>';
            $xml_data .= '<Order>';
            $xml_data .= '<OrderType>Purchase</OrderType>';
            $xml_data .= '<Merchant>11122333</Merchant>';
            $xml_data .= '<Amount>' . $gt . '</Amount>';
            $xml_data .= '<Currency>050</Currency>';
            $xml_data .= '<Description>Product Purchase from RFL Best Buy</Description>';
            $xml_data .= '<ApproveURL>' . $surl . '</ApproveURL>';
            $xml_data .= '<CancelURL>' . $curl . '</CancelURL>';
            $xml_data .= '<DeclineURL>' . $furl . '</DeclineURL>';
            $xml_data .= '</Order>';
            $xml_data .= '</Request>';
            $xml_data .= '</TKKPG>';

            // Information on the result of the order creation in the Response object
            // Examples of obtaining required fields:
            $xml = PostQW($xml_data);

            //dd($xml);
            $OrderID = $xml->Response->Order->OrderID;
            $SessionID = $xml->Response->Order->SessionID;
            $URL = $xml->Response->Order->URL;
            // Request for payment page
            if ($OrderID != '' and $SessionID != '') {
                //Update existing Order XML by Create Order Status
                $xml = new DOMDocument('1.0', 'utf-8');
                $xml->formatOutput = true;
                $xml->preserveWhiteSpace = false;
                $xml->load(url('Order.xml'));

                //Get item element
                $element = $xml->getElementsByTagName('Order')->item(0);

                //Load child elements
                $oID = $element->getElementsByTagName('OrderID')->item(0);
                $sID = $element->getElementsByTagName('SessionID')->item(0);
                $PurchaseAmount = $element->getElementsByTagName('PurchaseAmount')->item(0);
                $Currency = $element->getElementsByTagName('Currency')->item(0);
                $Description = $element->getElementsByTagName('Description')->item(0);
                $PAN = $element->getElementsByTagName('PAN')->item(0);
                $oStatus = $element->getElementsByTagName('Status')->item(0);

                //Replace old elements with new
                $element->replaceChild($oID, $oID);
                $element->replaceChild($sID, $sID);
                $element->replaceChild($PurchaseAmount, $PurchaseAmount);
                $element->replaceChild($Currency, $Currency);
                $element->replaceChild($Description, $Description);
                $element->replaceChild($PAN, $PAN);
                $element->replaceChild($oStatus, $oStatus);

                //Assign elements with new value
                $oID->nodeValue = $OrderID;
                $sID->nodeValue = $SessionID;
                $PurchaseAmount->nodeValue = $gt * 100;
                $Currency->nodeValue = '050';
                $Description->nodeValue = 'Test Product';
                $PAN->nodeValue = '';
                $oStatus->nodeValue = 'Created';
                $xml->save('Order.xml');

                // Add codes for saving the Order ID and Session ID in Merchant DB for future uses.
                header('Location: ' . $URL . '?ORDERID=' . $OrderID . '&SESSIONID=' . $SessionID . '');
                exit();
            }
        } elseif ($data['payment_method']['payment_method'] == 'debitcredit' || $data['payment_method']['payment_method'] == 'mobilebanking') {
            /*******************************************************/
            /*******************************************************/
            /*******************************************************/
            /************ Working Here for SSLCommerz **************/
            /************** sslcommerz_helpers.php ****************/
            /*******************************************************/
            /*******************************************************/

            $order_detail_created = order_detail_create($data, $rand, $secret_key, $user_id);
            $orders_master_attributes = order_master_create($data, $rand, $secret_key, $user_id);
            try {
                $orderplacing = $this->ordersmaster->create($orders_master_attributes);
                $orderplacing->payment_term_status = 'Pending';
                $orderplacing->save();

                 //dd($orderplacing);
                if (!empty($orderplacing)) {
                    //$msg_for_customer = 'Order has been placed. Order ID: ' . $orderplacing->id;
                    //sendSMS($orders_master_attributes['phone'] . ',' . $admins_nos, $msg_for_customer);

                    $user_data = $this->user->getById($user_id);
                    $cur_random_value = rand_string(18);
                    $qty = [];
                    $price = [];
                    foreach ($data['cart']->items as $item) {
                        $qty[] = $item['qty'];
                        $price[] = $item['purchaseprice'];
                    }
                    //$total_amount = array_sum($price);
                    //$delivery_charge = $data['user_details']['deliveryfee'];

                    $grand_total = $data['payment_method']['grand_total'];

                    //dd($grand_total);

                    // Here you have to receive all the order data to initate the payment.
                    // Lets your oder trnsaction informations are saving in a table called "orders"
                    // In orders table order uniq identity is "order_id","order_status" field contain status of the transaction, "grand_total" is the order amount to be paid and "currency" is for storing Site Currency which will be checked with paid currency.
                    $post_data = [];
                    $post_data['total_amount'] = $grand_total; // You cant not pay less than 10
                    $post_data['currency'] = 'BDT';
                    $post_data['tran_id'] = $cur_random_value; // tran_id must be unique
                    //Start to save these value  in session to pick in success page.
                    $_SESSION['payment_values']['tran_id'] = $post_data['tran_id'];
                    //End to save these value  in session to pick in success page.
                    $post_data['success_url'] = url('checkout/success?order_id=' . $orderplacing->id . '&random_code=' . $rand . '&secret_key=' . $secret_key);
                    $post_data['fail_url'] = url('checkout/fail');
                    $post_data['cancel_url'] = url('checkout/cancel');
                    // CUSTOMER INFORMATION
                    $post_data['cus_name'] = !empty($user_data->name) ? $user_data->name : 'Test Customer';
                    $post_data['cus_email'] = !empty($user_data->email) ? $user_data->email : 'Test Customer Email';
                    $post_data['cus_add1'] = !empty($user_data->address) ? $user_data->address : 'Test Customer Address';
                    $post_data['cus_add2'] = '';
                    $post_data['cus_city'] = '';
                    $post_data['cus_state'] = !empty($user_data->district) ? $user_data->district : 'Test Customer District';
                    $post_data['cus_postcode'] = '';
                    $post_data['cus_country'] = 'Bangladesh';
                    $post_data['cus_phone'] = !empty($user_data->phone) ? $user_data->phone : 'Test Customer District';
                    $post_data['cus_fax'] = '';
                    // SHIPMENT INFORMATION
                    $post_data['ship_name'] = !empty($user_data->name) ? $user_data->name : 'Test Customer';
                    $post_data['ship_add1 '] = !empty($user_data->address) ? $user_data->address : 'Test Customer Address';
                    $post_data['ship_add2'] = '';
                    $post_data['ship_city'] = '';
                    $post_data['ship_state'] = !empty($user_data->district) ? $user_data->district : 'Test Customer District';
                    $post_data['ship_postcode'] = '';
                    $post_data['ship_country'] = 'Bangladesh';
                    // OPTIONAL PARAMETERS
                    $post_data['value_a'] = $orderplacing->id; // order master id
                    $post_data['value_b'] = $rand; // order random id
                    $post_data['value_c'] = $orderplacing->grand_total; // grand total
                    $post_data['value_d'] = $orderplacing->total_amount; // total amount

                    $sslc = new SSLCommerz();
                    //dd($post_data);
                    $payment_options = $sslc->initiate($post_data, false);
                    //dd($payment_options);
                    if (!is_array($payment_options)) {
                        //print_r($payment_options);
                        $payment_options = [];
                    }
//                    redirect('/checkout/success?order_id=' . $orderplacing->id . '&random_code=' . $rand . '&secret_key=' . $secret_key)->send();

                    $check_status = prepare_sslcommerz($data['cart'], $user_data, $rand, $secret_key, $orderplacing->id);

                    if ($check_status['status'] == 'SUCCESS') {
                        echo Redirect::away($check_status['GatewayPageURL']);
                    } else {
                        return Redirect::to(url('/view_cart'));
                    }
                    //redirect('/checkout/success?order_id=' . $orderplacing->id . '&random_code=' . $rand . '&secret_key=' . $secret_key)->send();
                } else {
                    return back()->with('failed', 'Something went wrong');
                }
            } catch (\Illuminate\Database\QueryException $ex) {
                //dd($ex);
                $errorCode = $ex->errorInfo[1];

                if ($errorCode === 1062) {
                    return back()->with('failed', $ex->errorInfo[2]);
                } else {
                    return back()->with('failed', $ex->errorInfo[2]);
                }
            }
        }
    }

    public function success(Request $request)
    {

        //dd($request->all());
        $data = Session::all();
        $settings = $this->setting->getAll();
        $paymentsetting = $this->paymentsetting->getAll();
        $widgets = $this->dashboard->getAll();

        // if (Auth::check()) {
        if (Auth::check() && $request->get('singel') != null && (auth()->user()->isAdmin() || auth()->user()->isVendor())) {
            if ($request->get('singel') == 'No') {
                $orders_master = $this->ordersmaster->getById($request->get('order_id'));
                $user = $this->user->getById($orders_master->user_id);
                $orders_detail = $this->ordersdetail->getProductBySecretKey([
                    'random_code' => $request->get('random_code'),
                    'secret_key' => $request->get('secret_key')
                ]);
            } elseif ($request->get('singel') == 'Yes') {
                $orders_master = $this->ordersmaster->getByRandom($request->get('random_code'));

                $user = $this->user->getById($orders_master->user_id);
                //dd($user);
                $orders_detail = $this->ordersdetail->getById(['id' => $request->get('order_id')]);
            }
        } else {
            $orders_master = $this->ordersmaster->getById($request->get('order_id'));
            $user = $this->user->getById($orders_master->user_id);

            $orders_detail = $this->ordersdetail->getProductBySecretKey([
                'random_code' => $request->get('random_code'),
                'secret_key' => $request->get('secret_key')
            ]);

            if ($data['payment_method']['payment_method'] == 'bkash') {
                // Code pore add korbo
            } elseif ($data['payment_method']['payment_method'] == 'citybank') {
                if ($request->get('xmlmsg') != '') {
                    $xmlResponse = simplexml_load_string($request->get('xmlmsg'));
                    $json = json_encode($xmlResponse);
                    $array = json_decode($json, true);

                    //Update existing Order XML by Approved Status
                    $xmlData = new DOMDocument('1.0', 'utf-8');
                    $xmlData->formatOutput = true;
                    $xmlData->preserveWhiteSpace = false;
                    $xmlData->load(url('Order.xml'));

                    //Get item element
                    $element = $xmlData->getElementsByTagName('Order')->item(0);

                    //Load child element
                    $OrderID_Data = $element->getElementsByTagName('OrderID')->item(0);

                    if (@$array[OrderID] == $OrderID_Data->nodeValue) {
                        $SessionID_Data = $element->getElementsByTagName('SessionID')->item(0);
                        $Status_Data = $element->getElementsByTagName('Status')->item(0);
                        $ApprovalCode_Data = $element->getElementsByTagName('ApprovalCode')->item(0);
                        $PAN_Data = $element->getElementsByTagName('PAN')->item(0);

                        // Call GetOrderInformation Operation for getting Order details
                        $data = '<?xml version="1.0" encoding="UTF-8"?>';
                        $data .= '<TKKPG>';
                        $data .= '<Request>';
                        $data .= '<Operation>GetOrderInformation</Operation>';
                        $data .= '<Language>EN</Language>';
                        $data .= '<Order>';
                        $data .= '<Merchant>11122333</Merchant>';
                        $data .= '<OrderID>' . $OrderID_Data->nodeValue . '</OrderID>';
                        $data .= '</Order>';
                        $data .= '<SessionID>' . $SessionID_Data->nodeValue . '</SessionID>';
                        $data .= '<ShowParams>true</ShowParams>';
                        $data .= '<ShowOperations>false</ShowOperations>';
                        $data .= '<ClassicView>true</ClassicView>';
                        $data .= '</Request></TKKPG>';

                        $xml = PostQW($data);

                        $Orderstatus = $xml->Response->Order->row->Orderstatus;

                        //Extract additional parameters for verification
                        foreach ($xml->Response->Order->row->OrderParams->row as $item) {
                            if ($item->PARAMNAME == 'PAN') {
                                $PAN = $item->VAL;
                            }
                        }

                        //Replace old element with new
                        $element->replaceChild($Status_Data, $Status_Data);
                        $element->replaceChild($ApprovalCode_Data, $ApprovalCode_Data);
                        $element->replaceChild($PAN_Data, $PAN_Data);

                        //Assign element with new value
                        $Status_Data->nodeValue = $Orderstatus;
                        $ApprovalCode_Data->nodeValue = @$array[ApprovalCode];
                        $PAN_Data->nodeValue = $PAN;
                        $xmlData->save('Order.xml');
                    }
                    //dd($array['OrderStatus']);

                    try {
                        $data = Session::all();
                        $user_id = !empty($data['user_details']['user_id']) ? $data['user_details']['user_id'][0] : null;
                        $order_detail_created = order_detail_create($data, $request->get('rand'), $request->get('secret_key'), $user_id);
                        $orders_master_attributes = order_master_create($data, $request->get('rand'), $request->get('secret_key'), $user_id);

                        if ($order_detail_created != null && $orders_master_attributes) {
                            $orderplacing = $this->ordersmaster->create($orders_master_attributes);
                            $orders_master = $this->ordersmaster->getById($orderplacing->id);
                            $orders_master = $this->ordersmaster->getById($orders_master->id);
                            $user_id = $this->user->getById($orders_master->user_id);
                            //dd($user_id);

                           // $mm = adminSMSConfig($this->paymentsetting->getById(1));
                            //$admins_nos = implode(',', $mm);

                            if ($array['OrderStatus'] == 'APPROVED') {
                                $ordersmaster = $this->ordersmaster->update($orders_master->id, ['payment_term_status' => 'Approved', 'pur']);
                                $msg = 'Transaction is Falied';
                            } elseif ($orders_master->payment_term_status == 'Processing' || $orders_master->payment_term_status == 'Complete') {
                                $msg = 'Transaction is already Successful';
                            } else {
                                $msg = 'Transaction is Invalid';
                            }
                            //dd($orders_master->order_random);

                            $orders_detail = $this->ordersdetail->getProductBySecretKey([
                                'random_code' => $orders_master->order_random,
                                'secret_key' => $orders_master->secret_key
                            ]);

                            //$msg_for_customer = 'Order has been placed. Order ID: ' . $orderplacing->id;
                            //sendSMS($orders_master_attributes['phone'] . ',' . $admins_nos, $msg_for_customer);

                            $send_sms_phone_number   = explode(',',strip_tags(dynamic_widget($widgets, ['id' => 6])));
                            $send_sms_phone_number[] = $orders_master_attributes['phone'];
                            $msg_for_customer = strip_tags(dynamic_widget($widgets, ['id' => 7]));

                            send_sms_formatting($send_sms_phone_number,$orders_master->id,$orders_master_attributes['customer_name'],$msg_for_customer);
                            //redirect('/checkout/success?order_id=' . $orderplacing->id . '&random_code=' . $request->get('rand') . '&secret_key=' . $request->get('secret_key'))->send();

                            // return view transfer kora holo akhane theke
                        }
                    } catch (\Illuminate\Database\QueryException $ex) {
                        $errorCode = $ex->errorInfo[1];
                        if ($errorCode == '1062') {
                            return back()->with('failed', $ex->errorInfo[2]);
                        } else {
                            return back()->with('failed', 'Something went wrong');
                        }
                    }
                } else {
                }
            } elseif ($data['payment_method']['payment_method'] == 'debitcredit' || $data['payment_method']['payment_method'] == 'mobilebanking') {
                // dd($request->all());
                if (!empty($request->tran_id)) {
                    $tran_id = $request->tran_id;
                    $orders_master = $this->ordersmaster->getById($request->value_a);
                    //dd($orders_master);
                    $sslc = new SSLCommerz();

                    if ($orders_master->payment_term_status == 'Pending') {
                        $validation = $sslc->orderValidate($tran_id, $orders_master->grand_total, $orders_master->currency, $request->all());
                        // dd($validation);
                        if ($validation == true) {
                            /**
                             * That means IPN did not work or IPN URL was not set in your merchant panel. Here you need to update order status in order table as Processing or Complete.
                             * Here you can also sent sms or email for successfull transaction to customer
                             */

                            $old_status = $orders_master->payment_term_status ?? null;
                            $old_order_status = $orders_master->order_status ?? null;
                            $orders_master = $this->ordersmaster->update($request->value_a, ['payment_term_status' => 'Successful', 'order_status' => 'placed', 'trans_id' => $tran_id]);

                            try {
                                ActivityLog::create([
                                    'user_id' => $orders_master->user_id ?? null,
                                    'action' => 'payment_status_update_gateway',
                                    'entity_type' => 'orders_master',
                                    'entity_id' => $orders_master->id ?? $request->value_a,
                                    'old_values' => ['payment_term_status' => $old_status, 'order_status' => $old_order_status],
                                    'new_values' => ['payment_term_status' => 'Successful', 'order_status' => 'placed'],
                                    'note' => 'SSLCommerz validation update',
                                    'ip' => $request->ip(),
                                    'url' => $request->fullUrl()
                                ]);
                            } catch (\Exception $e) {
                                // Logging failure should not break payment flow
                            }

                            if (!empty($orders_master)) {
                                /*m = adminSMSConfig($this->paymentsetting->getById(1));
                                $admins_nos = implode(',', $mm);
                                $msg_for_customer = 'Order has been placed. Order ID: ' . $orders_master->id;
                                sendSMS($orders_master->phone . ',' . $admins_nos, $msg_for_customer);*/

                            $send_sms_phone_number   = explode(',',strip_tags(dynamic_widget($widgets, ['id' => 6])));
                            $send_sms_phone_number[] = $orders_master->phone;
                            $msg_for_customer = strip_tags(dynamic_widget($widgets, ['id' => 7]));
                            send_sms_formatting($send_sms_phone_number,$orders_master->id,$orders_master->customer_name,$msg_for_customer);

                            }
                            $msg = 'Transaction is successfully Complete';
                        } else {
                            /**
                             * That means IPN did not work or IPN URL was not set in your merchant panel and Transation validation failed.
                             * Here you need to update order status as Failed in order table.
                             */
                            $old_status = $orders_master->payment_term_status ?? null;
                            $orders_master = $this->ordersmaster->update($request->value_a, ['payment_term_status' => 'Failed', 'trans_id' => $tran_id]);

                            try {
                                ActivityLog::create([
                                    'user_id' => $orders_master->user_id ?? null,
                                    'action' => 'payment_status_update_gateway',
                                    'entity_type' => 'orders_master',
                                    'entity_id' => $orders_master->id ?? $request->value_a,
                                    'old_values' => ['payment_term_status' => $old_status],
                                    'new_values' => ['payment_term_status' => 'Failed'],
                                    'note' => 'SSLCommerz validation failed',
                                    'ip' => $request->ip(),
                                    'url' => $request->fullUrl()
                                ]);
                            } catch (\Exception $e) {
                                // Logging failure should not break payment flow
                            }
                            $msg = 'Validation Fail';
                        }
                    } elseif ($orders_master->payment_term_status == 'Processing' || $orders_master->payment_term_status == 'Complete') {
                        /** That means through IPN Order status already updated.
                         * Now you can just show the customer that transaction is completed.
                         * No need to udate database.
                         */
                        $msg = 'Transaction is successfully Complete';
                    } else {
                        //That means something wrong happened. You can redirect customer to your product page.
                        $msg = 'Invalid Transaction';
                    }
                    //dd($orders_detail);
                }
            }
        }

        //dd($data);

        //$msg_for_customer = 'Order has been placed. Order ID: ' . $orders_master->id;

        //sendSMS($orders_master_attributes['phone'] . ',' . $admins_nos, $msg_for_customer);
        return view('frontend.products.payment-success')
            ->with([
                'settings' => $settings,
                'paymentsetting' => $paymentsetting->first(),
                'widgets' => $widgets,
                'orders_master' => $orders_master,
                'orders_detail' => $orders_detail,
                'data' => $data,
                'success' => !empty($msg) ? $msg : 'Order has been placed successfully'
            ]);
    }

    /**
     * @param Request $request
     */
    public function fail(Request $request)
    {
        $data = Session::all();

        if ($data['payment_method']['payment_method'] == 'bkash') {
        } elseif ($data['payment_method']['payment_method'] == 'debitcredit') {
            $tran_id = $request->tran_id;
            $orders_master = $this->ordersmaster->getById($request->value_a);

            if ($orders_master->order_status == 'Pending') {
                $update_product = $this->ordersmaster->update($request->value_a, ['order_status' => 'Failed', 'trans_id' => $tran_id]);
                $msg = 'Transaction is Falied';
            } elseif ($orders_master->order_status == 'Processing' || $orders_master->order_status == 'Complete') {
                $msg = 'Transaction is already Successful';
            } else {
                $msg = 'Transaction is Invalid';
            }

            $settings = $this->setting->getAll();
            $paymentsetting = $this->paymentsetting->getAll();
            $widgets = $this->dashboard->getAll();
            $orders_master = $this->ordersmaster->getById($request->value_a);
            $data = $this->user->getById($orders_master->user_id);
        } elseif ($data['payment_method']['payment_method'] == 'citybank') {
            if ($request->get('xmlmsg') != '') {
                $xmlResponse = simplexml_load_string($request->get('xmlmsg'));
                $json = json_encode($xmlResponse);
                $array = json_decode($json, true);

                //Update existing Order XML by Declined Status
                $xmlData = new DOMDocument('1.0', 'utf-8');
                $xmlData->formatOutput = true;
                $xmlData->preserveWhiteSpace = false;
                $xmlData->load(url('Order.xml'));

                //Get item element
                $element = $xmlData->getElementsByTagName('Order')->item(0);

                //Load child element
                $OrderID_Data = $element->getElementsByTagName('OrderID')->item(0);

                if (@$array[OrderID] == $OrderID_Data->nodeValue) {
                    $SessionID_Data = $element->getElementsByTagName('SessionID')->item(0);
                    $Status_Data = $element->getElementsByTagName('Status')->item(0);
                    $ApprovalCode_Data = $element->getElementsByTagName('ApprovalCode')->item(0);
                    $PAN_Data = $element->getElementsByTagName('PAN')->item(0);

                    // Call GetOrderInformation Operation for getting Order details
                    $data = '<?xml version="1.0" encoding="UTF-8"?>';
                    $data .= '<TKKPG>';
                    $data .= '<Request>';
                    $data .= '<Operation>GetOrderInformation</Operation>';
                    $data .= '<Language>EN</Language>';
                    $data .= '<Order>';
                    $data .= '<Merchant>11122333</Merchant>';
                    $data .= '<OrderID>' . $OrderID_Data->nodeValue . '</OrderID>';
                    $data .= '</Order>';
                    $data .= '<SessionID>' . $SessionID_Data->nodeValue . '</SessionID>';

                    $data .= '<ShowParams>true</ShowParams>';
                    $data .= '<ShowOperations>false</ShowOperations>';
                    $data .= '<ClassicView>true</ClassicView>';
                    $data .= '</Request></TKKPG>';

                    $xml = PostQW($data);

                    $Orderstatus = $xml->Response->Order->row->Orderstatus;

                    //Extract additional parameters for verification
                    foreach ($xml->Response->Order->row->OrderParams->row as $item) {
                        if ($item->PARAMNAME == 'PAN') {
                            $PAN = $item->VAL;
                        }
                    }

                    //Replace old element with new
                    $element->replaceChild($Status_Data, $Status_Data);
                    $element->replaceChild($ApprovalCode_Data, $ApprovalCode_Data);
                    $element->replaceChild($PAN_Data, $PAN_Data);

                    //Assign element with new value
                    $Status_Data->nodeValue = $Orderstatus;
                    $ApprovalCode_Data->nodeValue = '';
                    $PAN_Data->nodeValue = @PAN;
                    $xmlData->save('Order.xml');
                }

                //dd($array);

                try {
                    $data = Session::all();

                    $user_id = !empty($data['user_details']['user_id']) ? $data['user_details']['user_id'][0] : null;
                    $order_detail_created = order_detail_create($data, $request->get('rand'), $request->get('secret_key'), $user_id);
                    $orders_master_attributes = order_master_create($data, $request->get('rand'), $request->get('secret_key'), $user_id);

                    if ($order_detail_created != null && $orders_master_attributes) {
                        $orderplacing = $this->ordersmaster->create($orders_master_attributes);
                        $orders_master = $this->ordersmaster->getById($orderplacing->id);
                        $orders_master = $this->ordersmaster->getById($orders_master->id);
                        $user_id = $this->user->getById($orders_master->user_id);
                        //dd($user_id);

                        if ($array['OrderStatus'] == 'DECLINED') {
                            $update_product = $this->ordersmaster->update($orders_master->id, ['order_status' => 'DECLINED', 'pur']);
                            $msg = 'Transaction is Falied';
                        } elseif ($orders_master->order_status == 'Processing' || $orders_master->order_status == 'Complete') {
                            $msg = 'Transaction is already Successful';
                        } else {
                            $msg = 'Transaction is Invalid';
                        }
                        //dd($orders_master->order_random);
                    }
                } catch (\Illuminate\Database\QueryException $ex) {
                    $errorCode = $ex->errorInfo[1];
                    if ($errorCode == '1062') {
                        return back()->with('failed', $ex->errorInfo[2]);
                    } else {
                        return back()->with('failed', 'Something went wrong');
                    }
                }
            }
        }

        $orders_detail = $this->ordersdetail->getProductBySecretKey([
            'random_code' => $orders_master->order_random,
            'secret_key' => $orders_master->secret_key
        ]);
        $settings = $this->setting->getAll();
        $paymentsetting = $this->paymentsetting->getAll();
        $widgets = $this->dashboard->getAll();
        return view('frontend.products.payment-fail')
            ->with([
                'settings' => $settings,
                'paymentsetting' => $paymentsetting->first(),
                'widgets' => $widgets,
                'orders_master' => $orders_master,
                'orders_detail' => $orders_detail,
                'success' => $msg
            ]);
    }

    /**
     * @param Request $request
     */
    public function cancel(Request $request)
    {
        $data = Session::all();

        if ($data['payment_method']['payment_method'] == 'debitcredit') {
            $tran_id = $request->tran_id;
            $orders_master = $this->ordersmaster->getById($request->value_a);
            //dd($orders_master);

            if ($orders_master->order_status == 'Pending') {
                $update_product = $this->ordersmaster->update($request->value_a, ['order_status' => 'Canceled', 'trans_id' => $tran_id]);
                $msg = 'Transaction is Cancel';
            } elseif ($orders_master->order_status == 'Processing' || $orders_master->order_status == 'Complete') {
                $msg = 'Transaction is already Successful';
            } else {
                $msg = 'Transaction is Invalid';
            }

            $settings = $this->setting->getAll();
            $paymentsetting = $this->paymentsetting->getAll();
            $widgets = $this->dashboard->getAll();
            $orders_master = $this->ordersmaster->getById($request->value_a);
            $data = $this->user->getById($orders_master->user_id);
            //dd($data);

            $orders_detail = $this->ordersdetail->getProductBySecretKey([
                'random_code' => $request->get('random_code'),
                'secret_key' => $orders_master->secret_key
            ]);
            //dd($orders_detail);

            return view('frontend.products.payment-cancel')
                ->with([
                    'settings' => $settings,
                    'paymentsetting' => $paymentsetting->first(),
                    'widgets' => $widgets,
                    'orders_master' => $orders_master,
                    'orders_detail' => $orders_detail,
                    'success' => $msg
                ]);
        } elseif ($data['payment_method']['payment_method'] == 'citybank') {
            if ($request->get('xmlmsg') != '') {
                $xmlResponse = simplexml_load_string($request->get('xmlmsg'));
                $json = json_encode($xmlResponse);
                $array = json_decode($json, true);

                //Update existing Order XML by Declined Status
                $xmlData = new DOMDocument('1.0', 'utf-8');
                $xmlData->formatOutput = true;
                $xmlData->preserveWhiteSpace = false;
                $xmlData->load(url('Order.xml'));

                //Get item element
                $element = $xmlData->getElementsByTagName('Order')->item(0);

                //Load child element
                $OrderID_Data = $element->getElementsByTagName('OrderID')->item(0);

                if (@$array[OrderID] == $OrderID_Data->nodeValue) {
                    $SessionID_Data = $element->getElementsByTagName('SessionID')->item(0);
                    $Status_Data = $element->getElementsByTagName('Status')->item(0);
                    $ApprovalCode_Data = $element->getElementsByTagName('ApprovalCode')->item(0);
                    $PAN_Data = $element->getElementsByTagName('PAN')->item(0);

                    // Call GetOrderInformation Operation for getting Order details
                    $data = '<?xml version="1.0" encoding="UTF-8"?>';
                    $data .= '<TKKPG>';
                    $data .= '<Request>';
                    $data .= '<Operation>GetOrderInformation</Operation>';
                    $data .= '<Language>EN</Language>';
                    $data .= '<Order>';
                    $data .= '<Merchant>11122333</Merchant>';
                    $data .= '<OrderID>' . $OrderID_Data->nodeValue . '</OrderID>';
                    $data .= '</Order>';
                    $data .= '<SessionID>' . $SessionID_Data->nodeValue . '</SessionID>';

                    $data .= '<ShowParams>true</ShowParams>';
                    $data .= '<ShowOperations>false</ShowOperations>';
                    $data .= '<ClassicView>true</ClassicView>';
                    $data .= '</Request></TKKPG>';

                    $xml = PostQW($data);

                    $Orderstatus = $xml->Response->Order->row->Orderstatus;

                    //Extract additional parameters for verification
                    foreach ($xml->Response->Order->row->OrderParams->row as $item) {
                        if ($item->PARAMNAME == 'PAN') {
                            $PAN = $item->VAL;
                        }
                    }

                    //Replace old element with new
                    $element->replaceChild($Status_Data, $Status_Data);
                    $element->replaceChild($ApprovalCode_Data, $ApprovalCode_Data);
                    $element->replaceChild($PAN_Data, $PAN_Data);

                    //Assign element with new value
                    $Status_Data->nodeValue = $Orderstatus;
                    $ApprovalCode_Data->nodeValue = '';
                    $PAN_Data->nodeValue = @PAN;
                    $xmlData->save('Order.xml');
                }

                //dd($array);

                try {
                    $data = Session::all();

                    $user_id = !empty($data['user_details']['user_id']) ? $data['user_details']['user_id'][0] : null;
                    $order_detail_created = order_detail_create($data, $request->get('rand'), $request->get('secret_key'), $user_id);
                    $orders_master_attributes = order_master_create($data, $request->get('rand'), $request->get('secret_key'), $user_id);

                    if ($order_detail_created != null && $orders_master_attributes) {
                        $orderplacing = $this->ordersmaster->create($orders_master_attributes);
                        $orders_master = $this->ordersmaster->getById($orderplacing->id);
                        $orders_master = $this->ordersmaster->getById($orders_master->id);
                        $user_id = $this->user->getById($orders_master->user_id);
                        //dd($user_id);

                        if ($array['OrderStatus'] == 'CANCELED') {
                            $update_product = $this->ordersmaster->update($orders_master->id, ['order_status' => 'CANCELED']);
                            $msg = 'Transaction is Falied';
                        } elseif ($orders_master->order_status == 'Processing' || $orders_master->order_status == 'Complete') {
                            $msg = 'Transaction is already Successful';
                        } else {
                            $msg = 'Transaction is Invalid';
                        }
                        //dd($orders_master->order_random);

                        $orders_detail = $this->ordersdetail->getProductBySecretKey([
                            'random_code' => $orders_master->order_random,
                            'secret_key' => $orders_master->secret_key
                        ]);
                        $settings = $this->setting->getAll();
                        $paymentsetting = $this->paymentsetting->getAll();
                        $widgets = $this->dashboard->getAll();
                        return view('frontend.products.payment-cancel')
                            ->with([
                                'settings' => $settings,
                                'paymentsetting' => $paymentsetting->first(),
                                'widgets' => $widgets,
                                'orders_master' => $orders_master,
                                'orders_detail' => $orders_detail,
                                'success' => $msg
                            ]);
                    }

                    $settings = $this->setting->getAll();
                    $paymentsetting = $this->paymentsetting->getAll();
                    $widgets = $this->dashboard->getAll();
                    return view('frontend.products.payment-cancel')
                        ->with([
                            'settings' => $settings,
                            'paymentsetting' => $paymentsetting->first(),
                            'widgets' => $widgets,
                            'success' => 'Payment declined'
                        ]);
                } catch (\Illuminate\Database\QueryException $ex) {
                    $errorCode = $ex->errorInfo[1];
                    if ($errorCode == '1062') {
                        return back()->with('failed', $ex->errorInfo[2]);
                    } else {
                        return back()->with('failed', 'Something went wrong');
                    }
                }
            }
        }
    }

    /**
     * @param Request $request
     */
    public function ipn(Request $request)
    {
        // return response()->json($request->all());
        //Received all the payement information from the gateway
        if ($request->input('tran_id')) { //Check transation id is posted or not.
            $tran_id = $request->input('tran_id');
            //Check order status in order tabel against the transaction id or order id.
            $order_details = DB::table('orders')
                ->where('order_id', $tran_id)
                ->select('order_id', 'order_status', 'currency', 'grand_total')->first();
            if ($order_details->order_status == 'Pending') {
                $sslc = new SSLCommerz();
                $validation = $sslc->orderValidate($tran_id, $order_details->grand_total, $order_details->currency, $request->all());
                if ($validation == true) {
                    /*
                    That means IPN worked. Here you need to update order status
                    in order table as Processing or Complete.
                    Here you can also sent sms or email for successfull transaction to customer
                    */
                    $update_product = DB::table('orders')
                        ->where('order_id', $tran_id)
                        ->update(['order_status' => 'Processing']);

                    echo 'Transaction is successfully Complete';
                } else {
                    /*
                    That means IPN worked, but Transation validation failed.
                    Here you need to update order status as Failed in order table.
                    */
                    $update_product = DB::table('orders')
                        ->where('order_id', $tran_id)
                        ->update(['order_status' => 'Failed']);

                    echo 'validation Fail';
                }
            } elseif ($order_details->order_status == 'Processing' || $order_details->order_status == 'Complete') {
                //That means Order status already updated. No need to udate database.

                echo 'Transaction is already successfully Complete';
            } else {
                //That means something wrong happened. You can redirect customer to your product page.

                echo 'Invalid Transaction';
            }
        } else {
            echo 'Inavalid Data';
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function pdf_invoice(Request $request)
    {
        //dd($request);
        $settings = $this->setting->getAll();
        $paymentsetting = $this->paymentsetting->getAll();
        $widgets = $this->dashboard->getAll();
        $orders_master = $this->ordersmaster->getById($request->get('order_id'));
        $data = $this->user->getById($orders_master->user_id);

        $orders_detail = $this->ordersdetail->getProductBySecretKey([
            'random_code' => $request->get('random_code'),
            'secret_key' => $request->get('secret_key')
        ]);

        $data = [
            'settings' => $settings[0],
            'paymentsetting' => $paymentsetting->first(),
            'widgets' => $widgets,
            'orders_master' => $orders_master,
            'orders_detail' => $orders_detail,
            'data' => $data
        ];

        $pdf = PDF::loadView('pdf.invoice', $data);
        $date = date('Ymds');
        //return $pdf->download('invoice' . $date . '.pdf');
        return $pdf->stream('invoice' . $date . '.pdf');
    }

    /**
     * @param $data
     * @return string
     */
    public function payment_success($data)
    {
        //dd($data);
        if (!empty($data)) {
            if ($data['payment_method']['payment_method'] == 'cash_on_delivery') {
                return 'Your order has been placed. We will contact you shortly. Pay to delivery person once your order arrived at your home.';
            } else {
                return 'Payment has been successful. Your order has been placed. We will contact you shortly.';
            }
        }
    }

    /** Shopping Cart Part */

    /**
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function mini_cart()
    {
        $tksign = '&#2547; ';

        if (Session::has('cart')) {
            $oldcart = Session::get('cart');
            $cart = new Cart($oldcart);

            //dd($cart);

            $html = null;
            //$html .= '<div class="hide-cardd-icone"><i class="fa fa-angle-right"></i></div>';
            $p = [];
            if ($cart->items != null) {
                foreach ($cart->items as $product) {
                    //dd($product);
                    $p[] = $product['qty'] * $product['item']['purchaseprice'];
                }
            }
            $totalp = array_sum($p);

            $html .= '<div class="list-group-one"><div class="cart-list-cpt"><h2><span><i class="fa fa-shopping-cart"></i></span>Order List<span> (' . $tksign . number_format($totalp) . ')</span></h2 >';
            $html .= '<h1>Items</h1>';
            $html .= '</div>';
            $html .= '<table class="table table-responsive minicart">';

            $price = [];
            if ($cart->items != null) {
                foreach ($cart->items as $product) {
                    $price[] = $product['qty'] * $product['item']['purchaseprice'];

                    //dd($product);
                    $maininfo = $this->product->getById($product['item']['productid']);
                    $html .= '<tr>';
                    $html .= '<td style="width: 8%;">';
                    $html .= '<div class="list-item-img">';
                    $html .= '<img src="' . get_first_product_image($product['item']['productid'], $maininfo) . '" class="img-responsive" />';
                    $html .= '</div>';
                    $html .= '</td>';
                    $html .= '<td style="width: 45%;"><span class="item-name">' . $maininfo->title . '</span></td>';
                    $html .= '<td style="width: 15%;"><span class="item-qty">x ' . $product['qty'] . '</span></td>';
                    $html .= '<td style="width: 30%;"><span class="item-price"> ' . $tksign . number_format(($product['qty'] * $product['item']['purchaseprice'])) . ' </span></td>';
                    $html .= '<td style="width: 2%;"><span class="item-del"><a href="javascript:void(0)" onclick="remove_cart_item(' . $product['item']['productid'] . ', ' . $product['item']['productcode'] . ')"><i class="fa fa-times"></i></a></span></td>';
                    $html .= '</tr>';
                }
            }
            $totalprice = array_sum($price);

            $html .= '</table >';
            $html .= '</div>';
            $html .= '<div class="price-next"><div class="all-price"><p> Total: <span> ' . $tksign . number_format($totalprice) . ' </span></p ></div>';
            $html .= '<div class="cheack-button"><a href="' . url('/view_cart') . '"> NEXT STEP </a></div>';
            $html .= '</div>';

            //dump($cart->totalqty);
            //dump($cart->totalprice);
            //dump($cart->items);
            //die();

            return response()->json(['data' => $html, 'total_price' => $totalprice]);
        } else {
            $html = null;
            //$html .= '';

            $html .= '<div class="list-group-one">';
            $html .= '<div class="cart-list-cpt">';
            $html .= '<h2><span><i class="fa fa-shopping-cart"></i></span> Order List<span> (0)</span></h2>';
            $html .= '<h1>Items</h1>';
            $html .= '</div>';

            return response()->json(['data' => $html, 'total_price' => 0]);
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function mini_compare()
    {
        if (Session::has('comparison')) {
            $oldcomparison = Session::get('comparison');
            $cart = new Comparison($oldcomparison);

            $html = null;

            $html .= 'Product added to compare list';

            return response()->json(['data' => $html]);
        } else {
            $html = null;
            $html .= '<div class="hide-cardd-area"><div class="hide-cardd-icone"><i class="fa fa-angle-right"></i></div></div>';

            $html .= '<div class="list-group-one">';
            $html .= '<div class="cart-list-cpt">';
            $html .= '<h2><span><i class="fa fa-shopping-cart"></i></span>Order List<span> (0)</span><span class="barclose"><i class="fa fa-times"></i></span></h2>';
            $html .= '<h1>Items</h1>';
            $html .= '</div>';

            return response()->json(['data' => $html, 'total_price' => 0]);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function add_to_compare(Request $request)
    {
        //dd($request);
        $product = [
            'productid' => $request->get('productid'),
            'productcode' => $request->get('productcode'),
            'seo_url' => $request->get('seo_url'),
            'multi_id' => $request->get('multi_id'),
        ];
        $oldcompare = Session::has('comparison') ? Session::get('comparison') : null;
        $compare = new Comparison($oldcompare);
        $compare->add($product, $request->get('productcode'));
        $request->session()->put('comparison', $compare);

        $pro = $this->product->getById($request->get('productid'));
        $first_image = \App\ProductImages::where('main_pid', $pro->id)->where('is_main_image', 1)->get()->first();

        $regularprice = $pro->local_selling_price;
        $save = ($pro->local_selling_price * $pro->local_discount) / 100;
        $sp = $regularprice - $save;
        $tksign = '&#2547; ';

        if ($sp < $regularprice) {
            $price = '<span class="price-new">' . $tksign . number_format($sp) . '</span>';
            $price .= '<span class="price-old">' . $tksign . number_format($sp) . '</span>';
        } else {
            $price = '<span class="price-new">' . $tksign . number_format($sp) . '</span>';
        }

        // left portion
        $html = '<div class="product-view quickview-w">';
        $html .= '<div class="left-content-product">';
        $html .= '<div class="row">';
        $html .= '<div class="col-md-12">';
        $html .= '<h4 class="title-area-cart-v"><i class="fa fa-plus"></i> 1 new item have been added to your compare list</h4>';
        $html .= '</div>';
        $html .= '<div class="col-sm-2">';
        $html .= '<div class="cart-img">';
        $html .= '<a href="' . product_seo_url($pro->seo_url, $pro->id) . '" target="_self" title="' . $pro->title . '">';
        if (!empty($first_image)) {
            $html .= '<img src="' . asset($first_image->icon_size_directory) . '" alt="' . $pro->title . '">';
        }
        $html .= '</a>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div class="col-sm-10">';
        $html .= '<div class="content-product-right content-product-right1">';

        $html .= '<div class=cart-prouct-title><h1>' . $pro->title . '</h1></div>';
        $html .= '<div class="product-label form-group">';
        $html .= '<div class="product_page_price1 price" itemprop="offerDetails" itemscope="" itemtype="">' . $price . '</div></div>';
        $html .= '</div>';

        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';

        $newcompare = Session::has('comparison') ? Session::get('comparison') : null;
        $com = new Comparison($newcompare);
        $total_com = count($com->items);
        //dd($total_com);

        return response()->json(['data' => $html, 'total_item' => $total_com]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function add_to_wishlist(Request $request)
    {
        // return $request->all();
        if (auth()->check()) {
            $data = [
                'user_id' => Auth::user()->id,
                'product_id' => request('product_id')
            ];
            $exist = Wishlist::where('product_id', request('product_id'))->get()->first();

            if ($exist) {
                return response()->json([
                    'message' => 'You have already added!',
                    'type' => 2,
                    'success' => false
                ]);
            }

            $wishlist = Wishlist::create($data);

            $total = Wishlist::where('user_id', auth()->user()->id)->count();

            if ($wishlist) {

                $product = $this->product->getById($request->product_id);
                $categories = $this->product->getProductCategories($request->product_id);
                $cat_info = \App\Term::where('id', $categories[0]['term_id'])->get()->first();
                $cat_name = $cat_info['name']??'';

                return response()->json([
                    'message' => 'Data has been added.',
                    'total' => $total,
                    'type' => 1,
                    'success' => true,
                    'product' => [
                        'name' => $product->title,
                        'id' => $product->id,
                        'cat_name' => $cat_name
                        ]
                ]);
            } else {
            }
        } else {

            return response()->json([
                'message' => 'Please Login First!',
                'type' => 3,
                'success' => false
            ]);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove_compare_product(Request $request)
    {
        $id = $request->get('productid');
        $code = $request->get('productcode');

        $oldcomp = Session::has('comparison') ? Session::get('comparison') : null;
        $comp = new Comparison($oldcomp);
        unset($comp->items[$code]);
        $request->session()->put('comparison', $comp);

        return response()->json([
            'success' => true
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove_wishlist_product(Request $request)
    {
        $wishlist = Wishlist::where('product_id', request('productid'))->get()->first();
        $wishlist->delete();

        return back();
    }

    /** Shopping Cart Part */

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view_compare()
    {
        $settings = $this->setting->getAll();
        $widgets = $this->dashboard->getAll();

        if (!Session::has('comparison')) {
            return view('frontend.products.comparison', ['settings' => $settings, 'widgets' => $widgets, 'comparison' => null]);
        }

        $oldcompare = Session::get('comparison');
        $compare = new Comparison($oldcompare);

        //dd($cart);

        return view('frontend.products.comparison')
            ->with(['settings' => $settings, 'comparison' => $compare->items, 'widgets' => $widgets]);
    }

    /** Shopping Cart Part */

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view_wishlist()
    {
        $settings = $this->setting->getAll();
        $widgets = $this->dashboard->getAll();

        if (!Session::has('wishlist')) {
            return view('frontend.products.wishlist', ['settings' => $settings, 'widgets' => $widgets, 'wishlist' => null]);
        }

        $oldwishlist = Session::get('wishlist');
        $wish = new Wishlist($oldwishlist);

        //dd($cart);

        return view('frontend.products.wishlist')
            ->with([
                'settings' => $settings,
                'wishlist' => $wish->items,
                'widgets' => $widgets
            ]);
    }

    public function flash_products()
    {
        $flash_rule = [
            'fs_is_active' => 1
        ];

        $tomorrow = date('Y-m-d', strtotime(date('Y-m-d') . '+1 days'));

        //dump($tomorrow);

        $flash_schedule = \App\FlashShedule::where($flash_rule)
            // ->whereDate('fs_end_date', '<=', 'NOW()')
            // ->whereDate('fs_start_date', '<=', $tomorrow)
            ->whereRaw('UNIX_TIMESTAMP(fs_start_date) <= UNIX_TIMESTAMP() AND UNIX_TIMESTAMP(fs_end_date) >= UNIX_TIMESTAMP()')
            ->orderBy('fs_start_date', 'ASC')
            ->get();

        // dd($flash_schedule);

        $settings = $this->setting->getAll();
        $widgets = $this->dashboard->getAll();

        return view('frontend.products.flash_products')
            ->with(['settings' => $settings, 'widgets' => $widgets, 'flash_schedule' => $flash_schedule]);
    }

    public function save_review(Request $request)
    {
        // dd($request);
        $validator = Validator::make(
            $request->all(),
            [
                'rating' => 'required',
                'product_id' => 'required',
                'user_id' => 'required',
                'vendor_id' => 'required'
            ]
        );
        $product_info = \App\Product::where(['id' => $request->get('product_id')])->get();

        // process the login
        if ($validator->fails()) {
            return redirect('product/' . $product_info[0]->seo_url)
                ->withErrors($validator)
                ->withInput();
        } else {
            // store
            if ($request->get('id')) {
                $attributes = [
                    'rating' => $request->get('rating'),
                    'comment' => $request->get('comment'),
                ];

                //dd($attributes);

                try {
                    $review = $this->review->update($request->get('id'), $attributes);
                    //dd($product);
                    return redirect()->back()->with('success', 'Successfully save changed');
                } catch (\Illuminate\Database\QueryException $ex) {
                    return redirect()->back()->withErrors($ex->getMessage());
                }
            } else {
                $attributes = [
                    'user_id' => $request->get('user_id'),
                    'product_id' => $request->get('product_id'),
                    'rating' => $request->get('rating'),
                    'comment' => $request->get('comment'),
                    'vendor_id' => $request->get('vendor_id'),
                    'is_active' => 1
                ];

                //dd($attributes);

                try {
                    $review = $this->review->create($attributes);
                    //dd($product);
                    return redirect()->back()->with('success', 'Successfully save changed');
                } catch (\Illuminate\Database\QueryException $ex) {
                    return redirect()->back()->withErrors($ex->getMessage());
                }
            }
        }
    }


    /**
     * @param Request $request
     */
    public function apply_coupon_voucher(Request $request)
    {
        if ($request->get('apply_coupon')) {
            $coupon_code = $request->get('coupon_code');
            $coupon_type = 'Coupon';

            coupon_voucher_verify($coupon_type, $coupon_code);
        } elseif ($request->get('apply_voucher')) {
            $coupon_code = $request->get('voucher_code');
            $coupon_type = 'Voucher';

            coupon_voucher_verify($coupon_type, $coupon_code);
        } elseif ($request->get('remove_coupon')) {
            Session::forget('my_coupon');
        }

        return redirect('/view_cart');
    }

    public function get_tab_data(Request $request)
    {
        $tksign = '&#2547; ';

        if ($request->get('type') == 'new_arrival') {
            $product = \App\Product::where('new_arrival', 'on')->orderBy('id', 'desc')->limit(8)->get()->toArray();
        } elseif ($request->get('type') == 'most_rated') {
            $product = \App\Product::where('new_arrival', 'on')->orderBy('id', 'desc')->limit(8)->get()->toArray();
        }

        $html = '';
        foreach ($product as $pro) {
            $pro = (object)$pro;
            $first_image = \App\ProductImages::where('main_pid', $pro->id)->where('is_main_image', 1)->get()->first();

            if (!empty($first_image->full_size_directory)) {
                $img = url($first_image->full_size_directory);
            } else {
                $img = url('storage/uploads/fullsize/2019-01/default.jpg');
            }
            $second_image = \App\ProductImages::where('main_pid', $pro->id)->where('is_main_image', 0)->get()->first();
            $regularprice = $pro->local_selling_price;
            $save = ($pro->local_selling_price * $pro->local_discount) / 100;
            $sp = round($regularprice - $save);

            $html .= '<div class="ltabs-items-inner ltabs-slider">';
            $html .= '<div class="item">';
            $html .= '<div class="item-inner product-layout transition product-grid">';
            $html .= '<div class="product-item-container">';
            $html .= '<div class="left-block left-b">';
            if ($pro->new_arrival == 'on') :
                $html .= '<div class="box-label">';
            $html .= '<span class="label-product label-new">New</span>';
            $html .= '</div>';
            endif;
            $html .= '<div class="product-image-container second_img">';
            $html .= '<a href="' . url('p/' . $pro->seo_url) . '" target="_self" title="' . $pro->title . '">';
            if (!empty($first_image)) :
                $html .= '<img src="' . $img . '" class="img-1 img-responsive" alt="' . $pro->title . '">';
            endif;
            if (!empty($second_image)):
                $html .= '<img src="' . url($second_image->full_size_directory) . '" class="img-2 img-responsive" alt="' . $pro->title . '">'; else:
                $html .= '<img src="' . $img . '" class="img-2 img-responsive" alt="' . $pro->title . '">';
            endif;
            $html .= '</a>';

            $html .= '</div>';
//            $html .= '<div class="so-quickview">';
//            $html .= '<a class="iframe-link btn-button quickview quickview_handler visible-lg" href="quickview.html" title="Quick view" data-fancybox-type="iframe"><i class="fa fa-eye"></i><span>Quick view</span></a>';
//            $html .= '</div>';
            $html .= '</div>';
            $html .= '<div class="right-block">';
            $html .= '<div class="button-group so-quickview cartinfo--left">';
            $html .= '<button type="button"class="addToCart" title="Add to cart"
            onclick="add_to_cart(' . $pro->id . ',' . $pro->product_code . ',' . $pro->sku . ',' . $regularprice . ',' . ($regularprice - $sp) . ',' . $sp . ', 0, null, 1);">
            <span>Add to cart</span>
            </button>';
//            $html .= '<button type="button" class="wishlist btn-button" title="Add to Wish List"
//            onclick="add_to_compare(' . $pro->id . ', ' . $pro->product_code . ', ' . $pro->seo_url . ');">';
//            $html .= '<i class="fa fa-heart-o"></i><span>Add to Wish List</span>';
//            $html .= '</button>';
            $html .= '<button type="button" class="compare btn-button" title="Compare this Product"
            onclick="add_to_compare(' . $pro->id . ', ' . $pro->product_code . ', ' . $pro->seo_url . ');">';
            $html .= '<i class="fa fa-retweet"></i><span>Compare this Product</span>';
            $html .= '</button>';
            $html .= '</div>';
            $html .= '<div class="caption hide-cont">';
            $html .= product_review($pro->id);
            $html .= '<h4><a href="product.html" title="Pastrami bacon" target="_self">Lastrami bacon</a></h4>';
            $html .= '</div>';
            $html .= '<p class="price">';
            $html .= '<span class="price-new">';
            if ($regularprice < $sp) {
                $price = '<span class="price-new product-price">' . $tksign . number_format($sp) . '</span>';
                $price .= '<span class="price-old">' . $tksign . number_format($regularprice) . '</span>';
            } else {
                $price = '<span class="price-new product-price">' . $tksign . number_format($sp) . '</span>';
            }
            $html .= $price;
            $html .= '</span>';
            $html .= '</p>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
        }

        //dd($html);
        return response()->json(['data' => $html]);
    }

    public function get_size_price_data(Request $request)
    {
        $html = '';
        if (isset($request->main_pid) && isset($request->color_codes)) {
            $gets = \App\Pcombinationdata::where(['main_pid' => $request->main_pid, 'color_codes' => $request->color_codes])->get();
            //dd($gets);

            // $data = \App\::where(['id' => $request->id])->get();
            $col_count = 1;
            foreach ($gets as $get) {
                if ($col_count == 1) {
                    $checked = 'checked';
                } else {
                    $checked = '';
                }

                $html .= '<input type="radio"
                id="size_' . $get->id . '"
                name="size_radio"
                data-size="' . $get->size . '"
                data-id="' . $get->id . '"
                data-price="' . $get->price . '"
                title="' . $get->size . '" ' . $checked . '>
                        <label for="size_' . $get->id . '">' . $get->size . '</label>';
                ++$col_count;
            }
        }
        return response()->json(['data' => $html]);
        //dd($get);
    }

    public function main_search_form(Request $request)
    {
        // dd($request);

        $settings = $this->setting->getAll();
        $posts = $this->post->getAll();
        $widgets = $this->dashboard->getAll();

        $keyword = $request->get('keyword');
        $cats = $request->get('category');

        // dd($cats);
        if ($cats != null) {
            $category = $this->term->getByAny('seo_url', $cats)->first();
            $cat = $category->seo_url;

            return redirect('/c/' . $cat . '?keyword=' . $keyword);
        } else {
            $option = [
                'keyword' => $request->get('keyword'),
                'sort_by' => $request->get('sort_by'),
                'sort_show' => $request->get('sort_show'),
                'price_min' => $request->get('price_min'),
                'price_max' => $request->get('price_max')
            ];
            $cat_info = get_search_category($option);

            $categorie = $this->term->getWhereIn($cat_info);

            // dd($categories);

            $get_fillter_product = $this->product->getProductByFilter($option, $cat_info);

            $default = [
                'type' => 'category',
                'limit' => 500,
                'offset' => 0
            ];
            $cats = $this->get_product_categories($default);
            $categories = $cats->toArray();
            //dd($settings);
            return view('frontend.products.search_results_key')
                ->with(['settings' => $settings,
                    'posts' => $posts,
                    'widgets' => $widgets,
                    'categories' => $categorie,
                    'products' => $get_fillter_product
                ]);
        }
    }

    public function main_search_product_ajax(Request $request)
    {
        if ($request->get('cat') != null || $request->get('cat') != '') {
            $category = $this->term->getByAny('seo_url', $request->get('cat'))->first();
            $cat = $category->id;
        } else {
            $cat = null;
        }

        $dufault = [
            'keyword' => $request->get('keyword'),
            'cat' => $cat
        ];

        //dd($dufault);

        $products = $this->product->get_search_product_ajax($dufault);

       // dd($products);

        $html = '<div>';
        $html .= '<ul>';

        foreach ($products as $item) {
            $url = url('/c/' . $item->t_url . '?keyword=' . $request->get('keyword'));

            $html .= '<li><a href="' . $url . '">' . $item->title . '<br><span>'. $item->sub_title . '</span> </a></li>';
        }

        $html .= '</ul>';
        $html .= '</div>';

        return response()->json(['html' => $html]);
    }

    public function get_product_pricing(Request $request)
    {
        if ($request->get('main_pid')) {
            $option = [
                'main_pid' => $request->get('main_pid'),
                'color' => (($request->get('color')) ? $request->get('color') : null),
                'size' => (($request->get('size')) ? $request->get('size') : null),
                'type' => (($request->get('type')) ? $request->get('type') : null)
            ];
            //dd($option);

            $get_data = get_product_pricing($option);

            return response()->json(['data' => $get_data]);
        }
    }

    public function track_order()
    {
        $settings = $this->setting->getAll();
        $widgets = $this->dashboard->getAll();
        $track = null;

        return view('frontend.pages.track_order', compact('track'))->with([
            'settings' => $settings,
            'widgets' => $widgets,
        ]);
    }

    public function track_order_store()
    {
        request()->validate([
            'order_number' => 'required',
            'phone_number' => 'required'
        ]);

        $track = OrdersMaster::whereId(request('order_number'))->wherePhone(request('phone_number'))->get()->first();
        // return $track;
        if ($track) {
            $detail = OrdersDetail::where('order_random', $track->order_random)->firstOrFail();

            $settings = $this->setting->getAll();
            $widgets = $this->dashboard->getAll();

            return view('frontend.pages.track_order', compact('track', 'detail'))->with([
                'settings' => $settings,
                'widgets' => $widgets,
            ]);
        } else {
            return back()->with('failed', 'Order ID or Phone Number is invalid.');
        }
    }

    public function store_location()
    {
        $settings = $this->setting->getAll();
        $posts = $this->post->getAll();
        $widgets = $this->dashboard->getAll();

        return view('frontend.pages.store_location')->with([
            'settings' => $settings,
            'posts' => $posts,
            'widgets' => $widgets,
        ]);
    }

    public function new_arrival()
    {
        $settings = $this->setting->getAll();
        $posts = $this->post->getAll();
        $widgets = $this->dashboard->getAll();

        $products = Product::where('new_arrival', 'on')->latest()->paginate(10);

        // return $products;
        return view('frontend.products.new_arrival', compact('products'))->with([
            'settings' => $settings,
            'posts' => $posts,
            'widgets' => $widgets,
        ]);
    }

    public function product_questions(Request $request, $id)
    {
        $product = Product::where('id', $id)->get();

        if ($product->count() > 0) {


            $product = $product->first();
            $settings = $this->setting->getAll();
            $widgets = $this->dashboard->getAll();

            $option = [
                'main_pid' => $product->id,
                'titel' => (($request->get('titel')) ? $request->get('titel') : null)
            ];
            $questions = $this->productQuestion->getByFilter($option);

            return view('frontend.pages.product_question', compact('product'))->with([
                'settings' => $settings,
                'widgets' => $widgets,
                'product' => $product,
                'questions' => $questions,
            ]);

        } else {
            return redirect()->back();
        }
    }

    public function product_question_post(Request $request, $id)
    {
        $product = Product::where('id', $id)->get();

        if ($product->count() > 0 && auth()->check()) {
            $product = $product->first();
           // dd($product);

            $attributes = [
                'main_pid' => $product->id,
                'user_id' => auth()->user()->id,
                'vendor_id' => $product->user_id,
                'description' => $request->get('post'),
                'qa_type' => 1,
                'que_id' => null,
                'is_active' => 1

            ];


            try {
                $this->productQuestion->create($attributes);
                return redirect()->back()->with('success', 'Successfully save changed');
            } catch (\Illuminate\Database\QueryException $ex) {
                return redirect()->back()->withErrors($ex->getMessage());
            }


        } else {
            return redirect()->back();
        }
    }

    public function product_rating(Request $request, $id)
    {
        $product = Product::where('id', $id)->get();

        if ($product->count() > 0) {
            $product = $product->first();
            $settings = $this->setting->getAll();
            $widgets = $this->dashboard->getAll();
            $option = [
                'main_pid' => $product->id,
                'titel' => (($request->get('titel')) ? $request->get('titel') : null)
            ];
            $reviews = $this->review->getByAny('product_id', $product->id);


            $review_per = auth()->check();
            if(auth()->check()){
                $buy = OrdersDetail::where(['user_id' => auth()->user()->id, 'product_id' => $product->id])->get();
                if($buy->count() > 0){
                    $review_per = true;
                }
            }


            return view('frontend.pages.product_review', compact('product'))->with([
                'settings' => $settings,
                'widgets' => $widgets,
                'product' => $product,
                'reviews' => $reviews,
                'review_per' => $review_per
            ]);

        } else {
            return redirect()->back();
        }
    }



    public function product_comments(Request $request,$id){

        $product                =  Product::findOrFail($id);
        $settings               = $this->setting->getAll();
        $widgets                = $this->dashboard->getAll();
        $comments               = $product->comments()->orderBy('created_at','DESC')->paginate(20);

        return response()->view('frontend.pages.product_comment',
         compact('product','settings','widgets','comments')
         );
    }


    public function product_comment_save(Request $request,$id){

        if(Auth::check()){
            $comment                      = new Comment;
            $comment->user_id             = Auth::id();
            $comment->item_id             = $id;
            $comment->comment_on          = 'products';
            $comment->comment             = $request->comment;
            $comment->save();
        }

        return redirect()->back()->with('status','Your comment submitted successfully.');
    }


}
