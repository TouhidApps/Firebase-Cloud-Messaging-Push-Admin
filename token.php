<?php

// use php curl to fetch initial access token & refresh token for use with google api.
// adapted from John Slegers suggestion on:
// http://stackoverflow.com/questions/8902376/php-how-to-get-the-access-token-authenticating-with-oauth-2-0-for-google-api

// requires your project 'client ID' & 'client secret' from https://console.developers.google.com/apis/library
// for some inane reason, google puts this in a dimensional json array

// requires human interaction to authenticate user account.  run this once.
// refresh-ga-token.php can then be run automatically whenever needed.
// refresh token will be saved for future use as it does not expire.



// please ensure the 'jsonKeys' folder exists in your home folder or somewhere secure.
// $folder = posix_getpwuid( posix_getuid())['dir']. '/jsonKeys/';




// read client_id and client_secret from json file
$clientFile = getcwd() . '/jsonKeys/google_api_client.json';
$serviceAccount = json_decode(file_get_contents($clientFile), true);

$tokenUri = $serviceAccount['token_uri'];
$scope = 'https://www.googleapis.com/auth/firebase.messaging';

$assertionPayload = [
    "iss" => $serviceAccount['client_email'],
    "scope" => $scope,
    "aud" => $tokenUri,
    "exp" => time() + 3600,  // Maximum expiration time is one hour
    "iat" => time()
];

function base64url_encode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

// Create the assertion JWT
$header = base64url_encode(json_encode(["alg" => "RS256", "typ" => "JWT"]));
$payload = base64url_encode(json_encode($assertionPayload));
$signature = '';
openssl_sign($header . '.' . $payload, $signature, $serviceAccount['private_key'], 'sha256');
$jwt = $header . '.' . $payload . '.' . base64url_encode($signature);

// Check open ssl if necessary for development
// $privateKeyContent = $serviceAccount['private_key']; // Paste your private key string here directly
// $private_key = openssl_pkey_get_private($privateKeyContent);
// if (!$private_key) {
//     echo('Loading private key failed: ' . openssl_error_string());
// } else {
//     echo 'Private key is loaded successfully';
// }

// Prepare the post fields
$postFields = [
    'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
    'assertion' => $jwt
];

// Initialize cURL session
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $tokenUri);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postFields));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true); // Keep this true in production

// Execute POST request and parse response
$response = curl_exec($ch);
if (curl_errno($ch)) {
    throw new Exception('cURL Error: ' . curl_error($ch));
}
curl_close($ch);

// Decode the response
$responseData = json_decode($response, true);
if (isset($responseData['access_token'])) {
    $accessToken = $responseData['access_token'];
    echo $accessToken;
    // echo "Access Token: " . $accessToken;
    // Use this access token to make API calls to Firebase FCM
} else {
    echo "Failed to get access token. Response was: " . $response;
}





//---------- method 2

// To use this method please check your url should be granted by under this url: https://console.cloud.google.com/apis/credentials/oauthclient (Authorised redirect URIs) section

// $YOUR_CLIENT_ID = "";
// $YOUR_CLIENT_SECRET = "";

// $redirUrl = 'http://' . $_SERVER["HTTP_HOST"].'/projects/test/firebasepush/token.php';

// // http://localhost/projects/test/firebasepush/token.php

// if (isset($_GET['code'])) {


//     // try to get an access token
//     $code = $_GET['code'];
//     $url = 'https://oauth2.googleapis.com/token';

//     $params = array(
//         "code" => $code,
//         "client_id" => $YOUR_CLIENT_ID,
//         "client_secret" => $YOUR_CLIENT_SECRET,
//         "redirect_uri" => $redirUrl,
//         "grant_type" => "authorization_code"
//     );

// 	$ch = curl_init();
// 	curl_setopt($ch, CURLOPT_URL, $url);
// 	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// 	curl_setopt($ch, CURLOPT_POST, true);
// 	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
// 	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

//     $output = curl_exec($ch);
//     $info = curl_getinfo($ch);
//     curl_close($ch);
//     if ($info['http_code'] === 200) {
//         header('Content-Type: ' . $info['content_type']);

// 	    // Decode the response
// 		$responseData = json_decode($output, true);
// 		$accessToken = $responseData['access_token'] ?? null;

// 		echo "Access Token: " . $accessToken;
//     } else {
//         echo 'An error happened: ' . curl_error($ch);
//     }

// } else {

//     $url = "https://accounts.google.com/o/oauth2/auth";

//     $params = array(
//         "response_type" => "code",
//         "client_id" => $YOUR_CLIENT_ID,
//         "redirect_uri" => $redirUrl,
//         "scope" => "https://www.googleapis.com/auth/firebase.messaging"
//     );

//     $request_to = $url . '?' . http_build_query($params);

//     header("Location: " . $request_to);
// }





?>