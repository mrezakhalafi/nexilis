<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

$dbconn = paliolite();
$f_pin = $_GET['f_pin'];
$rows = array();

$query = $dbconn->prepare("SELECT srp.*, sh.NAME FROM SHOP_REWARD_POINT srp LEFT JOIN SHOP sh ON srp.STORE_CODE = sh.CODE WHERE F_PIN = ?");
$query->bind_param("s", $f_pin);
$query->execute();
$groups  = $query->get_result();
$query->close();

while ($group = $groups->fetch_assoc()) {
    $rows[] = $group;
};

// return $rows;
echo json_encode($rows);
?>