<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

$fpin = $_POST['fpin'];
$dbconn = paliolite();

// get wishlist
if($query = $dbconn->prepare("SELECT PRODUCT_CODE, IS_POST FROM WISHLIST_PRODUCT WHERE FPIN = ?")){
    $query->bind_param('s', $fpin);
    $query->execute();
    $wishlist = $query->get_result();
    $query->close();
} else {
    //error !! don't go further
    var_dump($dbconn->error);
}

$rows = array();
while ($wish = $wishlist->fetch_assoc()) {

    if ($wish['IS_POST'] == 0){

        // get product details
        if ($query = $dbconn->prepare("SELECT CODE, NAME, THUMB_ID, PRICE FROM PRODUCT WHERE CODE = ?")) {
            $query->bind_param('s', $wish['PRODUCT_CODE']);
            $query->execute();
            $product = $query->get_result()->fetch_assoc();
            $query->close();

            $rows[] = $product;
        } else {
            //error !! don't go further
            var_dump($dbconn->error);
        }

    }else if ($wish['IS_POST'] == 1){

        if ($query = $dbconn->prepare("SELECT TITLE AS NAME, THUMB_ID, PRICING_MONEY AS PRICE, POST_ID AS CODE FROM POST WHERE POST_ID = ?")) {
            $query->bind_param('s', $wish['PRODUCT_CODE']);
            $query->execute();
            $product = $query->get_result()->fetch_assoc();
            $query->close();

            $rows[] = $product;
        } else {
            //error !! don't go further
            var_dump($dbconn->error);
        }

    }
};

echo json_encode($rows);
