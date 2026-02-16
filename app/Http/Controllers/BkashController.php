<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Repositories\OrdersMaster\OrdersMasterInterface;
use App\Repositories\SessionData\SessionDataInterface;
use App\Repositories\Temporaryorder\TemporaryorderInterface;

class BkashController extends Controller
{
    private $base_url;
    private $app_key;
    private $app_secret;
    private $username;
    private $password;


    private $temporaryorder;
    private $session_data;
    private $ordersmaster;

    public function __construct(TemporaryorderInterface $temporaryorder, SessionDataInterface $session_data, OrdersMasterInterface $ordersmaster)
    {
        // bKash Merchant API Information

        // You can import it from your Database
        $bkash_app_key = '5tunt4masn6pv2hnvte1sb5n3j'; // bKash Merchant API APP KEY
        $bkash_app_secret = '1vggbqd4hqk9g96o9rrrp2jftvek578v7d2bnerim12a87dbrrka'; // bKash Merchant API APP SECRET
        $bkash_username = 'sandboxTestUser'; // bKash Merchant API USERNAME
        $bkash_password = 'hWD@8vtzw0'; // bKash Merchant API PASSWORD
        $bkash_base_url = 'https://checkout.sandbox.bka.sh/v1.2.0-beta'; // For Live Production URL: https://checkout.pay.bka.sh/v1.2.0-beta

        $this->app_key = $bkash_app_key;
        $this->app_secret = $bkash_app_secret;
        $this->username = $bkash_username;
        $this->password = $bkash_password;
        $this->base_url = $bkash_base_url;



        $this->temporaryorder = $temporaryorder;
        $this->session_data = $session_data;
        $this->ordersmaster = $ordersmaster;
    }

    public function getToken(Request $request)
    {
        $self_token = $request->self_token;
        $bkash_cache_token = 'bkash_token_'.$self_token;

        Cache::forget($bkash_cache_token);

        $post_token = array(
            'app_key' => $this->app_key,
            'app_secret' => $this->app_secret
        );

        $headers = [
            "Accept: application/json",
            "Content-Type: application/json",
            "password: ".$this->password,
            "username: ".$this->username
        ];

        $curl = curl_init();
        
        curl_setopt_array($curl, [
          CURLOPT_URL => "$this->base_url/checkout/token/grant",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => json_encode($post_token),
          CURLOPT_HTTPHEADER => $headers,
        ]);
        
        $resultdata = curl_exec($curl);
        $err = curl_error($curl);
        
        curl_close($curl);
        

        $response = json_decode($resultdata, true);

        if (array_key_exists('msg', $response)) {
            return $response;
        }

        Cache::forever($bkash_cache_token, $response['id_token']);

        return response()->json(['success', true]);
    }

    public function createPayment(Request $request)
    {

        $self_token = $request->self_token;
        $cart_token = "cart_".$self_token;
        $pm_token = "paymethod_".$self_token;
        $ud_token = "user_details".$self_token;
        $exord_token = "existing_order_id".$self_token;
        $bkash_cache_token = 'bkash_token_'.$self_token;


        $cart_session = $this->session_data->getFirstByKey($cart_token);
        $cart = ($cart_session->session_data??false) ? json_decode($cart_session->session_data) : null;

        $pm_session = $this->session_data->getFirstByKey($pm_token);
        $pm = ($pm_session->session_data??false) ? json_decode($pm_session->session_data) : null;

        $ud_session = $this->session_data->getFirstByKey($ud_token);
        $ud = ($ud_session->session_data??false) ? json_decode($ud_session->session_data) : null;


        $exord_session = $this->session_data->getFirstByKey($exord_token);
        $exord = ($exord_session->session_data??false) ? json_decode($exord_session->session_data) : null;

        if(!$cart || !$pm || !$ud){
            return false;
        }

        $payingAmount = $pm->grand_total;


        $prebooking_token = "prebooking_".$self_token;
        $session_data = $this->session_data->getFirstByKey($prebooking_token);
        $prebooking = (Boolean) (($session_data->session_data??false) ? json_decode($session_data->session_data) : null);

        if($prebooking){

            $prebooking_min_token = "prebooking_min".$self_token;
            $session_data = $this->session_data->getFirstByKey($prebooking_min_token);
            $prebooking_min = (($session_data->session_data??false) ? json_decode($session_data->session_data) : null);
    

            $paying_amount_token = "paying_amount_".$self_token;
            $session_data = $this->session_data->getFirstByKey($paying_amount_token);
            $paying_amount_data =  (($session_data->session_data??false) ? json_decode($session_data->session_data) : null);
            

            if($prebooking_min <= $paying_amount_data){
                $payingAmount = $paying_amount_data;
            }

        }





        if (((string) $request->amount != (string) $payingAmount)) {
            return response()->json([
                'errorMessage' => 'Amount Mismatch',
                'errorCode' => 2006,
                'message' => [$request->amount,$payingAmount] 
            ],422);
        }


        // dd($post_data);
        $rand = time().uniqid('rand');
        $secret_key = time().uniqid('secret');

        $attributes = [
            '_token' => $rand.$secret_key,
            '_previous' => json_encode($request->all()),
            'cart' => json_encode($cart),
            'coupon_details' => null,
            'user_details' => json_encode($ud),
            'payment_method' => json_encode($pm),
            'prebooking' => $prebooking
        ];

        $data = [
            'cart' => $cart,
            'coupon_details' => null,
            'user_details' => $ud,
            'payment_method' => $pm,
            'prebooking' => $prebooking
        ];
        

    if($exord == null){
        $temp_ensuring = $this->temporaryorder->create($attributes);

        if ($temp_ensuring) {
            //dd($data);
            $user_id = !empty($ud->user_id) ? $ud->user_id[0] : null;

            $orders_master_attributes = order_master_create($data, $rand, $secret_key, $user_id);

            $order_detail_created = order_detail_create($data, $rand, $secret_key, $user_id);

            // return $data;

            $orderplacing = $this->ordersmaster->create($orders_master_attributes);
            $orderplacing->payment_term_status = 'Pending';
            $orderplacing->save();

            $request['merchantInvoiceNumber'] = $orderplacing->id;
            $update_order_id = $this->session_data->updateByKey($exord_token,$orderplacing->id);

        }    
    }else{
        $request['merchantInvoiceNumber'] = $exord;
    }



        $token = Cache::get($bkash_cache_token);

        $request['intent'] = 'sale';
        $request['currency'] = 'BDT';

        $url = curl_init("$this->base_url/checkout/payment/create");
        $request_data_json = json_encode($request->all());
        $header = array(
            'Content-Type:application/json',
            "authorization: $token",
            "x-app-key: $this->app_key"
        );

        curl_setopt($url, CURLOPT_HTTPHEADER, $header);
        curl_setopt($url, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($url, CURLOPT_POSTFIELDS, $request_data_json);
        curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($url, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        $resultdata = curl_exec($url);
        curl_close($url);
        return json_decode($resultdata, true);
    }

    public function executePayment(Request $request)
    {
        $self_token = $request->self_token;
        $bkash_cache_token = 'bkash_token_'.$self_token;

        $token = Cache::get($bkash_cache_token);

        $paymentID = $request->paymentID;
        $url = curl_init("$this->base_url/checkout/payment/execute/" . $paymentID);
        $header = array(
            'Content-Type:application/json',
            "authorization:$token",
            "x-app-key:$this->app_key"
        );

        curl_setopt($url, CURLOPT_HTTPHEADER, $header);
        curl_setopt($url, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);
        $resultdata = curl_exec($url);
        curl_close($url);
        return json_decode($resultdata, true);
    }

    public function queryPayment(Request $request)
    {
        $self_token = $request->self_token;
        $bkash_cache_token = 'bkash_token_'.$self_token;

        $token = Cache::get($bkash_cache_token);
        $paymentID = $request->payment_info['payment_id'];

        $url = curl_init("$this->base_url/checkout/payment/query/" . $paymentID);
        $header = array(
            'Content-Type:application/json',
            "authorization:$token",
            "x-app-key:$this->app_key"
        );

        curl_setopt($url, CURLOPT_HTTPHEADER, $header);
        curl_setopt($url, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);
        $resultdata = curl_exec($url);
        curl_close($url);
        return json_decode($resultdata, true);
    }

    public function bkashSuccess(Request $request)
    {
        // IF PAYMENT SUCCESS THEN YOU CAN APPLY YOUR CONDITION HERE
        if ('Noman' == 'success') {

            // THEN YOU CAN REDIRECT TO YOUR ROUTE

            Session::flash('successMsg', 'Payment has been Completed Successfully');

            return response()->json(['status' => true]);
        }

        Session::flash('error', 'Noman Error Message');

        return response()->json(['status' => false]);
    }
}
