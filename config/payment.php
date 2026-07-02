<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Taiwan Payment Gateway Endpoint
    |--------------------------------------------------------------------------
    |
    | Set this on the server after the provider is chosen and verified.
    | Leave it empty to keep the gateway page in sandbox payload preview mode.
    |
    */
    'gateway_endpoint' => env('PAYMENT_GATEWAY_ENDPOINT'),
    'gateway_provider' => env('PAYMENT_GATEWAY_PROVIDER', 'manual'),
    'gateway_mode' => env('PAYMENT_GATEWAY_MODE', 'sandbox'),
];
