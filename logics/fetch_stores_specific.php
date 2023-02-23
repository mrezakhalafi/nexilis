<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

$dbconn = paliolite();
if(!isset($store_id) && isset($_GET['store_id'])){
    $store_id = $_GET['store_id'];
}
if(isset($store_id)){
    $query = $dbconn->prepare("SELECT s.*, be.ID as BE_ID, be.EMAIL_SUPPORT as BE_EMAIL, ssa.ADDRESS, ssa.VILLAGE, ssa.DISTRICT, ssa.CITY, ssa.PROVINCE, ssa.ZIP_CODE, ssa.PHONE_NUMBER, ssa.COURIER_NOTE FROM SHOP s left join BUSINESS_ENTITY be on s.PALIO_ID = be.COMPANY_ID left join SHOP_SHIPPING_ADDRESS ssa on s.CODE = ssa.STORE_CODE WHERE s.CODE = ? order by s.SCORE desc");
    $query->bind_param("s", $store_id);
}
else{
    $query = $dbconn->prepare("SELECT s.*, be.ID as BE_ID, be.EMAIL_SUPPORT as BE_EMAIL FROM SHOP s left join BUSINESS_ENTITY be on s.PALIO_ID = be.COMPANY_ID order by s.SCORE desc");
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