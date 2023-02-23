<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

$dbconn = paliolite();

// SELECT USER PROFILE
// $query = $dbconn->prepare("SELECT * FROM PRODUCT_CATEGORY WHERE IS_QIOSK = 1 ORDER BY SORT_ORDER ASC");
$query = $dbconn->prepare("SELECT * FROM CATEGORY WHERE EDUCATIONAL = 3");
$query->execute();
$groups  = $query->get_result();
$query->close();

$rows = array();
while ($group = $groups->fetch_assoc()) {
    $rows[] = $group;
};

// echo json_encode($rows);
return $rows;
?>