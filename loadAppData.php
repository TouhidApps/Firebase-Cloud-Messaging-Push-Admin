<?php



// Set POST variables
$url = 'https://touhidapps.com/api/demo/jsondemoapi.php?option=2';

$headers = array(
    'Content-Type: application/json'
);

// Initialize curl handle
$ch = curl_init();

// Set URL to Firebase API
curl_setopt($ch, CURLOPT_URL, $url);

// Set request method to POST
curl_setopt($ch, CURLOPT_POST, true);

// Set our custom headers
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

// Get return value
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Set timeout
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

// Set JSON post data
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

// Actually send the push
$result = curl_exec($ch);

// Error handling
if (curl_errno($ch)) {
    echo 'GCM error: ' . curl_error($ch);
}

// Close curl handle
curl_close($ch);

// Debug API response
echo $result;





?>