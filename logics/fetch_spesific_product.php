<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

$dbconn = paliolite();

if(isset($_REQUEST['product_code'])){
    $product_code = $_REQUEST['product_code'];
}

$query = $dbconn->prepare("SELECT p.NAME, p.DESCRIPTION, p.THUMB_ID, p.CREATED_DATE, s.THUMB_ID as SHOP_THUMB_ID FROM PRODUCT p join SHOP s on p.SHOP_CODE = s.CODE WHERE p.CODE='$product_code'");
$query->execute();
$groups  = $query->get_result();
$query->close();

$rows = array();
while ($group = $groups->fetch_assoc()) {
    $rows[] = $group;
};

// echo json_encode($rows);

return $rows;
