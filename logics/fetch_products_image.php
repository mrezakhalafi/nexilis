<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

$dbconn = paliolite();
$store_id = $_GET['store_id'];

// SELECT USER PROFILE
if(!isset($store_id) && isset($_GET['store_id'])){
    $store_id = $_GET['store_id'];
}
if (isset($store_id)) {
    // $query = $dbconn->prepare("SELECT p.THUMB_ID, p.IS_SHOW, s.CODE as STORE_CODE FROM PRODUCT p join SHOP s on p.SHOP_CODE = s.CODE WHERE p.SHOP_CODE = ? ORDER BY p.IS_SHOW, p.SCORE DESC, p.CREATED_DATE DESC");
    // $query->bind_param("s", $store_id);
}
else {
    // $query = $dbconn->prepare("SELECT p.THUMB_ID, p.IS_SHOW, s.CODE as STORE_CODE FROM PRODUCT p join SHOP s on p.SHOP_CODE = s.CODE ORDER BY p.IS_SHOW, p.SCORE DESC, p.CREATED_DATE DESC");
    $query = $dbconn->prepare("SELECT 	
	s.CODE AS STORE_CODE,
	p.THUMB_ID, 
	1 AS `IS_SHOW` 
FROM 
	PRODUCT p 
LEFT JOIN SHOP s ON p.SHOP_CODE = s.CODE 
GROUP BY s.CODE");
}
$query->execute();
$groups  = $query->get_result();
$query->close();

$rows = array();
while ($group = $groups->fetch_assoc()) {
    $rows[] = $group;
};

echo json_encode($rows);
?>