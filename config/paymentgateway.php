<?php

return [
 /*
    |--------------------------------------------------------------------------
    | Payment Gateway Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "pymentgateway" your application is currently
    | running in. This may determine how you prefer to configure various
    | payment services like jazzcash, easypaisa the application utilizes. Set this in your ".env" file.
    |
    */
     'JC_POST_URL' => env('JC_POST_URL', ''),
     'JC_POST_URL_V2' => env('JC_POST_URL_V2', ''),
     'JC_POST_URL_CARD' => env('JC_POST_URL_CARD', ''),
     'JC_STATUS_INQUIRY_URL' => env('JC_STATUS_INQUIRY_URL', ''),
     'JC_HASHKEY' => env('JC_HASHKEY', ''),
     'JC_PASSWORD' => env('JC_PASSWORD', ''),
     'JC_MERCHANTID' => env('JC_MERCHANTID', ''),
     'JC_RETURNURL' => env('JC_RETURNURL', ''),
     'JC_FRONTURL' => env('JC_FRONTURL', ''),
     'JC_VERSION' => env('JC_VERSION', '1.1'),
     'JC_LANGUAGE' => env('JC_LANGUAGE', 'EN'),
     'JC_CURRENCY' => env('JC_CURRENCY', 'PKR'),

     'EP_MA_URL' => env('EP_MA_URL', ''),
     'EP_OTC_URL' => env('EP_OTC_URL', ''),
     'EP_STORE_ID' => env('EP_STORE_ID', ''),
     'EP_CREDENTIALS' => env('EP_CREDENTIALS', ''),
     'EP_MA' => env('EP_MA', ''),
     'EP_OTC' => env('EP_OTC', ''),
     'EP_EWP_ACCOUNT' => env('EP_EWP_ACCOUNT', ''),
     'EP_STATUS_INQUIRY_URL' => env('EP_STATUS_INQUIRY_URL', ''),
];

?>