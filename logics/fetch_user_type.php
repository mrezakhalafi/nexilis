<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
session_start();
$dbconn = paliolite();
// $merchant_name = "Bogajaya";

if (isset($_SESSION["f_pin"])) {
  $f_pin = $_SESSION["f_pin"];
} else {
  $f_pin = $_GET['f_pin'];

  $queryCheckFpin = "SELECT F_PIN FROM USER_LIST WHERE F_PIN = '$f_pin' OR IMEI = '$f_pin'";
  $query = $dbconn->prepare($queryCheckFpin);
  $query->execute();
  $getNewFPIN = $query->get_result()->fetch_assoc();
  $query->close();

  $f_pin = $getNewFPIN["F_PIN"];
  $_SESSION["f_pin"] = $f_pin;
}

$querystr = "SELECT m.POSITION
FROM MEMBERS m
LEFT JOIN `GROUPS` g ON m.GROUP_ID = g.GROUP_ID
LEFT JOIN USER_LIST u ON u.F_PIN = m.F_PIN
WHERE g.IS_ORGANIZATION = 1
AND m.F_PIN = '$f_pin'";
$query = $dbconn->prepare($querystr);
$query->execute();
$user_type = $query->get_result()->fetch_assoc();
$query->close();

return $user_type["POSITION"];

?>