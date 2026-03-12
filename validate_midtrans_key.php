<?php
// validate_midtrans_key.php

require __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

// 1. Load .env
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$serverKey = $_ENV['MIDTRANS_SERVER_KEY'] ?? '';
$isProduction = ($_ENV['MIDTRANS_IS_PRODUCTION'] ?? 'false') === 'true';

echo "\n--- DIAGNOSA KUNCI MIDTRANS ---\n";
echo "Server Key di .env: " . $serverKey . "\n";

// CHECK 1: Format Key
if (empty($serverKey)) {
    die("\n[FATAL] Server Key KOSONG. Silakan isi di file .env.\n");
}

if (strpos($serverKey, 'SB-') === 0) {
    echo "[INFO] Format Key: SANDBOX (Benar untuk testing)\n";
    $apiUrl = 'https://api.sandbox.midtrans.com/v1/token';
} elseif (strpos($serverKey, 'Mid-server-') === 0) {
    echo "[WARNING] Format Key: PRODUCTION (Salah untuk testing localhost!)\n";
    echo "          Key Production biasanya diblokir jika dipanggil dari localhost.\n";
    $apiUrl = 'https://api.midtrans.com/v1/token';
} else {
    echo "[WARNING] Format Key: TIDAK DIKENALI.\n";
    $apiUrl = 'https://api.sandbox.midtrans.com/v1/token'; // Asumsi sandbox
}

// CHECK 2: Test Koneksi Langsung (cURL)
echo "\n--- TEST KONEKSI KE MIDTRANS ---\n";
echo "Mencoba menghubungi API: $apiUrl ...\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.sandbox.midtrans.com/v2/token"); // Dummy endpoint check
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_USERPWD, $serverKey . ':');
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Accept: application/json'));
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    'payment_type' => 'bank_transfer',
    'bank_transfer' => ['bank' => 'bca'],
    'transaction_details' => ['order_id' => 'TEST-CONN-'.time(), 'gross_amount' => 1000]
]));
// FIX SSL
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

$result = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

if ($httpCode == 200 || $httpCode == 201) {
    echo "\n[SUKSES] Koneksi Berhasil! Key Anda Valid.\n";
    echo "Response: " . substr($result, 0, 100) . "...\n";
} elseif ($httpCode == 401) {
    echo "\n[GAGAL] Error 401: Access Denied.\n";
    echo "PENYEBAB: Server Key SALAH atau TIDAK COCOK dengan environment.\n";
    echo "Solusi: \n";
    echo "1. Buka dashboard.midtrans.com\n";
    echo "2. Pastikan switch di kiri atas ada di 'SANDBOX'\n";
    echo "3. Copy Server Key yang berawalan 'SB-Mid-server... '\n";
    echo "4. Paste ke file .env\n";
} else {
    echo "\n[GAGAL] HTTP Code: $httpCode\n";
    echo "Response: $result\n";
    echo "Error: $error\n";
}

