<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

// sentiment analysis library
require_once __DIR__ . '/autoload.php';

$dbconn = paliolite();
$shop_code = $_GET['shop_code']; // target post

// get product scores
$query = $dbconn->prepare("SELECT * FROM PRODUCT WHERE SHOP_CODE = '$shop_code'");
$query->execute();
$products  = $query->get_result();
$query->close();

$products_scores = 0;
foreach ($products as $product) {
    $products_scores += $product['SCORE'];
}

// get total follower
$query = $dbconn->prepare("SELECT * FROM SHOP WHERE CODE = '$shop_code'");
$query->execute();
$shop  = $query->get_result()->fetch_assoc();
$total_follower = $product['TOTAL_FOLLOWER'];
$query->close();

$cum_score = $products_scores + $total_follower;

$query = $dbconn->prepare("UPDATE SHOP SET SCORE = '$cum_score' WHERE CODE = '$shop_code'");
$status = $query->execute();
$query->close();

if ($status) {
    http_response_code(200);
} else {
    http_response_code(500);
}
