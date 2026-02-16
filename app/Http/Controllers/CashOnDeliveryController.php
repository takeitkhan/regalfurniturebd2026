<?php
namespace App\Http\Controllers;
use App\Helpers\OrderMailHelper;
use App\Models\SessionData;
use DB;

use Illuminate\Http\Request;
use App\Library\SslCommerz\SslCommerzNotification;
use App\Models\PaymentSetting;
use App\Repositories\Dashboard\DashboardInterface;
use App\Repositories\OrdersMaster\OrdersMasterInterface;
use App\Repositories\SessionData\SessionDataInterface;
use App\Repositories\Temporaryorder\TemporaryorderInterface;
use phpDocumentor\Reflection\Types\Boolean;


class CashOnDeliveryController extends Controller
{

    private $temporaryorder;
    private $session_data;
    private $ordersmaster;
    private $dashboard;

    public function __construct( TemporaryorderInterface $temporaryorder, SessionDataInterface $session_data, OrdersMasterInterface $ordersmaster, DashboardInterface $dashboard  )
    {
        $this->temporaryorder = $temporaryorder;
        $this->session_data = $session_data;
        $this->ordersmaster = $ordersmaster;
        $this->dashboard = $dashboard;
    }

    public function placeOrder(Request $request)
    {

        $self_token = $request->header('Self-Token');
        $cart_token = "cart_".$self_token;
        $pm_token = "paymethod_".$self_token;
        $ud_token = "user_details".$self_token;
        $exord_token = "existing_order_id".$self_token;

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


        // return $session_data;


        $post_data = array();
        $post_data['total_amount'] = $payingAmount; # You cant not pay less than 10
        $post_data['currency'] = "BDT";
        $post_data['tran_id'] = uniqid(); // tran_id must be unique

        # CUSTOMER INFORMATION
        $post_data['cus_name'] = $ud->name;
        $post_data['cus_email'] = $ud->email;
        $post_data['cus_add1'] = $ud->address;
        $post_data['cus_add2'] = "";
        $post_data['cus_city'] = "";
        $post_data['cus_state'] = "";
        $post_data['cus_postcode'] = "";
        $post_data['cus_country'] = "Bangladesh";
        $post_data['cus_phone'] = $ud->phone;
        $post_data['cus_fax'] = "";

        # SHIPMENT INFORMATION
        $post_data['ship_name'] = $ud->name;
        $post_data['ship_add1'] = $ud->address;
        // $post_data['ship_add2'] = "Dhaka";
        $post_data['ship_city'] = $ud->district;
        // $post_data['ship_state'] = "Dhaka";
        // $post_data['ship_postcode'] = "1000";
        $post_data['ship_phone'] = $ud->phone;
        $post_data['ship_country'] = "Bangladesh";

        $post_data['shipping_method'] = "NO";
        $post_data['product_name'] = "Regal Product";
        $post_data['product_category'] = "Regal Product";
        $post_data['product_profile'] = "Regal Product";

        # OPTIONAL PARAMETERS
        $post_data['value_a'] = $request->self_token;
        $post_data['value_c'] = "ref003";
        $post_data['value_d'] = "ref004";



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

        $coupon_token = $this->session_data->getFirstByKey("coupon_".$self_token)->session_data ?? false;
        $checkDisocuntVal = $this->session_data->getFirstByKey($pm_token)->session_data ?? false;
        $checkDisocunt = json_decode($checkDisocuntVal, true)['discount'] ?? 0;
        $checkDisocuntType = json_decode($checkDisocuntVal, true)['discount_type'] ?? 0;
        $getSelectedCoupon = (object)[
            'coupon_type' => $checkDisocuntType,
            'coupon_code' => $coupon_token,
            'coupon_amount' => $checkDisocunt
        ];

        $data = [
            'cart' => $cart,
            'coupon_details' => null,
            'user_details' => $ud,
            'payment_method' => $pm,
            'prebooking' => $prebooking,
            'my_coupon' =>$getSelectedCoupon
        ];

    $order_id = null;

    if($exord == null){
        $temp_ensuring = $this->temporaryorder->create($attributes);

        if ($temp_ensuring) {
            //dd($data);
            $user_id = !empty($ud->user_id) ? $ud->user_id[0] : null;

            $orders_master_attributes = order_master_create($data, $rand, $secret_key, $user_id);

            $order_detail_created = order_detail_create($data, $rand, $secret_key, $user_id);

            // return $data;

            $orderplacing = $this->ordersmaster->create($orders_master_attributes);
            $orderplacing->payment_term_status = 'COD';
            $orderplacing->save();

            $order_id = $orderplacing->id;
            $update_order_id = $this->session_data->updateByKey($exord_token,$orderplacing->id);

        }
    } else {
        $order_id = $exord;
    }


        $redirectDomain = "https://regalfurniturebd.com";
        $redirectURI = $redirectDomain."/something-wrong";

        if($order_id){
            $om = $this->ordersmaster->getById($order_id);
            $redirectURI = $redirectDomain."/thank-you?order_random=$om->order_random&order_key=$om->secret_key";

            $this->session_data->updateByKey($cart_token,null);
            $this->session_data->updateByKey($pm_token,null);
            $this->session_data->updateByKey($ud_token,null);


            $widgets = $this->dashboard->getAll();
            $send_sms_phone_number   = explode(',',strip_tags(dynamic_widget($widgets, ['id' => 6])));
            $send_sms_phone_number[] = $om->phone;
            $msg_for_customer = strip_tags(dynamic_widget($widgets, ['id' => 7]));

            send_sms_formatting($send_sms_phone_number,$om->id,$om->customer_name,$msg_for_customer);
            $subject = 'Thank you for ordering from Regal Furniture!';
            $address = $ud->email;
            $data = $order_id;
            //OrderMailHelper::send($data, $subject, $address, $cc_emails = false);
        }

        SessionData::where('session_key', 'LIKE', '%'.$self_token.'%')->delete();

        return response()->json([
            'uri' => $redirectURI,
            'order_random' =>$om->order_random,
            'order_key' =>$om->secret_key,
        ]);

    }



}
