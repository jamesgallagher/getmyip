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

// Basic function to check if it is a browser
function isRequestFromBrowser() {
    $userAgent = $_SERVER['HTTP_USER_AGENT'];

    // Check if the User-Agent header contains typical browser keywords
    $browserKeywords = array('Mozilla', 'AppleWebKit', 'Chrome', 'Safari', 'Firefox', 'Opera');
    foreach ($browserKeywords as $keyword) {
        if (strpos($userAgent, $keyword) !== false) {
            return true;
        }
    }

    return false;
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

// Parse the JSON response
$data = json_decode($response, true);

//If request is not from a browser, assume bash and just print IP. 
if (!isRequestFromBrowser()) {
    echo $ip;
    exit;
}

// HTML output
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IP Information</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>IP Information</h1>
        <p><strong>Your IP Address:</strong> <?php echo $ip; ?></p>
        <h2>Location Information</h2>
        <p><strong>Country:</strong> <?php echo $data['country']; ?></p>
        <p><strong>City:</strong> <?php echo $data['city']; ?></p>
        <p><strong>ISP:</strong> <?php echo $data['isp']; ?></p>
    </div>
</body>
</html>