<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

$dbconn = paliolite();

if(isset($_REQUEST['f_pin'])){
    $f_pin = $_REQUEST['f_pin'];
}

$query = $dbconn->prepare("SELECT IS_CHANGED_PROFILE FROM USER_LIST WHERE F_PIN='$f_pin' LIMIT 1");
$query->execute();
$userStatus = $query->get_result()->fetch_assoc();
$query->close();

echo $userStatus["IS_CHANGED_PROFILE"];

// return $rows;