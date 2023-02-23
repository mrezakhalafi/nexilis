<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

$dbconn = paliolite();

if(isset($_REQUEST['f_pin'])){
    $f_pin = $_REQUEST['f_pin'];
}

$consent = $_GET["consent"];
$answer = $_GET["answer"];

try {
    $sql = "REPLACE INTO `USER_CONSENT` (`KEY`, `F_PIN`, `VALUE`) VALUES ('$consent', '$f_pin', $answer)";
    $query = $dbconn->prepare($sql);
    $query->execute();
    $query->close();

    echo "Success";
} catch (Exception $e) {
    echo "Error";
}

// return $rows;