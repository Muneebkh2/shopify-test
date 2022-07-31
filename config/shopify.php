<?php

return [
    'store_url' => env('SHOPIFY_APP_HOST_NAME'),
    'api'       => [
        'access_token' => env('SHOPIFY_API_ACCESS_TOKEN'),
        'key'     => env('SHOPIFY_API_KEY'),
        'token'   => env('SHOPIFY_API_SECRET')
    ],
    'app'       => [
        'key'     => env('SHOPIFY_API_KEY'),
        'token'   => env('SHOPIFY_API_SECRET')
    ],
];