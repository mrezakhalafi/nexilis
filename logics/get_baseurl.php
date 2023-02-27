<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

function dbconn()
{
    //     $host = "localhost:3306";
    //     $user = "root";
    //  $password = "";
    $host = "127.0.0.1:3306";
    $user = "nup";
    $password = "5m1t0l_aptR";
    // $host = "202.158.33.27:3306";
    // $user = "nup";
    // $password = "5m1t0l_aptR";
    $database = "palio_lite_qiosk";

    $dbconn = mysqli_connect($host, $user, $password, $database);

    if (mysqli_connect_errno()) {
        echo "Koneksi database gagal : " . mysqli_connect_errno();
    } else {
        $dbconn->autocommit(TRUE);
        return $dbconn;
    }
}

$apikey = $_GET["key"];

$dbconn = dbconn();

$queryStr = "SELECT pr.KEY, pr.VALUE, be.ID AS BE_ID
FROM PREFS pr
LEFT JOIN BUSINESS_ENTITY be ON be.ID = pr.BE
WHERE be.API = '$apikey'";
$query = $dbconn->prepare($queryStr);
$query->execute();
$getSrv = $query->get_result();
$query->close();

$arr = array();
while ($key = $getSrv->fetch_assoc()) {
    $arr["BE_ID"] = $key['BE_ID'];
    $arr[$key["KEY"]] = $key["VALUE"];
}

// print_r($arr);

if (array_key_exists("app_builder_url_base", $arr)) {
    $urlBase = $arr["app_builder_url_base"];
    $be_id = $arr["BE_ID"];

    if (str_contains($urlBase, "newuniverse.io")) { // if url_base = CBN, get prefs

        echo json_encode($arr);
    } else { // not CBN
        $keys = array(
            "server_url" => $urlBase,
            "be_id" => $be_id,
        );
        echo json_encode($keys);
    }
    
} else {
    $urlBase = "https://newuniverse.io/";
    $be_id = $arr["BE_ID"];

    echo json_encode($arr);
}