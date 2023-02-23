<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
$dbconn = paliolite();

$query = $dbconn->prepare("SELECT DISTINCT SHOP_CODE FROM PRODUCT WHERE IS_DELETED = 0");
$query->execute();
$result = $query->get_result();
$array = [];
foreach ($result as $r) {
    array_push($array, $r['SHOP_CODE']);
}
$query->close();

echo json_encode($array);