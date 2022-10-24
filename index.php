<?php  
    function getIPAddress() 
    {  
        if(!empty($_SERVER['HTTP_CLIENT_IP'])) {  
            $ip = $_SERVER['HTTP_CLIENT_IP'];  
        }  
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {  
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];  
        }  
    else{  
        $ip = $_SERVER['REMOTE_ADDR'];  
    }  
    
    return $ip;  
}  

$ip = getIPAddress();  

// Switch API provider to provider that allows 42 reqs per minute 
$url = 'http://ip-api.com/json/' . trim($ip);

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($curl);
curl_close($curl);
header('Content-Type: application/json; charset=utf-8');
print $response;
echo "\n";
