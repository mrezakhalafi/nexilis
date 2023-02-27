<?php

    //ini_set('display_errors', 1);
    //ini_set('display_startup_errors', 1);
    // error_reporting(E_ALL);

function dbconn()
{
        
        $host = "127.0.0.1:3306";
        $user = "nup";
        $password = "5m1t0l_aptR";
        $database = "palio_lite_qiosk";

    $dbconn = mysqli_connect($host, $user, $password, $database);

    if (mysqli_connect_errno()) {
        echo "Koneksi database gagal : " . mysqli_connect_errno();
    } else {
        $dbconn->autocommit(TRUE);
        return $dbconn;
    }
}

$be = $_GET["be"];
$dbconn = dbconn();

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