<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if (isset($_GET['query'])) {
    
    $dbconn = paliolite();

    $que = "%" .$_GET['query'] . "%";

    // $str = "SELECT * FROM SHOP s WHERE s.NAME LIKE %" . $que . "%";

    $query = $dbconn->prepare("SELECT * FROM SHOP s WHERE s.NAME LIKE ? AND s.IS_QIOSK = 1");
    $query->bind_param('s', $que);
    $query->execute();
    $shops  = $query->get_result();
    $query->close();

    $rows = array();
    while ($shop = $shops->fetch_assoc()) {
        $rows[] = $shop;
    };

    return $rows;
}
