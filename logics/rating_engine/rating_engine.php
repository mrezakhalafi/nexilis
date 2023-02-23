<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

// sentiment analysis library
// require_once __DIR__ . '/autoload.php';

$dbconn = paliolite();
$product_code = $_POST['product_code']; // target post
$activity = $_POST['activity'];

if ($activity == 'like') {
    $flag_like = $_POST['flag_like'];
}

// get score vars
$query = $dbconn->prepare("SELECT * FROM POST_SCORE_PARAMETER");
$query->execute();
$score_var = $query->get_result();
$query->close();

$score = array();

while($sc = $score_var->fetch_assoc()) {
  $score[$sc["PARAM"]] = $sc["VALUE"];
}

// get post score 
$query = $dbconn->prepare("SELECT SCORE FROM POST WHERE POST_ID = '$product_code'");
$query->execute();
$post = $query->get_result()->fetch_assoc();
$query->close();
$post_score = $post["SCORE"];

if ($activity == 'like' && $flag_like == 1) {
    $total_score = $post_score + $score["LIKE"];
} else if ($activity == 'comment') {
    $total_score = $post_score + $score["COMMENT"];
}

$query = $dbconn->prepare("UPDATE POST SET SCORE = '$total_score' WHERE POST_ID = '$product_code'");
$status = $query->execute();
$query->close();

if($status){
    http_response_code(200);
} else {
    http_response_code(500);
}