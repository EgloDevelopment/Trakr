<?php
$start_time = microtime(true);
session_start();
function getString($length = 12)
{
    return substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / strlen($x)))), 1, $length);
}

function clear($inp)
{
    if (is_array($inp))
        return array_map(__METHOD__, $inp);

    if (!empty($inp) && is_string($inp)) {
        return str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $inp);
    }

    return $inp;
}

function isMobile()
{
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}


function getIP()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}
function getLoc()
{

    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];
    $country  = "Unknown";

    if (filter_var($client, FILTER_VALIDATE_IP)) {
        $ip = $client;
    } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
        $ip = $forward;
    } else {
        $ip = $remote;
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://www.geoplugin.net/json.gp?ip=" . $ip);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $ip_data_in = curl_exec($ch); // string
    curl_close($ch);

    $ip_data = json_decode($ip_data_in, true);
    $ip_data = str_replace('&quot;', '"', $ip_data); // for PHP 5.2 see stackoverflow.com/questions/3110487/

    if ($ip_data && $ip_data['geoplugin_countryName'] != null) {
        $country = $ip_data['geoplugin_countryName'];
    }

    return $country;
}

require_once('../config/config.php');

$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$key = clear($_GET['key']);
$auth = clear($_GET['auth']);

$sql = "SELECT * FROM users WHERE apikey = '$key'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $dbkey = $row['apikey'];
        $dbauth = $row['apiauth'];
    }




    if ($dbkey == $key && $dbauth == $auth) {
        if (isMobile()) {
            $device = 'Phone';
        } else {
            $device = 'Desktop';
        }
        $ip = getIP();
        $country = getLoc();
        $cpu = cpu();
        $memUsage = getServerMemoryUsage(false);
        $ram = getNiceFileSize($memUsage["total"] - $memUsage["free"]);
        $time = (number_format(microtime(true) - $start_time, 2));
        $sql = "INSERT INTO `data`(`ip`, `country`, `device`, `loadtime`, `servercpu`, `serverram`, `apiauth`) VALUES ('$ip','$country','$device','$time','0','0','$auth')";
        $conn->query($sql);
        //$conn->query($sql3);
    } else {
        header('HTTP/1.1 401 Unauthorized');
        http_response_code(401);
        print(http_response_code());
    }
} else {
    header('HTTP/1.1 401 Unauthorized');
    http_response_code(401);
    print(http_response_code());
}
