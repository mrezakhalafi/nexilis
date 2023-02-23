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
// $be_id = 309;

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

$offset = $_GET['offset'];
// $offset = 5;

$sql_where = "";

if (isset($_GET['category'])) {
    $sql_where = "AND c.ID = " . $_GET['category'];
}

function utf8ize($d) {
    if (is_array($d)) {
        foreach ($d as $k => $v) {
            $d[$k] = utf8ize($v);
        }
    } else if (is_string ($d)) {
        return utf8_encode($d);
    }
    return $d;
}

try {

    $sql = "SELECT p.* , c.ID AS CATEGORY_ID, c.CODE AS CATEGORY
    FROM POST p
    LEFT JOIN CONTENT_CATEGORY cc ON p.POST_ID = cc.POST_ID
    LEFT JOIN CATEGORY c ON cc.CATEGORY = c.ID
    WHERE (".$fpin_str.")
    ".$sql_where."
    AND p.EC_DATE IS NULL
    AND p.LINK != ''
    GROUP BY p.POST_ID
    ORDER BY CREATED_DATE DESC 
    LIMIT 5 
    OFFSET $offset";
    // echo $sql;
    $query = $dbconn->prepare($sql);
    $query->execute();
    $data = $query->get_result();
    $query->close();

    $rows = array();
    while ($row = $data->fetch_assoc()){
        $rows[] = $row;
    }


    // IF DATA EXIST RETURN DATA

    if (isset($rows)){
        // print_r($rows);
        echo(json_encode(utf8ize($rows)));

    }else{
        echo("");
    }

} catch (\Throwable $th) {

    echo $th->getMessage();

}
    
