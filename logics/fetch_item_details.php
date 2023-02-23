<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

$dbconn = paliolite();
$item_name = $_POST['item_name'];
// $item_name = "Bakpia Aneka Rasa Isi 20";

// get item code
$query = $dbconn->prepare("SELECT * FROM PRODUCT WHERE NAME='$item_name'");
$query->execute();
$item = $query->get_result()->fetch_assoc();
$item_code = $item['CODE'];
$query->close();

$query = $dbconn->prepare("SELECT * FROM PRODUCT_SHIPMENT_DETAIL WHERE PRODUCT_CODE=$item_code");
$query->execute();
$item_detail = $query->get_result()->fetch_assoc();
$query->close();

echo json_encode($item_detail);