<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

if (!isset($_GET['f_pin'])) {
    die();
}

$dbconn = paliolite();

$f_pin = $_GET["f_pin"];

// FETCH COLLECTIONS
$query = $dbconn->prepare("SELECT c.*, p.THUMB_ID AS COLLECTION_THUMB,
                            (SELECT COUNT(*) FROM `COLLECTION_PRODUCT` cp WHERE c.COLLECTION_CODE = cp.COLLECTION_CODE) AS `PRODUCT_COUNT`
                            FROM `COLLECTION` c
                            LEFT JOIN COLLECTION_PRODUCT cp ON c.COLLECTION_CODE = cp.COLLECTION_CODE
                            LEFT JOIN PRODUCT p ON cp.PRODUCT_CODE = p.CODE
                            WHERE c.F_PIN = '$f_pin'
                            GROUP BY cp.COLLECTION_CODE");
$query->execute();
$results = $query->get_result();
$query->close();

$collections = array();
while ($result = $results->fetch_assoc()) {
    $collections[] = $result;
};

echo json_encode($collections);
