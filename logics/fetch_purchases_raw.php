<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

if (!isset($_GET['f_pin'])) {
    die();
}

$dbconn = paliolite();

$f_pin = $_GET["f_pin"];

$sql = "SELECT p.*, s.NAME AS MERCHANT_NAME, pr.TITLE as PRODUCT_NAME
FROM PURCHASE p
left join SHOP s on p.MERCHANT_ID = s.CODE
LEFT JOIN POST pr on p.POST_ID = pr.POST_ID
WHERE p.FPIN = '$f_pin'";

// FETCH COLLECTIONS
$query = $dbconn->prepare($sql);
$query->execute();
$results = $query->get_result();
$query->close();

// echo $sql;

$collections = array();
while ($result = $results->fetch_assoc()) {
    $collections[] = $result;
};

return $collections;

?>