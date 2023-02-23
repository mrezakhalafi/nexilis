<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

$dbconn = paliolite();

if(isset($_REQUEST['f_pin'])){
    $f_pin = $_REQUEST['f_pin'];
}

$consent = $_GET["consent"];

$sql = "SELECT `VALUE` FROM `USER_CONSENT` WHERE `F_PIN`='$f_pin' AND `KEY` = '$consent' LIMIT 1";
$query = $dbconn->prepare($sql);
$query->execute();
$userConsent = $query->get_result()->fetch_assoc();
$query->close();

echo $userConsent["VALUE"];

// return $rows;