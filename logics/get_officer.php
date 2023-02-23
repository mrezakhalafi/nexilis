<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$dbconn = paliolite();

// 347 = BI, 23 = Officer

// $be = 347;
$userType = 23;

$f_pin = $_POST['f_pin'];

$sql = "SELECT * FROM USER_LIST WHERE F_PIN = '$f_pin'";
$query = $dbconn->prepare($sql);
$query->execute();
$getUserBE = $query->get_result()->fetch_assoc();
$query->close();

$be = $getUserBE['BE'];

// $sql = "SELECT * FROM USER_LIST ul LEFT JOIN USER_LIST_EXTENDED ule ON ul.F_PIN = ule.F_PIN WHERE ul.BE = '$be' AND ule.USER_TYPE = '$userType'";
$sql = "SELECT * FROM `GROUPS` WHERE IS_ORGANIZATION = 1 and BUSINESS_ENTITY = '$be'";

// $sql = "SELECT * FROM USER_LIST ul LEFT JOIN USER_LIST_EXTENDED ule ON ul.F_PIN = ule.F_PIN WHERE ul.BE = '$be' AND ule.OFFICIAL_ACCOUNT = '1'";

// echo $sql;

$query = $dbconn->prepare($sql);
$query->execute();
$userData = $query->get_result();
$query->close();

$arrayUser = [];

while ($row = $userData->fetch_assoc()) {

    $arrayUser[] = $row;
}

if (isset($arrayUser)) {
    print_r(json_encode($arrayUser));
} else {
    print_r("");
}
