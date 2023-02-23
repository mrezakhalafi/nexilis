<?php


// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

session_start();

$dbconn = paliolite();

// SELECT USER PROFILE
if (isset($_GET['store_id'])) {
    $store_id = $_GET['store_id'];
} else {
    die();
}

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


$sql = "    
SELECT 
rp.POST_ID
FROM 
REPORT_POST rp
WHERE 
rp.F_PIN = '$f_pin';
    ";
$query = $dbconn->prepare($sql);
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
// print_r($posts_reported);
// echo "<br>";
// echo "<br>";

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
$query->execute();
$groups = $query->get_result();
$query->close();

while ($group = $groups->fetch_assoc()) {
  $reports_count[$group["POST_ID"]] = array(
    "TOTAL_REPORTS" => $group["TOTAL_REPORTS"]
  );
}
// print_r($reports_count);
// echo "<br>";
// echo "<br>";

$str = '
(
  SELECT 
    p.POST_ID AS CODE, 
    p.TITLE, 
    p.DESCRIPTION, 
    p.CATEGORY,
    p.CREATED_DATE, 
    p.F_PIN AS CREATED_BY, 
    CONCAT(u.FIRST_NAME, " ", u.LAST_NAME) AS NAME, 
    NULL AS TAGGED_PRODUCT, 
    p.FILE_ID AS THUMB_ID, 
    0 AS `IS_PRODUCT`,
    (SELECT COUNT(POST_ID) FROM REPORT_POST) 
    AS REPORT_COUNT,
    (
      SELECT COUNT(bp.POST_ID)
      FROM BLOCK_POST bp
      WHERE bp.POST_ID = p.POST_ID AND bp.F_PIN = "' . $f_pin . '"
    ) AS IS_BLOCKED
  FROM 
    POST p 
    LEFT JOIN USER_LIST u ON p.F_PIN = u.F_PIN 
    LEFT JOIN REPORT_POST rp ON p.POST_ID = rp.POST_ID
  WHERE 
    p.F_PIN = "' . $store_id . '"
  AND p.EC_DATE IS NULL
  AND p.IS_REMOVED != 1
) 
';        
 
$query = $dbconn->prepare($str);
$query->execute();
$groups = $query->get_result();
$query->close();

$rows = array();
while ($group = $groups->fetch_assoc()) {
    $rows[] = $group;
};

$filtered_collections = array();

for ($i = 0; $i < count($rows); $i++) {
  $code = $rows[$i]["CODE"];
  $post_f_pin = $rows[$i]["F_PIN"];

  $is_less_100 = $reports_count[$code]["TOTAL_REPORTS"] < 100;
  $is_not_reported = !in_array($code, $posts_reported);
  $is_blocked = $rows[$i]['IS_BLOCKED'];

  if ($is_less_100 && $is_not_reported && $is_blocked == 0) {
    // continue;
    
    $filtered_collections[] = $rows[$i];
    
  } 
  
}

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

// print_r($filtered_collections);

echo json_encode(utf8ize($filtered_collections));
