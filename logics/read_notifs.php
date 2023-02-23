<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

$dbconn = paliolite();

$notif_ids = json_decode($_POST['notif_ids']);

$sql = '';

for ($i = 0; $i < count($notif_ids); $i++) {
    //count($id_array) --> if I input 4 fields, count($id_array) = 4)

    $sql = "UPDATE USER_NOTIFICATION SET READ_STATUS = 1 WHERE NOTIF_ID = '$notif_ids[$i]'";
    $query = $dbconn->prepare($sql);
    $query->execute();
    $query->close();
}

    



// echo $sql;
