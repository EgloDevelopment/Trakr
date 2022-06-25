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
        $time = (number_format(microtime(true) - $start_time, 2));
        $sql = "INSERT INTO `data`(`ip`, `country`, `device`, `loadtime`, `servercpu`, `serverram`, `apiauth`) VALUES ('0','0','0','$time','0','0','$auth')";
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
