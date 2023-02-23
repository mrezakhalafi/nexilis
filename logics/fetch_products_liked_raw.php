<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
session_start();
$dbconn = paliolite();
// SELECT USER PROFILE
if (isset($_SESSION["f_pin"])) {
    $f_pin = $_SESSION["f_pin"];
} else {
    $f_pin = $_GET['f_pin'];

    $queryCheckFpin = "SELECT F_PIN FROM USER_LIST WHERE F_PIN = '$f_pin' OR IMEI = '$f_pin'";
    $query = $dbconn->prepare($queryCheckFpin);
    $query->execute();
    $getNewFPIN = $query->get_result()->fetch_assoc();
    $query->close();

    $f_pin = $getNewFPIN["F_PIN"];
    $_SESSION["f_pin"] = $f_pin;
}
$rows = array();
if (isset($f_pin)) {
    $sql = "
    (SELECT p.POST_ID AS PRODUCT_CODE FROM POST_REACTION p WHERE p.F_PIN = '$f_pin' AND p.FLAG = 1)
    ";
    $query = $dbconn->prepare($sql);
    // $query = $dbconn->prepare("SELECT PRODUCT_CODE FROM PRODUCT_REACTION WHERE F_PIN = ? AND FLAG = 1");
    // $query->bind_param("s", $f_pin);
    // SELECT USER PROFILE
    $query->execute();
    $groups  = $query->get_result();
    $query->close();
    
    while ($group = $groups->fetch_assoc()) {
        $rows[] = $group;
    };
};
return $rows;
?>