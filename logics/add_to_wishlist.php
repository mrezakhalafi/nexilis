<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

$fpin = $_POST['fpin'];
$code = $_POST['code'];
$operation = $_POST['operation'];
$dbconn = paliolite();

if($operation == 'add'){
    // get store products
    $query = $dbconn->prepare("INSERT INTO WISHLIST_PRODUCT (FPIN, PRODUCT_CODE, IS_POST) VALUES ('$fpin', '$code', 1)");
    $query->execute();
    $query->close();
    
    echo "added successfully";
} else {
    // get store products
    $query = $dbconn->prepare("DELETE FROM WISHLIST_PRODUCT WHERE FPIN = '$fpin' AND PRODUCT_CODE = '$code'");
    $query->execute();
    $query->close();

    echo "deleted successfully";
}
