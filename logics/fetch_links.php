<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
session_start();
$dbconn = paliolite();
// $merchant_name = "Bogajaya";

if (isset($_SESSION['f_pin'])) {
  $f_pin = $_SESSION['f_pin'];
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

if (isset($_GET['p'])) {
  $be_id = $_GET['p'];
}

// get item code

// fetch from links 
// $query = $dbconn->prepare("SELECT * FROM LINKS");
// $query->execute();
// $results = $query->get_result();
// $query->close();

$collections = array();
// while ($result = $results->fetch_assoc()) {
//     $collections[] = $result;
// };

if (isset($f_pin)) {
  $be_or_fpin = "SELECT BE FROM USER_LIST WHERE F_PIN = '$f_pin'";
} else if (isset($be_id)) {
  $be_or_fpin = "SELECT ID FROM BUSINESS_ENTITY WHERE API = '$be_id'";
}

$reports_count = array();
$sql = " 
    SELECT 
    rp.POST_ID, 
   COUNT(rp.POST_ID) AS TOTAL_REPORTS
  FROM 
    REPORT_POST rp 
GROUP BY rp.POST_ID
        ";
// echo $sql;
$query = $dbconn->prepare($sql);
// $query = $dbconn->prepare("SELECT c1.PRODUCT_CODE FROM PRODUCT_COMMENT c1 LEFT JOIN PRODUCT_COMMENT c2 on c1.REF_COMMENT_ID = c2.COMMENT_ID WHERE (c1.REF_COMMENT_ID IS NULL OR c2.ID IS NOT NULL) AND c1.F_PIN = ?");
// $query->bind_param("s", $f_pin);
// SELECT USER PROFILE
$query->execute();
$groups = $query->get_result();
$query->close();

while ($group = $groups->fetch_assoc()) {
  $reports_count[$group["POST_ID"]] = array(
    "TOTAL_REPORTS" => $group["TOTAL_REPORTS"]
  );
}

// echo "<pre>";
// print_r($reports_count);
// echo "</pre>";

$reported = array();
$sql = "    
SELECT 
rp.POST_ID
FROM 
REPORT_POST rp
WHERE 
rp.F_PIN = '$f_pin';
    ";
$query = $dbconn->prepare($sql);
// $query = $dbconn->prepare("SELECT c1.PRODUCT_CODE FROM PRODUCT_COMMENT c1 LEFT JOIN PRODUCT_COMMENT c2 on c1.REF_COMMENT_ID = c2.COMMENT_ID WHERE (c1.REF_COMMENT_ID IS NULL OR c2.ID IS NOT NULL) AND c1.F_PIN = ?");
// $query->bind_param("s", $f_pin);
// SELECT USER PROFILE
$query->execute();
$groups = $query->get_result();
$query->close();

while ($group = $groups->fetch_assoc()) {
  $reported[] = $group;
};

$posts_reported = array();
    foreach ($reported as $post_reported) {
        $posts_reported[] = $post_reported["POST_ID"];
    }


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
// echo "<pre>";
// print_r($posts_reported);
// echo "</pre>";

// fetch from posts
// CONCAT(p.THUMB_ID,'|',p.FILE_ID) AS THUMB, 
$sql = "
SELECT 
  p.ID, 
  p.POST_ID AS LINK_ID, 
  p.TITLE, 
  p.F_PIN, 
  p.CATEGORY,
  p.PARENT,
  CONCAT(ul.FIRST_NAME, ' ', ul.LAST_NAME) AS USERNAME, 
  p.DESCRIPTION AS `DESC`, 
  p.FILE_ID AS THUMB, 
  p.THUMB_ID AS THUMBNAIL,
  p.LINK AS CONTEXT_LINK, 
  ul.IMAGE AS PROFPIC,
  ule.OFFICIAL_ACCOUNT,
  (
	SELECT 
    COUNT(c1.POST_ID)
  FROM 
    POST_REACTION c1 
  WHERE 
    c1.FLAG = 1
    AND c1.POST_ID = p.POST_ID
  ) AS TOTAL_LIKES, 
  (
	SELECT 
    COUNT(c1.POST_ID)
  FROM 
    POST_COMMENT c1 
    LEFT JOIN POST_COMMENT c2 ON c1.REF_COMMENT_ID = c2.COMMENT_ID 
  WHERE 
    (
      c1.REF_COMMENT_ID IS NULL 
      OR c2.ID IS NOT NULL
    ) 
    AND c1.POST_ID = p.POST_ID
  ) AS TOTAL_COMMENTS, 
  (
    SELECT 
      COUNT(s.CODE)
    FROM 
      SHOP s
    WHERE 
      s.CREATED_BY = p.F_PIN
    ) AS HAS_SHOP,
  p.CREATED_DATE AS CREATED_AT, 
  p.SCORE,
  ule.OFFICIAL_ACCOUNT,
  (SELECT COUNT(L_PIN) FROM FRIEND_LIST WHERE F_PIN = '$f_pin' AND L_PIN = p.F_PIN) AS IS_FRIEND,
  (
    SELECT COUNT(bp.POST_ID)
    FROM BLOCK_POST bp
    WHERE bp.POST_ID = p.POST_ID
    AND bp.F_PIN = '$f_pin'
  ) AS IS_BLOCK_POST
FROM 
  POST p 
  LEFT JOIN USER_LIST ul ON ul.F_PIN = p.F_PIN AND IFNULL(ul.AC, 0) = (SELECT IFNULL(AC, 0) FROM USER_LIST WHERE F_PIN = '$f_pin')
  LEFT JOIN USER_LIST_EXTENDED ule ON ule.F_PIN = ul.F_PIN
  LEFT JOIN ACCESS_CATEGORY acc ON ul.AC = acc.ID
WHERE 
  ul.BE IN (
    " . $be_or_fpin . "
  )
  AND p.EC_DATE IS NULL
  AND p.IS_REMOVED != 1
  ORDER BY p.SCORE DESC
";
// echo $sql;
$query = $dbconn->prepare($sql);
$query->execute();
$posts = $query->get_result();
$query->close();



while ($result = $posts->fetch_assoc()) {
  // if ($reports_count[$result["LINK_ID"]]["TOTAL_REPORTS"] < 100 && !in_array($result["LINK_ID"], $reported)) {
    $collections[] = $result;
  // }
};

$filtered_collections = array();

for ($i = 0; $i < count($collections); $i++) {
  $code = $collections[$i]["LINK_ID"];
  $post_f_pin = $collections[$i]["F_PIN"];
// echo $code . "<br>";
  // if ($reports_count[$code]["TOTAL_REPORTS"] < 100 && !in_array($code, $reported)) {
  //   $filtered_collections = $collections[$i]; 
  // }

  // echo $code . ' ';
  // var_dump($reports_count[$code]["TOTAL_REPORTS"]);
  // echo ' ';
  // var_dump(in_array($code, $posts_reported));
  // echo '<br>';

  $is_less_100 = $reports_count[$code]["TOTAL_REPORTS"] < 100;
  $is_not_reported = !in_array($code, $posts_reported);
  $is_not_blocked = !in_array($post_f_pin, $blocked_users);
  $is_less_100_users = $user_reports_arr[$post_f_pin]['TOTAL_REPORTS'] < 100;
  $is_not_reported_user = !in_array($post_f_pin, $users_reported);
  $is_blocked_post = $collections[$i]['IS_BLOCK_POST'];

  if ($is_less_100 && $is_not_blocked && $is_not_reported && $is_not_reported_user && $is_less_100_users && $is_blocked_post == 0) {
    // continue;
    
    $filtered_collections[] = $collections[$i];
    
  } 
  
}

// echo "<pre>";
// print_r($filtered_collections);
// echo "</pre>";


// echo json_encode($collections);
function utf8ize($d)
{
  if (is_array($d)) {
    foreach ($d as $k => $v) {
      $d[$k] = utf8ize($v);
    }
  } else if (is_string($d)) {
    return utf8_encode($d);
  }
  return $d;
}

echo json_encode(utf8ize($filtered_collections));
