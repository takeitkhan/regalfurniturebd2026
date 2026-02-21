<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\SessionData;
use Illuminate\Http\Request;
use Session;
use URL;
use Illuminate\Support\Str;

use App\Repositories\Dashboard\DashboardInterface;
use App\Repositories\OrdersMaster\OrdersMasterInterface;
use App\Repositories\SessionData\SessionDataInterface;
use App\Repositories\Temporaryorder\TemporaryorderInterface;



class BkashController extends Controller
{
    private $base_url;

    private $temporaryorder;
    private $session_data;
    private $ordersmaster;
    private $dashboard;

    private $bkashUserName;
    private $bkashPass;
    private $bkashAppKey;
    private $bkashAppSecret;

    public function __construct(TemporaryorderInterface $temporaryorder, SessionDataInterface $session_data, OrdersMasterInterface $ordersmaster, DashboardInterface $dashboard)
    {
        // Sandbox
//        $this->base_url = 'https://tokenized.sandbox.bka.sh/v1.2.0-beta';
        // Live
        $this->base_url = 'https://tokenized.pay.bka.sh/v1.2.0-beta';

        $this->temporaryorder = $temporaryorder;
        $this->session_data = $session_data;
        $this->ordersmaster = $ordersmaster;
        $this->dashboard = $dashboard;

        //Sandbox
        /*
        $this->bkashUserName = 'sandboxTokenizedUser02';
        $this->bkashPass = 'sandboxTokenizedUser02@12345';
        $this->bkashAppKey = '4f6o0cjiki2rfm34kfdadl1eqq';
        $this->bkashAppSecret = '2is7hdktrekvrbljjh44ll3d9l1dtjo4pasmjvs5vl5qr3fug4b';
*/

        $this->bkashUserName = '01844200777';//'sandboxTokenizedUser02';
        $this->bkashPass = 'xU8mY,<ve!8';//'sandboxTokenizedUser02@12345';
        $this->bkashAppKey = 'cbcS0p3Md8SXDozFQ1r58GwQtc';//'4f6o0cjiki2rfm34kfdadl1eqq';
        $this->bkashAppSecret = 'ppYLl6OIPZQr8ww2gBjiV3E7tk6Kourokum625NdmQClRhGgH50Q';//'2is7hdktrekvrbljjh44ll3d9l1dtjo4pasmjvs5vl5qr3fug4b';

    }

    public function authHeaders(){
        return array(
            'Content-Type:application/json',
            'Authorization:' .$this->grant(),
            'X-APP-Key:'.$this->bkashAppKey
        );
    }

    public function curlWithBody($url,$header,$method,$body_data_json){
        $curl = curl_init($this->base_url.$url);
        curl_setopt($curl,CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl,CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl,CURLOPT_POSTFIELDS, $body_data_json);
        curl_setopt($curl,CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    public function grant()
    {
        $header = array(
            'Content-Type:application/json',
            'username:'.$this->bkashUserName,
            'password:'.$this->bkashPass
        );
        $header_data_json=json_encode($header);

        $body_data = array('app_key'=> $this->bkashAppKey, 'app_secret'=> $this->bkashAppSecret);
        $body_data_json=json_encode($body_data);

        $response = $this->curlWithBody('/tokenized/checkout/token/grant',$header,'POST',$body_data_json);
//        dd($response);
        $token = json_decode($response)->id_token;

        return $token;
    }

    public function create(Request $request)
    {
        //return $request->all();
        $header =$this->authHeaders();

        //custom code
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
                        'note' => 'Order placed via API (bKash)',
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
        $DateTime = Date('YmdHis');
        $amount = ceil($pm->grand_total);
        //End
        //return $amount;
        $website_url = URL::to("/");

        $body_data = array(
            'mode' => '0011', //'0011',
            'payerReference' => ' ',
            'callbackURL' => $website_url.'/api/bkash/callback?orderId='.$OrderId.'&&self-token='.$self_token.'&&amount='.$amount,
            'amount' => $amount, //$request->amount ? $request->amount : 10,
            'currency' => 'BDT',
            'intent' => 'sale',
            'merchantInvoiceNumber' => $OrderId, //"Inv".Str::random(8) // you can pass here OrderID
            'self_token' => $self_token,
        );
        $body_data_json=json_encode($body_data);

        $response = $this->curlWithBody('/tokenized/checkout/create',$header,'POST',$body_data_json);

        return $response;
    }

    public function execute($paymentID)
    {

        $header =$this->authHeaders();

        $body_data = array(
            'paymentID' => $paymentID
        );
        $body_data_json=json_encode($body_data);

        $response = $this->curlWithBody('/tokenized/checkout/execute',$header,'POST',$body_data_json);

        $res_array = json_decode($response,true);

        return $response;
    }

    public function query($paymentID)
    {

        $header =$this->authHeaders();

        $body_data = array(
            'paymentID' => $paymentID,
        );
        $body_data_json=json_encode($body_data);

        $response = $this->curlWithBody('/tokenized/checkout/payment/status',$header,'POST',$body_data_json);

        $res_array = json_decode($response,true);

        return $response;
    }

    public function callback(Request $request)
    {
        $allRequest = $request->all();

        //dd($allRequest);
        $self_token = $request->self_token;
        $cart_token = "cart_" . $self_token;
        $pm_token = "paymethod_" . $self_token;
        $ud_token = "user_details" . $self_token;
        $exord_token = "existing_order_id" . $self_token;

        $redirectDomain = "https://regalfurniturebd.com";
//        $redirectDomain = "http://localhost:3000";

        if(isset($allRequest['status']) && $allRequest['status'] == 'success'){

            $response = $this->execute($allRequest['paymentID']);

            $arr = json_decode($response,true);

            if(array_key_exists("message",$arr)){
                // if execute api failed to response
                sleep(1);
                $response = $this->query($allRequest['paymentID']);
                $arr = json_decode($response,true);
            }

            if(array_key_exists("statusCode",$arr) && $arr['statusCode'] != '0000'){
                // your frontend failed route
                return redirect($redirectDomain.'/fail?data='.$arr['statusMessage']);
            }else{

                // response save to your db


                $amountPaid = $request->amount;
                $orderId =  $request->orderId;
                $om = $this->ordersmaster->getById($orderId);

                if ($amountPaid != $om->grand_total) {
                    $status = 'Partial';
                }

                $tranAmount = $request->paymentID . ":" . $request->amount;
                $tranAmount = $om->trans_id != null ? $om->trans_id . "|$tranAmount" : $tranAmount;

                $order = $this->ordersmaster->update($orderId, [
                    'payment_term_status' => "Successful",
                    'order_status' => "placed",
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
                        'note' => 'bKash payment success',
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

//                $redirectDomain = "https://regalfurniturebd.com";
                //$redirectDomain = "http://localhost:3000";
//                $redirectURI = $redirectDomain . "/something-wrong";

                $redirectURI = $redirectDomain . "/thank-you?order_random=$om->order_random&order_key=$om->secret_key";


                // your frontend success route
//                return redirect('http://localhost:3000/success?data='.$arr['statusMessage']);
                return redirect($redirectURI);
            }

        }else{
            // your frontend failed route
            return redirect($redirectDomain.'/fail');

        }

    }

    public function refund(Request $request)
    {
        $header =$this->authHeaders();

        $body_data = array(
            'paymentID' => 'TR0011861679292438620', //$request->paymentID,
            'amount' => '1',//$request->amount,
            'trxID' => 'ACK8MH68ZU',//$request->trxID,
            'sku' => 'sku',
            'reason' => 'Quality issue'
        );

        $body_data_json=json_encode($body_data);

        $response = $this->curlWithBody('/tokenized/checkout/payment/refund',$header,'POST',$body_data_json);

        // your database operation
        // save $response

        return $response;
    }

}
