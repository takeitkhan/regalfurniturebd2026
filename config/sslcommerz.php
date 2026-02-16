<?php

// SSLCommerz configuration

return [
    'projectPath' => env('PROJECT_PATH'),
    // For Sandbox, use "https://sandbox.sslcommerz.com"
    // For Live, use "https://securepay.sslcommerz.com"
   'apiDomain' => env("API_DOMAIN_URL", "https://securepay.sslcommerz.com"),
    //'apiDomain' => 'https://sandbox.sslcommerz.com',

    'apiCredentials' => [
        'store_id' => env("STORE_ID","regalfurniturebdlive"),
        'store_password' => env("STORE_PASSWORD","regalfurniturebdlive60822"),
        //'store_id' => 'test_regalfurniturebd001',
        //'store_password' => 'test_regalfurniturebd001@ssl'
    ],
    'apiUrl' => [
        'make_payment' => "/gwprocess/v4/api.php",
        'transaction_status' => "/validator/api/merchantTransIDvalidationAPI.php",
        'order_validate' => "/validator/api/validationserverAPI.php",
        'refund_payment' => "/validator/api/merchantTransIDvalidationAPI.php",
        'refund_status' => "/validator/api/merchantTransIDvalidationAPI.php",
    ],
//    'connect_from_localhost' => env("IS_LOCALHOST", false), // For Sandbox, use "true", For Live, use "false"
    'connect_from_localhost' => true, // For Sandbox, use "true", For Live, use "false"
    'success_url' => '/sslcommerz/success',
    'failed_url' => '/sslcommerz/fail',
    'cancel_url' => '/sslcommerz/cancel',
    'ipn_url' => '/sslcommerz/ipn',
];
