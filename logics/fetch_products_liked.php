<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
session_start();

$dbconn = paliolite();

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
    $query = $dbconn->prepare("SELECT POST_ID AS PRODUCT_CODE FROM POST_REACTION WHERE F_PIN = ? AND FLAG = 1");
    $query->bind_param("s", $f_pin);
    // SELECT USER PROFILE
    $query->execute();
    $groups  = $query->get_result();
    $query->close();
    
    while ($group = $groups->fetch_assoc()) {
        $rows[] = $group;
    };
};
echo json_encode($rows);
?>