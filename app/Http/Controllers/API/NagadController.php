<?php

namespace App\Http\Controllers\API;


use App\Models\SessionData;
use App\Models\ActivityLog;
use DB;
use App\Http\Controllers\Controller;
use App\Repositories\Dashboard\DashboardInterface;
use App\Repositories\OrdersMaster\OrdersMasterInterface;
use App\Repositories\SessionData\SessionDataInterface;
use App\Repositories\Temporaryorder\TemporaryorderInterface;
use Illuminate\Http\Request;
use App\Helpers\NagadHelpers;

class NagadController extends Controller
{

    private $temporaryorder;
    private $session_data;
    private $ordersmaster;
    private $dashboard;
    /**
     * @var NagadHelpers
     */
    private $nagadHelpers;

    /**
     * @param TemporaryorderInterface $temporaryorder
     * @param SessionDataInterface $session_data
     * @param OrdersMasterInterface $ordersmaster
     * @param DashboardInterface $dashboard
     * @param NagadHelpers $nagadHelpers
     */
    public function __construct(TemporaryorderInterface $temporaryorder, SessionDataInterface $session_data, OrdersMasterInterface $ordersmaster, DashboardInterface $dashboard, NagadHelpers $nagadHelpers)
    {
        $this->temporaryorder = $temporaryorder;
        $this->session_data = $session_data;
        $this->ordersmaster = $ordersmaster;
        $this->dashboard = $dashboard;
        $this->nagadHelpers = $nagadHelpers;
    }

    public function payViaAjax(Request $request)
    {
        $self_token = $request->token;
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
        session_start();
        date_default_timezone_set('Asia/Dhaka');


        // For Order Master Table Array
        $rand = time() . uniqid('rand');
        $secret_key = time() . uniqid('secret');

        $attributes = [
            '_token' => $rand . $secret_key,
            '_previous' => json_encode($request->all()),
            'cart' => json_encode($cart),
            'coupon_details' => null,
            'user_details' => json_encode($ud),
            'payment_method' => json_encode($pm),
            'prebooking' => NULL
        ];

        $data = [
            'cart' => $cart,
            'coupon_details' => null,
            'user_details' => $ud,
            'payment_method' => $pm,
            'prebooking' => NULL
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

                        try {
                            ActivityLog::create([
                                'user_id' => $orderplacing->user_id,
                                'action' => 'order_created_api',
                                'entity_type' => 'orders_master',
                                'entity_id' => $orderplacing->id,
                                'old_values' => [],
                                'new_values' => [
                                    'order_status' => $orderplacing->order_status,
                                    'payment_method' => $orderplacing->payment_method,
                                    'payment_term_status' => $orderplacing->payment_term_status,
                                    'order_from' => $orderplacing->order_from
                                ],
                                'note' => 'Order placed via API (Nagad)',
                                'ip' => $request->ip(),
                                'url' => $request->fullUrl()
                            ]);
                        } catch (\Exception $e) {
                            // ignore logging failures
                        }

                $post_data['value_b'] = $orderplacing->id;
                $OrderId = $orderplacing->id;
                $update_order_id = $this->session_data->updateByKey($exord_token, $orderplacing->id);
            }
        } else {
            $post_data['value_b'] = $exord;
            $OrderId = $exord;
        }


        // For Order Master Table Array


        //dd($pm->grand_total);
        $MerchantID = "688446189535722"; //"683002007104225" Sandbox;
        $DateTime = Date('YmdHis');
        $amount = $pm->grand_total;
        $OrderId = $OrderId; //'TEST' . strtotime("now") . rand(1000, 10000);
        $random = $this->nagadHelpers->generateRandomString();

        //$PostURL = "http://sandbox.mynagad.com:10080/remote-payment-gateway-1.0/api/dfs/check-out/initialize/" . $MerchantID . "/13017";
//        $PostURL = "http://sandbox.mynagad.com:10080/remote-payment-gateway-1.0/api/dfs/check-out/initialize/" . $MerchantID . "/" . $OrderId;
        $PostURL = "https://api.mynagad.com/api/dfs/check-out/initialize/" . $MerchantID . "/" . $OrderId;

        $_SESSION['orderId'] = $OrderId;

//        $merchantCallbackURL = "http://localhost/gentle/merchant-callback-website.php";
        $merchantCallbackURL = url('/') . "/api/nagad/receive-nagad-data";

        $SensitiveData = array(
            'merchantId' => $MerchantID,
            'datetime' => $DateTime,
            'orderId' => $OrderId,
            'challenge' => $random
        );

        $PostData = array(
        'accountNumber' => '01844618953', //Replace with Merchant Number (not mandatory)
            'dateTime' => $DateTime,
            'sensitiveData' => $this->nagadHelpers->EncryptDataWithPublicKey(json_encode($SensitiveData)),
            'signature' => $this->nagadHelpers->SignatureGenerate(json_encode($SensitiveData))
        );

        $Result_Data = $this->nagadHelpers->HttpPostMethod($PostURL, $PostData);
//        dd($Result_Data);
        if (isset($Result_Data['sensitiveData']) && isset($Result_Data['signature'])) {
            if ($Result_Data['sensitiveData'] != "" && $Result_Data['signature'] != "") {

                $PlainResponse = json_decode($this->nagadHelpers->DecryptDataWithPrivateKey($Result_Data['sensitiveData']), true);

                if (isset($PlainResponse['paymentReferenceId']) && isset($PlainResponse['challenge'])) {
                    $paymentReferenceId = $PlainResponse['paymentReferenceId'];
                    $randomServer = $PlainResponse['challenge'];

                    $SensitiveDataOrder = array(
                        'merchantId' => $MerchantID,
                        'orderId' => $OrderId,
                        'currencyCode' => '050',
                        'amount' => $amount,
                        'challenge' => $randomServer
                    );


                    $logo = "https://regalfurniturebd.com/_nuxt/img/only-logo.4a83d0c.svg";

                    $merchantAdditionalInfo = '{
                        "serviceName":"Regal Furniture BD",
                        "serviceLogoURL": "' . $logo . '",
                        "additionalFieldNameEN": "Type",
                        "additionalFieldNameBN": "টাইপ",
                        "additionalFieldValue": "Payment",
                        "self_token": "' . $self_token . '"
                    }';

                    $PostDataOrder = array(
                        'sensitiveData' => $this->nagadHelpers->EncryptDataWithPublicKey(json_encode($SensitiveDataOrder)),
                        'signature' => $this->nagadHelpers->SignatureGenerate(json_encode($SensitiveDataOrder)),
                        'merchantCallbackURL' => $merchantCallbackURL,
                        'additionalMerchantInfo' => json_decode($merchantAdditionalInfo)
                    );


//                    $OrderSubmitUrl = "http://sandbox.mynagad.com:10080/remote-payment-gateway-1.0/api/dfs/check-out/complete/" . $paymentReferenceId;
                    $OrderSubmitUrl = "https://api.mynagad.com/api/dfs/check-out/complete/" . $paymentReferenceId;
                    $Result_Data_Order = $this->nagadHelpers->HttpPostMethod($OrderSubmitUrl, $PostDataOrder);

//                    dd($Result_Data_Order);

//                    if ($Result_Data_Order['status'] == "Success") {
//                        $url = json_encode($Result_Data_Order['callBackUrl']);
//                        return "<script>window.open($url, '_self')</script>";
//                    } else {
//                        echo json_encode($Result_Data_Order);
//
//                    }
                    if ($Result_Data_Order['status'] == "Success") {
                        $url = json_encode($Result_Data_Order['callBackUrl']);
                        //$redirectUrl = "<script>window.open($url, '_self')</script>";
                        $redirectUrl = $url;
                    } else {
                        echo json_encode($Result_Data_Order);
                    }
                } else {
                    echo json_encode($PlainResponse);
                }
            }
        }
        $cUrl = $Result_Data_Order['callBackUrl'];

        return response()->json([
            'url' => $cUrl
        ]);

    }

    public function receiveNagadData(Request $request)
    {
        //dd($request->payment_ref_id);

        //$Query_String = explode("&", explode("?", $_SERVER['REQUEST_URI'])[1]);
        //$payment_ref_id = substr($Query_String[2], 15);

//        $url = "http://sandbox.mynagad.com:10080/remote-payment-gateway-1.0/api/dfs/verify/payment/" . $request->payment_ref_id;
        $url = "https://api.mynagad.com/api/dfs/verify/payment/" . $request->payment_ref_id;
        $json = $this->nagadHelpers->HttpGet($url);
        $arr = json_decode($json, true);


        //dd($arr);
        $additionalInfo = json_decode($arr['additionalMerchantInfo'], true);
        //dd($additionalInfo);
        $self_token = $additionalInfo['self_token'];
        $cart_token = "cart_" . $self_token;
        $pm_token = "paymethod_" . $self_token;
        $ud_token = "user_details" . $self_token;
        $exord_token = "existing_order_id" . $self_token;

        //dd($arr);

        if ($arr['status'] == 'Success') {
            $amountPaid = $arr['amount'];
            $om = $this->ordersmaster->getById($arr['orderId']);

            if ($amountPaid != $om->grand_total) {
                $status = 'Partial';
            }

            $tranAmount = $arr['issuerPaymentRefNo'] . ":" . $arr['amount'];
            $tranAmount = $om->trans_id != null ? $om->trans_id . "|$tranAmount" : $tranAmount;

            $order = $this->ordersmaster->update($arr['orderId'], [
                'payment_term_status' => "Successful",
                'order_status' => 'placed',
                'amount_paid' => $amountPaid,
                'trans_id' => $tranAmount
            ]);

            try {
                ActivityLog::create([
                    'user_id' => $om->user_id,
                    'action' => 'payment_status_update_gateway',
                    'entity_type' => 'orders_master',
                    'entity_id' => $om->id,
                    'old_values' => [
                        'payment_term_status' => $om->payment_term_status,
                        'order_status' => $om->order_status
                    ],
                    'new_values' => [
                        'payment_term_status' => 'Successful',
                        'order_status' => 'placed',
                        'amount_paid' => $amountPaid
                    ],
                    'note' => 'Nagad payment success',
                    'ip' => $request->ip(),
                    'url' => $request->fullUrl()
                ]);
            } catch (\Exception $e) {
                // ignore logging failures
            }


            $this->session_data->updateByKey($cart_token, null);
            $this->session_data->updateByKey($pm_token, null);
            $this->session_data->updateByKey($ud_token, null);

            SessionData::where('session_key', 'LIKE', '%' . $self_token . '%')->delete();

            $widgets = $this->dashboard->getAll();
            $send_sms_phone_number = explode(',', strip_tags(dynamic_widget($widgets, ['id' => 6])));
            $send_sms_phone_number[] = $om->phone;
            $msg_for_customer = strip_tags(dynamic_widget($widgets, ['id' => 7]));

            send_sms_formatting($send_sms_phone_number, $om->id, $om->customer_name, $msg_for_customer);

            $redirectDomain = "https://regalfurniturebd.com";
            $redirectURI = $redirectDomain . "/something-wrong";

            $redirectURI = $redirectDomain . "/thank-you?order_random=$om->order_random&order_key=$om->secret_key";
            //dd($redirectURI);

            $url = json_encode($redirectURI);
            return "<script>window.open($url, '_self')</script>";
        } else {
            $redirectDomain = "https://regalfurniturebd.com";
            $redirectURI = $redirectDomain . "/something-wrong";

            $url = json_encode($redirectURI);
            return "<script>window.open($url, '_self')</script>";
        }
    }


}
