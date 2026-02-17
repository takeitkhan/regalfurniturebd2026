<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Repositories\Dashboard\DashboardInterface;
use App\Repositories\Page\PageInterface;
use App\Repositories\Post\PostInterface;
use App\Repositories\Product\ProductInterface;
use App\Repositories\ProductSet\ProductSetInterface;
use App\Repositories\SessionData\SessionDataInterface;
use App\Repositories\Setting\SettingInterface;
use App\Repositories\Slider\SliderInterface;
use App\Repositories\Term\TermInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
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
        SliderInterface      $slider,
        ProductInterface     $product,
        SessionDataInterface $session_data,
        DashboardInterface   $dashboard,
        PageInterface        $page,
        ProductSetInterface  $product_set,
        PostInterface        $post,
        TermInterface        $terms,
        SettingInterface     $setting
    )
    {
        $this->slider = $slider;
        $this->product = $product;
        $this->session_data = $session_data;
        $this->dashboard = $dashboard;
        $this->page = $page;
        $this->product_set = $product_set;
        $this->post = $post;
        $this->terms = $terms;
        $this->setting = $setting;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->login();
    }

    public function login()
    {

        if (auth()->check() && auth()->user()->isAdmin() && auth()->user()->isAdmin()->id) {
            return redirect('dashboard');
        } elseif (auth()->check() && auth()->user()->isManager() && auth()->user()->isManager()->id) {
            return redirect('dashboard');
        } elseif (auth()->check() && auth()->user()->isOrderViewer() && auth()->user()->isOrderViewer()->id) {
            return redirect('orders');
        }
        return response()->view('login');
    }

    public function web_login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'is_active' => 1])) {
            return redirect()->intended(session('link'));
        } else {
            throw ValidationException::withMessages([
                'email' => [trans('auth.failed')],
            ]);
            return back();
        }
    }


    public function facebookFeed(Request $request)
    {
        $category = $this->terms
            ->self()
            ->select("seo_url")
            ->where('type', "category")
            ->where('id', '!=', 1)
            ->where('is_published', 1)
            ->where('is_active', 1)
            ->orderBy('serial', 'ASC')
            ->get();


        $products = $this->product
            ->self()
            ->select("id", "product_code", "title", "seo_url", "description", "stock_status", "local_selling_price", "local_discount", "is_active")
            ->with('firstImage')
            //   ->where('is_active',1)
            ->get();


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

            // dd($product);
        }

        $products = $products_db;
        //dd($category);

        return response()->view('fb_xml_feeds', compact('category', 'products'), 200, [
            'Content-Type' => 'application/xml'
        ]);
    }


    public function phpInfo()
    {
        phpinfo();
    }
}
