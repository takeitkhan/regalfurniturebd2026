<?php

namespace App\Http\Controllers;

use DB;

use Illuminate\Http\Request;
use App\Library\SslCommerz\SslCommerzNotification;
use App\Models\PaymentSetting;
use App\Repositories\Dashboard\DashboardInterface;
use App\Repositories\OrdersMaster\OrdersMasterInterface;
use App\Repositories\SessionData\SessionDataInterface;
use App\Repositories\Temporaryorder\TemporaryorderInterface;
use phpDocumentor\Reflection\Types\Boolean;

class SslCommerzController extends Controller
{

    private $temporaryorder;
    private $session_data;
    private $ordersmaster;
    private $dashboard;

    public function __construct(TemporaryorderInterface $temporaryorder, SessionDataInterface $session_data, OrdersMasterInterface $ordersmaster, DashboardInterface $dashboard)
    {
        $this->temporaryorder = $temporaryorder;
        $this->session_data = $session_data;
        $this->ordersmaster = $ordersmaster;
        $this->dashboard = $dashboard;
    }

    public function payViaAjax(Request $request)
    {
        $self_token = $request->self_token;
        $cart_token = "cart_" . $self_token;
        $pm_token = "paymethod_" . $self_token;
        $ud_token = "user_details" . $self_token;
        $exord_token = "existing_order_id" . $self_token;

        $cart_session = $this->session_data->getFirstByKey($cart_token);
        $cart = ($cart_session->session_data ?? false) ? json_decode($cart_session->session_data) : null;

        $pm_session = $this->session_data->getFirstByKey($pm_token);
        $pm = ($pm_session->session_data ?? false) ? json_decode($pm_session->session_data) : null;

        $ud_session = $this->session_data->getFirstByKey($ud_token);
        $ud = ($ud_session->session_data ?? false) ? json_decode($ud_session->session_data) : null;


        $exord_session = $this->session_data->getFirstByKey($exord_token);
        $exord = ($exord_session->session_data ?? false) ? json_decode($exord_session->session_data) : null;

        if (!$cart || !$pm || !$ud) {
            return false;
        }

        $payingAmount = $pm->grand_total;


        $prebooking_token = "prebooking_" . $self_token;
        $session_data = $this->session_data->getFirstByKey($prebooking_token);
        $prebooking = (boolean)(($session_data->session_data ?? false) ? json_decode($session_data->session_data) : null);

        if ($prebooking) {

            $prebooking_min_token = "prebooking_min" . $self_token;
            $session_data = $this->session_data->getFirstByKey($prebooking_min_token);
            $prebooking_min = (($session_data->session_data ?? false) ? json_decode($session_data->session_data) : null);


            $paying_amount_token = "paying_amount_" . $self_token;
            $session_data = $this->session_data->getFirstByKey($paying_amount_token);
            $paying_amount_data = (($session_data->session_data ?? false) ? json_decode($session_data->session_data) : null);


            if ($prebooking_min <= $paying_amount_data) {
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
        $rand = time() . uniqid('rand');
        $secret_key = time() . uniqid('secret');

        $attributes = [
            '_token' => $rand . $secret_key,
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
            'prebooking' => $prebooking,
            'device' => $request->device??'website'
        ];


        if ($exord == null) {
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

                $post_data['value_b'] = $orderplacing->id;
                $update_order_id = $this->session_data->updateByKey($exord_token, $orderplacing->id);

            }
        } else {
            $post_data['value_b'] = $exord;
        }


        $sslc = new SslCommerzNotification();
        # initiate(Transaction Data , false: Redirect to SSLCOMMERZ gateway/ true: Show all the Payement gateway here )
        $payment_options = $sslc->makePayment($post_data, 'checkout', 'json');
        // return $payment_options;

        if (!is_array($payment_options)) {
            print_r($payment_options);
            $payment_options = array();
        }

    }

    public function existingOrderPay(Request $request)
    {

        $order = $this->ordersmaster->getById($request->order_id);

        if (!$order && $order->payment_term_status == 'Successful')
            die;


        $post_data = array();
        $post_data['total_amount'] = $order->grand_total - $order->amount_paid; # You cant not pay less than 10
        $post_data['currency'] = "BDT";
        $post_data['tran_id'] = uniqid(); // tran_id must be unique

        # CUSTOMER INFORMATION
        $post_data['cus_name'] = $order->customer_name;
        $post_data['cus_email'] = $order->email;
        $post_data['cus_add1'] = $order->address;
        $post_data['cus_add2'] = "";
        $post_data['cus_city'] = "";
        $post_data['cus_state'] = "";
        $post_data['cus_postcode'] = "";
        $post_data['cus_country'] = "Bangladesh";
        $post_data['cus_phone'] = $order->phone;
        $post_data['cus_fax'] = "";

        # SHIPMENT INFORMATION
        $post_data['ship_name'] = $order->customer_name;
        $post_data['ship_add1'] = $order->address;
        // $post_data['ship_add2'] = "Dhaka";
        $post_data['ship_city'] = $order->district;
        // $post_data['ship_state'] = "Dhaka";
        // $post_data['ship_postcode'] = "1000";
        $post_data['ship_phone'] = $order->phone;
        $post_data['ship_country'] = "Bangladesh";

        $post_data['shipping_method'] = "NO";
        $post_data['product_name'] = "Regal Product";
        $post_data['product_category'] = "Regal Product";
        $post_data['product_profile'] = "Regal Product";

        # OPTIONAL PARAMETERS
        $post_data['value_a'] = '';
        $post_data['value_c'] = "ref003";
        $post_data['value_d'] = "ref004";

        $post_data['value_b'] = $request->order_id;


        $sslc = new SslCommerzNotification();
        # initiate(Transaction Data , false: Redirect to SSLCOMMERZ gateway/ true: Show all the Payement gateway here )
        $payment_options = $sslc->makePayment($post_data, 'checkout', 'json');
        // return $payment_options;

        if (!is_array($payment_options)) {
            print_r($payment_options);
            $payment_options = array();
        }
    }

    public function success(Request $request)
    {

        $tran_id = $request->input('tran_id');
        $amount = $request->input('amount');
        $currency = $request->input('currency');
        $order_id = $request->value_b;

        $self_token = $request->value_a;
        $cart_token = "cart_" . $self_token;
        $pm_token = "paymethod_" . $self_token;
        $ud_token = "user_details" . $self_token;

        $sslc = new SslCommerzNotification();

        $validation = $sslc->orderValidate($request->all(), $tran_id, $amount, $currency);

        $redirectDomain = "https://regalfurniturebd.com";
        $redirectURI = $redirectDomain . "/something-wrong";

        if ($validation && $order_id) {
            $om = $this->ordersmaster->getById($order_id);
            $redirectURI = $redirectDomain . "/redirect-from-ssl?order_random=$om->order_random&order_key=$om->secret_key&from=ssl";

            if (strpos($om->trans_id, $tran_id) !== false) {
                return redirect()->away($redirectURI);
            }


            $amountPaid = $amount + $om->amount_paid;
            $status = 'Successful';
            if ($amountPaid != $om->grand_total) {
                $status = 'Partial';
            }

            $tranAmount = $tran_id . ":" . $amount;
            $tranAmount = $om->trans_id != null ? $om->trans_id . "|$tranAmount" : $tranAmount;

            $order = $this->ordersmaster->update($order_id, [
                'payment_term_status' => $status,
                'amount_paid' => $amountPaid,
                'trans_id' => $tranAmount
            ]);

            $this->session_data->updateByKey($cart_token, null);
            $this->session_data->updateByKey($pm_token, null);
            $this->session_data->updateByKey($ud_token, null);


            $widgets = $this->dashboard->getAll();
            $send_sms_phone_number = explode(',', strip_tags(dynamic_widget($widgets, ['id' => 6])));
            $send_sms_phone_number[] = $om->phone;
            $msg_for_customer = strip_tags(dynamic_widget($widgets, ['id' => 7]));

            send_sms_formatting($send_sms_phone_number, $om->id, $om->customer_name, $msg_for_customer);

        }

        return redirect()->away($redirectURI);
    }

    public function fail(Request $request)
    {

        return 'waiting to code';
    }

    public function cancel(Request $request)
    {

        return 'waiting to code';
    }

    public function ipn(Request $request)
    {
        $tran_id = $request->input('tran_id');
        $amount = $request->input('amount');
        $currency = $request->input('currency');
        $order_id = $request->value_b;
        $self_token = $request->value_a;
        $cart_token = "cart_" . $self_token;
        $pm_token = "paymethod_" . $self_token;
        $ud_token = "user_details" . $self_token;

        $sslc = new SslCommerzNotification();

        $validation = $sslc->orderValidate($request->all(), $tran_id, $amount, $currency);

        if ($validation && $order_id) {
            $om = $this->ordersmaster->getById($order_id);

            if (strpos($om->trans_id, $tran_id) !== false) {
                return [
                    'success' => false,
                    'message' => 'Already updated'
                ];
            }


            $status = 'Successful';

            if ($amount != $om->grand_total) {
                $status = 'Partial';
            }

            $tranAmount = $tran_id . ":" . $amount;
            $tranAmount = $om->trans_id != null ? $om->trans_id . "|$tranAmount" : $tranAmount;
            $amountPaid = $amount + $om->amount_paid;

            $order = $this->ordersmaster->update($order_id, [
                'payment_term_status' => $status,
                'amount_paid' => $amountPaid,
                'trans_id' => $tranAmount
            ]);


            $this->session_data->updateByKey($cart_token, null);
            $this->session_data->updateByKey($pm_token, null);
            $this->session_data->updateByKey($ud_token, null);

        }

        return [
            'success' => true,
            'message' => 'Successfully updated'
        ];
    }


}
