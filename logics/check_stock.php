<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

$dbconn = paliolite();

$name = json_decode(base64_decode($_POST['items']));

try {    

    $item_arr = array();
    foreach($name as $na) {
        $item_arr[] = "CODE = '" . $na . "'";
    }

    $item_str = implode(" OR ", $item_arr);

    $sql = "SELECT CODE, NAME, QUANTITY FROM PRODUCT p WHERE " . $item_str;

    $query = $dbconn->prepare($sql);
    $query->execute();
    $result = $query->get_result();
    $query->close();

    $stock = array();
    while($res = $result->fetch_assoc()) {
        $stock[] = $res;
    }

    echo(json_encode($stock));

} catch (\Throwable $th) {

    echo $th->getMessage();

}
    