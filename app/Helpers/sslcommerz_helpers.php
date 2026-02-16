<?php

/**
 * @param $length
 * @return string
 */
function rand_string($length)
{
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

    $size = strlen($chars);
    $str = '';
    for ($i = 0; $i < $length; $i++) {
        $str .= $chars[rand(0, $size - 1)];
    }
    return $str;
}


function prepare_sslcommerz($cart, $user_data, $trackcode, $secret_key, $order_id)
{

    //dump($user_data);
    //dump($user_data->name);
    //dump($trackcode);
    //dump($secret_key);

    $qty = [];
    $price = [];
    foreach ($cart->items as $item) {
        $qty[] = $item['qty'];
        $price[] = $item['purchaseprice'];
    }
    $total_amount = array_sum($price);
    //dd($cart);

    $cur_random_value = rand_string(18);
    //$url = 'https://securepay.sslcommerz.com/gwprocess/v3/api.php';
    $url = 'https://sandbox.sslcommerz.com/gwprocess/v3/api.php';

    $fields = array(
        //test sandbox
        'store_id' => 'triti5c7a16d03c981',
        'store_passwd' => 'triti5c7a16d03c981@ssl',
        //live sand box
        // 'store_id' => 'bangabuildingmaterialsltdlive',
        // 'store_passwd' => '5B7157BC8AC7442718',
        'total_amount' => $total_amount,
        // 'payment_type' => 'VISA',
        'currency' => 'BDT',
        'tran_id' => $cur_random_value,
        'cus_name' => $user_data->name,
        'cus_email' => $user_data->email != '' ? $user_data->email : 'customer@regalfurniturebd.com',
        'cus_add1' => $user_data->address,
        'cus_city' => 'Dhaka',
        'cus_state' => 'Dhaka',
        'cus_postcode' => '1212',
        'cus_country' => 'Bangladesh',
        'cus_phone' => $user_data->phone,
        'cus_fax' => 'Not-Applicable',
        'ship_name' => $user_data->name,
        'ship_add1' => $user_data->address,
        'ship_city' => 'Dhaka',
        'ship_state' => 'Dhaka',
        'ship_postcode' => '1212',
        'ship_country' => 'Bangladesh',
        'desc' => 'Door',
        'success_url' => url('/checkout/success?order_id=' . $order_id . '&random_code=' . $trackcode . '&secret_key=' . $secret_key),
        'fail_url' => url('/checkout/failed?order_id=' . $order_id . '&random_code=' . $trackcode . '&secret_key=' . $secret_key),
        'cancel_url' => url('/view_cart')
    );

    //dd($fields);

    $fields_string = '';
    foreach ($fields as $key => $value) {
        $fields_string .= $key . '=' . $value . '&';
    }
    $fields_string = rtrim($fields_string, '&');


    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, count($fields));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

    $content = curl_exec($ch);

    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($code == 200 && !(curl_errno($ch))) {
        curl_close($ch);
        $sslcommerzResponse = $content;
        //dd($sslcommerzResponse);
    } else {
        curl_close($ch);
        echo "FAILED TO CONNECT WITH SSLCOMMERZ API";
        exit;
    }

    # PARSE THE JSON RESPONSE
    $sslcz = json_decode($sslcommerzResponse, true);
    //dd($sslcz);

    return $sslcz;
    //dd($sslcz['GatewayPageURL']);
    //echo $sslcz['GatewayPageURL'];
}


function payment_return_url()
{

    $response = $this->input->get('order_id');
    $response = explode('_', $response);
    $status = $response[0];
    $orderId = $response[1];
    //owndebugger($response); die();

    // $msg = $this->input->post(); //$_POST; // $this->input->post();
    if ($status == 'success') {
        $data['param'] = jsonEncode(array('status' => 1, 'epw' => $msg));
        //update
        $this->db->where('id', $orderId);
        $this->db->update('ecommerce_order_master', $data);
        $this->data['track_msg'] = 'Payment Has Been Successful. We will contact you shortly.';
        $this->data['status'] = 1;
        //Mobile MSG Operation
        $result = $this->db->get_where('ecommerce_order_master', array(
            'id' => $orderId
        ))->row();


        //$customer_message = "Order has been placed successfully on Regal Furniture\nOrder No: " . $result->id . "\nHotline: 09613737777";
        $customer_message = "Order has been placed successfully on Regal Furniture\nOrder No: " . $result->id;
        sendSms($customer_message, $result->contuct_number);
        sendSms($customer_message, $result->alternative_mobile);

        //Mobile MSG admin
        $message = "New Order has been placed successfully on Regal Furniture\nOrder No: " . $result->id;
        sendSms($message, '01844659228'); //Shuvo-01992659228
        sendSms($message, '01844602020'); //Habib-01924602020
        sendSms($message, '01844665159'); //Habib-01924602020

        $track = $this->db->get_where('ecommerce_order_master', array(
            'id' => $orderId
        ))->row()->payment_parameter;
        redirect("view_invoicer/redirect_to?track_id={$track}&status={$this->data['status']}&message={$this->data['track_msg']}");


    } else {
        $data['param'] = jsonEncode(array('status' => 2, 'epw' => $msg));
        //update
        $this->db->where('id', $orderId);
        $this->db->update('ecommerce_order_master', $data);
        $this->data['track_msg'] = 'Payment Has Been Failed. But, your data has been saved to our database. We will contact you soon.';
        $this->data['status'] = 2;

        $track = $this->db->get_where('ecommerce_order_master', array(
            'id' => $orderId
        ))->row()->payment_parameter;
        redirect("view_invoicer/redirect_to?track_id={$track}&status={$this->data['status']}&message={$this->data['track_msg']}");
    }

    //$track = $this->db->get_where('ecommerce_order_master', array(
    //           'id' => $orderId
    //       ))->row()->payment_parameter;
    //redirect("view_invoicer/redirect_to?track_id={$track}&status={$this->data['status']}&message={$this->data['track_msg']}");


}