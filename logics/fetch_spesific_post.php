<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

$dbconn = paliolite();

if(isset($_REQUEST['product_code'])){
    $product_code = $_REQUEST['product_code'];
}


if (isset($_GET['is_post'])){
    $is_post = $_GET['is_post'];
} else {
    $is_post = 0;
}

// $query = $dbconn->prepare("SELECT s.NAME, p.DESCRIPTION, p.THUMB_ID, p.CREATED_DATE, s.THUMB_ID AS SHOP_THUMB_ID FROM POST p LEFT JOIN SHOP s ON p.MERCHANT = s.CODE WHERE p.POST_ID='$product_code'");

if ($is_post == 0) {
    $sql = "SELECT 'Nexilis' AS NAME, p.DESC AS DESCRIPTION, NULL AS THUMB_ID, p.CREATED_AT AS CREATED_DATE FROM LINKS p WHERE p.LINK_ID='$product_code'";
} else {
    $sql = "SELECT CONCAT(ul.FIRST_NAME, ' ', ul.LAST_NAME) AS NAME, p.DESCRIPTION, p.CREATED_DATE, ul.IMAGE AS THUMB_ID FROM POST p LEFT JOIN USER_LIST ul ON p.F_PIN = ul.F_PIN WHERE p.POST_ID='$product_code'";
}

// echo $sql;

$query = $dbconn->prepare($sql);

// $query = $dbconn->prepare("SELECT p.NAME, p.DESCRIPTION, p.THUMB_ID, p.CREATED_DATE, s.THUMB_ID as SHOP_THUMB_ID FROM PRODUCT p join SHOP s on p.SHOP_CODE = s.CODE WHERE p.CODE='$product_code'");
$query->execute();
$groups  = $query->get_result();
$query->close();

$rows = array();
while ($group = $groups->fetch_assoc()) {
    $rows[] = $group;
};

// echo json_encode($rows);

return $rows;
