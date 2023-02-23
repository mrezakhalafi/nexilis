<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

// get store follow status

if (isset($_POST['f_pin']) && isset($_POST['store_code'])) {
    $f_pin = $_POST['f_pin'];
    $store_code = $_POST['store_code'];
} else if (isset($_GET['f_pin']) && isset($_GET['store_code'])) {
    $f_pin = $_GET['f_pin'];
    $store_code = $_GET['store_code'];
}

$dbconn = paliolite();

try {

    $query_one = $dbconn->prepare("SELECT COUNT(*) as cnt FROM SHOP_FOLLOW WHERE F_PIN = ? AND STORE_CODE = ?");
    $query_one->bind_param("ss", $f_pin, $store_code);
    $query_one->execute();
    $is_follow = $query_one->get_result()->fetch_assoc();
    $query_one->close();

    echo $is_follow['cnt'];
    
} catch (\Throwable $th) {
    //throw $th;
    echo $th->getMessage();
}