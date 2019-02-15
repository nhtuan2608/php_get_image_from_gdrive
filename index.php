<?php
require __DIR__ . '/vendor/autoload.php';

function httpGet($url)
{
    $ch = curl_init();  
 
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
 
    $output=curl_exec($ch);
 
    curl_close($ch);
    return $output;
}
 
$rs = httpGet("https://www.googleapis.com/drive/v3/files/13wb9AIg4P4rD8VtA1rHt9Ga08h8S1Iyi?key=AIzaSyAU-pR0BfI3l4AzvXfC5wl5bMSGnwonLFo");
echo $rs;