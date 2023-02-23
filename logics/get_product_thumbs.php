<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
session_start();

$id = $_POST['product_id'];
$is_prod = $_POST['is_product'];
$dbconn = paliolite();

if (isset($_SESSION["f_pin"])) {
    $f_pin = $_SESSION["f_pin"];
} else {
    $f_pin = $_POST['f_pin'];

    $queryCheckFpin = "SELECT F_PIN FROM USER_LIST WHERE F_PIN = '$f_pin' OR IMEI = '$f_pin'";
    $query = $dbconn->prepare($queryCheckFpin);
    $query->execute();
    $getNewFPIN = $query->get_result()->fetch_assoc();
    $query->close();

    $f_pin = $getNewFPIN["F_PIN"];
    $_SESSION["f_pin"] = $f_pin;
}

// get store products
if ($is_prod == 1) {
    $str = "
    SELECT
    p.CODE,
    p.SHOP_CODE, 
    p.THUMB_ID, 
    p.NAME, 
    p.DESCRIPTION,
    (
        SELECT COUNT(fl.F_PIN) 
        FROM FOLLOW_LIST fl
        WHERE fl.L_PIN = p.SHOP_CODE
        AND fl.F_PIN = '$f_pin'
    ) AS IS_FOLLOW,
    (
        SELECT COUNT(fl.L_PIN)
        FROM FOLLOW_LIST fl
        WHERE fl.L_PIN = p.SHOP_CODE
    ) AS TOTAL_FOLLOW,
    (
        SELECT COUNT(pr.F_PIN) 
        FROM PRODUCT_REACTION pr
        WHERE pr.PRODUCT_CODE = p.CODE
        AND pr.F_PIN = '$f_pin'
        AND pr.FLAG = 1
    ) AS IS_LIKED,
    (
        SELECT COUNT(pr.PRODUCT_CODE) 
        FROM PRODUCT_REACTION pr
        WHERE pr.PRODUCT_CODE = p.CODE
        AND pr.FLAG = 1
    ) AS TOTAL_LIKES
    FROM PRODUCT p
    WHERE p.CODE = '$id'";
} else {
    $str = "SELECT p.POST_ID AS CODE,p.FILE_ID AS THUMB_ID, p.THUMB_ID AS THUMBNAIL, p.FILE_TYPE, p.LINK, p.TITLE AS NAME, p.DESCRIPTION FROM POST p WHERE p.POST_ID = '$id'";
}

$query = $dbconn->prepare($str);
$query->execute();
$product = $query->get_result()->fetch_assoc();
$query->close();

function utf8ize($d) {
    // if (is_array($d)) {
    //     foreach ($d as $k => $v) {
    //         $d[$k] = utf8ize($v);
    //     }
    // } else if (is_string ($d)) {
    //     return utf8_encode($d);
    // }
    return $d;
}

echo json_encode(utf8ize($product));