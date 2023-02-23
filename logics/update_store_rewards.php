<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

$dbconn = paliolite();

$f_pin = $_POST['f_pin'];
$reward_json = base64_decode($_POST["reward_list"]);
$reward_list = json_decode($reward_json, true);

// SELECT USER PROFILE
if(!isset($f_pin) && isset($_POST['f_pin'])){
    $f_pin = $_POST['f_pin'];
}
$rows = array();
if (isset($f_pin)) {
    foreach ($reward_list as $reward) {
        $store_code = $reward["STORE_CODE"];
        $amount = $reward["AMOUNT"];
        $query = $dbconn->prepare("REPLACE INTO SHOP_REWARD_POINT (F_PIN, STORE_CODE, AMOUNT) VALUES ('" . $f_pin . "', '" . $store_code . "', " . $amount .");");
        $query->execute();
        $query->close();
    }
    
};


// return $rows;
?>