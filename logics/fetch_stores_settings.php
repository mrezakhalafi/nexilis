<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

$dbconn = paliolite();

if (isset($_GET['param'])){
    // follow + visitor visibility
    if($_GET['param'] == "stats"){
        $query = $dbconn->prepare("SELECT `VALUE` FROM `SHOP_SETTINGS` WHERE `PROPERTY` = 'VIEW_FOLLOW_TAB1'");
        $query->execute();
        $geoloc = $query->get_result()->fetch_assoc();
        $geolocSts = $geoloc['VALUE'];
        $query->close();
    
        echo $geolocSts;
    }
    // show linkless stores
    else if ($_GET['param'] == "show_linkless"){
        $query = $dbconn->prepare("SELECT `VALUE` FROM `SHOP_SETTINGS` WHERE `PROPERTY` = 'SHOW_LINKLESS_STORE'");
        $query->execute();
        $geoloc = $query->get_result()->fetch_assoc();
        $geolocSts = $geoloc['VALUE'];
        $query->close();
    
        echo $geolocSts;
    }
}
?>