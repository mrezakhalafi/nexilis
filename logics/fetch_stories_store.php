<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

$dbconn = paliolite();

$showLinkless = 2;
try {
    $query = $dbconn->prepare("SELECT `VALUE` FROM `SHOP_SETTINGS` WHERE `PROPERTY` = 'SHOW_LINKLESS_STORE'");
    $query->execute();
    $geoloc = $query->get_result()->fetch_assoc();
    $showLinkless = $geoloc['VALUE'];
    $query->close();
} catch (\Throwable $th) {
}

// SELECT SHOP
if(isset($_REQUEST['f_pin'])){
    $f_pin = $_REQUEST['f_pin'];
    $query = $dbconn->prepare("SELECT s.*, be.ID as BE_ID FROM SHOP s left join BUSINESS_ENTITY be on s.PALIO_ID = be.COMPANY_ID where IS_VERIFIED = 1 or s.CREATED_BY = ? or s.CREATED_BY in (SELECT fl.L_PIN FROM FRIEND_LIST fl where fl.F_PIN = ?) order by s.SCORE desc");
    $query->bind_param("ss", $f_pin, $f_pin);
} else {
    $query = $dbconn->prepare("SELECT s.*, be.ID as BE_ID FROM SHOP s left join BUSINESS_ENTITY be on s.PALIO_ID = be.COMPANY_ID where IS_VERIFIED = 1 order by s.SCORE desc");
}
$query->execute();
$groups  = $query->get_result();
$query->close();

$stores = array();
while ($group = $groups->fetch_assoc()) {
    if($showLinkless == 2 || ($showLinkless == 1 && empty($group["LINK"])) || ($showLinkless == 0 && !empty($group["LINK"]))){
        $stores[] = $group;
    }
};
return $stores;
?>