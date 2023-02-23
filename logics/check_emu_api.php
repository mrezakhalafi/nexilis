<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

$dbconn = paliolite();

$data = json_decode(file_get_contents('php://input'), true);

// contoh
// $json = '{
//     "board": "ASUS_I001DA",
//     "device": "aosp",
//     "fingerprint": "google/android_x86",
//     "host": "windows"
// }';
// $data = json_decode($json, true);

if(isset($data)){

    $keys = array_keys($data);

    // print_r($keys);

    $sql_where = "";

    // $keys = array_keys($arr);
    $where_arr = array();
    foreach($keys as $key) {
        $keyname = strtoupper($key);
        $value = $data[$key];
        $str = "(`KEY` = '$keyname' AND `VALUE` LIKE '%$value%')";
        array_push($where_arr, $str);
    }

    $sql_where = implode(" OR ", $where_arr);

    // echo $sql_where;

    $score = 0;
    try {
        $query_str = "SELECT SUM(SCORE) AS SUM_SCORE FROM EMULATOR_BLACKLIST WHERE " . $sql_where;
        // echo $query_str;
        // echo "\n\n";

        $query = $dbconn->prepare($query_str);

        $query->execute();
        $data = $query->get_result()->fetch_assoc();
        $query->close();

        $score = $data['SUM_SCORE'];

        if ($score > 5){

            $output = '{
                "error_code": 1
            }';

            echo($output);
        }else{

            $output = '{
                "error_code": 0
            }';

            echo($output);
        }

    } catch (\Throwable $th) {

        echo $th->getMessage();

    }
    
}