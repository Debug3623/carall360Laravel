<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Laravel CORS
    |--------------------------------------------------------------------------
    |
    | allowedOrigins, allowedHeaders and allowedMethods can be set to array('*')
    | to accept any value.
    |
    */
   
    'supportsCredentials' => false,
//    'allowedOrigins' => ['https://www.admin.hospitallcare.com/login', 'http://localhost:3000'],
    'allowedOrigins' => ['*'],
    'allowedOriginsPatterns' => [],
    'allowedHeaders' => ['*'],
    // 'allowedMethods' =>  ['GET', 'POST', 'PUT',  'DELETE'], //['*'],
    'allowedMethods' =>  ['*'],
    'exposedHeaders' => [],
    'maxAge' => 0,

];
