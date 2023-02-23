<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

$dbconn = paliolite();
if(!isset($store_id) && isset($_GET['store_id'])){
    $store_id = $_GET['store_id'];
}
if(isset($store_id)){
    $query = $dbconn->prepare("SELECT 
	s.CODE, 
	s.CATEGORY, 
	(SELECT COUNT(*) FROM PRODUCT pr WHERE pr.SHOP_CODE = '$store_id')+(SELECT COUNT(*) FROM POST ps WHERE ps.MERCHANT = '$store_id') AS PRODUCT_COUNT,
	s.PALIO_ID 
FROM SHOP s 
WHERE s.CODE = '$store_id'");
}

// SELECT USER PROFILE
$query->execute();
$groups  = $query->get_result()->fetch_assoc();
// $product_count = $groups["PRODUCT_COUNT"];
$query->close();

if(is_null($groups["PALIO_ID"])){
    $product_count = -1;
}

echo json_encode($groups);
?>