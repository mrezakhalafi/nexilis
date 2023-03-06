<?php

    //ini_set('display_errors', 1);
    //ini_set('display_startup_errors', 1);
    // error_reporting(E_ALL);

include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
$dbconn = paliolite();

$be = $_GET["be"];

// get all prefs
$queryStr = "SELECT `KEY`, `VALUE` FROM `PREFS` WHERE `BE` = $be";
$query = $dbconn->prepare($queryStr);
$query->execute();
$prefs = $query->get_result();
$query->close();

$arr = array();
while($key = $prefs->fetch_assoc()) {
    $arr[$key["KEY"]] = $key["VALUE"];
}

echo json_encode($arr);