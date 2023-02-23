<?php


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

$dbconn = paliolite();

// SELECT USER PROFILE
// if (isset($_GET['store_id'])) {
//     $store_id = $_GET['store_id'];
// } else {
//     die();
// }

if (isset($_GET['f_pin'])) {
  $f_pin = $_GET['f_pin'];
}

// $query = $dbconn->prepare("SELECT p.*, s.CODE as STORE_CODE, s.NAME as STORE_NAME, s.THUMB_ID as STORE_THUMB_ID, s.LINK as STORE_LINK, s.TOTAL_FOLLOWER as TOTAL_FOLLOWER FROM PRODUCT p join SHOP s on p.SHOP_CODE = s.CODE WHERE p.SHOP_CODE = '$store_id' AND p.IS_DELETED = 0 ORDER BY p.SCORE DESC, p.CREATED_DATE DESC");

// '
//       SELECT 
//         p.POST_ID, 
//         p.TITLE, 
//         p.DESCRIPTION, 
//         p.CREATED_DATE, 
//         p.F_PIN, 
//         CONCAT(u.FIRST_NAME, " ", u.LAST_NAME), 
//         NULL AS TAGGED_PRODUCT, 
//         p.FILE_ID, 
//         s.CODE, 
//         s.NAME, 
//         s.LINK, 
//         s.THUMB_ID, 
//         p.TOTAL_LIKES, 
//         s.IS_VERIFIED, 
//         0 AS `IS_PRODUCT`
//       FROM 
//         SHOP s 
//         LEFT JOIN POST p ON s.CREATED_BY = p.F_PIN 
//         LEFT JOIN USER_LIST u ON p.F_PIN = u.F_PIN 
//       WHERE 
//       s.CODE = "' . $store_id . '"'
$str = '
      SELECT 
        p.POST_ID, 
        p.TITLE, 
        p.DESCRIPTION, 
        p.CREATED_DATE, 
        p.F_PIN AS CODE, 
        CONCAT(u.FIRST_NAME, " ", u.LAST_NAME) AS NAME, 
        NULL AS TAGGED_PRODUCT, 
        CONCAT(p.FILE_ID,"|",p.THUMB_ID) AS THUMB_ID,
        0 AS `IS_PRODUCT`
      FROM 
        POST p 
        LEFT JOIN USER_LIST u ON p.F_PIN = u.F_PIN 
      WHERE 
      p.F_PIN = "' . $f_pin . '"';
        

// echo $str;
$query = $dbconn->prepare($str);
$query->execute();
$groups = $query->get_result();
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
