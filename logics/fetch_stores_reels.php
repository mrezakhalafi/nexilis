<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

$dbconn = paliolite();

// SELECT USER PROFILE
$query = $dbconn->prepare("SELECT sr.STORE_CODE, s.NAME as STORE_NAME, sr.TITLE, sr.COVER_ID, sr.URL, s.THUMB_ID as STORE_LOGO, s.IS_VERIFIED from SHOP_REELS sr left join SHOP s on sr.STORE_CODE = s.CODE order by sr.CREATED_DATE desc");
// $query = $dbconn->prepare("SELECT s.*, be.ID as BE_ID FROM SHOP s left join BUSINESS_ENTITY be on s.PALIO_ID = be.COMPANY_ID order by s.SCORE desc");

$query->execute();
$groups  = $query->get_result();
$query->close();

$rows = array();
while ($group = $groups->fetch_assoc()) {
    $rows[] = $group;
};

echo json_encode($rows);
?>