<?php

namespace App\Http\Controllers\API;

use App\Models\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Address\AddressInterface;
use App\Repositories\Newsletter\NewsletterInterface;
use App\Repositories\OrdersDetail\OrdersDetailInterface;
use App\Repositories\OrdersMaster\OrdersMasterInterface;
use App\Repositories\Product\ProductInterface;
use App\Repositories\Review\ReviewInterface;
use App\Repositories\SessionData\SessionDataInterface;
use App\Repositories\User\UserInterface;
use Illuminate\Support\Facades\Validator;
use Auth;
use Exception;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


class UserController extends Controller
{
        private $product;
        private $session_data;
        private $user;
        private $review;
        private $order;
        private $newsletter;
        private $address;
        private $order_master;
        private $order_details;
        public function __construct(ProductInterface $product,
                                    SessionDataInterface $session_data,
                                    UserInterface $user,
                                    ReviewInterface $review,
                                    OrdersMasterInterface $order,
                                    NewsletterInterface $newsletter,
                                    AddressInterface $address,
                                    OrdersMasterInterface $order_master,
                                    OrdersDetailInterface $order_details)

    {
            $this->product = $product;
            $this->session_data = $session_data;
            $this->user = $user;
            $this->review = $review;
            $this->order = $order;
            $this->newsletter = $newsletter;
            $this->address = $address;
            $this->order_master = $order_master;
            $this->order_details = $order_details;
    }

    public function addToWishlist(Request $request)
    {
        $self_token = auth()->id();
        $product_id = $request->pid;

        // dd();

        if($self_token == null || $self_token == "" )
            return false;

            $self_wishlist_key = "wishlist_".$self_token;
            $existing_session  = $this->session_data->getFirstByKey($self_wishlist_key);


            $product_ids = [$product_id];

            if($existing_session){
                $existing_product_ids = json_decode($existing_session->session_data);

                if(is_array($existing_product_ids)){

                    $existing_product_ids =array_filter($existing_product_ids);
                    $product_ids =array_slice( array_merge($product_ids,$existing_product_ids), 0, 5);

                }
            }

            $product_ids = json_encode($product_ids);

            $store = $this->session_data->updateByKey($self_wishlist_key,$product_ids);

            $success = false;
            $product  = null;

            if($store){
                $success =true;
                $db_product = $this->product->getByAny('id',$product_id)->first();
                // if($db_product)
                //     $db_product = $db_product->first();

                if($db_product){
                    $product = [

                        'title' => $db_product->title,
                        'seo_url' =>$db_product->seo_url,
                        'image_url'=> $db_product->firstImage->full_size_directory??null

                    ];
                }
            }

        return response()->json(compact('success', 'product'));
    }


    public function removeWishlist (Request $request)
    {
            $self_token = auth()->id();
            $product_id = $request->pid;



            if($self_token == null || $self_token == "")
                return false;

            $self_wishlist_key = "wishlist_".$self_token;
            $existing_session = $this->session_data->getFirstByKey($self_wishlist_key);
            $product_ids = [];

            if($existing_session){

                $existing_product_ids = json_decode($existing_session->session_data);

                if(is_array($existing_product_ids)){

                    $filter_rr = array_filter($existing_product_ids,function($item) use($product_id){
                            return $item != $product_id;
                    });

                    $product_ids = array_values($filter_rr);
                }
            }

            // echo "<pre>";

            // print_r($existing_product_ids);

            // print_r($product_ids);

            // echo "</pre>";
            // exit;



                $product_ids = json_encode($product_ids);
                $store = $this->session_data->updateByKey($self_wishlist_key,$product_ids);

                $success = false;

                if($store){
                    $success = true;
                }

        return response()->json(compact('success'));
    }

    public function viewWishlist(Request $request)
    {

        $self_token = auth()->id();
        $products = [];

        if($self_token == null || $self_token == "")
                return [];

        $self_wishlist_key = "wishlist_".$self_token;
        $existing_session = $this->session_data->getFirstByKey($self_wishlist_key);

        if($existing_session){
            $existing_product_ids = json_decode($existing_session->session_data);
            $db_products = $this->product->self();
            $db_products = $db_products->whereIn('id',$existing_product_ids)->with('firstImage')->get();

            // dd($db_products);

            foreach($db_products as $db_product){

                $product_item = [
                    'id' => $db_product->id,
                    'image_url'=> $db_product->firstImage->full_size_directory??null,
                    'seo_url'=> $db_product->seo_url,
                    'sku'=> $db_product->sku,
                    'title' => $db_product->title,
                    'sub_title' => $db_product->sub_title,
                    'product_name' => $db_product->product_name,
                    'short_description' => $db_product->short_description,
                    'product_price_now' => $db_product->product_price_now
                ];

                $products[] = $product_item;

            }
        }

        return response()->json(compact('products'));

    }


    public function userInfo(){
        $userinfo = Auth::user();
        $user =[
            'name' => $userinfo->name,
            'email' => $userinfo->email,
            'phone' =>$userinfo->phone
        ];

        return response()->json(compact('user'));
    }

    public function userReview(){

        $user_id = auth()->id();
        $review_by_user_id = $this->review->getByAny('user_id', $user_id);
        $reviews = [];
        foreach($review_by_user_id as $rev){

            $product = $rev->product;

            if($product){

                $product = [
                    'title' => $product->title??'',
                    'sub_title' => $product->sub_title??'',
                    'sku' => $product->sku??'',
                    'short_description' => $product->short_description??'',
                    'seo_url' => $product->seo_url,
                    'image_url' => $product->firstImage->icon_size_directory??null,
                ];

            }else
                $product = [];

            $reviews [$rev->id] =[
                'product' => $product,
                'rating' => $rev->rating,
                'comment' => $rev->comment,
                'is_active' => $rev->is_active,
                'created_at' => $rev->created_at->format('d F Y'),
            ];
        }
        return response()->json(compact('reviews'));
    }

    public function userOrder(){
        $user_id = auth()->id();

        $orders_by_user_id = $this->order->self()->where('user_id', $user_id)->orderBy('id','DESC')->get();
        $orders = [];
        //dd($orders_by_user_id);
        foreach($orders_by_user_id as $order){

            $totalQty = $order->orderdetails->count();

            $orders [] = [
                'id' => $order->id,
                'order_random' => $order->order_random,
                'order_key' => $order->secret_key,
                'customer_name' => $order->customer_name,
                'phone'=> $order->phone,
                'emergency_phone' => $order->emergency_phone,
                'address' => $order->address,
                'email' =>$order->email,
                'order_date' =>$order->order_date,
                'payment_method' =>$order->payment_method,
                'grand_total' =>$order->grand_total,
                'delivery_fee' =>$order->delivery_fee,
                'total_amount' =>$order->total_amount,
                'district' =>$order->district,
                'od_status' =>$order->order_status,
                'payment_term_status' =>$order->payment_term_status,
                'qty' =>$totalQty,
                'products' => $order->orderdetails
            ];
        }
        return response()->json(compact('orders'));
    }


    //

    public function userWantKnowOrderUpdate(Request $r){
        $attr = [
            'notification_for_id' => $r->notification_for_id,
            'notification_for' => $r->notification_for,
            'user_id' => $r->user_id,
            'message' => $r->message,
            'is_read' => 0
        ];
        $done = Notification::create($attr);
        $status = $done ? '1' : 0;
        return response()->json([
           'status' => $status,
           'data' => $done,
        ]);
    }

    public function getUserOrderComplaint(Request $request){
        $data = Notification::where('user_id', $request->user_id)->where('notification_for', 'order-complaint')
                ->where('notification_for_id', $request->notification_for_id)->get();
        return response()->json([
            'data' => $data,
        ]);
    }

    public function userSubscribe(Request $request){


            $arr = [
                'email' => $request->email
            ];

           $result = $this->newsletter->create($arr);
           if($result){

            return response()->json([
                'success' => true
            ]);

            }else{
            return response()->json(['success' => false]);
            }
    }

    public function addressStore(Request $request){

        $user_id = auth()->id();

        $validate = Validator::make($request->all(),[
            'name' => 'required',
            'phone' => 'required',
            'district' => 'required',
            'region' => 'required',
            'address' => 'required'
        ]);

        if($validate->fails()){

            return response()->json([
                'success' => false,
                'messages' => $validate->errors()
            ]);
        }

        $address_att = [
            'user_id'=>$user_id,
            'name' => $request->name,
            'address'=> $request->address,
            'region'=>$request->region,
            'district'=>$request->district,
            'phone' => $request->phone,
            'is_active' => 1
        ];

        $result = $this->address->create($address_att);
        if($result){
            return response()->json([
                'success' => (boolean)$result,
            ]);
        }else{
            return response()->json([
                'error' => (boolean)$result,
            ]);
        }
    }

    public function addressView(){
        $user_id = auth()->id();
        $db_address = $this->address->getByAny('user_id', $user_id);
        $addresses = [];
        foreach($db_address as $address){
            $addresses[$address->id] =[
                'name' => $address->name,
                'address'=> $address->address,
                'region' => $address->region,
                'district' => $address->district,
                'phone' => $address->phone,
                'id' => $address->id
            ];
        }
        return response()->json(compact('addresses'));
    }

    public function addressUpdate(Request $request){

        $id = $request->id;

        $validate = Validator::make($request->all(),[
            'name' => 'required',
            'phone' => 'required',
            'region' => 'required',
            'district' => 'required',
            'address' => 'required',
            'id' => 'required'
        ]);

        if($validate->fails()){

            return response()->json([
                'success' => false,
                'messages' => $validate->errors()
            ]);
        }


        $address_att = [
            'name' => $request->name,
            'address'=> $request->address,
            'region'=>$request->region,
            'district'=>$request->district,
            'phone' => $request->phone,
        ];

        $result = $this->address->update($id,$address_att);

        if($result){
            return response()->json([
                'success' => true,
            ]);
        }else{
            return response()->json([
                'success' => false,
            ]);
        }

    }

    public function addressDelete(Request $request){
        $id = $request->id;

        $result = $this->address->delete($id);
        if($result){
            return response()->json([
                'success' => 'Delete Successfull',
            ]);
        }else{
            return response()->json([
                'error' => "Somthing want Wrong!",
            ]);
        }

    }


    public function changePassword(Request $request){


        $validate = Validator::make($request->all(),[
            'old_password' => 'required',
            'password' => 'required|confirmed|min:6',
        ]);

        if($validate->fails()){
            return response()->json(
                ['success' => false,
                 'massege' => $validate->errors()->first()
                ]
            );
        }

        $user_id = auth()->id();
        $user = $this->user->getById($user_id);
        $checking_pass = Hash::check($request->old_password, $user->password);
        if(!$checking_pass){
            return response()->json(
                ['success' => false,
                 'massege' => 'Password not match!'
                ]
            );
        }
        $password = [
            'password' => Hash::make($request->password)
        ];
        $this->user->update($user_id, $password);

        return response()->json([
            'success' => true,
            'message'=> 'password Changed!'
        ]);

    }

    public function setDefaultAddress(Request $request){

        $user_id = auth()->id();
        $type = $request->type;
        $address_key = 'default_address_'.$user_id.$type;
        $this->session_data->updateByKey($address_key,json_encode($request->id));

        return response()->json([
            'success' => true
        ]);
    }

    public function getDefaultAddress(Request $request){

        $user_id =auth()->id();
        $type = $request->type;
        $address = [];
        $address_key = 'default_address_'.$user_id.$type;
        $session_data = $this->session_data->getFirstByKey($address_key);
        $address_id = $session_data ? json_decode($session_data->session_data) : null;
        $arrayfom = $address_id ? $this->address->getByAny('id',$address_id)->first() : null;

        if($arrayfom){

            if(is_array($arrayfom)){

                foreach($arrayfom as $data){
                    $address[] = [
                        'id' => $data->id,
                        'name' => $data->name,
                        'address' => $data->address,
                        'district' => $data->district,
                        'region' => $data->region,
                        'phone' => $data->phone,
                    ];
                }

            }else{

                $address = [
                    'id' => $arrayfom->id,
                    'name' => $arrayfom->name,
                    'address' => $arrayfom->address,
                    'district' => $arrayfom->district,
                    'region' => $arrayfom->region,
                    'phone' => $arrayfom->phone
                ];
            }

        }

        return response()->json(compact('address'));
    }

    public function userUpdate(Request $request){

        $user_id = auth()->id();
        $user_details = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'emergency_phone' => $request->emergency_phone,
            'birthday' => $request->birthday,
            'gender' => $request->gender,
            'district' => $request->district??'Dhaka',
            'postcode' => $request->postcode??'1200'
        ];

        if($user_id){

            $this->user->update($user_id, $user_details);

            return response()->json([
                'success'=> true,
                'message'=>'Infomation Updated!',
                // 'user' => auth()->user()
            ]);
        }else{

            return response()->json([
                'success'=> false,
                'message'=>'Fail to update',
                // 'user' => null
            ]);

        }

    }

    public function sumOrderNumers(){
        $user_id = auth()->id();

        $totalProductQty  = $this->order_master->self()->where('user_id', $user_id)->where('payment_term_status', 'Successful')->count();
        $totalPurchaseAmount = $this->order_master->self()->where('user_id', 1)->where('payment_term_status', 'Successful')->sum('total_amount');
        $db_masterOrders_id = $this->order_master->self()->where('user_id', 1)->where('payment_term_status', 'Successful')->get('order_random')->toArray();

        $order_ids = [];
        if(count($db_masterOrders_id)>0){
            $order_ids = array_column($db_masterOrders_id,'order_random');
        }
        $totalOrderQty = $this->order_details->self()->whereIn('order_random', $order_ids)->count();

        return response()->json(compact('totalOrderQty','totalProductQty', 'totalPurchaseAmount'));
    }



    public function deleteAccount(Request $request)
    {
        $device = $request->device;
        $email = $request->email;

        $user = null;
        $delete = null;

        if($device == 'mobile_ios' && $email != ""){
            $user = User::where('email', $email)->first();
        }

        if($user != null){
            $delete = $user->delete();
        }

        return response()->json([
            'success' => $delete != null,
            'message' => $delete != null ? 'Successfully Deleted' : 'Failed to Delete'
        ]);
    }
}
