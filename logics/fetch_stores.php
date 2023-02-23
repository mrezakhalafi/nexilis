<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

session_start();

$dbconn = paliolite();

// SELECT USER PROFILE
if (isset($_SESSION["f_pin"])) {
  $f_pin = $_SESSION["f_pin"];
} else {
  $f_pin = $_GET['f_pin'];

  $queryCheckFpin = "SELECT F_PIN FROM USER_LIST WHERE F_PIN = '$f_pin' OR IMEI = '$f_pin'";
  $query = $dbconn->prepare($queryCheckFpin);
  $query->execute();
  $getNewFPIN = $query->get_result()->fetch_assoc();
  $query->close();

  $f_pin = $getNewFPIN["F_PIN"];
  $_SESSION["f_pin"] = $f_pin;
}

$be_or_fpin = "SELECT BE FROM USER_LIST WHERE F_PIN = '$f_pin'";

if (isset($f_pin)) {
  $posts_reported_raw = include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/fetch_posts_reported_raw.php');
    $posts_reported = array();
    foreach ($posts_reported_raw as $post_reported) {
        $posts_reported[] = $post_reported["POST_ID"];
    }

    $reports_arr = include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/fetch_reported_posts.php');

    $blocked_users_raw = include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/fetch_blocked_users.php');
    $blocked_users = array();
    foreach ($blocked_users_raw as $blocked_user) {
        $blocked_users[] = $blocked_user["L_PIN"];
    }

    $users_reported_raw = include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/fetch_users_reported_raw.php');
    $users_reported = array();
    foreach ($users_reported_raw as $user_reported) {
        $users_reported[] = $user_reported["F_PIN_REPORTED"];
    }

    $user_reports_arr = include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/fetch_reported_users.php');

    $sql_query = "SELECT 
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
  s.IS_QIOSK IN
    (".$be_or_fpin.")
    OR s.CREATED_BY IN (
      SELECT 
        fl.L_PIN 
      FROM 
        FRIEND_LIST fl 
      WHERE 
        fl.F_PIN = '$f_pin'
    ) 
    OR s.CREATED_BY = '$f_pin' 
    GROUP BY s.CODE
  ORDER BY 
    s.SCORE DESC";
}
else {
    $sql_query = "SELECT 
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
    AND s.IS_QIOSK IN
    (".$be_or_fpin.")
    GROUP BY s.CODE
  ORDER BY 
    s.SCORE DESC";
};
// echo $sql_query;
$query = $dbconn->prepare($sql_query);
$query->execute();
$groups  = $query->get_result();
$query->close();

$rows = array();
while ($group = $groups->fetch_assoc()) {
  $post_f_pin = $group["CREATED_BY"];
  $is_less_100_users = $user_reports_arr[$post_f_pin]['TOTAL_REPORTS'] < 100;
  $is_not_reported_user = !in_array($post_f_pin, $users_reported);
  $is_not_blocked = !in_array($post_f_pin, $blocked_users);

  if ($is_less_100_users && $is_not_reported_user && $is_not_blocked) {
    $rows[] = $group;
  }
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

// echo "<pre>";
// print_r($rows);
// echo "</pre>";
echo json_encode(utf8ize($rows));
