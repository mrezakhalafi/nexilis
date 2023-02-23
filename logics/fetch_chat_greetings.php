<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

$dbconn = paliolite();

// follow + visitor visibility
if (isset($_REQUEST['param']) && $_REQUEST['param'] == "greet") {
    $query = $dbconn->prepare("SELECT `VALUE_TEXT` FROM `SHOP_SETTINGS` WHERE `PROPERTY` = 'CHAT_GREETING'");
    $query->execute();
    $geoloc = $query->get_result()->fetch_assoc();
    $geolocSts = $geoloc['VALUE_TEXT'];
    $query->close();

    echo $geolocSts;
}

?>