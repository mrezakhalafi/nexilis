<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

$dbconn = paliolite();

if (isset($_GET['f_pin'])) {
  $f_pin = $_GET['f_pin'];
}

$queryURL = $dbconn->prepare("SELECT ID, F_PIN, LINK_IMG AS THUMB, LINK_URL, LINK_ID FROM GRID_URL");
$queryURL->execute();
$resURL = $queryURL->get_result();
$queryURL->close();

$filtered_collections = array();

while ($result = $resURL->fetch_assoc()) {
    // if ($reports_count[$result["LINK_ID"]]["TOTAL_REPORTS"] < 100 && !in_array($result["LINK_ID"], $reported)) {
      $collections[] = $result;
    // }
  };

for ($i = 0; $i < count($collections); $i++) {
    $code = $collections[$i]["LINK_ID"];
    $post_f_pin = $collections[$i]["F_PIN"];

    $filtered_collections[] = $collections[$i];
    
  }

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
