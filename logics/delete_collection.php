<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

if (!isset($_POST['code'])) {
    die();
}

$dbconn = paliolite();

$collection_code = $_POST['code'];

try {

    $query = $dbconn->prepare("DELETE FROM `COLLECTION` WHERE COLLECTION_CODE = ?");
    $query->bind_param("s", $collection_code);
    $query->execute();
    $query->close();

    $query = $dbconn->prepare("DELETE FROM `COLLECTION_PRODUCT` WHERE COLLECTION_CODE = ?");
    $query->bind_param("s", $collection_code);
    $query->execute();
    $query->close();

    echo 'collection deleted';

} catch (\Throwable $th) {
    echo $th->getMessage() . ' on line ' . $th->getLine();
}

?>