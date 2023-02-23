<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

session_start();

$id = $_POST['product_id'];
$dbconn = paliolite();

$f_pin = $_POST['f_pin'];
$f_pin = $_SESSION['f_pin'];

// SELECT 
// p.POST_ID as CODE, 
// p.TITLE AS PRODUCT_NAME, 
// p.MERCHANT AS SHOP_CODE, 
// p.PRICING_MONEY AS PRICE, 
// p.THUMB_ID, 
// p.DESCRIPTION, 
// s.ID AS SHOP_ID, 
// s.NAME AS SHOP_NAME, 
// s.THUMB_ID AS SHOP_THUMBNAIL 
// FROM 
// POST p 
// LEFT JOIN SHOP s ON p.MERCHANT = s.CODE 
// WHERE 
// p.POST_ID = '$id'

// get store products
$query = $dbconn->prepare("SELECT 
p.POST_ID as CODE, 
p.F_PIN,
p.TITLE AS PRODUCT_NAME, 
p.MERCHANT AS SHOP_CODE, 
p.PRICING,
p.PRICING_MONEY AS PRICE, 
p.LINK,
p.FILE_ID AS THUMB_ID, 
p.DESCRIPTION, 
p.TOTAL_COMMENT,
p.TOTAL_LIKES,
p.REPORT,
CONCAT(ul.FIRST_NAME, ' ', ul.LAST_NAME) AS SHOP_NAME, 
ul.IMAGE AS SHOP_THUMBNAIL,
ule.OFFICIAL_ACCOUNT,
(
    SELECT 
    COUNT(pur.POST_ID) 
    FROM PURCHASE pur
    WHERE pur.POST_ID = p.POST_ID
    AND pur.FPIN = '$f_pin'
) AS IS_PURCHASED
FROM 
POST p 
LEFT JOIN USER_LIST ul ON ul.F_PIN = p.F_PIN
LEFT JOIN USER_LIST_EXTENDED ule ON ule.F_PIN = ul.F_PIN
WHERE 
p.POST_ID = '$id'
");
$query->execute();
$product  = $query->get_result()->fetch_assoc();
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