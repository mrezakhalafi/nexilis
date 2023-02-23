<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

$dbconn = paliolite();

// SELECT USER PROFILE
if(!isset($f_pin) && isset($_GET['f_pin'])){
    $f_pin = $_GET['f_pin'];
}


if (isset($f_pin)) {
    $query = $dbconn->prepare("SELECT 
    s.*, 
    be.ID AS BE_ID, 
    srp.AMOUNT AS REWARD_POINT, 
    ssa.ADDRESS, 
    ssa.VILLAGE, 
    ssa.DISTRICT, 
    ssa.CITY, 
    ssa.PROVINCE, 
    ssa.ZIP_CODE, 
    ssa.PHONE_NUMBER, 
    ssa.COURIER_NOTE, 
    COUNT(pc.COMMENT_ID) AS COMMENTS 
  FROM 
    SHOP s 
    LEFT JOIN SHOP_REWARD_POINT srp ON (
      s.CODE = srp.STORE_CODE 
      AND srp.F_PIN = '$f_pin'
    ) 
    LEFT JOIN BUSINESS_ENTITY be ON s.PALIO_ID = be.COMPANY_ID 
    LEFT JOIN SHOP_SHIPPING_ADDRESS ssa ON s.CODE = ssa.STORE_CODE 
    LEFT JOIN POST po ON po.MERCHANT = s.CODE
    LEFT JOIN POST_COMMENT pc ON pc.POST_ID = po.POST_ID
  WHERE 
    s.IS_VERIFIED = 1 
    AND s.IS_QIOSK = 1
    OR s.CREATED_BY = '$f_pin' 
    GROUP BY s.CODE
  ORDER BY 
    s.SCORE DESC");
}
else {
    $query = $dbconn->prepare("SELECT 
    s.*, 
    be.ID AS BE_ID,
    COUNT(pc.COMMENT_ID) AS COMMENTS 
  FROM 
    SHOP s 
    LEFT JOIN BUSINESS_ENTITY be ON s.PALIO_ID = be.COMPANY_ID 
    LEFT JOIN POST po ON po.MERCHANT = s.CODE
    LEFT JOIN POST_COMMENT pc ON pc.POST_ID = po.POST_ID
  WHERE 
    s.IS_VERIFIED = 1 
    AND s.IS_QIOSK = 1
    GROUP BY s.CODE
  ORDER BY 
    s.SCORE DESC");
};

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
