<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

$fpin = $_POST['fpin'];
$dbconn = paliolite();

if($_POST['address'] != ''){
    $address = $_POST['address'];

    try {
        $query = $dbconn->prepare("UPDATE USER_LIST_EXTENDED SET ADDRESS = '$address' WHERE F_PIN = '$fpin'");
        $query->execute();
        $query->close();
    } catch (\Throwable $th) {
        //throw $th;
        echo $th->getMessage();
    }

    echo 'success';

} else {

    try {

        // get store products
        $query = $dbconn->prepare("SELECT ule.ADDRESS, ul.FIRST_NAME, ul.LAST_NAME FROM USER_LIST_EXTENDED ule LEFT JOIN USER_LIST ul ON ule.F_PIN = ul.F_PIN  WHERE ul.F_PIN = '$fpin'");
        $query->execute();
        $user  = $query->get_result()->fetch_assoc();
        $query->close();

        echo utf8_encode(json_encode($user));
    } catch (\Throwable $th) {
        //throw $th;
        echo $th->getMessage();
    }
    
}

