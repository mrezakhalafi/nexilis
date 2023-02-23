<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

$dbconn = paliolite();

$f_pin = $_GET['f_pin'];

// check BE
$query = $dbconn->prepare("SELECT BE FROM USER_LIST WHERE F_PIN = '$f_pin'");
$query->execute();
$userBE = $query->get_result()->fetch_assoc();
$query->close();

$be_id = $userBE["BE"];

if ($be_id == 312) { // BE DigiNetS
    $news_fpin_arr = ["027bb0e4a8", "027bdad120", "027bd9b5ff", "027bd83b7c", "02f041b31a"];
    // $news_fpin = "02f041b31a";       
} else if ($be_id == 309) { // BE TNI
    $news_fpin_arr = ["02d7c16d7a"];
}

$query_arr = array();

foreach($news_fpin_arr as $fp) {
    array_push($query_arr, "p.F_PIN = '" . $fp . "'");
}

$fpin_str = implode(" OR ", $query_arr);

$sql_where = "";

if (isset($_GET['category'])) {
    $sql_where = "AND c.ID = ". $_GET['category'];
}

try {

    $sql = "
    SELECT p.* , c.ID, c.CODE
    FROM POST p
    LEFT JOIN CONTENT_CATEGORY cc ON p.POST_ID = cc.POST_ID
    LEFT JOIN CATEGORY c ON cc.CATEGORY = c.ID
    WHERE (".$fpin_str.")
    ".$sql_where."
    AND p.EC_DATE IS NULL
    AND p.LINK != ''
    ORDER BY CREATED_DATE DESC";
    $query = $dbconn->prepare($sql);
    $query->execute();
    $data = $query->get_result();
    $query->close();

    // IF DATA EXIST RETURN DATA

    if (isset($data)){
        echo(mysqli_num_rows($data));
    }else{
        echo("");
    }

} catch (\Throwable $th) {

    echo $th->getMessage();

}
    
