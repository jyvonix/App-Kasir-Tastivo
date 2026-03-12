<?php

$serverKey = env('MIDTRANS_SERVER_KEY', '');
$clientKey = env('MIDTRANS_CLIENT_KEY', '');
$isProduction = env('MIDTRANS_IS_PRODUCTION', false);

// ============================================================================
// AUTO-DETECT ENVIRONMENT (Smart Config)
// ============================================================================
// Jika key diawali "SB-", paksa mode Sandbox untuk menghindari error 401
// ketika user lupa mengganti IS_PRODUCTION=false di .env
if (strpos($serverKey, 'SB-') === 0) {
    $isProduction = false;
}

return [
    /*
    |--------------------------------------------------------------------------
    | Midtrans Server Key
    |--------------------------------------------------------------------------
    */
    'server_key' => $serverKey,

    /*
    |--------------------------------------------------------------------------
    | Midtrans Client Key
    |--------------------------------------------------------------------------
    */
    'client_key' => $clientKey,

    /*
    |--------------------------------------------------------------------------
    | Midtrans Environment (Production / Sandbox)
    |--------------------------------------------------------------------------
    */
    'is_production' => $isProduction,

    /*
    |--------------------------------------------------------------------------
    | Midtrans Sanitization
    |--------------------------------------------------------------------------
    */
    'is_sanitized' => true,

    /*
    |--------------------------------------------------------------------------
    | Midtrans 3D Secure
    |--------------------------------------------------------------------------
    */
    'is_3ds' => true,

    /*
    |--------------------------------------------------------------------------
    | Curl Options (Fix SSL Error on XAMPP/Windows)
    |--------------------------------------------------------------------------
    */
    'curl_options' => [
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => 0,
        10023 => [], // Fix for Undefined array key 10023 (CURLOPT_HTTPHEADER)
    ],
];
