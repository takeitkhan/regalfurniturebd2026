<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Admin\PostController;
use App\Repositories\Attribute\AttributeInterface;
use App\Repositories\Dashboard\DashboardInterface;
use App\Repositories\HomeSetting\HomeSettingInterface;
use App\Repositories\Page\PageInterface;
use App\Repositories\PaymentSetting\PaymentSettingInterface;
use App\Repositories\Post\PostInterface;
use App\Repositories\Product\ProductInterface;
use App\Repositories\Setting\SettingInterface;
use App\Repositories\Term\TermInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

/**
 * @property  page
 */
class HomeController extends Controller
{
    private $data = array();
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
     * @var PaymentSettingInterface
     */
    private $paymentsetting;
    /**
     * @var HomeSettingInterface
     */
    private $homesetting;

    /**
     * HomeController constructor.
     * @param PaymentSettingInterface $paymentsetting
     * @param SettingInterface|PaymentSettingInterface $setting
     * @param PageInterface $page
     * @param PostInterface $post
     * @param ProductInterface $product
     * @param DashboardInterface $dashboard
     * @param TermInterface $term
     * @param AttributeInterface $attribute
     * @param HomeSettingInterface $homesetting
     * @internal param array $data
     */
    public function __construct(PaymentSettingInterface $paymentsetting,
                                SettingInterface $setting,
                                PageInterface $page,
                                PostInterface $post,
                                ProductInterface $product,
                                DashboardInterface $dashboard,
                                TermInterface $term,
                                AttributeInterface $attribute,
                                HomeSettingInterface $homesetting)
    {
        $this->setting = $setting;
        $this->page = $page;
        $this->post = $post;
        $this->product = $product;
        $this->dashboard = $dashboard;
        $this->term = $term;
        $this->attribute = $attribute;
        $this->paymentsetting = $paymentsetting;
        $this->homesetting = $homesetting;
    }


    public function index()
    {
        $settings = $this->setting->getAll();
        $pages = $this->page->getAll();
        $posts = $this->post->getAll();
        //$top_exclusive = $this->product->getAllExclusive($skip = 0, $take = 4);
        //$bottom_exclusive = $this->product->getAllExclusive($skip = 4, $take = 4);
        $products = $this->product->getAll();
        $widgets = $this->dashboard->getAll();
        $homesettig = $this->homesetting->getAll();
        return view('frontend.home')
            ->with(['settings' => $settings,
                'pages' => $pages,
                'posts' => $posts,
                'products' => $products,
                'widgets' => $widgets,
                'homesettig' => $homesettig[0]]);
        //return view('frontend.basic.home')->with();
    }


    public function get_webpage($id, $slug)
    {
        // dd($id);
        $settings = $this->setting->getAll();
        $pages = $this->page->getById($id);
        //dd($pages);
        $posts = $this->post->getAll();
        // $products = $this->product->getAll();
        $widgets = $this->dashboard->getAll();
        // $product_details = $this->product->getById($id);


        return view('frontend.pages.page')
            ->with(['settings' => $settings, 'page' => $pages, 'posts' => $posts, 'widgets' => $widgets]);
    }

    public function specific_category($id)
    {
        $category = $this->term->getById($id);
        $divisions = DB::table('districts')->distinct()->select('division')->get();

        $settings = $this->setting->getAll();
        $posts = $this->post->getAll();
        $widgets = $this->dashboard->getAll();

        $sposts = array(
            'limit' => 500,
            'categories' => array($id),
            'offset' => 0
        );

        $sposts = $this->post->getPostsOnCategories($sposts);
        //dd($posts);

        return view('frontend.pages.scategory')
            ->with(['settings' => $settings, 'divisions' => $divisions, 'posts' => $posts, 'sposts' => $sposts, 'widgets' => $widgets, 'category' => $category]);
    }

    public function dealers()
    {
        //dd('sdfs');
        $category = $this->term->getById(619);
        $divisions = DB::table('districts')->distinct()->select('division')->get();

        $settings = $this->setting->getAll();
        $posts = $this->post->getAll();
        $widgets = $this->dashboard->getAll();

        $sposts = array(
            'limit' => 500,
            'categories' => array(619),
            'offset' => 0
        );
        $sposts = $this->post->getPostsOnCategories($sposts);
        //dd($posts);

        return view('frontend.pages.dealers')
            ->with(['settings' => $settings, 'divisions' => $divisions, 'posts' => $posts, 'sposts' => $sposts, 'widgets' => $widgets, 'category' => $category]);
    }


    public function get_district_by_division(Request $request)
    {
        //dd($request->division);
        $list = DB::table('districts')->distinct()->select('district')->groupBy('thana')->where('division', $request->division)->get();

        $html = '';
        foreach ($list->toArray() as $dist) {
            $html .= '<option value="' . $dist->district . '">' . $dist->district . '</option>';
        }
        return response()->json($html);
    }


     public function get_thana_by_district(Request $request)
    {
        //dd($request->district);
        $list = DB::table('districts')->distinct()->select('thana')->where('district', $request->district)->orderBy('thana')->get();

        $html = '';
        $html .= '<option value="All" selected>All Upazila</option>';
        foreach ($list->toArray() as $thana) {
            $html .= '<option value="' . $thana->thana . '">' . $thana->thana . '</option>';
        }
        return response()->json($html);
    }


    public function get_showroom_by_thana(Request $request)
    {
        $list = DB::table('posts')->distinct()->select('*')->where('thana', $request->thana)->get();

        //dd($list);

        $html = '';
        foreach ($list->toArray() as $location) {
            if ($location->categories == 619) {
                $html .= '<div class="address-shower">';
                $html .= '<div class="col-md-12">';
                $html .= '<a href="javascript:void(0)" class="btn btn-sm btn-success pull-right" onclick="mapGenerate(' . $location->id . ', ' . $location->latitude . ', ' . $location->longitude . ');">';
                $html .= '<i class="fa fa-map-pin"></i> Pin</a>';
                $html .= '<div class="mc-header news-text nearest-shop-list">';
                $html .= '<h5>' . $location->title . '</h5>';
                $html .= '<p>';
                $html .= '<span class="half-title">Address:</span>';
                $html .= '<span class="half-content">' . $location->address . '</span>';
                $html .= '</p>';
                $html .= '<p><i class="fa fa-phone"></i>' . $location->phone . '</p>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</div>';
            }
        }

        return response()->json($html);
    }

    /**
     * @return $this
     * All the data dumped on this method
     * Please check whatever you would like to check
     * Make your decision and work
     */

    public function all_data()
    {
        $settings = $this->setting->getAll();
        $paymentsetting = $this->paymentsetting->getAll();
        $pages = $this->page->getAll();
        $posts = $this->post->getAll();
        $products = $this->product->getAll();
        $widgets = $this->dashboard->getAll();

        $oldcart = Session::has('cart') ? Session::get('cart') : null;
        $categories = $this->term->getAll()->toArray();
        //dd($paymentsetting);

        return view('frontend.all_data')
            ->with([
                'settings' => $settings,
                'pages' => $pages,
                'posts' => $posts,
                'products' => $products,
                'widgets' => $widgets,
                'cart' => $oldcart,
                'paymentsetting' => $paymentsetting,
                'cats' => $categories
            ]);
        //return view('frontend.basic.home')->with();
    }
}
