<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');

$dbconn = paliolite();

if (!isset($hash) && isset($_GET['url'])) {
    $hash = $_GET['url'];

    $chain = explode(":", base64_decode($hash));
    $f_pin = $chain[1];
    $id = $chain[0];

    // echo "f_pin:" . $f_pin;
    // echo "<br>";
    // echo "id:" . $id;
    // echo "<br>";

    $rows = array();
    $sql = "SELECT pr.KEY, pr.VALUE
            FROM `UI_CONFIG_DETAIL` pr
            LEFT JOIN `UI_CONFIG` ui ON ui.ID = pr.UI_CONFIG
            WHERE pr.UI_CONFIG = $id";
    // echo $sql;
    // echo "<br>";
    $query = $dbconn->prepare($sql);
    $query->execute();
    $groups = $query->get_result();
    $query->close();

    while ($group = $groups->fetch_assoc()) {
        $rows[] = $group;
    };

    // echo "<pre>";
    echo json_encode($rows);
    // echo "</pre>";

} else {

    // echo "URL not supplied";
    $myObj = new stdClass();
    $myObj->KEY = null;
    $myObj->ALPHA = null;

    $myJSON = json_encode($myObj);

    echo $myJSON;
}
