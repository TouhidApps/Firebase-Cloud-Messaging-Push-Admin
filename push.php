<?php

// read client_id and client_secret from json file
$clientFile = getcwd() . '/jsonKeys/google_api_client.json';
$serviceAccount = json_decode(file_get_contents($clientFile), true);


$projectName 	= $serviceAccount['project_id']; // $_POST['projectName']; // fir-tutorial-7da84
$apiToken 		= $_POST['apiToken'];
$deviceToken 	= $_POST['deviceToken']; // Single device token or topic
$pushType 		= $_POST['pushType']; // withImage, transaction (When need different UI or action)
$pushTitle 		= $_POST['pushTitle'];
$pushBody 		= $_POST['pushBody'];
$pushImage 		= $_POST['pushImage'];

// Payload data you want to send to Android device(s)
$payloadData = array('message' => array(
    "token" => $deviceToken,
    "notification" => array( // title, body params are fixed
        "title" => $pushTitle,
        "body" => $pushBody,
        "image" => $pushImage
    ),
    "data" => array( // Custom values
        "pushType" => $pushType,
        "pushTitle" => $pushTitle,
        "pushBody" => $pushBody,
        "pushImage" => $pushImage
    )
));

$payload = json_encode($payloadData);

$payloadSize = strlen($payload);

if ($payloadSize > 4096) {
	$msg = '{"errorMessage" : "Payload (Push Data) size is over the 4KB limit! Please reduce text from title or body or both"}';
    echo $msg;

    die();
}

// Set POST variables
$url = 'https://fcm.googleapis.com/v1/projects/' . $projectName . '/messages:send';

$headers = array(
    'Authorization: Bearer ' . $apiToken,
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