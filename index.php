<?php
require __DIR__ . '/vendor/autoload.php';

// $APIKey = "";
// function httpGet($url)
// {
//     $ch = curl_init();  
 
//     curl_setopt($ch,CURLOPT_URL,$url);
//     curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
 
//     $output=curl_exec($ch);
 
//     curl_close($ch);
//     return $output;
// }
 
// $rs = httpGet("https://www.googleapis.com/drive/v3/files/13wb9AIg4P4rD8VtA1rHt9Ga08h8S1Iyi?key=".$APIKey);
// echo $rs;


if (php_sapi_name() != 'cli') {
    throw new Exception('This application must be run on the command line.');
}

/**
 * Returns an authorized API client.
 * @return Google_Client the authorized client object
 */
function getClient()
{
    $client = new Google_Client();
    $client->setApplicationName('Google Drive API PHP Quickstart');
    $client->setScopes(Google_Service_Drive::DRIVE_METADATA_READONLY);
    $client->setAuthConfig('credentials.json');
    $client->setAccessType('offline');
    $client->setPrompt('select_account consent');

    // Load previously authorized token from a file, if it exists.
    // The file token.json stores the user's access and refresh tokens, and is
    // created automatically when the authorization flow completes for the first
    // time.
    $tokenPath = 'token.json';
    if (file_exists($tokenPath)) {
        $accessToken = json_decode(file_get_contents($tokenPath), true);
        $client->setAccessToken($accessToken);
    }

    // If there is no previous token or it's expired.
    if ($client->isAccessTokenExpired()) {
        // Refresh the token if possible, else fetch a new one.
        if ($client->getRefreshToken()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
        } else {
            // Request authorization from the user.
            $authUrl = $client->createAuthUrl();
            printf("Open the following link in your browser:\n%s\n", $authUrl);
            print 'Enter verification code: ';
            $authCode = trim(fgets(STDIN));

            // Exchange authorization code for an access token.
            $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
            $client->setAccessToken($accessToken);

            // Check to see if there was an error.
            if (array_key_exists('error', $accessToken)) {
                throw new Exception(join(', ', $accessToken));
            }
        }
        // Save the token to a file.
        if (!file_exists(dirname($tokenPath))) {
            mkdir(dirname($tokenPath), 0700, true);
        }
        file_put_contents($tokenPath, json_encode($client->getAccessToken()));
    }
    return $client;
}


// Get the API client and construct the service object.
$client = getClient();

// Access Google File API and get file
$GAPIS = 'https://www.googleapis.com/drive/v3/files/';

function getImage($access_token) {
    global $GAPIS;

    $ch1 = curl_init();

    // Ket noi URL 
    curl_setopt($ch1, CURLOPT_URL, $GAPIS . '13wb9AIg4P4rD8VtA1rHt9Ga08h8S1Iyi');

    // cai dat header: accept type & token
    curl_setopt($ch1, CURLOPT_HTTPHEADER, Array('Accept: application/json','Authorization: Bearer '.$access_token));
    // goi de? cho phep function return gia tri
    curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
    
    //Thuc thi va gan vao ket qua
	$result = curl_exec($ch1);

    // dong $ch1
    curl_close($ch1);

    return $result;
}
$accessToken = $client -> getAccessToken();

foreach ($accessToken as $file) {
    printf("%s \n",$file);
}
//Chay xong vong lap ta thay:
//dong` 1: la` accessToken dc tao ra
//dong` 2: la` timeout
//dong` 3: refresh token
//dong` 4: scope
//dong` 5: kieu? token (authorization)
//dong` 6: created date

echo getImage(reset($accessToken));
    
