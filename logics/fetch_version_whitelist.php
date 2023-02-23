<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

$dbconn = paliolite();

if ( isset($_REQUEST['app_name'])) {
    $query = $dbconn->prepare("SELECT `VERSION` FROM `VERSION_WHITELIST` WHERE `APP_ID` = ?");
    $query->bind_param("s", $_REQUEST['app_name']);
    $query->execute();
    $geoloc = $query->get_result()->fetch_assoc();
    $geolocSts = $geoloc['VERSION'];
    $query->close();

    echo $geolocSts;
}

?>