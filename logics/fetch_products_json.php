<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');



$dbconn = paliolite();
if(!isset($store_id) && isset($_REQUEST['store_id'])){
    $store_id = $_REQUEST['store_id'];
}
if(!isset($f_pin) && isset($_REQUEST['f_pin'])){
    $f_pin = $_REQUEST['f_pin'];
}
if(!isset($que) && isset($_REQUEST['query'])){
    $que = $_REQUEST['query'];
}
$sql = "SELECT p.*, s.CODE as STORE_CODE, s.NAME as STORE_NAME, s.THUMB_ID as STORE_THUMB_ID, s.LINK as STORE_LINK, s.CATEGORY as CATEGORY, s.TOTAL_FOLLOWER as TOTAL_FOLLOWER, s.IS_VERIFIED as IS_STORE_VERIFIED, be.ID as BE_ID, sp.LENGTH, sp.WIDTH, sp.HEIGHT, sp.WEIGHT, sp.IS_FRAGILE FROM PRODUCT p join SHOP s on p.SHOP_CODE = s.CODE left join BUSINESS_ENTITY be on s.PALIO_ID = be.COMPANY_ID left join PRODUCT_SHIPMENT_DETAIL sp on p.CODE = sp.PRODUCT_CODE";
if(isset($store_id) || isset($f_pin) || isset($que)){
    $sql = $sql . " WHERE ";
}
if(isset($store_id)){
    $sql = $sql . "p.SHOP_CODE = ?";
    if(isset($que) || isset($f_pin)){
        $sql = $sql . " AND ";
    }
}
if(isset($que)){
    $sql = $sql . "(p.NAME like ? OR p.DESCRIPTION like ? OR s.NAME like ?)";
    $quelike = "%" . $que . "%";
    if(isset($f_pin)){
        $sql = $sql . " AND ";
    }
}
if(isset($f_pin)){
    $sql = $sql . "(s.IS_VERIFIED = 1 or s.CREATED_BY = ? or s.CREATED_BY in (SELECT fl.L_PIN FROM FRIEND_LIST fl where fl.F_PIN = ?))";
} else if(!(isset($store_id) || isset($f_pin) || isset($que))) {
    $sql = $sql . " WHERE s.IS_VERIFIED = 1";
} else {
    $sql = $sql . " AND s.IS_VERIFIED = 1";
}
$sql = $sql . " ORDER BY p.SCORE DESC, p.CREATED_DATE DESC";
$query = $dbconn->prepare($sql);
if (isset($store_id)) {
    if(isset($que)){
        if(isset($f_pin)){
            $query->bind_param("ssssss", $store_id, $quelike, $quelike, $quelike, $f_pin, $f_pin);
        } else {
            $query->bind_param("ssss", $store_id, $quelike, $quelike, $quelike);
        }
    } else if(isset($f_pin)){
        $query->bind_param("sss", $store_id, $f_pin, $f_pin);
    }
    else{
        $query->bind_param("s", $store_id);
    }
}
else if(isset($que)) {
    if(isset($f_pin)){
        $query->bind_param("sssss", $quelike, $quelike, $quelike, $f_pin, $f_pin);
    } else {
        $query->bind_param("sss", $quelike, $quelike, $quelike);
    }
} else if(isset($f_pin)){
    $query->bind_param("ss", $f_pin, $f_pin);
}
// SELECT USER PROFILE
$query->execute();
$groups  = $query->get_result();
$query->close();


$rows = array();
while ($group = $groups->fetch_assoc()) {
    $rows[] = $group;
};

function utf8ize($d) {
    if (is_array($d)) {
        foreach ($d as $k => $v) {
            $d[$k] = utf8ize($v);
        }
    } else if (is_string ($d)) {
        return utf8_encode($d);
    }
    return $d;
}

echo json_encode(utf8ize($rows));
?>