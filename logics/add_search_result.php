<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

$dbconn = paliolite();


// $items = explode('|', $_POST['items']);
$items = json_decode(base64_decode($_POST['rows']));

$arr = array();
foreach ($items as $it) {
    array_push($arr, "(\"" . $it->LINK_ID . "\", \"" . $it->TITLE . "\", \"" . $it->DESC . "\", \"" . $it->THUMB . "\", \"" . $it->CONTEXT_LINK . "\", \"" . $it->CATEGORY . "\")");
}

$str = implode(",", $arr);

// echo $str;

// try {

    $sql = "REPLACE INTO LINKS (`LINK_ID`, `TITLE`, `DESC`, `THUMB`, `CONTEXT_LINK`, `CATEGORY`) VALUES " . $str;

    // echo $sql;

    // $query = $dbconn->prepare("INSERT INTO `COLLECTION` (`F_PIN`, `COLLECTION_CODE`, `NAME`, `DESCRIPTION`, `TOTAL_VIEWS`, `STATUS`) VALUES ('$f_pin', '$collection_code', '$name', '$desc', 0, '$status')");
    // $query->execute();
    // $query->close();

    $query = $dbconn->prepare($sql);
    $query->execute();
    $query->close();

    echo 'success';

// } catch (\Throwable $th) {
//     echo $th->getMessage() . " on line " . $th->getLine();
// }

?>