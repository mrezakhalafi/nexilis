<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

$dbconn = paliolite();

$f_pin = $_GET['f_pin'];
$store_code = $_GET['store_code'];
// SELECT USER PROFILE
if(!isset($f_pin) && isset($_GET['f_pin'])){
    $f_pin = $_GET['f_pin'];
}
$rows = array();
if (isset($f_pin)) {
    $query = $dbconn->prepare("SELECT srp.* FROM SHOP_REWARD_POINT srp WHERE srp.STORE_CODE = ? AND F_PIN = ?");
    $query->bind_param("ss", $store_code, $f_pin);
    // SELECT USER PROFILE
    $query->execute();
    $groups  = $query->get_result()->fetch_assoc();
    $query->close();
    
    // while ($group = $groups->fetch_assoc()) {
    //     $rows[] = $group;
    // };
};
// return $rows;
echo json_encode($groups);
?>