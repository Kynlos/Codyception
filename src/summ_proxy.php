<?php
// Proxy for Cloudflare AI API
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
// Set CORS headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Get request body
$requestBody = file_get_contents('php://input');

$bartApiUrl = 'https://api.cloudflare.com/client/v4/accounts/REMOVED/ai/run/@cf/facebook/bart-large-cnn';
$bartApiKey = 'REMOVED';

// Initialize cURL
$ch = curl_init();

// Set cURL options
curl_setopt($ch, CURLOPT_URL, $bartApiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $requestBody);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Authorization: Bearer ' . $bartApiKey
));

// Execute cURL request
$response = curl_exec($ch);

// Check for cURL errors
if ($response === false) {
    $error = curl_error($ch);
    http_response_code(500);
    echo "cURL error: $error";
} else {
    // Return the response from the Cloudflare API
    http_response_code(200);
    echo $response;
}

// Close cURL
curl_close($ch);
