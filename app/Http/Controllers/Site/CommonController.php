<?php

namespace App\Http\Controllers\Site;

use App\Models\Product;
use App\Repositories\Attribute\AttributeInterface;
use App\Repositories\Dashboard\DashboardInterface;
use App\Repositories\Newsletter\NewsletterInterface;
use App\Repositories\OrdersDetail\OrdersDetailInterface;
use App\Repositories\OrdersMaster\OrdersMasterInterface;
use App\Repositories\Page\PageInterface;
use App\Repositories\Post\PostInterface;
use App\Repositories\Product\ProductInterface;
use App\Repositories\Returns\ReturnsInterface;
use App\Repositories\Role\RoleInterface;
use App\Repositories\Role_user\Role_userInterface;
use App\Repositories\Setting\SettingInterface;
use App\Repositories\Temporaryorder\TemporaryorderInterface;
use App\Repositories\Term\TermInterface;
use App\Repositories\User\UserInterface;
use App\Mail\ResetPassword;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\ThankYou;
use App\Models\Post;
use App\Models\Term;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;


/**
 * @property  return
 */
class CommonController extends Controller
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
     * @var UserInterface
     */
    private $user;
    /**
     * @var RoleInterface
     */
    private $role;
    /**
     * @var Role_userInterface
     */
    private $role_user;
    /**
     * @var OrdersDetailInterface
     */
    private $ordersdetail;
    /**
     * @var OrdersMasterInterface
     */
    private $ordersmaster;
    /**
     * @var TemporaryorderInterface
     */
    private $temporaryorder;
    /**
     * @var NewsletterInterface
     */
    private $newsletter;
    /**
     * @var ReturnInterface
     */
    private $returns;

    /**
     * CommonController constructor.
     * @param PaymentSettingInterface|SettingInterface $setting
     * @param PageInterface $page
     * @param PostInterface $post
     * @param ProductInterface $product
     * @param DashboardInterface $dashboard
     * @param TermInterface $term
     * @param AttributeInterface $attribute
     * @param UserInterface $user
     * @param RoleInterface $role
     * @param Role_userInterface $role_user
     * @param TemporaryorderInterface $temporaryorder
     * @param OrdersMasterInterface $ordersmaster
     * @param OrdersDetailInterface $ordersdetail
     * @param ReturnsInterface $returns
     * @param NewsletterInterface $newsletter
     * @internal param array $data
     */
    public function __construct(
        SettingInterface $setting,
        PageInterface $page,
        PostInterface $post,
        ProductInterface $product,
        DashboardInterface $dashboard,
        TermInterface $term,
        AttributeInterface $attribute,
        UserInterface $user,
        RoleInterface $role,
        Role_userInterface $role_user,
        TemporaryorderInterface $temporaryorder,
        OrdersMasterInterface $ordersmaster,
        OrdersDetailInterface $ordersdetail,
        ReturnsInterface $returns,
        NewsletterInterface $newsletter
    ) {
        $this->setting = $setting;
        $this->page = $page;
        $this->post = $post;
        $this->product = $product;
        $this->dashboard = $dashboard;
        $this->term = $term;
        $this->attribute = $attribute;
        $this->user = $user;
        $this->role = $role;
        $this->role_user = $role_user;
        $this->ordersdetail = $ordersdetail;
        $this->ordersmaster = $ordersmaster;
        $this->temporaryorder = $temporaryorder;
        $this->newsletter = $newsletter;
        $this->returns = $returns;

        $this->middleware('auth')->only('wishlist');
    }

    public function my_account()
    {
        $settings = $this->setting->getAll();
        $pages = $this->page->getAll();
        $posts = $this->post->getAll();
        $products = $this->product->getAll();
        $widgets = $this->dashboard->getAll();
        $user = Auth::user();
        $orders_details = $this->ordersdetail->getProductBySecretKey(['user_id' => $user->id]);

        return view('frontend.common.my_account')
            ->with(['user' => $user,
                'settings' => $settings,
                'pages' => $pages,
                'posts' => $posts,
                'products' => $products,
                'widgets' => $widgets,
                'orders_detail' => $orders_details
            ]);
    }

    public function profile_update(Request $request)
    {
        $settings = $this->setting->getAll();
        $pages = $this->page->getAll();
        $posts = $this->post->getAll();
        $products = $this->product->getAll();
        $widgets = $this->dashboard->getAll();
        $user = Auth::user();

        if ($request->get('update_profile')) {
            //dd($request);

            $attributes = [
                'name' => !empty($request->get('name')) ? $request->get('name') : null,
                'phone' => !empty($request->get('phone')) ? $request->get('phone') : null,
                'email' => !empty($request->get('email')) ? $request->get('email') : null,
                'emergency_phone' => !empty($request->get('emergency_phone')) ? $request->get('emergency_phone') : null,
                'address' => !empty($request->get('address')) ? $request->get('address') : null,
                'address_2' => !empty($request->get('address_2')) ? $request->get('address_2') : null,
                'postcode' => !empty($request->get('postcode')) ? $request->get('postcode') : null,
                'company' => !empty($request->get('company')) ? $request->get('company') : null,
                'district' => !empty($request->get('district')) ? $request->get('district') : null,
            ];

            $done_update = $this->user->update($request->get('user_id'), $attributes);
        } else {
        }

        return view('frontend.common.profile_update')
            ->with(['user' => $user,
                'settings' => $settings,
                'pages' => $pages,
                'posts' => $posts,
                'products' => $products,
                'widgets' => $widgets
            ]);
    }

    public function order_history()
    {
        $settings = $this->setting->getAll();
        $pages = $this->page->getAll();
        $posts = $this->post->getAll();
        $products = $this->product->getAll();
        $widgets = $this->dashboard->getAll();
        $user = Auth::user();
        $orders_details = $this->ordersdetail->getProductBySecretKey(['user_id' => $user->id]);

        return view('frontend.common.order_history')
            ->with(['user' => $user,
                'settings' => $settings,
                'pages' => $pages,
                'posts' => $posts,
                'products' => $products,
                'widgets' => $widgets,
                'orders_detail' => $orders_details
            ]);
    }

    public function return()
    {
        $settings = $this->setting->getAll();
        $pages = $this->page->getAll();
        $posts = $this->post->getAll();
        $products = $this->product->getAll();
        $widgets = $this->dashboard->getAll();
        $user = Auth::user();

        return view('frontend.common.return')
            ->with(['user' => $user,
                'settings' => $settings,
                'pages' => $pages,
                'posts' => $posts,
                'products' => $products,
                'widgets' => $widgets
            ]);
    }

    public function return_save(Request $request)
    {
        // dd($request);
        //d($request);
        // validate
        // read more on validation at
        $validator = Validator::make(
            $request->all(),
            [
                'first_name' => 'required',
                'email' => 'required',
                'telephone' => 'required',
                'order_id' => 'required',
                'date_ordered' => 'required',
                'product_name' => 'required',
                'product_code' => 'required',
                'quantity' => 'required',
                'comment' => 'required'
            ]
        );

        // process the login
        if ($validator->fails()) {
            return redirect('return')
                ->withErrors($validator)
                ->withInput();
        } else {
            // store
            $attributes = [
                'first_name' => !empty($request->get('first_name')) ? $request->get('first_name') : null,
                'last_name' => !empty($request->get('last_name')) ? $request->get('last_name') : null,
                'email' => !empty($request->get('email')) ? $request->get('email') : null,
                'telephone' => !empty($request->get('telephone')) ? $request->get('telephone') : null,
                'order_id' => !empty($request->get('order_id')) ? $request->get('order_id') : null,
                'date_ordered' => !empty($request->get('date_ordered')) ? $request->get('date_ordered') : null,
                'product_name' => !empty($request->get('product_name')) ? $request->get('product_name') : null,
                'product_code' => !empty($request->get('product_code')) ? $request->get('product_code') : null,
                'quantity' => !empty($request->get('quantity')) ? $request->get('quantity') : null,
                'reason_return' => !empty($request->get('reason_return')) ? $request->get('reason_return') : null,
                'product_opened' => !empty($request->get('product_opened')) ? $request->get('product_opened') : null,
                'comment' => !empty($request->get('comment')) ? $request->get('comment') : null
            ];

            //dd($attributes);

            try {
                $returns = $this->returns->create($attributes);
                return redirect('/')->with('success', 'Successfully Added');
            } catch (\Illuminate\Database\QueryException $ex) {
                //dd($ex);
                $errorCode = $ex->errorInfo[1];
                if ($errorCode == '1062') {
                    return back()->with('failed', $ex->errorInfo[2]);
                } else {
                    return back()->with('failed', 'Something went wrong');
                }
                //return redirect('products')->withErrors($ex->getMessage());
            }
        }
    }

    public function wishlist()
    {
        $settings = $this->setting->getAll();
        $pages = $this->page->getAll();
        $posts = $this->post->getAll();
        $products = $this->product->getAll();
        $widgets = $this->dashboard->getAll();
        $user = Auth::user();

        $wishs = Wishlist::where('user_id', auth()->user()->id)->latest()->paginate(10);
        // $wishs = auth()->user()->wishlists;

        return view('frontend.common.wishlist', compact('wishs'))
            ->with([
                'user' => $user,
                'settings' => $settings,
                'pages' => $pages,
                'posts' => $posts,
                'products' => $products,
                'widgets' => $widgets
            ]);
    }

    public function gift_voucher()
    {
        $settings = $this->setting->getAll();
        $pages = $this->page->getAll();
        $posts = $this->post->getAll();
        $products = $this->product->getAll();
        $widgets = $this->dashboard->getAll();
        $user = Auth::user();

        return view('frontend.common.gift_voucher')
            ->with(['user' => $user,
                'settings' => $settings,
                'pages' => $pages,
                'posts' => $posts,
                'products' => $products,
                'widgets' => $widgets
            ]);
    }

    public function compare()
    {
        $settings = $this->setting->getAll();
        $pages = $this->page->getAll();
        $posts = $this->post->getAll();
        $products = $this->product->getAll();
        $widgets = $this->dashboard->getAll();
        $user = Auth::user();

        return view('frontend.common.compare')
            ->with([
                'user' => $user,
                'settings' => $settings,
                'pages' => $pages,
                'posts' => $posts,
                'products' => $products,
                'widgets' => $widgets
            ]);
    }

    public function sitemap()
    {
        $settings = $this->setting->getAll();
        $pages = $this->page->getAll();
        $posts = $this->post->getAll();
        $products = Product::orderBy('id', 'DESC')->take(50)->get();
       // dd($products);
        $terms = Term::where('parent', 1)->get();
        $widgets = $this->dashboard->getAll();
        //$user = Auth::user();

        return view('frontend.common.sitemap')
            ->with([
                //'user' => $user,
                'settings' => $settings,
                'pages' => $pages,
                'posts' => $posts,
                'products' => $products,
                'terms' => $terms,
                'widgets' => $widgets
            ]);
    }

    public function faq()
    {
        $settings = $this->setting->getAll();
        $pages = $this->page->getAll();
        $posts = $this->post->getAll();
        $products = $this->product->getAll();
        $widgets = $this->dashboard->getAll();
        $user = Auth::user();

        return view('frontend.common.faq')
            ->with([
                'user' => $user,
                'settings' => $settings,
                'pages' => $pages,
                'posts' => $posts,
                'products' => $products,
                'widgets' => $widgets
            ]);
    }

    public function paymentmethod()
    {
        $settings = $this->setting->getAll();
        $pages = $this->page->getAll();
        $posts = $this->post->getAll();
        $products = $this->product->getAll();
        $widgets = $this->dashboard->getAll();
        $user = Auth::user();

        return view('frontend.common.paymentmethod')
            ->with([
                'user' => $user,
                'settings' => $settings,
                'pages' => $pages,
                'posts' => $posts,
                'products' => $products,
                'widgets' => $widgets
            ]);
    }

    public function our_store()
    {
        $settings = $this->setting->getAll();
        $pages = $this->page->getAll();
        $posts = $this->post->getAll();
        $products = $this->product->getAll();
        $widgets = $this->dashboard->getAll();
        $user = Auth::user();
        $districts = DB::table('districts')->distinct()->select('district')->orderBy('district')->get();
        $stores = Post::where(['district' => 'Dhaka'])->get();
        //dump($stores);

        // return $district;
        return view('frontend.common.our_store')
            ->with(['user' => $user,
                'settings' => $settings,
                'pages' => $pages,
                'posts' => $posts,
                'products' => $products,
                'widgets' => $widgets,
                'districts' => $districts,
                'stores' => $stores
            ]);
    }

    public function shop_location(Request $request)
    {
        $posts= Post::where('categories', 651)->where('thana', $request->thana)->whereIn('shop_type', $request->shop_type)->orderBy('id', 'desc')->get();

        $html = '';
        $i=1;
        foreach ($posts->toArray() as $location) {
            if ($location['categories']  == 651) {

                $html .= '<li> '.$i.'. '. $location['title'].'<br> '. $location['address'].'<br> Phone: '.$location['author']. '('.$location['phone'].')</li>';
                $i++;
               
            }
        }
        return response()->json($html);
        // return $posts->toArray();

        
    }

    public function shop_type(Request $request)
    {
        
        
        $thana      = $request->thana;
        $district   = $request->district;
        $shop_type  = $request->shop_type;
        $shop_type  = is_array($shop_type) ? $shop_type : explode(',',$shop_type);
        
        $posts= Post::where('categories', 651);
        
        $posts =  $posts->whereIn('shop_type',$shop_type);
        
        $posts = $district ? $posts->where('district', $district) : $posts;
        
        $posts = $thana != null && $thana && $thana != 'All' ? $posts->where('thana','LIKE', $thana) : $posts;
        
        $posts =  $posts->orderBy('id', 'desc')->get();
        
        $html = '';
        $i=1;
        
        
        foreach ($posts as $location) {
            if ($location['categories']  == 651) {

                $html .= '<li> '.$i.'. '. $location['title'].'<br> '. $location['address'].'<br> Phone: '.$location['author']. '('.$location['phone'].')</li>';
                $i++;
               
            }
        }

        return response()->json($html);
        // return $posts->toArray();

        
    }



    public function order_information()
    {
        $settings = $this->setting->getAll();
        $pages = $this->page->getAll();
        $posts = $this->post->getAll();
        $products = $this->product->getAll();
        $widgets = $this->dashboard->getAll();
        $user = Auth::user();

        return view('frontend.common.order_information')
            ->with(['user' => $user,
                'settings' => $settings,
                'pages' => $pages,
                'posts' => $posts,
                'products' => $products,
                'widgets' => $widgets
            ]);
    }

    /**
     * @return $this
     */
    public function login_now()
    {
        $settings = $this->setting->getAll();
        $pages = $this->page->getAll();
        $posts = $this->post->getAll();
        $products = $this->product->getAll();
        $widgets = $this->dashboard->getAll();

        session(['link' => url()->previous()]);

        $user = Auth::user();

        return view('frontend.pages.web_login_form')
            ->with(['user' => $user, 'settings' => $settings, 'pages' => $pages, 'posts' => $posts, 'products' => $products, 'widgets' => $widgets]);
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

    public function web_logout()
    {
        Auth::logout();
        return redirect()->intended(session('link'));
    }

    /**
     * @return $this
     */
    public function create_an_account()
    {
        $settings = $this->setting->getAll();
        $pages = $this->page->getAll();
        $posts = $this->post->getAll();
        $products = $this->product->getAll();
        $widgets = $this->dashboard->getAll();

        $user = Auth::user();

        return view('frontend.pages.create_account_form')
            ->with(['user' => $user, 'settings' => $settings, 'pages' => $pages, 'posts' => $posts, 'products' => $products, 'widgets' => $widgets]);
    }

    public function reset_password(Request $request)
    {
        $settings = $this->setting->getAll();
        $pages = $this->page->getAll();
        $posts = $this->post->getAll();
        $widgets = $this->dashboard->getAll();

        $user = Auth::user();

        if ($request->get('submit')) {
            $email = $request->get('email');
            $user = \App\Models\User::where('email', $email)->get()->first();

            if (!empty($user)) {
                $user_id = $user->id;

                $setting = $this->setting->getAll()->first();
                $url = url('password_confirmation?reset_code=' . base64_encode($user_id));
                $data = [
                    'com_name' => $setting->com_name,
                    'name' => $user->name,
                    'email' => $email,
                    'subject' => 'Reset Password',
                    'description' => 'Details',
                    'url' => $url
                ];
                $mail = Mail::to($email)->send(new ResetPassword($data));

                if ($mail) {
                    return redirect('login_now')->with(['message' => 'Email has been sent.']);
                } else {
                    return redirect('reset_password')->with(['message' => 'Email does\'t exits']);
                }
            } else {
                return view('frontend.common.reset_password')
                    ->with(['user' => $user, 'settings' => $settings,'email' => $email, 'pages' => $pages, 'posts' => $posts, 'widgets' => $widgets, 'message' => 'Email does\'t exits']);
            }
        } else {
            return view('frontend.common.reset_password')
                ->with(['user' => $user, 'settings' => $settings,'email' => $email??'', 'pages' => $pages, 'posts' => $posts, 'widgets' => $widgets]);
        }
    }

    public function password_confirmation(Request $request)
    {
        if (empty($request->get('reset_code'))) {
            return redirect('login_now');
        }

        $settings = $this->setting->getAll();
        $pages = $this->page->getAll();
        $posts = $this->post->getAll();
        $widgets = $this->dashboard->getAll();

        //dump($decoded);
        //dd($request->get('reset_code'));

        $decoded = base64_decode($request->get('reset_code'));
        $user = User::where('id', $decoded)->get()->first();

        return view('frontend.common.password_confirmation')
            ->with(['user' => $user, 'settings' => $settings, 'pages' => $pages, 'posts' => $posts, 'widgets' => $widgets]);
    }

    public function retrieve_password(Request $request)
    {
        $settings = $this->setting->getAll();
        $pages = $this->page->getAll();
        $posts = $this->post->getAll();
        $widgets = $this->dashboard->getAll();

        $validator = Validator::make(
            $request->all(),
            [
                'password' => 'required|alpha_dash',
                'confirm_password' => 'required|alpha_dash'
            ]
        );

        if ($validator->fails()) {
            return redirect('users')
                ->withErrors($validator)
                ->withInput();
        } else {
            if ($request->get('password') == $request->get('confirm_password')) {
                $id = $request->get('user_id');
                $attributes = ['password' => bcrypt($request->get('password'))];

                try {
                    $this->user->update($id, $attributes);

                    return redirect('login_now')->with('success', 'Successfully save changed');
                } catch (\Illuminate\Database\QueryException $ex) {
                    return redirect('users')->withErrors($ex->getMessage());
                }
            } else {
            }
        }

        return view('frontend.common.password_confirmation')
            ->with(['user' => $user??[], 'settings' => $settings, 'pages' => $pages, 'posts' => $posts, 'widgets' => $widgets]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function web_signup(Request $request)
    {
        //dd($request);
        if (is_blocked_ip($request->ip())) {
            try {
                \App\Models\UserRegisterLog::create([
                    'user_id' => null,
                    'name' => $request->get('name'),
                    'email' => $request->get('email'),
                    'ip' => $request->ip(),
                    'user_agent' => $request->header('User-Agent'),
                    'source' => 'web_signup',
                    'status' => 'blocked',
                    'reason' => 'ip_blocked',
                    'payload' => $request->except(['password', 'password_confirmation'])
                ]);
            } catch (\Exception $e) {
                // ignore logging failures
            }

            return redirect()->back()->withErrors(['email' => 'Registration is blocked from this IP.'])->withInput();
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => [
                'required',
                'email',
                'unique:users',
                function ($attribute, $value, $fail) {
                    if (is_disposable_email($value)) {
                        $fail('Disposable email addresses are not allowed.');
                    }
                }
            ],
            'telephone' => 'required',
            'emergency_contact_number' => 'required',
            'password' => 'required|string|min:6|max:32|confirmed',
            'agree' => 'required',
            'district' => 'required',
            'company' => ['nullable', function ($attribute, $value, $fail) {
                if ($value !== null && strtolower(trim($value)) === 'dhaka') {
                    $fail('The company is invalid.');
                }
            }],
        ]);

        if ($validator->fails()) {
            try {
                \App\Models\UserRegisterLog::create([
                    'user_id' => null,
                    'name' => $request->get('name'),
                    'email' => $request->get('email'),
                    'ip' => $request->ip(),
                    'user_agent' => $request->header('User-Agent'),
                    'source' => 'web_signup',
                    'status' => 'failed',
                    'reason' => $validator->errors()->first(),
                    'payload' => $request->except(['password', 'password_confirmation'])
                ]);
            } catch (\Exception $e) {
                // ignore logging failures
            }

            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = [
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'phone' => $request->get('telephone'),
            'emergency_phone' => $request->get('emergency_contact_number'),
            'company' => $request->get('company'),
            'address' => $request->get('address_1'),
            'address_2' => $request->get('address_2'),
            'district' => $request->get('district'),
            'post_code' => $request->get('post_code'),
            'password' => bcrypt($request->get('password')),
            'is_active' => true
        ];

        $newuser = User::create($user);

        try {
            \App\Models\UserRegisterLog::create([
                'user_id' => $newuser->id,
                'name' => $newuser->name,
                'email' => $newuser->email,
                'ip' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
                'source' => 'web_signup',
                'status' => 'success',
                'reason' => null,
                'payload' => $request->except(['password', 'password_confirmation'])
            ]);
        } catch (\Exception $e) {
            // ignore logging failures
        }

        $data = [
            'email' => $request->get('email'),
            'password' => $request->get('password'),
        ];

        Mail::to($newuser->email)->send(new ThankYou($data));

        if (!empty($newuser)) {
            $role_user = [
                'role_id' => $request->get('customer_group'),
                'user_id' => $newuser->id
            ];

            $this->role_user->create($role_user);
        }

        return redirect('login_now')->with('success', 'Successfully singed up');
    }

    /**
     *
     * @param \App\Http\Controllers\Auth\Request $request
     * @return type
     */
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('web_login');
    }

    /**
     * @param Request $request
     */
    public function subscribe_email(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required|unique:newsletters'
            ]
        );

       // dd($validator);

        // process the login
        if ($validator->fails()) {

            $v_h = '';
            foreach ($validator->errors()->all() as $messages)
            {
                $v_h .= $messages;
            }

            return redirect('/')->with('sweet_error', $v_h);

        } else {
            // store
            $attributes = ['email' => $request->get('email'), 'is_active' => 1];



            try {
                $newsletter = $this->newsletter->create($attributes);
                return redirect('/')->with('sweet_alert', 'Successfully Subscribed');

            } catch (\Illuminate\Database\QueryException $ex) {
                return redirect('/')->with('sweet_error', 'Something is problem');
            }
        }
    }

    public function newseventsindex()
    {
        $nents = Post::where('categories', '614')->orWhere('categories', '615')->latest()->get();

        $settings = $this->setting->getAll();
        $widgets = $this->dashboard->getAll();

        return view('frontend.pages.newsevents_index', compact('nents'))
            ->with(['settings' => $settings, 'widgets' => $widgets]);
    }

    public function newseventsshow($seo_url)
    {
        $nent = Post::where(['seo_url' =>  $seo_url, 'categories' => 661])->get()->first();
        
       // dd($nent);
        $settings = $this->setting->getAll();
        $widgets = $this->dashboard->getAll();

        return view('frontend.pages.newsevents_show', compact('nent'))
            ->with(['settings' => $settings, 'widgets' => $widgets]);
    }

    public function advertisementindex()
    {
        $settings = $this->setting->getAll();
        $widgets = $this->dashboard->getAll();

        $ads = Post::where('categories', '616')->latest()->get();

        return view('frontend.pages.advertisement_index', compact('ads'))
            ->with([
                'settings' => $settings,
                'widgets' => $widgets,
            ]);
    }

    public function advertisementshow($id)
    {
        $ad = Post::findOrFail($id);
        $settings = $this->setting->getAll();
        $widgets = $this->dashboard->getAll();

        return view('frontend.pages.advertisement_show', compact('ad'))
            ->with(['settings' => $settings, 'widgets' => $widgets]);
    }

    public function testimonialindex()
    {
        $settings = $this->setting->getAll();
        $widgets = $this->dashboard->getAll();

        $testimonials = Post::where('categories', '617')->latest()->get();

        return view('frontend.pages.testimonial_index', compact('testimonials'))
            ->with([
                'settings' => $settings,
                'widgets' => $widgets,
            ]);
    }

    public function testimonialshow($id)
    {
        $testi = Post::findOrFail($id);
        $settings = $this->setting->getAll();
        $widgets = $this->dashboard->getAll();

        return view('frontend.pages.testimonial_show', compact('testi'))
            ->with([
                'settings' => $settings,
                'widgets' => $widgets
            ]);
    }

    public function protfolioindex()
    {
        $settings = $this->setting->getAll();
        $widgets = $this->dashboard->getAll();

        $protfolios = Post::where('categories', '618')->latest()->get();

        return view('frontend.pages.protfolio_index', compact('protfolios'))
            ->with([
                'settings' => $settings,
                'widgets' => $widgets
            ]);
    }

    public function protfolioshow($id)
    {
        $pro = Post::findOrFail($id);
        $settings = $this->setting->getAll();
        $widgets = $this->dashboard->getAll();

        return view('frontend.pages.protfolio_show', compact('pro'))
            ->with([
                'settings' => $settings,
                'widgets' => $widgets
            ]);
    }

    public function preload()
    {
       
        // Cookie::queue('popup', 'true',time()+3600);
        // setcookie('popup', 'true', time()+3600);
        $cookie = cookie('popup', true, 60);
        return response()->json('hello')->cookie($cookie);
    }

    public function be_a_vendor()
    {
        $settings = $this->setting->getAll();
        $widgets = $this->dashboard->getAll();

        return view('frontend.pages.be_a_vendor')
            ->with(['settings' => $settings, 'widgets' => $widgets, ]);
    }

    public function save_vendor(Request $request)
    {
        // dd($request);
        // read more on validation at
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'email' => 'required',
                'password' => 'required|alpha_dash'
            ]
        );

        $validator1 = Validator::make(
            $request->all(),
            [
                'role_id' => 'required',
                'user_id' => 'required'
            ]
        );

        // process the login
        if ($validator->fails()) {
            return redirect('users')
                ->withErrors($validator)
                ->withInput();
        } else {
            // store
            $attributes = [
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'username' => $request->get('email'),
                'birthday' => date('Y-m-d', strtotime($request->get('birthday'))),
                'gender' => $request->get('gender'),
                'marital_status' => $request->get('marital_status'),
                'join_date' => date('Y-m-d'),
                'father' => $request->get('father'),
                'mother' => $request->get('mother'),
                'company' => $request->get('company'),
                'address' => $request->get('address'),
                'phone' => $request->get('phone'),
                'emergency_phone' => $request->get('emergency_mobile'),
                'password' => bcrypt($request->get('password')),
                'is_active' => 0
            ];

            try {
                $user = $this->user->create($attributes);
                if (!empty($user)) {
                    $attributes_role = [
                        'role_id' => 10,
                        'user_id' => $user->id
                    ];
                    try {
                        $this->role_user->create($attributes_role);
                        return redirect('login_now')->with('success', 'Successfully save changed');
                    } catch (\Illuminate\Database\QueryException $ex) {
                        return redirect('login_now')->withErrors($ex->getMessage());
                    }
                } else {
                    return redirect('be_a_vendor');
                }
            } catch (\Illuminate\Database\QueryException $ex) {
                return redirect('be_a_vendor')->withErrors($ex->getMessage());
            }
        }
    }

    public function item_cart_load()
    {
        $data = session()->all();
        if (!empty($data['cart'])) {
            $cart = $data['cart'];
            $total_qty = array_sum(array_column($cart->items, 'qty'));
            $individual_price = array();
            foreach ($cart->items as $item) {
                $individual_price[] = $item['purchaseprice'] * $item['qty'];
            }
            $totalprice = array_sum($individual_price);

        } else {
            $total_qty = 0;
            $totalprice = number_format(0);
        }
        $html = '';

        if($total_qty > 0 && $total_qty < 10){
            $html .= '<span class="cart-count">'.$total_qty.'</span>';
        }elseif($total_qty > 9){
            $html .= ' <span class="cart-count">9+</span>';
        }


        return response()->json(['html' => $html]);
    }

    public function item_compare_load()
    {
        $data = session()->all();
        if (!empty($data['comparison'])) {
            $total_qty = count($data['comparison']->items);
        } else {
            $total_qty = 0;
        }
        $html = '';

        if ($total_qty > 0 && $total_qty < 10) {
            $html .= '<div class="comp-ct" id="show_total_compare"><span class="cart-count">' . $total_qty . '</span></div>';
        } elseif ($total_qty > 9) {
            $html .= ' <div class="comp-ct" id="show_total_compare"><span class="cart-count">9+</span></div>';
        }

        return response()->json(['html' => $html]);
    }

    public function item_wishlist_load()
    {
        if (auth()->check()) {
            $total_qty = Wishlist::where(['user_id' => auth()->user()->id])->get()->count();
        } else {
            $total_qty = 0;
        }

        $html = '';

        if ($total_qty > 0 && $total_qty < 10) {
            $html .= '<div class="comp-ct compare-pos_bg" id="show_total_wishlist"><span class="cart-count">' . $total_qty . '</span></div>';
        } elseif ($total_qty > 9) {
            $html .= ' <div class="comp-ct compare-pos_bg" id="show_total_wishlist"><span class="cart-count">9+</span></div>';
        }

        return response()->json(['html' => $html]);
    }



}
