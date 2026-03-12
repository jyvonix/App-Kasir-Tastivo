<?php
// fix_midtrans_env.php
// Script to safely fix Midtrans configuration in .env

$envPath = __DIR__ . '/.env';

if (!file_exists($envPath)) {
    die("Error: .env file not found at $envPath\n");
}

$lines = file($envPath, FILE_IGNORE_NEW_LINES);
$newLines = [];
$serverKey = '';
$clientKey = '';
$isProduction = 'false'; // Default to sandbox

echo "Analyzing .env configuration...\n";

foreach ($lines as $line) {
    // Skip empty or comments
    if (trim($line) === '' || strpos(trim($line), '#') === 0) {
        $newLines[] = $line;
        continue;
    }

    // Parse Key-Value
    $parts = explode('=', $line, 2);
    if (count($parts) !== 2) {
        $newLines[] = $line;
        continue;
    }

    $key = trim($parts[0]);
    $value = trim($parts[1], " 	\n\r\0\x0B\"'"); // Remove quotes and spaces

    if ($key === 'MIDTRANS_SERVER_KEY') {
        $serverKey = $value;
        echo "[FOUND] Server Key: " . substr($serverKey, 0, 5) . "...\n";
        $newLines[] = "MIDTRANS_SERVER_KEY=" . $serverKey; // Re-write clean
    } elseif ($key === 'MIDTRANS_CLIENT_KEY') {
        $clientKey = $value;
        echo "[FOUND] Client Key: " . substr($clientKey, 0, 5) . "...\n";
        $newLines[] = "MIDTRANS_CLIENT_KEY=" . $clientKey; // Re-write clean
    } elseif ($key === 'MIDTRANS_IS_PRODUCTION') {
        // Skip adding it now, we will add the correct one at the end
        continue;
    } else {
        $newLines[] = $line;
    }
}

// Logic: Check Prefix
if (strpos($serverKey, 'SB-') === 0) {
    $isProduction = 'false';
    echo "[INFO] Detected SANDBOX key (starts with SB-).\n";
} elseif (!empty($serverKey)) {
    $isProduction = 'true';
    echo "[INFO] Detected PRODUCTION key (does not start with SB-).\n";
} else {
    echo "[WARN] Server Key is EMPTY!\n";
}

// Add IS_PRODUCTION
$newLines[] = "MIDTRANS_IS_PRODUCTION=" . $isProduction;

// Backup
copy($envPath, $envPath . '.bak');
echo "[INFO] Backup created at .env.bak\n";

// Write back
file_put_contents($envPath, implode("\n", $newLines));
echo "[SUCCESS] .env updated with clean keys and correct environment mode.\n";

