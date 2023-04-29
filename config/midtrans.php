<?php

return [
    'production_server_key' => env('MIDTRANS_PRODUCTION_SERVER_KEY'),
    'production_client_key' => env('MIDTRANS_PRODUCTION_CLIENT_KEY'),
    'sandbox_server_key' => env('MIDTRANS_SANDBOX_SERVER_KEY'),
    'sandbox_client_key' => env('MIDTRANS_SANDBOX_CLIENT_KEY'),
    'is_production' => env('MIDTRANS_ENVIRONMENT', 'sandbox') === 'production',
    'is_3ds' => env('MIDTRANS_3DS', false),
    'append_notif_url' => env('MIDTRANS_APPEND_NOTIF_URL'),
    'override_notif_url' => env('MIDTRANS_OVERRIDE_NOTIF_URL'),
    'payment_idempotency_key' => env('MIDTRANS_PAYMENT_IDEMPOTENCY_KEY'),
    'is_sanitized' => env('MIDTRANS_SANITIZED', false),
    'curl_options' => []
];
