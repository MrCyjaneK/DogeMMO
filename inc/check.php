<?php
//session_name('st34lm3');
//session_start();    
$apikey = $config->apikey;
$ver = $_SESSION['version'];
if (!isset($_SESSION['version'])) {
    $e = "Session not found";
}
if ($ver == 1) {
    $u = $_SESSION['user'];
    $check_hash = $u['hash'];
    unset($u['hash']);
    $data_check_arr = [];
    if (!isset($u)) {
        $e = "Session gone!";
        include 'inc/error.php';
        die();
    }
    foreach ($u as $key => $value) {
        $data_check_arr[] = $key . '=' . $value;
    }
    sort($data_check_arr);
    $data_check_string = implode("\n", $data_check_arr);
    $secret_key = hash('sha256', $apikey, true);
    $hash = hash_hmac('sha256', $data_check_string, $secret_key);
    //print_r($hash);
    //print_r("<br />".$check_hash);
    if (strcmp($hash, $check_hash) !== 0) {
        $e = '2. Invalid data provided, please try again. In case of persisting problem contact support in @DogeMMOSociety';
        $e .= print_r($_SESSION,1);
    }
    if ((time() - $u['auth_date']) > time()+60*60*1) {
        $e = 'Session expired! Please login again';
    }
    $session['user']['hash'] = $check_hash;
}
if ($ver == 2) {
    //Ok, it must be ok... please.
    //TODO: add verification.
}
if (isset($e)) {
    include 'inc/error.php';
    die();
}