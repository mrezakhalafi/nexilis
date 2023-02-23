<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
session_start();

$dbNewNus = newnus();
$dbPalio = paliolite();

// SELECT USER PROFILE
// $query = $dbconn->prepare("SELECT * FROM PRODUCT_CATEGORY WHERE IS_QIOSK = 1 ORDER BY SORT_ORDER ASC");

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

// $str = "
// SELECT w.*
// FROM WEBFORM w
// WHERE nc.BE_ID IN (
//     SELECT BE FROM USER_LIST WHERE F_PIN = '$f_pin'
// )
// ";
// $query = $dbconn->prepare($str);
// $query->execute();
// $groups  = $query->get_result();
// $query->close();

$sqlBE = "SELECT be.COMPANY_ID
  FROM BUSINESS_ENTITY be
  LEFT JOIN USER_LIST ul ON ul.BE = be.ID
  WHERE ul.F_PIN = '$f_pin'";

$queBE = $dbPalio->prepare($sqlBE);
$queBE->execute();
$resBE = $queBE->get_result()->fetch_assoc();
$c_id = $resBE["COMPANY_ID"];
$queBE->close();

$sqlWebform = "SELECT * FROM WEBFORM WHERE COMPANY_ID = $c_id AND GENERATE_APK > 0 ORDER BY CREATED_AT DESC LIMIT 1";
$queWebform = $dbNewNus->prepare($sqlWebform);
$queWebform->execute();
$resWebform = $queWebform->get_result()->fetch_assoc();
$queWebform->close();

// if ($resWebform[''])

// $rows = array();
// while ($group = $groups->fetch_assoc()) {
//     $rows[] = $group;
// };

echo json_encode($resWebform);
// return $rows;
?>