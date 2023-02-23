<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

$dbconn = paliobrowser();

if(empty($_GET)){
    $fpin = $_POST['fpin'];
    $method = $_POST['method'];
    $status = $_POST['status'];
    $items = $_POST['items'];
} else {
    $fpin = $_GET['fpin'];
    $method = $_GET['method'];
    $status = $_GET['status'];
    $items = $_GET['items'];
}

// insert company
$query = $dbconn->prepare("INSERT INTO PAYMENT (F_PIN, METHOD, STATUS, ITEMS) VALUES (?, ?, ?, ?)");
$query->bind_param("ssss", $fpin, $method, $status, $items);
$query->execute();
$query->close();

// echo "success";
redirect(base_url() . "palio_browser/pages/timeline.php");
