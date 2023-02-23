<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

$dbconn = paliolite();
$merchant_name = $_POST['merchant_name'];
// $merchant_name = "Bogajaya";

// get item code
$query = $dbconn->prepare("SELECT * FROM SHOP WHERE NAME='$merchant_name'");
$query->execute();
$merchant = $query->get_result()->fetch_assoc();
$merchant_code = $merchant['CODE'];
$query->close();

$query = $dbconn->prepare("SELECT * FROM SHOP_SHIPPING_ADDRESS WHERE STORE_CODE='$merchant_code'");
$query->execute();
$merchant_address = $query->get_result()->fetch_assoc();
$query->close();

echo json_encode($merchant_address);