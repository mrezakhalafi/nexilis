<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

if (!isset($_POST['code'])) {
    die();
}

$dbconn = paliolite();

$collection_code = $_POST['code'];
$status_flag = $_POST['status_flag'];

try {

    $query = $dbconn->prepare("UPDATE `COLLECTION` c SET c.STATUS = ? WHERE c.COLLECTION_CODE = ?");
    $query->bind_param("is", $status_flag, $collection_code);
    $query->execute();
    $query->close();

    echo 'visibility updated';

} catch (\Throwable $th) {
    echo $th->getMessage();
}

?>