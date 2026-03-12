<?php
// test_midtrans_connection.php
require __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

// 1. Load .env manually
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$serverKey = $_ENV['MIDTRANS_SERVER_KEY'] ?? '';
$isProduction = ($_ENV['MIDTRANS_IS_PRODUCTION'] ?? 'false') === 'true';

echo "Testing Midtrans Connection...\n";
echo "Server Key: " . substr($serverKey, 0, 5) . "...\n";
echo "Environment: " . ($isProduction ? 'PRODUCTION' : 'SANDBOX') . "\n";

// 2. Setup Midtrans
\Midtrans\Config::$serverKey = $serverKey;
\Midtrans\Config::$isProduction = $isProduction;
\Midtrans\Config::$isSanitized = true;
\Midtrans\Config::$is3ds = true;

// FIX SSL
\Midtrans\Config::$curlOptions = [
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => 0,
    10023 => [] // CURLOPT_HTTPHEADER fix
];

// 3. Create Transaction
$params = [
    'transaction_details' => [
        'order_id' => 'TEST-' . time(),
        'gross_amount' => 10000,
    ],
    'customer_details' => [
        'first_name' => 'Test',
        'email' => 'test@example.com',
    ],
];

try {
    $snapToken = \Midtrans\Snap::getSnapToken($params);
    echo "\n[SUCCESS] Connection Successful!\n";
    echo "Snap Token Generated: " . $snapToken . "\n";
} catch (\Exception $e) {
    echo "\n[ERROR] Connection Failed!\n";
    echo "Message: " . $e->getMessage() . "\n";
    
    if (strpos($e->getMessage(), '401') !== false) {
        echo "\nDiagnosis: 401 Unauthorized.\n";
        echo "1. Your Server Key is invalid.\n";
        echo "2. Or your account is not active.\n";
        echo "3. Or you are using a Production Key in Sandbox mode (or vice versa).\n";
    }
}

