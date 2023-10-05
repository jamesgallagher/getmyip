<?php  
/**
 * Gets IP address.
 */
function getIPAddress() {
    // Specify the headers to check
    $headersToCheck = [
        'HTTP_CLIENT_IP',
        'HTTP_X_FORWARDED_FOR',
        'HTTP_X_FORWARDED',
        'HTTP_X_CLUSTER_CLIENT_IP',
        'HTTP_FORWARDED_FOR',
        'HTTP_FORWARDED',
        'REMOTE_ADDR'
    ];

    foreach ($headersToCheck as $header) {
        if (!empty($_SERVER[$header])) {
            // If multiple IP addresses are present, extract the first one
            $ipAddresses = explode(',', $_SERVER[$header]);
            $ipAddress = trim($ipAddresses[0]);

            // Validate the IP address
            if (filter_var($ipAddress, FILTER_VALIDATE_IP)) {
                return $ipAddress;
            }
        }
    }

    // Return a default IP address if none of the headers contain a valid IP address
    return 'unknown';
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
print $ip . "\n\n";
print $response;
echo "\n";
